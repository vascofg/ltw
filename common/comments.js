$(document).ready(function(){
	$('input#send_comment').click(function(){
		if($("li#username").attr('userid')!=null){
			
			$.ajax({ 
  			 url: 'novo_comentario.php',
				dataType: 'json',
			 data: {news_id: $('.noticia').attr('id'),user_id:$("li#username").attr('userid'),text:$("#text_new_comment").val()},
			 type: 'get',
			 success: function(output) {
			 	if(output=="ok"){
			 	reload_comments();
			 	}
			 	else{
			 	alert("Ocorreu um erro.");
			 	}		
			}
		});
		}
	});
	if($('div.noticia').attr("id")>0){
  	$.ajax({ 
  		 url: 'obter_comentarios.php',
  		 dataType: 'json',
		 data: {id: $('.noticia').attr('id')},
		 type: 'get',
		 success: function(output) {
		 
			$.each(output,function(index,value){
				var comment=$('<div>');
				comment.attr({id:'comment_from_server'});
				comment.html('<h1>'+value.username+' disse:</h1><p>'+value.text+'</p><date>'+value.date+'</date');
				comment.appendTo('#comments_server');
			});		
		}
		});
		}
	
});

function reload_comments(){
if($('div.noticia').attr("id")>0){
  	$.ajax({ 
  		 url: 'obter_comentarios.php',
  		 dataType: 'json',
		 data: {id: $('.noticia').attr('id')},
		 type: 'get',
		 success: function(output) {
			$('#comments_server').empty();
			$.each(output,function(index,value){
				var comment=$('<div>');
				comment.attr({id:'comment_from_server'});
				comment.html('<h1>'+value.username+' disse:</h1><p>'+value.text+'</p>');
				comment.appendTo('#comments_server');
			});		
		}
		});
		}
}