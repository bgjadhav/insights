/*global widgets, Highcharts, $ */
var charts = {
	hack : function() {
		Highcharts.Axis.prototype.init = (function (func) {
			return function (chart, userOptions) {

				if (userOptions.categories) {
					var labels = userOptions.labels || {};
					var originalFormatter = userOptions.labels.formatter;

					this.userCategories = userOptions.categories;
					userOptions.categories = null;

					labels.formatter = function() {
						this.axis.options.labels.align = (this.value == this.axis.min) ? "left" : ((this.value == this.axis.max) ? "right" : "center");

						if (!originalFormatter) return this.axis.userCategories[this.value];

						this.value = this.axis.userCategories[this.value];
						return originalFormatter.call(this);
					};

					userOptions.labels = labels;
				}

				if (userOptions.tooltip) {
				}

				func.apply(this, [chart, userOptions]);
			};
		} (Highcharts.Axis.prototype.init));
	},
	reset : function() {
		var HCDefaults = $.extend(true, {}, Highcharts.getOptions(), {});

		// Fortunately, Highcharts returns the reference to defaultOptions itself
		// We can manipulate this and delete all the properties
		var defaultOptions = Highcharts.getOptions();
		for (var prop in defaultOptions) {
			if (typeof defaultOptions[prop] !== 'function') delete defaultOptions[prop];
		}
		// Fall back to the defaults that we captured initially, this resets the theme
		Highcharts.setOptions(HCDefaults);
	},
	yellow : function() {
		charts.reset();
		var textColours = ['#ffffff'];
		return Highcharts.theme = {
			//colors: ['#ffc627','#efefea', '#FF9933', '#FFD175', '#B2B2B2'],
			chart: {
				margin: [0,0,0,0],
				marginLeft: 0,
				marginTop: 0
			},
			colors: ['#FFAD08','#EDD75A', '#73B06F', '#0C8F8F', '#405059', '#CCCCCC', '#C7E4CF', '#4F596B', '#A89E86'],
			legend: {
				itemStyle: {
					color: '#f49d19',
					fontSize: '11px'
				},
				itemHoverStyle : {
					color: '#f49d19'
				},
				verticalAlign: 'top',
				itemMarginBottom: 4,
				y: 5,
				x: 10,
				enabled: true
			},
			tooltip: {
				headerFormat :'<span style="font-weight: bold;color:{point.color}">{point.key}</span><br/>',
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: false
					}
				}
			}
		};
	},
	"midnight-blue" : function() {
		charts.reset();
		var textColours = ['#3fa9f5','#ffffff', '#ffffff'];
		return Highcharts.theme = {
			chart: {
				marginLeft: 55,
				marginTop: null,
				marginBottom: null

			},
			colors: ['#fdc43b','#e37f3f','#67bc4b','#3385c1'],
			legend: {
				itemStyle: {
					color: '#ffffff',
					fontSize: '11px'
				},
				itemHoverStyle : {
					color: '#ffffff'
				},
				verticalAlign: 'bottom',
				itemMarginBottom: 10,
				y: 200
			},
			yAxis: {
				max: null,
				tickColor: '#fff',
				gridLineColor: 'rgba(255,255,255,0.1)',
				labels: {
					formatter: function() {
						return this.value/1000000 + 'm';
					},
					style: {
						color: '#fff'
					}
				},
				title : {
					text : ''
				}
			},
			xAxis : {
				tickColor: 'rgba(255,255,255,0.2)',
				lineColor: 'rgba(255,255,255,0.2)',
				labels: {
					style: {
						color: '#fff'
					}
				}
			},
			tooltip: {
				headerFormat: '',
				pointFormat: '{series.name}: <b>${point.y}</b>',
//				formatter: function() {
//					console.log(this);
//					return 'Spend: <b>${point.y}</b>';
//				}
			},
			plotOptions: {
				column : {
					borderWidth: 0
				},
				spline: {
					lineWidth: 2,
					marker: {
						enabled: false
					},
					dashStyle: 'dash'
				}
			}
		};
	},
	"midnight-blue-alt" : function() {
		charts.reset();
		var textColours = ['#3fa9f5','#ffffff', '#ffffff'];
		return Highcharts.theme = {
			chart: {
				marginLeft: 55,
				marginTop: null,
				marginBottom: 42

			},
			colors: ['#fdc43b','#e37f3f','#67bc4b','#3385c1'],
			legend: {
				itemStyle: {
					color: '#ffffff',
					fontSize: '11px'
				},
				itemHoverStyle : {
					color: '#ffffff'
				},
				verticalAlign: 'bottom',
				itemMarginBottom: 10,
				y: 200
			},
			yAxis: {
				max: null,
				tickColor: '#fff',
				gridLineColor: 'rgba(255,255,255,0.1)',
				labels: {
					formatter: function() {
						var text = this.value;
						return (text/100000) + '%';
					},
					style: {
						color: '#fff'
					}
				}
			},
			xAxis : {
				tickColor: 'rgba(255,255,255,0.2)',
				lineColor: 'rgba(255,255,255,0.2)',
				labels: {
					formatter: function () {
						var text = this.value;
						if(text != undefined){
							var formatted = text.length > 5 ? text.substring(0, 5) : text;
							return '<div class="js-ellipse" style="overflow:hidden" title="' + text + '">' + formatted + '</div>';
						}
					},
					style: {
						color: '#fff'
					},
					useHTML: true
				}
			},
			tooltip: {
				formatter: function() {
					var total = 0;
					$.each(this.series.yData, function(key, value) {
						total += value;
					});

					var percent = this.y / total * 100;
					var value = this.y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
					return 'Percentage: <b>' + percent.toFixed(2) + '%</b>';
				}
			},
			plotOptions: {
				pie: {
					dataLabels: {
						formatter: function () {
							return '<span style="color:' + textColours[this.point.index] + ';font-weight: bold;font-size:18px;">' + Math.round(this.point.percentage) + '%</span>';
						}
					}
				},
				column : {
					borderWidth: 0
				},
				spline: {
					lineWidth: 2,
					marker: {
						enabled: false
					},
					dashStyle: 'dash'
				}
			}
		};
		//Highcharts.setOptions(Highcharts.theme);
	},
	"certified-buyer-spend" : function() {
		charts.reset();
		return Highcharts.theme = {
			chart: {
				marginLeft: 45,
				marginRight: 20,
				marginTop: null,
				marginBottom: null
			},
			colors: ['#d1d2d4','#e2a726','#a3bfc3', '#fff'],
			legend: {
				itemStyle: {
					color: '#ffffff',
					fontSize: '11px'
				},
				itemHoverStyle : {
					color: '#ffffff'
				},
				verticalAlign: 'bottom',
				itemMarginBottom: 10,
				y: 200
			},
			yAxis: {
				max: null,
				tickColor: '#fff',
				gridLineColor: 'rgba(255,255,255,0.1)',
				labels: {
					style: {
						color: '#fff'
					},
					formatter: function () {
						return this.value + '%';
					}
				}
			},
			xAxis : {
				tickColor: 'rgba(255,255,255,0.2)',
				lineColor: 'rgba(255,255,255,0.2)',
				labels: {
					formatter: function () {
						var text = this.value;
						if(text != undefined){
							var formatted = text.length > 5 ? text.substring(0, 5) : text;
							return '<div class="js-ellipse" style="overflow:hidden" title="' + text + '">' + formatted + '</div>';
						}
					},
					style: {
						color: '#fff'
					},
					useHTML: true
				}
			},
			tooltip: {
				formatter: function() {
					return this.series.name + ' media spend: <b>' + this.y + '%</b>';
				}
			},
			plotOptions: {
				spline: {
					lineWidth: 3,
					marker: {
						enabled: false
					},
					dashStyle: 'solid'
				}
			}
		};
		//Highcharts.setOptions(Highcharts.theme);
	},
	"opto-brain" : function(format) {
		charts.reset();
		return Highcharts.theme = {
			chart: {
				marginLeft: 90,
				marginRight: 20,
				marginTop: null,
				marginBottom: null
			},
			colors: ['#d1d2d4','#e2a726','#a3bfc3', '#fff'],
			legend: {
				itemStyle: {
					color: '#ffffff',
					fontSize: '11px'
				},
				itemHoverStyle : {
					color: '#ffffff'
				},
				verticalAlign: 'bottom',
				itemMarginBottom: 10,
				y: 200
			},
			yAxis: {
				max: null,
				tickColor: '#fff',
				gridLineColor: 'rgba(255,255,255,0.1)',
				labels: {
					style: {
						color: '#fff'
					},
					formatter: function () {
						return widgets.format(this.value, format+'_yaxis');
					}
				},
				title:null
			},
			xAxis : {
				tickColor: 'rgba(255,255,255,0.2)',
				lineColor: 'rgba(255,255,255,0.2)',
				labels: {
					formatter: function () {
						var text = this.value;
						if(text != undefined){
							var formatted = text.length > 5 ? text.substring(0, 5) : text;
							return '<div class="js-ellipse" style="overflow:hidden" title="' + text + '">' + formatted + '</div>';
						}
					},
					style: {
						color: '#fff'
					},
					useHTML: true
				}
			},
			tooltip: {
				formatter: function() {
					return this.series.name + ': <b>'+widgets.format(this.y, format)+ '</b>';
				}
			},
			plotOptions: {
				spline: {
					lineWidth: 3,
					marker: {
						enabled: false
					},
					dashStyle: 'solid'
				}
			}
		};
		//Highcharts.setOptions(Highcharts.theme);
	},
	"white-blue" : function() {
		charts.reset();
		return Highcharts.theme = {
			colors: ['rgba(255,255,255,0.3)','#fff','#239cd8'],
			legend: {
				itemStyle: {
					color: '#ffffff'
				},
				itemHoverStyle : {
					color: '#ffffff'
				},
				y: -50
			},
			yAxis: {
				tickColor: '#fff',
				gridLineColor: 'rgba(255,255,255,0.1)',
				labels: {
					style: {
						color: '#fff'
					}
				}
			},
			xAxis : {
				tickColor: 'rgba(255,255,255,0.2)',
				lineColor: 'rgba(255,255,255,0.2)',
				labels: {
					style: {
						color: '#fff'
					}
				}
			}
		};
	},
	"cherry-blossom" : function() {
		charts.reset();
		return Highcharts.theme = {
			chart: {
				margin: [0,0,1,0],
				marginLeft: 0,
				marginTop: -30
			},
			colors: ['#a8bdcd','pink', '#808080','brown','#5d88be', 'green', '#d6bf87', 'black', 'red'],
			yAxis: {
//				max: 100,
				tickColor: '#fff',
				gridLineColor: 'rgba(255,255,255,0.1)',
				labels: {
					formatter: function() {
						var text = this.value;
						return text + '%';
					},
					style: {
						color: '#fff'
					}
				},
				title: {
					text : null
				}
			},
			legend : {
				enabled: true,
				align: 'right',
				verticalAlign: 'top',
				x: -10,
				y: 50,
				floating: true,
				itemStyle: {
					color: '#ffffff',
					fontWeight: 'bold'
				},
				backgroundColor: 'rgba(255,255,255,0.2)',
				itemMarginBottom: 10
			},
			xAxis : {
				labels: {
					style: {
						color: '#fff'
					}
				}
			},
			plotOptions: {
				area: {
					lineWidth: 0
				}
			}
		};
	},
	"deep-ocean" : function() {
		charts.reset();
		return Highcharts.theme = {
			chart: {
				plotBorderWidth: 0,
				margin: [3,20,60,62]
			},
			colors: ['rgba(255,255,255, 0.9)','#80b8ce'],
			credits: {
				enabled: false
			},
			title: {
				text: ''
			},
			xAxis : {
				tickColor: '#fff',
				lineColor: '#fff',
				labels: {
					style: {
						color: '#fff'
					}
				}
			},
			exporting: {
				enabled: false
			},
			yAxis: [{
				min: 0,
				max: null,
				gridLineColor: 'rgba(255,255,255,0.1)',
				tickColor: '#fff',
				lineColor: '#fff',
				labels: {
					style: {
						color: '#fff'
					},
					formatter: function () {
					   return this.value+'%';
					},
				},
				title: {
					text: ''
				}
			}],
			legend: {
				shadow: false,
				layout: 'horizontal',
				align: 'center',
				itemStyle: {
					color: '#fff'
				}
			},
			tooltip: {
				enabled: true,
				shared: true,
				borderWidth: 1,
				borderRadius: 8,
				borderColor: 'rgba(255,255,255,0.3)',
				shadow: false,
				useHTML: true,
				crosshairs: true,
				padding: 0,
				followTouchMove: true,
				valueDecimals: 2,
				headerFormat :'<table>',
				pointFormat: '<tr><td style="font-weight: bold;">{series.name}:</td><td>{point.y}%</td>',
				footerFormat: '</table>',
			},
			plotOptions: {
				column: {
					grouping: false,
					shadow: false,
					borderWidth: 0,
					cursor: 'pointer'
				},
				spline: {
					lineWidth: 2,
					marker: {
						enabled: false
					},
					dashStyle: 'dash',
					cursor: 'pointer'
				}
			},
		};
	},
	"deep-ocean-stack" : function() {
		charts.reset();
		return Highcharts.theme = {
			chart: {
				plotBorderWidth: 0,
				margin: [3,20,60,62]
			},
			colors: ['rgba(255,255,255, 0.9)','#80b8ce'],
			credits: {
				enabled: false
			},
			title: {
				text: ''
			},
			xAxis : {
				tickColor: '#fff',
				lineColor: '#fff',
				labels: {
					style: {
						color: '#fff'
					}
				}
			},
			exporting: {
				enabled: false
			},
			yAxis: [{
				min: 0,
				max: null,
				gridLineColor: 'rgba(255,255,255,0.1)',
				tickColor: '#fff',
				lineColor: '#fff',
				labels: {
					style: {
						color: '#fff'
					},
					formatter: function () {
					   return this.value+'%';
					},
				},
				title: {
					text: ''
				}
			}],
			legend: {
				shadow: false,
				layout: 'horizontal',
				align: 'center',
				itemStyle: {
					color: '#fff'
				}
			},
			tooltip: {
				enabled: true,
				shared: true,
				borderWidth: 1,
				borderRadius: 8,
				borderColor: 'rgba(255,255,255,0.3)',
				shadow: false,
				useHTML: true,
				crosshairs: true,
				padding: 0,
				followTouchMove: true,
				valueDecimals: 2,
				headerFormat :'<table>',
				pointFormat: '<tr><td style="font-weight: bold;">{series.name}:</td><td>{point.y}%</td>',
				footerFormat: '</table>',
			},
			plotOptions: {
				column: {
					grouping: false,
					shadow: false,
					borderWidth: 0,
					stacking: 'normal',
					cursor: 'pointer'
				},
				spline: {
					lineWidth: 2,
					marker: {
						enabled: false
					},
					dashStyle: 'dash',
					cursor: 'pointer'
				}
			},
		};
	},
	pie : function(data, container, theme) {
		var options = {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: 0,
				plotShadow: false,
				margin: [0,0,0,0],
				backgroundColor: 'rgba(255, 255, 255, 0)'
			},
			credits: {
				enabled: false
			},
			title: {
				text: ''
			},
			tooltip: {
				enabled: true,
				pointFormat:  '<span style="color:{point.color};font-weight: bold;">'+data.tooltip.pointFormat+': </span><b>{point.y}</b><br><b>{point.percentage:.1f}%</b> of total'
			},
			legend: {
				align: 'right',
				useHTML: true,
				layout: 'vertical',
				itemStyle: {
					"font-weight": 'normal'
				}
			},
			plotOptions: {
				pie: {
					center: [80,80],
					borderWidth: 0,
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						useHTML: true,
						distance: -15,
						color: 'white'
					},
					showInLegend: true
				}
			},
			series: [{
				type: 'pie',
				data: data.data
			}]
		};
		var styles = charts[theme]();
		options = $.extend(true, options, styles);
		container.highcharts(options);
	},
	donut : function(data, container, theme) {
		console.log('donut');
		var options = {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: 0,
				plotShadow: false,
				margin: [0,0,0,0],
				backgroundColor: 'rgba(255, 255, 255, 0)',
				type: 'pie'
			},
			credits: {
				enabled: false
			},
			title: {
				text: ''
			},
			tooltip: {
				enabled: true,
				pointFormat:  '<span style="color:{point.color};font-weight: bold;">'+data.tooltip.pointFormat+': </span><b>{point.y}</b><br><b>{point.percentage:.1f}%</b> of total'
			},
			legend: {
				enabled : false
			},
			plotOptions: {
				pie: {
					center: ['50%','50%'],
					borderWidth: 0,
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						distance: 30,
					},
					showInLegend: true
				}
			},
			series: data.data
		};
//		var styles = charts[theme]();
//		options = $.extend(true, options, styles);
		container.highcharts(options);
	},
	line : function(data, container, theme, format) {
		var options = {
			chart: {
				type : 'spline',
				plotBackgroundColor: null,
				plotBorderWidth: 0,
				plotShadow: false,
				margin: [5,0,30,35],
				backgroundColor: 'rgba(255, 255, 255, 0)'
			},
			credits: {
				enabled: false
			},
			title: {
				text: ''
			},
			legend: {
				align: 'right',
				useHTML: true,
				layout: 'vertical',
				itemStyle: {
					"font-weight": 'normal'
				}
			},
			xAxis : {
				type: 'datetime',
				categories: data.chart.categories,
				tickInterval: 1,
				maxPadding: 0,
				endOnTick: false,
				startOnTick: false
			},
			yAxis : {
				maxPadding:0,
				minPadding:0
			},
			plotOptions: {
				spline: {
					lineWidth: 3,
					marker: {
						enabled: false
					}
				},
				line: {
					marker: {
						enabled: false
					}
				}
			},
			series: data.data
		};
		if(data.chart.cut !== false) {
			options.xAxis.min = 0.5;
			options.xAxis.max = data.data[0].data.length;
		}
		var styles = charts[theme](format);
		options = $.extend(true, options, styles);
		container.highcharts(options);
	},
	area : function(data, container, theme) {

		var totals = [];
		$.each(data.data, function(key, value) {
			$.each(value.data, function(key2, value2) {
				if(key == 0) {
					totals.push(0);
				}
				totals[key2] = parseInt(totals[key2]) + parseInt(value2);
			});
		});

		var options = {
			chart: {
				type : 'area',
				plotBackgroundColor: null,
				plotBorderWidth: 0,
				plotShadow: false,
				margin: [0,0,0,0],
				//backgroundColor: 'rgba(255, 255, 255, 0.0)'
				backgroundColor: '#a8bdcd'
			},
			credits: {
				enabled: false
			},
			title: {
				text: ''
			},
			labels: {
				formatter: function() {
					return "-" + this.value + "-";
				}
			},
			legend: {
				align: 'right',
				useHTML: true,
				layout: 'vertical',
				itemStyle: {
					"font-weight": 'normal'
				}
			},
			tooltip: {
				positioner: function(boxWidth, boxHeight, point) {
					// Set up the variables
					var chart = this.chart,
						plotLeft = chart.plotLeft,
						plotTop = chart.plotTop,
						plotWidth = chart.plotWidth,
						plotHeight = chart.plotHeight,
						distance = 10, // You can use a number directly here, as you may not be able to use pick, as its an internal highchart function
						pointX = point.plotX,
						pointY = point.plotY,
						x = pointX + plotLeft + (chart.inverted ? distance : -boxWidth - distance),
						y = pointY - boxHeight + plotTop + 15, // 15 means the point is 15 pixels up from the bottom of the tooltip
						alignedRight;

					// It is too far to the left, adjust it
					if (x < 7) {
						x = plotLeft + pointX + distance;
					}

					// Test to see if the tooltip is too far to the right,
					// if it is, move it back to be inside and then up to not cover the point.
					if ((x + boxWidth) > (plotLeft + plotWidth)) {
						x -= (x + boxWidth) - (plotLeft + plotWidth);
						y = pointY - boxHeight + plotTop - distance;
						alignedRight = true;
					}

					// If it is now above the plot area, align it to the top of the plot area
					if (y < plotTop + 5) {
						y = plotTop + 5;

						// If the tooltip is still covering the point, move it below instead
						if (alignedRight && pointY >= y && pointY <= (y + boxHeight)) {
							y = pointY + plotTop + distance; // below
						}
					}

					// Now if the tooltip is below the chart, move it up. It's better to cover the
					// point than to disappear outside the chart. #834.
					if (y + boxHeight > plotTop + plotHeight) {
						y = mathMax(plotTop, plotTop + plotHeight - boxHeight - distance); // below
					}


					return {x: x, y: y};
				},
				headerFormat: '',
				formatter: function() {
					var value = parseFloat((this.y / totals[this.x]) * 100).toFixed(1);
					return '<span class="number">' + this.y.toFixed(2) + '%</span><span class="text">' + this.series.name + '</span>';
				},
				useHTML: true,
				shadow: false,
				borderColor: 'rgba(255, 255, 255, 0)',
				hideDelay: 1,
				style: {
					padding: 0,
					border: 0
				}
			},
			xAxis : {
				type: 'datetime',
				categories: data.chart.categories,
				tickInterval: 1,
				maxPadding: 0,
				endOnTick: false,
				startOnTick: false,
				labels: {
					align: 'left',
					x: -20,
					y: -5
				},
				min: 0.5
			},
			yAxis : {
				maxPadding:0,
				minPadding:0,
				labels: {
					align: 'left',
					x: 20,
					y: -6
				}
			},
			plotOptions: {
				area: {
					stacking: 'normal',
					marker: {
						enabled: false
					}
				}
			},
			series: data.data
		};
		if(data.chart.cut) {
			options.xAxis.min = 0.5;
			options.xAxis.max = data.data[0].data.length;
		}
		var styles = charts[theme]();
		options = $.extend(true, options, styles);
		container.highcharts(options);
	},
	column : function(data, container, theme) {
		var options = {
			chart: {
				type : 'column',
				plotBackgroundColor: null,
				plotBorderWidth: 0,
				plotShadow: false,
				margin: [5,0,30,35],
				backgroundColor: 'rgba(255, 255, 255, 0)'
			},
			credits: {
				enabled: false
			},
			title: {
				text: ''
			},
			legend: {
				align: 'right',
				useHTML: true,
				layout: 'vertical',
				itemStyle: {
					"font-weight": 'normal'
				}
			},
			xAxis : {
				type: 'datetime',
				categories: data.chart.categories,
				tickInterval: 1,
				maxPadding: 0,
				endOnTick: false,
				startOnTick: true
			},
			yAxis : {
				maxPadding:0,
				minPadding:0
			},
			plotOptions: {
				spline: {
					lineWidth: 3,
					marker: {
						enabled: false
					}
				},
				line: {
					marker: {
						enabled: false
					}
				}
			},
			series: data.data
		};
		if(data.chart.cut !== false) {
			options.xAxis.min = 0.5;
			options.xAxis.max = data.data[0].data.length;
		}
		var styles = charts[theme]();
		options = $.extend(true, options, styles);
		container.highcharts(options);
	},
	
	"channel-black" : function(data) {
		var chart = {
			chart : {
				type : 'spline',
				backgroundColor : 'rgba(255,255,255,0)',
			},
			colors: ['#79c73c', '#3e6063', '#f37157', '#79c73c', '#3e6063', '#f37157'],
			title : false,
			credits : {
				enabled: false
			},
			legend : {
				enabled : false
			},
			tooltip : {
				backgroundColor: null,
				borderWidth: 0,
				shadow: false,
				useHTML: true,
				style: {
					padding: 0
				},
				formatter : function(options) {
					var seperator;
					if(this.y > 100 && this.y < 100000) {
						seperatorBefore = '';
						seperatorAfter = '';
					} else if(this.y > 100) {
						seperatorBefore = '$';
						seperatorAfter = '';
					} else {
						seperatorBefore = '';
						seperatorAfter = '%';
					}
					var color;
					if(this.series.color == '#fff') {
						color = '#353f47';
					}
					return '<span class="inner" style="background: ' + this.series.color + '; color: '+ color +'">' + this.series.name + ' ' + seperatorBefore + this.y.toLocaleString() + seperatorAfter + '</span>';
				}
			},
			yAxis : {
				gridLineWidth : 0,
				title : {
					text : false
				},
				labels : {
					style: {
						color: '#fff'
					}
				},
				min : 0
			},
			xAxis : {
				lineColor: false,
				tickColor: false,
				tickInterval: 1,
				categories: data.chart.categories,
				labels: {
					step : 1,
					formatter : function() {
						return this.value;
					},
					style : {
						color: '#fff',
						fontSize: '15px'
					}
				}
			},
			plotOptions: {
				series: {
					lineWidth: 5,
					marker: {
						lineWidth : 2,
						lineColor : '#ffffff',
						fillColor : 'rgba(255,255,255, 0.4)',
						symbol : 'circle'
					},
					events: {
						legendItemClick: function (e) {
							e.preventDefault();
						}
					}
				},
			},
			legend : {
				enabled: true,
				align: 'right',
				verticalAlign: 'top',
				x: -10,
				y: -15,
				floating: true,
				itemStyle: {
					color: '#ffffff'
				},
				itemHoverStyle: {
					color: '#ffffff'
				}
			},
			series: data.data
		};
		if(data.data[1] && data.data[1].data.length < data.data[0].data.length) {
			chart.xAxis.plotLines = [{
				color: '#fff',
				width: 1,
				value: data.data[1].data.length -1,
				dashStyle: 'dash',
				label: {
                    text: 'Partial data',
                    align: 'top',
                    x: 5,
					rotation: 0,
					style: {
						color: '#fff'
					}
                }
			}]
		}
		return chart;
	},
	
	styled : function(data) {
		var chart = this['channel-black'](data);
		chart.plotOptions.series.marker.fillColor = null;
		chart.plotOptions.series.marker.lineWidth = 4;
		chart.plotOptions.series.marker.lineColor = null;
		chart.yAxis.labels = {
			useHTML : true,
			style : {
				fontSize : '13px',
				color: '#fff'
			}
		};
		chart.xAxis.lineWidth = 1;
		chart.xAxis.lineColor = 'rgba(255,255,255, 0.1)';
		return chart;
	}
};

widgets.charts = charts;
