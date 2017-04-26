/* global $, document, window, localStorage */
var functions = {
	callapi : function(url) {
		if (document.location.hostname == "localhost") {
			root = 'http://localhost:3000';
		} else {
			root = 'https://open-internal.mediamath.com:3000';
		}
		return $.getJSON(root + '/' + url);
	},
	createchart : function(results) {
		var charts = [$('#visits-day'), $('#visits-day-last-week')];
		var max = 0;
		var dates = {};
		var i=0, j=0;

		// set values and find the max
		$.each(results, function(key, data) {
			var interval = data.interval;
			data = data.data;
			
			dates[key] = {};

			// initially set to 0
			for(i=0;i<24;i++) {
				dates[key][i] = {};
				for(j=0;j<60;j++) {
					if(j%interval === 0) {
						dates[key][i][j] = 0;
					}
				}
			}

			// calculate totals
			$.each(data, function(key1, hours) {
				$.each(hours, function(key2, minutes) {
					dates[key][key1][key2] = minutes;
				});
			});

			// find max value
			for(i=0;i<24;i++) {
				for(j=0;j<60;j++) {
					if(dates[key][i][j] > max) {
						max = dates[key][i][j];
					}
				}
			}
			
		});

		// append to chart
		$.each(results, function(key, data) {
			var interval = data.interval;
			charts[key].empty();
			for(i=0;i<24;i++) {
				for(j=0;j<60;j++) {
					if(j%interval === 0) {
						var percent = Math.ceil((dates[key][i][j] / max) * 100);
						charts[key].append('<div class="bar" style="height:' + percent + '%" data-hour="' + i + '" data-minute="' + j + '" data-max="' + max +'" data-percent="'+ percent +'" data-value="'+ dates[key][i][j] +'"></div>');
					}
				}
			}
		});
	}
};

// store page title
var title = document.title;
console.log(title);

var analytics = {
	usersonline : {
		duration : 5000,
		init : function() {
			var self = this;
			var data = functions.callapi('usersonline');
			data.success(function (data) {
				// append data
				$('#users-online ul').empty();
				$.each(data, function(key, value) {
					if(value.custom.first_name && value.custom.last_name) {
						$('#users-online ul').append('<li>' + value.custom.first_name + ' ' + value.custom.last_name + '</li>');
					}
				});

				// run again once complete
				setTimeout(function() {
					self.init();
				}, self.duration);
			});
		}
	},
	toppages : {
		duration : 5000,
		init : function() {
			var self = this;
			var data = functions.callapi('toppages');
			data.success(function (data) {
				// append data
				$('#top-pages ul').empty();
				$.each(data, function(key, value) {
					$('#top-pages ul').append('<li>' + value._id + ' ' + value.count + '</li>');
				});

				// run again once complete
				setTimeout(function() {
					self.init();
				}, self.duration);
			});
		}
	},
	visitshour : {
		duration : 10000,
		interval : 3,
		init : function() {
			// call both;
			var self = this;
			var success1 = false, success2 = false;
			var results = { 0 : '', 1 : '' };
			// do multiple ajax requests, don't run callback until they are all complete
			var data1 = functions.callapi('visitshour?interval=' + this.interval);
			var data2 = functions.callapi('visitshour?interval=' + this.interval + '&days=-7');
			
			data1.done(function (data) {
				success1 = true;
				results[0] = data;
				if(success1 && success2) {
					functions.createchart(results);
					setTimeout(function() {
						self.init();
					}, self.duration);
				}
			});
			data2.done(function (data) {
				success2 = true;
				results[1] = data;
				if(success1 && success2) {
					functions.createchart(results);
					setTimeout(function() {
						self.init();
					}, self.duration);
				}
			});
		}
	},
	onlinecount : {
		duration : 2000,
		init : function() {
			var self = this;
			var data = functions.callapi('onlinecount');
			data.done(function (data) {
				// append data
				$('#online-users-count span').html(data);
				document.title = '(' + data + ') online - ' + title;
				
				// run again once complete
				setTimeout(function() {
					self.init();
				}, self.duration);
			});
		}
	}
};
$.each(analytics, function(key, value) {
	value.init();
});