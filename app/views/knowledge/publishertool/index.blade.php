@extends('layouts.main')

@section('title')
    Publishers Database
@stop

@section('head')
	{{ HTML::style('_css/publisher_tool.css?version=3.4') }}
    {{ HTML::style('_css/cso.css?version=3.4') }}
    {{ HTML::style('_css/jira/chosen.css') }}
    {{ HTML::style('_css/bootstrap.min.css') }}
    {{ HTML::style('_js/publisher_tool/package/slider/simple-slider.css') }}

    {{ HTML::script('_js/publisher_tool/package/require/require.js', [ 'data-main' => '_js/publisher_tool/app.base' ]) }}
    {{ HTML::script('_js/swag.min.js') }}
@stop

@section('body')
	@if(User::hasRole(['PublisherSolution']))
		<div id="cso">
			<nav>
				<ul>
					<li id="search" class="active"><a href="#">Home</a></li>
					<li id="questionaire" class=""><a href="#">Questionaire</a></li>
					@if( in_array( Session::get( 'user_type' ), [ 1, 2 ] ) )
						<li id="question_manager" class=""><a href="#">Question Manager</a></li>
						<li id="publisher_manager" class=""><a href="#">Publisher Manager</a></li>
					@endif
				</ul>
			</nav>
			<div id="main" class="">
				<div id="publisher_tool">
					<div id="questionaire">
						<h1 class="title">Publisher Knowledge Base</h1>
						<div class="loading">
							<img src="_img/loading.gif" alt="" />
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif
@stop
