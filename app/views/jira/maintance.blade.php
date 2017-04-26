@extends('layouts.main')

@section('title')

Product Roadmap and Candidates

@stop

@section('head')
	{{ HTML::style('_css/cso.css?version=6.8') }}
	{{ HTML::style('_css/cso_custom.css?version=6.8') }}
	{{ HTML::style('_css/roadmap.css?version=6.8') }}
	{{ HTML::style('_css/jira/pipeline.css?version=6.8') }}
	{{ HTML::style('_css/jira/product.css?version=7.3') }}
	{{ HTML::style('_css/jira/table.css?version=7.2') }}
	{{ HTML::style('_css/jira/buttons.css?version=7.3') }}
	{{ HTML::style('_css/pikaday.css') }}
	{{ HTML::script('_js/moment.js') }}
	{{ HTML::script('_js/handlebars.js') }}
	{{ HTML::script('_js/jira/jquery.dataTables.min.js') }}
	{{ HTML::script('_js/jquery.sparkline.min.js') }}
	{{ HTML::script('_js/jira/jquery.dataTables.scroller.js') }}
	{{ HTML::script('_js/jira/product/roadmap.js?version=7.2') }}
	{{ HTML::script('_js/jira/product/subscription.js?version=7.1') }}
	{{ HTML::script('_js/jira/product/download.js?version=7.3') }}
	{{ HTML::script('_js/jira/fooltips.js') }}
	{{ HTML::script('_js/dropzone.js') }}
	{{ HTML::script('_js/screenfull.js') }}
@stop

@section('body')
	<div id="cso" class="product">

		<div id="main" class="{{{ isset($project) ? $project : '' }}}">

			<div id="roadmap" class="intel product">

				<h1 style="margin: 5% 17% !important;">This page is under maintenance, please try back later.</h1>

			</div>

		</div>

	</div>
@stop
