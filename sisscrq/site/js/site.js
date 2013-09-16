function showModalDialog_Ok(titleDialog, messageDialog){
	$("#dialog-form:ui-dialog").dialog("destroy");
	$("#dialog-form").html(messageDialog);
	$("#dialog-form").dialog({
		title: titleDialog,
		modal: true,
		resizable: false,
		height: "auto",
		buttons: {
			Aceptar: function(){
				$(this).dialog("close");
			}
		}
	});
}

function printValoracion(urlPrint){
    $("#btn-imprimir-valoracion").attr("href", "#").button("option", "disabled", false);
	$("#btn-imprimir-valoracion").on("click", function(e){
		e.preventDefault();
		window.open(urlPrint);
	});
}

$(document).on("ready", function(){
	
});