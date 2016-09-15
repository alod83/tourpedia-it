// Avvio della pagina
$(document).ready(function() {
	Mappa("record");
	$("#container").hide();
	$("#rappr").hide();

// Aggiungo la classe "active" quando viene premuto un pulsante e la rimuovo agli altri pulsanti
    $(".button").click(function(event) {
        $(".button").removeClass("active");
        $(this).addClass("active");
    });
	
// Pulsante Home
	$("#home").bind('click', function() {
		$("#descr").show();
		$("#container").hide();
		$("#rappr").hide();
	});
	
// Pulsante Dati
	$("#dati").bind('click', function() {
		$("#descr").hide();
		$("#container").hide();
		$("#rappr").hide();
	});
	
	
// Pulsante Mappa
	$("#mappa").bind('click', function() {
		$("#descr").hide();
		$("#container").show();
		$("#rappr").show();
		$(".checked").trigger('click');
	});
	
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