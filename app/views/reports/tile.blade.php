@extends('reports.main')

@section('title')
{{$sections['title']}}
@stop

@section('main')

	@if (!$sections['noBack'])
		<a href="{{URL::to('/')}}/analytics/{{$parent}}" class="back">Back</a>
	@endif

	@if ($sections['mainTitle'])
		<h1 class="mainTitle">{{$sections['mainTitle']}}</h1>
	@endif

	@foreach ($sections['display'] as $section)
		<div id="{{$section['class']}}" class="loading">
			{{HTML::image('_img/loading.gif')}}
		</div>
	@endforeach

	<script id="container-template" type="text/x-handlebars-template">
		@include('reports.container')
	</script>
	<script id="table-template" type="text/x-handlebars-template">
		@include('reports.table')
	</script>
	<script id="table-small-template" type="text/x-handlebars-template">
		@include('reports.table-small')
	</script>
	<script id="chart-template" type="text/x-handlebars-template">
		@include('reports.chart')
	</script>

	<script>
		$(function() {
			var analytics = [];
			@foreach ($sections['display'] as $key => $section)
				analytics["{{$key}}"] = new Dashboard("{{$section['class']}}", "{{$ulrLevel}}", "{{$section['title']}}");
				analytics["{{$key}}"].init();
			@endforeach
		})
	</script>
@stop
