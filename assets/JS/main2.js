$(document).ready(function() {

		$('.navLink').mouseover(function(){
		$(this).css("color",'#428bca');
	})
	$('.navLink').mouseleave(function(){
		$(this).css("color",'#777');
	})
  
  $('.dropdown-menu li a').mouseover(function(){
		$(this).css("color",'#428bca');
	})
	$('.dropdown-menu li a').mouseleave(function(){
		$(this).css("color",'#777');
	})


var moveCarousel = function(){
	if ($('#slider #sliderItem').eq(0)) {
		$('#slider #sliderItem').eq(0).attr('class', 'item active');
	};
}


moveCarousel();


});//end of document
