<?php
//require_once(APPPATH.'libraries/tcpdf/examples/tcpdf_include.php');
require_once(APPPATH.'libraries/tcpdf/tcpdf.php');

class Tcpdf_lib{
    public function createTCPDFObject(){
        return new TCPDF();
    }
}