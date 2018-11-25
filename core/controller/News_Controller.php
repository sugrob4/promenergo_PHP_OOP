<?php
class News_Controller extends Base {
	
	protected $news_text;
	
	protected function input($params) {
		parent::input();
		
		$this->title .= "Новости";
		
		if(isset($params['id'])) {
			$id = $this->clear_int($params['id']);
		}
		if($id) {
			$this->news_text = $this->ob_m->get_news_text($id);
			
			$this->keywords = $this->news_text['keywords'];
			$this->discription = $this->news_text['discription'];
		}
	}
	
	protected function output() {
		
		$this->content = $this->render(VIEW.'news_page',array(
															'news_text' => $this->news_text
															));
		
		$this->page = parent::output();
		return $this->page;
	}
}
?>