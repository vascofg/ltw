$(document).ready(function(){	
	$("div#message div#close").live("click",function(){
		var message = $(this).parent();
		message.slideUp('fast', function(){message.remove();});
	});
	setTimeout(function(){$("div#message").slideUp('fast', function(){$(this).remove();});},5000);
});