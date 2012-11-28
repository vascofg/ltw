$(document).ready(function(){
	$("div.server h4").toggle(function()
		{
			$(this).parent().find("div.noticia").fadeIn();
		},
		function()
		{
			$(this).parent().find("div.noticia").fadeOut();
	});
});
