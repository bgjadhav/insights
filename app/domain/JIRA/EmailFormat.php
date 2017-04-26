<?php
class EmailFormat implements FilterInterface
{
	public static function filter($val)
	{
		return $val != '' ? $val : 'koruequiroz@mediamath.com';
	}


	public static function subscription($data)
	{
		return 'Product Roadmap And Candidates'
			.' Updates Suscription '
			.'- Ref:'.$data['created'].'-'.$data['user_id'];
	}
}
?>
