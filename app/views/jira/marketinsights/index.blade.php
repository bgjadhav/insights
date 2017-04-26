@extends('layouts.main')

@section('title')
Market Insights
@stop

@section('head')
	{{ HTML::style('_css/roadmap.css?version=6.8') }}
	{{ HTML::style('_css/jira/pipeline.css?version=6.8') }}
	{{ HTML::script('_js/moment.js') }}
	{{ HTML::script('_js/handlebars.js') }}
	{{ HTML::script('_js/jira/marketinsights.js?version=9.0') }}
	{{ HTML::script('_js/fooltips.js') }}
	{{ HTML::script('_js/dropzone.js') }}
	<?php include(app_path() . '/views/jira/marketinsights/template.handlebars'); ?>
@stop

@section('body')
	<div id="roadmap" class="intel">
		<div class="right">
			<input type="search" id="search" placeholder="Search..." />
			<select id="components">
				<option value="All">All Companies</option>
			</select>
			<select id="labels">
				<option value="All">All Labels</option>
			</select>
			<select id="regions">
				<option value="All">All Regions</option>
			</select>
		</div>
		<header>
			<div class="right">
				<div>Date</div>
				<div>Region</div>
				<div>Comments</div>
			</div>
		</header>
		<h1><span></span>Market Insights</h1>
		<div class="main rows-holder">
		</div>
	</div>
@stop
