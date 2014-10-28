$(document).ready(function(){
	// Thêm liên hệ
	$lienhei = 1;
	$('body').on('click','.themlienhe', function(){
		
		$('.chonlienhe').hide(500);
		$('.lienhe1').show(500);
		if($('.chonlienhe').attr('style') == 'display: none;') {
			$(".lienhe1").clone().appendTo(".lienhe").attr('class','lienhe'+$lienhei).find(".xoalienhe").attr('data-id',$lienhei);
		}
		
		$lienhei +=1;
	})
	
	$('body').on('click','.xoalienhe', function(){
		var idlienhen = $(this).attr('data-id');
		$lienhei -=1;
		if($lienhei == 1) {
			$('.chonlienhe').show(500);
			$('.lienhe'+idlienhen).attr('class','lienhe1');
			$('.lienhe1' + ' .xoalienhe').attr('data-id','1');
			$('.lienhe1').hide(500);
		} else {
			
			$('.lienhe'+idlienhen).remove();
		}
		
	})
	
	// Thêm liên hệ
	
})