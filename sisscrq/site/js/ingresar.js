$(document).on("ready", function(){
	$("button:submit").button({
		icons: {
			primary: "ui-icon-key"
		}
	});

	$("form[name=frm_ingresar]").on("submit", function(e){
		e.preventDefault();
		$("body").css("cursor", "wait");
		var usuario = $.trim($("input[name=usuario]").val());
		var password = $.trim($("input[name=password]").val());
		var message = "";
		
		if(usuario == "")
			message += "<li>Ingrese un usuario</li>";
		
		if(password == "")
			message += "<li>Ingrese la contraseña</li>";

		if(message != ""){
			$("body").css("cursor", "auto");
			showModalDialog_Ok("Ingreso al sistema incorrecto", "<ul>" + message + "</ul>");
		}
		else{
			$.ajax({
				url: webServer + "c/ingresar/",
				type: 'POST',
				dataType: 'json',
				data: $(this).serialize(),
				success: function(obj){
					$("body").css("cursor", "auto");

					if(obj.state){
						$("button:submit").button("option", "disabled", true);
						
						$("#dialog-form:ui-dialog").dialog("destroy");
						$("#dialog-form").html("<p>" + obj.messageHTML + "</p>");
						$("#dialog-form").dialog({
							title: "Ingreso al sistema correcto",
							modal: true,
							resizable: false,
							height: "auto"
						});
						location.href = webServer;
					}
					else
						showModalDialog_Ok("Ingreso al sistema incorrecto", "<ul>" + obj.messageHTML + "</ul>");
				}
			});
		}
	});
});