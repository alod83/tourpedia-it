$(document).ready(function(){
	$("#go").on( "click",function(){
		var username = document.login.username.value;
		var password = document.login.password.value;
		/*CHIAMATA AJAX DOVE CONTROLLO SE I VALORI INSERITI ESISTONO NELLA TABELLA UTENTI*/
		$.getJSON("../api/login.php?User="+username+"&Pass="+password, function(result){
			console.log("ciao");
			$(location).attr('href', result)
		});
	});
});