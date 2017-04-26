<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Insights Update</title>
		<style type="text/css">
			/* /\/\/\/\/\/\/\/\/ CLIENT-SPECIFIC STYLES /\/\/\/\/\/\/\/\/ */
			#outlook a{padding:0;} /* Force Outlook to provide a "view in browser" message */
			.ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
			.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing */
			body, table, td, p, a, li, blockquote{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
			table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up */
			img{-ms-interpolation-mode:bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */
			/* /\/\/\/\/\/\/\/\/ RESET STYLES /\/\/\/\/\/\/\/\/ */
			body{margin:0; padding:0;}
			img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
			table{border-collapse:collapse !important;}
			body, #bodyTable, #bodyCell{height:100% !important; margin:0; padding:0; width:100% !important;}
			/* /\/\/\/\/\/\/\/\/ TEMPLATE STYLES /\/\/\/\/\/\/\/\/ */
			#bodyCell{padding:20px;}
			#templateContainer{width:1072px !important; max-width:100% !important;}
			#bodyTable{background:#efefea;}
			@yield('css')
		</style>
		@yield('extraLinks')
	</head>
	<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
		<center>
			<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
				<tr>
					<td align="center" valign="top" id="bodyCell">
						<!-- BEGIN TEMPLATE // -->
						<table border="0" cellpadding="0" cellspacing="0" id="templateContainer">
							<tr>
								<td align="center" valign="top">
									<!-- BEGIN HEADER // -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateHeader">
										<tr>
											<td valign="top" class="headerContent" style="background:#fff;padding:20px;">
												<img src="{{ $message->embed(public_path().'/_img/'. (isset($logo) ? $logo : 'logo.png')) }}" width="{{$widthLogo or 114}}" height="{{$heightLogo or 43}}" style="width:{{$widthLogo or 114}}px;height:{{$heightLogo or 43}}px;" alt="Insights" />
											</td>
											<td valign="center" class="headerContent" style="background:#fff;padding:20px;text-align:center;">
												<span style="float:center;">@yield('title')</span>
											</td>
										</tr>
									</table>
									<!-- // END HEADER -->
								</td>
							</tr>
							<tr>
								<td align="center" valign="top" style="padding-top:15px">
									<!-- BEGIN BODY // -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateBody">
										<tr>
											<td valign="top" class="bodyContent">
												@yield('content')
											</td>
										</tr>
									</table>
									<!-- // END BODY -->
								</td>
							</tr>
						</table>
						<!-- // END TEMPLATE -->
					</td>
				</tr>
			</table>
		</center>
	</body>
</html>
