<?php
class MarketSellerController extends Controller
{

	public function index()
	{
		return View::make('seller.index');
	}
	public function search()
	{
		
		$result = Publisher::where('displayName','=', Input::get('search'))->groupBy('displayName')->get();
		$result = $result->toArray();
//	echo "<pre>";	print_r($result[0]);
	 if($result)
	 {
		return $view = View::make('seller.profile')->with('data', $result[0]);
	 }else 
	 { 
	   return  $view = View::make('seller.error');
	 }
		
	}
	
public function autofill()
	{
		
		$result = Publisher::select('*')
		
		 ->where('displayName','LIKE',Input::get('autofill')."%")
			->orderBy('displayName', 'ASC')
		->groupBy('displayName')
		
		->get();
		
	//ddd($result); exit;
		$result = $result->toArray();
	//	echo "<pre>";	print_r($result);
	 if($result)
	 {
		return $view = View::make('seller.sellerautofill')->with('data', $result);
	 }
		
	}
}
