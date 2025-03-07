<?php defined('BASEPATH') || exit('No direct script access allowed');
class Ippns_model extends CI_Model {
    private $_table = "vw_ippns_pegawai";

    public function get_all()
    {
        return $this->db->get($this->_table)->result();
    }

    public function getAllUnor()
    {
        return $this->db->get('vw_unor_satker_w_id_eselon1')->result();
    }
    
    public function get_ippns_by_unor($id){
        $query = $this->db->where("UNOR_ID", $id)
        ->or_where('"ESELON_1"',$id)	
		->or_where('"ESELON_2"',$id)
		->or_where('"ESELON_3"',$id)	
		->or_where('"ESELON_4"',$id)->get($this->_table)->result();
        
        //echo $this->db->get_where($this->_table, array('UNOR_ID' => $id))->get_compiled_select();
        return $query;
    }

    public function populateIPASN($id){
        //echo "peler";
        $list = $this->get_ippns_by_unor($id);
        $retList = array();
        foreach ($list as $key => $value) {                
            /**
             * Calculate Education Score
             */

            $value->SKOR_PENDIDIKAN = 1;
            if($value->TK_PENDIDIKAN==50){
                $value->SKOR_PENDIDIKAN = 25;
            }else if($value->TK_PENDIDIKAN==45){
                $value->SKOR_PENDIDIKAN = 20;
            }else if($value->TK_PENDIDIKAN==40||$value->TK_PENDIDIKAN==35){
                $value->SKOR_PENDIDIKAN = 15;
            }else if($value->TK_PENDIDIKAN==30 || $value->TK_PENDIDIKAN==25){
                $value->SKOR_PENDIDIKAN = 10;
            }else if($value->TK_PENDIDIKAN==20 || $value->TK_PENDIDIKAN==18 || $value->TK_PENDIDIKAN==17 || $value->TK_PENDIDIKAN==15 ){
                $value->SKOR_PENDIDIKAN = 5;
            }

            /**
             * Calculate Kompetensi
             */
            $value->SKOR_KOMPETENSI = 0;
            $value->NAMA_JENIS_JABATAN = "";
            $value->diklat_fungsional = 'x';
            
            if($value->JENIS_JABATAN_ID==1){
                $value->NAMA_JENIS_JABATAN = "Struktural";
                if($value->done_struktural=='t'){
                    $value->SKOR_KOMPETENSI = $value->SKOR_KOMPETENSI + 15;
                }

                if($value->tot_fungsional>20){
                    
                    $value->SKOR_KOMPETENSI = $value->SKOR_KOMPETENSI + 15;
                }

                if($value->done_kursus=='t'){
                    $value->SKOR_KOMPETENSI = $value->SKOR_KOMPETENSI + 10;
                }
            }
            
            else if($value->JENIS_JABATAN_ID==2){
                $value->NAMA_JENIS_JABATAN = "Fungsional";
                $value->done_struktural ='f';
                if($value->tot_fungsional>20){
                    /**
                     * Dianggap sudah ikut diklat fungsional
                     */
                    $value->diklat_fungsional = 'v';
                    $value->SKOR_KOMPETENSI = $value->SKOR_KOMPETENSI + 30;
                }

                if($value->done_kursus=='t'){
                    $value->SKOR_KOMPETENSI = $value->SKOR_KOMPETENSI + 10;
                }
            }
            

            else if($value->JENIS_JABATAN_ID==4){
                $value->NAMA_JENIS_JABATAN = "Pelaksana";
                $value->done_struktural ='f';
                if($value->tot_fungsional>20){
                    /**
                     * Dianggap sudah ikut diklat fungsional
                     */
                    
                    $value->SKOR_KOMPETENSI = $value->SKOR_KOMPETENSI + 22.5;
                }

                if($value->done_kursus=='t'){
                    $value->SKOR_KOMPETENSI = $value->SKOR_KOMPETENSI + 17.5;
                }
            }

            $value->done_kursus = $value->done_kursus =='t'?'v':'x';
            $value->done_struktural = $value->done_struktural =='t' ?'v':'x';
            $value->diklat_teknis = $value->tot_fungsional>20?'v':'x';

            $value->SKOR_KINERJA = 1;
            $value->RANGE_KINERJA = "<50";

            if($value->NILAI_SKP>=91 && $value->NILAI_SKP<=100){
                $value->SKOR_KINERJA = 30;
                $value->RANGE_KINERJA = "91 - 100";
            }else if($value->NILAI_SKP>=76 && $value->NILAI_SKP<=90){
                $value->SKOR_KINERJA = 25;
                $value->RANGE_KINERJA = "76 - 90";
            }else if($value->NILAI_SKP>=61 && $value->NILAI_SKP<=75){
                $value->SKOR_KINERJA = 15;
                $value->RANGE_KINERJA = "61 - 75";
            }else if($value->NILAI_SKP>=51 && $value->NILAI_SKP<=60){
                $value->SKOR_KINERJA = 5;
                $value->RANGE_KINERJA = "51 - 60";
            }

            $value->SKOR_DISIPLIN = 5;

            if($value->TINGKAT_HUKUMAN != null){
                if($value->TINGKAT_HUKUMAN=='B'){
                    $value->SKOR_DISIPLIN = 1;
                }else if($value->TINGKAT_HUKUMAN=='S'){
                    $value->SKOR_DISIPLIN = 2;
                }else if($value->TINGKAT_HUKUMAN=='R'){
                    $value->SKOR_DISIPLIN = 3;
                }
            }else {
                $value->TINGKAT_HUKUMAN = 'Tidak Pernah';
            }

            $value->SKOR_TOT = $value->SKOR_DISIPLIN + $value->SKOR_KINERJA + $value->SKOR_KOMPETENSI + $value->SKOR_PENDIDIKAN;

            $value->Kategori = "Sangat Rendah";

            if($value->SKOR_TOT>=61 && $value->SKOR_TOT<=70){
                $value->Kategori = "Rendah";
            }else if($value->SKOR_TOT>=71 && $value->SKOR_TOT<=80){
                $value->Kategori = "Sedang";
            }else if($value->SKOR_TOT>=81 && $value->SKOR_TOT<=90){
                $value->Kategori = "Tinggi";
            }else if($value->SKOR_TOT>=91){
                $value->Kategori = "Sangat Tinggi";
            }

            array_push($retList,$value);

        }
        return $retList;
    }

    

    

    

    



}