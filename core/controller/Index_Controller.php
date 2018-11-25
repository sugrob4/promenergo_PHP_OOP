<?php
defined('PROM') or exit('Access denied');

class Index_Controller extends Base {
	
	protected $text;
	
	protected function input() {
		//////
		parent::input();
		
		$this->title .= "Главная";
		
		$this->text = $this->ob_m->get_home_page();
		$this->keywords = $this->text['keywords'];
		$this->discription =  $this->text['discription'];
		
	}
	
	protected function output() {
		
		$this->content = $this->render(VIEW.'content',array(
														'text'=>$this->text
														));
		
		$this->page = parent::output();
		return $this->page;
		
	}
}
?>