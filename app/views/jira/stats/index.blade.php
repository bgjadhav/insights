@extends('layouts.index')

@section('title')
	Analytics Dashboard
@stop

@section('head')
	{{ HTML::style('_css/cso.css?version=6.0') }}
	{{ HTML::style('_css/cso_custom.css?version=6.0') }}
	{{ HTML::style('_css/notice.css?version=3.4') }}
	{{ HTML::style('_css/pikaday.css') }}
	{{ HTML::style('_css/jira/stats.css?version=6.1') }}

@section('body')

	<div id="cso" class="stats">
		@include('jira.stats.tabs')

		<div id="main" class="{{{ isset($sub) ? 'sub' : '' }}}">

		</div>
	</div>
@stop
