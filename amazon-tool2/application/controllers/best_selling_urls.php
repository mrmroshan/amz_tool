<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Best_selling_urls extends CI_Controller {

	/**
	 * __construct()
	 * 
	 * This is the constructor function
	 * 
	 */
	
	
	
	function __construct(){	
	
		parent::__construct();
		$this->load->model('best_selling_urls_model');
		$this->load->model('best_selling_product_urls_model');
		$this->load->model('project_model');
		$this->load->model('products_model');
		$this->load->model('product_qna_model');
	}
	
	
	/*
	public function progress_dashboard($project_id){
	
		$this->output->enable_profiler(false);
		$data = array();
	
		$data['project_id'] = $project_id;
		$this->load->view('best_selling_product_urls/progress_dashboard',$data);
	
	}//end of function
	*/
	
	
	/*
	public function collect_best_selling_page_urls($project_id){
		
		$record_set = $this->project_model->select_records('*',null,null,array('project_id'=>$project_id));
		$project_data = $record_set['result_set'][0];
	
		$best_selling_url = $project_data['amazon_best_selling_url'];
		$url_arr = array();
	
		try{
				
			$html = file_get_html($best_selling_url);
			$zg_page = $html->find('li[class="zg_page"]');
				
			if(!empty($zg_page)){
				foreach($zg_page as $li){
					$page_url = trim($li->children(0)->attr['href']);
					$url_arr[] = $page_url;
				}//end foreach
			}//end if
				
		} catch (Exception $e) {
			echo 'Error Caught:'.$e;
		}
		$save_data = array();
		foreach($url_arr as $k => $v){
			
			$save_data = array(
					'project_id' => $project_id,
					'best_selling_product_url' => $v,
					'url_index' => $k,
					'date' => date('Y-m-d'),
					'status' => 0
			);
			
			$dup_data = array(
					'project_id' => $project_id,
					//'best_selling_product_url' => $v,
					'url_index' => $k,				
			);
			
			
					
			if(!$this->best_selling_urls_model->is_exist($dup_data)){
				$this->best_selling_urls_model->save($save_data);
			}else{
				echo 'Urls existing!';
				exit;
			} 
		}
		//var_dump($save_data);
		
		echo 'Completed';
	
	}//end of function
	*/
	
	
	
	/*public function collect_product_url_from_each_page($project_id){
	
		$record_set = $this->best_selling_urls_model->select_records('*',null,null,array('project_id'=>$project_id,'status' => 0));
		$product_list_pages = $record_set['result_set'];//best_selling_product_url
			
		$url_arr = array();
		
		foreach($product_list_pages as $page){
			
			$best_selling_page_url = $page['best_selling_product_url'];
			$best_selling_url_index = $page['url_index'];
			$id = $page['id'];
						
			try{
			
				$html = file_get_html($best_selling_page_url);
				$zg_title = $html->find('div[class="zg_title"]');
			
				if(!empty($zg_title)){
					foreach($zg_title as $title){
						$product_page_url =$title->children(0)->attr['href'];
						
						$url_parts = explode('/',$product_page_url);						
						/*
						var_dump($url_parts);exit;
						0 => string 'http:' (length=12)
						1 => string '' (length=0)
						2 => string 'www.amazon.com' (length=14)
						3 => string 'Step2-787699-KidAlert-V-W-S' (length=27)
						4 => string 'dp' (length=2)
						5 => string 'B001R5CJII' (length=10)
						6 => string 'ref=zg_bs_723036011_1' (length=21)
						7 => string '191-2380758-8902245
	
						
						$url_arr[]['url'] = trim($product_page_url);
						$url_arr[]['page_id'] = $page['id'];

						$save_data = array(
								'project_id' => $project_id,
								'BSPP_url_id' => $page['id'],
								'product_url' => trim($product_page_url),
								'ASIN_id' => $url_parts[5],
								'status' => 0
						);		
						
						$dup_chk = array(
								'project_id' => $project_id,
								'BSPP_url_id' => $page['id'],
								//'product_url' => trim($product_page_url),
								'ASIN_id' => $url_parts[5],
								'status' => 0
						);
						//save record
						if(!$this->best_selling_product_urls_model->is_exist($dup_chk)){
							
							$this->best_selling_product_urls_model->save($save_data);
							//update fk table
							$update_data = array('status' => 1	);
							$this->best_selling_urls_model->update_record(
									$update_data,
									array(
											'id' => $id,
											'project_id' => $project_id,
											'url_index' => $best_selling_url_index
									));
						}else{
							echo 'Urls existing!';
							exit;
						}//end if						
					}//end foreach
				}//end if
		
			} catch (Exception $e) {
				echo 'Error Caught:'.$e;
			}
			
		
		}//end offoreach			
		
		echo 'Completed';
		
	}//end of unction
	
*/	
	
	

	/*public function get_product_details($project_id){
	

		$debug = false;
		$obj = new AmazonProductAPI();
				
		$record_set = $this->best_selling_product_urls_model->select_records('*',null,null,
							array('project_id'=>$project_id,
									'status' => 0)
		);
		$product_list = $record_set['result_set'];//best_selling_product_url
		
		$i = 0;
		foreach($product_list as $product){
			
			$asin_code = $product['ASIN_id'];
			$result = null;
		
			try{  
				$result = json_encode($obj->getItemByAsin($asin_code));	
			}catch(Exception $e){				
				echo $e->getMessage();
				exit;	
			}
			//var_dump(json_encode($result));
			if($debug) log_message('debug',"<b>appregistrar/issueToken(),token data saved:</b>".print_r($result,true));
			$save_data = array(
					'ASIN_id' =>$asin_code,
					'xml_data' => $result,
					'project_id' => $project_id
			);		

			$dup_chk = array(
					'ASIN_id' =>$asin_code,
					'project_id' => $project_id
			);
			//save record
			if(!$this->products_model->is_exist($dup_chk)){
			
				$this->products_model->save($save_data);
				
				$i++;
				
				//update fk table
				$update_data = array('status' => 1	);
				$this->best_selling_product_urls_model->update_record(
						$update_data,
						array(		
								'project_id' => $project_id,
								'ASIN_id' => $asin_code
						));
				
			}else{
				
			}
			
		}
		echo 'Completed';
	
	}//end of function
	
*/	
	
	public function get_product_QA($project_id){
		
		$q = null;
		$a = null;
		
		$record_set = $this->products_model->select_records('id,project_id,ASIN_id',null,null,
				array('project_id'=>$project_id)
		);
		$product_list = $record_set['result_set'];//best_selling_product_url
		
		$i = 0;
		foreach($product_list as $product){
				
			$asin_code = $product['ASIN_id'];
			//$result .= $asin_code.'<br>';
			
			////////////////////
			
			$asin = $asin_code;
			$url = (isset($_POST['url']))?$_POST['url']:"http://www.amazon.com/forum//ref=ask_dp_dpmw_al_hza?asin=$asin&cdSort=best";
			
			try{
				$html = file_get_html($url);
				//$webpage = $this->get_web_page($url);
				//$html = str_get_html($webpage['content']);
			
				$divQnA = $html->find('div[class="cdQAListItemContent"]');
				$pagination = $html->find('div[class="cdPageSelectorPagination"]');
					
				if(!empty($divQnA)){
						
					$n = 1;
					foreach($divQnA as $item){

						$q = @$item->children(0)->children(0)->plaintext;
						$a = @$item->children(1)->children(0)->children(1)->plaintext;
						
						$save_data = array(								
								'project_id' => $project_id,
								'product_id' => $product['id'],
								'ASIN_id' => $asin_code,
								'question' => $q,
								'answer' => $a
						);
						
						$this->product_qna_model->save($save_data);
						
					};
			
					$page = $pagination[0];
						
					$noOfAs = count($page->find('a'));
						
					$secondA = $page->find('a')[0];
					$secondAString = $page->find('a')[0]->plaintext;
						
					$ninthA = $page->find('a')[$noOfAs - 2];
					$ninthAString = $page->find('a')[$noOfAs - 2]->plaintext;
						
					$secondAhref = $secondA->href;
					$ninthAhref = $ninthA->href;
			
					//  http://www.amazon.com/Regalo-Easy-Step-Thru-White/forum/FxKNR79T2C2XTX/-
					$firstUrlPart = strstr($secondAhref,"/-/",true); 
					
					//  /2/ref=cm_cd_ql_psf_ql_pg2/176-1298056-2405123?_encoding=UTF8&asin=B001OC5UMQ&cdAnchor=B001OC5UMQ&cdSort=best
					$lastUrlPart = strstr($secondAhref,"/$secondAString/");
					$urlParts = explode('/',$secondAhref);
						
					//for($i=2;$i<=(int)$ninthAString;$i++){ // comented to reduce time spent
					for($i=2;$i<=8;$i++){
							
						$urlParts[7] = $i;
						$urlParts[8] = 'ref=cm_cd_ql_psf_ql_pg'.$i;
						$newUrl = null;

						foreach($urlParts as $urlPart){
							$newUrl .= $urlPart.'/';
						}
						
						//$urlList .=  $newUrl."<br>";
			
						$html = file_get_html($newUrl);
						//$webpage = $this->get_web_page($newUrl);
						//$html = str_get_html($webpage['content']);
			
						$divQnA = $html->find('div[class="cdQAListItemContent"]');
						$pagination = $html->find('div[class="cdPageSelectorPagination"]');
			
						$s = 1;
						
						foreach($divQnA as $item){							
						
							$q = @$item->children(0)->children(0)->plaintext;
							$a = @$item->children(1)->children(0)->children(1)->plaintext;
							
							$save_data = array(
									'project_id' => $project_id,
									'product_id' => $product['id'],
									'ASIN_id' => $asin_code,
									'question' => $q,
									'answer' => $a
							);
							
							$this->product_qna_model->save($save_data);
											
						};
					}
				}else{
					$q = "No content found!";
					$a = "No content found!";
					
					$save_data = array(
							'project_id' => $project_id,
							'product_id' => $product['id'],
							'ASIN_id' => $asin_code,
							'question' => $q,
							'answer' => $a
					);
					
					$this->product_qna_model->save($save_data);
				}
				//$data['contents'] = $this->contents;		
				
					
			} catch (Exception $e) {
				echo 'Error Caught:'.$e;
			}
			
			
			////////////////////
		}//end foreach
		
		echo 'Completed';
	}//end of function
	
	
	/*
	public function dashboard(){	
	
		$this->output->enable_profiler(false);
		$data = array();		
		$this->load->view('projects/index',$data);		
		
	}//end of function
			
	
	
	public function add_new(){	
				
		$form_data = array();
		$form_data['gen_message'] = null;
		$post = $this->input->post(null);
		$form_data['post'] = $post;
		
		if($this->input->post('btnSubmit')){
					
			$validation = $this->validate_form();
			
			
				if($validation){	
					//remove submit button from the array
					foreach($post as $k => $v){
						if($k == 'btnSubmit')unset($post[$k]);					
					}
					//$post['company_id'] =  $this->session->userdata('company_id');
					$post['status'] =  0;
					$save_data =$post;
							
					if(!$this->project_model->is_exist($save_data)){
					
						$result = $this->project_model->save($post);
						$this->new_project_id = $this->db->insert_id();
					
						if($result>0){
							$form_data['gen_message'] = array(
									'type' => 'success',
									'text' => 'Data saved!');
							$this->redirect_home(site_url('projects/progress_dashboard/'.$this->new_project_id));
						}else{

							$form_data['gen_message'] = array(
								'type' => 'error',
								'text' => 'Data not saved!');
						}
					}else{
						$form_data['gen_message'] = array(
								'type' => 'error',
								'text' => 'Given data already existing!');
						$form_data['form_data_val'] = $post;				
					
					}//end if datacheck
					
				}else{
					$form_data['gen_message'] = array(
									'type' => 'warning',
									'text' => 'Please correct following errors.'
							);
				}//end of validation check
		
		}//end if submit check	
		
		$this->load->view('projects/add_new',$form_data);

		
	}//end of function
	
	
	
	

	public function edit($id){
	
		$this->output->enable_profiler(false);
	
		$form_data = array();
	
		$form_data['gen_message'] = null;
		$form_data['form_data_val'] = null;
		$form_data['project_id'] = $id;
		$post = $this->input->post(null);
		//$form_data['post'] = $post;
		
		if(isset($post['btnSubmit'])){
	
			$form_data['form_data_val'] = $post;
			$validation = $this->validate_form(true);
			
			if($validation){
					
				//remove submit button from the array
				foreach($post as $k => $v){
					if($k == 'btnSubmit')unset($post[$k]);
				}
				//if form data is valid
				$post['status'] =  0;
				$save_data =$post;
				
				$result = $this->project_model->update_record($save_data,array('project_id'=>$id));
				
				if($result>0){
					$form_data['gen_message'] = array(
							'type' => 'success',
							'text' => 'Data updated!');				
				
					$this->redirect_home(site_url('projects/dashboard'));
				
				}else{
					$form_data['gen_message'] = array(
							'type' => 'error',
							'text' => 'Data not updated!');
				}//end if	
							
			}//end if check	
					
		}//end if submit check
	
		//get record data
		$record_set = $this->project_model->select_records('*',null,null,array('project_id'=>$id));
		$form_data['form_data_val'] = $record_set['result_set'][0];
		$this->load->view('projects/edit',$form_data);
			
	}//end of function
	
	
	
	
	
	
	private function validate_form($edit = false){
		
		//do validation here
		$this->form_validation->set_rules('proj_name', 'Proect name', 'required');
		$this->form_validation->set_rules('amazon_tracking_code', 'Amazone tracking code', 'required');
		$this->form_validation->set_rules('amazon_best_selling_url', 'Best sellign URL field', 'required');
		$this->form_validation->set_rules('read_more_tag', 'Read more element HTML', 'required');
		$this->form_validation->set_rules('site_url', 'Website URL', 'required');
		$this->form_validation->set_rules('amazon_button_url', 'Amazon button element URL', 'required');
		$this->form_validation->set_rules('template_id', 'Template', 'required|integer');
		
		if($edit == false){
			
		}else{
			
		}				
		return $this->form_validation->run();
				
	}//end of function
	
	*/
	
	/*
	
	public function index($fields_list = "*", $limit = 25 , $offset = 0){
	
		$this->output->enable_profiler(false);
		$form_data = array();	
		$record_set = $this->Category_Model->select_records('*',$limit,$offset);
		$form_data['record_set'] = $record_set;
		$form_data['limit'] = $limit;
		$form_data['offset'] = $offset;
		$this->load->view('AdminLTE-master/categories/index',$form_data);
	
	}//end of function
	
	*/
	
		
	
	/**
	 * This function is to produce dhtmlx grid
	 * @param string $feed_type
	 */
	
	/*
	public function produce_grid_feed($limit=25,$offset=0){
	
	
		$record_set = $this->project_model->select_records('*',$limit,$offset,null);
	
		// init xml writer
		$xml = new Xml_writer();
	
		$xml->setRootName('rows');
		$xml->initiate();
	
		foreach($record_set['result_set'] as $record ){
	
			$xml->startBranch('row',array('id' =>$record['project_id']));
			
			$xml->addNode('cell','<a href="'.
					site_url('projects/edit/'.$record['project_id']).'" >Edit</a> '.
					'| '.
					'<a href="'.site_url('projects/delete/'.$record['project_id']).
					'" onclick= "return confirm(\'Are you sure you want to delete this item?\');" >Delete',null, true);
			$xml->addNode('cell',$record['project_id'],null, true);
			$xml->addNode('cell',$record['proj_name'],null, true);
			$xml->addNode('cell',$record['site_url'],null, true);
			$xml->addNode('cell',$record['amazon_tracking_code'],null, true);
			$xml->addNode('cell',$record['amazon_best_selling_url'],null, true);
			$xml->addNode('cell',$record['read_more_tag'],null, true);
			$xml->addNode('cell',$record['template_id'],null, true);
			$xml->addNode('cell',$record['status'],null, true);
			
			
			$xml->endBranch();
	
		}
	
		$data = array();
		$xml->getXml(true);
	
	
	}//end of index()
	
	private function redirect_home($url){	
		
		$this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header('refresh:3; url='.$url);
		
	}//end of function

	*/
	
	/**
	 * common delete() is used to delete a record. Takes record id as parameter.
	 */
	/*
	public function delete($id=null){
	
		$record_set = $this->project_model->select_records('*',null,null,array('project_id'=>$id));
		$form_data['form_data_val'] = $record_set['result_set'][0];
		
		$data = array('project_id' => $id);
		$status = $this->project_model->delete($data);
		
		if($status == 1){
		
			$this->load->view('projects/delete',$form_data);
			$this->redirect_home(site_url('projects/dashboard'));
		}
		
	}//end of delete
	
	
	
		
	public function createDropdown(){		
					
			$myParams = count($_POST)>0? $_POST:$_GET;
	
			$selectByValue = isset($myParams['sbv'])? $myParams['sbv']:null ;
			$selectByValue = (!empty($selectByValue))?$selectByValue:null; // set default values if not supplied.
	
			$optionsOnly = isset($myParams['optionsonly'])? $myParams['optionsonly']:false;
			$optionsOnly = ($optionsOnly == 'true')? true:false;
	
			$dataset = $this->project_model->select_records();
			
			$wrapped_data['data'] = $dataset['result_set'];
			$wrapped_data['selectByValue'] = $selectByValue;
			$wrapped_data['optionsOnly'] = $optionsOnly;		
	
			$this->load->view('departments/departments_dropdown',$wrapped_data);	
	}
	*/
	
}//end of class

