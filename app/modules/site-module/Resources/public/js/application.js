function p24_krajeMapa(idDOM,kolik) {
	if (kolik < 8) {
		document.getElementById(idDOM).style.backgroundPosition = "0px -"+(kolik*160)+"px";
	}
	else {
		document.getElementById(idDOM).style.backgroundPosition = "-200px -"+((kolik-7)*160)+"px";
	}
}

$(document).ready(function() {
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

	$('.carousel').carousel({
		interval: 2000
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

	var toggleNavigation = function() {
		var top = $(document).scrollTop();

		if (top > 86 && !_w) {
			_w = true;
			$('.navigation-container').removeClass('navbar-static-top');
			$('.navigation-container').addClass('navbar-fixed-top');
		} else if (top <= 86 && _w ) {
			_w = false;
			$('.navigation-container').removeClass('navbar-fixed-top');
			$('.navigation-container').addClass('navbar-static-top');
		}
	}

	$(window).scroll(toggleNavigation);
	toggleNavigation();
});