<?php
class WidgetCategory extends Eloquent  implements WidgetInterface {
	protected $connection = 'dashboard';
	protected $table = 'widget_category';
	public $timestamps	= false;

	public function widget()
    {
        return $this->belongsTo('Widget');
    }

	public function getData()
	{
		return $this->get()->toArray();
	}
}
