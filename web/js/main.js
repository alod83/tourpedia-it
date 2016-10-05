// Avvio della pagina
$(document).ready(function() {
	Mappa($('input[name="type"]:checked').val());
	Grafico();
	dimensionitabelle();
	//alert($(window).height());
	
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
	
// Ridimensionamento pagina
	$(window).resize(function(){
		dimensionitabelle();
		//alert($(window).width());
	});
});

// Funzione che ridimensiona le tabelle della sezione Fonti
function dimensionitabelle() {
	if ($(window).width()>768){
			$(".tab_fonti_sx").css("width",($(".col-lg-12").width())*30/100);
			$(".tab_fonti_cx").css("width",($(".col-lg-12").width())*30/100);
			$(".tab_fonti_dx").css("width",($(".col-lg-12").width())*30/100);
			$(".tab_fonti_sx").css("margin-left",($(".col-lg-12").width())*3/100);
			$(".tab_fonti_sx").css("margin-right",($(".col-lg-12").width())*3/100);
			$(".tab_fonti_cx").css("margin-left","0");
			$(".tab_fonti_cx").css("margin-right","0");
			$(".tab_fonti_dx").css("margin-left",($(".col-lg-12").width())*3/100);
			$(".tab_fonti_dx").css("margin-right",($(".col-lg-12").width())*3/100);
			if ($(window).width()<975){
				$(".tab_fonti_dx").css("margin-top","6px");
			}
			if ($(window).width()>974){
				$(".tab_fonti_dx").css("margin-top","12px");
			}
		}
	if ($(window).width()<769){
		$(".tab_fonti_sx").css("width",($(".col-lg-12").width())*50/100);
		$(".tab_fonti_cx").css("width",($(".col-lg-12").width())*50/100);
		$(".tab_fonti_dx").css("width",($(".col-lg-12").width())*50/100);
		$(".tab_fonti_sx").css("margin-left",($(".col-lg-12").width())*25/100);
		$(".tab_fonti_sx").css("margin-right",($(".col-lg-12").width())*25/100);
		$(".tab_fonti_cx").css("margin-left",($(".col-lg-12").width())*25/100);
		$(".tab_fonti_cx").css("margin-right",($(".col-lg-12").width())*25/100);
		$(".tab_fonti_dx").css("margin-left",($(".col-lg-12").width())*25/100);
		$(".tab_fonti_dx").css("margin-right",($(".col-lg-12").width())*25/100);
		$(".tab_fonti_dx").css("margin-top","10px");
		if ($(window).width()>500){
			$(".tab_fonti_dx").css("margin-top",($(".tab_fonti_sx").height())+45);
		}
		if ($(window).width()<501){
				$(".tab_fonti_sx").css("width","207");
				$(".tab_fonti_sx").css("margin-left",(($(".col-lg-12").width())-207)/2);
				$(".tab_fonti_sx").css("margin-right",(($(".col-lg-12").width())-207)/2);
				$(".tab_fonti_cx").css("width","207");
				$(".tab_fonti_cx").css("margin-left",(($(".col-lg-12").width())-207)/2);
				$(".tab_fonti_cx").css("margin-right",(($(".col-lg-12").width())-207)/2);
				$(".tab_fonti_dx").css("margin-top","10");
				$(".tab_fonti_dx").css("width","207");
				$(".tab_fonti_dx").css("margin-left",(($(".col-lg-12").width())-207)/2);
				$(".tab_fonti_dx").css("margin-right",(($(".col-lg-12").width())-207)/2);
			}
	}
}