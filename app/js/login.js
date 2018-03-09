$(document).ready(function(){
	$("#go").click(function(){
		var username = document.login.username.value;
		var password = document.login.password.value;
		/*CHIAMATA AJAX DOVE CONTROLLO SE I VALORI INSERITI ESISTONO NELLA TABELLA UTENTI*/
		$.get("../api/login.php?User="+username+"&Pass="+password);
	});
});