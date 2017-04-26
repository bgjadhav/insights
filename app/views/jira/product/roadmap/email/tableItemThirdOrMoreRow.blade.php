<?php

	if (!empty($ticket['historial'])) {
?>

	@foreach ($ticket['historial'] as $change_id => $item)

	<?php
		$boder_bottom = '';
		$padding_td = 'padding:0px 4px 0px 4px;';
		$padding_tr = 'padding:2px 4px 0px 4px;';

		if ($change_id == $last_one) {
			$boder_bottom = 'border-bottom:1px solid #efefea;';
			$padding_td = 'padding:0px 4px 7px 4px;';
			$padding_tr = 'padding:2px 4px 7px 4px;';
		}
	?>

	<tr bgcolor="#FFFFFF" margin="0" border="0" style="{{$padding_tr}} margin:0px; border: 0px; {{$boder_bottom}} background:#fff; font-size: 12px;">

		<td nowrap margin="0" border="0" width="260" align="left" style="{{$padding_td}} padding-left:8px; margin:0px; border: 0px; {{$boder_bottom}} background:#fff; width:260px !important; font-size: 12px; font-weight: bold; color:#4d4d4d;">
		</td>


		<td nowrap margin="0" border="0" width="140" align="left" style="{{$padding_td}} margin:0px; border: 0px; {{$boder_bottom}} {{$background_category}} width:140px !important; font-size: 12px; color:#4d4d4d; text-align:left;">
		</td>

		<td nowrap margin="0" border="0" width="140" align="right" style="{{$padding_td}} margin:0px; border: 0px; {{$boder_bottom}} background:#fff; width:140px !important; font-size: 12px; font-weight: bold; text-align:right; color:#4d4d4d;">
			{{$labels[$change_id]}}:
		</td>


		<td nowrap margin="0" border="0" width="150" align="left" style="{{$padding_td}} margin:0px; border: 0px; {{$boder_bottom}} background:#fff; width:150px !important; font-size: 12px; text-align:left; color:#4d4d4d;">

			{{$item['changes'][0]}}
			<?php if (isset($item['changes'][1])) {
				echo '<i margin="0" border="0" style="padding:0px; margin:0px; border: 0px; font-size: 12px; color:#4d4d4d;">(was '.$item['changes'][1].')</i>';
			} ?>

		</td>

	</tr>

	@endforeach

<?php
} ?>
