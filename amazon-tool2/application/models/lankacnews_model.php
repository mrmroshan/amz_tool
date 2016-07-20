<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class lankacnews_Model extends CI_Model{

	function save_hot_news($data){

		
		$data = array(
				'news_id' => $data['aricle_id'],
				'headline' => $data['title'],
				'summary' => 'test',
				'article' => $data['dateSeg'],
				'publised_date' => date('Y-m-d'),
				'extracted_date' => date('Y-m-d'),
		);				
		$this->db->insert('lankacnews',$data);
		
	}//end of function
	
}//end of class
?>