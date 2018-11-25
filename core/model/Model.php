<?php
class Model {
	
	static $instance;
	
	public $ins_driver;
	
	
	static function get_instance() {
		if(self::$instance instanceof self) {
			return self::$instance;
		}
		return self::$instance = new self;
	}
	
	private function __construct() {
		
		try {
			$this->ins_driver = Model_Driver::get_instance();
		}
		catch(DbException $e) {
			exit();
		}
	}
	
	public function get_news() {
		$result = $this->ins_driver->select(
											array('news_id','title','anons','date'),
											'news',
											array(),
											'date',
											'DESC',
											3
									);
		$row = array();	
		foreach($result as $value) {
			$value['anons'] = substr($value['anons'],0,255);
			$value['anons'] = substr($value['anons'],0,strrpos($value['anons'],' '));
			
			$row[] = $value;
		}									
		return $row;									
	}
	
	public function get_pages($all = FALSE) {
		
		if($all) {
			$result = $this->ins_driver->select(
											array('page_id','title','type'),
											'pages',
											array(),
											'position',
											'ASC'
											);
		}
		else {
			$result = $this->ins_driver->select(
											array('page_id','title'),
											'pages',
											array('type' => "'post','contacts'"),
											'position',
											'ASC',
											FALSE,
											array("IN")
											);
		}
		
		
		return $result;									
	}
	
	public function get_catalog_type() {
		$result = $this->ins_driver->select(
											array('type_id','type_name'),
											'type'
											);
		return $result;									
	}
	public function get_catalog_brands() {
		$result = $this->ins_driver->select(
											array('brand_id','brand_name','parent_id'),
											'brands'
											);
		
		$arr = array();									
		foreach($result as $item) {
			if($item['parent_id'] == 0) {
				//parent
				$arr[$item['brand_id']][] = $item['brand_name'];
			}
			else {
				//child
				$arr[$item['parent_id']]['next_lvl'][$item['brand_id']] = $item['brand_name'];
			}
		}
		return $arr;									
	}
	
	public function get_home_page() {
		$result = $this->ins_driver->select(
											array('page_id','title','text','keywords','discription'),
											'pages',
											array('type'=> 'home'),
											FALSE,
											FALSE,
											1
											);
		return $result[0];									
	}
	
	public function get_header_menu() {
		$result = $this->ins_driver->select(
											array('type_id','type_name'),
											'type',
											array('in_header' => "'1','2','3','4'"),
											'in_header',
											'ASC',
											4,
											array('IN')
											);
		$row = array();
		foreach($result as $item) {
			$item['type_name'] = str_replace(" ","<br />",$item['type_name']);
			$item['type_name'] = mb_convert_case($item['type_name'],MB_CASE_UPPER,"UTF-8");
			$row[] = $item;
		}																	
											
		return $row;									
		
	}
	
	public function get_news_text($id) {
		$result = $this->ins_driver->select(
											array('title','text','date','keywords','discription'),
											'news',
											array('news_id' => $id)
											);
		return $result[0];									
	}
	
	public function get_page($id) {
		$result = $this->ins_driver->select(
											array('title','keywords','discription','text'),
											'pages',
											array('page_id' => $id)
											);
		return $result[0];									
	}
	
	public function get_contacts() {
		
		$result = $this->ins_driver->select(
											array('page_id','title','text','keywords','discription'),
											'pages',
											array('type'=>'contacts')
											);									
		return $result[0];									
	}
	
	public function get_child($id) {
		$result = $this->ins_driver->select(
											array('brand_id'),
											'brands',
											array('parent_id' => $id)
									);
		if($result) {
			$row = array();
			foreach	($result as $item) {
				$row[] = $item['brand_id'];
			}
			$row[] = $id;
			
			$res = implode(",",$row);	
											
			return $res;	
		}
		else {
			return FALSE;
		}
										
	}
	
	public function get_krohi($type,$id) {
		if($type == 'type') {
			$sql = "SELECT type_id, type_name
					FROM type
					WHERE type_id = $id";
		}
		
		if($type == "brand") {
			$sql = "(SELECT brand_id,brand_name
					FROM brands
					WHERE brand_id = (SELECT parent_id FROM brands WHERE brand_id = $id))
					UNION
					(SELECT brand_id, brand_name FROM brands WHERE brand_id = $id)";
		}
		$result = $this->ins_driver->ins_db->query($sql);
		
		if(!$result) {
			throw new DbException("Ошибка базы данных".$this->ins_driver->ins_db->errno."|".$this->ins_driver->ins_db->error);	
		}
		if($result->num_rows == 0) {
			return FALSE;
		}
		$row = array();
		
		for($i = 0; $i < $result->num_rows;$i++) {
			$row[] = $result->fetch_assoc();
		}
		
		return $row;
	}
	
	public function get_tovar($id) {
		$result = $this->ins_driver->select(
											array('title','text','img','keywords','discription'),
											'tovar',
											array('tovar_id' => $id,'publish' => 1)			
											);
		return $result[0];									
	}
	
	public function get_pricelist() {
		$sql = "
				SELECT brands.brand_id,
						brands.brand_name,
						brands.parent_id,
						tovar.title,
						tovar.anons,
						tovar.price
						FROM brands
				LEFT JOIN tovar
					ON tovar.brand_id=brands.brand_id
				WHERE brands.brand_id
					IN( 
						SELECT brands.parent_id FROM tovar 
						LEFT JOIN brands ON tovar.brand_id=brands.brand_id
						WHERE tovar.publish='1'
					)
					OR brands.brand_id
					IN (
						SELECT brands.brand_id
							 FROM tovar 
							 LEFT JOIN brands 
							 	ON tovar.brand_id=brands.brand_id 
							WHERE tovar.publish='1'
						)
					AND  tovar.publish='1'
				
				";
		$result = $this->ins_driver->ins_db->query($sql);
		
		if(!$result) {
			throw new DbException("Ошибка базы данных : ".$this->ins_driver->ins_db->errno."|".$this->ins_driver->ins_db->error);
		}
		if($result->num_rows == 0) {
			return FALSE;
		}
		
		$myrow = array();
		
		for($i = 0; $i < $result->num_rows; $i++) {
			$row = $result->fetch_assoc();
			
			if($row['parent_id'] === '0') {
				if(!empty($row['title'])) {
					$myrow[$row['brand_id']][$row['brand_name']][] = array(
																'title'=>$row['title'],
																'anons' => $row['anons'],
																'price' => $row['price']
																			
																			);
				}
				else {
					$myrow[$row['brand_id']][$row['brand_name']] = array();
				}
			}
			else {
				$myrow[$row['parent_id']]['sub'][$row['brand_name']][] = array(
																'title'=>$row['title'],
																'anons' => $row['anons'],
																'price' => $row['price']
																			
																			);
			}
			
		}	
		
		return $myrow;
	}
	
	public function add_page($title,$text,$position,$keywords,$discription) {
		$result = $this->ins_driver->insert(
									'pages',
									array('title','text','position','keywords','discription'),
									array($title,$text,$position,$keywords,$discription)
									);
		return $result;							
	}
	
	public function get_page_admin($id) {
		$result = $this->ins_driver->select(
											array('page_id','title','keywords','discription','text','position'),
											'pages',
											array('page_id' => $id)
											);
		return $result[0];									
	}
	
	
	public function edit_page($id,$title,$text,$position,$keywords,$discription) {
		$result = $this->ins_driver->update(
								'pages',
								array('page_id','title','text','position','keywords','discription'),
								array($id,$title,$text,$position,$keywords,$discription),
								array('page_id' =>$id)
								);
		return $result;						
	}
	
	public function delete_page($id) {
		$result = $this->ins_driver->delete('pages',array('page_id' =>$id));
		
		return $result;
	}
	public function add_news($title,$text,$anons,$keywords,$discription) {
		$result = $this->ins_driver->insert('news',
											array('title','text','anons','date','keywords','discription'),
											
											array($title,$text,$anons,time(),$keywords,$discription)
											);
		return $result;									
	}
	
	public function get_admin_news_text($id) {
		$result = $this->ins_driver->select(
											array('news_id','title','anons','text','date','keywords','discription'),
											'news',
											array('news_id' => $id)
											);
		return $result[0];									
	}
	
	public function edit_news($title,$text,$anons,$id,$keywords,$discription) {
		$result = $this->ins_driver->update(
										'news',
										array('title','text','anons','date','keywords','discription'),
										array($title,$text,$anons,time(),$keywords,$discription),
										array('news_id'=>$id)
										);
		return $result;								
										
	}
	
	public function delete_news($id) {
		$result = $this->ins_driver->delete(
											'news',array('news_id' => $id)
											);
		return $result;									
	}
	
	public function get_parent_brands() {
		$result = $this->ins_driver->select(
										array('brand_id','brand_name'),
										'brands',
										array('parent_id' => 0)
										);
		return $result;								
	}
	
	public function add_category($title,$parent) {
		$result = $this->ins_driver->insert(
											'brands',
											array('brand_name','parent_id'),
											array($title,$parent)
											);
		return $result;									
	}
	
	public function add_new_type($type_name) {
		$result = $this->ins_driver->insert(
											'type',
											array('type_name'),
											array($type_name),
											TRUE
											);
		return $result;									
	}
	
	public function add_goods($id,
										$title,
										$anons,
										$text,
										$img,
										$type,
										$publish,
										$price,
										$keywords,
										$discription) {
		$result = $this->ins_driver->insert(
											'tovar',
											array('title','anons','text','img','brand_id','type_id','publish','price','keywords','discription'),
											array($title,$anons,$text,$img,$id,$type,$publish,$price,$keywords,$discription)
											);
		return $result;																		
										}
	public function get_tovar_adm($id) {
		$result = $this->ins_driver->select(
											array('tovar_id','title','text','img','keywords','discription','anons','brand_id','type_id','publish','price'),
											'tovar',
											array('tovar_id' => $id)			
											);
		return $result[0];	
	}	
	
	public function edit_goods($id,$title,$anons,$text,$img,$type,$publish,$price,$category,$keywords,$discription) {
		if($img) {
			$result = $this->ins_driver->update(
											'tovar',
											array('title','anons','text','img','type_id','publish','price','brand_id','keywords','discription'),
											array($title,$anons,$text,$img,$type,$publish,$price,$category,$keywords,$discription),
											array('tovar_id' => $id)
											);
											
		}
		else {
			$result = $this->ins_driver->update(
											'tovar',
											array('title','anons','text','type_id','publish','price','brand_id','keywords','discription'),
											array($title,$anons,$text,$type,$publish,$price,$category,$keywords,$discription),
											array('tovar_id' => $id)
											);
		}
		return $result;
	}
	
	public function delete_tovar($id) {
		$result = $this->ins_driver->delete(
											'tovar',
											array('tovar_id'=>$id)
											);
		return $result;									
	}
	
	public function get_category($id) {
		$result = $this->ins_driver->select(
											array('brand_id','brand_name','parent_id'),
											'brands',
											array('brand_id' => $id)
											);
		return $result[0];									
	}
	
	public function edit_category($title,$parent,$id) {
		
		
			$result = $this->ins_driver->update(
											'brands',
											array('brand_name','parent_id'),
											array($title,$parent),
											array('brand_id'=>$id)
											);
		
									
		return $result;									
	}
	
	public function delete_category($id) {
		$result = $this->ins_driver->delete(
											'brands',
											array('brand_id' => $id)
											);
		$result2 = $this->ins_driver->update(
											'tovar',
											array('brand_id'),
											array(0),
											array('brand_id' => $id)
											);	
		if($result) {
			if($result2) {
				return TRUE;
			}
			else {
				return $result2;
			}
		}
		else {
			return $result;
		}																	
											
											
	}	
	public function get_type_adm($id) {
		$result = $this->ins_driver->select(
											array('type_id','type_name','in_header'),
											'type',
											array('type_id' => $id)
											);
											
		return $result[0];									
	}
	
	public function edit_types($type_name,$in_header,$id) {
		$result = $this->ins_driver->update(
											'type',
											array('type_name','in_header'),
											array($type_name,$in_header),
											array('type_id'=>$id)
											);
		return $result;									
	}
	
	public function delete_types($id) {
		$result = $this->ins_driver->delete(
											'type',
											array('type_id' => $id)
											);
		return $result;									
	}
	
	public function update_page_options($option,$new_id,$old = FALSE) {
		
		if(!$old) {
			$sql = "UPDATE pages SET type = '$option' WHERE page_id='$new_id'";
		}
		else {
			$sql = "UPDATE pages SET type = CASE
					WHEN page_id='$new_id' THEN '$option'
					WHEN page_id='$old' THEN 'post' END
					WHERE page_id IN('".$new_id."','".$old."')";
		}
		
		$result = $this->ins_driver->ins_db->query($sql);
		if(!$result) {
			throw new DbException("Ошибка базы данных: ".$this->ins_db->errno." | ".$this->ins_db->error);
			return FALSE;
		}
		return TRUE;
		
	}							
}

?>