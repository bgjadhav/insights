var Dashboard = function(section, url, title) {
	var private = {
		title : title,
		page : 0,
		section : section,
		url : (url === undefined) ? url =  '../../../' : url,
		type : 'table', // default is table
		oldtype : 'table', // default is table
		charts : [],
		pid: 0,
		toHide : [],
		sumTotal: false,
		success: 0,
		uniqueFilter : [],
		search : {},
		firstTrack: true,
		continueDownload: true
	}
	return {
		// remove special characters
		safename : function(name) {
			name = name.split(' ').join('_')
				.split('.').join('')
				.split('(').join('')
				.split(')').join('')
				.split('!').join('')
				.split('+').join('');
			return name;
		},
		formatid : function(id) {
			if(id.substring(0, 2) == 'xx') {
				return id.substring(2);
			} else {
				return id;
			}
		},
		// create the wrapper used for every type of page
		createwrapper : function(data) {
			var self = this;
			data.title = private.title;

			private.type = data.type[0];
			this.oldtype = private.type;
			data.filtersarray = [];
			if(sessionStorage.getItem('pid') == null){
				self.pid = data.pid;
				sessionStorage.setItem('pid', self.pid);
			}
			private.pid = sessionStorage.getItem('pid');

			var i = 0;
			// check if any dropdown elements are disabled
			$.each(data.filters, function(key1, filter) {
				key1 = key1.split('_').join(' ');
				var lookup = [];
				data.filtersarray[key1] = [];

				if(filter.length) {
					var array = true;
					filtered = filter[0];
				} else {
					filtered = filter;
				}
				var i = 0;
				$.each(filtered, function(key2, value) {
					data.filtersarray[key1][i] = [];
					lookup[self.formatid(key2)] = i;
					i++;
				});
				i = 0;
				$.each(filtered, function(key2, value) {
					data.filtersarray[key1][i].id = self.formatid(key2);
					data.filtersarray[key1][i].name = value;
					data.filtersarray[key1][i].checked = 'checked';
					i++;
				});
				i = 0;
				if(array) {
					$.each(filter[1], function(key2, value) {
						if (typeof lookup[value] != 'undefined') {
							data.filtersarray[key1][lookup[value]].checked = '';
						} else {
							$.each(data.filtersarray[key1], function(idForce, valueForce) {
								if ('xx'+data.filtersarray[key1][idForce]['id'] == value) {
									data.filtersarray[key1][idForce]['checked'] = '';
								}
							});
						}
						i++;
					});
				}
			});

			data.filtersarray = $.extend({}, data.filtersarray);
			if(Object.keys(data.filtersarray).length == 0) {
				data.filtersarray = false;
			}

			private.uniqueFilter = data.uniqueFilter;
			Handlebars.registerHelper('Has_Alls', function(index) {
				if (private.uniqueFilter.indexOf(index) !== -1 ) {
					return '';
				} else {
					return self.liClearAll();
				}
			});

			Handlebars.registerHelper('ifPodLeng', function(v1, v2) {
			  if(v1 === v2) {
				return '<span id="legendChart"></span>';
			  }
			  return '<span id="legend"></span>';
			});

			Handlebars.registerHelper('ifNoPodLeng', function(v1, v2) {
			  if(v1 === v2) {
				return '';
			  }
			  return '<span id="legendChart"></span>';
			});

			private.toHide = data.hide;

			if(data.report_status == '0') {
				data.report_status = null;
			}

			// load container
			var source = $("#container-template").html();
			var template = Handlebars.compile(source);

			var namesType = [];
			namesType['table'] = 'Table';
			namesType['mix'] = 'Mix';
			namesType['chart-column'] = 'Columns';
			namesType['chart-pie'] = 'Pie';
			namesType['chart-area'] = 'Area';
			namesType['chart-line'] = 'Line';
			data.nameTypesarray = [];
			var j = 0;
			$.each(data.type, function(key1, val) {
				data.nameTypesarray[j] = {'id':  val, 'name':namesType[val]};
				j++;
			});

			data.id = private.section;
			var html = template(data);// generate the html

			// update html
			$('#'+private.section).addClass(data.type[0]);
			$('#'+private.section).html(html);
			$('#'+private.section).find('.search-icon').hide();
			if(private.section == 'GlobalPodAnalyticsOrgAdv'
				|| private.section == 'GlobalPodAnalyticsUsage'
				|| private.section == 'GlobalPodAnalyticsCampaign'){
				$('#'+private.section).find('#Organization').hide();
				$('#'+private.section).find('.pod-dropdown').hide();
			}
			if(data.type[0] == 'table' && data.search != false) {
				$('#'+private.section).find('.search-icon').show();
				data.search = true;
			}
			var source = $("#table-template").html();// generate loading table
			var table = Handlebars.compile(source);
			private.table = table;// save loading table
			$('#'+private.section).find('.results').html(table);// add loading table
			this.prepareWrapper(data.type);// preparing standard
			this.buttons();// add button/ date functions
			if(data.report_status) {
				// get the data
				this.update();
			}
			
			$.each(data.filters, function(key, value) {
				if(value[2] == 'invert') {
					var input = $('input').filter(function() { return this.value == value[1] });
					input.click();
				}
			});
				//$('input').
				//data.filters[1]
		},
		liClearAll: function(){
			return '<li class="options"><span class="select">Select All</span><span class="clear">Clear All</span></li>';
		},
		prepareWrapper: function(types){
			if(types.length==1){
				$('#'+private.section).find('.UserOptions').hide();
			}
			$('#'+private.section).find('.type li span').hide();
			$('#moreDash'+private.section).hide();
			$('#paginationDash'+private.section).hide();
			$('#paginationStopDash'+private.section).hide();
		},
		// save the search
		updatesearch : function() {
			private.search = {};
			$('#'+private.section).find('.search input').each(function(key, value) {
				var name = $(this).data('name');
				var value = $(this).val();
				private.search[name] = value;
			});
		},
		createtable : function(type, data) {
			var self = this;
			var section = $('#'+private.section);
			self.prepareTable();

			if(typeof data.filters != 'undefined' && typeof data.filters.Columns != 'undefined' && data.filters.Columns[0] == '') {
				self.noResult();
			}
			else {
				if (typeof data.filters != 'undefined'){
					data.filters = $.param(data.filters);
				}
				private.ajax = $.ajax({
					type: "POST",
					url: private.url + type + '/' + private.section,
					data: data,
					success: function(json) {
						//~ console.log(json);
						//~ return false;
						self.notice(json.options.notice, json.notice);
						self.ufView(json.ufView);
						self.legend(json.legend, 'legend');
						self.showSearch(json.options.search);
						self.emptyTable();

						if (json.data.length > 0) {
							if(json.data.length >= 100 && json.options.pagination){
								$('#paginationDash'+private.section).show();
							}
							tColumn = self.tColumn(json.columns);
							columnsToTrack = tColumn[1];

							self.fillTable(json, section, tColumn, json.options);

							self.tAddTotalSearch();
							self.tTotals(json.options.total, tColumn[1], json.totals, json.formats);
							self.searchBoxes();
							self.tSearch();
							self.plugins();
						} else {
							self.noResult();
							json.columns = {};
						}

						self.track('table', self.tOptionTrack(json.columns), self.tSuccessTrack());
						self.exportShow();

						if (private.section == 'DBOpenActivity_PRDREQ' || private.section == 'DBOpenActivitySubType') {
							$(window).focus();
							$(window).blur();
						}
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						console.log(XMLHttpRequest.responseText);
						//self.showError();
						//self.track('table', self.tOptionTrack({}), '0');
						self.exportHide();
					}
				});
			}
		},
		fillTable: function (json, section, tColumn, options) {
			var self = this;
			var scroll = "560px";
			if (typeof options.scrollY != 'undefined' && options.scrollY != true) {
				scroll =  options.scrollY;
			}

			if (self.jiraPerformance() === true) {
				scroll = '350px';
			}

			private.DataTable = section.find('table.main').DataTable({
				aaData:			json.data,
				aoColumns:		tColumn[1],
				scrollCollapse:	false,
				columnDefs: [
					{
						render: function (data, type, row, colCurrent) {
							return self.format(row[colCurrent.col], json.formats[colCurrent.col]);
						},
						targets: self.tColumnFormat(json.formats)
					},
					self.tGroup(options.group)
				],
				scrollY:		scroll,
				sScrollX: 		"100%",
				sDom: 			"rtS",
				bDeferRender: 	true,
				deferRender: 	true,
				order: 			self.tOrder(options.order),
				drawCallback: function ( settings ) {
					if (options.group) {
						var api = this.api();
						var rows = api.rows( {page:'current'} ).nodes();
						var last=null;

						api.column(1, {page:'current'} ).data().each(function(group, i){
							if (last !== group) {
								$(rows).eq(i).before('<tr class="group"><td colspan="'+tColumn[0]+'">'+group+'</td></tr>');
								last = group;
							}
						});
					}
				}
			});

			if (self.jiraPerformance() === false) {
				if (scroll != '560px' && private.section != 'ToplineFinancials') {
					$('#'+private.section).css("min-height", scroll);
					$('#main #'+private.section+" .results").height(scroll);
				}
			}
		},

		jiraPerformance: function () {
			if (private.section == 'DBResolutionAnalytics' || private.section == 'DBOpenActivity_PRDREQ') {
				return true;
			} else {
				return false;
			}
		},

		addrowstable : function(type, data) {
			var self = this;
			var section = $('#'+private.section);
			data.filters = $.param(data.filters);
			private.ajax = $.ajax({
				type: "POST",
				url: private.url + type + '/' + private.section,
				data: data,
				success: function(json) {
					if (json.data.length > 0) {
						private.DataTable.rows.add(json.data).invalidate().draw(false);
						if(json.data.length == 100) {
							$('#moreDash'+private.section).hide('slow', function(){
								$('#paginationDash'+private.section).show('slow');
							});
						} else {
							self.endMore();
						}
					} else {
						self.endMore();
					}
					self.exportShow();
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					//self.showError();
					console.log(XMLHttpRequest.responseText);
					self.exportHide();
				}
			});
		},

		createsmalltable : function(data) {
			var self = this;
			var section = $('#'+private.section);
			section.find('.results').empty();
			var source = $("#table-small-template").html();
			var table = Handlebars.compile(source);
			var info = {columns : Array(), rows : Array()}
			var i = 0;

			$.each(data.data, function(key, value) {
				var types = ['Yesterday', 'Last 7 Days'];
				section.find('.results').append('<div class="small-table-holder"><h1>' + key + '</h1></div>');

				$.each(types, function(id, type) {
					var info = {
						title : key,
						columns : value.Columns,
						rows : Array()
					}
					$.each(value[type], function(key2, value2) {
						info.rows.push(value2);
					});
					section.find('.small-table-holder').eq(i).append(table(info));
				});
				i++;
			});

			if (data.notice.message!= '') {
				self.showNotice('.results', data.notice.status, data.notice.title, data.notice.message);
			}
		},

		createbasictable: function(data, section, title, i, formats, fullWidth) {
			var self = this;
			//format
			$.each(data.rows, function(ind, thisRow) {
				$.each(thisRow, function(id, row) {
					var origin = row;
					if (typeof formats[id] != 'undefined') {
						data.rows[ind][id] = self.format(origin, formats[id]);
					}
					if (typeof data.totals[id] != 'undefined') {
						if (data.totals[id] !=='') {
							data.totals[id] = data.totals[id] + origin;
						}
					} else {
						data.totals[id] = '';
					}
				});
			});

			//format Totals
			if (typeof data.totals != 'undefined') {
				$.each(data.totals, function(id, thisRow) {
					if (typeof formats[id] != 'undefined') {
						data.totals[id] = self.format(data.totals[id], formats[id]);
					}
				});
			}

			var self = this;
			var source = $("#table-small-template").html();
			var table = Handlebars.compile(source);
			var classFull = fullWidth == true ? ' full-width' : '';
			section.find('.results').append('<div class="small-table-holder'+classFull+'"><h1>'+data.title+'</h1></div>');
			section.find('.small-table-holder').eq(i).append(table(data));
		},
		createarea : function(data) {
			// generate chart template
			var source = $("#chart-template").html();
			var template = Handlebars.compile(source);
			var section = $('#'+private.section);

			var self = this;
			var statuses = ['RED', 'ORANGE', 'BLUE', 'GREEN'];

			section.find('.results').empty();
			// new row for each colour chart
			$.each(statuses, function(key, value) {
				section.find('.results').addClass('charts').append('<div id="status_' + value + '" class="row"></div>');
			});

			this.notice('.results', data.notice.status, data.notice.title, data.notice.message);

			// for each chart
			$.each(data.data, function(key, value) {
				// generate a safe name for id
				value.id=key;
				value.key=value.title;

				// should we add a critic?
				value.label_critic = '';
				if(value.status !='GREEN') {
					value.label_critic = value.critic + '*';
				}

				// append chart html
				var html = template(value);
				section.find('#status_' + value.status).append(html);

				// round the numbers and calculate a total
				var total = 0;
				var floatdata = [];
				$.each(value.data, function(key2, value2) {
					floatdata.push(parseFloat(value2));
					total =+ parseFloat(value2);
				});

				// only display if there has been data in the last x days
				if(total > 0) {
					// set chart background colour
					var backgroundColor = section.find('#chart_' + value.id).css('background-color');

					// create highcharts chart and store it for later use
					private.charts[value.id] = section.find('#chart_' + value.id + ' .holder').highcharts({
						chart: {
							type: 'area',
							backgroundColor: backgroundColor,
							margin: [0,-22,0,-22],
							width: 304,
							height: 117
						},
						rangeSelector : {enabled: false},
						navigator: {enabled: false},
						credits : {enabled: false},
						scrollbar : {enabled: false},
						title : {
							align: 'left',
							text : false
						},
						navigation: {
							buttonOptions: {enabled: false}
						},
						yAxis: {
							title: {text: ''},
							min:0.00001,
							gridLineColor: 'rgba(255,255,255,0.3)',
							labels: {
								x: 324,
								y: -3,
								style : {
									color: '#fff'
								}
							},
						},
						xAxis: {
							plotLines: [{
								events: {}
							  }],
							title: {text: ''},
							categories: data.categories[key],
							labels: {
								align: 'center',
								x: 25,
								y: -25,
								style : {
									color: '#fff'
								},
								formatter: function() {
									var value = this.value.replace(' ', String.fromCharCode(160));
									if (this.axis.categories.length <= 5) {
										return value;
									} else if (this.isFirst || this.isLast) {
										return '<span style="text-wrap:no-wrap;">'+value+'</span>';
									} else {
										var index  = this.axis.categories.indexOf(this.value);
										var second = Math.round(this.axis.categories.length/3);
										var thirth = Math.round(this.axis.categories.length/1.5);
										if (index == second || index == thirth) {
											return '<span style="text-wrap:no-wrap;">'+value+'</span>';
										}
										return '';
									}
								}
							}
						},
						plotOptions: {
							series: {
								fillOpacity: 0.4
							}
						},
						tooltip: {
							pointFormat: data.texts[0],
							crosshairs: [true, false]
						},
						legend: false,
						series : [{
							color: 'rgba(255,255,255,0.03)',
							data: floatdata,
							tooltip : {
							   valueDecimals : 2,
								borderWidth: 1,
							}
						}]
					});

					// chart exports
					section.find('#chart_' + value.id + ' .export').on('click', function(event) {
						section.find('#chart_' + value.id + ' .holder').toggleClass('active');
						section.find('#chart_' + value.id + ' .options').toggleClass('active');
					});

					// export the chart
					section.find('#chart_' + value.id + ' .options li').on('click', function(event) {
						var type = $(this).data('type');
						private.charts[value.id].highcharts().exportChart({
							type: type,
							filename: key
						},{
							title : {
								text : key,
								style : {
									color: '#ffffff'
								}
							}
						});
					});
				} else {
					// no spend
					section.find('#chart_' + value.id + ' .content').html('<div class="error">No data.</div>');
				}
			});
		},
		createcolumn : function(data) {
			var source = $("#chart-template").html();
			var template = Handlebars.compile(source);

			var self = this;
			var section = $('#'+private.section);
			section.find('.search-icon').hide();
			section.find('.results').empty().addClass('charts').append('<div id="status" class="row"></div>');

			this.notice('.results', data.notice.status, data.notice.title, data.notice.message);
			var results = [];

			$.each(data.data, function(key, value) {
				value.id=key;
				value.key=value.title;
				var html = template(value);
				section.find('#status').append(html);

				private.charts[key] = section.find('#chart_' + key + ' .holder').highcharts( {
					chart: {
						type : 'column',
						backgroundColor: '#5faeec',
						margin: [0,0,1,0],
						width: 304,
						height: 117
					},
					colors: ['rgba(255,255,255,0.3)'],
					exporting: {enabled: false},
					credits: {enabled: false},
					title: {text: ''},
					tooltip: {
						formatter: function() {
							var value = this.y;
							return '<b>' + this.x + '</b> '+data.texts[0]+' <br /> <b>' + value.toFixed(2) + '</b>'+data.texts[1];
						}
					},
					yAxis: {
						title: {text: ''},
						min:0.00001,
						gridLineColor: 'rgba(255,255,255,0.4)',
						labels: {
							align: 'left',
							x: 2,
							y: 11,
							style : {
								color: 'rgba(255,255,255,0.9)'
							}
						},
					},
					xAxis: {
						min : 0,
						dateTimeLabelFormats: {
							day: '%e %b'
						},
						labels: {
							align: 'top',
							x: -6,
							y: -5,
							style : {
								color: 'rgba(255,255,255,0.9)',
								textAlign: 'center'
							},
							step: 1
						},
						categories: data.categories
					},
					legend: false,
					plotOptions: {
						column: {
							borderWidth: 0,
							pointPadding: 0,
                    		borderWidth: 0
						}
					},
					series: [{
						name: '%',
						data: value.values
					}]
				});
			});
		},
		createpie : function(data) {
			// generate chart template
			var source = $("#chart-template").html();
			var template = Handlebars.compile(source);

			var self = this;
			var section = $('#'+private.section);
			section.find('.results').empty().addClass('charts').append('<div id="status" class="row"></div>');

			this.notice('.results', data.notice.status, data.notice.title, data.notice.message);
			var results = [];

			$.each(data.data, function(key, value) {
				value.id=key;
				value.key=value.title;
				var html = template(value);
				section.find('#status').append(html);

				private.charts[key] = section.find('#chart_' + key + ' .holder').highcharts( {
					chart: {
						type : 'pie',
						margin: [0,0,1,0],
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false
					},
					colors: ['#FFAD08','#EDD75A', '#73B06F', '#0C8F8F', '#405059', '#CCCCCC', '#C7E4CF', '#4F596B', '#A89E86'],
					exporting: {enabled: false},
					credits: {enabled: false },
					title: {text: ''},
					legend: false,
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {enabled: false},
							showInLegend: true
						}
					},
					series: [{
						name: data.texts[0],
						data: value.values
					}]
				});
			});
		},
		createline : function(data) {
			var source = $("#chart-template").html();
			var template = Handlebars.compile(source);
			var self = this;
			var section = $('#'+private.section);
			section.find('.results').empty().addClass('charts lines').append('<div id="status" class="row"></div>');
			self.notice('.results', data.notice.status, data.notice.title, data.notice.message);
			var results = [];
			self.legend(data.legend, 'legendChart');

			$.each(data.data, function(key, value) {

				if (key=='id') return false;
				value.id=key;

				var html = template(value);
				section.find('#status').append(html);

				var title = '';
				if ( typeof data.title !== 'undefined') {
					$.each(data.title, function (ii, n) {
						title = title+'<span class="title" id="'+self.safename(n)+'_'+key+'"> '+n+' </span>';
					});
				}

				var prefix = '';
				var legend = true;
				var border = 0;
				var axisColor = '#fff';
				var marker = true;
				var bgColor = 'rgba(255, 255, 255, 0)';
				var colors = ['#FFAD08','#EDD75A', '#73B06F', '#0C8F8F', '#405059', '#CCCCCC', '#C7E4CF', '#4F596B', '#A89E86'];

				if (data.two_charts == true) {
					legend = false;
					border = 1;
					marker = false;


					if (private.section == 'GlobalPodAnalytics') {
						bgColor = {
							linearGradient : [255,255, 255, 100],
								stops : [
									[0, 'rgb(232, 250, 254)'],
									[1, 'rgb(255, 255, 255)']
								]
						};
						axisColor = '#93cede';
					}

					if (key == 1) {
						prefix = '$';
					}
					var margin = [10,-20,50,35];
					colors = [];

					$.each(data.data[key], function (i, val) {
						data.data[key][i]['dashStyle'] = 'Dash';
						switch (data.data[key][i]['name']) {
							case 'Global_Deal':
								colors.push('#de396e');
								break;
							case 'OA':
								colors.push('#f69f1a');
								break;
							case 'PMP-D':
								colors.push('#62b762');
								data.data[key][i]['name'] = 'PMPD';
								break;
							case 'PMP-E':
								colors.push('#4a9cef');
								data.data[key][i]['name'] = 'PMPE';
								break;
							case 'PMP-E':
								colors.push('#4a9cef');
								data.data[key][i]['name'] = 'PMPE';
								break;
							case 'Any Opto':
								colors.push('#d1d2d4');
								data.data[key][i]['name'] = 'Any Opto';
								data.data[key][i]['dashStyle'] = 'Solid';
								break;
							case 'No Opto':
								colors.push('#fff');
								data.data[key][i]['dashStyle'] = 'LongDash';
								data.data[key][i]['name'] = 'No Opto';
								break;
							case 'Right Brain':
								colors.push('#e2a726');
								data.data[key][i]['name'] = 'Right Brain';
								data.data[key][i]['dashStyle'] = 'Solid';
								break;
							default:
								colors.push('#fff');
								data.data[key][i]['name'] = 'NoName';
								break;
						}
					});
				}

				private.charts[key] = section.find('#chart_'+key+' .holder').highcharts({
					chart: {
						renderTo: 'container',
						backgroundColor: bgColor,
						borderColor: '#9fd4f8',
						borderWidth: border,
						margin: margin,
						plotBorderColor: '#9fd4f8',
						type: 'spline'
					},
					colors: colors,
					exporting: {enabled: false},
					credits: {enabled: false},
					title: {
						useHTML: true,
						align : 'right',
						x: 0,
						y: 20,
						text: title
					},
					legend: {
						layout: 'vertical',
						align: 'right',
						verticalAlign: 'middle',
						enabled: legend,
						borderWidth: 0,
						itemStyle: {
							color: '#fff'
						},
					},
					plotOptions: {
						series: {
							marker: {
								enabled: marker
							}
						},
						 line: {
								connectNulls: false
							}
					},
					yAxis: {
						min:0.00001,
						max: data.max,
						tickColor: axisColor,
						gridLineColor: 'rgba(255,255,255,0.1)',
						showFirstLabel: false,
						labels: {
							style: {
								color: axisColor,
								width: '30px'
							},
							formatter: function () {
								return self.format(this.value, data.scale);
							},
							overflow: 'justify',
							y: +15,
							x: +20
						},
						title: {text: ''}
					},
					lang: {
						noData: 'No data'
					},
					xAxis : {
						tickColor: 'rgba(255,255,255,0.2)',
						categories: data.categories,
						lineColor: 'rgba(255,255,255,0.2)',
						showLastLabel: false,
						ordinal: true,
						labels: {
							y: +30,
							//x: -2,
							style: {
								color: axisColor
							}
						}
					},
					tooltip: {
						formatter: function() {
							var val = self.format(this.y, data.format);

							if (prefix=='$') {
								val = self.format(this.y, 'money');
							}

//							if (data.format_Y) {
//								val = this.y;
//								return this.series.name+data.text[key][this.series.name][this.y]+' on <b>' + this.x + '</b>';
//							}
							return this.series.name+data.text+'<b>'+val+'</b><br>on <b>' + this.x + '</b>';
						}
					},
					series: data.data[key]
				},
				function(chart) {

					if (data.two_charts == true) {

						if (chart.colorCounter == 0 && data.data[key][0]['data'].length > 0) {
							chart.symbolCounter = 0;
							$.each(data.data[key], function (i, val) {
								if (i=='id') return false;
								chart.addSeries({
									dashStyle: data.data[key][i]['dashStyle'],
									name: data.data[key][i]['name'],
									data: data.data[key][i]['data']
								});
							});
						}

						$('.highcharts-title').on('click', '#'+self.safename(data.title[1])+'_0', function() {
							$('#'+private.section).find('#chart_0').hide('fast', function() {
								$('#'+private.section).find('#chart_1').show('fast');
							});
						});
						$('.highcharts-title').on('click', '#'+self.safename(data.title[0])+'_1', function() {
							$('#'+private.section).find('#chart_1').hide('fast',  function() {
								$('#'+private.section).find('#chart_0').show('fast');
							});
						});
					}

				});
			});

			if (data.two_charts == true) {
				$('#'+private.section).find('#chart_0').hide();
			}
		},
		createstack : function(json, key) {
			var source = $("#chart-template").html();
			var template = Handlebars.compile(source);
			var self = this;
			var section = $('#' + key);
			var html = template(json);
			section.find('#status').append(html);

			private.charts[json.id] = section.find('#chart_'+ json.id+' .holder').highcharts( {
				chart: {
					renderTo: 'container',
					plotBorderWidth: 1,
					width: 550,
					margin: [80,1,22,55],
					borderWidth: 0,
					plotBackgroundColor: {
						linearGradient : [255,255, 255, 100],
						stops : [
							[0, 'rgb(232, 250, 254)'],
							[1, 'rgb(255, 255, 255)']
						]
					},
					plotBorderColor: '#add3dc'
				},
				colors: ['#e46c5b', '#62baf9', '#add3dc'],
				credits: {
					enabled: false
				},
				xAxis : {
					categories: json.categories,
					gridLineWidth: 0,
					minorGridLineWidth: 0,
					labels: {
						style: {
							color: '#4bb7de'
						}
					},
					lineWidth: 0,
					minorGridLineWidth: 0,
					lineColor: 'transparent',
					minorTickLength: 0,
					tickLength: 0,
					minPadding:0,
					maxPadding:0
				},
				exporting: {
					enabled: false
				},
				yAxis: [{
					min: 0,
					max: null,
					gridLineWidth: 0,
					minorGridLineWidth: 0,
					labels: {
						formatter: function () {
						   return '$'+this.value;
						},
						style: {
							color: '#4bb7de'
						},
						overflow: 'justify'
					},
					title: {
						text: ''
					},
					plotLines: [{
						value: json.data[2].data[0],
						color: '#add3dc',
						dashStyle: 'longdash',
						width: 4,
						label: {
							text: json.data[2].data[0] ==0 ? '' : json.data[2].name,
							style: {
								color: '#4bb7de'
							},
							verticalAlign: 'bottom',
							align: 'right',
							y: +14,
							x:-5
						}
					}]
				}],
				legend: {
					borderColor: '#add3dc',
					borderWidth: 1,
					symbolWidth: 20,
					symbolHeight: 20,
					width: 466,
					backgroundColor: '#fff',
					layout: 'horizontal',
					floating: true,
					align: 'center',
					verticalAlign: 'top',
					x: 12,
					padding: 30,
					y: -10,
					itemWidth : 220,
					itemDistance: 10,
					itemStyle : {
						color: '#4bb7de'
					},
					margin : 0
				},
				tooltip: {
					enabled: true,
					shared: true,
					borderWidth: 1,
					borderRadius: 8,
					shadow: false,
					useHTML: true,
					crosshairs: true,
					padding: 0,
					followTouchMove: true,
					valueDecimals: 2,
					headerFormat :'<table>',
					pointFormat: '<tr><td style="font-weight: bold;">{series.name}:</td><td>${point.y}</td></tr>',
					footerFormat: '<tr><td style="font-weight: bold;">'+json.data[2].name+':</td><td>'+self.format(json.data[2].data[0], 'money')+'</td></tr></table>'
				},
				plotOptions: {
					column: {
						grouping: false,
						shadow: false,
						borderWidth: 0,
						stacking: 'normal',
						cursor: 'pointer'
					},
				},
				series: [{
						name: json.data[1].name,
						type: 'column',
						data: json.data[1].data,
						stack: 'male'
					}, {
						name: json.data[0].name,
						type: 'column',
						data: json.data[0].data,
						stack: 'male'
					}
				]
			},
			function(chart){
				$.each(chart.series,function(i,serie){
					if (serie.name == 'Series 1')
						serie.legendGroup.destroy();
				});
			});
		},
		createmix : function(data) {
			var self = this;
			var section = $('#'+private.section);
			section.find('.results').empty();
			var i = 0;
			$.each(data.data, function(key, report) {
				if (report.type == 'basictable') {
					self.createbasictable(report.table, section, report.title, i, report.formats, report.full);
				} else if (report.type == 'stack') {
					var newsection = self.safename(report.title);
					section.find('.results').addClass('stack').append('<div id="'+newsection+'"><h1>'+report.title+'</h1><div id="status"></div></div>');
					self.createstack(report, newsection);
				}
				i++;
			});
		},
		// called each time a filter or date is changed.
		update : function(returnurl) {
			var self = this;
			if (private.ajax) {
				private.ajax.abort();
			}
			var options = {}
			var section = $('#'+private.section);
			var sumTotal = true;
			options.filters = {};

			if (returnurl != 'download') {
				section.find('.results').removeClass('charts').removeClass('lines');
			}

			if (section.find('.pod-dropdown').length > 0) {
				var pods = [];
				section.find('.pod-dropdown').each(function() {
					$('input', this).each(function() {
						if($(this).prop('checked') && $(this).val() != 'on') {
							pods.push($(this).val());
						}
					});
				});
				options.filters['pods'] = pods;
				private.pods = options.filters['pods'];
			}

			// get start date
			if (section.find('.date .start').length > 0) {
				options.date_start = section.find('.date .start').val();
				// get end date
				if(section.find('.date .end').length > 0) {
					options.date_end = section.find('.date .end').val();
				}
				if (options.date_end < options.date_start) {
					alert('End date can\'t be lower that start date');
					return false;
				}
			}

			// get range
			if (section.find('.range_selector').length > 0) {
				options.range_selector = section.find('.range_selector .active').text().replace(' ', '_').toLowerCase().replace(' ', '_');
			}

			if (section.find('.type').length > 0) {
				options.type = section.find('.type .active span').text();
				private.type = options.type;
			}

			if (section.find('.optionsType').length > 0) {
				options.optionType = section.find('.optionsType .active  span').text();
				private.optionType = options.optionType;
			}

			if (section.find('.checkboxes').length > 0) {
				section.find('.checkboxes').each(function() {
					$('.checkboxes-row', this).each(function() {
						var checkboxes = [];
						var name = $(this).attr('id');
						name = name.replace('checkboxrow_', '');
						$('input', this).each(function() {
							if($(this).prop('checked')) {
								checkboxes.push($(this).val());
							}
						});
						options.filters[name] = checkboxes;
					});
				});
				private.checkboxes = section.find('.checkboxes input').serialize();
			}

			// get custom filters
			if(section.find('.filters').length > 0) {
				section.find('.filters .dropdown').each(function() {
					var name = $('span.name', this).text().trim();
					name = name.split(' ').join('_');
					var filters = [];
					var checked = 0;
					var length = 0;
					$('input', this).each(function() {
						if($(this).prop('checked')) {
							filters.push($(this).val());
							checked++;
						}
						length++;
					});
					options.filters[name] = filters;
					if(filters.length == 0) {
						options.filters[name] = [''];
					}

					if (sumTotal == true && name != 'Columns' && length != checked) {
						sumTotal = false;
					}

				});
			}

			options.sumTotal = sumTotal ? 1 : 0;

			if(returnurl) {
				options.pid = private.pid;
				return options;
			} else {
				options.page = private.page;
				if (private.page == 0) {
					// change to loading screen
					section.find('.results').empty().html(private.table);
				}
				// get updated data
				this.getdata('data', options);
			}
		},
		getdata : function(type, data) {
			// url must be defined
			if(data === undefined) { data = ''; }
			var self = this;
			data.pid = private.pid;
			self.initGetData();
			this.optionType = private.optionType;
			var changeOptionT = false;
			if(this.oldtype != 'undefined' && (this.oldtype != private.type)){
				$('#'+private.section).find('#optionType').empty();
				changeOptionT = true;
				private.optionType = '';
				this.optionType= '';
				data.optionType = '';
			}
			this.oldtype = private.type;
			self.exportHide();

			$.each(private.toHide, function(key, values) {
				$.each(values, function(key2, idOption) {
					$('#'+idOption).show();
				});
			});

			if (private.toHide.hasOwnProperty(private.type)) {
				values = private.toHide[private.type];
				if (private.toHide.hasOwnProperty(private.type)) {
					$.each(values, function(key2, idOption) {
						$('#'+idOption+'').hide();
					});
				}
			}

			// decide which type of section to generate
			if(private.type == 'table' && type == 'data') {
				if (private.page==0) {
					self.createtable(type, data);
					self.resizeTable();
				} else {
					self.addrowstable(type, data);
					self.resizeTable();
				}
			} else {
				if(typeof data.filters != 'undefined') {
					data.filters = $.param(data.filters);
				}
				private.ajax = $.ajax({
					cache: false,
					url: private.url + type + '/' + private.section,
					data: data,
					dataType: "json",
					type: 'POST',
					success: function(json) {
						$('#'+private.section).removeClass('loading');
						if(type == 'wrapper') {
							self.createwrapper(json);
						} else if(type == 'data') {
							if(private.type == 'table') {
								if (private.page==0) {
									self.createtable(json);
								} else {
									self.addrowstable(json);
								}
							} else if(private.type == 'chart-area') {
								self.createarea(json);
							} else if(private.type == 'small-table') {
								self.createsmalltable(json);
							} else if(private.type == 'chart-column') {
								self.createcolumn(json);
							} else if(private.type == 'chart-pie') {
								self.createpie(json);
							} else if(private.type == 'chart-line') {
								self.createline(json);
							} else if(private.type == 'mix') {
								self.createmix(json);
							}
						}
						self.resizeTable();
						self.exportShow();
						if(typeof json.ufView != 'undefined' && Object.keys(json.ufView).length>=2 && (changeOptionT || $('#'+private.section).find('#optionType li').size() < 1) ) {
							self.ufViewLi(json.ufView);
						}
						if(typeof json.ufView != 'undefined' && Object.keys(json.ufView).length<2){
							$('#'+private.section).find('.optionsType').hide();
						}

						if (type != 'wrapper' && private.type != 'table' && private.type != 'small-table') {
							self.track(private.type, self.gOptionTrack(), self.gSuccessTrack());
						}
						if (private.type == 'small-table') {
							self.track('small-table', {}, self.tSuccessTrack());
						}
						self.exportShow();
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						//self.showError();
						console.log(XMLHttpRequest.responseText);
						self.exportHide();
					}
				});
			}
		},
		buttons : function() {
			var self = this;
			var section = $('#'+private.section);

			section.on('click', '#okMessage'+private.section, function() {
				self.clearMesssage();
			});

			section.on('click', '#cancelDownload'+private.section, function() {
				if (private.ajax) {
					private.ajax.abort();
				}
				self.stopPid();
				self.clearMesssage();
				private.continueDownload = false;
			});

			section.on('click', '#paginationDash'+private.section, function(e) {
				$('#paginationDash'+private.section).hide('fast', function() {
					$('#moreDash'+private.section).show('slow' , function() {
						private.page = private.page + 1;
						self.update();
					});
				});
			});

			// on page resize
			$(window).resize(function() {
				self.resizeTable();
			});

			// on range selector change
			section.on('click', '.range_selector li', function() {
				self.resetPage();
				$('.range_selector li', section).removeClass('active');
				$(this).addClass('active');
				self.update();
			});

			// dropdowns
			// slide down dropdowns
			section.on('click', 'span.name', function(e) {
				e.stopPropagation();
				$(this).next('ul').slideToggle();
			});

			section.on('click', '.dropdown li', function(e) {
				self.resetPage();
				e.stopPropagation();
			});

			// close dropdowns if clicked anywhere else
			$('html').click(function() {
				if($('.dropdown ul').height() > 10) {
					$('.dropdown ul').slideUp();
				}
				if($('.pod-dropdown>ul').height() > 10) {
					$('.pod-dropdown>ul').slideUp();
				}
			});

			// select all inputs
			section.on('click', '.options .select', function(e) {
				self.resetPage();
				e.stopPropagation();
				var parent = $(this).parents('ul');
				parent.find('li input').prop('checked', true);
				if (private.section == 'GlobalPodAnalytics' && $(this).parents('ul').attr('class') == 'Organization') {
					$("#GlobalPodAnalyticsOrgAdv").find('.Organization .options .select').trigger('click');
				} else if (private.section == 'GlobalPodAnalyticsOrgAdv' && $(this).parents('ul').attr('class') == 'Organization') {
					$("#GlobalPodAnalyticsUsage").find('.Organization .options .select').trigger('click');
				} else if (private.section == 'GlobalPodAnalyticsUsage' && $(this).parents('ul').attr('class') == 'Organization') {
					$("#GlobalPodAnalyticsCampaign").find('.Organization .options .select').trigger('click');
				}

				self.update();
			});

			// clear all inputs
			section.on('click', '.options .clear', function(e) {
				self.resetPage();
				e.stopPropagation();
				var parent = $(this).parents('ul');
				parent.find('li input').prop('checked', false);

				if (private.section == 'GlobalPodAnalytics' && $(this).parents('ul').attr('class') == 'Organization') {
					$("#GlobalPodAnalyticsOrgAdv").find('.Organization .options .clear').trigger('click');
				} else if (private.section == 'GlobalPodAnalyticsOrgAdv' && $(this).parents('ul').attr('class') == 'Organization') {
					$("#GlobalPodAnalyticsUsage").find('.Organization .options .clear').trigger('click');
				} else if (private.section == 'GlobalPodAnalyticsUsage' && $(this).parents('ul').attr('class') == 'Organization') {
					$("#GlobalPodAnalyticsCampaign").find('.Organization .options .clear').trigger('click');
				}
				//self.update();
			});

			// on dropdown change
			section.on('click', '.dropdown input', function(e) {
				self.resetPage();
				var parent = $(this).parents('ul');
				var text = $(this).next('label');
				if (private.uniqueFilter.indexOf(parent.attr('class')) !== -1 ) {
					var typeClick = false;
					if(e.type == 'Click'){
						typeClick = true;
					}
					parent.find('li input').prop('checked', false);
					$(this).prop('checked', true);
				}
				e.stopPropagation();
				
				if($('#GlobalAccountsDashboard')) {
					$('#legend').text($('.EAM input:checked').next('label').text() + ' - ' + $('.Week input:checked').next('label').text());
				}

				if (private.section == 'GlobalPodAnalytics' && parent.attr('class') == 'Organization') {
					var id = $(this).attr('id');
					$("#GlobalPodAnalyticsOrgAdv").find('.'+parent.attr('class')+' #'+id).trigger('click');
					$("#GlobalPodAnalyticsUsage").find('.'+parent.attr('class')+' #'+id).trigger('click');
					$("#GlobalPodAnalyticsCampaign").find('.'+parent.attr('class')+' #'+id).trigger('click');
				}
				self.update();
			});

			// set the default date
			section.find('div.date input.start').val(moment(section.find('div.date input.start').data('value')).format('YYYY-MM-DD'));
			section.find('div.date input.end').val(moment(section.find('div.date input.end').data('value')).format('YYYY-MM-DD'));
			var today = moment().toDate();
			// start date dropdown
			section.find('div.date input.start').pikaday({
				format: 'YYYY-MM-DD',
				maxDate: today,
				onSelect: function() {

					console.log(section.find('div.date input.start').pikaday());
					console.log(section.find('div.date input.start').data('value'));
					console.log();


					self.resetPage();
					self.update();
				}
			});
			// end date dropdown
			section.find('div.date input.end').pikaday({
				format: 'YYYY-MM-DD',
				maxDate: today,
				onSelect: function() {
					self.resetPage();
					self.update();
				}
			});

			// export with download file
			section.on('click', '#downloadData', function(e) {
				e.preventDefault();
				self.download();
			});

			section.on('click', '#GlobalPodAnalyticsNote', function(e) {
				$('#'+private.section).find('#legendChart').slideUp('slow');
			});

			// type view
			section.on('click', '.type li', function(e) {
				self.resetPage();
				$('.type li', section).removeClass('active');
				$(this).addClass('active');
				section.find('.type ul').slideToggle();
				self.update();
			});

			section.on('click', '.optionsType li', function(e) {
				self.resetPage();
				$('.optionsType li', section).removeClass('active');
				$(this).addClass('active');
				section.find('#optionType').slideToggle();
				self.update();
			});

			section.on('click', '.pod-dropdown ul li', function(e) {
				e.stopPropagation();
			});

			section.on('click', '.pod-dropdown ul li span input', function(e) {
				self.resetPage();
				var checked = $(this).prop('checked');
				var id = $(this).attr('id');
				$(this).parents('li').find('ul input').prop('checked', checked);
				$(this).find('#'+id).prop('checked', checked);

				if (private.section == 'GlobalPodAnalytics') {
					self.callPodOrg();
					$('#GlobalPodAnalyticsOrgAdv').find('#'+id).parents('li').find('ul input').prop('checked', checked);
					$("#GlobalPodAnalyticsOrgAdv").find('#'+id).trigger('click');
				} else if (private.section == 'GlobalPodAnalyticsOrgAdv' ) {
					$('#GlobalPodAnalyticsUsage').find('#'+id).parents('li').find('ul input').prop('checked', checked);
					$("#GlobalPodAnalyticsUsage").find('#'+id).trigger('click');
				} else if (private.section == 'GlobalPodAnalyticsUsage' ) {
					$('#GlobalPodAnalyticsCampaign').find('#'+id).parents('li').find('ul input').prop('checked', checked);
					$("#GlobalPodAnalyticsCampaign").find('#'+id).trigger('click');
				}

				self.update();
			});

			section.on('click', '.pod-dropdown ul li ul li input', function(e) {
				self.resetPage();
				var id = $(this).attr('id');
				if (private.section == 'GlobalPodAnalytics') {
					self.callPodOrg();
					$("#GlobalPodAnalyticsOrgAdv").find('#'+id).trigger('click');
				} else if (private.section == 'GlobalPodAnalyticsOrgAdv' ) {
					$("#GlobalPodAnalyticsUsage").find('#'+id).trigger('click');
				} else if (private.section == 'GlobalPodAnalyticsUsage' ) {
					$("#GlobalPodAnalyticsCampaign").find('#'+id).trigger('click');
				}
				self.update();
			});

			section.on('click', '.filters .dropdown span', function(e) {
				//self.resetPage();
				$('.UserOptions ul').slideUp();
			});

			section.on('click', '.checkboxes input', function(e) {
				self.resetPage();
				if (private.section == 'GlobalPodAnalytics') {
					var checked = 0;
					var id = $(this).parents('.checkboxes-row').attr('id');
					$('#'+id).each(function() {
						$('input', this).each(function() {
							if($(this).prop('checked')) {
								checked++;
							}
						});
					});
					if (checked == 0) {
						$(this).prop('checked', true);
					}
				}
				self.update();
			});

			// show search boxes
			section.on('click', '.search-icon', function() {
				if(section.find('.totals').hasClass("open")) {
					section.find('.totals').slideUp(function(){
						section.find('.search-boxes').slideDown();
						section.find('.totals').removeClass("open");
					});
				} else {
					if (section.find('.totals').length == 0) {
						section.find('.search-boxes').slideToggle();
					} else {
						section.find('.search-boxes').slideUp('slow', function(){
							section.find('.totals').slideDown('fast').addClass("open");
						});
					}
				}

			});

			// refresh page
			section.on('click', '.refresh', function() {
				self.update();
			});
		},
		callPodOrg: function() {
			var self = this;
			var section = $('#'+private.section);
			if (section.find('.pod-dropdown').length > 0) {
				var pods = [];
				section.find('.pod-dropdown').each(function() {
					$('input', this).each(function() {
						if($(this).prop('checked') && $(this).val() != 'on') {
							pods.push($(this).val());
						}
					});
				});
				self.getPodOrg(pods);
			}
		},
		download : function () {
			var self = this;
			var type = 'download';
			var data = self.getOptExport(type);
			var idExport = 0;
			var message = [];
			if($('#downloadData').data('type') == 'get') {
				window.location = private.url+'data'+'/'+private.section+'/'+type+'?'+data.filters+'&title='+$('#legend').text();
			} else {
				//message['title']
				self.showWait();
				private.ajax = $.ajax({
					url: private.url+'data'+'/'+private.section+'/'+type,
					data: data,
					cache: false,
					dataType: "json",
					type: 'POST',
					success: function(json) {
						self.analizeStatus(json);
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						self.showError();
						console.log(XMLHttpRequest.responseText);
					}
				});
			}
		},
		showError:  function(data) {
			this.showMessage({
				'title': "Opps, wasn't expecting that....",
				'message': 'An error has occured. Please try again in a few in minutes and if the problem still persists, contact open_analytics@mediamath.com',
				'extras': '<span id="okMessage'+private.section+'">OK</span>',
				'img': false,
				'visibility': 'visible'
			});
		},
		showWait: function(data) {
			this.showMessage({
				'title': 'Woah, Hold Your Horses...',
				'message': 'Exporting your data, in some cases this may take a few minutes...',
				'extras': '<span id="cancelDownload'+private.section+'">Cancel</span>',
				'img': '<img id="wait-blue" src="'+private.url + '../../_img/loading-blue.gif" />',
				'visibility': 'visible'
			});
		},
		clearMesssage: function(data) {
			this.showMessage({
				'title': '',
				'message': '',
				'extras': '',
				'img': '',
				'visibility': 'hidden'
			});
		},
		downloadFile: function(data) {
			var type = 'downloadFile';
			var self = this;
			$('.export-form form').empty();
			$('.export-form form').attr('action', private.url+'../'+type);
			$.each(data, function(key, value) {
				$('.export-form form').prepend('<input type="text" name="'+key+'" value="'+value+'" />');
			});
			$('.export-form form').submit();
			self.track('download', self.dOptionTrack(), '1');
			self.clearMesssage();
		},
		analizeStatus: function(json) {
			if (private.ajax) {private.ajax.abort();}
			var self = this;
			if (json.status=='ready') {
				self.downloadFile(json);
			} else if (json.status == 'error') {
				self.showError();
				self.track('download', self.dOptionTrack(), '0');
			} else {
				if (private.continueDownload == true) {
					self.askReady(json);
				} else {
					private.continueDownload = true;
				}
			}
		},
		askReady: function(json) {
			var self = this;
			var type = 'askReady';
			if (private.ajax) {private.ajax.abort();}
			setTimeout(function() {
			private.ajax = $.ajax({
				url: private.url+'../'+type,
				data: json,
				dataType: "json",
				cache: false,
				type: 'POST',
				success: function(json) {
					self.analizeStatus(json);
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					self.showError();
					console.log(XMLHttpRequest.responseText);
				}
			})}, 5000);
		},
		showMessage: function(data) {
			if (data['img'] != false) {
				$('#'+private.section).find('.cover-box .img-wait').html(data['img']);
			}
			$('#'+private.section).find('.cover-box h1').html(data['title']);
			$('#'+private.section).find('.cover-box .cover-message').html(data['message']);
			$('#'+private.section).find('.cover-box .cover-extra').html(data['extras']);
			$('#'+private.section).find('.cover').css('visibility', data['visibility']);
			$('#'+private.section).find('.cover-box').css('visibility', data['visibility']);
		},
		getOptExport: function(type) {
			var url = this.update(type);
			if(url.filters){
				url.filters = $.param(url.filters);
			}
			url.type = type;
			return url;
		},
		resizeTable: function() {
			var thead = $('#'+private.section).find('thead tr');
			var tbody = $('#'+private.section).find('tbody tr:first');
			tbody.find('td').each(function(key) {
				thead.find('th').eq(key).width($(this).width());
				thead.find('th').eq(key).css('max-width', $(this).width());
			});
		},
		plugins : function() {
			var section = $('#'+private.section);
			var height = section.find('td').eq(0).outerHeight(true) + 2;

			// add sparklines
			section.find('.sparkline').each(function() {
				// to array;
				var data = $(this).data('values').split(',');
				$(this).sparkline(data, {
					type: 'line',
					chartRangeMin: 0,
					height: Math.ceil(height/(4/3)),
					width: 152,
					fillColor: '#ffffff',
					lineColor: '#7cc4f6',
					maxSpotColor : false,
					minSpotColor : false,
					spotColor : false
				});
			});

			// pony award - easter egg
			section.find('tr .logo').hover(function() {
				var parent = $(this).parents('tr');
				if(parent.find('td').eq(0).text() == 1) {
					$(this).attr('data-image', $(this).attr('src'));
					$(this).attr('src', private.url + '../../_img/simon.png');
				}
			}, function() {
				var parent = $(this).parents('tr');
				if(parent.find('td').eq(0).text() == 1) {
					$(this).attr('src', $(this).attr('data-image'));
				}
			});
		},
		showNotice : function(id, status, title, message) {
			if(message != '') {
				var section = $('#'+private.section);
				section.find(id).append('<div id="notice" class="'+status+'"><span>'+title+'</span> '+message+'</div>');
			}
		},
		format : function(value, format) {
			if(format == 'number') {
				return value.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			} else if(format == 'money') {
				return '$' + parseFloat(value).toFixed(2).replace(/./g, function(c, i, a) {
					return i && c !== "." && ((a.length - i) % 3 === 0) ? ',' + c : c;
				});
			} else if(format == 'group_money') {
				var self = this;
				var res = value.split("<br />");

				$.each(res, function(index, v) {
					console.log(value);
					res[index] = self.format(v, 'money');
				});

				return res.join("<br />");

			} else if(format == 'decimal') {
				return parseFloat(value).toFixed(2).replace(/./g, function(c, i, a) {
					return i && c !== "." && ((a.length - i) % 3 === 0) ? ',' + c : c;
				});
			} else if(format == 'percentage') {
				return parseInt(value) + '%';
			} else if(format == 'percentage2') {
				return parseFloat(value).toFixed(2) + '%';
			} else if(format == 'percentage5') {
				return parseFloat(value).toFixed(5) + '%';
			} else if(format == 'percentage3') {
				return parseFloat(value).toFixed(3) + '%';
			} else if(format == 'wow') {
				return parseFloat(value*100).toFixed(2) + '%';
			} else if(format == 'ordinal') {
				var s=['th','st','nd','rd'],
					v=value%100;
				return value+(s[(v-20)%10]||s[v]||s[0]);
			} else if(format == 'month') {
				var monthNames = ["Nothing", "January", "February", "March", "April", "May", "June",
				  "July", "August", "September", "October", "November", "December"];
				return monthNames[value];
			} else if(format == 'shortMonth') {
				var monthNames = ["Nothing", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
				  "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
				return monthNames[value];
			} else if(format == 'toPercentage') {
				var self = this;
				var total = 0;
				$.each(value,function() {
					total += this;
				});

				$.each(value,function(i, v) {
					value[i] = parseFloat(self.format((v*100)/total, 'percentage2'));
				});
				return value;
			}
			else if(format == 'million') {
				return value/1000000 + 'm';

			} else if(format == 'seconds') {
				var h = Math.floor(value/3600); //Get whole hours
				value -= h*3600;
				var m = Math.floor(value/60); //Get remaining minutes
				value -= m*60;
				return h+":"+(m < 10 ? '0'+m : m)+":"+(value < 10 ? '0'+value : value);

			} else if(format == 'hoursDay') {

				var original = value;
				var h = Math.floor(value/3600); //Get whole hours
				value -= h*3600;
				var m = Math.floor(value/60); //Get remaining minutes
				value -= m*60;
				return h+"hrs "+(m < 10 ? '0'+m : m)+"min"+" ("+this.format(original/86400, 'decimal')+" days)";

				//~ var h = Math.floor(value/3600);
				//~ h = this.format(value, 'seconds');
				return h+"hrs"+" ("+this.format(value/86400, 'decimal')+" days)";

				return this.format(value, 'seconds')

			} else if(format == 'gap') {
				return value == '' ? '---' : value;
			} else {
				return value;
			}
		},
		initGetData: function(){
			// cancel all pending ajax requests
			if (private.ajax) {
				private.ajax.abort();
			}
			if (private.type != 'table') {
				$('#Columns').hide();
			} else {
				$('#Columns').show();
			}
			this.exportHide();
		},
		ufViewLi: function(ufView){
			$('#'+private.section).find('.optionsType').show();
			var i = 0;
			var activeClass = true;
			$.each(ufView, function(key, value) {
				var li = '<li';
				if(activeClass == true) {
					li=li+' class="active"';
					activeClass=false;
				}
				li = li+'><span>'+key+'</span><label>'+value+'</label></li>';
				$('#'+private.section).find('#optionType').append(li);
				$('#'+private.section).find('#optionType li span').hide();
			});
		},
		legend : function(jsonLegend, id) {
			if(jsonLegend !=''){
				var legend = '';
				$.each(jsonLegend, function(key, value) {
					legend = legend+'<span class="lTitle">'+value.title+'</span>';
					$.each(value.options, function(opt, descp) {
						var option = '';
						if(opt!='') {
							option = '<b>'+opt+':</b> ';
						}
						legend = legend+'<span class="option">'+option+descp+'</span>';
					});
					legend = legend;
				});
				$('#'+id).html(legend);
			}
		},
		noResult : function() {
			var section = $('#'+private.section);
			this.emptyTable();
			section.find('thead').html('<tr><th>Error</th></tr>');
			section.find('tbody').html('<tr><td>No results found</td></tr>');
		},
		notice : function(optionNotice, notice) {
			//if(optionNotice) {
				this.showNotice('#notice', notice.status, notice.title, notice.message);
			//}
		},
		ufView : function(ufView) {
			if(Object.keys(ufView).length<2) {
				$('#'+private.section).find('.optionsType').hide();
			}
		},
		prepareTable: function() {
			if(private.DataTable) {
				private.DataTable.destroy();
			}
			$('#'+private.section).find('.results').removeClass('charts');
			$('#'+private.section).find('.results').removeClass('charts');
			$('#legendChart').html('');
			$('.search-boxes').remove();
			if (private.ajax) {
				private.ajax.abort();
			}
		},
		showSearch : function (search) {
			if(search == true){
				$('#'+private.section).find('.search-icon').show();
			}
		},
		tColumnFormat : function (formats) {
			var formats_keys = new Array();
			$.each(formats, function(key, value) {
				formats_keys.push(parseInt(key));
			});
			return formats_keys;
		},
		tColumn : function (jsonColumns) {
			var columns = [0, jsonColumns];
			$.each(columns[1], function(key, value) {
				columns[0]++;
				columns[1][key].title = value.title.split('_').join(' ');
			});
			return columns;
		},
		tOptionTrack: function (jsonColumns) {
			var option = [];
			if (jsonColumns.length) {
				$.each(jsonColumns, function(key, value) {
					option.push(value.title);
				});
			} else {
				var section = $('#'+private.section);
				if(section.find('.Columns').length > 0) {
					section.find('.Columns').each(function() {
						$('input', this).each(function() {
							if($(this).prop('checked')) {
								option.push($(this).val());
							}
						});
					});
				} else {
					if(section.find('thead:first').length > 0) {
						section.find('thead:first [role="row"]').each(function() {
						$('th', this).each(function() {
							option.push($(this).text());
						});
					});
					} else {
						option.push('default');
					}
				}
			}

			if($('#'+private.section).find('.range_selector').length > 0) {
				option.unshift($('#'+private.section).find('.range_selector .active').text().replace(' ', '_').toLowerCase().replace(' ', '_'));
			}
			return option;
		},
		gOptionTrack: function () {
			var section = $('#'+private.section);
			var option = [];
			if (section.find('#optionType').length > 0) {
				section.find('#optionType').each(function() {
					$('li', this).each(function() {
						if($(this).hasClass('active')) {
							option.push($(this).find('label').text());
						}
					});
				});
			}
			if (option.length == 0 ) {
				option.push('default');
			}
			return option;
		},
		dOptionTrack: function () {
			var option = [];
			if (private.type == 'table') {
				option = this.tOptionTrack({});
			} else {
				option = this.gOptionTrack();
			}
			option.unshift('From '+private.type);
			return option;
		},
		gSuccessTrack: function () {
			var section = $('#'+private.section);
			var success = false;
			if (section.find('.charts').length > 0) {
				section.find('.charts').each(function() {
					$('div.row', this).each(function() {
						if ($(this).is(':empty') == false) {
							success = true;
						}
					});
				});
			}
			return success;
		},
		tSuccessTrack: function () {
			if ($('#'+private.section).find('thead tr:first').text() == 'Error') {
				return '0';
			} else {
				return '1';
			}
		},
		tGroup : function (jsonGroup) {
			var group = {};
			if(jsonGroup) {
				group.visible = false;
				group.targets = jsonGroup;
			}
			return group;
		},
		tOrder : function (jsonOrder) {
			var order = [];
			if(jsonOrder) {
				order = jsonOrder;
			}
			return order;
		},
		tSearch : function () {
			var section = $('#'+private.section);
			var count = 0;
			$.each(private.search, function(key, value) {
				section.find('input[data-name="' + key + '"]').val(value);
				section.find('input[data-name="' + key + '"]').keyup();
				if(value.length > 0) {
					count++;
				}
			});
			if(count > 0) {
				section.find('.search-boxes').slideDown();
			}
		},
		tTotals : function (optionTotal, tColumn, totals, formats) {
			if(optionTotal) {
				var totalToHTML = '';
				var self = this;
				var section = $('#'+private.section);
				$.each(tColumn, function(key, value) {
					var totalTD = '';
					if(typeof totals[key] !== 'undefined') {
						if(typeof formats[key] !== 'undefined') {
							totalTD = self.format(totals[key], formats[key]);
						} else {
							totalTD = totals[key];
						}
					}
					totalToHTML = totalToHTML+'<th class="thTotal">'+totalTD+'</th>';
				});
				section.find('.dataTables_scrollHeadInner table thead').append('<tr class="totals open">'+totalToHTML+'</tr>');
			}
		},
		tAddTotalSearch : function () {
			var self = this;
			var section = $('#'+private.section);
			var toSearch = section.find('.dataTables_scrollHeadInner table thead tr:first');
			var totalSearch = '';

			toSearch.find('th').each( function () {
				var title = $(this).text();
				totalSearch = totalSearch +'<th class="search"><input type="text" placeholder="Search.." data-name="'+title+'" value="" /></th>';
			});

			if(totalSearch != '') {
				section.find('.dataTables_scrollHeadInner table thead').append('<tr class="search-boxes">'+totalSearch+'</tr>');
				section.find('.search-boxes').hide();
			}
		},
		emptyTable : function () {
			var section = $('#'+private.section);
			section.find('thead').empty();
			section.find('tbody').empty();
		},
		searchBoxes : function () {
			var section = $('#'+private.section);
			section.find('.search-boxes input').each(function(key) {
				$(this).on('keyup change', function() {
					var value = $(this).val();
					private.DataTable.column(key).search( value ).draw();
					self.updatesearch();
				});
			});
		},
		resetPage: function (){
			private.page = 0;
			$('#paginationDash'+private.section).hide();
			$('#moreDash'+private.section).hide();
			$('#paginationStopDash'+private.section).hide();
		},
		endMore: function () {
			$('#moreDash'+private.section).hide('slow', function(){
				$('#paginationStopDash'+private.section).show('slow');
			});
		},
		exportHide: function () {
			$('#downloadData').hide();
		},
		exportShow: function () {
			$('#downloadData').show();
		},
		stopPid: function() {
			private.ajax = $.ajax({
				type: 'POST',
				url: '../'+private.url+'close-pid',
				data: 'pid='+private.pid,
				async: false
			});
		},
		track: function(type, columns, success) {
			var data = {};
			data.data = columns;
			data.success = success;
			private.ajax = $.ajax({
				type: 'POST',
				url:'../'+private.url+type+'/' + private.section+'/send-track',
				data: data,
				cache: false,
				type: 'POST',
				success: function(json) {},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					console.log(XMLHttpRequest.responseText);
				}
			});
		},
		getPodOrg: function(pods) {
			var type = 'getPodOrg';
			var self = this;
			if (private.ajax) {private.ajax.abort();}
			private.ajax = $.ajax({
				url: private.url+'../'+type,
				data: {'pods':pods},
				dataType: "json",
				cache: false,
				async: false,
				type: 'POST',
				success: function(json) {
					var org = '.Organization';
					$('#'+private.section).find(org).empty();
					$('#GlobalPodAnalyticsOrgAdv').find(org).empty();
					$('#GlobalPodAnalyticsUsage').find(org).empty();
					$('#GlobalPodAnalyticsCampaign').find(org).empty();
					$('#'+private.section).find(org).append(self.liClearAll());
					$('#GlobalPodAnalyticsOrgAdv').find(org).append(self.liClearAll());
					$('#GlobalPodAnalyticsUsage').find(org).append(self.liClearAll());
					$('#GlobalPodAnalyticsCampaign').find(org).append(self.liClearAll());
					$.each(json, function(key, name) {
						var li = '<li>';
						li = li+'<input  id="Organization_'+self.formatid(key)+'"  type="checkbox" value="'+self.formatid(key)+'" checked />';
						li = li+'<label>'+name+'</label>';
						li = li+'</li>';
						$('#'+private.section).find(org).append(li);
						$('#GlobalPodAnalyticsOrgAdv').find(org).append(li);
						$('#GlobalPodAnalyticsUsage').find(org).append(li);
						$('#GlobalPodAnalyticsCampaign').find(org).append(li);
					});
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					console.log(XMLHttpRequest.responseText);
				}
			});
		},
		init : function() {
			// initially generate the wrapper
			this.getdata('wrapper');
			$(window).focus(function(){
				$(window).resize();
			});
			$(window).blur(function(){
				$(window).resize();
			});
		}
	}
};
