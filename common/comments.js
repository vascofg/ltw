$(document).ready(function(){
	$('.cancel_button').empty();
	sendButton();	
});

function sendButton(){
	$('.cancel_button').empty();
	$('#send_comment').unbind('click');
	
	$('input#send_comment').click(function(){
		$('.cancel_button').empty();
		if($("li#username").attr('userid')!=null && $('#textarea_new_comment').val() != "") {
			
			$.ajax({ 
  			 url: 'novo_comentario.php',
				dataType: 'json',
			 data: {news_id: $('.noticia').attr('id'),user_id:$("li#username").attr('userid'),text:$("#textarea_new_comment").val()},
			 type: 'get',
			 success: function(output) {
			 	if(output=="ok") {
			 	$('#textarea_new_comment').val("");
			 	reloadComments();
			 	}
			 	else {
			 	alert("Ocorreu um erro.");
			 	}		
			}
			});
		}
	});
	
	if($('div.noticia').attr("id")>0) {
  		reloadComments();
	}		
}

function reloadComments() {
	$('.cancel_button').empty();
	
	if($('div.noticia').attr("id")>0) {
  	$.ajax({ 
  		 url: 'obter_comentarios.php',
  		 dataType: 'json',
		 data: {id: $('.noticia').attr('id')},
		 type: 'get',
		 success: function(output) {
			
			if(output.length == 0) {
				$('#comments_server').empty();
				var comment=$('<div>');
				comment.html('<h5>Não Existem Comentários.<h5>');
				comment.appendTo('#comments_server');
			}
			else {
				
				// Load comments	
				$('#comments_server').empty();
				
				$.each(output,function(index,value){
					var comment=$('<div>');
					
					comment.attr({id:'comment_from_server', comment_id:value.rowid});
					
					var delete_button="";
					if(value.deletable){
						delete_button='<input class=delete_comment type=button value="x">';
					}
					
					var edit_button = "";
					if(value.editable){
						edit_button='<input class=edit_comment type=button value="Editar">';
					}
					
					var $comment_d = "";
					var $comment_edition_d = "";
					var edition_date_label = "";
					if(value.edited == 1) {
						$comment_edition_d = value.edition_date_format;
						$comment_d = '<div class=original_date>Adicionado '+value.date_format+'</div>';
						
						edited_label='<div class=comment_edited_label>Editado</div>';
						edition_date_label='<div class=comment_edition_date>'+edited_label+$comment_edition_d+'</div>';
					}
					else {
						$comment_d = value.date_format;
					}
					
					comment.html(delete_button+edit_button+'<div class=comment_username>'+value.username+' disse:</div><div class=comment_text>'+value.text+'</div><div class=comment_date>'+edition_date_label+$comment_d+'</div>');
					comment.appendTo('#comments_server');
				});	
				
				// Delete comment
				$('input.delete_comment').click(function(eventObject) {
					$('.cancel_button').empty();
					
					if($("li#username").attr('userid')!=null) {
			
						var comment=eventObject.currentTarget.parentElement;
			
						$.ajax({ 
  			 				url: 'apagar_comentario.php',
							dataType: 'json',
			 				data: {id:$(comment).attr('comment_id'),news_id:$('.noticia').attr('id')},
						    type: 'get',
			 				success: function(output) {
			 					if(output=="ok"){
			 					reloadComments();
			 					}
			 					else{
			 						alert("Ocorreu um erro ao apagar o comentário: "+output);
			 					}		
									}
						});
					}
				});

				// Edit comment
				$('input.edit_comment').click(function(eventObject){
					$('.cancel_button').empty();
					if($("li#username").attr('userid')!=null){
						
						console.log(eventObject.currentTarget.parentElement);
						var comment=eventObject.currentTarget.parentElement;
	
						$.ajax({ 
  							url: 'obter_comentario.php',
							dataType: 'json',
							data: {id:$(comment).attr('comment_id')},
							type: 'get',
							success: function(output) {
								
								output=output[0];
	
								$('#comments_server').empty();
								var comment=$('<div>');
								$comment_d = output.date_format;
								comment.attr({id:'comment_from_server', comment_id:output.rowid});
								comment.html('<div class=comment_username>'+output.username+' disse:</div><div class=comment_text>'+output.text+'</div><div class=comment_date>'+$comment_d+'</div>');
								comment.appendTo('#comments_server'); 
	
								$('#textarea_new_comment').val(output.text);
	
								$('#send_comment').unbind('click');
								
								$('input#send_comment').val('Editar Comentário');
								
								$news_id = $('.noticia').attr('id');
								var cancel_edition_button = $('<div class=cancel_button>');
								cancel_edition_button.html('<a href="./?id='+$news_id+'"><input id="cancel_comment_edition" type=button value="Cancelar Edição"></a></div>');
								cancel_edition_button.appendTo('#new_comment');					


						$('input#send_comment').click(function(){
							$('.cancel_button').empty();
							if($("li#username").attr('userid')!=null && $('#textarea_new_comment').val() != ""){
			
								$.ajax({ 
  			 						url: 'editar_comentario.php',
									dataType: 'json',
			 						data: {text:$("#textarea_new_comment").val(), id:$('#comment_from_server').attr('comment_id')},
				 					type: 'get',
			 						success: function(output) {
			 							if(output=="ok"){
			 								$('#textarea_new_comment').val("");
			 								$('input#send_comment').val('Enviar Comentário');
			 								$('.cancel_button').empty();
			 								reloadComments();
			 								sendButton();
			 							}
			 							else {
			 								alert("Ocorreu um erro.");
			 							}		
											}
								});
							}
						});
									}
						});
				}
		});
		}
		}
	});
	}
}

