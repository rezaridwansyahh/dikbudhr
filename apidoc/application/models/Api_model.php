<?php 

/**
 * 
 */
class Api_model extends CI_Model
{
	function __construct(){
        
    }

    public function get_api_all(){
        $this->db->select('*');
        $this->db->from('webservice.api_controllers');
        $this->db->order_by("id","asc");
        $result = $this->db->get();

        return $result->result_array();
    }

    public function get_api_by_id($id){
        $this->db->select('*');
        $this->db->from('webservice.api_controllers');
        $this->db->where('webservice.api_controllers.id', $id);

        $result = $this->db->get();

        return $result->row_array();
    }

    public function get_api_by_kategori_id($kategori_id){
        $this->db->select('*');
        $this->db->from('webservice.api_controllers');
        $this->db->where('webservice.api_controllers.kategori_id', $kategori_id);

        $result = $this->db->get();

        return $result->result_array();
    }

    public function insert_api($arr_insert){
        $this->db->insert('webservice.api_controllers', $arr_insert);

        return $this->db->insert_id();
    }

    public function update_api($id_kategori, $arr_update){

        $this->db->where('webservice.api_controllers.id', $id_kategori);
        $this->db->update('webservice.api_controllers', $arr_update);

        return 'Update';
    }

    public function delete_api($id_kategori){
        $this->db->where('webservice.api_controllers.id', $id_kategori);
        $this->db->delete('webservice.api_controllers');

        return 'Delete';
    }
}