@section('css')
		.TitleMarketInsights{font-size:15px;text-align:left;float:center;font-family:'Helvetica Neue','Roboto',Helvetica,arial;}
		.HeadTableMarketInsights{border:1pt solid black;}
		.MainMarketInsights{margin:0; padding:0; border:0;font-size:12px;color:#4D4D4D;font-family:'Helvetica Neue','Roboto',Helvetica,arial;font-weight:normal;}
		.MainMarketInsights tr{height:27px !important;padding:0;margin:0;}
		.MainMarketInsights thead tr th{text-align:left;width:100% !important;height:27px !important;overflow: hidden; white-space: nowrap; text-overflow: ellipsis;padding:10px;}
		.MainMarketInsights thead tr th span{color:#4D4D4D;font-weight:bold;}
		.MainMarketInsights tbody tr td{height:27px !important;text-align:left;width:100% !important;overflow: hidden; white-space: nowrap; text-overflow: ellipsis;padding:10px;}
		.MainMarketInsights tbody tr td span{text-align:left;}
		.borderMarketInsights td{border-bottom:3px solid #ffffff;text-align:left;}
		.DetailsMarketInsights{text-align:center;}
		.CreatedMarketInsights {color:#99d142;}
		.RegiondMarketInsights {color:#b3b3b3;}
@stop

	<table class="MainMarketInsights">
		<thead>
			<tr class="HeadTableMarketInsights">
				<th>
					<span>Labels</span>
				</th>
				<th>
					<span>Companies</span>
				</th>
				<th colspan="2">
					<span>Summary</span>
				</th>
				<th>
					<span>Region</span>
				</th>
				<th>
					<span>Date</span>
				</th>
				<th>
					<span>Reporter</span>
				</th>
			</tr>
		</thead>

		<tbody>
		@foreach ($data as $item)
				<tr bgcolor="#FFFFFF">
					<td>
						<span class="DetailsMarketInsights">{{$item->labels}}</span>
					</td>
					<td>
						<span class="DetailsMarketInsights">{{$item->companies}}</span>
					</td>
					<td>
						<a href="{{$item->url}}" target="_blank">
							<img src="{{$message->embed(public_path().'/_img/jira/info.png') }}" width="12" height="12" alt="{{$item->url}}" />
						</a>
					</td>
					<td>
						<span style="font-weight:bold;">{{$item->summary}}</span>
					</td>
					<td>
						<span class="DetailsMarketInsights RegiondMarketInsights">{{$item->region}}</span>
					</td>
					<td>
						<span class="DetailsMarketInsights CreatedMarketInsights">{{$item->date_created}}</span>
					</td>
					<td>
						<span class="DetailsMarketInsights">{{$item->creator}}</span>
					</td>
				</tr>
		@endforeach
		</tbody>
	</table>
