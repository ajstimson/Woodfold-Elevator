/**
 * Custom jQuery
 */

(function($) {
	if($('body.page-id-1371').length){
		$('article').addClass('elevator-page');
	}
	if($('body.page-id-1376').length){
		$('article').addClass('gates-page');
	}
	$('.post-1371 p:lt(2), .post-1371 .login').wrapAll('<div class="flex" />');
	$('.post-1371 p:lt(2)').wrapAll('<div id="product-spec" class="flex-child-50" />');
	$('.post-1371 .login').wrap('<div class="flex-child-50" />');
	if($('a.cad-details img').length < 1){
		$('a.cad-details').append('<img src="/wp-content/uploads/2018/05/cadDetails.png">');
	}

	//If this is a case study page - alternate image/text content
	if($("title:contains('Case Studies')").length){
		var block = $('.nav-block:odd');
		$(block).each(function(){
			var moveThis = $(this).find('.flex-child-50:last-child');
			var here = $(this).find('.flex-child-50:first-child');
			$(moveThis).insertBefore(here);
		});
	}
	
	// $(window).load(function() {		

	// 	// Other stuff
	// 	if($('#rev_slider_21_3_wrapper, #rev_slider_21_2_wrapper').length){
	// 		if (typeof $(this).data('alias') == 'undefined') {
	// 			$(this).attr('data-alias', 'wood-color-selector');
	// 		}
	// 	}

	// 	if($('#rev_slider_5_1_wrapper, #rev_slider_5_2_wrapper').length){
	// 		if (typeof $(this).data('alias') == 'undefined') {
	// 			$(this).attr('data-alias', 'decorative options');
	// 		}
	// 	}

	// 	var url = window.location.pathname.split('/');
	// 	var finalPath = url.pop() || url.pop();
		
	// 	if (/^series/.test(finalPath)){
	// 		var spec = document.getElementById('product-spe');
	// 		var slide = document.getElementById('rev_slider_5_2_wrapper');
	// 		$(spec).insertBefore(slide);
	// 	}

	// 	// Set min-height to make columns equal length
	// 	var wrappers = $('.column-block .wrapper');
		
	// 	if($('body.page-id-1365').length){
	// 		wrappers = $('.flex-child-20 .smaller-p');
	// 	}
		
	// 	var wHeights = $(wrappers).map(function (){
	// 		return $(this).height();
	// 	}).get(),
	// 	maxWrapper = Math.max.apply(null, wHeights);

	// 	$(wrappers).each(function( index ){
	// 		var currWH = $(this).height();
	// 		if (currWH < maxWrapper) {

	// 			var newHeight = (maxWrapper - currWH);

	// 			if($('body.page-id-1365').length){
	// 				$(this).css( {	'min-height': (newHeight + currWH) + 'px'});
	// 			} else {
	// 				var currP = $(this).find('h3 + p').height();
	// 				$(this).find('h3 + p').css( {	'min-height': (newHeight + currP) + 'px'});
	// 			}
	// 		}
	// 	});
	// });

	// $(window).load(function () {

	// 	$('#rev_slider_21_2_wrapper, #rev_slider_21_3_wrapper').attr('data-tab', 'rs-45');
	// 	$("#rev_slider_21_2_wrapper .tp-tab, #rev_slider_21_3_wrapper .tp-tab").click(function(){
	// 		var tabDataRef = $(this).attr('data-liref');
		
	// 		var wrapper = $(this).closest('#rev_slider_21_3_wrapper');
		
	// 		if($('#rev_slider_21_2_wrapper').length){
	// 			wrapper = $(this).closest('#rev_slider_21_2_wrapper');
	// 		}
		
	// 		$(wrapper).attr('data-tab', tabDataRef);
	// 	});
		
	// 	$( "#slide-49-layer-5 img, #slide-57-layer-5 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/hw_alder_panel.png"></div>' ) );
	// 	$( "#slide-49-layer-7 img, #slide-57-layer-7 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/hw_birch_panel.png"></div>' ) );
	// 	$( "#slide-49-layer-8 img, #slide-57-layer-8 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/hw_cherry_panel.png"></div>' ) );
	// 	$( "#slide-49-layer-11 img, #slide-57-layer-11 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/hw_mahog_panel.png"></div>' ) );
	// 	$( "#slide-49-layer-12 img, #slide-57-layer-12 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/hw_maple_panel.png"></div>' ) );
	// 	$( "#slide-49-layer-13 img, #slide-57-layer-13 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/hw_oak_panel.png"></div>' ) );
	// 	$( "#slide-49-layer-14 img, #slide-57-layer-14 img" ).after( $( '<div class="img-hover" id="walnut-panel"><img src="/wp-content/uploads/2018/05/hw_walnut_panel.png"></div>' ) );
	// 	$( "#slide-50-layer-5 img, #slide-58-layer-52 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/vl_birch_panel.png"></div>' ) );
	// 	$( "#slide-50-layer-7 img, #slide-58-layer-50 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/vl_maple_panel.png"></div>' ) );
	// 	$( "#slide-50-layer-20 img, #slide-58-layer-48 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/vl_nat-oak_panel.png"></div>' ) );
	// 	$( "#slide-50-layer-21 img, #slide-58-layer-46 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/vl_lt-oak_panel.png"></div>' ) );
	// 	$( "#slide-50-layer-33 img, #slide-58-layer-44 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/vl_dk-oak_panel.png"></div>' ) );
	// 	$( "#slide-50-layer-22 img, #slide-58-layer-42 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/vl_cherry_panel.png"></div>' ) );
	// 	$( "#slide-50-layer-23 img, #slide-58-layer-40 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/vl_teak_panel.png"></div>' ) );
	// 	$( "#slide-50-layer-24 img, #slide-58-layer-38 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/vl_mahog_panel.png"></div>' ) );
	// 	$( "#slide-50-layer-25 img, #slide-58-layer-36 img" ).after( $( '<div class="img-hover" id="vl-walnut-panel"><img src="/wp-content/uploads/2018/05/vl_walnut_panel.png"></div>' ) );
	// 	$( "#slide-51-layer-5 img, #slide-59-layer-5 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/sc_white_panel.png"></div>' ) );
	// 	$( "#slide-51-layer-7 img, #slide-59-layer-7 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/sc_black_panel.png"></div>' ) );
	// 	$( "#slide-51-layer-8 img, #slide-59-layer-8 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/sc_tan_panel.png"></div>' ) );
	// 	$( "#slide-51-layer-11 img, #slide-59-layer-11 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/sc_gray_panel.png"></div>' ) );
	// 	$( "#slide-51-layer-12 img, #slide-59-layer-12 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/sc_chalk_panel.png"></div>' ) );
	// 	$( "#slide-51-layer-13 img, #slide-59-layer-13 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/sc_tahiti_panel.png"></div>' ) );
	// 	$( "#slide-51-layer-14 img, #slide-59-layer-14 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/sc_rattan_panel.png"></div>' ) );
	// 	$( "#slide-51-layer-20 img, #slide-59-layer-20 img" ).after( $( '<div class="img-hover" id="amethyst-panel"><img src="/wp-content/uploads/2018/05/sc_amethyst_panel.png"></div>' ) );
	// 	$( "#slide-52-layer-5 img, #slide-60-layer-5 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/ap_clear_bronze_panel.png"></div>' ) );
	// 	$( "#slide-52-layer-7 img, #slide-60-layer-7 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/ap_clear_clear_panel.png"></div>' ) );
	// 	$( "#slide-52-layer-20 img, #slide-60-layer-20 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/ap_clear_white_panel.png"></div>' ) );
	// 	$( "#slide-52-layer-21 img, #slide-60-layer-21 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/ap_clear_sand_panel.png"></div>' ) );
	// 	$( "#slide-52-layer-22 img, #slide-60-layer-22 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/ap_clear_gold_panel.png"></div>' ) );
	// 	$( "#slide-52-layer-23 img, #slide-60-layer-23 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/ap_bronze_bronze_panel.png"></div>' ) );
	// 	$( "#slide-52-layer-24 img, #slide-60-layer-24 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/ap_bronze_clear_panel.png"></div>' ) );
	// 	$( "#slide-52-layer-25 img, #slide-60-layer-25 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/ap_bronze_white_panel.png"></div>' ) );
	// 	$( "#slide-52-layer-32 img, #slide-60-layer-32 img" ).after( $( '<div class="img-hover" id="bronze-sand-panel"><img src="/wp-content/uploads/2018/05/ap_bronze_sand_panel.png"></div>' ) );
	// 	$( "#slide-52-layer-33 img, #slide-60-layer-33 img" ).after( $( '<div class="img-hover" id="bronze-gold-panel"><img src="/wp-content/uploads/2018/05/ap_bronze_gold_panel.png"></div>' ) );
	// 	$( "#slide-53-layer-5 img, #slide-61-layer-5 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/AlumClearPerf2_panel.png"></div>' ) );
	// 	$( "#slide-53-layer-7 img, #slide-61-layer-7 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/AlumClearSolid2_panel.png"></div>' ) );
	// 	$( "#slide-53-layer-8 img, #slide-61-layer-8 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/AlumGoldPerf2_panel.png"></div>' ) );
	// 	$( "#slide-53-layer-11 img, #slide-61-layer-11 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/AlumGoldSolid2_panel.png"></div>' ) );
	// 	$( "#slide-53-layer-12 img, #slide-61-layer-12 img" ).after( $( '<div class="img-hover"><img src="/wp-content/uploads/2018/05/AlumBronzePerf2_panel.png"></div>' ) );
	// 	$( "#slide-53-layer-13 img, #slide-61-layer-13 img" ).after( $( '<div class="img-hover" id="bronze-solid-panel"><img src="/wp-content/uploads/2018/05/AlumBronzeSolid2_panel.png"></div>' ) );

	// 	$("#slide-49-layer-14 img, #wanut-panel img, #slide-57-layer-14 img, #slide-51-layer-20 img, #amethyst-panel img, #slide-59-layer-20 img").hover(
	// 		function () {
	// 			$("#slide-49-layer-13, #slide-49-layer-17, #slide-51-layer-14, #slide-57-layer-13, #slide-51-layer-21, #slide-59-layer-14").css('z-index', '0');
	// 			$("#slide-49-layer-13, #slide-49-layer-17, #slide-51-layer-14, #slide-57-layer-13, #slide-51-layer-21, #slide-59-layer-14").parentsUntil('li').css('z-index', '0');
	// 		},
	// 		function () {
	// 			$("#slide-49-layer-13, #slide-49-layer-17, #slide-51-layer-14, #slide-57-layer-13, #slide-51-layer-21, #slide-59-layer-14").css('z-index', '8');
	// 			$("#slide-49-layer-13, #slide-49-layer-17, #slide-51-layer-14, #slide-57-layer-13, #slide-51-layer-21, #slide-59-layer-14").parentsUntil('li').css('z-index', '10');
	// 	});

	// 	$("#slide-50-layer-25 img, #slide-50-layer-25 #vl-walnut-panel").hover(
	// 		function () {
	// 			$("#slide-50-layer-24").css('z-index', '5');
	// 			$("#slide-50-layer-24").parentsUntil('li').css('z-index', '5');
	// 		},
	// 		function () {
	// 			$("#slide-50-layer-24").css('z-index', '9');
	// 			$("#slide-50-layer-24").parentsUntil('li').css('z-index', '9');
	// 	});

	// 	var firstImg = $('#slide-52-layer-24');
	// 	var firstImgPosition = $(firstImg).css("z-index");
		
	// 	var firstImgTxt = $('#slide-52-layer-30');
	// 	var firstImgTxtPosition = $(firstImgTxt).css("z-index");
		
	// 	var secondImg = $('#slide-52-layer-25');
	// 	var secondImgPosition = $(secondImg).css("z-index");
		
	// 	var secondImgTxt = $('#slide-52-layer-31');
	// 	var secondImgTxtPosition = $(secondImgTxt).css("z-index");
		

	// 	$("#slide-52-layer-32 img, #slide-52-layer-32 #bronze-sand-panel").hover(
	// 		function () {
	// 			$(firstImg).css('z-index', '0');
	// 			$(firstImgTxt).css('z-index', '0');
	// 			$(secondImg).css('z-index', '0');
	// 			$(secondImgTxt).css('z-index', '0');
	// 			$(firstImg).parentsUntil('li').css('z-index', '0');
	// 			$(firstImgTxt).parentsUntil('li').css('z-index', '0');
	// 			$(secondImg).parentsUntil('li').css('z-index', '0');
	// 			$(secondImgTxt).parentsUntil('li').css('z-index', '0');
	// 		},
	// 		function () {
	// 			$(firstImg).css('z-index', firstImgPosition);
	// 			$(firstImgTxt).css('z-index', firstImgTxtPosition);
	// 			$(secondImg).css('z-index', secondImgPosition);
	// 			$(secondImgTxt).css('z-index', secondImgTxtPosition);
	// 			$(firstImg).parentsUntil('li').css('z-index', firstImgPosition);
	// 			$(firstImgTxt).parentsUntil('li').css('z-index', firstImgTxtPosition);
	// 			$(secondImg).parentsUntil('li').css('z-index', secondImgPosition);
	// 			$(secondImgTxt).parentsUntil('li').css('z-index', secondImgTxtPosition);
	// 	});

	// 	var thirdImg = $('#slide-52-layer-32, #slide-60-layer-32');
	// 	var thirdImgPosition = $(thirdImg).css("z-index");

	// 	var thirdImgTxt = $('#slide-52-layer-34, #slide-60-layer-34');
	// 	var thirdImgTxtPosition = $(thirdImgTxt).css("z-index");

	// 	$("#slide-52-layer-33 img, #bronze-gold-panel, #slide-60-layer-35 img").hover(
	// 		function () {
	// 			$(secondImg).css('z-index', '0');
	// 			$(secondImgTxt).css('z-index', '0');
	// 			$(secondImg).parentsUntil('li').css('z-index', '0');
	// 			$(secondImgTxt).parentsUntil('li').css('z-index', '0');
	// 			$(thirdImg).css('z-index', '0');
	// 			$(thirdImgTxt).css('z-index', '0');
	// 			$(thirdImg).parentsUntil('li').css('z-index', '0');
	// 			$(thirdImgTxt).parentsUntil('li').css('z-index', '0');
	// 		},
	// 		function () {
	// 			$(secondImg).css('z-index', secondImgPosition);
	// 			$(secondImgTxt).css('z-index', secondImgTxtPosition);
	// 			$(secondImg).parentsUntil('li').css('z-index', secondImgPosition);
	// 			$(secondImgTxt).parentsUntil('li').css('z-index', secondImgTxtPosition);
	// 			$(thirdImg).css('z-index', thirdImgPosition);
	// 			$(thirdImgTxt).css('z-index', thirdImgTxtPosition);
	// 			$(thirdImg).parentsUntil('li').css('z-index', thirdImgPosition);
	// 			$(thirdImgTxt).parentsUntil('li').css('z-index', thirdImgTxtPosition);
	// 	});

	// 	var fourthImg = $('#slide-53-layer-12, #slide-61-layer-12');
	// 	var fourthImgPosition = $(fourthImg).css("z-index");

	// 	var fourthImgTxt = $('#slide-53-layer-16, #slide-61-layer-16');
	// 	var fourthImgTxtPosition = $(fourthImgTxt).css("z-index");

	// 	$("#slide-53-layer-13 img, #bronze-solid-panel, #slide-61-layer-13 img").hover(
	// 		function () {
	// 			$(fourthImg).css('z-index', '0');
	// 			$(fourthImgTxt).css('z-index', '0');
	// 			$(fourthImg).parentsUntil('li').css('z-index', '0');
	// 			$(fourthImgTxt).parentsUntil('li').css('z-index', '0');
	// 		},
	// 		function () {
	// 			$(fourthImg).css('z-index', fourthImgPosition);
	// 			$(fourthImgTxt).css('z-index', fourthImgTxtPosition);
	// 			$(fourthImg).parentsUntil('li').css('z-index', fourthImgPosition);
	// 			$(fourthImgTxt).parentsUntil('li').css('z-index', fourthImgTxtPosition);
	// 	});

	// });

	$('.nav-block.has-post-thumbnail:odd').addClass('even');
	$('.nav-block.has-post-thumbnail:even').addClass('odd');

	$('.tag-product-specifications').attr('id', 'product-spe');
	$('.series .category-case-study').attr('id', 'case-study');


})( jQuery );



// document.addEventListener('click', function(e) {
//     e = e || window.event;
//     var target = e.target || e.srcElement,
//         text = target.textContent || target.innerText;  
//     console.log(target); 
// }, false);
