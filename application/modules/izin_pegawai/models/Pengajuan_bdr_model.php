<?php defined('BASEPATH') || exit('No direct script access allowed');
class Pengajuan_bdr_model extends CI_Model {
    private $_table = "pengajuan_bdr";


    public function save_bdr($data){
        return $this->db->insert($this->_table, $data);
    }

    public function get_pengajuan_bawahan($nip_atasan){
        return $this->db->where('nip_atasan', $nip_atasan)->get($this->_table)->result();
    }

    public function get_pengajuan_pribadi($nip){
        return $this->db->where('nip', $nip)->get($this->_table)->result();
    }


    public function update_pengajuan($data){
        $this->db->where('id', $data['id']);
        unset($data['id']);
        return $this->db->update($this->_table, $data);
    }
    

    

}