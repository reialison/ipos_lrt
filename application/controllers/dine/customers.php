<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customers extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('dine/customers_model');
		$this->load->model('site/site_model');
		$this->load->helper('dine/customers_helper');
	}
	public function index()
	{
		$data = $this->syter->spawn('customers');

		$customers = $this->customers_model->get_customer();
		$data['code'] = customers_display($customers);

		$this->load->view('page',$data);
	}
	public function cust_setup($cust_id = null)
	{
		$data = $this->syter->spawn();

		if (is_null($cust_id)){
			$data['page_title'] = fa('fa-user fa-fw')." Add New Customer";
		}else {
			$customer = $this->customers_model->get_customer($cust_id);
			$customer = $customer[0];
			if (!empty($customer->cust_id)) {
				// $data['page_title'] = fa('fa-user fa-fw')." ".iSetObj($customer,'lname'.', '.'fname');
				$data['page_title'] = fa('fa-user fa-fw')." ".iSetObj($customer,'lname').", ".iSetObj($customer,'fname')." ".iSetObj($customer,'suffix');
				// if (!empty($customer->update_date))
					// $data['page_subtitle'] = "Last updated ".$customer->update_date;

			} else {
				header('Location:'.base_url().'customers/cust_setup');
			}
		}

		$data['code'] = customers_form_container($cust_id);
		$data['load_js'] = "dine/customers.php";
		$data['use_js'] = "customerFormContainerJs";

		$this->load->view('page',$data);
	}
	public function customer_load($cust_id = null)
	{
		$details = array();
		if (!is_null($cust_id))
			$item = $this->customers_model->get_customer($cust_id);
		if (!empty($item))
			$details = $item[0];

		$data['code'] = customers_details_form($details,$cust_id);
		$data['load_js'] = "dine/customers.php";
		$data['use_js'] = "customerDetailsJs";
		$this->load->view('load',$data);
	}
	public function customer_details_db($fromDel=false)
	{
		// if (!$this->input->post())
			// header("Location:".base_url()."items");

		$items = array(
			'fname' => $this->input->post('fname'),
			'lname' => $this->input->post('lname'),
			'mname' => $this->input->post('mname'),
			'suffix' => $this->input->post('suffix'),
			'phone' => $this->input->post('phone'),
			'email' => $this->input->post('email'),
			'tax_exempt' => (int)$this->input->post('tax_exempt'),
			'street_no' => $this->input->post('street_no'),
			'street_address' => $this->input->post('street_address'),
			'city' => $this->input->post('city'),
			'region' => $this->input->post('region'),
			'zip' => $this->input->post('zip'),
			'inactive' => (int)$this->input->post('inactive'),
		);

		if($fromDel){
			unset($items['tax_exempt']);
			unset($items['inactive']);
		}

		if ($this->input->post('cust_id')) {
			$id = $this->input->post('cust_id');
			$this->customers_model->update_customer($items,$id);
			$msg = "Updated Customer: ".ucwords($items['fname'])." ".ucwords($items['lname']);
		} else {
			$id = $this->customers_model->add_customer($items);
			$msg = "Added New Customer: ".ucwords($items['fname'])." ".ucwords($items['lname']);
		}

		echo json_encode(array('id'=>$id,'msg'=>$msg));
	}
	#customers menu
    public function cashier_customers(){
        $this->load->model('site/site_model');
		$this->load->helper('core/on_screen_key_helper');
        $this->load->model('dine/customers_model');
        $this->load->helper('dine/customers_helper');
        $data = $this->syter->spawn(null);
        $data['code'] = customersPage();
        // $data['add_css'] = 'css/cashier.css';
		$data['add_css'] = array('css/pos.css','css/onscrkeys.css','css/virtual_keyboard.css', 'css/cashier.css');	
		$data['add_js'] = array('js/on_screen_keys.js','js/jquery.keyboard.extension-navigation.min.js','js/jquery.keyboard.min.js');
        $data['load_js'] = 'dine/customers.php';
        $data['use_js'] = 'customersJs';
        $data['noNavbar'] = true; /*Hides the navbar. Comment-out this line to display the navbar.*/
        $this->load->view('cashier',$data);
    }
	public function load_customer_details()
	{
		$details = array();
		// $telno = $this->input->post('telno');
		$telno = str_replace('-', '', $this->input->post('telno'));
		
		if (!is_null($telno))
			$item = $this->customers_model->get_customer_info($telno);
		if (!empty($item))
			$details = $item[0];

		$data['code'] = customers_details_form($details,$details->cust_id);
		$data['load_js'] = "dine/customers.php";
		$data['use_js'] = "customerDetailsJs";
		$this->load->view('load',$data);
	}
	public function customers_list()
	{
		$customers = $this->customers_model->get_customer();

		$data['code'] = customersList($customers);
		$data['load_js'] = "dine/customers.php";
		$data['use_js'] = "customerDetailsJs";
		$this->load->view('load',$data);
	}
	public function validate_phone_number(){
		$telno = $this->input->post('telno');
		$cust_count = 0;
		
		$cust_count = $this->customers_model->get_all_customer_count($telno);
		$cust_det = $cust_count[0];
		
		if(empty($telno)){
			echo "empty";
		}else if($cust_det->total_count == 0){
			echo "none";
		}else if($cust_det->total_count > 0){
			echo "success||".$telno;
		}

	}

	public function customer_inquiry(){
		$data = $this->syter->spawn('inq');
        $data['page_title'] = fa('fa-random').' Customer Inquiry';
        $data['code'] = customerInquiry();           
        // $data['add_css'] = array('css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        // $data['add_js'] = array('js/select2.min.js?14.4');
        $data['load_js'] = "dine/customers.php";
        $data['use_js'] = "customerInquiryJS";
        $this->load->view('page',$data);
	}

	public function get_customer_trans($id=null,$asJson=true){
        sess_clear('menu_moves_cart');
        $this->load->helper('site/pagination_helper');
        $pagi = null;
        // $total_rows = 100;
        if($this->input->post('pagi'))
            $pagi = $this->input->post('pagi');
        $post = array();
        if(count($this->input->post()) > 0){
            $post = $this->input->post();
        }

       	$customer_id = $this->input->post('customer_id');
        
        $args = array();
        if($customer_id){
            $args['trans_sales.customer_id'] = $customer_id;
        }
        
        $order = array('trans_sales.sales_id'=>'desc');
        $group = null;
        // $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,null,true);
        // $page = paginate('items/get_items',$count,$total_rows,$pagi);
        $join["customers"] = array('content'=>"customers.cust_id = trans_sales.customer_id");

        $cust_trans = $this->site_model->get_tbl('trans_sales',$args,$order,$join,true,'*');
            
        if($cust_trans){
            foreach($cust_trans as $res){
                $this->make->sRow();
                    $this->make->td(ucwords($res->fname.' '.$res->lname));
                    $this->make->td($res->trans_ref);
                    $this->make->td(sql2Date($res->datetime)." ".toTimeW($res->datetime));
                    $this->make->td(num($res->total_amount), array("style"=>"text-align:right"));
                    $link = base_url() . 'reprint/view/'.$res->sales_id;
                    // $this->make->append('<td><a href="'.$link.'"  class="" target="_new"><i class="fa fa-eye"></i></a></td>');
                    $this->make->append('<td><a href="#"  class="print-receipt" ref="'.$res->sales_id.'"><i class="fa fa-eye"></i></a></td>');	                    
                $this->make->eRow();
            }
        }

       


        $json['html'] = $this->make->code();
        
        echo json_encode($json);
    }

    public function print_customers_inquiry_pdf()
    {
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("P", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Customer Inquiry Report');
        $pdf->SetSubject('');
        $pdf->SetKeywords('');

        // set default header data
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $set->branch_name, $set->address);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        
        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
        
		$customer_id = $this->input->get('customer_id');
	    
	    $args = array();
        if($customer_id){
            $args['trans_sales.customer_id'] = $customer_id;
        }

	    $order = array('trans_sales.sales_id'=>'desc');
	    $group = null;
	    // $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,null,true);
	    // $page = paginate('items/get_items',$count,$total_rows,$pagi);
	    $join["customers"] = array('content'=>"customers.cust_id = trans_sales.customer_id");

	    $cust_trans = $this->site_model->get_tbl('trans_sales',$args,$order,$join,true,'*');

        // echo "<pre>",print_r($items),"</pre>";die();
        $pdf->Write(0, 'Customer Inquiry Report', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(170, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->SetFont('helvetica', '', 9);
        // $pdf->Write(0, 'Report Period:    ', '', 0, 'L', false, 0, false, false, 0);
        
        $pdf->setX(200);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Transaction Time:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(170, 0, '', 'T', 0, 'C');
        $pdf->ln();              

        // echo "<pre>", print_r($trans), "</pre>";die();
        $pdf->SetFont('helvetica', 'B', 9);
        // -----------------------------------------------------------------------------
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(50, 0, 'Customer Name', 'B', 0, 'L');        
        $pdf->Cell(30, 0, 'Reference #', 'B', 0, 'L');        
        $pdf->Cell(50, 0, 'Trans Date', 'B', 0, 'L');        
        // $pdf->Cell(25, 0, 'VAT Sales', 'B', 0, 'C');        
        // $pdf->Cell(25, 0, 'VAT', 'B', 0, 'C');        
        $pdf->Cell(40, 0, 'Total Amount', 'B', 0, 'R'); 
        $pdf->ln();   
        
        $pdf->SetFont('helvetica', '', 9);     


        if(count($cust_trans) > 0){
            $ctr = 1;
            $last = count($cust_trans);
            foreach ($cust_trans as $dt =>$res) {

                $pdf->Cell(50, 0, ucwords($res->fname.' '.$res->lname), '', 0, 'L');        
	            $pdf->Cell(30, 0, $res->trans_ref, '', 0, 'L');        
	            $pdf->Cell(50, 0, sql2Date($res->datetime)." ".toTimeW($res->datetime), '', 0, 'L');
	            $pdf->Cell(40, 0, num($res->total_amount), '', 0, 'R');          
	            $pdf->ln();   
            }
        }

        $pdf->Output('customer_inquiry.pdf', 'I');
        

        //============================================================+
        // END OF FILE
        //============================================================+   
    }

    public function print_customer_inquiry_excel()
    {
        // $this->load->database('main', TRUE);
        
        date_default_timezone_set('Asia/Manila');
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $filename = 'Customer Inquiry Report';
        $rc=1;
        #GET VALUES
        // start_load(0);
            // $post = $this->set_post($_GET['calendar_range']);
        $this->load->model('dine/setup_model');
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];

        // update_load(10);
        // sleep(1);
        
        $customer_id = $this->input->get('customer_id');
        

        $args = array();
        if($customer_id){
            $args['trans_sales.customer_id'] = $customer_id;
        }

	    $order = array('trans_sales.sales_id'=>'desc');
	    $group = null;
	    // $count = $this->site_model->get_tbl($table,$args,$order,$join,true,$select,$group,null,true);
	    // $page = paginate('items/get_items',$count,$total_rows,$pagi);
	    $join["customers"] = array('content'=>"customers.cust_id = trans_sales.customer_id");

	    $cust_trans = $this->site_model->get_tbl('trans_sales',$args,$order,$join,true,'*');


        $styleHeaderCell = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '3C8DBC')
            ),
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'size' => 12,
                'color' => array('rgb' => 'FFFFFF'),
            )
        );
        $styleNum = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            ),
        );
        $styleTxt = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
        );
        $styleCenter = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
        );
        $styleTitle = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'size' => 12,
            )
        );
        $styleBoldLeft = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'font' => array(
                'bold' => true,
                'size' => 12,
            )
        );
        $styleBoldRight = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            ),
            'font' => array(
                'bold' => true,
                'size' => 12,
            )
        );
        $styleBoldCenter = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'size' => 12,
            )
        );
        
        $headers = array('Customer Name','Reference #', 'Trans Date','Total Amount');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        // $sheet->getColumnDimension('H')->setWidth(20);
        // $sheet->getColumnDimension('I')->setWidth(20);
        // $sheet->getColumnDimension('J')->setWidth(20);
        // $sheet->getColumnDimension('K')->setWidth(20);
        // $sheet->getColumnDimension('L')->setWidth(20);


        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->branch_name);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->address);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Customer Inquiry Report');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('E'.$rc)->setValue('Report Generated: '.(new \DateTime())->format('Y-m-d H:i:s'));
        $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        $rc++;

        
        $user = $this->session->userdata('user');
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('E'.$rc)->setValue('Generated by:    '.$user["full_name"]);
        $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        $rc++;


        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }
        $rc++; 

       
        if(count($cust_trans) > 0){
            $ctr = 1;
            $last = count($moves_array);
            $curr = $before_qty;
            foreach ($cust_trans as $dt =>$res) {

                $sheet->getCell('A'.$rc)->setValue(ucwords($res->fname.' '.$res->lname));
                $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                $sheet->setCellValueExplicit('B'.$rc, $res->trans_ref,PHPExcel_Cell_DataType::TYPE_STRING);
                // $sheet->getCell('B'.$rc)->setValue($res->trans_ref);
                // $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('C'.$rc)->setValue(sql2Date($res->datetime)." ".toTimeW($res->datetime));
                $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('D'.$rc)->setValue(num($res->total_amount));
                $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);

                $rc++; 
            }
        }

        
        update_load(100);        
        ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
        

        //============================================================+
        // END OF FILE
        //============================================================+   
    }
}