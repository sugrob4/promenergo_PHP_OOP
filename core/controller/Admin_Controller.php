<?php
class Admin_Controller extends Base_Admin {
	
	protected $pages;
	protected $home;
	protected $contacts;
	protected $message;
	protected $page_text;
	protected $option = 'add';
	
	protected function input($param = array()) {
		parent::input();
		
		
		$home = $this->ob_m->get_home_page();
		if(is_array($home)) {
			$this->home = $home['page_id'];
		}
		
		$contacts = $this->ob_m->get_contacts();
		if(is_array($contacts)) {
			$this->contacts = $contacts['page_id'];
		}
		///////////////////////
		if($this->is_post()) {
			$id = $this->clear_int($_POST['id']);
			$title = $_POST['title'];
			$text = $_POST['text'];
			$keywords = $_POST['keywords'];
			$discription = $_POST['discription'];
			$position = $this->clear_int($_POST['position']);
			
			if(!empty($title) && !empty($text)) {
				if($_POST['add_x']) {
					$result = $this->ob_m->add_page(
												$title,
												$text,
												$position,
												$keywords,
												$discription
												);
					if($result === TRUE) {
						$_SESSION['message'] = "Новая страница успшно добавлена";
					}
					else {
						$_SESSION['message'] = "Ошибка добавления данных";
					}
					
					header("Location:".SITE_URL."admin");	
					exit();						
				}
				
				if($_POST['edit_x']) {
					$result = $this->ob_m->edit_page($id,
													$title,
													$text,
													$position,
													$keywords,
													$discription
													);
					if($result === TRUE) {
						$_SESSION['message'] = "Изменения сохранены";
					}
					else {
						$_SESSION['message'] = "Ошибка изменения данных";
					}
					header("Location:".SITE_URL."admin/id/".$id);	
					exit();							
				}
			}
			else {
				if($_POST['add_x']) {
					$_SESSION['message'] = "Заполните обязательные поля";
					header("Location:".SITE_URL."admin");	
					exit();
				}
				elseif($_POST['edit_x']) {
					$_SESSION['message'] = "Заполните обязательные поля";
					header("Location:".SITE_URL."admin/id/".$id);	
					exit();
				}
			}
			
			if($_POST['home_x']) {
				$new_home_id = $_POST['home_page'];
				
				$result = $this->ob_m->update_page_options(
														'home',
														$new_home_id,
														$this->home
														);
				if($result === TRUE) {
					$_SESSION['message'] = "Изменения сохранены";
				}
				else {
					$_SESSION['message'] = "Ошибка изменения главной страницы ";
				}
					
				header("Location:".SITE_URL."admin");	
				exit();												
			}
			if($_POST['contacts_submit_x']) {
				$new_contacts_id = $_POST['contacts'];
				
				$result = $this->ob_m->update_page_options(
														'contacts',
														$new_contacts_id,
														$this->contacts
														);
				if($result === TRUE) {
					$_SESSION['message'] = "Изменения сохранены";
				}
				else {
					$_SESSION['message'] = "Ошибка изменения страницы показа контактной информации";
				}
					
				header("Location:".SITE_URL."admin");	
				exit();												
			}
		}
		/////////////////////
		
		$this->title .= " Редактирование страниц";
		
		$this->pages = $this->ob_m->get_pages(TRUE);
		
		if(isset($param['id'])) {
			$id = $this->clear_int($param['id']);
			$this->page_text = $this->ob_m->get_page_admin($id);
			$this->option = 'edit';
			
			if($param['option'] == 'delete') {
				$result = $this->ob_m->delete_page($id);
				
				if($result === TRUE) {
					$_SESSION['message'] = "Страница успешно удалена";
				}
				else {
					$_SESSION['message'] = "Ошибка при удалении данных";
				}
				
				header("Location:".SITE_URL."admin");
				exit();
			}
		}
		
		$this->message = $_SESSION['message'];
	}
	
	protected function output() {
		
		$this->content = $this->render(VIEW.'admin/edit_pages',array(
														'pages'=>$this->pages,
														'home' => $this->home,
														'contacts' =>$this->contacts,
														'mes' => $this->message,
														'page_text' =>$this->page_text,
														'option' =>$this->option
																));
		
		$this->page = parent::output();
		unset($_SESSION['message']);
		return $this->page;
	}
}
?>