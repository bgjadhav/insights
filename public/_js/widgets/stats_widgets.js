var stats_widgets = function(tile, data) {
	return {
		templates : {
			main : '#stats-template-main'
		},
		template : 'main',
		section : 'MoM',
		category : 'orgs',
		country : 'United Kingdom',

		events : function()
		{
			var self = this;
			this.div.on('change', '.countries', function() {
				self.country = $(this).val();
				$('#homepage').find('.countries').val(self.country).trigger('changed');
				self.country = $(this).val();
			});
			
			this.div.on('changed', '.countries', function() {
				self.country = $(this).val();
				self.update_country();
			});
		},
		
		template_html : function(data, template) {
			var source = $(this.templates[template]).html();
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
		
		show_data : function(data) {
			if(data.size == 'table') {
				this.div.find('.stats-content').addClass('type-table').html(widgets.templates.table(data, tile));
			} else if(data.size == 'pie') {
				widgets.charts.pie(data, this.div.find('.stats-content'), 'yellow')
			} else if(data.size == 'donut') {
				widgets.charts.donut(data, this.div.find('.stats-content'), 'yellow')
			}
		},
		
		update_country : function() {
			var self = this;
			if(this.ajax) {
				this.ajax.abort();
			}
			this.ajax = $.get('widgets/' + this.script + '?country=' + this.country + '&data=true', function(response) {
				self.show_data(response);
				self.sidebar(response);
			});
		},
		
		sidebar : function(data) {
			if(data.sidebar != undefined && data.sidebar) {
				console.log('sidebar');
				this.div.find('.stats-side').html(
					'<span class="label label-top">' + data.sidebar[1].name + '</span><span class="label label-bottom">' + data.sidebar[0].name + '</span>' +
					'<div class="progress">' +
						'<span class="text">' + data.sidebar[0].value + '</span>' +
						'<span class="bar" style="height: ' + data.percent + '%"><span class="text">' + data.sidebar[1].value + '</span></span>' +
					'</div>'
				);
			}
		},
		
		complete : function() {
			
		},

		init : function()
		{
			tile.find('.new').delay(1500).fadeOut();
			
			this.div = tile.find('.front');
			this.script = tile.data('script');
			this.div.html(this.template_html(data, 'main'));
			this.sidebar(data);
			this.events();
			
			this.div.find('.countries').val(this.country).trigger('changed');
		}
	}
}