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
  		reload_comments();
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
			
			if(output.length == 0)
			{
				$('#comments_server').empty();
				var comment=$('<div>');
				comment.html('<h5>Não Existem Comentários.<h5>');
				comment.appendTo('#comments_server');
			}
			else
			{	
				$('#comments_server').empty();
				$.each(output,function(index,value){
					var comment=$('<div>');
					$comment_d = value.date_format;
					comment.attr({id:'comment_from_server', comment_id:value.rowid});
					var delete_b="";
					if(value.deletable){
						delete_b='<input class=delete_comment type=button value="x">';
					}
					comment.html(delete_b+'<input class=edit_comment type=button value="Editar"><div class=comment_username>'+value.username+' disse:</div><div class=comment_text>'+value.text+'</div><div class=comment_date>'+$comment_d+'</div>');
					comment.appendTo('#comments_server');
				
			});		
			$('input.delete_comment').click(function(eventObject){
		if($("li#username").attr('userid')!=null){
			//console.log(eventObject.currentTarget.parentElement);
			var comment=eventObject.currentTarget.parentElement;
			
			$.ajax({ 
  			 url: 'apagar_comentario.php',
				dataType: 'json',
			 data: {id:$(comment).attr('comment_id')},
			 type: 'get',
			 success: function(output) {
			 	if(output=="ok"){
			 	reload_comments();
			 	}
			 	else{
			 	alert("Ocorreu um erro: "+output);
			 	}		
			}
		});
		}
	});
			}		
		}
		});
		}
}

