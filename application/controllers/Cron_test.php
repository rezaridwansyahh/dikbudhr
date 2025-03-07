<?php defined("BASEPATH") OR exit("No direct script access allowed");

class Home extends MX_Controller
{
    public $file;
    public $path;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper("file");
        $this->load->helper("directory");

        $this->path = APPPATH . "cron_test" . DIRECTORY_SEPARATOR;
        $this->file = $this->path . "cron.txt";
    }

    public function index()
    {
        if ($this->is_cli_request())
        {
            $date = date("Y:m:d h:i:s");
            $data = $date . " --- Cron test from CI";

            $this->write_file($data);
        }
        else
        {
            exit;
        }
    }

    private function write_file($data)
    {
        write_file($this->file, $data . "\n", "a");
    }
}