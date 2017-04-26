var roadmap = {
	container: $('.rows-holder'),
	selectedUserId : '',
	current : '',
	options : {
		projectsURL: "../ajax/get_roadmap.php",
		updateURL: "../ajax/get_versions_meta.php",
		usersURL: "../ajax/get_open_users.php",
		saveURL: "../ajax/update_meta_version.php",
		quarterURL: "../ajax/update_quarter.php"
	},
	load : function(url, callback) {
		$.getJSON(url, function(data) {
			rows = data;
			callback();
		});
	},
	updateUsers : function() {
		this.load(this.options.usersURL, function() {
			// empty dropdown and repopulate
			$("#user-dropdown").empty();

			// each user
			$.each(rows, function(key, row) {
				if (row.avatar_url == null) {
					row.avatar_url = "/_img/avatars/anonymous.png";
				}
				var row = '<li><a href="#" data="' + row.open_user_id + '"><img src="../' + row.avatar_url + '"> ' + row.display_name + '</a></li>';
				$("#user-dropdown").append(row);
			});
		});
	},
	update : function() {
		// display loading
		this.container.html(this.loading);

		// load projects
		this.load(this.options.projectsURL, function() {
			// remove loading
			roadmap.container.find('#loading').remove();

			// generate the template
			var source = $("#row-template").html();
			var template = Handlebars.compile(source);
			$.each(rows, function(key, row) {
				// set the progress colour
				if (row.release_status == "RELEASED") {
					progress = "progress-done";
				} else if (row.release_date && (new Date() > new Date(row.release_date))) {
					progress = "progress-late";
				} else if (row.release_date) {
					progress = "progress-ok";
				} else {
					progress = "progress-none";
				}
				row.progress = progress

				// set the release date
				row.release_date = row.release_date ? moment(row.release_date).format("ddd MMM D") : "";
				if (row.priority) {
					row.release_date = '<span class="priority">Priority</span>';
				}

				// append template
				var html = template(row);
				if(progress == "progress-done") {
					$('#completed').append(html);
				} else {
					$('#ongoing').append(html);
				}
			});

			// load sub tasks
			roadmap.load(roadmap.options.updateURL, function() {
				// generate the template
				var source = $("#extra-template").html();
				var template = Handlebars.compile(source);

				$.each(rows, function(key, row) {
					// if no avatar display default avatar
					if(row.avatar_url == null) {
						row.avatar_url = "/_img/avatars/anonymous.png";
					}

					// change release time format
					row.timestamp = moment(row.timestamp).fromNow()

					// append template
					var html = template(row);
					var parent = roadmap.container.find('[data-id="' + row.version_id + '"]');
					parent.next('.extra').append(html);

					// add class if there are sub tasks.
					parent.addClass('has-children');
				});

				// open up updated option if you just saved
				if(roadmap.current != '') {
					roadmap.container.find('[data-id="' + roadmap.current + '"]').find('.arrow').click();
				}
			});
		});
	},
	saveUpdate : function() {
		var status = $('#status-input').val();
		var user = $('#user-btn').attr('data-id');
		var id = $('#new-status').attr('data-id');
		this.load(roadmap.options.saveURL + "?status=" + status + "&id=" + id + "&user=" + user, function() {
			if(rows.success == false) {
				alert('error saving');
			} else {
				roadmap.current = id;
				roadmap.update();
			}
		});
	},
	saveQuarter : function(quarter,id,self) {
		self.parents('.quarter').data('active', 'false');
		var span = self.parents('.quarter').find('span');
		span.html('<img src="../_img/loading.gif" />');
		this.load(roadmap.options.quarterURL + "?quarter=" + quarter + "&id=" + id, function() {
			if(quarter) {
				span.text('Q' + quarter);
			} else {
				span.text('-');
			}
			self.parent().animate({
				width: 0
			});
		});
	},
	init : function() {
		this.loading = this.container.html();
		this.updateUsers();
		this.update();
		
		// click handlers
		
		// show / hide sub tasks
		this.container.on('click', '.version .name, .version .arrow', function() {
			var parent = $(this).parents('.row');
			if(parent.hasClass('has-children')) {
				parent.toggleClass('active');
				parent.next('.extra').stop(true,false).slideToggle();
			}
		});
		
		// add new task popup
		this.container.on('click', '.add', function(e) {
			e.preventDefault();
			var parent = $(this).parents('.row');
			$('#new-status').attr('data-id', parent.data('id')).modal('show');
		});
		
		// change user id
		$('#new-status').on('click', '.dropdown-menu li a', function () {
			var selText = $(this).text();
			$('#user-btn').html(selText + ' <span class="caret"></span>');
			$('#user-btn').attr('data-id', $(this).attr('data'));
		});
		
		// save new task
		$('#new-status').on('click', '#save-btn', function () {
			if ($('#user-btn').data('id') != "" && $('#status-input').val() != "") {
				roadmap.saveUpdate();
				$('#new-status').modal('hide');
			}
		});
		
		// show all quarters
		this.container.on('click', '.quarter', function(e) {
			e.stopPropagation();
			if($(this).data('active') == 'true') {
				$(this).data('active', 'false');
				$(this).find('ul').animate({
					width: 0
				});
			} else {
				$(this).data('active', 'true');
				$(this).find('ul').animate({
					width: 195
				});
			}
		});
		
		// info tooltip
		this.container.on('click', '.info', function(e) {
			e.preventDefault();
			$(this).next('.tooltips').fadeToggle();
		});
		
		// hide info tooltip
		this.container.on('click', '.tooltips a', function(e) {
			$(this).parent('.tooltips').fadeOut();
		});
		
		// select quarter
		this.container.on('click', '.quarter li', function(e) {
			e.stopPropagation();
			
			var quarter = $(this).data('id');
			var id = $(this).parents('.row').data('id');
			roadmap.saveQuarter(quarter,id,$(this));
		});
	}
};
roadmap.init();