$(document).ready(function() {

	// Bar Chart
	
	Morris.Bar({
		element: 'bar-charts',
		data: [
			{ y: '2016', a: 600, b: 90 },
			{ y: '2017', a: 65,  b: 65 },
			{ y: '2018', a: 70,  b: 40 },
			{ y: '2019', a: 45,  b: 65 },
			{ y: '2020', a: 60,  b: 40 },
			{ y: '2021', a: 85,  b: 65 },
			{ y: '2022', a: 900, b: 90 }
		],
		xkey: 'y',
		ykeys: ['a', 'b'],
		labels: ['Total Income', 'Total Outcome'],
		lineColors: ['#ff9b44','#fc6075'],
		lineWidth: '3px',
		barColors: ['#ff9b44','#fc6075'],
		resize: true,
		redraw: true
	});
	
	// Line Chart
	
	Morris.Line({
		element: 'line-charts',
		data: [
			{ y: '20016', a: 50, b: 90 },
			{ y: '2017', a: 75,  b: 65 },
			{ y: '2018', a: 50,  b: 40 },
			{ y: '2019', a: 75,  b: 65 },
			{ y: '2020', a: 50,  b: 40 },
			{ y: '2021', a: 75,  b: 65 },
			{ y: '2022', a: 100, b: 50 }
		],
		xkey: 'y',
		ykeys: ['a', 'b'],
		labels: ['Total Sales', 'Total Revenue'],
		lineColors: ['#ff9b44','#fc6075'],
		lineWidth: '3px',
		resize: true,
		redraw: true
	});
		
});