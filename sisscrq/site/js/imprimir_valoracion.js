$(document).on("ready", function(){
    var urlImprimir = webServer + "imprimir/valoracion/" + $.trim($("input#idv").val() + "/");
    
    printValoracion(urlImprimir);
});