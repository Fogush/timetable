<?php
header ('content-type: text/javascript; charset: UTF-8');
 $template_path  = $_GET['template_dir'];

if(isset($_GET['stickmenu']) && $_GET['stickmenu'] == 1) { 
?>
$(function ($) {
//////////////////////////////////////////////////////////////////////////
////// START - CODE FOR LOADING THE TOP MENU CHASING BAR
//////////////////////////////////////////////////////////////////////////

	  // grab the initial top offset of the navigation 
		var sticky_navigation_offset_top = $('#rt-navigation ').offset().top;
	
	   // our function that decides weather the navigation bar should have "fixed" css position or not.
		var sticky_navigation = function(){ 

		var scroll_top = $(window).scrollTop(); // our current vertical position from the top
		
		// if we've scrolled more than the navigation, change its position to fixed to stick to top, otherwise change it back to relative
		if (scroll_top > sticky_navigation_offset_top) { alert("hi");
			$('#rt-navigation').addClass('fixed').css({ 'top':0 });
			} else {
			$('#rt-navigation').removeClass('fixed'); 
		}   
	};
	
	// run our function on load
	sticky_navigation();
	
	// and run it again every time you scroll
	$(window).scroll(function() {
		 sticky_navigation();
	});
	
		
});
<?php } ?>


/*
 * jQuery :nth-last-child - v0.2 - 2/13/2010
 * http://benalman.com/projects/jquery-misc-plugins/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function($){var c=/:(nth)-last-child(?:\((even|odd|[\dn+-]*)\))?/,a=$.expr,b=a.filter.CHILD;a[":"]["nth-last-child"]=function(h,g,e,k){var j=e[0].match(c),f=$(h.parentNode).children(),d;j=a.preFilter.CHILD(j);b(h,j);d=f.eq(f.length-h.nodeIndex)[0];return b(d,j)}})(jQuery);

jQuery(document).ready(function($) {  
/////////////////////////////////////////////////////////////////////////
//Blockquote
/////////////////////////////////////////////////////////////////////////

         
        // Example One
        $('#tsc_quotes-rotator-container1 .tsc_quotes-rotator1').quovolver({
            transitionSpeed : 300, // transition speed
            autoPlaySpeed : 2300, // duration before each transition (default 2300 = 2.3 seconds)
            autoPlay : true, // auto play quotes rotator
            equalHeight : false // disable auto resizing of panels
        });
         
/////////////////////////////////////////////////////////////////////////
//Tooltip
/////////////////////////////////////////////////////////////////////////  
// placement examples
			$('.north').powerTip({placement: 'n'});
			$('.east').powerTip({placement: 'e'});
			$('.south').powerTip({placement: 's'});
			$('.west').powerTip({placement: 'w'});
			$('.north-west').powerTip({placement: 'nw'});
			$('.north-east').powerTip({placement: 'ne'});
			$('.south-west').powerTip({placement: 'sw'});
			$('.south-east').powerTip({placement: 'se'});

			// mouse follow examples
			$('#mousefollow-examples div').powerTip({followMouse: true});

			// mouse-on examples
			$('#mouseon-examples div').data('powertipjq', $([
				'<p><b>Here is some content</b></p>',
				'<p><a href="http://stevenbenner.com/">Maybe a link</a></p>',
				'<p><code>{ placement: \'e\', mouseOnToPopup: true }</code></p>'
			].join('\n')));
			$('#mouseon-examples div').powerTip({
				placement: 'e',
				mouseOnToPopup: true
			});

			// api examples
			$('#api-open').on('click', function() {
				$.powerTip.showTip($('#mouseon-examples div'));
			});
			$('#api-close').on('click', function() {
				$.powerTip.closeTip();
			});
			
//////////////////////////////////////////////////////////////////////////	
// TOGGLES 
//////////////////////////////////////////////////////////////////////////	
	if ($('.toggle span').length > 0) {
			var toggle = $('.toggle span');
				toggle.each(function () {
				var q = $(this);

				if (q.children('section').css('display') === 'block') {
				q.children('h4').addClass('collapse');
				} else if (q.children('section').css('display') === 'none') {
				q.children('h4').addClass('expanded');
				}

				q.children('h4').click(function () {

				q.children('section').slideToggle('normal', function () {
				if (q.children('section').css('display') === 'block') {
				q.children('h4').addClass('collapse');
				q.children('h4').removeClass('expanded');

				} else if (q.children('section').css('display') === 'none') {
				q.children('h4').addClass('expanded');
				q.children('h4').removeClass('collapse');
				}

				});
			});
			});
		}
       
 
//////////////////////////////////////////////////////////////////////////	
// ADD ODD CLASS TO ROWS
//////////////////////////////////////////////////////////////////////////	
	
	$(".zebra-style tr:odd, .toggle-style-faq .tgg-trigger:odd").addClass("odd");


})// end of window load

