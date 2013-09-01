$(document).on("ready", function(){
	$("form[name=frm_registrar_paciente]").on("submit", function(e){
		e.preventDefault();

		$("#dialog-form:ui-dialog").dialog("destroy");
		$("#dialog-form").html("¿Guardar los datos ingresados?");
		$("#dialog-form").dialog({
			title: "Confirmar",
			modal: true,
			resizable: false,
			height: 110,
			buttons: {
				Si: function(){
					$.ajax({
						url: webServer + "c/registrar/paciente/",
						type: "POST",
						dataType: "json",
						data: $("form[name=frm_registrar_paciente]").serialize(),
						success: function(obj){
							if(obj.state){
								$("form[name=frm_registrar_paciente] button:submit").button("option", "disabled", true);
								showModalDialog_Ok("Registro correcto", "<p>" + obj.messageHTML + "</p>");
							}
							else{
								showModalDialog_Ok("Registro incorrecto", "<ul>" + obj.messageHTML + "</ul>");
							}
						}
					});
				},
				No: function(){
					$(this).dialog("close");
				}
			}
		});
	});
});