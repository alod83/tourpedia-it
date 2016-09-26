// Avvio della pagina
$(document).ready(function() {
	Mappa($('input[name="type"]:checked').val());
	Grafico();
	
	
	// Radio Record
	$("#record").bind('click', function() {
		Mappa("record");
	});
	
// Radio Record/Popolazione
	$("#popolazione").bind('click', function() {
		Mappa("recpop");
	});
	
// Radio Record/Territorio
	$("#territorio").bind('click', function() {
		Mappa("recterr");
	});
});