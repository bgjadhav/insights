<div id="filters_jira_stats">
	<div class="date_picker">
		<div class="date">
			<span class="date"></span>
			<input class="start name" data-value="{{$date_picker['start']}}" />
		</div>
		<div class="date">
			<span class="date"></span>
			<input class="end name" data-value="{{$date_picker['end']}}" />
		</div>
	</div>

	@foreach ($filters as $key => $filter)
		<div class="dropdown {{$key}}_{{$project}}">
			<span class="name" id="perf_{{$key}}">{{$key}} <span class="arrow-down"></span></span>
			<ul class="perf_{{$key}}">
				@foreach ($filter as $id => $name)
				<li>

					@if ($id == 'all')
						<input id="perf_{{$key}}_{{$id}}" type="radio" value="{{$id}}" checked />
					@else
						<input id="perf_{{$key}}_{{$id}}" type="radio" value="{{$id}}"/>
					@endif

					<label><?php echo utf8_decode($name); ?></label>
				</li>
				@endforeach
			</ul>
		</div>
	@endforeach
</div>
