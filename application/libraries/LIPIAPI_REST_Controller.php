<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Rest Controller
 * A fully RESTful server implementation for CodeIgniter using one library, one config file and one controller.
 *
 * @package         CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 * @version         3.0.0
 */
require "REST_Controller.php";
class LIPIAPI_REST_Controller extends REST_Controller {
	
    public function getApplicationKey(){
        $api_key_variable = $this->config->item('rest_key_name');
        
         // Work out the name of the SERVER entry based on config
        $key_name = 'HTTP_' . strtoupper(str_replace('-', '_', $api_key_variable));
        if (($key = isset($this->_args[$api_key_variable]) ? $this->_args[$api_key_variable] : $this->input->server($key_name)))
        return $this->rest->db->where($this->config->item('rest_key_column'), $key)->get($this->config->item('rest_keys_table'))->row();
    }
    
    /**
     * Check to see if the API key has access to the controller and methods
     *
     * @access protected
     * @return bool TRUE the API key has access; otherwise, FALSE
     */
    protected function _check_access()
    {
        // If we don't want to check access, just return TRUE
        if ($this->config->item('rest_enable_access') === FALSE)
        {
            return TRUE;
        }

        // Fetch controller based on path and controller name
        $controller = implode(
            '/', [
            $this->router->module,
            $this->router->class,
			$this->router->method,
        ]);
        // Remove any double slashes for safety
        $controller = str_replace('//', '/', $controller);
        
        //
        $data_controller = $this->db->from("webservice.api_controllers")->where("url",$controller)->get()->first_row();
        $data_application =  $this->db->from("webservice.api_keys")->where("key",$this->rest->key)->get()->first_row();

        if($data_controller && $data_application){
             // Query the access table and get the number of results
            return $this->rest->db
                ->group_start()
                ->where('app_id', $data_application->id)
                ->where('controller_id', $data_controller->id)
                ->group_end()
                ->or_group_start()
                ->where('app_id is null')
                ->where('controller_id', $data_controller->id)
                ->group_end()
                ->get($this->config->item('rest_access_table'))
                ->num_rows() > 0;
        }
        else {
            return false;
        }

       
    }
}
