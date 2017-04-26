@extends('layouts.main')

@section('title')
	Insights
@stop

@section('head')
	<link rel="stylesheet" type="text/css" href="<?=URL::to('/'); ?>/_css/homepage.css?version=3.8" />
	<link rel="stylesheet" type="text/css" href="<?=URL::to('/'); ?>/_css/scrollbars.css" />
	<link rel="stylesheet" type="text/css" href="<?=URL::to('/'); ?>/_css/pqt.css" />
	<link rel="stylesheet" type="text/css" href="<?=URL::to('/'); ?>/_css/widgets/channel_widgets.css?version=3.8" />
	<link rel="stylesheet" type="text/css" href="<?=URL::to('/'); ?>/_css/widgets/stats_widgets.css" />
	@include('widgets.publisher_query_tool')
	@include('widgets.channels.list')
	@include('widgets.channels.block')
	@include('widgets.channels.chart')
	@include('widgets.channels.styled')
	@include('widgets.stats.main')
	<script src="<?=URL::to('/'); ?>/_js/packery.min.js"></script>
	<script src="<?=URL::to('/'); ?>/_js/handlebars.js"></script>
	<script src="<?=URL::to('/'); ?>/_js/moment.2.10.js"></script>
	<script src="<?=URL::to('/'); ?>/_js/nouislider.min.js"></script>
	<script src="<?=URL::to('/'); ?>/_js/widgets/publisher_query_tool.js?version=3.6"></script>
	<script src="<?=URL::to('/'); ?>/_js/widgets/widgets.js?version=3.8"></script>
	<script src="<?=URL::to('/'); ?>/_js/widgets/templates.js?version=3.6"></script>
	<script src="<?=URL::to('/'); ?>/_js/widgets/charts.js?version=3.7"></script>
	<script src="<?=URL::to('/'); ?>/_js/widgets/countdown.js"></script>
	<script src="<?=URL::to('/'); ?>/_js/widgets/highcharts.js"></script>
	<script src="<?=URL::to('/'); ?>/_js/widgets/channel_widgets.js?version=3.8"></script>
	<script src="<?=URL::to('/'); ?>/_js/widgets/stats_widgets.js"></script>
	<script src="<?=URL::to('/'); ?>/_js/nanoscroller.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
	<script src="<?=URL::to('/'); ?>/_js/draggabilly.min.js"></script>
	<script src="<?=URL::to('/'); ?>/_js/fooltips.js"></script>
@stop

@section('body')
	<div id="categories">
		<div class="zoom">
			<span class="minus">A</span>
			<div class="slider">
				<span class="line"></span>
			</div>
			<span class="plus">A</span>
		</div>
		<div class="inner">
			<ul></ul>
		</div>
	</div>
	<div id="homepage-zoom">
		<div id="homepage">
			@foreach ($widgets as $widget)
				<div class="tile {{$widget->width}} {{$widget->height}}" data-id="{{$widget->id}}" data-style="{{$widget->style}}" data-script="{{$widget->script}}" data-new="{{$widget->new}}" data-categories="{{$widget->categories}}" data-handle="{{$widget->handle}}">
					@if($widget->handle)
						<div class="handle"></div>
					@endif
				</div>
			@endforeach
		</div>
	</div>
@stop
