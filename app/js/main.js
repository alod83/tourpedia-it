//Creo la mappa e le altre variabili globali
var map;
var ResultsS="";
var ResultsA="";
var markersS = [];
var markersA = [];
var contatori = [];
var markerClusterS = null;
var markerClusterA = null;
var paginaS = 1;
var paginaA = 1;
var infowindowF;
var infowindowI;
var infowindowE;
/*FUNZIONE INIZIALIZZA*/
function inizializza(){
	/*Inizializzo la mappa*/
	map = new google.maps.Map(document.getElementById('map'), {
		center: {lat: 43.194351, lng: 6.240234},
		zoom: 5,
		mapTypeControl: false,
		fullscreenControl: false,
		styles: [
			{
				"featureType": "administrative.land_parcel",
				"elementType": "labels",
				"stylers": [
					{
						"visibility": "off"
					}
				]
			},
			{
				"featureType": "poi",
				"elementType": "labels.text",
				"stylers": [
					{
						"visibility": "off"
					}
				]
			},
			{
				"featureType": "poi.business",
				"stylers": [
					{
						"visibility": "off"
					}
				]
			},
			{
				"featureType": "road",
				"elementType": "labels.icon",
				"stylers": [
					{
						"visibility": "off"
					}
				]
			},
			{
				"featureType": "road.local",
				"elementType": "labels",
				"stylers": [
					{
						"visibility": "off"
					}
				]
			},
			{
				"featureType": "transit",
				"stylers": [
					{
						"visibility": "off"
					}
				]
			}
		]
	});
	/*Creo gli indicatori delle strutture e delle attrazioni per ogni nazione e li inserisco nella mappa*/
	var markerF = new google.maps.Marker({
		position: {lat: 46.227638, lng: 2.213749 },
		map: map,
		icon: "images/null.png",
		title: 'Francia'
	});
	var markerI = new google.maps.Marker({
		position: {lat: 41.871940, lng: 12.56738 },
		map: map,
		icon: "images/null.png",
		title: 'Italia'
	});
	var markerE = new google.maps.Marker({
		position: {lat: 40.463667, lng: -3.74922 },
		map: map,
		icon: "images/null.png",
		title: 'Spagna'
	});
	var Francia=new Array(0,0);
	var Italia=new Array(0,0);
	var Spagna=new Array(0,0);
	$.getJSON("../api/count.php?category=accommodation&country=France&not_null_latitude&not_null_longitude&min_latitude=1", function (result) {
		Francia[0] = result;
		$.getJSON("../api/count.php?category=attraction&country=France&not_null_latitude&not_null_longitude&min_latitude=1", function (result) {
			Francia[1] = result;
			infowindowF = new google.maps.InfoWindow({ 
				content: "<h1>Francia</h1><div class='infowindows'><img class='icons' src='images/structure.png'><p class='n1'> Strutture: "+Francia[0]+"</p></br><img class='icons' src='images/attr.png'><p class='n1'> Attrazioni: "+Francia[1]+"</p></div>",
			});
			infowindowF.open(map, markerF);
		});
	});
	$.getJSON("../api/count.php?category=accommodation&country=Italy&not_null_latitude&not_null_longitude&min_latitude=1", function (result) {
		Italia[0] = result;
		$.getJSON("../api/count.php?category=attraction&country=Italy&not_null_latitude&not_null_longitude&min_latitude=1", function (result) {
			Italia[1] = result;
			infowindowI = new google.maps.InfoWindow({ 
				content: "<h1>Italia</h1><div class='infowindows'><img class='icons' src='images/structure.png'><p class='n1'> Strutture: "+Italia[0]+"</p></br><img class='icons' src='images/attr.png'><p class='n1'> Attrazioni: "+Italia[1]+"</p></div>",
			});
			infowindowI.open(map, markerI);
		});
	});
	$.getJSON("../api/count.php?category=accommodation&country=Spain&not_null_latitude&not_null_longitude&min_latitude=1", function (result) {
		Spagna[0] = result;
		$.getJSON("../api/count.php?category=attraction&country=Spain&not_null_latitude&not_null_longitude&min_latitude=1", function (result) {
			Spagna[1] = result;
		});
		infowindowE = new google.maps.InfoWindow({
			content: "<h1>Spagna</h1><div class='infowindows'><img class='icons' src='images/structure.png'><p class='n1'> Strutture: "+Spagna[0]+"</p></br><img class='icons' src='images/attr.png'><p class='n1'> Attrazioni: "+Spagna[1]+"</p></div>",
		});
		infowindowE.open(map, markerE);
	});
	/*Se attivo Streetwiew lo rendo visibile*/
	var thePanorama = map.getStreetView();
	google.maps.event.addListener(thePanorama, 'visible_changed', function() {
		if (thePanorama.getVisible()) {
			$("#main").css('z-index','4');
		} else {
			$("#main").css('z-index','0');
		}
	});
	/*Creo i cluster per i marker*/
	markerClusterS = new MarkerClusterer(map);
	markerClusterA = new MarkerClusterer(map);
	/*Media query*/
	$( window ).resize(function() {
		$( ".open" ).css("height", ($(window).height()-196)+"px");
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
//AL CLICK DEL TASTO X DELLA SCHEDA DELLE STRUTTURE, LA SCHEDA SI CHIUDE E RICOMPARE LA LISTA DI STRUTTURE RICERCATA
function ChiudiS(){
	/*Reinserisco la lista di strutture ricercata*/
	$("#ricercaS").html(ResultsS);
	/*Tolgo, se presenti, eventuali marker selezionati*/
	for(var z = 0; z<markersS.length; z++){
		if(markersS[z].icon == 'images/structure_selected.png'){
			markersS[z].setIcon('images/bed_b.png');
		}
	}
	/*Inserisco la funzione di hover sulle strutture*/
	Hover();
	/*Inserisco la funzione di click sulle strutture*/
	$(".structure").click(
		function(){
			ClickS(this.id);
		}
	);
	/*Ricreo l'impaginazione della lista delle strutture*/
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
	}else if(paginaS != 1){
		listObj.show((paginaS-1)*7-(paginaS-2),6);
	}
	if(markersS.length <= listObj.page){
		document.getElementById("ricercaS").getElementsByClassName("pagination")[0].style.display = "none";
	}else if(markersS.length > listObj.page){
		document.getElementById("ricercaS").getElementsByClassName("pagination")[0].style.display = "block";
	}
	var res = document.getElementById("ricercaS").getElementsByClassName("list")[0].getElementsByClassName("result");
	for(var i=0; i<res.length; i++){
		for(var j=0; j<markersS.length; j++){
			if(res[i].id==markersS[j].title){
				var anteprima = res[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
				$.getJSON("../api/query.php?category=accommodation&_id="+res[i].id+"&not_null_latitude&not_null_longitude&min_latitude=1", function (result){
					var data = $.map(result, function(el) { return el });
					if(data.length >0){
						if(data[0].photo){
							anteprima.innerHTML = "<img src="+data[0].photo+">";
						}else{
							anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersS[j].getPosition().lat()+","+markersS[j].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
						}
					}
				});
			}
		}
	}
	listObj.on('updated', function(listObj) {
		var res = document.getElementById("ricercaS").getElementsByClassName("list")[0].getElementsByClassName("result");
		for(var i=0; i<res.length; i++){
			for(var j=0; j<markersS.length; j++){
				if(res[i].id==markersS[j].title){
					var anteprima = res[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
					$.getJSON("../api/query.php?category=accommodation&_id="+res[i].id+"&not_null_latitude&not_null_longitude&min_latitude=1", function (result){
						var data = $.map(result, function(el) { return el });
						if(data.length >0){
							if(data[0].photo){
								anteprima.innerHTML = "<img src="+data[0].photo+">";
							}else{
								anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersS[j].getPosition().lat()+","+markersS[j].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
							}
						}
					});
				}
			}
		}
	});
	/*Torno indietro con lo zoom*/
	map.setZoom(16);
};
//Al click del tasto X della scheda delle ATTRAZIONI, LA SCHEDA SI CHIUDE E RICOMPARE LA LISTA DI ATTRAZIONI RICERCATA
function ChiudiA(){
	/*Reinserisco la lista di attrazioni ricercata*/
	$("#ricercaA").html(ResultsA);
	/*Tolgo, se presenti, eventuali marker selezionati*/
	for(var j = 0; j<markersA.length; j++){
		if(markersA[j].icon == 'images/attraction_selected.png'){
			markersA[j].setIcon('images/attraction.png');
		}
	}
	/*Inserisco la funzione di hover sulle attrazioni*/
	Hover();
	/*Inserisco la funzione di click sulle attrazioni*/
	$('.attraction').click(
		function(){
			ClickA(this.id);
		}
	)
	/*Ricreo l'impaginazione della lista delle attrazioni*/
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
	}else if (paginaA != 1){
		listObj2.show((paginaA-1)*7-(paginaA-2),6);
	}
	if(markersA.length <= listObj2.page){
		document.getElementById("ricercaA").getElementsByClassName("pagination")[0].style.display = "none";
	}else if(markersA.length > listObj2.page){
		document.getElementById("ricercaA").getElementsByClassName("pagination")[0].style.display = "block";
	}
	var res = document.getElementById("ricercaA").getElementsByClassName("list")[0].getElementsByClassName("result");
	for(var i=0; i<res.length; i++){
		for(var z=0; z<markersA.length; z++){
			if(res[i].id==markersA[z].title){
				var anteprima = res[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
				anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersA[z].getPosition().lat()+","+markersA[z].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
			}
		}
	}
	listObj2.on('updated', function(listObj2) {
		var res = document.getElementById("ricercaA").getElementsByClassName("list")[0].getElementsByClassName("result");
		for(var i=0; i<res.length; i++){
			for(var z=0; z<markersA.length; z++){
				if(res[i].id==markersA[z].title){
					var anteprima = res[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
					anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersA[z].getPosition().lat()+","+markersA[z].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
				}
			}
		}
	});
	/*Torno indietro con lo zoom*/
	map.setZoom(16);
};
/*FUNZIONE HOVER SULLA LISTA*/
function Hover(){
	$(".result").hover(
		function(){
			for(var i = 0; i<markersA.length; i++){
				if(this.id==markersA[i].title){
					if(markersA[i].icon == 'images/attraction.png'){
						markersA[i].setIcon('images/attraction_selected.png');
					}
				}
			}
			for(var j = 0; j<markersS.length; j++){
				if(this.id==markersS[j].title){
					if(markersS[j].icon == 'images/bed_b.png'){
						markersS[j].setIcon('images/structure_selected.png');
					}
				}
			}
		},
		function(){
			for(var i = 0; i<markersA.length; i++){
				if(this.id==markersA[i].title){
					if(markersA[i].icon == 'images/attraction_selected.png'){
						markersA[i].setIcon('images/attraction.png');
					}
				}
			}
			for(var j = 0; j<markersS.length; j++){
				if(this.id==markersS[j].title){
					if(markersS[j].icon == 'images/structure_selected.png'){
						markersS[j].setIcon('images/bed_b.png');
					}
				}
			}
		}
	)
}
/*FUNZIONE CLICK SULLE ATTRAZIONI*/
function ClickA(id){
	/*Faccio zoom sull'attrazione selezionata*/
	if(map.getZoom() < 19){
		map.setZoom(19);
	}
	/*Mantengo il numero di pagina*/
	if($("#ricercaA").html() !== ""){
		if(document.getElementById("ricercaA").firstChild.className == "list"){
			if(document.getElementById("ricercaA").getElementsByClassName("list")[0].innerHTML !== ""){
				paginaA = parseInt(document.getElementById("ricercaA").getElementsByClassName("pagination")[0].getElementsByClassName("active")[0].getElementsByClassName("page")[0].innerHTML);
			}
		}
	}
	/*Modifico l'impaginazione*/
	$("#background").css("background-color","#FF0000");
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
	/*Coloro il marker selezionato, decolorando gli altri*/
	for(var i = 0; i<markersA.length; i++){
		if(id==markersA[i].title){
			markersA[i].setIcon('images/attraction_selected.png');
			map.setCenter(markersA[i].position);
		}else{
			markersA[i].setIcon('images/attraction.png');
		}
	}
	for(var i = 0; i<markersS.length; i++){
		if(markersS[i].icon == "images/structure_selected.png"){
			markersS[i].setIcon('images/bed_b.png');
		}
	}
	/*Inserisco il contenuto nella scheda e la apro*/
	$.getJSON("../api/query.php?category=attraction&_id="+id+"&not_null_latitude&not_null_longitude&min_latitude=1", function (result){
		var data = $.map(result, function(el) { return el });
		if(data.length >0){
			var string="<div id='schedaA'><form><input class='chiudi' type='image' src='images/chiudiA.svg' onclick='ChiudiA()'></form>";
			string+="<div class='foto'><img src='https://maps.googleapis.com/maps/api/streetview?size=480x250&location="+data[0].latitude+","+data[0].longitude+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'></div>"
			string+="<div id='header2' class='header'>";
			if(data[0].name){
				string+="<p class='name'>"+data[0].name+"</p>";
			}
			if(data[0].category){
				string+="<p>Tipo di attrazione: "+data[0].category+"</p>";
			}
			if(data[0].description){
				string+="<p>Categoria: "+data[0].description+"</p>";
			}
			string+="</div><div class='contenuto'>"
			string+="<img class='icons' src='images/address1.svg' alt='address'><p class='infoscheda'> ";
			if(data[0].address){
				string+=data[0].address+", ";
			}
			if(data[0].city){
				string+=data[0].city;
			}
			if(data[0].province){
				string+=" ("+data[0].province+")";
			}
			string+="</p></br>"
			if(data[0].telephone){
				string+="<img class='icons' src='images/telephone1.svg' alt='telephone'><p class='infoscheda'> <a href='tel:"+data[0].telephone+"'>"+data[0].telephone+"</a></p></br>";
			}
			if(data[0].email){
				string+="<img class='icons' src='images/mail1.svg' alt='email'><p class='infoscheda'> <a href='mailto:"+data[0].email+"'>"+data[0].email+"</a></p></br>";
			}
			if(data[0].url){
				string+="<img class='icons' src='images/internet1.svg' alt='internet'><p class='infoscheda'> <a class='website' href='http://"+data[0].url+"'>";
				if(data[0].url.indexOf(".com") !== -1){
					if(data[0].url.indexOf(".comune") == -1){
						string+=data[0].url.split(".com")[0]+".com";
					}else{
						string+=data[0].url;
					}
				}else if(data[0].url.indexOf(".net") !== -1){
					string+=data[0].url.split(".net")[0]+".net";
				}else if(data[0].url.indexOf(".it") !== -1){
					string+=data[0].url.split(".it")[0]+".it";
				}else if(data[0].url.indexOf(".org") !== -1){
					string+=data[0].url.split(".org")[0]+".org";
				}else{
					string+=data[0].url;
				}
				string+="</a></p></br>";
			}
			string+="</div></div>";
			$("#ricercaA").html(string);
		}
	});
}
/*FUNZIONE CLICK SULLE ACCOMMODATION*/
function ClickS(id){
	/*Faccio zoom sulla struttura selezionata*/
	if(map.getZoom() < 19){
		map.setZoom(19);
	}
	/*Mantengo il numero di pagina*/
	if($("#ricercaS").html() !== ""){
		if(document.getElementById("ricercaS").firstChild.className == "list"){
			if(document.getElementById("ricercaS").getElementsByClassName("list")[0].innerHTML !== ""){
				paginaS = parseInt(document.getElementById("ricercaS").getElementsByClassName("pagination")[0].getElementsByClassName("active")[0].getElementsByClassName("page")[0].innerHTML);
			}
		}
	}
	/*Coloro il marker selezionato*/
	for(var j = 0; j<markersS.length; j++){
		if(id == markersS[j].title){
			markersS[j].setIcon('images/structure_selected.png');
			map.setCenter(markersS[j].position);
		}else{
			markersS[j].setIcon('images/bed_b.png');
		}
	}
	for(var j = 0; j<markersA.length; j++){
		if(markersA[j].icon == "images/attraction_selected.png"){
			markersA[j].setIcon('images/attraction.png');
		}
	}
	/*Modifico l'impaginazione*/
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
	/*Inserisco il contenuto nella scheda e la apro*/
	$.getJSON("../api/query.php?category=accommodation&_id="+id+"&not_null_latitude&not_null_longitude&min_latitude=1", function (result){
		var data = $.map(result, function(el) { return el });
		if(data.length >0){
			var string="<div id='schedaS'><form><input class='chiudi' type='image' src='images/chiudiS.svg' onclick='ChiudiS()'></form>";
			if(data[0].photo){
				string+="<div class='foto'><img src=\'"+data[0].photo+"\'></div>";
			}else{
				string+="<div class='foto'><img src='https://maps.googleapis.com/maps/api/streetview?size=480x250&location="+data[0].latitude+","+data[0].longitude+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'></div>"
			}
			string+="<div id='header1' class='header'>";
			if(data[0].name){
				string+="<p class='name'>"+data[0].name+"</p>";
			}
			if(data[0]['number of stars']){
				for(var y=0; y<data[0]['number of stars']; y++){
					string+="<img class='star' src='images/star.svg'>";
				}
				string+="<br/>";
			}
			if(data[0].description){
				string+="<p>Tipo di struttura: "+data[0].description+"</p>";
			}
			string+="</div><div class='contenuto'>";
			string+="<img class='icons' id='address' src='images/address.svg' alt='address'><p class='infoscheda'> ";
			if(data[0]['locality']){
				string+="Località "+data[0]['locality']+",</br>";
			}
			if(data[0].address){
				string+=data[0].address+", ";
			}
			if(data[0]['postal-code']){
				string+=data[0]['postal-code']+" ";
			}
			if(data[0].city){
				string+=data[0].city;
			}			
			if(data[0].province){
				string+=" ("+data[0].province+")";
			}
			if(data[0]['hamlet']){
				string+="</br>Frazione di "+data[0]['hamlet'];
			}
			string+="</p></br>";
			if(data[0].telephone){
				string+="<img class='icons' src='images/telephone.svg' alt='telephone'><p class='infoscheda'> <a href='tel:"+data[0].telephone+"'>"+data[0].telephone+"</a></p></br>";
			}
			if(data[0].email){
				string+="<img class='icons' src='images/mail.svg' alt='email'><p class='infoscheda'> <a href='mailto:"+data[0].email+"'>"+data[0].email+"</a></p></br>";
			}
			if(data[0]['web site']){
				string+="<img class='icons' src='images/internet.svg' alt='internet'><p class='infoscheda'> <a class='website' href='http://"+data[0]['web site']+"' target='_blank'>"+data[0]['web site']+"</a></p></br>";
			}
			if(data[0]['fax']){
				string+="<img class='icons' src='images/fax.svg' alt='fax'><p class='infoscheda'> Fax: "+data[0]['fax']+"</p></br>";
			}
			if(data[0]['opening period']){
				string+="<img class='icons' src='images/clock.svg' alt='opening period'><p class='infoscheda'> Periodo di apertura: "+data[0]['opening period']+"</p></br>";
			}
			if(data[0]['category']||data[0]['facilities']||data[0]['beds']||data[0]['rooms']||data[0]['suites']||data[0]['languages']){
				string+="<h2 class='sottosezione'>Dettagli struttura</h2>";
				if(data[0]['category']){
					string+="<p>"+data[0]['category']+"</p></br>";
				}
				if(data[0]['facilities']){
					string+="<div id='Servizi'><h3>Servizi offerti:</h3><ul>";
					for(var j = 0; j< data[0]['facilities'].length; j++){
						string+="<li><span>"+data[0]['facilities'][j]+"</span></li>";
					}
					string+="</ul></div>";
				}
				if(data[0]['beds']||data[0]['rooms']||data[0]['suites']){
					string+="<div id='Capacità'><h3>Capacità della struttura:</h3>"
					if(data[0]['beds']){
						string+="<p class='infoscheda'>"+data[0]['beds']+" posti letto</p></br>";
					}
					if(data[0]['rooms']){
						string+="<p class='infoscheda'>"+data[0]['rooms']+" camere</p></br>";
					}
					if(data[0]['suites']){
						string+="<p class='infoscheda'>"+data[0]['suites']+" suite</p></br>";
					}
					string+="</div>"
				}
				if(data[0]['languages']){
					string+="<div id='Lingue'><h3>Lingue conosciute:</h3><ul>";
					for(var z = 0; z< data[0]['languages'].length; z++){
						string+="<li><img class='lang' src=\'images/"+data[0]['languages'][z]+".png\'> "+data[0]['languages'][z]+"</li>"
					}
					string+="</ul></div>";
				}
			}
			if(data[0]['facebook']||data[0]['instagram']||data[0]['twitter']){
				string+="<h2 class='sottosezione'>Contatti social</h2>";
				if(data[0]['facebook']){
					string+="<p class='infoscheda'><img class='icons' src='images/facebook.svg' alt='facebook'> <a href='http://"+data[0]['facebook']+"\' target='_blank'>"+data[0]['facebook']+"</a></p></br>";
				}
				if(data[0]['instagram']){
					string+="<p class='infoscheda'><img class='icons' src='images/instagram.svg' alt='intagram'> <a href='http://"+data[0]['instagram']+"\' target='_blank'>"+data[0]['instagram']+"</a></p></br>";
				}
				if(data[0]['twitter']){
					string+="<p class='infoscheda'><img class='icons' src='images/twitter.svg' alt='twitter'> <a href='http://"+data[0]['twitter']+"\' target='_blank'>"+data[0]['twitter']+"</a></p></br>";
				}
			}
			string+="<a id='mod' href='hotel.html' target='_blank'>Sei il proprietario? Aggiorna o modifica i dati della tua struttura, o aggiungine di nuovi</a>";
			string+="<div id='attrazioni_vicine'></div>"
			string+="</div></div>"
			$("#ricercaS").html(string);
			$.getJSON("../api/attrazioni_vicine.php?lat="+data[0].latitude+"&lon="+data[0].longitude, function (result){
				var string='';
				if(result.length >0){
					string+="<h3>Attrazioni più vicine alla tua struttura<h3/><div class='swiper-container'><div class='swiper-wrapper'>";
					for(var i=0; i<result.length; i++){
						string+="<div class='swiper-slide'><div class='didascalia'><img src='https://maps.googleapis.com/maps/api/streetview?size=200x200&location="+result[i].latitude+","+result[i].longitude+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'><p>"+result[i].name+"</p></div></div>";
					}
					string+="</div><div class='swiper-button-next'></div><div class='swiper-button-prev'></div><div class='swiper-pagination'></div></div>";
				}else{
					string+= '<div class="avviso"><p>Attrazioni più vicine alla struttura. Nessuna attrazione disponibile<p></div>';
				}
				$("#attrazioni_vicine").html(string);
			});
			var swiper = new Swiper('.swiper-container', {
				effect: 'coverflow',
				grabCursor: true,
				centeredSlides: true,
				slidesPerView: 'auto',
				coverflowEffect: {
					rotate: 50,
					stretch: 0,
					depth: 100,
					modifier: 1,
					slideShadows : true,
				},
				navigation: {
					nextEl: '.swiper-button-next',
					prevEl: '.swiper-button-prev',
				},
				pagination: {
					el: '.swiper-pagination',
					clickable: true
				},
			});
		}
	});
};
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
	ResultsS = "<ul class='list'>";
	ResultsA = "<ul class='list'>";
	if (place){
		var url= "../api/query.php?category=accommodation&place=";
		if (place=="Trentino-Alto Adige"){
			url+="Trentino";
		}else{
			url+=place;
		}
		$.getJSON(url+"&min_latitude=1&not_null_latitude&not_null_longitude", function (result) {
			var data = $.map(result, function(el) { return el });
			var lunghezza = data.length;
			if(lunghezza != 0){
				map.setZoom(8);
				map.setCenter({ lat : data[0].latitude , lng : data[0].longitude });
				for (var i=0; i<lunghezza; i++){
					var stringa="";
					coordinate = {lat: data[i].latitude, lng: data[i].longitude};
					if(data[i].name){
						stringa+="<p>"+data[i].name+"</p>";
					}
					if(data[i].address){
						stringa+="<p>"+data[i].address+"</p>";
					}
					ResultsS += "<li class='result structure' id="+data[i]["_id"]+"><div class='i'><div class='info'><p>"+stringa+"</p></div><div class='anteprima'></div></div></li>";
					var marker = new google.maps.Marker({
						position: coordinate,
						map: map,
						icon: 'images/bed_b.png',
						title: data[i]['_id'],
					})
					google.maps.event.addListener(marker, 'click', function(event) {
						ClickS(this.title);
					});
					markersS.push(marker);
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
		$.getJSON("../api/query.php?category=attraction&place="+place+"&min_latitude=1&not_null_latitude&not_null_longitude", function (result) {
			var data = $.map(result, function(el) { return el });
			var lunghezza = data.length;
			if(lunghezza != 0){
				map.setZoom(8);
				for (var i=0; i<lunghezza; i++){
					var stringa="";
					coordinate = {lat: data[i].latitude, lng: data[i].longitude};
					if(data[i].name){
						stringa+="<p>"+data[i].name+"</p>";
					}
					if(data[i].description){
						stringa+="<p>"+data[i].description+"</p>";
					}
					ResultsA += "<li class='result attraction' id="+data[i]['_id']+"><div class='i'><div class='info'><p>"+stringa+"</p></div><div class='anteprima'></div></div></li>";			
					var marker = new google.maps.Marker({
						position: coordinate,
						map: map,
						icon: 'images/attraction.png',
						title: data[i]['_id'],
					});
					google.maps.event.addListener(marker, 'click', function(event) {
						ClickA(this.title);
					});
					markersA.push(marker);
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
	ResultsS += "</ul><ul class='pagination'></ul>";
	ResultsA += "</ul><ul class='pagination'></ul>";
	$("#ricercaS").html(ResultsS);
	$("#ricercaA").html(ResultsA);
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
	/*SUGGERIMENTI RICERCA TESTUALE*/
	$( function() {
		$.ajaxSetup({
			async: false
		});
		var availableTags = [];
		$.getJSON("../api/autocomplete.php?&not_null_latitude&not_null_longitude&min_latitude=1", function (result) {
			var data = $.map(result, function(el) { return el });
			availableTags = data;
		});
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
	/*RICERCA SE PREMO INVIO*/
	$("#ricerca_luogo").keypress(function(e) {
		if (e.keyCode == 13){
			e.preventDefault();
			infowindowI.setMap(null);
			infowindowE.setMap(null);
			infowindowF.setMap(null);
			$('#ui-id-1').fadeOut();
			clearMarkers();
			createMarker(map);
			markerClusterS = new MarkerClusterer(map, markersS,
				{imagePath: 'images/m'}
			);
			markerClusterA = new MarkerClusterer(map, markersA,
				{imagePath: 'images/g'}
			);
			Hover();
			$('.structure').click(
				function(){
					ClickS(this.id);
				}
			);
			$('.attraction').click(
				function(){
					ClickA(this.id);
				}
			);
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
			var structures = document.getElementsByClassName("structure");
			for(var i=0; i<structures.length; i++){
				for(var j=0; j<markersS.length; j++){
					if(structures[i].id == markersS[j].title){
						var anteprima = structures[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
						$.getJSON("../api/query.php?category=accommodation&_id="+structures[i].id+"&not_null_latitude&not_null_longitude&min_latitude=1", function (result){
							var data = $.map(result, function(el) { return el });
							if(data.length >0){
								if(data[0].photo){
									anteprima.innerHTML = "<img src="+data[0].photo+">";
								}else{
									anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersS[j].getPosition().lat()+","+markersS[j].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
								}
							}
						});
					}
				}
			}
			var attractions = document.getElementsByClassName("attraction");
			for(var i=0; i<attractions.length; i++){
				for(var z=0; z<markersA.length; z++){
					if(attractions[i].id==markersA[z].title){
						var anteprima = attractions[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
						anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersA[z].getPosition().lat()+","+markersA[z].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
					}
				}
			}
			listObj.on('updated', function(listObj) {
				var res = document.getElementById("ricercaS").getElementsByClassName("list")[0].getElementsByClassName("result");
					for(var i=0; i<res.length; i++){
						for(var j=0; j<markersS.length; j++){
							if(res[i].id==markersS[j].title){
								var anteprima = res[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
								$.getJSON("../api/query.php?category=accommodation&_id="+res[i].id+"&not_null_latitude&not_null_longitude&min_latitude=1", function (result){
									var data = $.map(result, function(el) { return el });
									if(data.length >0){
										if(data[0].photo){
											anteprima.innerHTML = "<img src="+data[0].photo+">";
										}else{
											anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersS[j].getPosition().lat()+","+markersS[j].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
										}
									}
								});
							}
						}
					}
			});
			listObj2.on('updated', function(listObj2) {
				var res = document.getElementById("ricercaA").getElementsByClassName("list")[0].getElementsByClassName("result");
				for(var i=0; i<res.length; i++){
					for(var z=0; z<markersA.length; z++){
						if(res[i].id==markersA[z].title){
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
				$("#background").css("background-color","#FF0000");
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
			console.log(markersA, markersS);
		}
	});
	/*CREO L'EVENTO DELLA RICERCA TESTUALE*/
	$("#cerca").click(function(event) {
		event.preventDefault();
		infowindowI.setMap(null);
		infowindowE.setMap(null);
		infowindowF.setMap(null);
		$('#ui-id-1').fadeOut();
		clearMarkers();
		createMarker(map);
		markerClusterS = new MarkerClusterer(map, markersS,
			{imagePath: 'images/m'}
		);
		markerClusterA = new MarkerClusterer(map, markersA,
			{imagePath: 'images/g'}
		);
		Hover();
		$('.structure').click(
			function(){
				ClickS(this.id);
			}
		)
		$('.attraction').click(
			function(){
				ClickA(this.id);
			}
		)
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
				if(res[i].id==markersS[j].title){
					var anteprima = res[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
					anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersS[j].getPosition().lat()+","+markersS[j].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
				}
			}
			for(var z=0; z<markersA.length; z++){
				if(res[i].id==markersA[z].title){
					var anteprima = res[i].getElementsByClassName("i")[0].getElementsByClassName("anteprima")[0];
					anteprima.innerHTML = "<img src='https://maps.googleapis.com/maps/api/streetview?size=80x92&location="+markersA[z].getPosition().lat()+","+markersA[z].getPosition().lng()+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'>";
				}
			}
		}
		listObj.on('updated', function(listObj) {
			var res = document.getElementById("ricercaS").getElementsByClassName("list")[0].getElementsByClassName("result");
			for(var i=0; i<res.length; i++){
				for(var j=0; j<markersS.length; j++){
					if(res[i].id==markersS[j].title){
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
					if(res[i].id==markersA[z].title){
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
			$("#background").css("background-color","#FF0000");
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
			$(".navigation").removeClass("out");
			$('.navigation').css('left','0');
			$('#arrow').attr("src","images/left arrow.svg");
			contatori[contatori.length-1]=1;
		}else if(contatori[contatori.length-1]==1){
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
						$("#background").css("background-color","#FF0000");
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
						$("#background").css("background-color","#FF0000");
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
});