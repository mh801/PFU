//check if hash tag exists in the URL and add active class to menu tab
	$(document).ready(function(){
		if(window.location.hash) {
			$('.menu-item').each(function(){
				$(this).removeClass('active');
				});
	 		var hash_value = window.location.hash;	
	 		$(hash_value).addClass('active');
		}else{
			action = $(window.location).attr('href').split('/');
			action[4]=(action[4]==undefined)?'index':action[4];
			state('#'+action[4]);		
		}	
	});

	function state(elem){
		if(elem != null){
			$(elem).addClass('active');
		}	
	}	
