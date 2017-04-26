var	server = 'http://localhost/open_internal/public/ajax_login',
	data = 'username=rojohnson&password=Check1t!!';

function getTiles() {
	page.open('http://localhost/open_internal/public/emails', function() {
		page.includeJs("http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js", function() {
			setTimeout(function() {
				var height = page.evaluate(function(){
					return document.getElementById('homepage').offsetHeight;
				}); 
				page.clipRect = { top: 0, left: 0, width: 769, height: height };
				console.log('success');
				page.render('_img/tiles.png');
				phantom.exit();
			}, 5000);
		});
	});
	page.onError = function(msg, trace) {
		//console.log('error');
	};
}

var page = require('webpage').create()
page.open(server, 'post', data, function (status) {
	if (status !== 'success') {
		console.log(status);
	} else {
		//console.log('logged in');
		getTiles();
	}
});