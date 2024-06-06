jQuery(document).ready(function() {
	
	jQuery('#wt-slider').flexslider({						// slider settings
			animation: "slide",								// animation style
			controlNav: false,								// slider thumnails class
			slideshow: true,								// enable automatic sliding
			directionNav: true,								// disable nav arrows
//			slideshowSpeed: 3000,   							// slider speed
			smoothHeight: false,
			controlsContainer: "#wt-slider .slider-nav"
	});	
	
});