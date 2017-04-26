/* global window:true, $:false, Handlebars:false, Dropzone:false */
$(function() {
	"use strict";
	var pipeline = {
		options : {
			search: '',
			main: $('#roadmap .main'),
			notice: $('#roadmap #aggregate_date'),
			title: $('#roadmap h1 span'),
			loading: '<div id="loading"><img src="_img/loading.gif" /></div>'
		},

		displayPipeline: function(rows) {
			var self = this;
			self.options.main.empty();
			$.each(rows, function(key, value) {
				var stage = value.stage.substring(0,1);
				value.progress = stage;

				var name = value.opportunity_owner.split(' ');
				value.open_tracking_url = false;
//				if (value.open_tracking.indexOf('http') >= 0 ){
//					value.open_tracking_url = value.open_tracking;
//				}
				//var open_tracking = var containsFoo = str.indexOf('foo') >= 0;
				value.avatar_url = '/_img/avatars/'+value.opportunity_owner.toLowerCase().replace(/ /g, '_') +'.jpg';
				value.initials = name[0].substring(0,1) + name[1].substring(0,1);

				var html = self.options.pipelineTemplate(value);
				self.options.main.append(html);
			});
		},

		loadPipeline: function() {
			var self = this;
			this.options.title.html(this.options.type);
			this.options.main.html(this.options.loading);
			$.getJSON('pipeline/' + this.options.type + '?search=' + this.options.search + '&sorting=' + this.options.sorting, function(data) {
				self.displayPipeline(data);
				// display tooltips
				self.options.main.find('.status span, .image span').fooltips();
				if (data.length > 0) {
					self.notice(data[0].aggregate_date);
				}
			});
		},

		notice: function(aggregate_date){
			var date = aggregate_date.split(' ');
			this.options.notice.empty().append('('+date[0]+')');
		},

		events: function() {
			var self = this;
			$('#pipeline-select').on('click', 'li', function() {
				$('#pipeline-select li').removeClass('active');
				$(this).addClass('active');
				self.options.type = $(this).data('type');
				self.loadPipeline();
			});

			$('#search').on('change keyup input', function() {
				if (self.options.search != $(this).val()) {
					self.options.search = $(this).val();
					window.clearTimeout(self.timer);
					self.timer = window.setTimeout(function() {
						self.loadPipeline();
					}, 500);
				}
			});

			$('#sorting').on('change', function() {
				self.options.sorting = $(this).val();
				self.loadPipeline();
			});

			$('#upload-buttons').on('click', 'li', function() {

			});
		},

		uploads : function() {
			var self = this;
			Dropzone.autoDiscover = false;

			var sections = {
				media : {
					div : $("#media-dropzone"),
					text : 'Update Media Partnership Pipeline'
				},
				data : {
					div : $("#data-dropzone"),
					text : 'Update Data / tech Partnership Pipeline'
				}
			};

			$.each(sections, function(key, value) {
				var options = {
					maxFiles : 1,
					addedfile : function() {
						value.div.find('.loading').show();
					},
					uploadprogress : function(progress, percent) {
						value.div.find('.loading span').width(percent + '%');
						if(percent == 100) {
							value.div.find('.processing').show();
							value.div.find('.dz-message').hide();
						}
					},
					complete : function(file) {
						var result = $.parseJSON(file.xhr.response);

							console.log(file);
						value.div.find('.loading').hide();
						value.div.find('.dz-message').show();
						value.div.find('.processing').hide();
						this.removeAllFiles();

						if(result.success === false) {
							alert('Error on load data');
						}

						// refresh page
						self.loadPipeline();
					}
				};

				options.dictDefaultMessage = value.text;

				value.div.dropzone(options);
			});
		},

		track: function() {
			var data = {};
			data.data = ['Default'];
			data.success = '1';
			$.post('/analytics/table/'+this.options.type+' Pipeline/send-track', data).fail( function(xhr, textStatus, errorThrown) {
				console.log(xhr.responseText);
			});
		},



		init: function() {
			// default values
			this.options.type = $('#pipeline-select li.active').data('type');
			this.options.sorting = $('#sorting').val();
			this.events();

			var source = $("#pipeline-template").html();
			this.options.pipelineTemplate = Handlebars.compile(source);
			this.loadPipeline();
			this.uploads();
			this.track();
		}
	};

	pipeline.init();
});
