@extends('layouts.main')

@section('title')
Analytics Dashboard
@stop

@section('head')
	{{ HTML::style('_css/cso.css?version=5.9') }}
	{{ HTML::style('_css/cso_custom.css?version=5.9') }}
	{{ HTML::style('_css/notice.css?version=5.8') }}
	{{ HTML::style('_css/pikaday.css') }}
	{{ HTML::script('_js/moment.js') }}
	{{ HTML::script('_js/pikaday.js') }}
	{{ HTML::script('_js/handlebars.js') }}
	{{ HTML::script('_js/jquery.sparkline.min.js') }}
	{{ HTML::script('_js/jquery.dataTables.min.js') }}
	{{ HTML::script('_js/jquery.dataTables.scroller.js') }}
	{{ HTML::script('_js/highstock.js') }}
	{{ HTML::script('_js/highcharts.export.js') }}
	{{ HTML::script('_js/dashboard.js?version=6.2') }}
	{{ HTML::script('_js/pid.js?version=5.8') }}
	{{ HTML::script('_js/favourite_reports.js?version=5.9') }}
	{{ HTML::script('_js/tile-list.js?version=5.8') }}

@stop

@section('body')
	<div id="cso">
		<div id="main" class="{{{ isset($sub) ? 'sub' : '' }}}">
			 @yield('main')
		</div>
	</div>
@stop
