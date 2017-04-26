@extends('layouts.main')

@section('title')

Product Roadmap and Candidates

@stop

@section('head')
	{{ HTML::style('_css/cso.css?version=6.8') }}
	{{ HTML::style('_css/cso_custom.css?version=6.8') }}
	{{ HTML::style('_css/roadmap.css?version=6.8') }}
	{{ HTML::style('_css/jira/pipeline.css?version=6.8') }}
	{{ HTML::style('_css/jira/product.css?version=7.4') }}
	{{ HTML::style('_css/jira/table.css?version=9.4') }}
	{{ HTML::style('_css/jira/buttons.css?version=9.4') }}
	{{ HTML::style('_css/jira/gear.css?version=9.15') }}
	{{ HTML::style('_css/jira/description.css?version=8.9') }}

	{{ HTML::script('_js/jira/jquery.dataTables.min.js') }}

	{{ HTML::script('_js/jira/product/roadmap.js?version=9.20') }}
	{{ HTML::script('_js/jira/product/subscription.js?version=9.19') }}
	{{ HTML::script('_js/jira/product/download.js?version=9.19') }}
	{{ HTML::script('_js/jira/product/help.js?version=9.19') }}
	{{ HTML::script('_js/jira/product/share.js?version=9.20') }}
	{{ HTML::script('_js/jira/gear.js?version=9.20') }}
	{{ HTML::script('_js/jira/fooltips.js') }}
@stop

@section('body')
	<div id="cso" class="product">

		<div id="main" class="{{{ isset($project) ? $project : '' }}}">

			@include('jira.product.gear')

			@include('jira.product.roadmap.actions')

			@include('jira.product.tabs')

			<div id="roadmap" class="intel product">

				@include('jira.product.roadmap.cover')

				@include('jira.product.roadmap.filters')

				@include('jira.product.roadmap.table')

			</div>

		</div>

	</div>
@stop
