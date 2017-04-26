var DashboardPID = function(url) {
	var private = {
		url : url,
	}
	return {
		init : function() {
			if (sessionStorage.getItem('pid') != null){
				$.ajax({
					type: 'POST',
					url: private.url+'close-pid',
					data: 'pid='+sessionStorage.getItem('pid'),
				});
			}
		}
	}
};
