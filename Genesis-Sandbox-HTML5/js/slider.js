/**
 * Custom jQuery
 */

(function($) {
	
	$(window).load(function() {

		var width = $(window).width(),
			slider = $('.flexslider'),
			form = $('#slider-form');

		// if flexslider class exists...
		if ($(slider).length){
			// generate flex slider
			$(slider).flexslider({
				animation: "slide",
				pausePlay: false,
				keyboard: true,
				touch: true,
			    before: function(){
			    	$('.slide-title').css("opacity", '0');
			    	$('#flex-slides .slides > li').not( ".flex-active-slide" ).find('.flex-caption').css("opacity", '1');
			    },
			    after: function(){
			    	$('.slide-title').css("opacity", '1');
			    	$('#flex-slides .slides > li').not( ".flex-active-slide" ).find('.flex-caption').css("opacity", '0');
			    }
			});

			sliderCaptionInit();
			formFeatureInit();
			// formClose();
			clearFormValues();
			validCheck();

			if (width < 1025){
				formMobile(form, width);
			} else {
				formHeight(form);
			}

		}

		$(document).on("click", '#drawer', function (e) {
			e.preventDefault();
			drawerOpen();
		});

		$(document).on("click", '#form-close, .mobile-form #slider-form', function () {
			drawerClose();
		});

		$(".mobile-form #slider-form #gform_2").click(function(e) {
        	e.stopPropagation();
   		});

	    $(document).on("click focus", '#slider-form form input[type="text"]', function () {
	    	$(this).addClass('active-input');
	    	$(this).parent().parent().addClass('verifying');
	    });

	    // Add class to inputs if has value
	    $('#slider-form form input[type="text"]').each(function(){
	        $(this).on('change', function(){
	            if($(this).val().trim() != "") {
	                $(this).addClass('has-val');
	                $(this).parent().parent().addClass('verified');
	                validCheck();
	            }
	            else {
	                $(this).removeClass('has-val');
	                $(this).parent().parent().removeClass('verifying');
	            }
	    	});  
	    });

	    $(document).on("focusout", '#slider-form form input[type="text"]', function () {
	    	if ( !$(this).hasClass( 'has-val' ) ){
	    		$(this).removeClass( 'active-input' );
	    	}
	    	if ( $(this).hasClass( 'has-val' ) && $(this).attr("id") === "input_2_3"){
	    		var valid = validateEmail($(this).val());
	    		console.log(valid);
	    		if(valid === false){
	    			$(this).addClass('not-valid');
	    			$(this).parent().parent().addClass('error');
	    		} else {
	    			$(this).removeClass('not-valid');
	    			$(this).parent().parent().removeClass('error');
	    			validCheck();
	    		}
	    	}
		});

      	// Allow letters, ',', '.',''', '-', and several accent precursors only
		setInputFilter(document.getElementById('input_2_5'), function(value) {
			return /^[a-zA-ZæàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-¸˛˝´¨ˆ˚˜ˇ]*$/g.test(value); 
		});

		// Allow digits, '.', '-','+','(', ')', 'x' only
      	setInputFilter(document.getElementById('input_2_4'), function(value) {
			return /^[0-9 -+().x]*$/.test(value); 
		});
	});

	function sliderCaptionInit() {
		var caps = $('.flex-caption'),
			wdth = $('.flexslider').innerWidth();

		$(caps).each(function(){
			var wrap = $(this).find('.flex-wrap');
			$(wrap).width(wdth + 'px');
		});
	}

	function formFeatureInit() {

		var form = $('#gform_2');
		var inputs = $(form).find('input[type="text"]');
		
		formPlaceholders(inputs);
	}

	function formPlaceholders(i){
		$(i).each(function(){
			var cont = $(this).parent().prev().text();
			var place = cont.replace(/[^\w\s]/gi, '');

			$(this).after('<span class="focus-input" data-placeholder="' + place + '"></span>');

		});
	}

	function formHeight(f){
		var h = $('#flex-slides').innerHeight(),
			h = Number(h) - 74,
			target = $(f).find('#gform_2');

		$(f).height(h + 'px');

	}

	function formMobile (f,w){
		var html = '<div id="drawer" style="transition: all 500ms ease 0s;">';
    		html += '<a href="#" class="drawer-tab">';
			html += '<div class="drawer-tab-text">Contact Us</div>';
			html += '</a></div>';

		$('#flex-slides').append(html);

		$('.site-inner').prepend(f).addClass('mobile-form');

		formClose(f);
    
	}

	function drawerOpen(){
		$('#slider-form').animate({right: '0'}).css('background-color', 'rgba(0, 0, 0, 0.6)');
		$(this).hide();
	}

	function drawerClose(){
		$('#slider-form').animate({right: '-100%'});
		$('#drawer').show();
	}

	function formClose(f){
		var	svg = '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="svg-inline--fa fa-times fa-w-11" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg>';
			close = '<div id="form-close">' + svg + '</div>',
			target = $(f).find('#gform_2');

		$(f).prepend(close);

	}

	function clearFormValues(){
		$('#slider-form form').trigger("reset");
		// var inputs = $('#slider-form form input[type="text"]');
		// $(inputs).each(function(){
		// 	$(this).val();
		// });
	}


	function validateEmail($email) {

		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

		return emailReg.test( $email );

	}

	function validCheck(){
		var inputs = $('#slider-form form input[type="text"]'),
			valArr = [],
			button = document.getElementById('gform_submit_button_2');

		$(inputs).each(function(){
			var parent = $(this).parent().parent();
			if ( !$(parent).hasClass('error') && $(this).hasClass('has-val') ){
				valArr.push(true);
			} else {
				valArr.push(false);
			}
		});

		var valid = valArr.indexOf(false);
		
		if(valid === -1){
			
			button.removeAttribute("disabled");

		} else {

			button.setAttribute("disabled","disabled");
		}
	}

	function setInputFilter(textbox, inputFilter) {
	  ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
	    textbox.addEventListener(event, function() {
	      if (inputFilter(this.value)) {
	        this.oldValue = this.value;
	        this.oldSelectionStart = this.selectionStart;
	        this.oldSelectionEnd = this.selectionEnd;
	      } else if (this.hasOwnProperty("oldValue")) {
	        this.value = this.oldValue;
	        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
	      } else {
	        this.value = "";
	      }
	    });
	  });
	}

})( jQuery );



// document.addEventListener('click', function(e) {
//     e = e || window.event;
//     var target = e.target || e.srcElement,
//         text = target.textContent || target.innerText;  
//     console.log(target); 
// }, false);
