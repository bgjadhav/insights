<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="initial-scale=1.0">    <!-- So that mobile webkit will display zoomed in -->
    <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->

    <title>MediaMath</title>
    <style type="text/css">
        a, a:visited, a:hover { text-decoration: none; margin: 0px; padding:0px; }
        .ReadMsgBody { width: 100%; background-color: #ebebeb;}
        .ExternalClass {width: 100%; background-color: #ebebeb;}
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height:100%;}
        html, body {height: 100%;}
	body {-webkit-text-size-adjust:none; -ms-text-size-adjust:none;}
        body {margin:0; padding:0;}
        table {border-spacing:0;mso-table-lspace:0pt;mso-table-rspace:0pt;}
        table td {border-collapse:collapse;}
	#outlook a{padding:0;}
	img{-ms-interpolation-mode:bicubic;}
        .yshortcuts a {border-bottom: none !important;}
        .footer-links a, .footer-links a:visited {color: #e4e4e4;text-decoration:none;margin-right:10px;}
        .section-links a, .section-links a:visited {color: #383838;text-decoration:none;text-transform:uppercase;font-weight:bold;font-size:12px;}
        .article-links a, .article-links a:visited {color: #56bbeb;text-decoration:none;margin-right:10px;font-weight: bold;}
        .arrow {line-height: 16px;vertical-align:text-bottom;}
        .copyright a {color: #69c1ea;text-decoration:none;font-weight: bold;font-family: Calibri, sans-serif;}
        .learn-more a, .learn-more a:visited {color:#ffffff;font-weight: normal;}
        .divider {border-top:1px solid #f4f4f4;}
        .header-image {padding-top:15px; }


        /* Constrain email width for small screens */
        @media screen and (max-width: 600px) {
            table[class="container"] {
                width: 95% !important;
            }
        img{max-width:100%;height:auto;}
        }

    /* Styles for forcing columns to rows */
    @media only screen and (max-width : 600px) {

        /* force container columns to (horizontal) blocks */
        td[class="force-col"] {
            display: block;
            padding-right: 0 !important;
        }
        table[class="col-2"] {
            /* unset table align="left/right" */
            float: none !important;
            width: 100% !important;

            /* change left/right padding and margins to top/bottom ones */
            /*margin-bottom: 12px;*/
            padding-bottom: 12px;
            border-top: 1px solid #eee;
        }
        table[class="col-2a"] {
            /* unset table align="left/right" */
            float: none !important;
            width: 100% !important;

            /* change left/right padding and margins to top/bottom ones */
            /*margin-bottom: 12px;*/
            padding-bottom: 12px;
        }


        /* remove bottom border for last column/row */
        table[id="last-col-2"] {
            border-bottom: none !important;
            margin-bottom: 0;
        }

        /* align images right and shrink them a bit */
        img[class="col-2-img"] {
            float: right;
            margin-left: 6px;
            max-width: 130px;
        }
            .mediamath-logo { margin:0px auto; width:227px; display:block; clear:right; }
            /*.mediamath-slogan { margin:0px auto !important;padding:0 0 0 15px; display:block; width:220px; }*/
            .share { width:90px; margin:-10px auto;display: block;}
            .footer-links {margin:10px auto -5px;display:block;width:210px;padding:0px !important;}
            .footer-icons {margin:10px auto -5px auto;float:none !important;display:block;width:200px;padding:0px !important;}
            .splitrow, .article-links { padding-left: 30px !important; }
            .divider { display:none; }
            .header-image {margin-top:-5px !important;display: block; padding:0px !important;}
            .social-links { padding-top:15px !important; }
    }

        /* Give content more room on mobile */
        @media screen and (max-width: 480px) {
            td[class="container-padding"] {
                padding-left: 0px !important;
                padding-right: 0px !important;
            }
            img{max-width:100%;height:auto;}

            /*.mediamath-slogan { margin:0px auto !important;padding:0 0 0 15px;display:block; width:110px;line-height: 14px; }*/
            .share { width:90px; margin:-10px auto;display: block; padding-right:10px; }
            .footer-links {margin:10px auto -5px;display:block;width:210px;padding:0px !important;}
            .footer-icons {margin:10px auto -5px auto;float:none !important;display:block;width:245px; text-align:center;padding:0px !important;}
            .header-image {margin-top:-5px !important;display: block; padding:0px !important;}
         }


    </style>
</head>
<body style="margin:0; padding:0;" bgcolor="#e6e6e6" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">


<!-- 100% wrapper (grey background) -->
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#e6e6e6">
  <tr>
    <td align="center" valign="top" bgcolor="#ebebeb" style="background-color: #e6e6e6;">

      <!-- 600px container (white background) -->
      <table border="0" width="600" cellpadding="0" cellspacing="0" class="container" bgcolor="#ffffff">
        <!-- Header -->
        <tr>
        <td class="container-padding" bgcolor="#ffffff" style="padding-left: 30px; padding-right: 0px; padding-top:13px; background-color: #ffffff; font-size: 14px; line-height: 20px; font-family: Calibri, sans-serif; color: #333;">
        	<table border="0" cellpadding="0" cellspacing="0" class="columns-container" width="100%">
			  <tr>
			    <td class="force-col" valign="top">

			        <!-- Column 1: Logo and Slogan -->
			        <table border="0" cellspacing="0" cellpadding="0" width="100%" align="left" class="col-2a">
			        <tr>
			            <td align="left" class="section-links">

	<img src="{{ $message->embed(public_path().'/_img/'. (isset($logo) ? $logo : 'logo.png')) }}" alt="MediaMath" width="200" height="62" style="margin:0px auto;padding:0;margin-right:20px;" align="left" border="0" /></td>
	<td valign="center" class="headerContent" style="background:#fff;padding:20px;text-align:center;">
												<span style="float:center;">@yield('title')</span>
											</td>
			        </tr>
			        </table>
			  </tr>
			</table><!--/ end .columns-container-->
        </td>
        </tr>
        <!-- /Header -->
        <!-- Header image -->
        <tr>
        <td class="container-padding" bgcolor="#ffffff" style="background-color: #ffffff;">
        	<table border="0" cellpadding="0" cellspacing="0" class="columns-container" width="100%">
			  <tr>
                <td align="center" class="header-image"></div></td>
              </tr>
            </table>
        </td>
        </tr>
        <!-- /Header image -->        <!-- Starting 2 column articles -->
        <tr>
        <td class="container-padding" style="font-size: 14px; line-height: 20px; font-family: Calibri, sans-serif; color: #333;">
        	<table border="0" cellpadding="0" cellspacing="0" class="columns-container" width="100%">
			  <tr>
			    <td class="force-col" valign="top">

			        <!-- ### COLUMN 1 - Featured Column ### -->
			        <table border="0" cellspacing="0" cellpadding="0" width="100%" align="left" class="col-2">
			        <!-- Article - Intro text (optional) -->
                    <tr>
			            <td align="left" valign="top" width="250" class="article-links" style="width:250px !important; font-size:14px; line-height: 18px; font-family: Calibri, sans-serif; padding:0px 20px 20px 40px; color: #383838;">
			            <br />
						@yield('content')
						</td>
			        </tr>
			        </table>
                    <!-- ### /COLUMN 1 - Featured Column ### -->
			    </td>
			  </tr>
			</table><!--/ end .columns-container-->
        </td>
        </tr>
      </table>
      <!--/600px container -->

    </td>
  </tr>
</table>
<!--/100% wrapper-->
<br>
<br>
</body>
</html>
