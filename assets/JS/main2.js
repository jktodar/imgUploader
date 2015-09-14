$(document).ready(function() {

	$('.navLink').mouseover(function(){
		$(this).css("color",'#428bca');
	})
	$('.navLink').mouseleave(function(){
		$(this).css("color",'#777');
	})


var addCarousel = function(){

	catsArr.forEach(function(cat, i){
		var divInner = $('<div></div>', {
			class: "carousel-caption",
			text: '...'
		});
		var img = $('<img/>',{
			src: imagesArr[i],
			id: 'sliderImg',
			href: cat + '.php'
		});
		var a = $('<a></a>',{
			href: cat + '.php'
		});
		var divOuter = $('<div></div>', {
			class: 'item',
			id:'sliderItem'
			
		});

		$(img).append(divInner);
		$(a).append(img);
		$(divOuter).append(a);
		$('#slider').append(divOuter);
		if ($('#slider #sliderItem').eq(0)) {
			$('#slider #sliderItem').eq(0).attr('class', 'item active');
		};
		
	});
	
}


addCarousel();

});//end of document
