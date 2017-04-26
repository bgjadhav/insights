var channel_widget = function(tile, data) {
	return {
		templates : {
			main : '#channel-template-main',
			small : '#channel-template-small',
			chart : '#channel-template-chart',
			styled : '#channel-template-styled'
		},
		template : 'main',
		section : 'MoM',
		category : 'orgs',

		events : function()
		{
			var self = this;

			this.div.on('click', 'li', function() {
				self.div.find('li').removeClass('active');
				self.section = $(this).text();
				self.get_data();
			});

			this.div.on('click', '.switcher span', function() {
				if(self.category == 'orgs') {
					self.category = 'advs';
				} else {
					self.category = 'orgs';
				}
//				self.div.find('.switcher span').hide();
				self.get_data();
			});
			
			this.div.on('click', '.buttons span', function() {
				self.category = $(this).data('type');
				self.get_data();
			});
		},

		get_data : function()
		{
			var self = this;
			this.div.find('.rows').html('<div class="channel-loading"><img src="_img/ajax-loader-dark.gif" /></div>');
			if(this.ajax) {
				this.ajax.abort();
			}
			this.ajax = $.get('widgets/' + this.script + '?type=' + this.section + '&category=' + this.category, function(response) {
				if(response.size == 'chart') {
					self.chart(response);
				} else {
					self.create_widget(response);
				}
			});
		},

		manipulate_data : function(data) {
			$.each(data.data, function(key, value) {
				if(value.change == 'UP') {
					data.data[key].change = 'triangle triangle-green';
				} else if(value.change == 'DOWN') {
					data.data[key].change = 'triangle triangle-red';
				} else if(value.change == 'NON MOVER') {
					data.data[key].change = 'square';
				} else if(value.change == 'NEW') {
					data.data[key].change = 'square';
				}
			});
			
			if(this.section == 'WoW') {
				data.timeframe = 'week';
			} else if(this.section == 'MoM') {
				data.timeframe = 'month';
			} else if(this.section == 'QoQ') {
				data.timeframe = 'quarter';
			} else if(this.section == 'YoY') {
				data.timeframe = 'year';
			}
			
			data.category = this.category;
			data.category_name = this.category.substring(0,3);

			return data;
		},
		
		template_html : function(data) {
			var source = $(this.templates[this.template]).html();
			var template = Handlebars.compile(source);
			var html = template(data);
			return html;
		},

		create_widget : function(data) {
			data = this.manipulate_data(data);
			var html = this.template_html(data);
			this.div.html(html);
			this.complete();
		},
		
		complete : function() {
			// set active tab
			this.div.find('li[data-type="'+this.section+'"]').addClass('active');
			this.div.find('.switcher .' + this.category).show();
			this.div.find('.switcher .triangle').show();
			
			// animate
			this.div.find('.row, .section').css({
				opacity : 0,
				'margin-top' : 20
			});
			var opacity = 1;
			$.each(this.div.find('.row, .section'), function(key, value) {
				if(key == 12) {
					opacity = 0.66;
				} else if(key == 13) {
					opacity = 0.33;
				}
				$(this).delay(key*75).animate({
					opacity: opacity,
					'margin-top': 0
				}, 200);
			});
		},

		chart : function(data) {
			if(data.chart.type == 'styled') {
				this.template = 'styled';
			} else {
				this.template = 'chart';
			}
			var html = this.template_html(data);
			this.div.html(html);
			var chart = charts[data.chart.style](data);
			this.div.find('.chart').highcharts(chart);
			
			this.div.find('.buttons span[data-type="' + this.category + '"]').addClass('active');
			
			if(data.chart.brain) {
				var colours = ['#7968ac', '#5bbbea', '#f69f19', '#5c8527', '#de396d'];
				this.div.find('.chart .highcharts-yaxis-labels span').each(function(key) {
					$(this).css('color', colours[key]);
				});
			}
			
			if(data.chart.sexy) {
				this.div.addClass('sexy');
			}
		},

		init : function()
		{
			tile.find('.new').delay(1500).fadeOut();
			this.div = tile.find('.front');
			this.script = tile.data('script');
			this.events();

			if(data.size == 'chart') {
				this.category = 'billed_spend';
				this.chart(data);
			} else {
				if(data.size == 'small') {
					this.template = 'small';
				}

				this.create_widget(data);
			}
		}
	}
}