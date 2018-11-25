<?php
class Search_Controller extends Base  {
	
	protected $str;
	
	protected $navigation;
	
	protected $search;
	
	protected function input($param = array()) {
		parent::input();
		
		if(isset($param['page'])) {
			$page = $this->clear_int($param['page']);
			
			if($page == 0) {
				$page = 1;
			}
		}
		else {
			$page = 1;
		}
		
		if(isset($param['str'])) {
			$this->str = rawurldecode($this->clear_str($param['str']));
		}
		elseif($this->is_post()) {
			$this->str = $this->clear_str($_POST['txt1']);
		}
		
		$this->title .= "Результаты поиска по запросу - ".$this->str;
		
		$this->keywords .= "Поиск, промэнерго";
		$this->discription .= "Результаты поиска по запросу - ".$this->str;
		
		$pager = new Pager(
							$page,
							'tovar',
							array('publish' => 1),
							'tovar_id',
							'ASC',
							QUANTITY,
							QUANTITY_LINKS,
							array("="),
							array('title,text' =>$this->str)					
							);
		
		if(is_object($pager)) {
			$this->navigation = $pager-> get_navigation();
			$this->search = $pager->get_posts();
			$this->str = rawurlencode($this->str);
		}
		
		$this->need_right_side = FALSE;							
	}
	
	protected function output() {
		
		
		$this->content = $this->render(VIEW.'search_page',
											array(
													'search' => $this->search,
													'navigation' => $this->navigation,
													'str' => $this->str
													));
		
		$this->page = parent::output();
		
		return $this->page;
	}
}
?>