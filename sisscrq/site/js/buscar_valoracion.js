$(document).on("ready", function(){
	$("#btn-buscar-valoracion").button("option", "disabled", true);

	$("form[name=frm_buscar_valoracion]").on("submit", function(e){
		e.preventDefault();
	});

	$("input[name=q]").autocomplete({
		source: webServer + "c/lista/pacientes/",
		minLength: 2,
		focus: function(event, ui) {
			$("input[name=q]").val( ui.item.label );

			return false;
		},
		select: function(event, ui) {
			var urlPaciente = webServer + "c/paciente/" + ui.item.value + "/";
			var urlGrid = webServer + "ajax/grid_valoraciones.php?id=" + ui.item.value;
			var urlRealizar = webServer + "realizar/valoracion/paciente/" + ui.item.value + "/";

			$("body").css("cursor", "wait");

			$.ajax({
				url: urlPaciente,
				dataType: "json",
				type: "GET",
				success: function(p){
					$("#txt-paciente").text(p.apellidoPaterno + " " + p.apellidoMaterno + ", " + p.nombres);
					$("#txt-dni").text(p.dni);
					$("#txt-registro").text(p.registro);
		
					$("#btn-realizar-valoracion").attr("href", urlRealizar).button("option", "disabled", false);
		
					$("table#tbl-buscar-valoracion").jqGrid("setGridParam",{	url: urlGrid, page: 1	});
					$("table#tbl-buscar-valoracion").trigger("reloadGrid");

					$("body").css("cursor", "auto");
				}
			});

			$("input[name=q]").val( ui.item.label );

			return false;
		},
		open: function() {
			$(this).removeClass("ui-corner-all").addClass("ui-corner-top");
		},
		close: function() {
			$(this).removeClass("ui-corner-top").addClass("ui-corner-all");
		}
	});

	$("table#tbl-buscar-valoracion").jqGrid({
		url: webServer + "ajax/grid_valoraciones.php?id=0",
		datatype: "json",
		colNames:["N°", "Fecha y hora", "Turno", "Enfermera"],
		colModel:[
				  {	name: "id", index: "id", width: 80, align: "right"	},
				  { name: "fecha", index: "fecha", width: 170, align: "center"	},
				  {	name: "turno", index: "turno", width: 60, align: "center"	},
				  {	name: "enfermera", index: "enfermera", width: 300, align: "left", sortable:false	}
				 ],
		rowNum: 20,
		rowList: [20, 30, 40],
		pager: "#pag-buscar-valoracion",
		viewrecords: true,
		sortname: "fecha",
		sortorder: "DESC",
		caption:"Hojas de valoración diarias realizadas",
		height: "100%",
		onSelectRow: function(idHV){
			var urlVer = webServer + "ver/valoracion/" + idHV + "/";

			$("#btn-ver-valoracion").attr("href", urlVer).button("option", "disabled", false);
		}
	});

	$("table#tbl-buscar-valoracion").jqGrid("navGrid","div#pag-buscar-valoracion", {
		edit:false, add:false, del:false, search: false
	});
});