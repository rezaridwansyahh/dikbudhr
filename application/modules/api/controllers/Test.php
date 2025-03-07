<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Test extends Admin_Controller
{
	public function test_cache(){
		echo "test cache<br>";
		$data = $this->cache->get("ajaj");
		if($data==null){
			echo "not found ".date("Y-m-d H:i:s")." <br>";
			$xar = array(1,2,3,4,5);
			$this->cache->write($xar,"ajaj");
		}
		else {
			echo "found ".json_encode($data);
		}
	}
}