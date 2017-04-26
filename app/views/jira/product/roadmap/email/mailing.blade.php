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
            body, table, td, p, a, li, blockquote{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; font-family: Arial,Helvetica Neue,Helvetica,sans-serif;} /* Prevent WebKit and Windows mobile changing default text sizes */
            table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up */
            img{-ms-interpolation-mode:bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */
            /* /\/\/\/\/\/\/\/\/ RESET STYLES /\/\/\/\/\/\/\/\/ */
            body{margin:0; padding:0; font-family: Arial,Helvetica Neue,Helvetica,sans-serif;}
            img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
            span {border:0; margin:0;padding:0;}
            table{border-collapse:collapse !important;}
            /* /\/\/\/\/\/\/\/\/ TEMPLATE STYLES /\/\/\/\/\/\/\/\/ */
            body{min-width:720px !important; width:100% !important; max-width:1072px !important;}
            @yield('css')
        </style>
        @yield('extraLinks')
    </head>

    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="margin:0; padding:0;margin:0; padding:0;height:100% !important; margin:0; padding:0; width:100% !important; font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
        <center>
            <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="min-width:720px !important; width:100% !important; max-width:1072px !important; background:#efefea; border-collapse:collapse !important;">
                <tr>
                    <td align="center" valign="top" style="height:100% !important; margin:0; padding:0; width:100% !important;padding:10px;">

                        <!-- BEGIN TEMPLATE // -->
                        <table border="0" cellpadding="0" cellspacing="0" style="min-width:720px !important; width:100% !important; max-width:1072px !important;border-collapse:collapse !important;">

                            <tr>
                                <td align="center" valign="top">

                                    <!-- BEGIN HEADER // -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse !important;">
                                        <tr>

                                            <td valign="top" class="headerContent" style="background:#fff;padding:20px 0px 20px 20px;">
                                                <img src="{{ $message->embed(public_path().'/_img/logo.png')}}" width="180" height="48" style="border:0; height:auto; line-height:100%; outline:none; text-decoration:none; width:180px; height:48px;" alt="Insights" />
                                            </td>

                                            <td nowrap valign="center" style="background:#fff;padding:8px 10px 0px 20px;text-align:center;">
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">

                                                    <tr><td valign="bottom" nowrap align="right" style="border:0; margin:0; padding:0; color: rgb(85, 187, 234); clear: both; float: right; display: inline; letter-spacing: -0.8px; margin: 0px; padding: 0px; font-size: 20px; word-spacing: 0em;line-height: 0.8em;">Product Roadmap and Candidates Update</td></tr>

                                                    <tr><td nowrap align="right" valign="top" style="border:0; margin:0;padding:0; float:right; color:#8F8F8F; clear:both; font-style: italic; padding:0px; margin:2px 0px 0px 0px; font-size:14px;">{{$description}}</td></tr>

                                                </table>
                                            </td>

                                            <td width="52" nowrap align="right" valign="center" style="background:#fff;padding:18px 20px 20px 10px;text-align:left;">
                                                <img src="{{ $message->embed(public_path().'/_img/jira/roadmap.png')}}" width="48" height="48" alt="Rodmap" style="border:0; height:auto; line-height:100%; outline:none; text-decoration:none; width:48px; height:48px; clear:both; float:right;"/>
                                            </td>

                                        </tr>
                                    </table>
                                    <!-- // END HEADER -->

                                </td>
                            </tr>

                            <tr>
                                <td align="center" valign="top" style="padding-top:15px">

                                    <!-- BEGIN BODY // -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 1em;color:#565656;border-collapse:collapse !important;">
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
