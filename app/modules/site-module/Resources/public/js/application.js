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
});