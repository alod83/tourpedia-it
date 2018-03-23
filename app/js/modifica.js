function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#image_upload_preview').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

function Clear(){
	$("#inputFile").val('');
	$("#NomeFile").html("Nessun file selezionato");
	$('#image_upload_preview').attr('src', 'images/nopicture.png');
	$(".clear").css('display','none');
}

$(document).ready(function(){
	/*INTRODUCO LA STRUTTURA DALLA SESSIONE*/
	$.getJSON("../api/struttura_scelta.php", function (result){
		if(result['name'] !=  null){
			$("#nome_struttura").val(result['name']);
		};
		if(result['description'] !=  null){
			$("#categoria_struttura").val(result['description']);
		}
		if(result['address'] != null){
			$("#indirizzo_struttura").val(result['address']);
		}
		if(result['number of stars'] != null){
			$("input[name=stelle][value="+result['number of stars']+"]").prop("checked",true);
		}
		if(result['email'] != null){
			$("#email_struttura").val(result['email']);
		}
		if(result['telephone'] != null){
			$("#telefono_struttura").val(result['telephone']);
		}
		if(result['country'] != null){
			$("#paese_struttura").val(result['country']);
		}
		if(result['region'] != null){
			$("#regione_struttura").val(result['region']);
		}
		if(result['province'] != null){
			$("#provincia_struttura").val(result['province']);
		}
		if(result['postal-code'] != null){
			$("#cap_struttura").val(result['postal-code']);
		}
		if(result['city'] != null){
			$("#città_struttura").val(result['city']);
		}
		if(result['latitude'] != null){
			$("#latitudine_struttura").val(result['latitude']);
		}
		if(result['longitude'] != null){
			$("#longitudine_struttura").val(result['longitude']);
		}
		if(result['locality'] != null){
			$("#località_struttura").val(result['locality']);
		}
		if(result['hamlet'] != null){
			$("#frazione_struttura").val(result['hamlet']);
		}
		if(result['fax'] != null){
			$("#fax_struttura").val(result['fax']);
		}
		if(result['opening period'] != null){
			$("#orari").val(result['opening period']);
		}
		if(result['web site'] != null){
			$("#website_struttura").val(result['web site']);
		}
		if(result['facilities'] != null){
			for(var i = 0; i< result['facilities'].length; i++){
				$("input[name=servizi][value='"+result['facilities'][i]+"']").prop("checked",true);
			}
		}
		if(result['photo'] != null){
			/*$("#InputFile").val(result['latitude']);*/
		}
		if(result['beds'] != null){
			$("#posti_struttura").val(result['beds']);
		}
		if(result['rooms'] != null){
			$("#camere_struttura").val(result['rooms']);
		}
		if(result['suites'] != null){
			$("#suite_struttura").val(result['suites']);
		}
		if(result['facebook'] != null){
			$("#facebook_struttura").val(result['facebook']);
		}
		if(result['instagram'] != null){
			$("#instagram_struttura").val(result['instagram']);
		}
		if(result['twitter'] != null){
			$("#twitter_struttura").val(result['twitter']);
		}
		if(result['languages'] != null){
			for(var i = 0; i< result['languages'].length; i++){
				$("input[name=lingue][value='"+result['languages'][i]+"']").prop("checked",true);
			}
		}
		if(result['category'] != null){
			$("#descrizione_struttura").val(result['category']);
		}
	});
	/*INPUT IMMAGINE*/
	$("#inputFile").change(function () {
		readURL(this);
		$("#NomeFile").html($("#inputFile")[0].files[0]['name']);
		$(".clear").fadeIn();
	});
	$(".clear").click(function(event) {
		event.preventDefault();
		Clear();
	});
	$("#Invia").click(function (){
		var array = {};
		if($("#nome_struttura").val()){
			array['name'] = $("#nome_struttura").val();
		}
		if($("#categoria_struttura").val()){
			array['description'] = $("#categoria_struttura").val();
		}
		if($("#indirizzo_struttura").val()){
			array['address'] = $("#indirizzo_struttura").val();
		}
		if($('input[name=stelle]:checked').val()){
			array['number of stars'] = $('input[name=stelle]:checked').val();
		}
		if($("#email_struttura").val()){
			array['email'] = $("#email_struttura").val();
		}
		if($("#telefono_struttura").val()){
			array['telephone'] = $("#telefono_struttura").val();
		}
		if($("#paese_struttura").val()){
			array['country'] = $("#paese_struttura").val();
		}
		if($("#regione_struttura").val()){
			array['region'] = $("#regione_struttura").val();
		}
		if($("#provincia_struttura").val()){
			array['province'] = $("#provincia_struttura").val();
		}
		if($("#cap_struttura").val()){
			array['postal-code'] = $("#cap_struttura").val();
		}
		if($("#città_struttura").val()){
			array['city'] = $("#città_struttura").val();
		}
		if($("#latitudine_struttura").val()){
			array['latitude'] = $("#latitudine_struttura").val();
		}
		if($("#longitudine_struttura").val()){
			array['longitude'] = $("#longitudine_struttura").val();
		}
		if($("#località_struttura").val()){
			array['locality'] = $("#località_struttura").val();
		}
		if($("#frazione_struttura").val()){
			array['hamlet'] = $("#frazione_struttura").val();
		}
		if($("#fax_struttura").val()){
			array['fax'] = $("#fax_struttura").val();
		}
		if($("#orari").val()){
			array['opening period'] = $("#orari").val(); 
		}
		if($("input[name=servizi]:checked").length > 0){
			array['facilities']=[];
			for(var i=0; i<$("input[name=servizi]:checked").length; i++){
				array['facilities'].push($("input[name=servizi]:checked:eq("+i+")").val());
			}
		}
		if($("#website_struttura").val()){
			array['web site'] = $("#website_struttura").val();
		}
		if($("#posti_struttura").val()){
			array['beds'] = $("#posti_struttura").val();
		}
		if($("#camere_struttura").val()){
			array['rooms'] = $("#camere_struttura").val();
		}
		if($("#suite_struttura").val()){
			array['suites'] = $("#suite_struttura").val();
		}
		if($("#facebook_struttura").val()){
			array['facebook'] = $("#facebook_struttura").val();
		}
		if($("#instagram_struttura").val()){
			array['instagram'] = $("#instagram_struttura").val();
		}
		if($("#twitter_struttura").val()){
			array['twitter'] = $("#twitter_struttura").val();
		}
		if($("#descrizione_struttura").val()){
			array['category'] = $("#descrizione_struttura").val();
		}
		if($("input[name=lingue]:checked").length > 0){
			array['languages']=[];
			for(var i=0; i<$("input[name=lingue]:checked").length; i++){
				array['languages'].push($("input[name=lingue]:checked:eq("+i+")").val());
			}
		}
		var ciao = JSON.stringify(array);
		$.getJSON("../api/modifica.php?ar="+ciao, function (result){
			console.log(result);
		});
	});
});