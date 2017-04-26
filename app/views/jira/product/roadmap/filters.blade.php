@include('jira.product.roadmap.switch')

<div id="filters" class="top">
		@include('jira.product.roadmap.init_data')

	<div class="right options" style="margin-bottom:20px;">

		@include('jira.product.search')

		@include('jira.product.roadmap.extra')


		@foreach ($filters[$subproject] as $key => $filter)

			@if ($filter['select'] == 'all')
				<select id="{{$key}}" class="selectBox all">
			@else
				<select id="{{$key}}" class="selectBox noall">
			@endif


			@foreach ($filter['data'] as $id => $name)

				@if ($id == $filter['select'])
					<option value="{{$id}}" selected><?php echo utf8_decode($name); ?></option>
				@else
					<option value="{{$id}}"><?php echo utf8_decode($name); ?></option>
				@endif

			@endforeach
		</select>
		@endforeach

	</div>

</div>
