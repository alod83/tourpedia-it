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

function Logout(){
	$.getJSON("../api/logout.php", function (result){
		if(result == "SESSION CLOSED"){
			$(location).attr('href', "..\/app\/hotel.html");
		}
	});
}

function hasExtension(inputID, exts) {
    var fileName = document.getElementById(inputID).value;
	return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$', "i")).test(fileName);
}
var array = {};
$(document).ready(function(){
	/*LOGOUT*/
	$("#logout").click(function(){
		Logout();
	});
	/*INTRODUCO LA STRUTTURA DALLA SESSIONE*/
	$.getJSON("../api/struttura_scelta.php", function (result){
		if(result["_id"]){
			var s="<img src='images/profile.svg' alt='utente'><p>"+result["_id"]+"</p>";
			$("#utente").html(s);
		}
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
			$('#image_upload_preview').attr('src', result['photo']);
			$('#NomeFile').html(result['photo'].replace('photos/',''));
			$(".clear").fadeIn();
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
	/*LOGOUT*/
	/*INPUT IMMAGINE*/
	$("#inputFile").change(function () {
		if(hasExtension('inputFile',['.jpg', '.png', '.jpeg'])){
			readURL(this);
			$('#img_msg').html("");
			$("#NomeFile").html($("#inputFile")[0].files[0]['name']);
			$(".clear").fadeIn();
		}else{
			Clear();
			$('#img_msg').html("Estensione non valida");
		}
	});
	$(".clear").click(function(event) {
		event.preventDefault();
		Clear();
	});
	$("#Invia").click(function (){
		if($("input[name=autorizzazione]:checked").length > 0){
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
			var form = new FormData();
			var myFormData = document.getElementById('inputFile').files[0]; //get the file 
			if (myFormData) {   //Check the file is emty or not
				form.append('inputFile', myFormData); //append files
			}
			if($('#inputFile').val() != null){
				if (hasExtension('inputFile',['.jpg', '.png', '.jpeg'])) {
					$.ajax({
						type: 'POST',               
						processData: false,
						contentType: false, 
						data: form,
						url: "../api/photo.php", //My reference URL
						dataType : 'json',  
						success: function(jsonData){
							if(jsonData == 1){
								$('#ok_img').html("Immagine salvata correttamente");
							}else{
								$('#ok_img').html("Errore nel caricamento dell'immagine");
							}
						}
					});
				}
			}
			var stringa = JSON.stringify(array);
			$.getJSON("../api/modifica.php?ar="+stringa, function (result){
				if(result.length > 0){
					$('#ok_data').html("Dati caricati correttamente");
					$(location).attr('href', "..\/app\/statistiche.html");
				}
			});
		}else{
			$('#ok_data').html("Acconsenti al trattamento dei dati personali per salvare le modifiche");
		}
	});
});