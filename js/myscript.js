$(function(){

	var w = $(window).width();
	var spwidth = 768;

	// ホバーのアニメーション用クラス付与

	$('.js_hover')
	.on('mouseenter touchstart', function(){
		$(this).addClass('hover');
	}).on('mouseleave touchend', function(){
		$(this).removeClass('hover');
	});

	// ホバーのアニメーション用クラス付与

	// スマホのメニュークリック
	if(w <= spwidth){
		$('.nav_bar').click(function(){
			$(this).toggleClass('active');
			$('nav').toggleClass('active');
		});

		$('.has_sub span.arrow').click(function(){
			if(!$(this).hasClass('active')){
				$('.has_sub span.arrow').removeClass('active');
				$('ul.submenu').slideUp('0.3s');
			}
			$(this).toggleClass('active');
			$(this).next('ul.submenu').slideToggle('0.3s');
		});
	}
	// スマホのメニュークリック


});