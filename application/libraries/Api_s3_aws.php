<?php 
require "vendor/autoload.php";
class Api_s3_aws
{
    private $_CI;
	public function __construct(){
        $this->_CI = & get_instance();
        $this->_CI->load->model('jenis_arsip/jenis_arsip_model');
        $this->_CI->load->model('activities/activity_model');
	}

    public function createfile_nip(){
        $this->_CI->load->library('aws/awslib');
        $s3 = $this->connectAws();
        $bucket = "simpeg";
        $filename = "b926a2ad0dd1b4a7e1189f5148168d41.pdf";
        $sumber = trim($this->_CI->settings_lib->item('site.pathsk')).$filename;
        $return = $this->createFileAws($bucket,$sumber,'testdengannip.pdf');
        var_dump($return);
    }

    public function viewdoc($key){
        try {
            $arsip = $this->_CI->arsip_digital_model->find_by("ref",$key);
            if (!file_exists(trim($arsip->location).trim($arsip->name))) {
                die("File tidak ditemukan, lokasi ");
            }
            if ($arsip->name != "") {
                echo '<embed src="'.trim($this->_CI->settings_lib->item('site.urluploaded')).$arsip->NIP."/".$arsip->name.'" width="100%" height="1000" alt="pdf">';
            }else
                echo 'File blm upload';    
        } catch (\Throwable $th) {
            echo 'file error';
        }
        
    }
    public function viewdocAws($key,$bucket= "simpeg"){
        try {
            // $datadetil = $this->arsip_digital_model->find_by("ref",$id);
            if ($key){
                $file_url = $this->preSignUrl($bucket,$key);
                echo '<embed src="'.$file_url.'" width="100%" height="1000" alt="pdf">';
            } else {
                echo 'File tidak ditemukan';
            }
        } catch (\Throwable $th) {
            echo 'file error';
        }
        
    }
    public function preSignUrl($bucket,$key){
        $this->_CI->load->library('aws/awslib');
        $s3 = $this->connectAws();
        $command = $s3->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key'    => $key
        ]);
        // Create a pre-signed URL for a request with duration of 1 miniutes
        $presignedRequest = $s3->createPresignedRequest($command, '+1 minutes');
        // Get the actual presigned-url
        $presignedUrl =  (string)  $presignedRequest->getUri();
        return $presignedUrl;
    }
    function connectAwsDevLipi(){
        date_default_timezone_set("Asia/Jakarta");
        $endpoint = "https://dev-storage.lipi.go.id";
        $s3 = new Aws\S3\S3Client([
            "version" => "latest",
            "region" => "us-east-1",
            "endpoint" => $endpoint,
            "use_path_style_endpoint" => true,
            "credentials" => [
                "key" => "admin",
                "secret" => "admin2020",
            ],
        ]);
        return $s3;
    }
    function connectAwsDev(){
        date_default_timezone_set("Asia/Jakarta");
        $endpoint = "https://devminio.brin.go.id";
        $s3 = new Aws\S3\S3Client([
            "version" => "latest",
            "region" => "us-east-1",
            "endpoint" => $endpoint,
            "use_path_style_endpoint" => true,
            "credentials" => [
                "key" => "dev-simpeg",
                "secret" => "d3vm1n10",
            ],
        ]);
        return $s3;
    }
    function connectAws(){
        date_default_timezone_set("Asia/Jakarta");
        $endpoint =DOMAIN_MINIO;
        $s3 = new Aws\S3\S3Client([
            "version" => "latest",
            "region" => "us-east-1",
            "endpoint" => $endpoint,
            "use_path_style_endpoint" => true,
            "credentials" => [
                "key" => USER_MINIO,
                "secret" => PASS_MINIO,
            ],
        ]);
        return $s3;
    }
    function createBucket($s3,$bucket){
        $result = $s3->createBucket([
            "Bucket" => $bucket,
        ]);
        return $result;
    }
    function createFileAws($bucket,$SourceFile,$key,$type=""){
        $this->_CI->load->library('aws/awslib');
        $s3 = $this->connectAws();
        $type = $type !="" ? $type : "application/pdf";
        $result = $s3->putObject([
            "Bucket" => $bucket,
            "Key" => $key,
            'ACL'    => 'public-read', // make file 'public'
            // you can use relative
            "Body" => $SourceFile,
            // or absolute path
            // "SourceFile" => "/home/yoga/files.pdf",
            "ContentType" => $type,
        ]);
        // Print the body of the result by indexing into the result object.
        // return $result;
    }
    public function downloadFile($bucket,$key,$filename)
    {
        $this->_CI->load->helper('download');
        $this->_CI->load->library('aws/awslib');
        $s3 = $this->connectAws();
        $result = $s3->getObject([
            'Bucket' => $bucket,
            'Key'    => $key
        ]);
       // header('Content-Disposition: attachment; filename=' . $filename);    
        //echo $result['Body'];
        header("Content-type:application/pdf");
        header("Content-Disposition:attachment;filename=" . $filename);
        //readfile($result['Body']);
	echo $result['Body'];
	exit(); 
    }
    public function deleteObject($bucket,$key){
        try {
            $this->_CI->load->library('aws/awslib');
            $s3 = $this->connectAws();
            $result = $s3->deleteObject([
                "Bucket" => $bucket,
                "Key" => $key,
            ]);
            // Print the body of the result by indexing into the result object.
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
        
    }

    public function insertArsip($nip,$no_sk,$id_jenis_arsip,$keterangan,$file_content,$filename, $is_valid = 1)
    {
        $this->_CI->load->model('arsip_digital/arsip_digital_model');
        $location       = trim($this->_CI->settings_lib->item('site.pathuploaded')). '/';
        if (!is_dir($location)) {
            mkdir($location, 0777, true);
        }
        $array                      = explode('.', $filename);
        $extension                  = end($array);
        $name = md5(uniqid()).".".$extension;
        $arsip = $this->_CI->arsip_digital_model->find_by(array('sk_number' => $no_sk, 'NIP' => $nip));
        $data = array();
        $data['NIP']                = $nip;
        $data['ID_JENIS_ARSIP']     = $id_jenis_arsip;
        $data['sk_number']          = $no_sk;
        $data['location']           = $filename;
        $data['name']               = $name;
        $data['EXTENSION_FILE']     = isset($extension) ? $extension : null;
        $data['KETERANGAN']         = $keterangan;
        $data['ISVALID']           = $is_valid?1:0;
        $this->_CI->arsip_digital_model->skip_validation(true);
        if($no_sk && isset($arsip) && !empty($arsip->ID)){
            $data['UPDATED_DATE']    = date("Y-m-d");
            $data['UPDATED_BY']    = $this->current_user;
            $this->_CI->arsip_digital_model->update($arsip->ID, $data);
            $this->_CI->activity_model->log_activity(1, 'Update Dokumen : ' . $arsip->ID . ' : ' . $this->_CI->input->ip_address(), 'Home');
            return $arsip->ID;
        }
        else{
            $data['CREATED_DATE']    = date("Y-m-d");
            $data['CREATED_BY']    = $this->current_user;
            $id = $this->_CI->arsip_digital_model->insert($data);
            $this->_CI->activity_model->log_activity(1, 'Upload Dokumen : ' . $id . ' : ' . $this->_CI->input->ip_address(), 'Home');
        }
        return $id; 
                
    }
}
