$(document).ready(function(){
  $(".add_favorite").live('click',function(){
	var thisdiv = $(this);
	$.ajax({ url: 'ad_rem_favorito.php',
	 data: {id: thisdiv.attr('id'),
			op: 0},
	 type: 'post',
	 success: function(output) {
				if(output=="Sucesso"){
					$("img",thisdiv).attr('src','common/star_filled.png');
					thisdiv.attr("class","del_favorite");
				}
				else
					alert(output);
			  }
	});
  });
  $(".del_favorite").live('click',function(){
	var thisdiv = $(this);
	$.ajax({ url: 'ad_rem_favorito.php',
	 data: {id: thisdiv.attr('id'),
			op: 1},
	 type: 'post',
	 success: function(output) {
				if(output=="Sucesso"){
					$("img",thisdiv).attr('src','common/star_empty.png');
					thisdiv.attr("class","add_favorite");
				}
				else
					alert(output);
			  }
	});
  });
});