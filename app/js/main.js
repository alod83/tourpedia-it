//Creo la mappa e le altre variabili globali
var map;
var ResultsS="";
var ResultsA="";
var markersS = new Array();
var markersA = new Array();
var contatori = new Array();
var markerClusterS = null;
var markerClusterA = null;
var paginaS = 1;
var paginaA = 1;
/*INIZIALIZZO LA MAPPA*/
function inizializza(){
	map = new google.maps.Map(document.getElementById('map'), {
	  center: {lat: 42.0, lng: 12.0},
	  zoom: 6,
	  mapTypeControl: false,
	  fullscreenControl: false
	});
	/*SE ATTIVO STREETWIEW LO RENDO VISIBILE*/
	var thePanorama = map.getStreetView();
	google.maps.event.addListener(thePanorama, 'visible_changed', function() {
		if (thePanorama.getVisible()) {
			$("#main").css('z-index','4');
		} else {
			$("#main").css('z-index','0');
		}
	});
	/*CREO I CLUSTER PER I MARKER*/
	markerClusterS = new MarkerClusterer(map);
	markerClusterA = new MarkerClusterer(map);
	/*MEDIA QUERY*/
	$( window ).resize(function() {
		$( ".open" ).css("height", ($(window).height()-186)+"px");
		if(contatori[contatori.length-1]==0){
			if($("#ricercaS").html() == ""){
				if($("#ricercaA").html() == ""){
					$(".navigation").removeClass("out");
					$(".navigation").css("left", "-2000px");
				}
				if($("#ricercaA").html() !== ""){
					$(".navigation").removeClass("out");
					$(".navigation").css("left", "-2000px");
				}
			}else{
				$(".navigation").addClass("out");
				$(".navigation").css("left", "-"+($(".navigation").width())+"px");
			}
		}else{
			$(".navigation").removeClass("out");
			$(".navigation").css("left", "0");
		}
	});
}
//Prende un array in input, e restuisce come output un array con gli elementi del primo, senza doppioni
function Unique(inputArray){
    var temporaryArray = new Array();
	var outputArray = new Array();
    for (var i = 0; i < inputArray.length; i++){
		temporaryArray[inputArray[i]] = true;
	}
	for (var j in temporaryArray){
		outputArray.push(j);
	}
    return outputArray;
}
//Al click del tasto X della scheda delle STRUTTURE, la scheda si chiude e ricompare la lista di strutture ricercata
function ChiudiS(){
	$("#ricercaS").html(ResultsS);
	for(var i = 0; i<markersS.length; i++){
		if(markersS[i].icon == 'images/structMar.png'){
			markersS[i].setIcon('images/bed_b.png');
		}
	}
	Hover();
	Click();
	var options = {
				valueNames: [ 'i' ],
				page: 6,
				innerWindow: 3,
				outerWindow: 3,
				left:3,
				right:3,
				pagination: true
			 };
	listObj = new List('ricercaS', options);
	if(paginaS == 1){
		listObj.show(1,6);
	}else{
		listObj.show((paginaS-1)*7-(paginaS-2),6);
	}
	if(markersS.length <= listObj.page){
		document.getElementById("ricercaS").getElementsByClassName("pagination")[0].style.display = "none";
	}else{
		document.getElementById("ricercaS").getElementsByClassName("pagination")[0].style.display = "block";
	}
	var res = document.getElementById("ricercaS").getElementsByClassName("list")[0].getElementsByClassName("result");
	for(var i=0; i<res.length; i++){
		for(var j=0; j<markersS.length; j++){
			if(res[i].id==markersS[j].title.replace(/ /g,"_")){
			var anteprima = res[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
			anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersS[j].getPosition().lat()+","+markersS[j].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
			}
		}
	}
	listObj.on('updated', function(listObj) {
		var res = document.getElementById("ricercaS").getElementsByClassName("list")[0].getElementsByClassName("result");
		for(var i=0; i<res.length; i++){
			for(var j=0; j<markersS.length; j++){
				if(res[i].id==markersS[j].title.replace(/ /g,"_")){
				var anteprima = res[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
				anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersS[j].getPosition().lat()+","+markersS[j].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
				}
			}
		}
	});
	map.setZoom(10);
};
//Al click del tasto X della scheda delle ATTRAZIONI, la scheda si chiude e ricompare la lista di attrazioni ricercata
function ChiudiA(){
	$("#ricercaA").html(ResultsA);
	for(var i = 0; i<markersA.length; i++){
		if(markersA[i].icon == 'images/attrMar.png'){
			markersA[i].setIcon('images/attraction.png');
		}
	}
	Hover();
	Click();
	var options = {
				valueNames: [ 'i' ],
				page: 6,
				innerWindow: 3,
				outerWindow: 3,
				left:3,
				right:3,
				pagination: true
			 };
	listObj2 = new List('ricercaA', options);
	if(paginaA == 1){
		listObj2.show(1,6);
	}else{
		listObj2.show((paginaA-1)*7-(paginaA-2),6);
	}
	if(markersA.length <= listObj2.page){
		document.getElementById("ricercaA").getElementsByClassName("pagination")[0].style.display = "none";
	}else{
		document.getElementById("ricercaA").getElementsByClassName("pagination")[0].style.display = "block";
	}
	var res = document.getElementById("ricercaA").getElementsByClassName("list")[0].getElementsByClassName("result");
	for(var i=0; i<res.length; i++){
		for(var z=0; z<markersA.length; z++){
			if(res[i].id==markersA[z].title.replace(/ /g,"_")){
				var anteprima = res[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
				anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersA[z].getPosition().lat()+","+markersA[z].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
			}
		}
	}
	listObj2.on('updated', function(listObj2) {
		var res = document.getElementById("ricercaA").getElementsByClassName("list")[0].getElementsByClassName("result");
		for(var i=0; i<res.length; i++){
			for(var z=0; z<markersA.length; z++){
				if(res[i].id==markersA[z].title.replace(/ /g,"_")){
					var anteprima = res[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
					anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersA[z].getPosition().lat()+","+markersA[z].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
				}
			}
		}
	});
	map.setZoom(10);
};
//inserisco la lista dei risultati
function insertResult(){
	var place = document.form_ricerca.ricerca_luogo.value;
	var url= "../api/query.php?category=accommodation&place=";
	if (place=="Trentino-Alto Adige"){
		url+="Trentino";
	}else{
		url+=place;
	}
	ResultsS = "<ul class='list'>";
	$.getJSON(url, function (data) {
		for (i=0; i<data.length; i++){
			if(data[i].latitude && data[i].longitude){
				var stringa="";
				if(data[i].name){
					stringa+="<p>"+data[i].name+"</p>";
				}
				if(data[i].address){
					stringa+="<p>"+data[i].address+"</p>";
				}
				ResultsS += "<li class='result' id="+data[i].name.replace(/ /g,"_")+"><div class='i'><div class='info'><p>"+stringa+"</p></div><div class='anteprima'></div></div></li>";
			}
		}
	});
	ResultsS += "</ul><ul class='pagination'></ul>";
	$("#ricercaS").html(ResultsS);
	ResultsA = "<ul class='list'>";
	$.getJSON("../api/query.php?category=attraction&place="+place, function (data) {
		for (i=0; i<data.length; i++){
			if(data[i].latitude && data[i].longitude){
				var stringa="";
				if(data[i].name){
					stringa+="<p>"+data[i].name+"</p>";
				}
				if(data[i].description){
					stringa+="<p>"+data[i].description+"</p>";
				}
				ResultsA += "<li class='result' id="+data[i].name.replace(/ /g,"_")+"><div class='i'><div class='info'><p>"+stringa+"</p></div><div class='anteprima'></div></div></li>";			
			}
		}
	});
	ResultsA += "</ul><ul class='pagination'></ul>";
	$("#ricercaA").html(ResultsA);
}

/*FUNZIONE HOVER SULLA LISTA*/
function Hover(){
	$(".result").hover(
		function(){
			for(var i = 0; i<markersA.length; i++){
				if(this.id==markersA[i].title.replace(/ /g,"_")){
					if(markersA[i].icon == 'images/attraction.png'){
						markersA[i].setIcon('images/attrMar.png');
					}
				}
			}
			for(var j = 0; j<markersS.length; j++){
				if(this.id==markersS[j].title.replace(/ /g,"_")){
					if(markersS[j].icon == 'images/bed_b.png'){
						markersS[j].setIcon('images/structMar.png');
					}
				}
			}
		},
		function(){
			for(var i = 0; i<markersA.length; i++){
				if(this.id==markersA[i].title.replace(/ /g,"_")){
					if(markersA[i].icon == 'images/attrMar.png'){
						markersA[i].setIcon('images/attraction.png');
					}
				}
			}
			for(var j = 0; j<markersS.length; j++){
				if(this.id==markersS[j].title.replace(/ /g,"_")){
					if(markersS[j].icon == 'images/structMar.png'){
						markersS[j].setIcon('images/bed_b.png');
					}
				}
			}
		}
	)
}
/*FUNZIONE CLICK SULLA LISTA*/
function Click(){
	$(".result").click(
		function(){
			if($("#ricercaS").html() !== ""){
				if(document.getElementById("ricercaS").firstChild.className == "list"){
					if(document.getElementById("ricercaS").getElementsByClassName("list")[0].innerHTML !== ""){
						paginaS = parseInt(document.getElementById("ricercaS").getElementsByClassName("pagination")[0].getElementsByClassName("active")[0].getElementsByClassName("page")[0].innerHTML);
					}
				}
			}
			if($("#ricercaA").html() !== ""){
				if(document.getElementById("ricercaA").firstChild.className == "list"){
					if(document.getElementById("ricercaA").getElementsByClassName("list")[0].innerHTML !== ""){
						paginaA = parseInt(document.getElementById("ricercaA").getElementsByClassName("pagination")[0].getElementsByClassName("active")[0].getElementsByClassName("page")[0].innerHTML);
					}
				}
			}
			if(map.getZoom() < 17){
				map.setZoom(17);
			}
			for(var i = 0; i<markersA.length; i++){
				if(this.id==markersA[i].title.replace(/ /g,"_")){
					if(markersA[i].icon == 'images/attrMar.png'){
						$("#ricercaA").html(markersA[i].content);
						map.setCenter(markersA[i].position);
					}
				}
			}
			for(var j = 0; j<markersS.length; j++){
				if(this.id==markersS[j].title.replace(/ /g,"_")){
					if(markersS[j].icon == 'images/structMar.png'){
						$("#ricercaS").html(markersS[j].content);
						map.setCenter(markersS[j].position);
					}
				}
			}
		}
	)
}
/*TOGLIE I SEGNALINI, CANCELLA L'ARRAY markers SE ESISTENTE E LO RICREA VUOTO PER UNA NUOVA RICERCA*/
function clearMarkers() {
    // Clear all markers
    for (var i = 0; i < markersS.length; i++) {
        markersS[i].setMap(null);
    }
	for (var j = 0; j < markersA.length; j++) {
		markersA[j].setMap(null);
	}		
	markersS.length = 0;
	markersA.length = 0;
    // Recreate the Markers array
	markerClusterS.clearMarkers();
	markerClusterA.clearMarkers();
}
/*CREA I SEGNALINI SULLA MAPPA, STRUTTURE E ATTRAZIONI, IN BASE AL PARAMETRO PASSATO NEL CAMPO DI RICERCA*/
function createMarker(map){
	var place = document.form_ricerca.ricerca_luogo.value;
	if (place){
		var url= "../api/query.php?category=accommodation&place=";
		if (place=="Trentino-Alto Adige"){
			url+="Trentino";
		}else{
			url+=place;
		}
		$.getJSON(url, function (data) {
			if(data.length != 0){
				/*var correctData=0;*/
				for (i=0; i<data.length; i++){
					if(data[i].latitude && data[i].longitude){
						if(place==data[i].region){
							map.setZoom(8);
						}else{
							map.setZoom(10);
						}
						var string="<div id='schedaS'><form><input class='chiudi' type='image' src='images/chiudiS.svg' onclick='ChiudiS()'></form>";
						coordinate = {lat: data[i].latitude, lng: data[i].longitude};
						map.setCenter({ lat : data[0].latitude , lng : data[0].longitude });
						string+="<div class='foto'><img src='https://maps.googleapis.com/maps/api/streetview?size=480x250&location="+data[i].latitude+","+data[i].longitude+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'></div>"
						string+="<div id='header1' class='header'>";
						if(data[i].name){
							string+="<p class='name'>"+data[i].name+"</p>";
						}
						if(data[i].stars){
							for(var y=0; y<data[i].stars; y++){
								string+="<img class='star' src='images/star.svg'>";
							}
							string+="<br/>";
						}
						if(data[i].description){
							string+="<p>Tipo di struttura: "+data[i].description+"</p>";
						}
						string+="</div><div class='contenuto'>";
						string+="<img class='icons' src='images/address.svg' alt='address'><p class='infoscheda'> ";
						if(data[i].address){
							string+=data[i].address+", ";
						}
						if(data[i].city){
							string+=data[i].city;
						}
						if(data[i].province){
							string+=" ("+data[i].province+")";
						}
						string+="</p></br>";
						if(data[i].telephone){
							string+="<img class='icons' src='images/telephone.svg' alt='telephone'><p class='infoscheda'> <a href='tel:"+data[i].telephone+"'>"+data[i].telephone+"</a></p></br>";
						}
						if(data[i].email){
							string+="<img class='icons' src='images/mail.svg' alt='email'><p class='infoscheda'> <a href='mailto:"+data[i].email+"'>"+data[i].email+"</a></p></br>";
						}
						if(data[i]['web site']){
							string+="<img class='icons' src='images/internet.svg' alt='internet'><p class='infoscheda'> <a class='website' href='http://"+data[i]['web site']+"'>"+data[i]['web site']+"</a></p></br>";
						}
						string+="</div></div>"
						var marker = new google.maps.Marker({
							position: coordinate,
							map: map,
							icon: 'images/bed_b.png',
							title: data[i].name,
							content: string
						});
						/*var infowindow = new google.maps.InfoWindow({ 
							position: coordinate,
							content: data[i].name+"<br/>"+data[i].description+"<br/>"+data[i].address+data[i].city+"("+data[i].province+")"+"<br/>"+data[i].telephone+"<br/>"+data[i].email,
							size: new google.maps.Size(50,50)
						});*/
						google.maps.event.addListener(marker, 'click', function(event) {
							/*infowindow.setContent(this.content);
							infowindow.open(map, this);*/
							$("#ricercaS").html(this.content);
							$(".navigation").removeClass("out");
							$('.navigation').css('left','0');
							$('#arrow').attr("src","images/left arrow.svg");
							contatori[contatori.length-1]=1;
							$(".expand:eq(0)").attr("src","images/up.png");
							$(".campi_ricerca:eq(0)").addClass("campi_ricerca open");
							$(".campi_ricerca:eq(0)").css("height", ($(window).height()-186)+"px");
							contatori[0]=1;
							$(".expand:eq(1)").attr("src","images/down.png");
							$(".campi_ricerca:eq(1)").removeClass("open");
							$(".campi_ricerca:eq(1)").css("height", "0");
							contatori[1]=0;
							$("#background").css("background-color","#0000ff");
							if(map.getZoom() < 17){
								map.setZoom(17);
							}
							map.setCenter(this.position);
						});
						markersS.push(marker);
					}
				}
				if (markersS.length != 0){
					$("#ns").html("("+markersS.length+")");
					if(window.matchMedia("(max-width: 1156px)").matches){
						map.setCenter({lat: markersS[0].getPosition().lat(), lng: markersS[0].getPosition().lng()-0.3});
					}else{
						map.setCenter(markersS[0].position);
					}
				}else{
					$("#ns").html("(Nessun risultato trovato)");
				}
			}else{
				$("#ns").html("(Nessun risultato trovato)");
			}
		});
		$.getJSON("../api/query.php?category=attraction&place="+place, function (data) {
			if(data.length != 0){
				for (i=0; i<data.length; i++){
					if(data[i].latitude && data[i].longitude){
						coordinate = {lat: data[i].latitude, lng: data[i].longitude};
						if(place==data[i].region){
							map.setZoom(8);
						}else{
							map.setZoom(10);
						}
						var string="<div id='schedaA'><form><input class='chiudi' type='image' src='images/chiudiA.svg' onclick='ChiudiA()'></form>";
						string+="<div class='foto'><img src='https://maps.googleapis.com/maps/api/streetview?size=480x250&location="+data[i].latitude+","+data[i].longitude+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'></div>"
						string+="<div id='header2' class='header'>";
						if(data[i].name){
							string+="<p class='name'>"+data[i].name+"</p>";
						}
						if(data[i].category){
							string+="<p>Tipo di attrazione: "+data[i].category+"</p>";
						}
						if(data[i].description){
							string+="<p>Categoria: "+data[i].description+"</p>";
						}
						string+="</div><div class='contenuto'>"
						string+="<img class='icons' src='images/address1.svg' alt='address'><p class='infoscheda'> ";
						if(data[i].address){
							string+=data[i].address+", ";
						}
						if(data[i].city){
							string+=data[i].city;
						}
						if(data[i].province){
							string+=" ("+data[i].province+")";
						}
						string+="</p></br>"
						if(data[i].telephone){
							string+="<img class='icons' src='images/telephone1.svg' alt='telephone'><p class='infoscheda'> <a href='tel:"+data[i].telephone+"'>"+data[i].telephone+"</a></p></br>";
						}
						if(data[i].email){
							string+="<img class='icons' src='images/mail1.svg' alt='email'><p class='infoscheda'> <a href='mailto:"+data[i].email+"'>"+data[i].email+"</a></p></br>";
						}
						if(data[i].url){
							string+="<img class='icons' src='images/internet1.svg' alt='internet'><p class='infoscheda'> <a class='website' href='http://"+data[i].url+"'>";
							if(data[i].url.indexOf(".com") !== -1){
								if(data[i].url.indexOf(".comune") == -1){
									string+=data[i].url.split(".com")[0]+".com";
								}else{
									string+=data[i].url;
								}
							}else if(data[i].url.indexOf(".net") !== -1){
								string+=data[i].url.split(".net")[0]+".net";
							}else if(data[i].url.indexOf(".it") !== -1){
								string+=data[i].url.split(".it")[0]+".it";
							}else if(data[i].url.indexOf(".org") !== -1){
								string+=data[i].url.split(".org")[0]+".org";
							}else{
								string+=data[i].url;
							}
							string+="</a></p></br>";
						}
						string+="</div></div>"
						var marker = new google.maps.Marker({
							position: coordinate,
							map: map,
							icon: 'images/attraction.png',
							title: data[i].name,
							content: string
						});
						/*var infowindow = new google.maps.InfoWindow({ 
							position: coordinate,
							content: data[i].name+"<br/>"+data[i].description+"<br/>"+data[i].address+data[i].city+"("+data[i].province+")"+"<br/>"+data[i].telephone+"<br/>"+data[i].email,
							size: new google.maps.Size(50,50)
						});*/
						google.maps.event.addListener(marker, 'click', function(event) {
							//infowindow.setContent(this.content);
							//infowindow.open(map, this);
							$("#ricercaA").html(this.content);
							$(".navigation").removeClass("out");
							$('.navigation').css('left','0');
							$('#arrow').attr("src","images/left arrow.svg");
							contatori[contatori.length-1]=1;
							$(".expand:eq(1)").attr("src","images/up.png");
							$(".campi_ricerca:eq(1)").addClass("campi_ricerca open");
							$(".campi_ricerca:eq(1)").css("height", ($(window).height()-186)+"px");
							contatori[1]=1;
							$(".expand:eq(0)").attr("src","images/down.png");
							$(".campi_ricerca:eq(0)").removeClass("open");
							$(".campi_ricerca:eq(0)").css("height", "0");
							contatori[0]=0;
							$("#background").css("background-color","red");
							if(map.getZoom() < 17){
								map.setZoom(17);
							}
							map.setCenter(this.position);
						});
						markersA.push(marker);
					}
				}
				if(markersA.length != 0){
					$("#na").html("("+markersA.length+")");
					if(window.matchMedia("(max-width: 1156px)").matches){
						map.setCenter({lat: markersA[0].getPosition().lat(), lng: markersA[0].getPosition().lng()-0.3});
					}else{
						map.setCenter(markersA[0].position);
					}
				}else{
					$("#na").html("(Nessun risultato trovato)");
				}
			}else{
				$("#na").html("(Nessun risultato trovato)");
			}
		});
	}
}
/*AL CARICAMENTO DELLA PAGINA, CREA LA MAPPA, ,CREA L'EVENTO CLICK DEL TASTO CERCA,E QUELLI RELATIVI AL TASTO X*/
$(document).ready(function() {
	/*NASCONDO IL TASTO X DELLA RICERCA TESTUALE*/
	$("#ricerca_luogo").val("");
	$("#x").hide();
	/*INIZIALIZZO I CONTATORI PER L'APERTURA E LA CHIUSURA DEI BOX DI RICERCA*/
	for (var i=0; i<$(".expand").length; i++){
		contatori[i]=0;
	}
	contatori.push(0);
	/*RICERCA SE PREMO INVIO*/
	$("#ricerca_luogo").keypress(function(e) {
		if (e.keyCode == 13){
			e.preventDefault();
			$('#ui-id-1').fadeOut();
			insertResult();
			clearMarkers();
			createMarker(map);
			markerClusterS = new MarkerClusterer(map, markersS,
				{imagePath: 'images/m'}
			);
			markerClusterA = new MarkerClusterer(map, markersA,
				{imagePath: 'images/g'}
			);
			Hover();
			Click();
			var options = {
				valueNames: [ 'i' ],
				page: 6,
				innerWindow: 3,
				outerWindow: 3,
				left:3,
				right:3,
				pagination: true
			 };
			 
			var listObj = new List('ricercaS', options);
			if(markersS.length <= listObj.page){
				document.getElementById("ricercaS").getElementsByClassName("pagination")[0].style.display = "none";
			}else{
				document.getElementById("ricercaS").getElementsByClassName("pagination")[0].style.display = "block";
			}
			var listObj2 = new List('ricercaA', options);
			if(markersA.length <= listObj2.page){
				document.getElementById("ricercaA").getElementsByClassName("pagination")[0].style.display = "none";
			}else{
				document.getElementById("ricercaA").getElementsByClassName("pagination")[0].style.display = "block";
			}
			var res = document.getElementsByClassName("result");
			for(var i=0; i<res.length; i++){
				for(var j=0; j<markersS.length; j++){
					if(res[i].id==markersS[j].title.replace(/ /g,"_")){
						var anteprima = res[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
						anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersS[j].getPosition().lat()+","+markersS[j].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
					}
				}
				for(var z=0; z<markersA.length; z++){
					if(res[i].id==markersA[z].title.replace(/ /g,"_")){
						var anteprima = res[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
						anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersA[z].getPosition().lat()+","+markersA[z].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
					}
				}
			}
			listObj.on('updated', function(listObj) {
				var res = document.getElementById("ricercaS").getElementsByClassName("list")[0].getElementsByClassName("result");
					for(var i=0; i<res.length; i++){
						for(var j=0; j<markersS.length; j++){
							if(res[i].id==markersS[j].title.replace(/ /g,"_")){
							var anteprima = res[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
							anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersS[j].getPosition().lat()+","+markersS[j].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
							}
						}
					}
			});
			listObj2.on('updated', function(listObj2) {
				var res = document.getElementById("ricercaA").getElementsByClassName("list")[0].getElementsByClassName("result");
				for(var i=0; i<res.length; i++){
					for(var z=0; z<markersA.length; z++){
						if(res[i].id==markersA[z].title.replace(/ /g,"_")){
							var anteprima = res[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
							anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersA[z].getPosition().lat()+","+markersA[z].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
						}
					}
				}
			});
			$(".navigation").removeClass("out");
			$('.navigation').css('left','0');
			contatori[contatori.length-1]=1;
			$('#arrow').attr("src","images/left arrow.svg");
			if($(".list:eq("+0+")").html() !== ""){
				$(".expand:eq("+0+")").attr("src","images/up.png");
				$(".campi_ricerca:eq("+0+")").addClass("campi_ricerca open");
				$(".campi_ricerca:eq("+0+")").css("height", ($(window).height()-186)+"px");
				contatori[0]=1;
				$(".expand:eq("+1+")").attr("src","images/down.png");
				$(".campi_ricerca:eq("+1+")").removeClass("open");
				$(".campi_ricerca:eq("+1+")").css("height", "0");
				contatori[1]=0;
				$("#background").css("background-color","#0000ff");
			}else if($(".list:eq("+1+")").html() !== ""){
				$(".expand:eq("+1+")").attr("src","images/up.png");
				$(".campi_ricerca:eq("+1+")").addClass("campi_ricerca open");
				$(".campi_ricerca:eq("+1+")").css("height", ($(window).height()-186)+"px");
				contatori[1]=1;
				$(".expand:eq("+0+")").attr("src","images/down.png");
				$(".campi_ricerca:eq("+0+")").removeClass("open");
				$(".campi_ricerca:eq("+0+")").css("height", "0");
				contatori[0]=0;
				$("#background").css("background-color","red");
			}else{
				$(".expand:eq("+0+")").attr("src","images/down.png");
				$(".campi_ricerca:eq("+0+")").removeClass("open");
				$(".campi_ricerca:eq("+0+")").css("height", "0");
				contatori[0]=0;
				$(".expand:eq("+1+")").attr("src","images/down.png");
				$(".campi_ricerca:eq("+1+")").removeClass("open");
				$(".campi_ricerca:eq("+1+")").css("height", "0");
				contatori[1]=0;
				$("#background").css("background-color","#0000ff");
			}
			console.log(markersA,markersS);
		}
	});
	/*CREO L'EVENTO DELLA RICERCA TESTUALE*/
	$("#cerca").click(function(event) {
		event.preventDefault();
		$('#ui-id-1').fadeOut();
		insertResult();
		clearMarkers();
		createMarker(map);
		markerClusterS = new MarkerClusterer(map, markersS,
			{imagePath: 'images/m'}
		);
		markerClusterA = new MarkerClusterer(map, markersA,
			{imagePath: 'images/g'}
		);
		Hover();
		Click();
		var options = {
				valueNames: [ 'i' ],
				page: 6,
				innerWindow: 3,
				outerWindow: 3,
				left:3,
				right:3,
				pagination: true
			 };

		var listObj = new List('ricercaS', options);
		if(markersS.length <= listObj.page){
			document.getElementById("ricercaS").getElementsByClassName("pagination")[0].style.display = "none";
		}else{
			document.getElementById("ricercaS").getElementsByClassName("pagination")[0].style.display = "block";
		}
		var listObj2 = new List('ricercaA', options);
		if(markersA.length <= listObj2.page){
			document.getElementById("ricercaA").getElementsByClassName("pagination")[0].style.display = "none";
		}else{
			document.getElementById("ricercaA").getElementsByClassName("pagination")[0].style.display = "block";
		}
		var res = document.getElementsByClassName("result");
		for(var i=0; i<res.length; i++){
			for(var j=0; j<markersS.length; j++){
				if(res[i].id==markersS[j].title.replace(/ /g,"_")){
					var anteprima = res[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
					anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersS[j].getPosition().lat()+","+markersS[j].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
				}
			}
			for(var z=0; z<markersA.length; z++){
				if(res[i].id==markersA[z].title.replace(/ /g,"_")){
					var anteprima = res[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
					anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersA[z].getPosition().lat()+","+markersA[z].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
				}
			}
		}
		listObj.on('updated', function(listObj) {
			var res = document.getElementById("ricercaS").getElementsByClassName("list")[0].getElementsByClassName("result");
			for(var i=0; i<res.length; i++){
				for(var j=0; j<markersS.length; j++){
					if(res[i].id==markersS[j].title.replace(/ /g,"_")){
					var anteprima = res[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
					anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersS[j].getPosition().lat()+","+markersS[j].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
					}
				}
			}
		});
		$(".navigation").removeClass("out");
		$('.navigation').css('left','0');
		contatori[contatori.length-1]=1;
		$('#arrow').attr("src","images/left arrow.svg");
		if($(".list:eq("+0+")").html() !== ""){
			$(".expand:eq("+0+")").attr("src","images/up.png");
			$(".campi_ricerca:eq("+0+")").addClass("campi_ricerca open");
			$(".campi_ricerca:eq("+0+")").css("height", ($(window).height()-186)+"px");
			contatori[0]=1;
			$(".expand:eq("+1+")").attr("src","images/down.png");
			$(".campi_ricerca:eq("+1+")").removeClass("open");
			$(".campi_ricerca:eq("+1+")").css("height", "0");
			contatori[1]=0;
			$("#background").css("background-color","#0000ff");
		}else if($(".list:eq("+1+")").html() !== ""){
			$(".expand:eq("+1+")").attr("src","images/up.png");
			$(".campi_ricerca:eq("+1+")").addClass("campi_ricerca open");
			$(".campi_ricerca:eq("+1+")").css("height", ($(window).height()-186)+"px");
			contatori[1]=1;
			$(".expand:eq("+0+")").attr("src","images/down.png");
			$(".campi_ricerca:eq("+0+")").removeClass("open");
			$(".campi_ricerca:eq("+0+")").css("height", "0");
			contatori[0]=0;
			$("#background").css("background-color","red");
		}else{
			$(".expand:eq("+0+")").attr("src","images/down.png");
			$(".campi_ricerca:eq("+0+")").removeClass("open");
			$(".campi_ricerca:eq("+0+")").css("height", "0");
			contatori[0]=0;
			$(".expand:eq("+1+")").attr("src","images/down.png");
			$(".campi_ricerca:eq("+1+")").removeClass("open");
			$(".campi_ricerca:eq("+1+")").css("height", "0");
			contatori[1]=0;
			$("#background").css("background-color","#0000ff");
		}
	});
	// if text input field value is not empty show the "X" button
	$("#ricerca_luogo").keyup(
		function() 
		{
			$("#x").fadeIn();
			if ($.trim($("#ricerca_luogo").val()) == "") 
			{
		 	   $("#x").fadeOut();
			}
		}
	);
	/*AL CLICK DELLA X, CANCELLO IL CONTENUTO DELLA RICERCA TESTUALE E NASCONDO IL TASTO X*/
	// on click of "X", delete input field value and hide "X"
	$("#x").click(function(event) {
			event.preventDefault();
			$("#ricerca_luogo").val("");
			$(this).hide();
		}
	);
	/*AL CLICK DEL TASTO INDICATO DALLA FRECCIA, APRO E CHIUDO IL DIV DI NAVIGAZIONE*/
	$("#tasto").click(function(){
		if(contatori[contatori.length-1]==0){
			console.log("miao");
			$(".navigation").removeClass("out");
			$('.navigation').css('left','0');
			$('#arrow').attr("src","images/left arrow.svg");
			contatori[contatori.length-1]=1;
		}else if(contatori[contatori.length-1]==1){
			console.log("ciao");
			$(".navigation").css("left", "-"+($(".navigation").width())+"px");
			$('#arrow').attr("src","images/right arrow.svg");
			contatori[contatori.length-1]=0;
		}
	});
	/* AL CLICK DEL TASTO DI ESPANSIONE, APRO IL CORRISPONDENTE BOX DI RICERCA*/
	$(".argument").click(function(){
		var value = this.getElementsByClassName("expand")[0].value;
		if(contatori[value]==0){
			if($(".list:eq("+value+")").html() !== ""){
				if(value==0){
					if($(".list:eq("+1+")").html() !== ""){
						$(".expand:eq("+1+")").attr("src","images/down.png");
						$(".campi_ricerca:eq("+1+")").removeClass("open");
						$(".campi_ricerca:eq("+1+")").css("height", "0");
						contatori[1]=0;
						$(".expand:eq("+value+")").attr("src","images/up.png");
						$(".campi_ricerca:eq("+value+")").addClass("campi_ricerca open");
						$(".campi_ricerca:eq("+value+")").css("height", ($(window).height()-186)+"px");
						contatori[value]=1;
						$("#background").css("background-color","#0000ff");
						console.log("blu");
					}
				}else if(value==1){
					if($(".list:eq("+0+")").html() !== ""){
						$(".expand:eq("+0+")").attr("src","images/down.png");
						$(".campi_ricerca:eq("+0+")").removeClass("open");
						$(".campi_ricerca:eq("+0+")").css("height", "0");
						contatori[0]=0;
						$(".expand:eq("+value+")").attr("src","images/up.png");
						$(".campi_ricerca:eq("+value+")").addClass("campi_ricerca open");
						$(".campi_ricerca:eq("+value+")").css("height", ($(window).height()-186)+"px");
						contatori[value]=1;
						$("#background").css("background-color","red");
						console.log("rosso");
					}
				}
			}else{
				$(".expand:eq("+value+")").attr("src","images/down.png");
				$(".campi_ricerca:eq("+value+")").removeClass("open");
				$(".campi_ricerca:eq("+value+")").css("height", "0");
				contatori[value]=0;
			}
		}else if(contatori[value]==1){
			if($(".list:eq("+value+")").html() !== ""){
				if(value==0){
					if($(".list:eq("+1+")").html() !== ""){
						$(".expand:eq("+1+")").attr("src","images/up.png");
						$(".campi_ricerca:eq("+1+")").addClass("campi_ricerca open");
						$(".campi_ricerca:eq("+1+")").css("height", ($(window).height()-186)+"px");
						contatori[1]=1;
						$(".expand:eq("+value+")").attr("src","images/down.png");
						$(".campi_ricerca:eq("+value+")").removeClass("open");
						$(".campi_ricerca:eq("+value+")").css("height", "0");
						contatori[value]=0;
						$("#background").css("background-color","red");
						console.log("rosso");
					}else{
						$(".expand:eq("+1+")").attr("src","images/down.png");
						$(".campi_ricerca:eq("+1+")").removeClass("open");
						$(".campi_ricerca:eq("+1+")").css("height", "0");
						contatori[1]=0;
					}
				}else if(value==1){
					if($(".list:eq("+0+")").html() !== ""){
						$(".expand:eq("+0+")").attr("src","images/up.png");
						$(".campi_ricerca:eq("+0+")").addClass("campi_ricerca open");
						$(".campi_ricerca:eq("+0+")").css("height", ($(window).height()-186)+"px");
						contatori[0]=1;
						$(".expand:eq("+value+")").attr("src","images/down.png");
						$(".campi_ricerca:eq("+value+")").removeClass("open");
						$(".campi_ricerca:eq("+value+")").css("height", "0");
						contatori[value]=0;
						$("#background").css("background-color","#0000ff");
						console.log("blu");
					}else{
						$(".expand:eq("+0+")").attr("src","images/down.png");
						$(".campi_ricerca:eq("+0+")").removeClass("open");
						$(".campi_ricerca:eq("+0+")").css("height", "0");
						contatori[0]=0;
					}
				}
			}
		}
	});
	/*SUGGERIMENTI RICERCA TESTUALE*/
	$( function() {
		$.ajaxSetup({
			async: false
		});
		var Tags=new Array();
		$.getJSON("../api/query.php?category=accommodation", function (data) {
			for(var i=0; i<data.length; i++){
				if(data[i].latitude && data[i].longitude){
					Tags.push(data[i].region);
					Tags.push(data[i].city);
				}
			}
		});
		$.getJSON("../api/query.php?category=attraction", function (data) {
			for(var i=0; i<data.length; i++){
				if(data[i].latitude && data[i].longitude){
					Tags.push(data[i].region);
					Tags.push(data[i].city);
				}
			}
		});
		var availableTags = Unique(Tags);
		$( "#ricerca_luogo" ).autocomplete({
			minLength: 2,
			source: function(req, responseFn) {
				var re = $.ui.autocomplete.escapeRegex(req.term);
				var matcher = new RegExp( "^" + re, "i" );
				var a = $.grep( availableTags, function(item,index){
					return matcher.test(item);
				});
				responseFn( a );
			}
		});
	});
});