var idUser = 0;

$(document).on("ready", function(){
	$("form[name=frm_admin]").on("submit", function(e){
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
					$.ajax({
						url: webServer + "c/admin/",
						type: "POST",
						dataType: "json",
						data: $("form[name=frm_admin]").serialize(),
						success: function(obj){
							if(obj.state){
								$("form[name=frm_admin] button:submit").button("option", "disabled", true);
								$("table#tbl-admin").jqGrid("setGridParam",{
									url: webServer + "ajax/grid_usuarios.php", 
									page: 1
								});
								$("table#tbl-admin").trigger("reloadGrid");
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
	
	$("table#tbl-admin").jqGrid({
		url: webServer + "ajax/grid_usuarios.php",
		datatype: "json",
		colNames:["DNI", "Nombre", "Usuario"],
		colModel:[
		{
			name: "id", 
			index: "id", 
			width: 80, 
			align: "center", 
			sortable:false
		},
{
			name: "nombre", 
			index: "nombre", 
			width: 300, 
			align: "left", 
			sortable:false
		},
{
			name: "usuario", 
			index: "usuario", 
			width: 150, 
			align: "center", 
			sortable:false
		}
		],
		rowNum: 10,
		rowList: [10, 15, 20],
		pager: "#pag-admin",
		viewrecords: true,
		sortname: "nombre",
		sortorder: "ASC",
		caption:"Usuarios registrados",
		height: "100%",
		onSelectRow: function(idU){
			idUser = idU;
			$("#btn-del-user").button("option", "disabled", false);
		}
	});

	$("table#tbl-admin").jqGrid("navGrid","div#pag-admin", {
		edit:false, 
		add:false, 
		del:true, 
		search: false
	});
	
	$("#btn-admin").css("width", $("table#tbl-admin").css("width"));
	
	$("#btn-del-user").on("click", function(e){
		$("#dialog-form:ui-dialog").dialog("destroy");
		$("#dialog-form").html("<p>¿Desea deshabilitar el usuario?</p>");
		$("#dialog-form").dialog({
			title: "Confirmar",
			modal: true,
			resizable: false,
			height: "auto",
			buttons: {
				Si: function(){
					$.ajax({
						url: webServer + "c/admin/eliminar/",
						type: "POST",
						dataType: "json",
						data: "id=" + idUser,
						success: function(obj){
							if(obj.state){
								$("#btn-del-user").button("option", "disabled", true);
								$("table#tbl-admin").jqGrid("setGridParam",{
									url: webServer + "ajax/grid_usuarios.php", 
									page: 1
								});
								$("table#tbl-admin").trigger("reloadGrid");
								showModalDialog_Ok("Usuario deshabilitado", "<p>" + obj.messageHTML + "</p>");
							}
							else{
								showModalDialog_Ok("Usuario no deshabilitado", "<ul>" + obj.messageHTML + "</ul>");
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