var productSubscription = {

	options : {
		subscription: ''
	},

	events: function() {

		var self = this;

		$("#subscription").click(function() {
			if (!$("#subscription").hasClass("procesing")) {
				self.showSubscriptionOptions();
				self.track('1');


			}
		});

		$("#close-subscription").click(function() {
			self.hideSubscriptionOptions();

		});

		$("#accept-subscription").click(function() {
			self.showLoad();
			self.updateSubscription();

		});

		$(document).mouseup(function (e) {
			if (!$("#subscription").hasClass("procesing")) {
				self.hideSubscriptionUp(e);
			}

		});

		$(document).mousedown(function (e) {
			if (!$("#subscription").hasClass("procesing")) {
				self.hideSubscriptionDown(e);
			}

		});

	},

	showSubscriptionOptions: function(data) {
		this.hideError();
		this.hideSucess();
		this.hideConflict();
		this.hideLoad();
		this.showContent();

		var section = $("#options");

		section.find("#subscription-top").css("display", "block");
		section.find("#subscription-cover-box").css("display", "block").removeClass("messages");


	},

	hideSubscriptionOptions: function() {
		var section = $("#options");

		section.find("#subscription-top").hide();
		section.find("#subscription-cover-box").css("display", "none");

		this.updateRadioValue();


	},

	hideSubscriptionDown: function(e) {
		var cover = $("#subscription-cover-box");
		var general = $("#subscription");
		if (!cover.is(e.target) && !general.is(e.target) && cover.has(e.target).length === 0) {
			this.hideSubscriptionOptions();
		}


	},

	hideSubscriptionUp: function(e) {
		var container = $("#subscription-cover-box");
		if (!container.is(e.target) && container.has(e.target).length === 0) {
			this.hideSubscriptionOptions();
		}


	},

	showContent: function() {
		var section = $("#subscription-cover-box");

		section.find("#subscription-title").css("display", "block");
		section.find("#subscription-cover-message").css("display", "block");
		section.find("#accept-subscription").css("display", "block");
		this.displayCloseButton();


	},

	hideContent: function() {
		var section = $("#subscription-cover-box");

		section.find("#subscription-title").css("display", "none");
		section.find("#subscription-cover-message").css("display", "none");
		section.find("#accept-subscription").css("display", "none");
		section.find("#close-subscription").css("display", "none");


	},

	showError: function() {
		this.hideLoad();
		this.addClassMessageToCover();
		$("#subscription-message-error").css("display", "block");
		this.displayCloseButton();


	},

	hideError: function() {
		$("#subscription-message-error").css("display", "none");


	},

	showSucess: function() {

		this.hideLoad();

		this.addClassMessageToCover();

		if (this.options.subscription == 'none') {
			$("#subscription-message-unsubscribe").css("display", "block");

		} else if (this.options.subscription == 'weekly') {
			$("#subscription-message-subscribe").css("display", "block");
		}

		this.displayCloseButton();


	},

	hideSucess: function() {
		var section = $("#subscription-cover-box");

		section.find("#subscription-message-subscribe").css("display", "none");
		section.find("#subscription-message-unsubscribe").css("display", "none");


	},

	showConflict: function() {
		this.hideLoad();
		this.addClassMessageToCover();
		$("#subscription-message-conflict").css("display", "block");
		this.displayCloseButton();


	},

	hideConflict: function() {
		$("#subscription-message-conflict").css("display", "none");


	},

	hideLoad: function() {
		var section = $("#options");
		section.find("#subscription-message-procesing").css("display", "none");
		section.find("#subscription").removeClass("procesing");


	},

	showLoad: function() {
		var section = $("#options");
		section.find("#subscription-message-procesing").css("display", "block");
		section.find("#subscription").addClass("procesing");


	},

	addClassMessageToCover: function() {
		$("#subscription-cover-box").addClass("messages");


	},

	displayCloseButton: function(){
		$("#close-subscription").css("display", "block");


	},

	updateSubscription: function() {

		this.hideContent();
		var currentSelected =  this.radioValue();

		if (this.options.subscription == currentSelected) {
			this.showSucess();

		} else {
			this.sendSuscription(currentSelected); // for test
		}



	},

	sendSuscription: function(currentSelected) {

		var self = this;

		$.ajax({
			url: insights_base_path+'/product/roadmap/subscription',
			data: {'susbcribe' : currentSelected},
			cache: false,
			type: 'POST',
			success: function(result) {

				if (result == 'conflict' ) {
					self.updateConflict();
				} else {
					self.updateSucess(currentSelected);
				}

			},
			error: function(xhr, status, error) {
				self.updateError();
			}
		});


	},

	updateConflict: function() {
		this.updateRadioValue();
		this.showConflict();


	},

	updateSucess: function(currentSelected) {
		this.options.subscription = currentSelected;
		this.showSucess();


	},

	updateError: function(currentSelected) {
		this.updateRadioValue();
		this.showError();


	},

	updateRadioValue: function() {

		if(this.options.subscription == 'none') {
			this.updateRadioNone();

		} else {
			this.updateRadioWeely();
		}


	},

	updateRadioWeely: function() {
		var section = $("#subscription-cover-message");

		section.find("#subscriptionNone").attr("checked", false).prop("checked", false);
		section.find("#subscriptionWeekly").attr("checked", true).prop("checked", true);


	},

	updateRadioNone: function() {
		var section = $("#subscription-cover-message");

		section.find("#subscriptionWeekly").attr("checked", false).prop("checked", false);
		section.find("#subscriptionNone").attr("checked", true).prop("checked", true);


	},

	radioValue: function() {
		var section = $("#subscription-cover-message");

		if (section.find("#subscriptionWeekly").is(":checked")) {
			return section.find("#subscriptionWeekly").val();

		} else {
			return section.find("#subscriptionNone").val();
		}
	},

	checkUrlParameter: function() {

		if (this.havesubscriptionParameter()) {
			$("#subscription").trigger( 'click' );
		}


	},

	havesubscriptionParameter: function() {
		var sPageURL = decodeURIComponent(window.location.search.substring(1)),
			sURLVariables = sPageURL.split('&'),
			sParameterName,
			i;

		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');

			if (sParameterName[0] === 'subscription') {
				return true;
			}
		}


	},

	this_tab: function() {
		if ($('#roadmap').find('#lroadmap').hasClass('activate')) {
			return 'roadmap';
		} else {
			return 'candidate';
		}
	},

	track: function(success) {
		var data = {};
		data.data = 'alert';
		data.success = success;
		var self = this;

		$.ajax({
			type: 'POST',
			url:insights_base_path+'/analytics/click/'+this.this_tab()+'/send-track-props',
			data: data,
			cache: false,
			success: function(json) {},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				console.log(XMLHttpRequest.responseText);
			}
		});


	},

	init: function() {
		this.options.subscription = this.radioValue();
		this.events();
		this.checkUrlParameter();


	}

};


$(document).ready(function() {
	productSubscription.init();
	$("#options").show();
});
