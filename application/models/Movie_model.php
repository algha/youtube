<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Movie_model extends MY_Model {

	protected  $id;

	public function __construct() {
		parent::__construct();

		$this->id = $this->_table.'_id';
	}

	function getListBackEnd($get){
		
		$aColumns = array( $this->id, 'Name','Family_Description', 'Category_Description', 'Region', 'Language','Click','Published_at','Created_at');

		$parameters = tableRequestHelper($aColumns,$get);
		
		$this->db->from($this->_table);
		if ($parameters["where"] != ""){
			$this->db->where($parameters["where"],null, false);
		}
		
		if ($parameters["order"] == ""){
			$parameters["order"] = "Created_at DESC";
		}
		
		
		$this->db->order_by($parameters["order"]);
		$this->db->limit($parameters["length"],$parameters["start"]);
		$Datas= $this->db->get()->result_array();

		$count = $this->getCount();
		
		$response = array(
				'aaData' => $Datas,
				'iTotalRecords' => $count,
				'iTotalDisplayRecords' => $count
		);

		return $response;
	}
	
	function getList(Array $limit = array(0,6),Array $order = array("Created_at","DESC"),Array $where = array(),Array $like = array()){
		$this->db->select("*");
		$this->db->from($this->_table);
		
		if ($where != null){
			$this->db->where($where);
		}
		
		if ($order != null){
			$this->db->order_by($order[0], $order[1]);
		}
		
		if ($limit != null){
			$this->db->limit($limit[1],$limit[0]);
		}
		
		if($like != null){
			$this->db->like($like);
		}
		
		return $this->db->get()->result_array();
	}
	
	function getCount($where = NULL){
		$this->db->from($this->_table);
		if ($where != null){
			$this->db->where($where);
		}
		return $this->db->count_all_results();
	}

	function add($data,$insert_id = false){
		$this->db->insert($this->_table, $data);
		return $insert_id == true ? $this->db->insert_id() : $this->db->affected_rows();
	}

	function getone($id){
		$query = $this->db->get_where($this->_table,array($this->id=>$id));
		return $query->row_array();
	}
	
	function getoneByName($name){
		$query = $this->db->get_where($this->_table,array("UrlName"=>$name));
		return $query->row_array();
	}

	function del($id){
		return $this->delete(array($this->id=>$id));
	}

}