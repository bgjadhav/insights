/* global window:true, $:false, Handlebars:false, Dropzone:false */
$(function() {
	"use strict";
	var competitiveIntel = {
		options : {
			component: 'All',
			label: 'All',
			search: '',
			regions: 'All',
			main: $('#roadmap .main'),
			notice: $('#roadmap #aggregate_date'),
			title: $('#roadmap h1 span'),
			loading: '<div id="loading"><img src="_img/loading.gif" /></div>'
		},

		getClassJira : function(status) {
			var color_status = [
				'New', 'Reopened', 'Open', 'Not Prioritized', 'Not Scoped',
				'Conception', 'Backlog', 'Document UAT Plan', 'Pending',
				'Initial Discussion', 'To Do', 'Ready for Development',
				'Discovery', 'Pending Retrospective', 'Req/RMA Hardware',
				'Waiting on Development Resources', 'In Review'
			];
			if (color_status.indexOf(status) > -1) {
				return 'new';
			}

			color_status = [
				'QA', 'Closed', 'Released: Post-Release Monitoring in Progress',
				'Ready for Prod Release', 'Done', 'Deferred', 'Reviewed',
				'Resolved - Approved', 'Reviewed', 'Not Applicable', 'Hired',
				'MM Approved', 'Approved', 'MM Declined', 'Reference',
				'Work Complete', 'Partial Release', 'RMA - Returned Item',
				'RMA - Recycled', 'In Production', 'Denied', 'Cancelled',
				'Finalized', 'Released', 'To Be Deleted'
			];
			if (color_status.indexOf(status) > -1) {
				return 'complete';
			}

			return 'in-progress';
		},

		getPriorityName:  function(priority) {
			var priorities_names = [
				'',
				'P1 - Blocker',
				'P2 - Critical',
				'P3 - Major',
				'P4 - Minor',
				'P5 - Trivial',
				'P0 - Production Problem',
				'P0 - Unassigned',
				'Major',
				'Minor',
				'Critical',
				'Trivial',
				'Blocker'
			];
			return priorities_names[priority];
		},

		displayCompetitiveIntel: function(data) {
			var self = this;
			self.options.main.empty();

			$.each(data, function(index, value) {
				var t = value.date_created.split(/[- :]/);
				var d = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
				value.nice_date = ("0" + d.getDate()).slice(-2) + '-' + ("0" + parseInt(d.getMonth()+1)).slice(-2) + '-' + d.getFullYear();

				var amount;
				this.components_title = '';
				if (this.components.length > 0 ) {
					this.components_title = this.components[0].component;
				}

				if(this.components.length > 1) {
					amount = this.components.length -1;
					this.components_title += ' (+' + amount + ')';
				}
				this.components.shift();

				if(this.labels.length > 0) {
					this.labels_title = this.labels[0].label;
					if(this.labels.length > 1) {
						amount = this.labels.length -1;
						this.labels_title += ' (+' + amount + ')';
					}
					this.labels.shift();
				} else {
					this.labels_title = 'No Labels';
				}

				value.open_tracking_url  = 'http://issues.mediamath.com/browse/'+value.key;
				var region = value.region;
				if (region == 0 ) { value.region  = 'GLOBAL'; }
				value.priority_name = self.getPriorityName(value.priority);
				value.classStatus = self.getClassJira(value.status);

				var overwiew = value.description;
				overwiew = overwiew.replace(/<br \/>/g, ' ').replace(/\u00A0/g, '');
				value.overview = overwiew.trim().replace('  ', ' ').replace(/(\r\n|\n|\r)/gm, ' ').substring(0, 100);

				var html = self.options.competitiveIntelTemplate(value);
				self.options.main.append(html);
				value.description = value.description.trim();
				value.description = value.description.replace(/(\r)/gm, '');
				value.description = value.description.replace(/(\n)/gm, '<br>');
				value.description = value.description.replace(/\u00A0/g, '');
				value.description = value.description.replace(/(<br\s*\/?>){3,}/gi, '<br>');
				$('#desc_'+value.issue_id).hide();
				$('#desc_'+value.issue_id).html(value.description);
			});
		},

		loadCompetitiveIntel: function() {
			var self = this;
			this.track();
			$.getJSON('market_insights/data?idComponent='+this.options.component+'&idLabel='+this.options.label+'&regions='+this.options.regions+'&search='+this.options.search, function(data) {
				self.displayCompetitiveIntel(data);
				self.options.main.find('.status span, .image span').fooltips();
				//self.options.main.find('.statusDescription').html();
				$(this).html();
			}).fail( function(xhr, textStatus, errorThrown) {
				console.log(xhr.responseText);
			});
		},

		initFilterComponent: function() {
			var self = this;
			$.getJSON('meta_mi_component', function(data) {
				$.each(data, function(key, value) {
					var li = '<option value="'+value+'">'+value+'</option>';
					$('#components').append(li);
				});
			});
		},

		initFilterLabel: function() {
			var self = this;
			$.getJSON('meta_mi_label', function(data) {
				$.each(data, function(key, value) {
					var li = '<option value="'+value+'">'+value+'</option>';
					$('#labels').append(li);
				});
			});
		},

		initFilterRegions: function() {
			var self = this;
			$.getJSON('meta_mi_region', function(data) {
				$.each(data, function(key, value) {
					var li = '<option value="'+value+'">'+value+'</option>';
					$('#regions').append(li);
				});
			});
		},

		showmore : function(div) {
			var parent = div.parents('div.sub-row');
			parent.find('.more').toggleClass('less');
			parent.find('span.description-detail').slideToggle();
			parent.find('span.info').toggle();
			parent.find('.components ul').slideToggle();
		},

		events: function() {
			var self = this;
			$('#components').on('change', function() {
				self.options.component = $(this).val();
				self.loadCompetitiveIntel();
			});

			$('#labels').on('change', function() {
				self.options.label = $(this).val();
				self.loadCompetitiveIntel();
			});

			$('#regions').on('change', function() {
				self.options.regions = $(this).val();
				self.loadCompetitiveIntel();
			});

			// split into many, as per simons request
			$('#roadmap').on('click', '.more', function(e) {
				self.showmore($(this));
			});

			$('#roadmap').on('click', '.components .title', function(e) {
				self.showmore($(this));
			});

			$('#roadmap').on('click', '.button a', function(e) {
				e.preventDefault();
				self.showmore($(this));
			});

			$('#roadmap').on('click', 'span.info', function(e) {
				self.showmore($(this));
			});

			$('#search').on('change keyup input', function() {
				if (self.options.search != $(this).val()) {
					self.options.search = $(this).val();
					window.clearTimeout(self.timer);
					self.timer = window.setTimeout(function() {
						self.loadCompetitiveIntel();
					}, 500);
				}
			});
		},

		track: function() {
			var data = {};
			data.data = ['Default'];
			data.success = '1';
			$.post('/analytics/table/Marketing Insights/send-track-props', data).fail( function(xhr, textStatus, errorThrown) {
				console.log(xhr.responseText);
			});
		},

		init: function() {
			this.events();
			this.initFilterComponent();
			this.initFilterLabel();
			this.initFilterRegions();
			var source = $("#competitiveintel-template").html();
			this.options.competitiveIntelTemplate = Handlebars.compile(source);
			this.loadCompetitiveIntel();
		}
	};

	competitiveIntel.init();
});
