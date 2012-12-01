$(document).ready(function(){
	$("div.server h4 div.arrow").toggle(
		function()
		{
			var thiselem=$(this);
			thiselem.parent().parent().find("div.noticia").slideDown(function(){thiselem.text("˄");});
		},
		function()
		{
			var thiselem=$(this);
			thiselem.parent().parent().find("div.noticia").slideUp(function(){thiselem.text("˅");
		});
	});
	$("div.server h3 div.arrow").toggle(
		function()
		{
			var thiselem=$(this);
			thiselem.parent().parent().find("div.newsbody").slideDown(function(){thiselem.text("˄");});
		},
		function()
		{
			var thiselem=$(this);
			thiselem.parent().parent().find("div.newsbody").slideUp(function(){thiselem.text("˅");
		});
	});
	$.datepicker.setDefaults( $.datepicker.regional[ "pt" ] );
	$("input[name=start_date],input[name=end_date]").datepicker({dateFormat : 'dd-mm-yy'});
});
