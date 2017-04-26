<!doctype html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>@yield('title')</title>
		<script src="<?=URL::to('/'); ?>/_js/jquery-1.11.0.min.js"></script>
		<script src="<?=URL::to('/'); ?>/_js/frame.js?version=6.8"></script>

		<link rel="stylesheet" type="text/css" href="<?=URL::to('/'); ?>/_css/frame.css?version=7.1" />
		<link href="https://fonts.googleapis.com/css?family=Roboto:100,700" rel="stylesheet" type="text/css">

		<link rel="shortcut icon" href="<?=URL::to('/'); ?>/favicon.ico">

		<!-- Page specific HEAD code -->
		@yield('head')

		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			ga('create', 'UA-2211250-24', 'auto');
			ga('send', 'pageview');

		</script>

		<script>
			var insights_base_path = "<?=URL::to('/'); ?>";

		</script>

	</head>
	<body @if(!in_array(1, Session::get('dialogs'))) class="warning" @endif>
		@include('includes.header')
		@include('includes.legal')
		@include('includes.sidebar')
		<div id="content">
			@yield('body')
		</div>
	</body>
</html>
