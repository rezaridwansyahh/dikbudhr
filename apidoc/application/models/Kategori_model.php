<?php 

/**
 * 
 */
class Kategori_model extends CI_Model
{
	function __construct(){

    }

    public function get_kategori(){
        $this->db->select('*');
        $this->db->from('webservice.api_kategori');

        $result = $this->db->get();

        return $result->result_array();
    }

    public function get_kategori_byId($id_kategori){
        $this->db->select('*');
        $this->db->from('webservice.api_kategori');
        $this->db->where('webservice.api_kategori.id', $id_kategori);

        $result = $this->db->get();

        return $result->row_array();
    }

    public function insert_kategori($arr_insert){
        $this->db->insert('webservice.api_kategori', $arr_insert);

        return $this->db->insert_id();
    }

    public function update_kategori($id_kategori, $arr_update){

        $this->db->where('webservice.api_kategori.id', $id_kategori);
        $this->db->update('webservice.api_kategori', $arr_update);

        return 'Update';
    }

    public function delete_kategori($id_kategori){
        $this->db->where('webservice.api_kategori.id', $id_kategori);
        $this->db->delete('webservice.api_kategori');

        return 'Delete';
    }

    public function update_api_contoller($arr_id_kategori){

        $this->db->set('webservice.api_controllers.kategori_id', '');
        $this->db->where_in('webservice.api_controllers.id', $arr_id_kategori);
        $this->db->update('webservice.api_controllers'); 

        return 'Update';
    }

    public function get_api_controller_by_kategori_id($id_kategori){
        $this->db->select('*');
        $this->db->from('webservice.api_controllers');
        $this->db->where('webservice.api_controllers.kategori_id', $id_kategori);

        $result = $this->db->get();

        return $result->result_array();
    }


}