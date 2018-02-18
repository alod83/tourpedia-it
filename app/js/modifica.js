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
	$("#inputFile").change(function () {
		readURL(this);
		$("#NomeFile").html($("#inputFile")[0].files[0]['name']);
		$(".clear").fadeIn();
	});
	$(".clear").click(function(event) {
		event.preventDefault();
		Clear();
	});
});