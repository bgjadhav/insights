/* global window:true, $:false, Handlebars:false, Dropzone:false */
$(function() {
	"use strict";
	var projectPage = {

		options: {
			component: '',
			label: '',
			currentBasePath: '',
			status: '',
			target: '',
			download: 'current',
			hideReleased: '',
			tid: 0,
			search: '',
			year: '',
			geo: '',
			order: '',
			orderI: '',
			reset: false,
			firstLoad: '',
			filtered: '',
			colspan: 8,
			filters: {},
			section: $('#roadmap'),
			main: $('#roadmap table.main'),
			roadmap: ''
		},

		loadContent: function() {
			var self = this;

			self.checkDisplay();

			var filters = self.getCurrentFilters();
			var this_tid = filters.tid;

			filters = $.param(filters);

			$.getJSON(self.options.currentBasePath+'/data?'+filters, function(data) {

				if (data.length !== 0) {

					self.displayProduct(data, this_tid);

					self.setFooltips();

				} else {
					self.noSearch();
				}

			});

			self.cleanExtraTable();

		},

		getCurrentFilters: function() {
			return {
				'idComponent' : this.options.component,
				'idLabel' : this.options.label,
				'status' : this.options.status,
				'search' : this.options.search,
				'roadmap' : this.options.roadmap,
				'year' : this.options.year,
				'geo' : this.options.geo,
				'idTarget' : this.options.target,
				'idOrder' : this.options.orderI,
				'tid' : this.options.tid,
				'order' : this.options.order,
				'firstLoad' : this.options.firstLoad,
				'hideReleased' : this.options.hideReleased,
				'filtered' : this.options.filtered,
				'reset': this.options.reset,
				'init' : this.options.loaded
			};

		},

		displayProduct: function(data, this_tid) {
			var self = this;

			// content
			data = self.prepareData(data);

			self.fillTable(data);

			self.openDetailIfFilterTid(this_tid, data.length);


		},

		detailOpenAndClosing: function(tr, tid) {

			var self = this;

			var row = self.options.section.find('table.main').DataTable().row(tr);

			if (row.child.isShown()) {
				row.child.hide();
				row.child.remove();
				tr.removeClass('shown');

			} else {

				row.child(
					self.itemDescription(row.data()),

					'description_content '

				).show();

				tr.addClass('shown');

				tid = tid.replace('tc_link_to_', '');

				self.track('open_detail', tid);
			}

			self.cleanExtraTable();


		},

		openDetailIfFilterTid: function(this_tid, length) {
			if (this_tid > 0 && length == 1) {
				this.options.section.find('[id^="tc_link_to_"]').trigger('click');
			}


		},

		cleanExtraTable: function() {
			var section = $('#roadmap');

			section.find('.dataTables_wrapper .dataTables_length').hide();
			section.find('.dataTables_wrapper .dataTables_filter').hide();
			section.find('.dataTables_wrapper .dataTables_info').hide();
			section.find('.dataTables_wrapper tbody .dataTables_empty').hide();
			section.find('.dataTables_wrapper .dataTables_paginate').hide();
			section.find('.dataTables_wrapper thead th').removeClass('sorting');


		},

		fillTable: function(data) {
			var self = this;

			var section = $('#roadmap');

			if(self.options.DataTable) {
				self.options.DataTable.destroy();
			}

			self.options.DataTable = section.find('table.main').DataTable({
				aaData: data,
				aoColumns: self.columnsByProject(),
				"paging": false,
				"filter": false,
				"ordering": false,
				"info": false,
				"dom" : 'rt',
				scrollY: '60vh',
				"scrollCollapse": true,
				"scrollX": true,
				bSort: false,
				"columnDefs": [ {
					"targets": 2,
					"createdCell": function (td, cellData, rowData, row, col) {
						var class_name = cellData.toLowerCase();
						class_name = class_name.replace('&', '');
						class_name = class_name.replace('  ', ' ');
						class_name = class_name.replace(/ /g, '_');
						class_name = class_name.replace('(', '');
						class_name = class_name.replace(')', '');
						class_name = class_name.replace('/', '_');
						class_name = class_name.replace('-', '_');
						$(td).addClass(class_name);


						}
					},
					{
						"targets": 1,
						"createdCell": function (td, cellData, rowData, row, col) {
							var id_name = $(cellData).find('a').attr('id');
							$(td).attr('id', 'tc_'+id_name);
						}
					}
				]
			});

		},

		prepareData: function(data) {
			var self = this;

			$.each(data, function(index, value) {

				value = self.getWatchers(value);

				value = self.getAssignee(value);

				value = self.getLabels(value);

				value = self.setDetailsTicket(value);

				value = self.fillEmptyValues(value);

				value = self.fillGear(value);

				value = self.getPriority(value);

				value = self.getComponents(value);

				value = self.getTargets(value);

				value.major = self.itemMajor(value.major);
				value.status = self.itemStatus(value);
				value.short_name = self.itemSummary(value);
				value.release_phase = self.itemStatus(value);
				value.target_release = self.itemChangedTarget(value);

				value.DT_RowId = "row_"+value.issue_id;

			});

			return data;
		},

		itemMajor: function(major) {
			if(major==true) {
				return '<span class="linkToTicket" data-title="Priority Project"><a class="info major"></a></span>';
			} else {
				return '<span class="" data-title="Info"><a class="info"></a></span>';
			}
		},

		itemSummary: function(row) {
			var limit = 60;

			var summary = row.epic_name.substring(0, limit);

			if (summary.length >= limit) {
				summary += ' ...';
			}

			return this.linkSummary(row, summary);
		},

		linkSummary: function(row, summary) {
			return '<span class="name"><span class="nameProd"'
			 + ' data-title="Link to ticket: '+row.key+'">'
			 + '<a id="link_to_'+row.issue_id+'" href="'+row.open_tracking_url+'" target="_blank">'+summary+'</a></span></span>';
		},

		itemStatus: function(row) {
			var release_phase = ['Partial Release (Alpha)', 'Partial Release (Closed Beta)', 'Released (Open Beta)', 'Released (GA)'];

			if(release_phase.indexOf(row.status) != -1) {
				return '<span class="linkToReleasePhase" data-title="For more'
					+' information about release phases, click the link.">'
					+'<a id="linkToReleasePhase_'+row.issue_id+'" href="https://wiki.mediamath.com/x/YKKeEw#ProductRoadmapDictionary-ProductReleasePhases"'
					+' target="_blank" class="info">'+row.status+'</a></span>';
			} else {
				return row.status;
			}
		},

		itemChangedTarget: function(row) {
			if(row.change_target_release) {
				return '<div class="tRelease" data-title="The Target Release date has been pushed back from its original"><i>'+row.target_release+'</i></div>';
			} else {
				return '<div class="tReleaseNo">'+row.target_release+'</div>';
			}
		},

		itemDescription: function(row) {

			var self = this;

			return '<table class="table-description" style="with:100%">'
				+row.description
				+'</table>';
		},

		cleanBodyTable: function() {
			$('#roadmap').find('tbody').empty();
		},

		addLoading: function() {
			var self = this;
			var img = self.loadingImage();

			var section = $('#roadmap');

			if (section.hasClass('.dataTables_scrollBody')) {
				section.find('dataTables_scrollBody table tbody').empty();
				section.find('.table-holder .dataTables_wrapper .dataTables_scrollHead tbody').remove();
				section.find('.dataTables_scrollBody tbody').html(img);

			} else {
				section.find('.table-holder .dataTables_wrapper .dataTables_scrollHead tbody').remove();
				section.find('tbody').html(img);
			}


		},

		removeLoading: function() {
			$('#roadmap').find('table tbody td.loading').html();


		},

		checkDisplay: function() {
			var self = this;
			$('#roadmap').find('tbody').html('');
			self.cleanBodyTable();
			self.cleanExtraTable();
			self.addLoading();


		},

		loadingImage: function() {
			return '<tr><td class="loading"><img src="'+insights_base_path+'/_img/ajax-loader.gif"></td></tr>';
		},

		setDetailsTicket: function(value) {

			var self = this;

			value.open_tracking_url  = 'http://issues.mediamath.com/browse/'+value.key;

			value.priority_name = value.priority;

			value.description = self.rowToDescription(['Weekly Status', value.weekly_update])

			+ self.rowToDescription(['Geo-specific Concerns', value.geo_specific])

			+ this.rowToDescription(['Assignee', value.assignee])

			+ this.rowToDescription(['Watchers', value.watchers])

			+ this.rowToDescription(['Labels', value.labels])

			+ self.rowToDescription(['Background', value.background_and_strat_fit])

			+ self.rowToDescription(['Requirements/Acceptance Criteria', value.requirements])

			+ self.rowToDescription(['Out of Scope', value.out_of_scope]);

			return value;
		},

		getLinkShareOne: function(tid) {

			var filtersTid = {
				'roadmap' : this.options.roadmap,
				'tid' : tid
			};

			filtersTid = $.param(filtersTid);

			var link = '<a class="TidLInk" href="';

			link = link+this.options.currentBasePath+'?'+filtersTid+'">';
			link = link+this.options.currentBasePath+'?'+filtersTid+'</a>';

			return link;
		},

		addShareThis: function(value) {
			value.description += this.rowToDescription(['Share Link', this.getLinkShareOne(value.issue_id)]);
			return value;
		},

		fillEmptyValues: function(value) {
			if (value.candidate_consid == '') {
				value.candidate_consid = 'None';
			}

			if (value.year == '') {
				value.year = 'None';
			}

			if (value.geo_all == '') {
				value.geo_all = 'None';
			}

			if (value.reporter == '') {
				value.reporter = 'Anonymous';
			}

			if (value.release_phase == '') {
				value.release_phase = 'None';
			}

			if (value.change_target_release==0) {
				value.change_target_release = false;
			}

			return value;
		},

		fillGear: function(value) {
			value.gear = '<div class="no-close-box arrow_gear" id="arrow_gear_'+value.issue_id+'"></div><ul class="no-close-box gear" id="gear_'+value.issue_id+'">'
			+'<li id="li_comment_'+value.issue_id+'" class="no-close-box gear_li li_comment"">Comment</li>'
			+'<li id="li_follow_'+value.issue_id+'" class="no-close-box gear_li li_follow">Follow</li>'
			+'<li id="li_share_'+value.issue_id+'" class="no-close-box gear_li li_share">Share</li>'
			+'<li id="li_view_id_'+value.issue_id+' li_view_'+value.key+'" class="no-close-box gear_li li_view">View</li>'
			+'</ul>';

			return value;
		},

		rowToDescription: function(data) {

			data[1] = data[1].trim();
			data[1] = data[1].replace(/<img[^>]*>/g,"<i class='security_deleted'>This image cannot be displayed in Insights. Please visit the corresponding JIRA ticket to view.</i>");
			data[1] = data[1].replace(/<script[^>]*>/g,"<i class='security_deleted'>This script cannot be displayed in Insights. Please visit the corresponding JIRA ticket to view.</i>");

			if (data[1] == '') {
				data[1] = 'None';
			}

			var classRow = data[0];
			classRow = classRow.toLowerCase();
			classRow = classRow.replace(/ /g, '_');
			classRow = classRow.replace('/', '_');

			return '<tr><td class="description-row description_'+classRow+'">'+data[0]+':</td>'
			+'<td class="descr_value_'+classRow+'">'+data[1]+'</td></tr>';
		},

		getComponents: function(value) {
			if (value.first_component == 'No Components' || value.first_component == '' ) {
				value.first_component == 'No Product Category'
			}
			return value;
		},

		getLabels: function(value) {

			value.labels_ = value.labels.split(",");

			value.labels_[0].trim();

			value.labels = value.labels.trim();

			if (value.labels == 'No Labels' || value.labels == '') {
				value.labels = 'None';
			}

			return value;
		},

		getAssignee: function(value) {
			var self = this;

			value.assignee = value.assignee.trim();

			if (value.assignee == '') {
				value.assignee = 'Unassigned';
			}

			return value;
		},

		getWatchers: function(value) {
			var self = this;

			value.watchers = value.watchers.trim();

			if (value.watchers == '') {
				value.watchers = 'No Watchers';
			}

			return value;
		},

		getPriority: function(value) {
			value.major = false;
			if (value.priority == 'Major') {
				value.major = true;
			}
			return value;
		},

		getTargets: function(value) {
			var self = this;
			value = self.getTargetRelease(value);
			value = self.getTargetOpenBeta(value);

			if (self.options.roadmap == 'roadmap') {
				value = self.getTargetActualGA(value);
			}
			value = self.getTargetClosedBeta(value);
			return value;
		},

		getTargetRelease: function(value) {
			if (value.target_release_year != '') {
				value.target_release += ' '+value.target_release_year;
			}
			value.target_release.trim();
			if (value.target_release == '') {
				value.target_release = 'None';
			}
			return value;
		},

		getTargetActualGA: function(value) {
			if (value.actual_ga_year != '') {
				value.actual_ga += ' '+value.actual_ga_year;
			}

			value.actual_ga.trim();

			if (value.actual_ga == '') {
				value.actual_ga = 'None';
			}
			return value;
		},

		getTargetOpenBeta: function(value) {
			if (value.target_open_beta_year != '') {
				value.target_open_beta += ' '+value.target_open_beta_year;
			}
			value.target_open_beta.trim();

			if (value.target_open_beta == '') {
				value.target_open_beta = 'None';
			}
			return value;
		},

		getTargetClosedBeta: function(value) {
			if (value.target_closed_beta_year != '') {
				value.target_closed_beta += ' '+value.target_closed_beta_year;
			}
			value.target_closed_beta.trim();
			if (value.target_closed_beta == '') {
				value.target_closed_beta = 'None';
			}
			return value;
		},

		setFooltips: function() {
			var self = this;
			self.options.main.find('.tRelease').fooltips({
				class : 'release',
				offsety : -145
			});

			self.options.main.find('.linkToTicket').fooltips({
				offsety : -150,
				offsetx : -7
			});

			self.options.main.find('.linkToReleasePhase').fooltips({
				offsety : -165
			});

			self.options.main.find('.nameProd').fooltips({
				offsety : -160
			});

			$('#s_major').find('.linkToTicket').fooltips({
				offsety : -150,
				offsetx : 10
			});


		},

		noSearch: function() {

			var self = this;
			self.cleanBodyTable();
			self.cleanExtraTable();

			var section = $('#roadmap');

			self.options.colspan = 9;

			var message = '<div id="noResults" class="yielded_no_results">Sorry! Your search yielded no results. Please try ';

			if (self.options.roadmap == 'roadmap') {
				message += 'the Candidates list or Requests tab.';

			} else {
				message += 'the Roadmap or Request tabs.';
			}

			message += '</div>';

			if (section.find('.dataTables_scrollBody tbody').leght > 0) {

				section.find('.dataTables_scrollBody tbody').html('<tr class="nosearched"><td colspan="'+self.options.colspan+'">'+message+'</td></tr>');

			} else {
				$('#roadmap .table-holder tbody').append('<tr class="nosearched"><td colspan="'+self.options.colspan+'">'+message+'</td></tr>');
			}


		},

		events: function() {
			var self = this;

			var filters = self.options.section.find('#filters');

			var section = $('#options');

			var switch_options = $('#switch_options');

			self.options.section.find('tbody').on('click', '[id^="tc_link_to_"]', function() {
				self.cleanExtraTable();

				var tr = $(this).closest('tr');

				self.detailOpenAndClosing(tr, $(this).attr('id'));

				self.cleanExtraTable();


			});

			filters.find('#components').on('change', function() {
				self.checkAllClassSelects(filters.find('#components'));
				self.resetPage();
				self.options.component = $(this).val();
				self.loadContent();


			});

			filters.find('#labels').on('change', function() {
				self.resetPage();
				self.checkAllClassSelects(filters.find('#labels'));
				self.options.label = $(this).val();
				self.loadContent();


			});

			filters.find('#yearT').on('change', function() {
				self.resetPage();
				self.checkAllClassSelects(filters.find('#yearT'));
				self.options.year = $(this).val();
				self.loadContent();


			});

			filters.find('#targetMM').on('change', function() {
				self.resetPage();
				self.checkAllClassSelects(filters.find('#targetMM'));
				self.options.target = $(this).val();
				self.loadContent();


			});

			filters.find('#statusT').on('change', function() {
				self.resetPage();
				self.checkAllClassSelects(filters.find('#statusT'));
				self.options.status = $(this).val();
				self.loadContent();


			});

			filters.find('#geoT').on('change', function() {
				self.resetPage();
				self.checkAllClassSelects(filters.find('#geoT'));
				self.options.geo = $(this).val();
				self.loadContent();


			});

			self.options.section.find('.sorting').on('click', function() {
				self.options.firstLoad = false;
				self.removeSortingClass();

				var newOrder = $(this).attr('id').substring(2);
				if (newOrder == self.options.orderI) {
					self.options.orderI = $(this).attr('id').substring(2);
					self.changeSameOrderItem($(this));

				} else {
					self.options.orderI = $(this).attr('id').substring(2);
					$(this).addClass('sorting_asc');
					self.options.order = 'ASC';
				}

				self.options.tid = 0;
				self.options.filtered = false;
				self.loadContent();


			});

			filters.find('#search').on('change keyup input', function() {
				self.resetPage();
				if (self.options.search != $(this).val() || $(this).val() == '') {
					self.options.search = $(this).val();
					window.clearTimeout(self.timer);
					self.timer = window.setTimeout(function() {
						self.loadContent();


					}, 500);
				}


			});

			filters.find('#hide_released').on('click', function() {
				self.options.hideReleased = $(this).prop("checked");
				self.loadContent();


			});

			switch_options.find('#ProdCandidate').on('click', function() {

				if (self.options.roadmap != 'candidate') {

					self.options.loaded = '&init=yes';

					self.options.section.find('#lroadmap').removeClass('activate');
					self.options.section.find('#lcandidate').addClass('activate');

					self.resetPage();
					self.options.roadmap = $(this).val();
					self.updateFiltersCandidate();

					$('#yearT').hide('fast');
					$('#targetMM').hide('fast');
					$('#label_hide_released').hide('fast');

					self.options.loaded = '';
				}


			});

			switch_options.find('#ProdRoadmap').on('click', function() {

				if (self.options.roadmap != 'roadmap') {
					self.options.loaded = '&init=yes';

					$('#roadmap #lcandidate').removeClass('activate');
					$('#roadmap #lroadmap').addClass('activate');

					self.resetPage();
					self.options.roadmap = $(this).val();
					self.updateFiltersRoadmap();

					$('#yearT').show('fast');
					$('#targetMM').show('fast');
					$('#label_hide_released').show('fast');

					self.options.loaded = '';
				}


			});

			section.find('#share-general').on('click', function(e) {
				self.showShareLink();


			});

			section.find('#close-share').click(function() {
				self.hideShareLink();


			});

			filters.find('#reset_filters').click(function() {
				self.options.reset = true;
				self.defaultFilters();
				self.loadContent();
				self.options.reset = false;


			});

			$(document).mouseup(function(e) {
				self.hideShareUp(e);


			});

			$(document).mousedown(function(e) {
				self.hideShareDown(e);


			});
		},

		columnsByProject: function() {

			if (this.options.roadmap == 'candidate') {
				return this.columnsRoadmap(false);
			} else {
				return this.columnsRoadmap(true);
			}
		},

		columnsRoadmap: function(visibility) {

			return [
				{
					"data": "major",
					"className": 'td_major',
					"searchable": false,
					"orderable": false
				},
				{
					"className": 'details-control',
					"data": "short_name",
					"defaultContent": '',
					"searchable": false,
					"orderable": false
				},
				{
					"data": "first_component",
					"className": 'td_component',
					"searchable": false,
					"orderable": false
				},
				{
					"data": "target_closed_beta",
					"className": 'td_target_closed_beta',
					"searchable": false,
					"orderable": false,
					"visible": visibility
				},
				{
					"data": "target_open_beta",
					"className": 'td_target_open_beta',
					"searchable": false,
					"orderable": false,
					"visible": visibility
				},
				{
					"data": "target_release",
					"className": 'td_target_release',
					"searchable": false,
					"orderable": false,
					"visible": visibility
				},
				{
					"data": "status",
					"className": 'td_status',
					"searchable": false,
					"orderable": false
				},
				{
					"data": "geo_all",
					"className": 'td_geo',
					"searchable": false,
					"orderable": false
				},
				{
					"data": "gear",
					"className": 'td_gear',
					"searchable": false,
					"orderable": false
				}
			];
		},

		changeSameOrderItem: function(obj) {
			var self = this;

			if (self.options.order == 'DESC') {
				obj.removeClass('sorting_desc');
				obj.addClass('sorting_asc');
				self.options.order = 'ASC';

			} else {
				self.options.order = 'DESC';
				obj.removeClass('sorting_asc');
				obj.addClass('sorting_desc');
			}


		},

		hideShareDown: function(e) {
			var cover = $("#share-cover-box");
			var general = $("#share-general");
			if (!cover.is(e.target) && !general.is(e.target) && cover.has(e.target).length === 0) {
				this.hideShareLink();
			}


		},

		hideShareUp: function(e) {
			var container = $("#share-cover-box");
			if (!container.is(e.target) && container.has(e.target).length === 0) {
				this.hideShareLink();
			}


		},

		defaultFilters: function() {

			var section = this.options.section;

			section.find('#components').val('all');
			this.checkAllClassSelects(section.find('#components'));

			section.find('#statusT').val('all');
			this.checkAllClassSelects(section.find('#statusT'));

			section.find('#geoT').val('all');
			this.checkAllClassSelects(section.find('#geoT'));

			section.find('#targetMM').val('all');
			this.checkAllClassSelects(section.find('#targetMM'));

			section.find('#labels').val('all');
			this.checkAllClassSelects(section.find('#labels'));

			section.find('#search').val('');
			this.resetPage();

			if (this.options.roadmap == 'roadmap') {
				section.find('#yearT').val(2016);
				this.options.year = '2016';

			} else {

				section.find('#yearT').val('all');
				this.options.year = 'all';
			}

			this.checkAllClassSelects(section.find('#yearT'));

			this.removeSortingClass();

			section.find('#s_major').addClass('sorting_desc');


			$('#hide_released').attr('checked', false);
			$('#hide_released').prop('checked', false);

			this.options.component = 'all';
			this.options.label = 'all';
			this.options.status = 'all';
			this.options.geo = 'all';
			this.options.target = 'all';
			this.options.download = 'current';
			this.options.hideReleased = false;
			this.options.tid = 0;
			this.options.page = 0;
			this.options.search = '';
			this.options.order = 'DESC';
			this.options.orderI = 'major';
			this.options.firstLoad = true;
			this.options.filtered = false;



		},

		checkAllClassSelects: function(obj) {
			obj.removeClass('all');
			obj.removeClass('noall');

			if (obj.val() == 'all') {
				obj.addClass('all');

			} else {
				obj.addClass('noall');
			}


		},

		showShareLink: function(data) {
			var section = $('#share-link');
			section.val();

			var filters = this.getCurrentFilters();

			filters['filtered'] = true;
			filters = $.param(filters);

			$("#share-top").css("display", "block");
			$("#share-cover-box").css("display", "block");

			section.focus();
			section.val(this.options.currentBasePath+'?'+filters);
			section.select();


		},

		hideShareLink: function(data) {
			$("#share-top").css("display", "none");
			$("#share-cover-box").css("display", "none");


		},

		removeSortingClass: function() {
			var section = this.options.section.find('thead div');
			section.removeClass('sorting_asc');
			section.removeClass('sorting_desc');


		},

		showlabels: function(div) {
			var parent = div.parents('.item-list');
			parent.find('.extra .button a').click();


		},

		resetPage: function() {
			this.options.firstLoad = true;
			this.options.filtered = false;
			this.options.tid = 0;

			this.removeSortingClass();
			this.options.section.find('#s_major').addClass('sorting_desc');
			this.options.orderI = 'major';
			this.options.order = 'DESC';


		},

		updateFiltersCandidate: function() {

			var self = this;
			var filters = this.setCurentCandidate();

			$.each(filters, function(idFilter, data) {
				self.initAndFillFilter(data['data'], idFilter, 'all', data['select']);
			});

			this.updateValueAfterChangeFilters();
			this.deleteClassesAllAfterChangeFilters();
			this.loadContent();


		},

		setCurentCandidate: function() {
			var filters = this.options.filters['candidate'];
			filters['components']['select'] = this.options.component;
			filters['labels']['select'] = this.options.label;
			filters['geoT']['select'] = this.options.geo;
			filters['statusT']['select'] = this.options.status;
			filters['targetMM']['select'] = 'all';
			filters['yearT']['select'] = 'all';
			return filters;
		},

		updateFiltersRoadmap: function() {

			var self = this;
			var filters = this.setCurentRoadmap();

			$.each(filters, function(idFilter, data) {
				self.initAndFillFilter(data['data'], idFilter, 'all', data['select']);
			});

			this.updateValueAfterChangeFilters();
			this.deleteClassesAllAfterChangeFilters();
			this.loadContent();


		},

		setCurentRoadmap: function() {
			var filters = this.options.filters['roadmap'];
			filters['components']['select'] = this.options.component;
			filters['labels']['select'] = this.options.label;
			filters['statusT']['select'] = this.options.status;
			filters['geoT']['select'] = this.options.geo;
			filters['targetMM']['select'] = this.options.target;
			filters['yearT']['select'] = new Date().getFullYear();
			return filters;
		},

		updateValueAfterChangeFilters: function() {
			this.options.status = $('#statusT').val();
			this.options.geo = $('#geoT').val();
			this.options.component = $('#components').val();
			this.options.label = $('#labels').val();
			this.options.year = $('#yearT').val();
			this.options.target = $('#targetMM').val();


		},

		deleteClassesAllAfterChangeFilters: function() {
			this.checkAllClassSelects($('#statusT'));
			this.checkAllClassSelects($('#getT'));
			this.checkAllClassSelects($('#components'));
			this.checkAllClassSelects($('#labels'));
			this.checkAllClassSelects($('#yearT'));
			this.checkAllClassSelects($('#targetMM'));


		},

		initAndFillFilter: function(data, idFilter, valDefault, myselected) {

			var optionsIds = {
				'components' : 'component',
				'labels' : 'label',
				'statusT' : 'status',
				'yearT' : 'year',
			};

			var idOption = optionsIds[idFilter];

			var self = this;
			var section = $('#roadmap');
			section.find('#'+idFilter).empty();

			var filtered = false;

			$.each(data, function(id, name) {
				if (id == myselected) {
					var lis = '<option value="'+id+'" selected>'+name+'</option>';
					section.find('#'+idFilter).append(lis);
					filtered = true;
				} else {
					var lins = '<option value="'+id+'">'+name+'</option>';
					section.find('#'+idFilter).append(lins);
				}
			});

			if (filtered === false) {
				section.find('#'+idFilter+' option[value='+valDefault+']').attr('selected','selected');
			}


		},

		forceDefaultoption: function(idOption, valAll) {
			this.options[idOption] = valAll;


		},

		cleanConfigProject: function() {

			// All to all
			this.options.year = new Date().getFullYear();

			this.options.component = 'all';
			this.options.label = 'all';
			this.options.status = 'all';
			this.options.geo = 'all';
			this.options.target = 'all';
			this.options.search = '';
			this.setCurrentBase();


		},

		setFirstValues: function() {
			this.options.tid = $('#tid').text();
			this.options.firstLoad = $('#firstLoad').text();
			this.options.filtered = $('#filtered').text();
			this.options.hideReleased = $('#textHideReleased').text();
			this.options.orderI = $('#orderI').text();
			this.options.order = $('#order').text();
			this.options.roadmap = $('#roadmapProject').text();
			this.options.search = $('#search').val();
			this.options.status = $('#statusT').val();
			this.options.geo = $('#geoT').val();
			this.options.component = $('#components').val();
			this.options.label = $('#labels').val();
			this.options.year = $('#yearT').val();
			this.options.target = $('#targetMM').val();
			this.options.filters = $.parseJSON($('#toStorageFilters').text());

			this.changeProjectInRefresh();


		},

		changeProjectInRefresh: function() {

			var section = this.options.section;

			if (this.options.roadmap != 'candidate') {
				section.find('#lcandidate').removeClass('activate');
				section.find('#lroadmap').addClass('activate');

				$('#ProdCandidate').attr('checked', false).prop('checked', false);
				$('#ProdRoadmap').attr('checked', true).prop('checked', true);

				$('#yearT').show('fast');
				$('#targetMM').show('fast');
				$('#label_hide_released').show('fast');

			} else {
				section.find('#lroadmap').removeClass('activate');
				section.find('#lcandidate').addClass('activate');

				$('#ProdRoadmap').attr('checked', false).prop('checked', false);
				$('#ProdCandidate').attr('checked', true).prop('checked', true);

				section.find('th.td_release_phase').hide('fast');
				section.find('th.td_target_release').hide('fast');
				section.find('th.td_target_open_beta').hide('fast');
				section.find('th.td_target_closed_beta').hide('fast');

				$('#yearT').hide('fast');
				$('#targetMM').hide('fast');
				$('#label_hide_released').hide('fast');
			}


		},

		setCurrentBase: function() {
			this.options.currentBasePath = insights_base_path+'/product/roadmap';


		},

		initPage: function() {
			this.setCurrentBase();
			this.setFirstValues();
			this.deleteHelperDivs();


		},

		deleteHelperDivs: function() {
			$('#toStorageFilters').remove();
			$('#tid').remove();
			$('#firstLoad').remove();
			$('#textHideReleased').remove();
			$('#roadmapProject').remove();
			$('#filtered').remove();
			$('#orderI').remove();
			$('#order').remove();


		},

		cleanValues: function() {
			this.options.component = '';
			this.options.label = '';
			this.options.currentBasePath = '';
			this.options.status = '';
			this.options.geo = '';
			this.options.target = '';
			this.options.download = 'current';
			this.options.hideReleased = '';
			this.options.tid = 0;
			this.options.search = '';
			this.options.year = '';
			this.options.order = '';
			this.options.orderI = '';
			this.options.firstLoad = '';
			this.options.filtered = '';


		},

		alertTootTip: function() {
			$('#help').fooltips({
				offsety : -160,
				offsetx : 3
			});


		},

		this_tab: function() {
			var self = this;

			if ($('#roadmap').find('#lroadmap').hasClass('activate')) {
				return 'roadmap';
			} else {
				return 'candidate';
			}
		},

		track: function(category, tid) {
			var data = {};
			var self = this;

			data.data = category+'&tid='+tid;
			data.success = 1;

			$.ajax({
				type: 'POST',
				url:insights_base_path+'/analytics/click/'+self.this_tab()+'/send-track-props',
				data: data,
				cache: false,
				success: function(json) {},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					console.log(XMLHttpRequest.responseText);
				}
			});
		},

		init: function() {

			this.options.loaded = 'true';

			this.alertTootTip();

			this.cleanValues();

			this.initPage();

			this.loadContent();

			this.events();

			this.options.loaded = 'false';
		}
	};

	$(document).ready(function() {
		projectPage.init();
	});
});
