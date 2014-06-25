function p24_krajeMapa(idDOM,kolik) {
	if (kolik < 8) {
		document.getElementById(idDOM).style.backgroundPosition = "0px -"+(kolik*160)+"px";
	}
	else {
		document.getElementById(idDOM).style.backgroundPosition = "-200px -"+((kolik-7)*160)+"px";
	}
}

$(document).ready(function() {
	$("a[rel=lightbox]").fancybox({'type' : 'image'});alert

	jQuery('body').width(jQuery('body').width()+1).width('auto');
	$(".accordion").find('li div').css('display', 'none');
	$(".accordion").zAccordion({
		buildComplete: function () {
			$(".accordion").find('li.slider-open div').fadeIn(600);
		},
		animationStart: function () {
			$(".accordion").find('li.slider-open div').css('display', 'none');
			$(".accordion").find('li.slider-previous div').css('display', 'none');
		},
		animationComplete: function () {
			$(".accordion").find('li.slider-open div').fadeIn(600);
		},
		speed: 700,
		slideClass: "slider",
		width: "857",
		height: "200",
		slideWidth: "390",
		trigger: "mouseover"
	});

	$('.header-container .control-search input[name="search"]').on('focus', function() {
		$(this).closest('.control-search').animate({width:'600px'}, 500);
		//$(this).closest('.twitter-typeahead').animate({backgroundColor: 'rgba(255,255,255,0.9)'}, 500);
	});
	$('.header-container .control-search input[name="search"]').on('focusout', function() {
		$(this).closest('.control-search').animate({width:'250px'}, 500);
		//$(this).closest('.twitter-typeahead').animate({backgroundColor: 'rgba(255,255,255,0.3)'}, 500);
	});

	var _w = false;
	var _s = false;

	var toggleNavigation = function() {
		var top = $(document).scrollTop();

		if (top > 106 && top < 166) {
			var t = top - 86;
			t = t / 0.6 / 100 / 4;
			$('.navigation-container').css('-webkit-box-shadow', '0px 0px 20px 0px rgba(50, 50, 50, ' + t + ')');
			$('.navigation-container').css('-moz-box-shadow', '0px 0px 20px 0px rgba(50, 50, 50, ' + t + ')');
			$('.navigation-container').css('box-shadow', '0px 0px 20px 0px rgba(50, 50, 50, ' + t + ')');
			_s = false;
		} else if (top <= 106 && _s != 1) {
			$('.navigation-container').css('-webkit-box-shadow', 'none');
			$('.navigation-container').css('-moz-box-shadow', 'none');
			$('.navigation-container').css('box-shadow', 'none');
			_s = 1;
		} else if (top >= 166 && _s != 2) {
			$('.navigation-container').css('-webkit-box-shadow', '0px 0px 20px 0px rgba(50, 50, 50, 0.25)');
			$('.navigation-container').css('-moz-box-shadow', '0px 0px 20px 0px rgba(50, 50, 50, 0.25)');
			$('.navigation-container').css('box-shadow', '0px 0px 20px 0px rgba(50, 50, 50, 0.25)');
			_s = 2;
		}

		if (top > 101 && !_w) {
			_w = true;
			$('.navigation-container').removeClass('navbar-static-top');
			$('.navigation-container').addClass('navbar-fixed-top');
		} else if (top <= 101 && _w ) {
			_w = false;
			$('.navigation-container').removeClass('navbar-fixed-top');
			$('.navigation-container').addClass('navbar-static-top');
		}
	}

	$(window).scroll(toggleNavigation);
	toggleNavigation();
});