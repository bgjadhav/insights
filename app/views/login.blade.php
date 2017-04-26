<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>insights</title>
		<link type="text/css" rel="stylesheet" href="//fast.fonts.com/cssapi/ba8dfb9e-e38b-4188-b303-095bf2d86e6d.css?v.1.3">
		<link type="text/css" rel="stylesheet" href="<?=URL::to('/'); ?>/_css/login.css?version=3.1">
	</head>
	<body>
		<div id="login">
			<section class="logo">
				<div class="request_access">
					<p>Whilst it's lovely that you want to be involved; this area is for a select few only...</p>
				</div>
				<a href="#" class="request_access_link">Request access</a><img src="_img/logo.svg" class="logo_img" alt="" />
			</section>
			<section class="login">
				<form method="post">
					<fieldset>
						<input type="text" name="username" placeholder="username" />
						<input type="password" name="password" placeholder="password" />
						<input type="submit" class="submit" />
					</fieldset>
					<div class="loading"><span></span></div>
				</form>
			</section>
		</div>
		<script src="<?=URL::to('/'); ?>/_js/jquery-1.11.0.min.js"></script>
		<script src="<?=URL::to('/'); ?>/_js/jquery-migrate-1.2.1.min.js"></script>
		<script>
			$(document).ready(function () {
				$('#login form input.submit').addClass('done');
			});

			$(function() {
				$('#login').addClass('loaded');
				function move(direction, div, complete) {
					div.animate({
						left: 8 * direction + 'px'
					},20,function() {
						complete();
					});
				}

				var steps = 8;
				function shake(div, complete) {
					div.each(function() {
						for(i=0;i<steps;i++) {
							if (i%2 == 0) {
								var direction = 1;
							} else {
								var direction = -1;
							}
							if(i < steps-1) {
								move(direction, $(this), function() { });
							} else {
								setTimeout(function() {
									move(0, div, complete);
								},steps*20);
							}
						}
					});
				}

				$('#login form input').on('input', function() {
					// if( $('input[name="username"]').val() != '' && $('input[name="password"]').val() != '' ) {
					// 	$('#login form input.submit').addClass('done');
					// } else {
					// 	$('#login form input.submit').removeClass('done');
					// }
					$('#login form input').each(function() {
						var length = $(this).val().length;
						if(length > 10) {
							$(this).addClass('small');
						} else {
							$(this).removeClass('small');
						}
					});
				});

				$('#login form').submit(function(e) {
					e.preventDefault();
					if($('#login form input.submit').hasClass('done')) {
						var height = $(window).height();
						var div = $('#login section');
						$('#login .loading').show();
						setTimeout(function() {
							$('#login').addClass('animate');

							setTimeout(function() {
								$('.error_msg').slideUp("slow");
								$('#login .loading span').stop(true,false).animate({
									width: '564px'
								}, 2000
								);
							},500);

							$.ajax({
								type: "POST",
								url: "<?=URL::to('/');?>/ajax_login",
								data: {
									username: $('input[name="username"]').val(),
									password: $('input[name="password"]').val()
								}
							}).done(function(data){
								if (data.success){
									$('#login section.logo').animate({
										top : ((height / 2) + 200) * -1
									});
									$('#login section.login').animate({
										top : (height / 2) + 200
									}, 400, function() {
										@if(App::environment('local') || App::environment('dev'))
											window.location = '<?php echo $redirect ?>';
										@else
											// force https
											window.location = '<?php echo str_replace('http://', 'https://', $redirect) ?>';
											/*@todo redirect
											*/
											setInterval(function() {
												window.location = '<?php echo str_replace('http://', 'https://', $redirect) ?>';
											}, 5000);
										@endif
									});
								} else {
									shake(div, function() {
										$('#login').removeClass('animate');
										$('#login .loading span').stop(true,false).animate({
											width: '10px'
										});
										setTimeout(function() {
											$('#login .loading').hide();
										},900);

										if(data.error == "internal_only") {
											$('#login').addClass('internal-only');
											$('#login .request_access').slideDown();
										}
									});
								}
							});
						},20);
					}
				});
			});
		</script>
	</body>
</html>
