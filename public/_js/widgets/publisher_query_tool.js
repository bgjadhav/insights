/* JSHint */
/* global $:false, window:false, Handlebars:false, moment:false */
var Pqt = function(type, tile, data) {
	return {
		templates : {
			front : '#pqt-front-template',
			back : '#pqt-back-template',
			front_item : '#pqt-front-item',
			url : '#pqt-back-url'
		},
		urls : {},
		compiled : {},
		visible :[],
		ajax : false,

		load_template : function(template) {
			var templateid = this.templates[template];
			var source = $(templateid).html();
			this.compiled[template] = Handlebars.compile(source);
			return this.compiled[template];
		},

		events : function() {
			var self = this;

			// rotate
			this.tile.on('click', '.create', function(e) {
				e.preventDefault();
				self.tile.toggleClass('flip');
				self.get_organisations();
				self.get_exchanges();
				self.get_countries();
			});

			// list item clicked
			this.tile.find('.front').on('click', '.content li', function() {
				var details = $(this).find('.details');
				var box = $(this).find('.details .box');
				details.show();
				var box_height = box.outerHeight();
				var line = $(this).find('.details .line');

				var id = $(this).data('id');

				if(!$(this).data('down')) {
					// keep track of which items are visible
					self.visible.push(id);

					details.hide();

					line.width(3);
					line.delay(500).animate({
						width : 36
					},200);

					line.css('bottom', box_height/2 + 20);
					box.css('margin-left', -285);
					box.delay(700).animate({
						'margin-left' : 0
					});

					$(this).find('.details').slideDown(500);
					$(this).data('down', true);
				} else {
					var index = self.visible.indexOf(id);
					if (index > -1) {
						self.visible.splice(index, 1);
					}

					box.animate({
						'margin-left' : -285
					});
					line.delay(400).animate({
						width : 3
					},200);
					$(this).find('.details').delay(700).slideUp();
					$(this).data('down', false);
				}
			});

			// add initial url and add url code
			this.add_url();
			this.tile.find('.back').on('click', '.add', function() {
				self.add_url();
			});

			// form elements
			// pmp/ao
			this.tile.find('.back').on('click', '.options div', function() {
				$(this).toggleClass('selected');
				var search = self.tile.find('.back').find('.pmp-search');
				// could redo this
				if(self.type == 'pqt') {
					if($(this).hasClass('pmp')) {
						search.slideToggle();
					}
				} else {
					var count = 0;
					self.tile.find('.back').find('.pmpe, .pmp, .globaldeals').each(function() {
						if($(this).hasClass('selected')) {
							count++;
						}
					});
					if(count > 0) {
						search.slideDown();
					} else {
						search.slideUp();
					}
				}
			});

			// on dropdown click
			this.tile.find('.back').on('click', '.dropdown span', function() {
				$(this).toggleClass('open');
				self.tile.find('.back .dropdown ul').not($(this).next('ul')).slideUp();
				$(this).next('ul').slideToggle();
			});

			// on form submit
			this.tile.find('.back').on('click', '.run', function() {
				var data = self.get_form_values();
				var button = $(this);			
				var name = self.tile.find('[name="report-name"]');
				var url = self.tile.find('.urls input').eq(0);
				var submit = true;
				name.removeClass('invalid');
				url.removeClass('invalid');

				if(name.val() === '') {
					submit = false;
					name.addClass('invalid');
				}
				if(url.val() === '') {
					submit = false;
					url.addClass('invalid');
				}
				if(submit) {
					button.addClass('run-loading');
					name.removeClass('invalid');
					url.removeClass('invalid');
					$.post(self.urls.save, data, function(response) {
						if(response.success) {
							button.removeClass('run-loading');
							self.tile.find('.back .create').click();
							self.reload_items_ajax();
							self.reset_form_values();
						} else {
							if(response.error == 'count') {
								alert('Sorry, you can only run one report at a time');
								button.removeClass('run-loading');
							} else {
								alert('Error, please try again later or tell Ben you are seeing this error');
							}
						}
					});
				}
			});
			
			this.tile.find('.front').on('click', '.arrow-left', function() {
				self.date = new Date(new Date().setDate(self.date.getDate()-1));
				self.urls.main = self.urls.main_old + '?date=' + (self.date).toISOString().substring(0, 10);
				self.reload_items_ajax();
				self.update_date();
			});
			
			this.tile.find('.front').on('click', '.arrow-right', function() {
				if(new Date(new Date().setDate(self.date.getDate()+1)) <= new Date()) {
					self.date = new Date(new Date().setDate(self.date.getDate()+1));
					self.urls.main = self.urls.main_old + '?date=' + (self.date).toISOString().substring(0, 10);
					self.reload_items_ajax();
					self.update_date();
				}
			});
			
			this.update_date();
		},
		
		update_date : function() {
			if(this.date) {
				this.tile.find('.date-picker input').val(this.date.toDateString());
			}
		},

		reset_form_values : function() {
			var self = this;
			var div = this.tile.find('.back');
			div.find('.dropdown input').prop('checked', false);
			div.find('[name="pmpsearch"]').val('');
			div.find('[name="report-name"]').val('');
			div.find('.pmp').removeClass('selected');
			div.find('.oa').removeClass('selected');
			div.find('.urls input').val('');
			div.find('.pmp-search').slideUp();
		},

		get_form_values : function() {
			var div = this.tile.find('.back');
			var values = {};
			values.name = div.find('[name="report-name"]').val();
			values.deal_id = div.find('[name="pmpsearch"]').val();
			values.user_id = div.find('[name="user_id"]').val();
			
			div.find('.options div').each(function() {
				var value = 0;
				if($(this).hasClass('selected')) {
					value = 1;
				}
				values[$(this).attr('class').split(' ')[0]] = value;
			});

			div.find('.dropdown').each(function(key, value) {
				var thisclass = $(this).attr('class').split(' ')[0];
				values[thisclass] = [];
				$(this).find('input').each(function(key, value) {
					if($(this).prop('checked')) {
						var id = $(this).data('id');
						values[thisclass].push(id);
					}
				});
			});

			values.urls = [];
			div.find('.urls input').each(function(key, value) {
				values.urls.push($(this).val());
			});
			
			console.log(values);

			return values;
		},

		get_organisations : function() {
			var self = this;
			$.get('widgets/organisations', function(response) {
				var div = self.tile.find('.back .organisations');
				div.addClass('loaded');
				$.each(response, function(key, value) {
					value.ORG_ID = value.ORG_ID.split('id')[1];
					div.find('ul').append('<li><label><input type="checkbox" data-id="' + value.ORG_ID + '" /> ' + value.ORG_NAME + '</label></li>');
				});
			});
		},

		get_exchanges : function() {
			var self = this;
			$.get('widgets/exchanges', function(response) {
				var div = self.tile.find('.back .exchanges');
				div.addClass('loaded');
				$.each(response, function(key, value) {
					value.EXCH_ID = value.EXCH_ID.split('id')[1];
					div.find('ul').append('<li><label><input type="checkbox" data-id="' + value.EXCH_ID + '" /> ' + value.EXCH_NAME + '</label></li>');
				});
			});
		},

		get_countries : function() {
			var self = this;
			$.get('widgets/countries', function(response) {
				var div = self.tile.find('.back .countries');
				div.addClass('loaded');
				$.each(response, function(key, value) {
					div.find('ul').append('<li><label><input type="checkbox" data-id="' + value.COUNTRY + '" /> ' + value.COUNTRY + '</label></li>');
				});
			});
		},

		add_url : function() {
			// remove old add button
			var last_url = this.tile.find('.back .urls .url').last();
			last_url.find('.add').remove();
			last_url.find('input').animate({
				width: 297
			});

			// add another url field
			var html = this.compiled.url();
			this.tile.find('.back .urls').append(html);
			last_url = this.tile.find('.back .urls .url').last();
			last_url.hide().slideDown();
		},

		date_time_interval : function() {
			this.tile.find('.front .content li').each(function() {
				var start = $(this).find('.time').data('time');
				var end = $(this).find('.time').data('finished');
				var time;
				if($(this).hasClass('active')) {
					time = moment.utc(start, "YYYY-MM-DD hh:mm:ss").fromNow(true);
				} else {
					time = moment.utc(start, "YYYY-MM-DD hh:mm:ss").from(moment.utc(end), true);
				}
				$(this).find('.time').text(time);
			});
		},

		date_time : function() {
			// run once first
			this.date_time_interval();

			var self = this;
			// display time running
			if(this.timeinterval) {
				window.clearInterval(this.timeinterval);
			}
			this.timeinterval = window.setInterval(function() {
				self.date_time_interval();
			}, 2000);
		},

		load_items : function() {
			var html = this.compiled.front_item(this.data);
			this.tile.find('.front .content').html(html);
		},

		show_items : function() {
			var self = this;
			$.each(this.visible, function(key, id) {
				var div = self.tile.find('.front .content').find('[data-id="' + id + '"]');
				var box = div.find('.details .box');
				var line = div.find('.details .line');
				div.data('down', true);
				div.find('.details').show();
				var box_height = box.outerHeight();
				box.css('margin-left', 0);
				line.css('bottom', box_height/2 + 20);
			});
		},

		reload_items_ajax : function() {
			var self = this;
			if(this.ajax) {
				this.ajax.abort();
			}
			this.ajax = $.get(this.urls.main, function(data) {
				self.ajax = false;
				self.data = data.data;
				self.load_items();
				self.reload_items();
				self.date_time();
				self.show_items();
			});
		},

		reload_items : function() {
			var self = this;
			this.iteminterval = window.setTimeout(function() {
				self.reload_items_ajax();
			}, 30000);
		},

		version : function(type) {
			if(this.type == 'pqt') {
				this.urls.save = 'widgets/publisher-query-tool-save';
				this.urls.main = 'widgets/publisher-query-tool';
				this.logo = 'querytool/logo.png';
			} else if(this.type == 'irt') {
				this.urls.save = 'widgets/incremental-reach-tool-save';
				this.urls.main = 'widgets/incremental-reach-tool';
				this.templates.back = '#irt-back-template';
				this.logo = 'querytool/logo-incremental.png';
			} else if(this.type == 'qb') {
				this.urls.save = '';
				this.date = new Date();
				this.urls.main_old = 'widgets/qubole-data-status';
				this.urls.main = 'widgets/qubole-data-status?date=' + (this.date).toISOString().substring(0, 10);
				this.templates.back = '#irt-back-template';
				this.templates.front_item = '#qb-front-item';
				this.logo = 'querytool/logo-qubole.png';
			}
		},

		init : function() {
			var self = this;
			this.data = data.data;
			this.tile = tile;
			this.type = type;
			
			// bit of a hack to use this code for multiple projects
			this.version();

			// compile all templates
			$.each(this.templates, function(key, value) {
				self.load_template(key);
			});

			// append front and back templates
			$.each(['front', 'back'], function(key, value) {
				data.logo = self.logo;
				var html = self.compiled[value](data);
				self.tile.find('.' + value).html(html);
			});

			// load items
			this.load_items();

			//add events code
			this.events();
			this.date_time();
			this.reload_items();
		}
	};
};

// for older browsers
Array.prototype.indexOf||(Array.prototype.indexOf=function(r){var t=this.length>>>0,e=Number(arguments[1])||0;for(e=0>e?Math.ceil(e):Math.floor(e),0>e&&(e+=t);t>e;e++)if(e in this&&this[e]===r)return e;return-1;});