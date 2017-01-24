function initMap() {

}

function clearMarkers(markers) {
    // Clear all markers
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    // Recreate the Markers array
    delete markers;
    markers = new Array();
}

function createMarker(map, markers){
	console.log( "cerca!" );
	var place = document.form_ricerca.ricerca_luogo.value;
	console.log(place);
	$.getJSON("./api/query.php?place="+place, function (data) {
		console.log( "query eseguita!" );
		console.log(data);
		for (i=0; i<data.length; i++){
			coordinate = {lat: data[i].latitude, lng: data[i].longitude};
			nome = data[i].name;
			var marker = new google.maps.Marker({
				position: coordinate,
				map: map,
				icon: 'images/bed_b.png',
				title: data[i].name,
				content: data[i].name+"<br/>"+data[i].description+"<br/>"+data[i].address+", "+data[i].city+" ("+data[i].province+")"+"<br/>"+data[i].telephone+"<br/>"+data[i].email
			});
			var infowindow = new google.maps.InfoWindow({ 
				position: coordinate,
				content: data[i].name+"<br/>"+data[i].description+"<br/>"+data[i].address+data[i].city+"("+data[i].province+")"+"<br/>"+data[i].telephone+"<br/>"+data[i].email,
				size: new google.maps.Size(50,50)
			});
			google.maps.event.addListener(marker, 'click', function(event) {
				infowindow.setContent(this.content);
				infowindow.open(map, this);
			});
			markers.push(marker);
		}
	});
}

$(document).ready(function() {
	$("#x").hide();
	var markers = new Array();
	console.log( "ready!" );
	var map = new google.maps.Map(document.getElementById('map'), {
	  center: {lat: 42.0, lng: 13.0},
	  zoom: 6
	});
	$("#ricerca_luogo").keypress(function(e) {
		if (e.keyCode == 13){
			e.preventDefault();
			clearMarkers(markers);
			createMarker(map, markers);
		}
	});
	$("#cerca").click(function(event) {
		event.preventDefault();
		clearMarkers(markers);
		createMarker(map, markers);
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
	// on click of "X", delete input field value and hide "X"
	$("#x").click(function(event) {
			event.preventDefault();
			$("#ricerca_luogo").val("");
			$(this).hide();
		}
	);
	$( function() {
		$.ajaxSetup({
			async: false
		});
		var availableTags;
		$.getJSON("./api/tags.php", function (data) {
			availableTags = data;
		});
		console.log(availableTags)
		$( "#ricerca_luogo" ).autocomplete({
			source: availableTags
		});
	});
});