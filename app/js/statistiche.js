var regione = null;
var provincia = null;
var latitudine = null;
var longitudine = null;
$(document).ready(function(){
	$.getJSON("../api/statisticheP.php?&n=2", function (result) {
	// Create the chart
		Highcharts.chart('grafico', {
			chart: {
				type: 'column'
			},
			title: {
				text: 'Numero di viaggiatori per tipologia di vacanza'
			},
			subtitle: {
				text: 'Clicca ciascuna colonna per vedere le fasce di età. Dati aggiornati al 2016.'
			},
			xAxis: {
				type: 'category'
			},
			yAxis: {
				title: {
					text: 'Migliaia di persone'
				}
			},
			legend: {
				enabled: false
			},
			plotOptions: {
				series: {
					borderWidth: 0,
					dataLabels: {
						enabled: true,
						format: '{point.y}'
					}
				}
			},

			tooltip: {
				headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
				pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b><br/>'
			},

			series: [{
				name: 'Tipo di viaggio',
				colorByPoint: true,
				data: [{
					name: result[0].Viaggio,
					y: result[0].Dato+result[1].Dato+result[2].Dato+result[3].Dato+result[4].Dato+result[5].Dato+result[6].Dato,
					drilldown: result[0].Viaggio
				}, {
					name: result[7].Viaggio,
					y: result[7].Dato+result[8].Dato+result[9].Dato+result[10].Dato+result[11].Dato+result[12].Dato+result[13].Dato,
					drilldown: result[7].Viaggio
				}, {
					name: result[14].Viaggio,
					y: result[14].Dato+result[15].Dato+result[16].Dato+result[17].Dato+result[18].Dato+result[19].Dato+result[20].Dato,
					drilldown: result[14].Viaggio
				}]
			}],
			drilldown: {
				series: [{
					name: result[0].Viaggio,
					id: result[0].Viaggio,
					data: [
						[
							'fino a 14 anni',
							result[1].Dato
						],
						[
							'15-24 anni',
							result[6].Dato
						],
						[
							'25-34 anni',
							result[5].Dato
						],
						[
							'35-44 anni',
							result[0].Dato
						],
						[
							'45-54 anni',
							result[2].Dato
						],
						[
							'55-64 anni',
							result[4].Dato
						],
						[
							'65 anni e più',
							result[3].Dato
						]
					]
				}, {
					name: result[7].Viaggio,
					id: result[7].Viaggio,
					data: [
						[
							'fino a 14 anni',
							result[9].Dato
						],
						[
							'15-24 anni',
							result[13].Dato
						],
						[
							'25-34 anni',
							result[12].Dato
						],
						[
							'35-44 anni',
							result[7].Dato
						],
						[
							'45-54 anni',
							result[8].Dato
						],
						[
							'55-64 anni',
							result[10].Dato
						],
						[
							'65 anni e più',
							result[11].Dato
						]
					]
				}, {
					name: result[14].Viaggio,
					id: result[14].Viaggio,
					data: [
						[
							'fino a 14 anni',
							result[14].Dato
						],
						[
							'15-24 anni',
							result[20].Dato
						],
						[
							'25-34 anni',
							result[19].Dato
						],
						[
							'35-44 anni',
							result[15].Dato
						],
						[
							'45-54 anni',
							result[16].Dato
						],
						[
							'55-64 anni',
							result[18].Dato
						],
						[
							'65 anni e più',
							result[17].Dato
						]
					]
				}]
			}
		});
	});
	$.getJSON("../api/statisticheP.php?&n=4", function (result){
		Highcharts.chart('grafico2', {
			chart: {
				type: 'column'
			},
			title: {
				text: 'Tipo di struttura scelta dai viaggiatori'
			},
			subtitle: {
				text: 'Dati aggiornati al 2016'
			},
			xAxis: {
				categories: [
					result[8].Alloggio,
					result[7].Alloggio,
					result[5].Alloggio,
					result[1].Alloggio,
					result[4].Alloggio,
					result[6].Alloggio,
					result[3].Alloggio,
					result[2].Alloggio,
					result[0].Alloggio,
				],
				crosshair: true
			},
			yAxis: {
				min: 0,
				title: {
					text: 'Dati (in migliaia)'
				}
			},
			tooltip: {
				headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
				pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
					'<td style="padding:0"><b>{point.y}</b></td></tr>',
				footerFormat: '</table>',
				shared: true,
				useHTML: true
			},
			plotOptions: {
				column: {
					pointPadding: 0.1,
					borderWidth: 0
				}
			},
			series: [{
				name: result[0].Viaggio,
				data: [result[8].Dato, result[7].Dato, result[5].Dato, result[1].Dato, result[4].Dato, result[6].Dato, result[3].Dato, result[2].Dato, result[0].Dato],
			},{
				name: result[9].Viaggio,
				data: [result[17].Dato, result[16].Dato, result[14].Dato, result[10].Dato, result[13].Dato, result[15].Dato, result[12].Dato, result[11].Dato, result[9].Dato]
			},{
				name: result[18].Viaggio,
				data: [result[26].Dato, result[25].Dato, result[23].Dato, result[19].Dato, result[22].Dato, result[24].Dato,result[21].Dato, result[20].Dato,result[18].Dato]
			}]
		});
	});
	$.getJSON("../api/statisticheP.php?&n=1", function (result){
		Highcharts.chart('grafico3', {

			title: {
				text: 'Numero di prenotazioni via Internet dal 2014 al 2016'
			},

			subtitle: {
				text: 'Internet sta diventando una risorsa,<br> rendi il tuo sito visibile inserendolo nella sezione modifica'
			},

			yAxis: {
				title: {
					text: 'Numero di prenotazioni, in migliaia'
				}
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'middle'
			},

			plotOptions: {
				series: {
					label: {
						connectorAllowed: false
					},
					pointStart: 2010
				}
			},

			series: [{
					name: result[0].Viaggio,
					data: [result[0].Dato, result[1].Dato, result[2].Dato]
				}, {
					name: result[3].Viaggio,
					data: [result[3].Dato, result[4].Dato, result[5].Dato]
				}, {
					name: result[6].Viaggio,
					data: [result[6].Dato, result[7].Dato, result[8].Dato]
				}],

			responsive: {
				rules: [{
					condition: {
						maxWidth: 500
					},
					chartOptions: {
						legend: {
							layout: 'horizontal',
							align: 'center',
							verticalAlign: 'bottom'
						}
					}
				}]
			}

		});
	});
	$.getJSON("../api/statisticheP.php?&n=3", function (result){
		Highcharts.chart('grafico4', {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				text: 'Mezzo di trasporto più usato dai viaggiatori'
			},
			subtitle: {
				text: 'Dati aggiornati al 2016'
			},
			tooltip: {
				pointFormat: '<b>{point.y}%</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						format: '<b>{point.name}</b>: {point.y}%',
						style: {
							color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
						}
					}
				}
			},
			series: [{
				name: 'Mezzi',
				colorByPoint: true,
				data: [{
					name: 'auto',
					y: result[0].Dato
				}, {
					name: 'treno',
					y: result[1].Dato,
				}, {
					name: 'aereo',
					y: result[2].Dato
				}, {
					name: 'pullman',
					y: result[3].Dato
				}, {
					name: 'mezzi non specificati',
					y: result[4].Dato
				}, {
					name: 'nave',
					y: result[5].Dato
				},{
					name: 'camper, autocaravan',
					y: result[6].Dato
				}]
			}]
		});
	});
	$.getJSON("../api/statisticheP.php?n=5", function (result){
		Highcharts.chart('grafico5', {

			title: {
				text: 'Andamento di presenze nelle strutture ricettive in Italia'
			},

			subtitle: {
				text: 'Serie dal 2010 al 2016'
			},

			yAxis: {
				title: {
					text: 'Numero di presenze'
				}
			},
			xAxis: {
				categories: [
					result[0].Periodo,
					result[1].Periodo,
					result[2].Periodo,
					result[3].Periodo,
					result[4].Periodo,
					result[5].Periodo,
					result[6].Periodo,
				],
				tickPositions: [ 0, 1, 2, 3, 4, 5, 6]
			},
			
			plotOptions: {
				column: {
					pointPadding: 0,
					borderWidth: 0
				}
			},

			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'middle'
			},

			series: [{
				name: 'Italia',
				data: [result[0].Dato,result[1].Dato,result[2].Dato,result[3].Dato,result[4].Dato,result[5].Dato,result[6].Dato],
			}],

			responsive: {
				rules: [{
					condition: {
						maxWidth: 500
					},
					chartOptions: {
						legend: {
							layout: 'horizontal',
							align: 'center',
							verticalAlign: 'bottom'
						}
					}
				}]
			}

		});
	});
	$.getJSON("../api/struttura_scelta.php", function (result){
		regione=result["region"];
		provincia=result["province"];
		latitudine=result["latitude"];
		longitudine=result["longitude"];
		if(regione != null){
			$.getJSON("../api/statisticheP.php?n=6&reg="+regione, function (result){
				if(result.length > 0){
					Highcharts.chart('grafico6', {

						title: {
							text: 'Andamento di presenze nelle strutture ricettive: dati regionali'
						},

						subtitle: {
							text: 'Serie dal 2010 al 2016'
						},

						yAxis: {
							title: {
								text: 'Numero di presenze'
							}
						},
						xAxis: {
							categories: [
								result[0].Periodo,
								result[1].Periodo,
								result[2].Periodo,
								result[3].Periodo,
								result[4].Periodo,
								result[5].Periodo,
								result[6].Periodo,
							],
							tickPositions: [ 0, 1, 2, 3, 4, 5, 6]
						},
						plotOptions: {
							column: {
								pointPadding: 0,
								borderWidth: 0
							}
						},
						legend: {
							layout: 'vertical',
							align: 'right',
							verticalAlign: 'middle'
						},
						series: [{
							name: 'Regione ('+result[0].Territorio+')',
							data: [result[0].Dato,result[1].Dato,result[2].Dato,result[3].Dato,result[4].Dato,result[5].Dato,result[6].Dato],
							color: '#434348'
						}],

						responsive: {
							rules: [{
								condition: {
									maxWidth: 500
								},
								chartOptions: {
									legend: {
										layout: 'horizontal',
										align: 'center',
										verticalAlign: 'bottom'
									}
								}
							}]
						}

					});
				}else{
					var s = '<div class="avviso"><p>Campo \'Regione\' assente o non valido. Inseriscilo correttamente nella sezione di modifica per visualizzare altri dati<p></div>';
					$('#grafico6').html(s);
				}
			});
			$.getJSON("../api/statisticheP.php?n=8&reg="+regione, function (result){
				if(result.length>0){
					Highcharts.chart('grafico8', {
						chart: {
							type: 'column'
						},
						title: {
							text: 'Numero di persone che scelgono la tua regione come meta di viaggi'
						},
						subtitle: {
							text: 'Dati aggiornati al 2016'
						},
						xAxis: {
							categories: [
								result[0].Viaggio,
								result[1].Viaggio,
								result[2].Viaggio,
								result[3].Viaggio,
								result[4].Viaggio
							],
							crosshair: true
						},
						yAxis: {
							min: 0,
							title: {
								text: 'Dati (in migliaia)'
							}
						},
						tooltip: {
							headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
							pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
								'<td style="padding:0"><b>{point.y}</b></td></tr>',
							footerFormat: '</table>',
							shared: true,
							useHTML: true
						},
						plotOptions: {
							column: {
								pointPadding: 0.1,
								borderWidth: 0
							}
						},
						series: [{
							name: result[0].Destinazione,
							data: [result[0].Dato, result[1].Dato, result[2].Dato, result[3].Dato, result[4].Dato],
						}]
					});
				}else{
					var s = '<div class="avviso"><p>Campo \'Regione\' assente o non valido. Inseriscilo correttamente nella sezione di modifica per visualizzare altri dati<p></div>';
					$('#grafico8').html(s);
				}
			});
			$.getJSON("../api/statisticheP.php?n=9&reg="+regione, function (result){
				if(result.length>0){
					var chart = Highcharts.chart('grafico9', {
						chart: {
							type: 'column'
						},
						title: {
							text: 'Traffico aereo nei pricipali aeroporti della regione'
						},
						subtitle: {
							text: 'Serie dal 2014 al 2016'
						},
						xAxis: {
							categories: [
								result[0].Periodo,
								result[1].Periodo,
								result[2].Periodo,
							],
							crosshair: true
						},
						yAxis: {
							min: 0,
							title: {
								text: 'Dati (in migliaia)'
							}
						},
						tooltip: {
							headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
							pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
								'<td style="padding:0"><b>{point.y}</b></td></tr>',
							footerFormat: '</table>',
							shared: true,
							useHTML: true
						},
						plotOptions: {
							column: {
								pointPadding: 0.1,
								borderWidth: 0
							}
						},
						series: [{
							name: result[0].Aeroporti,
							data: [result[0].Dato, result[1].Dato, result[2].Dato],
						}]
					});
					/*Controllo se nella regione è presente più di un aeroporto*/
					if(result.length>3){
						for(var i=3; i< result.length; i=i+3){
							chart.addSeries({ name: result[i].Aeroporti, data: [result[i].Dato, result[i+1].Dato, result[i+2].Dato]});
						}
					}
				}else{
					var s = '<div class="avviso"><p>Campo \'Regione\' assente o non valido. Inseriscilo correttamente nella sezione di modifica per visualizzare altri dati<p></div>';
					$('#grafico9').html(s);
				}
			});
		}else{
			var s = '<div class="avviso"><p>Campo \'Regione\' assente o non valido. Inseriscilo correttamente nella sezione di modifica per visualizzare altri dati<p></div>';
			$('#grafico6').html(s);
			$('#grafico8').html(s);
			$('#grafico9').html(s);
		}
		if(provincia != null){
			$.getJSON("../api/statisticheP.php?n=7&prov="+provincia, function (result){
				Highcharts.chart('grafico7', {

					title: {
						text: 'Andamento di presenze nelle strutture ricettive: dati provinciali'
					},

					subtitle: {
						text: 'Serie dal 2010 al 2016'
					},

					yAxis: {
						title: {
							text: 'Numero di presenze'
						}
					},
					legend: {
						layout: 'vertical',
						align: 'right',
						verticalAlign: 'middle'
					},
					xAxis: {
						categories: [
							result[0].Periodo,
							result[1].Periodo,
							result[2].Periodo,
							result[3].Periodo,
							result[4].Periodo,
							result[5].Periodo,
							result[6].Periodo,
						],
						tickPositions: [ 0, 1, 2, 3, 4, 5, 6]
					},
					
					plotOptions: {
						column: {
							pointPadding: 0,
							borderWidth: 0
						}
					},

					series: [{
						name: 'Provincia ('+result[0].Sigla+')',
						data: [result[0].Dato,result[1].Dato,result[2].Dato,result[3].Dato,result[4].Dato,result[5].Dato,result[6].Dato],
						color: '#90ed7d'
					}],

					responsive: {
						rules: [{
							condition: {
								maxWidth: 500
							},
							chartOptions: {
								legend: {
									layout: 'horizontal',
									align: 'center',
									verticalAlign: 'bottom'
								}
							}
						}]
					}
				});
			});
		}else{
			var s = '<div class="avviso"><p>Campo \'Provincia\' assente o non valido. Inseriscilo correttamente nella sezione di modifica per visualizzare altri dati<p></div>';
			$('#grafico7').html(s);
			$('#grafico9').html(s);
		}
		if((latitudine != null) && (longitudine != null)){
			$.getJSON("../api/attrazioni_vicine.php?lat="+latitudine+"&lon="+longitudine, function (result){
				if(result.length >0){
					var s="<p>Le attrazioni più vicine alla tua struttura</p><div class='swiper-container'><div class='swiper-wrapper'>";
					for(var i=0; i<result.length; i++){
						s+="<div class='swiper-slide'><div class='didascalia'><img src='https://maps.googleapis.com/maps/api/streetview?size=300x250&location="+result[i].latitude+","+result[i].longitude+"&heading=151.78&pitch=-0.76&key=AIzaSyCtU5lBoEO2eEDY7GVUSoj-7sVqWbFS1rk'><p>"+result[i].name+"</p></div></div>";
					}
					s+="</div><div class='swiper-button-next'></div><div class='swiper-button-prev'></div><div class='swiper-pagination'></div></div>";
					$('#attrazioni_vicine').html(s);
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
				}else{
					var s = '<div class="avviso"><p>Campi \'Latitudine\' e\\o \'Latitudine\' assenti o non validi. Inseriscili correttamente nella sezione di modifica per visualizzare altri dati<p></div>';
					$('#attrazioni_vicine').html(s);
				}
			});
		}else{
			var s = '<div class="avviso"><p>Campi \'Latitudine\' e\\o \'Longitudine\' assenti o non validi. Inseriscili correttamente nella sezione di modifica per visualizzare altri dati<p></div>';
			$('#attrazioni_vicine').html(s);
		}
	});
});