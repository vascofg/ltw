var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

function updateResults(search, ms) {
	var title, text, tag, p;
	if($("input:checkbox[name='title']").attr("checked"))
		title=1;
	else
		title=0;
	if($("input:checkbox[name='text']").attr("checked"))
		text=1;
	else
		text=0;
	if($("input:checkbox[name='tag']").attr("checked"))
		tag=1;
	else
		tag=0;
	p = window.location.search.replace( "?p=", "" ); //get page
	delay(function(){
		$.ajax({ url: 'pesquisar_noticia.php',
		 data: {search: search,
				title:	title,
				text:	text,
				searchtag: tag,
				p:		p},
		 type: 'post',
		 success: function(output) {
					$("div#conteudo").html(output);
				  }
		});
	}, ms);
};

$(document).ready(function(){
	$("input:text[name='search']").keyup(function()
	{
		if($('input:checkbox:checked').length>0)
			updateResults($("input:text[name='search']").val(), 500);
	});
	$("input:checkbox[name='title'],input:checkbox[name='text'],input:checkbox[name='tag']").change(function()
	{
		if($('input:checkbox:checked').length>0)
			updateResults($("input:text[name='search']").val(), 50);
		else
			updateResults("", 50);
	});
});