<?php
class Catalog_Controller extends Base {
	
	protected $type = FALSE;
	protected $id = FALSE;
	protected $parent = FALSE;
	protected $navigation;
	protected $catalog;
	protected $krohi;
	
	protected function input($param = array()) {
		
		parent::input();
		
		$this->title .= "Каталог";
		
		$this->need_right_side = FALSE;
		
		if(isset($param['brand'])) {
			$this->type = "brand";
			$this->id = $this->clear_int($param['brand']);
		}
		elseif(isset($param['type'])) {
			$this->type = "type";
			$this->id = $this->clear_int($param['type']);
		}
		elseif(isset($param['parent'])) {
			$this->parent = TRUE;
			$this->id = $this->clear_int($param['parent']);
		}
		
		if(isset($param['page'])) {
			$page = $this->clear_int($param['page']);
			if($page == 0) {
				$page = 1;
			}
		}
		else {
			$page = 1;
		}
		
		if($this->type) {
			if(!$this->id) {
				return;
			}
			
		
			$pager = new Pager(
								$page,
								'tovar',
								array($this->type.'_id'=>$this->id,'publish'=>1),
								'tovar_id',
								'ASC',
								QUANTITY,
								QUANTITY_LINKS
								);
			$this->krohi = $this->ob_m->get_krohi($this->type,$this->id);
			$this->keywords = $this->krohi[0][$this->type.'_name'].','.$this->krohi[1]['brand_name'];					
			$this->discription = $this->krohi[0][$this->type.'_name'].','.$this->krohi[1]['brand_name'];						
		}
		elseif($this->parent) {
			if(!$this->id) {
				return;
			}
			$ids = $this->ob_m->get_child($this->id);
			
			if(!$ids) {
				return;
			}
			
			$pager = new Pager(
								$page,
								'tovar',
								array('brand_id' => $ids,'publish'=>1),
								'tovar_id',
								'ASC',
								QUANTITY,
								QUANTITY_LINKS,
								array("IN","=")					
								);
			$this->type = "parent";	
			
			$this->krohi = $this->ob_m->get_krohi('brand',$this->id);				
			$this->keywords = $this->krohi[0]['brand_name'];					
			$this->discription = $this->krohi[0]['brand_name'];
		}
		elseif(!$this->type && !$this->parent) {
			$pager = new Pager(
								$page,
								'tovar',
								array('publish'=>1),
								'tovar_id',
								'ASC',
								QUANTITY,
								QUANTITY_LINKS
								);
			$this->krohi[0]['brand_name'] = "Каталог";	
			$this->keywords = "Промэнерго, каталог товаров";					
			$this->discription = "Промэнерго, каталог товаров";				
								
		}
		if(is_object($pager)) {
			$this->navigation = $pager->get_navigation();
			$this->catalog = $pager->get_posts();
			
		}
		
	}
	
	
	protected function output() {
	
		$previous = FALSE;
		if($this->type && $this->id) {
			$previous = "/".$this->type."/".$this->id;
		}
		
		$this->content = $this->render(VIEW.'catalog_page', array(
															'catalog' => $this->catalog,
															'navigation' =>$this->navigation,
															'previous'=>$previous,
															'krohi' => $this->krohi
															));
		
		$this->page = parent::output();
		return $this->page;
	}
}
?>