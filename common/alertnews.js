$(document).ready(function(){
	var latestnews;
	$.ajax({ url: 'ultima_noticia.php',
		 success: function(output) {
					if(output!="erro")
						latestnews=output;
					else
						alert(output);
				  }
		});
	
	var intervalid = window.setInterval(function(){
	  $.ajax({ url: 'ultima_noticia.php',
		 success: function(output) {
					if(output!="erro")
					{
						if(output>latestnews)
						{
							$("div#menu").after('<div id=\"message\" style=\"display:none\">Existem novas notícias<div id=\"close\">[ x ]</div></div>');
							$("div#message").slideDown('fast');
							clearInterval(intervalid);
						}
					}
					else
					{
						alert(output);
						clearInterval(intervalid);
					}
				  }
		});
	}, 5000);
});