function Mappa(tipo, series){
	if (tipo=="record") {
$('#heatmap').highcharts('Map', {
    chart: {
			borderRadius: 20,
			shadow: true,
			style: {
				fontFamily: '"Verdana"'
				},
	},		
	title: {
           text: 'Strutture'
			},
	subtitle: {
            text: 'Numero di strutture ricettive negli open data forniti dalle regioni'
			},
	mapNavigation: {
            enabled: false
			},
	colorAxis: {dataClasses: [{
                            from: 0,
                            to: 1000,
                            color: '#58ACFA',
                            name: '0-1000'
                        }, {
                            from: 1001,
                            to: 3000,
                            color: '#0080FF',
                           name: '1001-3000'
                        },  {
                            from: 3001,
                            to: 6000,
                            color: '#013ADF',
                           name: '3001-6000'
                        },	{
                            from: 6001,
                            to: 10000,
                            color: '#0404B4',
                           name: '6001-10000'
                        },  {
                            from: 10001,
                            to: 16000,
                            color: '#0B0B3B',
                           name: '10001-16000'
                        }
						]
            },

	credits: {
		enabled: false,
	},

	series: [
	{ name: 'Numero di strutture',
		"type": "map",
		"data": series,
	},
]
    });
	}
	
	if (tipo=="recpop") {
$('#heatmap').highcharts('Map', {
	chart: {
			borderRadius: 20,
			shadow: true,
			style: {
				fontFamily: '"Verdana"'
				},
	},       
	title: {
            text: 'Strutture su popolazione'
			},
	subtitle: {
            text: 'Numero di strutture ricettive ogni mille abitanti'
			},
	mapNavigation: {
            enabled: false
			},
	colorAxis: {dataClasses: [{
                            from: 0,
                            to: 1,
                            color: '#58ACFA',
                            name: '0-1'
                        }, {
                            from: 1.001,
                            to: 3,
                            color: '#0080FF',
                           name: '1-3'
                        },  {
                            from: 3.001,
                            to: 6,
                            color: '#013ADF',
                           name: '3-6'
                        },	{
                            from: 6.001,
                            to: 10,
                            color: '#0404B4',
                           name: '6-10'
                        }
                        ]

            },

	credits: {
		enabled: false,
	},

	series: [
	{ name: 'Numero di strutture ogni mille abitanti',
		"type": "map",
		"data": series,
	},
]
    });
	}
	if (tipo=="recterr") {
$('#heatmap').highcharts('Map', {
    chart: {
			borderRadius: 20,
			shadow: true,
			style: {
				fontFamily: '"Verdana"'
				},
	},   
	title: {
            text: 'Strutture su territorio'
			},
	subtitle: {
            text: 'Numero di strutture ricettive ogni dieci chilometri quadrati'
			},
	mapNavigation: {
            enabled: false
			},
	colorAxis: {dataClasses: [{
                            from: 0,
                            to: 1,
                            color: '#58ACFA',
                            name: '0-1'
                        }, {
                            from: 1.001,
                            to: 3,
                            color: '#0080FF',
                           name: '1-3'
                        },  {
                            from: 3.001,
                            to: 6,
                            color: '#013ADF',
                           name: '3-6'
                        },	{
                            from: 6.001,
                            to: 10,
                            color: '#0404B4',
                           name: '6-10'
                        }
                        ]
            },

	credits: {
		enabled: false,
	},

	series: [
	{ name: 'Numero di strutture ogni dieci chilometri quadrati',
		"type": "map",
		"data": series,
	},
]
    });
	}
}