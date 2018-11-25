<?php
class Map_Controller extends Base {
	
	protected $pages,$catalog;
	
	protected function input() {
		parent::input();
		
		$this->title .= "Карта сайта";
		
		$this->pages = $this->ob_m->get_pages();
		
		$this->catalog = $this->ob_m->get_catalog_brands();
		
		$this->keywords = "Карта сайта";
		$this->discription = "Промстрой энерго карта сайта";
	}
	
	protected function output() {
		
		
		$this->content = $this->render(VIEW.'sitemap_page',
										array(
											'pages' => $this->pages,
											'catalog' => $this->catalog
										)
										);
		
		$this->page = parent::output();
		
		return $this->page;
	}
}
?>