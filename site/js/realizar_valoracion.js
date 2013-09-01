$(document).on("ready", function(){
	$("form[name=frm_valoracion]").on("submit", function(e){
		e.preventDefault();

		$("#dialog-form:ui-dialog").dialog("destroy");
		$("#dialog-form").html("<p>¿Guardar los datos ingresados?</p>");
		$("#dialog-form").dialog({
			title: "Confirmar",
			modal: true,
			resizable: false,
			height: 110,
			buttons: {
				Si: function(){
					$("body").css("cursor", "wait");

					$.ajax({
						url: webServer + "ajax/realizar_valoracion.php",
						type: "POST",
						dataType: "json",
						data: $("form[name=frm_valoracion]").serialize(),
						success: function(obj){
							$("body").css("cursor", "auto");
							if(obj.state){
								$("form[name=frm_valoracion] button:submit").button("option", "disabled", true);
								showModalDialog_Ok("Registro correcto de valoración", "<p>" + obj.msg + "</p>");
								
								printValoracion(webServer + "imprimir/valoracion/" + obj.id + "/");
							}
							else
								showModalDialog_Ok("Registro incorrecto de valoración", "<p>" + obj.msg + "</p>");
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