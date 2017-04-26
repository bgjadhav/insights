var productGear = {

	options: {
		category : '',
		ticket : 0	},

	events: function() {

		var self = this;

		$('#roadmap').find('tbody').on('click', '[id^="gear_"] .gear_li', function() {
			var parent = $(this).parent('ul').attr('id');

			$('#'+parent).css('background', "#fff url(../../_img/ajax-loader.gif) no-repeat");

			self.itemClicked($(this));

			$('#'+parent).css('background', "");

			return false;
		});

		$('#roadmap').on('click', 'td.td_gear', function() {
			self.iconClicked($(this).find('ul').attr('id'));

			return false;
		});

		$('#roadmap').on('click', '[id^="linkToReleasePhase_"]', function() {
			self.linkToPhase($(this).attr('id'));
		});

		$('#roadmap').find('tbody').on('click', '[id^="link_to_"]', function() {
			self.linkToTicket($(this).attr('id'));
		});


		$('#go-to-ticket-confirm').on('click', function() {
			self.resetCover();
			self.hideBoxGoTo();
			self.track('view_go_jira');
		});

		$('#comment-to-ticket-confirm').on('click', function() {
			self.resetCover();
			self.hideComment();
			self.track('add_comment');
		});

		$('#follow-ticket-confirm').on('click', function() {
			self.beWatcher($(this).attr( "class"));
			return false;
		});

		$('#main #share-ticket-box').on('click', '#share-ticket-close-share', function() {
			self.resetCover();
			self.hideShare();

			return false;
		});

		$('#go-to-ticket-cancel').on('click', function() {
			self.resetCover();
			self.hideBoxGoTo();

			return false;
		});

		$('#comment-to-ticket-cancel').on('click', function() {
			self.resetCover();
			self.hideComment();

			return false;
		});

		$('#follow-ticket-cancel').on('click', function() {
			self.resetCover();
			self.hideFollow();

			return false;
		});

		$('#thanks-gear-cancel').on('click', function() {
			self.resetCover();
			self.resetMessage();

			return false;
		});

		$('#mistake-gear-cancel').on('click', function() {
			self.resetCover();
			self.resetMessage();

			return false;
		});

		$(document).mouseup(function(e) {
			self.hideMouseUp(e);

			return false;
		});
	},

	linkToTicket: function(id) {

		this.options.ticket = id.replace('tc_link_to_', '');

		this.options.ticket = id.replace('link_to_', '');

		this.track('link_to_ticket');

		this.options.ticket = 0;
	},

	linkToPhase: function(id) {
		this.options.ticket = id.replace('linkToReleasePhase_', '');

		this.track('link_to_phase');

		this.options.ticket = 0;
	},

	resetCover: function() {
		this.coverChange('hidden');
		this.uncolorizeItems();
		this.changeTicket(0);
	},

	resetMessage: function() {
		this.showThanks('none');
		this.showError('none');
	},

	iconClicked: function(id) {
		this.options.category = 'click';
		this.slideBox(id);
	},

	menuClicked: function(id) {
		this.showBox(id);
	},

	slideBox: function(id) {
		var status = $('#'+id).css('display');

		this.options.ticket = id.replace('gear_', '');

		this.hideBox();

		if (status == 'none' ) {
			this.showBox(id);
			this.track('open_gear');
		}
	},

	showBox: function(id) {
		$('#arrow_'+id).css("display", "block");
		$('#'+id).css("display", "block");
	},

	hideBox: function() {
		$('[id^="gear_"]').css("display", "none");
		$('[id^="arrow_gear_"]').css("display", "none");
		this.uncolorizeItems();
	},

	hideAllGear: function() {
		$('[id^="gear_"]').css("display", "none");
		$('[id^="arrow_gear_"]').css("display", "none");
		this.uncolorizeItems();
	},

	hideMouseUp: function(e) {
		var container = $(e.target);

		if (!$('#waiting-gear-box').is(':visible')) {

			if (container.hasClass('cover')) {
				this.hideAllBoxes();
				this.uncolorizeItems();

			} else if ($('[id^="gear_"]').is(':visible')) {

				if ( !container.hasClass('td_gear') ) {

					if (!container.hasClass('cover') && !container.hasClass('gear-box') && !container.hasClass('no-close-box')  && $('[id^="gear_"]').is(':visible')) {
						this.hideAllGear();


					}
				}
			}
		}
	},

	hideAllBoxes: function() {
		this.hideGearBox();
		this.resetCover();
	},

	hideGearBox: function() {
		$('.gear-box').css('display', 'none');
	},

	hideBoxGoTo: function() {
		$('#go-to-ticket-box').css('display', 'none');
	},

	hideShare: function() {
		$('#share-ticket-box').css('display', 'none');
	},

	hideComment: function() {
		$('#comment-to-ticket-box').css('display', 'none');
	},

	showFollow: function(idProduct) {
		$('#follow-ticket-box').css('display', 'block');

		$('#follow-ticket-confirm').removeClass().addClass('no-close-box gear_option_right '+idProduct);
	},

	hideFollow: function() {
		$('#follow-ticket-box').css('display', 'none');
	},

	itemClicked: function(li) {

		this.coverChange('visible');

		var id = li.attr('id');

		this.options.category = this.cleanItemClass(li.attr('class'));

		this.runCategory(id);

		this.uncolorizeItems();

		this.colorizeItem(id);

		this.track(this.options.category);

	},

	cleanItemClass: function(classes) {
		return classes.replace('no-close-box ', '').replace('gear_option_right ', '').replace('gear_li li_', '');
	},

	colorizeItem: function(id) {
		$('[id="'+id+'"]').css("color", "#3FA9F5");
	},

	uncolorizeItems: function() {
		$('[id^="gear_"]').find('.gear_li').css("color", "#000");
	},

	runCategory: function(id) {
		var self = this;

		switch (self.options.category) {
		case 'view':
			self.runView(id);
			break;
		case 'share':
			self.options.category = 'share_project';
			self.runShare(id);
			break;
		case 'comment':
			self.runComment(id);
			break;
		case 'follow':
			self.runFollow(id);
			break;
		}
	},

	runView: function(id) {
		var ids = id.split(" ");

		var idProduct = this.cleanId(ids[1], 'view');

		this.changeTicket(this.cleanId(ids[0], 'view_id'));

		$('#go-to-ticket-box').css('display', 'block');

		$("#go-to-ticket-confirm").attr("href", this.urlToJira(idProduct));
	},

	runComment: function(id) {
		var self = this;
		var section = $('#main');

		var idProduct = self.cleanId(id, 'comment');

		self.changeTicket(idProduct);

		$('#comment-to-ticket-box').css('display', 'block');

		$("#comment-to-ticket-confirm").attr("href", self.urlToCommentJira(idProduct));
	},

	runShare: function(id) {
		var self = this;
		var section = $('#main');

		var idProduct = self.cleanId(id, 'share');

		self.changeTicket(idProduct);

		self.showShareLink(idProduct);
	},

	runFollow: function(id) {
		var self = this;
		var section = $('#main');

		var idProduct = self.cleanId(id, 'follow');

		self.changeTicket(idProduct);

		self.showFollow(idProduct);

	},

	changeTicket: function(id) {
		this.options.ticket = id;
	},

	beWatcher: function(classes) {

		var idProduct =  classes.replace('no-close-box gear_option_right ', '');
		var self = this;

		self.hideFollow();
		self.showWaiting('block');

		$.ajax({
			type: 'POST',
			url:insights_base_path+'/jira/add/watcher',
			data: {'issue_id' : idProduct},
			cache: false,
			success: function(json) {
				self.hideFollow();
				self.showWaiting('none');

				if(json.result == 'OK') {
					self.showThanks('block');

				} else {
					self.showError('block');
				}

			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log(XMLHttpRequest.responseText);

				self.hideFollow();
				self.showWaiting('none');
				self.showError('block');
			}
		});

		self.track('follow_be_watcher');
	},

	showThanks: function(visibility) {
		$('#thanks-gear-box').css('display', visibility);
	},

	showError: function(visibility) {
		$('#mistake-gear-box').css('display', visibility);
	},

	showWaiting: function(visibility) {
		$('#waiting-gear-box').css('display', visibility);
	},

	coverChange: function(visibility) {
		$('#roadmap').find('.cover').css('visibility', visibility);
	},

	cleanId: function(id, category) {
		return id.replace('li_'+category+'_', '');
	},

	urlToJira: function(idProduct) {
		return 'https://issues.mediamath.com/login.jsp?os_destination=%2Fbrowse%2F'+idProduct;	},

	urlToCommentJira: function(idProduct) {
		return 'https://issues.mediamath.com/login.jsp?os_destination=%2FAddComment!default.jspa?id='+idProduct;	},

	urlToInsights: function(idProduct) {
		var self = this;

		if ($('#main').hasClass('requests')) {
			return insights_base_path+'/product/requests?tid='+idProduct;

		} else {
			return self.urlToRoadmapInsights(idProduct);
		}	},

	urlToRoadmapInsights: function(idProduct) {

		if ($('#roadmap').find('#lroadmap').hasClass('activate')) {
			return insights_base_path+'/product/roadmap?roadmap=roadmap&tid='+idProduct;
		} else {
			return insights_base_path+'/product/roadmap?roadmap=candidate&tid='+idProduct;
		}	},

	showShareLink: function(idProduct) {
		var section = $('#share-ticket-link');
		section.val();

		var filters = 'my id'

		$('#share-ticket-box').css('display', 'block');

		section.focus();
		section.val(this.urlToInsights(idProduct));
		section.select();
	},

	this_tab: function() {
		var self = this;

		if ($('#main').hasClass('requests')) {
			return 'requests';

		} else {
			return self.roadmapOrCandidatesList();
		}
	},

	roadmapOrCandidatesList: function() {
		if ($('#roadmap').find('#lroadmap').hasClass('activate')) {
			return 'roadmap';
		} else {
			return 'candidate';
		}
	},

	track: function(category) {
		var data = {};
		var self = this;

		data.data = category+'&tid='+self.options.ticket;
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
		this.events();

	}

};


$(document).ready(function() {
	productGear.init();
});
