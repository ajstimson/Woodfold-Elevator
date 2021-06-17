window.formData = {};

(function($) {
    $(window).load(function() {

        /*

             ===========  *  FUNCTIONS  *  ===========

         */


        initReview();

        /*

            ===========  *  ACTIONS  *  ===========

        */

        $(document).on("change focus keyup", '#gform_1 input, #gform_1 select, #input_1_100, #footer-quantity', function() {

            initReview();

        });

        $(document).on("click", '#request-quote button:not(.disabled)', function() {
            //TODO add removeExtraStuff when submitting order
            removeExtraStuff();
            addGuestData();
            ajaxCall();
        });


    });

    function initReview() {
        //Set timeout to adjust for slow calculations
        setTimeout(reviewDataObj, 400);
    }

    function reviewDataObj() {
        var review = $('#gform_1 li.review:not(.guest-only)'),
            obj = {};


        $(review).each(function(index) {
            var label = formatObjNames($(this).children('label').text());
            var input = $(this).find("input, textarea,select");
            var type = input[0].type,
                value;

            if (type === 'text' || type === 'number' || type === 'email' || type === 'textarea') {
                value = $(input).val().replace(/"/g, '');
            } else

            if (type === 'select-one') {
                value = $(input).find('option:selected').val();
            } else

            if (type === 'checkbox') {
                value = $(input).prop('checked');
            } else

            if (type === 'radio') {

                value = radioReviewData(this);

            } else {
                console.error($(input).attr('name') + ' has an invalid type!');
            }

            obj[label] = { 'type': type, 'value': value };

        });

        if (window.guest === false) {

            cullCustomerData(obj);

        }

        obj.custom.value[0].name = 'Custom';

        obj = addStaticData(obj);

        setReviewHTML(obj);

        // empty global formData object
        formData.cache = {};
        // update global formData object
        formData.cache = obj;

    }

    function radioReviewData(el) {
        var obj = {},
            inputs = $(el).find('.gfield_radio li');

        for (var i = 0; i < inputs.length; i++) {

            var itemName = inputs[i].children[1].textContent,
                input = inputs[i].children[0],
                status = $(input).is(':checked');

            obj[i] = { "name": itemName, "status": status };
        }

        return obj;
    }

    $.fn.ignore = function(sel) {
        return this.clone().find(sel || ">*").remove().end();
    };

    function formatObjNames(txt) {
        // TODO: THESE CAN BE COMBINED TO A SINGLE LINE
        // remove symbols and excess whitespace
        txt = txt.replace(/[^a-zA-Z0-9 ]/g, "").trim();
        // replace whitespace with underscore
        txt = txt.replace(/ /g, "_");
        // replace double quotes
        txt = txt.replace('"', '');
        // lowercase
        txt = txt.toLowerCase();
        // console.log(txt);
        return txt;
    }

    function cullCustomerData(obj) {
        if (obj.ship_to_a_different_address.value === false) {
            obj.ship_to_state.value = '';
            obj.ship_to_country.value = '';
        }
    }

    function addStaticData(obj) {

        var calculations = $('#gform_1 div#field_1_49'),

            fullWidth = $(calculations).find('#dim-1'),
            fullTitle = formatObjNames($(fullWidth).ignore("span").text()),

            numGates = $(calculations).find('#dim-3'),
            numTitle = formatObjNames($(numGates).ignore("span").text()),

            stackCollapse = $('#stackCollapsedSize').text().replace('"', ''),
            stackTitle = formatObjNames($('.stack-collapse-text-left').text()),

            wallToLead = $('#wallToLeadPostClearance').text().replace('"', ''),
            wallToLeadTitle = formatObjNames($('.stack-collapse-text-right').text()),


            // doorHeight = $('#cab-height span').text(),
            doorHeight = $('#input_1_14').val(),
            // doorHeightTitle = formatObjNames($('#cab-height').ignore("span").text()),
            doorHeightTitle = $('#field_1_14 label').text(),

            quote = FormData.quote,

            note = $('#input_1_100').val(),

            quantity = $('#footer-quantity').val();


        obj[fullTitle] = {
            'value': $(fullTitle).find("span").text()
        };

        obj[numTitle] = {
            'value': formatObjNames($(numGates).find("span").text())
        };

        obj[stackTitle] = {
            'value': stackCollapse
        };

        obj[wallToLeadTitle] = {
            'value': wallToLead
        };

        obj[doorHeightTitle] = {
            'value': doorHeight
        };

        obj['order_notes'] = {
            'value': note
        };

        obj['quote'] = {
            'value': quote
        };

        obj.quantity = {
            'value': quantity,
            'type': 'number'
        }

        return obj;

    }

    function setReviewHTML(obj) {

        var target = $('#review-content'),
            config = reviewConfigHTML(obj);

        $(target).empty();
        if (window.guest === false) {

            var address = reviewAddressHTML(obj);

            $(target).append(address).append(config);

        } else {

            $(target).append(config);

        }

    }

    function reviewAddressHTML(obj) {
        var html;

        html = '<ul class="your-address">' +
            '<li><strong>Your Info</strong></li>' +
            '<li>' + obj.company.value + '</li>' +
            '<li>' + obj.first_name.value + ' ' + obj.last_name.value + '</li>' +
            '<li>' + obj.phone.value + ' ' + obj.ext.value + '</li>' +
            '<li>' + obj.email.value + '</li>' +
            '<li>' + obj.street_address.value + '</li>' +
            '<li>' + obj.street_address_2.value + '</li>' +
            '<li>' + obj.city.value + ', ' + obj.state.value + ' ' + obj.country.value + '</li>';

        if (
            obj.customer_company.value.length > 0 ||
            obj.customer_first_name.value.length > 0 ||
            obj.customer_last_name.value.length > 0
        ) {

            html += '<ul class="customer-address">' +
                '<li><strong>Customer Address</strong></li>' +
                '<li>' + obj.customer_company.value + '</li>' +
                '<li>' + obj.customer_first_name.value + ' ' + obj.customer_last_name.value + '</li>' +
                '<li>' + obj.customer_po_number.value + '</li>' +
                '<li>' + obj.customer_phone.value + ' ' + obj.extension.value + '</li>' +
                '<li>' + obj.ship_to_address.value + '</li>' +
                '<li>' + obj.apartment_suite_unit_etc.value + '</li>' +
                '<li>' + obj.ship_to_city.value + ', ' + obj.state__province__region.value +
                ' ' + obj.ship_to_state.value + ' ' + obj.ship_to_region.value + ' ' + obj.ship_to_province.value +
                ' ' + obj.ship_to_country.value + '</li>' +
                '<li>' + obj.zip__postal_code.value + '</li>' +
                '</ul>';
        }
        html += '</ul>' +
            '<ul class="order-details">' +
            '<li><strong>Order Details</strong></li>' +
            '<li>P.O. Number: ' + obj.po_number.value + '</li>' +
            '<li>Sidemark: ' + obj.sidemark.value + '</li>' +
            '<li>Pricing Zone: ' + obj.shipping.value + '</li>' +
            '</ul>';

        return html;

    }

    function reviewConfigHTML(obj) {

        var html = '<ul class="config-details">';
        html += '<li><strong>Gate Configuration Details</strong></li>';

        var quote = '$' + obj.quote.value.toFixed(2);

        if ($('#choice_1_134_0').is(':checked')) {
            quote = 'Woodfold will contact you with a quote after completing this order';
        }

        if (obj.vision_panel_position.value !== 'false') {
            obj.vision_panel_position.value = $('#input_1_37 option:checked').text();
        }
        //console.log(obj.vision_panel_position.value);

        html += configDataList(
            'Cab Width',
            obj.gate_width.type,
            obj.gate_width.value
        );

        html += configDataList(
            'Number of Gate Panels',
            'text',
            obj.number_of_gate_panels.value
        );

        html += configRadioItem(
            'Double Ended Gate',
            obj.double_ended_gate.value
        );

        html += configRadioItem(
            'Stack Direction',
            obj.stack_direction.value
        );

        html += configDataList(
            'Pocket Depth',
            obj.pocket_depth.type,
            obj.pocket_depth.value
        );

        html += configRadioItem(
            'Jamb Orientation',
            obj.change_jamb_orientation.value
        );

        html += configDataList(
            'Full Opening Width',
            'text',
            obj.full_opening_width.value
        );

        html += configDataList(
            'Stack Collapsed Size',
            'text',
            obj.stack_collapsed_size.value
        );

        html += configDataList(
            'Wall to Lead Post Clearance',
            'text',
            obj.wall_to_lead_post_clearance.value
        );

        html += configDataList(
            'Height',
            obj.cab_height.type,
            obj.cab_height.value
        );

        html += configDataList(
            'Height Option',
            obj.height_options.type,
            obj.height_options.value
        );

        html += configRadioItem(
            'Vision Panel Choice',
            obj.include_vision_panels.value
        );

        html += configDataList(
            'Number of Vision Panels',
            obj.number_of_vision_panels.type,
            obj.number_of_vision_panels.value
        );

        html += configDataList(
            'Vision Panel Location',
            obj.vision_panel_position.type,
            obj.vision_panel_position.value
        );

        html += configDataList(
            'Vision Panel Material',
            obj.vision_panel_material.type,
            obj.vision_panel_material.value
        );

        var panelArr = [];
        panelArr['_'] = obj.acrylic.value;
        panelArr['Alumifold'] = obj.alumifold_perforated.value;
        panelArr['Natural Hardwood Veneers'] = obj.natural_hardwood_veneers.value;
        panelArr['Vinyl Laminate Woodgrains'] = obj.vinyl_laminate_woodgrains.value;
        panelArr['Vinyl Laminate Solid'] = obj.vinyl_laminate_solid_colors__textures.value;
        panelArr['Alumifold Solid'] = obj.alumifold_solid.value;
        panelArr['Fire Core'] = obj.fire_core.value;
        panelArr['__'] = obj.custom.value;


        html += panelSelection(
            'Panel Selection',
            panelArr
        );

        html += configRadioItem(
            'Panel Finish',
            obj.finish.value
        );

        html += configDataList(
            'Special Finish Color Info',
            obj.enter_type_of_color.type,
            obj.enter_type_of_color.value
        );

        html += configRadioItem(
            'Lead Post and Panel Connector Choice',
            obj.lead_post__connector.value
        );

        html += configRadioItem(
            'Track Choice',
            obj.track.value
        );

        html += configRadioItem(
            'Hinge Hardware',
            obj.hinge_hardware.value
        );

        html += configRadioItem(
            'Side Channels',
            obj.side_channels.value
        );

        html += configRadioItem(
            'Closure Option',
            obj.closure_options.value
        );

        //only show the following items if user logged in
        if (window.guest === false) {

            html += configDataList(
                'Quantity',
                obj.quantity.type,
                obj.quantity.value
            );

            html += configDataList(
                'Rush Order',
                obj.rush_shipping.type,
                obj.rush_shipping.value
            );

            html += configDataList('Price',
                'text',
                quote
            );
        }

        html += '</ul>';

        html = html.replace("undefined", '');

        return html;
    }

    function configDataList(str, type, value) {
        var html = '<li>';

        if (type === 'text' || type === 'number' || type === 'email' || type === 'textarea') {
            if (value === '' || value === '0') {
                return ' ';
            } else {
                html += str + ': ' + value;
            }
        }

        if (type === 'select-one') {
            if (value === 'AA' || value === 'false' || value === '') {
                return ' ';
            } else {
                html += str + ': ' + value;
            }
        }

        html += '</li>'

        return html;
    }

    function configRadioItem(str, obj) {

        var html = '';

        for (var i in obj) {
            if (obj[i].status === true) {

                html += '<li>' + str + ': ' + obj[i].name + '</li>';

                return html;

            }
        }

        // return nothing if all options are false
        return html;
    }

    function panelSelection(str, array) {

        array = prependCategoryNames(array);

        var html;

        for (var i in array) {
            html += configRadioItem(str, array[i]).replace(/_ /g, '');
        }

        return html;

    }

    function prependCategoryNames(array) {
        for (var i in array) {
            var category = i;

            var data = array[i];
            for (var e in data) {
                category = category.replace('_', '');
                data[e].name = category + ' ' + data[e].name;
                data[e].name = data[e].name.trimStart();
            }
        }
        return array;
    }

    function removeExtraStuff() {
        delete formData.cache.shipping;
        delete formData.cache.quantity;
        delete formData.cache.quote;
    }

    function addGuestData() {
        var targets = $('.guest-only input'),
            guestData = {};

        $(targets).each(function() {
            var label = $(this).parent().parent().find('label').text().slice(0, -1);
            var val = $(this).val();

            guestData[label] = val
        });

        formData.cache['guest'] = guestData;
    }

    function ajaxCall() {
        var data = JSON.stringify(window.formData.cache);
        $.ajax({
            type: 'POST',
            url: local.ajax_url,
            data: {
                action: 'request_quote_email',
                data: window.formData.cache,
            },
            success: function(response) {
                if (response.success === true) {
                    alert('Your request has been recieved. You will be contacted shortly');
                } else {
                    alert('An error occurred. Please try submitting your request again.');
                }
            }
        });
    }



})(jQuery);