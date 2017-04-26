@extends('layouts.main')

@section('title')
Market Insights
@stop

@section('head')

	{{ HTML::style('_css/roadmap.css?version=6.8') }}
	{{ HTML::style('_css/jira/pipeline.css?version=6.8') }}
	{{ HTML::script('_js/moment.js') }}
	{{ HTML::script('_js/handlebars.js') }}
	{{ HTML::script('_js/seller.js?version=9.0') }}
	{{ HTML::script('_js/fooltips.js') }}
	{{ HTML::script('_js/dropzone.js') }}
	<?php include(app_path() . '/views/jira/marketinsights/template.handlebars'); ?>
@stop
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
<style>


#publisher-list{float:left;list-style:none;margin-top:-3px;padding:0;width:190px;position: absolute;}
#publisher-list li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
#publisher-list li:hover{background:#ece3d2;cursor: pointer;}

</style>
@section('body')
	<div id="roadmap" class="intel">
	
	<!--  	<header>
			<div class="right">
				<div>Date</div>
				<div>Region</div>
				<div>Comments</div>
			</div>
		</header> -->
		<h1><span></span>Publisher Search </br></br> 	
			
		 </h1>
		
			
<div class="container">
  <div class="row">
    <div class="col-xs-6 col-md-4">
      <div class="input-group">
      <div class="frmSearch">
   <input type="search" id="search"  class="form-control" placeholder="Search" id="txtSearch"/>
   <div id="suggesstion-box"></div>
</div>
   <div class="input-group-btn">
        <button id="searchclk" class="btn btn-primary" type="button">
        <span class="glyphicon glyphicon-search"></span>
        </button>
   </div>
   </div>
    </div>
    </br></br>
          <div class="col-md-5  toppad  pull-right col-md-offset-3 ">

      </div>
    <div id="components"></div>
  </div>
  </div>

			
		<div class="main rows-holder">
		
		</div>
	</div>
@stop
