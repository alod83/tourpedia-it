/*QUERY Presenze negli alberghi
ITALIA
$sql = "SELECT Sigla, Periodo, Dato FROM `presenza_negli_alberghi` WHERE Territorio=\'Italia\' AND Esercizio=\'totale esercizi ricettivi\' AND Indicatori=\'presenze\'";
REGIONE
$sql = "SELECT Sigla, Periodo, Dato FROM `presenza_negli_alberghi` WHERE Esercizio=\'totale esercizi ricettivi\' AND Territorio=\'Emilia Romagna\' AND Indicatori=\'presenze\'";
PROVINCIA
$sql = "SELECT Territorio, Periodo, Dato FROM `presenza_negli_alberghi` WHERE Esercizio=\'totale esercizi ricettivi\' AND Sigla=\'BO\' AND Indicatori=\'presenze\'";
*/
/*QUERY Destinazione preferita
$sql = "SELECT Destinazione, Viaggio, Dato FROM `regioni_destinazione` WHERE Destinazione=\'Piemonte\'";
*/
/*QUERY Voli aerei
$sql = "SELECT Aeroporti, Periodo, Dato FROM `voli_aerei_per_aeroporto` WHERE Regione=\'Lazio\' AND servizio=\'linea interni\' AND Nazionalità=\'Mondo\' AND ArrivoPartenza=\'arrivi\'";
*/
$(document).ready(function(){
	$.getJSON("../api/statisticheG.php?&n=2", function (result) {
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
	$.getJSON("../api/statisticheG.php?&n=4", function (result){
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
				data: [result[8].Dato, result[7].Dato, result[5].Dato, result[1].Dato, result[4].Dato, result[6].Dato, result[3].Dato, result[2].Dato, result[0].Dato]
			},{
				name: result[9].Viaggio,
				data: [result[17].Dato, result[16].Dato, result[14].Dato, result[10].Dato, result[13].Dato, result[15].Dato, result[12].Dato, result[11].Dato, result[9].Dato]
			},{
				name: result[18].Viaggio,
				data: [result[26].Dato, result[25].Dato, result[23].Dato, result[19].Dato, result[22].Dato, result[24].Dato,result[21].Dato, result[20].Dato,result[18].Dato]
			}]
		});
	});
	$.getJSON("../api/statisticheG.php?&n=1", function (result){
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
	$.getJSON("../api/statisticheG.php?&n=3", function (result){
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
});