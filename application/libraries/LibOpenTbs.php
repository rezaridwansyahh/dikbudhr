<?php 
include_once('opentbs/tbs_class.php'); // Load the TinyButStrong template engine
include_once('opentbs/tbs_plugin_opentbs.php'); // Load the OpenTBS plugin
class LibOpenTbs {
	function __construct(){
		$TBS = new clsTinyButStrong; // new instance of TBS
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN); // load the OpenTBS plugin
		$this->TBS = $TBS;
	}
}