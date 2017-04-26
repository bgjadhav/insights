<body>
    It Works :)
    @if (count($data) > 0)
		@foreach ($data as $index => $item)
		<tr bgcolor="#ffffff">
			<td>
				<span>{{$index}}: </span>
			</td>
			<td style="text-align:right;">
				<span>{{$item}}</span>
			</td>
		</tr>
		@endforeach
	@endif
</body>

