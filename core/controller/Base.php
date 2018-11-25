<?php
abstract class Base extends Base_Controller {
	
	protected $ob_m;
	
	protected $title;
	
	protected $style;
	
	protected $script;
	
	protected $header;
	
	protected $header_menu;
	
	protected $content;
	
	protected $left_bar;
	
	protected $right_bar;
	
	protected $footer;
	
	protected $need_right_side = TRUE;
	protected $news,$pages,$catalog_type,$catalog_brands,$keywords,$discription;
	
	
	protected function input() {
		
		$this->title = "Промстрой энерго | ";
		
		foreach($this->styles as $style) {
			$this->style[] = SITE_URL.VIEW.$style;
		}
		
		foreach($this->scripts as $script) {
			$this->script[] = SITE_URL.VIEW.$script;
		}
		
		//object Model
		$this->ob_m = Model::get_instance();
		
		$this->news = $this->ob_m->get_news();
		
		$this->pages = $this->ob_m->get_pages();
		
		$this->catalog_type = $this->ob_m->get_catalog_type();
		
		$this->catalog_brands = $this->ob_m->get_catalog_brands();
		
		$this->header_menu = $this->ob_m->get_header_menu();
		
	}
	
	protected function output() {
		
		$this->left_bar = $this->render(VIEW.'left_bar',array(
														'pages'=>$this->pages,
														'types' => $this->catalog_type,
														'brands' => $this->catalog_brands
														));
		
		if($this->need_right_side) {
			$this->right_bar = $this->render(VIEW.'right_bar',array(
																'news' => $this->news
																));
		}
		
		
		$this->footer = $this->render(VIEW.'footer', array(
													'pages' => $this->pages
													));
		$this->header = $this->render(VIEW.'header',array(
															'styles' => $this->style,
															'scripts' => $this->script,
															'header_menu' => $this->header_menu,
															'title' => $this->title,
															'keywords' => $this->keywords,
															'discription' => $this->discription
															
															));											
		
		$page = $this->render(VIEW.'index',
										array(
											'header'=>$this->header,
											'left_bar' =>$this->left_bar,
											'content' => $this->content,
											'right_bar' => $this->right_bar,
											'footer' => $this->footer
											));
		return $page;									
	}
	
}
?>