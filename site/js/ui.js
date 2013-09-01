$(document).on("ready", function(){
	$(".btn-search-notext").button({
		text: false,
		icons: {
			primary: "ui-icon-search"
		}
	});
	$(".btn-search-text").button({
		icons: {
			primary: "ui-icon-search"
		}
	});
	$(".btn-refresh-notext").button({
		text: false,
		icons: {
			primary: "ui-icon-refresh"
		}
	});
	$(".btn-refresh-text").button({
		icons: {
			primary: "ui-icon-refresh"
		}
	});

	$(".btn-save-text").button({
		icons: {
			primary: "ui-icon-disk"
		}
	});

	$(".btn-print-text").button({
		icons: {
			primary: "ui-icon-print"
		}
	});

	$("button:reset").button({
		icons: {
			primary: "ui-icon-refresh"
		}
	}).on("click", function(){
		$(this).prev("button:submit").button("option", "disabled", false);
	});
	
	$(".btn-del-text").button({
		icons:{
			primary: "ui-icon-close"
		}
	});

	$(".radio, .tool-set").buttonset();

	$(".btn-disabled").button("option", "disabled", true);
});