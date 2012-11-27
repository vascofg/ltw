var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

$(document).ready(function(){
  $(".add_favorite").live('click',function(){
	var thisdiv = $(this);
	delay(function(){
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
	}, 100);
  });
  $(".del_favorite").live('click',function(){
	var thisdiv = $(this);
	delay(function(){
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
	}, 100);
  });
});