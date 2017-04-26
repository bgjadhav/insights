<?php
	$boder_bottom = '';
	$padding_td = 'padding:8px 4px 0px 4px;';
	$padding_tr = 'padding:8px 4px 0px 4px;';

	if ($first_key == $last_one) {
		$boder_bottom = 'border-bottom:1px solid #efefea;';
		$padding_td = 'padding:8px 4px 8px 4px;';
		$padding_tr = 'padding:8px 4px 8px 4px;';
	}
?>


<tr bgcolor="#FFFFFF" margin="0" border="0" style="{{$padding_tr}} margin:0px; border: 0px; {{$boder_bottom}} background:#fff; font-size: 12px;">

	<td nowrap margin="0" border="0" width="260" align="left" style="{{$padding_td}} padding-left:8px; margin:0px; border: 0px; {{$boder_bottom}} background:#fff; width:260px !important; font-size: 12px; font-weight: bold; color:#4d4d4d;">

		<a href="https://insights.mediamath.com/product/roadmap?roadmap={{$ticket['roadmap']}}&tid={{$ticket['issue_id']}}" target="_blank" style="padding:0px; margin:0px; border: 0px; font-size: 12px; font-weight: bold; color:#4d4d4d;">
			{{$ticket['short_name']}}
		</a>

	</td>


	<td nowrap margin="0" border="0" width="140" align="left" style="{{$padding_td}} margin:0px; border: 0px; {{$boder_bottom}} {{$background_category}} width:140px !important; font-size: 12px; color:#4d4d4d; text-align:left;">
		{{$ticket['first_component']}}
	</td>

	<td nowrap margin="0" border="0" width="140" align="right" style="{{$padding_td}} margin:0px; border: 0px; {{$boder_bottom}} background:#fff; width:140px !important; font-size: 12px; font-weight: bold; text-align:right; color:#4d4d4d;">
		{{$item['label']}}:
	</td>


	<td nowrap margin="0" border="0" width="150" align="left" style="{{$padding_td}} margin:0px; border: 0px; {{$boder_bottom}} background:#fff; width:150px !important; font-size: 12px; text-align:left; color:#4d4d4d;">

		{{$item['changes'][0]}}
		<?php if (isset($item['changes'][1])) {
			echo '<i margin="0" border="0" style="padding:0px; margin:0px; border: 0px; font-size: 12px; color:#4d4d4d;">(was '.$item['changes'][1].')</i>';
		} ?>

	</td>

</tr>
