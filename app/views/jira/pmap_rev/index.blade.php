@extends('main.layouts.open_layout')

@section('title')
	Insights | PMAP Revenue
@stop

@section('head')
	<link rel="stylesheet" type="text/css" href="{{ asset('_css/jira/pmap_rev/index.css') }}" />
@stop

@section('body')
	<div id="pmap-rev">
		<h1 class="title">PMAP Revenue</h1>

		<div id="table-section">
			<table>
				<thead>
					<tr>
						<th>Ticket</th>
						<th>Breakout</th>
						<th>Revenue</th>
					</tr>
				</thead>

				<?php 
					$ticket_name = '';
				?>
				@foreach($tickets_7 as $t7)
					<tr class="7d">
						<td>
							@if($t7->ticket != $ticket_name)
								{{ $t7->ticket }}
								<?php $ticket_name = $t7->ticket; ?>
							@endif
						</td>
						<td>{{ $t7->supply_type }}</td>
						<td>{{ $t7->media_cost }}</td>
					</tr>
				@endforeach
			</table>
		</div>
	</div>

	<script>
		
	</script>
@stop
