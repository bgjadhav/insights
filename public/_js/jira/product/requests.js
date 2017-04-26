/* global window:true, $:false, Handlebars:false, Dropzone:false */
$(function() {
	"use strict";
	var projectPage = {

		options: {
			component: '',
			label: '',
			currentBasePath: '',
			status: '',
			reporter: '',
			consideration: '',
			loaded : '',
			tid: '',
			search: '',
			order: '',
			reset: false,
			orderI: '',
			firstLoad: '',
			filtered: '',
			section: $('#roadmap'),
			main: $('#main table.main')
		},

		loadContent: function() {
			var self = this;

			self.checkDisplay();

			var filters = self.getCurrentFilters();
			var this_tid = filters.tid;

			filters = $.param(filters);

			$.getJSON(self.options.currentBasePath+'/data?'+filters, function(data) {

				self.options.main.find('#loadingImage').remove();

				if (data.length !== 0) {

					self.displayProduct(data, this_tid);

					self.setFooltips();

				} else {
					self.noSearch();
				}

			});
		},

		getCurrentFilters: function() {
			return {
				'idComponent' : this.options.component,
				'idLabel' : this.options.label,
				'status' : this.options.status,
				'search' : this.options.search,
				'idConsideration' : this.options.consideration,
				'idReporter' : this.options.reporter,
				'idOrder' : this.options.orderI,
				'tid' : this.options.tid,
				'order' : this.options.order,
				'firstLoad' : this.options.firstLoad,
				'filtered' : this.options.filtered,
				'reset': this.options.reset,
				'init' : this.options.loaded
			};
		},

		displayProduct: function(data, this_tid) {

			var self = this;

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
					'description_content'
				).show();
				tr.addClass('shown');

				tid = tid.replace('tc_link_to_', '');

				self.track('open_detail', tid);

			}
			self.cleanExtraTable();
		},

		prepareData: function(data) {
			var self = this;

			$.each(data, function(index, value) {

				value = self.setDetailsTicket(value);

				value = self.getAssignee(value);

				value = self.getWatchers(value);

				value = self.getLabels(value);

				value = self.fillEmptyValues(value);

				value = self.fillGear(value);

				value = self.setDescription(value);

				value = self.getComponents(value);

				value.reporter = $("<div />").html(value.reporter).text();

				value.short_name = self.itemSummary(value);

				value.DT_RowId = "row_"+value.issue_id;

			});

			return data;
		},

		fillTable: function(data) {
			var self = this;

			if(self.options.DataTable) {
				self.options.DataTable.destroy();
			}

			var section  = self.options.section;


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
					"targets": 1,
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
						"targets": 0,
						"createdCell": function (td, cellData, rowData, row, col) {
							var id_name = $(cellData).find('a').attr('id');
							$(td).attr('id', 'tc_'+id_name);
						}
					}
				]
			});
		},

		columnsByProject: function() {
			return  [
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
					"data": "status",
					"className": 'td_status',
					"searchable": false,
					"orderable": false
				},
				{
					"data": "candidate_consid",
					"className": 'td_candidate_consid',
					"searchable": false,
					"orderable": false
				},
				{
					"data": "reporter",
					"className": 'td_reporter',
					"searchable": false,
					"orderable": false
				},
				{
					"data": "created",
					"className": 'td_created',
					"searchable": false,
					"orderable": false
				},
				{
					"data": "labels_title",
					"className": 'td_labels_title',
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

		itemSummary: function(row) {
			var limit = 60;

			var summary = row.summary.substring(0, limit);

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

		openDetailIfFilterTid: function(this_tid, length) {

			if (this_tid > 0 && length == 1) {
				this.options.section.find('[id^="tc_link_to_"]').trigger('click');
			}
		},

		addLoading: function() {
			var self = this;
			var img = self.loadingImage();

			var section  = self.options.section;

			section.find('.table-holder .dataTables_wrapper .dataTables_scrollHead tbody').remove();

			if (section.hasClass('.dataTables_scrollBody')) {
				section.find('dataTables_scrollBody table tbody').empty().append(img);

			} else {
				section.find('tbody').append(img);
			}

		},

		itemDescription: function(row) {

			var self = this;

			return '<table class="table-description">'
				+row.description
				+'</table>';
		},

		checkDisplay: function() {
			var self = this;

			var section  = self.options.section;

			section.find('tbody').empty().append('');

			self.cleanBodyTable();
			self.cleanExtraTable();
			self.addLoading();

			self.loadingImage();
		},

		cleanBodyTable: function() {
			var section  = this.options.section;
			section.find('tbody').empty();
		},

		loadingImage: function() {
			return '<tr><td class="loading"><img src="'+insights_base_path+'/_img/ajax-loader.gif"></td></tr>';
		},

		setDetailsTicket: function(value) {
			var self = this;
			value.open_tracking_url  = 'http://issues.mediamath.com/browse/'+value.key;
			value.priority_name = value.priority;
			return value;
		},

		setDescription: function(value) {

			value.description = this.rowToDescription(['Description', value.description])

			+ this.rowToDescription(['Assignee', value.assignee])

			+ this.rowToDescription(['Watchers', value.watchers])

			+ this.rowToDescription(['Labels', value.labels]);

			return value;
		},

		getLinkShareOne: function(tid) {
			var filtersTid = {
				'tid' : tid
			};

			filtersTid = $.param(filtersTid);

			var link = '<a class="TidLInk" href="';

			link = link+this.options.currentBasePath+'?'+filtersTid+'">';
			link = link+this.options.currentBasePath+'?'+filtersTid+'</a>';

			return link;
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

		fillEmptyValues: function(value) {
			if (value.candidate_consid == '') {
				value.candidate_consid = 'None';
			}

			if (value.reporter == '') {
				value.reporter = 'Anonymous';
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

		getComponents: function(value) {
			if (value.first_component == 'No Components' || value.first_component == '' ) {
				value.first_component == 'No Product Category'
			}
			return value;
		},

		getLabels: function(value) {
			value.labels_ = value.labels.split(",");
			value.labels_[0] = value.labels_[0].trim();

			value.labels = value.labels.trim();

			if (value.labels == 'No Labels' || value.labels == '') {
				value.labels_title = 'None';
				value.labels = 'None';

			} else {
				value.labels_title = value.labels_[0];
				var label_length = value.labels_.length;

				if (label_length > 1) {
					value.labels_title += ' (+' + (label_length -1) + ')';
				}
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
				value.watchers == 'None';
			}

			return value;
		},

		setFooltips: function() {
			var self = this;

			self.options.main.find('.nameProd').fooltips({
				offsety : -160
			});

		},

		noSearch: function() {

			var self = this;
			self.cleanBodyTable();
			self.cleanExtraTable();

			var section  = self.options.section;

			var message = '<div id="noResults" class="yielded_no_results">';

			message += 'Sorry! Your search yielded no results. Please try the Roadmap or Candidates list in the Roadmap tab.';

			message += '</div>';

			if (section.find('.dataTables_scrollBody tbody').leght > 0) {

				section.find('.dataTables_scrollBody tbody').empty().append('<tr class="nosearched"><td colspan="8">'+message+'</td></tr>');

			} else {
				$('#roadmap .table-holder tbody').append('<tr class="nosearched"><td colspan="8">'+message+'</td></tr>');
			}

		},

		events: function() {
			var self = this;

			var filters = self.options.section.find('#filters');

			var section = $('#options');

			self.options.section.find('tbody').on('click', '[id^="tc_link_to_"]', function() {
				self.cleanExtraTable();

				var tr = $(this).closest('tr');

				self.detailOpenAndClosing(tr, $(this).attr('id'));
				self.cleanExtraTable();


			});

			filters.find('#components').on('change', function() {

				self.resetPage();

				self.checkAllClassSelects(filters.find('#components'));

				self.options.component = $(this).val();
				self.options.filtered = false;
				self.options.tid = 0;
				self.loadContent();

			});

			filters.find('#labels').on('change', function() {
				self.resetPage();
				self.hideCommonOptions();
				self.checkAllClassSelects(filters.find('#labels'));
				self.options.label = $(this).val();
				self.options.filtered = false;
				self.options.tid = 0;
				self.loadContent();

			});

			filters.find('#considerations').on('change', function() {
				self.resetPage();
				self.hideCommonOptions();
				self.checkAllClassSelects(filters.find('#considerations'));
				self.options.consideration = $(this).val();
				self.options.filtered = false;
				self.options.tid = 0;
				self.loadContent();

			});

			filters.find('#reporter').on('change', function() {
				self.resetPage();
				self.hideCommonOptions();
				self.checkAllClassSelects(filters.find('#reporter'));
				self.options.reporter = $(this).val();
				self.options.filtered = false;
				self.options.tid = 0;
				self.loadContent();

			});

			filters.find('#statusT').on('change', function() {
				self.resetPage();
				self.hideCommonOptions();
				self.checkAllClassSelects(filters.find('#statusT'));
				self.options.status = $(this).val();
				self.options.filtered = false;
				self.options.tid = 0;
				self.loadContent();

			});

			self.options.section.find('.sorting').on('click', function() {
				self.hideCommonOptions();
				self.options.firstLoad = false;
				self.removeSortingClass();

				var newOrder = $(this).attr('id').substring(2);
				if (newOrder== self.options.orderI) {
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
				self.hideCommonOptions();
				self.resetPage();
				if (self.options.search != $(this).val() || $(this).val() == '') {
					self.options.search = $(this).val();
					window.clearTimeout(self.timer);
					self.timer = window.setTimeout(function() {
						self.options.filtered = false;
						self.options.tid = 0;
						self.loadContent();
					}, 500);
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
				self.hideCommonOptions();
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

		cleanExtraTable: function() {
			var section = this.options.section.find('.dataTables_wrapper');

			section.find('.dataTables_length').hide();
			section.find('.dataTables_filter').hide();
			section.find('.dataTables_info').hide();
			section.find('tbody .dataTables_empty').hide();
			section.find('.dataTables_paginate').hide();
			section.find('thead th').removeClass('sorting');

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

			section.find('#reporter').val('all');
			this.checkAllClassSelects(section.find('#reporter'));

			section.find('#statusT').val('all');
			this.checkAllClassSelects(section.find('#statusT'));

			section.find('#considerations').val('all');
			this.checkAllClassSelects(section.find('#considerations'));

			section.find('#components').val('all');
			this.checkAllClassSelects(section.find('#components'));

			section.find('#labels').val('all');
			this.checkAllClassSelects(section.find('#labels'));

			section.find('#search').val('');
			this.resetPage();

			this.removeSortingClass();

			this.options.section.find('#s_created').addClass('sorting_desc');

			this.options.reporter = 'all';
			this.options.component = 'all';
			this.options.label = 'all';
			this.options.status = 'all';
			this.options.consideration = 'all';
			this.options.tid = 0;
			this.options.search = '';
			this.options.order = 'DESC';
			this.options.orderI = 'created';
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

		hideOptionsDownload: function(data) {
			$("#export-top").css("display", "none");
			$("#export-cover-box").css("display", "none");

		},

		hideCommonOptions: function(data) {
			this.hideOptionsDownload();
			this.hideShareLink();

		},

		removeSortingClass: function() {
			var section = this.options.section.find('thead div');

			section.removeClass('sorting_asc');
			section.removeClass('sorting_desc');

		},

		endMore: function() {
			$('#moreDash').hide('slow', function(){
				$('#paginationDash').hide();
				$('#paginationStopDash').show('slow');
			});

		},

		showmore: function(div) {

			var parent = div.parents('div.sub-row');
			parent.find('.more').toggleClass('less');
			parent.find('span.description-detail').slideToggle();
			parent.find('span.info').toggle();

			var parentTOP = div.parents('.item-list');
			parentTOP.find('.row .right .labels ul').slideToggle();

		},

		showlabels: function(div) {
			var parent = div.parents('.item-list');
			parent.find('.extra .button a').click();

		},

		resetPage: function() {
			this.options.firstLoad = true;
			this.options.filtered = false;

			this.removeSortingClass();

			this.options.section.find('#s_created').addClass('sorting_desc');

			this.options.orderI = 'created';
			this.options.order = 'DESC';

		},

		resetRoadmapSearchReleased: function() {
			var self = this;
			$('#search').val(this.options.search);

		},

		setCurrentBase: function() {
			this.options.currentBasePath = insights_base_path+'/product/requests';

		},

		initPage: function() {
			this.setCurrentBase();
			this.setFirstValues();
			this.deleteHelperDivs();

		},

		setFirstValues: function() {
			this.options.tid = $('#tid').text();
			this.options.firstLoad = $('#firstLoad').text();
			this.options.filtered = $('#filtered').text();
			this.options.search = $('#search').val();
			this.options.order = $('#order').text();
			this.options.orderI = $('#orderI').text();
			this.options.status = $('#statusT').val();
			this.options.consideration = $('#considerations').val();
			this.options.component = $('#components').val();
			this.options.label = $('#labels').val();
			this.options.reporter = $('#reporter').val();

		},

		cleanValues: function() {
			this.options.component = '';
			this.options.label = '';
			this.options.currentBasePath = '';
			this.options.reporter = '';
			this.options.status = '';
			this.options.consideration = '';
			this.options.tid = 0;
			this.options.search = '';
			this.options.order = '';
			this.options.orderI = '';
			this.options.firstLoad = '';
			this.options.filtered = '';

		},

		deleteHelperDivs: function() {
			$('#tid').remove();
			$('#firstLoad').remove();
			$('#filtered').remove();
			$('#orderI').remove();
			$('#order').remove();

		},


		alertTootTip: function() {
			$('#help').fooltips({
				offsety : -160,
				offsetx : 3
			});

		},

		track: function(category, tid) {
			var data = {};
			var self = this;

			data.data = category+'&tid='+tid;
			data.success = 1;

			$.ajax({
				type: 'POST',
				url:insights_base_path+'/analytics/click/requests/send-track-props',
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
