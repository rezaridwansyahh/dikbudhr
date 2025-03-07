<?php defined('BASEPATH') || exit('No direct script access allowed');


class Dokumen extends MX_Controller
{
    public function __construct()
	{
		parent::__construct();
    }

    public function index()
	{
		Template::render();
	}//end index()

    public function validasi()
	{
		Template::render();
	}//end index()



}