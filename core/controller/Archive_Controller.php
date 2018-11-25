<?php
class Archive_Controller extends Base {
	
	protected $archive;
	protected $navigation;
	
	public function input($param = array()) {
		parent::input();
		
		if($param['page']) {
			$page = $this->clear_int($param['page']);
			
			if($page == 0) {
				$page = 1;
			}
		}
		else {
			$page = 1;
		}
		
		$this->title .= "Архив новостей";
		
		$this->keywords = "Промстройэнерго, новости";
		$this->discription = "Архив новостей";
		
		$pager = new Pager(
							$page,
							'news',
							array(),
							'date',
							'DESC',
							QUANTITY,
							QUANTITY_LINKS
						);
		$this->archive = $pager->get_posts();
		$this->navigation = $pager->get_navigation();							
		
	}
	
	public function output() {
		
		$this->content = $this->render(VIEW.'archive_page',
													array(
														'archive' => $this->archive,
														'navigation' =>$this->navigation
														));
		
		$this->page = parent::output();
		return $this->page;
	}
}
?>