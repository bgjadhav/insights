/* global window:true, $:false, Handlebars:false, Dropzone:false */
$(function() {
	"use strict";
	var JiraPerformance = {

		dropdownsEvent:  function(section) {
			var self = this;

			// dropdowns
			// slide down dropdowns
			section.on('click', 'span.name', function(e) {
				e.stopPropagation();
				$(this).next('ul').slideToggle();
			});

			//~ section.on('click', '.dropdown li', function(e) {
				//~ e.stopPropagation();
			//~ });
		},

		clearAll:  function(section) {
			var self = this;
			section.on('click', '.options .clear', function(e) {
				e.stopPropagation();
				var parent = $(this).parents('ul');
				parent.find('li input').prop('checked', false);
			});
		},

		events: function() {
			var self = this;
			var section = this.getSectionFilters();
			this.dropdownsEvent(section);
			this.clearAll(section);
		},

		getSectionFilters:  function() {
			return $('#filters_jira_performance');
		},

		eventsProduct: function() {
			var self = this;
			var section = this.getSectionFiltersProduct();
			this.asigneeOptionSelectAllEventProduct(section);
			this.reporterOptionsSelectAllEventProduct(section);
			this.asigneeListEventProduct(section);
			this.reporterListEventProduct(section);
			this.dateEventProduct(section);

		},

		getSectionFiltersProduct:  function() {
			return $('#content .product #filters_jira_performance');
		},

		asigneeOptionSelectAllEventProduct:  function(section) {
			var self = this;

			section.on('click', '.Asignee_product .options .select', function(e) {
				e.stopPropagation();
				var parent = $(this).parents('ul');
				parent.find('li input').prop('checked', true);
				parent.find('li #perf_Asignee_all').prop('checked', false);

				$('#DBOpenActivity_PRDREQ').find('.filters .Asignee .options .select').trigger('click');
				$('#DBResolutionAnalytics').find('.filters .Asignee .options .select').trigger('click');

			});
		},

		asigneeListEventProduct:  function(section) {
			var self = this;
			section.on('click', '.Asignee_product ul li input', function(e) {

				var parent = $(this).parents('ul');
				parent.find('li input').prop('checked', false);
				$(this).prop('checked', true);

				var asignee = self.callAsignee('product');
				$('#DBOpenActivity_PRDREQ').find('.Asignee input:checkbox').prop('checked', false);
				$('#DBResolutionAnalytics').find('.Asignee input:checkbox').prop('checked', false);
				$('#DBOpenActivity_PRDREQ').find(".filters .dropdown ul.Asignee input[id='Asignee_"+asignee[0]+"']").trigger('click');
				$('#DBResolutionAnalytics').find(".filters .dropdown ul.Asignee input[id='Asignee_"+asignee[0]+"']").trigger('click');

			});
		},

		reporterOptionsSelectAllEventProduct:  function(section) {
			var self = this;
			section.on('click', '.Reporter_product .options .select', function(e) {
				e.stopPropagation();
				var parent = $(this).parents('ul');
				parent.find('li input').prop('checked', true);
				parent.find('li #perf_Reporter_all').prop('checked', false);

				$('#DBOpenActivity_PRDREQ').find('.filters .Reporter .options .select').trigger('click');
				$('#DBResolutionAnalytics').find('.filters .Reporter .options .select').trigger('click');

			});
		},

		reporterListEventProduct:  function(section) {
			var self = this;
			section.on('click', '.Reporter_product ul li input', function(e) {

				var parent = $(this).parents('ul');
				parent.find('li input').prop('checked', false);
				$(this).prop('checked', true);
				//e.stopPropagation();

				var reporter = self.callRepoter('product');

				$('#DBOpenActivity_PRDREQ').find('.Reporter input:checkbox').prop('checked', false);
				$('#DBResolutionAnalytics').find('.Reporter input:checkbox').prop('checked', false);
				$('#DBOpenActivity_PRDREQ').find(".filters .dropdown ul.Reporter input[id='Reporter_"+reporter[0]+"']").trigger('click');
				$('#DBResolutionAnalytics').find(".filters .dropdown ul.Reporter input[id='Reporter_"+reporter[0]+"']").trigger('click');

			});
		},

		dateEventProduct:  function(section) {

			// set the default date
			section.find('div.date input.start').val(moment(section.find('div.date input.start').data('value')).format('YYYY-MM-DD'));
			section.find('div.date input.end').val(moment(section.find('div.date input.end').data('value')).format('YYYY-MM-DD'));
			var today = moment().toDate();
			// start date dropdown
			section.find('div.date input.start').pikaday({
				format: 'YYYY-MM-DD',
				maxDate: today,
				onSelect: function() {
					$('#DBOpenActivity_PRDREQ').find('div.date input.start').val(section.find('.date .start').val());
					$('#DBResolutionAnalytics').find('div.date input.start').val(section.find('.date .start').val());
					$('#DBOpenActivity_PRDREQ').find('.refresh').trigger('click');
					$('#DBResolutionAnalytics').find('.refresh').trigger('click');
				}
			});


			// end date dropdown
			section.find('div.date input.end').pikaday({
				format: 'YYYY-MM-DD',
				maxDate: today,
				onSelect: function() {
					$('#DBOpenActivity_PRDREQ').find('div.date input.end').val(section.find('.date .end').val());
					$('#DBResolutionAnalytics').find('div.date input.end').val(section.find('.date .end').val());
					$('#DBOpenActivity_PRDREQ').find('.refresh').trigger('click');
					$('#DBResolutionAnalytics').find('.refresh').trigger('click');
				}
			});


		},

		eventsPartner: function() {
			var self = this;
			var section = this.getSectionFiltersPartner();
			this.asigneeOptionSelectAllEventPartner(section);
			this.reporterOptionsSelectAllEventPartner(section);
			this.asigneeListEventPartner(section);
			this.reporterListEventPartner(section);
			this.dateEventPartner(section);
		},

		getSectionFiltersPartner:  function() {
			return $('#content .partner #filters_jira_performance');
		},

		asigneeOptionSelectAllEventPartner:  function(section) {
			var self = this;

			section.on('click', '.Asignee_partner .options .select', function(e) {
				e.stopPropagation();
				var parent = $(this).parents('ul');
				parent.find('li input').prop('checked', true);
				parent.find('li #perf_Asignee_all').prop('checked', false);

				$('#DBOpenActivity').find('.filters .Asignee .options .select').trigger('click');
				$('#DBOpenActivitySubType').find('.filters .Asignee .options .select').trigger('click');
				$('#DBOpenedSubType').find('.filters .Asignee .options .select').trigger('click');

			});
		},

		asigneeListEventPartner:  function(section) {
			var self = this;
			section.on('click', '.Asignee_partner ul li input', function(e) {

				var parent = $(this).parents('ul');
				parent.find('li input').prop('checked', false);
				$(this).prop('checked', true);

				var asignee = self.callAsignee('partner');
				$('#DBOpenActivity').find('.Asignee input:checkbox').prop('checked', false);
				$('#DBOpenActivitySubType').find('.Asignee input:checkbox').prop('checked', false);
				$('#DBOpenedSubType').find('.Asignee input:checkbox').prop('checked', false);
				$('#DBOpenActivity').find(".filters .dropdown ul.Asignee input[id='Asignee_"+asignee[0]+"']").trigger('click');
				$('#DBOpenActivitySubType').find(".filters .dropdown ul.Asignee input[id='Asignee_"+asignee[0]+"']").trigger('click');
				$('#DBOpenedSubType').find(".filters .dropdown ul.Asignee input[id='Asignee_"+asignee[0]+"']").trigger('click');

			});
		},

		reporterOptionsSelectAllEventPartner:  function(section) {
			var self = this;
			section.on('click', '.Reporter_partner .options .select', function(e) {
				e.stopPropagation();
				var parent = $(this).parents('ul');
				parent.find('li input').prop('checked', true);
				parent.find('li #perf_Reporter_all').prop('checked', false);

				$('#DBOpenActivity').find('.filters .Reporter .options .select').trigger('click');
				$('#DBOpenActivitySubType').find('.filters .Reporter .options .select').trigger('click');
				$('#DBOpenedSubType').find('.filters .Reporter .options .select').trigger('click');

			});
		},

		reporterListEventPartner:  function(section) {
			var self = this;
			section.on('click', '.Reporter_partner ul li input', function(e) {

				var parent = $(this).parents('ul');
				parent.find('li input').prop('checked', false);
				$(this).prop('checked', true);
				//e.stopPropagation();

				var reporter = self.callRepoter('partner');

				$('#DBOpenActivity').find('.Reporter input:checkbox').prop('checked', false);
				$('#DBOpenActivitySubType').find('.Reporter input:checkbox').prop('checked', false);
				$('#DBOpenedSubType').find('.Reporter input:checkbox').prop('checked', false);
				$('#DBOpenActivity').find(".filters .dropdown ul.Reporter input[id='Reporter_"+reporter[0]+"']").trigger('click');
				$('#DBOpenActivitySubType').find(".filters .dropdown ul.Reporter input[id='Reporter_"+reporter[0]+"']").trigger('click');
				$('#DBOpenedSubType').find(".filters .dropdown ul.Reporter input[id='Reporter_"+reporter[0]+"']").trigger('click');

			});
		},

		dateEventPartner:  function(section) {

			// set the default date
			section.find('div.date input.start').val(moment(section.find('div.date input.start').data('value')).format('YYYY-MM-DD'));
			section.find('div.date input.end').val(moment(section.find('div.date input.end').data('value')).format('YYYY-MM-DD'));
			var today = moment().toDate();
			// start date dropdown
			section.find('div.date input.start').pikaday({
				format: 'YYYY-MM-DD',
				maxDate: today,
				onSelect: function() {
					$('#DBOpenActivity').find('div.date input.start').val(section.find('.date .start').val());
					$('#DBOpenActivitySubType').find('div.date input.start').val(section.find('.date .start').val());
					$('#DBOpenedSubType').find('div.date input.start').val(section.find('.date .start').val());
					$('#DBOpenActivity').find('.refresh').trigger('click');
					$('#DBOpenActivitySubType').find('.refresh').trigger('click');
					$('#DBOpenedSubType').find('.refresh').trigger('click');
				}
			});


			// end date dropdown
			section.find('div.date input.end').pikaday({
				format: 'YYYY-MM-DD',
				maxDate: today,
				onSelect: function() {
					$('#DBOpenActivity').find('div.date input.end').val(section.find('.date .end').val());
					$('#DBOpenActivitySubType').find('div.date input.end').val(section.find('.date .end').val());
					$('#DBOpenedSubType').find('div.date input.end').val(section.find('.date .end').val());
					$('#DBOpenActivity').find('.refresh').trigger('click');
					$('#DBOpenActivitySubType').find('.refresh').trigger('click');
					$('#DBOpenedSubType').find('.refresh').trigger('click');
				}
			});


		},


		callRepoter: function(projectPage) {
			var self = this;
			var section = this.getSectionFilters();
			if (section.find('.Reporter_'+projectPage).length > 0) {
				var reporter = [];
				section.find('.Reporter_'+projectPage).each(function() {
					$('input', this).each(function() {
						if($(this).prop('checked') && $(this).val() != 'on') {
							reporter.push($(this).val());
						}
					});
				});
				return reporter;
			}
		},

		callAsignee: function(projectPage) {
			var self = this;
			var section = this.getSectionFilters();
			if (section.find('.Asignee_'+projectPage).length > 0) {
				var assignee = [];
				section.find('.Asignee_'+projectPage).each(function() {
					$('input', this).each(function() {
						if($(this).prop('checked') && $(this).val() != 'on') {
							assignee.push($(this).val());
						}
					});
				});
				return assignee;
			}
		},

		init: function() {
			this.events();
			this.eventsProduct();
			this.eventsPartner();
			//$(window).resize();
			//~ $(window).focus(function(){
				//~ $(window).resize();
			//~ });
		}
	};

	JiraPerformance.init();
});
