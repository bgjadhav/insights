var defaults = {
	type : 'table',
	class : '',
	dateRange : false,
	title : '',
	table : {
		export : false,
		thead : false,
		width : '200', // 100%,
		columns : new Array()
	},
	chart : {
		type : 'line'
	},
	extras : false,
	edit : false
}

function CommaFormattedN(amount) {
	var result = amount.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    return result;
}

var zoom = 1;
var draggables = Array();
var widgets = {
	loadWidget : function(i, filters) {
		var idWidget = i;
		var tile = $('#homepage .tile').eq(idWidget);
		tile.empty();
		var script = tile.data('script');

		if(filters === undefined) {
			filters = '';
		} else {
			filters = {'filters': $.param(filters, true)};
		}

		if(script) {
			// show loading
			tile.addClass('loading');
			$.getJSON('widgets/'+script, filters, function( data ) {

				var colourClass = tile.attr('data-style');

				// set up page flip divs
				tile.append(widgets.templates.base(colourClass));

				// finished loading
				tile.removeClass('loading');

				// add tile type class
				tile.addClass('type-' + data.type);

				// add tile custom class
				tile.addClass(data.class);

				// add tile chart style
				tile.addClass(data.style);

				// add new sash if new
				if(tile.data('new') == 1) {
					tile.prepend('<div class="new"> </div>');
				}

				//merge provided data with default data
				var data = $.extend({}, defaults, data);

				//append header
				tile.find('.front').append(widgets.templates.header(data));

				// check if an edit button should be added
				if(data.edit) {
					tile.append('<a href="#" class="edit">Edit</a>');
				}

				// add the info button
				if(data.info) {
					tile.append('<span class="info" data-title="' + data.info + '"></span>');
				}

				// append body based on type
				if(data.type == 'table') {
					tile.find('.front').append(widgets.templates.table(data, tile));
					tile.find('.table').nanoScroller();
				} else if(data.type == 'table-compare') {
					// also needs table class
					tile.addClass('type-table');
					tile.find('.front').append('<div class="main"></div>');
					var showTotal = true;
					var switchStyle = '';
					if (data.nototal) {
						showTotal= false;
					}
					if (data.noSwitch) {
						switchStyle=' style="width: 769px;"';
					}

					var filterId = 'filters'+idWidget;

					tile.find('.front').append('<div class="switch"'+switchStyle+'></div>');
					tile.find('.front header').prepend('<ul class="tabs"></ul><div class="filters" id="'+filterId+'"><div class="dropdown"></div></div>');
					$.each(data.data, function(key, row) {

						$.each(row.data, function(idRow, valuesRow) {
							if (data.format) {
								$.each(data.format, function(idFormat, typeFormat) {
									valuesRow[idFormat] = widgets.format(valuesRow[idFormat], typeFormat);
								});
							}

							if (data.addTextAdditional) {
								$.each(data.addTextAdditional, function(idHeadings, vHeadings) {
									valuesRow[idHeadings] = valuesRow[idHeadings]+'<br><span class="addInfo">'+vHeadings+'</span>';
								});
							}
						});

						var table = data;
						table.data = row.data;
						if(row.main == true) {
							tile.find('.front .main').append(widgets.templates.table(table, tile));
						} else {
							if (data.addSpan) {
								tile.find('.front header ul.tabs').append('<li class="'+key+'"><span>'+key+'</span><span class="no_background"></span></li>');
							} else {
								tile.find('.front header ul.tabs').append('<li>'+key+'</li>');
							}
							tile.find('.front .switch').append(widgets.templates.table(table, tile));

							if (showTotal) {
								tile.find('.front .switch').append('<div class="totals">Current sov: ' + row.current_sov  + ' Last Month: ' + row.last_month  + '</div>');
							}
						}
					});

					tile.find('.table').nanoScroller();

					tile.find('.front .switch .totals').hide();
					tile.find('.front .switch .totals').eq(0).show();

					tile.find('.front .switch .table').hide();
					tile.find('.front .switch .table').eq(0).show();
					tile.find('.front header ul.tabs li').eq(0).addClass('active');

					tile.on('click', 'header ul.tabs li', function() {
						var index = $(this).index();

						tile.find('.front header ul.tabs li').removeClass('active');
						tile.find('.front header ul.tabs li').eq(index).addClass('active');

						tile.find('.front .switch .table').hide();
						tile.find('.front .switch .table').eq(index).show();

						tile.find('.front .switch .totals').hide();
						tile.find('.front .switch .totals').eq(index).show();
					});

				} else if(data.type == 'certified') {
					var certified = widgets.templates.certified(data, tile);
					tile.find('.front').append(certified[0]);
					tile.find('.back').append(certified[1]);
				} else if(data.type == 'map') {

					var map = widgets.templates.map(data, tile);
					tile.find('.front').append(map);
					//tile.find('.back').append(certified[1]);

					var berlin = new google.maps.LatLng(38.520816, 13.410186);

					var neighborhoods = [
					];
					$.each(data.data, function(key, value) {
						var latlng = new google.maps.LatLng(value.lat, value.long)
						neighborhoods.push(latlng);
					});

					var markers = [];
					var iterator = 0;

					var map;

					for (var i = 0; i < neighborhoods.length; i++) {
					    setTimeout(function() {
					      addMarker();
					    }, i * 20);
					  }

					function addMarker() {
					  markers.push(new google.maps.Marker({
					    position: neighborhoods[iterator],
					    map: map,
					    draggable: false,
					    animation: google.maps.Animation.DROP,
						icon: '_img/circle.png'
					  }));
					  iterator++;
					}

					var mapOptions = {
					    zoom: 1,
					    center: berlin,
					    styles: [{"featureType":"water","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]},{"featureType":"landscape","stylers":[{"color":"#f2e5d4"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"}]},{"featureType":"administrative","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"road"},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{},{"featureType":"road","stylers":[{"lightness":20}]}]
					  };

					  map = new google.maps.Map(document.getElementById(data.class), mapOptions);

				} else if(data.type == 'chart') {
					// add chart type class
					tile.addClass('type-chart-' + data.chart.type);

					// pie charts
					if(data.chart.type == 'pie') {
						tile.find('.front').append(widgets.templates.chart(data, tile));
						widgets.charts.pie(data, tile.find('.chart'), colourClass);
					}
					
					if(data.chart.type == 'donut') {
						tile.find('.front').append(widgets.templates.chart(data, tile));
						widgets.charts.donut(data, tile.find('.chart'), colourClass);
					}

					// line charts
					if(data.chart.type == 'line') {

						if (data.more_charts) {
							tile.find('.front header').append('<span class="tabs"></span>');
							var cloneData = data.data;
							$.each(cloneData, function(key, cData) {
								tile.find('.front .tabs').append('<span class="'+key+' indexTab">'+data.chart.tabs[key]+'</span>');
								data.data = cData;
								data.subtitle = data.chart.tabs[key];
								tile.find('.front').append(widgets.templates.chart(data, tile, key));
								widgets.charts.line(data, tile.find('.chart'+key), colourClass, data.chart.formats[key]);
								tile.find('.chart'+key).hide();
							});
							tile.find('.chart0').show();
							tile.find('.front header .tabs .0').addClass('active');
						} else {
							tile.find('.front').append(widgets.templates.chart(data, tile));
							widgets.charts.line(data, tile.find('.chart'), colourClass, '');
						}
					}

					// area charts
					if(data.chart.type == 'area') {
						tile.find('.front').append(widgets.templates.chart(data, tile));
						widgets.charts.area(data, tile.find('.chart'), colourClass);
					}

					// bar charts
					if(data.chart.type == 'column') {
						tile.find('.front').append(widgets.templates.chart(data, tile));
						widgets.charts.column(data, tile.find('.chart'), colourClass);
					}
				} else if(data.type == 'countdown') {
					// countdown
					tile.find('.front').append(widgets.templates.countdown(data, tile));
					tile.find('.counters').countdown(data.data).on('update.countdown', function(event) {
						var $this = $(this).html(event.strftime(''
							+ '<span class="count">%D<span>day%!d</span></span>'
							+ '<span class="count">%H<span>hr</span></span>'
							+ '<span class="count">%M<span>min</span></span>'
							+ '<span class="count">%S<span>sec</span></span>'
						));
					}).on('finish.countdown', function(event) {
						tile.find('.front').html('<h2>Party time!</h2>');
					});
				} else if(data.type == 'count') {
					// comparison of the total of numbers
					tile.find('.front').append(widgets.templates.count(data, tile));
					tile.find('.front span.count').each(function(num) {
						var thisDiv = $(this);
						$({ Counter: 0 }).animate({ Counter: thisDiv.data('value') }, {
							duration: 750,
							easing: 'swing',
							step: function () {

								var currentNum = Math.ceil(this.Counter);

								if(data.count != undefined){
									if($.isArray(data.count.commas)){
										if(data.count.commas[num] == true){
											currentNum = CommaFormattedN(currentNum);
										}
									} else if(data.count.commas == true){
										currentNum = CommaFormattedN(currentNum);
									}
								}

								/*
if(data.count != undefined){
									if(data.count.commas == true){
										currentNum = CommaFormattedN(currentNum);
									}
								}
*/

								var the_currency = '';

								if(data.count != undefined){
									if($.isArray(data.count.currency)){
										if(data.count.currency[num] != undefined && data.count.currency[num] != ''){
											var the_currency = data.count.currency[num];
										}
									} else if(data.count.currency != undefined && data.count.currency != ''){
										var the_currency = data.count.currency;
									}
								}


								thisDiv.text(the_currency+currentNum);
							},
							complete : function() {

								var finalNum = thisDiv.data('value');

								if(data.count != undefined){
									if($.isArray(data.count.commas)){
										if(data.count.commas[num] == true){
											finalNum = CommaFormattedN(finalNum);
										}
									} else if(data.count.commas == true){
										finalNum = CommaFormattedN(finalNum);
									}
								}

								/*
if(data.count != undefined){
									if(data.count.commas == true){
										//var currentNum = currentNum.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
										finalNum = CommaFormattedN(finalNum);
									}
								}
*/

								var the_currency = '';

								/*if(data.count != undefined && data.count.currency != undefined && data.count.currency != ''){
									var the_currency = data.count.currency;
								}*/

								if(data.count != undefined){
									if($.isArray(data.count.currency)){
										if(data.count.currency[num] != undefined && data.count.currency[num] != ''){
											var the_currency = data.count.currency[num];
										}
									} else if(data.count.currency != undefined && data.count.currency != ''){
										var the_currency = data.count.currency;
									}
								}

								thisDiv.text(the_currency+finalNum);
							}
						});
					});
				} else if(data.type == 'publisher-query-tool') {
					tile.find('.front header').remove();
					var pqt = new Pqt('pqt',tile, data);
					pqt.init();
				} else if(data.type == 'incremental-reach-tool') {
					tile.find('.front header').remove();
					var pqt = new Pqt('irt',tile, data);
					pqt.init();
				} else if(data.type == 'qubole-data-status') {
					tile.find('.front header').remove();
					var pqt = new Pqt('qb',tile, data);
					pqt.init();
				} else if(data.type == 'channel') {
					tile.find('.front header').remove();
					var channel = new channel_widget(tile, data);
					channel.init();
				} else if(data.type == 'stats') {
					tile.find('.front header').remove();
					var stats = new stats_widgets(tile, data, widgets);
					stats.init();
				}

				if (data.filters) {
						var count = 0;
						var button = false;
						var orderLi = false;

						$.each(data.filters, function(key, row) {
							var uniqueFilter = '';
							if (row.checked=='OnlyOne') {
								uniqueFilter = ' uniqueFilter';
							}

							tile.find('.front .filters .dropdown').append('<div class="'+key+'"><span name="'+key+'" class="name" id="'+key+'"></span><div class="ItemBorder"></div><div class="ItemBorder ItemBorder2"></div><ul class="'+key+uniqueFilter+' itemFilter" name="'+idWidget+'"></ul></div>');

							var indexLi = 0;

							$.each(row.data, function(idF, nameF) {
								var checked = ' checked';
								if (row.selected && typeof row.selected[idF] == 'undefined' ) {
									checked = '';
								} else {
									orderLi = indexLi;
								}

								if (row.type == 'button') {
									if (button == false) {
										button = true;
									}
									tile.find('.front .filters .dropdown .'+key+' ul').append('<li name="'+indexLi+'"><button class="itemF'+checked+'" id="'+i+'_'+key+'_'+idF+'" value="'+idF+'" type="submit" name="name">'+nameF+'</button></li>');
								} else {
									tile.find('.front .filters .dropdown .'+key+' ul').append('<li><input id="'+i+'_'+key+'_'+idF+'" type="text" value="'+idF+'"'+checked+' /><label>'+nameF+'</label></li>');
								}
								indexLi++;
							});


						});

						var position = '';

						if (button) {
							if (data.Date_position == 'first') {
								tile.find('.front .filters .dropdown .Date ul li[name='+orderLi+']').addClass('first');
								tile.find('.front .filters .dropdown .Date ul li[name='+(orderLi+1)+']').addClass('second');
								tile.find('.front .filters .dropdown .Date ul li[name='+(orderLi+2)+']').addClass('third');
							} else if (data.Date_position == 'second') {
								tile.find('.front .filters .dropdown .Date ul li[name='+(orderLi-1)+']').addClass('first');
								tile.find('.front .filters .dropdown .Date ul li[name='+orderLi+']').addClass('second');
								tile.find('.front .filters .dropdown .Date ul li[name='+(orderLi+1)+']').addClass('third');
							} else {
								tile.find('.front .filters .dropdown .Date ul li[name='+(orderLi-2)+']').addClass('first');
								tile.find('.front .filters .dropdown .Date ul li[name='+(orderLi-1)+']').addClass('second');
								tile.find('.front .filters .dropdown .Date ul li[name='+orderLi+']').addClass('third');
							}
						}

						var classCarrusel = '';

						if (tile.find('.front .filters .dropdown .Date ul li').length <= 3) {
							classCarrusel =' disabled';
							tile.find('.front .filters .dropdown .Date ul').css('width', '270px');
						} else {
							tile.find('.front .filters .dropdown .Date ul').css('width', '300px');
						}

						tile.find('.front .filters .dropdown .Date ul').prepend('<li class="previous'+classCarrusel+'"><div class="previous'+classCarrusel+'"></div></li>');
						tile.find('.front .filters .dropdown .Date ul').append('<li class="next'+classCarrusel+'"><div class="next'+classCarrusel+'"></div></li>');

						if (data.first) {
							tile.find('.front .filters .dropdown .Date ul.itemFilter').css('display', 'none');
						} else {
							tile.find('.front .filters .dropdown .Date div.ItemBorder').css('display', 'block');
							tile.find('.front .filters .dropdown .Date ul.itemFilter').css('display', 'block');
						}
					}

				// load tooltips
				$('.info').fooltips();

				// add any extras
				if(data.extras) {
					tile.find('.front header').prepend('<ul class="percentages"></ul>');
					$.each(data.extras.data, function(key, value) {
						tile.find('.front header .percentages').append('<li><strong>' + key + '</strong> ' + value + '%</li>');
					});
				}
			});
		}
	},
	scroll : function() {
		// lazy load each tile based on if they are visible on the screen
		var windowHeight = $(window).height();
		var scrollAmount = $(window).scrollTop();
		$('#homepage .tile').each(function() {
			var index = $(this).index();
			var offset = $(this).offset().top * zoom;
			var distance = parseInt(windowHeight) + parseInt(scrollAmount);
			if(offset < distance && !$(this).attr('data-loading') && $(this).is(':visible')) {
				// mark as loading
				$(this).attr('data-loading', 'loading');
				widgets.loadWidget(index);
			}
		});
	},
	categories : function() {
		var tiles = $('#homepage').find('.tile');

		var checked = [];
		$('#categories input:checked').each(function(key, value) {
			checked.push($(this).val());
		});

		if(checked.length != 0) {
			tiles.each(function(key, item) {
				var categories = $(this).data('categories').split(',');
				var show = 0;
				$.each(checked, function(key, value) {
					if($.inArray(value, categories) > -1) {
						show++;
					}
				});
				if(show > 0) {
					$(this).show();
				} else {
					$(this).hide();
				}
			});
		} else {
			tiles.each(function(key, item) {
				$(this).show();
				draggables[key].enable();
			});
		}

		$('#homepage').packery();
		setTimeout(function() {
			$(window).scroll();
			$(window).resize();
		}, 500);
	},
	format : function(value, format) {
		if(format == 'number') {
			return value.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		} else if(format == 'money') {
			return '$' + parseFloat(value).toFixed(2).replace(/./g, function(c, i, a) {
				return i && c !== "." && ((a.length - i) % 3 === 0) ? ',' + c : c;
			});
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
		} else if(format == 'ordinal') {
			var s=['th','st','nd','rd'],
				v=value%100;
			return value+(s[(v-20)%10]||s[v]||s[0]);
		} else if(format == 'month') {
			var monthNames = ["Nothing", "January", "February", "March", "April", "May", "June",
			  "July", "August", "September", "October", "November", "December"];
			return monthNames[value];
		} else if(format == 'million') {
			return value/1000000 + 'm';
		} else if(format == 'money_yaxis') {
			return '$'+value/1000000+'m';
		} else {
			return value;
		}
	},
	buttons : function() {
		var self =
		// on dropdown change
		$('#homepage').on('click', '.filters .Date', function(e) {
			var option = $('#homepage .filters .Date .itemFilter').css('display') == 'none' ? 'block' : 'none';
			$('#homepage .filters .Date .itemFilter').css('display', option);
			$('#homepage .filters .Date .ItemBorder').css('display', option);
		});
		$('#homepage').on('click', '.filters .dropdown input', function(e) {
			var parent = $(this).parents('ul');
			if (parent.attr('class').indexOf('unique') != 1) {
				parent.find('li input').prop('checked', false);
				$(this).prop('checked', true);
			}
		});
		$('#homepage').on('click', '.filters .dropdown li.previous', function(e) {

			if ($(this).hasClass('disabled') == false) {
				var idTile = $(this).parents('ul').attr('name');

				$('#homepage').find('#filters'+idTile+' .dropdown li.third').removeClass('third');

				var second = $('#homepage').find('#filters'+idTile+' .dropdown li.second');
				second.removeClass('second');
				second.attr('class', 'third');

				var first = $('#homepage').find('#filters'+idTile+' .dropdown li.first');
				first.removeClass('first');
				first.attr('class', 'second');

				var tmp = first.attr('name');
				tmp--;
				$('#homepage').find('#filters'+idTile+' .dropdown li'+ "[name='" + tmp + "']").attr('class', 'first');

				if (tmp == 0) {
					$('#homepage').find('#filters'+idTile+' .dropdown li.previous').addClass('disabled');
					$('#homepage').find('#filters'+idTile+' .dropdown div.previous').addClass('disabled');
				}
				$('#homepage').find('#filters'+idTile+' .dropdown li.next').removeClass('disabled');
				$('#homepage').find('#filters'+idTile+' .dropdown div.next').removeClass('disabled');
			}
		});

		$('#homepage').on('click', '.filters .dropdown li.next', function(e) {
			if ($(this).hasClass('disabled') == false) {

				var idTile = $(this).parents('ul').attr('name');

				$('#homepage').find('#filters'+idTile+' .dropdown li.first').removeClass('first');

				var second = $('#homepage').find('#filters'+idTile+' .dropdown li.second');
				second.attr('class', 'first');
				second.removeClass('second');

				var third = $('#homepage').find('#filters'+idTile+' .dropdown li.third');
				third.attr('class', 'second');
				third.removeClass('third');

				var tmp = third.attr('name');
				tmp++;

				$('#homepage').find('#filters'+idTile+' .dropdown li'+ "[name='" + tmp + "']").attr('class', 'third');

				var count = $(this).parents('ul').find('li').length - 2 - 1;
				if (count == tmp) {
					$('#homepage').find('#filters'+idTile+' .dropdown li.next').addClass('disabled');
					$('#homepage').find('#filters'+idTile+' .dropdown div.next').addClass('disabled');
				}

				$('#homepage').find('#filters'+idTile+' .dropdown li.previous').removeClass('disabled');
				$('#homepage').find('#filters'+idTile+' .dropdown div.previous').removeClass('disabled');
			}
		});
	},
	init : function() {
		// check if tiles should be loaded on scroll
		$(window).scroll(function() {
			widgets.scroll();
		});

		// on click of edit button
		$('#homepage').on('click', '.edit', function(e) {
			e.preventDefault();
			var script = $(this).parent().addClass('flip').data('script');
			var back = $(this).parent().children('.back');
			back.addClass('loading edit-form');
			$(this).remove();
			$.get('widgets/' + script + '/edit', function( data ) {
				back.removeClass('loading');
				back.html('<header><h3>Edit</h3></header><div class="table nano"><div class="nano-content"></div></div>');
				back.find('.nano-content').html(data);
				back.find('.table').nanoScroller();
			});
		});

		// on edit form submit
		$('#homepage').on('submit', '.edit-form form', function(e) {
			e.preventDefault();
			var action = $(this).attr('action');
			var form = $(this);
			form.append('<img src="_img/loading.gif" alt="" />');
			$.post( action, $(this).serialize(), function(data) {
				form.parents('.tile').removeClass('flip').removeAttr('data-loading').empty();
				$(window).scroll();
			});
		});

		// add another row
		$('#homepage').on('click', '.add', function(e) {
			e.preventDefault();
			var form = $(this).parents('form')
			var html = form.children('fieldset').last().html();

			// add another row
			form.children('fieldset').last().after('<fieldset>' + html + '</fieldset>');

			// change month
			var date = new Date( form.children('fieldset').last().find('label').html() );
			date.setMonth( date.getMonth( ) + 1 );

			date = date.getFullYear() + '-' + (date.getMonth( ) + 1) + '-' + (date.getDate() < 10 ? '0' + date.getDate() : date.getDate());
			form.children('fieldset').last().find('label').html(date);
			form.children('fieldset').last().find('input[type="hidden"]').val(date);

			// set values
			form.children('fieldset').last().find('input[type="text"]').val('0');
		});

		// check on page load too
		this.scroll();
		this.buttons();
		charts.hack();
	}
}

$(function() {
	widgets.init();

	// packery stuff
	var $container = $('#homepage');
	var columnWidth = 392;

	// init
	$container.packery({
		columnWidth: columnWidth,
		isFitWidth: true,
    	rowHeight: 339
	});

	var categories = [];
	$container.find('.tile').each( function( i, itemElem ) {
		// make element draggable with Draggabilly
		if($(this).data('handle') > 0) {
			draggables[i] = new Draggabilly( itemElem, {
				handle: '.handle'
			});
		} else {
			draggables[i] = new Draggabilly( itemElem );
		}

		// bind Draggabilly events to Packery
		$container.packery( 'bindDraggabillyEvents', draggables[i] );

		// get each category
		var category = $(this).data('categories').split(',');
		$.each(category, function(key, value) {
			if(value != '') {
				categories.push(value);
			}
		});
	});
	$(window).scroll();

	function uniqueArray( ar ) {
	  var j = {};

	  ar.forEach( function(v) {
		j[v+ '::' + typeof v] = v;
	  });


	  return Object.keys(j).map(function(v){
		return j[v];
	  });
	}

	// add categories to page
	categories = uniqueArray(categories);
	$.each(categories, function(key, value) {
		if(value != '') {
			$('#categories ul').append('<li><input type="checkbox" id="checkbox' + key + '" value="' + value + '" /> <label for="checkbox' + key + '">' + value + '</label></li>');
		}
	});

	$('#categories').on('change', 'input', function() {
		widgets.categories();
	});

	$container.packery( 'on', 'dragItemPositioned', function() {
		var divs = $container.packery('getItemElements');
		var newOrder = [];
		$.each(divs, function() {
			var id = $(this).data('id');
			newOrder.push(id);
		});
		$.post( "widgets/update_order", { order: newOrder } );
	});


	// on dropdown change
	$('#homepage').on('click', '.dropdown input', function(e) {
		var options = {};
		var parent = $(this).parents('ul');
		var idTile = parent.attr('name');

		if($('#homepage').find('#filters'+idTile).length > 0) {

			$('#homepage').find('#filters'+idTile+' .dropdown').each(function() {

				$('div', this).each(function() {
					var name = $(this).find('span').attr('name');
					var filters = [];

					$('input', this).each(function() {
						if($(this).prop('checked')) {
							filters.push($(this).val());
						}
					});
					options[name] = filters;
					if(filters.length == 0) {
						options[name] = [''];
					}
				});
			});
		}
		widgets.loadWidget(idTile, options);
	});

	$('#homepage').on('click', '.dropdown button', function(e) {
		var options = {};
		var parent = $(this).parents('ul');
		var button = $(this);
		var idTile = parent.attr('name');
		var myLi = $(this).parents('li').attr('name');
		var myPosition = $(this).parents('li').attr('class');

		if($('#homepage').find('#filters'+idTile).length > 0) {

			$('#homepage').find('#filters'+idTile+' .dropdown').each(function() {

				$('div', this).each(function() {
					var name = $(this).find('span').attr('name');
					var filters = [];

					$('input', this).each(function() {
						if($(this).prop('checked')) {
							filters.push($(this).val());
						}
					});

					filters.push(button.val());

					options[name] = filters;
					options[name+'_position'] = myPosition;
					if(filters.length == 0) {
						options[name] = [''];
					}
				});
			});
		}
		widgets.loadWidget(idTile, options);
	});

	// Decisioning and Opto Usage
	$('#homepage').on('click', '.indexTab', function(e) {
		var parent = $(this).parents('div.front');
		$(this).parents('div.front').find('.chart').hide();
		$(this).parents('div.front').find('.tabs span').removeClass('active');
		var index = $(this).attr('class');
		index = index.replace(' indexTab', '');
		$(this).parents('div.front').find('.chart'+index).show();
		$(this).parents('div.front').find('.tabs span.'+index).addClass('active');
	});

	// slider
	var slider = $('#categories .slider');
	if(slider.length > 0) {
		noUiSlider.create(slider[0], {
			start: 1,
			range: {
				'min': 0.6,
				'max': 1.3
			}
		});
		slider[0].noUiSlider.on('change', function() {
			zoom = slider[0].noUiSlider.get();
			$('#homepage-zoom').css('zoom', zoom);
			onResize();
		});
	}

	/* write our own resize code so we can center the div while using the packery layout */
	function onResize() {
		var outsideSize = $('#content').innerWidth();
		var cols = Math.floor( outsideSize / ( columnWidth * zoom) );
		$container.width((cols * (columnWidth)) +20 +'px');
		$container.packery();

		$('#categories .inner').width((cols * columnWidth) * zoom);

		/* bit of a hack to fix the lazy load */
		setTimeout(function() {
			$(window).scroll();
		}, 500);
	}
	var resizeTimeout;
	eventie.bind( window, 'resize', function() {
		if ( resizeTimeout ) {
			clearTimeout( resizeTimeout );
		}
		resizeTimeout = setTimeout( onResize, 50 );
	});
	onResize();
});
