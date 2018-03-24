/* UI Controller. This should be added to the back of the page. */

//goto page top
function goTop() {
  try {
    $('body, html').animate({ scrollTop: $("#section_pop_menu").offset().top }, 400);
  } catch(e) {}
}

// goto page bottom
function goBottom() {
	try {
		$('body, html').animate({ scrollTop: $("#bottom").offset().top }, 100);
	} catch (e) {}
}



/////////////////////////////////////////////////////////////////
//
// ALERT OR NOTICE CONTROL
//
/////////////////////////////////////////////////////////////////

// custom alert
function showAlert(msg) {
	if (msg!=undefined && msg.length>0) {
		$('#alert').css('background-color', '#fee');
		$('#alert').css('color', '#f00');
		$('#alert').css('border-radius', '20px;');
		$('#alert').html(msg);
		$('#alert').show().effect("bounce");
		$('#alert').delay(2000).slideUp(500);
	}
}

// custom notice
function showNotice(msg) {
	if (msg!=undefined && msg.length>0) {
		$('#alert').html(msg);
		$('#alert').show();
	}
}

// get bulletin categories and change the layout of elements on the screen to suit your environment.
$(document).ready(function(){

	$("h1").effect("slide");
	
	/* show go top/prev/next button */
	$(window).scroll(function(e){
		e.stopPropagation();
		e.preventDefault();
		if ($(window).scrollTop()>80) {
			$('#gotop').show();
		} else {
			$('#gotop').hide();
		}
	});
	
	/* set header and footer */
	function setHeaderFooterPosition() {
		var baseoffset = $('#article').offset();
		var left  = 28;
		var right = 24;
		if ($(window).width()<501) { // mobile
			left  = 18;
			right = 10;
		}
		$('#menu').css('left', baseoffset.left+left);
		$('#domain').css('left', baseoffset.left+left+20);
		$('#section_logon_status').css('right', ($(window).width()-(baseoffset.left+$('#article').outerWidth())+right));
		$('#section_logon_status').show();
		$('#alert').css('padding-left', baseoffset.left+left);
		$('#copyright').css('left', baseoffset.left);
	}
	
	// load
	setHeaderFooterPosition();

	// resize
	$(window).resize(function(e){
		e.stopPropagation();
		e.preventDefault();
		setHeaderFooterPosition();
	});
	
	/* show or not button for speak */
	('speechSynthesis' in window) ? $('#speak').show() : $('#speak').hide();
});

$(window).on('beforeunload', function() {
	if ('speechSynthesis' in window) {
		window.speechSynthesis.cancel();
	}
});

