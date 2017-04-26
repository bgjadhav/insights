@extends('emails.daily')

@section('extraLinks')
	<link mc:nocompile="" href="http://us9.campaign-archive2.com/css/archivebar-desktop.css" rel="stylesheet"></link>
@stop

@section('content')
	<table bgcolor="#FFFFFF" style="margin-top:0px;">
	<tbody class="mcnTextBlockOuter">
		<tr>
			<td class="mcnTextBlockInner" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" valign="top">

				<table class="mcnTextContentContainer" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;" width="100%" border="0" cellpadding="0" cellspacing="0" align="left"><tbody>
					<tr>
						<td class="mcnTextContent" style="padding-top: 9px;padding-right: 18px;padding-bottom: 9px;padding-left: 18px;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #000000;font-family: Helvetica;font-size: 16px;line-height: 150%;text-align: center;" valign="top">

							<h2 class="null" style="text-align: left;margin: 0;padding: 0;display: block;font-family: Helvetica;font-size: 36px;font-style: normal;font-weight: bold;line-height: 125%;letter-spacing: -.75px;color: #000000 !important;">
								<span style="font-size:18px">Welcome to Weekly Market Insights Subscription</span>
							</h2>

							<div style="text-align: left;">
								<span style="font-size:12px"><br>Hello All,<br><br>
									Check out the new summissions to the Market Insights dashboard from the previous week:</span>
							</div>

							<br>
							@include('jira.marketinsights.table', array('data' => $data))


							<div style="clear: both;text-align: left;margin-top:15px;">
								<span style="font-size:12px">For full-access to all approved Market Insights submissions please visit the dashboard <strong><a href="https://insights.mediamath.com/competitive_intel" target="_blank" style="word-wrap: break-word;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #000000;font-weight: normal;text-decoration: underline;"><span style="color:#008080">https://insights.mediamath.com/competitive_intel</span></a></strong>.

								<br>
								<span style="font-size:12px">To contribute Market Insights and Competitive Intelligence email: compete@mediamath.com<br>

								<br>
								<br>
								<span style="font-size:12px">For sumission guidelines visit <strong><a href="https://wiki.mediamath.com/display/Comm/Competitive+Intelligence+Guidelines" target="_blank" style="word-wrap: break-word;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #000000;font-weight: normal;text-decoration: underline;"><span style="color:#008080">https://wiki.mediamath.com/display/Comm/Competitive+Intelligence+Guidelines</span></a></strong>

								<br>
								<br>
								<span style="font-size:12px">Thank you.

								<br>
								<br>
								<span style="font-size:12px">Best regards,
									<br>
									<strong>Product Operations Team</strong>
									<br>
									<a href="mailto:PrOps@mediamath.com?subject=Weekly%20Market%20Insights%20Subscription" target="_blank" style="color:rgb(123,102,175); font-weight:normal; text-decoration:underline; word-wrap:break-word">PrOps@mediamath.com</a>
								</span>
								<br>
							</div>

							<br>
							@include('emails.copyrightMM')
						</td>
					</tr>
				</tbody></table>

			</td>
		</tr>
	</tbody>
</table>
@stop
