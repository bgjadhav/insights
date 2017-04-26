/* global window:true, $:false, Handlebars:false, Dropzone:false */
$(function() {
	"use strict";
	var seller = {
		options : {
			search: '',
			loading: '<div id="loading"><img src="_img/loading.gif" /></div>'
		},

	
		loadseller: function(val) {
			var self = this;
			
			$.get('publisher_search/data?search='+val, function(data) {
				$('#components').show();
					$('#components').html(data);
				
			}).fail( function(xhr, textStatus, errorThrown) {
				console.log(xhr.responseText);
			});
		},
		autofillseller: function() {
			var self = this;
			
			$.get('publisher_autofill/data?autofill='+this.options.search, function(data) {
				$("#suggesstion-box").show();
				$("#suggesstion-box").html(data);
				
				
			}).fail( function(xhr, textStatus, errorThrown) {
				console.log(xhr.responseText);
			});
		},

	

		events: function() {
			var self = this;


			$('#search').on('change keyup input', function() {
				$('#components').hide();
				if (self.options.search != $(this).val()) {
					self.options.search = $(this).val();
					window.clearTimeout(self.timer);
					self.timer = window.setTimeout(function() {
						self.autofillseller();
					//	self.loadseller();
					}, 500);
				}
				
				$("#publisher-list li").click(function() {
					
					$('#search').val(  $(this).html()); // gets innerHTML of clicked li
					$('#components').show();
					$('#components').html('<div id="loading"><img src="_img/loading.gif" /></div>');
					$("#suggesstion-box").hide();
						self.loadseller( $(this).html());
				    
				});
			});
			
			$("#searchclk").click(function() {
				self.loadseller( $('#search').val());
			});
		},


		init: function() {
			this.events();
			//this.loadseller();
		}
	};

	seller.init();
});
