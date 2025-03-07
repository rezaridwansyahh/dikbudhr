<?php 

class Manage_unitkerja extends Admin_Controller {
    protected $permissionCreate = 'Unitkerja.Masters.Create';
    protected $permissionDelete = 'Unitkerja.Masters.Delete';
    protected $permissionEdit   = 'Unitkerja.Masters.Edit';
    protected $permissionView   = 'Unitkerja.Masters.View';
    protected $permissionFiltersatker   = 'Pegawai.Kepegawaian.Filtersatker';
    protected $permissionEselon1   = 'Pegawai.Kepegawaian.permissionEselon1';
    public $UNOR_ID = null;
    public $aunor = array();
    public function __construct()
    {
        parent::__construct();
        $this->load->model("pegawai/unitkerja_model");
        $this->load->model('pegawai/pegawai_model');
        $this->load->model("pegawai/jenis_satker_model");
        $this->load->model("pegawai/peraturan_otk_model");
        $this->load->helper('dikbud');
        // filter untuk role executive
        if($this->auth->has_permission($this->permissionFiltersatker)){
            $this->UNOR_ID = $this->pegawai_model->getunor_induk($this->auth->username());
        }
        $jenis_satkers = $this->jenis_satker_model->find_all();
        // $jenis_satkers = get_list_jenis_satker();
        Template::set("jenis_satkers",$jenis_satkers); 
        $peraturan_otks = $this->peraturan_otk_model->find_all();
        Template::set("peraturan_otks",$peraturan_otks); 
    }
    public function index(){
        Template::set('toolbar_title', "Manage Unit Kerja.");
        Template::render();
    }
    public function tabel(){
        Template::set('toolbar_title', "Manage Unit Kerja.");
        Template::render();
    }
    public function pilihunitkerja(){
        Template::set('toolbar_title', "Pilih Unit Kerja.");
        Template::render();
    }

    public function get_children($parent=null){
        if($parent==null){
            return $this->db->query('
            select parent."ID",parent."NAMA_UNOR","IS_SATKER",(select count(*) from unitkerja children where children."DIATASAN_ID" = parent."ID" and children."EXPIRED_DATE" is null )as num_child
            from unitkerja parent
            where 
            deleted is null 
            AND (parent."DIATASAN_ID"   is null OR parent."DIATASAN_ID" = \'\') 
            and parent."EXPIRED_DATE" is null 
            ORDER BY parent."ORDER" asc 
            ')->result();
        }
        else {
            return $this->db->query('
                        select parent."ID",parent."NAMA_UNOR","IS_SATKER",(select count(*) from unitkerja children where children."DIATASAN_ID" = parent."ID" and children."EXPIRED_DATE" is null)as num_child
                        from unitkerja parent
                        where 
                        deleted is null 
                        AND parent."DIATASAN_ID"  = ? and parent."EXPIRED_DATE" is null 
                        ORDER BY parent."ORDER" asc 
            ',array($parent))->result();
        }
    }
    public function get_children_cache($parent=null){
        // $this->create_cache_unor_tree();
        if($parent==null){
            return $this->db->query('
            select parent."ID",parent."NAMA_UNOR","IS_SATKER",(select count(*) from unitkerja children where children."DIATASAN_ID" = parent."ID" and children."EXPIRED_DATE" is null )as num_child
            from unitkerja parent
            where 
            deleted is null 
            AND (parent."DIATASAN_ID"   is null OR parent."DIATASAN_ID" = \'\') 
            and parent."EXPIRED_DATE" is null 
            ORDER BY parent."ORDER" asc 
            ')->result();
        }
        else {
            $aunitkerja = $this->cache->get('json_unor_tree');
            // print_r($aunitkerja);
            if($aunitkerja==null){
                return $this->db->query('
                        select parent."ID",parent."NAMA_UNOR","IS_SATKER",(select count(*) from unitkerja children where children."DIATASAN_ID" = parent."ID" and children."EXPIRED_DATE" is null)as num_child
                        from unitkerja parent
                        where 
                        deleted is null 
                        AND parent."DIATASAN_ID"  = ? and parent."EXPIRED_DATE" is null 
                        ORDER BY parent."ORDER" asc 
            ',array($parent))->result();
            }else{
                return $aunitkerja[$parent];
            }
        }
    }
    public function moveNode(){
        $_position = $this->input->post("_position");
        $_currNodeId = $this->input->post("_currNodeId");
        $_parentNodeId = $this->input->post("_parentNodeId");
        $_oldParentId = $this->input->post("_oldParentId");
        $_nx = $this->input->post("_nx");
        $currNodeId = str_replace("node_","",$_currNodeId);
        $parentNodeId = str_replace("node_","",$_parentNodeId);
        $oldParentId = str_replace("node_","",$_oldParentId);

        $output = array(
            "success"=>false,
            "msg"=>'Transaksi tidak dapat dilanjutkan');
        if($currNodeId == $parentNodeId){
           echo json_encode($output);
           exit;
        }   
        $index = 1;
        $xyz = array();
        foreach($_nx as $nx){
            $nxid = str_replace("node_","",$nx);
            $xyz[] =  array(
                        'ORDER'=>$index,
                        'ID'=> $nxid
                );
            $index++;
        }
        

        $this->db->update_batch("hris.unitkerja",array_values($xyz),"ID");   
         $output = array(
            "success"=>true,
            "msg"=>'Transaksi berhasil'); 
        $this->db->where("ID",$currNodeId);
        $this->db->set("DIATASAN_ID",$parentNodeId);
        $this->db->update("hris.unitkerja");    
        echo json_encode($output);
        exit;     
       
       $siblings = $this->get_children($parentNodeId);
       $xyz = array();
       $index=1;
       foreach($siblings as $row){
           
            if($row->ID == $currNodeId){
                
            }
            else {
                $xyz[$row->ID] = array(
                        'ORDER'=>$index,
                        'ID'=>$row->ID
                );
            }
            $index++;
       }
       $tempxyz = $xyz;
       if($_position==0){
           $xyz = array_merge(array(array('ORDER'=>0,'ID'=>$currNodeId)),$xyz);
       }
       else {
           $index=1;
           $temp = array();
           $input = true;
           foreach($xyz as $row){
               $temp[] = $row;
                if($row['ORDER']>=$_position && $input){
                    $temp[] = array('ORDER'=>$_position,'ID'=>$currNodeId);
                    $input = false;
                }
                $index++;
           }
           $xyz = $temp;
       }
       $this->db->update_batch("hris.unitkerja",array_values($xyz),"ID");
       $output = array(
            "success"=>true,
            'xyz'=>$xyz,
            'tempxyz'=> $tempxyz,
            "msg"=>'Transaksi berhasil');
       echo json_encode($output);
        exit;
    }
    public function ajax_tree(){
        $parent = $this->input->get("parent");
        $data = array();
        $default_icon = "fa fa-folder icon-lg icon-state-success";
        if ($parent == "#") {
            $children = $this->get_children(null);
            foreach($children as $record){
                if($record->IS_SATKER) {
                    $icon = "fa fa-cogs icon-lg icon-state-warning";
                }
                else {
                    $icon = $default_icon;
                }
                $data [] =  array(
                    "x"=>$parent,
                    "id" => "node_" .$record->ID,  
                    "text" => $record->NAMA_UNOR,  
                    "icon" => $icon ,
                    "children" => $record->num_child>0, 
                    "state"=> array(
                           "opened"   => boolean  // is the node open 
                    ),
                    "type" => "root"
                );
            }
        } else {
            $splitnode = explode("_",$parent);
            $children = $this->get_children($splitnode[1]);
            foreach($children as $record){
                if($record->IS_SATKER) {
                    $icon = "fa fa-cogs icon-lg icon-state-warning";
                }
                else {
                    $icon = $default_icon;
                }
                $data [] =  array(
                    "xy"=>$splitnode[1],
                    "id" => "node_" .$record->ID,  
                    "text" => $record->NAMA_UNOR,  
                    "icon" => $icon ,
                    "children" => $record->num_child>0
                );
            }
        }

        header('Content-type: text/json');
        header('Content-type: application/json');
        echo json_encode($data);
    }
    public function ajax_tree_user(){
        $parent = $this->input->get("parent");
        $data = array();
        $default_icon = "fa fa-folder icon-lg icon-state-success";
        if ($parent == "#") {
            $children = $this->get_children_cache(null);
            foreach($children as $record){
                if($record->IS_SATKER) {
                    $icon = "fa fa-cogs icon-lg icon-state-warning";
                }
                else {
                    $icon = $default_icon;
                }
                $data [] =  array(
                    "x"=>$parent,
                    "id" => "node_" .$record->ID,  
                    "text" => $record->NAMA_UNOR,  
                    "icon" => $icon ,
                    "children" => $record->num_child>0, 
                    "state"=> array(
                           "opened"   => boolean  // is the node open 
                    ),
                    "type" => "root"
                );
            }
        } else {
            $splitnode = explode("_",$parent);
            $children = $this->get_children_cache($splitnode[1]);
            foreach($children as $record){
                if($record->IS_SATKER) {
                    $icon = "fa fa-cogs icon-lg icon-state-warning";
                }
                else {
                    $icon = $default_icon;
                }
                $data [] =  array(
                    "xy"=>$splitnode[1],
                    "id" => "node_" .$record->ID,  
                    "text" => $record->NAMA_UNOR,  
                    "icon" => $icon ,
                    "children" => $record->num_child>0
                );
            }
        }

        header('Content-type: text/json');
        header('Content-type: application/json');
        echo json_encode($data);
    }
    
    public function createNew($parent_id=''){
        $parent_id = str_replace("node_","",$parent_id);
        Template::set("PARENT_ID",$parent_id);
        
        $this->unitkerja_model->where("ID",$parent_id);
        $data_parent_unor =$this->unitkerja_model->find_first_row();
        
        Template::set("selectedParentUnor",$data_parent_unor);  
        Template::set_view("manage_unitkerja/crud.php");
        Template::set('toolbar_title', "CRUD Unit Kerja");

        Template::render();
    }
    public function delete($node_id){
        $satker_id = str_replace("node_","",$node_id);
        //echo var_dump($satker_id);
        $this->unitkerja_model->delete_custom($satker_id);
    }
    public function edit($node_id){
        $satker_id = str_replace("node_","",$node_id);
        $this->unitkerja_model->where("ID",$satker_id);
        $data_unor =$this->unitkerja_model->find_first_row();
        
        $this->unitkerja_model->where("ID",$data_unor->DIATASAN_ID);
        $data_parent_unor =$this->unitkerja_model->find_first_row();
        
        /*ADD BY BANA*/
        $this->pegawai_model->where("PNS_ID",$data_unor->PEMIMPIN_PNS_ID);
        $selectedAtasanLangsung = $this->pegawai_model->find_first_row();
        Template::set('selectedAtasanLangsung',$selectedAtasanLangsung );

    $selectedUnorInduk = $data_unor->UNOR_INDUK;
        $this->unitkerja_model->where("ID",$selectedUnorInduk);
        $data_unor_induk=$this->unitkerja_model->find_first_row();
        Template::set('selectedUnorInduk',$data_unor_induk);

        /*OKOKOKOOKO*/
        
        Template::set("PARENT_ID",$data_unor->DIATASAN_ID); 
        Template::set("selectedParentUnor",$data_parent_unor);  
        Template::set("ID",$data_unor->ID);
        Template::set("data",$data_unor);
        //Template::set("nama_pejabat",$pejabat);
        Template::set_view("manage_unitkerja/crud.php");
        Template::set('toolbar_title', "CRUD Unit Kerja");

        Template::render();
    }
    public function save(){
        $this->cache->delete("unors");
        $this->cache->delete_group("unors");
        $this->cache->delete("aunor");
        $this->cache->delete_group("aunor");
        $this->form_validation->set_rules($this->unitkerja_model->get_validation_rules());

        if ($this->form_validation->run() === false) {
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error
                </h4>
                ".validation_errors()."
            </div>
            ";
            echo json_encode($response);
            exit();
        }
        $id = $this->input->post("ID");
         $PARENT_ID = $this->input->post("PARENT_ID");
        $this->pegawai_model->where("PNS_ID",$this->input->post("PEMIMPIN_PNS_ID"));
        $selectedAtasanLangsung = $this->pegawai_model->find_first_row();
        $data = $this->unitkerja_model->prep_data($this->input->post());

        $data['IS_SATKER'] = isset($data['IS_SATKER']) ? 1: 0;
        $data['DIATASAN_ID'] = $PARENT_ID;
        $data['EXPIRED_DATE']=isset($data['EXPIRED_DATE']) && $data['EXPIRED_DATE'] != ''  ?$data['EXPIRED_DATE'] : null;
        $rowData = $this->unitkerja_model->find($id);
        if($selectedAtasanLangsung){
            $data['NAMA_PEJABAT']=$selectedAtasanLangsung->NAMA;
        }
        if($rowData){
            $this->unitkerja_model->update($id,$data);
        }
        else {
            $data['ID']=$id;
            $this->unitkerja_model->insert($data);
        }
        
        $response = array();
        $response ['success']= true;
        $response ['msg']= "Transaksi berhasil";
        echo json_encode($response);   
    }
    public function test_path(){
        $this->load->model('pegawai/unitkerja_model');
        echo $this->unitkerja_model->get_parent_path(103,true,false);    
    }
    public function test_parent(){
        $this->load->model('pegawai/unitkerja_model');
        echo json_encode($this->unitkerja_model->get_satker(103));    
        echo 123;
    }   
    public function ajax(){
        $json = array();
        $limit = 10;
        $page = $this->input->get('page') ? $this->input->get('page') : "1";
        $q= $this->input->get('term');
        // $start = ($page-1)*$limit;
        
        if(!empty($q)){
            $json = $this->data_model($q,$page,$limit,TRUE);
        }
        echo json_encode($json);
    }
    public function ajaxkodeinternal(){
        $this->auth->restrict($this->permissionUpdateMandiri);
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        
        $json = array();
        $limit = 10;
        $page = $this->input->get('page') ? $this->input->get('page') : "1";
        $q= $this->input->get('term');
        $start = ($page-1)*$limit;
        if(!empty($q)){
            $json = $this->data_modelinternal($q,$start,$limit);
        }
        echo json_encode($json);
    }
    public function ajaxall(){
        $json = array();
        $limit = 10;
        $page = $this->input->get('page') ? $this->input->get('page') : "1";
        $q= $this->input->get('term');
        // $start = ($page-1)*$limit;
        
        if(!empty($q)){
            $json = $this->data_model($q,$page,$limit,TRUE);
            //$json = $this->data_model($q,$start,$limit,TRUE)
        }
        echo json_encode($json);
    }
    private function data_model($key,$page,$limit,$filter_expired_date=TRUE){
          // update
          // ASLI
            // $this->db->start_cache();
            
            // $this->db->like('lower("NAMA_UNOR")', strtolower($key));
            // $this->db->from("hris.unitkerja");
    

        
            
            
            // $this->db->stop_cache();
            // $total = $this->db->get()->num_rows();
            // $this->db->select('ID as id,NAMA_UNOR as text');

            // $this->db->limit($limit,$start);
            // $data = $this->db->get()->result();
            //#ASLI
            
            //modified by bana
            $filter_query="";
            $start = ($page-1)*$limit;
            if($filter_expired_date) {
                $filter_query = " AND r.\"EXPIRED_DATE\" IS NULL";
            }
            $sql = "with recursive r as (
                SELECT \"ID\",\"NAMA_UNOR\"::text as NAMA_UNOR,\"EXPIRED_DATE\" from hris.unitkerja WHERE \"DIATASAN_ID\" = 'A8ACA7397AEB3912E040640A040269BB'
                UNION ALL
                SELECT a.\"ID\",a.\"NAMA_UNOR\" || ' - ' || r.NAMA_UNOR::text,a.\"EXPIRED_DATE\" FROM hris.unitkerja a JOIN r ON a.\"DIATASAN_ID\" = r.\"ID\"
            )
            SELECT r.\"ID\" as id,r.NAMA_UNOR as text FROM r WHERE lower(r.NAMA_UNOR) ILIKE ? $filter_query LIMIT ? OFFSET ?";
           

            $key1 = "%".$key."%";
            //var_dump($key1);
            $query = $this->db->query($sql, array(strtolower($key1), $limit, $start));
            $data = $query->result();
            // $total = $query->num_rows();
            //var_dump($sql);
            $sqlCount = "with recursive r as (
                SELECT \"ID\",\"NAMA_UNOR\"::text as NAMA_UNOR,\"EXPIRED_DATE\" from hris.unitkerja WHERE \"DIATASAN_ID\" = 'A8ACA7397AEB3912E040640A040269BB'
                UNION ALL
                SELECT a.\"ID\",a.\"NAMA_UNOR\" || ' - ' || r.NAMA_UNOR::text,a.\"EXPIRED_DATE\" FROM hris.unitkerja a JOIN r ON a.\"DIATASAN_ID\" = r.\"ID\"
            )
            SELECT *  FROM r WHERE lower(r.NAMA_UNOR) ILIKE ? $filter_query";
            $queryCount = $this->db->query($sqlCount, array(strtolower($key1)));    
            $total = $queryCount->num_rows();
            // var_dump($total);
            // $total = $queryCount->result();
            
            $totalPage = floor($total/$limit);
            // echo $start;
            $morePages = $page != $totalPage;
            $o = array(
            "results" => $data,
                "pagination" => array(
                    "more" =>$morePages,
                )
            );   
            $this->db->flush_cache();
            return $o;    }
    private function data_modelinternal($key,$start,$limit){
          // update
             
          $where_clause = "";
            if($this->UNOR_ID){
                $where_clause = 'AND ("ESELON_1" = \''.$this->UNOR_ID.'\' OR "ESELON_2" = \''.$this->UNOR_ID.'\' OR "ESELON_3" = \''.$this->UNOR_ID.'\' OR "ESELON_4" = \''.$this->UNOR_ID.'\')' ;
            }
            $this->db->start_cache();
            $this->db->like('lower("NAMA_UNOR")', strtolower($key));
            $this->db->from("hris.unitkerja");
            $this->db->where("EXPIRED_DATE IS NULL");
            $this->db->where('"ID" in (select "UNOR_INDUK" from hris.unitkerja) '.$where_clause);
            $this->db->stop_cache();
            $total = $this->db->get()->num_rows();
            $this->db->select('ID as id,NAMA_UNOR as text');
            //$this->db->where('"IS_SATKER" =1 '.$where_clause);
            $this->db->where('"ID" in (select "UNOR_INDUK" from hris.unitkerja) '.$where_clause);
                
            $this->db->limit($limit,$start);

            $data = $this->db->get()->result();

            $endCount = $start + $limit;
            $morePages = $endCount > $total;
            $o = array(
            "results" => $data,
                "pagination" => array(
                    "more" =>$morePages,
                )
            );   
            $this->db->flush_cache();
            return $o;
    }
    public function ajax_unit_list_internal(){
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai/ppnpn');
        }
        $length = 10;
        $term = $this->input->get('term');
        $page = $this->input->get('page');
        $this->db->flush_cache();
        //Jika ada role executive ?
        $UNOR_ID = '';
        $CI = &get_instance();
        
        if($this->auth->has_permission($this->permissionFiltersatker)){
            $UNOR_ID = $this->pegawai_model->getunor_id($CI->auth->username());
        }
        if($this->auth->has_permission($this->permissionEselon1)){
            $UNOR_ID = $this->pegawai_model->getunor_eselon1($this->auth->username());
        }
        $data = $this->unitkerja_model->find_unit($term,$UNOR_ID);

        $output = array();
        $output['results'] = array();
        foreach($data as $row){
            $output['results'] [] = array(
                'id'=>$row->KODE_INTERNAL,
                'text'=>$row->NAMA_UNOR_FULL
            );
        }
        $output['pagination'] = array("more"=>false);
        
        echo json_encode($output);
    }
    public function ajax_unit_list(){
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai/ppnpn');
        }
        $length = 10;
        $term = $this->input->get('term');
        $page = $this->input->get('page');
        $this->db->flush_cache();
        //Jika ada role executive ?
        $UNOR_ID = '';
        $CI = &get_instance();
        
        if($this->auth->has_permission($this->permissionFiltersatker)){
            $UNOR_ID = $this->pegawai_model->getunor_id($CI->auth->username());
        }
        if($this->auth->has_permission($this->permissionEselon1)){
            $UNOR_ID = $this->pegawai_model->getunor_eselon1($this->auth->username());
        }
        $data = $this->unitkerja_model->find_unit($term,$UNOR_ID);

        $output = array();
        $output['results'] = array();
        foreach($data as $row){
            $output['results'] [] = array(
                'id'=>$row->ID,
                'text'=>$row->NAMA_UNOR_FULL
            );
        }
        $output['pagination'] = array("more"=>false);
        
        echo json_encode($output);
    }
    private function data_modelall($key,$start,$limit,$filter_expired_date=FALSE){

            $filter_query="";
            if($filter_expired_date) {
                $filter_query = "AND a.\"EXPIRED_DATE\" IS NULL";
            }

            $sql = "with recursive r as (
                select \"ID\",\"NAMA_UNOR\"::text as NAMA_UNOR,\"EXPIRED_DATE\" from hris.unitkerja WHERE \"ID\" = 'A8ACA7397AEB3912E040640A040269BB'
                UNION ALL
                SELECT a.\"ID\",a.\"NAMA_UNOR\" || ' - ' || r.NAMA_UNOR::text,a.\"EXPIRED_DATE\" FROM hris.unitkerja a JOIN r ON a.\"DIATASAN_ID\" = r.\"ID\"
            )
            SELECT r.\"ID\" as id,r.NAMA_UNOR as text FROM r WHERE lower(r.NAMA_UNOR) ILIKE ? ".$filter_query." LIMIT ? OFFSET ?";
            
            //$this->db->start_cache();
            //$this->db->like('lower("NAMA_UNOR")', $key);
            //if($filter_expired_date) $this->db->where('"EXPIRED_DATE"',NULL);
            //$this->db->from("hris.unitkerja");
            //$total = $this->db->get()->num_rows();
            //$this->db->select('ID as id,"NAMA_UNOR" as text');
            //$this->db->limit($limit,$start);
            $key1 = "%".$key."%";
            $query = $this->db->query($sql, array(strtolower($key1), $limit, $start));
            $data = $query->result();
            $total = $query->num_rows();    

            //$data = $this->db->query($sql, array(strtolower($key1), $limit, $start))->result();
            //$total = $this->db->get()->num_rows();
            //$this->db->stop_cache();    
            //echo var_dump($this->db->last_query());
            $endCount = $start + $limit;
            $morePages = $endCount > $total;
            $o = array(
            "results" => $data,
                "pagination" => array(
                    "more" =>$morePages,
                )
            );   
            $this->db->flush_cache();
            return $o;
    }
    public function getbysatker()
    {
        $satker = $this->input->get('satker');
        $json = array(); 
        $records = $this->unitkerja_model->find_all_active($satker);
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) :
                $json['id'][] = $record->ID;
                $json['nama'][] = $record->NAMA_UNOR;
            endforeach;
        endif;
        echo json_encode($json);
        die();
    }
    public function getbysatkerID()
    {
        $this->auth->restrict($this->permissionUpdateMandiri);
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $satker = $this->input->get('satker');
        $json = array(); 
        $records = $this->unitkerja_model->find_all_active($satker);
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) :
                $json['id'][] = $record->KODE_INTERNAL;
                $json['nama'][] = $record->NAMA_UNOR;
            endforeach;
        endif;
        echo json_encode($json);
        die();
    }
    public function getnamajabatan()
    {
        $this->auth->restrict($this->permissionUpdateMandiri);
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $unor = $this->input->get('unor');
        $json = array(); 
        $records = $this->unitkerja_model->findnamajabatan($unor);
        echo isset($records->NAMA_JABATAN) ? $records->NAMA_JABATAN : "";
    }
    public function getnamajabatan_byid()
    {
        //$this->auth->restrict($this->permissionUpdateMandiri);
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
        $ID_UNOR = $this->input->get('unor');
        $json = array(); 
        $records = $this->unitkerja_model->findnamajabatan_by_id($ID_UNOR);
        echo isset($records->NAMA_JABATAN) ? $records->NAMA_JABATAN : "";
    }
    public function downloadexcell()
    {
        $array_unor = $this->getcache_unor();
        $this->load->library('Excel');
        $a_peraturan = $this->peraturan_otk_model->get_array();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load(trim($this->settings_lib->item('site.pathuploaded')).'template.xls');
        $no = 2;    
        $row = 1;
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,"No");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,1,"ID");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,1,"NAMA");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,1,"Jabatan");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,1,"Pejabat ID");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,1,"Nama Pejabat");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,1,"Jenis");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,1,"Peraturan");
        foreach ($array_unor as $record) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$no,$no);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$no,$record->ID);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$no,$record->NAMA);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$no,$record->NAMA_JABATAN);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$no,$record->PEMIMPIN_PNS_ID);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$no,$record->GELAR_DEPAN." ".$record->PEJABAT_NAMA." ".$record->GELAR_BELAKANG);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$no,$record->JENIS_SATKER);
            $nomor_peraturan = isset($a_peraturan[$record->PERATURAN]) ? $a_peraturan[$record->PERATURAN] : "";
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$no,$nomor_peraturan);
            $no++;
        }
        $filename = "unitkerja".mt_rand(1,100000).'.xls'; //just some random filename
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
        //$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
        $objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
        exit; //done.. exiting!
    }
    public function getdata(){
        $this->auth->restrict($this->permissionView);
        if (!$this->input->is_ajax_request()) {
            Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/pegawai/manage_unitkerja/tabel');
        }
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        
        $length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
        $start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

        $selectedUnors = array();
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            
        }

        $this->db->start_cache();
        $non_aktif = false;
        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        $advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
        if($advanced_search_filters){
            $filters = array();
            foreach($advanced_search_filters as  $filter){
                $filters[$filter['name']] = $filter["value"];
            }
            if($filters['waktu']){
                $this->db->group_start();
                $this->db->where('unitkerja."WAKTU"',$filters['waktu']);    
                $this->db->group_end();
            }
            if($filters['nama_unit']){
                $this->db->like('upper(unitkerja."NAMA_UNOR")',strtoupper(trim($filters['nama_unit'])),"BOTH");    
            }
            if($filters['jenis']){
                $this->db->where('upper(unitkerja."JENIS_SATKER")',strtoupper(trim($filters['jenis'])));    
            }
            if($filters['status_active']){
                if($filters['status_active'] == "1")
                    $this->db->where('unitkerja."EXPIRED_DATE" is null');    
                if($filters['status_active'] == "2"){
                    $this->db->where('unitkerja."EXPIRED_DATE" is not null');
                    $non_aktif = true;
                }
            }
            
        }
        $this->db->stop_cache();
        $output=array();
        $output['draw']=$draw;
        $total= $this->unitkerja_model->count_all_pimpinan("",$non_aktif);
        $orders = $this->input->post('order');
        $this->unitkerja_model->order_by("ID","ASC");
        foreach($orders as $order){
            if($order['column']==3){
                $this->unitkerja_model->order_by("NAMA",$order['dir']);
            }
            if($order['column']==4){
                $this->unitkerja_model->order_by("JENIS_SATKER",$order['dir']);
            }
            if($order['column']==5){
                $this->unitkerja_model->order_by("EXPIRED_DATE",$order['dir']);
            }
        }
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();
        
        $this->unitkerja_model->limit($length,$start);
        $records=$this->unitkerja_model->find_all_pimpinan("",$non_aktif);
        $this->db->flush_cache();
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NAMA_UNOR;
                $row []  = $record->WAKTU;
                $row []  = $record->NAMA;
                $row []  = $record->JENIS_SATKER;
                $row []  = $record->EXPIRED_DATE;
                $btn_actions = array();
                 
                
                if($this->auth->has_permission($this->permissionEdit)){
                $btn_actions  [] = "<a href='".base_url()."pegawai/manage_unitkerja/edit/".$record->ID."' class='btn btn-sm btn-warning show-modal' title='Update data'><i class='glyphicon glyphicon-edit'></i> </a>";
                }
                 
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        
    }
    function getcache_unor($UNOR_ID = ""){
        $aunitkerja = $this->cache->get('aunor');
        if ($aunitkerja == null) {
            $records = $this->db->query('
                        select parent."ID",parent."NAMA_UNOR","IS_SATKER","DIATASAN_ID","ESELON_4","ESELON_3","ESELON_2","ESELON_1","PEJABAT_NAMA","PEMIMPIN_PNS_ID",
                        "NIP_BARU","GELAR_DEPAN","GELAR_BELAKANG","NAMA_JABATAN","JENIS_SATKER","PERATURAN"
                        from vw_unit_list_pejabat parent
                        where 
                        deleted is null 
                        and parent."EXPIRED_DATE" is null 
                        ORDER BY "DIATASAN_ID" asc, parent."ORDER" asc')->result();
            $json = array(); 
            if(isset($records) && is_array($records) && count($records)):
                foreach ($records as $record) :
                    $json[$record->DIATASAN_ID][] = $record;
                endforeach;
            endif;
            $aunitkerja =  $this->createchild("A8ACA7397AEB3912E040640A040269BB",$json);
            $this->cache->write($aunitkerja, 'aunor',3600);
            $this->cache->write($json, 'json_unor',3600);
        } 
        return $aunitkerja;
    }
    function create_cache_unor_tree(){
        $aunitkerja = $this->cache->get('aunor_tree');
        if ($aunitkerja == null) {
            $records = $this->db->query('
                        select parent."ID",parent."NAMA_UNOR","IS_SATKER",(select count(*) from unitkerja children where children."DIATASAN_ID" = parent."ID" and children."EXPIRED_DATE" is null)as num_child
                        from unitkerja parent
                        where 
                        deleted is null 
                        AND parent."DIATASAN_ID"  is not null and parent."EXPIRED_DATE" is null 
                        ORDER BY "DIATASAN_ID" asc, parent."ORDER" asc')->result();
            $json = array(); 
            if(isset($records) && is_array($records) && count($records)):
                foreach ($records as $record) :
                    $json[$record->DIATASAN_ID][] = $record;
                endforeach;
            endif;
            $aunitkerja =  $this->createchild("A8ACA7397AEB3912E040640A040269BB",$json);
            $this->cache->write($aunitkerja, 'aunor_tree',3600);
            $this->cache->write($json, 'json_unor_tree',3600);
        } 
        return $aunitkerja;
    }
    function createchild($parent_id,$json){
        $return = null;
        if(count($json[$parent_id]) > 0){
            for($i=0;$i<count($json[$parent_id]);$i++){
                if($json[$parent_id][$i]->ESELON_4 != "") {
                    $json[$parent_id][$i]->NAMA = "------------".$json[$parent_id][$i]->NAMA_UNOR;
                    $return = $json[$parent_id][$i];
                 }else if($json[$parent_id][$i]->ESELON_3 != "") {
                    $json[$parent_id][$i]->NAMA = "--------".$json[$parent_id][$i]->NAMA_UNOR;
                    $return = $json[$parent_id][$i];
                 }
                 else if($json[$parent_id][$i]->ESELON_2 != "") {
                    $json[$parent_id][$i]->NAMA = "---".$json[$parent_id][$i]->NAMA_UNOR;
                    $return = $json[$parent_id][$i];
                 }
                 else IF($json[$parent_id][$i]->ESELON_1 != "") {
                    $json[$parent_id][$i]->NAMA = $json[$parent_id][$i]->NAMA_UNOR;
                    $return = $json[$parent_id][$i];
                 }else{
                    $json[$parent_id][$i]->NAMA = $json[$parent_id][$i]->NAMA_UNOR;
                    $return = $json[$parent_id][$i];
                 }
                array_push($this->aunor,$return);
                $this->createchild($json[$parent_id][$i]->ID,$json);
            }
         }
         return $this->aunor;
    }
}