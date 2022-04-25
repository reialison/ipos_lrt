<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Help extends CI_Controller {
	
	var $data = array();

	public function __construct()
    {
        parent::__construct();
	    $this->load->model('dine/cashier_model');
	   	$this->load->helper('core/help_helper');
        $this->load->helper('dine/cashier_helper');
            $this->load->helper('core/on_screen_key_helper');
        $this->load->helper('core/string_helper');
        $this->load->model('site/site_model');
    }
    public function index()
    {
    	$this->video_tut();
    }
			
    /**********************************************************
	**** Date: May 07, 2018							  	  *****
	**** Title: HELP 									  *****
	**** Created by: Rod 								  *****
	***********************************************************/
	public function video_tut($search=null)
	{	
		$search = $this->input->post('search');
		$help_info = $this->site_model->get_help_info($search);		
		 $data = $this->syter->spawn(null);
		// $role = $this->hr_model->get_role();
		// echo "<pre>",print_r($this->input->post()),"</pre>";die();
		// $this->data['code']=makeHelp($help_info);

		$this->data['function']='compHelpJs';
		$this->data['title'] = "Help";
	    $data['code'] = makeHelp($help_info);
            $data['add_css'] = array('css/cashier.css','css/onscrkeys.css','css/virtual_keyboard.css');
            $data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
            $data['load_js'] = 'dine/cashier.php';
            $data['use_js'] = 'controlPanelJs';
            $this->load->view('cashier',$data);

		// $this->load->view('cashier/head',$this->data);
		// $this->load->view('cashier/body',$this->data);
		// $this->load->view('parts/sideNav',$this->data);
		// // $this->load->view('parts/crumbler',$this->data);
		// $this->load->view('js/dine/setup',$this->data['function']);
		// $this->load->view('contents/default',$this->data);
		// $this->load->view('parts/chat');
		// $this->load->view('parts/foot');	
	}
	/********************************************************
	***		Title: HELP
	*** 	End of modification
	********************************************************/
}