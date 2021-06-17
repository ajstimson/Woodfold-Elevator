// ************************************************
// 
// Shopping Cart API
// Author: Burlaka Dmytro
// Customized by: Andrew Stimson
// Example: https://codepen.io/Dimasion/pen/oBoqBM
// Dependencies: Bootstrap 4.0, Tether.js
// 
// ************************************************

(function($) {

    $(window).load(function() {
        
        cartInit();
        
        function cartInit() {

            cartLoading();
            modalPlaced();

            // TO DO: Check that all items in cart have the same cart hash value as the current meta item
            // IF NOT: Clear all items of cart without mismatched cart hash
            displayCart();
        }

        function cartLoading() {
            var space = $('#bc-social-icons'),
                width = $(space).width();

            // set fixed width
            $(space).width(width + 'px');

            // replace html with loader
            loadingAnimation(space);

            setTimeout(function() {
                cartPlace(space, width);

            }, 750);

        }

        function loadingAnimation(el){
            $(el).addClass('cart-loader').html('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
        }

        function cartPlace(horse, w) {

            var cart = cartHTML();
            $(horse).replaceWith(cart);

        }

        function cartHTML() {

            var count = 0;

            if (sessionStorage.count === 'undefined'){
                count = sessionStorage.count;
            }

            var html = '<button type="button" class="btn btn-primary"'; 
                html += 'data-toggle="modal" data-target="#cart">Cart'; 
                html += ' (<span class="total-count">' + count + '</span>)</button>';

            return html;
        }

        function modalPlaced(){
            var modal = modalHTML(),
                appnd = $('.content .breadcrumb');

            appnd.after(modal);
        }

        function modalHTML(){
            var html = '<div class="modal fade" id="cart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
                html += '<div class="modal-dialog modal-lg" role="document">';
                html += '<div class="modal-content"><div class="modal-header">';
                html += '<h5 class="modal-title">Your Elevator Gate Orders</h5>';
                html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                html += '<span aria-hidden="true">&times;</span>';
                html += '</button></div>';
                html += '<div class="modal-body"><table class="show-cart table" cellspacing="0">';
                html += '</table>';
                html += '<div>Total: $<span class="total-cart"></span></div>';
                html += '</div>';
                html += '<div class="modal-footer">';
                html += '<button type="button" class="btn btn-secondary clear-cart">Clear Cart</button>';
                html += '<button type="button" class="btn btn-secondary new-order" onclick="window.location.href = `/elevator-form/`;">Create New Order</button>';
                html += '<button type="button" class="btn btn-primary">Complete Order</button>';
                html += '</div></div></div></div>';

            return html;
        }

        function cartTableHeader(){
            var thead = ' <thead><tr>';
                thead += '<th class="product-remove">&nbsp;</th>';
                thead += '<th class="product-po-number">PO Number</th>';
                thead += '<th class="product-details">Details</th>';
                thead += '<th class="product-quantity">Quantity</th>';
                thead += '<th class="product-price">Quote</th>';
                thead += '<th class="product-options">Options</th>';
                thead += '</tr></thead>';

            return thead;
        }

        function displayCart() {
            var cartArray = shoppingCart.listCart(),
                output = cartTableHeader();

            output += '<tbody>'
            
            for (var i in cartArray) {
                
                output += '<tr>';
                output += '<td>' 
                output += '<button class="delete-item input-group-addon btn btn-primary"';
                output += ' data-name="' + cartArray[i].name + '">X</button>';
                output += '</td><td>';
                output += cartArray[i].name;
                output += '</td><td class="item-details">';
                output += itemDetails(cartArray[i].json);
                output += '</td><td>';
                output += cartArray[i].quantity;
                output += '</td><td>';
                output += '$<span>' + cartArray[i].price + '</span>';
                output += '</td><td>';
                output += cartItemOptions(cartArray[i])
                output += '</td><td class="order-item-json" style="display:none">';
                output += cartArray[i].json
                output += '</td>';
                output += '</tr>';
            }

            output += '</tbody>'

            $('.show-cart').html(output);
            $('.total-cart').html(shoppingCart.totalCart());
            
            // this is empty right now
            $('.total-count').html(sessionStorage.count);
        }

        function itemDetails(json) {
            
            json = JSON.parse(json);
            
            var html = '<ul>';
                html +='<li>';
                html += 'Gate Width: ' + json.gate_width.value;
                html += '</li><li>';
                html += 'Cab Height: ' + json.cab_height.value;
                html += '</li><li>';
                html += 'Panel Count: ' + json.number_of_gate_panels.value;
                html += '</li><li>';
                html += '<a href="#">See More...</a>';
                html += '</li>';
                html += '</ul>';

            return html;
        }

        function cartItemOptions(arr){
            
            var session = document.head.querySelector("[property~=cart-hash][content]").content;

            var html = '<ul class="cart-item-options">';
                html += '<li>';
                html += '<button class="plus-item input-group-addon"'; 
                html += ' data-name="' + arr.name + '">Copy</button>';
                html += '</li>';
                html += '<li>';
                html += '<button type="button" class="edit-item" ';
                html += 'data-session="' + session + '" ';
                html += 'data-item-id="' + arr.itemID + '">';
                html += 'Edit</button>';
                html += '</li>';
                html += '<li>';
                html += '<button class="save-item" ';
                html += 'data-session="' + session + '" ';
                html += 'data-item-id="' + arr.itemID + '">';
                html += 'Save</button>';
                html += '</li>';
                html += '</ul>';

            return html;
        }

        // *****************************************
        // Triggers / Events
        // ***************************************** 
        // Add item
        $('#gform_1').submit(function(event) {
            event.preventDefault();

            var $form = $(this),
                data = formData.cache,
                orderNumber = data.po_number.value,
                price = Number(data.quote.value),
                quantity = Number(data.quantity.value),
                itemID = document.head.querySelector("[property~=cart-item][content]").content,
                json = JSON.stringify(data),
                 url = $form.attr('action');

            shoppingCart.addItemToCart(orderNumber, price, quantity, itemID, json);

            displayCart();

            // trigger cart modal display
            $("#cart").modal()
        });
       

        // Clear items
        $('.clear-cart').click(function() {
            shoppingCart.clearCart();
            displayCart();
        });
        
        // Delete item button
        $('.show-cart').on("click", ".delete-item", function(event) {
            var name = $(this).data('name');
            
            shoppingCart.removeItemFromCart(name);
            displayCart();
        })

        // Edit Item Button
        $('.show-cart').on("click", ".edit-item", function(event) {
            var session = $(this).data('session');
            var itemID = $(this).data('item-id');
            
            shoppingCart.editItem(session, itemID);
        });

        // Create copy of order
        $('.show-cart').on("click", ".plus-item", function(event) {
            var name = $(this).data('name');
            shoppingCart.duplicateItemInCart(name);
            displayCart();
        });

        // Save to Dashboard
        $('.show-cart').on("click", ".save-item", function(event) {
            var session = $(this).data('session');
            var itemID = $(this).data('item-id');
            var element = $(this);

            loadingAnimation(element);
            
            setTimeout(function() {
                saveItem(element);
            }, 1000);
        });

        // move this to shoppingCart obj eventually
        function saveItem(el){
            $(el).removeClass('cart-loader');
            $(el).css('background-color', 'green').html('Saved!');
        }

        $('.show-cart').on("click", ".item-details a", function(event) {
            event.preventDefault();

            var accordion = $(this);
            
            $(accordion).html('Coming Soon...')

            setTimeout(function() {
                $(accordion).html('See More...');
            }, 1000);
        });

        // DELETE THIS
        // $('.show-cart').on("change", ".item-count", function(event) {
        //     var name = $(this).data('name');
        //     var count = Number($(this).val());
        //     shoppingCart.setCountForItem(name, count);
        //     displayCart();
        // });

    }); //end window load function

})(jQuery);

var shoppingCart = (function() {
            
    // =============================
    // Private methods and propeties
    // =============================
                
    cart = [];

    // Constructor
    function Item(name, price, quantity, itemID, json) {
        this.name = name;
        this.price = price;
        this.quantity = quantity;
        this.itemID = itemID;
        this.json = json;
    }

    // Save cart
    function saveCart() {
        sessionStorage.setItem('shoppingCart', JSON.stringify(cart));
        sessionStorage.setItem('count', cart.length);
    }

    // Load cart
    function loadCart() {
        cart = JSON.parse(sessionStorage.getItem('shoppingCart'));
    }

    function countDuplicates(name){
        var count = 1;
        
        for (var item in cart) {
            if (cart[item].name === name) {
                count = count++;
            }
        }

        return count;
    }

    function cloneObj(obj) {
        if (null == obj || "object" != typeof obj) return obj;
        var copy = obj.constructor();
        for (var attr in obj) {
            if (obj.hasOwnProperty(attr)) copy[attr] = obj[attr];
        }

        return copy;
    }


    if (sessionStorage.getItem("shoppingCart") != null) {
        loadCart();
    }

    // =============================
    // Public methods and propeties
    // =============================
    var obj = {};

    // Add to cart
    obj.addItemToCart = function(name, price, quantity, itemID, json) {

        var item = new Item(name, price, quantity, itemID, json);
        cart.push(item);
        saveCart();
    };

    obj.duplicateItemInCart = function(name) {
        
        name = $.trim(name);

        for (var item in cart) {
            if (cart[item].name === name) {
               
                var clone = cloneObj(cart[item]);

                cart.push(clone);

                saveCart();

                break;

            }
        }
    };

    // Remove item from cart
    obj.removeItemFromCart = function(name) {

        var message = 'Are you sure you want to delete this item from the cart?';
        
        if (!confirm(message)) { 
            return false;
        } else {
            name = $.trim(name);
            
            for (var item in cart) {
                if (cart[item].name === name) {
                    cart.splice(item, 1);
                    break;
                }
            }

            saveCart();
        }

    };

    // Clear cart
    obj.clearCart = function() {
        
        var message = 'Are you sure you want to clear all cart items?';
       
        if (!confirm(message)) { 
            return false;
        } else {
            cart = [];
            saveCart();
       }
        
    };

    // Total cart
    obj.totalCart = function() {

        var totalCart = 0;

        for (var item in cart) {
            
            console.log(cart[item].price);

            totalCart += cart[item].price;
        }

        return Number(totalCart);
    };

    // List cart
    obj.listCart = function() {

        var cartCopy = [];
        for (i in cart) {
            item = cart[i];
            itemCopy = {};
            for (p in item) {
                itemCopy[p] = item[p];

            }
            itemCopy.total = Number(item.price);
            cartCopy.push(itemCopy);
        }

        return cartCopy;

    };

    obj.editItem = function(session, id) {


        id = $.trim(id);
        
        for (var item in cart) {

            if (cart[item].itemID === id) {

                cart.splice(item, 1);
                
                saveCart();

                var url  = '/elevator-form?session=' + session + '&itemID=' + id;

                window.location.href = url;
            }
        }
    }

    return obj;

})();