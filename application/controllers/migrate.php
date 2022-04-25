<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Migrate {
	public function __construct() {        
	    parent::__construct();
	}
	
	
	public function execute_migration($ajax=false){
		// var_dump(MASTERMIGRATION);die();

		if(MASTERMIGRATION){	

            $this->load->model('core/master_model');
            $exec = $this->master_model->execute_migration();

			if( $exec){
				if(isset($_POST['ajax'])){
					echo true;
				}else{
					echo "<pre>",print_r($exec),"</pre>";die();
					// return true;
				}
			}
        }
	}

	public function batch_test(){
		echo "adasdasd";
		echo MASTERMIGRATION;
	}
}