<?php 
include_once('tbs_plugin_opentbs_1.9.11/tbs_class.php'); // Load the TinyButStrong template engine
include_once('tbs_plugin_opentbs_1.9.11/tbs_plugin_opentbs.php'); // Load the OpenTBS plugin

class Template_doc_lib {
	public function gen_xls($template){
		$TBS = new clsTinyButStrong; // new instance of TBS
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN); // load the OpenTBS plugin
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
		return $TBS;
	}
}