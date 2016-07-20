<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	
	
	function __construct(){
		
		parent::__construct();
		
	}
	
	public function index(){
		
			
		$data = array();
		$data['msg'] = null;
		$this->load->view('index',$data);
		$this->load->view('js-css-loader',$data);
						
		
	}//end of function	
	
}//end of Home

/* End of file home.php */
/* Location: ./application/controllers/home.php */
?>