<div  id="filters" class="top">

	<div class="right options" style="margin-bottom:20px;">

		@include('jira.product.search')

		<div id="extra_options">
			@include('jira.product.reset')
		</div>

		<span id="firstLoad">{{$extras['firstLoad']}}</span>
		<span id="filtered">{{$extras['filtered']}}</span>
		<span id="tid">{{$extras['tid']}}</span>
		<span id="orderI">{{$extras['orderI']}}</span>
		<span id="order">{{$extras['order']}}</span>


		@foreach ($filters as $key => $filter)

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
