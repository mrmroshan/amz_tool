<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Amazon_Tool_Model extends CI_Model{

	function save_urls($data){

		
		$data = array(
				'proj_name' => $data['proj_name'],
				'url' => $data['url'],
				'date' => date('Y-m-d')
		);				
		$this->db->insert('urls',$data);
		
	}//end of function
	
	
	
	
}//end of class
?>