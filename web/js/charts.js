function Grafico(series) {

    			
    $('#gcontainer').highcharts({
        chart: {
            type: 'bar',
			borderRadius: 20,
			shadow: true,
			style: {
				fontFamily: '"Verdana"'
				},
        },
        title: {
            text: 'Open Data vs. <a href="http://www.booking.com">Booking.com</a>'
        },
        subtitle: {
            text: 'Confronto tra i dati ricavati dagli open data delle Regioni e da Booking.com'
        },
        xAxis: {
			categories: ['Abruzzo', 'Calabria', 'Campania', 'Lazio', 'Molise', "Valle d'Aosta", 'Basilicata', 'Friuli-Venezia Giulia', 'Sardegna', 'Liguria', 'Trentino-Alto Adige', 'Umbria', 'Marche', 'Sicilia', 'Puglia', 'Piemonte', 'Lombardia', 'Emilia-Romagna', 'Veneto', 'Toscana'],
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Numero di strutture',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ' strutture'
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 80,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: series,
    });
}