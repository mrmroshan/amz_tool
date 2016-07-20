<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends CI_Controller {

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
	
	
	public function produce_grid_feed($limit=100,$offset=0,$project_id){
		
		$asin_code = null;
		$json_data = null;
		$result = null;
		
		$product_data_array = array();
		
		$record_set = $this->products_model->select_records('*',null,null,
				array('project_id'=>$project_id)
		);
		$product_list = $record_set['result_set'];
		
		// init xml writer
		$xml = new Xml_writer();
		
		$xml->setRootName('rows');
		$xml->initiate();
		
		foreach($product_list as $product){
		
			$id = $product['id'];
			$asin_code = $product['ASIN_id'];			
			$json_data = $product['xml_data'];
			$content = $product['content'];
			$posted = $product['posted'];
			$product_data_array = json_decode($json_data);

			
			$product_title = (isset($product_data_array->Items->Item->ItemAttributes->Title))? $product_data_array->Items->Item->ItemAttributes->Title:'Not supported format';
			$med_image_url = (isset($product_data_array->Items->Item->MediumImage->URL))? $product_data_array->Items->Item->MediumImage->URL:'Not supported format'; 
			$med_img_height =(isset($product_data_array->Items->Item->MediumImage->Height))? $product_data_array->Items->Item->MediumImage->Height:0;
			$med_img_width =(isset($product_data_array->Items->Item->MediumImage->Width))? $product_data_array->Items->Item->MediumImage->Width:0;
			
			$img_tag = '<img src="'.$med_image_url.'" />';//width="'.$med_img_height.'" height="'.$med_img_width.'"

			$divid = 'divqnastatus_'.$asin_code;
			$qna_fetch_value = $product['qna_fetched'];
			if(empty($content)){
				$qna_fetch = '<div id="'.$divid.'" class="incomplete"><a href="javascript:void(0)" onclick="fetch_QnA(\''.$divid.'\',\''.$asin_code.'\','.$project_id.')">Fetch Q&A</a></div>';
				$prepare_content = "<div id=\"divPrepCont".$asin_code."\">Content not ready</div>";
			}else{
				$qna_fetch = '<div id="'.$divid.'" class="complete">Done<br><textarea rows="4" cols="60" onClick="this.select()">'.$content.'</textarea></div>';
				//$prepare_content = "<div id=\"divPrepCont".$asin_code."\"><a href=\"javascript:void(0)\"
						 //onclick=\"\prepare_contents('divPrepCont".$asin_code."','".$asin_code."','".$project_id."')\" >Prepare Contents</a></div>";
				$prepare_content = '<select id="'.$asin_code.'" onchange="updateStatus(this,'.$project_id.')"><br>';
						if($posted == 0){
							$prepare_content .='<option value="0" selected>No</option><option value="1">Yes</option>';
						}else{
							$prepare_content .='<option value="0" >No</option><option value="1" selected>Yes</option>';
						}
						$prepare_content .='</select><div id="div'.$asin_code.'" ></div>';
				//<a href="'.site_url('projects/prepare_product_contents'.'/'.$asin_code.'/'.$project_id).'"></a></div>
					
			}
			
			
			$xml->startBranch('row',array('id' => $id));	
			$xml->addNode('cell',$id,null, true);
			$xml->addNode('cell',$img_tag,null, true);
			$asincodetxt = '<input type="text" value="'.$asin_code.'">';
			$xml->addNode('cell',$asincodetxt,null, true);
			$xml->addNode('cell',$product_title,null, true);
			$xml->addNode('cell',$qna_fetch,null, true);
			$xml->addNode('cell',$prepare_content,null, true);
			$xml->endBranch();
				
			
		}//end foreach
		
		$xml->getXml(true);
	
	
	}//end of index()
	
	
	public function updateStatus($proj_id,$sel_id,$sel_value){		
		
		$data=array('posted'=>$sel_value);
		$where=array('project_id'=>$proj_id,'ASIN_id'=>$sel_id);
		$result =$this->products_model->update_record($data,$where);
		
		echo 'Updated!';
	}
	
	
	private function redirect_home($url){	
		
		$this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header('refresh:3; url='.$url);
		
	}//end of function

	
	
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

