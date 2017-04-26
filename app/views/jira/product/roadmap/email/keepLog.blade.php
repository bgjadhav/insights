@extends('jira.product.roadmap.email.mailing')

@section('content')
<table  width="100%" style="border-collapse:collapse !important;">
<tr><td align="left" style="border:0; margin:0; padding:20px; font-family: Arial,Helvetica Neue,Helvetica,sans-serif; font-size:12px; background:#fff; color:#4d4d4d; align:left;">
Hello {{$data['user_full_name']}},
<br>
<br>
There was a conflict with our records when you tried to update to subscription to {{$data['status']}}.
<br>
<br>
Our team is working to resolve it and we will let you know as soon when it has been fixed.
<br>
<br>
If you have some question you can contact with us in <a href="mailto:product-operations@mediamath.com" style="text-align: left; color:#4d4d4d; text-decoration:none;">product-operations@mediamath.com</a>.
<br>
<br>
Thanks,
<br>
The Product Operations Team
</td></tr></table>
@stop
