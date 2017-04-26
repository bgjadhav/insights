@extends('layouts.main')

@section('title')
	Analytics Dashboard
@stop

@section('head')
	{{ HTML::style('_css/cso.css?version=6.0') }}
	{{ HTML::style('_css/cso_custom.css?version=6.0') }}
	{{ HTML::style('_css/notice.css?version=3.4') }}
	{{ HTML::style('_css/pikaday.css') }}
	{{ HTML::style('_css/jira/performance.css?version=6.1') }}
	{{ HTML::script('_js/moment.js') }}
	{{ HTML::script('_js/pikaday.js') }}
	{{ HTML::script('_js/handlebars.js') }}
	{{ HTML::script('_js/jquery.sparkline.min.js') }}
	{{ HTML::script('_js/jquery.dataTables.min.js') }}
	{{ HTML::script('_js/jquery.dataTables.scroller.js') }}
	{{ HTML::script('_js/highstock.js') }}
	{{ HTML::script('_js/highcharts.export.js') }}
	{{ HTML::script('_js/jira/performance.js?version=5.2') }}
	{{ HTML::script('_js/dashboard.js?version=6.3') }}
	{{ HTML::script('_js/pid.js?version=3.4') }}
	{{ HTML::script('_js/tile-list.js?version=5.8') }}
@stop

@section('body')

	<div id="cso" class="{{$project}}">
		@include('jira.performance.tabs')

		<div id="main" class="{{{ isset($sub) ? 'sub' : '' }}}">
			 @yield('main')
		</div>
	</div>
@stop
