<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project_model extends CI_Model{

	var $table_name = 'at_projects';
		
	/**
	 * common constructor
	 */
	function __construct(){	
	
		parent::__construct();
		
	}//end construct
	
	

	/**
	 * common function to select records.
	 */
	public function select_records($fields = '*', $limit = 20, $offset= 0,$where_condition = null ){				
		
		$this->db->select($fields);
		if(!empty($where_condition)){
			$this->db->where($where_condition);
		}
		$query = $this->db->get($this->table_name, $limit, $offset);			
		$results['result_set'] = $query->result_array();
				
		//for pagination
		if(!empty($where_condition)){
			$this->db->where($where_condition);
		}
		$query2 = $this->db->get($this->table_name);
		$results['total_records'] =  $query2->num_rows();
		
		return $results;
		
	}//end of select_records()
	
	

	/**
	 * common function to select record by id
	 */
	public function select_record_by_id($field_val = null){
			
		//if($this->debug) log_message('debug', 'select record ids:');
		
		$this->db->select('*');
		$this->db->where($field_val);
		$query = $this->db->get($this->table_name);		
		$records['result_set'] = $query->result_array();
		$records['num_rows'] = $query->num_rows();
		return $records;
		
	}//end of function select_record_by_ids(){
	
	
	/**
	 * is_exist()
	 * 
	 * This function check whether data is already present in the database
	 * 
	 * @param array $data
	 * @return bool
	 * 
	 */
	public function is_exist($data){
		
		$val = false;
		
		//$result = $this->select_records('*',10,0,$data);
		$result = $this->db->get_where($this->table_name, $data, 10, 0);
		if($result->num_rows	 > 0){
			$val = true;
		}	
		return $val;
		
	}//end of function
	
	
	
	/**
	 * common function to save record
	 */
	public function save($data){
			
		$this->db->insert($this->table_name, $data);		
		return $this->db->affected_rows();
		
	}//end of save_records()
	
	
	/**
	 * common function to update record
	 */
	public function update_record($data,$where){
			
		$this->db->where($where);
		$this->db->update($this->table_name, $data);
		return $this->db->affected_rows();
		
	}//end of function
	
	
	/**
	 * common function to delete record
	 */
	public function delete($data = null){
			
		$this->db->delete($this->table_name,$data);
		return $this->db->affected_rows();
	
	}//end of function delete
	
	
	/**
	 * common function to count all records.
	 */
	public function count_all_records(){		
		return $this->db->count_all($this->table_name);
	}//count_all_records()
	
	
}//end class
?>