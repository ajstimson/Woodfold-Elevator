window.formData = {};

var $width_min = 20.75,
    $width_max = 64.75,
    $pocket_max = 12,
    $height_min = 76,
    $height_max = 97,
    $quantity_max = 75;


(function ($) {
// ELEVATOR FORM CLICK and/or CHANGE EVENT FUNCTIONS
    $(window).load(function () {

        // track previous selections
        var previous;

        $("input").on('focus', function () {
            // Store the current value on focus and on change
            previous = this;
        }).change(function() {
            // Make sure the previous value is updated
            previous = this;
        });

        // Convert form header to h1
        var header = document.querySelector('#gform_wrapper_1 h3.gform_title');
        header.outerHTML = '<h1>' + header.innerHTML + '</h1>';


        
        var formJSON = reviewDataObj();

        $(document).on("change focus keyup", '#gform_1 input, #gform_1 select', function () {
            // update form json data
            formJSON = reviewDataObj();
        });

        // when clicking on addressDetailsInput
        $(document).on("click focus keyup", '#address-details input[type="text"]', function () {
            $(this).addClass('active-input');
            $(this).parent().parent().removeClass('verified').addClass('verifying');
            // Add soon
            // validCheck(this);
        });

        // Add class to inputs if has value
        $('#address-details input[type="text"]').each(function(){
            $(this).on('change', function(){
                if($(this).val().trim() != "") {
                    
                    $(this).addClass('has-val');
                }

                else {
                    $(this).removeClass('has-val');
                }
            });  
        });

        $(document).on("focusout", '#address-details input[type="text"]', function () {
            if ( !$(this).hasClass( 'has-val' ) ){
                $(this).removeClass( 'active-input' );
            }
        });

        // if ship to different address is clicked
        $('#choice_1_74_1').click(function () {
            
            // first clear zone
            var zone = $('#input_1_91');
            clearZone(zone);
            
            // if this is checked
            if($(this).prop('checked')){
                // show ship to information
                $('#customer-toggle').show();
                
                selectZone( getShipToData() );

            } else {
                
                $('#customer-toggle').hide();

                selectZone( getDefaultData() );
            }
        });

        // If country is changed...
        $('#input_1_59').change(function () {
            setSubdivision();
        });

        $(document).on("change", '.address-item.select select, #field_1_56 input', function () {

            selectZone( getShipToData() );

        });

        // when number of vision panels === All
        $(document).on('change', '#input_1_35', function () {
            var a = $(this);

            if (a.val() === 'All'){
                
                var previous = $('#panel-slide .gfield_radio > li:not(.no-gate-render) input:checked').val();
                // store selection for later
                a.data('position', 'all');
                a.data('previous', previous);
                deselectAllOtherPanels();
            
            } else 
            if ( a.data('position') === 'all' ){
                 
                 a.data('position', a.val());

                 restorePanelSelection(a.data('previous'));
            }
        });

        // When changing Cab Height
        // $('#input_1_14').change(function () {
        //     setDoorHeight();
        // });

        // When selecting Height Option
        // $('#input_1_16').change(function () {
        //     setDoorHeight();
        // });

        // When clicking slide trigger fields (slide action)
        var delay = 500;
        // stack slide trigger
        $('#field_1_40').click(function () {
            var id = 'stack-slide';
            clickSlideTrigger(id, delay);
        });
        // panel slide trigger
        $('#field_1_22').click(function () {
            var id = 'panel-slide';
            clickSlideTrigger(id, delay);
        });
        // frame slide trigger
        $('#field_1_23').click(function () {
            var id = 'frame-slide';
            clickSlideTrigger(id, delay);
        });

        var a;
        // When clicking on Stack Direction
        $('#input_1_20 input').click(function () {
            a = $(this);
            flipConfig(a);
        });

        $('#field_1_46 input').click(function () {
            a = $(this);
            toggleJambState(a);
        });

        $('#field_1_48 input').click(function () {
            a = $(this);
            setJambHold(a);
        });

        $(document).on("change keyup", ".check-limits", function () {
            a = $(this);
            setTimeout(function() {
                
                limitCheck(a);

                if (a.val().length > 0 ){
                    inputMinMax(a);
                }
            }, 600);
        });

        // cases for calculating quotes
        // if any trigger-quote element changes
        $(document).on("change", '.trigger-quote', function () {
            calculateQuote();
        });

        // if vision panels are cleared
        $(document).on("click", '#clear-button button', function () {
            calculateQuote();
        });

        // if gate width, height, pocket depth, or quantity are keyed
        $(document).on("keyup", '#input_1_13, #dup-width input, #input_1_14, #input_1_26, #input_1_24', function () {
            calculateQuote();
        });
        
        // end cases

        // When changing stack option
        $(document).on("click change touchend keyup", "#input_1_13, #stack-slide input", function () {
            gateCalcInit();
        });

        // when vision panel position is selected render gate config image
        $(document).on('change', '#input_1_35, #input_1_37', function () {
            visionPositionInit();
        });

        // when clicking a panel or frame option render gate config image
        $('.panels .gfield_radio li:not(.no-gate-render) input, .frame-item .gfield_radio input').click(function () {
            renderGate();
        });

        // only allow one panel selection at a time (for non-vision panels)
        $(document).on('click', '#panel-slide .gfield_radio li:not(.no-gate-render) label', function () {
            var input = $(this).closest(input);
            // var other = $("#panel-slide .gfield_radio input").not(input);
            var alter = $('#field_1_27 input');
            $("#panel-slide .gfield_radio input").not(input).not(alter).prop("checked", false);
        });


        $(document).on('click', '#back-active', function () {
            slideRight();
        });

        
        $(document).on('click', '#clear-button', function (e) {
            e.preventDefault();
            clearOptions();
            renderGate();
        });

        $(document).on('click change keyup', '#input_1_13, #dup-width input, #input_1_26', function () {
            var a = $(this);
            stackWidthMatch(a);
            widthPocketSum(a);
        });

         $(document).on('click', '#field_1_89 input', function () {
            var a = $(this);
            toggleColorType(a);
         });
    });

    

    function calculateQuote(){
        var zone                    = parseInt($('#input_1_91 input:checked').val(), 10),
            height                  = parseFloat($('#input_1_14').val()),
            panelCount              = parseInt($('#dim-3 span').text(), 10),
            finish                  = $('#field_1_89 input:checked').val(),
            rush                    = $('#choice_1_39_1'),
            quantity                = $('#input_1_24').val(),
            acrylic                 = isAcrylic(),
            perforated              = isPerforated(),
            birchOakArr             = ['Birch', 'Oak'],
            birchOak                = isChecked('#field_1_28 input', birchOakArr),
            alderMapleMahoganyArr   = ['Alder', 'Maple', 'Mahogany'],   
            alderMapleMahogany      = isChecked('#field_1_28 input', alderMapleMahoganyArr),
            cherryWalnutArr         = ['Cherry', 'Walnut'],
            cherryWalnut            = isChecked('#field_1_28 input', cherryWalnutArr),
            vinylWoodgrain          = isChecked('#field_1_92 input'),
            vinylSolid              = isChecked('#field_1_29 input'),
            goldUpcharge            = isGold(),
            doubleGateCharge        = isChecked('#field_1_46 input#choice_1_46_0'),
            visionPanelCount        = 0,
            baseQuote               = 0;

        // All subsequent calculations assume a panel height less than 78.5"
        // So if panel height is > 78.5", all panels are +$2
        if ( height > 80 ){
            baseQuote = panelCount * 2;
        }

        if ( acrylic === true || perforated === true ){

            visionPanelCount = $('#input_1_35').val();

            if ( visionPanelCount === 'All' ){

                baseQuote = visionPanelCalc(baseQuote, zone, acrylic, perforated, panelCount);

            } else if ( visionPanelCount !== false ){

                // update panel count for future calculations
                panelCount = panelCount - visionPanelCount;
                // only tally vision panel count which will be 2,3, or 4
                baseQuote = visionPanelCalc(baseQuote, zone, acrylic, perforated, visionPanelCount);
            }
        }

        if ( birchOak === true ){
            baseQuote = panelCalc(baseQuote, zone, panelCount, 66, 71, 74);
        }

        if ( alderMapleMahogany === true ){
            baseQuote = panelCalc(baseQuote, zone, panelCount, 71, 75, 78);
        }

        if ( cherryWalnut === true ){
            baseQuote = panelCalc(baseQuote, zone, panelCount, 75, 79, 82);
        }
        

        if ( vinylWoodgrain === true || vinylSolid === true ){
            baseQuote = panelCalc(baseQuote, zone, panelCount, 58, 62, 65);
        }

        //Check finish
        if ( finish === 'Special Finish' ) {
            baseQuote = baseQuote + 50;
        }

        //Check if gold is used in frame or panels
        if ( goldUpcharge === true ){
            baseQuote = baseQuote + (baseQuote * .2);
        }

        //Check if double gate selected
        if ( doubleGateCharge === true ){
            baseQuote = baseQuote + 20;
        } 
        
        //Check rush status
        // To do: add check for special finish (rush is not available in such cases)
        

        //Finally get quantity
        if ( quantity > 0 ) {
            baseQuote = baseQuote * quantity;
        } else {
            baseQuote = 0;
        }

        if ( rush.is(':checked') ) {
            baseQuote = baseQuote + 60;
        }

        // if quote process is not complete, reset to 0
        if (isNaN(parseFloat(baseQuote))){
            baseQuote = 0;
        }

        updateQuoteFields(baseQuote);

    }

    function isAcrylic(){
        var bronze = $('#choice_1_27_0'),
            clear = $('#choice_1_27_1');

        if ( bronze.is(':checked') || clear.is(':checked')){
            
            return true;

        } else {

            return false;

        }
    }

    function isPerforated(type){
        var bronze = $('#choice_1_27_2'),
            silver = $('#choice_1_27_3');

        if ( bronze.is(':checked') || silver.is(':checked')){
            
            return true;

        } else {

            return false;

        }
    }

    function isChecked(a, arr){
        var inputs = $(a),
            checkd = false;

        if ( arr !== undefined ){

            for (var i = 0; i < arr.length; i++) {
                $(inputs).each(function(){

                    if (    $(this).is(':checked') && 
                            $(this).val() === arr[i] 
                    ){

                        checkd = true;
                    }

                });
            }

        } else {
            $(inputs).each(function(){

                    if ($(this).is(':checked')){

                        checkd = true;
                    }

            });
        }

        return checkd;
    }

    function isGold(){
        var gold = $('input[value*="gold" i]');
        var isGold = false;
        $(gold).each(function(){
            var status = isChecked(this);
            if (status === true){
                isGold = status;
                return false;
            }
        });

        return isGold;
    }

    function visionPanelCalc(baseQuote, zone, acrylic, perforated, count){
        
        var quote;

        if(zone === 1) {

            if ( acrylic === true ) {
                quote = 66 * count; 
            }
            
            if ( perforated === true ) {
                quote = 72 * count;
            }
        }
        if(zone === 2) {

            if ( acrylic === true ) {
                quote = 70 * count; 
            }
            
            if ( perforated === true ) {
                quote = 75 * count;
            }
        }
        if(zone === 3) {

            if ( acrylic === true ) {
                quote = 63 * count; 
            }
            
            if ( perforated === true ) {
                quote = 67 * count;
            }
        }

        quote = quote + baseQuote;

        return quote;
    }

    function panelCalc(baseQuote, zone, count, zone3Cost, zone1Cost, zone2Cost){

        // // console.log('panelCalc')
        // // console.log('baseQuote, zone, count, zone3Cost, zone1Cost, zone2Cost');
        // console.log(baseQuote, zone, count, zone3Cost, zone1Cost, zone2Cost);

        var quote;

        if(zone === 1) {
            quote = zone1Cost * count;
        }
        if(zone === 2) {
            quote = zone2Cost * count;
        }
        if(zone === 3) {
            quote = zone3Cost * count;
        }

        quote = quote + baseQuote;

        return quote;

    }

    function updateQuoteFields(baseQuote){
        var quoteFields = $('.quote-feature p + p'),
            quote       = baseQuote.toFixed(2),
            quoteAmount = '$' + quote;

        $(quoteFields).each(function(){
            $(this).html(quoteAmount);
        });
    }

    

    function reviewDataObj(){
        var review = $('#gform_1 li.review'),
            obj = {};

        $(review).each(function(index){
                var label       = formatObjNames($(this).children('label').text()),
                    input       = $(this).find("input, select"),
                    type        = getGinputContainer(input[0].parentNode),
                    value;                

                if (type === 'text' || type === 'number' || type === 'email'){
                    value = $(input).val();
                } 

                else
                
                if (type === 'select'){
                    value = $(input).find('option:selected').val();
                } 
                
                else
                
                if (type === 'checkbox'){
                    value = $(input).prop('checked');
                } 
                
                else 
                
                if (type === 'radio'){

                    value = radioReviewData(this);

                } 

                else {
                    console.error($(input).attr('name') + ' has an invalid type!');
                }

                obj[label]= {'type': type, 'value': value};
        });
        
        obj = addStaticData(obj);
        // console.log(obj);

        setReviewHTML(obj);

        // empty global formData object
        formData.cache = {};
        // update global formData object
        formData.cache = obj;

        
    }

    function getGinputContainer(el){

        var classList = el.classList;

        for (var i=0, l = classList.length; i<l; ++i) {
            if(/ginput_container_.*/.test(classList[i])) {
                
                var index = classList[i].lastIndexOf("_");
                var result = classList[i].substr(index+1);

                return result;
            }
            if(/gchoice_.*/.test(classList[i])){
                return 'radio';
            }
        }
    }

    function radioReviewData(el) {
        var obj = {},
            inputs = $(el).find('.gfield_radio li');

            for(var i = 0; i < inputs.length; i++) {
                
                var itemName = inputs[i].children[1].textContent,
                    input = inputs[i].children[0],
                    status = $(input).is(':checked');

                obj[i]= { "name": itemName, "status": status };
            }

        return obj;
    }

    $.fn.ignore = function(sel){
        return this.clone().find(sel||">*").remove().end();
    };

    function formatObjNames(txt){
        // remove symbols and excess whitespace
        txt = txt.replace(/[^a-zA-Z0-9 ]/g, "").trim();
        // replace whitespace with underscore
        txt = txt.replace(/ /g,"_");
        // lowercase
        txt = txt.toLowerCase();

        return txt;
    }

    function addStaticData(obj){
        var calculations = $('#gform_1 div#field_1_49'),
            
            fullWidth = $(calculations).find('#dim-1'),
            fullTitle = formatObjNames($(fullWidth).ignore("span").text()),
            
            numGates = $(calculations).find('#dim-3'),
            numTitle = formatObjNames($(numGates).ignore("span").text()),

            stackCollapse = $('#stackCollapsedSize').text(),
            stackTitle = formatObjNames($('.stack-collapse-text-left').text()),
            wallToLead = $('#wallToLeadPostClearance').text(),
            wallToLeadTitle = formatObjNames($('.stack-collapse-text-right').text()),
            
            // doorHeight = $('#cab-height span').text(),
            doorHeight = $('#input_1_14').val(),
            // doorHeightTitle = formatObjNames($('#cab-height').ignore("span").text()),
            doorHeightTitle = $('#field_1_14 label').text(),
            
            quote = $('#first-slide .quote-feature p:last-child').text();
            // console.log(stackTitle);

        obj[fullTitle] = {
            'value': $(fullWidth).find("span").text()
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

        obj.quote = {
            'value': quote.replace('$', '')
        };

        return obj;

    }

    function setReviewHTML(obj){

        var target = $('#review-content'),
            address = reviewAddressHTML(obj),
            config = reviewConfigHTML(obj);
       
       $(target).empty();
       $(target).append(address).append(config);

    }

    function reviewAddressHTML(obj) {
        var html;
            html = '<ul class="your-address">'
                + '<li><strong>Your Info</strong></li>'
                + '<li>' + obj.company.value + '</li>'
                + '<li>' + obj.first_name.value + ' ' + obj.last_name.value + '</li>'
                + '<li>' + obj.phone.value + ' ' + obj.ext.value + '</li>'
                + '<li>' + obj.email.value + '</li>'
                + '<li>' + obj.street_address.value + '</li>'
                + '<li>' + obj.street_address_2.value + '</li>'
                + '<li>' + obj.city.value + ', ' + obj.state.value + ' ' + obj.country.value + '</li>'
                + '</ul>';

            if (
                obj.customer_company.value.length > 0 || 
                obj.customer_first_name.value.length > 0 ||
                obj.customer_last_name.value.length
                ){

                    html += '<ul class="customer-address">'
                         + '<li><strong>Customer Address</strong></li>'
                         + '<li>' + obj.customer_company.value + '</li>'
                         + '<li>' + obj.customer_first_name.value + ' ' + obj.customer_last_name.value + '</li>'
                         + '<li>' + obj.customer_po_number.value + '</li>'
                         + '<li>' + obj.customer_phone.value + ' ' + obj.extension.value + '</li>'
                         + '<li>' + obj.ship_to_address.value + '</li>'
                         + '<li>' + obj.apartment_suite_unit_etc.value + '</li>'
                         + '<li>' + obj.ship_to_city.value + ', ' + obj.state__province__region.value 
                         + ' ' + obj.ship_to_state.value + ' ' + obj.ship_to_region.value + ' ' + obj.ship_to_province.value 
                         + ' ' + obj.ship_to_country.value + '</li>'
                         + '<li>' + obj.zip__postal_code.value + '</li>'
                         + '</ul>';
                }
            html += '<ul class="order-details">'
                 + '<li><strong>Order Details</strong></li>'
                 + '<li>P.O. Number: ' + obj.po_number.value + '</li>' 
                 + '<li>Sidemark: ' + obj.sidemark.value + '</li>'
                 + '<li>Pricing Zone: ' + obj.shipping.value + '</li>'
                 + '</ul>';

            return html;

    }

    function reviewConfigHTML(obj) {
        var html = '<ul class="config-details">';
            html += '<li><strong>Gate Configuration Details</strong></li>';
                 
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
                        obj.wall_to_leadpost_clearance.value
                    );

            html += configDataList(
                        'Height',
                        obj.door_height.type,
                        obj.door_height.value
                    );

            html += configDataList(
                        'Height Option',
                        obj.height_options.type,
                        obj.height_options.value
                    );

            html += configDataList(
                        'Rivet to Rivet Height',
                        'text',
                        obj.door_height.value
                    );

            html += configRadioItem(
                        'Acrylic/Vision Panel Choice',
                        obj.acrylic__perforated_vision_panels.value
                    );

            html += configDataList(
                        'Quantity of Acrylic/Vision Panels',
                        obj.number_of_panels.type,
                        obj.number_of_panels.value
                    );

            html += configDataList(
                        'Acrylic/Vision Panel Location',
                        obj.position.type,
                        obj.position.value
                    );

            html += panelSelection(
                        'Panel Selection',
                        obj.natural_hardwood_veneers.value,
                        obj.vinyl_laminate_woodgrains.value,
                        obj.vinyl_laminate_solid_colors__textures.value
                    );

            html += configRadioItem(
                        'Hardwood Finish',
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
                        obj.quote.value
                    );

            html += '</ul>';

            html = html.replace("undefined", '');

            return html;
    }

    function configDataList(str, type, value){

        var html = '<li>';

        if (type === 'text' || type === 'number' || type === 'email'){
            if(value === '' || value === '0'){
                return ' ';
            } else {
                html += str + ': ' + value;
            }
        }

        if (type === 'select'){
            if(value === 'AA' || value === 'false' || value === ''){
                return ' ';
            } else {
                html += str + ': ' + value;
            }
        }

        html += '</li>'

        return html;
    }

    function configRadioItem(str, obj){
        
        var html = '';

        for (var i in obj) {
            if (obj[i].status === true){
                
                html += '<li>' + str + ': ' + obj[i].name + '</li>';

                return html;

            }
        }

        // return nothing if all options are false
        return html;
    }

    function panelSelection(str, obj_1, obj_2, obj_3){
        var html;

        html += configRadioItem(str, obj_1);
        html += configRadioItem(str, obj_2);
        html += configRadioItem(str, obj_3);

        return html;
    }

})(jQuery);



// document.addEventListener('click', function(e) {
//     e = e || window.event;
//     var target = e.target || e.srcElement,
//         text = target.textContent || target.innerText;  
//     console.log(target); 
// }, false);


// Duplicates width field for display in stack data
//stackWidthInput();

// Builds stack image display
//changeWidthAttr();

function stackWidthInput() {
    var target = $('#field_1_41');
    var html = '<label class="gfield_label">Gate Width</label>';
    html += '<div class="ginput_container ginput_container_number">';
    html += '<input type="number" value="" class="medium" placeholder="(Decimal Inches)">';
    html += '</div></li>';

    $(target).html(html);
    $(target).attr("id", "dup-width");

    var copyFrom = $('#input_1_13');
    stackWidthMatch(copyFrom);
    widthPocketSum(copyFrom);
}

function stackWidthMatch(a) {
    var dee = $('#input_1_13'),
        dum = $('#dup-width input'),
        id = a[0].id,
        val = a[0].value;

    if (val > $width_min) {
        if (id === 'input_1_13' && val > $width_min) {

            $(dum).val(val);
        } else {
            $(dee).val(val);
        }
    }
}

function widthPocketSum(a) {
    var width = a[0].value,
        pocket = $('#input_1_26').val();

    if (!$.isNumeric(pocket)) {
        pocket = 0;
    }

    var sum = Number(width) + Number(pocket),
        target = $('#input_1_45');

    $(target).val(sum);

    combinedLimit(sum);
}

// function combinedLimit(sum) {
//     var max = 57.5;
//     if (sum > max) {
//         var message = 'Combined Width and Pocket Depth maximum reached! Please enter combined amount less than 57.5"';
//         errorHandler(message, 2);
//     }
// }


function stackContainerWidth() {
    var img = document.getElementById('config-img-0'),
        target = document.querySelector('.stack-collapse-container'),
        checkWidth = setInterval(function () {
            if (img.width > 0) {
                target.style.width = img.offsetWidth + 'px';
                gateWrapperWidth(img.offsetWidth);
                clearInterval(checkWidth);
            }
        }, 100);
}


function changeWidthAttr() {

    $('#input_1_13, #input_1_14, #input_1_26').prop('type', 'number');
    $('#input_1_13, #dup-width input, #input_1_14, #input_1_26').attr('step', 0.125);

}

// rewrite for non-es6 browsers
function priorityLoop(arr) {
    let newArr = [];
    for (let i = 0; i < arr.length; i++) {
        newArr.push(Number(arr[i].priority));
    }

    return newArr;
}

function messageLoop(arr, p) {
    for (let i = 0; i < arr.length; i++) {
        if (arr[i].priority === p) {
            // console.log("TCL: errorReporting -> arr[i].message", arr[i].message);
            return arr[i].message;
        }
    }
}

var errorCount = 0;
var errorArr = [];

function errorHandler(m, p) {
    errorCount++;
    errorCapture(m, p, errorCount);
}

function errorCapture(m, p, e) {
    var i = 0,

        value = {};
    value.count = e;
    value.message = m;
    value.priority = p;

    errorArr.push(value);

    var waitForErrors = setInterval(function () {

        if (i > 3) {
            // stop repeating this process
            clearInterval(waitForErrors);
            // reset error count variable
            errorCount = 0;
            // pass array to error reporting
            errorReporting(errorArr);
            debounce(errorReporting(errorArr), 100);
        }
        i++;
    }, 150);
}

function errorReporting(arr) {
    var pArr = [];
    
    pArr = priorityLoop(arr);

    // var max = Math.max(...pArr);
    var max = 3;

    var message = messageLoop(arr, max);

    // console.log("TCL: errorReporting -> message", message);

    emptyError();

}

function emptyError() {
    //empty your array
    errorArr.length = 0;
}

// Returns a function, that, as long as it continues to be invoked, will not
// be triggered. The function will be called after it stops being called for
// N milliseconds. If `immediate` is passed, trigger the function on the
// leading edge, instead of the trailing.
function debounce(func, wait, immediate) {
    var timeout;
    return function () {
        var context = this,
            args = arguments;
        var later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}