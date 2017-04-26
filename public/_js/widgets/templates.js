var templates = {
	base : function(colourClass) {
		var template = '<div class="front ' + colourClass + '"></div><div class="back ' + colourClass + '"></div>';
		return template;
	},
	header : function(data) {
		var template = '<header><h3>' + data.title + '</h3></header>';
		return template;
	},
	tdStyles : function(data,i) {
		var style = '';
		if(data.table.columns) {
			if(data.table.columns[i]) {
				if(data.table.columns[i].bold) {
					style += 'font-weight: bold;';
				}
				if(data.table.columns[i].color) {
					style += 'color: ' + data.table.columns[i].color + ';';
				}
				if(data.table.columns[i].align) {
					style += 'text-align: ' + data.table.columns[i].align + ';';
				}
				if(data.table.columns[i].size) {
					style += 'font-size: ' + data.table.columns[i].size + ';';
				}
			}
		}
		return style;
	},
	table : function(data, tile) {
		var template = '<div class="table nano">';
			template += '<div class="nano-content">';
				template += '<table cellpadding="0" cellspacing="0" style="width: ' + data.table.width + '">';
				if(data.table.thead) {
					var keys = Object.keys(data.data[0]);
					template += '<tr>';
						var i = 0;
						if(data.table.headings) {
							$.each(data.table.headings, function(key, value) {
								style = templates.tdStyles(data, i);
								template += '<th style="' + style + '">' + value + '</th>';
								i++;
							});
						} else {
							$.each(keys, function(key, value) {
								style = templates.tdStyles(data, i);
								template += '<th style="' + style + '">' + value + '</th>';
								i++;
							});
						}
					template += '</tr>';
				}
				$.each(data.data, function(key, value) {
					template += '<tr>';
						var i = 0;
						$.each(value, function(key2, value2) {
							style = templates.tdStyles(data, i);
							template +='<td style="' + style + '">' + value2 + '</td>';
							i++;
						});
					template += '</tr>';
				});
				template += '</table>';
			template += '</div>';
		template += '</div>';
		return template;
	},
	map : function(data, tile){

		var template = '<div class="type-map" id="'+data.class+'"></div>';
		return template;

	},
	certified : function(data, tile) {
		var template = new Array();
		template[0] = ' ';
		$.each(data.total, function(key, value) {
			template[0] += '<div class="row ' + key + '"><span></span>' + key + '<strong>' + value + '</strong></div>';
		});

		template[1] = ' ';
		$.each(data.data, function(key, value) {
			template[1] += '<div class="type-table ' + key + '">';
				var temp = data;
				temp.title = key + ' <strong>Certified</strong>';
				temp.data = value;

				template[1] += '<span class="close">back</span>';
				template[1] += templates.header(temp);
				template[1] += templates.table(temp);
			template[1] += '</div>';
		});

		// events for this template
		tile.on('click', '.row', function() {
			var thisClass = $(this).attr('class').split(' ')[1];
			tile.addClass('flip');
			tile.find('.back div.type-table, .back header').hide();
			tile.find('.back, .back .' + thisClass + ', .back .' + thisClass + ' header').show();
		});
		tile.on('click', '.close', function() {
			tile.removeClass('flip');
		});

		return template;
	},
	count : function(data, tile) {
		var template = '';

		$.each(data.data, function(key, value) {

			if(data.class == 'raptor-attack'){
				template += '<div class="count-holder"><span class="raptor"><img src="_img/velo.png"></span><span class="count" data-value="' + value + '">0</span><span class="name">' + key + '</span><span class="raptor-title">Velociraptor <strong>Attack</strong></span></div>';
			} else {
				template += '<div class="count-holder"><span class="count" data-value="' + value + '">0</span><span class="name">' + key + '</span></div>';
			}

		});
		template += '<div class="line"></div>';

		return template;
	},
	countdown : function(data, tile) {
		template = ' ';
		template += '<div class="counters"> </div>';
		return template;
	},
	chart : function(data, tile, id) {
		if (typeof id === 'undefined'){
			id = '';
		}
		var template = '<div class="chart chart'+id+'"></div>';
		return template;
	}
}
widgets.templates = templates;
