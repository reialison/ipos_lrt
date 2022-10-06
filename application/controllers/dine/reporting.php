<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once (dirname(__FILE__)."/prints.php");
class Reporting extends Prints {
    var $data = null;
    public function __construct(){
        parent::__construct();
        $this->load->model('dine/cashier_model');         
        $this->load->helper('dine/reporting_helper');         
    }
    public function menus_rep(){
        $data = $this->syter->spawn('menu_sales_rep');
        $data['page_title'] = fa('icon-book-open')." Menu Sales Report";
        $data['code'] = menusRep();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['page_no_padding'] = true;
        $data['sideBarHide'] = true;
        $data['load_js'] = 'dine/reporting';
        $data['use_js'] = 'menusRepJS';
        $this->load->view('page',$data);
    }
    public function menus_rep_gen(){
        start_load(0);
        $post = $this->set_post();
        $curr = $this->search_current();
        update_load(10);
        $trans = $this->trans_sales($post['args'],$curr);
        $sales = $trans['sales'];
        update_load(15);
        $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
        update_load(20);
        $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
        update_load(25);
        $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
        update_load(30);
        $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
        update_load(35);
        $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
        update_load(40);
        $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
        update_load(45);
        $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
        update_load(50);
        $payments = $this->payment_sales($sales['settled']['ids'],$curr);
        update_load(53);
        $gross = $trans_menus['gross']; 
        $net = $trans['net'];
        $charges = $trans_charges['total']; 
        $discounts = $trans_discounts['total']; 
        $local_tax = $trans_local_tax['total']; 
        $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;
        if($less_vat < 0)
            $less_vat = 0;
        $tax = $trans_tax['total'];
        $no_tax = $trans_no_tax['total'];
        $zero_rated = $trans_zero_rated['total'];
        $no_tax -= $zero_rated;
        
        update_load(55);
        $cats = $trans_menus['cats'];                 
        $menus = $trans_menus['menus'];
        $subcats = $trans_menus['sub_cats'];      
        $menu_total = $trans_menus['menu_total'];
        $total_qty = $trans_menus['total_qty'];
        update_load(60);
        usort($menus, function($a, $b) {
            return $b['amount'] - $a['amount'];
        });
        update_load(80);
        $this->make->sDiv();
            $this->make->sTable(array('class'=>'table reportTBL'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('Code');
                        $this->make->th('Name');
                        $this->make->th('Category');
                        $this->make->th('Price (SRP)');
                        $this->make->th('QTY');
                        $this->make->th('QTY AVG');
                        $this->make->th('Sales');
                        $this->make->th('Sales AVG');
                        $this->make->th('Cost');
                        $this->make->th('Total Cost');
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();
                    foreach ($menus as $res) {
                        $this->make->sRow();
                            $this->make->td($res['code']);
                            $this->make->td($res['name']);
                            $this->make->td($cats[$res['cat_id']]['name']);
                            $this->make->td($res['sell_price']);
                            $this->make->td($res['qty']);
                            $this->make->td( num( ($res['qty'] / $total_qty) * 100 ).'%' );
                            $this->make->td( num($res['amount']) );
                            $this->make->td( num( ($res['amount'] / $menu_total) * 100 ).'%' );
                            $this->make->td($res['cost_price']);
                            $this->make->td($res['cost_price'] * $res['qty']);
                        $this->make->eRow();
                    }    
                    $this->make->sRow();
                        $this->make->th('');
                        $this->make->th('');
                        $this->make->th('');
                        $this->make->th('Total');
                        $this->make->th($total_qty);
                        $this->make->th('Total');
                        $this->make->th(num($menu_total));
                        $this->make->th('');
                        $this->make->th('');
                        $this->make->th('');
                    $this->make->eRow();             
                    // $mods_total = $trans_menus['mods_total'];
                    // if($mods_total > 0){
                    //     $this->make->sRow();
                    //         $this->make->td('Total Modifiers Sale',array('colspan'=>'6') );
                    //         $this->make->td( num($mods_total));
                    //     $this->make->eRow();
                    // }
                    // $net_no_adds = $net-$charges-$local_tax;
                    // $this->make->sRow();
                    //     $this->make->td('Total Sales');
                    //     $this->make->td( num($net));
                    //     $this->make->td('',array('colspan'=>'6') );
                    // $this->make->eRow();
                    // $txt = numInt(($charges));
                    // if($charges > 0)
                    //     $txt = "(".numInt(($charges)).")";
                    // $this->make->sRow();
                    //     $this->make->td('Total Charges');
                    //     $this->make->td( $txt );
                    //     $this->make->td('',array('colspan'=>'6') );
                    // $this->make->eRow();
                    // $txt = numInt(($local_tax));
                    // if($local_tax > 0)
                    //     $txt = "(".numInt(($local_tax)).")";
                    // $this->make->sRow();
                    //     $this->make->td('Total Local Tax' );
                    //     $this->make->td( $txt );
                    //     $this->make->td('',array('colspan'=>'6') );
                    // $this->make->eRow();   
                    // $this->make->sRow();
                    //     $this->make->td('Total Discounts');
                    //     $this->make->td( num($discounts) );
                    //     $this->make->td('',array('colspan'=>'6') );
                    // $this->make->eRow();
                    // $this->make->sRow();
                    //     $this->make->td('Total VAT EXEMPT');
                    //     $this->make->td( num($less_vat) );
                    //     $this->make->td('',array('colspan'=>'6') );
                    // $this->make->eRow();
                    // $this->make->sRow();
                    //     $this->make->td('Total Gross Sales');
                    //     $this->make->td( num($gross) );
                    //     $this->make->td('',array('colspan'=>'6') );
                    // $this->make->eRow();
                $this->make->eTableBody();
            $this->make->eTable();
            $this->make->sDivRow();
                $this->make->sDivCol(4);
                    $this->make->sTable(array('class'=>'table reportTBL','style'=>'margin-top:10px;'));
                        $this->make->sTableHead();
                            $this->make->sRow();
                                $this->make->th('Category');
                                $this->make->th('QTY');
                                $this->make->th('Amount');
                            $this->make->eRow();
                        $this->make->eTableHead();
                        $this->make->sTableBody();
                            foreach ($cats as $cat_id => $ca) {
                                if($ca['amount'] > 0){
                                    $this->make->sRow();
                                        $this->make->td($ca['name']);
                                        $this->make->td($ca['qty']);
                                        $this->make->td($ca['amount']);
                                    $this->make->eRow();
                                }
                            }    
                        $this->make->eTableBody();
                    $this->make->eTable();
                $this->make->eDivCol();
                $this->make->sDivCol(4);
                    $this->make->sTable(array('class'=>'table reportTBL','style'=>'margin-top:10px;'));
                        $this->make->sTableHead();
                            $this->make->sRow();
                                $this->make->th('Types');
                                $this->make->th('QTY');
                                $this->make->th('Amount');
                            $this->make->eRow();
                        $this->make->eTableHead();
                        $this->make->sTableBody();
                            foreach ($subcats as $id => $val) {
                                if($val['amount'] > 0){
                                    $this->make->sRow();
                                        $this->make->td($val['name']);
                                        $this->make->td($val['qty']);
                                        $this->make->td($val['amount']);
                                    $this->make->eRow();
                                }
                            }
                        $this->make->eTableBody();
                    $this->make->eTable();
                $this->make->eDivCol();
                $this->make->sDivCol(4);
                    $this->make->sTable(array('class'=>'table reportTBL','style'=>'margin-top:10px;'));
                        $this->make->sTableBody();
                            $mods_total = $trans_menus['mods_total'];
                            if($mods_total > 0){
                                $this->make->sRow();
                                    $this->make->td('Total Modifiers Sale');
                                    $this->make->td( num($mods_total));
                                $this->make->eRow();
                            }
                            $submods_total = $trans_menus['submods_total'];
                            if($submods_total > 0){
                                $this->make->sRow();
                                    $this->make->td('Total Submodifiers Sale');
                                    $this->make->td( num($submods_total));
                                $this->make->eRow();
                            }
                            $this->make->sRow();
                                $this->make->td('Total Sales');
                                $this->make->td(num($net));
                            $this->make->eRow();
                            $this->make->sRow();
                                $txt = numInt(($charges));
                                if($charges > 0)
                                    $txt = "(".numInt(($charges)).")";
                                $this->make->td('Total Charges');
                                $this->make->td($txt);
                            $this->make->eRow();
                            $this->make->sRow();
                                $txt = numInt(($local_tax));
                                if($local_tax > 0)
                                    $txt = "(".numInt(($local_tax)).")";
                                $this->make->td('Total Local Tax');
                                $this->make->td($txt);
                            $this->make->eRow();
                            $this->make->sRow();
                                $this->make->td('Total Discounts');
                                $this->make->td(num($discounts));
                            $this->make->eRow();
                            $this->make->sRow();
                                $this->make->td('Total VAT EXEMPT');
                                $this->make->td(num($less_vat));
                            $this->make->eRow();
                            $this->make->sRow();
                                $this->make->td('Total Gross Sales');
                                $this->make->td(num($gross));
                            $this->make->eRow();
                        $this->make->eTableBody();
                    $this->make->eTable();
                $this->make->eDivCol();
            $this->make->eDivRow();


        $this->make->eDiv();
        update_load(100);
        $code = $this->make->code();
        $json['code'] = $code;
        $json['tbl_vals'] = $menus;
        $json['dates'] = $this->input->post('calendar_range');
        echo json_encode($json);
    }
    public function menus_rep_excel(){
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $filename = 'Menu Sales Report';
        $rc=1;
        #GET VALUES
            start_load(0);
            $post = $this->set_post($_GET['calendar_range']);
            $curr = true;
            update_load(10);
            $trans = $this->trans_sales($post['args'],$curr);
            $sales = $trans['sales'];
            update_load(15);
            $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
            update_load(20);
            $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
            update_load(25);
            $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
            update_load(30);
            $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
            update_load(35);
            $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
            update_load(40);
            $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
            update_load(45);
            $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
            update_load(50);
            $payments = $this->payment_sales($sales['settled']['ids'],$curr);
            update_load(53);
            $gross = $trans_menus['gross']; 
            $net = $trans['net'];
            $charges = $trans_charges['total']; 
            $discounts = $trans_discounts['total']; 
            $local_tax = $trans_local_tax['total']; 
            $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;
            if($less_vat < 0)
                $less_vat = 0;
            $tax = $trans_tax['total'];
            $no_tax = $trans_no_tax['total'];
            $zero_rated = $trans_zero_rated['total'];
            $no_tax -= $zero_rated;
            update_load(55);
            $cats = $trans_menus['cats'];                 
            $menus = $trans_menus['menus'];
            $menu_total = $trans_menus['menu_total'];
            $total_qty = $trans_menus['total_qty'];
            update_load(60);
            usort($menus, function($a, $b) {
                return $b['amount'] - $a['amount'];
            });
            update_load(80);
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
                'size' => 14,
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
        $styleTitle = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'size' => 16,
            )
        );
        
        $headers = array('Code','Name','Price (SRP)','QTY','QTY(AVG)','Amount','Amount(AVG)','Cost','Total Cost');
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(50);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);


        $sheet->mergeCells('A'.$rc.':I'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Menu Sales Report');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;
        
        $dates = explode(" to ",$_GET['calendar_range']);
        $from = sql2DateTime($dates[0]);
        $to = sql2DateTime($dates[1]);
        $sheet->getCell('A'.$rc)->setValue('Date From: '.$from);
        $sheet->mergeCells('A'.$rc.':I'.$rc);
        $rc++;

        $sheet->getCell('A'.$rc)->setValue('Date To: '.$to);
        $sheet->mergeCells('A'.$rc.':I'.$rc);
        $rc++;
        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }

        $rc++;
        foreach ($menus as $res) {
                $sheet->getCell('A'.$rc)->setValue($res['code']);
                $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('B'.$rc)->setValue($res['name']);
                $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('C'.$rc)->setValue($res['sell_price']);
                $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('D'.$rc)->setValue($res['qty']);     
                $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('E'.$rc)->setValue(num( ($res['qty'] / $total_qty) * 100 ).'%');     
                $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('F'.$rc)->setValue(num($res['amount']));     
                $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('G'.$rc)->setValue(num( ($res['amount'] / $menu_total) * 100 ).'%');
                $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('H'.$rc)->setValue($res['cost_price']);
                $sheet->getStyle('H'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('I'.$rc)->setValue($res['cost_price'] * $res['qty']);
                $sheet->getStyle('I'.$rc)->applyFromArray($styleNum);

            $rc++;
        } 
        $rc++;


        $mods_total = $trans_menus['mods_total'];
        if($mods_total > 0){
            $sheet->getCell('A'.$rc)->setValue('Total Modifiers Sale: ');
            $sheet->getCell('B'.$rc)->setValue(num($mods_total));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            $rc++;
        }
        $net_no_adds = $net-$charges-$local_tax;
        $sheet->getCell('A'.$rc)->setValue('Total Sales: ');
        $sheet->getCell('B'.$rc)->setValue(num($net));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $txt = numInt(($charges));
        if($charges > 0)
            $txt = "(".numInt(($charges)).")";
        $sheet->getCell('A'.$rc)->setValue('Total Charges: ');
        $sheet->getCell('B'.$rc)->setValue($txt);
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $txt = numInt(($local_tax));
        if($local_tax > 0)
            $txt = "(".numInt(($local_tax)).")";
        $sheet->getCell('A'.$rc)->setValue('Total Local Tax: ');
        $sheet->getCell('B'.$rc)->setValue($txt);
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $sheet->getCell('A'.$rc)->setValue('Total Discounts: ');
        $sheet->getCell('B'.$rc)->setValue(num($discounts));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $sheet->getCell('A'.$rc)->setValue('Total VAT EXEMPT: ');
        $sheet->getCell('B'.$rc)->setValue(num($less_vat));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $sheet->getCell('A'.$rc)->setValue('Total Gross Sales: ');
        $sheet->getCell('B'.$rc)->setValue(num($gross));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        
        update_load(100);
        ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }

    //////hourly sales rep
    public function hourly_rep(){
        $data = $this->syter->spawn('hourly_rep');
        $data['page_title'] = fa('icon-doc')." Hourly Sales Report";
        $data['code'] = hourlyRep();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['page_no_padding'] = true;
        $data['sideBarHide'] = true;
        $data['load_js'] = 'dine/reporting';
        $data['use_js'] = 'hourlyRepJS';
        $this->load->view('page',$data);
    }

    public function check_hourly_sales(){
        $this->load->helper('dine/reports_helper');

        $date = $this->input->post('calendar_range');
        // $user = $this->input->post('user');
        $json = $this->input->post('json');

        $datesx = explode(" to ",$date);
        // $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
        // $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));
        $date_from = $datesx[0];
        $date_to = $datesx[1];


        // echo $date_from.' -- '.$date_to;

        $args = array();
        // if($user)
        //     $args["trans_sales.user_id"] = array('use'=>'where','val'=>$user,'third'=>false);

        $args["trans_sales.terminal_id"] = array('use'=>'where','val'=>TERMINAL_ID,'third'=>false);
        $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.inactive = 0"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.datetime between '".date('Y-m-d H:i:s',strtotime($date_from))."' and '".date('Y-m-d H:i:s',strtotime($date_to))."'"] = array('use'=>'where','val'=>null,'third'=>false);

        $post = $this->set_post();
        $curr = $this->search_current();
        $trans = $this->trans_sales($args,$curr);
        $sales = $trans['sales'];

        // var_dump($sales); die();
        // $get_trans = $this->cashier_model->get_trans_sales(null,$args,'asc');
        // $unserialize = unserialize(TIMERANGES);
        // var_dump($unserialize); die();

        $ranges = array();
        foreach (unserialize(TIMERANGES) as $ctr => $time) {
            $key = date('H',strtotime($time['FTIME']));
            $ranges[$key] = array('start'=>$time['FTIME'],'end'=>$time['TTIME'],'tc'=>0,'guest'=>0,'net'=>0);
            // $ranges[$key] = array();
        }

        // echo "<pre>",print_r($ranges),"</pre>";
        // die();
        $dates = array();
        if(count($sales['settled']['orders']) > 0){
            foreach ($sales['settled']['orders'] as $sales_id => $val) {
                $dates[date2Sql($val->datetime)]['ranges'] = $ranges;
            }

            foreach ($sales['settled']['orders'] as $sales_id => $val) {
                if(isset($dates[date2Sql($val->datetime)])){
                    $date_arr = $dates[date2Sql($val->datetime)];
                    $range = $date_arr['ranges'];
                    $H = date('H',strtotime($val->datetime));
                    $guestc = $val->guest;
                    if($val->guest == 0){
                        $guestc = 1;
                    }
                    if(isset($range[$H])){
                        $r = $range[$H];
                        $r['tc'] += 1;
                        $r['guest'] +=  $guestc;
                        $r['net'] += $val->total_amount;
                        $range[$H] = $r;
                    }
                    $dates[date2Sql($val->datetime)]['ranges'] = $range;
                }
            }
            
            // $this->print_drawer_details($get_trans,$date,$user,$asjson);
            $this->make->sDiv();
                $this->make->sTable(array('class'=>'table'));
                    $this->make->sTableHead();
                        $this->make->sRow(array('class'=>'bg-blue'));
                            $this->make->th('Time');
                            $this->make->th('Transaction Count');
                            $this->make->th('Net');
                            // $this->make->th('Trans Time');
                            $this->make->th('Average');
                            // $this->make->th('Category');
                            // $this->make->th('Price (SRP)');
                            // $this->make->th('QTY');
                            // $this->make->th('QTY AVG');
                            // $this->make->th('Sales');
                            // $this->make->th('Sales AVG');
                        $this->make->eRow();
                    $this->make->eTableHead();
                    $this->make->sTableBody();
                    $txt = '';
                    // foreach($dates as $key1 => $v1){
                    //     // $this->make->sRow();
                    //     //     $this->make->th(sql2Date($key1),array('colspan'=>4));
                    //     // $this->make->eRow();
                    //     //$txt .= sql2Date($key1);
                    //     foreach($v1 as $key2 => $v2){

                    //         foreach($v2 as $key3 => $v3){
                    //             $id = (int) $key3;
                    //             $times = $unserialize[$id];
                    //             $txt = $key1.' &nbsp;'.$times['FTIME'].' to '.$times['TTIME'];
                    //             $this->make->sRow();
                    //                 $this->make->th($txt,array('colspan'=>3));
                    //             $this->make->eRow();
                    //             foreach($v3 as $key4 => $v4){
                    //                 $this->make->sRow();
                    //                     $this->make->th($v4['time']);
                    //                     $this->make->th($v4['reference']);
                    //                     $this->make->th($v4['net']);
                    //                 $this->make->eRow();
                    //             }
                    //             // var_dump($times);
                    //             // $this->make->sRow();
                    //             //     $this->make->th($key3);
                    //             // $this->make->eRow();
                    //         }

                    //     }

                    // }
                    $ctr = $guest_ctr = 0;
                    $gtavg = $gtctr = $gtnet = 0;
                    foreach($dates as $key1 => $v1){
                        $this->make->sRow(array('class'=>''));
                            $this->make->th(sql2Date($key1),array('colspan'=>4,'style'=>'text-align:center;'));
                        $this->make->eRow();

                        $ranges = $v1['ranges'];
                        //$txt .= sql2Date($key1);
                        $tavg = 0;
                        $tctr = 0;
                        $tnet = 0;
                        foreach($ranges as $key2 => $ran){
                            if($ran['tc'] == 0 || $ran['net'] == 0)
                                $avg = 0;
                            else
                                $avg = $ran['net']/$ran['tc'];
                            $ctr += $ran['tc'];
                            $guest_ctr += $ran['guest'];

                            $this->make->sRow();
                                $this->make->th($ran['start']."-".$ran['end']);
                                $this->make->th($ran['tc']);
                                $this->make->th(numInt($ran['net']));
                                $this->make->th(numInt($avg));
                            $this->make->eRow();
                            $tctr += $ran['tc'];
                            $tnet += $ran['net'];
                            // if($ctr == 0 || $ran['net'] == 0)
                            //     $tavg = 0;
                            // else
                            //     $tavg += $ran['net']/$ctr;

                        }
                        $gtctr += $tctr;
                        $gtnet += $tnet;

                    }
                    $gtavg = $gtnet/$gtctr;
                    $this->make->sRow();
                        $this->make->th('TOTAL');
                        $this->make->th($guest_ctr );
                        $this->make->th(numInt($gtnet));
                        $this->make->th(numInt($gtavg));
                    $this->make->eRow();
                    $this->make->eTableBody();
                $this->make->eTable();
            $this->make->eDiv();

            $code = $this->make->code();

            // echo $code;
            echo json_encode(array("code"=>$code));
            
        }
        else{
            $error = "There is no sales found.";
            echo json_encode(array("code"=>"<pre style='background-color:#fff'>$error</pre>"));
        }
        // var_dump($dates);
    }

    public function check_hourly_sales_excel(){
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $filename = 'Hourly Sales Report';
        $rc=1;
        #GET VALUES
        start_load(0);
            // $post = $this->set_post($_GET['calendar_range']);
            
        $date = $_GET['calendar_range'];

        $datesx = explode(" to ",$date);
        // $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
        // $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));
        $date_from = $datesx[0];
        $date_to = $datesx[1];

        update_load(10);
        sleep(1);

        $args = array();

        // if($user)
        //     $args["trans_sales.user_id"] = array('use'=>'where','val'=>$user,'third'=>false);

        $args["trans_sales.terminal_id"] = array('use'=>'where','val'=>TERMINAL_ID,'third'=>false);
        $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.inactive = 0"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.datetime between '".date('Y-m-d H:i:s',strtotime($date_from))."' and '".date('Y-m-d H:i:s',strtotime($date_to))."'"] = array('use'=>'where','val'=>null,'third'=>false);

        $post = $this->set_post($_GET['calendar_range']);
        $curr = true;
        $trans = $this->trans_sales($args,$curr);
        $sales = $trans['sales'];

        // $get_trans = $this->cashier_model->get_trans_sales(null,$args,'asc');

        update_load(20);
        sleep(1);

        $ranges = array();
        foreach (unserialize(TIMERANGES) as $ctr => $time) {
            $key = date('H',strtotime($time['FTIME']));
            $ranges[$key] = array('start'=>$time['FTIME'],'end'=>$time['TTIME'],'tc'=>0,'net'=>0);
            // $ranges[$key] = array();
        }

        update_load(30);
        sleep(1);

        // echo "<pre>",print_r($ranges),"</pre>";
        // die();
        $dates = array();
        if(count($sales['settled']['orders']) > 0){
            foreach ($sales['settled']['orders'] as $sales_id => $val) {
                $dates[date2Sql($val->datetime)]['ranges'] = $ranges;
            }
            foreach ($sales['settled']['orders'] as $sales_id => $val) {
                if(isset($dates[date2Sql($val->datetime)])){
                    $date_arr = $dates[date2Sql($val->datetime)];
                    $range = $date_arr['ranges'];
                    $H = date('H',strtotime($val->datetime));
                    $guestc = $val->guest;
                    if($val->guest == 0){
                        $guestc = 1;
                    }
                    if(isset($range[$H])){
                        $r = $range[$H];
                        $r['tc'] += 1;
                        $r['guest'] +=  $guestc;
                        $r['net'] += $val->total_amount;
                        $range[$H] = $r;
                    }
                    $dates[date2Sql($val->datetime)]['ranges'] = $range;
                }
            }
        }

        update_load(60);
        sleep(1);        


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
                'size' => 14,
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
                'size' => 16,
            )
        );
        
        $headers = array('Time','Transaction Count','Net','Average');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        // $sheet->getColumnDimension('E')->setWidth(20);
        // $sheet->getColumnDimension('F')->setWidth(20);
        // $sheet->getColumnDimension('G')->setWidth(20);


        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Hourly Sales Report');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;
        
        // $dates = explode(" to ",$_GET['date']);
        $from = sql2DateTime($date_from);
        $to = sql2DateTime($date_to);
        $sheet->getCell('A'.$rc)->setValue('Date From: '.$from);
        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $rc++;

        $sheet->getCell('A'.$rc)->setValue('Date To: '.$to);
        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $rc++;
        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }

        $rc++;
        $ctr = 0;
        $gtavg = $gtctr = $gtnet = 0;
        update_load(80);
        sleep(1);
        foreach($dates as $key1 => $v1){
            $sheet->getCell('A'.$rc)->setValue(sql2Date($key1));
            $sheet->mergeCells('A'.$rc.':D'.$rc);
            $sheet->getStyle('A'.$rc)->applyFromArray($styleCenter);
            $rc++;

            $ranges = $v1['ranges'];
            //$txt .= sql2Date($key1);
            $tavg = 0;
            $tctr = 0;
            $tnet = 0;
            foreach($ranges as $key2 => $ran){
                if($ran['tc'] == 0 || $ran['net'] == 0)
                    $avg = 0;
                else
                    $avg = $ran['net']/$ran['tc'];
                $ctr += $ran['tc'];

                $sheet->getCell('A'.$rc)->setValue($ran['start']."-".$ran['end']);
                $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('B'.$rc)->setValue($ran['tc']);
                $sheet->getStyle('B'.$rc)->applyFromArray($styleCenter);
                $sheet->getCell('C'.$rc)->setValue(numInt($ran['net']));
                $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('D'.$rc)->setValue(numInt($avg));     
                $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);

                // $this->make->sRow();
                //     $this->make->th($ran['start']."-".$ran['end']);
                //     $this->make->th($ran['tc']);
                //     $this->make->th(numInt($ran['net']));
                //     $this->make->th(numInt($avg));
                // $this->make->eRow();
                $tctr += $ran['tc'];
                $tnet += $ran['net'];
                // $tavg += $avg;
                $rc++;
                // if($ctr == 0 || $ran['net'] == 0)
                //     $tavg = 0;
                // else
                //     $tavg += $ran['net']/$ctr;

            }
            
            $gtctr += $tctr;
            $gtnet += $tnet;

        }
        $gtavg = $gtnet/$gtctr;
        $sheet->getCell('A'.$rc)->setValue('TOTAL');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->getCell('B'.$rc)->setValue($gtctr);
        $sheet->getStyle('B'.$rc)->applyFromArray($styleCenter);
        $sheet->getCell('C'.$rc)->setValue(numInt($gtnet));
        $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
        $sheet->getCell('D'.$rc)->setValue(numInt($gtavg));     
        $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
        // foreach ($menus as $res) {
        //         $sheet->getCell('A'.$rc)->setValue($res['code']);
        //         $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //         $sheet->getCell('B'.$rc)->setValue($res['name']);
        //         $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
        //         $sheet->getCell('C'.$rc)->setValue($res['sell_price']);
        //         $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('D'.$rc)->setValue($res['qty']);     
        //         $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('E'.$rc)->setValue(num( ($res['qty'] / $total_qty) * 100 ).'%');     
        //         $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('F'.$rc)->setValue(num($res['amount']));     
        //         $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('G'.$rc)->setValue(num( ($res['amount'] / $menu_total) * 100 ).'%');
        //         $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);

        //     $rc++;
        // } 
        // $rc++;

        
        update_load(100);
        ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }
    
        // New Report
    // Created by: Rod
    public function sales_rep()
    {
        $data = $this->syter->spawn('sales_rep');        
        $data['page_title'] = fa('fa-money')." Sales Report";
        $data['code'] = salesRep();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['page_no_padding'] = false;
        $data['sideBarHide'] = false;
        $data['load_js'] = 'dine/reporting';
        $data['use_js'] = 'salesRepJS';
        $this->load->view('page',$data);
    }
    public function sales_rep_gen()
    {
        $this->load->model('dine/setup_model');
        // $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];
        start_load(0);
        $menu_cat_id = $this->input->post("menu_cat_id");        
        // $brand = $this->input->post("brand");        
        $daterange = $this->input->post("calendar_range");        
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        // $date = $this->input->post("date");
        $this->menu_model->db = $this->load->database('main', TRUE);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        $trans = $this->menu_model->get_cat_sales_rep($from, $to, $menu_cat_id);  
        $trans_ret = $this->menu_model->get_cat_sales_rep_retail($from, $to, "");  
        // echo $this->db->last_query();              
        // echo "<pre>", print_r($trans), "</pre>"; die();
        $trans_count = count($trans);
        $trans_count_ret = count($trans_ret);
        $counter = 0;
        $this->make->sDiv();
            $this->make->sTable(array("id"=>"main-tbl", 'class'=>'table reportTBL sortable'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('Category');
                        $this->make->th('Qty');
                        // $this->make->th('VAT Sales');
                        // $this->make->th('VAT');
                        $this->make->th('Gross');
                        $this->make->th('Sales (%)');
                        $this->make->th('Cost');
                        $this->make->th('Cost (%)');
                        $this->make->th('Margin');
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();
                    $tot_qty = 0;
                    $tot_vat_sales = 0;
                    $tot_vat = 0;
                    $tot_gross = 0;
                    $tot_sales_prcnt = 0;
                    $tot_cost = 0;
                    $tot_margin = 0;
                    $tot_cost_prcnt = 0;
                    foreach ($trans as $v) {
                        $tot_gross += $v->gross;
                        $tot_cost += $v->cost;
                    }
                    foreach ($trans as $res) {
                        $this->make->sRow();
                            $this->make->td($res->menu_cat_name);
                            $this->make->td(num($res->qty), array("style"=>"text-align:right"));                            
                            // $this->make->td(num($res->vat_sales), array("style"=>"text-align:right"));                            
                            // $this->make->td(num($res->vat), array("style"=>"text-align:right"));                            
                            $this->make->td(num($res->gross), array("style"=>"text-align:right"));                            
                            $this->make->td(num($res->gross / $tot_gross * 100). "%", array("style"=>"text-align:right"));                            
                            $this->make->td(num($res->cost), array("style"=>"text-align:right"));                            
                            if($tot_cost != 0){
                                $this->make->td(num($res->cost / $tot_cost * 100). "%", array("style"=>"text-align:right"));
                            }else{
                                $this->make->td("0.00%", array("style"=>"text-align:right"));                                
                            }
                            $this->make->td(num($res->gross - $res->cost), array("style"=>"text-align:right"));                         
                        $this->make->eRow();

                         // Grand Total
                        $tot_qty += $res->qty;
                        $tot_vat_sales += $res->vat_sales;
                        $tot_vat += $res->vat;
                        // $tot_gross += $res->gross;
                        $tot_sales_prcnt = 0;
                        // $tot_cost += $res->cost;
                        $tot_margin += $res->gross - $res->cost;
                        $tot_cost_prcnt = 0;

                        $counter++;
                        $progress = ($counter / $trans_count) * 100;
                        update_load(num($progress));

                    }    
                    $this->make->sRow();
                        $this->make->th('Grand Total');
                        $this->make->th(num($tot_qty), array("style"=>"text-align:right"));
                        // $this->make->th(num($tot_vat_sales), array("style"=>"text-align:right"));
                        // $this->make->th(num($tot_vat), array("style"=>"text-align:right"));
                        $this->make->th(num($tot_gross), array("style"=>"text-align:right"));
                        $this->make->th("", array("style"=>"text-align:right"));
                        $this->make->th(num($tot_cost), array("style"=>"text-align:right"));
                        $this->make->th("", array("style"=>"text-align:right"));                                                
                        $this->make->th(num($tot_margin), array("style"=>"text-align:right"));                     
                    $this->make->eRow();                                 
                $this->make->eTableBody();
            $this->make->eTable();
        $this->make->eDiv();
        $this->make->append('<center><h4>Retail Items</h4></center>');
        $this->make->sDiv();
            $this->make->sTable(array("id"=>"main-tbl", 'class'=>'table reportTBL sortable'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('Category');
                        $this->make->th('Qty');
                        // $this->make->th('VAT Sales');
                        // $this->make->th('VAT');
                        $this->make->th('Gross');
                        $this->make->th('Sales (%)');
                        $this->make->th('Cost');
                        $this->make->th('Cost (%)');
                        $this->make->th('Margin');
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();
                    $tot_qty = 0;
                    $tot_vat_sales = 0;
                    $tot_vat = 0;
                    $tot_gross = 0;
                    $tot_sales_prcnt = 0;
                    $tot_cost = 0;
                    $tot_margin = 0;
                    $tot_cost_prcnt = 0;
                    foreach ($trans_ret as $v) {
                        $tot_gross += $v->gross;
                        // $tot_cost += $v->cost;
                    }
                    foreach ($trans_ret as $res) {
                        $this->make->sRow();
                            $this->make->td($res->name);
                            $this->make->td(num($res->qty), array("style"=>"text-align:right"));                            
                            // $this->make->td(num($res->vat_sales), array("style"=>"text-align:right"));                            
                            // $this->make->td(num($res->vat), array("style"=>"text-align:right"));                            
                            $this->make->td(num($res->gross), array("style"=>"text-align:right"));                            
                            $this->make->td(num($res->gross / $tot_gross * 100). "%", array("style"=>"text-align:right"));                            
                            // $this->make->td(num($res->cost), array("style"=>"text-align:right"));                            
                            $this->make->td(num(0), array("style"=>"text-align:right"));                            
                            if($tot_cost != 0){
                                $this->make->td(num($res->cost / $tot_cost * 100). "%", array("style"=>"text-align:right"));
                            }else{
                                $this->make->td("0.00%", array("style"=>"text-align:right"));                                
                            }
                            $this->make->td(num($res->gross - 0), array("style"=>"text-align:right"));                         
                            // $this->make->td(num($res->gross - $res->cost), array("style"=>"text-align:right"));                         
                        $this->make->eRow();

                         // Grand Total
                        $tot_qty += $res->qty;
                        $tot_vat_sales += $res->vat_sales;
                        $tot_vat += $res->vat;
                        // $tot_gross += $res->gross;
                        $tot_sales_prcnt = 0;
                        // $tot_cost += $res->cost;
                        // $tot_margin += $res->gross - $res->cost;
                        $tot_margin += $res->gross - 0;
                        $tot_cost_prcnt = 0;

                        $counter++;
                        $progress = ($counter / $trans_count_ret) * 100;
                        update_load(num($progress));

                    }    
                    $this->make->sRow();
                        $this->make->th('Grand Total');
                        $this->make->th(num($tot_qty), array("style"=>"text-align:right"));
                        // $this->make->th(num($tot_vat_sales), array("style"=>"text-align:right"));
                        // $this->make->th(num($tot_vat), array("style"=>"text-align:right"));
                        $this->make->th(num($tot_gross), array("style"=>"text-align:right"));
                        $this->make->th("", array("style"=>"text-align:right"));
                        $this->make->th(num($tot_cost), array("style"=>"text-align:right"));
                        $this->make->th("", array("style"=>"text-align:right"));                                                
                        $this->make->th(num($tot_margin), array("style"=>"text-align:right"));                     
                    $this->make->eRow();                                 
                $this->make->eTableBody();
            $this->make->eTable();
        $this->make->eDiv();
        update_load(100);
        $code = $this->make->code();
        $json['code'] = $code;        
        $json['tbl_vals'] = $trans;
        $json['dates'] = $this->input->post('calendar_range');
        echo json_encode($json);
    }
    public function sales_rep_pdf()
    {
        // Include the main TCPDF library (search for installation path).
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Sales Report');
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

        // ---------------------------------------------------------
        $this->load->model('dine/setup_model');
        // $this->load->database('main', TRUE);
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
        
        $menu_cat_id = $_GET['menu_cat_id'];        
        $daterange = $_GET['calendar_range'];        
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        $trans = $this->menu_model->get_cat_sales_rep($from, $to, $menu_cat_id);
        $trans_ret = $this->menu_model->get_cat_sales_rep_retail($from, $to, "");
        $trans_mod = $this->menu_model->get_mod_cat_sales_rep($from, $to, $menu_cat_id);
        // $trans_payment = $this->menu_model->get_payment_date($from, $to);
        // echo $this->db->last_query();die();                  


        $pdf->Write(0, 'Sales Report', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Write(0, 'Report Period:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, $daterange, '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Transaction Time:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln();              

        // echo "<pre>", print_r($trans), "</pre>";die();

        // -----------------------------------------------------------------------------
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(75, 0, 'Category', 'B', 0, 'L');        
        $pdf->Cell(32, 0, 'Qty', 'B', 0, 'R');        
        // $pdf->Cell(32, 0, 'VAT Sales', 'B', 0, 'C');        
        // $pdf->Cell(32, 0, 'VAT', 'B', 0, 'C');        
        $pdf->Cell(32, 0, 'Gross', 'B', 0, 'R');        
        $pdf->Cell(32, 0, 'Sales (%)', 'B', 0, 'R');        
        $pdf->Cell(32, 0, 'Cost', 'B', 0, 'R');        
        $pdf->Cell(32, 0, 'Cost (%)', 'B', 0, 'R');        
        $pdf->Cell(32, 0, 'Margin', 'B', 0, 'R');        
        $pdf->ln();                  

        // GRAND TOTAL VARIABLES
        $tot_qty = 0;
        $tot_vat_sales = 0;
        $tot_vat = 0;
        $tot_gross = 0;
        $tot_mod_gross = 0;
        $tot_sales_prcnt = 0;
        $tot_cost = 0;
        $tot_cost_prcnt = 0; 
        $tot_margin = 0;
        $counter = 0;
        $progress = 0;
        $trans_count = count($trans);
        // echo print_r($trans);die();
        foreach ($trans as $val) {
            $tot_gross += $val->gross;
            $tot_cost += $val->cost;
        }
        foreach ($trans_mod as $vv) {
            $tot_mod_gross += $vv->mod_gross;
        }
        foreach ($trans as $k => $v) {
            $pdf->Cell(75, 0, $v->menu_cat_name, '', 0, 'L');        
            $pdf->Cell(32, 0, num($v->qty), '', 0, 'R');        
            // $pdf->Cell(32, 0, num($v->vat_sales), '', 0, 'R');        
            // $pdf->Cell(32, 0, num($v->vat), '', 0, 'R');        
            $pdf->Cell(32, 0, num($v->gross), '', 0, 'R');        
            $pdf->Cell(32, 0, num($v->gross / $tot_gross * 100)."%", '', 0, 'R');        
            $pdf->Cell(32, 0, num($v->cost), '', 0, 'R');                    
            if($tot_cost != 0){
                $pdf->Cell(32, 0, num($v->cost / $tot_cost * 100)."%", '', 0, 'R');                        
            }else{
                $pdf->Cell(32, 0, "0.00%", '', 0, 'R');                                        
            }
            $pdf->Cell(32, 0, num($v->gross - $v->cost), '', 0, 'R');        
            $pdf->ln();                

            // Grand Total
            $tot_qty += $v->qty;
            // $tot_vat_sales += $v->vat_sales;
            // $tot_vat += $v->vat;
            // $tot_gross += $v->gross;
            $tot_sales_prcnt = 0;
            // $tot_cost += $v->cost;
            $tot_margin += $v->gross - $v->cost;
            $tot_cost_prcnt = 0;

            $counter++;
            $progress = ($counter / $trans_count) * 100;
            update_load(num($progress));              
        }

        update_load(100);

        // update_load(100);        
        $pdf->Cell(75, 0, "Grand Total", 'T', 0, 'L');        
        $pdf->Cell(32, 0, num($tot_qty), 'T', 0, 'R');        
        // $pdf->Cell(32, 0, num($tot_vat_sales), 'T', 0, 'R');        
        // $pdf->Cell(32, 0, num($tot_vat), 'T', 0, 'R');        
        $pdf->Cell(32, 0, num($tot_gross), 'T', 0, 'R');        
        $pdf->Cell(32, 0, "", 'T', 0, 'R');        
        $pdf->Cell(32, 0, num($tot_cost), 'T', 0, 'R');        
        $pdf->Cell(32, 0, "", 'T', 0, 'R'); 
        $pdf->Cell(32, 0, num($tot_margin), 'T', 0, 'R'); 



        //retail
        $tot_gross_ret = 0;
        if(count($trans_ret) > 0){
            $pdf->ln(7);                
            $pdf->Cell(267, 0, 'Retail Items', '', 0, 'C');
            $pdf->ln(); 

            $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
            $pdf->Cell(75, 0, 'Category', 'B', 0, 'L');        
            $pdf->Cell(32, 0, 'Qty', 'B', 0, 'R');        
            // $pdf->Cell(32, 0, 'VAT Sales', 'B', 0, 'C');        
            // $pdf->Cell(32, 0, 'VAT', 'B', 0, 'C');        
            $pdf->Cell(32, 0, 'Gross', 'B', 0, 'R');        
            $pdf->Cell(32, 0, 'Sales (%)', 'B', 0, 'R');        
            $pdf->Cell(32, 0, 'Cost', 'B', 0, 'R');        
            $pdf->Cell(32, 0, 'Cost (%)', 'B', 0, 'R');        
            $pdf->Cell(32, 0, 'Margin', 'B', 0, 'R');        
            $pdf->ln();               

            $tot_qty = 0;
            $tot_vat_sales = 0;
            $tot_vat = 0;
            $tot_mod_gross = 0;
            $tot_sales_prcnt = 0;
            $tot_cost = 0;
            $tot_cost_prcnt = 0; 
            $tot_margin = 0;
            $counter = 0;
            $progress = 0;
            $trans_count = count($trans_ret);
            // echo print_r($trans);die();
            foreach ($trans_ret as $val) {
                $tot_gross_ret += $val->gross;
                // $tot_cost += $val->cost;
            }
            foreach ($trans_ret as $k => $v) {
                $pdf->Cell(75, 0, $v->name, '', 0, 'L');        
                $pdf->Cell(32, 0, num($v->qty), '', 0, 'R');        
                // $pdf->Cell(32, 0, num($v->vat_sales), '', 0, 'R');        
                // $pdf->Cell(32, 0, num($v->vat), '', 0, 'R');        
                $pdf->Cell(32, 0, num($v->gross), '', 0, 'R');        
                $pdf->Cell(32, 0, num($v->gross / $tot_gross_ret * 100)."%", '', 0, 'R');        
                $pdf->Cell(32, 0, num(0), '', 0, 'R');                    
                if($tot_cost != 0){
                    $pdf->Cell(32, 0, num($v->cost / $tot_cost * 100)."%", '', 0, 'R');                        
                }else{
                    $pdf->Cell(32, 0, "0.00%", '', 0, 'R');                                        
                }
                $pdf->Cell(32, 0, num($v->gross - 0), '', 0, 'R');        
                $pdf->ln();                

                // Grand Total
                $tot_qty += $v->qty;
                // $tot_vat_sales += $v->vat_sales;
                // $tot_vat += $v->vat;
                // $tot_gross += $v->gross;
                $tot_sales_prcnt = 0;
                // $tot_cost += $v->cost;
                $tot_margin += $v->gross - 0;
                $tot_cost_prcnt = 0;

                $counter++;
                $progress = ($counter / $trans_count) * 100;
                update_load(num($progress));              
            }

            update_load(100);        
            $pdf->Cell(75, 0, "Grand Total", 'T', 0, 'L');        
            $pdf->Cell(32, 0, num($tot_qty), 'T', 0, 'R');        
            // $pdf->Cell(32, 0, num($tot_vat_sales), 'T', 0, 'R');        
            // $pdf->Cell(32, 0, num($tot_vat), 'T', 0, 'R');        
            $pdf->Cell(32, 0, num($tot_gross_ret), 'T', 0, 'R');        
            $pdf->Cell(32, 0, "", 'T', 0, 'R');        
            $pdf->Cell(32, 0, num($tot_cost), 'T', 0, 'R');        
            $pdf->Cell(32, 0, "", 'T', 0, 'R'); 
            $pdf->Cell(32, 0, num($tot_margin), 'T', 0, 'R');
        }


        ///////////fpr payments
        $this->cashier_model->db = $this->load->database('main', TRUE);
        $args = array();
        // if($user)
        //     $args["trans_sales.user_id"] = array('use'=>'where','val'=>$user,'third'=>false);

        $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.inactive = 0"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        // $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);

        // $terminal = TERMINAL_ID;
        // $args['trans_sales.terminal_id'] = $terminal;
        // if($menu_cat_id != 0){
        //     $args["menu_categories.menu_cat_id"] = array('use'=>'where','val'=>$menu_cat_id,'third'=>false);
        // }


        // $post = $this->set_post();
        // $curr = $this->search_current();
        $curr = false;
        $trans = $this->trans_sales($args,$curr);
        // $trans = $this->trans_sales_cat($args,false);
        $sales = $trans['sales'];

        $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
        $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
        $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
        $tax_disc = $trans_discounts['tax_disc_total'];
        $no_tax_disc = $trans_discounts['no_tax_disc_total'];
        $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
        $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
        $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
        $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
        $payments = $this->payment_sales($sales['settled']['ids'],$curr);

        $gross = $trans_menus['gross'];

        $net = $trans['net'];
        $void = $trans['void'];
        $charges = $trans_charges['total'];
        $discounts = $trans_discounts['total'];
        $local_tax = $trans_local_tax['total'];
        $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;

        if($less_vat < 0)
            $less_vat = 0;


        $tax = $trans_tax['total'];
        $no_tax = $trans_no_tax['total'];
        $zero_rated = $trans_zero_rated['total'];
        $no_tax -= $zero_rated;

        $loc_txt = numInt(($local_tax));
        $net_no_adds = $net-($charges+$local_tax);
        $nontaxable = $no_tax - $no_tax_disc;
        $taxable =   ($gross - $discounts - $less_vat - $nontaxable) / 1.12;
        $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
        $add_gt = $taxable+$nontaxable+$zero_rated;
        $nsss = $taxable +  $nontaxable +  $zero_rated;

        $vat_ = $taxable * .12;

        $pdf->ln(7);
        $pdf->Cell(30, 0, 'GROSS', '', 0, 'L');
        $pdf->Cell(35, 0, num($tot_gross + $tot_mod_gross + $tot_gross_ret), '', 0, 'R');
        $pdf->ln();
        $pdf->Cell(30, 0, 'VAT SALES', '', 0, 'L');
        $pdf->Cell(35, 0, num($taxable), '', 0, 'R');
        $pdf->ln();
        $pdf->Cell(30, 0, 'VAT', '', 0, 'L');
        $pdf->Cell(35, 0, num($vat_), '', 0, 'R');
        $pdf->ln();
        $pdf->Cell(30, 0, 'VAT EXEMPT SALES', '', 0, 'L');
        $pdf->Cell(35, 0, num($nontaxable-$zero_rated), '', 0, 'R');
        $pdf->ln();
        $pdf->Cell(30, 0, 'ZERO RATED', '', 0, 'L');
        $pdf->Cell(35, 0, num($zero_rated), '', 0, 'R');


        //MENU SUB CAT
        $pdf->ln(7);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0, strtoupper('Sub Categories'), '', 0, 'L');
        $pdf->Cell(35, 0, strtoupper('Amount'), '', 0, 'R');
        $pdf->SetFont('helvetica', '', 9);

        $subcats = $trans_menus['sub_cats'];
        $qty = 0;
        $total = 0;
        foreach ($subcats as $id => $val) {
            $pdf->ln();
            $pdf->Cell(30, 0, strtoupper($val['name']), '', 0, 'L');
            $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
            $total += $val['amount'];
        }
        if($tot_gross_ret != 0){
            $pdf->ln();
            $pdf->Cell(30, 0, 'RETAIL', '', 0, 'L');
            $pdf->Cell(35, 0, num($tot_gross_ret), '', 0, 'R');
            $total += $tot_gross_ret;
        }

        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0,'SUBTOTAL ', 'T', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($total), 'T', 0, 'R');

        // numInt($trans_menus['mods_total'])
        $pdf->ln();
        // $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0,'MODIFIERS TOTAL ', '', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($trans_menus['mods_total']), '', 0, 'R');

        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0,'TOTAL ', 'T', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($total + $trans_menus['mods_total']), 'T', 0, 'R');


        //DISCOUNTS
        $pdf->ln(7);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(60, 0, strtoupper('Discount'), '', 0, 'L');
        $pdf->Cell(35, 0, strtoupper('Amount'), '', 0, 'R');
        $pdf->SetFont('helvetica', '', 9);

        $types = $trans_discounts['types'];
        foreach ($types as $code => $val) {
            $pdf->ln();
            $pdf->Cell(60, 0, strtoupper($val['name']), '', 0, 'L');
            $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
            
        }

        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(60, 0,'TOTAL ', 'T', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($discounts), 'T', 0, 'R');
        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(60, 0,'VAT EXEMPT ', '', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($less_vat), '', 0, 'R');


        //CAHRGES
        $pdf->ln(7);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0, strtoupper('Charges'), '', 0, 'L');
        $pdf->Cell(35, 0, strtoupper('Amount'), '', 0, 'R');
        $pdf->SetFont('helvetica', '', 9);
        // $pdf->ln();

        $types = $trans_charges['types'];
        foreach ($types as $code => $val) {
            $pdf->ln();
            $pdf->Cell(30, 0, strtoupper($val['name']), '', 0, 'L');
            $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
            
        }
           
        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0,'TOTAL ', 'T', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($charges), 'T', 0, 'R');


        //PAYMENTS
        $pdf->ln(7);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0, strtoupper('Payment Mode'), '', 0, 'L');
        $pdf->Cell(35, 0, strtoupper('Payment Amount'), '', 0, 'R');
        $pdf->SetFont('helvetica', '', 9);
        // $pdf->ln();


        $payments_types = $payments['types'];
        $payments_total = $payments['total'];
        foreach ($payments_types as $code => $val) {
        $pdf->ln();
            $pdf->Cell(30, 0, strtoupper($code), '', 0, 'L');
            $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
            
        }
           
        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0,'TOTAL ', 'T', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($payments_total), 'T', 0, 'R');


        if($trans['total_chit']){
            // $print_str .= append_chars(substrwords('TOTAL CHIT',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //               .append_chars(numInt($trans['total_chit']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            // $print_str .= "\r\n";
            $pdf->ln(7);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->Cell(30, 0,'TOTAL CHIT ', '', 0, 'L');
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(35, 0, num($trans['total_chit']), '', 0, 'R');
        }



        $types = $trans['types'];
        $types_total = array();
        $guestCount = 0;
        foreach ($types as $type => $tp) {
            foreach ($tp as $id => $opt){
                if(isset($types_total[$type])){
                    $types_total[$type] += round($opt->total_amount,2);

                }
                else{
                    $types_total[$type] = round($opt->total_amount,2);
                }
                if($opt->guest == 0)
                    $guestCount += 1;
                else
                    $guestCount += $opt->guest;
            }
        }

        $pdf->ln(7);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0,'TRANS COUNT ', '', 0, 'L');
        $tc_total  = 0;
        $tc_qty = 0;
        foreach ($types_total as $typ => $tamnt) {
            $pdf->SetFont('helvetica', '', 9);
            $pdf->ln();
            $pdf->Cell(30, 0, strtoupper($typ), '', 0, 'L');
            $pdf->Cell(20, 0, count($types[$typ]), '', 0, 'L');
            $pdf->Cell(25, 0, num($tamnt), '', 0, 'R');
            $tc_total += $tamnt;
            $tc_qty += count($types[$typ]);
        }
        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(50, 0, 'TC TOTAL', 'T', 0, 'L');
            $pdf->SetFont('helvetica', '', 9);
        // $pdf->Cell(20, 0, count($types[$typ]), '', 0, 'L');
        $pdf->Cell(25, 0, num($tc_total), 'T', 0, 'R');


        

        // -----------------------------------------------------------------------------

        // $tbl = '<table cellspacing="0" cellpadding="1">';
        // $tbl .= "<tr>";
        // $tbl .= "<th style='width:60px;'>Category</th>"; 
        // $tbl .= "<th>Qty</th>";
        // $tbl .= "<th>VAT Sales</th>";
        // $tbl .= "<th>VAT</th>";
        // $tbl .= "<th>Gross</th>";
        // $tbl .= "<th>Sales (%)</th>";
        // $tbl .= "<th>Cost</th>";
        // $tbl .= "<th>Cost (%)</th>";
        // $tbl .= "</tr>";
        // foreach ($trans as $k => $v) {
        //     $tbl .= "<tr>";
        //         $tbl .= '<td>'.$v->menu_cat_name."</td>";             
        //         $tbl .= "<td style='text-align:right;'>".num($v->qty)."</td>";             
        //         $tbl .= "<td style='text-align:right;'>".num($v->vat_sales)."</td>"; 
        //         $tbl .= "<td style='text-align:right;'>".num($v->vat)."</td>";                 
        //         $tbl .= "<td style='text-align:right;'>".num($v->gross)."</td>";                 
        //         $tbl .= "<td style='text-align:right;'>".num(0)."</td>";                 
        //         $tbl .= "<td style='text-align:right;'>".num($v->cost)."</td>";                 
        //         $tbl .= "<td style='text-align:right;'>".num(0)."</td>";                 
        //     $tbl .= "</tr>";         
        // }
        // $tbl .= "</table>";
        
        // $pdf->writeHTML($tbl, true, false, false, false, '');

        // -----------------------------------------------------------------------------

        //Close and output PDF document
        $pdf->Output('sales_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+   
    }
    public function menu_sales_rep_gen()
    {
        $this->load->model('dine/setup_model');
        // $this->load->database('main', TRUE);
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];
        start_load(0);
        $menu_cat_id = $this->input->post("menu_cat_id");        
        // $brand = $this->input->post("brand");        
        $daterange = $this->input->post("calendar_range");        
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        // $date = $this->input->post("date");
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        $trans = $this->menu_model->get_menu_sales_rep($from, $to, $menu_cat_id);
        $trans_ret = $this->menu_model->get_menu_sales_rep_retail($from, $to, "");
                        
        // echo "<pre>", print_r($trans), "</pre>";
        $trans_count = count($trans);
        $counter = 0;
        $this->make->sDiv();
            $this->make->sTable(array('class'=>'table reportTBL'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('Menu');
                        $this->make->th('Category');
                        $this->make->th('Qty');
                        // $this->make->th('VAT Sales');
                        // $this->make->th('VAT');
                        $this->make->th('Gross');
                        $this->make->th('Sales (%)');
                        $this->make->th('Cost');
                        $this->make->th('Cost (%)');
                        $this->make->th('Margin');
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();
                    $tot_qty = 0;
                    $tot_vat_sales = 0;
                    $tot_vat = 0;
                    $tot_gross = 0;
                    $tot_sales_prcnt = 0;
                    $tot_cost = 0;
                    $tot_margin = 0;
                    $tot_cost_prcnt = 0;
                    foreach ($trans as $val) {
                        $tot_gross += $val->gross;
                        $tot_cost += $val->cost;
                        $tot_margin += $val->gross - $val->cost;
                    }
                    foreach ($trans as $res) {
                        $this->make->sRow();
                            $this->make->td($res->menu_name);
                            $this->make->td($res->menu_cat_name);
                            $this->make->td(num($res->qty), array("style"=>"text-align:right"));                            
                            // $this->make->td(num($res->vat_sales), array("style"=>"text-align:right"));                            
                            // $this->make->td(num($res->vat), array("style"=>"text-align:right"));                            
                            $this->make->td(num($res->gross), array("style"=>"text-align:right"));                            
                            if($tot_gross != 0){
                                $this->make->td(num($res->gross / $tot_gross * 100)."%", array("style"=>"text-align:right"));                                
                            }else{
                                $this->make->td("0.00%", array("style"=>"text-align:right"));                                
                            }
                            $this->make->td(num($res->cost), array("style"=>"text-align:right"));                            
                            if($tot_cost != 0){
                                $this->make->td(num($res->cost / $tot_cost * 100)."%", array("style"=>"text-align:right"));                            
                            }else{
                                $this->make->td("0.00%", array("style"=>"text-align:right"));                                
                            }
                            $this->make->td(num($res->gross - $res->cost), array("style"=>"text-align:right")); 

                        $this->make->eRow();

                        /// Grand Total
                        $tot_qty += $res->qty;
                        // $tot_vat_sales += $res->vat_sales;
                        // $tot_vat += $res->vat;
                        // $tot_gross += $res->gross;
                        $tot_sales_prcnt = 0;
                        // $tot_cost += $res->cost;
                        $tot_cost_prcnt = 0;

                        $counter++;
                        $progress = ($counter / $trans_count) * 100;
                        update_load(num($progress));

                    }     
                    $this->make->sRow();
                        $this->make->th('Grand Total');
                        $this->make->th("");
                        $this->make->th(num($tot_qty), array("style"=>"text-align:right"));
                        // $this->make->th(num($tot_vat_sales), array("style"=>"text-align:right"));
                        // $this->make->th(num($tot_vat), array("style"=>"text-align:right"));
                        $this->make->th(num($tot_gross), array("style"=>"text-align:right"));
                        $this->make->th("", array("style"=>"text-align:right"));
                        $this->make->th(num($tot_cost), array("style"=>"text-align:right"));
                        $this->make->th("", array("style"=>"text-align:right"));                        
                        $this->make->th(num($tot_margin), array("style"=>"text-align:right"));                          
                    $this->make->eRow();                                 
                $this->make->eTableBody();
            $this->make->eTable();
        $this->make->eDiv();
        $this->make->append('<center><h4>Retail Items</h4></center>');
        $this->make->sDiv();
            $this->make->sTable(array('class'=>'table reportTBL'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('Menu');
                        $this->make->th('Category');
                        $this->make->th('Qty');
                        // $this->make->th('VAT Sales');
                        // $this->make->th('VAT');
                        $this->make->th('Gross');
                        $this->make->th('Sales (%)');
                        $this->make->th('Cost');
                        $this->make->th('Cost (%)');
                        $this->make->th('Margin');
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();
                    $tot_qty = 0;
                    $tot_vat_sales = 0;
                    $tot_vat = 0;
                    $tot_gross = 0;
                    $tot_sales_prcnt = 0;
                    $tot_cost = 0;
                    $tot_margin = 0;
                    $tot_cost_prcnt = 0;
                    foreach ($trans_ret as $val) {
                        $tot_gross += $val->gross;
                        // $tot_cost += $val->cost;
                        $tot_margin += $val->gross - 0;
                    }
                    foreach ($trans_ret as $res) {
                        $this->make->sRow();
                            $this->make->td($res->item_name);
                            $this->make->td($res->cat_name);
                            $this->make->td(num($res->qty), array("style"=>"text-align:right"));                            
                            // $this->make->td(num($res->vat_sales), array("style"=>"text-align:right"));                            
                            // $this->make->td(num($res->vat), array("style"=>"text-align:right"));                            
                            $this->make->td(num($res->gross), array("style"=>"text-align:right"));                            
                            if($tot_gross != 0){
                                $this->make->td(num($res->gross / $tot_gross * 100)."%", array("style"=>"text-align:right"));                                
                            }else{
                                $this->make->td("0.00%", array("style"=>"text-align:right"));                                
                            }
                            $this->make->td(num(0), array("style"=>"text-align:right"));                            
                            if($tot_cost != 0){
                                $this->make->td(num($res->cost / $tot_cost * 100)."%", array("style"=>"text-align:right"));                            
                            }else{
                                $this->make->td("0.00%", array("style"=>"text-align:right"));                                
                            }
                            $this->make->td(num($res->gross - 0), array("style"=>"text-align:right")); 

                        $this->make->eRow();

                        /// Grand Total
                        $tot_qty += $res->qty;
                        // $tot_vat_sales += $res->vat_sales;
                        // $tot_vat += $res->vat;
                        // $tot_gross += $res->gross;
                        $tot_sales_prcnt = 0;
                        // $tot_cost += $res->cost;
                        $tot_cost_prcnt = 0;

                        // $counter++;
                        // $progress = ($counter / $trans_count) * 100;
                        // update_load(num($progress));

                    }     
                    $this->make->sRow();
                        $this->make->th('Grand Total');
                        $this->make->th("");
                        $this->make->th(num($tot_qty), array("style"=>"text-align:right"));
                        // $this->make->th(num($tot_vat_sales), array("style"=>"text-align:right"));
                        // $this->make->th(num($tot_vat), array("style"=>"text-align:right"));
                        $this->make->th(num($tot_gross), array("style"=>"text-align:right"));
                        $this->make->th("", array("style"=>"text-align:right"));
                        $this->make->th(num($tot_cost), array("style"=>"text-align:right"));
                        $this->make->th("", array("style"=>"text-align:right"));                        
                        $this->make->th(num($tot_margin), array("style"=>"text-align:right"));                          
                    $this->make->eRow();                                 
                $this->make->eTableBody();
            $this->make->eTable();
        $this->make->eDiv();
        update_load(100);
        $code = $this->make->code();
        $json['code'] = $code;        
        $json['tbl_vals'] = $trans;
        $json['dates'] = $this->input->post('calendar_range');
        echo json_encode($json);
    }
    public function menu_sales_rep_pdf()
    {
        // Include the main TCPDF library (search for installation path).
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Sales Report');
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

        // ---------------------------------------------------------

        // $this->load->database('main', TRUE);
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
        
        $menu_cat_id = $_GET['menu_cat_id'];        
        $daterange = $_GET['calendar_range'];        
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        $trans = $this->menu_model->get_menu_sales_rep($from, $to, $menu_cat_id);
        $trans_ret = $this->menu_model->get_menu_sales_rep_retail($from, $to, "");
        $trans_mod = $this->menu_model->get_mod_menu_sales_rep($from, $to, $menu_cat_id);
        // $trans_payment = $this->menu_model->get_payment_date($from, $to);                  

        $pdf->Write(0, 'Sales Report', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Write(0, 'Report Period:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, $daterange, '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Transaction Time:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln();              

        // echo "<pre>", print_r($trans), "</pre>";die();

        // -----------------------------------------------------------------------------
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(55, 0, 'Menu', 'B', 0, 'L');        
        $pdf->Cell(38, 0, 'Category', 'B', 0, 'L');        
        $pdf->Cell(25, 0, 'Qty', 'B', 0, 'R');        
        // $pdf->Cell(25, 0, 'VAT Sales', 'B', 0, 'C');        
        // $pdf->Cell(25, 0, 'VAT', 'B', 0, 'C');        
        $pdf->Cell(25, 0, 'Gross', 'B', 0, 'R');        
        $pdf->Cell(25, 0, 'Sales (%)', 'B', 0, 'R');        
        $pdf->Cell(25, 0, 'Cost', 'B', 0, 'R');        
        $pdf->Cell(25, 0, 'Cost (%)', 'B', 0, 'R');        
        $pdf->Cell(50, 0, 'Margin', 'B', 0, 'R');        
        $pdf->ln();                  

        // GRAND TOTAL VARIABLES
        $tot_qty = 0;
        $tot_vat_sales = 0;
        $tot_vat = 0;
        $tot_gross = 0;
        $tot_mod_gross = 0;
        $tot_sales_prcnt = 0;
        $tot_cost = 0;
        $tot_cost_prcnt = 0; 
        $tot_margin = 0; 
        $counter = 0;
        $progress = 0;
        $trans_count = count($trans);
        foreach ($trans as $val) {
            $tot_gross += $val->gross;
            $tot_cost += $val->cost;
            $tot_margin += $val->gross - $val->cost;
        }
        foreach ($trans_mod as $vv) {
            $tot_mod_gross += $vv->mod_gross;
        }

        foreach ($trans as $k => $v) {
            $pdf->Cell(55, 0, $v->menu_name, '', 0, 'L');        
            $pdf->Cell(38, 0, $v->menu_cat_name, '', 0, 'L');        
            $pdf->Cell(25, 0, num($v->qty), '', 0, 'R');        
            // $pdf->Cell(25, 0, num($v->vat_sales), '', 0, 'R');        
            // $pdf->Cell(25, 0, num($v->vat), '', 0, 'R');        
            $pdf->Cell(25, 0, num($v->gross), '', 0, 'R');        
            if($tot_gross != 0){
                $pdf->Cell(25, 0, num($v->gross / $tot_gross * 100)."%", '', 0, 'R');                        
            }else{
                $pdf->Cell(25, 0, "0.00%", '', 0, 'R');                                        
            }
            $pdf->Cell(25, 0, num($v->cost), '', 0, 'R');        
            if($tot_cost != 0){
                $pdf->Cell(25, 0, num($v->cost / $tot_cost * 100)."%", '', 0, 'R');                    
            }else{
                $pdf->Cell(25, 0, "0.00%", '', 0, 'R');                                    
            }
            $pdf->Cell(50, 0, num($v->gross - $v->cost * 100), '', 0, 'R');                    
            $pdf->ln();                

            // Grand Total
            $tot_qty += $v->qty;
            // $tot_vat_sales += $v->vat_sales;
            // $tot_vat += $v->vat;
            // $tot_gross += $v->gross;
            $tot_sales_prcnt = 0;
            // $tot_cost += $v->cost;
            $tot_cost_prcnt = 0;

            $counter++;
            $progress = ($counter / $trans_count) * 100;
            update_load(num($progress));              
        }
        update_load(100);        
        $pdf->Cell(55, 0, "Grand Total", 'T', 0, 'L');        
        $pdf->Cell(38, 0, "", 'T', 0, 'L');        
        $pdf->Cell(25, 0, num($tot_qty), 'T', 0, 'R');        
        // $pdf->Cell(25, 0, num($tot_vat_sales), 'T', 0, 'R');        
        // $pdf->Cell(25, 0, num($tot_vat), 'T', 0, 'R');        
        $pdf->Cell(25, 0, num($tot_gross), 'T', 0, 'R');        
        $pdf->Cell(25, 0, "", 'T', 0, 'R');        
        $pdf->Cell(25, 0, num($tot_cost), 'T', 0, 'R');        
        $pdf->Cell(25, 0, "", 'T', 0, 'R');        
        $pdf->Cell(50, 0, num($tot_margin), 'T', 0, 'R'); 

        //retail
        $tot_gross_ret = 0;
        if(count($trans_ret) > 0){
            $pdf->ln(7);                
            $pdf->Cell(267, 0, 'Retail Items', '', 0, 'C');
            $pdf->ln();

            $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
            $pdf->Cell(55, 0, 'Menu', 'B', 0, 'L');        
            $pdf->Cell(38, 0, 'Category', 'B', 0, 'L');        
            $pdf->Cell(25, 0, 'Qty', 'B', 0, 'R');        
            // $pdf->Cell(25, 0, 'VAT Sales', 'B', 0, 'C');        
            // $pdf->Cell(25, 0, 'VAT', 'B', 0, 'C');        
            $pdf->Cell(25, 0, 'Gross', 'B', 0, 'R');        
            $pdf->Cell(25, 0, 'Sales (%)', 'B', 0, 'R');        
            $pdf->Cell(25, 0, 'Cost', 'B', 0, 'R');        
            $pdf->Cell(25, 0, 'Cost (%)', 'B', 0, 'R');        
            $pdf->Cell(50, 0, 'Margin', 'B', 0, 'R');        
            $pdf->ln();                  

            // GRAND TOTAL VARIABLES
            $tot_qty = 0;
            $tot_vat_sales = 0;
            $tot_vat = 0;
            $tot_mod_gross = 0;
            $tot_sales_prcnt = 0;
            $tot_cost = 0;
            $tot_cost_prcnt = 0; 
            $tot_margin = 0; 
            $counter = 0;
            $progress = 0;
            $trans_count = count($trans);
            foreach ($trans_ret as $val) {
                $tot_gross_ret += $val->gross;
                // $tot_cost += $val->cost;
                $tot_margin += $val->gross - 0;
            }

            foreach ($trans_ret as $k => $v) {
                $pdf->Cell(55, 0, $v->item_name, '', 0, 'L');        
                $pdf->Cell(38, 0, $v->cat_name, '', 0, 'L');        
                $pdf->Cell(25, 0, num($v->qty), '', 0, 'R');        
                // $pdf->Cell(25, 0, num($v->vat_sales), '', 0, 'R');        
                // $pdf->Cell(25, 0, num($v->vat), '', 0, 'R');        
                $pdf->Cell(25, 0, num($v->gross), '', 0, 'R');        
                if($tot_gross != 0){
                    $pdf->Cell(25, 0, num($v->gross / $tot_gross * 100)."%", '', 0, 'R');                        
                }else{
                    $pdf->Cell(25, 0, "0.00%", '', 0, 'R');                                        
                }
                $pdf->Cell(25, 0, num(0), '', 0, 'R');        
                if($tot_cost != 0){
                    $pdf->Cell(25, 0, num($v->cost / $tot_cost * 100)."%", '', 0, 'R');                    
                }else{
                    $pdf->Cell(25, 0, "0.00%", '', 0, 'R');                                    
                }
                $pdf->Cell(50, 0, num($v->gross - 0 * 100), '', 0, 'R');                    
                $pdf->ln();                

                // Grand Total
                $tot_qty += $v->qty;
                // $tot_vat_sales += $v->vat_sales;
                // $tot_vat += $v->vat;
                // $tot_gross += $v->gross;
                $tot_sales_prcnt = 0;
                // $tot_cost += $v->cost;
                $tot_cost_prcnt = 0;

                // $counter++;
                // $progress = ($counter / $trans_count) * 100;
                // update_load(num($progress));              
            }
            update_load(100);        
            $pdf->Cell(55, 0, "Grand Total", 'T', 0, 'L');        
            $pdf->Cell(38, 0, "", 'T', 0, 'L');        
            $pdf->Cell(25, 0, num($tot_qty), 'T', 0, 'R');        
            // $pdf->Cell(25, 0, num($tot_vat_sales), 'T', 0, 'R');        
            // $pdf->Cell(25, 0, num($tot_vat), 'T', 0, 'R');        
            $pdf->Cell(25, 0, num($tot_gross_ret), 'T', 0, 'R');        
            $pdf->Cell(25, 0, "", 'T', 0, 'R');        
            $pdf->Cell(25, 0, num($tot_cost), 'T', 0, 'R');        
            $pdf->Cell(25, 0, "", 'T', 0, 'R');        
            $pdf->Cell(50, 0, num($tot_margin), 'T', 0, 'R');
        }



        ///////////fpr payments
        $this->cashier_model->db = $this->load->database('main', TRUE);
        $args = array();
        // if($user)
        //     $args["trans_sales.user_id"] = array('use'=>'where','val'=>$user,'third'=>false);

        $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.inactive = 0"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);

        // if($menu_cat_id != 0){
        //     $args["menu_categories.menu_cat_id"] = array('use'=>'where','val'=>$menu_cat_id,'third'=>false);
        // }


        $post = $this->set_post();
        // $curr = $this->search_current();
        $curr = false;
        $trans = $this->trans_sales($args,$curr);
        $sales = $trans['sales'];

        $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
        $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
        $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
        $tax_disc = $trans_discounts['tax_disc_total'];
        $no_tax_disc = $trans_discounts['no_tax_disc_total'];
        $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
        $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
        $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
        $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
        $payments = $this->payment_sales($sales['settled']['ids'],$curr);

        $gross = $trans_menus['gross'];

        $net = $trans['net'];
        $void = $trans['void'];
        $charges = $trans_charges['total'];
        $discounts = $trans_discounts['total'];
        $local_tax = $trans_local_tax['total'];
        $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;

        if($less_vat < 0)
            $less_vat = 0;

        $tax = $trans_tax['total'];
        $no_tax = $trans_no_tax['total'];
        $zero_rated = $trans_zero_rated['total'];
        $no_tax -= $zero_rated;

        $loc_txt = numInt(($local_tax));
        $net_no_adds = $net-($charges+$local_tax);
        $nontaxable = $no_tax - $no_tax_disc;
        $taxable =   ($gross - $discounts - $less_vat - $nontaxable) / 1.12;
        $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
        $add_gt = $taxable+$nontaxable+$zero_rated;
        $nsss = $taxable +  $nontaxable +  $zero_rated;

        $vat_ = $taxable * .12;

        $pdf->ln(7);
        $pdf->Cell(30, 0, 'GROSS', '', 0, 'L');
        $pdf->Cell(35, 0, num($tot_gross + $tot_mod_gross + $tot_gross_ret), '', 0, 'R');
        $pdf->ln();
        $pdf->Cell(30, 0, 'VAT SALES', '', 0, 'L');
        $pdf->Cell(35, 0, num($taxable), '', 0, 'R');
        $pdf->ln();
        $pdf->Cell(30, 0, 'VAT', '', 0, 'L');
        $pdf->Cell(35, 0, num($vat_), '', 0, 'R');
        $pdf->ln();
        $pdf->Cell(30, 0, 'VAT EXEMPT SALES', '', 0, 'L');
        $pdf->Cell(35, 0, num($nontaxable-$zero_rated), '', 0, 'R');
        $pdf->ln();
        $pdf->Cell(30, 0, 'ZERO RATED', '', 0, 'L');
        $pdf->Cell(35, 0, num($zero_rated), '', 0, 'R');

        //MENU SUB CAT
        $pdf->ln(7);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0, strtoupper('Sub Categories'), '', 0, 'L');
        $pdf->Cell(35, 0, strtoupper('Amount'), '', 0, 'R');
        $pdf->SetFont('helvetica', '', 9);

        $subcats = $trans_menus['sub_cats'];
        $qty = 0;
        $total = 0;
        foreach ($subcats as $id => $val) {
            $pdf->ln();
            $pdf->Cell(30, 0, strtoupper($val['name']), '', 0, 'L');
            $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
            $total += $val['amount'];
        }
        if($tot_gross_ret != 0){
            $pdf->ln();
            $pdf->Cell(30, 0, 'RETAIL', '', 0, 'L');
            $pdf->Cell(35, 0, num($tot_gross_ret), '', 0, 'R');
            $total += $tot_gross_ret;
        }

        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0,'SUBTOTAL ', 'T', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($total), 'T', 0, 'R');

        // numInt($trans_menus['mods_total'])
        $pdf->ln();
        // $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0,'MODIFIERS TOTAL ', '', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($trans_menus['mods_total']), '', 0, 'R');

        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0,'TOTAL ', 'T', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($total + $trans_menus['mods_total']), 'T', 0, 'R');


        //DISCOUNTS
        $pdf->ln(7);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(60, 0, strtoupper('Discount'), '', 0, 'L');
        $pdf->Cell(35, 0, strtoupper('Amount'), '', 0, 'R');
        $pdf->SetFont('helvetica', '', 9);

        $types = $trans_discounts['types'];
        foreach ($types as $code => $val) {
            $pdf->ln();
            $pdf->Cell(60, 0, strtoupper($val['name']), '', 0, 'L');
            $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
            
        }

        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(60, 0,'TOTAL ', 'T', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($discounts), 'T', 0, 'R');
        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(60, 0,'VAT EXEMPT ', '', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($less_vat), '', 0, 'R');


        //CAHRGES
        $pdf->ln(7);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0, strtoupper('Charges'), '', 0, 'L');
        $pdf->Cell(35, 0, strtoupper('Amount'), '', 0, 'R');
        $pdf->SetFont('helvetica', '', 9);
        // $pdf->ln();

        $types = $trans_charges['types'];
        foreach ($types as $code => $val) {
            $pdf->ln();
            $pdf->Cell(30, 0, strtoupper($val['name']), '', 0, 'L');
            $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
            
        }
           
        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0,'TOTAL ', 'T', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($charges), 'T', 0, 'R');


        //PAYMENTS
        $pdf->ln(7);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0, strtoupper('Payment Mode'), '', 0, 'L');
        $pdf->Cell(35, 0, strtoupper('Payment Amount'), '', 0, 'R');
        $pdf->SetFont('helvetica', '', 9);
        // $pdf->ln();


        $payments_types = $payments['types'];
        $payments_total = $payments['total'];
        foreach ($payments_types as $code => $val) {
        $pdf->ln();
            $pdf->Cell(30, 0, strtoupper($code), '', 0, 'L');
            $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
            
        }
           
        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0,'TOTAL ', 'T', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($payments_total), 'T', 0, 'R');


        if($trans['total_chit']){
            // $print_str .= append_chars(substrwords('TOTAL CHIT',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //               .append_chars(numInt($trans['total_chit']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            // $print_str .= "\r\n";
            $pdf->ln(7);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->Cell(30, 0,'TOTAL CHIT ', '', 0, 'L');
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(35, 0, num($trans['total_chit']), '', 0, 'R');
        }



        $types = $trans['types'];
        $types_total = array();
        $guestCount = 0;
        foreach ($types as $type => $tp) {
            foreach ($tp as $id => $opt){
                if(isset($types_total[$type])){
                    $types_total[$type] += round($opt->total_amount,2);

                }
                else{
                    $types_total[$type] = round($opt->total_amount,2);
                }
                if($opt->guest == 0)
                    $guestCount += 1;
                else
                    $guestCount += $opt->guest;
            }
        }

        $pdf->ln(7);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0,'TRANS COUNT ', '', 0, 'L');
        $tc_total  = 0;
        $tc_qty = 0;
        foreach ($types_total as $typ => $tamnt) {
            $pdf->SetFont('helvetica', '', 9);
            $pdf->ln();
            $pdf->Cell(30, 0, strtoupper($typ), '', 0, 'L');
            $pdf->Cell(20, 0, count($types[$typ]), '', 0, 'L');
            $pdf->Cell(25, 0, num($tamnt), '', 0, 'R');
            $tc_total += $tamnt;
            $tc_qty += count($types[$typ]);
        }
        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(50, 0, 'TC TOTAL', 'T', 0, 'L');
            $pdf->SetFont('helvetica', '', 9);
        // $pdf->Cell(20, 0, count($types[$typ]), '', 0, 'L');
        $pdf->Cell(25, 0, num($tc_total), 'T', 0, 'R');

        
 


        // -----------------------------------------------------------------------------

        // $tbl = '<table cellspacing="0" cellpadding="1">';
        // $tbl .= "<tr>";
        // $tbl .= "<th style='width:60px;'>Category</th>"; 
        // $tbl .= "<th>Qty</th>";
        // $tbl .= "<th>VAT Sales</th>";
        // $tbl .= "<th>VAT</th>";
        // $tbl .= "<th>Gross</th>";
        // $tbl .= "<th>Sales (%)</th>";
        // $tbl .= "<th>Cost</th>";
        // $tbl .= "<th>Cost (%)</th>";
        // $tbl .= "</tr>";
        // foreach ($trans as $k => $v) {
        //     $tbl .= "<tr>";
        //         $tbl .= '<td>'.$v->menu_cat_name."</td>";             
        //         $tbl .= "<td style='text-align:right;'>".num($v->qty)."</td>";             
        //         $tbl .= "<td style='text-align:right;'>".num($v->vat_sales)."</td>"; 
        //         $tbl .= "<td style='text-align:right;'>".num($v->vat)."</td>";                 
        //         $tbl .= "<td style='text-align:right;'>".num($v->gross)."</td>";                 
        //         $tbl .= "<td style='text-align:right;'>".num(0)."</td>";                 
        //         $tbl .= "<td style='text-align:right;'>".num($v->cost)."</td>";                 
        //         $tbl .= "<td style='text-align:right;'>".num(0)."</td>";                 
        //     $tbl .= "</tr>";         
        // }
        // $tbl .= "</table>";
        
        // $pdf->writeHTML($tbl, true, false, false, false, '');

        // -----------------------------------------------------------------------------

        //Close and output PDF document
        $pdf->Output('sales_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+   
    }
    public function dtr_rep()
    {
        $data = $this->syter->spawn('sales_rep');        
        $data['page_title'] = fa('fa-clock-o')." DTR";
        $data['code'] = dtrRep();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['page_no_padding'] = false;
        $data['sideBarHide'] = false;
        $data['load_js'] = 'dine/reporting';
        $data['use_js'] = 'dtrRepJS';
        $this->load->view('page',$data);
    }
    public function dtr_rep_gen()
    {        
        $this->load->model("dine/reports_model");
        $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
               
        $daterange = $this->input->post("calendar_range");       
        $start_time = $this->input->post("start_time");       
        $end_time = $this->input->post("end_time");       
        $dates = explode(" to ",$daterange);
        $from = date2SqlDateTime($dates[0]. " ". $start_time);
        $to = date2SqlDateTime($dates[1]. " ". $end_time);                
        $trans = $this->reports_model->get_dtr($from, $to);
        // echo "<pre>", print_r($trans), "</pre>";
        $trans_count = count($trans);
        $counter = 0;
        $this->make->sDiv();
            $this->make->sTable(array('class'=>'table reportTBL'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('Date Time');
                        $this->make->th('Type');
                        $this->make->th('Employee Name');
                        $this->make->th('Emp Code');                        
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();
                    foreach ($trans as $res) {
                        $this->make->sRow();
                            $this->make->td($res->datetime);
                            $this->make->td($res->type);
                            $this->make->td($res->fname. " ".$res->mname." ".$res->lname);
                            $this->make->td($res->username);                            
                        $this->make->eRow();

                        $counter++;
                        $progress = ($counter / $trans_count) * 100;
                        update_load(num($progress));

                    }    
                                           
                $this->make->eTableBody();
            $this->make->eTable();
        $this->make->eDiv();

       $this->make->sDiv();
        $this->make->sTable(array('class'=>'table reportTBL'));
             $trans2 = $this->reports_model->get_total_hours($from, $to);                         
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('Total Hours:');
                        $this->make->th('');
                    $this->make->eRow();                        
                $this->make->eTableHead();
                $this->make->sTableBody();                
                    foreach ($trans2 as $res) {
                        $this->make->sRow();
                            $this->make->td($res["fname"]. " ".$res["mname"]." ".$res["lname"]);
                            $this->make->td($res["time_to_sec"]);                            
                        $this->make->eRow();                        
                    }    
                                           
                $this->make->eTableBody();
            $this->make->eTable();
        $this->make->eDiv();
        update_load(100);
        $code = $this->make->code();
        $json['code'] = $code;        
        $json['tbl_vals'] = $trans;
        $json['dates'] = $this->input->post('calendar_range');
        echo json_encode($json);
    }
    public function dtr_rep_pdf()
    {
        // Include the main TCPDF library (search for installation path).
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model('dine/setup_model');     
        date_default_timezone_set('Asia/Manila');   

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('DTR');
        $pdf->SetSubject('');
        $pdf->SetKeywords('');

        // set default header data
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "BARCINO RETAIL CORPORATION", $set->address);

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

        // ---------------------------------------------------------
        $this->load->model("dine/reports_model");
        $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
               
        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
        
        $daterange = $this->input->get("calendar_range");       
        $start_time = $this->input->get("start_time");       
        $end_time = $this->input->get("end_time");       
        $dates = explode(" to ",$daterange);
        $from = date2SqlDateTime($dates[0]. " ". $start_time);
        $to = date2SqlDateTime($dates[1]. " ". $end_time);                        
        // $date = $this->input->post("date");
        // $from = date2SqlDateTime($date. " ".$set->store_open);        
        // $to = date2SqlDateTime(date('Y-m-d', strtotime($date . ' +1 day')). " ".$set->store_close);
        $trans = $this->reports_model->get_dtr($from, $to);
        $total_hours = $this->reports_model->get_total_hours($from, $to); 
        
        $pdf->Write(0, 'EMPLOYEE DTR REPORT', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(180, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Write(0, 'Report Period:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, $daterange, '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(100);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Transaction Time:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(100);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(180, 0, '', 'T', 0, 'C');
        $pdf->ln();              

        // echo "<pre>", print_r($trans), "</pre>";die();
           
        // -----------------------------------------------------------------------------
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(43, 0, 'Date Time', 'B', 0, 'L');        
        $pdf->Cell(40, 0, 'Type', 'B', 0, 'L');        
        $pdf->Cell(65, 0, 'Employee Name', 'B', 0, 'L');        
        $pdf->Cell(32, 0, 'Emp Code', 'B', 0, 'L');        
        $pdf->ln();                  

        // GRAND TOTAL VARIABLES
        $counter = 0;
        $progress = 0;
        $trans_count = count($trans);        
              
        foreach ($trans as $k => $v) {
            $pdf->Cell(43, 0, $v->datetime, '', 0, 'L');        
            $pdf->Cell(40, 0, $v->type, '', 0, 'L');        
            $pdf->Cell(65, 0, $v->fname. " ".$v->mname." ".$v->lname, '', 0, 'L');        
            $pdf->Cell(32, 0, $v->username, '', 0, 'L');                    
            $pdf->ln();                

            $counter++;
            $progress = ($counter / $trans_count) * 100;
            update_load(num($progress));              
        }
        update_load(100);        
        $pdf->Cell(180, 0, "Total Hours", 'T', 0, 'L');
        $pdf->ln();
        foreach ($total_hours as $k => $v) {                
            $pdf->Cell(43, 0, $v["fname"]. " ".$v["mname"]." ".$v["lname"], '', 0, 'L');        
            $pdf->Cell(40, 0, $v["time_to_sec"], '', 0, 'L');                    
            $pdf->ln();                
        }        

        // -----------------------------------------------------------------------------

        //Close and output PDF document
        $pdf->Output('dtr_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+   
    }

    public function dtr_rep_excel(){   
        date_default_timezone_set('Asia/Manila');

        $this->load->model("dine/reports_model");
        $this->load->database('main', TRUE);

        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $filename = 'Employee DTR Report';
        $rc=1;

                            
        // $date = $this->input->post("date");
        // $from = date2SqlDateTime($date. " ".$set->store_open);        
        // $to = date2SqlDateTime(date('Y-m-d', strtotime($date . ' +1 day')). " ".$set->store_close);

        #GET VALUES
            start_load(0);
            $daterange = $this->input->get("calendar_range");       
            $start_time = $this->input->get("start_time");       
            $end_time = $this->input->get("end_time");       
            $dates = explode(" to ",$daterange);
            $from = date2SqlDateTime($dates[0]. " ". $start_time);
            $to = date2SqlDateTime($dates[1]. " ". $end_time);    
            $curr = true;
            update_load(10);
            $trans = $this->reports_model->get_dtr($from, $to);
            update_load(15);
            $total_hours = $this->reports_model->get_total_hours($from, $to);
            
            update_load(80);
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
                'size' => 14,
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
        $styleTitle = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'size' => 13
            )
        );

        $styleBorder =  array(
            'borders' => array(
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ),
                'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
            )
            ));
        
        $headers = array('Date Time','Type','Employee Name','Emp Code');
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(50);
        $sheet->getColumnDimension('D')->setWidth(20);

        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('BARCINO RETAIL CORPORATION');
        $sheet->getStyle('A'.$rc.':D'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->address);
        $sheet->getStyle('A'.$rc.':D'.$rc)->applyFromArray($styleTitle);
        $rc++;
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('EMPLOYEE DTR REPORT');
        $sheet->getStyle('A'.$rc.':D'.$rc)->applyFromArray($styleBorder);
        $sheet->getStyle('A'.$rc.':D'.$rc)->applyFromArray(array('font'=>array('bold' => true,'size'=>13)));

        $rc++;
        
        $dates = explode(" to ",$_GET['calendar_range']);
        $from = sql2DateTime($dates[0]);
        $to = sql2DateTime($dates[1]);

        $sheet->getCell('A'.$rc)->setValue('Report Period: '.$daterange);
        $sheet->mergeCells('A'.$rc.':B'.$rc);

        $sheet->getCell('C'.$rc)->setValue('Report Generated: '.(new \DateTime())->format('Y-m-d H:i:s'));
        $sheet->mergeCells('C'.$rc.':D'.$rc);
        $rc++;

        $sheet->getCell('A'.$rc)->setValue('Transaction Time: ');
        $sheet->mergeCells('A'.$rc.':B'.$rc);

        $user = $this->session->userdata('user');
        $sheet->getCell('C'.$rc)->setValue('Generated By: '.$user['full_name']);
        $sheet->mergeCells('C'.$rc.':D'.$rc);
        $sheet->getStyle('A'.$rc.':D'.$rc)->applyFromArray($styleBorder);
        $rc++;$rc++;

        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }

        $rc++;
        foreach ($trans as $v) {
                $sheet->getCell('A'.$rc)->setValue($v->datetime);
                $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('B'.$rc)->setValue($v->type);
                $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('C'.$rc)->setValue($v->fname. " ".$v->mname." ".$v->lname);
                $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('D'.$rc)->setValue($v->username);     
                $sheet->getStyle('D'.$rc)->applyFromArray($styleTxt);
            $rc++;
        } 
        $ur = $rc-1;
        $sheet->getStyle('A'.$ur.':D'.$ur)->applyFromArray($styleBorder);

        $rc++;

        $sheet->getCell('A'.$rc)->setValue('Total Hours:');
        $rc++;

        foreach ($total_hours as $v) {
            $sheet->getCell('A'.$rc)->setValue($v["fname"]. " ".$v["mname"]." ".$v["lname"]);
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue($v["time_to_sec"]);
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            $rc++;
        } 
        $rc++;
        
        update_load(100);
        ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }
    // End of New Report


    //New Hourly Sales Jed
    public function hourly_sales_rep_gen(){
         $this->load->helper('dine/reports_helper');
        $json = $this->input->post('json');

        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];
        start_load(0);
        $menu_cat_id = $this->input->post("menu_cat_id");        
        $brand = $this->input->post("brand"); 
        // echo $brand; die();       
        $daterange = $this->input->post("calendar_range");        
        $dates = explode(" to ",$daterange);
        $from = date2SqlDateTime($dates[0]." ".$set->store_open);
        $to_plus = date('Y-m-d',strtotime($dates[1] . "+1 days"));
        $to = date2SqlDateTime($to_plus." ".$set->store_open);


        // echo $brand.' -- '.$to;
        // die();

        $args = array();
        // if($user)
        //     $args["trans_sales.user_id"] = array('use'=>'where','val'=>$user,'third'=>false);
        if($brand != 0){
            $args["menus.brand"] = array('use'=>'where','val'=>$brand,'third'=>false);
        }

        $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.inactive = 0"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);

        if($menu_cat_id != 0){
            $args["menu_categories.menu_cat_id"] = array('use'=>'where','val'=>$menu_cat_id,'third'=>false);
        }


        // echo "<pre>",print_r($args),"</pre>";

        $post = $this->set_post();
        $curr = $this->search_current();
        $trans = $this->trans_sales_cat($args,$curr);
        $sales = $trans['sales'];

        // $payments = $this->payment_sales($sales['settled']['ids'],$curr);
        // var_dump($sales); die();
        // $get_trans = $this->cashier_model->get_trans_sales(null,$args,'asc');
        // $unserialize = unserialize(TIMERANGES);
        // var_dump($unserialize); die();
        update_load(10);
        $ranges = array();
        foreach (unserialize(TIMERANGES) as $ctr => $time) {
            $key = date('H',strtotime($time['FTIME']));
            $ranges[$key] = array('start'=>$time['FTIME'],'end'=>$time['TTIME'],'tc'=>0,'net'=>0,'tg'=>0,'gross'=>0,'charges'=>0,'discounts'=>0,'vsales'=>0,'vat'=>0);
            // $ranges[$key] = array();
        }

        // die();
        $dates = array();
        if(count($sales['settled']['orders']) > 0){
            foreach ($sales['settled']['orders'] as $sales_id => $val) {
                $dates[date2Sql($val->datetime)]['ranges'] = $ranges;
            }
            // $this->cashier_model->db = $this->load->database('default', TRUE);
            foreach ($sales['settled']['orders'] as $sales_id => $val) {
                if(isset($dates[date2Sql($val->datetime)])){
                    $date_arr = $dates[date2Sql($val->datetime)];
                    $range = $date_arr['ranges'];
                    $H = date('H',strtotime($val->datetime));
                    if(isset($range[$H])){
                        // $discnt = 0;
                        // $dargs["trans_sales_discounts.sales_id"] = array('use'=>'where','val'=>$val->sales_id,'third'=>false);
                        // $d_res  = $this->cashier_model->get_trans_sales_discounts_group2(null,$dargs);
                        // // var_dump($d_res);
                        // if($d_res){
                        //     $discnt = $d_res[0]->total;
                        //     // echo $discnt;
                        // }
                        // $chrgs = 0;
                        // $cargs["trans_sales_charges.sales_id"] = array('use'=>'where','val'=>$val->sales_id,'third'=>false);
                        // $c_res  = $this->cashier_model->get_trans_sales_charges(null,$cargs);
                        // // echo $this->cashier_model->db->last_query();
                        // if($c_res){
                        //     $chrgs = $c_res[0]->amount;
                        //     // echo $chrgs;
                        // }
                        $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$sales_id));
                        $sales_tax = $this->cashier_model->get_trans_sales_tax(null,array("trans_sales_tax.sales_id"=>$sales_id));
                        $sales_no_tax = $this->cashier_model->get_trans_sales_no_tax(null,array("trans_sales_no_tax.sales_id"=>$sales_id));
                        $sales_zero_rated = $this->cashier_model->get_trans_sales_zero_rated(null,array("trans_sales_zero_rated.sales_id"=>$sales_id));
                        $sales_charges = $this->cashier_model->get_trans_sales_charges(null,array("trans_sales_charges.sales_id"=>$sales_id));
                        $sales_local_tax = $this->cashier_model->get_trans_sales_local_tax(null,array("trans_sales_local_tax.sales_id"=>$sales_id));

                        $discnt = 0;
                        $total_discounts_non_vat = 0;
                        foreach($sales_discs as $dc){
                            $discnt += $dc->amount;
                            if($dc->no_tax == 1){
                                $total_discounts_non_vat += $dc->amount;
                            }
                        }

                        $tax = 0;
                        foreach ($sales_tax as $tx) {
                            $tax += $tx->amount;
                        }
                        $no_tax = 0;
                        foreach ($sales_no_tax as $nt) {
                            $no_tax += $nt->amount;
                        }
                        $zero_rated = 0;
                        foreach ($sales_zero_rated as $zt) {
                            $zero_rated += $zt->amount;
                        }

                        if($zero_rated > 0){
                            $no_tax = 0;
                        }


                        $local_tax = 0;
                        foreach ($sales_local_tax as $lt) {
                            $local_tax += $lt->amount;
                        }
                        $chrgs = 0;
                        foreach ($sales_charges as $ch) {
                            $chrgs += $ch->amount;
                        }


                        $vat_sales = ( ( ( $val->total_amount - ($chrgs + $local_tax) ) - $tax)  - $no_tax + $total_discounts_non_vat ) - $zero_rated;

                        $r = $range[$H];
                        $r['tc'] += 1;
                        $r['net'] += $val->total_amount;
                        $r['gross'] += $val->menu_total;
                        $r['charges'] += $chrgs;
                        $r['discounts'] += $discnt;
                        $r['vsales'] += $vat_sales;
                        $r['vat'] += $tax;

                        if($val->guest == 0 || $val->guest == null || $val->guest == '')
                            $r['tg'] += 1;
                        else
                            $r['tg'] += $val->guest;

                        // $r['tg'] += $val->guest;
                        $range[$H] = $r;
                    }
                    $dates[date2Sql($val->datetime)]['ranges'] = $range;
                }
            }

            update_load(40);
            
            // $this->print_drawer_details($get_trans,$date,$user,$asjson);
            $this->make->sDiv();
                $this->make->sTable(array('id'=>'main-tbl','class'=>'table'));
                    $this->make->sTableHead();
                        $this->make->sRow(array('class'=>'reportTBL'));
                            $this->make->th('Time');
                            $this->make->th('Guest Count');
                            $this->make->th('Gross Sales');
                            $this->make->th('Vat Sales');
                            $this->make->th('VAT');
                            $this->make->th('Charges');
                            $this->make->th('Discounts');
                            $this->make->th('Average(%)');
                        $this->make->eRow();
                    $this->make->eTableHead();
                    $this->make->sTableBody();
                    $txt = '';
                    
                    $ctr = 0;
                    $gtavg = $gtctr = $gtnet = 0;
                    foreach($dates as $key1 => $v1){
                        $this->make->sRow(array('class'=>''));
                            $this->make->th(sql2Date($key1),array('colspan'=>8,'style'=>'text-align:center;'));
                        $this->make->eRow();

                        $ranges = $v1['ranges'];
                        //$txt .= sql2Date($key1);
                        $tavg = 0;
                        $tctr = 0;
                        $tnet = 0;
                        $tgc = 0;
                        $tdisc = 0;
                        $tcharges = 0;
                        $tvsales = 0;
                        $ttax = 0;
                        $tgross = 0;
                        $counter = 0;
                        $rcount = count($ranges);
                        foreach($ranges as $key2 => $ran){
                            if($ran['tc'] == 0 || $ran['net'] == 0)
                                $avg = 0;
                            else
                                $avg = $ran['net']/$ran['tc'];
                            $ctr += $ran['tc'];

                            $this->make->sRow();
                                // $this->make->th($ran['start']."-".$ran['end']);
                                $this->make->th(date('h:i A',strtotime($ran['start'])));
                                $this->make->th($ran['tg']);
                                $this->make->th(numInt($ran['gross']), array("style"=>"text-align:right"));
                                $this->make->th(numInt($ran['vsales']), array("style"=>"text-align:right"));
                                $this->make->th(numInt($ran['vat']), array("style"=>"text-align:right"));
                                $this->make->th(numInt($ran['charges']), array("style"=>"text-align:right"));
                                $this->make->th(numInt($ran['discounts']), array("style"=>"text-align:right"));
                                $this->make->th(numInt($avg), array("style"=>"text-align:right"));
                            $this->make->eRow();
                            $tctr += $ran['tc'];
                            $tnet += $ran['net'];
                            $tgc += $ran['tg'];
                            $tdisc += $ran['discounts'];
                            $tcharges += $ran['charges'];
                            $tvsales += $ran['vsales'];
                            $ttax += $ran['vat'];
                            $tgross += $ran['gross'];
                            // if($ctr == 0 || $ran['net'] == 0)
                            //     $tavg = 0;
                            // else
                            //     $tavg += $ran['net']/$ctr;

                            $counter++;
                            $progress = ($counter / $rcount) * 100;
                            // update_load(num($progress));

                        }
                        update_load(80);
                        $gtctr += $tctr;
                        $gtnet += $tnet;

                    }
                    $gtavg = $gtnet/$gtctr;
                    $this->make->sRow(array('class'=>'reportTBL'));
                        $this->make->th('TOTAL');
                        $this->make->th($tgc);
                        $this->make->th(numInt($tgross), array("style"=>"text-align:right"));
                        $this->make->th(numInt($tvsales), array("style"=>"text-align:right"));
                        $this->make->th(numInt($ttax), array("style"=>"text-align:right"));
                        $this->make->th(numInt($tcharges), array("style"=>"text-align:right"));
                        $this->make->th(numInt($tdisc), array("style"=>"text-align:right"));
                        $this->make->th(numInt($gtavg), array("style"=>"text-align:right"));
                        // $this->make->th(numInt($gtavg));
                    $this->make->eRow();
                    $this->make->eTableBody();
                $this->make->eTable();
            $this->make->eDiv();

            update_load(100);

            // echo $code;
            // echo json_encode(array("code"=>$code));
            $code = $this->make->code();
            $json['code'] = $code;
            // $json['tbl_vals'] = $menus;
            // $json['dates'] = $this->input->post('calendar_range');
            echo json_encode($json);
            
        }
        else{
            update_load(100);
            $error = "There is no sales found.";
            echo json_encode(array("code"=>"<pre style='background-color:#fff'>$error</pre>"));
        }
        // var_dump($dates);
    }

     public function hourly_sales_rep_pdf()
    {
        // Include the main TCPDF library (search for installation path).
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Hourly Sales Report');
        $pdf->SetSubject('');
        $pdf->SetKeywords('');

        // set default header data
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "BARCINO RETAIL CORPORATION", $set->address);

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

        // ---------------------------------------------------------

        $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
        
        $menu_cat_id = $_GET['menu_cat_id'];        
        $daterange = $_GET['calendar_range'];        
        $dates = explode(" to ",$daterange);
        $from = date2SqlDateTime($dates[0]." ".$set->store_open);
        $to_plus = date('Y-m-d',strtotime($dates[1] . "+1 days"));
        $to = date2SqlDateTime($to_plus." ".$set->store_open);
        $trans_payment = $this->menu_model->get_payment_date($from, $to);


        // echo $from.' -- '.$to;
        // die();
        $pdf->Write(0, 'Hourly Sales Report', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Write(0, 'Report Period:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, $daterange, '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Transaction Time:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln();              

        // echo "<pre>", print_r($trans), "</pre>";die();

        // -----------------------------------------------------------------------------
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(43, 0, 'Time', 'B', 0, 'L');        
        $pdf->Cell(32, 0, 'Guest Count', 'B', 0, 'L');        
        $pdf->Cell(32, 0, 'Gross Sales', 'B', 0, 'C');        
        $pdf->Cell(32, 0, 'VAT Sales', 'B', 0, 'C');        
        $pdf->Cell(32, 0, 'VAT', 'B', 0, 'C');        
        $pdf->Cell(32, 0, 'Charges', 'B', 0, 'C');        
        $pdf->Cell(32, 0, 'Discounts', 'B', 0, 'C');       
        $pdf->Cell(32, 0, 'Average (%)', 'B', 0, 'C');        
        // $pdf->Cell(24, 0, 'Average (%)', 'B', 0, 'C');        
        $pdf->ln();                  

        $args = array();
        // if($user)
        //     $args["trans_sales.user_id"] = array('use'=>'where','val'=>$user,'third'=>false);

        $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.inactive = 0"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);

        if($menu_cat_id != 0){
            $args["menu_categories.menu_cat_id"] = array('use'=>'where','val'=>$menu_cat_id,'third'=>false);
        }        


        $post = $this->set_post();
        $curr = $this->search_current();
        $trans = $this->trans_sales_cat($args,$curr);
        $sales = $trans['sales'];

        $payments = $this->payment_sales($sales['settled']['ids'],$curr);

        // var_dump($sales); die();
        // $get_trans = $this->cashier_model->get_trans_sales(null,$args,'asc');
        // $unserialize = unserialize(TIMERANGES);
        // var_dump($unserialize); die();

        $ranges = array();
        foreach (unserialize(TIMERANGES) as $ctr => $time) {
            $key = date('H',strtotime($time['FTIME']));
            $ranges[$key] = array('start'=>$time['FTIME'],'end'=>$time['TTIME'],'tc'=>0,'net'=>0,'tg'=>0,'gross'=>0,'charges'=>0,'discounts'=>0,'vsales'=>0,'vat'=>0);
            // $ranges[$key] = array();
        }

        update_load(20);

        // echo "<pre>",print_r($ranges),"</pre>";
        // die();
        $dates = array();
        if(count($sales['settled']['orders']) > 0){
            foreach ($sales['settled']['orders'] as $sales_id => $val) {
                $dates[date2Sql($val->datetime)]['ranges'] = $ranges;
            }
            // $this->cashier_model->db = $this->load->database('default', TRUE);
            foreach ($sales['settled']['orders'] as $sales_id => $val) {
                if(isset($dates[date2Sql($val->datetime)])){
                    $date_arr = $dates[date2Sql($val->datetime)];
                    $range = $date_arr['ranges'];
                    $H = date('H',strtotime($val->datetime));
                    if(isset($range[$H])){
                        // $discnt = 0;
                        // $dargs["trans_sales_discounts.sales_id"] = array('use'=>'where','val'=>$val->sales_id,'third'=>false);
                        // $d_res  = $this->cashier_model->get_trans_sales_discounts_group2(null,$dargs);
                        // // var_dump($d_res);
                        // if($d_res){
                        //     $discnt = $d_res[0]->total;
                        //     // echo $discnt;
                        // }
                        // $chrgs = 0;
                        // $cargs["trans_sales_charges.sales_id"] = array('use'=>'where','val'=>$val->sales_id,'third'=>false);
                        // $c_res  = $this->cashier_model->get_trans_sales_charges(null,$cargs);
                        // // echo $this->cashier_model->db->last_query();
                        // if($c_res){
                        //     $chrgs = $c_res[0]->amount;
                        //     // echo $chrgs;
                        // }
                        $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$sales_id));
                        $sales_tax = $this->cashier_model->get_trans_sales_tax(null,array("trans_sales_tax.sales_id"=>$sales_id));
                        $sales_no_tax = $this->cashier_model->get_trans_sales_no_tax(null,array("trans_sales_no_tax.sales_id"=>$sales_id));
                        $sales_zero_rated = $this->cashier_model->get_trans_sales_zero_rated(null,array("trans_sales_zero_rated.sales_id"=>$sales_id));
                        $sales_charges = $this->cashier_model->get_trans_sales_charges(null,array("trans_sales_charges.sales_id"=>$sales_id));
                        $sales_local_tax = $this->cashier_model->get_trans_sales_local_tax(null,array("trans_sales_local_tax.sales_id"=>$sales_id));

                        $discnt = 0;
                        $total_discounts_non_vat = 0;
                        foreach($sales_discs as $dc){
                            $discnt += $dc->amount;
                            if($dc->no_tax == 1){
                                $total_discounts_non_vat += $dc->amount;
                            }
                        }

                        $tax = 0;
                        foreach ($sales_tax as $tx) {
                            $tax += $tx->amount;
                        }
                        $no_tax = 0;
                        foreach ($sales_no_tax as $nt) {
                            $no_tax += $nt->amount;
                        }
                        $zero_rated = 0;
                        foreach ($sales_zero_rated as $zt) {
                            $zero_rated += $zt->amount;
                        }

                        if($zero_rated > 0){
                            $no_tax = 0;
                        }


                        $local_tax = 0;
                        foreach ($sales_local_tax as $lt) {
                            $local_tax += $lt->amount;
                        }
                        $chrgs = 0;
                        foreach ($sales_charges as $ch) {
                            $chrgs += $ch->amount;
                        }


                        $vat_sales = ( ( ( $val->total_amount - ($chrgs + $local_tax) ) - $tax)  - $no_tax + $total_discounts_non_vat ) - $zero_rated;

                        $r = $range[$H];
                        $r['tc'] += 1;
                        $r['net'] += $val->total_amount;
                        $r['gross'] += $val->menu_total;
                        $r['charges'] += $chrgs;
                        $r['discounts'] += $discnt;
                        $r['vsales'] += $vat_sales;
                        $r['vat'] += $tax;
                        $r['tg'] += $val->guest;
                        $range[$H] = $r;
                    }
                    $dates[date2Sql($val->datetime)]['ranges'] = $range;
                }
            }

            update_load(30);

            $ctr = 0;
            $gtavg = $gtctr = $gtnet = 0;
            $strt = "";
            foreach($dates as $key1 => $v1){
                // $this->make->sRow(array('class'=>''));
                //     $this->make->th(sql2Date($key1),array('colspan'=>8,'style'=>'text-align:center;'));
                // $this->make->eRow();

                if($strt == ""){
                    $pdf->ln(2);
                    $pdf->Cell(267, 0, sql2Date($key1), '', 0, 'C');
                    $pdf->ln(5);
                }else{
                    $pdf->AddPage();
                    $pdf->SetFont('helvetica', 'B', 11);
                    $pdf->Write(0, 'Hourly Sales Report', '', 0, 'L', true, 0, false, false, 0);
                    $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
                    $pdf->Cell(267, 0, '', 'T', 0, 'C');
                    $pdf->ln(0.9);      
                    $pdf->SetFont('helvetica', '', 9);
                    $pdf->Write(0, 'Report Period:    ', '', 0, 'L', false, 0, false, false, 0);
                    $pdf->Write(0, $daterange, '', 0, 'L', false, 0, false, false, 0);
                    $pdf->setX(200);
                    $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
                    $pdf->Write(0, 'Transaction Time:    ', '', 0, 'L', false, 0, false, false, 0);
                    $pdf->setX(200);
                    $user = $this->session->userdata('user');
                    $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
                    $pdf->ln(1);      
                    $pdf->Cell(267, 0, '', 'T', 0, 'C');
                    $pdf->ln();              

                    // echo "<pre>", print_r($trans), "</pre>";die();

                    // -----------------------------------------------------------------------------
                    $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
                    $pdf->Cell(43, 0, 'Time', 'B', 0, 'L');        
                    $pdf->Cell(32, 0, 'Guest Count', 'B', 0, 'L');        
                    $pdf->Cell(32, 0, 'Gross Sales', 'B', 0, 'C');        
                    $pdf->Cell(32, 0, 'VAT Sales', 'B', 0, 'C');        
                    $pdf->Cell(32, 0, 'VAT', 'B', 0, 'C');        
                    $pdf->Cell(32, 0, 'Charges', 'B', 0, 'C');        
                    $pdf->Cell(32, 0, 'Discounts', 'B', 0, 'C');       
                    $pdf->Cell(32, 0, 'Average (%)', 'B', 0, 'C');        
                    // $pdf->Cell(24, 0, 'Average (%)', 'B', 0, 'C');        
                    $pdf->ln();

                    $pdf->ln(2);
                    $pdf->Cell(267, 0, sql2Date($key1), '', 0, 'C');
                    $pdf->ln(3);
                }

                update_load(40);


                $ranges = $v1['ranges'];
                //$txt .= sql2Date($key1);
                $tavg = 0;
                $tctr = 0;
                $tnet = 0;
                $tgc = 0;
                $tdisc = 0;
                $tcharges = 0;
                $tvsales = 0;
                $ttax = 0;
                $tgross = 0;
                $counter = 0;
                $rcount = count($ranges);
                foreach($ranges as $key2 => $ran){
                    if($ran['tc'] == 0 || $ran['net'] == 0)
                        $avg = 0;
                    else
                        $avg = $ran['net']/$ran['tc'];
                    $ctr += $ran['tc'];

                    // $this->make->sRow();
                    //     // $this->make->th($ran['start']."-".$ran['end']);
                    //     $this->make->th(date('h:i A',strtotime($ran['start'])));
                    //     $this->make->th($ran['tg']);
                    //     $this->make->th(numInt($ran['gross']), array("style"=>"text-align:right"));
                    //     $this->make->th(numInt($ran['vsales']), array("style"=>"text-align:right"));
                    //     $this->make->th(numInt($ran['vat']), array("style"=>"text-align:right"));
                    //     $this->make->th(numInt($ran['charges']), array("style"=>"text-align:right"));
                    //     $this->make->th(numInt($ran['discounts']), array("style"=>"text-align:right"));
                    //     $this->make->th(numInt($avg), array("style"=>"text-align:right"));
                    // $this->make->eRow();

                    // $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
                    $pdf->Cell(43, 0, date('h:i A',strtotime($ran['start'])), '', 0, 'L');        
                    $pdf->Cell(32, 0, $ran['tg'], '', 0, 'L');        
                    $pdf->Cell(32, 0, num($ran['gross']), '', 0, 'R');        
                    $pdf->Cell(32, 0, num($ran['vsales']), '', 0, 'R');        
                    $pdf->Cell(32, 0, num($ran['vat']), '', 0, 'R');        
                    $pdf->Cell(32, 0, num($ran['charges']), '', 0, 'R');        
                    $pdf->Cell(32, 0, num($ran['discounts']), '', 0, 'R');        
                    $pdf->Cell(32, 0, num($avg), '', 0, 'R');        
                    
                    $pdf->ln();  



                    $tctr += $ran['tc'];
                    $tnet += $ran['net'];
                    $tgc += $ran['tg'];
                    $tdisc += $ran['discounts'];
                    $tcharges += $ran['charges'];
                    $tvsales += $ran['vsales'];
                    $ttax += $ran['vat'];
                    $tgross += $ran['gross'];
                    // if($ctr == 0 || $ran['net'] == 0)
                    //     $tavg = 0;
                    // else
                    //     $tavg += $ran['net']/$ctr;

                    $counter++;
                    $progress = ($counter / $rcount) * 100;
                    // update_load(num($progress));

                }

                update_load(70);
                $gtctr += $tctr;
                $gtnet += $tnet;
                $pdf->ln(3);

                $pdf->Cell(43, 0, 'TOTAL', 'T', 0, 'L');        
                $pdf->Cell(32, 0, $tgc, 'T', 0, 'L');        
                $pdf->Cell(32, 0, num($tgross), 'T', 0, 'R');        
                $pdf->Cell(32, 0, num($tvsales), 'T', 0, 'R');        
                $pdf->Cell(32, 0, num($ttax), 'T', 0, 'R');        
                $pdf->Cell(32, 0, num($tcharges), 'T', 0, 'R');        
                $pdf->Cell(32, 0, num($tdisc), 'T', 0, 'R');        
                $pdf->Cell(32, 0, '', 'T', 0, 'R');

                $strt = $key1;

                // $pdf->ln(10);
                // foreach ($trans_payment as $key => $val) {
                // $pdf->ln();
                //     $pdf->Cell(25, 0, strtoupper($val->payment_type), '', 0, 'L');
                //     if($val->payment_type == 'gc' || $val->payment_type == 'credit' ){        
                //         $pdf->Cell(15, 0, num($val->total_amount_gc), '', 0, 'R');
                //     }else{
                //         $pdf->Cell(15, 0, num($val->total_to_pay), '', 0, 'R');
                //     }

                // } 

            }

            update_load(90);

            // $payments_types = $payments['types'];
            // $payments_total = $payments['total'];
            // $pdf->ln(6);
            // // $pays = array();
            // // $total = 0;
            // // foreach ($payments_types as $py) {
            // //     // if(!in_array($py->sales_id, $ids_used)){
            // //     //     $ids_used[] = $py->sales_id;
            // //     // }
            // //     if($py->amount > $py->to_pay)
            // //         $amount = $py->to_pay;
            // //     else
            // //         $amount = $py->amount;
            // //     if(!isset($pays[$py->payment_type])){
            // //         $pays[$py->payment_type] = array('qty'=>1,'amount'=>$amount);
            // //     }
            // //     else{
            // //         $pays[$py->payment_type]['qty'] += 1;
            // //         $pays[$py->payment_type]['amount'] += $amount;
            // //     }
            // //     $total += $amount;
            // // }

            // $pdf->SetFont('helvetica', 'B', 9);
            // $pdf->Cell(30, 0, strtoupper('Payment Mode'), '', 0, 'L');
            // $pdf->Cell(35, 0, strtoupper('Payment Amount'), '', 0, 'C');
            // $pdf->SetFont('helvetica', '', 9);
            // // $pdf->ln();

            // foreach ($payments_types as $code => $val) {
            // $pdf->ln();
            //     $pdf->Cell(25, 0, strtoupper($code), '', 0, 'L');
            //     $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
            //     // $print_str .= append_chars(substrwords(ucwords(strtoupper($code)),18,""),"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
            //                   // .append_chars(numInt($val['amount']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //     // $pay_qty += $val['qty'];
            // }
            //     // $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
            // // $pdf->ln();
            // // $pdf->Cell(60, 0, '', 'T', 0, 'C');
            // $pdf->ln();
            // $pdf->SetFont('helvetica', 'B', 9);
            // $pdf->Cell(25, 0,'TOTAL ', 'T', 0, 'L');
            // $pdf->SetFont('helvetica', '', 9);
            // $pdf->Cell(35, 0, num($payments_total), 'T', 0, 'R');


        }else{
            $pdf->ln(2);
                $pdf->Cell(267, 0, 'No Sales Found', '', 0, 'C');
            $pdf->ln(5);
        }

        update_load(100);
        //Close and output PDF document
        $pdf->Output('hourly_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+   
    }


    public function hourly_sales_rep_excel(){
        date_default_timezone_set('Asia/Manila');
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $filename = 'Hourly Sales Report';
        $rc=1;
        #GET VALUES
        start_load(0);
            // $post = $this->set_post($_GET['calendar_range']);
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];

        $menu_cat_id = $_GET['menu_cat_id'];        
        $daterange = $_GET['calendar_range'];        
        $dates = explode(" to ",$daterange);
        $from = date2SqlDateTime($dates[0]." ".$set->store_open);
        $to_plus = date('Y-m-d',strtotime($dates[1] . "+1 days"));
        $to = date2SqlDateTime($to_plus." ".$set->store_open);
        $trans_payment = $this->menu_model->get_payment_date($from, $to);

        update_load(10);
        sleep(1);

        $args = array();
        // if($user)
        //     $args["trans_sales.user_id"] = array('use'=>'where','val'=>$user,'third'=>false);

        $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.inactive = 0"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);

        if($menu_cat_id != 0){
            $args["menu_categories.menu_cat_id"] = array('use'=>'where','val'=>$menu_cat_id,'third'=>false);
        }        


        $post = $this->set_post();
        $curr = $this->search_current();
        $trans = $this->trans_sales_cat($args,$curr);
        $sales = $trans['sales'];

        $payments = $this->payment_sales($sales['settled']['ids'],$curr);

        // $get_trans = $this->cashier_model->get_trans_sales(null,$args,'asc');

        update_load(15);
        sleep(1);

        $ranges = array();
        foreach (unserialize(TIMERANGES) as $ctr => $time) {
            $key = date('H',strtotime($time['FTIME']));
            $ranges[$key] = array('start'=>$time['FTIME'],'end'=>$time['TTIME'],'tc'=>0,'net'=>0,'tg'=>0,'gross'=>0,'charges'=>0,'discounts'=>0,'vsales'=>0,'vat'=>0);
            // $ranges[$key] = array();
        }

        update_load(20);
        sleep(1);
        // echo "<pre>",print_r($ranges),"</pre>";
        // die();
        $dates = array();
        if(count($sales['settled']['orders']) > 0){
            foreach ($sales['settled']['orders'] as $sales_id => $val) {
                $dates[date2Sql($val->datetime)]['ranges'] = $ranges;
            }
            // $this->cashier_model->db = $this->load->database('default', TRUE);
            foreach ($sales['settled']['orders'] as $sales_id => $val) {
                if(isset($dates[date2Sql($val->datetime)])){
                    $date_arr = $dates[date2Sql($val->datetime)];
                    $range = $date_arr['ranges'];
                    $H = date('H',strtotime($val->datetime));
                    if(isset($range[$H])){
                        // $discnt = 0;
                        // $dargs["trans_sales_discounts.sales_id"] = array('use'=>'where','val'=>$val->sales_id,'third'=>false);
                        // $d_res  = $this->cashier_model->get_trans_sales_discounts_group2(null,$dargs);
                        // // var_dump($d_res);
                        // if($d_res){
                        //     $discnt = $d_res[0]->total;
                        //     // echo $discnt;
                        // }
                        // $chrgs = 0;
                        // $cargs["trans_sales_charges.sales_id"] = array('use'=>'where','val'=>$val->sales_id,'third'=>false);
                        // $c_res  = $this->cashier_model->get_trans_sales_charges(null,$cargs);
                        // // echo $this->cashier_model->db->last_query();
                        // if($c_res){
                        //     $chrgs = $c_res[0]->amount;
                        //     // echo $chrgs;
                        // }
                        $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$sales_id));
                        $sales_tax = $this->cashier_model->get_trans_sales_tax(null,array("trans_sales_tax.sales_id"=>$sales_id));
                        $sales_no_tax = $this->cashier_model->get_trans_sales_no_tax(null,array("trans_sales_no_tax.sales_id"=>$sales_id));
                        $sales_zero_rated = $this->cashier_model->get_trans_sales_zero_rated(null,array("trans_sales_zero_rated.sales_id"=>$sales_id));
                        $sales_charges = $this->cashier_model->get_trans_sales_charges(null,array("trans_sales_charges.sales_id"=>$sales_id));
                        $sales_local_tax = $this->cashier_model->get_trans_sales_local_tax(null,array("trans_sales_local_tax.sales_id"=>$sales_id));

                        $discnt = 0;
                        $total_discounts_non_vat = 0;
                        foreach($sales_discs as $dc){
                            $discnt += $dc->amount;
                            if($dc->no_tax == 1){
                                $total_discounts_non_vat += $dc->amount;
                            }
                        }

                        $tax = 0;
                        foreach ($sales_tax as $tx) {
                            $tax += $tx->amount;
                        }
                        $no_tax = 0;
                        foreach ($sales_no_tax as $nt) {
                            $no_tax += $nt->amount;
                        }
                        $zero_rated = 0;
                        foreach ($sales_zero_rated as $zt) {
                            $zero_rated += $zt->amount;
                        }

                        if($zero_rated > 0){
                            $no_tax = 0;
                        }


                        $local_tax = 0;
                        foreach ($sales_local_tax as $lt) {
                            $local_tax += $lt->amount;
                        }
                        $chrgs = 0;
                        foreach ($sales_charges as $ch) {
                            $chrgs += $ch->amount;
                        }


                        $vat_sales = ( ( ( $val->total_amount - ($chrgs + $local_tax) ) - $tax)  - $no_tax + $total_discounts_non_vat ) - $zero_rated;

                        $r = $range[$H];
                        $r['tc'] += 1;
                        $r['net'] += $val->total_amount;
                        $r['gross'] += $val->menu_total;
                        $r['charges'] += $chrgs;
                        $r['discounts'] += $discnt;
                        $r['vsales'] += $vat_sales;
                        $r['vat'] += $tax;
                        $r['tg'] += $val->guest;
                        $range[$H] = $r;
                    }
                    $dates[date2Sql($val->datetime)]['ranges'] = $range;
                }
            }

            update_load(30);
            sleep(1);

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
            
            $headers = array('Time','Guest Count','Gross Sales','Vat Sales','VAT','Charges','Discounts','Average %');
            $sheet->getColumnDimension('A')->setWidth(20);
            $sheet->getColumnDimension('B')->setWidth(20);
            $sheet->getColumnDimension('C')->setWidth(20);
            $sheet->getColumnDimension('D')->setWidth(20);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(20);
            $sheet->getColumnDimension('G')->setWidth(20);
            $sheet->getColumnDimension('H')->setWidth(20);


            $sheet->mergeCells('A'.$rc.':H'.$rc);
            $sheet->getCell('A'.$rc)->setValue('BARCINO RETAIL CORPORATION');
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
            $rc++;

            $sheet->mergeCells('A'.$rc.':H'.$rc);
            $sheet->getCell('A'.$rc)->setValue($set->address);
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
            $rc++;

            $sheet->mergeCells('A'.$rc.':H'.$rc);
            $sheet->getCell('A'.$rc)->setValue('Hourly Sales Report');
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $rc++;

            $sheet->mergeCells('A'.$rc.':D'.$rc);
            $sheet->getCell('A'.$rc)->setValue('Report Period: '.$daterange);
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->mergeCells('E'.$rc.':H'.$rc);
            $sheet->getCell('E'.$rc)->setValue('Report Generated: '.(new \DateTime())->format('Y-m-d H:i:s'));
            $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
            $rc++;

            $sheet->mergeCells('A'.$rc.':D'.$rc);
            $sheet->getCell('A'.$rc)->setValue('Transaction Time:');
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $user = $this->session->userdata('user');
            $sheet->mergeCells('E'.$rc.':H'.$rc);
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
            $ctr = 0;
            $gtavg = $gtctr = $gtnet = 0;
            $strt = "";

            foreach($dates as $key1 => $v1){

                $sheet->mergeCells('A'.$rc.':H'.$rc);
                $sheet->getCell('A'.$rc)->setValue(sql2Date($key1));
                $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
                $rc++;


                $ranges = $v1['ranges'];
                //$txt .= sql2Date($key1);
                $tavg = 0;
                $tctr = 0;
                $tnet = 0;
                $tgc = 0;
                $tdisc = 0;
                $tcharges = 0;
                $tvsales = 0;
                $ttax = 0;
                $tgross = 0;
                $counter = 0;
                $rcount = count($ranges);
                foreach($ranges as $key2 => $ran){
                    if($ran['tc'] == 0 || $ran['net'] == 0)
                        $avg = 0;
                    else
                        $avg = $ran['net']/$ran['tc'];
                    $ctr += $ran['tc'];

                    // $this->make->sRow();
                    //     // $this->make->th($ran['start']."-".$ran['end']);
                    //     $this->make->th(date('h:i A',strtotime($ran['start'])));
                    //     $this->make->th($ran['tg']);
                    //     $this->make->th(numInt($ran['gross']), array("style"=>"text-align:right"));
                    //     $this->make->th(numInt($ran['vsales']), array("style"=>"text-align:right"));
                    //     $this->make->th(numInt($ran['vat']), array("style"=>"text-align:right"));
                    //     $this->make->th(numInt($ran['charges']), array("style"=>"text-align:right"));
                    //     $this->make->th(numInt($ran['discounts']), array("style"=>"text-align:right"));
                    //     $this->make->th(numInt($avg), array("style"=>"text-align:right"));
                    // $this->make->eRow();

                    // $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
                    // $pdf->Cell(43, 0, date('h:i A',strtotime($ran['start'])), '', 0, 'L');        
                    // $pdf->Cell(32, 0, $ran['tg'], '', 0, 'L');        
                    // $pdf->Cell(32, 0, num($ran['gross']), '', 0, 'R');        
                    // $pdf->Cell(32, 0, num($ran['vsales']), '', 0, 'R');        
                    // $pdf->Cell(32, 0, num($ran['vat']), '', 0, 'R');        
                    // $pdf->Cell(32, 0, num($ran['charges']), '', 0, 'R');        
                    // $pdf->Cell(32, 0, num($ran['discounts']), '', 0, 'R');        
                    // $pdf->Cell(32, 0, num($avg), '', 0, 'R');        
                    
                    // $pdf->ln();  
                    $sheet->getCell('A'.$rc)->setValue(date('h:i A',strtotime($ran['start'])));
                    $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                    $sheet->getCell('B'.$rc)->setValue($ran['tg']);
                    $sheet->getStyle('B'.$rc)->applyFromArray($styleCenter);
                    $sheet->getCell('C'.$rc)->setValue(numInt($ran['gross']));
                    $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
                    $sheet->getCell('D'.$rc)->setValue(numInt($ran['vsales']));     
                    $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
                    $sheet->getCell('E'.$rc)->setValue(numInt($ran['vat']));     
                    $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
                    $sheet->getCell('F'.$rc)->setValue(numInt($ran['charges']));     
                    $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
                    $sheet->getCell('G'.$rc)->setValue(numInt($ran['discounts']));     
                    $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
                    $sheet->getCell('H'.$rc)->setValue(numInt($avg));     
                    $sheet->getStyle('H'.$rc)->applyFromArray($styleNum);


                    $rc++;
                    $tctr += $ran['tc'];
                    $tnet += $ran['net'];
                    $tgc += $ran['tg'];
                    $tdisc += $ran['discounts'];
                    $tcharges += $ran['charges'];
                    $tvsales += $ran['vsales'];
                    $ttax += $ran['vat'];
                    $tgross += $ran['gross'];
                    // if($ctr == 0 || $ran['net'] == 0)
                    //     $tavg = 0;
                    // else
                    //     $tavg += $ran['net']/$ctr;

                    $counter++;
                    $progress = ($counter / $rcount) * 100;
                    // update_load(num($progress));

                }
                $gtctr += $tctr;
                $gtnet += $tnet;

                update_load(80);
                sleep(1);
                // $pdf->ln(3);

                // $pdf->Cell(43, 0, 'TOTAL', 'T', 0, 'L');        
                // $pdf->Cell(32, 0, $tgc, 'T', 0, 'L');        
                // $pdf->Cell(32, 0, num($tgross), 'T', 0, 'R');        
                // $pdf->Cell(32, 0, num($tvsales), 'T', 0, 'R');        
                // $pdf->Cell(32, 0, num($ttax), 'T', 0, 'R');        
                // $pdf->Cell(32, 0, num($tcharges), 'T', 0, 'R');        
                // $pdf->Cell(32, 0, num($tdisc), 'T', 0, 'R');        
                // $pdf->Cell(32, 0, '', 'T', 0, 'R');

                $sheet->getCell('A'.$rc)->setValue('TOTAL');
                $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
                $sheet->getCell('B'.$rc)->setValue($tgc);
                $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldCenter);
                $sheet->getCell('C'.$rc)->setValue(numInt($tgross));
                $sheet->getStyle('C'.$rc)->applyFromArray($styleBoldRight);
                $sheet->getCell('D'.$rc)->setValue(numInt($tvsales));     
                $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
                $sheet->getCell('E'.$rc)->setValue(numInt($ttax));     
                $sheet->getStyle('E'.$rc)->applyFromArray($styleBoldRight);
                $sheet->getCell('F'.$rc)->setValue(numInt($tcharges));     
                $sheet->getStyle('F'.$rc)->applyFromArray($styleBoldRight);
                $sheet->getCell('G'.$rc)->setValue(numInt($tdisc));     
                $sheet->getStyle('G'.$rc)->applyFromArray($styleBoldRight);
                $sheet->getCell('H'.$rc)->setValue('');     
                $sheet->getStyle('H'.$rc)->applyFromArray($styleBoldRight);
                $rc++;

            }
            // $payments_types = $payments['types'];
            // $payments_total = $payments['total'];
            // // $pays = array();
            // // $total = 0;
            // // foreach ($trans_payment as $py) {
            // //     // if(!in_array($py->sales_id, $ids_used)){
            // //     //     $ids_used[] = $py->sales_id;
            // //     // }
            // //     if($py->amount > $py->to_pay)
            // //         $amount = $py->to_pay;
            // //     else
            // //         $amount = $py->amount;
            // //     if(!isset($pays[$py->payment_type])){
            // //         $pays[$py->payment_type] = array('qty'=>1,'amount'=>$amount);
            // //     }
            // //     else{
            // //         $pays[$py->payment_type]['qty'] += 1;
            // //         $pays[$py->payment_type]['amount'] += $amount;
            // //     }
            // //     $total += $amount;
            // // }

            // $rc++;
            // $sheet->getCell('A'.$rc)->setValue('Payment Mode');
            // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
            // $sheet->getCell('B'.$rc)->setValue('Payment Amount');
            // $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldLeft);
            // $rc++;

            // foreach ($payments_types as $code => $val) {
            //     // $pdf->ln();
            //     $sheet->getCell('A'.$rc)->setValue(strtoupper($code));
            //     $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            //     // $pdf->Cell(25, 0, strtoupper($code), '', 0, 'L');
            //     $sheet->getCell('B'.$rc)->setValue(numInt($val['amount']));
            //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            //     // $pdf->Cell(15, 0, numInt($val['amount']), '', 0, 'L');
            //     // $print_str .= append_chars(substrwords(ucwords(strtoupper($code)),18,""),"right",PAPER_RD_COL_1," ").align_center($val['qty'],PAPER_RD_COL_2," ")
            //                   // .append_chars(numInt($val['amount']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            //     // $pay_qty += $val['qty'];
            //     $rc++;
            // }
            // // $pdf->ln();

        
            // $sheet->getCell('A'.$rc)->setValue('TOTAL');
            // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
            // $sheet->getCell('B'.$rc)->setValue(numInt($payments_total));
            // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        
        }else{
            $rc++;
            $sheet->mergeCells('A'.$rc.':H'.$rc);
            $sheet->getCell('A'.$rc)->setValue('No Sales Found');
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        } 
        
        update_load(100);
        // ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public function menu_sales_rep_excel()
    {
        // $this->load->database('main', TRUE);
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        date_default_timezone_set('Asia/Manila');
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $filename = 'Menu Sales Report';
        $rc=1;
        #GET VALUES
        start_load(0);
            // $post = $this->set_post($_GET['calendar_range']);
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];

        update_load(10);
        sleep(1);
        
        $menu_cat_id = $_GET['menu_cat_id'];        
        $daterange = $_GET['calendar_range'];        
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        $trans = $this->menu_model->get_menu_sales_rep($from, $to, $menu_cat_id);
        $trans_ret = $this->menu_model->get_menu_sales_rep_retail($from, $to, "");   
        $trans_mod = $this->menu_model->get_mod_menu_sales_rep($from, $to, $menu_cat_id);
        $trans_payment = $this->menu_model->get_payment_date($from, $to);   

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
        
        $headers = array('Menu','Category', 'Qty','Gross','Sales (%)','Cost','Cost (%)', 'Margin');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        // $sheet->getColumnDimension('I')->setWidth(20);


        $sheet->mergeCells('A'.$rc.':H'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->branch_name);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':H'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->address);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':H'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Sales Report');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $rc++;

        $sheet->mergeCells('A'.$rc.':E'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Report Period: '.$daterange);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->mergeCells('F'.$rc.':H'.$rc);
        $sheet->getCell('F'.$rc)->setValue('Report Generated: '.(new \DateTime())->format('Y-m-d H:i:s'));
        $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        $rc++;

        $sheet->mergeCells('A'.$rc.':E'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Transaction Time:');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $user = $this->session->userdata('user');
        $sheet->mergeCells('F'.$rc.':H'.$rc);
        $sheet->getCell('F'.$rc)->setValue('Generated by:    '.$user["full_name"]);
        $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        $rc++;

        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }
        $rc++;               
                

        // GRAND TOTAL VARIABLES
        $tot_qty = 0;
        $tot_vat_sales = 0;
        $tot_vat = 0;
        $tot_gross = 0;
        $tot_mod_gross = 0;
        $tot_sales_prcnt = 0;
        $tot_cost = 0;
        $tot_cost_prcnt = 0; 
        $tot_margin = 0;
        $counter = 0;
        $progress = 0;
        $trans_count = count($trans) + 10;
        foreach ($trans as $val) {
            $tot_gross += $val->gross;
            $tot_cost += $val->cost;
            $tot_margin += $val->gross - $val->cost;
        }
        foreach ($trans_mod as $vv) {
            $tot_mod_gross += $vv->mod_gross;
        }

        foreach ($trans as $k => $v) {
            // $pdf->Cell(55, 0, $v->menu_name, '', 0, 'L');        
            // $pdf->Cell(38, 0, $v->menu_cat_name, '', 0, 'L');        
            // $pdf->Cell(25, 0, num($v->qty), '', 0, 'R');        
            // $pdf->Cell(25, 0, num($v->vat_sales), '', 0, 'R');        
            // $pdf->Cell(25, 0, num($v->vat), '', 0, 'R');        
            // $pdf->Cell(25, 0, num($v->gross), '', 0, 'R');        
            // $pdf->Cell(25, 0, num(0), '', 0, 'R');        
            // $pdf->Cell(25, 0, num($v->cost), '', 0, 'R');        
            // $pdf->Cell(25, 0, num(0), '', 0, 'R');                    
            // $pdf->ln(); 

            $sheet->getCell('A'.$rc)->setValue($v->menu_name);
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue($v->menu_cat_name);
            $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('C'.$rc)->setValue(num($v->qty));
            $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
            // $sheet->getCell('D'.$rc)->setValue(num($v->vat_sales));     
            // $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
            // $sheet->getCell('E'.$rc)->setValue(num($v->vat));     
            // $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
            $sheet->getCell('D'.$rc)->setValue(num($v->gross));     
            $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
            if($tot_gross != 0){
                $sheet->getCell('E'.$rc)->setValue(num($v->gross / $tot_gross * 100)."%");                     
            }else{
                $sheet->getCell('E'.$rc)->setValue("0.00%");                                     
            }
            $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
            $sheet->getCell('F'.$rc)->setValue(num($v->cost));     
            $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
            if($tot_cost != 0){
                $sheet->getCell('G'.$rc)->setValue(num($v->cost / $tot_cost * 100)."%");     
            }else{
                $sheet->getCell('G'.$rc)->setValue("0.00%");                     
            }
            $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
            $sheet->getCell('H'.$rc)->setValue(num($v->gross - $v->cost));     
            $sheet->getStyle('H'.$rc)->applyFromArray($styleNum);               

            // Grand Total
            $tot_qty += $v->qty;
            // $tot_vat_sales += $v->vat_sales;
            // $tot_vat += $v->vat;
            // $tot_gross += $v->gross;
            $tot_sales_prcnt = 0;
            // $tot_cost += $v->cost;
            $tot_cost_prcnt = 0;

            $counter++;
            $progress = ($counter / $trans_count) * 100;
            update_load(num($progress)); 
            $rc++;             
        }
        $sheet->getCell('A'.$rc)->setValue('Grand Total');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue("");
        $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('C'.$rc)->setValue(num($tot_qty));
        $sheet->getStyle('C'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('D'.$rc)->setValue(num($tot_vat_sales));     
        // $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('E'.$rc)->setValue(num($tot_vat));     
        // $sheet->getStyle('E'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('D'.$rc)->setValue(num($tot_gross));     
        $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('E'.$rc)->setValue();     
        $sheet->getStyle('E'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('F'.$rc)->setValue(num($tot_cost));     
        $sheet->getStyle('F'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('G'.$rc)->setValue();     
        $sheet->getStyle('G'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('H'.$rc)->setValue(num($tot_margin));     
        $sheet->getStyle('H'.$rc)->applyFromArray($styleBoldRight);
        $rc++; 


        //retail
        $tot_gross_ret = 0;
        if(count($trans_ret) > 0){
            $rc++; 
            $col = 'A';
            $sheet->mergeCells('A'.$rc.':G'.$rc);
            $sheet->getCell('A'.$rc)->setValue('Retail Items');
            $sheet->getStyle('A'.$rc)->applyFromArray($styleCenter);
            $rc++;

            foreach ($headers as $txt) {
                $sheet->getCell($col.$rc)->setValue($txt);
                $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
                $col++;
            }
            $rc++;               
                    

            // GRAND TOTAL VARIABLES
            $tot_qty = 0;
            $tot_vat_sales = 0;
            $tot_vat = 0;
            $tot_mod_gross = 0;
            $tot_sales_prcnt = 0;
            $tot_cost = 0;
            $tot_cost_prcnt = 0; 
            $tot_margin = 0;
            $counter = 0;
            $progress = 0;
            $trans_count = count($trans) + 10;
            foreach ($trans_ret as $val) {
                $tot_gross_ret += $val->gross;
                // $tot_cost += $val->cost;
                $tot_margin += $val->gross - 0;
            }

            foreach ($trans_ret as $k => $v) {
                // $pdf->Cell(55, 0, $v->menu_name, '', 0, 'L');        
                // $pdf->Cell(38, 0, $v->menu_cat_name, '', 0, 'L');        
                // $pdf->Cell(25, 0, num($v->qty), '', 0, 'R');        
                // $pdf->Cell(25, 0, num($v->vat_sales), '', 0, 'R');        
                // $pdf->Cell(25, 0, num($v->vat), '', 0, 'R');        
                // $pdf->Cell(25, 0, num($v->gross), '', 0, 'R');        
                // $pdf->Cell(25, 0, num(0), '', 0, 'R');        
                // $pdf->Cell(25, 0, num($v->cost), '', 0, 'R');        
                // $pdf->Cell(25, 0, num(0), '', 0, 'R');                    
                // $pdf->ln(); 

                $sheet->getCell('A'.$rc)->setValue($v->item_name);
                $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('B'.$rc)->setValue($v->cat_name);
                $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('C'.$rc)->setValue(num($v->qty));
                $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
                // $sheet->getCell('D'.$rc)->setValue(num($v->vat_sales));     
                // $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
                // $sheet->getCell('E'.$rc)->setValue(num($v->vat));     
                // $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('D'.$rc)->setValue(num($v->gross));     
                $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
                if($tot_gross != 0){
                    $sheet->getCell('E'.$rc)->setValue(num($v->gross / $tot_gross * 100)."%");                     
                }else{
                    $sheet->getCell('E'.$rc)->setValue("0.00%");                                     
                }
                $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('F'.$rc)->setValue(num(0));     
                $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
                if($tot_cost != 0){
                    $sheet->getCell('G'.$rc)->setValue(num($v->cost / $tot_cost * 100)."%");     
                }else{
                    $sheet->getCell('G'.$rc)->setValue("0.00%");                     
                }
                $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('H'.$rc)->setValue(num($v->gross - 0));     
                $sheet->getStyle('H'.$rc)->applyFromArray($styleNum);               

                // Grand Total
                $tot_qty += $v->qty;
                // $tot_vat_sales += $v->vat_sales;
                // $tot_vat += $v->vat;
                // $tot_gross += $v->gross;
                $tot_sales_prcnt = 0;
                // $tot_cost += $v->cost;
                $tot_cost_prcnt = 0;

                // $counter++;
                // $progress = ($counter / $trans_count) * 100;
                // update_load(num($progress)); 
                $rc++;             
            }
            $sheet->getCell('A'.$rc)->setValue('Grand Total');
            $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
            $sheet->getCell('B'.$rc)->setValue("");
            $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldLeft);
            $sheet->getCell('C'.$rc)->setValue(num($tot_qty));
            $sheet->getStyle('C'.$rc)->applyFromArray($styleBoldRight);
            // $sheet->getCell('D'.$rc)->setValue(num($tot_vat_sales));     
            // $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
            // $sheet->getCell('E'.$rc)->setValue(num($tot_vat));     
            // $sheet->getStyle('E'.$rc)->applyFromArray($styleBoldRight);
            $sheet->getCell('D'.$rc)->setValue(num($tot_gross_ret));     
            $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
            $sheet->getCell('E'.$rc)->setValue();     
            $sheet->getStyle('E'.$rc)->applyFromArray($styleBoldRight);
            $sheet->getCell('F'.$rc)->setValue(num($tot_cost));     
            $sheet->getStyle('F'.$rc)->applyFromArray($styleBoldRight);
            $sheet->getCell('G'.$rc)->setValue();     
            $sheet->getStyle('G'.$rc)->applyFromArray($styleBoldRight);
            $sheet->getCell('H'.$rc)->setValue(num($tot_margin));     
            $sheet->getStyle('H'.$rc)->applyFromArray($styleBoldRight);
            $rc++; 
        }
        

        ///////////fpr payments
        $this->cashier_model->db = $this->load->database('main', TRUE);
        $args = array();
        // if($user)
        //     $args["trans_sales.user_id"] = array('use'=>'where','val'=>$user,'third'=>false);

        $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.inactive = 0"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);

        // if($menu_cat_id != 0){
        //     $args["menu_categories.menu_cat_id"] = array('use'=>'where','val'=>$menu_cat_id,'third'=>false);
        // }


        $post = $this->set_post();
        // $curr = $this->search_current();
        $curr = false;
        $trans = $this->trans_sales($args,$curr);
        $sales = $trans['sales'];

        $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
        $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
        $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
        $tax_disc = $trans_discounts['tax_disc_total'];
        $no_tax_disc = $trans_discounts['no_tax_disc_total'];
        $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
        $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
        $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
        $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
        $payments = $this->payment_sales($sales['settled']['ids'],$curr);

        $gross = $trans_menus['gross'];

        $net = $trans['net'];
        $void = $trans['void'];
        $charges = $trans_charges['total'];
        $discounts = $trans_discounts['total'];
        $local_tax = $trans_local_tax['total'];
        $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;

        if($less_vat < 0)
            $less_vat = 0;


        $tax = $trans_tax['total'];
        $no_tax = $trans_no_tax['total'];
        $zero_rated = $trans_zero_rated['total'];
        $no_tax -= $zero_rated;

        $loc_txt = numInt(($local_tax));
        $net_no_adds = $net-($charges+$local_tax);
        $nontaxable = $no_tax;
        $taxable = ($gross - $less_vat - $nontaxable - $zero_rated) / 1.12;
        $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
        $add_gt = $taxable+$nontaxable+$zero_rated;
        $nsss = $taxable +  $nontaxable +  $zero_rated;

        $vat_ = $taxable * .12;

        $rc++;
        $sheet->getCell('A'.$rc)->setValue('GROSS');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($tot_gross + $tot_mod_gross + $tot_gross_ret));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $sheet->getCell('A'.$rc)->setValue('VAT SALES');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($taxable));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $sheet->getCell('A'.$rc)->setValue('VAT');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($vat_));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $sheet->getCell('A'.$rc)->setValue('VAT EXEMPT SALES');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($nontaxable-$zero_rated));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $sheet->getCell('A'.$rc)->setValue('ZERO RATED');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($zero_rated));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        // //MENU SUB CAT
        $rc++; $rc++;
        $sheet->getCell('A'.$rc)->setValue('SUB CATEGORIES');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue('AMOUNT');
        $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        $subcats = $trans_menus['sub_cats'];
        $total = 0;
        foreach ($subcats as $id => $val) {
            $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper($val['name']));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            $total += $val['amount'];
        }
        if($tot_gross_ret != 0){
            $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper('RETAIL'));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue(num($tot_gross_ret));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            $total += $tot_gross_ret;
        }
        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('SubTotal'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($total));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        $rc++;
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('A'.$rc)->setValue(strtoupper('MODIFIERS TOTAL'));
        $sheet->getCell('B'.$rc)->setValue(num($trans_menus['mods_total']));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('TOTAL'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($total + $trans_menus['mods_total']));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);


        //DISCOUNTS
        $rc++; $rc++;
        $sheet->getCell('A'.$rc)->setValue('DISCOUNT');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue('AMOUNT');
        $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        $types = $trans_discounts['types'];
        foreach ($types as $code => $val) {
            $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper($val['name']));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            
        }

        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('Total'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($discounts));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('VAT EXEMPT'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($less_vat));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);


        // //CAHRGES
        $rc++; $rc++;
        $sheet->getCell('A'.$rc)->setValue('CHARGES');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue('AMOUNT');
        $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        $types = $trans_charges['types'];
        foreach ($types as $code => $val) {
            $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper($val['name']));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            
        }
        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('Total'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($charges));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        // //PAYMENTS
        $rc++; $rc++;
        $sheet->getCell('A'.$rc)->setValue('PAYMENT MODE');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue('PAYMENT AMOUNT');
        $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        $payments_types = $payments['types'];
        $payments_total = $payments['total'];
        foreach ($payments_types as $code => $val) {
            $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper($code));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        }

        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('Total'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($payments_total));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);


        if($trans['total_chit']){
            $rc++; $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper('Total Chit'));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
            $sheet->getCell('B'.$rc)->setValue(num($trans['total_chit']));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        }


        $types = $trans['types'];
        $types_total = array();
        $guestCount = 0;
        foreach ($types as $type => $tp) {
            foreach ($tp as $id => $opt){
                if(isset($types_total[$type])){
                    $types_total[$type] += round($opt->total_amount,2);

                }
                else{
                    $types_total[$type] = round($opt->total_amount,2);
                }
                if($opt->guest == 0)
                    $guestCount += 1;
                else
                    $guestCount += $opt->guest;
            }
        }
        $rc++;
        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('Trans Count'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $tc_total  = 0;
        $tc_qty = 0;
        foreach ($types_total as $typ => $tamnt) {
            $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper($typ));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue(count($types[$typ]));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            $sheet->getCell('C'.$rc)->setValue(num($tamnt));
            $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
            $tc_total += $tamnt;
            $tc_qty += count($types[$typ]);
        }
        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('TC TOTAL'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('C'.$rc)->setValue(num($tc_total));
        $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);



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

    public function sales_rep_excel()
    {
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        date_default_timezone_set('Asia/Manila');
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $filename = 'Category Sales Report';
        $rc=1;
        #GET VALUES
        start_load(0);
            // $post = $this->set_post($_GET['calendar_range']);
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];

        update_load(10);
        sleep(1);
        
        $menu_cat_id = $_GET['menu_cat_id'];        
        $daterange = $_GET['calendar_range'];        
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        $trans = $this->menu_model->get_cat_sales_rep($from, $to, $menu_cat_id);
        $trans_ret = $this->menu_model->get_cat_sales_rep_retail($from, $to, "");   
        $trans_mod = $this->menu_model->get_mod_cat_sales_rep($from, $to, $menu_cat_id);
        $trans_payment = $this->menu_model->get_payment_date($from, $to);   

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
        
        $headers = array('Category', 'Qty','Gross','Sales (%)','Cost','Cost (%)', 'Margin');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        // $sheet->getColumnDimension('H')->setWidth(20);


        $sheet->mergeCells('A'.$rc.':H'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->branch_name);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->address);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Sales Report');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Report Period: '.$daterange);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('E'.$rc)->setValue('Report Generated: '.(new \DateTime())->format('Y-m-d H:i:s'));
        $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Transaction Time:');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $user = $this->session->userdata('user');
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('F'.$rc)->setValue('Generated by:    '.$user["full_name"]);
        $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        $rc++;

        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }
        $rc++;                          

        // GRAND TOTAL VARIABLES
        $tot_qty = 0;
        $tot_vat_sales = 0;
        $tot_vat = 0;
        $tot_gross = 0;
        $tot_mod_gross = 0;
        $tot_sales_prcnt = 0;
        $tot_cost = 0;
        $tot_cost_prcnt = 0; 
        $tot_margin = 0;
        $counter = 0;
        $progress = 0;
        $trans_count = count($trans);
        foreach ($trans as $val) {
            $tot_gross += $val->gross;
            $tot_cost += $val->cost;
            $tot_margin += $val->gross - $val->cost;
        }
        foreach ($trans_mod as $vv) {
            $tot_mod_gross += $vv->mod_gross;
        }

        foreach ($trans as $k => $v) {
            $sheet->getCell('A'.$rc)->setValue($v->menu_cat_name);
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue($v->qty);
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            // $sheet->getCell('C'.$rc)->setValue(num($v->vat_sales));
            // $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
            // $sheet->getCell('D'.$rc)->setValue(num($v->vat));     
            // $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
            $sheet->getCell('C'.$rc)->setValue(num($v->gross));     
            $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
            $sheet->getCell('D'.$rc)->setValue(num($v->gross / $tot_gross * 100)."%");     
            $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
            $sheet->getCell('E'.$rc)->setValue(num($v->cost));                     
            $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
            if($tot_cost != 0){
                $sheet->getCell('F'.$rc)->setValue(num($v->cost / $tot_cost * 100)."%");     
            
            }else{
                $sheet->getCell('F'.$rc)->setValue('0.00%');                                     
            }
            $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
            $sheet->getCell('G'.$rc)->setValue(num($v->gross - $v->cost));     
            $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);       

            // Grand Total
            $tot_qty += $v->qty;
            // $tot_vat_sales += $v->vat_sales;
            // $tot_vat += $v->vat;
            // $tot_gross += $v->gross;
            $tot_sales_prcnt = 0;
            // $tot_cost += $v->cost;
            $tot_cost_prcnt = 0;

            $counter++;
            $progress = ($counter / $trans_count) * 100;
            update_load(num($progress));   
            $rc++;           
        }

        $sheet->getCell('A'.$rc)->setValue('Grand Total');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($tot_qty));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('C'.$rc)->setValue(num($tot_vat_sales));     
        // $sheet->getStyle('C'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('D'.$rc)->setValue(num($tot_vat));     
        // $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('C'.$rc)->setValue(num($tot_gross));     
        $sheet->getStyle('C'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('D'.$rc)->setValue("");     
        $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('E'.$rc)->setValue(num($tot_cost));     
        $sheet->getStyle('E'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('F'.$rc)->setValue("");     
        $sheet->getStyle('F'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('G'.$rc)->setValue(num($tot_margin));     
        $sheet->getStyle('G'.$rc)->applyFromArray($styleBoldRight);
        $rc++; 

        //retail
        $tot_gross_ret = 0;
        if(count($trans_ret) > 0){

            $rc++; 
            $col = 'A';
            $sheet->mergeCells('A'.$rc.':G'.$rc);
            $sheet->getCell('A'.$rc)->setValue('Retail Items');
            $sheet->getStyle('A'.$rc)->applyFromArray($styleCenter);
            $rc++;

            foreach ($headers as $txt) {
                $sheet->getCell($col.$rc)->setValue($txt);
                $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
                $col++;
            }
            $rc++;                          

            // GRAND TOTAL VARIABLES
            $tot_qty = 0;
            $tot_vat_sales = 0;
            $tot_vat = 0;
            $tot_mod_gross = 0;
            $tot_sales_prcnt = 0;
            $tot_cost = 0;
            $tot_cost_prcnt = 0; 
            $tot_margin = 0;
            $counter = 0;
            $progress = 0;
            $trans_count = count($trans_ret);
            foreach ($trans_ret as $val) {
                $tot_gross_ret += $val->gross; 
                $tot_margin += $val->gross - 0;
            }

            foreach ($trans_ret as $k => $v) {
                $sheet->getCell('A'.$rc)->setValue($v->name);
                $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('B'.$rc)->setValue($v->qty);
                $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
                // $sheet->getCell('C'.$rc)->setValue(num($v->vat_sales));
                // $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
                // $sheet->getCell('D'.$rc)->setValue(num($v->vat));     
                // $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('C'.$rc)->setValue(num($v->gross));     
                $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('D'.$rc)->setValue(num($v->gross / $tot_gross_ret * 100)."%");     
                $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
                $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('E'.$rc)->setValue(num(0));                                     
                if($tot_cost != 0){
                    $sheet->getCell('F'.$rc)->setValue(num($v->cost / $tot_cost * 100)."%");     
                }else{
                    $sheet->getCell('F'.$rc)->setValue('0.00%');                     
                }
                $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('G'.$rc)->setValue(num($v->gross - 0));     
                $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);       

                // Grand Total
                $tot_qty += $v->qty;
                // $tot_vat_sales += $v->vat_sales;
                // $tot_vat += $v->vat;
                // $tot_gross += $v->gross;
                $tot_sales_prcnt = 0;
                // $tot_cost += $v->cost;
                $tot_cost_prcnt = 0;

                $counter++;
                $progress = ($counter / $trans_count) * 100;
                update_load(num($progress));   
                $rc++;           
            }

            $sheet->getCell('A'.$rc)->setValue('Grand Total');
            $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
            $sheet->getCell('B'.$rc)->setValue(num($tot_qty));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);
            // $sheet->getCell('C'.$rc)->setValue(num($tot_vat_sales));     
            // $sheet->getStyle('C'.$rc)->applyFromArray($styleBoldRight);
            // $sheet->getCell('D'.$rc)->setValue(num($tot_vat));     
            // $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
            $sheet->getCell('C'.$rc)->setValue(num($tot_gross_ret));     
            $sheet->getStyle('C'.$rc)->applyFromArray($styleBoldRight);
            $sheet->getCell('D'.$rc)->setValue("");     
            $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
            $sheet->getCell('E'.$rc)->setValue(num($tot_cost));     
            $sheet->getStyle('E'.$rc)->applyFromArray($styleBoldRight);
            $sheet->getCell('F'.$rc)->setValue("");     
            $sheet->getStyle('F'.$rc)->applyFromArray($styleBoldRight);
            $sheet->getCell('G'.$rc)->setValue(num($tot_margin));     
            $sheet->getStyle('G'.$rc)->applyFromArray($styleBoldRight);
            $rc++; 
        }


        ///////////fpr payments
        $this->cashier_model->db = $this->load->database('main', TRUE);
        $args = array();
        // if($user)
        //     $args["trans_sales.user_id"] = array('use'=>'where','val'=>$user,'third'=>false);

        $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.inactive = 0"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);

        // if($menu_cat_id != 0){
        //     $args["menu_categories.menu_cat_id"] = array('use'=>'where','val'=>$menu_cat_id,'third'=>false);
        // }


        $post = $this->set_post();
        // $curr = $this->search_current();
        $curr = false;
        $trans = $this->trans_sales($args,$curr);
        $sales = $trans['sales'];

        $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
        $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
        $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
        $tax_disc = $trans_discounts['tax_disc_total'];
        $no_tax_disc = $trans_discounts['no_tax_disc_total'];
        $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
        $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
        $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
        $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
        $payments = $this->payment_sales($sales['settled']['ids'],$curr);

        $gross = $trans_menus['gross'];

        $net = $trans['net'];
        $void = $trans['void'];
        $charges = $trans_charges['total'];
        $discounts = $trans_discounts['total'];
        $local_tax = $trans_local_tax['total'];
        $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;

        if($less_vat < 0)
            $less_vat = 0;

        $tax = $trans_tax['total'];
        $no_tax = $trans_no_tax['total'];
        $zero_rated = $trans_zero_rated['total'];
        $no_tax -= $zero_rated;

        $loc_txt = numInt(($local_tax));
        $net_no_adds = $net-($charges+$local_tax);
        $nontaxable = $no_tax;
        $taxable = ($gross - $less_vat - $nontaxable - $zero_rated) / 1.12;
        $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
        $add_gt = $taxable+$nontaxable+$zero_rated;
        $nsss = $taxable +  $nontaxable +  $zero_rated;

        $vat_ = $taxable * .12;

        $rc++;
        $sheet->getCell('A'.$rc)->setValue('GROSS');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($tot_gross + $tot_mod_gross + $tot_gross_ret));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $sheet->getCell('A'.$rc)->setValue('VAT SALES');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($taxable));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $sheet->getCell('A'.$rc)->setValue('VAT');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($vat_));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $sheet->getCell('A'.$rc)->setValue('VAT EXEMPT SALES');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($nontaxable-$zero_rated));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $sheet->getCell('A'.$rc)->setValue('ZERO RATED');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($zero_rated));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        // //MENU SUB CAT
        $rc++; $rc++;
        $sheet->getCell('A'.$rc)->setValue('SUB CATEGORIES');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue('AMOUNT');
        $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        $subcats = $trans_menus['sub_cats'];
        $total = 0;
        foreach ($subcats as $id => $val) {
            $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper($val['name']));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            $total += $val['amount'];
        }
        if($tot_gross_ret != 0){
            $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper('RETAIL'));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue(num($tot_gross_ret));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            $total += $tot_gross_ret;
        }
        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('SubTotal'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($total));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        $rc++;
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('A'.$rc)->setValue(strtoupper('MODIFIERS TOTAL'));
        $sheet->getCell('B'.$rc)->setValue(num($trans_menus['mods_total']));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('TOTAL'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($total + $trans_menus['mods_total']));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        //DISCOUNTS
        $rc++; $rc++;
        $sheet->getCell('A'.$rc)->setValue('DISCOUNT');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue('AMOUNT');
        $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        $types = $trans_discounts['types'];
        foreach ($types as $code => $val) {
            $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper($val['name']));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            
        }

        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('Total'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($discounts));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('VAT EXEMPT'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($less_vat));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);


        // //CAHRGES
        $rc++; $rc++;
        $sheet->getCell('A'.$rc)->setValue('CHARGES');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue('AMOUNT');
        $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        $types = $trans_charges['types'];
        foreach ($types as $code => $val) {
            $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper($val['name']));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            
        }
        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('Total'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($charges));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        // //PAYMENTS
        $rc++; $rc++;
        $sheet->getCell('A'.$rc)->setValue('PAYMENT MODE');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue('PAYMENT AMOUNT');
        $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        $payments_types = $payments['types'];
        $payments_total = $payments['total'];
        foreach ($payments_types as $code => $val) {
            $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper($code));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        }

        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('Total'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($payments_total));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        if($trans['total_chit']){
            $rc++; $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper('Total Chit'));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
            $sheet->getCell('B'.$rc)->setValue(num($trans['total_chit']));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        }


        $types = $trans['types'];
        $types_total = array();
        $guestCount = 0;
        foreach ($types as $type => $tp) {
            foreach ($tp as $id => $opt){
                if(isset($types_total[$type])){
                    $types_total[$type] += round($opt->total_amount,2);

                }
                else{
                    $types_total[$type] = round($opt->total_amount,2);
                }
                if($opt->guest == 0)
                    $guestCount += 1;
                else
                    $guestCount += $opt->guest;
            }
        }
        $rc++;
        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('Trans Count'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $tc_total  = 0;
        $tc_qty = 0;
        foreach ($types_total as $typ => $tamnt) {
            $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper($typ));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue(count($types[$typ]));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            $sheet->getCell('C'.$rc)->setValue(num($tamnt));
            $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
            $tc_total += $tamnt;
            $tc_qty += count($types[$typ]);
        }
        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('TC TOTAL'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('C'.$rc)->setValue(num($tc_total));
        $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);

        
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

    public function item_sales_ui()
    {
        $data = $this->syter->spawn('sales_rep');        
        $data['page_title'] = fa('fa-money')."Item Sales Report";
        $data['code'] = itemRep();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['page_no_padding'] = false;
        $data['sideBarHide'] = false;
        $data['load_js'] = 'dine/reporting';
        $data['use_js'] = 'itemRepJS';
        $this->load->view('page',$data);
    }

    public function item_sales_rep_gen()
    {
        $this->load->model('dine/setup_model');
        // $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];
        start_load(0);
        $menu_cat_id = $this->input->post("menu_cat_id");        
        $daterange = $this->input->post("calendar_range");        
        $dates = explode(" to ",$daterange);        
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        // $date = $this->input->post("date");
        $this->menu_model->db = $this->load->database('main', TRUE);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        
        $trans = $this->menu_model->get_item_sales($from, $to);          
        // $trans_mod = $this->menu_model->get_mod_cat_sales_rep($from, $to, "");
        $trans_count = count($trans);
        $counter = 0;
        $this->make->sDiv();
            $this->make->sTable(array("id"=>"main-tbl", 'class'=>'table reportTBL sortable'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('Transaction Date');
                        $this->make->th('Item Code');
                        $this->make->th('Item Description');                        
                        $this->make->th('Category');
                        $this->make->th('Subc Category');
                        $this->make->th('Quantity Sold');                        
                        $this->make->th('Selling Price');
                        $this->make->th('Total');                        
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();
                    $tot_qty = 0;                                        
                    $total = 0;   
                    $tot_vat_sales = 0;
                    $tot_vat = 0;
                    $tot_gross = 0;
                    $tot_mod_gross = 0;
                    $tot_sales_prcnt = 0;
                    $tot_cost = 0;
                    $tot_cost_prcnt = 0; 
                    $tot_margin = 0;
                    $counter = 0;
                    $progress = 0;
                    $trans_count = count($trans);
                    // echo print_r($trans);die();
                    foreach ($trans as $val) {
                        $tot_gross += $val->item_gross;
                        $tot_cost += 0;
                    }
                    // foreach ($trans_mod as $vv) {
                    //     $tot_mod_gross += $vv->mod_gross;
                    // }                                          
                    
                    foreach ($trans as $res) {
                        $this->make->sRow();
                            $this->make->td(sql2Date($res->date));
                            $this->make->td($res->code);
                            $this->make->td($res->item_name);                            
                            $this->make->td($res->cat_name);                                                       
                            $this->make->td($res->sub_cat_name);                                                       
                            $this->make->td(num($res->tot_qty), array("style"=>"text-align:right"));                                                        
                            $this->make->td(num($res->price), array("style"=>"text-align:right"));                            
                            $this->make->td(num($res->tot_qty * $res->price), array("style"=>"text-align:right"));                            
                        $this->make->eRow();

                        // Grand Total
                        $tot_qty += $res->tot_qty;                        
                        $total += $res->tot_qty * $res->price;                        

                        $counter++;
                        $progress = ($counter / $trans_count) * 100;
                        update_load(num($progress));

                    }    
                    $this->make->sRow();
                        $this->make->th('Grand Total');
                        $this->make->th("", array("style"=>"text-align:right"));                        
                        $this->make->th("", array("style"=>"text-align:right"));
                        $this->make->th("", array("style"=>"text-align:right"));
                        $this->make->th("", array("style"=>"text-align:right"));
                        $this->make->th(num($tot_qty), array("style"=>"text-align:right"));
                        $this->make->th("", array("style"=>"text-align:right"));                                                
                        $this->make->th(num($total), array("style"=>"text-align:right"));                        
                    $this->make->eRow();                                 

                    $this->make->sRow();
                        ///////////fpr payments
                        $this->cashier_model->db = $this->load->database('main', TRUE);
                        $args = array();
                        // if($user)
                        //     $args["trans_sales.user_id"] = array('use'=>'where','val'=>$user,'third'=>false);
                        $daterange = $this->input->post("calendar_range");        
                        $dates = explode(" to ",$daterange);
                        $from = date2SqlDateTime($dates[0]);
                        $to = date2SqlDateTime($dates[1]);
                        $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
                        $args["trans_sales.inactive = 0"] = array('use'=>'where','val'=>null,'third'=>false);
                        $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
                        // $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);

                        // $terminal = TERMINAL_ID;
                        // $args['trans_sales.terminal_id'] = $terminal;
                        // if($menu_cat_id != 0){
                        //     $args["menu_categories.menu_cat_id"] = array('use'=>'where','val'=>$menu_cat_id,'third'=>false);
                        // }


                        // $post = $this->set_post();
                        // $curr = $this->search_current();
                        $curr = false;
                        $trans = $this->trans_sales($args,$curr);
                        // $trans = $this->trans_sales_cat($args,false);
                        $sales = $trans['sales'];

                        $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
                        $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
                        $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
                        $tax_disc = $trans_discounts['tax_disc_total'];
                        $no_tax_disc = $trans_discounts['no_tax_disc_total'];
                        $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
                        $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
                        $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
                        $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
                        $payments = $this->payment_sales($sales['settled']['ids'],$curr);

                        $gross = $trans_menus['item_total'];

                        // if($gross > 0){
                        $net = $trans['net'];
                        $void = $trans['void'];
                        $charges = $trans_charges['total'];
                        $discounts = $trans_discounts['total'];
                        $local_tax = $trans_local_tax['total'];
                        $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;

                        if($less_vat < 0)
                            $less_vat = 0;

                        $tax = $trans_tax['total'];
                        $no_tax = $trans_no_tax['total'];
                        $zero_rated = $trans_zero_rated['total'];
                        $no_tax -= $zero_rated;

                        $loc_txt = numInt(($local_tax));
                        $net_no_adds = $net-($charges+$local_tax);
                        if($gross <= 0){
                            $taxable = 0;
                            $nontaxable = 0;
                        }else{
                            $nontaxable = $no_tax - $no_tax_disc;
                            $taxable =   ($gross - $discounts - $less_vat - $nontaxable) / 1.12;
                        }
                        $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
                        $add_gt = $taxable+$nontaxable+$zero_rated;
                        $nsss = $taxable +  $nontaxable +  $zero_rated;

                        $vat_ = $taxable * .12;
                        // }

                        $this->make->sRow();                        
                            $this->make->td("GROSS");
                            $this->make->td(num($tot_gross + $tot_mod_gross),array("style"=>"text-align:right"));                        
                        $this->make->sRow();
                        $this->make->sRow();                        
                            $this->make->td("VAT SALES");
                            $this->make->td(num($taxable),array("style"=>"text-align:right"));                        
                        $this->make->sRow();
                        $this->make->sRow();                        
                            $this->make->td("VAT");
                            $this->make->td(num($vat_),array("style"=>"text-align:right"));                        
                        $this->make->sRow();
                        $this->make->sRow();                        
                            $this->make->td("VAT EXEMPT SALES");
                            $this->make->td(num($nontaxable-$zero_rated),array("style"=>"text-align:right"));                        
                        $this->make->sRow();
                        $this->make->sRow();                        
                            $this->make->td("ZERO RATED");
                            $this->make->td(num($zero_rated),array("style"=>"text-align:right"));                        
                        $this->make->sRow();
                        
                        // //MENU SUB CAT
                        // $pdf->ln(7);
                        // $pdf->SetFont('helvetica', 'B', 9);
                        // $pdf->Cell(30, 0, strtoupper('Sub Categories'), '', 0, 'L');
                        // $pdf->Cell(35, 0, strtoupper('Amount'), '', 0, 'R');
                        // $pdf->SetFont('helvetica', '', 9);

                        // $subcats = $trans_menus['sub_cats'];
                        // $qty = 0;
                        // $total = 0;
                        // foreach ($subcats as $id => $val) {
                        //     $pdf->ln();
                        //     $pdf->Cell(30, 0, strtoupper($val['name']), '', 0, 'L');
                        //     $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
                        //     $total += $val['amount'];
                        // }

                        // $pdf->ln();
                        // $pdf->SetFont('helvetica', 'B', 9);
                        // $pdf->Cell(30, 0,'SUBTOTAL ', 'T', 0, 'L');
                        // $pdf->SetFont('helvetica', '', 9);
                        // $pdf->Cell(35, 0, num($total), 'T', 0, 'R');

                        // // numInt($trans_menus['mods_total'])
                        // $pdf->ln();
                        // // $pdf->SetFont('helvetica', 'B', 9);
                        // $pdf->Cell(30, 0,'MODIFIERS TOTAL ', '', 0, 'L');
                        // $pdf->SetFont('helvetica', '', 9);
                        // $pdf->Cell(35, 0, num($trans_menus['mods_total']), '', 0, 'R');

                        // $pdf->ln();
                        // $pdf->SetFont('helvetica', 'B', 9);
                        // $pdf->Cell(30, 0,'TOTAL ', 'T', 0, 'L');
                        // $pdf->SetFont('helvetica', '', 9);
                        // $pdf->Cell(35, 0, num($total + $trans_menus['mods_total']), 'T', 0, 'R');


                        // //DISCOUNTS
                        // $pdf->ln(7);
                        // $pdf->SetFont('helvetica', 'B', 9);
                        // $pdf->Cell(60, 0, strtoupper('Discount'), '', 0, 'L');
                        // $pdf->Cell(35, 0, strtoupper('Amount'), '', 0, 'R');
                        // $pdf->SetFont('helvetica', '', 9);

                        // $types = $trans_discounts['types'];
                        // foreach ($types as $code => $val) {
                        //     $pdf->ln();
                        //     $pdf->Cell(60, 0, strtoupper($val['name']), '', 0, 'L');
                        //     $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
                            
                        // }

                        // $pdf->ln();
                        // $pdf->SetFont('helvetica', 'B', 9);
                        // $pdf->Cell(60, 0,'TOTAL ', 'T', 0, 'L');
                        // $pdf->SetFont('helvetica', '', 9);
                        // $pdf->Cell(35, 0, num($discounts), 'T', 0, 'R');
                        // $pdf->ln();
                        // $pdf->SetFont('helvetica', 'B', 9);
                        // $pdf->Cell(60, 0,'VAT EXEMPT ', '', 0, 'L');
                        // $pdf->SetFont('helvetica', '', 9);
                        // $pdf->Cell(35, 0, num($less_vat), '', 0, 'R');


                        // //CAHRGES
                        // $pdf->ln(7);
                        // $pdf->SetFont('helvetica', 'B', 9);
                        // $pdf->Cell(30, 0, strtoupper('Charges'), '', 0, 'L');
                        // $pdf->Cell(35, 0, strtoupper('Amount'), '', 0, 'R');
                        // $pdf->SetFont('helvetica', '', 9);
                        // // $pdf->ln();

                        // $types = $trans_charges['types'];
                        // foreach ($types as $code => $val) {
                        //     $pdf->ln();
                        //     $pdf->Cell(30, 0, strtoupper($val['name']), '', 0, 'L');
                        //     $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
                            
                        // }
                           
                        // $pdf->ln();
                        // $pdf->SetFont('helvetica', 'B', 9);
                        // $pdf->Cell(30, 0,'TOTAL ', 'T', 0, 'L');
                        // $pdf->SetFont('helvetica', '', 9);
                        // $pdf->Cell(35, 0, num($charges), 'T', 0, 'R');


                        // //PAYMENTS
                        // $pdf->ln(7);
                        // $pdf->SetFont('helvetica', 'B', 9);
                        // $pdf->Cell(30, 0, strtoupper('Payment Mode'), '', 0, 'L');
                        // $pdf->Cell(35, 0, strtoupper('Payment Amount'), '', 0, 'R');
                        // $pdf->SetFont('helvetica', '', 9);
                        // // $pdf->ln();


                        // $payments_types = $payments['types'];
                        // $payments_total = $payments['total'];
                        // foreach ($payments_types as $code => $val) {
                        // $pdf->ln();
                        //     $pdf->Cell(30, 0, strtoupper($code), '', 0, 'L');
                        //     $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
                            
                        // }
                           
                        // $pdf->ln();
                        // $pdf->SetFont('helvetica', 'B', 9);
                        // $pdf->Cell(30, 0,'TOTAL ', 'T', 0, 'L');
                        // $pdf->SetFont('helvetica', '', 9);
                        // $pdf->Cell(35, 0, num($payments_total), 'T', 0, 'R');


                        // if($trans['total_chit']){
                        //     // $print_str .= append_chars(substrwords('TOTAL CHIT',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
                        //     //               .append_chars(numInt($trans['total_chit']),"left",PAPER_RD_COL_3_3," ")."\r\n";
                        //     // $print_str .= "\r\n";
                        //     $pdf->ln(7);
                        //     $pdf->SetFont('helvetica', 'B', 9);
                        //     $pdf->Cell(30, 0,'TOTAL CHIT ', '', 0, 'L');
                        //     $pdf->SetFont('helvetica', '', 9);
                        //     $pdf->Cell(35, 0, num($trans['total_chit']), '', 0, 'R');
                        // }



                        // $types = $trans['types'];
                        // $types_total = array();
                        // $guestCount = 0;
                        // foreach ($types as $type => $tp) {
                        //     foreach ($tp as $id => $opt){
                        //         if(isset($types_total[$type])){
                        //             $types_total[$type] += round($opt->total_amount,2);

                        //         }
                        //         else{
                        //             $types_total[$type] = round($opt->total_amount,2);
                        //         }
                        //         if($opt->guest == 0)
                        //             $guestCount += 1;
                        //         else
                        //             $guestCount += $opt->guest;
                        //     }
                        // }

                        // $pdf->ln(7);
                        // $pdf->SetFont('helvetica', 'B', 9);
                        // $pdf->Cell(30, 0,'TRANS COUNT ', '', 0, 'L');
                        // $tc_total  = 0;
                        // $tc_qty = 0;
                        // foreach ($types_total as $typ => $tamnt) {
                        //     $pdf->SetFont('helvetica', '', 9);
                        //     $pdf->ln();
                        //     $pdf->Cell(30, 0, strtoupper($typ), '', 0, 'L');
                        //     $pdf->Cell(20, 0, count($types[$typ]), '', 0, 'L');
                        //     $pdf->Cell(25, 0, num($tamnt), '', 0, 'R');
                        //     $tc_total += $tamnt;
                        //     $tc_qty += count($types[$typ]);
                        // }
                        // $pdf->ln();
                        // $pdf->SetFont('helvetica', 'B', 9);
                        // $pdf->Cell(50, 0, 'TC TOTAL', 'T', 0, 'L');
                        //     $pdf->SetFont('helvetica', '', 9);
                        // // $pdf->Cell(20, 0, count($types[$typ]), '', 0, 'L');
                        // $pdf->Cell(25, 0, num($tc_total), 'T', 0, 'R');
                    $this->make->eRow();

                $this->make->eTableBody();
            $this->make->eTable();
        $this->make->eDiv();
        update_load(100);
        $code = $this->make->code();
        $json['code'] = $code;        
        $json['tbl_vals'] = $trans;
        $json['dates'] = $this->input->post('calendar_range');
        echo json_encode($json);
    }

    public function item_sales_rep_excel()
    {
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        date_default_timezone_set('Asia/Manila');
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $filename = 'Item Sales Report';
        $rc=1;
        #GET VALUES
        start_load(0);
            // $post = $this->set_post($_GET['calendar_range']);
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];

        update_load(10);
        sleep(1);
        
        $daterange = $_GET['calendar_range'];        
        $dates = explode(" to ",$daterange);        
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        $trans = $this->menu_model->get_item_sales($from, $to); 
        $trans_mod = $this->menu_model->get_mod_cat_sales_rep($from, $to, "");       

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
        
        $headers = array('Transaction Date', 'Item Code','Item Description','Category','Sub Category','Quantity Sold','Price', 'Total');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);


        $sheet->mergeCells('A'.$rc.':H'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->branch_name);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->address);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Sales Report');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Report Period: '.$daterange);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('E'.$rc)->setValue('Report Generated: '.(new \DateTime())->format('Y-m-d H:i:s'));
        $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Transaction Time:');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $user = $this->session->userdata('user');
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('F'.$rc)->setValue('Generated by:    '.$user["full_name"]);
        $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        $rc++;

        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }
        $rc++;                          

        // GRAND TOTAL VARIABLES
        $tot_qty = 0;
        $total = 0;
        $tot_vat_sales = 0;
        $tot_vat = 0;
        $tot_gross = 0;
        $tot_mod_gross = 0;
        $tot_sales_prcnt = 0;
        $tot_cost = 0;
        $tot_cost_prcnt = 0; 
        $tot_margin = 0;
        $counter = 0;
        $progress = 0;
        $trans_count = count($trans);
        // echo print_r($trans);die();
        foreach ($trans as $val) {
            $tot_gross += $val->item_gross;
            $tot_cost += 0;
        }
        foreach ($trans_mod as $vv) {
            $tot_mod_gross += $vv->mod_gross;
        }        

        foreach ($trans as $k => $v) {
            $sheet->getCell('A'.$rc)->setValue($v->date);
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue($v->code);
            $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);            
            $sheet->getCell('C'.$rc)->setValue(num($v->item_name));     
            $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('D'.$rc)->setValue($v->cat_name);     
            $sheet->getStyle('D'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('E'.$rc)->setValue($v->sub_cat_name);     
            $sheet->getStyle('E'.$rc)->applyFromArray($styleTxt);            
            $sheet->getCell('F'.$rc)->setValue(num($v->tot_qty));                                 
            $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
            $sheet->getCell('G'.$rc)->setValue(num($v->price));     
            $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
            $sheet->getCell('H'.$rc)->setValue(num($v->tot_qty * $v->price));     
            $sheet->getStyle('H'.$rc)->applyFromArray($styleNum);       

            // Grand Total
            $tot_qty += $v->tot_qty;
            $total += $v->tot_qty * $v->price;
            
            $tot_sales_prcnt = 0;
            // $tot_cost += $v->cost;
            $tot_cost_prcnt = 0;

            $counter++;
            $progress = ($counter / $trans_count) * 100;
            update_load(num($progress));   
            $rc++;           
        }

        $sheet->getCell('A'.$rc)->setValue('Grand Total');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('F'.$rc)->setValue(num($tot_qty));
        $sheet->getStyle('F'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('H'.$rc)->setValue(num($total));     
        $sheet->getStyle('H'.$rc)->applyFromArray($styleBoldRight);
        $rc++;

        ///////////fpr payments
        $this->cashier_model->db = $this->load->database('main', TRUE);
        $args = array();
        // if($user)
        //     $args["trans_sales.user_id"] = array('use'=>'where','val'=>$user,'third'=>false);

        $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.inactive = 0"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);

        // if($menu_cat_id != 0){
        //     $args["menu_categories.menu_cat_id"] = array('use'=>'where','val'=>$menu_cat_id,'third'=>false);
        // }


        $post = $this->set_post();
        // $curr = $this->search_current();
        $curr = false;
        $trans = $this->trans_sales($args,$curr);
        $sales = $trans['sales'];

        $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
        $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
        $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
        $tax_disc = $trans_discounts['tax_disc_total'];
        $no_tax_disc = $trans_discounts['no_tax_disc_total'];
        $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
        $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
        $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
        $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
        $payments = $this->payment_sales($sales['settled']['ids'],$curr);

        $gross = $trans_menus['gross'];

        $net = $trans['net'];
        $void = $trans['void'];
        $charges = $trans_charges['total'];
        $discounts = $trans_discounts['total'];
        $local_tax = $trans_local_tax['total'];
        $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;

        if($less_vat < 0)
            $less_vat = 0;

        $tax = $trans_tax['total'];
        $no_tax = $trans_no_tax['total'];
        $zero_rated = $trans_zero_rated['total'];
        $no_tax -= $zero_rated;

        $loc_txt = numInt(($local_tax));
        $net_no_adds = $net-($charges+$local_tax);
        $nontaxable = $no_tax - $no_tax_disc;
        $taxable =   ($gross - $discounts - $less_vat - $nontaxable) / 1.12;
        $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
        $add_gt = $taxable+$nontaxable+$zero_rated;
        $nsss = $taxable +  $nontaxable +  $zero_rated;

        $vat_ = $taxable * .12;

        $rc++;
        $sheet->getCell('A'.$rc)->setValue('GROSS');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($tot_gross + $tot_mod_gross));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $sheet->getCell('A'.$rc)->setValue('VAT SALES');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($taxable));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $sheet->getCell('A'.$rc)->setValue('VAT');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($vat_));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $sheet->getCell('A'.$rc)->setValue('VAT EXEMPT SALES');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($nontaxable-$zero_rated));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $sheet->getCell('A'.$rc)->setValue('ZERO RATED');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($zero_rated));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        // //MENU SUB CAT
        $rc++; $rc++;
        $sheet->getCell('A'.$rc)->setValue('SUB CATEGORIES');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue('AMOUNT');
        $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        $subcats = $trans_menus['sub_cats'];
        $total = 0;
        foreach ($subcats as $id => $val) {
            $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper($val['name']));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            $total += $val['amount'];
        }
        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('SubTotal'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($total));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        $rc++;
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('A'.$rc)->setValue(strtoupper('MODIFIERS TOTAL'));
        $sheet->getCell('B'.$rc)->setValue(num($trans_menus['mods_total']));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('TOTAL'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($total + $trans_menus['mods_total']));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        //DISCOUNTS
        $rc++; $rc++;
        $sheet->getCell('A'.$rc)->setValue('DISCOUNT');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue('AMOUNT');
        $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        $types = $trans_discounts['types'];
        foreach ($types as $code => $val) {
            $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper($val['name']));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            
        }

        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('Total'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($discounts));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('VAT EXEMPT'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($less_vat));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);


        // //CAHRGES
        $rc++; $rc++;
        $sheet->getCell('A'.$rc)->setValue('CHARGES');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue('AMOUNT');
        $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        $types = $trans_charges['types'];
        foreach ($types as $code => $val) {
            $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper($val['name']));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            
        }
        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('Total'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($charges));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        // //PAYMENTS
        $rc++; $rc++;
        $sheet->getCell('A'.$rc)->setValue('PAYMENT MODE');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue('PAYMENT AMOUNT');
        $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);

        $payments_types = $payments['types'];
        $payments_total = $payments['total'];
        foreach ($payments_types as $code => $val) {
            $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper($code));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue(num($val['amount']));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        }

        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('Total'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($payments_total));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);

        if($trans['total_chit']){
            $rc++; $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper('Total Chit'));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
            $sheet->getCell('B'.$rc)->setValue(num($trans['total_chit']));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        }


        $types = $trans['types'];
        $types_total = array();
        $guestCount = 0;
        foreach ($types as $type => $tp) {
            foreach ($tp as $id => $opt){
                if(isset($types_total[$type])){
                    $types_total[$type] += round($opt->total_amount,2);

                }
                else{
                    $types_total[$type] = round($opt->total_amount,2);
                }
                if($opt->guest == 0)
                    $guestCount += 1;
                else
                    $guestCount += $opt->guest;
            }
        }
        $rc++;
        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('Trans Count'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $tc_total  = 0;
        $tc_qty = 0;
        foreach ($types_total as $typ => $tamnt) {
            $rc++;
            $sheet->getCell('A'.$rc)->setValue(strtoupper($typ));
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue(count($types[$typ]));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            $sheet->getCell('C'.$rc)->setValue(num($tamnt));
            $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
            $tc_total += $tamnt;
            $tc_qty += count($types[$typ]);
        }
        $rc++;
        $sheet->getCell('A'.$rc)->setValue(strtoupper('TC TOTAL'));
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('C'.$rc)->setValue(num($tc_total));
        $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
        
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
    public function item_sales_rep_pdf()
    {
        // Include the main TCPDF library (search for installation path).
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Item Sales Report');
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

        // ---------------------------------------------------------
        $this->load->model('dine/setup_model');
        // $this->load->database('main', TRUE);
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
        
        $daterange = $_GET['calendar_range'];        
        $dates = explode(" to ",$daterange);        
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        $trans = $this->menu_model->get_item_sales($from, $to);        
        // $trans_mod = $this->menu_model->get_mod_cat_sales_rep($from, $to, "");


        $pdf->Write(0, 'Item Sales Report', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Write(0, 'Report Period:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, $daterange, '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Transaction Time:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln();              

        // echo "<pre>", print_r($trans), "</pre>";die();

        // -----------------------------------------------------------------------------
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(25, 0, 'Trans Date', 'B', 0, 'L');        
        $pdf->Cell(30, 0, 'Item Code', 'B', 0, 'L');                
        $pdf->Cell(110, 0, 'Item Description', 'B', 0, 'L');        
        $pdf->Cell(25, 0, 'Category', 'B', 0, 'L');        
        $pdf->Cell(16, 0, 'Sub Cat', 'B', 0, 'L');        
        $pdf->Cell(20, 0, 'Qty Sold', 'B', 0, 'R');        
        $pdf->Cell(20, 0, 'Selling Price', 'B', 0, 'R');        
        $pdf->Cell(20, 0, 'Total', 'B', 0, 'R');        
        $pdf->ln();                  

        // GRAND TOTAL VARIABLES
        $tot_qty = 0;
        $total = 0;
        $tot_vat_sales = 0;
        $tot_vat = 0;
        $tot_gross = 0;
        $tot_mod_gross = 0;
        $tot_sales_prcnt = 0;
        $tot_cost = 0;
        $tot_cost_prcnt = 0; 
        $tot_margin = 0;
        $counter = 0;
        $progress = 0;
        $trans_count = count($trans);

        foreach ($trans as $val) {
            $tot_gross += $val->item_gross;
            $tot_cost += 0;
        }

        foreach ($trans as $k => $v) {
            $pdf->Cell(25, 0, sql2Date($v->date), '', 0, 'L');        
            $pdf->Cell(30, 0, $v->code, '', 0, 'L'); 

            if (strlen($v->item_name)) {
                $width = 110;
                $font_size = 9;
                do {
                    $font_size -= 0.5;
                    $string_font_width = $pdf->GetStringWidth(ucwords(strtolower($v->item_name)),'helvetica','',$font_size) + 3; # +4 Not exactly sure but works
                } while ($string_font_width > $width);

                // $pdf->SetFont('dejavb','',$font_size);
                $pdf->SetFont('helvetica', '', $font_size);
               
            }                   
            $pdf->Cell(110, 0, $v->item_name, '', 0, 'L');        
            $pdf->SetFont('helvetica', '', 9);

            if (strlen($v->cat_name)) {
                $width = 25;
                $font_size = 9;
                do {
                    $font_size -= 0.5;
                    $string_font_width = $pdf->GetStringWidth(ucwords(strtolower($v->cat_name)),'helvetica','',$font_size) + 3; # +4 Not exactly sure but works
                } while ($string_font_width > $width);

                // $pdf->SetFont('dejavb','',$font_size);
                $pdf->SetFont('helvetica', '', $font_size);
               
            }
            $pdf->Cell(25, 0, $v->cat_name, '', 0, 'L');
            $pdf->SetFont('helvetica', '', 9);

            if (strlen($v->sub_cat_name)) {
                $width = 16;
                $font_size = 9;
                do {
                    $font_size -= 0.5;
                    $string_font_width = $pdf->GetStringWidth(ucwords(strtolower($v->sub_cat_name)),'helvetica','',$font_size) + 3; # +4 Not exactly sure but works
                } while ($string_font_width > $width);

                // $pdf->SetFont('dejavb','',$font_size);
                $pdf->SetFont('helvetica', '', $font_size);
               
            }
            $pdf->Cell(16, 0, $v->sub_cat_name, '', 0, 'L');
            $pdf->SetFont('helvetica', '', 9);

            $pdf->Cell(20, 0, num($v->tot_qty), '', 0, 'R');                                
            $pdf->Cell(20, 0, num($v->price), '', 0, 'R');                                    
            $pdf->Cell(20, 0, num($v->tot_qty * $v->price), '', 0, 'R');        
            $pdf->ln();                

            // Grand Total
            $tot_qty += $v->tot_qty;
            $total += $v->tot_qty * $v->price;    

           
            // echo print_r($trans);die();
            // foreach ($trans_mod as $vv) {
            //     $tot_mod_gross += $vv->mod_gross;
            // }

            $counter++;
            $progress = ($counter / $trans_count) * 100;
            update_load(num($progress));              
        }
        update_load(100);        
        $pdf->Cell(25, 0, "Grand Total", 'T', 0, 'L');        
        $pdf->Cell(30, 0, "", 'T', 0, 'R');                
        $pdf->Cell(110, 0, "", 'T', 0, 'R');        
        $pdf->Cell(25, 0, "", 'T', 0, 'R');        
        $pdf->Cell(16, 0, "", 'T', 0, 'R');        
        $pdf->Cell(20, 0, num($tot_qty), 'T', 0, 'R');        
        $pdf->Cell(20, 0, "", 'T', 0, 'R'); 
        $pdf->Cell(20, 0, num($total), 'T', 0, 'R'); 

        ///////////fpr payments
        $this->cashier_model->db = $this->load->database('main', TRUE);
        $args = array();
        // if($user)
        //     $args["trans_sales.user_id"] = array('use'=>'where','val'=>$user,'third'=>false);

        $args["trans_sales.trans_ref  IS NOT NULL"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.inactive = 0"] = array('use'=>'where','val'=>null,'third'=>false);
        $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);
        // $args["trans_sales.datetime between '".$from."' and '".$to."'"] = array('use'=>'where','val'=>null,'third'=>false);

        // $terminal = TERMINAL_ID;
        // $args['trans_sales.terminal_id'] = $terminal;
        // if($menu_cat_id != 0){
        //     $args["menu_categories.menu_cat_id"] = array('use'=>'where','val'=>$menu_cat_id,'third'=>false);
        // }


        // $post = $this->set_post();
        // $curr = $this->search_current();
        $curr = false;
        $trans = $this->trans_sales($args,$curr);
        // $trans = $this->trans_sales_cat($args,false);
        $sales = $trans['sales'];

        $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
        $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
        $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
        $tax_disc = $trans_discounts['tax_disc_total'];
        $no_tax_disc = $trans_discounts['no_tax_disc_total'];
        $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
        $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
        $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
        $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
        $payments = $this->payment_sales($sales['settled']['ids'],$curr);

        $gross = $trans_menus['gross'];

        $net = $trans['net'];
        $void = $trans['void'];
        $charges = $trans_charges['total'];
        $discounts = $trans_discounts['total'];
        $local_tax = $trans_local_tax['total'];
        $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;

        if($less_vat < 0)
            $less_vat = 0;


        $tax = $trans_tax['total'];
        $no_tax = $trans_no_tax['total'];
        $zero_rated = $trans_zero_rated['total'];
        $no_tax -= $zero_rated;

        $loc_txt = numInt(($local_tax));
        $net_no_adds = $net-($charges+$local_tax);
        $nontaxable = $no_tax - $no_tax_disc;
        $taxable =   ($gross - $discounts - $less_vat - $nontaxable) / 1.12;
        $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
        $add_gt = $taxable+$nontaxable+$zero_rated;
        $nsss = $taxable +  $nontaxable +  $zero_rated;

        $vat_ = $taxable * .12;

        $pdf->ln(7);
        $pdf->Cell(30, 0, 'GROSS', '', 0, 'L');
        $pdf->Cell(35, 0, num($tot_gross + $tot_mod_gross), '', 0, 'R');
        $pdf->ln();
        $pdf->Cell(30, 0, 'VAT SALES', '', 0, 'L');
        $pdf->Cell(35, 0, num($taxable), '', 0, 'R');
        $pdf->ln();
        $pdf->Cell(30, 0, 'VAT', '', 0, 'L');
        $pdf->Cell(35, 0, num($vat_), '', 0, 'R');
        $pdf->ln();
        $pdf->Cell(30, 0, 'VAT EXEMPT SALES', '', 0, 'L');
        $pdf->Cell(35, 0, num($nontaxable-$zero_rated), '', 0, 'R');
        $pdf->ln();
        $pdf->Cell(30, 0, 'ZERO RATED', '', 0, 'L');
        $pdf->Cell(35, 0, num($zero_rated), '', 0, 'R');


        //MENU SUB CAT
        $pdf->ln(7);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0, strtoupper('Sub Categories'), '', 0, 'L');
        $pdf->Cell(35, 0, strtoupper('Amount'), '', 0, 'R');
        $pdf->SetFont('helvetica', '', 9);

        $subcats = $trans_menus['sub_cats'];
        $qty = 0;
        $total = 0;
        foreach ($subcats as $id => $val) {
            $pdf->ln();
            $pdf->Cell(30, 0, strtoupper($val['name']), '', 0, 'L');
            $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
            $total += $val['amount'];
        }

        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0,'SUBTOTAL ', 'T', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($total), 'T', 0, 'R');

        // numInt($trans_menus['mods_total'])
        $pdf->ln();
        // $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0,'MODIFIERS TOTAL ', '', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($trans_menus['mods_total']), '', 0, 'R');

        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0,'TOTAL ', 'T', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($total + $trans_menus['mods_total']), 'T', 0, 'R');


        //DISCOUNTS
        $pdf->ln(7);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(60, 0, strtoupper('Discount'), '', 0, 'L');
        $pdf->Cell(35, 0, strtoupper('Amount'), '', 0, 'R');
        $pdf->SetFont('helvetica', '', 9);

        $types = $trans_discounts['types'];
        foreach ($types as $code => $val) {
            $pdf->ln();
            $pdf->Cell(60, 0, strtoupper($val['name']), '', 0, 'L');
            $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
            
        }

        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(60, 0,'TOTAL ', 'T', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($discounts), 'T', 0, 'R');
        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(60, 0,'VAT EXEMPT ', '', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($less_vat), '', 0, 'R');


        //CAHRGES
        $pdf->ln(7);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0, strtoupper('Charges'), '', 0, 'L');
        $pdf->Cell(35, 0, strtoupper('Amount'), '', 0, 'R');
        $pdf->SetFont('helvetica', '', 9);
        // $pdf->ln();

        $types = $trans_charges['types'];
        foreach ($types as $code => $val) {
            $pdf->ln();
            $pdf->Cell(30, 0, strtoupper($val['name']), '', 0, 'L');
            $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
            
        }
           
        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0,'TOTAL ', 'T', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($charges), 'T', 0, 'R');


        //PAYMENTS
        $pdf->ln(7);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0, strtoupper('Payment Mode'), '', 0, 'L');
        $pdf->Cell(35, 0, strtoupper('Payment Amount'), '', 0, 'R');
        $pdf->SetFont('helvetica', '', 9);
        // $pdf->ln();


        $payments_types = $payments['types'];
        $payments_total = $payments['total'];
        foreach ($payments_types as $code => $val) {
        $pdf->ln();
            $pdf->Cell(30, 0, strtoupper($code), '', 0, 'L');
            $pdf->Cell(35, 0, num($val['amount']), '', 0, 'R');
            
        }
           
        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0,'TOTAL ', 'T', 0, 'L');
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(35, 0, num($payments_total), 'T', 0, 'R');


        if($trans['total_chit']){
            // $print_str .= append_chars(substrwords('TOTAL CHIT',18,""),"right",PAPER_RD_COL_1," ").align_center('',PAPER_RD_COL_2," ")
            //               .append_chars(numInt($trans['total_chit']),"left",PAPER_RD_COL_3_3," ")."\r\n";
            // $print_str .= "\r\n";
            $pdf->ln(7);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->Cell(30, 0,'TOTAL CHIT ', '', 0, 'L');
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(35, 0, num($trans['total_chit']), '', 0, 'R');
        }



        $types = $trans['types'];
        $types_total = array();
        $guestCount = 0;
        foreach ($types as $type => $tp) {
            foreach ($tp as $id => $opt){
                if(isset($types_total[$type])){
                    $types_total[$type] += round($opt->total_amount,2);

                }
                else{
                    $types_total[$type] = round($opt->total_amount,2);
                }
                if($opt->guest == 0)
                    $guestCount += 1;
                else
                    $guestCount += $opt->guest;
            }
        }

        $pdf->ln(7);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(30, 0,'TRANS COUNT ', '', 0, 'L');
        $tc_total  = 0;
        $tc_qty = 0;
        foreach ($types_total as $typ => $tamnt) {
            $pdf->SetFont('helvetica', '', 9);
            $pdf->ln();
            $pdf->Cell(30, 0, strtoupper($typ), '', 0, 'L');
            $pdf->Cell(20, 0, count($types[$typ]), '', 0, 'L');
            $pdf->Cell(25, 0, num($tamnt), '', 0, 'R');
            $tc_total += $tamnt;
            $tc_qty += count($types[$typ]);
        }
        $pdf->ln();
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(50, 0, 'TC TOTAL', 'T', 0, 'L');
            $pdf->SetFont('helvetica', '', 9);
        // $pdf->Cell(20, 0, count($types[$typ]), '', 0, 'L');
        $pdf->Cell(25, 0, num($tc_total), 'T', 0, 'R');
        

        // -----------------------------------------------------------------------------

        //Close and output PDF document
        $pdf->Output('item_sales_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+   
    }

    public function void_sales_rep(){
        $data = $this->syter->spawn('sales_rep');        
        $data['page_title'] = fa('fa-money')." Voided Sales Report";
        $data['code'] = voidedSalesRep();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['page_no_padding'] = false;
        $data['sideBarHide'] = false;
        $data['load_js'] = 'dine/reporting';
        $data['use_js'] = 'voidSalesRepJS';
        $this->load->view('page',$data);
    }
    public function void_res_rep(){
        $data = $this->syter->spawn('sales_rep');        
        $data['page_title'] = fa('fa-money')." Voided Reservation Report";
        $data['code'] = voidedSalesRes();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['page_no_padding'] = false;
        $data['sideBarHide'] = false;
        $data['load_js'] = 'dine/reporting';
        $data['use_js'] = 'voidSalesResJS';
        $this->load->view('page',$data);
    }

    public function voided_sales_rep_gen()
    {
        $this->load->model('dine/setup_model');
        
        $this->load->model("dine/menu_model");
        
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];
        start_load(0);
        $menu_cat_id = $this->input->post("menu_cat_id");        
        $daterange = $this->input->post("calendar_range");        
        $dates = explode(" to ",$daterange);
        
        $this->menu_model->db = $this->load->database('main', TRUE);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        $trans = $this->menu_model->get_voided_cat_sales_rep($from, $to, $menu_cat_id);  
        $trans_ret = $this->menu_model->get_voided_cat_sales_rep_retail($from, $to, "");  
        
        $trans_count = count($trans);
        $trans_count_ret = count($trans_ret);
        $counter = 0;

        $this->make->sDiv();
            $this->make->sTable(array("id"=>"main-tbl", 'class'=>'table reportTBL sortable'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('Category');
                        $this->make->th('Qty');
                        $this->make->th('Gross');
                        $this->make->th('Sales (%)');
                        $this->make->th('Cost');
                        $this->make->th('Cost (%)');
                        $this->make->th('Margin');
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();
                    $tot_qty = 0;
                    $tot_vat_sales = 0;
                    $tot_vat = 0;
                    $tot_gross = 0;
                    $tot_sales_prcnt = 0;
                    $tot_cost = 0;
                    $tot_margin = 0;
                    $tot_cost_prcnt = 0;
                    foreach ($trans as $v) {
                        $tot_gross += $v->gross;
                        $tot_cost += $v->cost;
                    }
                    foreach ($trans as $res) {
                        $this->make->sRow();
                            $this->make->td($res->menu_cat_name);
                            $this->make->td(num($res->qty), array("style"=>"text-align:right"));                                                    
                            $this->make->td(num($res->gross), array("style"=>"text-align:right"));                            
                            $this->make->td(num($res->gross / $tot_gross * 100). "%", array("style"=>"text-align:right"));                            
                            $this->make->td(num($res->cost), array("style"=>"text-align:right"));                            
                            if($tot_cost != 0){
                                $this->make->td(num($res->cost / $tot_cost * 100). "%", array("style"=>"text-align:right"));
                            }else{
                                $this->make->td("0.00%", array("style"=>"text-align:right"));                                
                            }
                            $this->make->td(num($res->gross - $res->cost), array("style"=>"text-align:right"));                         
                        $this->make->eRow();

                        $tot_qty += $res->qty;
                        $tot_vat_sales += $res->vat_sales;
                        $tot_vat += $res->vat;
                        $tot_sales_prcnt = 0;
                        $tot_margin += $res->gross - $res->cost;
                        $tot_cost_prcnt = 0;

                        $counter++;
                        $progress = ($counter / $trans_count) * 100;
                        update_load(num($progress));

                    }    
                    $this->make->sRow();
                        $this->make->th('Grand Total');
                        $this->make->th(num($tot_qty), array("style"=>"text-align:right"));
                        $this->make->th(num($tot_gross), array("style"=>"text-align:right"));
                        $this->make->th("", array("style"=>"text-align:right"));
                        $this->make->th(num($tot_cost), array("style"=>"text-align:right"));
                        $this->make->th("", array("style"=>"text-align:right"));
                        $this->make->th(num($tot_margin), array("style"=>"text-align:right"));                     
                    $this->make->eRow();                                 
                $this->make->eTableBody();
            $this->make->eTable();
        $this->make->eDiv();
        $this->make->append('<center><h4>Retail Items</h4></center>');
        $this->make->sDiv();
            $this->make->sTable(array("id"=>"main-tbl", 'class'=>'table reportTBL sortable'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('Category');
                        $this->make->th('Qty');
                        $this->make->th('Gross');
                        $this->make->th('Sales (%)');
                        $this->make->th('Cost');
                        $this->make->th('Cost (%)');
                        $this->make->th('Margin');
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();
                    $tot_qty = 0;
                    $tot_vat_sales = 0;
                    $tot_vat = 0;
                    $tot_gross = 0;
                    $tot_sales_prcnt = 0;
                    $tot_cost = 0;
                    $tot_margin = 0;
                    $tot_cost_prcnt = 0;
                    foreach ($trans_ret as $v) {
                        $tot_gross += $v->gross;
                    }
                    foreach ($trans_ret as $res) {
                        $this->make->sRow();
                            $this->make->td($res->name);
                            $this->make->td(num($res->qty), array("style"=>"text-align:right"));                          
                            $this->make->td(num($res->gross), array("style"=>"text-align:right"));                            
                            $this->make->td(num($res->gross / $tot_gross * 100). "%", array("style"=>"text-align:right"));                          
                            $this->make->td(num(0), array("style"=>"text-align:right"));                            
                            if($tot_cost != 0){
                                $this->make->td(num($res->cost / $tot_cost * 100). "%", array("style"=>"text-align:right"));
                            }else{
                                $this->make->td("0.00%", array("style"=>"text-align:right"));                                
                            }
                            $this->make->td(num($res->gross - 0), array("style"=>"text-align:right"));                                                  
                        $this->make->eRow();

                        $tot_qty += $res->qty;
                        $tot_vat_sales += $res->vat_sales;
                        $tot_vat += $res->vat;
                        $tot_sales_prcnt = 0;
                        $tot_margin += $res->gross - 0;
                        $tot_cost_prcnt = 0;

                        $counter++;
                        $progress = ($counter / $trans_count_ret) * 100;
                        update_load(num($progress));

                    }    
                    $this->make->sRow();
                        $this->make->th('Grand Total');
                        $this->make->th(num($tot_qty), array("style"=>"text-align:right"));
                        $this->make->th(num($tot_gross), array("style"=>"text-align:right"));
                        $this->make->th("", array("style"=>"text-align:right"));
                        $this->make->th(num($tot_cost), array("style"=>"text-align:right"));
                        $this->make->th("", array("style"=>"text-align:right"));                                                
                        $this->make->th(num($tot_margin), array("style"=>"text-align:right"));                     
                    $this->make->eRow();                                 
                $this->make->eTableBody();
            $this->make->eTable();
        $this->make->eDiv();
        update_load(100);
        $code = $this->make->code();
        $json['code'] = $code;        
        $json['tbl_vals'] = $trans;
        $json['dates'] = $this->input->post('calendar_range');
        echo json_encode($json);
    }
    public function voided_sales_res_gen()
    {
        $this->load->model('dine/setup_model');
        
        $this->load->model("dine/menu_model");
        
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];
        start_load(0);
        $menu_cat_id = $this->input->post("menu_cat_id");        
        $daterange = $this->input->post("calendar_range");        
        $dates = explode(" to ",$daterange);
        
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        $trans = $this->menu_model->get_voided_sales_res($from, $to); 
        // echo "<pre>",print_r($trans),"</pre>";die();  
        
        $trans_count = count($trans);
        $counter = 0;

        $this->make->sDiv();
            $this->make->sTable(array("id"=>"main-tbl", 'class'=>'table reportTBL sortable'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('Ref #');
                        $this->make->th('Transaction #');
                        $this->make->th('Name');
                        $this->make->th('Total Amount');
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();
                    foreach ($trans as $res) {
                        // echo "<pre>",print_r($res),"</pre>";die();
                        $this->make->sRow();
                            $this->make->td($res->ref_no);
                            $this->make->td($res->trans_ref);
                            $this->make->td(ucwords($res->fname." ".$res->mname." ".$res->lname));
                            $this->make->td(num($res->total_amount), array("style"=>"text-align:right"));
                        $this->make->eRow();
                        $counter++;
                        $progress = ($counter / $trans_count) * 100;
                        update_load(num($progress));

                    }                                   
                $this->make->eTableBody();
            $this->make->eTable();
        $this->make->eDiv();
        update_load(100);
        $code = $this->make->code();
        $json['code'] = $code;        
        $json['tbl_vals'] = $trans;
        $json['dates'] = $this->input->post('calendar_range');
        echo json_encode($json);
    }

    public function voided_sales_rep_pdf()
    {
        // Include the main TCPDF library (search for installation path).
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Sales Report');
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

        // ---------------------------------------------------------
        $this->load->model('dine/setup_model');
        // $this->load->database('main', TRUE);
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
        
        $menu_cat_id = $_GET['menu_cat_id'];        
        $daterange = $_GET['calendar_range'];        
        $dates = explode(" to ",$daterange);

        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        $trans = $this->menu_model->get_voided_cat_sales_rep($from, $to, $menu_cat_id);
        $trans_ret = $this->menu_model->get_voided_cat_sales_rep_retail($from, $to, "");        

        $pdf->Write(0, 'Voided Sales Report', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Write(0, 'Report Period:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, $daterange, '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Transaction Time:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln();              

        // -----------------------------------------------------------------------------
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(75, 0, 'Category', 'B', 0, 'L');        
        $pdf->Cell(32, 0, 'Qty', 'B', 0, 'R');            
        $pdf->Cell(32, 0, 'Gross', 'B', 0, 'R');        
        $pdf->Cell(32, 0, 'Sales (%)', 'B', 0, 'R');        
        $pdf->Cell(32, 0, 'Cost', 'B', 0, 'R');        
        $pdf->Cell(32, 0, 'Cost (%)', 'B', 0, 'R');        
        $pdf->Cell(32, 0, 'Margin', 'B', 0, 'R');        
        $pdf->ln();                  

        // GRAND TOTAL VARIABLES
        $tot_qty = 0;
        $tot_gross = 0;
        $tot_sales_prcnt = 0;
        $tot_cost = 0;
        $tot_cost_prcnt = 0; 
        $tot_margin = 0;
        $counter = 0;
        $progress = 0;
        $trans_count = count($trans);

        foreach ($trans as $val) {
            $tot_gross += $val->gross;
            $tot_cost += $val->cost;
        }

        foreach ($trans as $k => $v) {
            $pdf->Cell(75, 0, $v->menu_cat_name, '', 0, 'L');        
            $pdf->Cell(32, 0, num($v->qty), '', 0, 'R');          
            $pdf->Cell(32, 0, num($v->gross), '', 0, 'R');        
            $pdf->Cell(32, 0, num($v->gross / $tot_gross * 100)."%", '', 0, 'R');        
            $pdf->Cell(32, 0, num($v->cost), '', 0, 'R');                    
            if($tot_cost != 0){
                $pdf->Cell(32, 0, num($v->cost / $tot_cost * 100)."%", '', 0, 'R');                        
            }else{
                $pdf->Cell(32, 0, "0.00%", '', 0, 'R');                                        
            }
            $pdf->Cell(32, 0, num($v->gross - $v->cost), '', 0, 'R');        
            $pdf->ln();                

            $tot_qty += $v->qty;
            $tot_sales_prcnt = 0;
            $tot_margin += $v->gross - $v->cost;
            $tot_cost_prcnt = 0;

            $counter++;
            $progress = ($counter / $trans_count) * 100;
            update_load(num($progress));              
        }

        update_load(100);

        $pdf->Cell(75, 0, "Grand Total", 'T', 0, 'L');        
        $pdf->Cell(32, 0, num($tot_qty), 'T', 0, 'R');           
        $pdf->Cell(32, 0, num($tot_gross), 'T', 0, 'R');        
        $pdf->Cell(32, 0, "", 'T', 0, 'R');        
        $pdf->Cell(32, 0, num($tot_cost), 'T', 0, 'R');        
        $pdf->Cell(32, 0, "", 'T', 0, 'R'); 
        $pdf->Cell(32, 0, num($tot_margin), 'T', 0, 'R'); 

        //retail
        $tot_gross_ret = 0;
        if(count($trans_ret) > 0){
            $pdf->ln(7);                
            $pdf->Cell(267, 0, 'Retail Items', '', 0, 'C');
            $pdf->ln(); 

            $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
            $pdf->Cell(75, 0, 'Category', 'B', 0, 'L');        
            $pdf->Cell(32, 0, 'Qty', 'B', 0, 'R');        
            $pdf->Cell(32, 0, 'Gross', 'B', 0, 'R');        
            $pdf->Cell(32, 0, 'Sales (%)', 'B', 0, 'R');        
            $pdf->Cell(32, 0, 'Cost', 'B', 0, 'R');        
            $pdf->Cell(32, 0, 'Cost (%)', 'B', 0, 'R');        
            $pdf->Cell(32, 0, 'Margin', 'B', 0, 'R');        
            $pdf->ln();               

            $tot_qty = 0;
            $tot_sales_prcnt = 0;
            $tot_cost = 0;
            $tot_cost_prcnt = 0; 
            $tot_margin = 0;

            foreach ($trans_ret as $val) {
                $tot_gross_ret += $val->gross;
            }
            foreach ($trans_ret as $k => $v) {
                $pdf->Cell(75, 0, $v->name, '', 0, 'L');        
                $pdf->Cell(32, 0, num($v->qty), '', 0, 'R');             
                $pdf->Cell(32, 0, num($v->gross), '', 0, 'R');        
                $pdf->Cell(32, 0, num($v->gross / $tot_gross_ret * 100)."%", '', 0, 'R');        
                $pdf->Cell(32, 0, num(0), '', 0, 'R');                    
                if($tot_cost != 0){
                    $pdf->Cell(32, 0, num($v->cost / $tot_cost * 100)."%", '', 0, 'R');                        
                }else{
                    $pdf->Cell(32, 0, "0.00%", '', 0, 'R');                                        
                }
                $pdf->Cell(32, 0, num($v->gross - 0), '', 0, 'R');        
                $pdf->ln();                

                $tot_qty += $v->qty;
                $tot_sales_prcnt = 0;
                $tot_margin += $v->gross - 0;
                $tot_cost_prcnt = 0;            
            }

            update_load(100);        
            $pdf->Cell(75, 0, "Grand Total", 'T', 0, 'L');        
            $pdf->Cell(32, 0, num($tot_qty), 'T', 0, 'R');            
            $pdf->Cell(32, 0, num($tot_gross_ret), 'T', 0, 'R');        
            $pdf->Cell(32, 0, "", 'T', 0, 'R');        
            $pdf->Cell(32, 0, num($tot_cost), 'T', 0, 'R');        
            $pdf->Cell(32, 0, "", 'T', 0, 'R'); 
            $pdf->Cell(32, 0, num($tot_margin), 'T', 0, 'R');
        }       

        //Close and output PDF document
        $pdf->Output('voided_sales_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+   
    }
    public function voided_sales_res_pdf()
    {
        // Include the main TCPDF library (search for installation path).
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Voided Reservation Report');
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

        // ---------------------------------------------------------
        $this->load->model('dine/setup_model');
        // $this->load->database('main', TRUE);
        // $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
        
        // $menu_cat_id = $_GET['menu_cat_id'];        
        $daterange = $_GET['calendar_range'];        
        $dates = explode(" to ",$daterange);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        // echo $from.' '.$to;die();
        $trans = $this->menu_model->get_voided_sales_res($from, $to);
        // echo "<pre>",print_r($trans),"</pre>";die();
        // $trans_ret = $this->menu_model->get_voided_cat_sales_res_retail($from, $to, "");        

        $pdf->Write(0, 'Voided Reservation Report', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Write(0, 'Report Period:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, $daterange, '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Transaction Time:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln();              

        // -----------------------------------------------------------------------------
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(50, 0, 'Ref #', 'B', 0, 'L');        
        $pdf->Cell(50, 0, 'Transaction  #', 'B', 0, 'R');            
        $pdf->Cell(50, 0, 'Name', 'B', 0, 'R');        
        $pdf->Cell(50, 0, 'Total Amount', 'B', 0, 'R');       
        $pdf->ln();                  

        // GRAND TOTAL VARIABLES
        // $tot_qty = 0;
        // $tot_gross = 0;
        // $tot_sales_prcnt = 0;
        // $tot_cost = 0;
        // $tot_cost_prcnt = 0; 
        // $tot_margin = 0;
        $counter = 0;
        // $progress = 0;
        $trans_count = count($trans);

        foreach ($trans as $k => $v) {
            $pdf->Cell(50, 0, $v->ref_no, '', 0, 'L');        
            $pdf->Cell(50, 0, num($v->trans_ref), '', 0, 'R');          
            $pdf->Cell(50, 0, ucwords($v->fname." ".$v->mname." ".$v->lname), '', 0, 'R');        
            $pdf->Cell(50, 0, num($v->total_amount), '', 0, 'R');

            $counter++;
            $progress = ($counter / $trans_count) * 100;
            update_load(num($progress));              
        }

        update_load(100);  

        //Close and output PDF document
        $pdf->Output('voided_sales_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+   
    }

    public function voided_sales_rep_excel()
    {
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        date_default_timezone_set('Asia/Manila');
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $filename = 'Voided Category Sales Report';
        $rc=1;
        #GET VALUES
        start_load(0);

        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];

        update_load(10);
        sleep(1);
        
        $menu_cat_id = $_GET['menu_cat_id'];        
        $daterange = $_GET['calendar_range'];        
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        $trans = $this->menu_model->get_voided_cat_sales_rep($from, $to, $menu_cat_id);
        $trans_ret = $this->menu_model->get_voided_cat_sales_rep_retail($from, $to, "");  

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
        
        $headers = array('Category', 'Qty','Gross','Sales (%)','Cost','Cost (%)', 'Margin');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);

        $sheet->mergeCells('A'.$rc.':H'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->branch_name);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->address);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Voided Sales Report');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Report Period: '.$daterange);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('E'.$rc)->setValue('Report Generated: '.(new \DateTime())->format('Y-m-d H:i:s'));
        $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Transaction Time:');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $user = $this->session->userdata('user');
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('F'.$rc)->setValue('Generated by:    '.$user["full_name"]);
        $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        $rc++;

        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }
        $rc++;                          

        // GRAND TOTAL VARIABLES
        $tot_qty = 0;
        $tot_gross = 0;
        $tot_sales_prcnt = 0;
        $tot_cost = 0;
        $tot_cost_prcnt = 0; 
        $tot_margin = 0;
        $counter = 0;
        $progress = 0;
        $trans_count = count($trans);
        foreach ($trans as $val) {
            $tot_gross += $val->gross;
            $tot_cost += $val->cost;
            $tot_margin += $val->gross - $val->cost;
        }
        foreach ($trans_mod as $vv) {
            $tot_mod_gross += $vv->mod_gross;
        }

        foreach ($trans as $k => $v) {
            $sheet->getCell('A'.$rc)->setValue($v->menu_cat_name);
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue($v->qty);
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            $sheet->getCell('C'.$rc)->setValue(num($v->gross));     
            $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
            $sheet->getCell('D'.$rc)->setValue(num($v->gross / $tot_gross * 100)."%");     
            $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
            $sheet->getCell('E'.$rc)->setValue(num($v->cost));                     
            $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
            if($tot_cost != 0){
                $sheet->getCell('F'.$rc)->setValue(num($v->cost / $tot_cost * 100)."%");     
            
            }else{
                $sheet->getCell('F'.$rc)->setValue('0.00%');                                     
            }
            $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
            $sheet->getCell('G'.$rc)->setValue(num($v->gross - $v->cost));     
            $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);       

            // Grand Total
            $tot_qty += $v->qty;
            $tot_sales_prcnt = 0;
            $tot_cost_prcnt = 0;

            $counter++;
            $progress = ($counter / $trans_count) * 100;
            update_load(num($progress));   
            $rc++;           
        }

        $sheet->getCell('A'.$rc)->setValue('Grand Total');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($tot_qty));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('C'.$rc)->setValue(num($tot_gross));     
        $sheet->getStyle('C'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('D'.$rc)->setValue("");     
        $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('E'.$rc)->setValue(num($tot_cost));     
        $sheet->getStyle('E'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('F'.$rc)->setValue("");     
        $sheet->getStyle('F'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('G'.$rc)->setValue(num($tot_margin));     
        $sheet->getStyle('G'.$rc)->applyFromArray($styleBoldRight);
        $rc++; 

        //retail
        $tot_gross_ret = 0;
        if(count($trans_ret) > 0){

            $rc++; 
            $col = 'A';
            $sheet->mergeCells('A'.$rc.':G'.$rc);
            $sheet->getCell('A'.$rc)->setValue('Retail Items');
            $sheet->getStyle('A'.$rc)->applyFromArray($styleCenter);
            $rc++;

            foreach ($headers as $txt) {
                $sheet->getCell($col.$rc)->setValue($txt);
                $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
                $col++;
            }
            $rc++;                          

            // GRAND TOTAL VARIABLES
            $tot_qty = 0;
            $tot_sales_prcnt = 0;
            $tot_cost = 0;
            $tot_cost_prcnt = 0; 
            $tot_margin = 0;
            foreach ($trans_ret as $val) {
                $tot_gross_ret += $val->gross; 
                $tot_margin += $val->gross - 0;
            }

            foreach ($trans_ret as $k => $v) {
                $sheet->getCell('A'.$rc)->setValue($v->name);
                $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('B'.$rc)->setValue($v->qty);
                $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('C'.$rc)->setValue(num($v->gross));     
                $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('D'.$rc)->setValue(num($v->gross / $tot_gross_ret * 100)."%");     
                $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
                $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('E'.$rc)->setValue(num(0));                                     
                if($tot_cost != 0){
                    $sheet->getCell('F'.$rc)->setValue(num($v->cost / $tot_cost * 100)."%");     
                }else{
                    $sheet->getCell('F'.$rc)->setValue('0.00%');                     
                }
                $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('G'.$rc)->setValue(num($v->gross - 0));     
                $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);       

                // Grand Total
                $tot_qty += $v->qty;
                $tot_sales_prcnt = 0;
                $tot_cost_prcnt = 0;  
                $rc++;           
            }

            $sheet->getCell('A'.$rc)->setValue('Grand Total');
            $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
            $sheet->getCell('B'.$rc)->setValue(num($tot_qty));
            $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);
            $sheet->getCell('C'.$rc)->setValue(num($tot_gross_ret));     
            $sheet->getStyle('C'.$rc)->applyFromArray($styleBoldRight);
            $sheet->getCell('D'.$rc)->setValue("");     
            $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
            $sheet->getCell('E'.$rc)->setValue(num($tot_cost));     
            $sheet->getStyle('E'.$rc)->applyFromArray($styleBoldRight);
            $sheet->getCell('F'.$rc)->setValue("");     
            $sheet->getStyle('F'.$rc)->applyFromArray($styleBoldRight);
            $sheet->getCell('G'.$rc)->setValue(num($tot_margin));     
            $sheet->getStyle('G'.$rc)->applyFromArray($styleBoldRight);
            $rc++; 
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

    public function voided_sales_res_excel()
    {
        // $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        date_default_timezone_set('Asia/Manila');
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $filename = 'Voided Reservation Report';
        $rc=1;
        #GET VALUES
        start_load(0);

        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];

        update_load(10);
        sleep(1);
        
        $menu_cat_id = $_GET['menu_cat_id'];        
        $daterange = $_GET['calendar_range'];        
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        $trans = $this->menu_model->get_voided_sales_res($from, $to);
        // $trans_ret = $this->menu_model->get_voided_cat_sales_res_retail($from, $to, "");  

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
        
        $headers = array('Ref #', 'Transaction #','Name','Total Amount');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        // $sheet->getColumnDimension('E')->setWidth(20);
        // $sheet->getColumnDimension('F')->setWidth(20);
        // $sheet->getColumnDimension('G')->setWidth(20);

        $sheet->mergeCells('A'.$rc.':H'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->branch_name);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->address);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Voided Reservation Report');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Report Period: '.$daterange);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('E'.$rc)->setValue('Report Generated: '.(new \DateTime())->format('Y-m-d H:i:s'));
        $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Transaction Time:');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $user = $this->session->userdata('user');
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('F'.$rc)->setValue('Generated by:    '.$user["full_name"]);
        $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        $rc++;

        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }
        $rc++;                          

        // GRAND TOTAL VARIABLES
        $tot_qty = 0;
        $tot_gross = 0;
        $tot_sales_prcnt = 0;
        $tot_cost = 0;
        $tot_cost_prcnt = 0; 
        $tot_margin = 0;
        $counter = 0;
        $progress = 0;
        $trans_count = count($trans);
        // foreach ($trans as $val) {
        //     $tot_gross += $val->gross;
        //     $tot_cost += $val->cost;
        //     $tot_margin += $val->gross - $val->cost;
        // }
        // foreach ($trans_mod as $vv) {
        //     $tot_mod_gross += $vv->mod_gross;
        // }

        foreach ($trans as $k => $v) {
            $sheet->getCell('A'.$rc)->setValue($v->ref_no);
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
            $sheet->getCell('B'.$rc)->setValue($v->trans_ref);
            $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
            $sheet->getCell('C'.$rc)->setValue(ucwords($v->fname." ".$v->mname." ".$v->lname));     
            $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
            $sheet->getCell('D'.$rc)->setValue(num($v->total_amount));     
            $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
            // $sheet->getCell('E'.$rc)->setValue(num($v->cost));                     
            // $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
            // if($tot_cost != 0){
            //     $sheet->getCell('F'.$rc)->setValue(num($v->cost / $tot_cost * 100)."%");     
            
            // }else{
            //     $sheet->getCell('F'.$rc)->setValue('0.00%');                                     
            // }
            // $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
            // $sheet->getCell('G'.$rc)->setValue(num($v->gross - $v->cost));     
            // $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);       

            // Grand Total
            // $tot_qty += $v->qty;
            $tot_sales_prcnt = 0;
            $tot_cost_prcnt = 0;

            $counter++;
            $progress = ($counter / $trans_count) * 100;
            update_load(num($progress));   
            $rc++;           
        }

        // $sheet->getCell('A'.$rc)->setValue('Grand Total');
        // $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        // $sheet->getCell('B'.$rc)->setValue(num($tot_qty));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('C'.$rc)->setValue(num($tot_gross));     
        // $sheet->getStyle('C'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('D'.$rc)->setValue("");     
        // $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('E'.$rc)->setValue(num($tot_cost));     
        // $sheet->getStyle('E'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('F'.$rc)->setValue("");     
        // $sheet->getStyle('F'.$rc)->applyFromArray($styleBoldRight);
        // $sheet->getCell('G'.$rc)->setValue(num($tot_margin));     
        // $sheet->getStyle('G'.$rc)->applyFromArray($styleBoldRight);
        // $rc++; 

        // //retail
        // $tot_gross_ret = 0;
        // if(count($trans_ret) > 0){

        //     $rc++; 
        //     $col = 'A';
        //     $sheet->mergeCells('A'.$rc.':G'.$rc);
        //     $sheet->getCell('A'.$rc)->setValue('Retail Items');
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleCenter);
        //     $rc++;

        //     foreach ($headers as $txt) {
        //         $sheet->getCell($col.$rc)->setValue($txt);
        //         $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
        //         $col++;
        //     }
        //     $rc++;                          

        //     // GRAND TOTAL VARIABLES
        //     $tot_qty = 0;
        //     $tot_sales_prcnt = 0;
        //     $tot_cost = 0;
        //     $tot_cost_prcnt = 0; 
        //     $tot_margin = 0;
        //     foreach ($trans_ret as $val) {
        //         $tot_gross_ret += $val->gross; 
        //         $tot_margin += $val->gross - 0;
        //     }

        //     foreach ($trans_ret as $k => $v) {
        //         $sheet->getCell('A'.$rc)->setValue($v->name);
        //         $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //         $sheet->getCell('B'.$rc)->setValue($v->qty);
        //         $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('C'.$rc)->setValue(num($v->gross));     
        //         $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('D'.$rc)->setValue(num($v->gross / $tot_gross_ret * 100)."%");     
        //         $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
        //         $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('E'.$rc)->setValue(num(0));                                     
        //         if($tot_cost != 0){
        //             $sheet->getCell('F'.$rc)->setValue(num($v->cost / $tot_cost * 100)."%");     
        //         }else{
        //             $sheet->getCell('F'.$rc)->setValue('0.00%');                     
        //         }
        //         $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('G'.$rc)->setValue(num($v->gross - 0));     
        //         $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);       

        //         // Grand Total
        //         $tot_qty += $v->qty;
        //         $tot_sales_prcnt = 0;
        //         $tot_cost_prcnt = 0;  
        //         $rc++;           
        //     }

        //     $sheet->getCell('A'.$rc)->setValue('Grand Total');
        //     $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        //     $sheet->getCell('B'.$rc)->setValue(num($tot_qty));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);
        //     $sheet->getCell('C'.$rc)->setValue(num($tot_gross_ret));     
        //     $sheet->getStyle('C'.$rc)->applyFromArray($styleBoldRight);
        //     $sheet->getCell('D'.$rc)->setValue("");     
        //     $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
        //     $sheet->getCell('E'.$rc)->setValue(num($tot_cost));     
        //     $sheet->getStyle('E'.$rc)->applyFromArray($styleBoldRight);
        //     $sheet->getCell('F'.$rc)->setValue("");     
        //     $sheet->getStyle('F'.$rc)->applyFromArray($styleBoldRight);
        //     $sheet->getCell('G'.$rc)->setValue(num($tot_margin));     
        //     $sheet->getStyle('G'.$rc)->applyFromArray($styleBoldRight);
        //     $rc++; 
        // }
        
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

    public function promo_rep(){
        $data = $this->syter->spawn('promo_rep');        
        $data['page_title'] = fa('fa-money')." Promo Report";
        $data['code'] = promoRep();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css','assets/global/plugins/select2/css/select2.min.css','assets/global/plugins/select2/css/select2-bootstrap.min.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js','assets/global/plugins/select2/js/select2.full.min.js','assets/pages/scripts/components-select2.min.js');
        $data['page_no_padding'] = false;
        $data['sideBarHide'] = false;
        $data['load_js'] = 'dine/reporting';
        $data['use_js'] = 'promoRepJS';
        $this->load->view('page',$data);
    }

    public function promo_rep_gen(){
        $this->load->model('dine/setup_model');
        // $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];
        start_load(0);

        $daterange = $this->input->post("calendar_range");        
        $dates = explode(" to ",$daterange);

        $this->menu_model->db = $this->load->database('main', TRUE);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        
        $setup = $this->setup_model->get_details(1);
        if(!empty($setup)){
            $set = $setup[0];            
            $store_open = $set->store_open;            
        }else{
            $store_open = "00:00:00";
        }
        start_load(0);     
    

        $trans = $this->menu_model->get_promo($from, $to);


        $trans_count = count($trans);
        $counter = 0;
        $total = 0;
        $total_amt = 0;

        $this->make->sDiv();
            $this->make->sTable(array("id"=>"main-tbl", 'class'=>'table reportTBL sortable'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('Name');
                        $this->make->th('Date'); 
                        $this->make->th('Remarks');                        
                        $this->make->th('Qty');
                        $this->make->th('Amount');                         
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();                
                    
                foreach ($trans as $res) {

                    $this->make->sRow();
                        $this->make->td($res->name);
                        $this->make->td($res->date);
                        $this->make->td($res->remarks);
                        $this->make->td($res->qty, array("style"=>"text-align:right"));
                        $this->make->td(numInt($res->amount), array("style"=>"text-align:right"));
                    $this->make->eRow();

                    $total += $res->qty;
                    $total_amt += $res->amount;
                   
                    $counter++;
                    $progress = ($counter / $trans_count) * 100;
                    update_load(num($progress));

                }    

                $this->make->sRow();
                    $this->make->th('Grand Total',array('colspan'=>'3'));
                                                                  
                    $this->make->th($total, array("style"=>"text-align:right"));
                    $this->make->th(numInt($total_amt), array("style"=>"text-align:right"));                        
                $this->make->eRow();                                 

                $this->make->eTableBody();
            $this->make->eTable();
        $this->make->eDiv();
        update_load(100);
        $code = $this->make->code();
        $json['code'] = $code;        
        $json['tbl_vals'] = $trans;
        $json['dates'] = $this->input->post('calendar_range');
        echo json_encode($json);
    }

    public function promo_rep_pdf()
    {
         // Include the main TCPDF library (search for installation path).
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Sales Report');
        $pdf->SetSubject('');
        $pdf->SetKeywords('');

        // set default header data

        // ---------------------------------------------------------
        // $this->load->database('main', TRUE);
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");

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

        $daterange = $_GET["calendar_range"];        
        $dates = explode(" to ",$daterange);

        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
          
        $trans = $this->menu_model->get_promo($from, $to);

        $trans_count = count($trans);
        $counter = 0;
        $total = 0;
        $total_amt = 0;

        $pdf->Write(0, 'Promo Report', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Write(0, 'Report Period:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, $from.' '.$to, '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Transaction Time:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln();              


        // -----------------------------------------------------------------------------
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        
        $pdf->cell(40, 0, 'Name', 'B', 0, 'L'); 
        $pdf->cell(23, 0, 'Date', 'B', 0, 'L'); 
        $pdf->cell(23, 0, 'Remarks', 'B', 0, 'L'); 
        $pdf->cell(23, 0, 'Qty', 'B', 0, 'R'); 
        $pdf->cell(23, 0, 'Amount', 'B', 0, 'R');          
   
        $pdf->ln();                  

        foreach ($trans as $res) {
            $total += $res->qty;
            $total_amt += $res->amount;

            $pdf->Cell(40, 0, $res->name, '', 0, 'L'); 
            $pdf->Cell(23, 0, $res->date, '', 0, 'L'); 
            $pdf->Cell(23, 0, $res->remarks, '', 0, 'L'); 
            $pdf->Cell(23, 0, $res->qty, '', 0, 'R');
            $pdf->Cell(23, 0, $res->amount, '', 0, 'R');

            $pdf->ln(); 
           
            $counter++;
            $progress = ($counter / $trans_count) * 100;
            update_load(num($progress));

        }    
        $pdf->ln();
       
        update_load(100);        
        $pdf->Cell(86, 0, "Grand Total", 'T', 0, 'L');                
        $pdf->Cell(23, 0, $total, 'T', 0, 'R');
        $pdf->Cell(23, 0, numInt($total_amt), 'T', 0, 'R');         

        //Close and output PDF document
        $pdf->Output('promo_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+   
    }

    public function promo_rep_excel()
    { 
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $filename = 'Promo Report';
        $rc=1;        

        start_load(0);

        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");

        $setup = $this->setup_model->get_details(1);
        if(isset($setup[0]))
        {
            $set = $setup[0];            
            $store_open = $set->store_open;
        }else{
            $store_open = "00:00:00";
        }

        #GET VALUES

        $daterange = $_GET["calendar_range"];        
        $dates = explode(" to ",$daterange);

        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);

        update_load(10);
        sleep(1);
           
        $trans = $this->menu_model->get_promo($from,$to); 

        $trans_count = count($trans);
        $counter = 0;
        $total = 0;
        $total_amt = 0;

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
        
        $headers = array('Name','Date','Remarks','Qty','Amount'); 
        
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);


        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->branch_name);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->address);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Promo Report');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $rc++;

        $sheet->mergeCells('A'.$rc.':B'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Report Period: '.$daterange);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->mergeCells('C'.$rc.':D'.$rc);
        $sheet->getCell('C'.$rc)->setValue('Report Generated: '.(new \DateTime())->format('Y-m-d H:i:s'));
        $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);
        $rc++;

        $sheet->mergeCells('A'.$rc.':B'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Transaction Time:');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $user = $this->session->userdata('user');
        $sheet->mergeCells('C'.$rc.':D'.$rc);
        $sheet->getCell('D'.$rc)->setValue('Generated by:    '.$user["full_name"]);
        $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
        $rc++;
        
        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }
        $rc++;          

        foreach ($trans as $res) {
            $sheet->getCell('A'.$rc)->setValue($res->name);
            $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);

            $sheet->getCell('B'.$rc)->setValue($res->date);
            $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);

            $sheet->getCell('C'.$rc)->setValue($res->remarks);
            $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);

            $sheet->getCell('D'.$rc)->setValue($res->qty);
            $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);

            $sheet->getCell('E'.$rc)->setValue($res->amount);
            $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);

            $total +=  $res->qty;
            $total_amt +=  $res->amount;
           
            $counter++;
            $progress = ($counter / $trans_count) * 100;
            update_load(num($progress));   

            $rc++;           
        }

        $sheet->mergeCells('A'.$rc.':C'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Grand Total');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('D'.$rc)->setValue($total);
        $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
        $sheet->getCell('E'.$rc)->setValue($total_amt);
        $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        
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
    public function monthly_sales_breakdown(){
        $data = $this->syter->spawn('menu_sales_rep');
        $data['page_title'] = fa('icon-book-open')." Monthly Sales Breakdown";
        $data['code'] = makeMonthlyBreakdown();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['page_no_padding'] = true;
        $data['sideBarHide'] = true;
        $data['load_js'] = 'dine/reporting';
        $data['use_js'] = 'salesmonthlybreakdownJs';
        $this->load->view('page',$data);
    }
    public function monthly_break_gen(){
       $this->load->model('dine/reports_model');         

        start_load(0);
        update_load(10);
        $year = $this->input->post('year');
        $this->make->sDiv();
            $this->make->sTable(array('class'=>'table reportTBL'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('MONTH(MM)');
                        $this->make->th('VATABLE SALES');
                        $this->make->th('VAT ZERO-RATED SALES');
                        $this->make->th('VAT EXEMPT');
                        $this->make->th('SUBJECT TO OTHER PERCENTAGE TAXES');
                        $this->make->th('GROSS SALES');
                        $this->make->th('OR END RANGE');
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();
                $details = $this->setup_model->get_branch_details();
                $open_time = $details[0]->store_open;
                $close_time = $details[0]->store_close;
                  update_load(30);
                $year_total_vat = $year_gross_sales = $year_zero_rated = $year_vatex = 0;

                  //                 for($x = 1; $x <= 12; $x++){   
                                   
                  //  $m = strtotime("01-".$x."-".$year);
                  //  echo $x."-01-".$year." $m adasda<br>";
                  //                   $dateM = date("F", $m);
                  // echo $x." : ". $dateM."<br>";
                  //                 }die();
                $get_data = $this->reports_model->get_monthly_breakdown($year);
                $month_data_arr = array();
                 update_load(50);
                // echo "<pre>",print_r($get_data),"</pre>";die();
                foreach($get_data as $g_d){
                    $month_data_arr[$g_d['month_']] = $g_d;
                }

                for($x = 1; $x <= 12; $x++){        
                    $xx = $x; 
                    if($x <10){
                        $xx = '0'.$x;
                    }           
                    $month_last_OR = "";
                    $month_total_vat = $month_gross_sales = $month_zero_rated = $month_vatex = 0;
                    $m = strtotime("01-".$xx."-".$year);
                    $dateM = date("F", $m);
                    $start_month = date($year.'-'.$x.'-1');
                    $end_month = date("Y-m-t", strtotime($start_month));
                    $post = $this->set_post(null,$start_month);
                    $trans = $this->trans_sales($post['args']);
                    $sales = $trans['sales'];
                    $month_total_vat = $month_zero_rated = $month_vatex = $month_gross_sales = 0;
                    $month_last_OR = "";
                    
                    if(isset($month_data_arr[$x])){
                        $month_total_vat = $month_data_arr[$x]['vatable_sales'] ;
                        $month_zero_rated = $month_data_arr[$x]['trans_zero_rated'] ;
                        $month_vatex = $month_data_arr[$x]['vat_exempt'] ;
                        $month_gross_sales = $month_data_arr[$x]['gross'] ;
                        $month_last_OR = $month_data_arr[$x]['trans_ref'] ;
                    }

                    $year_total_vat += $month_total_vat;
                    $year_zero_rated += $month_zero_rated;
                    $year_vatex += $month_vatex;
                    $year_gross_sales += $month_gross_sales;
                    // echo "<pre>",print_r($month_array),"</pre>";die();
                    $text_align = array('style'=>'text-align:right;');
                    $this->make->sRow();
                        $this->make->td($dateM);
                        $this->make->td(number_format($month_total_vat,2),$text_align);
                        $this->make->td(number_format($month_zero_rated,2),$text_align);
                        $this->make->td(number_format($month_vatex,2),$text_align);
                        $this->make->td("");
                        $this->make->td(number_format($month_gross_sales,2),$text_align);
                        $this->make->td($month_last_OR);
                    $this->make->eRow();

                    update_load(70);
                }
                    update_load(90);
                $this->make->sRow();
                    $this->make->th('Total');
                    $this->make->th(number_format($year_total_vat,2),$text_align);
                    $this->make->th(number_format($year_zero_rated,2),$text_align);
                    $this->make->th(number_format($year_vatex,2),$text_align);
                    $this->make->th(number_format(0,2),$text_align);
                    $this->make->th(number_format($year_gross_sales,2),$text_align);
                    $this->make->th('');
                $this->make->eRow();             
            $this->make->eTableBody();
        $this->make->eTable();
        


        $this->make->eDiv();
        update_load(100);
        $code = $this->make->code();
        $json['code'] = $code;
        // $json['tbl_vals'] = $menus;
        // $json['dates'] = $this->input->post('calendar_range');
        echo json_encode($json);
    }
    public function excel_monthly_sales_break(){
            //diretso excel na
            $this->load->model('dine/reports_model');         

            $this->load->library('Excel');
            $year = $this->input->get('year');
            start_load(0);
            // echo "<pre>",print_r($year),"</pre>";die();
            // $month_array = $this->session->userData('month_array');
            // $month_date = $this->session->userData('month_date');
            $sheet = $this->excel->getActiveSheet();
            $branch_details = $this->setup_model->get_branch_details();
            $branch = array();
            foreach ($branch_details as $bv) {
                $branch = array(
                    'id' => $bv->branch_id,
                    'res_id' => $bv->res_id,
                    'branch_code' => $bv->branch_code,
                    'name' => $bv->branch_name,
                    'branch_desc' => $bv->branch_desc,
                    'contact_no' => $bv->contact_no,
                    'delivery_no' => $bv->delivery_no,
                    'address' => $bv->address,
                    'base_location' => $bv->base_location,
                    'currency' => $bv->currency,
                    'inactive' => $bv->inactive,
                    'tin' => $bv->tin,
                    'machine_no' => $bv->machine_no,
                    'bir' => $bv->bir,
                    'permit_no' => $bv->permit_no,
                    'serial' => $bv->serial,
                    'accrdn' => $bv->accrdn,
                    'email' => $bv->email,
                    'website' => $bv->website,
                    'store_open' => $bv->store_open,
                    'store_close' => $bv->store_close,
                );
            }
            $sheet->mergeCells('A1:G1');
            $sheet->mergeCells('A2:G2');
            $sheet->mergeCells('A3:G3');
            $sheet->mergeCells('A4:G4');
            $sheet->mergeCells('A5:G5');
            $sheet->mergeCells('A6:G6');
            $sheet->mergeCells('A7:G7');
            $sheet->mergeCells('A8:G8');
            $sheet->mergeCells('A9:G9');
            $sheet->getCell('A1')->setValue($branch['name']);
            $sheet->getCell('A2')->setValue($branch['address']);
            $sheet->setCellValueExplicit('A3', 'TIN #'.$branch['tin'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('A4', 'ACCRDN #'.$branch['accrdn'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('A5', 'PERMIT #'.$branch['permit_no'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('A6', 'SN #'.$branch['serial'], PHPExcel_Cell_DataType::TYPE_STRING);
            // $sheet->getCell('A7')->setValue($branch['machine_no']);
            $sheet->setCellValueExplicit('A7', 'MIN #'.$branch['machine_no'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->getCell('A8')->setValue('Monthly Sales Breakdown');
            // $sheet->getCell('A9')->setValue($month_date['text']);
            $rn = 10;
            // $sheet->mergeCells('A10');
            $sheet->getCell('A'.$rn)->setValue('MONTH(MM)');
            // $sheet->mergeCells('B'.$rn);
            $sheet->getCell('B'.$rn)->setValue('VATABLE SALES');
            // $sheet->mergeCells('E'.$rn);
            // $sheet->getCell('C'.$rn)->setValue('Accumulating Sales');
            // $sheet->mergeCells('H10');
            $sheet->getCell('C'.$rn)->setValue('VAT ZERO-RATED SALES');
            // $sheet->mergeCells('I10');
            $sheet->getCell('D'.$rn)->setValue('VAT EXEMPT');
            // $sheet->mergeCells('J10');
            $sheet->getCell('E'.$rn)->setValue('SUBJECT TO OTHER PERCENTAGE TAXES');
            // $sheet->mergeCells('K10');
            $sheet->getCell('F'.$rn)->setValue('GROSS SALES');
            // $sheet->mergeCells('L10');
            $sheet->getCell('G'.$rn)->setValue('OR END RANGE');
            $sheet->getStyle("A".$rn.":G".$rn)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("A".$rn.":G".$rn)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $sheet->getStyle("A".$rn.":G".$rn)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle("A".$rn.":G".$rn)->getFill()->getStartColor()->setRGB('29bb04');
            $sheet->getStyle('A1:'.'G11')->getFont()->setBold(true);
            update_load(50);
            $rn = 11;
                $details = $this->setup_model->get_branch_details();
                $open_time = $details[0]->store_open;
                $close_time = $details[0]->store_close;
                  // update_load(30);
                $year_total_vat = $year_gross_sales = $year_zero_rated = $year_vatex = 0;

                $get_data = $this->reports_model->get_monthly_breakdown($year);
                $month_data_arr = array();
                update_load(70);
                // echo "<pre>",print_r($get_data),"</pre>";die();
                foreach($get_data as $g_d){
                    $month_data_arr[$g_d['month_']] = $g_d;
                }
                for($x = 1; $x <= 12; $x++){         
                    $xx = $x; 
                    if($x <10){
                        $xx = '0'.$x;
                    }               
                    $month_last_OR="";
                    $month_total_vat = $month_gross_sales = $month_zero_rated = $month_vatex = 0;
                    $m = strtotime("01-".$xx."-".$year);
                    $dateM = date("F", $m);
                    $start_month = date($year.'-'.$x.'-1');
                    $end_month = date("Y-m-t", strtotime($start_month));
                    $post = $this->set_post(null,$start_month);
                    $trans = $this->trans_sales($post['args']);
                    $month_total_vat = $month_zero_rated = $month_vatex = $month_gross_sales = 0;
                    $month_last_OR = "";
                    
                    if(isset($month_data_arr[$x])){
                        $month_total_vat = $month_data_arr[$x]['vatable_sales'] ;
                        $month_zero_rated = $month_data_arr[$x]['trans_zero_rated'] ;
                        $month_vatex = $month_data_arr[$x]['vat_exempt'] ;
                        $month_gross_sales = $month_data_arr[$x]['gross'] ;
                        $month_last_OR = $month_data_arr[$x]['trans_ref'] ;
                    }

                    // echo "<pre>",print_r($trans),"</pre>";die();

                    $sales = $trans['sales'];
                    // while (strtotime($start_month) <= strtotime($end_month)) {
                    //     $post = $this->set_post(null,$start_month);
                    //     $trans = $this->trans_sales($post['args']);
                    //     $sales = $trans['sales'];
                    //     $trans_menus = $this->menu_sales($sales['settled']['ids']);
                    //     $trans_charges = $this->charges_sales($sales['settled']['ids']);
                    //     $trans_discounts = $this->discounts_sales($sales['settled']['ids']);
                    //     $tax_disc = $trans_discounts['tax_disc_total'];
                    //     $no_tax_disc = $trans_discounts['no_tax_disc_total'];
                    //     $trans_local_tax = $this->local_tax_sales($sales['settled']['ids']);
                    //     $trans_tax = $this->tax_sales($sales['settled']['ids']);
                    //     $trans_no_tax = $this->no_tax_sales($sales['settled']['ids']);
                    //     $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids']);
                    //     $gross = $trans_menus['gross'];
                    //     $net = $trans['net'];
                    //     $void = $trans['void'];
                    //     $charges = $trans_charges['total'];
                    //     $discounts = $trans_discounts['total'];
                    //     $local_tax = $trans_local_tax['total'];
                    //     $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;
                    //     if($less_vat < 0)
                    //         $less_vat = 0;
                    //     $tax = $trans_tax['total'];
                    //     $no_tax = $trans_no_tax['total'];
                    //     $zero_rated = $trans_zero_rated['total'];
                    //     $no_tax -= $zero_rated;
                    //     $loc_txt = numInt(($local_tax));
                    //     $net_no_adds = $net-($charges+$local_tax);
                    //     $nontaxable = $no_tax - $no_tax_disc;
                    //     $taxable = ($gross - $less_vat - $nontaxable - $zero_rated - $discounts) / 1.12;
                    //     $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
                    //     $add_gt = $taxable+$nontaxable+$zero_rated;
                    //     $nsss = $taxable +  $nontaxable +  $zero_rated;
                    //     $net_sales = $gross - $discounts - $less_vat;
                    //     $vat_ = $taxable * .12;
                    //     if(!empty($trans['last_ref'])){
                    //         $month_last_OR = $trans['last_ref']->trans_ref;
                    //     }
                        
                    //     $pos_start = date2SqlDateTime($start_month." ".$open_time);
                    //     $oa = date('a',strtotime($open_time));
                    //     $ca = date('a',strtotime($close_time));
                    //     $pos_end = date2SqlDateTime($start_month." ".$close_time);
                    //     if($oa == $ca){
                    //         $pos_end = date('Y-m-d H:i:s',strtotime($pos_end . "+1 days"));
                    //     }

                    //     $gt = $this->old_grand_net_total($pos_start);

                    //     $types = $trans_discounts['types'];
                    //     $sndisc = 0;
                    //     $othdisc = 0;
                    //     $month_total_vat += $taxable;
                    //     $month_gross_sales += $gross;
                    //     $month_zero_rated +=$zero_rated;
                    //     $month_vatex +=$nontaxable;
                    //     $start_month = date("Y-m-d", strtotime("+1 day", strtotime($start_month)));
                    // }
                    $year_total_vat += $month_total_vat;
                    $year_zero_rated += $month_zero_rated;
                    $year_vatex += $month_vatex;
                    $year_gross_sales += $month_gross_sales;

                    // update_load(70); 
                    $sheet->getStyle("A".$rn.":G".$rn)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $sheet->getCell('A'.$rn)->setValue($dateM);
                    $sheet->getCell('B'.$rn)->setValue(number_format($month_total_vat,2));
                    $sheet->getCell('C'.$rn)->setValue(number_format($month_zero_rated,2));
                    $sheet->getCell('D'.$rn)->setValue(number_format($month_vatex,2));
                    $sheet->getCell('E'.$rn)->setValue("");
                    $sheet->getCell('F'.$rn)->setValue(number_format($month_gross_sales,2));
                    $sheet->getCell('G'.$rn)->setValue("'".$month_last_OR."'");

                    $rn++;

                }

                $sheet->getStyle('A23:'.'G23')->getFont()->setBold(true);
                $sheet->getStyle('A23:'.'G23')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getCell('A'.$rn)->setValue("TOTAL");
                $sheet->getCell('B'.$rn)->setValue(number_format($year_total_vat,2));
                $sheet->getCell('C'.$rn)->setValue(number_format($year_zero_rated,2));
                $sheet->getCell('D'.$rn)->setValue(number_format($year_vatex,2));
                $sheet->getCell('E'.$rn)->setValue("");
                $sheet->getCell('F'.$rn)->setValue(number_format($year_gross_sales,2));
                $sheet->getCell('G'.$rn)->setValue("");
                update_load(100);
            if (ob_get_contents()) 
                ob_end_clean();
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='."Monthly Sales Breakdown".'.xls');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            $objWriter->save('php://output');
    }
    // Summary of Invoice Sales
    public function summary_sales_invoiced_c(){
        $data = $this->syter->spawn('menu_sales_rep');
        $data['page_title'] = fa('icon-book-open')." Summary Of Invoice Sales (Credit Card)";
        $data['code'] = makeSummaryCredit();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['page_no_padding'] = true;
        $data['sideBarHide'] = true;
        $data['load_js'] = 'dine/reporting';
        $data['use_js'] = 'summaryCreditReportJs';
        $this->load->view('page',$data);
    }
    public function gen_invoice_credit_c(){
        // die("aaa");
        $this->load->model('dine/reports_model');

        start_load(0);
        update_load(10);
        $calendar_range = $this->input->post('year');
        $this->make->sDiv();
            $this->make->sTable(array('class'=>'table reportTBL'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('CUSTOMER/CREDIT CARD');
                        $this->make->th('INVOICE #');
                        $this->make->th('CASH SALES');
                        $this->make->th('FOOD(V)');
                        $this->make->th('FOOD (NV)');
                        $this->make->th('WSL (V)');
                        $this->make->th('WSL (NV)');
                        $this->make->th('SC');
                        $this->make->th('SC adj');
                        $this->make->th('VAT');
                        $this->make->th('VAT adj');
                        $this->make->th('DISC');
                        $this->make->th('TOTALS');
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();
                $details = $this->setup_model->get_branch_details();
                // echo "<pre>"
                $open_time = $details[0]->store_open;
                $close_time = $details[0]->store_close;
                  update_load(30);

                    // echo "<pre>",print_r($this->input->post()),"</pre>";die();
                    // echo $calendar_range;die();
                    $post = $this->input->post();//$this->set_post($calendar_range);
                    $curr = true;

                    // $this->cashier_model->db = $this->load->database('main', TRUE);
                    // echo "xxadads";die();

                    $results = $this->reports_model->get_trans_sales_via_credit_card($post);


                    // $trans = $this->trans_sales_cred($post['args']);
                        // echo "<pre>",print_r($this->input->post()),"</pre>";die();
                    // $sales = $results;//['sales'];
                     $grand_cash_sale = $grand_fl_vatable = $grand_fl_non_vatable  = $grand_wl_vatable = $grand_wl_non_vatable = $grand_vat  = $grand_sc = $grand_disc  = $grand_total_sales = 0;
                    foreach ($results as $sales) {
                        // $value = $v->id;               
                    
                        $sndisc = 0;
                        $othdisc = 0;
                    // echo "<pre> aa: ",print_r($sales),"</pre>";die();

                        // echo "<pre>",print_r($value),"</pre>";die();
                        update_load(70);
                        $total = $sales['fl_vatable'] + $sales['fl_non_vatable'] + $sales['wl_vatable'] + $sales['wl_non_vatable'] + $sales['service_charge'] + $sales['vat'] + $sales['trans_discount'];
                        $this->make->sRow();
                        $this->make->td($sales['card_number']);
                        $this->make->td($sales['trans_ref']);
                        $this->make->td("");
                        $this->make->td(number_format($sales['fl_vatable'],2));
                        $this->make->td(number_format($sales['fl_non_vatable'],2));
                        $this->make->td(number_format($sales['wl_vatable'],2));
                        $this->make->td(number_format($sales['wl_non_vatable'],2));                       
                         $this->make->td(number_format($sales['service_charge'],2));                       
                        $this->make->td("");                       
                        $this->make->td(number_format($sales['vat'],2));                       
                        $this->make->td("");   
                        $this->make->td(number_format($sales['trans_discount'],2));  
                        $this->make->td(number_format($total,2));   
                        $grand_cash_sale += 0;//$trans['all_orders'][$value]->total_paid;
                        $grand_fl_vatable += $sales['fl_vatable'];
                        $grand_fl_non_vatable += $sales['fl_non_vatable'];
                        $grand_wl_vatable += $sales['wl_vatable'];
                        $grand_wl_non_vatable += $sales['wl_non_vatable'];
                        $grand_vat += $sales['vat'];
                        $grand_sc += $sales['service_charge'];
                        $grand_disc += $sales['trans_discount'];
                         $grand_total_sales += $total;

                        $this->make->eRow();
                    }
                $this->make->sRow();
                    $this->make->th('TOTAL');
                    $this->make->th('');
                    $this->make->th(number_format($grand_cash_sale,2));
                    $this->make->th(number_format($grand_fl_vatable,2));
                    $this->make->th(number_format($grand_fl_non_vatable,2));
                   $this->make->th(number_format($grand_wl_vatable,2));
                    $this->make->th(number_format($grand_wl_non_vatable,2));
                    $this->make->th(number_format($grand_sc,2));
                    $this->make->th('');
                    $this->make->th(number_format($grand_vat,2));
                    $this->make->th('');
                    $this->make->th(number_format($grand_disc,2));
                    $this->make->th(number_format($grand_total_sales,2));
                $this->make->eRow();             
            $this->make->eTableBody();
        $this->make->eTable();
        


        $this->make->eDiv();
        update_load(100);
        $code = $this->make->code();
        $json['code'] = $code;
        // $json['tbl_vals'] = $menus;
        // $json['dates'] = $this->input->post('calendar_range');
        echo json_encode($json);
    }
    public function excel_invoice_credit_c(){
            //diretso excel na
            
            $this->load->model('dine/reports_model');
            $this->load->library('Excel');
            $year = $this->input->get('year');

            start_load(0);
            // echo "<pre>",print_r($year),"</pre>";die();
            // $month_array = $this->session->userData('month_array');
            // $month_date = $this->session->userData('month_date');
            $sheet = $this->excel->getActiveSheet();
            $branch_details = $this->setup_model->get_branch_details();
            $post = $this->input->get();//$this->set_post($calendar_range);   \
            $post['year'] = $post['calendar_range'];
            $results = $this->reports_model->get_trans_sales_via_credit_card($post);

            $file_name = "";
            // echo "<pre>",print_r($post),"</pre>";die();
                
            if(!empty($post['calendar_range'])){
                
                $file_name = str_replace(  "/", " ", str_replace( ":", "_",$post['calendar_range'] )) ;
            }
            // echo $file_name;die();
            $branch = array();
            foreach ($branch_details as $bv) {
                $branch = array(
                    'id' => $bv->branch_id,
                    'res_id' => $bv->res_id,
                    'branch_code' => $bv->branch_code,
                    'name' => $bv->branch_name,
                    'branch_desc' => $bv->branch_desc,
                    'contact_no' => $bv->contact_no,
                    'delivery_no' => $bv->delivery_no,
                    'address' => $bv->address,
                    'base_location' => $bv->base_location,
                    'currency' => $bv->currency,
                    'inactive' => $bv->inactive,
                    'tin' => $bv->tin,
                    'machine_no' => $bv->machine_no,
                    'bir' => $bv->bir,
                    'permit_no' => $bv->permit_no,
                    'serial' => $bv->serial,
                    'accrdn' => $bv->accrdn,
                    'email' => $bv->email,
                    'website' => $bv->website,
                    'store_open' => $bv->store_open,
                    'store_close' => $bv->store_close,
                );
            }
            $sheet->mergeCells('A1:M1');
            $sheet->mergeCells('A2:M2');
            $sheet->mergeCells('A3:M3');
            $sheet->mergeCells('A4:M4');
            $sheet->mergeCells('A5:M5');
            $sheet->mergeCells('A6:M6');
            $sheet->mergeCells('A7:M7');
            $sheet->mergeCells('A8:M8');
            $sheet->mergeCells('A9:M9');
            $sheet->getCell('A1')->setValue($branch['name']);
            $sheet->getCell('A2')->setValue($branch['address']);
            $sheet->setCellValueExplicit('A3', 'TIN #'.$branch['tin'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('A4', 'ACCRDN #'.$branch['accrdn'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('A5', 'PERMIT #'.$branch['permit_no'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('A6', 'SN #'.$branch['serial'], PHPExcel_Cell_DataType::TYPE_STRING);
            // $sheet->getCell('A7')->setValue($branch['machine_no']);
            $sheet->setCellValueExplicit('A7', 'MIN #'.$branch['machine_no'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->getCell('A8')->setValue('Summary Of Invoice Sales (Credit Card)');
            // $sheet->getCell('A9')->setValue($month_date['text']);
            $rn = 10;
            // $sheet->mergeCells('A10');
            $sheet->getCell('A'.$rn)->setValue('CUSTOMER/CREDIT CARD');
            $sheet->getCell('B'.$rn)->setValue('INVOICE #');
            $sheet->getCell('C'.$rn)->setValue('CASH SALES');
            $sheet->getCell('D'.$rn)->setValue('FOOD (V)');
            $sheet->getCell('E'.$rn)->setValue('FOOD (NV)');
            $sheet->getCell('F'.$rn)->setValue('WSL (V)');
            $sheet->getCell('G'.$rn)->setValue('WSL (NV)');
            $sheet->getCell('H'.$rn)->setValue('SC');
            $sheet->getCell('I'.$rn)->setValue('SC Adj');
            $sheet->getCell('J'.$rn)->setValue('VAT');
            $sheet->getCell('K'.$rn)->setValue('VAT Adj');
            $sheet->getCell('L'.$rn)->setValue('DISC');
            $sheet->getCell('M'.$rn)->setValue('TOTAL');     
            $sheet->getStyle("A".$rn.":M".$rn)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("A".$rn.":M".$rn)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $sheet->getStyle("A".$rn.":M".$rn)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $sheet->getStyle("A".$rn.":M".$rn)->getFill()->getStartColor()->setRGB('29bb04');
            $sheet->getStyle('A1:'.'M10')->getFont()->setBold(true);
            update_load(50);
            $rn = 11;
                $details = $this->setup_model->get_branch_details();
                $open_time = $details[0]->store_open;
                $close_time = $details[0]->store_close;


                    $post = $this->set_post($_GET['calendar_range']);
                    $curr = true;
                    // $trans = $this->trans_sales_cred($post['args']);
                    // $sales = $trans['sales'];
                   $grand_cash_sale = $grand_fl_vatable = $grand_fl_non_vatable  = $grand_wl_vatable = $grand_wl_non_vatable = $grand_vat  = $grand_sc = $grand_disc  = $grand_total_sales = 0;

                        // echo "<pre>",print_r($results),"</pre>";die();
                    foreach ($results as $sales) {
                        $sheet->getStyle("A".$rn.":M".$rn)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                        $total = $sales['fl_vatable'] + $sales['fl_non_vatable'] + $sales['wl_vatable'] + $sales['wl_non_vatable'] + $sales['service_charge'] + $sales['vat'] + $sales['trans_discount'];



                        // echo "<pre>",print_r($value),"</pre>";die();
                        $sheet->getCell('A'.$rn)->setValue("'".$sales['card_number']."'");
                        $sheet->getCell('B'.$rn)->setValue("'".$sales['trans_ref']."'");
                        $sheet->getCell('C'.$rn)->setValue("");

                        $sheet->getCell('D'.$rn)->setValue(number_format($sales['fl_vatable'],2));
                        $sheet->getCell('E'.$rn)->setValue(number_format($sales['fl_non_vatable'],2));
                        $sheet->getCell('F'.$rn)->setValue(number_format($sales['wl_vatable'],2));
                        $sheet->getCell('G'.$rn)->setValue(number_format($sales['wl_non_vatable'],2));
                        $sheet->getCell('H'.$rn)->setValue(number_format($sales['service_charge'],2));
                        $sheet->getCell('I'.$rn)->setValue("");
                        $sheet->getCell('J'.$rn)->setValue(number_format($sales['vat'],2));
                        $sheet->getCell('K'.$rn)->setValue("");
                        $sheet->getCell('L'.$rn)->setValue(number_format($sales['trans_discount'],2));
                        $sheet->getCell('M'.$rn)->setValue(number_format($total,2));
                        $grand_cash_sale += 0;//$trans['all_orders'][$value]->total_paid;
                        $grand_fl_vatable += $sales['fl_vatable'];
                        $grand_fl_non_vatable += $sales['fl_non_vatable'];
                        $grand_wl_vatable += $sales['wl_vatable'];
                        $grand_wl_non_vatable += $sales['wl_non_vatable'];
                        $grand_vat += $sales['vat'];
                        $grand_sc += $sales['service_charge'];
                        $grand_disc += $sales['trans_discount'];
                         $grand_total_sales += $total;

                        $rn++;
                    }

                update_load(70);
                 $sheet->getStyle("A".$rn.":M".$rn)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle("A".$rn.":M".$rn)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

                $sheet->getStyle('A'.$rn.':'.'M'.$rn)->getFont()->setBold(true);

                $sheet->getCell('A'.$rn)->setValue("TOTAL");
                $sheet->getCell('B'.$rn)->setValue("");
                $sheet->getCell('C'.$rn)->setValue(number_format($grand_cash_sale,2));
                $sheet->getCell('D'.$rn)->setValue(number_format($grand_fl_vatable,2));
                $sheet->getCell('E'.$rn)->setValue(number_format($grand_fl_non_vatable,2));
                $sheet->getCell('F'.$rn)->setValue(number_format($grand_wl_vatable,2));
                $sheet->getCell('G'.$rn)->setValue(number_format($grand_wl_non_vatable,2));
                $sheet->getCell('H'.$rn)->setValue(number_format($grand_sc,2));
                $sheet->getCell('I'.$rn)->setValue("");
                $sheet->getCell('J'.$rn)->setValue(number_format($grand_vat,2));
                $sheet->getCell('K'.$rn)->setValue("");
                $sheet->getCell('L'.$rn)->setValue(number_format($grand_disc,2));
                $sheet->getCell('M'.$rn)->setValue(number_format($grand_total_sales,2));


            if (ob_get_contents()) 
                ob_end_clean();
                update_load(100);
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='."summary_invoice_credit ".$file_name.'.xls');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            $objWriter->save('php://output');
    }

    public function summary_sales(){
            $this->load->model('dine/reports_model');
            $this->load->helper('dine/reports_helper');
            $data = $this->syter->spawn('monthly');
            $data['page_title'] = 'Summary of Sales';
            $data['code'] = summarySalesUi();
            $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
            $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
            $data['load_js'] = 'dine/reporting.php';
            $data['use_js'] = 'summaryJS';
            $this->load->view('page',$data);
        }
    public function get_summary_reports(){
        ini_set('memory_limit', '-1');
        set_time_limit(3600);
        sess_clear('summary_array');
        sess_clear('month_date');
        $this->load->helper('dine/reports_helper');
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $json = $this->input->post('json');
        $start_month = date($year.'-'.$month.'-01');
        $end_month = date("Y-m-t", strtotime($start_month));
        $month_date = array('text'=>sql2Date($start_month).' to '.sql2Date($end_month),'month_year'=>$start_month);
        update_load(10);
        // sleep(1);
        $load = 10;

        $details = $this->setup_model->get_branch_details();
            
        $open_time = $details[0]->store_open;
        $close_time = $details[0]->store_close;

                // echo $cmonth.'<br>';
        $month_array = array();
        while (strtotime($start_month) <= strtotime($end_month)) {
            // echo "$start_month\n";
            $post = $this->set_post(null,$start_month);
            $trans = $this->trans_sales($post['args']);
            $sales = $trans['sales'];
            $trans_menus = $this->menu_sales($sales['settled']['ids']);
            $trans_charges = $this->charges_sales($sales['settled']['ids']);
            $trans_discounts = $this->discounts_sales($sales['settled']['ids']);
            $tax_disc = $trans_discounts['tax_disc_total'];
            $no_tax_disc = round($trans_discounts['no_tax_disc_total'],2);
            $trans_local_tax = $this->local_tax_sales($sales['settled']['ids']);
            $trans_tax = $this->tax_sales($sales['settled']['ids']);
            $trans_no_tax = $this->no_tax_sales($sales['settled']['ids']);
            $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids']);
            // $payments = $this->payment_sales($sales['settled']['ids']);

            // $wine_liq = $this->item_sales_cat($sales['settled']['ids'],6);

            // $gross = $trans_menus['gross']-$wine_liq['gross']-$wine_liq['nv_amount'];
            $gross = $trans_menus['gross'];
            
            // $net = $trans['net']-$wine_liq['gross']-$wine_liq['nv_gross'];
            $net = $trans['net'];
            $void = $trans['void'];
            $charges = $trans_charges['total'];
            // $discounts = $trans_discounts['total'] - $wine_liq['discount'] - $wine_liq['nv_discount'];
            $discounts = $trans_discounts['total'];
            $local_tax = $trans_local_tax['total'];
            $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;
            if($less_vat < 0)
                $less_vat = 0;
            $tax = $trans_tax['total'];
            $no_tax = round($trans_no_tax['total'],2);
            $zero_rated = $trans_zero_rated['total'];
            $no_tax -= $zero_rated;
            $loc_txt = numInt(($local_tax));
            $net_no_adds = $net-($charges+$local_tax);
            // $nontaxable = $no_tax;
            // $nontaxable = $no_tax - $no_tax_disc - $wine_liq['nv_gross'];
            $nontaxable = $no_tax - $no_tax_disc;
            // $taxable =   ($gross - $discounts - $less_vat - $nontaxable) / 1.12;
            $taxable = ($gross - $less_vat - $nontaxable - $zero_rated - $discounts) / 1.12;
            $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
            $add_gt = $taxable+$nontaxable+$zero_rated;
            $nsss = $taxable +  $nontaxable +  $zero_rated;

            // $nontaxable -= $wine_liq['nv_discount'];
            // $net_sales = $gross + $charges - $discounts - $less_vat;
            //pinapaalis ni sir yun charges sa net sales kagaya nun nasa zread 8 20 2018
           
            // $final_gross = $gross;
            $vat_ = $taxable * .12;            
            
            $pos_start = date2SqlDateTime($start_month." ".$open_time);
            $oa = date('a',strtotime($open_time));
            $ca = date('a',strtotime($close_time));
            $pos_end = date2SqlDateTime($start_month." ".$close_time);
            if($oa == $ca){
                $pos_end = date('Y-m-d H:i:s',strtotime($pos_end . "+1 days"));
            }

            $types = $trans_discounts['types'];
            // $qty = 0;
            $sndisc = 0;
            $pwdisc = 0;
            $othdisc = 0;
            $empdisc =0;
            foreach ($types as $code => $val) {
                $amount = $val['amount'];
                if($code == 'PWDISC'){
                    // $amount = $val['amount'] / 1.12;
                    // $pwdisc = $val['amount'] - $wine_liq['discount'] - $wine_liq['nv_discount'];
                    $pwdisc = $val['amount'];
                }elseif($code == 'SNDISC'){
                    // $sndisc = $val['amount'] - $wine_liq['discount'] - $wine_liq['nv_discount'];
                    $sndisc = $val['amount'];
                }elseif($code == 'EMPDS'){
                    // $empdisc = $val['amount'] - $wine_liq['discount'] - $wine_liq['nv_discount'];
                    $empdisc = $val['amount'];
                }else{
                    // $othdisc += $val['amount'] - $wine_liq['discount'] - $wine_liq['nv_discount'];
                    $othdisc += $val['amount'];
                }
                // $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",PAPER_TOTAL_COL_1," ")
                //                      .append_chars('-'.Num($amount,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                // $qty += $val['qty'];
            }

            // $gross_sales = $taxable+$nontaxable-$pwdisc-$sndisc;
            $gross_sales = $gross + $charges - $less_vat;
            // $net_sales = $gross_sales + $charges + $vat_ - $empdisc - $othdisc;
            $net_sales = $gross - $discounts - $less_vat;
            
            // echo $pwdisc; die();
            $month_array[$start_month] = array(            
                'vatsales'=>$taxable,
                'vatex'=>$nontaxable,
                'zero_rated'=>$zero_rated,
                'vat'=>$vat_,
                'net_sales'=>$net_sales,
                'pwdisc'=>$pwdisc,
                'sndisc'=>$sndisc,
                'empdisc'=>$empdisc,
                'othdisc'=>$othdisc,
                'lessvat'=>$less_vat,
                'gross'=>$gross_sales,
                'charges'=>$charges,
                // 'senior'=>
            );
            $load += 2;
            update_load($load);
            // sleep(1);
            $start_month = date("Y-m-d", strtotime("+1 day", strtotime($start_month)));
        }
        // var_dump($month_array);
        // die();
        // $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
        // $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
        update_load(75);
        // sleep(1);

        $this->session->set_userdata('days',cal_days_in_month(CAL_GREGORIAN, (int)$month, $year));
        $this->session->set_userdata('summary_array',$month_array);
        $this->session->set_userdata('month_date',$month_date);
        // //diretso excel na
        // $this->load->library('Excel');
        // $sheet = $this->excel->getActiveSheet();
        // $sheet->getCell('A1')->setValue('Point One Integrated Solutions Inc.');
        update_load(100);
        // ob_end_clean();
        // header('Content-type: application/vnd.ms-excel');
        // header('Content-Disposition: attachment;filename=monthly_sales.xls"');
        // header('Cache-Control: max-age=0');
        // $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        // $objWriter->save('php://output');
        
    }

    public function summary_sales_gen()
    {
        $month_array = $this->session->userData('summary_array');
        $days = $this->session->userData('days');

        $this->make->sDiv();
            $this->make->sTable(array("id"=>"main-tbl", 'class'=>'table reportTBL sortable'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('');
                        $this->make->th('Food Stuff');
                        $this->make->th('Food Stuff');
                        $this->make->th('Total',array('rowspan'=>'2'));
                        $this->make->th('Senior Discount',array('rowspan'=>'2'));
                        $this->make->th('PWD Discount',array('rowspan'=>'2'));
                        $this->make->th('Gross Sales',array('rowspan'=>'2'));
                        $this->make->th('Charges',array('rowspan'=>'2'));
                        $this->make->th('Output VAT',array('rowspan'=>'2'));
                        $this->make->th('EMP Discount',array('rowspan'=>'2'));
                        $this->make->th("Customer's Discount",array('rowspan'=>'2'));
                        $this->make->th('Tip',array('rowspan'=>'2'));
                        $this->make->th('Net Sales',array('rowspan'=>'2'));
                    $this->make->eRow();

                     $this->make->sRow();
                        $this->make->th('');
                        $this->make->th('(Vatable)');
                        $this->make->th('(Non Vatable)');
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();

                    $vatsales_total = 0;
                    $vatex_total = 0;
                    $vat_total = 0;
                    $sndisc_total = 0;
                    $pwdisc_total = 0;
                    $lessvat_total = 0;
                    $empdisc_total = 0;
                    $othdisc_total = 0;
                    $net_sales_total = 0;
                    $total_charges = 0;
                    $gross_total = 0;
                    
                    foreach ($month_array as $date=>$vals) {                      

                        $this->make->sRow();
                            $this->make->td(date('d',strtotime($date)));
                            $this->make->td(num($vals['vatsales']), array("style"=>"text-align:right"));
                            $this->make->td(num($vals['vatex'] + $vals['zero_rated']), array("style"=>"text-align:right"));
                            $this->make->td(num($vals['vatsales'] + $vals['vatex'] + $vals['zero_rated']), array("style"=>"text-align:right"));
                            $this->make->td('('.num($vals['sndisc']) .')', array("style"=>"text-align:right"));
                            $this->make->td('('.num($vals['pwdisc']) .')', array("style"=>"text-align:right"));
                            $this->make->td(num($vals['gross']), array("style"=>"text-align:right"));
                            $this->make->td(num($vals['charges']), array("style"=>"text-align:right"));
                            $this->make->td(num($vals['vat']), array("style"=>"text-align:right"));
                            $this->make->td('('.num($vals['empdisc']) .')', array("style"=>"text-align:right"));
                            $this->make->td('('.num($vals['othdisc']) .')', array("style"=>"text-align:right"));
                            $this->make->td(num(0), array("style"=>"text-align:right"));
                            $this->make->td(num($vals['net_sales']), array("style"=>"text-align:right"));
                        $this->make->eRow();

                        $vatsales_total += $vals['vatsales'];
                        $vatex_total += $vals['vatex'] + $vals['zero_rated'];
                        $vat_total += $vals['vat'];
                        $sndisc_total += $vals['sndisc'];
                        $pwdisc_total += $vals['pwdisc'];
                        $lessvat_total += $vals['lessvat'];
                        $empdisc_total += $vals['empdisc'];
                        $othdisc_total += $vals['othdisc'];
                        $net_sales_total += $vals['net_sales'];
                        $total_charges += $vals['charges'];
                        $gross_total += $vals['gross'];

                    }    
                    $this->make->sRow();
                        $this->make->th('Total');
                        $this->make->th(num($vatsales_total), array("style"=>"text-align:right"));
                        $this->make->th(num($vatex_total), array("style"=>"text-align:right"));
                        $this->make->th(num($vatsales_total + $vatex_total), array("style"=>"text-align:right"));
                        $this->make->th('('.num($sndisc_total).')', array("style"=>"text-align:right"));
                        $this->make->th('('.num($pwdisc_total).')', array("style"=>"text-align:right"));
                        $this->make->th(num($gross_total), array("style"=>"text-align:right"));
                        $this->make->th(num($total_charges), array("style"=>"text-align:right"));      
                        $this->make->th(num($vat_total), array("style"=>"text-align:right"));
                        $this->make->th('('.num($empdisc_total).')', array("style"=>"text-align:right"));
                        $this->make->th('('.num($othdisc_total).')', array("style"=>"text-align:right"));
                         $this->make->th(num(0), array("style"=>"text-align:right"));
                        $this->make->th(num($net_sales_total), array("style"=>"text-align:right"));
                    $this->make->eRow(); 

                    $this->make->sRow();
                        $this->make->td('',array('colspan'=>'13'));
                    $this->make->sRow();

                    $rent_perc = $gross_total * 0.18;
                    $rent_vat = $rent_perc * 0.12;
                    $rent_ewt = $rent_perc * 0.05;

                    // $this->make->sRow();
                    //     $this->make->td('Average Sales per Day',array('colspan'=>'2'));
                    //     $this->make->td(num(($vatsales_total + $vatex_total)/$days), array("style"=>"text-align:right"));
                    // $this->make->sRow();
                    // $this->make->sRow();
                    //     $this->make->td('Gross Sales',array('colspan'=>'2'));
                    //     $this->make->td(num($gross_total), array("style"=>"text-align:right"));
                    // $this->make->sRow();

                    // $this->make->sRow();
                    //     $this->make->td('',array('colspan'=>'3'));
                    // $this->make->sRow();

                    // $this->make->sRow();
                    //     $this->make->td('Percentage Rental - 18%',array('colspan'=>'2'));
                    //     $this->make->td(num($rent_perc), array("style"=>"text-align:right"));
                    // $this->make->sRow();
                    // $this->make->sRow();
                    //     $this->make->td('VAT - 12%',array('colspan'=>'2'));
                    //     $this->make->td(num($rent_vat), array("style"=>"text-align:right"));
                    // $this->make->sRow();
                    // $this->make->sRow();
                    //     $this->make->td('EWT - 5%',array('colspan'=>'2'));
                    //     $this->make->td('('.num($rent_ewt ).')', array("style"=>"text-align:right"));
                    // $this->make->sRow();
                    // $this->make->sRow();
                    //     $this->make->th('Net Rental',array('colspan'=>'2'));
                    //     $this->make->th(num($rent_perc + $rent_vat - $rent_ewt), array("style"=>"text-align:right"));
                    // $this->make->sRow();
                               
                $this->make->eTableBody();
            $this->make->eTable();
        $this->make->eDiv();        
        
        update_load(100);

        $code = $this->make->code();
        $json['code'] = $code;        
        $json['dates'] = $this->input->post('calendar_range');

        echo json_encode($json);
    }
    public function summary_sales_pdf()
    {
        // Include the main TCPDF library (search for installation path).
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Summary of Sales Report');
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

        // ---------------------------------------------------------
        $this->load->model('dine/setup_model');
        // $this->load->database('main', TRUE);
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
        
        $month_array = $this->session->userData('summary_array');
        $month_date = $this->session->userData('month_date');
        $days = $this->session->userData('days');


        $pdf->Write(0, 'Summary of Sales', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Write(0, 'Report Period:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, $month_date['text'], '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Transaction Time:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln();              

        // echo "<pre>", print_r($trans), "</pre>";die();
        $pdf->SetFont('helvetica','',7);
        // -----------------------------------------------------------------------------
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(5, 0, '', '', 0, 'L');        
        $pdf->Cell(20, 0, 'Food Stuff', '', 0, 'R');        
        $pdf->Cell(20, 0, 'Food Stuff', '', 0, 'R');        
        $pdf->Cell(20, 0, 'Total', '', 0, 'R');        
        $pdf->Cell(20, 0, 'Senior Discount', '', 0, 'R');        
        $pdf->Cell(20, 0, 'PWD Discount', '', 0, 'R');        
        $pdf->Cell(20, 0, 'Gross Sales', '', 0, 'R');  
        $pdf->Cell(20, 0, 'Service Charge', '', 0, 'R');
        $pdf->Cell(20, 0, 'Output Vat', '', 0, 'R');  
        $pdf->Cell(30, 0, 'EMP Discount', '', 0, 'R'); 
        $pdf->Cell(25, 0, "Customer's Discount", '', 0, 'R'); 
        $pdf->Cell(20, 0, 'Tip', '', 0, 'R');    
        $pdf->Cell(25, 0, 'Net Sales', '', 0, 'R');     
        $pdf->ln();    
        $pdf->Cell(5, 0, '', '', 0, 'L');        
        $pdf->Cell(20, 0, '(Vatable)', '', 0, 'R');        
        $pdf->Cell(20, 0, '(Non Vatable)', '', 0, 'R');                
        $pdf->ln(); 
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln(); 

        $vatsales_total = 0;
        $vatex_total = 0;
        $zero_rate_total = $vat_total = $net_sales_total = $pwdisc_total = $sndisc_total = $othdisc_total = $empdisc_total = $lessvat_total = $total_charges = $gross_total = 0;
        
        foreach ($month_array as $date => $vals) { 
            $pdf->Cell(5, 0, date('d',strtotime($date)), '', 0, 'R');        
            $pdf->Cell(20, 0, num($vals['vatsales']), '', 0, 'R');    
            $pdf->Cell(20, 0, num($vals['vatex'] + $vals['zero_rated']), '', 0, 'R');        
            $pdf->Cell(20, 0, num($vals['vatsales'] + $vals['vatex'] + $vals['zero_rated']), '', 0, 'R');
            $pdf->Cell(20, 0, '('.num($vals['sndisc']).')', '', 0, 'R');
            $pdf->Cell(20, 0, '('.num($vals['pwdisc']).')', '', 0, 'R');
            $pdf->Cell(20, 0, num($vals['gross']), '', 0, 'R');
            $pdf->Cell(20, 0, num($vals['charges']), '', 0, 'R'); 
            $pdf->Cell(20, 0, num($vals['vat']), '', 0, 'R');
            $pdf->Cell(30, 0, '('.num($vals['empdisc']).')', '', 0, 'R');
            $pdf->Cell(25, 0, '('.num($vals['othdisc']).')', '', 0, 'R'); 
            $pdf->Cell(20, 0, num(0), '', 0, 'R'); 
            $pdf->Cell(25, 0, num($vals['net_sales']), '', 0, 'R');    

            $vatsales_total += $vals['vatsales'];
            $vatex_total += $vals['vatex'] + $vals['zero_rated'];
            $vat_total += $vals['vat'];
            $sndisc_total += $vals['sndisc'];
            $pwdisc_total += $vals['pwdisc'];
            $lessvat_total += $vals['lessvat'];
            $empdisc_total += $vals['empdisc'];
            $othdisc_total += $vals['othdisc'];
            $net_sales_total += $vals['net_sales'];
            $total_charges += $vals['charges'];
            $gross_total += $vals['gross'];              
           
            // $counter++;
            // $progress = ($counter / $trans_count) * 100;
            // update_load(num($progress));  

            $pdf->ln();               
        }

        update_load(100);

        $pdf->ln(5); 

        // update_load(100);        
        $pdf->Cell(5, 0, "Total", 'T', 0, 'L');        
        $pdf->Cell(20, 0, num($vatsales_total), 'T', 0, 'R');            
        $pdf->Cell(20, 0, num($vatex_total), 'T', 0, 'R');        
        $pdf->Cell(20, 0, num($vatsales_total + $vatex_total), 'T', 0, 'R');        
        $pdf->Cell(20, 0, '('.num($sndisc_total).')', 'T', 0, 'R');        
        $pdf->Cell(20, 0, '('.num($pwdisc_total).')', 'T', 0, 'R'); 
        $pdf->Cell(20, 0, num($gross_total), 'T', 0, 'R'); 
        $pdf->Cell(20, 0, num($total_charges), 'T', 0, 'R'); 
        $pdf->Cell(20, 0, num($vat_total), 'T', 0, 'R'); 
        $pdf->Cell(30, 0, '('.num($empdisc_total).')', 'T', 0, 'R'); 
        $pdf->Cell(25, 0, '('.num($othdisc_total).')', 'T', 0, 'R'); 
        $pdf->Cell(20, 0, num(0), 'T', 0, 'R'); 
        $pdf->Cell(25, 0, num($net_sales_total), 'T', 0, 'R'); 


        // $pdf->AddPage();

        // $pdf->Cell(50, 0, "Average Sales per Day", 'T', 0, 'L');
        // $pdf->Cell(25, 0, num(($vatsales_total + $vatex_total)/$days), 'T', 0, 'R');  
        // $pdf->ln(5);         
        // $pdf->Cell(50, 0, "Gross Sales", 'T', 0, 'L');
        // $pdf->Cell(25, 0, num($gross_total), 'T', 0, 'R');  

        // $pdf->ln(5);  

        // $rent_perc = $gross_total * 0.18;
        // $rent_vat = $rent_perc * 0.12;
        // $rent_ewt = $rent_perc * 0.05;

        // $pdf->Cell(50, 0, "Percentage Rental - 18%", 'T', 0, 'L'); 
        // $pdf->Cell(25, 0, num($rent_perc), 'T', 0, 'R');  
        // $pdf->ln();
        // $pdf->Cell(50, 0, "Vat - 12%", 'T', 0, 'L');
        // $pdf->Cell(25, 0, num($rent_vat), 'T', 0, 'R');  
        // $pdf->ln();
        // $pdf->Cell(50, 0, "EWT - 5%", 'T', 0, 'L');
        // $pdf->Cell(25, 0, '('.num($rent_ewt) .')', 'T', 0, 'R');  
        // $pdf->ln();
        // $pdf->Cell(50, 0, "Net Rental", 'T', 0, 'L');
        // $pdf->Cell(25, 0, num($rent_perc + $rent_vat - $rent_ewt), 'T', 0, 'R');  
        //retail
     

        // -----------------------------------------------------------------------------

        //Close and output PDF document
        $pdf->Output('summary_sales_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+   
    }

    public function summary_sales_excel(){
        //diretso excel na
        
        $this->load->library('Excel');
        $month_array = $this->session->userData('summary_array');
        $month_date = $this->session->userData('month_date');
        $days = $this->session->userData('days');

        $sheet = $this->excel->getActiveSheet();
        $branch_details = $this->setup_model->get_branch_details();
        $branch = array();
        foreach ($branch_details as $bv) {
            $branch = array(
                'id' => $bv->branch_id,
                'res_id' => $bv->res_id,
                'branch_code' => $bv->branch_code,
                'name' => $bv->branch_name,
                'branch_desc' => $bv->branch_desc,
                'contact_no' => $bv->contact_no,
                'delivery_no' => $bv->delivery_no,
                'address' => $bv->address,
                'base_location' => $bv->base_location,
                'currency' => $bv->currency,
                'inactive' => $bv->inactive,
                'tin' => $bv->tin,
                'machine_no' => $bv->machine_no,
                'bir' => $bv->bir,
                'permit_no' => $bv->permit_no,
                'serial' => $bv->serial,
                'accrdn' => $bv->accrdn,
                'email' => $bv->email,
                'website' => $bv->website,
                'store_open' => $bv->store_open,
                'store_close' => $bv->store_close,
            );
        }

       
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
        $styleTitle = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'size' => 16,
            )
        );

        $rn = 1;
        $sheet->mergeCells('A'.$rn.':M'.$rn);
        $sheet->getCell('A'.$rn)->setValue($branch['name']);
        $sheet->getStyle('A'.$rn)->applyFromArray($styleTitle);
        $rn++;

        $sheet->mergeCells('A'.$rn.':M'.$rn);
        $sheet->getCell('A'.$rn)->setValue($branch['address']);
        $sheet->getStyle('A'.$rn)->applyFromArray($styleTitle);
        $rn++;

        $sheet->mergeCells('A'.$rn.':G'.$rn);
        $sheet->getCell('A'.$rn)->setValue('Summary of Sales');
        $sheet->getStyle('A'.$rn)->applyFromArray($styleTxt);
        $rn++;

        $sheet->mergeCells('A'.$rn.':D'.$rn);
        $sheet->getCell('A'.$rn)->setValue('Report Period: '.$month_date['text']);
        $sheet->getStyle('A'.$rn)->applyFromArray($styleTxt);
        $sheet->mergeCells('G'.$rn.':M'.$rn);
        $sheet->getCell('G'.$rn)->setValue('Report Generated: '.(new \DateTime())->format('Y-m-d H:i:s'));
     
        $rn++;

        $sheet->mergeCells('A'.$rn.':D'.$rn);
        $sheet->getCell('A'.$rn)->setValue('');
        $sheet->getStyle('A'.$rn)->applyFromArray($styleTxt);
        $user = $this->session->userdata('user');
        $sheet->mergeCells('G'.$rn.':M'.$rn);
        $sheet->getCell('G'.$rn)->setValue('Generated by:    '.$user["full_name"]);
        $sheet->getStyle('G'.$rn)->applyFromArray($styleTxt);
        
        $rn = 7;
        $sheet->getCell('A'.$rn)->setValue('Day');
        $sheet->getCell('B'.$rn)->setValue('Food Stuff');
        $sheet->getCell('C'.$rn)->setValue('Food Stuff');
        $sheet->mergeCells('D7:D8');
        $sheet->getCell('D'.$rn)->setValue('Total');
        $sheet->mergeCells('E7:E8');
        $sheet->getCell('E'.$rn)->setValue('Senior Discount');
        $sheet->mergeCells('F7:F8');
        $sheet->getCell('F'.$rn)->setValue('PWD Discount');
        $sheet->mergeCells('G7:G8');
        $sheet->getCell('G'.$rn)->setValue('Gross Sales');

        $sheet->mergeCells('H7:H8');
        $sheet->getCell('H'.$rn)->setValue('Service Charge');
        $sheet->mergeCells('I7:I8');
        $sheet->getCell('I'.$rn)->setValue('Output VAT');
        $sheet->mergeCells('J7:J8');
        $sheet->getCell('J'.$rn)->setValue('Emp Discount');
        $sheet->mergeCells('K7:K8');
        $sheet->getCell('K'.$rn)->setValue('Customer Discount');

        $sheet->mergeCells('L7:L8');
        $sheet->getCell('L'.$rn)->setValue('Tip');

        $sheet->mergeCells('M7:M8');
        $sheet->getCell('M'.$rn)->setValue('Net Sales');

        $sheet->getStyle("A".$rn.":M8")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A'.$rn.':'.'M8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('A'.$rn.':'.'M8')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('A'.$rn.':'.'M8')->getFill()->getStartColor()->setRGB('29bb04');
        $sheet->getStyle('A1:'.'M8')->getFont()->setBold(true);

        $rn++;

        $sheet->getCell('B'.$rn)->setValue('(Vatable)');
        $sheet->getCell('C'.$rn)->setValue('(Non Vatable)');
        
        $rn = 9;
        
        if($month_array){
            $vatsales_total = 0;
            $vatex_total = 0;
            $zero_rate_total = $vat_total = $net_sales_total = $pwdisc_total = $sndisc_total = $othdisc_total = $empdisc_total = $lessvat_total = $total_charges = 0;
            foreach($month_array as $date => $vals){
                $sheet->getCell('A'.$rn)->setValue(date('d',strtotime($date)));
                
                if($vals['vatsales']){
                    $sheet->getStyle('B'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('B'.$rn)->setValue($vals['vatsales']);
                    $sheet->getStyle('C'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('C'.$rn)->setValue($vals['vatex'] + $vals['zero_rated']);
                    $sheet->getStyle('D'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('D'.$rn)->setValue($vals['vatsales'] + $vals['vatex'] + $vals['zero_rated']);                       
                    $sheet->getStyle('E'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('E'.$rn)->setValue($vals['sndisc'] * -1);
                    $sheet->getStyle('F'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('F'.$rn)->setValue($vals['pwdisc'] * -1);
                     $sheet->getStyle('G'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('G'.$rn)->setValue($vals['gross']);
                    $sheet->getStyle('H'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('H'.$rn)->setValue($vals['charges']);
                    $sheet->getStyle('I'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('I'.$rn)->setValue($vals['vat']);
                    $sheet->getStyle('J'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('J'.$rn)->setValue($vals['empdisc'] * -1);
                    $sheet->getStyle('K'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('K'.$rn)->setValue($vals['othdisc'] * -1);
                    $sheet->getStyle('L'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('L'.$rn)->setValue(0);
                    
                    
                    
                    $sheet->getStyle('M'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('M'.$rn)->setValue($vals['net_sales']);

                     

                    $vatsales_total += $vals['vatsales'];
                    $vatex_total += $vals['vatex'] + $vals['zero_rated'];
                    $vat_total += $vals['vat'];
                    $sndisc_total += $vals['sndisc'];
                    $pwdisc_total += $vals['pwdisc'];
                    $lessvat_total += $vals['lessvat'];
                    $empdisc_total += $vals['empdisc'];
                    $othdisc_total += $vals['othdisc'];
                    $net_sales_total += $vals['net_sales'];
                    $total_charges += $vals['charges'];
                    $gross_total += $vals['gross'];
                }
                $rn++;
            }
            $sheet->getStyle('A'.$rn.':R'.$rn)->getFont()->setBold(true);
            $sheet->getCell('A'.$rn)->setValue('TOTAL');
            $sheet->getStyle('B'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('B'.$rn)->setValue($vatsales_total);
            $sheet->getStyle('C'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('C'.$rn)->setValue($vatex_total);
            $sheet->getStyle('D'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('D'.$rn)->setValue($vatsales_total + $vatex_total);                
            $sheet->getStyle('E'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('E'.$rn)->setValue($sndisc_total * -1);
            $sheet->getStyle('F'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('F'.$rn)->setValue($pwdisc_total * -1);
            $sheet->getStyle('G'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('G'.$rn)->setValue($gross_total);
            $sheet->getStyle('H'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('H'.$rn)->setValue($total_charges);
            $sheet->getStyle('I'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('I'.$rn)->setValue($vat_total);
            $sheet->getStyle('J'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('J'.$rn)->setValue($empdisc_total * -1);
            $sheet->getStyle('K'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('K'.$rn)->setValue($othdisc_total * -1);
            $sheet->getStyle('L'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('L'.$rn)->setValue(0);
            $sheet->getStyle('M'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('M'.$rn)->setValue($net_sales_total);
        }

        // $rn += 2;

        // $rent_perc = $gross_total * 0.18;
        // $rent_vat = $rent_perc * 0.12;
        // $rent_ewt = $rent_perc * 0.05;

        // $sheet->mergeCells('B'.$rn.':C'.$rn);
        // $sheet->getCell('B'.$rn)->setValue('Average Sales per Day');
        // $sheet->getCell('D'.$rn)->setValue(($vatsales_total + $vatex_total)/$days);
        // $sheet->getStyle('D'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        // $rn++;
        // $sheet->mergeCells('B'.$rn.':C'.$rn);
        // $sheet->getCell('B'.$rn)->setValue('Gross Sales');
        // $sheet->getCell('D'.$rn)->setValue($gross_total); 
        // $sheet->getStyle('D'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        // $rn += 2;

        // $sheet->mergeCells('B'.$rn.':C'.$rn);
        // $sheet->getCell('B'.$rn)->setValue('Percentage Rental - 18%');
        // $sheet->getCell('D'.$rn)->setValue($rent_perc);
        // $sheet->getStyle('D'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        // $rn++;
        // $sheet->mergeCells('B'.$rn.':C'.$rn);
        // $sheet->getCell('B'.$rn)->setValue('VAT - 12%');
        // $sheet->getCell('D'.$rn)->setValue($rent_vat);
        // $sheet->getStyle('D'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        // $rn++;
        // $sheet->mergeCells('B'.$rn.':C'.$rn);
        // $sheet->getCell('B'.$rn)->setValue('EWT - 5%');
        // $sheet->getCell('D'.$rn)->setValue($rent_ewt * -1);
        // $sheet->getStyle('D'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        // $rn++;
        // $sheet->mergeCells('B'.$rn.':C'.$rn);
        // $sheet->getCell('B'.$rn)->setValue('Net Rental');
        // $sheet->getCell('D'.$rn)->setValue($rent_perc + $rent_vat - $rent_ewt);
        // $sheet->getStyle('D'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        if (ob_get_contents()) 
            ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=summary_'.date('F Y',strtotime($month_date['month_year'])).'.xls');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public function cashier_report(){
        $this->load->model('dine/reports_model');
        $this->load->helper('dine/reports_helper');
        $data = $this->syter->spawn('monthly');
        $data['page_title'] = "Cashier's Report";
        $data['code'] = cashierUi();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['load_js'] = 'dine/reporting.php';
        $data['use_js'] = 'cashierJS';
        $this->load->view('page',$data);
    }
    public function get_cashier_reports(){
        ini_set('memory_limit', '-1');
        set_time_limit(3600);
        sess_clear('date_array');
        sess_clear('month_date');
        $this->load->helper('dine/reports_helper');
        $this->load->model('dine/clock_model'); 
        // $date = date("Y-m-d", strtotime($date));

        
        $date = $this->input->post('date');
        $cashier = $this->input->post('cashier');
        $json = $this->input->post('json');

        if($json == 'true'){
            $asjson = true;
        }else{
            $asjson = false;
        }

        // $now = sql2Date($this->site_model->get_db_now('sql'));
        // if(strtotime($date) < strtotime($now)){
        //     $this->db = $this->load->database('main', TRUE);
        // }
        $get_shift = $this->clock_model->get_old_shift(date2Sql($date),$cashier);

        if(count($get_shift) > 0){
            // $shift_out = $get_shift[0]->cashout_id;
            // $this->print_drawer_details($shift_out,$date,$user,$asjson);

             update_load(10);
            // sleep(1);
            $load = 10;

            $details = $this->setup_model->get_branch_details();
                
            $open_time = $details[0]->store_open;
            $close_time = $details[0]->store_close;

                  
            $date_array = array();
            
            $post = $this->set_post(null,$date);
            $trans = $this->trans_sales($post['args']);
            
            $sales = $trans['sales'];
            $trans_menus = $this->menu_sales($sales['settled']['ids']);
            $trans_charges = $this->charges_sales($sales['settled']['ids']);
            $trans_discounts = $this->discounts_sales($sales['settled']['ids']);
            $tax_disc = $trans_discounts['tax_disc_total'];
            $no_tax_disc = $trans_discounts['no_tax_disc_total'];
            $trans_local_tax = $this->local_tax_sales($sales['settled']['ids']);
            $trans_tax = $this->tax_sales($sales['settled']['ids']);
            $trans_no_tax = $this->no_tax_sales($sales['settled']['ids']);
            $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids']);
            
            $gross = $trans_menus['gross'];
            
            $net = $trans['net'];
            $void = $trans['void'];
            $charges = $trans_charges['total'];
            $discounts = $trans_discounts['total'];
            $local_tax = $trans_local_tax['total'];
            $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;
            if($less_vat < 0)
                $less_vat = 0;
            $tax = $trans_tax['total'];
            $no_tax = $trans_no_tax['total'];
            $zero_rated = $trans_zero_rated['total'];
            $no_tax -= $zero_rated;
            $loc_txt = numInt(($local_tax));
            $net_no_adds = $net-($charges+$local_tax);
            
            $nontaxable = $no_tax - $no_tax_disc;
            
            $taxable = ($gross - $less_vat - $nontaxable - $zero_rated - $discounts) / 1.12;
            $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
            $add_gt = $taxable+$nontaxable+$zero_rated;
            $nsss = $taxable +  $nontaxable +  $zero_rated;
            
            $vat_ = $taxable * .12;

            $net_sales = $gross + $charges + $vat_ - $discounts - $less_vat;
            
            $pos_start = date2SqlDateTime($date." ".$open_time);
            $oa = date('a',strtotime($open_time));
            $ca = date('a',strtotime($close_time));
            $pos_end = date2SqlDateTime($date." ".$close_time);
            if($oa == $ca){
                $pos_end = date('Y-m-d H:i:s',strtotime($pos_end . "+1 days"));
            }

            $gt = $this->old_grand_net_total($pos_start);

            $types = $trans_discounts['types'];
            
            $sndisc = 0;
            $pwdisc = 0;
            $othdisc = 0;
            foreach ($types as $code => $val) {
                $amount = $val['amount'];
                if($code == 'PWDISC'){
                    
                    $pwdisc = $val['amount'];
                }elseif($code == 'SNDISC'){
                    $sndisc = $val['amount'];
                }else{
                    $othdisc += $val['amount'];
                }
               
            }
           
            $date_array[$date] = array(
                'cr_beg'=>iSetObj($trans['first_ref'],'trans_ref'),
                'cr_end'=>iSetObj($trans['last_ref'],'trans_ref'),
                'cr_count'=>$trans['ref_count'],
                'beg'=>$gt['old_grand_total'],
                'new'=>$gt['old_grand_total']+$net_no_adds,
                'ctr'=>$gt['ctr'],
                'vatsales'=>$taxable,
                'vatex'=>$nontaxable,
                'zero_rated'=>$zero_rated,
                'vat'=>$vat_,
                'net_sales'=>$net_sales,
                'pwdisc'=>$pwdisc,
                'sndisc'=>$sndisc,
                'othdisc'=>$othdisc,
                'lessvat'=>$less_vat,
                'gross'=>$gross,
                'charges'=>$charges,
                
            );
        
           
            update_load(75);
           
            $cashier = $this->cashier_model->get_cashout_header($get_shift[0]->cashout_id);
            $this->session->set_userdata('cashier_report',$cashier);
            $this->session->set_userdata('cashier_array',$date_array);
            $this->session->set_userdata('month_date',$date);

            
        }
        else{
            $error = "There is no shift found.";
            echo json_encode(array("code"=>"<pre style='background-color:#fff'>$error</pre>"));
        }
       
        update_load(100);
       
        
    }

    public function cashier_report_gen()
    {
        $this->load->model('dine/reports_model');         
        $cashier_array = $this->session->userData('cashier_array');
        $post = $this->input->post();
        // echo "<pre>",print_r($post),"</pre>";die();
        $results = $this->reports_model->get_trans_sales_per_cashier($post);
        $results_cashier = $this->reports_model->get_trans_sales_per_cashier($post, true);
        $receipt_discounts = $this->reports_model->get_receipt_discounts();
        $receipt_discounts_sales = $this->reports_model->get_receipt_discount_sales($post);
        $receipt_discount_payment_sales = $this->reports_model->get_receipt_discount_sales_per_payment($post);
        $total_charge_array = $total_cash_array = array('gross_cashier'=>0,'gross_fvn_cashier'=>0,
                                    'gross_wl_cashier'=>0,'gross_wln_cashier'=>0,
                                    'gross_sc_cashier'=>0,'gross_ft_tax_cashier'=>0,'gross_line_total_cashier'=>0);

         // $total_charge_array['gross_cashier'] += $gross_cashier; 
         //                        $total_charge_array['gross_fvn_cashier'] += $gross_fvn_cashier;
         //                        $total_charge_array['gross_wl_cashier'] += $gross_wl_cashier;
         //                        $total_charge_array['gross_wln_cashier'] += $gross_wln_cashier;
         //                        $total_charge_array['gross_sc_cashier'] += $gross_sc_cashier;
         //                        $total_charge_array['gross_ft_tax_cashier'] += $gross_ft_tax_cashier;
                               
         //                        $total_charge_array['gross_line_total_cashier'] += $gross_line_total_cashier;
                               

         // = $total_cash_array = array();
        $row_receipt_count  = count($receipt_discounts);
        // echo "<pre>",print_r($receipt_discount_payment_sales),"</pre>";die();
        $this->make->sDiv(array("style"=>"margin-top:58px;"));
        // $this->make->append('<p>No available data based on filters.</p>');
         if(!empty($results)){
            $this->make->sTable(array("id"=>"main-tbl", 'class'=>'table reportTBL sortable ','width'=>'100%'));
                $this->make->sTableHead();
                    // $this->make->sRow();
                    //     $this->make->th('');
                    //     $this->make->th('',array('rowspan'=>'2'));
                    //     $this->make->th('',array('rowspan'=>'2'));
                    //     $this->make->th('',array('rowspan'=>'2'));
                    //     $this->make->th('',array('rowspan'=>'2'));
                    //     $this->make->th('',array('rowspan'=>'2'));
                    //     $this->make->th('',array('rowspan'=>'2'));
                    //     // $this->make->th('',array('rowspan'=>'2'));
                    //     $this->make->th('Discount',array('rowspan'=>'2','colspan'=>$row_receipt_count));
                    //     $this->make->th("",array('rowspan'=>'2'));
                    //     $this->make->th('',array('rowspan'=>'2'));
                    // $this->make->eRow();
                         $this->make->sRow();
                        $this->make->th('TOTAL Sales/Reading',array('class'=>"bold"));
                        $this->make->th('Food (V)',array('class'=>"bold"));
                        $this->make->th('Food (NV)',array('class'=>"bold"));
                        $this->make->th('W&L (V)',array('class'=>"bold"));
                        $this->make->th('W&L (NV)',array('class'=>"bold"));
                        $this->make->th('S. Charge',array('class'=>"bold"));
                        $this->make->th('F-Tax',array('class'=>"bold"));
                        // $this->make->td('W&L-Tax ',array());

                        // for($d = 0 ; $d < $row_receipt_count ; $d++ ){
                        foreach($receipt_discounts as $disc_id=>$receipt_disc){
                             $this->make->th($receipt_disc['disc_code'],array('class'=>"bold"));

 
                        }
                         foreach($receipt_discounts_sales as $disc_id=>$receipt_disc_sales){
                            if($receipt_discounts[$disc_id]['no_tax'] == '0'){
                               $less_adjustment_non_taxable +=  $receipt_disc_sales['disc_amount'];
                                        
                            }
                        }

                   
                        
                       

                        // $this->make->td('Discount',array('rowspan'=>'2'));
                        $this->make->th("Others",array('class'=>"bold"));
                        $this->make->th("Totals&nbsp;&nbsp;&nbsp;&nbsp;",array('class'=>"bold"));
                    $this->make->eRow();

                     
                $this->make->eTableHead();
                $this->make->sTableBody();                    

                    $rows = array('Gross','Less:Adjustments','Net','Others: Add/(Less)','Total','Less:Charge Account','HSBC','Personal Account','Quick Delivery','Gift Certificate','Others');

                    $vatsales_total = 0;
                    $vatex_total = 0;
                    $vat_total = 0;
                    $total_discount = 0;
                    $total_charges = 0;
                    $total_row = 0;
                    $less_adjustment_total_line1 =  $less_adjustment_total_line2 = 0;
                    $less_adjustment_taxable = 0;

                    $ctr = 0;    
                    $ctr_d=1;              
                    $fl_vatable_total = $wl_vatable_total = $fl_non_vatable_total = $wl_non_vatable_total = $service_charge_total = $fl_tax_total = $wl_tax_total = $discount_total = $f_net_tax_total = $less_adjustment_non_taxable_cashier = 0;
                  
                       // $this->make->sRow();
                       //  $this->make->td('TOTAL Sales/Reading',array('class'=>"bold"));
                       //  $this->make->td('Food (V)',array('class'=>"bold"));
                       //  $this->make->td('Food (NV)',array('class'=>"bold"));
                       //  $this->make->td('W&L (V)',array('class'=>"bold"));
                       //  $this->make->td('W&L (NV)',array('class'=>"bold"));
                       //  $this->make->td('S. Charge',array('class'=>"bold"));
                       //  $this->make->td('F-Tax',array('class'=>"bold"));
                       //  // $this->make->td('W&L-Tax ',array());

                       //  // for($d = 0 ; $d < $row_receipt_count ; $d++ ){
                       //  foreach($receipt_discounts as $disc_id=>$receipt_disc){
                       //       $this->make->td($receipt_disc['disc_name'],array('class'=>"bold"));

 
                       //  }
                        //  foreach($receipt_discounts_sales as $disc_id=>$receipt_disc_sales){
                        //     if($receipt_discounts[$disc_id]['no_tax'] == '0'){
                        //        $less_adjustment_non_taxable +=  $receipt_disc_sales['disc_amount'];
                                        
                        //     }
                        // }

                   
                        
                        $less_adjustment_no_tax = $less_adjustment_non_taxable * BASE_TAX; 
                        // $less_adjustment_no_tax_cashier = $less_adjustment_non_taxable_cashier * BASE_TAX;
                        $f_net_tax_total -=  $less_adjustment_no_tax ;

                        // $this->make->td('Discount',array('rowspan'=>'2'));
                    //     $this->make->td("Others",array('class'=>"bold"));
                    //     $this->make->td('Totals',array('class'=>"bold"));
                    // $this->make->eRow();
               
                    foreach ($results as $date=>$vals) {  
                        $gross_sales = $vals['fl_amount'] + $vals['wl_amount'] + $vals['fli_amount'];
                        $total = $vals['fl_vatable'] + $vals['fl_non_vatable'] + $vals['wl_vatable'] + $vals['wl_non_vatable'] + $vals['vat'] + $vals['service_charge']; //-  ($vals['trans_discount']);
                        $discount = $vals['trans_discount'];

                        $fl_vatable_total += $vals['fl_vatable'];
                        $fl_non_vatable_total += $vals['fl_non_vatable'] ;
                        $wl_vatable_total += $vals['wl_vatable'];
                        $wl_non_vatable_total += $vals['wl_non_vatable'] ;
                        $fl_tax_total += $vals['fl_tax'];
                        $wl_tax_total += $vals['wl_tax'];
                        $total_charges += $vals['service_charge'];                  
                        $total_discount += $vals['trans_discount'];
                        // $vat_exempt = $vals['wl_non_vatable'] +  $vals['fl_non_vatable'];
                        $total_row += $total;
                        // $f_net_tax_total += $vals['vat'];
                        // $f_net_tax_total -= $vat_exempt;
                        $gross_fl_vat = ($vals['fl_amount'] + $vals['fli_amount'] ) /1.12;
                        $gross_wl_vat = $vals['wl_amount']/1.12;
                        $gross_fl_wl_vat = $gross_wl_vat + $gross_fl_vat;
                        $gross_vat = ($gross_fl_wl_vat) * BASE_TAX;
                        $gross_total = $gross_fl_wl_vat + $gross_vat;
                        $net_fl_vat = $gross_fl_vat -$vals['fl_non_vatable'];
                        $net_wl_vat = $gross_wl_vat -$vals['wl_non_vatable'];
                        $vat_exempt = $vals['vat_exempt'] - $less_adjustment_no_tax;
                        $f_net_tax_total = $gross_vat -  $vat_exempt - $less_adjustment_no_tax ;

                        $this->make->sRow();
                            $this->make->td($rows[$ctr]);
                            $this->make->td(num($gross_fl_vat), array("style"=>"text-align:right"));
                            $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td(num($gross_wl_vat), array("style"=>"text-align:right"));
                            $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td(num($vals['service_charge']), array("style"=>"text-align:right"));
                            $this->make->td(num($gross_vat), array("style"=>"text-align:right"));
                            // $this->make->td(num($vals['wl_tax']), array("style"=>"text-align:right"));
                            $this->make->td("", array("style"=>"text-align:right"));
                             $this->make->td("", array("style"=>"text-align:right"));
                              $this->make->td("", array("style"=>"text-align:right"));
                               $this->make->td("", array("style"=>"text-align:right"));
                                $this->make->td("", array("style"=>"text-align:right"));
                                 $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td(num(0), array("style"=>"text-align:right"));
                            $this->make->td(num($gross_total), array("style"=>"text-align:right"));
                        $this->make->eRow();

                         $this->make->sRow();
                            $this->make->td($rows[1]);
                            $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td('('.$less_adjustment_no_tax.')', array("style"=>"text-align:right"));
                            // $this->make->td("", array("style"=>"text-align:right"));
                            $less_adjustment_total_line1 += $less_adjustment_no_tax;
                            if(!empty($receipt_discounts_sales)){
                                foreach($receipt_discounts_sales as $disc_id => $receipt_disc_sales){
                                    $this->make->td('('.num($receipt_disc_sales['disc_amount']).')', array("style"=>"text-align:right"));
                                    $less_adjustment_total_line1 += $receipt_disc_sales['disc_amount'];
                                   

                                }

                            }
                            // $this->make->td(num($vals['trans_discount'] * -1), array("style"=>"text-align:right"));
                            $this->make->td(num(0), array("style"=>"text-align:right"));
                            $this->make->td('('.num($less_adjustment_total_line1).')', array("style"=>"text-align:right"));
                        $this->make->eRow();
                        
                         $this->make->sRow();
                            $this->make->td($rows[1]);
                            $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td('('.num($vat_exempt).')', array("style"=>"text-align:right"));
                            // $this->make->td("", array("style"=>"text-align:right"));

                            if(!empty($receipt_discounts_sales)){
                                foreach($receipt_discounts_sales as $receipt_disc_sales){
                                    $this->make->td('', array("style"=>"text-align:right"));
                                        
                                }

                            }
                            // $this->make->td(num($vals['trans_discount'] * -1), array("style"=>"text-align:right"));
                            $this->make->td(num(0), array("style"=>"text-align:right"));
                            $this->make->td('('.num($vat_exempt).')', array("style"=>"text-align:right"));
                        $this->make->eRow();
                        $this->make->sRow();
                        $this->make->td($rows[2]);
                            $this->make->td( num($net_fl_vat), array("style"=>"text-align:right"));
                            $this->make->td( num($vals['fl_non_vatable']), array("style"=>"text-align:right"));
                            $this->make->td(num($net_wl_vat), array("style"=>"text-align:right"));
                            $this->make->td(num($vals['wl_non_vatable']), array("style"=>"text-align:right"));
                            $this->make->td(num($vals['service_charge']), array("style"=>"text-align:right"));
                            $this->make->td(num($f_net_tax_total), array("style"=>"text-align:right"));
                            // $this->make->td("", array("style"=>"text-align:right"));

                           if(!empty($receipt_discounts_sales)){
                                foreach($receipt_discounts_sales as $receipt_disc_sales){
                                    $this->make->td('('.num($receipt_disc_sales['disc_amount']).')', array("style"=>"text-align:right"));
                                        
                                }

                            }
                            // $this->make->td(num($vals['trans_discount'] * -1), array("style"=>"text-align:right"));

                            $net_total_total = $gross_total- $less_adjustment_total_line1 - $vat_exempt;

                            $this->make->td(num(0), array("style"=>"text-align:right"));
                            $this->make->td(num($net_total_total), array("style"=>"text-align:right"));
                        $this->make->eRow();
                         $this->make->sRow();
                         $this->make->td($rows[3]);
                            $this->make->td('', array("style"=>"text-align:right"));
                            $this->make->td('', array("style"=>"text-align:right"));
                            $this->make->td('', array("style"=>"text-align:right"));
                            $this->make->td('', array("style"=>"text-align:right"));
                            $this->make->td('', array("style"=>"text-align:right"));
                            $this->make->td('', array("style"=>"text-align:right"));
                            // $this->make->td("", array("style"=>"text-align:right"));

                           if(!empty($receipt_discounts_sales)){
                                foreach($receipt_discounts_sales as $receipt_disc_sales){
                                    $this->make->td('', array("style"=>"text-align:right"));
                                        
                                }

                            }
                            // $this->make->td(num($vals['trans_discount'] * -1), array("style"=>"text-align:right"));


                            $this->make->td('', array("style"=>"text-align:right"));
                            $this->make->td('', array("style"=>"text-align:right"));
                        $this->make->eRow();
                        $this->make->sRow();
                            $this->make->td($rows[4]);
                            $this->make->td('', array("style"=>"text-align:right"));
                            $this->make->td('', array("style"=>"text-align:right"));
                            $this->make->td('', array("style"=>"text-align:right"));
                            $this->make->td('', array("style"=>"text-align:right"));
                            $this->make->td('', array("style"=>"text-align:right"));
                            $this->make->td('', array("style"=>"text-align:right"));
                            // $this->make->td("", array("style"=>"text-align:right"));

                           if(!empty($receipt_discounts_sales)){
                                foreach($receipt_discounts_sales as $receipt_disc_sales){
                                    $this->make->td('', array("style"=>"text-align:right"));
                                        
                                }

                            }
                            // $this->make->td(num($vals['trans_discount'] * -1), array("style"=>"text-align:right"));


                            $this->make->td('', array("style"=>"text-align:right"));
                            $this->make->td('', array("style"=>"text-align:right"));
                        $this->make->eRow();
                        $this->make->sRow();
                            $this->make->td($rows[5]);
                            $this->make->td('', array("style"=>"text-align:right"));
                            $this->make->td('', array("style"=>"text-align:right"));
                            $this->make->td('', array("style"=>"text-align:right"));
                            $this->make->td('', array("style"=>"text-align:right"));
                            $this->make->td('', array("style"=>"text-align:right"));
                            $this->make->td('', array("style"=>"text-align:right"));
                            // $this->make->td("", array("style"=>"text-align:right"));

                           if(!empty($receipt_discounts_sales)){
                                foreach($receipt_discounts_sales as $receipt_disc_sales){
                                    $this->make->td('', array("style"=>"text-align:right"));
                                        
                                }

                            }
                            // $this->make->td(num($vals['trans_discount'] * -1), array("style"=>"text-align:right"));


                            $this->make->td('', array("style"=>"text-align:right"));
                            $this->make->td('', array("style"=>"text-align:right"));
                        $this->make->eRow();
                           // echo "<pre>receipt_discount_payment_sales: ",print_r($receipt_discount_payment_sales),"</pre>";die();
                        $receipt_discount_payment_arr = array();
                        foreach($receipt_discount_payment_sales as $disc_id=>$payment_type_raw){
                            foreach($payment_type_raw as $payment_type => $p_details){

                                if(isset($receipt_discount_payment_arr[$payment_type])){
                                   $receipt_discount_payment_arr[$payment_type] +=  $p_details['disc_amount'];
                                            
                                }else{
                                    $receipt_discount_payment_arr[$payment_type] = $p_details['disc_amount'];
                                }
                            }
                        }

                                                // echo "<pre>",print_r($receipt_discount_payment_arr),"</pre>";die();

                      
                        $ctr++;
                    }    

                    // if($ctr < 11){
                    //     foreach($rows as $each){
                    //         $this->make->sRow();
                    //             $this->make->td($each, array('colspan'=>'11'));
                    //         $this->make->sRow();

                    //         $ctr++;
                    //     }
                        
                    // }  

                    // echo "<pre>",print_r($total_charge_array),"</pre>";
                    //                     echo "<pre>",print_r($total_cash_array),"</pre>";

                    // die();
                      foreach($results_cashier as $res_cash){
                            if(!empty($res_cash['card_type'])){
                                $payment_type_label = ucwords($res_cash['payment_type'].' - '.$res_cash['card_type']);
                                $p_type_label = $res_cash['payment_type'].' - '.$res_cash['card_type'];
                            }else{
                                $payment_type_label = ucwords($res_cash['payment_type']);
                                $p_type_label = $res_cash['payment_type'];

                            }
                            $gross_fv_cashier = $res_cash['fl_amount'] + $res_cash['fli_amount'];
                            $gross_cashier = ($gross_fv_cashier / 1.12) - $res_cash['fl_non_vatable'] ;
                            $gross_fvn_cashier = $res_cash['fl_non_vatable'];
                            $gross_wl_cashier = ($res_cash['wl_amount']/1.12) -$res_cash['wl_non_vatable'] ;
                            $gross_wln_cashier = $res_cash['wl_non_vatable'] ;
                            $gross_sc_cashier = $res_cash['service_charge'] ;
                            $less_adjustment_no_tax_cashier = $gross_line_total_cashier= 0;
                            if(isset($receipt_discount_payment_arr[$p_type_label])){
                                $less_adjustment_no_tax_cashier = $receipt_discount_payment_arr[$p_type_label] * BASE_TAX;
                            }
                            $gross_ft_tax_cashier = ((($gross_fv_cashier/1.12)+($res_cash['wl_amount']/1.12)) * .12) -  ($res_cash['vat_exempt']-$less_adjustment_no_tax_cashier) - $less_adjustment_no_tax_cashier ;

                            $gross_discount_cashier = 0;
                            // $f_net_tax_total = (($gross_fv_cashier+$gross_wl_cashier) * .12) -  $res_cash['vat_exempt'] - $less_adjustment_no_tax_cashier ;
                            // $gross_ft_tax_cashier;die();
                            if( $res_cash['payment_type'] !='cash'){
                                $this->make->sRow();

                                    $this->make->td($payment_type_label);
                                    $this->make->td(num($gross_cashier), array("style"=>"text-align:right"));
                                    $this->make->td(num($gross_fvn_cashier), array("style"=>"text-align:right"));
                                    $this->make->td(num($gross_wl_cashier), array("style"=>"text-align:right"));
                                    $this->make->td(num($gross_wln_cashier), array("style"=>"text-align:right"));
                                    $this->make->td(num($gross_sc_cashier), array("style"=>"text-align:right"));
                                    $this->make->td(num($gross_ft_tax_cashier), array("style"=>"text-align:right"));
                                    // $this->make->td("", array("style"=>"text-align:right"));

                                foreach($receipt_discounts_sales as $rdisc_id=>$receipt_disc_sales){

                                    if(isset($receipt_discount_payment_sales[$rdisc_id][$res_cash['payment_type']])){

                                        $gross_discount_cashier += $receipt_discount_payment_sales[$rdisc_id]['disc_amount'];
                                        if(isset( $total_charge_array['gross_discount_cashier'][$rdisc_id])){

                                                $total_charge_array['gross_discount_cashier'][$rdisc_id] += $receipt_discount_payment_sales[$rdisc_id]['disc_amount'];
                                                $this->make->td('('.num($receipt_discount_payment_sales[$rdisc_id]['disc_amount']) . ')', array("style"=>"text-align:right"));

                                        }else{
                                            if(isset($receipt_discount_payment_sales[$rdisc_id]['disc_amount'])){
                                                $total_charge_array['gross_discount_cashier'][$rdisc_id] = $receipt_discount_payment_sales[$rdisc_id]['disc_amount'];
                                                $this->make->td('('.num($receipt_discount_payment_sales[$rdisc_id]['disc_amount'] ). ')', array("style"=>"text-align:right"));

                                            }else{
                                                $total_charge_array['gross_discount_cashier'][$rdisc_id] = 0;
                                              $this->make->td(0, array("style"=>"text-align:right"));


                                            }
                                        }
                                        
                                        // $total_charge_array['gross_discount_cashier'][$rdisc_id] += $receipt_discount_payment_sales[$rdisc_id]['disc_amount'];

                                    }else{
                                        $this->make->td(0, array("style"=>"text-align:right"));

                                    }

                                }
                                // $this->make->td(num($vals['trans_discount'] * -1), array("style"=>"text-align:right"));

                                $gross_line_total_cashier = $gross_cashier + $gross_fvn_cashier +  $gross_wl_cashier + $gross_wln_cashier + $gross_sc_cashier + $gross_ft_tax_cashier - $gross_discount_cashier;
                                $this->make->td(0, array("style"=>"text-align:right"));
                                $this->make->td(num($gross_line_total_cashier), array("style"=>"text-align:right"));
                                $this->make->eRow();

                                $total_charge_array['gross_cashier'] += $gross_cashier; 
                                $total_charge_array['gross_fvn_cashier'] += $gross_fvn_cashier;
                                $total_charge_array['gross_wl_cashier'] += $gross_wl_cashier;
                                $total_charge_array['gross_wln_cashier'] += $gross_wln_cashier;
                                $total_charge_array['gross_sc_cashier'] += $gross_sc_cashier;
                                $total_charge_array['gross_ft_tax_cashier'] += $gross_ft_tax_cashier;
                               
                                $total_charge_array['gross_line_total_cashier'] += $gross_line_total_cashier;
                               

                            }else{
                                foreach($receipt_discounts_sales as $rdisc_id=>$receipt_disc_sales){

                                    if(isset($receipt_discount_payment_sales[$rdisc_id])) {

                                        // $this->make->td('('.num($receipt_discount_payment_sales[$rdisc_id][$p_type_label]['disc_amount']) . ')', array("style"=>"text-align:right"));
                                        $gross_discount_cashier += $receipt_discount_payment_sales[$rdisc_id][$res_cash['payment_type']]['disc_amount'];
                                        if(isset( $total_cash_array['gross_discount_cashier'][$rdisc_id])){

                                                $total_cash_array['gross_discount_cashier'][$rdisc_id] += $receipt_discount_payment_sales[$rdisc_id][$res_cash['payment_type']]['disc_amount'];
                                        }else{
                                            if(isset( $receipt_discount_payment_sales[$rdisc_id][$res_cash['payment_type']]['disc_amount'])){
                                                $total_cash_array['gross_discount_cashier'][$rdisc_id] = $receipt_discount_payment_sales[$rdisc_id][$res_cash['payment_type']]['disc_amount'];
                                            }else{
                                                $total_cash_array['gross_discount_cashier'][$rdisc_id] = 0;

                                            }
                                        }

                                    }else{
                                        // $this->make->td(0, array("style"=>"text-align:right"));

                                    }

                                }
                                $total_cash_array['gross_cashier'] += $gross_cashier; 
                                $total_cash_array['gross_fvn_cashier'] += $gross_fvn_cashier;
                                $total_cash_array['gross_wl_cashier'] += $gross_wl_cashier;
                                $total_cash_array['gross_wln_cashier'] += $gross_wln_cashier;
                                $total_cash_array['gross_sc_cashier'] += $gross_sc_cashier;
                                $total_cash_array['gross_ft_tax_cashier'] += $gross_ft_tax_cashier;
                                // $total_cash_array['gross_discount_cashier'] += $gross_discount_cashier;
                                $total_cash_array['gross_line_total_cashier'] += $gross_line_total_cashier;
                            }


                        }

                    $this->make->sRow();
                        $this->make->td('Total Charges Sales');
                        if(isset($total_charge_array['gross_cashier'])){
                             $this->make->td(num($total_charge_array['gross_cashier']), array("style"=>"text-align:right"));
                            $this->make->td(num($total_charge_array['gross_fvn_cashier']), array("style"=>"text-align:right"));
                            $this->make->td(num($total_charge_array['gross_wl_cashier']), array("style"=>"text-align:right"));
                            $this->make->td(num($total_charge_array['gross_wln_cashier']), array("style"=>"text-align:right"));
                            $this->make->td(num( $total_charge_array['gross_sc_cashier'] ), array("style"=>"text-align:right"));      
                            $this->make->td(num( $total_charge_array['gross_ft_tax_cashier']), array("style"=>"text-align:right"));
                             foreach($receipt_discounts_sales as $rdisc_id=>$receipt_disc_sales){
                                if(isset( $total_charge_array['gross_discount_cashier'][$rdisc_id])){

                                    $this->make->td('('.num(  $total_charge_array['gross_discount_cashier'][$rdisc_id]) .')', array("style"=>"text-align:right"));
                                }else{
                                    $this->make->td(num(  0), array("style"=>"text-align:right"));

                                }

                             }
                            // $this->make->th(num(0), array("style"=>"text-align:right"));
                            // $this->make->th(num(0), array("style"=>"text-align:right"));
                             $this->make->td(num(0), array("style"=>"text-align:right"));
                            $this->make->td(num($total_charge_array['gross_line_total_cashier']), array("style"=>"text-align:right"));
                        }else{
                              $this->make->td(num(0), array("style"=>"text-align:right"));
                            $this->make->td(num(0), array("style"=>"text-align:right"));
                            $this->make->td(num(0), array("style"=>"text-align:right"));
                            $this->make->td(num(0), array("style"=>"text-align:right"));
                            $this->make->td(num(0), array("style"=>"text-align:right"));      
                            $this->make->td(num(0), array("style"=>"text-align:right"));
                              foreach($receipt_discounts_sales as $rdisc_id=>$receipt_disc_sales){
                               
                                    $this->make->td(num(  0), array("style"=>"text-align:right"));


                             }
                            // $this->ma
                            $this->make->td(num(0), array("style"=>"text-align:right"));
                            $this->make->td(num(0), array("style"=>"text-align:right"));
              
                        }
                      
                    $this->make->eRow(); 
                    $this->make->sRow();
                        $this->make->td('Total Cash Sales');
                        // echo "<pre>",print_r($total_cash_array),"</pre>";die();
                      if(isset($total_cash_array['gross_cashier'])){
                            $t_disc_total = 0;
                            $this->make->td(num($total_cash_array['gross_cashier']), array("style"=>"text-align:right"));
                            $this->make->td(num($total_cash_array['gross_fvn_cashier']), array("style"=>"text-align:right"));
                            $this->make->td(num($total_cash_array['gross_wl_cashier']), array("style"=>"text-align:right"));
                            $this->make->td(num($total_cash_array['gross_wln_cashier']), array("style"=>"text-align:right"));
                            $this->make->td(num( $total_cash_array['gross_sc_cashier'] ), array("style"=>"text-align:right"));      
                            $this->make->td(num( $total_cash_array['gross_ft_tax_cashier']), array("style"=>"text-align:right"));
                             foreach($receipt_discounts_sales as $rdisc_id=>$receipt_disc_sales){
                                if(isset( $total_cash_array['gross_discount_cashier'][$rdisc_id])){
                                    $t_disc_total += $total_cash_array['gross_discount_cashier'][$rdisc_id];
                                    $this->make->td('('.num(  $total_cash_array['gross_discount_cashier'][$rdisc_id]) . ')', array("style"=>"text-align:right"));
                                }else{
                                    $this->make->td(num(  0), array("style"=>"text-align:right"));

                                }

                             }
                            // $this->make->th(num(0), array("style"=>"text-align:right"));
                            // $this->make->th(num(0), array("style"=>"text-align:right"));
                             $this->make->td(num(0), array("style"=>"text-align:right"));
                             $total_cash_array_total = $total_cash_array['gross_cashier'] + $total_cash_array['gross_fvn_cashier'] + $total_cash_array['gross_wl_cashier'] +$total_cash_array['gross_wln_cashier'] +$total_cash_array['gross_sc_cashier'] + $total_cash_array['gross_ft_tax_cashier'] - $t_disc_total;
                            $this->make->td(num($total_cash_array_total), array("style"=>"text-align:right"));
                        }else{
                              $this->make->td(num(0), array("style"=>"text-align:right"));
                            $this->make->td(num(0), array("style"=>"text-align:right"));
                            $this->make->td(num(0), array("style"=>"text-align:right"));
                            $this->make->td(num(0), array("style"=>"text-align:right"));
                            $this->make->td(num(0), array("style"=>"text-align:right"));      
                            $this->make->td(num(0), array("style"=>"text-align:right"));
                              foreach($receipt_discounts_sales as $rdisc_id=>$receipt_disc_sales){
                               
                                    $this->make->td(num(  0), array("style"=>"text-align:right"));


                             }
                            // $this->ma
                            $this->make->td(num(0), array("style"=>"text-align:right"));
                            $this->make->td(num(0), array("style"=>"text-align:right"));
              
                        }
                      
                    $this->make->eRow(); 

                $this->make->eTableBody();
            $this->make->eTable();
        }
        $this->make->eDiv();        
        
        update_load(80);

        $details = $this->setup_model->get_branch_details();
        $code = $this->make->code();
        $json['code'] = $code;        
        $json['dates'] = $this->input->post('date');
        $json['cashier_name'] = $this->reports_model->get_user($this->input->post('cashier'));
        $json['branch_name'] = $details[0]->branch_name;
        $json['address'] = $details[0]->address;
        echo json_encode($json);
        update_load(100);
    }

    public function cashier_report_pdf()
    {
        // Include the main TCPDF library (search for installation path).
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        $date = $this->input->post();
        $cashier = $this->input->post('cashier');
        // $post

        $results = $this->reports_model->get_trans_sales_per_cashier($post);
        $results_cashier = $this->reports_model->get_trans_sales_per_cashier($post, true);
        $receipt_discounts = $this->reports_model->get_receipt_discounts();
        $receipt_discounts_sales = $this->reports_model->get_receipt_discount_sales($post);
        $receipt_discount_payment_sales = $this->reports_model->get_receipt_discount_sales_per_payment($post);
        $total_charge_array = $total_cash_array = array('gross_cashier'=>0,'gross_fvn_cashier'=>0,
                                    'gross_wl_cashier'=>0,'gross_wln_cashier'=>0,
                                    'gross_sc_cashier'=>0,'gross_ft_tax_cashier'=>0,'gross_line_total_cashier'=>0);
        // create new PDF document
        $pdf = new TCPDF("P", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Cashier Report');
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

        // ---------------------------------------------------------
        $this->load->model('dine/setup_model');
        // $this->load->database('main', TRUE);
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
        
        $cashier = $this->session->userData('cashier_report');
        $cashier_array = $this->session->userData('cashier_array');
        $month_date = $this->session->userData('month_date');

        $pdf->SetFont('helvetica','',7);

        $pdf->Write(0, "Cashier's Report", '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(180, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->Write(0, 'Cashier:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, $cashier ? $cashier->username : '', '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(10);
        $pdf->Write(0, '', '', 0, 'L', true, 0, false, false, 0);

        $pdf->Write(0, 'Report Period:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, $month_date, '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(130);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);

        $shift = $cashier ? $cashier->check_in . ' - ' . $cashier->check_out : '';
        $pdf->Write(0, 'Transaction Time:    '.$shift, '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(130);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(180, 0, '', 'T', 0, 'C');
        $pdf->ln();              

         $pdf->SetFont('helvetica','',7);

        // echo "<pre>", print_r($trans), "</pre>";die();
        
        // -----------------------------------------------------------------------------
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(25, 0, 'Total Sales Reading', 'B', 0, 'L');        
        $pdf->Cell(15, 0, 'Food (V)', 'B', 0, 'R');        
        $pdf->Cell(15, 0, 'Food (NV)', 'B', 0, 'R');        
        $pdf->Cell(15, 0, 'W&L (V)', 'B', 0, 'R');        
        $pdf->Cell(15, 0, 'W&L (NV)', 'B', 0, 'R');        
        $pdf->Cell(15, 0, 'S. Charge', 'B', 0, 'R');        
        $pdf->Cell(15, 0, 'F-Tax', 'B', 0, 'R');  
        $pdf->Cell(15, 0, 'W&L-Tax', 'B', 0, 'R');
        $pdf->Cell(15, 0, 'Discount', 'B', 0, 'R');  
        $pdf->Cell(15, 0, 'Others', 'B', 0, 'R');  
        $pdf->Cell(20, 0, 'Total', 'B', 0, 'R');            
       
        $pdf->ln(); 

        $rows = array('Gross','Less:Adjustments','Net','Others: Add/(Less)','Total','Less:Charge Account','HSBC','Personal Account','Quick Delivery','Gift Certificate','Others'); 

        foreach($rows as $each){
            $pdf->Cell(10, 0, $each, '', 0, 'L');
            $pdf->ln();
        }

        $pdf->setY(48);

        $vatsales_total = 0;
        $vatex_total = 0;
        $vat_total = 0;
        $total_discount = 0;
        $total_charges = 0;
        $total_row = 0;
        
        foreach ($cashier_array as $date => $vals) { 
            $total = $vals['vatsales'] + $vals['vatex'] + $vals['zero_rated'] + $vals['vat'] + $vals['charges'] -  ($vals['sndisc'] + $vals['pwdisc'] + $vals['lessvat'] + $vals['othdisc']);
            $discount = $vals['sndisc'] + $vals['pwdisc'] + $vals['lessvat'] + $vals['othdisc'];

            $vatsales_total += $vals['vatsales'];
            $vatex_total += $vals['vatex'] + $vals['zero_rated'];
            $vat_total += $vals['vat'];
            $total_charges += $vals['charges'];                  
            $total_discount += $discount;
            $total_row += $total;

            $pdf->Cell(25, 0, '', '', 0, 'R');
            $pdf->Cell(15, 0, num($vals['vatsales']), '', 0, 'R');    
            $pdf->Cell(15, 0, num($vals['vatex'] + $vals['zero_rated']), '', 0, 'R');        
            $pdf->Cell(15, 0, num(0), '', 0, 'R');
            $pdf->Cell(15, 0, num(0), '', 0, 'R');
            $pdf->Cell(15, 0, num($vals['charges']), '', 0, 'R');
            $pdf->Cell(15, 0, num($vals['vat']), '', 0, 'R');
            $pdf->Cell(15, 0, num(0), '', 0, 'R'); 
            $pdf->Cell(15, 0, num($discount * -1), '', 0, 'R'); 
            $pdf->Cell(15, 0, num(0), '', 0, 'R'); 
            $pdf->Cell(20, 0, num($total), '', 0, 'R');    

            $pdf->ln();               
        }

        update_load(100);

        $pdf->setY(83); 

        // update_load(100);        

        $pdf->Cell(25, 0, "Total Charge Sales", 'T', 0, 'L');        
        $pdf->Cell(15, 0, num(0), 'T', 0, 'R');            
        $pdf->Cell(15, 0, num(0), 'T', 0, 'R');        
        $pdf->Cell(15, 0, num(0), 'T', 0, 'R');        
        $pdf->Cell(15, 0, num(0), 'T', 0, 'R');
        $pdf->Cell(15, 0, num(0), 'T', 0, 'R'); 
        $pdf->Cell(15, 0, num(0), 'T', 0, 'R'); 
        $pdf->Cell(15, 0, num(0), 'T', 0, 'R'); 
        $pdf->Cell(15, 0, num(0), 'T', 0, 'R'); 
        $pdf->Cell(15, 0, num(0), 'T', 0, 'R'); 
        $pdf->Cell(20, 0, num(0), 'T', 0, 'R');

        $pdf->ln();

        $pdf->Cell(25, 0, "Total Cash Sales", 'T', 0, 'L');        
        $pdf->Cell(15, 0, num($vatsales_total), 'T', 0, 'R');            
        $pdf->Cell(15, 0, num($vatex_total), 'T', 0, 'R');        
        $pdf->Cell(15, 0, num(0), 'T', 0, 'R');        
        $pdf->Cell(15, 0, num(0), 'T', 0, 'R');
        $pdf->Cell(15, 0, num($total_charges), 'T', 0, 'R'); 
        $pdf->Cell(15, 0, num($vat_total), 'T', 0, 'R'); 
        $pdf->Cell(15, 0, num(0), 'T', 0, 'R'); 
        $pdf->Cell(15, 0, num($total_discount * -1), 'T', 0, 'R'); 
        $pdf->Cell(15, 0, num(0), 'T', 0, 'R');
        $pdf->Cell(20, 0, num($total_row), 'T', 0, 'R'); 

        // -----------------------------------------------------------------------------

        //Close and output PDF document
        $pdf->Output('cashier_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+   
    }

    public function cashier_report_excel(){
        //diretso excel na
        
        $this->load->library('Excel');
        $cashier = $this->session->userData('cashier_report');
        $cashier_array = $this->session->userData('cashier_array');
        $month_date = $this->session->userData('month_date');
        $sheet = $this->excel->getActiveSheet();
        $branch_details = $this->setup_model->get_branch_details();
        $branch = array();
        foreach ($branch_details as $bv) {
            $branch = array(
                'id' => $bv->branch_id,
                'res_id' => $bv->res_id,
                'branch_code' => $bv->branch_code,
                'name' => $bv->branch_name,
                'branch_desc' => $bv->branch_desc,
                'contact_no' => $bv->contact_no,
                'delivery_no' => $bv->delivery_no,
                'address' => $bv->address,
                'base_location' => $bv->base_location,
                'currency' => $bv->currency,
                'inactive' => $bv->inactive,
                'tin' => $bv->tin,
                'machine_no' => $bv->machine_no,
                'bir' => $bv->bir,
                'permit_no' => $bv->permit_no,
                'serial' => $bv->serial,
                'accrdn' => $bv->accrdn,
                'email' => $bv->email,
                'website' => $bv->website,
                'store_open' => $bv->store_open,
                'store_close' => $bv->store_close,
            );
        }

       
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
        $styleTitle = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'size' => 16,
            )
        );

        $rn = 1;
        $sheet->mergeCells('A'.$rn.':M'.$rn);
        $sheet->getCell('A'.$rn)->setValue($branch['name']);
        $sheet->getStyle('A'.$rn)->applyFromArray($styleTitle);
        $rn++;

        $sheet->mergeCells('A'.$rn.':M'.$rn);
        $sheet->getCell('A'.$rn)->setValue($branch['address']);
        $sheet->getStyle('A'.$rn)->applyFromArray($styleTitle);
        $rn++;

        $sheet->mergeCells('A'.$rn.':G'.$rn);
        $sheet->getCell('A'.$rn)->setValue("Cashier's Report");
        $sheet->getStyle('A'.$rn)->applyFromArray($styleTxt);
        $rn++;

        $sheet->mergeCells('A'.$rn.':D'.$rn);
        $username = $cashier ? $cashier->username : '';
        $sheet->getCell('A'.$rn)->setValue('Cashier: '.$username);

        $rn++;
        $sheet->mergeCells('A'.$rn.':D'.$rn);
        $sheet->getCell('A'.$rn)->setValue('Report Period: '.$month_date);
        $sheet->getStyle('A'.$rn)->applyFromArray($styleTxt);
        $sheet->mergeCells('G'.$rn.':M'.$rn);
        $sheet->getCell('G'.$rn)->setValue('Report Generated: '.(new \DateTime())->format('Y-m-d H:i:s'));
     
        $rn++;

        $sheet->mergeCells('A'.$rn.':D'.$rn);
        $shift = $cashier ? $cashier->check_in . ' - ' . $cashier->check_out : '';
        $sheet->getCell('A'.$rn)->setValue('Transaction Time: '.$shift);
        $sheet->getStyle('A'.$rn)->applyFromArray($styleTxt);
        $user = $this->session->userdata('user');
        $sheet->mergeCells('G'.$rn.':M'.$rn);
        $sheet->getCell('G'.$rn)->setValue('Generated by:    '.$user["full_name"]);
        $sheet->getStyle('G'.$rn)->applyFromArray($styleTxt);
        
        $rn = 8;
        $sheet->getCell('A'.$rn)->setValue('Total Sales/Reading');
        $sheet->getCell('B'.$rn)->setValue('Food(V)');
        $sheet->getCell('C'.$rn)->setValue('Food(NV)');
        $sheet->getCell('D'.$rn)->setValue('W&L(V)');
        $sheet->getCell('E'.$rn)->setValue('W&L(V)');
        $sheet->getCell('F'.$rn)->setValue('S. Charge');
        $sheet->getCell('G'.$rn)->setValue('F-Tax');
        $sheet->getCell('H'.$rn)->setValue('W&L-Tax');
        $sheet->getCell('I'.$rn)->setValue('Discount');
        $sheet->getCell('J'.$rn)->setValue('Others');
        $sheet->getCell('K'.$rn)->setValue('Total');

        $sheet->getStyle("A".$rn.":K8")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A'.$rn.':'.'K8')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $sheet->getStyle('A'.$rn.':'.'K8')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $sheet->getStyle('A'.$rn.':'.'K8')->getFill()->getStartColor()->setRGB('29bb04');
        $sheet->getStyle('A1:'.'K8')->getFont()->setBold(true);

        $rn = 9;

        $rows = array('Gross','Less:Adjustments','Net','Others: Add/(Less)','Total','Less:Charge Account','HSBC','Personal Account','Quick Delivery','Gift Certificate','Others'); 

        foreach($rows as $each){
            $sheet->getCell('A'.$rn)->setValue($each);
            $rn++;
        }

        $rn = 9;
        
        if($cashier_array){
            $vatsales_total = 0;
            $vatex_total = 0;
            $vat_total = 0;
            $total_discount = 0;
            $total_charges = 0;
            $total_row = 0;
            foreach($cashier_array as $date => $vals){
                $sheet->getCell('A'.$rn)->setValue(date('d',strtotime($date)));
                
                if($vals['vatsales']){
                    $total = $vals['vatsales'] + $vals['vatex'] + $vals['zero_rated'] + $vals['vat'] + $vals['charges'] -  ($vals['sndisc'] + $vals['pwdisc'] + $vals['lessvat'] + $vals['othdisc']);
                    $discount = $vals['sndisc'] + $vals['pwdisc'] + $vals['lessvat'] + $vals['othdisc'];

                    $vatsales_total += $vals['vatsales'];
                    $vatex_total += $vals['vatex'] + $vals['zero_rated'];
                    $vat_total += $vals['vat'];
                    $total_charges += $vals['charges'];                  
                    $total_discount += $discount;
                    $total_row += $total;

                    $sheet->getStyle('B'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('B'.$rn)->setValue($vals['vatsales']);
                    $sheet->getStyle('C'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('C'.$rn)->setValue($vals['vatex'] + $vals['zero_rated']);
                    $sheet->getStyle('D'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('D'.$rn)->setValue(0);                       
                    $sheet->getStyle('E'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('E'.$rn)->setValue(0);
                    $sheet->getStyle('F'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('F'.$rn)->setValue($vals['charge']);
                     $sheet->getStyle('G'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('G'.$rn)->setValue($vals['vat']);
                    $sheet->getStyle('H'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('H'.$rn)->setValue(0);
                    $sheet->getStyle('I'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('I'.$rn)->setValue($discount * -1);
                    $sheet->getStyle('J'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('J'.$rn)->setValue(0);
                    $sheet->getStyle('K'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getCell('K'.$rn)->setValue($total);
                }
                $rn++;
            }

            $rn = 20;

            $sheet->getStyle('A'.$rn.':R'.$rn)->getFont()->setBold(true);
            $sheet->getCell('A'.$rn)->setValue('Total Charge Sales');
            $sheet->getStyle('B'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('B'.$rn)->setValue(0);
            $sheet->getStyle('C'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('C'.$rn)->setValue(0);
            $sheet->getStyle('D'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('D'.$rn)->setValue(0);                
            $sheet->getStyle('E'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('E'.$rn)->setValue(0);
            $sheet->getStyle('F'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('F'.$rn)->setValue(0);
            $sheet->getStyle('G'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('G'.$rn)->setValue(0);
            $sheet->getStyle('H'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('H'.$rn)->setValue(0);
            $sheet->getStyle('I'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('I'.$rn)->setValue(0);
            $sheet->getStyle('J'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('J'.$rn)->setValue(0);
            $sheet->getStyle('K'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('K'.$rn)->setValue(0);

            $rn++;
            $sheet->getStyle('A'.$rn.':R'.$rn)->getFont()->setBold(true);
            $sheet->getCell('A'.$rn)->setValue('Total Cash Sales');
            $sheet->getStyle('B'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('B'.$rn)->setValue($vatsales_total);
            $sheet->getStyle('C'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('C'.$rn)->setValue($vatex_total);
            $sheet->getStyle('D'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('D'.$rn)->setValue(0);                
            $sheet->getStyle('E'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('E'.$rn)->setValue(0);
            $sheet->getStyle('F'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('F'.$rn)->setValue($total_charges);
            $sheet->getStyle('G'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('G'.$rn)->setValue($vat_total);
            $sheet->getStyle('H'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('H'.$rn)->setValue(0);
            $sheet->getStyle('I'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('I'.$rn)->setValue($total_discount * -1);
            $sheet->getStyle('J'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('J'.$rn)->setValue(0);
            $sheet->getStyle('K'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('K'.$rn)->setValue($total_row);
        }
      

        if (ob_get_contents()) 
            ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=cashier('.date('Y_m_d').').xls');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }


    public function bir_textfile_gen(){
        $this->load->model("dine/reports_model");
        $this->reports_model->db = $this->load->database('main', TRUE);
        // $setup = $this->setup_model->get_details(1);
        // if(isset($setup[0]))
        // {
        //     $set = $setup[0];            
        //     $store_open = $set->store_open;
        // }else{
        //     $store_open = "00:00:00";
        // }
        update_load(20);        

        // $daterange = "03/01/2019 to 03/12/2019";//
        // $daterange = $this->input->post("calendar_range");        
        // $dates = explode(" to ",$daterange);

        // $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        // $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        // $file_name = str_ireplace("/", "", $daterange);

        $get_trans_sales =  $this->reports_model->get_bir_trans_sales();
        // echo "<pre>",print_r($get_trans_sales),"</pre>";die();
        update_load(60);        

     


  
        // die();
        // echo $txt_contents;die();
        // echo base_url();die();
        $date_now = date('Ymd');
        $file_name = 'generated_txtfile_'.$date_now.'.csv';
        $file_path = BIR_TEXT_PATH.$file_name;
        update_load(80);        
        if(!empty($get_trans_sales)){

            $fp = fopen( $file_path, 'w');
            fputcsv($fp, array('Date','Trans No','VAT Sales','Exempt Sales','Zero Rated Sales','VAT','Discount','Charges','Net Sales'));
            foreach ($get_trans_sales as $fields) {
                fputcsv($fp, $fields);
            }
            fclose($fp);
        }

        // $content = file_put_contents($file_path, $txt_contents);
        update_load(100);     
        echo  json_encode(array('file_path'=>$file_path,"error"=>""));
           
    }

    public function charge_sales_summary_report(){
        $this->load->model('dine/reports_model');
        $this->load->helper('dine/reports_helper');
        $data = $this->syter->spawn('monthly');
        $data['page_title'] = "Charge Sales Summary Report";
        $data['code'] = cashierUi();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['load_js'] = 'dine/reporting.php';
        $data['use_js'] = 'cashierChargeJS';
        $this->load->view('page',$data);
    }

    public function cashier_charge_report_gen()
    {
        $this->load->model('dine/reports_model');         
        $cashier_array = $this->session->userData('cashier_array');
        $post = $this->input->post();
        // echo "<pre>",print_r($post),"</pre>";die();
        $results = $this->reports_model->get_trans_sales_credit_charges($post);
        $receipt_discounts = $this->reports_model->get_receipt_discounts();
        $receipt_discounts_sales = $this->reports_model->get_receipt_discount_sales_per_payment($post,true);
        // echo "<pre>",print_r($receipt_discounts_sales),"</pre>";die();
        // $results_cashier = $this->reports_model->get_trans_sales_per_cashier($post, true);
        // $receipt_discounts = $this->reports_model->get_receipt_discounts();
        // $receipt_discounts_sales = $this->reports_model->get_receipt_discount_sales($post);
        // $receipt_discount_payment_sales = $this->reports_model->get_receipt_discount_sales_per_payment($post);
        // $total_charge_array = $total_cash_array = array('gross_cashier'=>0,'gross_fvn_cashier'=>0,
        //                             'gross_wl_cashier'=>0,'gross_wln_cashier'=>0,
        //                             'gross_sc_cashier'=>0,'gross_ft_tax_cashier'=>0,'gross_line_total_cashier'=>0);

         // $total_charge_array['gross_cashier'] += $gross_cashier; 
         //                        $total_charge_array['gross_fvn_cashier'] += $gross_fvn_cashier;
         //                        $total_charge_array['gross_wl_cashier'] += $gross_wl_cashier;
         //                        $total_charge_array['gross_wln_cashier'] += $gross_wln_cashier;
         //                        $total_charge_array['gross_sc_cashier'] += $gross_sc_cashier;
         //                        $total_charge_array['gross_ft_tax_cashier'] += $gross_ft_tax_cashier;
                               
         //                        $total_charge_array['gross_line_total_cashier'] += $gross_line_total_cashier;
                               

         // = $total_cash_array = array();
        $row_receipt_count  = count($receipt_discounts);
        // echo "<pre>",print_r($receipt_discount_payment_sales),"</pre>";die();
        $this->make->sDiv();
            $this->make->sTable(array("id"=>"main-tbl", 'class'=>'table reportTBL sortable '));
                $this->make->sTableHead();
                    // $this->make->sRow();
                    //     $this->make->th('');
                    //     $this->make->th('',array('rowspan'=>'2'));
                    //     $this->make->th('',array('rowspan'=>'2'));
                    //     $this->make->th('',array('rowspan'=>'2'));
                    //     $this->make->th('',array('rowspan'=>'2'));
                    //     $this->make->th('',array('rowspan'=>'2'));
                    //     $this->make->th('',array('rowspan'=>'2'));
                    //     // $this->make->th('',array('rowspan'=>'2'));
                    //     $this->make->th('Discount',array('rowspan'=>'2','colspan'=>$row_receipt_count));
                    //     $this->make->th("",array('rowspan'=>'2'));
                    //     $this->make->th('',array('rowspan'=>'2'));
                    // $this->make->eRow();
                         $this->make->sRow();
                        $this->make->th('Customer/ Credit Card',array('class'=>"bold"));
                            $this->make->th('Receipt #',array('class'=>"bold"));
                        $this->make->th('Cash Sales',array('class'=>"bold"));
                        $this->make->th('Food (V)',array('class'=>"bold"));
                        $this->make->th('Food (NV)',array('class'=>"bold"));
                        $this->make->th('W&L (V)',array('class'=>"bold"));
                        $this->make->th('W&L (NV)',array('class'=>"bold"));
                        $this->make->th('S. Charge',array('class'=>"bold"));
                        $this->make->th('SC Adj',array('class'=>"bold"));
                        $this->make->th('VAT',array('class'=>"bold"));
                        $this->make->th('VAT Adj.',array('class'=>"bold"));
                        // $this->make->td('W&L-Tax ',array());

                        // for($d = 0 ; $d < $row_receipt_count ; $d++ ){
                        foreach($receipt_discounts as $disc_id=>$receipt_disc){
                             $this->make->th($receipt_disc['disc_code'],array('class'=>"bold"));

 
                        }
                         foreach($receipt_discounts_sales as $disc_id=>$receipt_disc_sales){
                            if($receipt_discounts[$disc_id]['no_tax'] == '0'){
                               $less_adjustment_non_taxable +=  $receipt_disc_sales['disc_amount'];
                                        
                            }
                        }

                   
                        
                       

                        // $this->make->td('Discount',array('rowspan'=>'2'));
                        $this->make->th("Totals&nbsp;&nbsp;&nbsp;&nbsp;",array('class'=>"bold"));
                    $this->make->eRow();

                     
                $this->make->eTableHead();
                $this->make->sTableBody();                    


                    $vatsales_total = 0;
                    $vatex_total = 0;
                    $vat_total = 0;
                    $total_discount = 0;
                    $total_charges = 0;
                    $total_row = 0;
                    $less_adjustment_total_line1 =  $less_adjustment_total_line2 = 0;
                    $less_adjustment_taxable = 0;

                    $ctr = 0;    
                    $ctr_d=1;              
                    $fl_vatable_total = $wl_vatable_total = $fl_non_vatable_total = $wl_non_vatable_total = $service_charge_total = $fl_tax_total = $wl_tax_total = $discount_total = $f_net_tax_total = $less_adjustment_non_taxable_cashier = 0;
                  
                       // $this->make->sRow();
                       //  $this->make->td('TOTAL Sales/Reading',array('class'=>"bold"));
                       //  $this->make->td('Food (V)',array('class'=>"bold"));
                       //  $this->make->td('Food (NV)',array('class'=>"bold"));
                       //  $this->make->td('W&L (V)',array('class'=>"bold"));
                       //  $this->make->td('W&L (NV)',array('class'=>"bold"));
                       //  $this->make->td('S. Charge',array('class'=>"bold"));
                       //  $this->make->td('F-Tax',array('class'=>"bold"));
                       //  // $this->make->td('W&L-Tax ',array());

                       //  // for($d = 0 ; $d < $row_receipt_count ; $d++ ){
                       //  foreach($receipt_discounts as $disc_id=>$receipt_disc){
                       //       $this->make->td($receipt_disc['disc_name'],array('class'=>"bold"));

 
                       //  }
                        //  foreach($receipt_discounts_sales as $disc_id=>$receipt_disc_sales){
                        //     if($receipt_discounts[$disc_id]['no_tax'] == '0'){
                        //        $less_adjustment_non_taxable +=  $receipt_disc_sales['disc_amount'];
                                        
                        //     }
                        // }

                   
                        
                        $less_adjustment_no_tax = $less_adjustment_non_taxable * BASE_TAX; 
                        // $less_adjustment_no_tax_cashier = $less_adjustment_non_taxable_cashier * BASE_TAX;
                        $f_net_tax_total -=  $less_adjustment_no_tax ;

                        // $this->make->td('Discount',array('rowspan'=>'2'));
                    //     $this->make->td("Others",array('class'=>"bold"));
                    //     $this->make->td('Totals',array('class'=>"bold"));
                    // $this->make->eRow();
                    $cur_card_type="";
                    $total_based = $grand_total = array();
                    $grand_total = array('gross_fl_vat'=>0,'fl_non_vatable_total'=>0,
                                'gross_wl_vat'=>0,'wl_non_vatable_total'=>0,'service_charge'=>0,'gross_vat'=>0,'gross_total'=>0);

                    if(!empty($results)){

                        foreach ($results as $date=>$vals) {  
                            $gross_sales = $vals['fl_amount'] + $vals['wl_amount'] + $vals['fli_amount'];
                            $total = $vals['fl_vatable'] + $vals['fl_non_vatable'] + $vals['wl_vatable'] + $vals['wl_non_vatable'] + $vals['vat'] + $vals['service_charge']; //-  ($vals['trans_discount']);
                            $discount = $vals['trans_discount'];

                            $fl_vatable_total += $vals['fl_vatable'];
                            $fl_non_vatable_total += $vals['fl_non_vatable'] ;
                            $wl_vatable_total += $vals['wl_vatable'];
                            $wl_non_vatable_total += $vals['wl_non_vatable'] ;
                            $fl_tax_total += $vals['fl_tax'];
                            $wl_tax_total += $vals['wl_tax'];
                            $total_charges += $vals['service_charge'];                  
                            $total_discount += $vals['trans_discount'];
                            // $vat_exempt = $vals['wl_non_vatable'] +  $vals['fl_non_vatable'];
                            $total_row += $total;
                            // $f_net_tax_total += $vals['vat'];
                            // $f_net_tax_total -= $vat_exempt;
                            $gross_fl_vat = ($vals['fl_amount'] + $vals['fli_amount'] ) /1.12;
                            $gross_wl_vat = $vals['wl_amount']/1.12;
                            $gross_fl_wl_vat = $gross_wl_vat + $gross_fl_vat;
                            $gross_vat = ($gross_fl_wl_vat) * BASE_TAX;
                            $gross_total = $gross_fl_wl_vat + $gross_vat;
                            $net_fl_vat = $gross_fl_vat -$vals['fl_non_vatable'];
                            $net_wl_vat = $gross_wl_vat -$vals['wl_non_vatable'];
                            $vat_exempt = $vals['vat_exempt'] - $less_adjustment_no_tax;
                            $f_net_tax_total = $gross_vat -  $vat_exempt - $less_adjustment_no_tax ;
                            $cur_type = $vals['payment_type']."-".$vals['card_type'];
                           

                            if($cur_card_type != $cur_type){
                                

                                if($cur_card_type != ""){
                                    $this->make->sRow();
                                        $this->make->td("TOTAL",array("style"=>"font-weight:bold"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td(num($total_based[$cur_card_type]['gross_fl_vat']) , array("style"=>"text-align:right;font-weight:bold"));
                                        $this->make->td(num($total_based[$cur_card_type]['fl_non_vatable_total']), array("style"=>"text-align:right;font-weight:bold"));
                                        $this->make->td(num($total_based[$cur_card_type]['gross_wl_vat']), array("style"=>"text-align:right;font-weight:bold"));
                                        $this->make->td(num($total_based[$cur_card_type]['wl_non_vatable_total']), array("style"=>"text-align:right;font-weight:bold"));

                                        $this->make->td(num($total_based[$cur_card_type]['service_charge']), array("style"=>"text-align:right;font-weight:bold"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td(num($total_based[$cur_card_type]['gross_vat']), array("style"=>"text-align:right;font-weight:bold"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        if(!empty($receipt_discounts)){
                                            foreach($receipt_discounts as $disc_id => $receipt_disc_sales){
                                                if(isset($total_based[$cur_card_type]['discs'][$disc_id])){

                                                    $this->make->td('('.num($total_based[$cur_card_type]['discs'][$disc_id]).')', array("style"=>"text-align:right;font-weight:bold"));
                                                    
                                                }else{
                                                    $this->make->td(0, array("style"=>"text-align:right;font-weight:bold"));

                                                }

                                               

                                            }

                                        }
                                        $this->make->td(num($total_based[$cur_card_type]['gross_total']), array("style"=>"text-align:right;font-weight:bold"));
                                      
                                    $this->make->eRow();

                                }

                                $this->make->sRow();
                                    $this->make->td(ucwords($cur_type), array("style"=>"font-weight:bold"));
                                     $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        // $this->make->td(num($vals['wl_tax']), array("style"=>"text-align:right"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td("", array("style"=>"text-align:right"));

                                $this->make->eRow();
                                 $total_based[$cur_type] = array('gross_fl_vat'=>0,'fl_non_vatable_total'=>0,
                                    'gross_wl_vat'=>0,'wl_non_vatable_total'=>0,'service_charge'=>0,'gross_vat'=>0,'gross_total'=>0);

                            }

                            $this->make->sRow();
                                $this->make->td($vals['card_number']);
                                $this->make->td($vals['trans_ref'], array("style"=>"text-align:right"));
                                $this->make->td("", array("style"=>"text-align:right"));
                                $this->make->td(num($gross_fl_vat), array("style"=>"text-align:right"));
                                $this->make->td(num($fl_non_vatable_total), array("style"=>"text-align:right"));
                                $this->make->td(num($gross_wl_vat), array("style"=>"text-align:right"));
                                $this->make->td(num($wl_non_vatable_total), array("style"=>"text-align:right"));
                                // $this->make->td(num($vals['wl_tax']), array("style"=>"text-align:right"));
                                $this->make->td(num($vals['service_charge']), array("style"=>"text-align:right"));
                                $this->make->td("", array("style"=>"text-align:right"));
                                $this->make->td(num($gross_vat), array("style"=>"text-align:right"));
                                $this->make->td("", array("style"=>"text-align:right"));
                                 if(!empty($receipt_discounts)){
                                    foreach($receipt_discounts as $disc_id => $receipt_disc_sales){
                                        if(isset($receipt_discounts_sales[$vals['trans_ref']][$cur_type][$disc_id])){

                                            $this->make->td('('.num($receipt_discounts_sales[$vals['trans_ref']][$cur_type][$disc_id]['disc_amount']).')', array("style"=>"text-align:right"));
                                            if(isset($total_based[$cur_type]['discs'][$disc_id])){
                                              $total_based[$cur_type]['discs'][$disc_id] += $receipt_discounts_sales[$vals['trans_ref']][$cur_type][$disc_id]['disc_amount'];
                                            }else{
                                              $total_based[$cur_type]['discs'][$disc_id] = $receipt_discounts_sales[$vals['trans_ref']][$cur_type][$disc_id]['disc_amount'];
             
                                            }

                                            if(isset($grand_total['discs'][$disc_id])){
                                                 $grand_total['discs'][$disc_id] += $receipt_discounts_sales[$vals['trans_ref']][$cur_type][$disc_id]['disc_amount'];
                                            }else{
                                                $grand_total['discs'][$disc_id] = $receipt_discounts_sales[$vals['trans_ref']][$cur_type][$disc_id]['disc_amount'];
                                            }
                                        }else{
                                            $this->make->td(0, array("style"=>"text-align:right"));

                                        }

                                        $less_adjustment_total_line1 += $receipt_disc_sales['disc_amount'];
                                       

                                    }

                                }

                                $this->make->td(num($gross_total), array("style"=>"text-align:right"));

                                $grand_total['gross_fl_vat'] += $total_based[$cur_type]['gross_fl_vat'] += $gross_fl_vat;
                                $grand_total['fl_non_vatable_total'] += $total_based[$cur_type]['fl_non_vatable_total'] += $fl_non_vatable_total;
                                $grand_total['gross_wl_vat'] += $total_based[$cur_type]['gross_wl_vat'] += $gross_wl_vat;
                                $grand_total['wl_non_vatable_total'] += $total_based[$cur_type]['wl_non_vatable_total'] += $wl_non_vatable_total;
                                $grand_total['service_charge'] += $total_based[$cur_type]['service_charge'] += $vals['service_charge'];
                                $grand_total['gross_vat'] += $total_based[$cur_type]['gross_vat'] += $gross_vat;
                                $grand_total['gross_total'] += $total_based[$cur_type]['gross_total'] += $gross_total;
                            $this->make->eRow();

                          
                             

                          // echo "<pre>",print_r($receipt_discount_payment_arr),"</pre>";die();

                          $cur_card_type = $cur_type;
                        }    
                         $this->make->sRow();
                                        $this->make->td("TOTAL",array("style"=>"font-weight:bold"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td(num($total_based[$cur_card_type]['gross_fl_vat']) , array("style"=>"text-align:right;font-weight:bold"));
                                        $this->make->td(num($total_based[$cur_card_type]['fl_non_vatable_total']), array("style"=>"text-align:right;font-weight:bold"));
                                        $this->make->td(num($total_based[$cur_card_type]['gross_wl_vat']), array("style"=>"text-align:right;font-weight:bold"));
                                        $this->make->td(num($total_based[$cur_card_type]['wl_non_vatable_total']), array("style"=>"text-align:right;font-weight:bold"));

                                        $this->make->td(num($total_based[$cur_card_type]['service_charge']), array("style"=>"text-align:right;font-weight:bold"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        $this->make->td(num($total_based[$cur_card_type]['gross_vat']), array("style"=>"text-align:right;font-weight:bold"));
                                        $this->make->td("", array("style"=>"text-align:right"));
                                        if(!empty($receipt_discounts)){
                                            foreach($receipt_discounts as $disc_id => $receipt_disc_sales){
                                                if(isset($total_based['discs'][$disc_id])){

                                                    $this->make->td('('.num($total_based[$cur_card_type]['discs'][$disc_id]).')', array("style"=>"text-align:right;font-weight:bold"));
                                                    
                                                }else{
                                                    $this->make->td(0, array("style"=>"text-align:right;font-weight:bold"));

                                                }

                                               

                                            }

                                        }
                                        $this->make->td(num($total_based[$cur_card_type]['gross_total']), array("style"=>"text-align:right;font-weight:bold"));
                                      
                         $this->make->eRow();
                    }




                      $this->make->sRow();
                            $this->make->td("&nbsp;",array("style"=>"font-weight:bold"));
                            $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td("" , array("style"=>"text-align:right"));
                            $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td("", array("style"=>"text-align:right"));

                            $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td("", array("style"=>"text-align:right"));
                            $this->make->td("", array("style"=>"text-align:right"));
                            if(!empty($receipt_discounts)){
                                foreach($receipt_discounts as $disc_id => $receipt_disc_sales){
                                    if(isset($total_based['discs'][$disc_id])){

                                        $this->make->td("", array("style"=>"text-align:right"));
                                                
                                    }else{
                                        $this->make->td("", array("style"=>"text-align:right"));

                                   }
                                          
                                }

                             }
                            $this->make->td("", array("style"=>"text-align:right"));
                                  
                     $this->make->eRow();
                      $this->make->sRow();
                                    $this->make->td("GRAND TOTAL",array("style"=>"font-weight:bold"));
                                    $this->make->td("", array("style"=>"text-align:right"));
                                    $this->make->td("", array("style"=>"text-align:right"));
                                    $this->make->td(num($grand_total['gross_fl_vat']) , array("style"=>"text-align:right;font-weight:bold"));
                                    $this->make->td(num($grand_total['fl_non_vatable_total']), array("style"=>"text-align:right;font-weight:bold"));
                                    $this->make->td(num($grand_total['gross_wl_vat']), array("style"=>"text-align:right;font-weight:bold"));
                                    $this->make->td(num($grand_total['wl_non_vatable_total']), array("style"=>"text-align:right;font-weight:bold"));

                                    $this->make->td(num($grand_total['service_charge']), array("style"=>"text-align:right;font-weight:bold"));
                                    $this->make->td("", array("style"=>"text-align:right"));
                                    $this->make->td(num($grand_total['gross_vat']), array("style"=>"text-align:right;font-weight:bold"));
                                    $this->make->td("", array("style"=>"text-align:right"));
                                    if(!empty($receipt_discounts)){
                                        foreach($receipt_discounts as $disc_id => $receipt_disc_sales){
                                            if(isset($grand_total['discs'][$disc_id])){

                                                $this->make->td('('.num($grand_total['discs'][$disc_id]).')', array("style"=>"text-align:right;font-weight:bold"));
                                                
                                            }else{
                                                $this->make->td(0, array("style"=>"text-align:right;font-weight:bold"));

                                            }

                                           

                                        }

                                    }
                                    $this->make->td(num($grand_total['gross_total']), array("style"=>"text-align:right;font-weight:bold"));
                                  
                     $this->make->eRow();
                      
                        
                $this->make->eTableBody();
            $this->make->eTable();
        $this->make->eDiv();        
        
        update_load(80);

        $details = $this->setup_model->get_branch_details();
        $code = $this->make->code();
        $json['code'] = $code;        
        $json['dates'] = $this->input->post('date');
        $json['cashier_name'] = $this->reports_model->get_user($this->input->post('cashier'));
        $json['branch_name'] = $details[0]->branch_name;
        $json['address'] = $details[0]->address;
        echo json_encode($json);
        update_load(100);
    }

    public function ejournal_rep(){
        $data = $this->syter->spawn('menu_sales_rep');
        $data['page_title'] = fa('icon-book-open')." BIR E-Sales Report";
        $data['code'] = esalesRep();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['page_no_padding'] = true;
        $data['sideBarHide'] = true;
        $data['load_js'] = 'dine/reporting';
        $data['use_js'] = 'eSalesRepJS';
        $this->load->view('page',$data);
    }

    public function get_esales_reports(){
        ini_set('memory_limit', '-1');
        set_time_limit(3600);
        // sess_clear('month_array');
        // sess_clear('month_date');
        $this->load->helper('dine/reports_helper');
        // $month = $this->input->post('month');
        // $year = $this->input->post('year');
        // $json = $this->input->post('json');
        // $start_month = date($year.'-'.$month.'-01');
        // $end_month = date("Y-m-t", strtotime($start_month));
        // $month_date = array('text'=>sql2Date($start_month).' to '.sql2Date($end_month),'month_year'=>$start_month);
        update_load(10);

        $date = $this->input->post('calendar_range_2');
        // echo $date; die();
        // $user = $this->input->post('user');
        // $json = $this->input->post('json');

        $datesx = explode(" to ",$date);
        // $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
        // $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));
        $start_month = $datesx[0];
        $end_month = $datesx[1];

        // sleep(1);
        $load = 10;

        $details = $this->setup_model->get_branch_details();
            
        $open_time = $details[0]->store_open;
        $close_time = $details[0]->store_close;

        

        $this->make->sDiv();
            $this->make->sTable(array('class'=>'table reportTBL'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('Date');
                        $this->make->th('Registerno');
                        $this->make->th('Invoice Start');
                        $this->make->th('Invoice End');
                        $this->make->th('Total Sales');
                        $this->make->th('Vatable Sales');
                        $this->make->th('Vat Exempt');
                        $this->make->th('Zero Rated');
                        $this->make->th('12% VAT');
                    $this->make->eRow();
                $this->make->eTableHead();



        $month_array = array();
        while (strtotime($start_month) <= strtotime($end_month)) {
            // echo "$start_month\n";
            $post = $this->set_post(null,$start_month);
            // echo "<pre>",print_r($post['args']),"</pre>";die();
            $trans = $this->trans_sales($post['args']);
            $sales = $trans['sales'];
            $trans_menus = $this->menu_sales($sales['settled']['ids']);
            $trans_charges = $this->charges_sales($sales['settled']['ids']);
            $trans_discounts = $this->discounts_sales($sales['settled']['ids']);
            $tax_disc = $trans_discounts['tax_disc_total'];
            $no_tax_disc = $trans_discounts['no_tax_disc_total'];
            $trans_local_tax = $this->local_tax_sales($sales['settled']['ids']);
            $trans_tax = $this->tax_sales($sales['settled']['ids']);
            $trans_no_tax = $this->no_tax_sales($sales['settled']['ids']);
            $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids']);
            // $payments = $this->payment_sales($sales['settled']['ids']);
            $gross = $trans_menus['gross'];
            
            $net = $trans['net'];
            $void = $trans['void'];
            $charges = $trans_charges['total'];
            $discounts = $trans_discounts['total'];
            $local_tax = $trans_local_tax['total'];
            $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;
            if($less_vat < 0)
                $less_vat = 0;
            $tax = $trans_tax['total'];
            $no_tax = $trans_no_tax['total'];
            $zero_rated = $trans_zero_rated['total'];
            $no_tax -= $zero_rated;
            $loc_txt = numInt(($local_tax));
            $net_no_adds = $net-($charges+$local_tax);
            // $nontaxable = $no_tax;
            $nontaxable = $no_tax - $no_tax_disc;
            // $taxable =   ($gross - $discounts - $less_vat - $nontaxable) / 1.12;
            $taxable = ($gross - $less_vat - $nontaxable - $zero_rated - $discounts) / 1.12;
            $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
            $add_gt = $taxable+$nontaxable+$zero_rated;
            $nsss = $taxable +  $nontaxable +  $zero_rated;
            // $net_sales = $gross + $charges - $discounts - $less_vat;
            //pinapaalis ni sir yun charges sa net sales kagaya nun nasa zread 8 20 2018
            $net_sales = $gross - $discounts - $less_vat;
            // $final_gross = $gross;
            $vat_ = $taxable * .12;
            
            $pos_start = date2SqlDateTime($start_month." ".$open_time);
            $oa = date('a',strtotime($open_time));
            $ca = date('a',strtotime($close_time));
            $pos_end = date2SqlDateTime($start_month." ".$close_time);
            if($oa == $ca){
                $pos_end = date('Y-m-d H:i:s',strtotime($pos_end . "+1 days"));
            }

            // $gt = $this->old_grand_net_total($pos_start);

            $types = $trans_discounts['types'];
            // $qty = 0;
            $sndisc = 0;
            $pwdisc = 0;
            $othdisc = 0;
            foreach ($types as $code => $val) {
                $amount = $val['amount'];
                if($code == 'PWDISC'){
                    // $amount = $val['amount'] / 1.12;
                    $pwdisc = $val['amount'];
                }elseif($code == 'SNDISC'){
                    $sndisc = $val['amount'];
                }else{
                    $othdisc += $val['amount'];
                }
                // $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",PAPER_TOTAL_COL_1," ")
                //                      .append_chars('-'.Num($amount,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                // $qty += $val['qty'];
            }
            // echo $pwdisc; die();
            // $month_array[$start_month] = array(
            //     'cr_beg'=>iSetObj($trans['first_ref'],'trans_ref'),
            //     'cr_end'=>iSetObj($trans['last_ref'],'trans_ref'),
            //     'cr_count'=>$trans['ref_count'],
            //     'beg'=>$gt['old_grand_total'],
            //     'new'=>$gt['old_grand_total']+$net_no_adds,
            //     'ctr'=>$gt['ctr'],
            //     'vatsales'=>$taxable,
            //     'vatex'=>$nontaxable,
            //     'zero_rated'=>$zero_rated,
            //     'vat'=>$vat_,
            //     'net_sales'=>$net_sales,
            //     'pwdisc'=>$pwdisc,
            //     'sndisc'=>$sndisc,
            //     'othdisc'=>$othdisc,
            //     'lessvat'=>$less_vat,
            //     'gross'=>$gross,
            //     'charges'=>$charges,
            //     // 'senior'=>
            // );

            
                        $this->make->sTableBody();
                            // foreach ($menus as $res) {
                            $this->make->sRow();
                                $this->make->td(sql2Date($start_month));
                                $this->make->td('1');
                                $this->make->td(iSetObj($trans['first_ref'],'trans_ref'));
                                $this->make->td(iSetObj($trans['last_ref'],'trans_ref'));
                                $this->make->td(num($net_sales));
                                $this->make->td(num($taxable));
                                $this->make->td(num($nontaxable));
                                $this->make->td(num($zero_rated));
                                $this->make->td(num($vat_));
                                // $this->make->td($res['cost_price'] * $res['qty']);
                            $this->make->eRow();
                            // }    
                            // $this->make->sRow();
                            //     $this->make->th('');
                            //     $this->make->th('');
                            //     $this->make->th('');
                            //     $this->make->th('Total');
                            //     $this->make->th($total_qty);
                            //     $this->make->th('Total');
                            //     $this->make->th(num($menu_total));
                            //     $this->make->th('');
                            //     $this->make->th('');
                            //     $this->make->th('');
                            // $this->make->eRow();             
                        $this->make->eTableBody();

            $load += 2;
            update_load($load);
            // sleep(1);
            $start_month = date("Y-m-d", strtotime("+1 day", strtotime($start_month)));
        }
            $this->make->eTable();
        $this->make->eDiv();
        // var_dump($month_array);
        // die();
        // $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
        // $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
        update_load(90);
        // sleep(1);
        // $this->session->set_userdata('month_array',$month_array);
        // $this->session->set_userdata('month_date',$month_date);
        // //diretso excel na
        // $this->load->library('Excel');
        // $sheet = $this->excel->getActiveSheet();
        // $sheet->getCell('A1')->setValue('Point One Integrated Solutions Inc.');
        update_load(100);
        $code = $this->make->code();
        $json['code'] = $code;
        // $json['tbl_vals'] = $menus;
        // $json['dates'] = $this->input->post('calendar_range');
        echo json_encode($json);
        
    }

    public function e_sales_excel(){
        //diretso excel na
        ini_set('memory_limit', '-1');
        set_time_limit(3600);
        // sess_clear('month_array');
        // sess_clear('month_date');
        // $this->load->helper('dine/reports_helper');
        // $month = $this->input->post('month');
        // $year = $this->input->post('year');
        // $json = $this->input->post('json');
        // $start_month = date($year.'-'.$month.'-01');
        // $end_month = date("Y-m-t", strtotime($start_month));
        // $month_date = array('text'=>sql2Date($start_month).' to '.sql2Date($end_month),'month_year'=>$start_month);
        // update_load(10);
        // $date = $this->set_post($_GET['calendar_range']);
        $date = $this->input->get('calendar_range_2');
        // $date = $this->input->post('calendar_range_2');
        // echo $date; die();
        // $user = $this->input->post('user');
        // $json = $this->input->post('json');

        $datesx = explode(" to ",$date);
        // $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
        // $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));
        $start_month = $datesx[0];
        $end_month = $datesx[1];

        // sleep(1);
        $load = 10;

        $details = $this->setup_model->get_branch_details();
            
        $open_time = $details[0]->store_open;
        $close_time = $details[0]->store_close;


        
        $this->load->library('Excel');
        // $month_array = $this->session->userData('month_array');
        // $month_date = $this->session->userData('month_date');
        $sheet = $this->excel->getActiveSheet();
        $branch_details = $this->setup_model->get_branch_details();
        $branch = array();
        // echo "<pre>",print_r($month_array),"</pre>";die();
        foreach ($branch_details as $bv) {
            $branch = array(
                'id' => $bv->branch_id,
                'res_id' => $bv->res_id,
                'branch_code' => $bv->branch_code,
                'name' => $bv->branch_name,
                'branch_desc' => $bv->branch_desc,
                'contact_no' => $bv->contact_no,
                'delivery_no' => $bv->delivery_no,
                'address' => $bv->address,
                'base_location' => $bv->base_location,
                'currency' => $bv->currency,
                'inactive' => $bv->inactive,
                'tin' => $bv->tin,
                'machine_no' => $bv->machine_no,
                'bir' => $bv->bir,
                'permit_no' => $bv->permit_no,
                'serial' => $bv->serial,
                'accrdn' => $bv->accrdn,
                'email' => $bv->email,
                'website' => $bv->website,
                'store_open' => $bv->store_open,
                'store_close' => $bv->store_close,
            );
        }
        $sheet->mergeCells('A1:D1');
        $sheet->getCell('A1')->setValue('BIR E-Sales Report');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getCell('A2')->setValue('Print Datetime');
        $sheet->getCell('B2')->setValue(date('m/d/Y H:i:s A'));

        $sheet->mergeCells('E1:I1');
        $sheet->getCell('E1')->setValue('Condition Between');
        $sheet->mergeCells('E2:I2');
        $sheet->getCell('E2')->setValue($date);

        // $sheet->setCellValueExplicit('A3', 'TIN #'.$branch['tin'], PHPExcel_Cell_DataType::TYPE_STRING);
        // $sheet->setCellValueExplicit('A4', 'ACCRDN #'.$branch['accrdn'], PHPExcel_Cell_DataType::TYPE_STRING);
        // $sheet->setCellValueExplicit('A5', 'PERMIT #'.$branch['permit_no'], PHPExcel_Cell_DataType::TYPE_STRING);
        // $sheet->setCellValueExplicit('A6', 'SN #'.$branch['serial'], PHPExcel_Cell_DataType::TYPE_STRING);
        // $sheet->getCell('A7')->setValue($branch['machine_no']);
        // $sheet->setCellValueExplicit('A7', 'MIN #'.$branch['machine_no'], PHPExcel_Cell_DataType::TYPE_STRING);
        // $sheet->getCell('A8')->setValue('Monthly Sales Report');
        // $sheet->getCell('A9')->setValue($month_date['text']);
        $rn = 4;
        // $sheet->mergeCells('A10:A11');
        $sheet->getCell('A'.$rn)->setValue('Date');
        $sheet->getCell('B'.$rn)->setValue('Registerno');
        $sheet->getCell('C'.$rn)->setValue('Invoice Start');
        $sheet->getCell('D'.$rn)->setValue('Invoice End');
        $sheet->getCell('E'.$rn)->setValue('Total Sales');
        $sheet->getCell('F'.$rn)->setValue('Vatable Sales');
        $sheet->getCell('G'.$rn)->setValue('Vat Exempt Sales');
        $sheet->getCell('H'.$rn)->setValue('Zero Rated');
        $sheet->getCell('I'.$rn)->setValue('12% VAT');
        $sheet->getStyle("A".$rn.":I".$rn)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $sheet->getStyle('A'.$rn.':'.'I'.$rn)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        // $sheet->getStyle('A'.$rn.':'.'R11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        // $sheet->getStyle('A'.$rn.':'.'R11')->getFill()->getStartColor()->setRGB('29bb04');
        $sheet->getStyle('A'.$rn.':I'.$rn)->getFont()->setBold(true);
        $rn = 5;
        $s_total_sales = $s_total_vs = $s_total_ve = $s_total_zr = $s_total_vat = 0;
        while (strtotime($start_month) <= strtotime($end_month)) {
            // echo "$start_month\n";
            $post = $this->set_post(null,$start_month);
            // echo "<pre>",print_r($post['args']),"</pre>";die();
            $trans = $this->trans_sales($post['args']);
            $sales = $trans['sales'];
            $trans_menus = $this->menu_sales($sales['settled']['ids']);
            $trans_charges = $this->charges_sales($sales['settled']['ids']);
            $trans_discounts = $this->discounts_sales($sales['settled']['ids']);
            $tax_disc = $trans_discounts['tax_disc_total'];
            $no_tax_disc = $trans_discounts['no_tax_disc_total'];
            $trans_local_tax = $this->local_tax_sales($sales['settled']['ids']);
            $trans_tax = $this->tax_sales($sales['settled']['ids']);
            $trans_no_tax = $this->no_tax_sales($sales['settled']['ids']);
            $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids']);
            // $payments = $this->payment_sales($sales['settled']['ids']);
            $gross = $trans_menus['gross'];
            
            $net = $trans['net'];
            $void = $trans['void'];
            $charges = $trans_charges['total'];
            $discounts = $trans_discounts['total'];
            $local_tax = $trans_local_tax['total'];
            $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;
            if($less_vat < 0)
                $less_vat = 0;
            $tax = $trans_tax['total'];
            $no_tax = $trans_no_tax['total'];
            $zero_rated = $trans_zero_rated['total'];
            $no_tax -= $zero_rated;
            $loc_txt = numInt(($local_tax));
            $net_no_adds = $net-($charges+$local_tax);
            // $nontaxable = $no_tax;
            $nontaxable = $no_tax - $no_tax_disc;
            // $taxable =   ($gross - $discounts - $less_vat - $nontaxable) / 1.12;
            $taxable = ($gross - $less_vat - $nontaxable - $zero_rated - $discounts) / 1.12;
            $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
            $add_gt = $taxable+$nontaxable+$zero_rated;
            $nsss = $taxable +  $nontaxable +  $zero_rated;
            // $net_sales = $gross + $charges - $discounts - $less_vat;
            //pinapaalis ni sir yun charges sa net sales kagaya nun nasa zread 8 20 2018
            $net_sales = $gross - $discounts - $less_vat;
            // $final_gross = $gross;
            $vat_ = $taxable * .12;
            
            $pos_start = date2SqlDateTime($start_month." ".$open_time);
            $oa = date('a',strtotime($open_time));
            $ca = date('a',strtotime($close_time));
            $pos_end = date2SqlDateTime($start_month." ".$close_time);
            if($oa == $ca){
                $pos_end = date('Y-m-d H:i:s',strtotime($pos_end . "+1 days"));
            }

            // $gt = $this->old_grand_net_total($pos_start);

            $types = $trans_discounts['types'];
            // $qty = 0;
            $sndisc = 0;
            $pwdisc = 0;
            $othdisc = 0;
            foreach ($types as $code => $val) {
                $amount = $val['amount'];
                if($code == 'PWDISC'){
                    // $amount = $val['amount'] / 1.12;
                    $pwdisc = $val['amount'];
                }elseif($code == 'SNDISC'){
                    $sndisc = $val['amount'];
                }else{
                    $othdisc += $val['amount'];
                }
                // $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",PAPER_TOTAL_COL_1," ")
                //                      .append_chars('-'.Num($amount,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                // $qty += $val['qty'];
            }
            // echo $pwdisc; die();
            // $month_array[$start_month] = array(
            //     'cr_beg'=>iSetObj($trans['first_ref'],'trans_ref'),
            //     'cr_end'=>iSetObj($trans['last_ref'],'trans_ref'),
            //     'cr_count'=>$trans['ref_count'],
            //     'beg'=>$gt['old_grand_total'],
            //     'new'=>$gt['old_grand_total']+$net_no_adds,
            //     'ctr'=>$gt['ctr'],
            //     'vatsales'=>$taxable,
            //     'vatex'=>$nontaxable,
            //     'zero_rated'=>$zero_rated,
            //     'vat'=>$vat_,
            //     'net_sales'=>$net_sales,
            //     'pwdisc'=>$pwdisc,
            //     'sndisc'=>$sndisc,
            //     'othdisc'=>$othdisc,
            //     'lessvat'=>$less_vat,
            //     'gross'=>$gross,
            //     'charges'=>$charges,
            //     // 'senior'=>
            // );

            $sheet->getCell('A'.$rn)->setValue(sql2Date($start_month));
            $sheet->getCell('B'.$rn)->setValue(1);
            $sheet->getCell('C'.$rn)->setValue(iSetObj($trans['first_ref'],'trans_ref'));
            $sheet->getCell('D'.$rn)->setValue(iSetObj($trans['last_ref'],'trans_ref'));
            $sheet->getStyle('E'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('E'.$rn)->setValue($net_sales);
            $sheet->getStyle('F'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('F'.$rn)->setValue($taxable);
            $sheet->getStyle('G'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('G'.$rn)->setValue($nontaxable);
            $sheet->getStyle('H'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('H'.$rn)->setValue($zero_rated);
            $sheet->getStyle('I'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $sheet->getCell('I'.$rn)->setValue($vat_);

            $s_total_sales += $net_sales;
            $s_total_vs += $taxable;
            $s_total_ve += $nontaxable; 
            $s_total_zr += $zero_rated; 
            $s_total_vat += $vat_;
                            

            $load += 2;
            $rn++;
            update_load($load);
            // sleep(1);
            $start_month = date("Y-m-d", strtotime("+1 day", strtotime($start_month)));
        }

        $rn++;
        $sheet->getStyle('A'.$rn.':I'.$rn)->getFont()->setBold(true);
        $sheet->getCell('D'.$rn)->setValue('Summary');
        $sheet->getStyle('E'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->getCell('E'.$rn)->setValue($s_total_sales);
        $sheet->getStyle('F'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->getCell('F'.$rn)->setValue($s_total_vs);
        $sheet->getStyle('G'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->getCell('G'.$rn)->setValue($s_total_ve);
        $sheet->getStyle('H'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->getCell('H'.$rn)->setValue($s_total_zr);
        $sheet->getStyle('I'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->getCell('I'.$rn)->setValue($s_total_vat);

        $rn++;
        $rn++;
        $sheet->getStyle('A'.$rn.':I'.$rn)->getFont()->setBold(true);
        $sheet->getCell('D'.$rn)->setValue('Grand Total');
        $sheet->getStyle('E'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->getCell('E'.$rn)->setValue($s_total_sales);
        $sheet->getStyle('F'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->getCell('F'.$rn)->setValue($s_total_vs);
        $sheet->getStyle('G'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->getCell('G'.$rn)->setValue($s_total_ve);
        $sheet->getStyle('H'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->getCell('H'.$rn)->setValue($s_total_zr);
        $sheet->getStyle('I'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->getCell('I'.$rn)->setValue($s_total_vat);

        if (ob_get_contents()) 
            ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=esales.xls');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public function esales_pdf(){
        //diretso excel na
        ini_set('memory_limit', '-1');
        set_time_limit(3600);
        

        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('E-Sales Report');
        $pdf->SetSubject('');
        $pdf->SetKeywords('');

        // set default header data
        // $setup = $this->setup_model->get_details(1);
        // $set = $setup[0];
        // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $set->branch_name, $set->address);

        // // set header and footer fonts
        // $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        // $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // // set default monospaced font
        // $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // // set margins
        // $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // // set auto page breaks
        // $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // // set image scale factor
        // $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // // set some language-dependent strings (optional)
        // if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        //     require_once(dirname(__FILE__).'/lang/eng.php');
        //     $pdf->setLanguageArray($l);
        // }


        // update_load(10);
        // $date = $this->set_post($_GET['calendar_range']);
        $date = $this->input->get('calendar_range_2');
        // $date = $this->input->post('calendar_range_2');
        // echo $date; die();
        // $user = $this->input->post('user');
        // $json = $this->input->post('json');

        $datesx = explode(" to ",$date);
        // $date_from = (empty($dates[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[0])));
        // $date_to = (empty($dates[1]) ? date('Y-m-d') : date('Y-m-d',strtotime($dates[1])));
        $start_month = $datesx[0];
        $end_month = $datesx[1];


        $details = $this->setup_model->get_branch_details();
            
        $open_time = $details[0]->store_open;
        $close_time = $details[0]->store_close;

        // set font
        $pdf->SetFont('helvetica', 'B', 12);

        // add a page
        $pdf->AddPage();

        $pdf->ln(2);
        $pdf->SetFont('helvetica', 'B', 27);
        $pdf->cell(150,0,'BIR E-Sales Report',0,0,'L');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->cell(100,0,'Condition Between '.$date,0,0,'L');
        $pdf->ln(11);
        $pdf->cell(150,0,'Print Datetime '.date('m/d/Y H:i:s A'),0,0,'L');

        $pdf->ln(13);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->cell(30,0,'Date','B',0,'L');
        $pdf->cell(30,0,'RegisterNo','B',0,'L');
        $pdf->cell(30,0,'Invoice Start','B',0,'R');
        $pdf->cell(30,0,'Invoice End','B',0,'C');
        $pdf->cell(30,0,'Total Sales','B',0,'R');
        $pdf->cell(30,0,'Vatable Sales','B',0,'R');
        $pdf->cell(30,0,'Vat Exempt','B',0,'R');
        $pdf->cell(30,0,'Zero Rated','B',0,'R');
        $pdf->cell(30,0,'12% VAT','B',0,'R');
        $pdf->ln(6);
        $pdf->cell(45,0,'',0,0,'L');
        $pdf->cell(30,0,'RegisterNo : 1',0,0,'L');
        $pdf->ln(6);

        $s_total_sales = $s_total_vs = $s_total_ve = $s_total_zr = $s_total_vat = 0;
        while (strtotime($start_month) <= strtotime($end_month)) {
            // echo "$start_month\n";
            $post = $this->set_post(null,$start_month);
            // echo "<pre>",print_r($post['args']),"</pre>";die();
            $trans = $this->trans_sales($post['args']);
            $sales = $trans['sales'];
            $trans_menus = $this->menu_sales($sales['settled']['ids']);
            $trans_charges = $this->charges_sales($sales['settled']['ids']);
            $trans_discounts = $this->discounts_sales($sales['settled']['ids']);
            $tax_disc = $trans_discounts['tax_disc_total'];
            $no_tax_disc = $trans_discounts['no_tax_disc_total'];
            $trans_local_tax = $this->local_tax_sales($sales['settled']['ids']);
            $trans_tax = $this->tax_sales($sales['settled']['ids']);
            $trans_no_tax = $this->no_tax_sales($sales['settled']['ids']);
            $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids']);
            // $payments = $this->payment_sales($sales['settled']['ids']);
            $gross = $trans_menus['gross'];
            
            $net = $trans['net'];
            $void = $trans['void'];
            $charges = $trans_charges['total'];
            $discounts = $trans_discounts['total'];
            $local_tax = $trans_local_tax['total'];
            $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;
            if($less_vat < 0)
                $less_vat = 0;
            $tax = $trans_tax['total'];
            $no_tax = $trans_no_tax['total'];
            $zero_rated = $trans_zero_rated['total'];
            $no_tax -= $zero_rated;
            $loc_txt = numInt(($local_tax));
            $net_no_adds = $net-($charges+$local_tax);
            // $nontaxable = $no_tax;
            $nontaxable = $no_tax - $no_tax_disc;
            // $taxable =   ($gross - $discounts - $less_vat - $nontaxable) / 1.12;
            $taxable = ($gross - $less_vat - $nontaxable - $zero_rated - $discounts) / 1.12;
            $total_net = ($taxable) + ($nontaxable+$zero_rated) + $tax + $local_tax;
            $add_gt = $taxable+$nontaxable+$zero_rated;
            $nsss = $taxable +  $nontaxable +  $zero_rated;
            // $net_sales = $gross + $charges - $discounts - $less_vat;
            //pinapaalis ni sir yun charges sa net sales kagaya nun nasa zread 8 20 2018
            $net_sales = $gross - $discounts - $less_vat;
            // $final_gross = $gross;
            $vat_ = $taxable * .12;
            
            $pos_start = date2SqlDateTime($start_month." ".$open_time);
            $oa = date('a',strtotime($open_time));
            $ca = date('a',strtotime($close_time));
            $pos_end = date2SqlDateTime($start_month." ".$close_time);
            if($oa == $ca){
                $pos_end = date('Y-m-d H:i:s',strtotime($pos_end . "+1 days"));
            }

            // $gt = $this->old_grand_net_total($pos_start);

            $types = $trans_discounts['types'];
            // $qty = 0;
            $sndisc = 0;
            $pwdisc = 0;
            $othdisc = 0;
            foreach ($types as $code => $val) {
                $amount = $val['amount'];
                if($code == 'PWDISC'){
                    // $amount = $val['amount'] / 1.12;
                    $pwdisc = $val['amount'];
                }elseif($code == 'SNDISC'){
                    $sndisc = $val['amount'];
                }else{
                    $othdisc += $val['amount'];
                }
                // $print_str .= append_chars(substrwords(ucwords(strtolower($val['name'])),18,""),"right",PAPER_TOTAL_COL_1," ")
                //                      .append_chars('-'.Num($amount,2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                // $qty += $val['qty'];
            }
            
            // $pdf->ln(25);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->cell(30,0,sql2Date($start_month),0,0,'L');
            $pdf->cell(30,0,'1',0,0,'L');
            $pdf->cell(30,0,iSetObj($trans['first_ref'],'trans_ref'),0,0,'R');
            $pdf->cell(30,0,iSetObj($trans['last_ref'],'trans_ref'),0,0,'C');
            $pdf->cell(30,0,num($net_sales),0,0,'R');
            $pdf->cell(30,0,num($taxable),0,0,'R');
            $pdf->cell(30,0,num($nontaxable),0,0,'R');
            $pdf->cell(30,0,num($zero_rated),0,0,'R');
            $pdf->cell(30,0,num($vat_),0,0,'R');


            // $sheet->getCell('A'.$rn)->setValue($start_month);
            // $sheet->getCell('B'.$rn)->setValue(1);
            // $sheet->getCell('C'.$rn)->setValue(iSetObj($trans['first_ref'],'trans_ref'));
            // $sheet->getCell('D'.$rn)->setValue(iSetObj($trans['last_ref'],'trans_ref'));
            // $sheet->getStyle('E'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            // $sheet->getCell('E'.$rn)->setValue($net_sales);
            // $sheet->getStyle('F'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            // $sheet->getCell('F'.$rn)->setValue($taxable);
            // $sheet->getStyle('G'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            // $sheet->getCell('G'.$rn)->setValue($nontaxable);
            // $sheet->getStyle('H'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            // $sheet->getCell('H'.$rn)->setValue($zero_rated);
            // $sheet->getStyle('I'.$rn)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            // $sheet->getCell('I'.$rn)->setValue($vat_);

            $s_total_sales += $net_sales;
            $s_total_vs += $taxable;
            $s_total_ve += $nontaxable; 
            $s_total_zr += $zero_rated; 
            $s_total_vat += $vat_;
                            

            // $load += 2;
            // $rn++;
            // update_load($load);
            // sleep(1);
            $pdf->ln(6);
            $start_month = date("Y-m-d", strtotime("+1 day", strtotime($start_month)));
        }

        $pdf->ln(5);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->cell(30,0,'',0,0,'L');
        $pdf->cell(30,0,'',0,0,'L');
        $pdf->cell(30,0,'',0,0,'R');
        $pdf->cell(30,0,'Summary',0,0,'C');
        $pdf->cell(30,0,num($s_total_sales),0,0,'R');
        $pdf->cell(30,0,num($s_total_vs),0,0,'R');
        $pdf->cell(30,0,num($s_total_ve),0,0,'R');
        $pdf->cell(30,0,num($s_total_zr),0,0,'R');
        $pdf->cell(30,0,num($s_total_vat),0,0,'R');

        $pdf->ln(10);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->cell(30,0,'',0,0,'L');
        $pdf->cell(30,0,'',0,0,'L');
        $pdf->cell(30,0,'',0,0,'R');
        $pdf->cell(30,0,'Grand Total',0,0,'C');
        $pdf->cell(30,0,num($s_total_sales),0,0,'R');
        $pdf->cell(30,0,num($s_total_vs),0,0,'R');
        $pdf->cell(30,0,num($s_total_ve),0,0,'R');
        $pdf->cell(30,0,num($s_total_zr),0,0,'R');
        $pdf->cell(30,0,num($s_total_vat),0,0,'R');

        //Close and output PDF document
        $pdf->Output('sales_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+          
    }
    public function top_items_rep()
    {
        $data = $this->syter->spawn('top_items_rep');        
        $data['page_title'] = fa('fa-money')." Top Items Report";
        $data['code'] = TopItemsRep();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['page_no_padding'] = false;
        $data['sideBarHide'] = false;
        $data['load_js'] = 'dine/reporting';
        $data['use_js'] = 'topItemsRepJS';
        $this->load->view('page',$data);
    }

    public function top_items_rep_gen()
    {
        
        $user = $this->session->userdata('user');
        $time = $this->site_model->get_db_now();

        $daterange = $this->input->post('calendar_range');
        $dates = explode(" to ",$daterange);
        $from = date('Y-m-d H:i:s',strtotime($dates[0]));
        $to = date('Y-m-d H:i:s',strtotime($dates[1]));

        // echo $from." - ".$to; die();


        $select = 'trans_sales.*,menus.menu_code,menus.menu_id,menus.menu_name,menu_subcategory.menu_sub_name,menu_subcategories.menu_sub_cat_name,menu_categories.menu_cat_name,SUM(trans_sales_menus.qty) as qty_sold,SUM(trans_sales_menus.price * trans_sales_menus.qty) as sold_amount';
        $join = array();
        $join['trans_sales_menus'] = array('content'=>'trans_sales_menus.sales_id = trans_sales.sales_id and trans_sales_menus.pos_id = trans_sales.pos_id');
        $join['menus'] = array('content'=>'menus.menu_id = trans_sales_menus.menu_id');
        $join['menu_subcategory'] = array('content'=>'menu_subcategory.menu_sub_id = menus.menu_sub_id','mode'=>'left');
        $join['menu_categories'] = array('content'=>'menu_categories.menu_cat_id = menus.menu_cat_id');
        $join['menu_subcategories'] = array('content'=>'menu_subcategories.menu_sub_cat_id = menus.menu_sub_cat_id');

        $n_item_res = array();
        $args["trans_sales.terminal_id"] = TERMINAL_ID;
        $args["trans_sales.type_id"] = 10;
        $args["trans_sales.inactive"] = '0';
        $args["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
        $args['trans_sales.datetime >= '] = $from;
        $args['trans_sales.datetime <= '] = $to;
        $pgroup = 'menus.menu_id';
        $this->site_model->db= $this->load->database('main', TRUE);
        $details = $this->site_model->get_tbl('trans_sales',$args,array(),$join,true,$select,$pgroup);

        $select2 = 'menu_subcategories.menu_sub_cat_name';
        $join2 = array();
        $args2 = array();
        $this->site_model->db= $this->load->database('main', TRUE);
        $det2 = $this->site_model->get_tbl('menu_subcategories',$args2,array(),$join2,true,$select2);

        // $select3 = 'trans_sales_menu_modifiers.*,SUM(trans_sales_menu_modifiers.qty) as mod_qty_sold,SUM(trans_sales_menu_modifiers.price) as mod_sold_amount';
        $select3 = 'trans_sales_menu_modifiers.*,trans_sales_menu_modifiers.qty as mod_qty_sold,(trans_sales_menu_modifiers.price * trans_sales_menu_modifiers.qty) as mod_sold_amount';
        $join3 = array();
        $args3 = array();
        $this->site_model->db= $this->load->database('main', TRUE);
        $pgroup3 = null;
        $args3["trans_sales.terminal_id"] = TERMINAL_ID;
        $args3["trans_sales.type_id"] = 10;
        $args3["trans_sales.inactive"] = '0';
        $args3["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
        $args3['trans_sales.datetime >= '] = $from;
        $args3['trans_sales.datetime <= '] = $to;
        $join3['trans_sales'] = array('content'=>'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id and trans_sales.pos_id = trans_sales_menu_modifiers.pos_id');
        $det3 = $this->site_model->get_tbl('trans_sales_menu_modifiers',$args3,array(),$join3,true,$select3,$pgroup3);

        $select4 = 'trans_sales_menu_submodifiers.*,trans_sales_menu_submodifiers.qty as submod_qty_sold,(trans_sales_menu_submodifiers.price * trans_sales_menu_submodifiers.qty) as submod_sold_amount';
        $join4['trans_sales'] = array('content'=>'trans_sales.sales_id = trans_sales_menu_submodifiers.sales_id');
        $det4 = $this->site_model->get_tbl('trans_sales_menu_submodifiers',$args3,array(),$join4,true,$select4,$pgroup3);
        // echo $this->site_model->db->last_query(); die();
            // echo "<pre>", print_r($vv), "</pre>"; die();
        $det_array = array();
        foreach ($details as $vv) {
            $det_array[$vv->menu_sub_cat_name][] = array(
                    'menu_code'=>$vv->menu_code,
                    'menu_name'=>$vv->menu_name,
                    'category'=>$vv->menu_cat_name,
                    'subcategory'=>$vv->menu_sub_name,
                    'qty_sold'=>$vv->qty_sold,
                    'sold_amount'=>$vv->sold_amount,
                    'sales_id'=>$vv->sales_id,
                    'menu_id'=>$vv->menu_id,
                );
        }



        $det_arr = array();
        foreach ($det3 as $v) {
            if(isset($det_arr[$v->menu_id][$v->mod_id])){
                $row = $det_arr[$v->menu_id][$v->mod_id];
                $row['mod_qty_sold'] += $v->mod_qty_sold;
                $row['mod_sold_amount'] += $v->mod_sold_amount;

                $det_arr[$v->menu_id][$v->mod_id] = $row;

            }else{
                $det_arr[$v->menu_id][$v->mod_id] = array(
                    'sales_id'=>$v->sales_id,
                    'mod_name'=>$v->mod_name,
                    'menu_id'=>$v->menu_id,
                    'mod_id'=>$v->mod_id,
                    'mod_qty_sold'=>$v->mod_qty_sold,
                    'mod_sold_amount'=>$v->mod_sold_amount,
                );                
            }

        }

        $det_arr2 = array();
        foreach ($det4 as $subm) {
            if(isset($det_arr2[$subm->mod_id][$subm->sales_submod_id])){
                $row = $det_arr2[$subm->mod_id][$subm->sales_submod_id];
                $row['submod_qty_sold'] += $subm->submod_qty_sold;
                $row['submod_sold_amount'] += $subm->submod_sold_amount;

                $det_arr2[$subm->mod_id][$subm->sales_submod_id] = $row;

            }else{
                $det_arr2[$subm->mod_id][$subm->sales_submod_id] = array(
                    'sales_id'=>$subm->sales_id,
                    'submod_name'=>$subm->submod_name,
                    'mod_id'=>$subm->mod_id,
                    'submod_qty_sold'=>$subm->submod_qty_sold,
                    'submod_sold_amount'=>$subm->submod_sold_amount,
                );                
            }
        }
        // echo "<pre>", print_r($det_arr), "</pre>"; die();
        // die();

        $this->make->sDiv();
            $this->make->sTable(array("id"=>"main-tbl", 'class'=>'table reportTBL sortable'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('ITEM CODE');
                        $this->make->th('DESCRIPTION');
                        $this->make->th('CATEGORY');
                        $this->make->th('SUBCATEGORY');
                        $this->make->th('SOLD QTY');
                        $this->make->th('SOLD AMOUNT');
                        $this->make->th('INACTIVE');
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();
                    foreach ($det2 as $val2) {
                        // echo "<pre>", print_r($val2->menu_cat_name), "</pre>"; die();
                        foreach ($det_array as $menucat => $vals) {
                            $total_qty = 0;
                            $total_amt = 0;
                            if($val2->menu_sub_cat_name == $menucat){
                                $this->make->sRow();
                                $this->make->td($menucat);
                                $this->make->td('');                     
                                $this->make->td('');                     
                                $this->make->td('');                     
                                $this->make->td('');                     
                                $this->make->td('');                     
                                $this->make->td('');                     
                                $this->make->eRow();
            
                                $mod_total_qty = 0;
                                $mod_total_amt = 0;
                                foreach ($vals as $id => $vvv) {
                                    // echo "<pre>", print_r($vvv), "</pre>"; die();
                                    $this->make->sRow();
                                    $this->make->td($vvv['menu_code']);
                                    $this->make->td($vvv['menu_name']);
                                    $this->make->td($vvv['category']);
                                    $this->make->td($vvv['subcategory']);
                                    $this->make->td($vvv['qty_sold'], array("style"=>"text-align:right"));
                                    $this->make->td(num($vvv['sold_amount']), array("style"=>"text-align:right"));
                                    $this->make->td('');                     
                                    $this->make->eRow();
                                    foreach ($det_arr as $m_id => $m_val) {
                                        foreach ($m_val as $modid => $mod_val) {
                                        // echo "<pre>", print_r($mod_val), "</pre>"; die();
                                            if($vvv['menu_id'] == $m_id){
                                                $where = array('mod_id'=>$modid);
                                                $dd = $this->site_model->get_details($where,'modifiers');

                                                $this->make->sRow();
                                                $this->make->td($dd[0]->mod_code);
                                                $this->make->td($mod_val['mod_name']);
                                                $this->make->td($vvv['category']);
                                                $this->make->td($vvv['subcategory']);
                                                $this->make->td($mod_val['mod_qty_sold'], array("style"=>"text-align:right"));
                                                $this->make->td(num($mod_val['mod_sold_amount']), array("style"=>"text-align:right"));
                                                $this->make->td('');
                                                $this->make->eRow();
                                            $mod_total_qty += $mod_val['mod_qty_sold'];
                                            $mod_total_amt += $mod_val['mod_sold_amount'];

                                                foreach($det_arr2 as $mod_id => $submods){

                                                    if($mod_id == $modid){
                                                        foreach($submods as $submod){
                                                            $this->make->sRow();
                                                            $this->make->td($dd[0]->mod_code);
                                                            $this->make->td($submod['submod_name']);
                                                            $this->make->td($vvv['category']);
                                                            $this->make->td($vvv['subcategory']);
                                                            $this->make->td($submod['submod_qty_sold'], array("style"=>"text-align:right"));
                                                            $this->make->td(num($submod['submod_sold_amount']), array("style"=>"text-align:right"));
                                                            $this->make->td('');
                                                            $this->make->eRow();

                                                             $total_qty += $submod['submod_qty_sold'];
                                                            $total_amt += $submod['submod_sold_amount'];
                                                        }
                                                    }
                                                }
                                                   
                                                //     if($submod['mod_id'] == $mod_id){
                                                //         $this->make->sRow();
                                                //             $this->make->td($dd[0]->mod_code);
                                                //             $this->make->td($mod_val['mod_name']);
                                                //             $this->make->td($vvv['category']);
                                                //             $this->make->td($vvv['subcategory']);
                                                //             $this->make->td($mod_val['mod_qty_sold'], array("style"=>"text-align:right"));
                                                //             $this->make->td(num($mod_val['mod_sold_amount']), array("style"=>"text-align:right"));
                                                //             $this->make->td('');
                                                //             $this->make->eRow();
                                                //     }
                                                // }
                                            }
                                        }
                                    }
                                    $total_qty += $vvv['qty_sold'];
                                    $total_amt += $vvv['sold_amount'];

                                }
                            $this->make->sRow(array("style"=>""));
                                $this->make->td('');
                                $this->make->td('');
                                $this->make->td('');
                                $this->make->td('P', array("style"=>"text-align:right"));                           
                                $this->make->td(num($total_qty+$mod_total_qty), array("style"=>"text-align:right"));                       
                                $this->make->td(num($total_amt+$mod_total_amt), array("style"=>"text-align:right"));                            
                                $this->make->td('');
                                // $this->make->td('');
                            $this->make->eRow();
                            }
                        }
                    }
                $this->make->eTableBody();
            $this->make->eTable();
        $this->make->eDiv();

        update_load(100);
        $code = $this->make->code();
        $json['code'] = $code;        
        $json['tbl_vals'] = array();
        $json['dates'] = $this->input->post('calendar_range');
        echo json_encode($json);
    }

    public function top_items_excel(){
        date_default_timezone_set('Asia/Manila');
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $dates = explode(" to ",$_GET['calendar_range']);
        $ddate = sql2Date($dates[0]);
        $filename = 'Top Items Report '.$ddate;
        $rc=1;
        #GET VALUES
            start_load(0);
        $daterange = $_GET['calendar_range'];
        $dates = explode(" to ",$daterange);
        $from = date('Y-m-d H:i:s',strtotime($dates[0]));
        $to = date('Y-m-d H:i:s',strtotime($dates[1]));

        // echo $from." - ".$to; die();


        // $select = 'trans_sales.*,menus.menu_code,menus.menu_id,menus.menu_name,menu_subcategory.menu_sub_name,menu_subcategories.menu_sub_cat_name,menu_categories.menu_cat_name,SUM(trans_sales_menus.qty) as qty_sold,SUM(trans_sales_menus.price) as sold_amount';
        // $join = array();
        // $join['trans_sales_menus'] = array('content'=>'trans_sales_menus.sales_id = trans_sales.sales_id');
        // $join['menus'] = array('content'=>'menus.menu_id = trans_sales_menus.menu_id');
        // $join['menu_subcategory'] = array('content'=>'menu_subcategory.menu_sub_id = menus.menu_sub_id');
        // $join['menu_categories'] = array('content'=>'menu_categories.menu_cat_id = menus.menu_cat_id');
        // $join['menu_subcategories'] = array('content'=>'menu_subcategories.menu_sub_cat_id = menus.menu_sub_cat_id');

        // $n_item_res = array();
        // $args["trans_sales.type_id"] = 10;
        // $args["trans_sales.inactive"] = '0';
        // $args["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
        // $args['trans_sales.datetime >= '] = $from;
        // $args['trans_sales.datetime <= '] = $to;
        // $pgroup = 'menus.menu_code';
        // $this->site_model->db= $this->load->database('main', TRUE);
        // $details = $this->site_model->get_tbl('trans_sales',$args,array(),$join,true,$select,$pgroup);

        // $select2 = 'menu_subcategories.menu_sub_cat_name';
        // $join2 = array();
        // $args2 = array();
        // $this->site_model->db= $this->load->database('main', TRUE);
        // $det2 = $this->site_model->get_tbl('menu_subcategories',$args2,array(),$join2,true,$select2);

        // $select3 = 'trans_sales_menu_modifiers.*,SUM(trans_sales_menu_modifiers.qty) as mod_qty_sold,SUM(trans_sales_menu_modifiers.price) as mod_sold_amount';
        // $join3 = array();
        // $args3 = array();
        // $this->site_model->db= $this->load->database('main', TRUE);
        //  $pgroup3 = 'mod_id';
        // $det3 = $this->site_model->get_tbl('trans_sales_menu_modifiers',$args3,array(),$join3,true,$select3,$pgroup3);
        // $select3 = 'trans_sales_menu_modifiers.*';
        // $join3 = array();
        // $args3 = array();
        // $this->site_model->db= $this->load->database('main', TRUE);
        //  $pgroup3 = 'mod_id';
        // $det3 = $this->site_model->get_tbl('trans_sales_menu_modifiers',$args3,array(),$join3,true,$select3,$pgroup3);


        $select = 'trans_sales.*,menus.menu_code,menus.menu_id,menus.menu_name,menu_subcategory.menu_sub_name,menu_subcategories.menu_sub_cat_name,menu_categories.menu_cat_name,SUM(trans_sales_menus.qty) as qty_sold,SUM(trans_sales_menus.price * trans_sales_menus.qty) as sold_amount';
        $join = array();
        $join['trans_sales_menus'] = array('content'=>'trans_sales_menus.sales_id = trans_sales.sales_id');
        $join['menus'] = array('content'=>'menus.menu_id = trans_sales_menus.menu_id');
        $join['menu_subcategory'] = array('content'=>'menu_subcategory.menu_sub_id = menus.menu_sub_id','mode'=>'left');
        $join['menu_categories'] = array('content'=>'menu_categories.menu_cat_id = menus.menu_cat_id');
        $join['menu_subcategories'] = array('content'=>'menu_subcategories.menu_sub_cat_id = menus.menu_sub_cat_id');

        $n_item_res = array();
        $args["trans_sales.type_id"] = 10;
        $args["trans_sales.inactive"] = '0';
        $args["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
        $args['trans_sales.datetime >= '] = $from;
        $args['trans_sales.datetime <= '] = $to;
        $pgroup = 'menus.menu_id';
        $this->site_model->db= $this->load->database('main', TRUE);
        $details = $this->site_model->get_tbl('trans_sales',$args,array(),$join,true,$select,$pgroup);

        $select2 = 'menu_subcategories.menu_sub_cat_name';
        $join2 = array();
        $args2 = array();
        $this->site_model->db= $this->load->database('main', TRUE);
        $det2 = $this->site_model->get_tbl('menu_subcategories',$args2,array(),$join2,true,$select2);

        // $select3 = 'trans_sales_menu_modifiers.*,SUM(trans_sales_menu_modifiers.qty) as mod_qty_sold,SUM(trans_sales_menu_modifiers.price) as mod_sold_amount';
        $select3 = 'trans_sales_menu_modifiers.*,trans_sales_menu_modifiers.qty as mod_qty_sold,(trans_sales_menu_modifiers.price * trans_sales_menu_modifiers.qty) as mod_sold_amount';
        $join3 = array();
        $args3 = array();
        $this->site_model->db= $this->load->database('main', TRUE);
        $pgroup3 = null;
        $args3["trans_sales.type_id"] = 10;
        $args3["trans_sales.inactive"] = '0';
        $args3["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
        $args3['trans_sales.datetime >= '] = $from;
        $args3['trans_sales.datetime <= '] = $to;
        $join3['trans_sales'] = array('content'=>'trans_sales.sales_id = trans_sales_menu_modifiers.sales_id');
        $det3 = $this->site_model->get_tbl('trans_sales_menu_modifiers',$args3,array(),$join3,true,$select3,$pgroup3);

        $select4 = 'trans_sales_menu_submodifiers.*,trans_sales_menu_submodifiers.qty as submod_qty_sold,(trans_sales_menu_submodifiers.price * trans_sales_menu_submodifiers.qty) as submod_sold_amount';
        $join4['trans_sales'] = array('content'=>'trans_sales.sales_id = trans_sales_menu_submodifiers.sales_id');
        $det4 = $this->site_model->get_tbl('trans_sales_menu_submodifiers',$args3,array(),$join4,true,$select4,$pgroup3);


        update_load(10);

        update_load(30);

        $det_array = array();
        foreach ($details as $vv) {
            // echo "<pre>", print_r($vv), "</pre>"; die();
            $det_array[$vv->menu_sub_cat_name][] = array(
                    'menu_code'=>$vv->menu_code,
                    'menu_name'=>$vv->menu_name,
                    'category'=>$vv->menu_cat_name,
                    'subcategory'=>$vv->menu_sub_name,
                    'qty_sold'=>$vv->qty_sold,
                    'sold_amount'=>$vv->sold_amount,
                    'sales_id'=>$vv->sales_id,
                    'menu_id'=>$vv->menu_id,
                );
        }
        $det_arr = array();
        foreach ($det3 as $v) {
            if(isset($det_arr[$v->menu_id][$v->mod_id])){
                $row = $det_arr[$v->menu_id][$v->mod_id];
                $row['mod_qty_sold'] += $v->mod_qty_sold;
                $row['mod_sold_amount'] += $v->mod_sold_amount;

                $det_arr[$v->menu_id][$v->mod_id] = $row;

            }else{
                $det_arr[$v->menu_id][$v->mod_id] = array(
                    'sales_id'=>$v->sales_id,
                    'mod_name'=>$v->mod_name,
                    'menu_id'=>$v->menu_id,
                    'mod_id'=>$v->mod_id,
                    'mod_qty_sold'=>$v->mod_qty_sold,
                    'mod_sold_amount'=>$v->mod_sold_amount,
                );                
            }

        }

        $det_arr2 = array();
        foreach ($det4 as $subm) {
            if(isset($det_arr2[$subm->mod_id][$subm->sales_submod_id])){
                $row = $det_arr2[$subm->mod_id][$subm->sales_submod_id];
                $row['submod_qty_sold'] += $subm->submod_qty_sold;
                $row['submod_sold_amount'] += $subm->submod_sold_amount;

                $det_arr2[$subm->mod_id][$subm->sales_submod_id] = $row;

            }else{
                $det_arr2[$subm->mod_id][$subm->sales_submod_id] = array(
                    'sales_id'=>$subm->sales_id,
                    'submod_name'=>$subm->submod_name,
                    'mod_id'=>$subm->mod_id,
                    'submod_qty_sold'=>$subm->submod_qty_sold,
                    'submod_sold_amount'=>$subm->submod_sold_amount,
                );                
            }
        }

        update_load(70);

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
                'size' => 14,
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
        $styleTitle = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'size' => 20,
            )
        );
        $styleNumC = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'f5f755')
            ),
        );
        $styleTxtC = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'font' => array(
                'bold' => true,
                // 'size' => 20,
            )
            // 'fill' => array(
            //     'type' => PHPExcel_Style_Fill::FILL_SOLID,
            //     'color' => array('rgb' => 'f5f755')
            // ),
        );
        
        $headers = array('ITEM CODE','DESCRIPTION','CATEGORY','SUBCATEGORY','SOLD QTY','SOLD AMOUNT','INACTIVE');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(50);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(30);
        // $sheet->getColumnDimension('I')->setWidth(20);


        $sheet->mergeCells('A'.$rc.':C'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Top Items Report');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;
        
        $dates = explode(" to ",$_GET['calendar_range']);
        $from = sql2DateTime($dates[0]);
        $to = sql2DateTime($dates[1]);
        $sheet->getCell('A'.$rc)->setValue('Date From: '.$from);
        $sheet->mergeCells('A'.$rc.':C'.$rc);
        $rc++;

        $sheet->getCell('A'.$rc)->setValue('Date To: '.$to);
        $sheet->mergeCells('A'.$rc.':C'.$rc);
        $rc++;

        $sheet->getCell('A'.$rc)->setValue('Print Date : '.date('m/d/Y h:i:s A'));
        $sheet->mergeCells('A'.$rc.':C'.$rc);
        $rc++;
        $rc++;
        $rc++;

        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleTxt);
            $col++;
        }

        $rc++;
        $rc++;

        $tqty = 0;
        $tamount = 0;
        foreach ($det2 as $val2) {
            foreach ($det_array as $menucat => $vals) {
                $total_qty = 0;
                $total_amt = 0;
                if($val2->menu_sub_cat_name == $menucat){
                    $sheet->getCell('A'.$rc)->setValue($menucat);
                    $sheet->getStyle('A'.$rc)->applyFromArray($styleTxtC);
                    $rc++;
                    $mod_total_qty = 0;
                    $mod_total_amt = 0;
                    foreach ($vals as $id => $vvv) {
                        $sheet->getCell('A'.$rc)->setValue($vvv['menu_code']);
                        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                        $sheet->getCell('B'.$rc)->setValue($vvv['menu_name']);
                        $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
                        $sheet->getCell('C'.$rc)->setValue($vvv['category']);
                        $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);
                        $sheet->getCell('D'.$rc)->setValue($vvv['subcategory']);
                        $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
                        $sheet->getCell('E'.$rc)->setValue(num($vvv['qty_sold']));
                        $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
                        $sheet->getCell('F'.$rc)->setValue(num($vvv['sold_amount']));
                        $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
                        $sheet->getCell('G'.$rc)->setValue('');
                        $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
                        $rc++;
                        foreach ($det_arr as $m_id => $m_val) {
                            foreach ($m_val as $modid => $mod_val) {
                                // $sheet->getCell('A'.$rc)->setValue($vvv['menu_code']);
                                // $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                                if($vvv['menu_id'] == $m_id){
                                    $where = array('mod_id'=>$modid);
                                    $dd = $this->site_model->get_details($where,'modifiers');
                                    $sheet->getCell('A'.$rc)->setValue($dd[0]->mod_code);
                                    $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                                    $sheet->getCell('B'.$rc)->setValue($mod_val['mod_name']);
                                    $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
                                    $sheet->getCell('C'.$rc)->setValue($vvv['category']);
                                    $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);
                                    $sheet->getCell('D'.$rc)->setValue($vvv['subcategory']);
                                    $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
                                    $sheet->getCell('E'.$rc)->setValue(num($mod_val['mod_qty_sold']));
                                    $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
                                    $sheet->getCell('F'.$rc)->setValue(num($mod_val['mod_sold_amount']));
                                    $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
                                    $mod_total_qty += $mod_val['mod_qty_sold'];
                                    $mod_total_amt += $mod_val['mod_sold_amount'];
                                    $rc++;

                                    foreach($det_arr2 as $mod_id => $submods){

                                        if($mod_id == $modid){
                                            foreach($submods as $submod){
                                                $this->make->sRow();
                                                $this->make->td($dd[0]->mod_code);
                                                $this->make->td($submod['submod_name']);
                                                $this->make->td($vvv['category']);
                                                $this->make->td($vvv['subcategory']);
                                                $this->make->td($submod['submod_qty_sold'], array("style"=>"text-align:right"));
                                                $this->make->td(num($submod['submod_sold_amount']), array("style"=>"text-align:right"));
                                                $this->make->td('');
                                                $this->make->eRow();

                                                 $sheet->getCell('A'.$rc)->setValue($dd[0]->mod_code);
                                                $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                                                $sheet->getCell('B'.$rc)->setValue($submod['submod_name']);
                                                $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
                                                $sheet->getCell('C'.$rc)->setValue($vvv['category']);
                                                $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);
                                                $sheet->getCell('D'.$rc)->setValue($vvv['subcategory']);
                                                $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
                                                $sheet->getCell('E'.$rc)->setValue(num($submod['submod_qty_sold']));
                                                $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
                                                $sheet->getCell('F'.$rc)->setValue(num($submod['submod_sold_amount']));
                                                $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
                                                $mod_total_qty += $submod['submod_qty_sold'];
                                                $mod_total_amt += $submod['submod_sold_amount'];
                                                $rc++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                            $total_qty += $vvv['qty_sold'];
                            $total_amt += $vvv['sold_amount'];
                            
                    }
                        // $sheet->getCell('C'.$rc)->setValue('SUBTOTAL');
                        // $sheet->getStyle('C'.$rc)->applyFromArray($styleTxtC);
                        $sheet->getCell('E'.$rc)->setValue(num($total_qty+$mod_total_qty));
                        $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
                        $sheet->getCell('F'.$rc)->setValue(num($total_amt+$mod_total_amt));
                        $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
                     $rc++;
                     $tqty += $total_qty; 
                     $tamount += $total_amt; 

                     $mtqty += $mod_total_qty; 
                     $mtamount += $mod_total_amt;
                }
            }
        }
                $sheet->getCell('F2')->setValue('Total Sold Qty :');
                $sheet->getStyle('F2')->applyFromArray($styleNum);
                $sheet->getCell('F3')->setValue('Total Sold Amount :');
                $sheet->getStyle('F3')->applyFromArray($styleNum);

                $sheet->getCell('G2')->setValue(num($tqty+$mtqty));
                $sheet->getStyle('G2')->applyFromArray($styleNum);
                $sheet->getCell('G3')->setValue(num($tamount+$mtamount));
                $sheet->getStyle('G3')->applyFromArray($styleNum);
            // $sheet->getCell('A'.$rc)->setValue(RECEIPT_ADDITIONAL_HEADER_BELOW_BRANCH);
            // $sheet->getStyle('A'.$rc)->applyFromArray($styleTxtC);
        //     $rc++;
        //     $total_qty = 0;
        //     $total_amt = 0;

        //     foreach ($vals as $id => $vvv) {
        //         $sheet->setCellValueExplicit('A'.$rc, $vvv['trans_ref'], PHPExcel_Cell_DataType::TYPE_STRING);
        //         // $sheet->getCell('A'.$rc)->setValue('`'.$vvv['trans_ref']);
        //         // $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //         $sheet->getCell('B'.$rc)->setValue($vvv['menu_code']);
        //         $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
        //         $sheet->getCell('C'.$rc)->setValue($vvv['menu_name']);
        //         $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);
        //         $sheet->getCell('D'.$rc)->setValue(num($vvv['qty']));
        //         $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('E'.$rc)->setValue(num($vvv['orig_price']));
        //         $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('F'.$rc)->setValue(num($vvv['ext_price']));
        //         $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('G'.$rc)->setValue(num($vvv['unit_price']));
        //         $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('H'.$rc)->setValue($vvv['reason']);
        //         $sheet->getStyle('H'.$rc)->applyFromArray($styleTxt);
        //         $total_qty += $vvv['qty'];
        //         $total_amt += $vvv['ext_price'];
        //         $rc++;
        //     }

        //     $sheet->getCell('C'.$rc)->setValue('SUBTOTAL');
        //     $sheet->getStyle('C'.$rc)->applyFromArray($styleTxtC);
        //     $sheet->getCell('D'.$rc)->setValue(num($total_qty));
        //     $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
        //     $sheet->getCell('F'.$rc)->setValue(num($total_amt));
        //     $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        // }
       
       
        // $rc++;
        
        update_load(100);
        ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }
    public function brand_sales_rep()
    {
        $data = $this->syter->spawn('sales_rep');        
        $data['page_title'] = fa('fa-money')."Brand Sales Report";
        $data['code'] = brandsalesRep();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['page_no_padding'] = false;
        $data['sideBarHide'] = false;
        $data['load_js'] = 'dine/reporting';
        $data['use_js'] = 'brandsalesRepJS';
        $this->load->view('page',$data);
    }
    public function brand_sales_rep_gen()
    {
        $this->load->model('dine/setup_model');
        // $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];
        // echo print_r($_POST);die();
        start_load(0);
        $menu_cat_id = $this->input->post("menu_cat_id");        
        $brand = $this->input->post("brand");        
        $daterange = $this->input->post("calendar_range");        
        $dates = explode(" to ",$daterange);
        // echo $brand;die();
        // echo "<pre>", print_r($this->input->post("")), "</pre>"; die();
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        // $date = $this->input->post("date");
        $this->menu_model->db = $this->load->database('main', TRUE);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        $trans = $this->menu_model->get_cat_sales_rep($from, $to, $menu_cat_id,$brand);  
        $trans_ret = $this->menu_model->get_cat_sales_rep_retail($from, $to, "");  
        $menu_brand = $this->menu_model->get_menu_brand();
        // echo $this->db->last_query();              
        $trans_count = count($trans);
        $trans_count_ret = count($trans_ret);
        $counter = 0;
        if($brand != ""){
            $this->make->sDiv();
                $this->make->sTable(array("id"=>"main-tbl", 'class'=>'table reportTBL sortable'));
                    $this->make->sTableHead();
                        $this->make->sRow();
                            $this->make->th('Category');
                            $this->make->th('Qty');
                            $this->make->th('Gross');
                            $this->make->th('Sales (%)');
                            $this->make->th('Cost');
                            $this->make->th('Cost (%)');
                            $this->make->th('Margin');
                        $this->make->eRow();
                    $this->make->eTableHead();
                    $this->make->sTableBody();
                        $tot_qty = 0;
                        $tot_vat_sales = 0;
                        $tot_vat = 0;
                        $tot_gross = 0;
                        $tot_sales_prcnt = 0;
                        $tot_cost = 0;
                        $tot_margin = 0;
                        $tot_cost_prcnt = 0;
                        foreach ($trans as $v) {
                            $tot_gross += $v->gross;
                            $tot_cost += $v->cost;
                        }
                        foreach ($trans as $res) {
                            $this->make->sRow();
                                $this->make->td($res->menu_name);
                                $this->make->td(num($res->qty), array("style"=>"text-align:right"));
                                $this->make->td(num($res->gross), array("style"=>"text-align:right"));
                                $this->make->td(num($res->gross / $tot_gross * 100). "%", array("style"=>"text-align:right"));
                                $this->make->td(num($res->cost), array("style"=>"text-align:right"));                            
                                if($tot_cost != 0){
                                    $this->make->td(num($res->cost / $tot_cost * 100). "%", array("style"=>"text-align:right"));
                                }else{
                                    $this->make->td("0.00%", array("style"=>"text-align:right"));                                
                                }
                                $this->make->td(num($res->gross - $res->cost), array("style"=>"text-align:right"));
                            $this->make->eRow();

                             // Grand Total
                            $tot_qty += $res->qty;
                            $tot_vat_sales += $res->vat_sales;
                            $tot_vat += $res->vat;
                            $tot_sales_prcnt = 0;
                            $tot_margin += $res->gross - $res->cost;
                            $tot_cost_prcnt = 0;

                            $counter++;
                            $progress = ($counter / $trans_count) * 100;
                            update_load(num($progress));

                        }    
                        $this->make->sRow();
                            $this->make->th('Grand Total');
                            $this->make->th(num($tot_qty), array("style"=>"text-align:right"));
                            $this->make->th(num($tot_gross), array("style"=>"text-align:right"));
                            $this->make->th("", array("style"=>"text-align:right"));
                            $this->make->th(num($tot_cost), array("style"=>"text-align:right"));
                            $this->make->th("", array("style"=>"text-align:right"));
                            $this->make->th(num($tot_margin), array("style"=>"text-align:right"));                     
                        $this->make->eRow();                                 
                    $this->make->eTableBody();
                $this->make->eTable();
            $this->make->eDiv();
        }else{
             $this->make->sDiv();
            // $this->make->append('<left><h4>'.ucwords($mb->brand).'</h4></left>');
            $this->make->sTable(array("id"=>"main-tbl", 'class'=>'table reportTBL sortable'));
            $this->make->sTableHead();
            $this->make->sRow();
                $this->make->th('Category');
                $this->make->th('Qty');
                $this->make->th('Gross');
                $this->make->th('Sales (%)');
                $this->make->th('Cost');
                $this->make->th('Cost (%)');
                $this->make->th('Margin');
                $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();
                    $tot_qty = 0;
                    $tot_vat_sales = 0;
                    $tot_vat = 0;
                    $tot_gross = 0;
                    $tot_sales_prcnt = 0;
                    $tot_cost = 0;
                    $tot_margin = 0;
                    $tot_cost_prcnt = 0;
                    foreach ($menu_brand as $brands => $mb) {
                        $trans_new = $this->menu_model->get_brand_cat_sales_rep($from, $to, $menu_cat_id,$mb->brand);
                        foreach ($trans_new as $res) {
                        // echo "<pre>", print_r($mb), "</pre>";
                            if($mb->brand == $res->brand){ 
                                foreach ($trans_new as $v) {
                                    $tot_gross += $v->gross;
                                    $tot_cost += $v->cost;
                                }
                                $this->make->sRow();
                                $this->make->td(strtoupper($mb->brand_name),array('colspan'=>7,"style"=>"text-align:center"));
                                // $this->make->td('');                     
                                // $this->make->td('');                     
                                // $this->make->td('');                     
                                // $this->make->td('');                     
                                // $this->make->td('');                     
                                // $this->make->td('');                     
                                $this->make->eRow();
                                $trans_new2 = $this->menu_model->get_brand_menu_rep($from, $to, $menu_cat_id,$mb->brand);
                                foreach ($trans_new2 as $res) {
                                    if($mb->brand == $res->brand){ 
                                    $this->make->sRow();
                                        $this->make->td($res->menu_name);
                                        $this->make->td(num($res->qty), array("style"=>"text-align:right"));
                                        $this->make->td(num($res->gross), array("style"=>"text-align:right"));
                                        $this->make->td(num($res->gross / $tot_gross * 100). "%", array("style"=>"text-align:right"));
                                        $this->make->td(num($res->cost), array("style"=>"text-align:right"));                            
                                        if($tot_cost != 0){
                                            $this->make->td(num($res->cost / $tot_cost * 100). "%", array("style"=>"text-align:right"));
                                        }else{
                                            $this->make->td("0.00%", array("style"=>"text-align:right"));                                
                                        }
                                        $this->make->td(num($res->gross - $res->cost), array("style"=>"text-align:right"));
                                    $this->make->eRow();

                                     // Grand Total
                                    $tot_qty += $res->qty;
                                    $tot_vat_sales += $res->vat_sales;
                                    $tot_vat += $res->vat;
                                    $tot_sales_prcnt = 0;
                                    $tot_margin += $res->gross - $res->cost;
                                    $tot_cost_prcnt = 0;

                                    $counter++;
                                    $progress = ($counter / $trans_count) * 100;
                                    update_load(num($progress));

                                }
                            }
                        }
                    }
                }

                $this->make->sRow();
                $this->make->th('Grand Total');
                $this->make->th(num($tot_qty), array("style"=>"text-align:right"));
                $this->make->th(num($tot_gross), array("style"=>"text-align:right"));
                $this->make->th("", array("style"=>"text-align:right"));
                $this->make->th(num($tot_cost), array("style"=>"text-align:right"));
                $this->make->th("", array("style"=>"text-align:right"));
                $this->make->th(num($tot_margin), array("style"=>"text-align:right"));                     
            $this->make->eRow();                                 
            $this->make->eTableBody();
            $this->make->eTable();
            $this->make->eDiv();
        }
        update_load(100);
        $code = $this->make->code();
        $json['code'] = $code;        
        $json['tbl_vals'] = $trans;
        $json['dates'] = $this->input->post('calendar_range');
        echo json_encode($json);
    }

    public function brand_sales_rep_pdf()
    {
        // Include the main TCPDF library (search for installation path).
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Brand Sales Report');
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

        // ---------------------------------------------------------
        $this->load->model('dine/setup_model');
        // $this->load->database('main', TRUE);
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
        
        $menu_cat_id = $_GET['menu_cat_id'];        
        $daterange = $_GET['calendar_range'];        
        $brand = $_GET['brand'];        
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        $trans = $this->menu_model->get_cat_sales_rep($from, $to, $menu_cat_id,$brand);
        $trans_ret = $this->menu_model->get_cat_sales_rep_retail($from, $to, "");
        $trans_mod = $this->menu_model->get_mod_cat_sales_rep($from, $to, $menu_cat_id);
        $menu_brand = $this->menu_model->get_menu_brand();
        // $trans_payment = $this->menu_model->get_payment_date($from, $to);
        // echo $this->db->last_query();die();                  


        $pdf->Write(0, 'Sales Report', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Write(0, 'Report Period:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, $daterange, '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Transaction Time:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln();              

        // echo "<pre>", print_r($trans), "</pre>";die();

        // -----------------------------------------------------------------------------
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(75, 0, 'Category', 'B', 0, 'L');        
        $pdf->Cell(32, 0, 'Qty', 'B', 0, 'R');        
        // $pdf->Cell(32, 0, 'VAT Sales', 'B', 0, 'C');        
        // $pdf->Cell(32, 0, 'VAT', 'B', 0, 'C');        
        $pdf->Cell(32, 0, 'Gross', 'B', 0, 'R');        
        $pdf->Cell(32, 0, 'Sales (%)', 'B', 0, 'R');        
        $pdf->Cell(32, 0, 'Cost', 'B', 0, 'R');        
        $pdf->Cell(32, 0, 'Cost (%)', 'B', 0, 'R');        
        $pdf->Cell(32, 0, 'Margin', 'B', 0, 'R');        
        $pdf->ln();                  

        // GRAND TOTAL VARIABLES
        $tot_qty = 0;
        $tot_vat_sales = 0;
        $tot_vat = 0;
        $tot_gross = 0;
        $tot_mod_gross = 0;
        $tot_sales_prcnt = 0;
        $tot_cost = 0;
        $tot_cost_prcnt = 0; 
        $tot_margin = 0;
        $counter = 0;
        $progress = 0;
        $trans_count = count($trans);
        // echo print_r($trans);die();
        foreach ($trans as $val) {
            $tot_gross += $val->gross;
            $tot_cost += $val->cost;
        }
        foreach ($trans_mod as $vv) {
            $tot_mod_gross += $vv->mod_gross;
        }
        if($brand != ""){
            foreach ($trans as $k => $v) {
                $pdf->Cell(75, 0, $v->menu_name, '', 0, 'L');        
                $pdf->Cell(32, 0, num($v->qty), '', 0, 'R');       
                $pdf->Cell(32, 0, num($v->gross), '', 0, 'R');        
                $pdf->Cell(32, 0, num($v->gross / $tot_gross * 100)."%", '', 0, 'R');        
                $pdf->Cell(32, 0, num($v->cost), '', 0, 'R');                    
                if($tot_cost != 0){
                    $pdf->Cell(32, 0, num($v->cost / $tot_cost * 100)."%", '', 0, 'R');                        
                }else{
                    $pdf->Cell(32, 0, "0.00%", '', 0, 'R');                                        
                }
                $pdf->Cell(32, 0, num($v->gross - $v->cost), '', 0, 'R');        
                $pdf->ln();                

                // Grand Total
                $tot_qty += $v->qty;
                $tot_sales_prcnt = 0;
                $tot_margin += $v->gross - $v->cost;
                $tot_cost_prcnt = 0;

                $counter++;
                $progress = ($counter / $trans_count) * 100;
                update_load(num($progress));              
            }
        }else{
            $tot_qty = 0;
            $tot_vat_sales = 0;
            $tot_vat = 0;
            $tot_gross = 0;
            $tot_mod_gross = 0;
            $tot_sales_prcnt = 0;
            $tot_cost = 0;
            $tot_cost_prcnt = 0; 
            $tot_margin = 0;
            $counter = 0;
            $progress = 0;
            foreach ($menu_brand as $brands => $mb) {
                $trans_new = $this->menu_model->get_brand_cat_sales_rep($from, $to, $menu_cat_id,$mb->brand);
                foreach ($trans_new as $k => $v) {
                    $pdf->Cell(75, 0, strtoupper($mb->brand), '', 0, 'L'); 
                    $pdf->ln();         
                    // foreach ($trans_new as $res) {
                    $trans_new2 = $this->menu_model->get_brand_menu_rep($from, $to, $menu_cat_id,$mb->brand);
                    foreach ($trans_new2 as $k => $v) {
                        if($mb->brand == $v->brand){ 
                            // foreach ($trans_new as $val) {
                                $tot_gross += $v->gross;
                                $tot_cost += $v->cost;
                            // }
                            $pdf->Cell(75, 0, $v->menu_name, '', 0, 'L');        
                            $pdf->Cell(32, 0, num($v->qty), '', 0, 'R');       
                            $pdf->Cell(32, 0, num($v->gross), '', 0, 'R');        
                            $pdf->Cell(32, 0, num($v->gross / $tot_gross * 100)."%", '', 0, 'R');        
                            $pdf->Cell(32, 0, num($v->cost), '', 0, 'R');                    
                            if($tot_cost != 0){
                                $pdf->Cell(32, 0, num($v->cost / $tot_cost * 100)."%", '', 0, 'R');                        
                            }else{
                                $pdf->Cell(32, 0, "0.00%", '', 0, 'R');                                        
                            }
                            $pdf->Cell(32, 0, num($v->gross - $v->cost), '', 0, 'R');        
                            $pdf->ln();                

                            // Grand Total
                            $tot_qty += $v->qty;
                            $tot_sales_prcnt = 0;
                            $tot_margin += $v->gross - $v->cost;
                            $tot_cost_prcnt = 0;

                            $counter++;
                            $progress = ($counter / $trans_count) * 100;
                            update_load(num($progress));              
                        }
                    }
                }
            }
        }

        update_load(100);

        // update_load(100);        
        $pdf->Cell(75, 0, "Grand Total", 'T', 0, 'L');        
        $pdf->Cell(32, 0, num($tot_qty), 'T', 0, 'R');         
        $pdf->Cell(32, 0, num($tot_gross), 'T', 0, 'R');        
        $pdf->Cell(32, 0, "", 'T', 0, 'R');        
        $pdf->Cell(32, 0, num($tot_cost), 'T', 0, 'R');        
        $pdf->Cell(32, 0, "", 'T', 0, 'R'); 
        $pdf->Cell(32, 0, num($tot_margin), 'T', 0, 'R'); 


        // -----------------------------------------------------------------------------

        //Close and output PDF document
        $pdf->Output('brand_sales_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+   
    }
    
    public function brand_sales_rep_excel()
    {
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        date_default_timezone_set('Asia/Manila');
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $filename = 'Brand Sales Report';
        $rc=1;
        #GET VALUES
        start_load(0);
            // $post = $this->set_post($_GET['calendar_range']);
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];

        update_load(10);
        sleep(1);
        
        $menu_cat_id = $_GET['menu_cat_id'];        
        $daterange = $_GET['calendar_range'];        
        $brand = $_GET['brand'];        
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        $trans = $this->menu_model->get_cat_sales_rep($from, $to, $menu_cat_id,$brand);
        $trans_ret = $this->menu_model->get_cat_sales_rep_retail($from, $to, "");   
        $trans_mod = $this->menu_model->get_mod_cat_sales_rep($from, $to, $menu_cat_id);
        $trans_payment = $this->menu_model->get_payment_date($from, $to);
        $menu_brand = $this->menu_model->get_menu_brand();   

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
        
        $headers = array('Category', 'Qty','Gross','Sales (%)','Cost','Cost (%)', 'Margin');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        // $sheet->getColumnDimension('H')->setWidth(20);


        $sheet->mergeCells('A'.$rc.':H'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->branch_name);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->address);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':G'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Sales Report');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Report Period: '.$daterange);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('E'.$rc)->setValue('Report Generated: '.(new \DateTime())->format('Y-m-d H:i:s'));
        $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Transaction Time:');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $user = $this->session->userdata('user');
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('F'.$rc)->setValue('Generated by:    '.$user["full_name"]);
        $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        $rc++;

        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }
        $rc++;                          

        // GRAND TOTAL VARIABLES
        $tot_qty = 0;
        $tot_vat_sales = 0;
        $tot_vat = 0;
        $tot_gross = 0;
        $tot_mod_gross = 0;
        $tot_sales_prcnt = 0;
        $tot_cost = 0;
        $tot_cost_prcnt = 0; 
        $tot_margin = 0;
        $counter = 0;
        $progress = 0;
        $trans_count = count($trans);
        if($brand != ""){
            foreach ($trans as $val) {
                $tot_gross += $val->gross;
                $tot_cost += $val->cost;
                $tot_margin += $val->gross - $val->cost;
            }
            foreach ($trans_mod as $vv) {
                $tot_mod_gross += $vv->mod_gross;
            }

            foreach ($trans as $k => $v) {
                $sheet->getCell('A'.$rc)->setValue($v->menu_cat_name);
                $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('B'.$rc)->setValue($v->qty);
                $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('C'.$rc)->setValue(num($v->gross));     
                $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('D'.$rc)->setValue(num($v->gross / $tot_gross * 100)."%");     
                $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('E'.$rc)->setValue(num($v->cost));                     
                $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
                if($tot_cost != 0){
                    $sheet->getCell('F'.$rc)->setValue(num($v->cost / $tot_cost * 100)."%");     
                
                }else{
                    $sheet->getCell('F'.$rc)->setValue('0.00%');                                     
                }
                $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('G'.$rc)->setValue(num($v->gross - $v->cost));     
                $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);       

                // Grand Total
                $tot_qty += $v->qty;
                $tot_sales_prcnt = 0;
                $tot_cost_prcnt = 0;

                $counter++;
                $progress = ($counter / $trans_count) * 100;
                update_load(num($progress));   
                $rc++;           
            }
        }else{
            $tot_qty = 0;
            $tot_vat_sales = 0;
            $tot_vat = 0;
            $tot_gross = 0;
            $tot_mod_gross = 0;
            $tot_sales_prcnt = 0;
            $tot_cost = 0;
            $tot_cost_prcnt = 0; 
            $tot_margin = 0;
            $counter = 0;
            $progress = 0;
            foreach ($menu_brand as $brands => $mb) {
                $trans_new = $this->menu_model->get_brand_cat_sales_rep($from, $to, $menu_cat_id,$mb->brand);
                // foreach ($trans_new as $res) {
                foreach ($trans_new as $k => $v) {
                    if($mb->brand == $v->brand){
                        $sheet->getCell('A'.$rc)->setValue(strtoupper($mb->brand));
                        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                        $rc++;
                        $trans_new2 = $this->menu_model->get_brand_menu_rep($from, $to, $menu_cat_id,$mb->brand);
                        foreach ($trans_new2 as $k => $v) {
                            if($mb->brand == $v->brand){ 
                            // foreach ($trans_new as $val) {
                                $tot_gross += $v->gross;
                                $tot_cost += $v->cost;
                                $tot_margin += $v->gross - $v->cost;
                            // }
                            
                                $sheet->getCell('A'.$rc)->setValue($v->menu_cat_name);
                                $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                                $sheet->getCell('B'.$rc)->setValue($v->qty);
                                $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
                                $sheet->getCell('C'.$rc)->setValue(num($v->gross));     
                                $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
                                $sheet->getCell('D'.$rc)->setValue(num($v->gross / $tot_gross * 100)."%");     
                                $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
                                $sheet->getCell('E'.$rc)->setValue(num($v->cost));                     
                                $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
                                if($tot_cost != 0){
                                    $sheet->getCell('F'.$rc)->setValue(num($v->cost / $tot_cost * 100)."%");     
                                
                                }else{
                                    $sheet->getCell('F'.$rc)->setValue('0.00%');                                     
                                }
                                $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
                                $sheet->getCell('G'.$rc)->setValue(num($v->gross - $v->cost));     
                                $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);       

                                // Grand Total
                                $tot_qty += $v->qty;
                                $tot_sales_prcnt = 0;
                                $tot_cost_prcnt = 0;

                                $counter++;
                                $progress = ($counter / $trans_count) * 100;
                                update_load(num($progress));   
                                $rc++;           
                            }
                        }
                    }
                }
            }
        }

        $sheet->getCell('A'.$rc)->setValue('Grand Total');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
        $sheet->getCell('B'.$rc)->setValue(num($tot_qty));
        $sheet->getStyle('B'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('C'.$rc)->setValue(num($tot_gross));     
        $sheet->getStyle('C'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('D'.$rc)->setValue("");     
        $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('E'.$rc)->setValue(num($tot_cost));     
        $sheet->getStyle('E'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('F'.$rc)->setValue("");     
        $sheet->getStyle('F'.$rc)->applyFromArray($styleBoldRight);
        $sheet->getCell('G'.$rc)->setValue(num($tot_margin));     
        $sheet->getStyle('G'.$rc)->applyFromArray($styleBoldRight);
        $rc++; 

        // foreach ($menu_brand as $brands => $mb) {
        //         $trans_new = $this->menu_model->get_brand_cat_sales_rep($from, $to, $menu_cat_id,$mb->brand);
        //         // foreach ($trans_new as $res) {
        //         foreach ($trans_new as $val) {
        //             $tot_gross += $val->gross;
        //             $tot_cost += $val->cost;
        //         }
        //         foreach ($trans_new as $k => $v) {
        //             if($mb->brand == $v->brand){ 
        //                 $pdf->Cell(75, 0, strtoupper($mb->brand), '', 0, 'L'); 
        //                 $pdf->ln();         
        //                 $pdf->Cell(75, 0, $v->menu_cat_name, '', 0, 'L');        
        //                 $pdf->Cell(32, 0, num($v->qty), '', 0, 'R');       
        //                 $pdf->Cell(32, 0, num($v->gross), '', 0, 'R');        
        //                 $pdf->Cell(32, 0, num($v->gross / $tot_gross * 100)."%", '', 0, 'R');        
        //                 $pdf->Cell(32, 0, num($v->cost), '', 0, 'R');                    
        //                 if($tot_cost != 0){
        //                     $pdf->Cell(32, 0, num($v->cost / $tot_cost * 100)."%", '', 0, 'R');                        
        //                 }else{
        //                     $pdf->Cell(32, 0, "0.00%", '', 0, 'R');                                        
        //                 }
        //                 $pdf->Cell(32, 0, num($v->gross - $v->cost), '', 0, 'R');        
        //                 $pdf->ln();                

        //                 // Grand Total
        //                 $tot_qty += $v->qty;
        //                 $tot_sales_prcnt = 0;
        //                 $tot_margin += $v->gross - $v->cost;
        //                 $tot_cost_prcnt = 0;

        //                 $counter++;
        //                 $progress = ($counter / $trans_count) * 100;
        //                 update_load(num($progress));              
        //             }
        //         }
        //     }

        
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

    public function gc_sales_rep()
    {
        $this->load->model('dine/gift_cards_model');
        //get sa database yun mga payment types
        $gc_brands = $this->gift_cards_model->get_gift_card_brand();

        $data = $this->syter->spawn('gc_sales_rep');        
        $data['page_title'] = fa('fa-money')." Gift Cheque Sales Report";
        $data['code'] = gcSalesRep(null,$gc_brands);
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['page_no_padding'] = false;
        $data['sideBarHide'] = false;
        $data['load_js'] = 'dine/reporting';
        $data['use_js'] = 'gcSalesRepJS';
        $this->load->view('page',$data);
    }

     public function gc_rep_gen()
    {
        $this->load->model('dine/setup_model');
        // $this->load->database('main', TRUE);
        $this->load->model("dine/gift_cards_model");
        
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];
        start_load(0);
        $gc_type = $this->input->post("gc_type");        
        // $brand = $this->input->post("brand");        
        $daterange = $this->input->post("calendar_range");        
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        // $date = $this->input->post("date");
        $this->menu_model->db = $this->load->database('main', TRUE);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        $trans_ret = $this->gift_cards_model->get_gift_cards_rep_retail($from, $to, $gc_type);  
        // echo $this->db->last_query();              
        // echo "<pre>", print_r($trans), "</pre>"; die();
        $trans_count_ret = count($trans_ret);
        $counter = 0;
        
        $this->make->sDiv();
            $this->make->sTable(array("id"=>"main-tbl", 'class'=>'table reportTBL sortable'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('Acknowledgement Receipt #');
                        $this->make->th('GC Type');
                        $this->make->th('GC Description');
                        $this->make->th('GC From');
                        $this->make->th('GC To');
                        $this->make->th('QTY');
                        $this->make->th('Amount');
                        $this->make->th('Total');
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();
                    $tot_qty = 0;
                    $tot_vat_sales = 0;
                    $tot_vat = 0;
                    $tot_gross = 0;
                    $tot_sales_prcnt = 0;
                    $tot_cost = 0;
                    $tot_margin = 0;
                    $tot_cost_prcnt = 0;
                   
                    foreach ($trans_ret as $res) {
                        $tot_gross += $res->gross;

                        $this->make->sRow();
                            $this->make->td($res->ref);
                            $this->make->td($res->brand_id);
                            $this->make->td($res->description_id); 
                            $this->make->td($res->gc_from);
                            $this->make->td($res->gc_to);                           
                            $this->make->td(num($res->qty), array("style"=>"text-align:right"));                            
                            $this->make->td(num($res->price), array("style"=>"text-align:right"));
                            $this->make->td(num($res->gross), array("style"=>"text-align:right"));
                        $this->make->eRow();

                         // Grand Total
                        $tot_qty += $res->qty;
                        
                        $tot_cost_prcnt = 0;

                        $counter++;
                        $progress = ($counter / $trans_count_ret) * 100;
                        update_load(num($progress));

                    }    
                    $this->make->sRow();
                        $this->make->th('Grand Total');
                        $this->make->th('');
                        $this->make->th('');
                        $this->make->th('');
                        $this->make->th('');
                        $this->make->th(num($tot_qty), array("style"=>"text-align:right"));
                        $this->make->th('');
                        $this->make->th(num($tot_gross), array("style"=>"text-align:right"));                                           
                    $this->make->eRow();                                 
                $this->make->eTableBody();
            $this->make->eTable();
        $this->make->eDiv();
        update_load(100);
        $code = $this->make->code();
        $json['code'] = $code;        
        $json['tbl_vals'] = $trans_ret;
        $json['dates'] = $this->input->post('calendar_range');
        echo json_encode($json);
    }

    public function gc_rep_excel()
    {
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/gift_cards_model");
        date_default_timezone_set('Asia/Manila');
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $filename = 'Gift Cheque Sales Report';
        $rc=1;
        #GET VALUES
        start_load(0);
            // $post = $this->set_post($_GET['calendar_range']);
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];

        update_load(10);
        sleep(1);
        
        $gc_type = $_GET['gc_type'];        
        $daterange = $_GET['calendar_range'];        
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        
        $trans_ret = $this->gift_cards_model->get_gift_cards_rep_retail($from, $to, $gc_type);  
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
        
        $headers = array('Acknowledgement Receipt #', 'GC Type','GC Description','GC From','GC To','Qty','Amount','Total');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);

        $sheet->mergeCells('A'.$rc.':E'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->branch_name);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':E'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->address);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':E'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Gift Cheque Sales Report');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Report Period: '.$daterange);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('E'.$rc)->setValue('Report Generated: '.(new \DateTime())->format('Y-m-d H:i:s'));
        $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Transaction Time:');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $user = $this->session->userdata('user');
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('F'.$rc)->setValue('Generated by:    '.$user["full_name"]);
        $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        $rc++;

        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }
        $rc++;                          

        // GRAND TOTAL VARIABLES
        $tot_qty = 0;
        $tot_vat_sales = 0;
        $tot_vat = 0;
        $tot_gross = 0;
        $tot_mod_gross = 0;
        $tot_sales_prcnt = 0;
        $tot_cost = 0;
        $tot_cost_prcnt = 0; 
        $tot_margin = 0;
        $counter = 0;
        $progress = 0;
       

        
        $rc++; 

        //retail
        $tot_gross_ret = 0;
        if(count($trans_ret) > 0){                    

            // GRAND TOTAL VARIABLES
            $tot_qty = 0;
            $tot_vat_sales = 0;
            $tot_vat = 0;
            $tot_mod_gross = 0;
            $tot_sales_prcnt = 0;
            $tot_cost = 0;
            $tot_cost_prcnt = 0; 
            $tot_margin = 0;
            $counter = 0;
            $progress = 0;
            $trans_count = count($trans_ret);

            foreach ($trans_ret as $k => $v) {
                $tot_gross_ret += $v->gross; 
                $sheet->getCell('A'.$rc)->setValue($v->ref);
                $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('B'.$rc)->setValue($v->brand_id);
                $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);

                $sheet->getCell('C'.$rc)->setValue($v->description_id);
                $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);

                $sheet->getCell('D'.$rc)->setValue($v->gc_from);
                $sheet->getStyle('D'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('E'.$rc)->setValue($v->gc_to);
                $sheet->getStyle('E'.$rc)->applyFromArray($styleTxt);

                // $sheet->getCell('C'.$rc)->setValue(num($v->vat_sales));
                // $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
                // $sheet->getCell('D'.$rc)->setValue(num($v->vat));     
                // $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('F'.$rc)->setValue(num($v->qty));     
                $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('G'.$rc)->setValue(num($v->price));     
                $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
                $sheet->getStyle('H'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('H'.$rc)->setValue(num($v->gross));                                     
                 

                // Grand Total
                $tot_qty += $v->qty;
                // $tot_vat_sales += $v->vat_sales;
                // $tot_vat += $v->vat;
                // $tot_gross += $v->gross;
                $tot_sales_prcnt = 0;
                // $tot_cost += $v->cost;
                $tot_cost_prcnt = 0;

                $counter++;
                $progress = ($counter / $trans_count) * 100;
                update_load(num($progress));   
                $rc++;           
            }

            $sheet->getCell('A'.$rc)->setValue('Grand Total');
            $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
            $sheet->getCell('F'.$rc)->setValue(num($tot_qty));
            $sheet->getStyle('F'.$rc)->applyFromArray($styleBoldRight);
            // $sheet->getCell('C'.$rc)->setValue(num($tot_vat_sales));     
            // $sheet->getStyle('C'.$rc)->applyFromArray($styleBoldRight);
            // $sheet->getCell('D'.$rc)->setValue(num($tot_vat));     
            // $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);
            
            $sheet->getCell('H'.$rc)->setValue(num($tot_gross_ret));     
            $sheet->getStyle('H'.$rc)->applyFromArray($styleBoldRight);
           
            $rc++; 
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

    public function gc_rep_pdf()
    {
        // Include the main TCPDF library (search for installation path).
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Gift Cheque Sales Report');
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

        // ---------------------------------------------------------
        $this->load->model('dine/setup_model');
        // $this->load->database('main', TRUE);
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/gift_cards_model");
        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
         
         $gc_type = $_GET['gc_type'];     
        $daterange = $_GET['calendar_range'];        
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        
        $trans_ret = $this->gift_cards_model->get_gift_cards_rep_retail($from, $to, $gc_type);
        // $trans_payment = $this->menu_model->get_payment_date($from, $to);
        // echo $this->db->last_query();die();                  


        $pdf->Write(0, 'Gift Cheque Sales Report', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Write(0, 'Report Period:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, $daterange, '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Transaction Time:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln();              

        // echo "<pre>", print_r($trans), "</pre>";die();

        // -----------------------------------------------------------------------------
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(45, 0, 'Acknowledgement Receipt #', 'B', 0, 'L');  
        $pdf->Cell(32, 0, 'GC Type', 'B', 0, 'L');      
        $pdf->Cell(32, 0, 'GC Description', 'B', 0, 'L');
        $pdf->Cell(32, 0, 'GC From', 'B', 0, 'L');
        $pdf->Cell(32, 0, 'GC To', 'B', 0, 'L');        
        // $pdf->Cell(32, 0, 'VAT Sales', 'B', 0, 'C');        
        // $pdf->Cell(32, 0, 'VAT', 'B', 0, 'C');        
        $pdf->Cell(32, 0, 'Qty', 'B', 0, 'R');        
        $pdf->Cell(32, 0, 'Amount', 'B', 0, 'R');        
        $pdf->Cell(32, 0, 'Total', 'B', 0, 'R');
        $pdf->ln();                  

        // GRAND TOTAL VARIABLES
        $tot_qty = 0;
        $tot_vat_sales = 0;
        $tot_vat = 0;
        $tot_gross = 0;
        $tot_mod_gross = 0;
        $tot_sales_prcnt = 0;
        $tot_cost = 0;
        $tot_cost_prcnt = 0; 
        $tot_margin = 0;
        $counter = 0;
        $progress = 0;
        // echo print_r($trans);die();
       
        


        //retail
        $tot_gross_ret = 0;
        if(count($trans_ret) > 0){
            
            $pdf->ln(); 

            $tot_qty = 0;
            $tot_vat_sales = 0;
            $tot_vat = 0;
            $tot_mod_gross = 0;
            $tot_sales_prcnt = 0;
            $tot_cost = 0;
            $tot_cost_prcnt = 0; 
            $tot_margin = 0;
            $counter = 0;
            $progress = 0;
            $trans_count = count($trans_ret);
           
            foreach ($trans_ret as $k => $v) {
                $tot_gross_ret += $v->gross;
                $pdf->Cell(45, 0, $v->ref, '', 0, 'L');
                $pdf->Cell(32, 0, $v->brand_id, '', 0, 'L');         
                $pdf->Cell(32, 0, $v->description_id, '', 0, 'L');
                $pdf->Cell(32, 0, $v->gc_from, '', 0, 'L');
                $pdf->Cell(32, 0, $v->gc_to, '', 0, 'L');        
                // $pdf->Cell(32, 0, num($v->vat_sales), '', 0, 'R');        
                // $pdf->Cell(32, 0, num($v->vat), '', 0, 'R');        
                $pdf->Cell(32, 0, num($v->qty), '', 0, 'R');        
                $pdf->Cell(32, 0, num($v->price), '', 0, 'R');        
                
                $pdf->Cell(32, 0, num($v->gross), '', 0, 'R');        
                $pdf->ln();                

                // Grand Total
                $tot_qty += $v->qty;
                // $tot_vat_sales += $v->vat_sales;
                // $tot_vat += $v->vat;
                // $tot_gross += $v->gross;
                $tot_sales_prcnt = 0;
                // $tot_cost += $v->cost;
                $tot_margin += $v->gross - 0;
                $tot_cost_prcnt = 0;

                $counter++;
                $progress = ($counter / $trans_count) * 100;
                update_load(num($progress));              
            }

            update_load(100);        
            $pdf->Cell(45, 0, "Grand Total", 'T', 0, 'L');
            $pdf->Cell(32, 0, "", 'T', 0, 'R');
            $pdf->Cell(32, 0, "", 'T', 0, 'R');
            $pdf->Cell(32, 0, "", 'T', 0, 'R');      
            $pdf->Cell(32, 0, "", 'T', 0, 'R'); 
            $pdf->Cell(32, 0, num($tot_qty), 'T', 0, 'R');
            $pdf->Cell(32, 0, "", 'T', 0, 'R');        
            // $pdf->Cell(32, 0, num($tot_vat_sales), 'T', 0, 'R');        
            // $pdf->Cell(32, 0, num($tot_vat), 'T', 0, 'R');        
            $pdf->Cell(32, 0, num($tot_gross_ret), 'T', 0, 'R'); 
        }

        update_load(100);    
        //Close and output PDF document
        $pdf->Output('gift_cheque_sales_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+   
    }

    public function print_pdf_sales($sales_id=null,$asJson=true,$return_print_str=false,$add_reprinted=true,$splits=null,$include_footer=true,$no_prints=1,$order_slip_prints=0,$approved_by=null,$main_db=false,$openDrawer=false)
    {
        // include(dirname(__FILE__)."/cashier.php");
        // Include the main TCPDF library (search for installation path).
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("P", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Sales Report');
        $pdf->SetSubject('');
        $pdf->SetKeywords('');

        // set default header data
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];
        // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $set->branch_name, $set->address);

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

        // ---------------------------------------------------------
        $this->load->model('dine/setup_model');
        // $this->load->database('main', TRUE);
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();

        if($main_db){
            $this->db = $this->load->database('main', TRUE);
        }
        $branch = $this->get_branch_details(false);
        $return = $this->get_order(false,$sales_id);
        $order = $return['order'];
        $details = $return['details'];
        $payments = $return['payments'];
        $pfields = $return['pfields'];
        $discounts = $return['discounts'];
        $local_tax = $return['local_tax'];
        $charges = $return['charges'];
        $tax = $return['taxes'];
        $no_tax = $return['no_tax'];
        $zero_rated = $return['zero_rated'];
        $totalsss = $this->total_trans(false,$details,$discounts);
        $discs = $totalsss['discs'];
        $is_printed = $order['printed'];
        $print_str = $print_str_part1 = $print_str_part2 = $print_str_part3 = "\r\n";
        $is_billing = false;
        $open_drawer = false;

        $wrap = wordwrap($branch['name'],PAPER_WIDTH,"|#|");
        $exp = explode("|#|", $wrap);

        $pdf->SetFont('helvetica', '', 12);

        foreach ($exp as $v) {
            $pdf->Cell(50, 0, $v, '', 0, 'C'); 
            $pdf->ln();  
        }

        $splt = 1;
        $cancelled = 0;

        $wrap = wordwrap($branch['address'],PAPER_WIDTH,"|#|");
        $exp = explode("|#|", $wrap);
        foreach ($exp as $v) {
            $pdf->Cell(50, 0, $v, '', 0, 'C'); 
            $pdf->ln();  
        }   
        if($branch['tin'] != ""){
            $pdf->Cell(50, 0, 'VAT REG TIN:'.$branch['tin'], '', 0, 'C'); 
            $pdf->ln();
        }
        if($branch['serial'] != ""){
            $pdf->Cell(50, 0, 'S/N:'.$branch['serial'], '', 0, 'C'); 
            $pdf->ln();
        }
        if($branch['machine_no'] != ""){
            $pdf->Cell(50, 0, 'MIN:'.$branch['machine_no'], '', 0, 'C'); 
            $pdf->ln();
        }
        $pdf->ln();
        $pdf->Cell(50, 0, date('M d, Y h:i A',strtotime($order['datetime'])), '', 0, 'R'); 
        $pdf->ln();
        $pdf->ln();

        $pdf->Cell(50, 0, 'OFFICIAL RECEIPT', '', 0, 'L'); 
        $pdf->ln();
        $pdf->Cell(50, 0, "OR #: ".$order['ref']."", '', 0, 'L'); 
        $pdf->ln();
        $pdf->ln();

        $pdf->Cell(50, 0, strtoupper($order['type']), '', 0, 'L'); 
        $pdf->ln();
        $pdf->ln();

        $pdf->Cell(50, 0, "Table No:  ".$order['table_name'], '', 0, 'L'); 
        $pdf->ln();
        $pdf->Cell(50, 0, "Register No:  ".$order['terminal_id'], '', 0, 'L'); 
        $pdf->ln();
        $pdf->Cell(50, 0, "Cashier Name:  ".ucwords($order['name']), '', 0, 'L'); 
        $pdf->ln();
        $pdf->Cell(50, 0, "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _", '', 0, 'L'); 
        $pdf->ln();

        $pdf->Cell(50, 0, "Name: _____________________", '', 0, 'L'); 
        $pdf->ln();
        $pdf->Cell(50, 0, "Address: ___________________", '', 0, 'L'); 
        $pdf->ln();
        $pdf->Cell(50, 0, "TIN: _______________________", '', 0, 'L'); 
        $pdf->ln();
        $pdf->Cell(50, 0, "Business Style: ______________", '', 0, 'L'); 
        $pdf->ln();
        $pdf->Cell(50, 0, "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _", '', 0, 'L'); 
        $pdf->ln();
        $log_user = $this->session->userdata('user');

        if($order['customer_id'] != ""){
            if($main_db){
                $this->db = $this->load->database('default', TRUE);
            }
            $this->load->model('dine/customers_model');
            $customers = $this->customers_model->get_customer($order['customer_id']);
            if(count($customers) > 0){
                $cust = $customers[0];
                $name = strtolower($cust->fname." ".$cust->mname." ".$cust->lname." ".$cust->suffix);
                $pdf->Cell(50, 0, "Customer : ".ucwords($name), '', 0, 'R'); 
                // $print_str .= "Customer : ".$this->append_chars(ucwords($name),"right",19," ")."\r\n";
                $pdf->Cell(50, 0, "Customer : ".ucwords($cust->phone), '', 0, 'R'); 
                    $pdf->ln();
                    $address = strtolower($cust->street_no." ".$cust->street_address." ".$cust->city." ".$cust->region." ".$cust->zip);
                    $pdf->Cell(50, 0, "Address  : ".ucwords($address), '', 0, 'R'); 
                    $pdf->ln();
                    // $print_str .= "Address  : ".$this->append_chars(ucwords($address),"right",19," ")."\r\n";
                }
            }

            if($main_db){
                $this->db = $this->load->database('main', TRUE);
            }
        // }
        if($order['type'] == 'delivery' && $order['customer_id'] != ""){
        
        }
        $pre_total = 0;
            $post_details = array();
            $post_details_nc = array();
            $discs_items = array();
            foreach ($discs as $disc) {
                if(isset($disc['items']))
                    $discs_items[$disc['type']] = $disc['items'];
            }

            // echo "<pre>",print_r($details),"</pre>";die();

            $dscTxt = array();
            foreach ($details as $line_id => $val) {
                foreach ($discs_items as $type => $dissss) {
                    if(in_array($line_id, $dissss)){
                        $qty = 1;
                        if(isset($dscTxt[$val['menu_id'].'_'.$val['price']][$type]['qty'])){
                            $qty = $dscTxt[$val['menu_id'].'_'.$val['price']][$type]['qty'] + 1;
                        }
                        $dscTxt[$val['menu_id'].'_'.$val['price']][$type] = array('txt' => '#'.$type,'qty' => $qty);
                    }
                }
            }

            foreach ($details as $line_id => $val) {
            if($val['nocharge'] == 1){
                if (!isset($post_details_nc[$val['menu_id'].'_'.$val['price']])) {
                    $dscsacs = array();
                    if(isset($dscTxt[$val['menu_id'].'_'.$val['price']])){
                        $dscsacs = $dscTxt[$val['menu_id'].'_'.$val['price']];
                    }
                    $remarksArr = array();
                    if($val['remarks'] != '')
                        $remarksArr = array($val['remarks']." x ".$val['qty']);
                    $post_details_nc[$val['menu_id'].'_'.$val['price']] = array(
                        'name' => $val['name'],
                        'code' => $val['code'],
                        'price' => $val['price'],
                        'no_tax' => $val['no_tax'],
                        'discount' => $val['discount'],
                        'qty' => $val['qty'],
                        'discounted'=>$dscsacs,
                        'remarks'=>$remarksArr,
                        'modifiers' => array()
                    );
                } else {
                    if($val['remarks'] != "")
                        $post_details_nc[$val['menu_id'].'_'.$val['price']]['remarks'][]= $val['remarks']." x ".$val['qty'];
                    $post_details_nc[$val['menu_id'].'_'.$val['price']]['qty'] += $val['qty'];
                }

                if (empty($val['modifiers']))
                    continue;

                $modifs = $val['modifiers'];
                $n_modifiers = $post_details_nc[$val['menu_id'].'_'.$val['price']]['modifiers'];
                foreach ($modifs as $vv) {
                    if (!isset($n_modifiers[$vv['id']])) {
                        $n_modifiers[$vv['id']] = array(
                            'name' => $vv['name'],
                            'price' => $vv['price'],
                            'qty' => $val['qty'],
                            'discount' => $vv['discount']
                        );
                    } else {
                        $n_modifiers[$vv['id']]['qty'] += $val['qty'];
                    }
                }
                $post_details_nc[$val['menu_id'].'_'.$val['price']]['modifiers'] = $n_modifiers;
            }else{
                if (!isset($post_details[$val['menu_id'].'_'.$val['price']])) {
                    $dscsacs = array();
                    if(isset($dscTxt[$val['menu_id'].'_'.$val['price']])){
                        $dscsacs = $dscTxt[$val['menu_id'].'_'.$val['price']];
                    }
                    $remarksArr = array();
                    if($val['remarks'] != '')
                        $remarksArr = array($val['remarks']." x ".$val['qty']);
                    
                    $post_details[$val['menu_id'].'_'.$val['price']] = array(
                        'name' => $val['name'],
                        'code' => $val['code'],
                        'price' => $val['price'],
                        'no_tax' => $val['no_tax'],
                        'discount' => $val['discount'],
                        'qty' => $val['qty'],
                        'discounted'=>$dscsacs,
                        'remarks'=>$remarksArr,
                        'modifiers' => array()
                    );
                } else {
                    if($val['remarks'] != "")
                        $post_details[$val['menu_id'].'_'.$val['price']]['remarks'][]= $val['remarks']." x ".$val['qty'];
                    $post_details[$val['menu_id'].'_'.$val['price']]['qty'] += $val['qty'];
                }

                if (empty($val['modifiers']))
                    continue;

                $modifs = $val['modifiers'];
                $n_modifiers = $post_details[$val['menu_id'].'_'.$val['price']]['modifiers'];
                foreach ($modifs as $vv) {
                    if (!isset($n_modifiers[$vv['id']])) {
                        $n_modifiers[$vv['id']] = array(
                            'name' => $vv['name'],
                            'price' => $vv['price'],
                            'qty' => $val['qty'],
                            'discount' => $vv['discount']
                        );
                    } else {
                        $n_modifiers[$vv['id']]['qty'] += $val['qty'];
                    }
                }
                $post_details[$val['menu_id'].'_'.$val['price']]['modifiers'] = $n_modifiers;
            }

        }
        /* END NEW BLOCK */
        $tot_qty = 0;
        $pdf->Cell(20, 0, "QTY", '', 0, 'L'); 
        $pdf->Cell(50, 0, "Item Description", '', 0, 'C'); 
        $pdf->Cell(30, 0, "Amount", '', 0, 'R'); 
        $pdf->ln();
        $pdf->ln();

        foreach ($post_details as $val) {
            $tot_qty += $val['qty'];
            // $print_str .= $this->append_chars($val['qty'] ."  " ,"right",PAPER_DET_COL_1," ");
            $pdf->Cell(20, 0, $val['qty'], '', 0, 'L'); 

            $len = strlen($val['name']);

            if($val['qty'] == 1){
                $lgth = 21;
            }else{
                $lgth = 16;
            }

            if($len > $lgth){
                $arr2 = str_split($val['name'], $lgth);
                $counter = 1;
                foreach($arr2 as $k => $vv){
                    if($counter == 1){
                        if ($val['qty'] == 1) {
                            // $pdf->Cell(20, 0, "", '', 0, 'L'); 
                            $pdf->Cell(50, 0, substrwords($vv,100,""), '', 0, 'L'); 
                            $pdf->Cell(30, 0, number_format($val['price'],2), '', 0, 'R'); 
                            $pdf->ln();
                        } else {
                            // $pdf->Cell(20, 0, "", '', 0, 'L'); 
                            $pdf->Cell(50, 0, substrwords($vv,100,""), '', 0, 'L'); 
                            $pdf->Cell(30, 0, number_format($val['price'] * $val['qty'],2), '', 0, 'R'); 
                            $pdf->ln();
                        }
                    }else{
                        // if ($val['qty'] == 1) {
                            // $pdf->Cell(20, 0, "", '', 0, 'L'); 
                            $pdf->Cell(50, 0, substrwords($vv,100,""), '', 0, 'L'); 
                            $pdf->Cell(30, 0, "", '', 0, 'R');
                            $pdf->ln();
                    }
                    $counter++;
                }
                
                if ($val['qty'] == 1) {
                    $pre_total += $val['price'];
                }else{
                    $pre_total += $val['price'] * $val['qty'];
                }
            }else{
                if ($val['qty'] == 1) {
                    // $pdf->Cell(20, 0, "e", '', 0, 'L');
                    $pdf->Cell(50, 0, substrwords($val['name'],100,""), '', 0, 'L');
                    $pdf->Cell(30, 0, number_format($val['price'],2), '', 0, 'R');
                     $pdf->ln();
                    $pre_total += $val['price'];
                } else {
                    $pdf->Cell(20, 0, "", '', 0, 'L');
                    $pdf->Cell(50, 0, substrwords($val['name'],100,""), '', 0, 'L');
                    $pdf->Cell(30, 0, number_format($val['price'] * $val['qty'],2), '', 0, 'R');
                     $pdf->ln();
                    $pre_total += $val['price'] * $val['qty'];
                }
            }
            if(count($val['discounted']) > 0){
                foreach ($val['discounted'] as $dssstxt) {
                  // $print_str .= "      ";
                    // $pdf->Cell(20, 0, "", '', 0, 'R');
                    $pdf->Cell(50, 0, "", '', 0, 'L');
                    $pdf->Cell(30, 0, $dssstxt['txt']." x ".$dssstxt['qty'], '', 0, 'R');
                     $pdf->ln();
                }
            }
            if(isset($val['remarks']) && count($val['remarks']) > 0){
                if(KERMIT){
                    foreach ($val['remarks'] as $rmrktxt) {
                        // $pdf->Cell(20, 0, " ", '', 0, 'R');
                        $pdf->Cell(50, 0, "  * ", '', 0, 'L');
                        $pdf->Cell(30, 0, ucwords($rmrktxt), '', 0, 'R');
                         $pdf->ln();
                    }
                }
            }

            if (empty($val['modifiers']))
                continue;

            $modifs = $val['modifiers'];
            foreach ($modifs as $vv) {
                // $print_str .= "   * ".$vv['qty']." ";
                $pdf->ln();
                $pdf->Cell(20, 0, "", '', 0, 'L');
                $pdf->Cell(5, 0, "*".$vv['qty']." ", '', 0, 'L');
                if ($vv['qty'] == 1) {
                    // $pdf->Cell(20, 0, "   ", '', 0, 'L');
                    $pdf->Cell(45, 0, substrwords($vv['name'],100,""), '', 0, 'L');
                    $pdf->Cell(30, 0, number_format($vv['price'],2), '', 0, 'R');
                    $pre_total += $vv['price'];
                } else {
                    // $pdf->Cell(20, 0, "   ", '', 0, 'L');
                    $pdf->Cell(45, 0, substrwords($vv['name'],100,""), '', 0, 'L');
                    $pdf->Cell(30, 0, number_format($vv['price'] * $vv['qty'],2), '', 0, 'R');
                    $pre_total += $vv['price'] * $vv['qty'];
                }
            }
            

        }

        //for no charges
        if($post_details_nc){
            // $print_str .= "\r\n";
            $pdf->ln();
            $pdf->Cell(20, 0, TAKEOUTTEXT, '', 0, 'L');
            $pdf->ln();
            // $print_str .= PAPER_LINE."\r\n";
            // $print_str .= "          ".TAKEOUTTEXT."\r\n";
            // $print_str .= PAPER_LINE."\r\n";
            foreach ($post_details_nc as $val) {
                $tot_qty += $val['qty'];
                $pdf->Cell(20, 0, $val['qty']." ", '', 0, 'L');
                // $print_str .= $this->append_chars($val['qty']." ","right",PAPER_DET_COL_1," ");

                $len = strlen($val['name']);

                if($val['qty'] == 1){
                    $lgth = 21;
                }else{
                    $lgth = 16;
                }

                if($len > $lgth){
                    $arr2 = str_split($val['name'], $lgth);
                    $counter = 1;
                    foreach($arr2 as $k => $vv){
                        if($counter == 1){
                            if ($val['qty'] == 1) {
                                 $pdf->Cell(50, 0, substrwords($vv,100,""), '', 0, 'L'); 
                                 $pdf->Cell(30, 0, number_format($val['price'],2), '', 0, 'R'); 
                                 $pdf->ln();
                                // $print_str .= $this->append_chars(substrwords($vv,100,""),"right",PAPER_DET_COL_2," ").
                                    // $this->append_chars(number_format($val['price'],2),"left",PAPER_DET_COL_3," ")."\r\n";
                            } else {
                                $pdf->Cell(50, 0, substrwords($vv,100,"")." ".$val['price'], '', 0, 'L'); 
                                $pdf->Cell(30, 0, number_format($val['price'] * $val['qty'],2), '', 0, 'R'); 
                                $pdf->ln();
                                // $print_str .= $this->append_chars(substrwords($vv,100,"")." ".$val['price'],"right",PAPER_DET_COL_2," ").
                                    // $this->append_chars(number_format($val['price'] * $val['qty'],2),"left",PAPER_DET_COL_3," ")."\r\n";
                            }
                        }else{
                            // if ($val['qty'] == 1) {
                                 $pdf->Cell(20, 0, "", '', 0, 'L'); 
                                 $pdf->Cell(50, 0, substrwords($vv,100,""), '', 0, 'L'); 
                                 $pdf->Cell(30, 0, "", '', 0, 'L'); 
                                 $pdf->ln();
                                // $print_str .= $this->append_chars("","right",PAPER_DET_COL_1," ");
                                // $print_str .= $this->append_chars(substrwords($vv,100,""),"right",PAPER_DET_COL_2," ").
                                    // $this->append_chars("","left",PAPER_DET_COL_3," ")."\r\n";
                            // } else {
                                // $print_str .= $this->append_chars(substrwords($vv,100,"")." @ ".$val['price'],"right",PAPER_DET_COL_2," ").
                                //     $this->append_chars("","left",PAPER_DET_COL_3," ")."\r\n";
                            // }
                        }
                        $counter++;
                    }
                    
                    if ($val['qty'] == 1) {
                        $pre_total += $val['price'];
                    }else{
                        $pre_total += $val['price'] * $val['qty'];
                    }
                }else{
                    if ($val['qty'] == 1) {
                        $pdf->Cell(50, 0, substrwords($val['name'],100,""), '', 0, 'L'); 
                        $pdf->Cell(30, 0, number_format($val['price'],2), '', 0, 'R'); 
                        $pdf->ln();
                        // $print_str .= $this->append_chars(substrwords($val['name'],100,""),"right",PAPER_DET_COL_2," ").
                            // $this->append_chars(number_format($val['price'],2),"left",PAPER_DET_COL_3," ")."\r\n";
                        $pre_total += $val['price'];
                    } else {
                        $pdf->Cell(50, 0, substrwords($val['name'],100,""), '', 0, 'L'); 
                        $pdf->Cell(30, 0, number_format($val['price'] * $val['qty'],2), '', 0, 'R'); 
                        $pdf->ln();
                        // $print_str .= $this->append_chars(substrwords($val['name'],100,"")." ".$val['price'],"right",PAPER_DET_COL_2," ").
                            // $this->append_chars(number_format($val['price'] * $val['qty'],2),"left",PAPER_DET_COL_3," ")."\r\n";
                        $pre_total += $val['price'] * $val['qty'];
                    }
                }
                if(count($val['discounted']) > 0){
                    foreach ($val['discounted'] as $dssstxt) {
                        $pdf->Cell(20, 0, "", '', 0, 'L'); 
                        $pdf->Cell(50, 0, $dssstxt['txt']." x ".$dssstxt['qty'], '', 0, 'R'); 
                        $pdf->ln();
                      // $print_str .= "      ";
                      // $print_str .= $this->append_chars($dssstxt['txt']." x ".$dssstxt['qty'],"right",PAPER_DET_COL_2," ")."\r\n";
                    }
                }
                if(isset($val['remarks']) && count($val['remarks']) > 0){
                    // foreach ($val['remarks'] as $rmrktxt) {
                    //     $print_str .= "     * ";
                    //     $print_str .= $this->append_chars(ucwords($rmrktxt),"right",PAPER_DET_COL_2," ")."\r\n";
                    // }
                }

                if (empty($val['modifiers']))
                    continue;

                $modifs = $val['modifiers'];
                foreach ($modifs as $vv) {
                    $print_str .= "   * ".$vv['qty']." ";

                    if ($vv['qty'] == 1) {
                        $pdf->Cell(50, 0, substrwords($vv['name'],100,""), '', 0, 'L'); 
                        $pdf->Cell(30, 0, number_format($vv['price'],2), '', 0, 'R'); 
                        $pdf->ln();
                        // $print_str .= $this->append_chars(substrwords($vv['name'],100,""),"right",PAPER_DET_SUBCOL," ")
                            // .$this->append_chars(number_format($vv['price'],2),"left",PAPER_DET_COL_3," ")."\r\n";
                        $pre_total += $vv['price'];
                    } else {
                        $pdf->Cell(50, 0, substrwords($vv['name'],100,""), '', 0, 'L'); 
                        $pdf->Cell(30, 0, number_format($vv['price'] * $vv['qty'],2), '', 0, 'R'); 
                        $pdf->ln();
                        // $print_str .= $this->append_chars(substrwords($vv['name'],100,"")." ".$vv['price'],"right",PAPER_DET_SUBCOL," ")
                            // .$this->append_chars(number_format($vv['price'] * $vv['qty'],2),"left",PAPER_DET_COL_3," ")."\r\n";
                        $pre_total += $vv['price'] * $vv['qty'];
                    }
                }
                


                //DISCOUNT PALATANDAAN
                // if(in_array($val[''], haystack))

            }
        }
        $vat = 0;
        if($tax > 0){
            foreach ($tax as $tx) {
               $vat += $tx['amount'];
            }
        }
        $no_tax_amt = 0;
        foreach ($no_tax as $k=>$v) {
            $no_tax_amt += $v['amount'];
        }

        $zero_rated_amt = 0;
        foreach ($zero_rated as $k=>$v) {
            $zero_rated_amt += $v['amount'];
        }
        if($zero_rated_amt > 0){
            $no_tax_amt = 0;
        }


        //start of not show for cancelled trans

        if($cancelled == 0){

            $total_discounts = 0;
            $total_discounts_sm = 0;
            foreach ($discounts as $dcs_ci => $dcs) {
                foreach ($dcs['persons'] as $code => $dcp) {
                    // $print_str .= $this->append_chars($dcs_ci,"right",28," ").$this->append_chars('P'.num($dcp['amount']),"left",10," ");
                    // $print_str .= "\r\n".$this->append_chars($dcp['name'],"right",28," ");
                    // $print_str .= "\r\n".$this->append_chars($dcp['code'],"right",28," ")."\r\n";
                    // $print_str .= "\r\n".$this->append_chars(asterisks($dcp['code']),"right",28," ")."\r\n";
                    $total_discounts += $dcp['amount'];
                    $dcAmt = $dcp['amount'];
                    // if(MALL_ENABLED && MALL == 'megamall'){
                    //     if($dcs_ci == PWDDISC){
                    //         $dcAmt = $dcAmt / 1.12;       
                    //     }
                    // }
                    $total_discounts_sm += $dcAmt;
                }
            }
            $total_discounts_non_vat = 0;
            foreach ($discounts as $dcs_ci => $dcs) {
               
                foreach ($dcs['persons'] as $code => $dcp) {
                    // $print_str .= $this->append_chars($dcs_ci,"right",28," ").$this->append_chars('P'.num($dcp['amount']),"left",10," ");
                    // $print_str .= "\r\n".$this->append_chars($dcp['name'],"right",28," ");
                    // $print_str .= "\r\n".$this->append_chars($dcp['code'],"right",28," ")."\r\n";
                    // $print_str .= "\r\n".$this->append_chars(asterisks($dcp['code']),"right",28," ")."\r\n";
                    if($dcs['no_tax'] == 1){
                        $total_discounts_non_vat += $dcp['amount'];
                    }
                }
            }
            $total_charges = 0;
            if(count($charges) > 0){
                foreach ($charges as $charge_id => $opt) {
                    $total_charges += $opt['total_amount'];
                }
            }
            $local_tax_amt = 0;
            if(count($local_tax) > 0){
                foreach ($local_tax as $lt_id => $lt) {
                    $local_tax_amt += $lt['amount'];
                }
            }
            // echo num($total_charges + $local_tax_amt);

            // echo '((('.$order['amount'].' - ('.$total_charges.' + '.$local_tax_amt.') - '.$vat.') - '.$no_tax_amt.'+'.$total_discounts_non_vat.') -'.$zero_rated_amt;


            $vat_sales = ( ( ( $order['amount'] - ($total_charges + $local_tax_amt) ) - $vat)  - $no_tax_amt + $total_discounts_non_vat ) - $zero_rated_amt;

            // echo '===== '.$vat_sales;
            // $vat_sales = ( ( ( $order['amount'] ) - $vat)  - $no_tax_amt + $total_discounts) - $zero_rated_amt;
            // echo "vat_sales= ((".$order['amount']." - ".$total_charges."))- ".$vat." )- ".$no_tax_amt." + ".$total_discounts." - ".$zero_rated_amt;
            if($vat_sales < 0){
                $vat_sales = 0;
            }
                $pdf->ln();
                $pdf->Cell(50, 0, "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _", '', 0, 'L');
                $pdf->ln();
                $pdf->Cell(40, 0, "Total Amount", '', 0, 'L');
                $pdf->Cell(30, 0, number_format($pre_total,2), '', 0, 'R');
                // $pdf->ln();
            // $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ");
                    // $print_str .= $this->append_chars("Total Amount","right",PAPER_DET_COL_2," ").
                // $this->append_chars(number_format($pre_total,2),"left",PAPER_DET_COL_3," ")."\r\n";
                if(count($discounts) >0){
                $less_vat = round(($pre_total - ($order['amount'] - $total_charges + $local_tax_amt ) ) - $total_discounts,2);
                // $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ");
                // $pdf->ln();
                
                    if($less_vat >0){
                        $pdf->ln();
                        $pdf->Cell(40, 0, "Less VAT", '', 0, 'L');
                        $pdf->Cell(30, 0, number_format($less_vat,2), '', 0, 'R');
                        // $print_str .= $this->append_chars(ucwords("Less VAT"),"right",PAPER_DET_COL_2," ").$this->append_chars(number_format( $less_vat,2),"left",PAPER_DET_COL_3," ")."\r\n";
                    }

                }else{
                    // $print_str .= $this->append_chars("Sub-Total","right",PAPER_DET_COL_2," ").
                    // $this->append_chars(number_format($pre_total,2),"left",PAPER_DET_COL_3," ")."\r\n";
                }
                if(count($discounts) >0){
                    $hasSMPWD = false;
                    if(count($dcs['persons']) > 0){

                        foreach ($discounts as $dcs_ci => $dcs) {
                            foreach ($dcs['persons'] as $code => $dcp) {
                                $discRateTxt = " (".$dcp['disc_rate']."%)";
                                if($dcs['fix'] == 1){
                                    $discRateTxt = " (".$dcp['disc_rate'].")";
                                }
                                $dcAmt = $dcp['amount'];
                                $pdf->ln();
                                $pdf->Cell(40, 0, "LESS ".$dcs_ci." DISCOUNT", '', 0, 'L');
                                $pdf->Cell(30, 0, number_format($dcAmt,2), '', 0, 'R');
                                  // $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ");
                                // $print_str .= $this->append_chars("LESS ".$dcs_ci." DISCOUNT","right",PAPER_DET_COL_2," ").$this->append_chars(' '.num($dcAmt),"left",PAPER_DET_COL_3," ")."\r\n";
                                
                            }
                        }
                       
                    }
                } 
           
                if(count($charges) > 0){
                    foreach ($charges as $charge_id => $opt) {
                        $charge_amount = $opt['total_amount'];
                        // if($opt['absolute'] == 0){
                        //     $charge_amount = ($opt['amount'] / 100) * ($order['amount'] - $vat);
                        // }
                        $pdf->ln();
                        $pdf->Cell(40, 0, $opt['name'] ."(".$opt['rate']."%)", '', 0, 'L');
                        $pdf->Cell(30, 0, number_format($charge_amount,2), '', 0, 'R');
                            // $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ");     
                        // $print_str .= $this->append_chars($opt['name'] ."(".$opt['rate']."%)","right",PAPER_DET_COL_2," ").$this->append_chars(number_format($charge_amount,2),"left",PAPER_DET_COL_3," ")."\r\n";
                // $print_str .= $this->append_chars("Service Charge (8.5%)","right",PAPER_DET_COL_2," ").
                    }
                    // $print_str .= PAPER_LINE."\r\n";
                }
                // if(PRINT_VERSION == 'V2'){    
                //     $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ");     
                //     $print_str .="<bold>";
                //     $print_str .= $this->append_chars($tot_qty." Item(s) Total Due","right",PAPER_DET_COL_2," ")."</bold><bold>".
                //     $this->append_chars(number_format($order['amount'],2),"left",PAPER_DET_COL_3," ")."</bold>"."\r\n";
                // }else{
                    $pdf->ln();
                    $pdf->Cell(40, 0, $tot_qty." Item(s) Total Due", '', 0, 'L');
                    $pdf->Cell(30, 0, number_format($order['amount'],2), '', 0, 'R');
                    // $print_str .= $this->append_chars($tot_qty." Item(s) Total Due","right",PAPER_DET_COL_2," ").
                    // $this->append_chars(number_format($order['amount'],2),"left",PAPER_DET_COL_3," ")."\r\n";
                // }
                $py_ref = "";
                if (!empty($payments)) {

                   
                    $pay_total = 0;
                    $gft_ctr = 0;
                    $nor_ctr = 0;
                    $py_ref = "";
                    $total_payment = 0;
                  
                    foreach ($payments as $payment_id => $opt) {
                        $pdf->ln();
                        $pdf->Cell(40, 0, ucwords($opt['payment_type']), '', 0, 'L');
                        $pdf->Cell(30, 0, number_format($opt['amount'],2), '', 0, 'R');
                        // $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ");     
                        // $print_str .= $this->append_chars(ucwords($opt['payment_type']),"right",PAPER_DET_COL_2," ").
                        // $this->append_chars(number_format($opt['amount'],2),"left",PAPER_DET_COL_3," ")."\r\n";
                        // $print_str .= $this->append_chars(ucwords($opt['payment_type']),"right",PAPER_TOTAL_COL_1," ").$this->append_chars("P ".number_format($opt['amount'],2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                        
                        if($opt['payment_type'] == 'check'){
                            $pdf->ln();
                            $pdf->Cell(40, 0, "     Check # ".$opt['reference'], '', 0, 'L');
                            // $print_str .= $this->append_chars("     Check # ".$opt['reference'],"right",PAPER_WIDTH," ")."\r\n";
                        }else{
                            if (!empty($opt['reference']) && $opt['payment_type'] != 'deposit') {
                                $py_ref = $opt['reference'];
                                // $print_str .= $this->append_chars("     Reference ".$opt['reference'],"right",PAPER_WIDTH," ")."\r\n";
                            }
                        }

                        if($opt['payment_type'] == 'foodpanda'){
                            if (!empty($opt['approval_code']))
                                    $pdf->ln();
                                    $pdf->Cell(40, 0, "  Order Code: ".$opt['approval_code'], '', 0, 'L');
                                    // $print_str .= $this->append_chars("  Order Code: ".$opt['approval_code'],"right",PAPER_WIDTH," ")."\r\n";
                        }else if($opt['payment_type'] == 'check'){
                                $pdf->ln();
                                $pdf->Cell(40, 0, "     Bank: ".$opt['card_number'], '', 0, 'L');
                            // $print_str .= $this->append_chars("     Bank: ".$opt['card_number'],"right",PAPER_WIDTH," ")."\r\n";
                        }else if($opt['payment_type'] == 'picc'){
                            $pdf->ln();
                            $pdf->Cell(40, 0, "     Name: ".$opt['card_number'], '', 0, 'L');
                            // $print_str .= $this->append_chars("     Name: ".$opt['card_number'],"right",PAPER_WIDTH," ")."\r\n";
                        }else{
                            if (!empty($opt['card_number'])) {
                                if (!empty($opt['card_type'])) {
                                    $pdf->ln();
                                    $pdf->Cell(40, 0, "  Card Type: ".$opt['card_type'], '', 0, 'L');
                                    // $print_str .= $this->append_chars("  Card Type: ".$opt['card_type'],"right",PAPER_WIDTH," ")."\r\n";
                                }
                                $print_str .= $this->append_chars("  Card #: ".$opt['card_number'],"right",PAPER_WIDTH," ")."\r\n";
                                if (!empty($opt['approval_code']))
                                    $pdf->ln();
                                    $pdf->Cell(40, 0, "  Approval #: ".$opt['approval_code'], '', 0, 'L');
                                    // $print_str .= $this->append_chars("  Approval #: ".$opt['approval_code'],"right",PAPER_WIDTH," ")."\r\n";
                            }
                        }
                        // $pay_total += $opt['amount'];
                        // if($opt['payment_type'] == 'gc'){
                        //     $gft_ctr++;
                        // }
                        // else
                        //     $nor_ctr++;
                        if($opt['payment_type'] == 'gc'){
                            if($opt['amount'] > $opt['to_pay']){
                                $total_payment  += $opt['to_pay'];
                            }else{
                                $total_payment  += $opt['amount'];
                            }
                        }
                        else{
                            $total_payment  += $opt['amount'];
                        }


                      

                        
                    }

                    
                    //  if(PRINT_VERSION=="V2" && !$return_print_str){

                    //     $print_str .= "STARTCUT==============================ENDCUT";
                    // }else{
                    //     $print_str .="<b>";
                    // }

                     // $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ");     
                     // $pdf->ln();
                    if($gft_ctr == 1 && $nor_ctr == 0){
                        $pdf->ln();
                        $pdf->Cell(40, 0, "Change", '', 0, 'L');
                        $pdf->Cell(30, 0, number_format(0,2), '', 0, 'R');
                        // $print_str .= $this->append_chars('Change',"right",PAPER_DET_COL_2," ").$this->append_chars(number_format(0,2),"left",PAPER_DET_COL_3," ")."\r\n";
                    }else{
                        $pdf->ln();
                        $pdf->Cell(40, 0, "Change", '', 0, 'L');
                        $pdf->Cell(30, 0, number_format(abs($total_payment - $order['amount']),2), '', 0, 'R');
                        // $print_str .= $this->append_chars('Change',"right",PAPER_DET_COL_2," ").$this->append_chars(number_format(abs($total_payment - $order['amount']),2),"left",PAPER_DET_COL_3," ")."\r\n";
                    }
                    $pdf->ln();
                    $pdf->Cell(50, 0, "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _", '', 0, 'L');
                    $pdf->ln();
                       //  if(PRINT_VERSION=="V2" && !$return_print_str){
                       //    $print_str .= "STARTCUT==============================ENDCUT";

                       // }else{
                       //   $print_str .="</b>";
                       // }
                    $pdf->ln();
                    $pdf->Cell(40, 0, ucwords("VATABLE SALES"), '', 0, 'L');
                    $pdf->Cell(30, 0, num($vat_sales), '', 0, 'R');
                    $pdf->ln();
                    $pdf->Cell(40, 0, ucwords("VATEXEMPT"), '', 0, 'L');
                    $pdf->Cell(30, 0, number_format($no_tax_amt-$total_discounts_non_vat,2), '', 0, 'R');
                    $pdf->ln();
                    $pdf->Cell(40, 0, ucwords("Zero Rated VAT"), '', 0, 'L');
                    $pdf->Cell(30, 0, number_format($zero_rated_amt,2), '', 0, 'R');
                    // $print_str .= PAPER_LINE_SINGLE."\r\n";
                     // $print_str .= "\r\n".$this->append_chars(" " ,"right",PAPER_DET_COL_1," ").$this->append_chars(ucwords("VATABLE SALES"),"right",PAPER_DET_COL_2," ").$this->append_chars(num($vat_sales),"left",PAPER_DET_COL_3," ")."\r\n";
                    // $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ").$this->append_chars(ucwords("VATEXEMPT"),"right",PAPER_DET_COL_2," ").$this->append_chars(number_format($no_tax_amt-$total_discounts_non_vat,2),"left",PAPER_DET_COL_3," ")."\r\n";
                    // $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ").$this->append_chars(ucwords("Zero Rated VAT"),"right",PAPER_DET_COL_2," ").$this->append_chars(number_format($zero_rated_amt,2),"left",PAPER_DET_COL_3," ")."\r\n";
                    
                       if($tax > 0){
                            foreach ($tax as $tx) {
                                 $pdf->ln();
                                 $pdf->Cell(40, 0, $tx['name']." Amount", '', 0, 'L');
                                 $pdf->Cell(30, 0, number_format(abs($tx['amount']),2), '', 0, 'R');
                               // $print_str .=  $this->append_chars(" " ,"right",PAPER_DET_COL_1," ").$this->append_chars($tx['name']." Amount","right",PAPER_DET_COL_2," ").$this->append_chars(number_format(abs($tx['amount']),2),"left",PAPER_DET_COL_3," ")."\r\n";
                            }
                        }
                    // $print_str .= "\r\n";
                    $pdf->ln();
                    

                    $total_discount_snr_count = $total_discount_pwd_count = $total_discount_count = 0;
                    foreach ($discounts as $dcs_ci => $dcs) {
                            if($dcs_ci == 'SNDISC' || $dcs_ci == 'PWDISC'){
                                // echo "<pre>",print_r($dcs['persons']),"</pre>";die();
                                foreach ($dcs['persons'] as $code => $dcp) {   
                                    if($dcs_ci == 'SNDISC'){
                                        $total_discount_snr_count += 1;                            
                                    } 
                                    if($dcs_ci == 'PWDISC'){

                                        $total_discount_pwd_count += 1;                            
                                    } 
                                                                   }
                            }
                    }

                     $pdf->ln();
                     $pdf->Cell(40, 0, ucwords("Total Guest Count"), '', 0, 'L');
                     $pdf->Cell(30, 0, (($order['guest'] >0) ? $order['guest']: 1), '', 0, 'R');
                    // $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ").$this->append_chars(ucwords("Total Guest Count"),"right",PAPER_DET_COL_2," ").$this->append_chars((($order['guest'] >0) ? $order['guest']: 1),"left",PAPER_DET_COL_3," ")."\r\n";
                    
                    if($total_discount_snr_count > 0){
                         $pdf->ln();
                         $pdf->Cell(40, 0, ucwords("Total SC Guest Count"), '', 0, 'L');
                         $pdf->Cell(30, 0, $total_discount_snr_count, '', 0, 'R');
                        // $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ").$this->append_chars(ucwords("Total SC Guest Count"),"right",PAPER_DET_COL_2," ").$this->append_chars($total_discount_snr_count,"left",PAPER_DET_COL_3," ")."\r\n\r\n";
                    }

                    if($total_discount_pwd_count > 0){
                         $pdf->ln();
                         $pdf->Cell(40, 0, ucwords("Total PWD Guest Count:"), '', 0, 'L');
                         $pdf->Cell(30, 0, $total_discount_pwd_count, '', 0, 'R');
                        $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ").$this->append_chars(ucwords("Total PWD Guest Count:"),"right",PAPER_DET_COL_2," ").$this->append_chars($total_discount_pwd_count,"left",PAPER_DET_COL_3," ")."\r\n\r\n";
                    }

                    
                    $pdf->ln();
                    $pdf->Cell(50, 0, "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _", '', 0, 'L');
                    $pdf->ln();
                    // $print_str .= PAPER_LINE_SINGLE."\r\n\r\n";

                    if ($include_footer) {
                        $rec_footer = "";
                        if($branch['rec_footer'] != ""){
                            $wrap = str_replace ("<br>","\r\n", $branch['rec_footer'] );
                            $exp = explode("\r\n", $wrap);
                            foreach ($exp as $v) {
                                $wrap2 = wordwrap($v,35,"|#|");
                                $exp2 = explode("|#|", $wrap2);  
                                foreach ($exp2 as $v2) {
                                    $pdf->ln();
                                     $pdf->Cell(50, 0, $v2, '', 0, 'C');
                                    // $rec_footer .= $this->align_center($v2,PAPER_WIDTH," ")."\r\n";
                                }
                            }                        
                        }
                        if($order['inactive'] == 0){
                            // $print_str .= $rec_footer;
                        }    
                            $pdf->ln();
                            // $print_str .= "\r\n";
                            // $print_str .= $this->align_center("POS Vendor Details",PAPER_WIDTH," ")."\r\n";
                            // $print_str .= PAPER_LINE."\r\n";
                            $pdf->Cell(50, 0, "PointOne Integrated Tech., Inc.", '', 0, 'C');
                            $pdf->ln();
                            $pdf->Cell(50, 0, "1409 Prestige Tower", '', 0, 'C');
                            $pdf->ln();
                            $pdf->Cell(50, 0, "Ortigas Center, Pasig City", '', 0, 'C');
                            $pdf->ln();
                            $pdf->Cell(50, 0, "TIN: 008543444-000", '', 0, 'L');
                            $pdf->ln();
                            // $print_str .= $this->align_center("PointOne Integrated Tech., Inc.",PAPER_WIDTH," ")."\r\n";
                            // $print_str .= $this->align_center("1409 Prestige Tower",PAPER_WIDTH," ")."\r\n";
                            // $print_str .= $this->align_center("Ortigas Center, Pasig City",PAPER_WIDTH," ")."\r\n";
                            // $print_str .= $this->append_chars('TIN:',"right",PAPER_RD_COL_3," ")
                            //            .  $this->append_chars('008543444-000',"left",PAPER_DET_SUBCOL," ")."\r\n";
                            if($branch['accrdn'] != ""){

                                $pdf->Cell(40, 0, "Accred. No." .$branch['accrdn'], '', 0, 'L');
                                $pdf->ln();
                                // $print_str .= $this->append_chars('Accred. No.',"right",PAPER_RD_COL_3," ")
                                       // .  $this->append_chars($branch['accrdn'],"left",PAPER_DET_SUBCOL," ")."\r\n";
                                // $print_str .= $this->align_center('ACCRDN:',PAPER_WIDTH," ")."\r\n".$this->align_center($branch['accrdn'],PAPER_WIDTH," ")."\r\n";
                            }
                            $pdf->Cell(50, 0, "Permit. No." .$branch['permit_no'], '', 0, 'L');
                            $pdf->ln();
                            $pdf->Cell(50, 0, "Date Issued: December 22, 2014", '', 0, 'L');
                            $pdf->ln();
                            $pdf->Cell(50, 0, "Valid Until: ".date2Word('December 22, 2025'), '', 0, 'L');
                            $pdf->ln();
                            // $print_str .= $this->append_chars('Permit No.:',"right",PAPER_RD_COL_3," ")
                                       // .  $this->append_chars($branch['permit_no'],"left",PAPER_DET_SUBCOL," ")."\r\n";
                            // $print_str .= $this->append_chars('POS Version:',"right",PAPER_RD_COL_3," ")
                            //            .  $this->append_chars('iPos ver 1.0',"left",PAPER_DET_SUBCOL," ")."\r\n";
                            // $print_str .= $this->append_chars('Date Issued:',"right",PAPER_RD_COL_3," ")
                                       // .  $this->append_chars('December 22, 2014',"left",PAPER_DET_SUBCOL," ")."\r\n";
                                       // .  $this->append_chars(date2Word($order['datetime']),"right",PAPER_TOTAL_COL_2," ")."\r\n";
                            // $print_str .= $this->append_chars('Valid Until:',"right",PAPER_RD_COL_3," ")
                                       // .  $this->append_chars(date2Word('December 22, 2025'),"left",PAPER_DET_SUBCOL," ")."\r\n";
                                       // .  $this->append_chars(date2Word( date('Y-m-d',strtotime($order['datetime'].' +5 year')) ),"right",PAPER_TOTAL_COL_2," ")."\r\n";

                        if($branch['contact_no'] != ""){
                            $pdf->Cell(50, 0, "For feedback, please call us at", '', 0, 'C');
                            $pdf->ln();
                            $pdf->Cell(50, 0, $branch['contact_no'], '', 0, 'C');
                            $pdf->ln();
                            // $print_str .= $this->align_center("For feedback, please call us at",PAPER_WIDTH," ")."\r\n"
                                         // .$this->align_center($branch['contact_no'],PAPER_WIDTH," ")."\r\n";
                        }
                        if($branch['email'] != ""){
                            $pdf->Cell(50, 0, "Or Email us at", '', 0, 'C');
                            $pdf->ln();
                            $pdf->Cell(50, 0, $branch['email'], '', 0, 'C');
                            $pdf->ln();
                            // $print_str .= $this->align_center("Or Email us at",PAPER_WIDTH," ")."\r\n" 
                                       // .$this->align_center($branch['email'],PAPER_WIDTH," ")."\r\n";
                        }
                        if($branch['website'] != ""){
                            $pdf->Cell(50, 0, "Please visit us at", '', 0, 'C');
                            $pdf->ln();
                            $pdf->Cell(50, 0, $branch['website'], '', 0, 'C');
                            $pdf->ln();
                            // $print_str .= $this->align_center("Please visit us at",PAPER_WIDTH," ")."\r\n"
                                         // .$this->align_center($branch['website'],PAPER_WIDTH," ")."\r\n";
                        }
                        $pdf->ln();
                        foreach ($discounts as $dcs_ci => $dcs) {
                            if($dcs_ci == 'SNDISC' || $dcs_ci == 'PWDISC'){
                                foreach ($dcs['persons'] as $code => $dcp) {
                                    // ."\r\n"
                                    // ."\r\n"
                                    if($dcs_ci == 'SNDISC'){
                                        $pdf->Cell(40, 0, "Senior Citizen TIN: ".$dcp['code'], '', 0, 'L');
                                        $pdf->ln();
                                        $pdf->Cell(40, 0, "OSCA ID No.: ".$dcp['name'], '', 0, 'L');
                                        $pdf->ln();
                                        // $print_str .= "\r\n".$this->append_chars("Senior Citizen TIN: ".$dcp['code'],"right",PAPER_TOTAL_COL_1," ");
                                        // $print_str .= "\r\n".$this->append_chars("OSCA ID No.: ".$dcp['name'],"right",PAPER_TOTAL_COL_1," ");
                                    }
                                    if($dcs_ci == 'PWDISC'){
                                        $pdf->Cell(40, 0, "PWD TIN: ".$dcp['code'], '', 0, 'L');
                                        $pdf->ln();
                                        $pdf->Cell(40, 0, "OSCA ID No.: ".$dcp['name'], '', 0, 'L');
                                        $pdf->ln();
                                        // $print_str .= "\r\n".$this->append_chars("PWD TIN: ".$dcp['code'],"right",PAPER_TOTAL_COL_1," ");
                                        // $print_str .= "\r\n".$this->append_chars("OSCA ID No.: ".$dcp['name'],"right",PAPER_TOTAL_COL_1," ");
                                    }

                                   

                                }
                            }
                             if($dcs_ci == 'D1018'){
                                        $pdf->Cell(40, 0, "Name: ".$dcp['name'], '', 0, 'L');
                                        $pdf->ln();
                                        $pdf->Cell(40, 0, "Address:", '', 0, 'L');
                                        $pdf->ln();
                                        $pdf->Cell(40, 0, "TIN: ".$dcp['code'], '', 0, 'L');
                                        $pdf->ln();
                                        $pdf->Cell(40, 0, "VIP ID NO:", '', 0, 'L');
                                        $pdf->ln();
                                        //  $print_str .= "\r\n".$this->append_chars("Name: ".$dcp['name'],"right",PAPER_TOTAL_COL_1," ");
                                        // $print_str .= "\r\n".$this->append_chars("Address: ","right",PAPER_TOTAL_COL_1," ");
                                        // $print_str .= "\r\n".$this->append_chars("TIN: ".$dcp['code'],"right",PAPER_TOTAL_COL_1," ");
                                        // $print_str .= "\r\n".$this->append_chars("VIP ID NO: ","right",PAPER_TOTAL_COL_1," ");

                            }

                            if($dcs_ci == 'D1006'){
                                        $pdf->Cell(40, 0, "PWD TIN:", '', 0, 'L');
                                        $pdf->ln();
                                        $pdf->Cell(40, 0, "National Athlete ID No.: ", '', 0, 'L');
                                        $pdf->ln();
                                         // $print_str .= "\r\n".$this->append_chars("PWD TIN: ","right",PAPER_TOTAL_COL_1," ");
                                        // $print_str .= "\r\n".$this->append_chars("National Athlete ID No.: ","right",PAPER_TOTAL_COL_1," ");
                                     

                            }

                        }

                        if($zero_rated_amt > 0){
                                $pdf->Cell(40, 0, "Name:", '', 0, 'L');
                                $pdf->ln();
                                $pdf->Cell(40, 0, "Address: ", '', 0, 'L');
                                $pdf->ln();
                                $pdf->Cell(40, 0, "TIN:", '', 0, 'L');
                                $pdf->ln();
                                $pdf->Cell(40, 0, "Diplomat ID No.: ", '', 0, 'L');
                                $pdf->ln();
                                // $print_str .= "\r\n".$this->append_chars("Name: ","right",PAPER_TOTAL_COL_1," ");
                                // $print_str .= "\r\n".$this->append_chars("Address: ","right",PAPER_TOTAL_COL_1," ");
                                // $print_str .= "\r\n".$this->append_chars("TIN: ","right",PAPER_TOTAL_COL_1," ");
                                // $print_str .= "\r\n".$this->append_chars("Diplomat ID No: ","right",PAPER_TOTAL_COL_1," ");
                        }
                        
                            if(isset($py_ref) && !empty($py_ref)){
                                $pdf->Cell(40, 0, "Name:", '', 0, 'L');
                                $pdf->ln();
                                $pdf->Cell(40, 0, "Address: ", '', 0, 'L');
                                $pdf->ln();
                                $pdf->Cell(40, 0, "TIN:", '', 0, 'L');
                                $pdf->ln();
                                $pdf->Cell(40, 0, "GC Serial No.: ".$py_ref, '', 0, 'L');
                                $pdf->ln();
                                // $print_str .= "\r\n".$this->append_chars("Name: ","right",PAPER_TOTAL_COL_1," ");
                                // $print_str .= "\r\n".$this->append_chars("Address: ","right",PAPER_TOTAL_COL_1," ");
                                // $print_str .= "\r\n".$this->append_chars("TIN: ","right",PAPER_TOTAL_COL_1," ");
                                // $print_str .= "\r\n".$this->append_chars("GC Serial No: ".$py_ref,"right",PAPER_TOTAL_COL_1," ");

                            }
                        // $print_str .= PAPER_LINE."\r\n";
                        if($order['inactive'] == 0){
                            // $print_str .= "\r\n";
                            $pdf->ln();
                          
                        }

                    }

                  
                    if (!empty($order['ref']) && $order['inactive'] == 0) {
                        // $print_str .= $this->align_center(PAPER_LINE,PAPER_WIDTH," ");
                        $pdf->Cell(40, 0, "_________________________________", '', 0, 'L');
                        $pdf->ln();
                        $pdf->Cell(40, 0, "Customer Signature Over Printed Name", '', 0, 'L');
                        $pdf->ln();
                        // $print_str .= "\r\n\r\n".$this->append_chars("_________________________________","right",PAPER_TOTAL_COL_1," ");
                        // $print_str .= "\r\n".$this->append_chars("Customer Signature Over Printed Name","right",PAPER_TOTAL_COL_1," ");
                        // $print_str .= $this->align_center(PAPER_LINE,PAPER_WIDTH," ");
                        // $print_str .= "\r\n";
                    }
                    $pdf->ln();
                    $pdf->Cell(70, 0, "THIS INVOICE/RECEIPT WILL BE VALID FOR ", '', 0, 'C');
                    $pdf->ln();
                    $pdf->Cell(70, 0, "FIVE(5) YEARS FROM THE DATE OF THE PERMIT TO USE ", '', 0, 'C');
                    $pdf->ln();
                    $pdf->Cell(70, 0, "THIS INVOICE/RECEIPT WILL BE VALID FOR", '', 0, 'C');
                    $pdf->ln();
                    $pdf->Cell(70, 0, "FIVE(5) YEARS FROM THE DATE OF THE PERMIT", '', 0, 'C');
                    $pdf->ln();
                    $pdf->Cell(70, 0, "TO USE", '', 0, 'C');
                    $pdf->ln();
                    // $print_str .="\r\n".$this->align_center("THIS INVOICE/RECEIPT WILL BE VALID FOR FIVE(5) YEARS FROM THE DATE OF THE PERMIT TO USE",PAPER_WIDTH," ")."\r\n";
                    //     $print_str .= "\r\n".$this->append_chars("THIS INVOICE/RECEIPT WILL BE VALID FOR","right",PAPER_TOTAL_COL_1," ");
                    //     $print_str .= "\r\n".$this->append_chars("FIVE(5) YEARS FROM THE DATE OF THE PERMIT","right",PAPER_TOTAL_COL_1," ");
                    //      $print_str .= "\r\n".$this->append_chars("TO USE","right",PAPER_TOTAL_COL_1," ");
                
                } 

                 
            
                // if (!empty($payments)) {

                   
                // } else {
                //     $is_billing = true;
                //     $print_str .= "\r\n".$this->append_chars("","right",PAPER_WIDTH,"=");
                //     if(PRINT_VERSION == 'V1'){
                //         $print_str .= "\r\n\r\n".$this->append_chars("Billing Amount","right",PAPER_TOTAL_COL_1," ").$this->append_chars("P ".number_format($order['amount'],2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                //     }
                //     if(is_array($splits)){
                //         $print_str .= $this->append_chars("Split Amount by ".$splits['by'],"right",PAPER_TOTAL_COL_1," ").$this->append_chars("P ".number_format($splits['total'],2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                //     }


                //     //for senior deaitls with signature forbiliing
                //     foreach ($discounts as $dcs_ci => $dcs) {
                //         if($dcs_ci == 'SNDISC' || $dcs_ci == 'PWDISC'){
                //             // $print_str .= PAPER_LINE."\r\n";
                //             $print_str .= "\r\n";
                //             $print_str .= $this->align_center("OSCA/PWD Details",PAPER_WIDTH," ")."\r\n";
                //             // $print_str .= PAPER_LINE."\r\n";
                //             // $print_str .= $this->align_center(PAPER_LINE,42," ");
                //             break;
                //         }
                //     }
                //     foreach ($discounts as $dcs_ci => $dcs) {
                //         if($dcs_ci == 'SNDISC' || $dcs_ci == 'PWDISC'){
                //             foreach ($dcs['persons'] as $code => $dcp) {
                //                 // ."\r\n"
                //                 // ."\r\n"
                //                 $print_str .= "\r\n".$this->append_chars("ID NO      : ".$dcp['code'],"right",PAPER_TOTAL_COL_1," ");
                //                 $print_str .= "\r\n".$this->append_chars("NAME       : ".$dcp['name'],"right",PAPER_TOTAL_COL_1," ");
                //                 $print_str .= "\r\n".$this->append_chars("ADDRESS    : ","right",PAPER_TOTAL_COL_1," ");
                //                 $print_str .= "\r\n".$this->append_chars("SIGNATURE  : ","right",PAPER_TOTAL_COL_1," ");
                //                 $print_str .= "\r\n".$this->append_chars("             ____________________","right",PAPER_TOTAL_COL_1," ")."\r\n";
                //                 // $print_str .= "\r\n".$this->append_chars(asterisks($dcp['code']),"right",28," ")."\r\n";
                //             }
                //         }
                //     }


                //     if ($include_footer) {
                //         // $print_str .= "\r\n\r\n";
                //         if($branch['contact_no'] != ""){
                //             $print_str .= $this->align_center("For feedback, please call us at",PAPER_WIDTH," ")."\r\n"
                //                          .$this->align_center($branch['contact_no'],PAPER_WIDTH," ")."\r\n";
                //         }
                //         if($branch['email'] != ""){
                //             $print_str .= $this->align_center("Or Email us at",PAPER_WIDTH," ")."\r\n" 
                //                        .$this->align_center($branch['email'],PAPER_WIDTH," ")."\r\n";
                //         }
                //         if($branch['website'] != "")
                //             $print_str .= $this->align_center("Please visit us at \r\n".$branch['website'],PAPER_WIDTH," ")."\r\n";
                //     }

                // }

             


            }
            //end of showing for cancelled trans
        
        $pdf->Output('sales_report.pdf', 'I');
    }

    public function get_branch_details($asJson=true){
       $this->load->model('dine/setup_model');
       $details = $this->setup_model->get_branch_details();
       $det = array();
       foreach ($details as $res) {
           $det = array(
                    "id"=>$res->branch_id,
                    "code"=>$res->branch_code,
                    "name"=>$res->branch_name,
                    "desc"=>$res->branch_desc,
                    "contact_no"=>$res->contact_no,
                    "delivery_no"=>$res->delivery_no,
                    "address"=>$res->address,
                    "base_location"=>$res->base_location,
                    "currency"=>$res->currency,
                    "tin"=>$res->tin,
                    "machine_no"=>$res->machine_no,
                    "bir"=>$res->bir,
                    "permit_no"=>$res->permit_no,
                    "serial"=>$res->serial,
                    "accrdn"=>$res->accrdn,
                    "email"=>$res->email,
                    "website"=>$res->website,
                    "pos_footer"=>$res->pos_footer,
                    "rec_footer"=>$res->rec_footer,
                    "brand"=>$res->brand,
                    "is_multibrand"=>$res->is_multibrand,
                    "layout"=>base_url().'uploads/'.$res->image
                  );
       }
       if($asJson)
            echo json_encode($det);
        else
            return $det;
    }
    public function get_order($asJson=true,$sales_id=null){
        /*
         * -------------------------------------------
         *   Load receipt data
         * -------------------------------------------
        */
        $this->load->model('dine/cashier_model');
        $orders = $this->cashier_model->get_trans_sales($sales_id);
        // echo $sales_id; 
        // echo "<pre>",print_r($orders),"</pre>";die();
        $order = array();
        $details = array();
        $details2 = array();
        $details_to = array();
        foreach ($orders as $res) {
            $order = array(
                "sales_id"=>$res->sales_id,
                'ref'=>$res->trans_ref,
                "type"=>$res->type,
                "table_id"=>$res->table_id,
                "table_name"=>$res->table_name,
                "guest"=>$res->guest,
                "serve_no"=>$res->serve_no,
                "user_id"=>$res->user_id,
                "customer_id"=>$res->customer_id,
                "customer_name"=>$res->customer_name,
                "name"=>$res->username,
                "terminal_id"=>$res->terminal_id,
                "terminal_name"=>$res->terminal_name,
                "terminal_code"=>$res->terminal_code,
                "shift_id"=>$res->shift_id,
                "datetime"=>$res->datetime,
                "update_date"=>$res->update_date,
                "amount"=>$res->total_amount,
                "balance"=>round($res->total_amount,2) - round($res->total_paid,2),
                "paid"=>$res->paid,
                "printed"=>$res->printed,
                "inactive"=>$res->inactive,
                "waiter_id"=>$res->waiter_id,
                "void_ref"=>$res->void_ref,
                "reason"=>$res->reason,
                "ref_no"=>$res->ref_no,
                "depo_amount"=>$res->depo_amount,
                "cname"=>$res->fname,
                "waiter_name"=>ucwords(strtolower($res->waiterfname." ".$res->waitermname." ".$res->waiterlname." ".$res->waitersuffix)),
                "waiter_username"=>ucwords(strtolower($res->waiterusername)),
                "kitchen_display_status" => $res->kitchen_display_status,
                "dispatch_display_status" => $res->dispatch_display_status,
                "completed_display_status" => $res->completed_display_status
                // "memo"=>ucwords(strtolower($res->memo))
                // "pay_type"=>$res->pay_type,
                // "pay_amount"=>$res->pay_amount,
                // "pay_ref"=>$res->pay_ref,
                // "pay_card"=>$res->pay_card,
            );
        }
        $order_menus = $this->cashier_model->get_trans_sales_menus(null,array("trans_sales_menus.sales_id"=>$sales_id));
        // echo "<pre>",print_r($order_menus),"</pre>";die();
        $order_items = $this->cashier_model->get_trans_sales_items(null,array("trans_sales_items.sales_id"=>$sales_id));
        $order_mods = $this->cashier_model->get_trans_sales_menu_modifiers(null,array("trans_sales_menu_modifiers.sales_id"=>$sales_id));
        $order_submods = $this->cashier_model->get_trans_sales_menu_submodifiers(null,array("trans_sales_menu_submodifiers.sales_id"=>$sales_id));
        $sales_discs = $this->cashier_model->get_trans_sales_discounts(null,array("trans_sales_discounts.sales_id"=>$sales_id));
        $sales_tax = $this->cashier_model->get_trans_sales_tax(null,array("trans_sales_tax.sales_id"=>$sales_id));
        $sales_payments = $this->cashier_model->get_trans_sales_payments(null,array("trans_sales_payments.sales_id"=>$sales_id));
        $sales_no_tax = $this->cashier_model->get_trans_sales_no_tax(null,array("trans_sales_no_tax.sales_id"=>$sales_id));
        $sales_zero_rated = $this->cashier_model->get_trans_sales_zero_rated(null,array("trans_sales_zero_rated.sales_id"=>$sales_id));
        $sales_charges = $this->cashier_model->get_trans_sales_charges(null,array("trans_sales_charges.sales_id"=>$sales_id));
        $sales_local_tax = $this->cashier_model->get_trans_sales_local_tax(null,array("trans_sales_local_tax.sales_id"=>$sales_id));
        $payment_fields = $this->cashier_model->get_trans_sales_payment_fields(null,array("trans_sales_payment_fields.sales_id"=>$sales_id));
        $pays = array();
        foreach ($sales_payments as $py) {
            $pays[$py->payment_id] = array(
                    "sales_id"      => $py->sales_id,
                    "payment_type"  => $py->payment_type,
                    "amount"        => $py->amount,
                    "to_pay"        => $py->to_pay,
                    "reference"     => $py->reference,
                    "card_type"     => $py->card_type,
                    "card_number"   => $py->card_number,
                    "approval_code"   => $py->approval_code,
                    "user_id"       => $py->user_id,
                    "datetime"      => $py->datetime,
                );
        }
        foreach ($order_menus as $men) {
            $details[$men->line_id] = array(
                "id"=>$men->sales_menu_id,
                "menu_id"=>$men->menu_id,
                "name"=>$men->menu_name,
                "code"=>$men->menu_code,
                "price"=>$men->price,
                "qty"=>$men->qty,
                "no_tax"=>$men->no_tax,
                "discount"=>$men->discount,
                "remarks"=>$men->remarks,
                "free_user_id"=>$men->free_user_id,
                "nocharge"=>$men->nocharge,
                "is_takeout"=>$men->is_takeout,
                "kitchen_slip_printed"=>$men->kitchen_slip_printed,
                "ref_line_id"=>$men->ref_line_id,
                "is_promo"=>$men->is_promo,
                "promo_type"=>$men->promo_type,
                "free_promo_amount"=>$men->free_promo_amount,
                "menu_category_id"=>$men->menu_category_id,
                "pf_id"=>$men->pf_id,
            );
            $mods = array();
            foreach ($order_mods as $mod) {
                if($mod->line_id == $men->line_id){
                    $mods[$mod->sales_mod_id] = array(
                        "id"=>$mod->mod_id,
                        "sales_mod_id"=>$mod->sales_mod_id,
                        "mod_group_id"=>$mod->mod_group_id,
                        "line_id"=>$mod->line_id,
                        "name"=>$mod->mod_name,
                        "price"=>$mod->price,
                        "qty"=>$mod->qty,
                        "discount"=>$mod->discount,
                        "mod_line_id"=>$mod->mod_line_id,
                        "kitchen_slip_printed"=>$mod->kitchen_slip_printed
                    );
                    $submods = array();
                    foreach ($order_submods as $submod) {
                        if($submod->mod_line_id == $mod->mod_line_id){

                            $where = array('mod_id'=>$submod->mod_id,'name'=>$submod->submod_name);
                            $gdet = $this->site_model->get_details($where,'modifier_sub');

                            $submods[$submod->sales_submod_id] = array(
                                "mod_id"=>$submod->mod_id,
                                "sales_submod_id"=>$submod->sales_submod_id,
                                // "mod_group_id"=>$mod->mod_group_id,
                                "line_id"=>$submod->line_id,
                                "name"=>$submod->submod_name,
                                "price"=>$submod->price,
                                "qty"=>$submod->qty,
                                "group"=>$gdet[0]->group,
                                // "discount"=>$mod->discount,
                                "mod_line_id"=>$submod->mod_line_id,
                                "kitchen_slip_printed"=>$submod->kitchen_slip_printed
                            );

                            $mods[$mod->sales_mod_id]['submodifiers'] = $submods;
                        }
                    }


                }
            }
            $details[$men->line_id]['modifiers'] = $mods;
        }
        // echo "<pre>",print_r($details),"</pre>";die();
        //for new os print
        foreach ($order_menus as $men) {
            if($men->is_takeout == 0){

                if(isset($details2[$men->menu_id])){
                // $details2[$men->menu_id]['qty'] += $men->qty;
                // if($details2[$men->menu_id]['remarks'] != ""){
                //     $details2[$men->menu_id]['remarks'] .= ', '.$men->remarks;
                // }else{
                //     $details2[$men->menu_id]['remarks'] = $men->remarks;   
                // }
                    $dets = $details2[$men->menu_id]['dets'];
                    $dets[$men->line_id] = array(
                        "qty"=>$men->qty,
                        "remarks"=>$men->remarks,
                        "line_id"=>$men->line_id,
                        "id"=>$men->sales_menu_id,
                        "kitchen_slip_printed"=>$men->kitchen_slip_printed 
                    );
                    $details2[$men->menu_id]['dets'] = $dets;

                }else{
                    $details2[$men->menu_id] = array(
                        "menu_id"=>$men->menu_id,
                        "name"=>$men->menu_name,
                        "code"=>$men->menu_code,
                        "price"=>$men->price
                    );
                    $dets = array();
                    $dets[$men->line_id] = array(
                        "qty"=>$men->qty,
                        "remarks"=>$men->remarks,
                        "line_id"=>$men->line_id,
                        "id"=>$men->sales_menu_id,
                        "kitchen_slip_printed"=>$men->kitchen_slip_printed 
                    );
                    $details2[$men->menu_id]['dets'] = $dets;
                }

                $mods = array();
                foreach ($order_mods as $mod) {
                    if($mod->line_id == $men->line_id){
                        $mods[$mod->sales_mod_id] = array(
                            "id"=>$mod->mod_id,
                            "sales_mod_id"=>$mod->sales_mod_id,
                            "mod_group_id"=>$mod->mod_group_id,
                            "line_id"=>$mod->line_id,
                            "name"=>$mod->mod_name,
                            "price"=>$mod->price,
                            "qty"=>$mod->qty,
                            "discount"=>$mod->discount,
                            "mod_line_id"=>$mod->mod_line_id,
                            "kitchen_slip_printed"=>$mod->kitchen_slip_printed
                        );

                        $submods = array();
                        foreach ($order_submods as $submod) {
                            if($submod->mod_line_id == $mod->mod_line_id){

                                $where = array('mod_id'=>$submod->mod_id,'name'=>$submod->submod_name);
                                $gdet = $this->site_model->get_details($where,'modifier_sub');

                                $submods[$submod->sales_submod_id] = array(
                                    "mod_id"=>$submod->mod_id,
                                    "sales_submod_id"=>$submod->sales_submod_id,
                                    // "mod_group_id"=>$mod->mod_group_id,
                                    "line_id"=>$submod->line_id,
                                    "name"=>$submod->submod_name,
                                    "price"=>$submod->price,
                                    "qty"=>$submod->qty,
                                    "group"=>$gdet[0]->group,
                                    // "discount"=>$mod->discount,
                                    "mod_line_id"=>$submod->mod_line_id,
                                    "kitchen_slip_printed"=>$submod->kitchen_slip_printed
                                );

                                $mods[$mod->sales_mod_id]['submodifiers'] = $submods;
                            }
                        }


                    }
                }
                // $details[$men->line_id]['modifiers'] = $mods;
                $details2[$men->menu_id]['dets'][$men->line_id]['modifiers'] = $mods;


            }else{
                //takeout sa dinein
                if(isset($details_to[$men->menu_id])){
                // $details2[$men->menu_id]['qty'] += $men->qty;
                // if($details2[$men->menu_id]['remarks'] != ""){
                //     $details2[$men->menu_id]['remarks'] .= ', '.$men->remarks;
                // }else{
                //     $details2[$men->menu_id]['remarks'] = $men->remarks;   
                // }
                    $dets = $details_to[$men->menu_id]['dets'];
                    $dets[$men->line_id] = array(
                        "qty"=>$men->qty,
                        "remarks"=>$men->remarks,
                        "line_id"=>$men->line_id,
                        "id"=>$men->sales_menu_id,
                        "kitchen_slip_printed"=>$men->kitchen_slip_printed 
                    );
                    $details_to[$men->menu_id]['dets'] = $dets;

                }else{
                    $details_to[$men->menu_id] = array(
                        "menu_id"=>$men->menu_id,
                        "name"=>$men->menu_name,
                        "code"=>$men->menu_code,
                        "price"=>$men->price
                    );
                    $dets = array();
                    $dets[$men->line_id] = array(
                        "qty"=>$men->qty,
                        "remarks"=>$men->remarks,
                        "line_id"=>$men->line_id,
                        "id"=>$men->sales_menu_id,
                        "kitchen_slip_printed"=>$men->kitchen_slip_printed 
                    );
                    $details_to[$men->menu_id]['dets'] = $dets;
                }

                $mods = array();
                foreach ($order_mods as $mod) {
                    if($mod->line_id == $men->line_id){
                        $mods[$mod->sales_mod_id] = array(
                            "id"=>$mod->mod_id,
                            "sales_mod_id"=>$mod->sales_mod_id,
                            "mod_group_id"=>$mod->mod_group_id,
                            "line_id"=>$mod->line_id,
                            "name"=>$mod->mod_name,
                            "price"=>$mod->price,
                            "qty"=>$mod->qty,
                            "discount"=>$mod->discount,
                            "mod_line_id"=>$mod->mod_line_id,
                            "kitchen_slip_printed"=>$mod->kitchen_slip_printed
                        );

                        $submods = array();
                        foreach ($order_submods as $submod) {
                            if($submod->mod_line_id == $mod->mod_line_id){

                                $where = array('mod_id'=>$submod->mod_id,'name'=>$submod->submod_name);
                                $gdet = $this->site_model->get_details($where,'modifier_sub');

                                $submods[$submod->sales_submod_id] = array(
                                    "mod_id"=>$submod->mod_id,
                                    "sales_submod_id"=>$submod->sales_submod_id,
                                    // "mod_group_id"=>$mod->mod_group_id,
                                    "line_id"=>$submod->line_id,
                                    "name"=>$submod->submod_name,
                                    "price"=>$submod->price,
                                    "qty"=>$submod->qty,
                                    "group"=>$gdet[0]->group,
                                    // "discount"=>$mod->discount,
                                    "mod_line_id"=>$submod->mod_line_id,
                                    "kitchen_slip_printed"=>$submod->kitchen_slip_printed
                                );

                                $mods[$mod->sales_mod_id]['submodifiers'] = $submods;
                            }
                        }


                    }
                }
                // $details[$men->line_id]['modifiers'] = $mods;
                $details_to[$men->menu_id]['dets'][$men->line_id]['modifiers'] = $mods;

            }
            
        }


        // echo "<pre>",print_r($details2),"</pre>";die();


        foreach ($order_items as $men){
            $details[$men->line_id] = array(
                "id"=>$men->sales_item_id,
                "menu_id"=>$men->item_id,
                "name"=>$men->name,
                "code"=>$men->code,
                "price"=>$men->price,
                "qty"=>$men->qty,
                "no_tax"=>$men->no_tax,
                "discount"=>$men->discount,
                "remarks"=>$men->remarks,
                "nocharge"=>$men->nocharge,
                "is_takeout"=>$men->is_takeout,
                "retail"=>1
            );
        }
        ### CHANGED #############
        $per_item_disc = false;
        foreach ($sales_discs as $dc) {
            if($dc->items != ""){
                $per_item_disc = true;
            }
        }
        // var_dump($per_item_disc); die();
        if($per_item_disc){
            $discounts = array();
            $persons = array();
            foreach ($sales_discs as $dc) {
                $discounts[$dc->items] = array(
                        "no_tax"  => $dc->no_tax,
                        "guest" => $dc->guest,
                        "disc_rate" => $dc->disc_rate,
                        "disc_id" => $dc->disc_id,
                        "disc_code" => $dc->disc_code,
                        "disc_type" => $dc->type,
                        "amount" => $dc->amount,
                        "fix" => $dc->fix,
                        "name" => $dc->name,
                        "code" => $dc->code,
                        "bday" => $dc->bday,
                        "items" => $dc->items,
                        "openamt" => 0,
                        // "persons" => array()
                );
            }
        }else{
            $discounts = array();
            $persons = array();
            foreach ($sales_discs as $dc) {
                $openamt = 0;
                if($dc->fix == 1 && $dc->disc_rate == 0){
                    $openamt = $dc->amount;
                }

                $discounts[$dc->disc_code] = array(
                        "no_tax"  => $dc->no_tax,
                        "guest" => $dc->guest,
                        "disc_rate" => $dc->disc_rate,
                        "disc_id" => $dc->disc_id,
                        "disc_code" => $dc->disc_code,
                        "disc_type" => $dc->type,
                        "fix" => $dc->fix,
                        "items" => $dc->items,
                        "openamt" => $openamt,
                        "persons" => array()
                );
            }
            foreach ($sales_discs as $dc) {
                $pcode = $dc->code;
                $bday = "";
                if($dc->bday != "")
                    $bday = sql2Date($dc->bday);
                $person = array(
                    "name"  => $dc->name,
                    "code"  => $dc->code,
                    "bday"  => $bday,
                    "amount" => $dc->amount,
                    "disc_rate" => $dc->disc_rate,
                );
                if(isset($discounts[$dc->disc_code])){
                    $dscp =  $discounts[$dc->disc_code]['persons'];
                    $dscp[$pcode] = $person;
                    $discounts[$dc->disc_code]['persons'] = $dscp;
                }
            }
        }
        ### CHANGED #############
        $tax = array();
        foreach ($sales_tax as $tx) {
            $tax[$tx->sales_tax_id] = array(
                    "sales_id"  => $tx->sales_id,
                    "name"  => $tx->name,
                    "rate" => $tx->rate,
                    "amount" => $tx->amount
                );
        }
        $no_tax = array();
        foreach ($sales_no_tax as $nt) {
            $no_tax[$nt->sales_no_tax_id] = array(
                "sales_id" => $nt->sales_id,
                "amount" => $nt->amount,
            );
        }
        $zero_rated = array();
        foreach ($sales_zero_rated as $zt) {
            $zero_rated[$zt->sales_zero_rated_id] = array(
                "sales_id" => $zt->sales_id,
                "amount" => $zt->amount,
                "name" => $zt->name,
                "card_no" => $zt->card_no
            );
        }
        $local_tax = array();
        foreach ($sales_local_tax as $lt) {
            $local_tax[$lt->sales_local_tax_id] = array(
                "sales_id" => $lt->sales_id,
                "amount" => $lt->amount,
            );
        }
        $charges = array();
        foreach ($sales_charges as $ch) {
            $charges[$ch->charge_id] = array(
                    "name"  => $ch->charge_name,
                    "code"  => $ch->charge_code,
                    "amount"  => $ch->rate,
                    "absolute" => $ch->absolute,
                    "total_amount" => $ch->amount,
                    "rate"=>$ch->rate
                );
        }
        $pfields = array();
        foreach ($payment_fields as $pf) {
            $pfields[$pf->field_id] = array(
                    "field_name"  => $pf->field_name,
                    "value"  => $pf->value,
                );
        }
        if($asJson)
            echo json_encode(array('order'=>$order,"details"=>$details,"discounts"=>$discounts,"taxes"=>$tax,"no_tax"=>$no_tax,"zero_rated"=>$zero_rated,"payments"=>$pays,"charges"=>$charges,"local_tax"=>$local_tax,"details2"=>$details2,"details_to"=>$details_to,"pfields"=>$pfields));
        else
            return array('order'=>$order,"details"=>$details,"discounts"=>$discounts,"taxes"=>$tax,"no_tax"=>$no_tax,"zero_rated"=>$zero_rated,"payments"=>$pays,"charges"=>$charges,"local_tax"=>$local_tax,"details2"=>$details2,"details_to"=>$details_to,"pfields"=>$pfields);
    }
    public function total_trans($asJson=true,$cart=null,$disc_cart=null,$charge_cart=null,$zero_rated=null){
            $counter = sess('counter');
            $zero_r = 0;
            // if(is_array($zero_rated)){
            //      // && isset($zero_rated['amount']) && $zero_rated['amount'] > 0
            //      foreach ($zero_rated as $zid => $opt) {
            //          if($opt['amount'] > 0){
            //             $counter['zero_rated'] = 1;
            //             break;
            //          }
            //      }            
            // }
            $trans_cart = array();
            if($this->session->userData('trans_cart')){
                $trans_cart = $this->session->userData('trans_cart');
            }
            $trans_mod_cart = array();
            if($this->session->userData('trans_mod_cart')){
                $trans_mod_cart = $this->session->userData('trans_mod_cart');
                // echo var_dump($trans_mod_cart);
                // return false;
            }
            $trans_submod_cart = array();
            if($this->session->userData('trans_submod_cart')){
                $trans_submod_cart = $this->session->userData('trans_submod_cart');
                
            }
            if(is_array($cart)){
                $trans_cart = $cart;
            }

            // echo '<pre>',print_r($trans_cart),'</pre>';

            $total = 0;
            $gross = 0;
            $discount = 0;
            $zero_rated = 0;
            $vat_sales = 0;
            $non_vat_sales = 0;
            $cart_total_qty = 0;
            $total_no_charge = 0;
            $charge_used = array();
            $alcohol = 0;
            if(count($trans_cart) > 0){
                foreach ($trans_cart as $trans_id => $trans){
                    if(isset($trans['cost']))
                        $cost = $trans['cost'];
                    if(isset($trans['price']))
                        $cost = $trans['price'];

                    if(isset($trans['modifiers'])){
                        foreach ($trans['modifiers'] as $trans_mod_id => $mod) {
                            if($trans_id == $mod['line_id']){
                                $cost += $mod['price'];
                                
                                if(isset($mod['submodifiers'])){
                                    foreach($mod['submodifiers'] as $sub_id => $smod){
                                        if($smod['mod_line_id'] == $mod['mod_line_id']){
                                            $cost += $smod['price'];
                                        }
                                    }
                                }
                            }


                        }
                        // echo '1';
                    }else{
                        if(count($trans_mod_cart) > 0){
                            // echo '2';
                            foreach ($trans_mod_cart as $trans_mod_id => $mod) {
                                if($trans_id == $mod['trans_id']){
                                    $cost += $mod['cost'];
                                
                                    if(isset($trans_submod_cart)){
                                        foreach ($trans_submod_cart as $trans_submod_id => $submod) {
                                            if($trans_mod_id == $submod['mod_line_id'])
                                                $cost += $submod['price'];
                                        }
                                    }

                                }
                            }
                        }
                    }


                    if(isset($counter['zero_rated']) && $counter['zero_rated'] == 1){
                        $rate = 1.12;
                        // $cost = num(($cost / $rate),2);
                        $cost = ($cost / $rate);
                        // $zr = ($cost * $rate);
                        // $cost = $cost-$zr;
                        $zero_rated += $trans['qty'] * $cost;
                        $zero_r = 1;
                    }
                    $total += $trans['qty'] * $cost;
                    if(isset($trans['nocharge']) && $trans['nocharge'] != 0){
                        // $total_no_charge += $trans['qty'] * $cost;
                        // echo 'pumasok';
                        $charge_used[$trans['nocharge']] = array('charge_id'=>$trans['nocharge']);
                    }
                    // echo $trans['nocharge'].'BRBR';
                    $cart_total_qty += $trans['qty'];

                    $where = array('menu_id'=>$trans['menu_id']);
                    $men = $this->site_model->get_details($where,'menus');

                    if(isset($men[0]->alcohol) &&$men[0]->alcohol == 1){
                        $alcohol += $trans['qty'] * $cost;
                    }


                }
            }
            // echo $total; die();


            $gross = $total;
            $trans_disc_cart = sess('trans_disc_cart');
            if(is_array($disc_cart)){
                $trans_disc_cart = $disc_cart;
            }
            // echo '<pre>',print_r(sess('item_discount')),'</pre>'; die();
            $discs = array();
            $less_vat = 0;
            $vatss = 0;
            $ps_counter = 0;

            $item_discount_cart = sess('item_discount');
            // if(is_array($disc_cart)){
            //     $item_discount_cart = $disc_cart;
            // }
            $per_item_disc = false;
            if(is_array($disc_cart)){
                foreach ($disc_cart as $key => $value) {
                    if($value['items'] != ""){
                        $per_item_disc = true;
                    }
                }
            }else{
                if($item_discount_cart){
                    $per_item_disc = true;
                }
            }

            if($per_item_disc){

                //for line discount
                if(is_array($disc_cart)){
                    $item_discount_cart = $disc_cart;
                }
                // echo '<pre>',print_r($item_discount_cart),'</pre>';
                if(count($item_discount_cart) > 0){

                    foreach($item_discount_cart as $id => $val){
                        $discount += $val['amount'];

                        $row = $trans_cart[$id];
                        
                        if(isset($row['cost']))
                            $cost = $row['cost'];
                        if(isset($row['price']))
                            $cost = $row['price'];

                        $total_amt = $row['qty'] * $cost;

                        if($val['disc_code'] == ATHLETE_CODE){
                            $to_disc = $total_amt / 1.12;
                            $less_v = 0;
                        }else{
                            if($val['no_tax'] == 1){
                                $to_disc = $total_amt / 1.12;
                                $less_v = $to_disc * 0.12;
                                // $rate = $val->disc_rate / 100;
                                // $discount = $to_disc * $rate;
                                // $total_disc = $less_v + $discount;
                            }else{
                                $less_v = 0;
                            }
                        }

                        $less_vat += $less_v;
                        $vatss += $less_v;

                    }


                }

            }else{

                //for lahatan disocunt
                if(count($trans_disc_cart) > 0 ){
                    $error_disc = 0;
                    foreach ($trans_disc_cart as $disc_id => $row) {
                        if(!isset($row['disc_type'])){
                            $error_disc = 1;
                        }
                        else{
                            if($row['disc_type'] == "")
                                $error_disc = 1;
                        }
                    }
                    if($error_disc == 0){
                        foreach ($trans_disc_cart as $disc_id => $row) {
                            $rate = $row['disc_rate'];
                            $guests = $row['guest'];

                            if($row['disc_type'] == 'equal'){
                                $no_persons = count($row['persons']);
                                $ps_counter += $no_persons;
                            }
                        }
                    }
                }
     
                // echo $ps_counter.'ssss';
     
                if(count($trans_disc_cart) > 0 ){
                    $error_disc = 0;
                    foreach ($trans_disc_cart as $disc_id => $row) {
                        if(!isset($row['disc_type'])){
                            $error_disc = 1;
                        }
                        else{
                            if($row['disc_type'] == "")
                                $error_disc = 1;
                        }
                    }
                    if($error_disc == 0){
                        foreach ($trans_disc_cart as $disc_id => $row) {
                            // echo $total." ==== ";
                            // echo '<pre>',print_r($row['persons']),'</pre>';
                            if(count($row['persons']) > 0){
                                $rate = $row['disc_rate'];
                                $guests = $row['guest'];
                                switch ($row['disc_type']) {
                                    case "equal":
                                            if($row['disc_code'] == 'SNDISC'){
                                                $divi = ($total-$alcohol)/$row['guest'];
                                            }else{
                                                $divi = $total/$row['guest'];
                                            }
                                            $divi_less = $divi;
                                            $lv = 0;

                                            $where = array('id'=>1);
                                            $set_det = $this->site_model->get_details($where,'settings');
                                            if($counter['type'] != 'dinein' && $counter['type'] != 'mcb' && $row['disc_code'] == 'SNDISC' && $total > $set_det[0]->ceiling_amount && $set_det[0]->ceiling_amount != 0){
                                                $divi = $set_det[0]->ceiling_amount;
                                                $divi_less = $set_det[0]->ceiling_amount;
                                            }

                                            if($counter['type'] == 'mcb' && $row['disc_code'] == 'SNDISC' && $total > $set_det[0]->ceiling_mcb && $set_det[0]->ceiling_mcb != 0){
                                                $divi = $set_det[0]->ceiling_mcb;
                                                $divi_less = $set_det[0]->ceiling_mcb;
                                            }


                                            if($row['disc_code'] == ATHLETE_CODE){
                                                $divi_less = ($divi / 1.12);
                                                $lv = $divi - $divi_less;

                                                $no_persons = count($row['persons']);
                                                foreach ($row['persons'] as $code => $per) {
                                                    $discs[] = array('type'=>$row['disc_code'],'amount'=>($rate / 100) * $divi_less);
                                                    $discount += ($rate / 100) * $divi_less;
                                                    // $less_vat += $lv;
                                                }

                                            }else{
                                                if($row['no_tax'] == 1){
                                                    $divi_less = ($divi / 1.12);
                                                    $lv = $divi - $divi_less;
                                                    // echo $lv.'ssssss';
                                                }
                                                $no_persons = count($row['persons']);
                                                foreach ($row['persons'] as $code => $per) {
                                                    $discs[] = array('type'=>$row['disc_code'],'amount'=>($rate / 100) * $divi_less);
                                                    $discount += ($rate / 100) * $divi_less;
                                                    $less_vat += $lv;
                                                }
                                                $tl = $divi * ( abs($row['guest'] - $no_persons) );
                                                $tdl = ($divi_less * $no_persons) - $discount;
                                                $tl2 = $divi * ( abs($row['guest'] - $ps_counter) );
                                                $vat1 = $tl2 / 1.12;
                                                $vatss = ($vat1 * 0.12);
                                                // echo $vatss.'eeeee-';
                                            }


                                            // $total = $tl + $tdl;
                                            // $total = ($divi * $row['guest']) - $discount;
                                            break;
                                    default:
                                        if($row['fix'] == 0){
                                            if(DISCOUNT_NET_OF_VAT && $row['disc_code'] != DISCOUNT_NET_OF_VAT_EX){
                                                $no_citizens = count($row['persons']);
                                                $total_net_vat = ($total / 1.12);                     
                                                foreach ($row['persons'] as $code => $per) {
                                                    $discs[] = array('type'=>$row['disc_code'],'amount'=>($rate / 100) * $total_net_vat);
                                                    $discount += ($rate / 100) * $total_net_vat;
                                                }
                                                // $total -= $discount;
                                            }
                                            else{

                                                if($row['disc_code'] == ATHLETE_CODE){
                                                    $no_citizens = count($row['persons']);
                                                    // if($row['no_tax'] == 1)
                                                    $total = ($total / 1.12);                     
                                                    
                                                    foreach ($row['persons'] as $code => $per) {
                                                        $discs[] = array('type'=>$row['disc_code'],'amount'=>($rate / 100) * $total);
                                                        $discount += ($rate / 100) * $total;
                                                    }
                                                }else{
                                                    $no_citizens = count($row['persons']);
                                                    if($row['no_tax'] == 1)
                                                        $total = ($total / 1.12);                     
                                                    
                                                    foreach ($row['persons'] as $code => $per) {
                                                        $discs[] = array('type'=>$row['disc_code'],'amount'=>($rate / 100) * $total);
                                                        $discount += ($rate / 100) * $total;
                                                    }
                                                    // $total -= $discount;
                                                }    
                                            }
                                        }
                                        else{
                                            if($row['openamt'] != 0){
                                                $discs[] = array('type'=>$row['disc_code'],'amount'=>$row['openamt']);
                                                $discount += $row['openamt'];
                                            }else{
                                                $discs[] = array('type'=>$row['disc_code'],'amount'=>$rate);
                                                $discount += $rate;
                                            }
                                            // $total -= $discount;
                                        }

                                }
                            }
                            // echo $discount."<br>";
                        }
                    }
                }

            }


            
            // echo $total."<br>";
            // echo $less_vat."<br>";
            // echo $discount."<br>";
            $total -= $discount + $less_vat;
            $total_for_charge = $total - $less_vat;
            // echo $total;    
            $trans_charge_cart = sess('trans_charge_cart');
            if(is_array($charge_cart)){
                $trans_charge_cart = $charge_cart;
            }
            #CHARGES
            $charges = array();
            $total_charges = 0;
            $net_total = $total;

            if(ADD_CHARGES_NET_OF_VAT){
                $amount_cmpt = ($gross / 1.12);              
            }
            else{
                $amount_cmpt = $net_total;                
            }
            #
            #GET VATABLE AMOUNT (FOR SM)
            #
                if(MALL_ENABLED && MALL == 'megamall'){
                    $discountt = 0;
                    $taxable_amount = 0;
                    $not_taxable_amount = 0;
                    $discss = array();
                    $item_count = count($trans_cart);
                    foreach ($trans_cart as $trans_id => $v) {
                        if(isset($v['cost']))
                            $cost = $v['cost'];
                        if(isset($v['price']))
                            $cost = $v['price'];
                        ####################
                        if(isset($v['modifiers'])){
                            foreach ($v['modifiers'] as $trans_mod_id => $m) {
                                if($trans_id == $m['line_id']){
                                    $cost += $m['price'];
                                }
                            }
                        }
                        else{
                            if(count($trans_mod_cart) > 0){
                                foreach ($trans_mod_cart as $trans_mod_id => $m) {
                                    if($trans_id == $m['trans_id']){
                                        $cost += $m['cost'];
                                    }
                                }
                            }
                        }
                        ####################
                        foreach ($trans_disc_cart as $disc_id => $row) {
                            $rate = $row['disc_rate'];
                            switch ($row['disc_type']) {
                                case "equal":
                                        // $divi = $cost/$row['guest'];
                                        // $discount = ($rate / 100) * $divi;
                                        // $cost -= $discount;

                                        $divi = $cost/$row['guest'];
                                        $divi_less = $divi;
                                        if($row['no_tax'] == 1){
                                            $divi_less = ($divi / 1.12);
                                        }
                                        $no_persons = count($row['persons']);
                                        foreach ($row['persons'] as $code => $per) {
                                            $discss[] = array('type'=>$row['disc_code'],'amount'=>($rate / 100) * $divi_less);
                                            $discountt += ($rate / 100) * $divi_less;
                                        }
                                        $tl = $divi * ( abs($row['guest'] - $no_persons) );
                                        $tdl = ($divi_less * $no_persons) - $discountt;
                                        $cost = $tl - $tdl;
                                        // $cost = ($divi * $row['guest']) - $discount;
                                        break;
                                default:
                                    if($row['fix'] == 0){
                                        $no_citizens = count($row['persons']);
                                        if($row['no_tax'] == 1)
                                            $cost = ($cost / 1.12);                     
                                        foreach ($row['persons'] as $code => $per) {
                                            $discss[] = array('type'=>$row['disc_code'],'amount'=>($rate / 100) * $cost);
                                            $discountt += ($rate / 100) * $cost;
                                        }
                                        $cost -= $discountt;
                                    }
                                    else{
                                        $rate = $rate/$item_count;
                                        $discss[] = array('type'=>$row['disc_code'],'amount'=>$rate);
                                        $discountt = $rate; 
                                        $cost -= $discountt;

                                    }
                                    // $discount = ($rate / 100) * $cost;
                                    // $cost -= $discount;
                            }
                        }

                        if($v['no_tax'] == 0){
                            $taxable_amount += $cost * $v['qty'];
                        }
                        else{
                            $not_taxable_amount += $cost * $v['qty'];
                        }
                    }
                    if($not_taxable_amount > 0){
                        $has_no_tax_disc = false;
                        foreach ($trans_disc_cart as $disc_id => $row) {
                            if($row['no_tax'] == 1){
                                $has_no_tax_disc = true;
                                break;
                            }    
                        }    
                        if($has_no_tax_disc){
                            $amount_cmpt = $net_total;                       
                        }
                        else{
                            $amount_cmpt = ($taxable_amount/1.12) + $not_taxable_amount;
                        }
                    }
                    else{
                        if($taxable_amount > 0){
                            $amount_cmpt = ($taxable_amount/1.12);
                        }
                        else{
                            $amount_cmpt = $net_total;                                    
                        }
                    }
                }
            $is_absolute = 0;
            // echo $total;
            //total tranns charges
            $withtakeout = 0;
            $total_for_takeout = 0;
            $total_for_dinein = 0;
            $ps_counter = 0;
            foreach ($trans_cart as $trans_id => $v) {
                if($v['is_takeout'] == 1){
                    $withtakeout = 1;
                    if(isset($v['cost']))
                        $cost = $v['cost'];
                    if(isset($v['price']))
                        $cost = $v['price'];
                    $total_per = $v['qty'] * $cost;
                    $total_for_takeout += $total_per;
                }else{
                    if(isset($v['cost']))
                        $cost = $v['cost'];
                    if(isset($v['price']))
                        $cost = $v['price'];
                    $total_per = $v['qty'] * $cost;
                    $total_for_dinein += $total_per;
                }

            }

            $total_for_dinein -= $discount + $less_vat;
            // $total_for_charge = $total - $less_vat;
            
            //to get the discount and less vat of for dinein

            // echo $withtakeout.'aaaaa';
            // if($withtakeout == 0){
            // die('sss');
                if(count($trans_charge_cart) > 0 ){
                    // echo $am."<br>";
                    foreach ($trans_charge_cart as $charge_id => $opt) {
                        $char_res = $this->site_model->get_tbl('charges',array('charge_id'=>$charge_id),array(),null,true);
                        $char_no_tax = $char_res[0]->no_tax;
                        $charge_amount = $opt['amount'];
                        if($opt['absolute'] == 0){

                            if($char_no_tax == 1){

                                // $charge_amount = ($opt['amount'] / 100) * $am;
                                // echo '<pre>',print_r($trans_disc_cart),'</pre>';
                                //Modified by Jed 11/28/2017
                                //wrong charges pag may discounts
                                if($per_item_disc){
                                    $charge_total = 0;
                                    foreach ($trans_cart as $trans_id => $v) {
                                        
                                        if($v['is_takeout'] != 1){

                                            if(isset($v['cost']))
                                                $cost = $v['cost'];
                                            if(isset($v['price']))
                                                $cost = $v['price'];
                                            $total_per = $v['qty'] * $cost;

                                            if(isset($item_discount_cart[$trans_id])){
                                                
                                                if($item_discount_cart[$trans_id]['disc_code'] == 'DIPLOMAT'){

                                                    // if($item_discount_cart[$trans_id]['no_tax'] == 1){
                                                        $to_disc = $total_per / 1.12;
                                                        $lessv = $to_disc * 0.12;
                                                        // $rate = $val->disc_rate / 100;
                                                        // $discount = $to_disc * $rate;
                                                        // $total_disc = $less_v + $discount;
                                                    // }else{
                                                    //     $less_v = 0;
                                                    // }

                                                        // echo $total.' --- '.$lessv;
                                                    $charge_total += ($opt['amount'] / 100) * ($total_per - $lessv);
                                                }else{
                                                    $no_tx =  $item_discount_cart[$trans_id]['no_tax'];
                                                    if($no_tx == 1){
                                                        $to_disc = $total_per / 1.12;
                                                        $lessv = $to_disc * 0.12;
                                                        $charge_total += ($opt['amount'] / 100) * ($total_per - $total_no_charge - $lessv);
                                                        // die('ss');
                                                    }else{
                                                        $charge_total += ($opt['amount'] / 100) * (($total_per - $item_discount_cart[$trans_id]['amount'])/1.12);
                                                    }
                                                }

                                            }else{
                                                $charge_total += ($opt['amount'] / 100) * (($total_per - $total_no_charge)/1.12);
                                            }

                                        }
                                    }

                                    $charge_amount = $charge_total;

                                }else{

                                    // $charge_amount = ($opt['amount'] / 100) * $am;
                                    // echo '<pre>',print_r($trans_disc_cart),'</pre>';
                                    //Modified by Jed 11/28/2017
                                    //wrong charges pag may discounts
                                    if(count($trans_disc_cart) > 0 ){
                                        $error_disc = 0;
                                        foreach ($trans_disc_cart as $disc_id => $row) {
                                            if(!isset($row['disc_type'])){
                                                $error_disc = 1;
                                            }
                                            else{
                                                if($row['disc_type'] == "")
                                                    $error_disc = 1;
                                            }
                                        }

                                        if($error_disc == 0){
                                            $has_no_tax_disc = false;
                                            foreach ($trans_disc_cart as $disc_id => $row) {
                                                if($row['no_tax'] == 1){
                                                    $has_no_tax_disc = true;
                                                    break;
                                                }    
                                            }
                                            // var_dump($has_no_tax_disc);
                                            if($has_no_tax_disc){
                                                // echo $total_for_dinein." -- ".$vatss.'AAAAA';
                                                // $charge_amount = ($opt['amount'] / 100) * ($total - $total_no_charge - $vatss);         
                                                $charge_amount = ($opt['amount'] / 100) * ($total_for_dinein - $vatss);         
                                            }
                                            else{
                                                // $charge_amount = ($opt['amount'] / 100) * (($total - $total_no_charge)/1.12);
                                                $charge_amount = ($opt['amount'] / 100) * ($total_for_dinein/1.12);
                                            }
                                            
                                        }else{
                                            // $charge_amount = ($opt['amount'] / 100) * (($total - $total_no_charge)/1.12);
                                            $charge_amount = ($opt['amount'] / 100) * ($total_for_dinein/1.12);
                                        }

                                    }else{
                                        if($zero_r == 1){
                                            // echo $total." - ".$total_no_charge." - ".$total_zero_lv; die();
                                            $charge_amount = ($opt['amount'] / 100) * ($total_for_dinein);
                                        }else{
                                            // $charge_amount = ($opt['amount'] / 100) * ($total/1.12);
                                            $charge_amount = ($opt['amount'] / 100) * ($total_for_dinein/1.12);
                                        }
                                    }
                                }

                            }else{

                                if($total != 0){
                                    $charge_amount = ($opt['amount'] / 100) * $gross;

                                }else{
                                     $charge_amount = 0;
                                }


                            }


                        }
                        $charges[$charge_id] = array('code'=>$opt['code'],
                                           'name'=>$opt['name'],
                                           'amount'=>$charge_amount,
                                           );
                        $total_charges += $charge_amount;
                    }
                    $total += $total_charges;
                }

            $loc_res = $this->site_model->get_tbl('settings',array(),array(),null,true,'local_tax',null,1);
            $local_tax = $loc_res[0]->local_tax;
            $lt_amt = 0;
            if($local_tax > 0){
                // $lt_amt = ($local_tax / 100) * $net_total;
                $lt_amt = ($local_tax / 100) * $amount_cmpt;
                $total += $lt_amt;
            }
            if($asJson)
                echo json_encode(array('total'=>$total,'discount'=>$discount,'discs'=>$discs,'charge'=>$total_charges,'charges'=>$charges,'zero_rated'=>$zero_rated,'local_tax'=>$lt_amt,'cart_total_qty'=>$cart_total_qty,'gross'=>$gross));
            else
                return array('total'=>$total,'discount'=>$discount,'discs'=>$discs,'charge'=>$total_charges,'charges'=>$charges,'zero_rated'=>$zero_rated,'local_tax'=>$lt_amt,'cart_total_qty'=>$cart_total_qty,'gross'=>$gross);
        }

    public function menus_extract()
    {
        $data = $this->syter->spawn('menus_extract');        
        $data['page_title'] = fa('fa-money')." Extract Menu";
        $data['code'] = extractMenuPage();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['page_no_padding'] = false;
        $data['sideBarHide'] = false;
        $data['load_js'] = 'dine/reporting';
        $data['use_js'] = 'extractMenuJS';
        $this->load->view('page',$data);
    }

    public function excel_menu_extract(){
        ini_set('memory_limit', '-1');
        set_time_limit(3600);


        date_default_timezone_set('Asia/Manila');
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        // $dates = explode(" to ",$_GET['calendar_range']);
        $ddate = date('m/d/Y');
        $filename = 'Menu Extract '.$ddate;
        $rc=1;
        #GET VALUES
        start_load(0);
       
        


        $select = 'menus.menu_code, menu_name, cost, menu_cat_name, menu_sub_name, menus.menu_id, brands.brand_name';
        $join = array();
        $join['menu_subcategory'] = array('content'=>'menu_subcategory.menu_sub_id = menus.menu_sub_id','mode'=>'left');
        $join['menu_categories'] = array('content'=>'menu_categories.menu_cat_id = menus.menu_cat_id');
        $join['menu_subcategories'] = array('content'=>'menu_subcategories.menu_sub_cat_id = menus.menu_sub_cat_id');
        $join['brands'] = array('content'=>'brands.id = menus.brand');

        // $n_item_res = array();
        // $args["trans_sales.type_id"] = 10;
        // $args["trans_sales.inactive"] = '0';
        // $args["trans_sales.trans_ref is not null"] = array('use'=>'where','val'=>null,'third'=>false);
        // $args['trans_sales.datetime >= '] = $from;
        // $args['trans_sales.datetime <= '] = $to;
        // $pgroup = 'menus.menu_id';
        // $this->site_model->db= $this->load->database('main', TRUE);
        $menus = $this->site_model->get_tbl('menus',array(),array(),$join,true,$select);


        // echo "<pre>", print_r($menus), "</pre>"; die();


        


        update_load(10);

        // update_load(30);

        // $det_array = array();
        // foreach ($details as $vv) {
        //     $det_array[$vv->menu_sub_cat_name][] = array(
        //             'menu_code'=>$vv->menu_code,
        //             'menu_name'=>$vv->menu_name,
        //             'category'=>$vv->menu_cat_name,
        //             'subcategory'=>$vv->menu_sub_name,
        //             'qty_sold'=>$vv->qty_sold,
        //             'sold_amount'=>$vv->sold_amount,
        //             'sales_id'=>$vv->sales_id,
        //             'menu_id'=>$vv->menu_id,
        //         );
        // }
        // $det_arr = array();
        // foreach ($det3 as $v) {
        //     if(isset($det_arr[$v->menu_id][$v->mod_id])){
        //         $row = $det_arr[$v->menu_id][$v->mod_id];
        //         $row['mod_qty_sold'] += $v->mod_qty_sold;
        //         $row['mod_sold_amount'] += $v->mod_sold_amount;

        //         $det_arr[$v->menu_id][$v->mod_id] = $row;

        //     }else{
        //         $det_arr[$v->menu_id][$v->mod_id] = array(
        //             'sales_id'=>$v->sales_id,
        //             'mod_name'=>$v->mod_name,
        //             'menu_id'=>$v->menu_id,
        //             'mod_id'=>$v->mod_id,
        //             'mod_qty_sold'=>$v->mod_qty_sold,
        //             'mod_sold_amount'=>$v->mod_sold_amount,
        //         );                
        //     }

        // }

        // $det_arr2 = array();
        // foreach ($det4 as $subm) {
        //     if(isset($det_arr2[$subm->mod_id][$subm->sales_submod_id])){
        //         $row = $det_arr2[$subm->mod_id][$subm->sales_submod_id];
        //         $row['submod_qty_sold'] += $subm->submod_qty_sold;
        //         $row['submod_sold_amount'] += $subm->submod_sold_amount;

        //         $det_arr2[$subm->mod_id][$subm->sales_submod_id] = $row;

        //     }else{
        //         $det_arr2[$subm->mod_id][$subm->sales_submod_id] = array(
        //             'sales_id'=>$subm->sales_id,
        //             'submod_name'=>$subm->submod_name,
        //             'mod_id'=>$subm->mod_id,
        //             'submod_qty_sold'=>$subm->submod_qty_sold,
        //             'submod_sold_amount'=>$subm->submod_sold_amount,
        //         );                
        //     }
        // }

        // update_load(70);

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
                'size' => 14,
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
        $styleTitle = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'size' => 20,
            )
        );
        $styleNumC = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'f5f755')
            ),
        );
        $styleTxtC = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
            'font' => array(
                'bold' => true,
                // 'size' => 20,
            )
            // 'fill' => array(
            //     'type' => PHPExcel_Style_Fill::FILL_SOLID,
            //     'color' => array('rgb' => 'f5f755')
            // ),
        );
        
        $headers = array('BRAND','MENU CODE','MENU NAME','CATEGORY','SUBCATEGORY','PRICE','MODIFIER GROUP CODE','MODIFIER GROUP','QTY TO SELECT','MODIFIERS CODE','MODIFIER','MODIFIER PRICE','SUBMODIFIER GRP','SUBMODIFIER CODE','SUBMODIFIER','SUBMODIFIER PRICE');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(20);
        $sheet->getColumnDimension('L')->setWidth(20);
        $sheet->getColumnDimension('M')->setWidth(20);
        $sheet->getColumnDimension('N')->setWidth(20);
        $sheet->getColumnDimension('O')->setWidth(20);
        $sheet->getColumnDimension('O')->setWidth(20);
        // $sheet->getColumnDimension('I')->setWidth(20);


        $sheet->mergeCells('A'.$rc.':C'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Extracted Menu');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->getCell('A'.$rc)->setValue('Print Date : '.date('m/d/Y h:i:s A'));
        $sheet->mergeCells('A'.$rc.':C'.$rc);
        $rc++;
        $rc++;
        $rc++;

        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }

        $rc++;
        // $rc++;

        foreach($menus as $arid => $vals){
            $sheet->getCell('A'.$rc)->setValue($vals->brand_name);
            $sheet->getCell('B'.$rc)->setValue($vals->menu_code);
            $sheet->getCell('C'.$rc)->setValue($vals->menu_name);
            $sheet->getCell('D'.$rc)->setValue($vals->menu_cat_name);
            $sheet->getCell('E'.$rc)->setValue($vals->menu_sub_name);
            $sheet->getCell('F'.$rc)->setValue($vals->cost);

            $where = array('menu_id'=>$vals->menu_id);
            $have_mod = $this->site_model->get_details($where,'menu_modifiers');

            if(count($have_mod > 0)){

                $menu_rc = $rc;

                foreach($have_mod as $hmod_id => $hmod_val){
                    $select = 'name, mod_group_id, min_no';
                    $join = array();
                    // $join['menu_subcategory'] = array('content'=>'menu_subcategory.menu_sub_id = menus.menu_sub_id','mode'=>'left');
                    // $join['menu_categories'] = array('content'=>'menu_categories.menu_cat_id = menus.menu_cat_id');
                    // $join['menu_subcategories'] = array('content'=>'menu_subcategories.menu_sub_cat_id = menus.menu_sub_cat_id');
                    // $join['brands'] = array('content'=>'brands.id = menus.brand');

                    // $n_item_res = array();
                    $args = array();
                    $args["mod_group_id"] = $hmod_val->mod_group_id;
                    $mod_grp = $this->site_model->get_tbl('modifier_groups',$args,array(),$join,true,$select);

                    // echo "<pre>", print_r($have_mod), "</pre>"; die();
                    foreach($mod_grp as $mid => $mgrp_val){
                        $sheet->getCell('G'.$menu_rc)->setValue('MG'.$mgrp_val->mod_group_id);
                        $sheet->getCell('H'.$menu_rc)->setValue($mgrp_val->name);
                        $sheet->getCell('I'.$menu_rc)->setValue($mgrp_val->min_no);

                        $select = '*';
                        $join = array();
                        // $join['menu_subcategory'] = array('content'=>'menu_subcategory.menu_sub_id = menus.menu_sub_id','mode'=>'left');
                        // $join['menu_categories'] = array('content'=>'menu_categories.menu_cat_id = menus.menu_cat_id');
                        // $join['menu_subcategories'] = array('content'=>'menu_subcategories.menu_sub_cat_id = menus.menu_sub_cat_id');
                        // $join['brands'] = array('content'=>'brands.id = menus.brand');

                        // $n_item_res = array();
                        $args = array();
                        $args["mod_group_id"] = $mgrp_val->mod_group_id;
                        $mod_grp_det = $this->site_model->get_tbl('modifier_group_details',$args,array(),$join,true,$select);

                        if(count($mod_grp_det) > 0){

                            $modgrp_rc = $menu_rc;

                            foreach ($mod_grp_det as $modar => $modsd_val) {

                                $where = array('mod_id'=>$modsd_val->mod_id);
                                $modifiers = $this->site_model->get_details($where,'modifiers');

                                // echo "<pre>", print_r($modifiers), "</pre>"; die();
                                if($modifiers){
                                    $sheet->getCell('J'.$modgrp_rc)->setValue($modifiers[0]->mod_code);
                                    $sheet->getCell('K'.$modgrp_rc)->setValue($modifiers[0]->name);
                                    $sheet->getCell('L'.$modgrp_rc)->setValue($modifiers[0]->cost);

                                    // $modgrp_rc++;

                                    $select = '*';
                                    $join = array();
                                    $args = array();
                                    $args["mod_id"] = $modifiers[0]->mod_id;
                                    $submod = $this->site_model->get_tbl('modifier_sub',$args,array(),$join,true,$select);

                                    if(count($submod) > 0){

                                        $submod_rc = $modgrp_rc;

                                        foreach($submod as $sm_arr => $smod_val){

                                            $sheet->getCell('M'.$submod_rc)->setValue($smod_val->group);
                                            $sheet->getCell('N'.$submod_rc)->setValue('SM00'.$smod_val->mod_sub_id);
                                            $sheet->getCell('O'.$submod_rc)->setValue($smod_val->name);
                                            $sheet->getCell('P'.$submod_rc)->setValue($smod_val->cost);
                                            
                                            $submod_rc++;

                                        }
                                        $submod_rc--;
                                        $modgrp_rc = $submod_rc;
                                    }

                                    $modgrp_rc++;


                                }
                                
                            }
                            $modgrp_rc--;
                            $menu_rc = $modgrp_rc;
                        }

                        $modgrp_rc++;

                    }

                    $menu_rc++;
                }
                $menu_rc--;
                $rc = $menu_rc;
            }

            $rc++;
        }



        $tqty = 0;
        $tamount = 0;
        // foreach ($det2 as $val2) {
        //     foreach ($det_array as $menucat => $vals) {
        //         $total_qty = 0;
        //         $total_amt = 0;
        //         if($val2->menu_sub_cat_name == $menucat){
        //             $sheet->getCell('A'.$rc)->setValue($menucat);
        //             $sheet->getStyle('A'.$rc)->applyFromArray($styleTxtC);
        //             $rc++;
        //             $mod_total_qty = 0;
        //             $mod_total_amt = 0;
        //             foreach ($vals as $id => $vvv) {
        //                 $sheet->getCell('A'.$rc)->setValue($vvv['menu_code']);
        //                 $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //                 $sheet->getCell('B'.$rc)->setValue($vvv['menu_name']);
        //                 $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
        //                 $sheet->getCell('C'.$rc)->setValue($vvv['category']);
        //                 $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);
        //                 $sheet->getCell('D'.$rc)->setValue($vvv['subcategory']);
        //                 $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
        //                 $sheet->getCell('E'.$rc)->setValue(num($vvv['qty_sold']));
        //                 $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        //                 $sheet->getCell('F'.$rc)->setValue(num($vvv['sold_amount']));
        //                 $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        //                 $sheet->getCell('G'.$rc)->setValue('');
        //                 $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
        //                 $rc++;
        //                 foreach ($det_arr as $m_id => $m_val) {
        //                     foreach ($m_val as $modid => $mod_val) {
        //                         // $sheet->getCell('A'.$rc)->setValue($vvv['menu_code']);
        //                         // $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //                         if($vvv['menu_id'] == $m_id){
        //                             $where = array('mod_id'=>$modid);
        //                             $dd = $this->site_model->get_details($where,'modifiers');
        //                             $sheet->getCell('A'.$rc)->setValue($dd[0]->mod_code);
        //                             $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //                             $sheet->getCell('B'.$rc)->setValue($mod_val['mod_name']);
        //                             $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
        //                             $sheet->getCell('C'.$rc)->setValue($vvv['category']);
        //                             $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);
        //                             $sheet->getCell('D'.$rc)->setValue($vvv['subcategory']);
        //                             $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
        //                             $sheet->getCell('E'.$rc)->setValue(num($mod_val['mod_qty_sold']));
        //                             $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        //                             $sheet->getCell('F'.$rc)->setValue(num($mod_val['mod_sold_amount']));
        //                             $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        //                             $mod_total_qty += $mod_val['mod_qty_sold'];
        //                             $mod_total_amt += $mod_val['mod_sold_amount'];
        //                             $rc++;

        //                             foreach($det_arr2 as $mod_id => $submods){

        //                                 if($mod_id == $modid){
        //                                     foreach($submods as $submod){
        //                                         $this->make->sRow();
        //                                         $this->make->td($dd[0]->mod_code);
        //                                         $this->make->td($submod['submod_name']);
        //                                         $this->make->td($vvv['category']);
        //                                         $this->make->td($vvv['subcategory']);
        //                                         $this->make->td($submod['submod_qty_sold'], array("style"=>"text-align:right"));
        //                                         $this->make->td(num($submod['submod_sold_amount']), array("style"=>"text-align:right"));
        //                                         $this->make->td('');
        //                                         $this->make->eRow();

        //                                          $sheet->getCell('A'.$rc)->setValue($dd[0]->mod_code);
        //                                         $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //                                         $sheet->getCell('B'.$rc)->setValue($submod['submod_name']);
        //                                         $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
        //                                         $sheet->getCell('C'.$rc)->setValue($vvv['category']);
        //                                         $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);
        //                                         $sheet->getCell('D'.$rc)->setValue($vvv['subcategory']);
        //                                         $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
        //                                         $sheet->getCell('E'.$rc)->setValue(num($submod['submod_qty_sold']));
        //                                         $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        //                                         $sheet->getCell('F'.$rc)->setValue(num($submod['submod_sold_amount']));
        //                                         $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        //                                         $mod_total_qty += $submod['submod_qty_sold'];
        //                                         $mod_total_amt += $submod['submod_sold_amount'];
        //                                         $rc++;
        //                                     }
        //                                 }
        //                             }
        //                         }
        //                     }
        //                 }
        //                     $total_qty += $vvv['qty_sold'];
        //                     $total_amt += $vvv['sold_amount'];
                            
        //             }
        //                 // $sheet->getCell('C'.$rc)->setValue('SUBTOTAL');
        //                 // $sheet->getStyle('C'.$rc)->applyFromArray($styleTxtC);
        //                 $sheet->getCell('E'.$rc)->setValue(num($total_qty+$mod_total_qty));
        //                 $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        //                 $sheet->getCell('F'.$rc)->setValue(num($total_amt+$mod_total_amt));
        //                 $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        //              $rc++;
        //              $tqty += $total_qty; 
        //              $tamount += $total_amt; 

        //              $mtqty += $mod_total_qty; 
        //              $mtamount += $mod_total_amt;
        //         }
        //     }
        // }
                
         
        
        update_load(100);
        ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public function menus_rep_hourly(){
        $data = $this->syter->spawn('menu_sales_rep');
        $data['page_title'] = fa('icon-book-open')." Menu Hourly Sales Report";
        $data['code'] = menusRepHourly();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['page_no_padding'] = true;
        $data['sideBarHide'] = true;
        $data['load_js'] = 'dine/reporting';
        $data['use_js'] = 'menusRepHrJS';
        $this->load->view('page',$data);
    }

    public function menus_hourly_rep_gen(){
        start_load(0);
        $this->load->model('dine/setup_model');

        $daterange = $this->input->post("calendar_range");        
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        // $date = $this->input->post("date");
        $from = date2SqlDateTime($dates[0]);        
        $to = date2SqlDateTime($dates[1]);        
        // $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        update_load(10);
        $this->menu_model->db = $this->load->database('main', TRUE);
        $trans = $this->menu_model->get_menu_sales_rep_hourly($from, $to);


        $datas = array();

        if($trans){
            $tran_ctr = 0;
            $t_salesid = '';
            foreach ($trans as $det => $v) {
                // if($tran_ctr == 0){
                //     $t_salesid = $v->sales_id;
                // }
                if($t_salesid != $v->sales_id){
                    //unang pasok sa sales_id
                    //get charges
                    $charges = 0;
                    $span_charge = '';
                    $args2 = array();
                    $args2["sales_id"] = $v->sales_id;
                    $chrg = $this->site_model->get_tbl('trans_sales_charges',$args2,array(),array(),true,'*');

                    if($chrg){
                        $span_charge = 'span';
                        foreach($chrg as $cid =>$vv){
                            $charges += $vv->amount;
                        }
                    }


                    //get discounts
                    $span_disc = '';
                    $lessvat = 0;
                    $net_sales = 0;
                    $discounts = 0;
                    $is_line_disc = false;
                    $disc_line_id = '';
                    $disc_name = '';
                    $join = array();
                    $join['receipt_discounts'] = array('content'=>'receipt_discounts.disc_id = trans_sales_discounts.disc_id');
                    $args2 = array();
                    $args2["sales_id"] = $v->sales_id;
                    $disc = $this->site_model->get_tbl('trans_sales_discounts',$args2,array(),$join,true,'trans_sales_discounts.*, disc_name');

                    if($disc){
                        foreach($disc as $did =>$vv){
                            $disc_line_id = $vv->items;
                            if($disc_name != ''){
                                $disc_name .= ", ".$vv->disc_name;
                            }else{
                                $disc_name .= $vv->disc_name;
                            }
                            $discounts += $vv->amount;
                        }

                        if($disc_line_id != ''){
                            $is_line_disc = true;
                        }else{
                            // get the vat exempt and netsales
                            $span_disc = 'span';
                            if($vv->no_tax == 1){
                                $args3["sales_id"] = $v->sales_id;
                                $menus = $this->site_model->get_tbl('trans_sales_menus',$args3,array(),array(),true,'*');
                                $tgross = 0;
                                foreach($menus as $mid => $vm){
                                    $tgross += $vm->qty * $vm->price;
                                }

                                $lessvat = ($tgross / 1.12) * 0.12;
                                $net_sales = $tgross - $discounts - $lessvat;

                            }
                        }

                        if($is_line_disc){
                            //per line discount
                            $discounts = 0;
                            $lessvat = 0;
                            $disc_name = '';
                            $disc_line_id = '';
                            $join = array();
                            $join['receipt_discounts'] = array('content'=>'receipt_discounts.disc_id = trans_sales_discounts.disc_id');
                            $args2 = array();
                            $args2["sales_id"] = $v->sales_id;
                            $args2["items"] = $v->line_id;
                            $disc2 = $this->site_model->get_tbl('trans_sales_discounts',$args2,array(),$join,true,'trans_sales_discounts.*, disc_name');
                            // if($disc2){
                                foreach($disc2 as $did2 =>$vv2){
                                    if($disc_name != ''){
                                        $disc_name .= ", ".$vv2->disc_name;
                                    }else{
                                        $disc_name .= $vv2->disc_name;
                                    }
                                    $disc_line_id = $vv2->items;
                                    $discounts = $vv2->amount;
                                    if($vv2->no_tax == 1){
                                        $lessvat = ($v->gross/1.12) * 0.12;
                                    }
                                    $net_sales = $v->gross - $lessvat - $discounts;
                                }
                            // }else{
                            //     $disc_line_id = '';
                            // }

                        }
                    }



                }else{
                    //2nd pasok and so on
                   
                    if($charges != 0){
                        $charges = 0;
                    }

                    if($discounts != 0 && $is_line_disc == false){
                        $discounts = 0;
                        // $lessvat = 'span';
                        // $net_sales = 'span';
                    }else{
                        // if($is_line_disc != ''){
                            $discounts = 0;
                            $lessvat = 0;
                            $disc_name = '';
                            $disc_line_id = '';
                            $join = array();
                            $join['receipt_discounts'] = array('content'=>'receipt_discounts.disc_id = trans_sales_discounts.disc_id');
                            $args2 = array();
                            $args2["sales_id"] = $v->sales_id;
                            $args2["items"] = $v->line_id;
                            $disc2 = $this->site_model->get_tbl('trans_sales_discounts',$args2,array(),$join,true,'trans_sales_discounts.*, disc_name');

                            foreach($disc2 as $did2 =>$vv2){
                                if($disc_name != ''){
                                    $disc_name .= ", ".$vv2->disc_name;
                                }else{
                                    $disc_name .= $vv2->disc_name;
                                }
                                $disc_line_id = $vv2->items;
                                $discounts = $vv2->amount;
                                if($vv2->no_tax == 1){
                                    $lessvat = ($v->gross/1.12) * 0.12;
                                }
                                $net_sales = $v->gross - $lessvat - $discounts;
                            }
                        // }
                    }

                }

                $args3 = array();
                $args3["sales_id"] = $v->sales_id;
                $pays = $this->site_model->get_tbl('trans_sales_payments',$args3,array(),array(),true,'*');

                $pay_type = '';
                foreach ($pays as $pid => $p) {
                    if($pay_type != ''){
                        $pay_type .= ', '.$p->payment_type;
                    }else{
                        $pay_type .= $p->payment_type;
                    }

                }

                $remark = 'VALID';
                if($v->inactive == 1){
                    $remark = 'VOIDED';
                }

                if($v->guest == 0){
                    $guest = 1;
                }else{
                    $guest = $v->guest;
                }

                $setup = $this->setup_model->get_details(1);
                $set = $setup[0];
                
                $datas[$v->sales_id][] = array(
                    'datetime'=>$v->datetime,
                    'terminal_code'=>$v->terminal_code,
                    'outlet'=>$set->branch_name,
                    'customer'=>'',
                    'trans_ref'=>$v->trans_ref,
                    'menu_code'=>$v->menu_code,
                    'menu_name'=>$v->menu_name,
                    'qty'=>$v->qty,
                    'price'=>$v->mprice,
                    'gross'=>$v->gross,
                    'trans_type'=>$v->trans_type,
                    'guest'=>$guest,
                    'sales_id'=>$v->sales_id,
                    'charges'=>$charges,
                    'remarks'=>$remark,
                    'line_id'=>$v->line_id,
                    'discount'=>$discounts,
                    'disc_line_id'=>$disc_line_id,
                    'disc_name'=>$disc_name,
                    'lessvat'=>$lessvat,
                    'net_sales'=>$net_sales,
                    'span_disc'=>$span_disc,
                    'span_charge'=>$span_charge,
                    'payment_type'=>$pay_type,
                );

                $t_salesid = $v->sales_id;
                // $tran_ctr++;
            }
        }

        // echo "<pre>", print_r(count($datas[4])), "</pre>"; die();
        // echo "<pre>", print_r($datas), "</pre>"; die();


        // $post = $this->set_post();
        // $curr = $this->search_current();
        // $trans = $this->trans_sales($post['args'],$curr);
        // $sales = $trans['sales'];
        update_load(15);
        // $trans_menus = $this->menu_sales($sales['settled']['ids'],$curr);
        // update_load(20);
        // $trans_charges = $this->charges_sales($sales['settled']['ids'],$curr);
        // update_load(25);
        // $trans_discounts = $this->discounts_sales($sales['settled']['ids'],$curr);
        // update_load(30);
        // $trans_local_tax = $this->local_tax_sales($sales['settled']['ids'],$curr);
        // update_load(35);
        // $trans_tax = $this->tax_sales($sales['settled']['ids'],$curr);
        // update_load(40);
        // $trans_no_tax = $this->no_tax_sales($sales['settled']['ids'],$curr);
        // update_load(45);
        // $trans_zero_rated = $this->zero_rated_sales($sales['settled']['ids'],$curr);
        // update_load(50);
        // $payments = $this->payment_sales($sales['settled']['ids'],$curr);
        // update_load(53);
        // $gross = $trans_menus['gross']; 
        // $net = $trans['net'];
        // $charges = $trans_charges['total']; 
        // $discounts = $trans_discounts['total']; 
        // $local_tax = $trans_local_tax['total']; 
        // $less_vat = (($gross+$charges+$local_tax) - $discounts) - $net;
        // if($less_vat < 0)
        //     $less_vat = 0;
        // $tax = $trans_tax['total'];
        // $no_tax = $trans_no_tax['total'];
        // $zero_rated = $trans_zero_rated['total'];
        // $no_tax -= $zero_rated;
        
        // update_load(55);
        // $cats = $trans_menus['cats'];                 
        // $menus = $trans_menus['menus'];
        // $subcats = $trans_menus['sub_cats'];      
        // $menu_total = $trans_menus['menu_total'];
        // $total_qty = $trans_menus['total_qty'];
        // update_load(60);
        // usort($menus, function($a, $b) {
        //     return $b['amount'] - $a['amount'];
        // });
        update_load(80);
        $this->make->sDiv(array('style'=>'overflow:auto;height:800px;'));
            $this->make->sTable(array('class'=>'table reportTBL'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('No.');
                        $this->make->th('Time');
                        $this->make->th('Sales Date');
                        $this->make->th('Terminal Code');
                        $this->make->th('Outlet');
                        $this->make->th('OR No.');
                        $this->make->th('Customer Name');
                        $this->make->th('SKU Code');
                        $this->make->th('Sku Description');
                        $this->make->th('Qty');
                        $this->make->th('Price');
                        $this->make->th('Gross Sales');
                        $this->make->th('Type of Disc.');
                        $this->make->th('Discount Amount');
                        $this->make->th('Vat Duduct');
                        $this->make->th('Net Sales');
                        $this->make->th('Mode of Payment');
                        $this->make->th('Trans Type');
                        $this->make->th('No. Of Guest');
                        $this->make->th('Charges');
                        $this->make->th('Remarks');
                        // $this->make->th('Remarks');
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();
                    $ctr = 1;
                    foreach ($datas as $sid => $trans) {
                        foreach ($trans as $res => $v) {
                            $this->make->sRow();
                                $this->make->td($ctr);
                                $this->make->td(date('h:i:s A',strtotime($v['datetime'])));
                                $this->make->td(date('m/d/Y',strtotime($v['datetime'])));
                                $this->make->td($v['terminal_code']);
                                $this->make->td($v['outlet']);
                                $this->make->td($v['trans_ref']);
                                $this->make->td($v['customer']);
                                $this->make->td($v['menu_code']);
                                $this->make->td($v['menu_name']);
                                // $this->make->td($cats[$res['cat_id']]['name']);
                                // $this->make->td($res['sell_price']);
                                $this->make->td($v['qty']);
                                // $this->make->td('');
                                $this->make->td(num($v['gross']));
                                $this->make->td(num($v['gross']));
                                

                                if($v['span_disc'] == 'span'){
                                    if($v['discount'] != 0){
                                        //discount whole transaction
                                        $rowspan = count($datas[$v['sales_id']]);
                                        $this->make->td($v['disc_name'],array('rowspan'=>$rowspan,'style'=>'vertical-align:middle;'));
                                        $this->make->td(num($v['discount']),array('rowspan'=>$rowspan,'style'=>'vertical-align:middle;'));
                                        $this->make->td(num($v['lessvat']),array('rowspan'=>$rowspan,'style'=>'vertical-align:middle;'));
                                        $this->make->td(num($v['net_sales']),array('rowspan'=>$rowspan,'style'=>'vertical-align:middle;'));
                                    }
                                    //     //per line disc
                                    //     if($v['discount'] == "span" || $v['discount'] != 0){
                                    //         // $this->make->td(num($v['discount']));

                                    //     }else{
                                    //         $this->make->td($v['disc_name']);
                                    //         $this->make->td(num($v['discount']));
                                    //         $this->make->td(num($v['lessvat']));
                                    //         $this->make->td(num($v['net_sales']));
                                    //     }
                                        

                                    // }
                                }else{
                                    if($v['discount'] != 0){
                                        $this->make->td($v['disc_name']);
                                        $this->make->td(num($v['discount']));
                                        $this->make->td(num($v['lessvat']));
                                        $this->make->td(num($v['net_sales']));

                                    }else{

                                        $this->make->td('');
                                        $this->make->td('0');
                                        $this->make->td('0');
                                        $this->make->td(num($v['gross']));
                                    }
                                }
                                // $net_sales
                                // $this->make->td(num($v->gross));
                                $this->make->td($v['payment_type']);
                                $this->make->td($v['trans_type']);
                                $this->make->td($v['guest']);

                                if($v['span_charge'] == 'span'){
                                    if($v['charges'] != 0){
                                        //discount whole transaction
                                        $rowspan = count($datas[$v['sales_id']]);
                                        $this->make->td(num($v['charges']),array('rowspan'=>$rowspan,'style'=>'vertical-align:middle;'));
                                    }
                                }else{
                                    $this->make->td('0');
                                }


                                $this->make->td($v['remarks']);
                                // $this->make->td( num( ($res['qty'] / $total_qty) * 100 ).'%' );
                                // $this->make->td( num($res['amount']) );
                                // $this->make->td( num( ($res['amount'] / $menu_total) * 100 ).'%' );
                                // $this->make->td($res['cost_price']);
                                // $this->make->td($res['cost_price'] * $res['qty']);
                            $this->make->eRow();

                            $ctr++;
                        }
                    }    
                    
                $this->make->eTableBody();
            $this->make->eTable();


        $this->make->eDiv();
        update_load(100);
        $code = $this->make->code();
        $json['code'] = $code;
        $json['tbl_vals'] = $trans;
        $json['dates'] = $this->input->post('calendar_range');
        echo json_encode($json);
    }

    public function menus_rep_hrly_excel(){
        $this->load->model('dine/setup_model');
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $filename = 'Menu Hourly Sales Report';
        $rc=1;
        #GET VALUES
        start_load(0);


        $daterange = $_GET['calendar_range'];      
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        // $date = $this->input->post("date");
        $from = date2SqlDateTime($dates[0]);        
        $to = date2SqlDateTime($dates[1]);        
        // $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        update_load(10);
        $this->menu_model->db = $this->load->database('main', TRUE);
        $trans = $this->menu_model->get_menu_sales_rep_hourly($from, $to);


        $datas = array();

        if($trans){
            $tran_ctr = 0;
            $t_salesid = '';
            foreach ($trans as $det => $v) {
                // if($tran_ctr == 0){
                //     $t_salesid = $v->sales_id;
                // }
                if($t_salesid != $v->sales_id){
                    //unang pasok sa sales_id
                    //get charges
                    $charges = 0;
                    $span_charge = '';
                    $args2 = array();
                    $args2["sales_id"] = $v->sales_id;
                    $chrg = $this->site_model->get_tbl('trans_sales_charges',$args2,array(),array(),true,'*');

                    if($chrg){
                        $span_charge = 'span';
                        foreach($chrg as $cid =>$vv){
                            $charges += $vv->amount;
                        }
                    }


                    //get discounts
                    $span_disc = '';
                    $lessvat = 0;
                    $net_sales = 0;
                    $discounts = 0;
                    $is_line_disc = false;
                    $disc_line_id = '';
                    $disc_name = '';
                    $join = array();
                    $join['receipt_discounts'] = array('content'=>'receipt_discounts.disc_id = trans_sales_discounts.disc_id');
                    $args2 = array();
                    $args2["sales_id"] = $v->sales_id;
                    $disc = $this->site_model->get_tbl('trans_sales_discounts',$args2,array(),$join,true,'trans_sales_discounts.*, disc_name');

                    if($disc){
                        foreach($disc as $did =>$vv){
                            $disc_line_id = $vv->items;
                            if($disc_name != ''){
                                $disc_name .= ", ".$vv->disc_name;
                            }else{
                                $disc_name .= $vv->disc_name;
                            }
                            $discounts += $vv->amount;
                        }

                        if($disc_line_id != ''){
                            $is_line_disc = true;
                        }else{
                            // get the vat exempt and netsales
                            $span_disc = 'span';
                            if($vv->no_tax == 1){
                                $args3["sales_id"] = $v->sales_id;
                                $menus = $this->site_model->get_tbl('trans_sales_menus',$args3,array(),array(),true,'*');
                                $tgross = 0;
                                foreach($menus as $mid => $vm){
                                    $tgross += $vm->qty * $vm->price;
                                }

                                $lessvat = ($tgross / 1.12) * 0.12;
                                $net_sales = $tgross - $discounts - $lessvat;

                            }
                        }

                        if($is_line_disc){
                            //per line discount
                            $discounts = 0;
                            $lessvat = 0;
                            $disc_name = '';
                            $disc_line_id = '';
                            $join = array();
                            $join['receipt_discounts'] = array('content'=>'receipt_discounts.disc_id = trans_sales_discounts.disc_id');
                            $args2 = array();
                            $args2["sales_id"] = $v->sales_id;
                            $args2["items"] = $v->line_id;
                            $disc2 = $this->site_model->get_tbl('trans_sales_discounts',$args2,array(),$join,true,'trans_sales_discounts.*, disc_name');
                            // if($disc2){
                                foreach($disc2 as $did2 =>$vv2){
                                    if($disc_name != ''){
                                        $disc_name .= ", ".$vv2->disc_name;
                                    }else{
                                        $disc_name .= $vv2->disc_name;
                                    }
                                    $disc_line_id = $vv2->items;
                                    $discounts = $vv2->amount;
                                    if($vv2->no_tax == 1){
                                        $lessvat = ($v->gross/1.12) * 0.12;
                                    }
                                    $net_sales = $v->gross - $lessvat - $discounts;
                                }
                            // }else{
                            //     $disc_line_id = '';
                            // }

                        }
                    }



                }else{
                    //2nd pasok and so on
                   
                    if($charges != 0){
                        $charges = 0;
                    }

                    if($discounts != 0 && $is_line_disc == false){
                        $discounts = 0;
                        // $lessvat = 'span';
                        // $net_sales = 'span';
                    }else{
                        // if($is_line_disc != ''){
                            $discounts = 0;
                            $lessvat = 0;
                            $disc_name = '';
                            $disc_line_id = '';
                            $join = array();
                            $join['receipt_discounts'] = array('content'=>'receipt_discounts.disc_id = trans_sales_discounts.disc_id');
                            $args2 = array();
                            $args2["sales_id"] = $v->sales_id;
                            $args2["items"] = $v->line_id;
                            $disc2 = $this->site_model->get_tbl('trans_sales_discounts',$args2,array(),$join,true,'trans_sales_discounts.*, disc_name');

                            foreach($disc2 as $did2 =>$vv2){
                                if($disc_name != ''){
                                    $disc_name .= ", ".$vv2->disc_name;
                                }else{
                                    $disc_name .= $vv2->disc_name;
                                }
                                $disc_line_id = $vv2->items;
                                $discounts = $vv2->amount;
                                if($vv2->no_tax == 1){
                                    $lessvat = ($v->gross/1.12) * 0.12;
                                }
                                $net_sales = $v->gross - $lessvat - $discounts;
                            }
                        // }
                    }

                }

                $args3 = array();
                $args3["sales_id"] = $v->sales_id;
                $pays = $this->site_model->get_tbl('trans_sales_payments',$args3,array(),array(),true,'*');

                $pay_type = '';
                foreach ($pays as $pid => $p) {
                    if($pay_type != ''){
                        $pay_type .= ', '.$p->payment_type;
                    }else{
                        $pay_type .= $p->payment_type;
                    }

                }

                $remark = 'VALID';
                if($v->inactive == 1){
                    $remark = 'VOIDED';
                }

                if($v->guest == 0){
                    $guest = 1;
                }else{
                    $guest = $v->guest;
                }

                $setup = $this->setup_model->get_details(1);
                $set = $setup[0];
                
                $datas[$v->sales_id][] = array(
                    'datetime'=>$v->datetime,
                    'terminal_code'=>$v->terminal_code,
                    'outlet'=>$set->branch_name,
                    'customer'=>'',
                    'trans_ref'=>$v->trans_ref,
                    'menu_code'=>$v->menu_code,
                    'menu_name'=>$v->menu_name,
                    'qty'=>$v->qty,
                    'price'=>$v->mprice,
                    'gross'=>$v->gross,
                    'trans_type'=>$v->trans_type,
                    'guest'=>$guest,
                    'sales_id'=>$v->sales_id,
                    'charges'=>$charges,
                    'remarks'=>$remark,
                    'line_id'=>$v->line_id,
                    'discount'=>$discounts,
                    'disc_line_id'=>$disc_line_id,
                    'disc_name'=>$disc_name,
                    'lessvat'=>$lessvat,
                    'net_sales'=>$net_sales,
                    'span_disc'=>$span_disc,
                    'span_charge'=>$span_charge,
                    'payment_type'=>$pay_type,
                );

                $t_salesid = $v->sales_id;
                // $tran_ctr++;
            }
        }


        update_load(15);
            
        update_load(80);
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
                'size' => 14,
                'color' => array('rgb' => 'FFFFFF'),
            )
        );
        $styleNum = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            ),
        );
        $styleNumMerge = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
        );
        $styleTxt = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            ),
        );
        $styleTxtMerge = array(
            'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
        );
        $styleTitle = array(
            'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            ),
            'font' => array(
                'bold' => true,
                'size' => 16,
            )
        );

        // $this->make->th('No.');
        // $this->make->th('Time');
        // $this->make->th('Sales Date');
        // $this->make->th('Terminal Code');
        // $this->make->th('Outlet');
        // $this->make->th('OR No.');
        // $this->make->th('Customer Name');
        // $this->make->th('SKU Code');
        // $this->make->th('Sku Description');
        // $this->make->th('Qty');
        // $this->make->th('Price');
        // $this->make->th('Gross Sales');
        // $this->make->th('Type of Disc.');
        // $this->make->th('Vat Duduct');
        // $this->make->th('Net Sales');
        // $this->make->th('Trans Type');
        // $this->make->th('No. Of Guest');
        // $this->make->th('Remarks');
        
        $headers = array('No.','Time','Sales Date','Terminal Code','Outlet','OR No.','Customer Name','SKU Code','SKU Description','Qty','Price','Gross Sales','Type of Disc.','Discount Amount','Vat Deduct','Net Sales','Mode of Payment','Transaction Type','No. of Guest','Charges','Remarks');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(20);
        $sheet->getColumnDimension('L')->setWidth(20);
        $sheet->getColumnDimension('M')->setWidth(20);
        $sheet->getColumnDimension('N')->setWidth(20);
        $sheet->getColumnDimension('O')->setWidth(20);
        $sheet->getColumnDimension('P')->setWidth(20);
        $sheet->getColumnDimension('Q')->setWidth(20);
        $sheet->getColumnDimension('R')->setWidth(20);
        $sheet->getColumnDimension('S')->setWidth(20);
        $sheet->getColumnDimension('T')->setWidth(20);
        $sheet->getColumnDimension('U')->setWidth(20);


        $sheet->mergeCells('A'.$rc.':I'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Menu Hourly Sales Report');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;
        
        $dates = explode(" to ",$_GET['calendar_range']);
        $from = sql2DateTime($dates[0]);
        $to = sql2DateTime($dates[1]);
        $sheet->getCell('A'.$rc)->setValue('Date From: '.$from);
        $sheet->mergeCells('A'.$rc.':I'.$rc);
        $rc++;

        $sheet->getCell('A'.$rc)->setValue('Date To: '.$to);
        $sheet->mergeCells('A'.$rc.':I'.$rc);
        $rc++;

        $sheet->getCell('A'.$rc)->setValue('TO CHOOKS!');
        $sheet->mergeCells('A'.$rc.':I'.$rc);
        $rc++;
        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }

        $rc++;
        
        $ctr = 1;
        foreach ($datas as $sid => $trans) {
            foreach ($trans as $res => $v) {
                // $this->make->sRow();
                // $datas[$v->sales_id][] = array(
                //     'datetime'=>$v->datetime,
                //     'terminal_code'=>'T00001',
                //     'outlet'=>'Chooks',
                //     'customer'=>'',
                //     'trans_ref'=>$v->trans_ref,
                //     'menu_code'=>$v->menu_code,
                //     'menu_name'=>$v->menu_name,
                //     'qty'=>$v->qty,
                //     'price'=>$v->mprice,
                //     'gross'=>$v->gross,
                //     'trans_type'=>$v->trans_type,
                //     'guest'=>$v->guest,
                //     'sales_id'=>$v->sales_id,
                //     'charges'=>$charges,
                //     'remarks'=>$remark,
                //     'line_id'=>$v->line_id,
                //     'discount'=>$discounts,
                //     'disc_line_id'=>$disc_line_id,
                //     'disc_name'=>$disc_name,
                //     'lessvat'=>$lessvat,
                //     'net_sales'=>$net_sales,
                //     'span_disc'=>$span_disc,
                //     'span_charge'=>$span_charge,
                // );


                $sheet->getCell('A'.$rc)->setValue($ctr);
                $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('B'.$rc)->setValue(date('h:i:s A',strtotime($v['datetime'])));
                $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('C'.$rc)->setValue(date('m/d/Y',strtotime($v['datetime'])));
                $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('D'.$rc)->setValue($v['terminal_code']);
                $sheet->getStyle('D'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('E'.$rc)->setValue($v['outlet']);
                $sheet->getStyle('E'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('F'.$rc)->setValue('`'.$v['trans_ref']);
                $sheet->getStyle('F'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('G'.$rc)->setValue($v['customer']);
                $sheet->getStyle('G'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('H'.$rc)->setValue($v['menu_code']);
                $sheet->getStyle('H'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('I'.$rc)->setValue($v['menu_name']);
                $sheet->getStyle('I'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('J'.$rc)->setValue($v['qty']);
                $sheet->getStyle('J'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('K'.$rc)->setValue(num($v['gross']));
                $sheet->getStyle('K'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('L'.$rc)->setValue(num($v['gross']));
                $sheet->getStyle('L'.$rc)->applyFromArray($styleNum);

                if($v['span_disc'] == 'span'){
                    if($v['discount'] != 0){
                        //discount whole transaction
                        $rowspan = count($datas[$v['sales_id']]);
                        // $this->make->td($v['disc_name'],array('rowspan'=>$rowspan,'style'=>'vertical-align:middle;'));
                        // $this->make->td(num($v['discount']),array('rowspan'=>$rowspan,'style'=>'vertical-align:middle;'));
                        // $this->make->td(num($v['lessvat']),array('rowspan'=>$rowspan,'style'=>'vertical-align:middle;'));
                        // $this->make->td(num($v['net_sales']),array('rowspan'=>$rowspan,'style'=>'vertical-align:middle;'));

                        $merge_ct = $rc + $rowspan - 1;

                        $sheet->mergeCells('M'.$rc.':M'.$merge_ct);
                        $sheet->getCell('M'.$rc)->setValue($v['disc_name']);
                        $sheet->getStyle('M'.$rc)->applyFromArray($styleTxtMerge);
                        $sheet->mergeCells('N'.$rc.':N'.$merge_ct);
                        $sheet->getCell('N'.$rc)->setValue(num($v['discount']));
                        $sheet->getStyle('N'.$rc)->applyFromArray($styleNumMerge);
                        $sheet->mergeCells('O'.$rc.':O'.$merge_ct);
                        $sheet->getCell('O'.$rc)->setValue(num($v['lessvat']));
                        $sheet->getStyle('O'.$rc)->applyFromArray($styleNumMerge);
                        $sheet->mergeCells('P'.$rc.':P'.$merge_ct);
                        $sheet->getCell('P'.$rc)->setValue(num($v['net_sales']));
                        $sheet->getStyle('P'.$rc)->applyFromArray($styleNumMerge);


                    }
                   
                }else{
                    if($v['discount'] != 0){
                        // $this->make->td($v['disc_name']);
                        // $this->make->td(num($v['discount']));
                        // $this->make->td(num($v['lessvat']));
                        // $this->make->td(num($v['net_sales']));

                        $sheet->getCell('M'.$rc)->setValue($v['disc_name']);
                        $sheet->getStyle('M'.$rc)->applyFromArray($styleTxt);
                        $sheet->getCell('N'.$rc)->setValue(num($v['discount']));
                        $sheet->getStyle('N'.$rc)->applyFromArray($styleNum);
                        $sheet->getCell('O'.$rc)->setValue(num($v['lessvat']));
                        $sheet->getStyle('O'.$rc)->applyFromArray($styleNum);
                        $sheet->getCell('P'.$rc)->setValue(num($v['net_sales']));
                        $sheet->getStyle('P'.$rc)->applyFromArray($styleNum);

                    }else{

                        // $this->make->td('');
                        // $this->make->td('0');
                        // $this->make->td('0');
                        // $this->make->td(num($v['gross']));

                        $sheet->getCell('M'.$rc)->setValue('');
                        $sheet->getStyle('M'.$rc)->applyFromArray($styleTxt);
                        $sheet->getCell('N'.$rc)->setValue(num(0));
                        $sheet->getStyle('N'.$rc)->applyFromArray($styleNum);
                        $sheet->getCell('O'.$rc)->setValue(num(0));
                        $sheet->getStyle('O'.$rc)->applyFromArray($styleNum);
                        $sheet->getCell('P'.$rc)->setValue(num($v['gross']));
                        $sheet->getStyle('P'.$rc)->applyFromArray($styleNum);
                    }
                }

                $sheet->getCell('Q'.$rc)->setValue($v['payment_type']);
                $sheet->getStyle('Q'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('R'.$rc)->setValue($v['trans_type']);
                $sheet->getStyle('R'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('S'.$rc)->setValue($v['guest']);
                $sheet->getStyle('S'.$rc)->applyFromArray($styleTxt);

                if($v['span_charge'] == 'span'){
                    if($v['charges'] != 0){
                        //discount whole transaction
                        $rowspan = count($datas[$v['sales_id']]);
                        // $this->make->td(num($v['charges']),array('rowspan'=>$rowspan,'style'=>'vertical-align:middle;'));
                        $merge_ct = $rc + $rowspan - 1;

                        $sheet->mergeCells('T'.$rc.':T'.$merge_ct);
                        $sheet->getCell('T'.$rc)->setValue(num($v['charges']));
                        $sheet->getStyle('T'.$rc)->applyFromArray($styleNumMerge);
                    }
                }else{
                    $sheet->getCell('T'.$rc)->setValue(num(0));
                    $sheet->getStyle('T'.$rc)->applyFromArray($styleNum);
                }


                $sheet->getCell('U'.$rc)->setValue($v['remarks']);
                $sheet->getStyle('U'.$rc)->applyFromArray($styleTxt);

                $rc++;
                $ctr++;
            }
        }    

        // foreach ($menus as $res) {
        //         $sheet->getCell('A'.$rc)->setValue($res['code']);
        //         $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        //         $sheet->getCell('B'.$rc)->setValue($res['name']);
        //         $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);
        //         $sheet->getCell('C'.$rc)->setValue($res['sell_price']);
        //         $sheet->getStyle('C'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('D'.$rc)->setValue($res['qty']);     
        //         $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('E'.$rc)->setValue(num( ($res['qty'] / $total_qty) * 100 ).'%');     
        //         $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('F'.$rc)->setValue(num($res['amount']));     
        //         $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('G'.$rc)->setValue(num( ($res['amount'] / $menu_total) * 100 ).'%');
        //         $sheet->getStyle('G'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('H'.$rc)->setValue($res['cost_price']);
        //         $sheet->getStyle('H'.$rc)->applyFromArray($styleNum);
        //         $sheet->getCell('I'.$rc)->setValue($res['cost_price'] * $res['qty']);
        //         $sheet->getStyle('I'.$rc)->applyFromArray($styleNum);

        //     $rc++;
        // } 
        $rc++;


        // $mods_total = $trans_menus['mods_total'];
        // if($mods_total > 0){
        //     $sheet->getCell('A'.$rc)->setValue('Total Modifiers Sale: ');
        //     $sheet->getCell('B'.$rc)->setValue(num($mods_total));
        //     $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        //     $rc++;
        // }
        // $net_no_adds = $net-$charges-$local_tax;
        // $sheet->getCell('A'.$rc)->setValue('Total Sales: ');
        // $sheet->getCell('B'.$rc)->setValue(num($net));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $txt = numInt(($charges));
        // if($charges > 0)
        //     $txt = "(".numInt(($charges)).")";
        // $sheet->getCell('A'.$rc)->setValue('Total Charges: ');
        // $sheet->getCell('B'.$rc)->setValue($txt);
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $txt = numInt(($local_tax));
        // if($local_tax > 0)
        //     $txt = "(".numInt(($local_tax)).")";
        // $sheet->getCell('A'.$rc)->setValue('Total Local Tax: ');
        // $sheet->getCell('B'.$rc)->setValue($txt);
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('Total Discounts: ');
        // $sheet->getCell('B'.$rc)->setValue(num($discounts));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('Total VAT EXEMPT: ');
        // $sheet->getCell('B'.$rc)->setValue(num($less_vat));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        // $sheet->getCell('A'.$rc)->setValue('Total Gross Sales: ');
        // $sheet->getCell('B'.$rc)->setValue(num($gross));
        // $sheet->getStyle('B'.$rc)->applyFromArray($styleNum);
        // $rc++;
        
        update_load(100);
        ob_end_clean();
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public function customer_balance_rep(){
        $this->load->model('dine/gift_cards_model');
        //get sa database yun mga payment types

        $data = $this->syter->spawn('gc_sales_rep');        
        $data['page_title'] = fa('fa-money')." Customer Balance";
        $data['code'] = customerBalanceRep();
        $data['add_css'] = array('css/morris/morris.css','css/datepicker/datepicker.css','css/daterangepicker/daterangepicker-bs3.css');
        $data['add_js'] = array('js/plugins/morris/morris.min.js','js/plugins/datepicker/bootstrap-datepicker.js','js/plugins/daterangepicker/daterangepicker.js');
        $data['page_no_padding'] = false;
        $data['sideBarHide'] = false;
        $data['load_js'] = 'dine/reporting';
        $data['use_js'] = 'custBalanceRepJS';
        $this->load->view('page',$data);
    }

     public function cust_balance_rep_gen()
    {
        $this->load->model('dine/setup_model');
        // $this->load->database('main', TRUE);
        $this->load->model("dine/cashier_model");
        
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];
        start_load(0);
        $customer_id = $this->input->post("customer_id");        
        // $brand = $this->input->post("brand");        
        $daterange = $this->input->post("calendar_range");        
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        // $date = $this->input->post("date");
        $this->menu_model->db = $this->load->database('main', TRUE);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        $cust_balance = $this->cashier_model->get_cust_balance($from, $to, $customer_id);  
        // echo $this->db->last_query();              
        // echo "<pre>", print_r($trans), "</pre>"; die();
        $trans_count = count($cust_balance);
        $counter = 0;

        $tot_amount = 0;
        $tot_paid = 0;
        
        $this->make->sDiv();
            $this->make->sTable(array("id"=>"main-tbl", 'class'=>'table reportTBL sortable'));
                $this->make->sTableHead();
                    $this->make->sRow();
                        $this->make->th('Reference #');
                        $this->make->th('Customer Name');
                        $this->make->th('Transaction Date');
                        $this->make->th('Amount Due');
                        $this->make->th('Amount Paid');
                        $this->make->th('Balance');
                        $this->make->th('');
                    $this->make->eRow();
                $this->make->eTableHead();
                $this->make->sTableBody();
                   
                    foreach ($cust_balance as $res) {
                        $this->make->sRow();
                            $this->make->td($res->trans_ref);
                            $this->make->td(ucwords($res->fname .' '.$res->lname));
                            $this->make->td(sql2DateTime($res->datetime)); 
                            $this->make->td(num($res->total_amount), array("style"=>"text-align:right"));
                            $this->make->td(num($res->ar_payment_amount), array("class"=>"ar-amount","style"=>"text-align:right"));                           
                            $this->make->td(num($res->total_amount-$res->ar_payment_amount), array("class"=>"ar-balance","style"=>"text-align:right"));
                            
                            if($res->total_amount == $res->ar_payment_amount){
                                $this->make->append('<td>');
                                    $this->make->button('PAID',array('class'=>'btn-block tables-btn-teal btn-success','disabled'=>'disabled'));
                                $this->make->append('</td>');
                            }else{
                                $this->make->append('<td>');
                                    $this->make->button(fa('fa-money fa-sm fa-fw').' Add Payment',array('id'=>'btn-'.$res->sales_id,'ref'=>$res->sales_id,'class'=>'add-payment btn-block tables-btn-teal'));
                                $this->make->append('</td>');
                            }

                        $this->make->eRow();

                         // Grand Total
                        $tot_amount += $res->total_amount;
                        
                        $tot_paid += $res->ar_payment_amount;

                        $counter++;
                        $progress = ($counter / $trans_count) * 100;
                        update_load(num($progress));

                    }    
                    $this->make->sRow();
                        $this->make->th('Grand Total');
                        $this->make->th('');
                        $this->make->th('');
                        $this->make->th(num($tot_amount), array("style"=>"text-align:right"));
                        $this->make->th(num($tot_paid), array("style"=>"text-align:right"));
                        $this->make->th(num($tot_amount-$tot_paid), array("style"=>"text-align:right"));                                           
                        $this->make->th('');
                    $this->make->eRow();                                 
                $this->make->eTableBody();
            $this->make->eTable();
        $this->make->eDiv();
        update_load(100);
        $code = $this->make->code();
        $json['code'] = $code;        
        $json['dates'] = $this->input->post('calendar_range');
        echo json_encode($json);
    }

    public function cust_balance_rep_pdf()
    {
        // Include the main TCPDF library (search for installation path).
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Customer Balance Report');
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

        // ---------------------------------------------------------
        $this->load->model('dine/setup_model');
        // $this->load->database('main', TRUE);
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/cashier_model");
        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();
         
        $customer_id = $_GET['customer_id'];     
        $daterange = $_GET['calendar_range'];        
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        
        $cust_balance = $this->cashier_model->get_cust_balance($from, $to, $customer_id);
        // $trans_payment = $this->menu_model->get_payment_date($from, $to);
        // echo $this->db->last_query();die();                  


        $pdf->Write(0, 'Customer Balance Report', '', 0, 'L', true, 0, false, false, 0);
        $pdf->SetLineStyle(array('width' => 0.6, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln(0.9);      
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Write(0, 'Report Period:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->Write(0, $daterange, '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $pdf->Write(0, 'Report Generated:    '.(new \DateTime())->format('Y-m-d H:i:s'), '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Transaction Time:    ', '', 0, 'L', false, 0, false, false, 0);
        $pdf->setX(200);
        $user = $this->session->userdata('user');
        $pdf->Write(0, 'Generated by:    '.$user["full_name"], '', 0, 'L', true, 0, false, false, 0);        
        $pdf->ln(1);      
        $pdf->Cell(267, 0, '', 'T', 0, 'C');
        $pdf->ln();              

        // echo "<pre>", print_r($trans), "</pre>";die();

        // -----------------------------------------------------------------------------
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => 'black'));
        $pdf->Cell(45, 0, 'Reference #', 'B', 0, 'L');  
        $pdf->Cell(70, 0, 'Customer Name', 'B', 0, 'L');      
        $pdf->Cell(32, 0, 'Transaction Date', 'B', 0, 'L');
        $pdf->Cell(32, 0, 'Amount Due', 'B', 0, 'R');
        $pdf->Cell(32, 0, 'Amount Paid', 'B', 0, 'R');        
        // $pdf->Cell(32, 0, 'VAT Sales', 'B', 0, 'C');        
        // $pdf->Cell(32, 0, 'VAT', 'B', 0, 'C');        
        $pdf->Cell(32, 0, 'Balance', 'B', 0, 'R');  
        $pdf->ln();                  

        // GRAND TOTAL VARIABLES
        $tot_amount = 0;
        $tot_paid = 0;
        $counter = 0;
        $progress = 0;

        if(count($cust_balance) > 0){
            
            $pdf->ln(); 

           
            $trans_count = count($cust_balance);
           
            foreach ($cust_balance as $k => $v) {
                $pdf->Cell(45, 0, $v->trans_ref, '', 0, 'L');
                $pdf->Cell(70, 0, ucwords($v->fname.' '.$v->lname), '', 0, 'L');         
                $pdf->Cell(32, 0, sql2DateTime($v->datetime), '', 0, 'L');
                $pdf->Cell(32, 0, num($v->total_amount), '', 0, 'R');
                $pdf->Cell(32, 0, num($v->ar_payment_amount), '', 0, 'R');  
                $pdf->Cell(32, 0, num($v->total_amount-$v->ar_payment_amount), '', 0, 'R');        
                $pdf->ln();                

                // Grand Total
                $tot_amount += $v->total_amount;
                $tot_paid += $v->ar_payment_amount;

                $counter++;
                $progress = ($counter / $trans_count) * 100;
                update_load(num($progress));              
            }

            update_load(100);        
            $pdf->Cell(45, 0, "Grand Total", 'T', 0, 'L');
            $pdf->Cell(70, 0, "", 'T', 0, 'R');
            $pdf->Cell(32, 0, "", 'T', 0, 'R');
            $pdf->Cell(32, 0, num($tot_amount), 'T', 0, 'R');
            $pdf->Cell(32, 0, num($tot_paid), 'T', 0, 'R');          
            $pdf->Cell(32, 0, num($tot_amount-$tot_paid), 'T', 0, 'R'); 
        }

        update_load(100);    
        //Close and output PDF document
        $pdf->Output('customer_balance_report.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+   
    }

    public function cust_balance_rep_excel()
    {
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/cashier_model");
        date_default_timezone_set('Asia/Manila');
        $this->load->library('Excel');
        $sheet = $this->excel->getActiveSheet();
        $filename = 'Customer Balance Report';
        $rc=1;
        #GET VALUES
        start_load(0);
            // $post = $this->set_post($_GET['calendar_range']);
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];

        update_load(10);
        sleep(1);
        
        $customer_id = $_GET['customer_id'];        
        $daterange = $_GET['calendar_range'];        
        $dates = explode(" to ",$daterange);
        // $from = date2SqlDateTime($dates[0]);
        // $to = date2SqlDateTime($dates[1]);
        $from = date2SqlDateTime($dates[0]. " ".$set->store_open);        
        $to = date2SqlDateTime(date('Y-m-d', strtotime($dates[1] . ' +1 day')). " ".$set->store_open);
        
        $cust_balance = $this->cashier_model->get_cust_balance($from, $to, $customer_id);  
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
        
        $headers = array('Reference #', 'Customer Name','Transaction Date','Amount Due','Amount Paid','Balance');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);

        $sheet->mergeCells('A'.$rc.':E'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->branch_name);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':E'.$rc);
        $sheet->getCell('A'.$rc)->setValue($set->address);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTitle);
        $rc++;

        $sheet->mergeCells('A'.$rc.':E'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Customer Balance Report');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Report Period: '.$daterange);
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('E'.$rc)->setValue('Report Generated: '.(new \DateTime())->format('Y-m-d H:i:s'));
        $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
        $rc++;

        $sheet->mergeCells('A'.$rc.':D'.$rc);
        $sheet->getCell('A'.$rc)->setValue('Transaction Time:');
        $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
        $user = $this->session->userdata('user');
        $sheet->mergeCells('E'.$rc.':G'.$rc);
        $sheet->getCell('F'.$rc)->setValue('Generated by:    '.$user["full_name"]);
        $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);
        $rc++;

        $col = 'A';
        foreach ($headers as $txt) {
            $sheet->getCell($col.$rc)->setValue($txt);
            $sheet->getStyle($col.$rc)->applyFromArray($styleHeaderCell);
            $col++;
        }
        $rc++;                          

        // GRAND TOTAL VARIABLES
        $tot_amount = 0;
        $tot_paid = 0;
        
        $counter = 0;
        $progress = 0;
      

        //retail
        if(count($cust_balance) > 0){                    

            $trans_count = count($cust_balance);

            foreach ($cust_balance as $k => $v) {
                $sheet->setCellValueExplicit('A'.$rc, $v->trans_ref,PHPExcel_Cell_DataType::TYPE_STRING);
                // $sheet->getCell('A'.$rc)->setValue($v->trans_ref);
                // $sheet->getStyle('A'.$rc)->applyFromArray($styleTxt);
                $sheet->getCell('B'.$rc)->setValue(ucwords($v->fname.' '.$v->lname));
                $sheet->getStyle('B'.$rc)->applyFromArray($styleTxt);

                $sheet->getCell('C'.$rc)->setValue(sql2DateTime($v->datetime));
                $sheet->getStyle('C'.$rc)->applyFromArray($styleTxt);

                $sheet->getCell('D'.$rc)->setValue(num($v->total_amount));
                $sheet->getStyle('D'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('E'.$rc)->setValue(($v->ar_payment_amount));
                $sheet->getStyle('E'.$rc)->applyFromArray($styleNum);
                $sheet->getCell('F'.$rc)->setValue(num($v->total_amount-$v->ar_payment_amount));     
                $sheet->getStyle('F'.$rc)->applyFromArray($styleNum);                                     
                 

                // Grand Total
                $tot_amount += $v->total_amount;
                $tot_paid += $v->ar_payment_amount;

                $counter++;
                $progress = ($counter / $trans_count) * 100;
                update_load(num($progress));   
                $rc++;           
            }

            $sheet->getCell('A'.$rc)->setValue('Grand Total');
            $sheet->getStyle('A'.$rc)->applyFromArray($styleBoldLeft);
            $sheet->getCell('D'.$rc)->setValue(num($tot_amount));
            $sheet->getStyle('D'.$rc)->applyFromArray($styleBoldRight);

            $sheet->getCell('E'.$rc)->setValue(num($tot_paid));
            $sheet->getStyle('E'.$rc)->applyFromArray($styleBoldRight);
            
            $sheet->getCell('F'.$rc)->setValue(num($tot_amount-$tot_paid));     
            $sheet->getStyle('F'.$rc)->applyFromArray($styleBoldRight);
           
            $rc++; 
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

    function update_ar_amount(){
        $sales_id = $this->input->post('sales_id');
        $amount = $this->input->post('amount');

        $trans_sales = $this->cashier_model->get_just_trans_sales($sales_id)->result();

        $total_ar_amount = $trans_sales[0]->ar_payment_amount + $amount;

        if($total_ar_amount > $trans_sales[0]->total_amount){
            echo json_encode(array('status'=>'error','msg'=>'Total amount paid should not be more than total amount.'));
        }else{
            $this->cashier_model->update_trans_sales(array('ar_payment_amount'=>$total_ar_amount),$sales_id);

            $total_balance = $trans_sales[0]->total_amount - $total_ar_amount;

            echo json_encode(array('status'=>'success','ar_amount'=>$total_ar_amount,'balance'=>$total_balance));
        }

        
    }

    public function print_pdf_gc($sales_id=null,$asJson=true,$return_print_str=false,$add_reprinted=true,$splits=null,$include_footer=true,$no_prints=1,$order_slip_prints=0,$approved_by=null,$main_db=false,$openDrawer=false)
    {
        // include(dirname(__FILE__)."/cashier.php");
        // Include the main TCPDF library (search for installation path).
        require_once( APPPATH .'third_party/tcpdf.php');
        $this->load->model("dine/setup_model");
        date_default_timezone_set('Asia/Manila');

        // create new PDF document
        $pdf = new TCPDF("P", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('iPOS');
        $pdf->SetTitle('Gift Cheque Report');
        $pdf->SetSubject('');
        $pdf->SetKeywords('');

        // set default header data
        $setup = $this->setup_model->get_details(1);
        $set = $setup[0];
        // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $set->branch_name, $set->address);

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

        // ---------------------------------------------------------
        $this->load->model('dine/setup_model');
        // $this->load->database('main', TRUE);
        $this->menu_model->db = $this->load->database('main', TRUE);
        $this->load->model("dine/menu_model");
        start_load(0);

        // set font
        $pdf->SetFont('helvetica', 'B', 11);

        // add a page
        $pdf->AddPage();

        if($main_db){
            $this->db = $this->load->database('main', TRUE);
        }
        $branch = $this->get_branch_details(false);
        $return = $this->get_gc_order(false,$sales_id);
        $order = $return['order'];
        $details = $return['details'];
        $payments = $return['payments'];
        $pfields = $return['pfields'];
        $discounts = $return['discounts'];
        $local_tax = $return['local_tax'];
        $charges = $return['charges'];
        $tax = $return['taxes'];
        $no_tax = $return['no_tax'];
        $zero_rated = $return['zero_rated'];
        $totalsss = $this->total_trans(false,$details,$discounts);
        $discs = $totalsss['discs'];
        $is_printed = $order['printed'];
        $print_str = $print_str_part1 = $print_str_part2 = $print_str_part3 = "\r\n";
        $is_billing = false;
        $open_drawer = false;

        $wrap = wordwrap($branch['name'],PAPER_WIDTH,"|#|");
        $exp = explode("|#|", $wrap);

        $pdf->SetFont('helvetica', '', 12);

        foreach ($exp as $v) {
            $pdf->Cell(50, 0, $v, '', 0, 'C'); 
            $pdf->ln();  
        }

        $splt = 1;
        $cancelled = 0;

        $wrap = wordwrap($branch['address'],PAPER_WIDTH,"|#|");
        $exp = explode("|#|", $wrap);
        foreach ($exp as $v) {
            $pdf->Cell(50, 0, $v, '', 0, 'C'); 
            $pdf->ln();  
        }   
       
        $pdf->ln();
        $pdf->Cell(50, 0, date('M d, Y h:i A',strtotime($order['datetime'])), '', 0, 'R'); 
        $pdf->ln();
        $pdf->ln();

        $pdf->Cell(50, 0, 'ACKNOWLEDGEMENT RECEIPT ', '', 0, 'L'); 
        $pdf->ln();
        $pdf->Cell(50, 0, "AR #: ".$order['ref']."", '', 0, 'L'); 
        $pdf->ln();
        $pdf->ln();

        $pdf->Cell(50, 0, strtoupper($order['type']), '', 0, 'L'); 
        $pdf->ln();
        $pdf->ln();

        $pdf->Cell(50, 0, "Register No:  ".$order['terminal_id'], '', 0, 'L'); 
        $pdf->ln();
        $pdf->Cell(50, 0, "Cashier Name:  ".ucwords($order['name']), '', 0, 'L'); 
        $pdf->ln();
        $pdf->Cell(50, 0, "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _", '', 0, 'L'); 
        $pdf->ln();

        
        $log_user = $this->session->userdata('user');

        if($order['customer_id'] != ""){
            if($main_db){
                $this->db = $this->load->database('default', TRUE);
            }
            $this->load->model('dine/customers_model');
            $customers = $this->customers_model->get_customer($order['customer_id']);
            if(count($customers) > 0){
                $cust = $customers[0];
                $name = strtolower($cust->fname." ".$cust->mname." ".$cust->lname." ".$cust->suffix);
                $pdf->Cell(50, 0, "Customer : ".ucwords($name), '', 0, 'R'); 
                // $print_str .= "Customer : ".$this->append_chars(ucwords($name),"right",19," ")."\r\n";
                $pdf->Cell(50, 0, "Customer : ".ucwords($cust->phone), '', 0, 'R'); 
                    $pdf->ln();
                    $address = strtolower($cust->street_no." ".$cust->street_address." ".$cust->city." ".$cust->region." ".$cust->zip);
                    $pdf->Cell(50, 0, "Address  : ".ucwords($address), '', 0, 'R'); 
                    $pdf->ln();
                    // $print_str .= "Address  : ".$this->append_chars(ucwords($address),"right",19," ")."\r\n";
                }
            }

            if($main_db){
                $this->db = $this->load->database('main', TRUE);
            }
        // }
        if($order['type'] == 'delivery' && $order['customer_id'] != ""){
        
        }
        $pre_total = 0;
            $post_details = array();
            $post_details_nc = array();
            $discs_items = array();
            foreach ($discs as $disc) {
                if(isset($disc['items']))
                    $discs_items[$disc['type']] = $disc['items'];
            }

            // echo "<pre>",print_r($details),"</pre>";die();

            $dscTxt = array();
            foreach ($details as $line_id => $val) {
                foreach ($discs_items as $type => $dissss) {
                    if(in_array($line_id, $dissss)){
                        $qty = 1;
                        if(isset($dscTxt[$val['menu_id'].'_'.$val['price']][$type]['qty'])){
                            $qty = $dscTxt[$val['menu_id'].'_'.$val['price']][$type]['qty'] + 1;
                        }
                        $dscTxt[$val['menu_id'].'_'.$val['price']][$type] = array('txt' => '#'.$type,'qty' => $qty);
                    }
                }
            }

            foreach ($details as $line_id => $val) {
            if($val['nocharge'] == 1){
                if (!isset($post_details_nc[$val['menu_id'].'_'.$val['price']])) {
                    $dscsacs = array();
                    if(isset($dscTxt[$val['menu_id'].'_'.$val['price']])){
                        $dscsacs = $dscTxt[$val['menu_id'].'_'.$val['price']];
                    }
                    $remarksArr = array();
                    if($val['remarks'] != '')
                        $remarksArr = array($val['remarks']." x ".$val['qty']);
                    $post_details_nc[$val['menu_id'].'_'.$val['price']] = array(
                        'name' => $val['name'],
                        'code' => $val['code'],
                        'price' => $val['price'],
                        'no_tax' => $val['no_tax'],
                        'discount' => $val['discount'],
                        'qty' => $val['qty'],
                        'discounted'=>$dscsacs,
                        'remarks'=>$remarksArr,
                        'modifiers' => array()
                    );
                } else {
                    if($val['remarks'] != "")
                        $post_details_nc[$val['menu_id'].'_'.$val['price']]['remarks'][]= $val['remarks']." x ".$val['qty'];
                    $post_details_nc[$val['menu_id'].'_'.$val['price']]['qty'] += $val['qty'];
                }

                if (empty($val['modifiers']))
                    continue;

                $modifs = $val['modifiers'];
                $n_modifiers = $post_details_nc[$val['menu_id'].'_'.$val['price']]['modifiers'];
                foreach ($modifs as $vv) {
                    if (!isset($n_modifiers[$vv['id']])) {
                        $n_modifiers[$vv['id']] = array(
                            'name' => $vv['name'],
                            'price' => $vv['price'],
                            'qty' => $val['qty'],
                            'discount' => $vv['discount']
                        );
                    } else {
                        $n_modifiers[$vv['id']]['qty'] += $val['qty'];
                    }
                }
                $post_details_nc[$val['menu_id'].'_'.$val['price']]['modifiers'] = $n_modifiers;
            }else{
                if (!isset($post_details[$val['menu_id'].'_'.$val['price']])) {
                    $dscsacs = array();
                    if(isset($dscTxt[$val['menu_id'].'_'.$val['price']])){
                        $dscsacs = $dscTxt[$val['menu_id'].'_'.$val['price']];
                    }
                    $remarksArr = array();
                    if($val['remarks'] != '')
                        $remarksArr = array($val['remarks']." x ".$val['qty']);
                    
                    $post_details[$val['menu_id'].'_'.$val['price']] = array(
                        'name' => $val['name'],
                        'code' => $val['code'],
                        'price' => $val['price'],
                        'no_tax' => $val['no_tax'],
                        'discount' => $val['discount'],
                        'qty' => $val['qty'],
                        'discounted'=>$dscsacs,
                        'remarks'=>$remarksArr,
                        'modifiers' => array()
                    );
                } else {
                    if($val['remarks'] != "")
                        $post_details[$val['menu_id'].'_'.$val['price']]['remarks'][]= $val['remarks']." x ".$val['qty'];
                    $post_details[$val['menu_id'].'_'.$val['price']]['qty'] += $val['qty'];
                }

                if (empty($val['modifiers']))
                    continue;

                $modifs = $val['modifiers'];
                $n_modifiers = $post_details[$val['menu_id'].'_'.$val['price']]['modifiers'];
                foreach ($modifs as $vv) {
                    if (!isset($n_modifiers[$vv['id']])) {
                        $n_modifiers[$vv['id']] = array(
                            'name' => $vv['name'],
                            'price' => $vv['price'],
                            'qty' => $val['qty'],
                            'discount' => $vv['discount']
                        );
                    } else {
                        $n_modifiers[$vv['id']]['qty'] += $val['qty'];
                    }
                }
                $post_details[$val['menu_id'].'_'.$val['price']]['modifiers'] = $n_modifiers;
            }

        }
        /* END NEW BLOCK */
        $tot_qty = 0;
        $pdf->Cell(20, 0, "QTY", '', 0, 'L'); 
        $pdf->Cell(50, 0, "Item Description", '', 0, 'C'); 
        $pdf->Cell(30, 0, "Amount", '', 0, 'R'); 
        $pdf->ln();
        $pdf->ln();

        foreach ($post_details as $val) {
            $tot_qty += $val['qty'];
            // $print_str .= $this->append_chars($val['qty'] ."  " ,"right",PAPER_DET_COL_1," ");
            $pdf->Cell(20, 0, $val['qty'], '', 0, 'L'); 

            $len = strlen($val['name']);

            if($val['qty'] == 1){
                $lgth = 21;
            }else{
                $lgth = 16;
            }

            if($len > $lgth){
                $arr2 = str_split($val['name'], $lgth);
                $counter = 1;
                foreach($arr2 as $k => $vv){
                    if($counter == 1){
                        if ($val['qty'] == 1) {
                            // $pdf->Cell(20, 0, "", '', 0, 'L'); 
                            $pdf->Cell(50, 0, substrwords($vv,100,""), '', 0, 'L'); 
                            $pdf->Cell(30, 0, number_format($val['price'],2), '', 0, 'R'); 
                            $pdf->ln();
                        } else {
                            // $pdf->Cell(20, 0, "", '', 0, 'L'); 
                            $pdf->Cell(50, 0, substrwords($vv,100,""), '', 0, 'L'); 
                            $pdf->Cell(30, 0, number_format($val['price'] * $val['qty'],2), '', 0, 'R'); 
                            $pdf->ln();
                        }
                    }else{
                        // if ($val['qty'] == 1) {
                            // $pdf->Cell(20, 0, "", '', 0, 'L'); 
                            $pdf->Cell(50, 0, substrwords($vv,100,""), '', 0, 'L'); 
                            $pdf->Cell(30, 0, "", '', 0, 'R');
                            $pdf->ln();
                    }
                    $counter++;
                }
                
                if ($val['qty'] == 1) {
                    $pre_total += $val['price'];
                }else{
                    $pre_total += $val['price'] * $val['qty'];
                }
            }else{
                if ($val['qty'] == 1) {
                    // $pdf->Cell(20, 0, "e", '', 0, 'L');
                    $pdf->Cell(50, 0, substrwords($val['name'],100,""), '', 0, 'L');
                    $pdf->Cell(30, 0, number_format($val['price'],2), '', 0, 'R');
                     $pdf->ln();
                    $pre_total += $val['price'];
                } else {
                    $pdf->Cell(20, 0, "", '', 0, 'L');
                    $pdf->Cell(50, 0, substrwords($val['name'],100,""), '', 0, 'L');
                    $pdf->Cell(30, 0, number_format($val['price'] * $val['qty'],2), '', 0, 'R');
                     $pdf->ln();
                    $pre_total += $val['price'] * $val['qty'];
                }
            }
            if(count($val['discounted']) > 0){
                foreach ($val['discounted'] as $dssstxt) {
                  // $print_str .= "      ";
                    // $pdf->Cell(20, 0, "", '', 0, 'R');
                    $pdf->Cell(50, 0, "", '', 0, 'L');
                    $pdf->Cell(30, 0, $dssstxt['txt']." x ".$dssstxt['qty'], '', 0, 'R');
                     $pdf->ln();
                }
            }
            if(isset($val['remarks']) && count($val['remarks']) > 0){
                if(KERMIT){
                    foreach ($val['remarks'] as $rmrktxt) {
                        // $pdf->Cell(20, 0, " ", '', 0, 'R');
                        $pdf->Cell(50, 0, "  * ", '', 0, 'L');
                        $pdf->Cell(30, 0, ucwords($rmrktxt), '', 0, 'R');
                         $pdf->ln();
                    }
                }
            }

            if (empty($val['modifiers']))
                continue;

            $modifs = $val['modifiers'];
            foreach ($modifs as $vv) {
                // $print_str .= "   * ".$vv['qty']." ";
                $pdf->ln();
                $pdf->Cell(20, 0, "", '', 0, 'L');
                $pdf->Cell(5, 0, "*".$vv['qty']." ", '', 0, 'L');
                if ($vv['qty'] == 1) {
                    // $pdf->Cell(20, 0, "   ", '', 0, 'L');
                    $pdf->Cell(45, 0, substrwords($vv['name'],100,""), '', 0, 'L');
                    $pdf->Cell(30, 0, number_format($vv['price'],2), '', 0, 'R');
                    $pre_total += $vv['price'];
                } else {
                    // $pdf->Cell(20, 0, "   ", '', 0, 'L');
                    $pdf->Cell(45, 0, substrwords($vv['name'],100,""), '', 0, 'L');
                    $pdf->Cell(30, 0, number_format($vv['price'] * $vv['qty'],2), '', 0, 'R');
                    $pre_total += $vv['price'] * $vv['qty'];
                }
            }
            

        }

        //for no charges
        if($post_details_nc){
            // $print_str .= "\r\n";
            $pdf->ln();
            $pdf->Cell(20, 0, TAKEOUTTEXT, '', 0, 'L');
            $pdf->ln();
            // $print_str .= PAPER_LINE."\r\n";
            // $print_str .= "          ".TAKEOUTTEXT."\r\n";
            // $print_str .= PAPER_LINE."\r\n";
            foreach ($post_details_nc as $val) {
                $tot_qty += $val['qty'];
                $pdf->Cell(20, 0, $val['qty']." ", '', 0, 'L');
                // $print_str .= $this->append_chars($val['qty']." ","right",PAPER_DET_COL_1," ");

                $len = strlen($val['name']);

                if($val['qty'] == 1){
                    $lgth = 21;
                }else{
                    $lgth = 16;
                }

                if($len > $lgth){
                    $arr2 = str_split($val['name'], $lgth);
                    $counter = 1;
                    foreach($arr2 as $k => $vv){
                        if($counter == 1){
                            if ($val['qty'] == 1) {
                                 $pdf->Cell(50, 0, substrwords($vv,100,""), '', 0, 'L'); 
                                 $pdf->Cell(30, 0, number_format($val['price'],2), '', 0, 'R'); 
                                 $pdf->ln();
                                // $print_str .= $this->append_chars(substrwords($vv,100,""),"right",PAPER_DET_COL_2," ").
                                    // $this->append_chars(number_format($val['price'],2),"left",PAPER_DET_COL_3," ")."\r\n";
                            } else {
                                $pdf->Cell(50, 0, substrwords($vv,100,"")." ".$val['price'], '', 0, 'L'); 
                                $pdf->Cell(30, 0, number_format($val['price'] * $val['qty'],2), '', 0, 'R'); 
                                $pdf->ln();
                                // $print_str .= $this->append_chars(substrwords($vv,100,"")." ".$val['price'],"right",PAPER_DET_COL_2," ").
                                    // $this->append_chars(number_format($val['price'] * $val['qty'],2),"left",PAPER_DET_COL_3," ")."\r\n";
                            }
                        }else{
                            // if ($val['qty'] == 1) {
                                 $pdf->Cell(20, 0, "", '', 0, 'L'); 
                                 $pdf->Cell(50, 0, substrwords($vv,100,""), '', 0, 'L'); 
                                 $pdf->Cell(30, 0, "", '', 0, 'L'); 
                                 $pdf->ln();
                                // $print_str .= $this->append_chars("","right",PAPER_DET_COL_1," ");
                                // $print_str .= $this->append_chars(substrwords($vv,100,""),"right",PAPER_DET_COL_2," ").
                                    // $this->append_chars("","left",PAPER_DET_COL_3," ")."\r\n";
                            // } else {
                                // $print_str .= $this->append_chars(substrwords($vv,100,"")." @ ".$val['price'],"right",PAPER_DET_COL_2," ").
                                //     $this->append_chars("","left",PAPER_DET_COL_3," ")."\r\n";
                            // }
                        }
                        $counter++;
                    }
                    
                    if ($val['qty'] == 1) {
                        $pre_total += $val['price'];
                    }else{
                        $pre_total += $val['price'] * $val['qty'];
                    }
                }else{
                    if ($val['qty'] == 1) {
                        $pdf->Cell(50, 0, substrwords($val['name'],100,""), '', 0, 'L'); 
                        $pdf->Cell(30, 0, number_format($val['price'],2), '', 0, 'R'); 
                        $pdf->ln();
                        // $print_str .= $this->append_chars(substrwords($val['name'],100,""),"right",PAPER_DET_COL_2," ").
                            // $this->append_chars(number_format($val['price'],2),"left",PAPER_DET_COL_3," ")."\r\n";
                        $pre_total += $val['price'];
                    } else {
                        $pdf->Cell(50, 0, substrwords($val['name'],100,""), '', 0, 'L'); 
                        $pdf->Cell(30, 0, number_format($val['price'] * $val['qty'],2), '', 0, 'R'); 
                        $pdf->ln();
                        // $print_str .= $this->append_chars(substrwords($val['name'],100,"")." ".$val['price'],"right",PAPER_DET_COL_2," ").
                            // $this->append_chars(number_format($val['price'] * $val['qty'],2),"left",PAPER_DET_COL_3," ")."\r\n";
                        $pre_total += $val['price'] * $val['qty'];
                    }
                }
                if(count($val['discounted']) > 0){
                    foreach ($val['discounted'] as $dssstxt) {
                        $pdf->Cell(20, 0, "", '', 0, 'L'); 
                        $pdf->Cell(50, 0, $dssstxt['txt']." x ".$dssstxt['qty'], '', 0, 'R'); 
                        $pdf->ln();
                      // $print_str .= "      ";
                      // $print_str .= $this->append_chars($dssstxt['txt']." x ".$dssstxt['qty'],"right",PAPER_DET_COL_2," ")."\r\n";
                    }
                }
                if(isset($val['remarks']) && count($val['remarks']) > 0){
                    // foreach ($val['remarks'] as $rmrktxt) {
                    //     $print_str .= "     * ";
                    //     $print_str .= $this->append_chars(ucwords($rmrktxt),"right",PAPER_DET_COL_2," ")."\r\n";
                    // }
                }

                if (empty($val['modifiers']))
                    continue;

                $modifs = $val['modifiers'];
                foreach ($modifs as $vv) {
                    $print_str .= "   * ".$vv['qty']." ";

                    if ($vv['qty'] == 1) {
                        $pdf->Cell(50, 0, substrwords($vv['name'],100,""), '', 0, 'L'); 
                        $pdf->Cell(30, 0, number_format($vv['price'],2), '', 0, 'R'); 
                        $pdf->ln();
                        // $print_str .= $this->append_chars(substrwords($vv['name'],100,""),"right",PAPER_DET_SUBCOL," ")
                            // .$this->append_chars(number_format($vv['price'],2),"left",PAPER_DET_COL_3," ")."\r\n";
                        $pre_total += $vv['price'];
                    } else {
                        $pdf->Cell(50, 0, substrwords($vv['name'],100,""), '', 0, 'L'); 
                        $pdf->Cell(30, 0, number_format($vv['price'] * $vv['qty'],2), '', 0, 'R'); 
                        $pdf->ln();
                        // $print_str .= $this->append_chars(substrwords($vv['name'],100,"")." ".$vv['price'],"right",PAPER_DET_SUBCOL," ")
                            // .$this->append_chars(number_format($vv['price'] * $vv['qty'],2),"left",PAPER_DET_COL_3," ")."\r\n";
                        $pre_total += $vv['price'] * $vv['qty'];
                    }
                }
                


                //DISCOUNT PALATANDAAN
                // if(in_array($val[''], haystack))

            }
        }
        $vat = 0;
        if($tax > 0){
            foreach ($tax as $tx) {
               $vat += $tx['amount'];
            }
        }
        $no_tax_amt = 0;
        foreach ($no_tax as $k=>$v) {
            $no_tax_amt += $v['amount'];
        }

        $zero_rated_amt = 0;
        foreach ($zero_rated as $k=>$v) {
            $zero_rated_amt += $v['amount'];
        }
        if($zero_rated_amt > 0){
            $no_tax_amt = 0;
        }


        //start of not show for cancelled trans

        if($cancelled == 0){

            $total_discounts = 0;
            $total_discounts_sm = 0;
            foreach ($discounts as $dcs_ci => $dcs) {
                foreach ($dcs['persons'] as $code => $dcp) {
                    // $print_str .= $this->append_chars($dcs_ci,"right",28," ").$this->append_chars('P'.num($dcp['amount']),"left",10," ");
                    // $print_str .= "\r\n".$this->append_chars($dcp['name'],"right",28," ");
                    // $print_str .= "\r\n".$this->append_chars($dcp['code'],"right",28," ")."\r\n";
                    // $print_str .= "\r\n".$this->append_chars(asterisks($dcp['code']),"right",28," ")."\r\n";
                    $total_discounts += $dcp['amount'];
                    $dcAmt = $dcp['amount'];
                    // if(MALL_ENABLED && MALL == 'megamall'){
                    //     if($dcs_ci == PWDDISC){
                    //         $dcAmt = $dcAmt / 1.12;       
                    //     }
                    // }
                    $total_discounts_sm += $dcAmt;
                }
            }
            $total_discounts_non_vat = 0;
            foreach ($discounts as $dcs_ci => $dcs) {
               
                foreach ($dcs['persons'] as $code => $dcp) {
                    // $print_str .= $this->append_chars($dcs_ci,"right",28," ").$this->append_chars('P'.num($dcp['amount']),"left",10," ");
                    // $print_str .= "\r\n".$this->append_chars($dcp['name'],"right",28," ");
                    // $print_str .= "\r\n".$this->append_chars($dcp['code'],"right",28," ")."\r\n";
                    // $print_str .= "\r\n".$this->append_chars(asterisks($dcp['code']),"right",28," ")."\r\n";
                    if($dcs['no_tax'] == 1){
                        $total_discounts_non_vat += $dcp['amount'];
                    }
                }
            }
            $total_charges = 0;
            if(count($charges) > 0){
                foreach ($charges as $charge_id => $opt) {
                    $total_charges += $opt['total_amount'];
                }
            }
            $local_tax_amt = 0;
            if(count($local_tax) > 0){
                foreach ($local_tax as $lt_id => $lt) {
                    $local_tax_amt += $lt['amount'];
                }
            }
            // echo num($total_charges + $local_tax_amt);

            // echo '((('.$order['amount'].' - ('.$total_charges.' + '.$local_tax_amt.') - '.$vat.') - '.$no_tax_amt.'+'.$total_discounts_non_vat.') -'.$zero_rated_amt;


            $vat_sales = ( ( ( $order['amount'] - ($total_charges + $local_tax_amt) ) - $vat)  - $no_tax_amt + $total_discounts_non_vat ) - $zero_rated_amt;

            // echo '===== '.$vat_sales;
            // $vat_sales = ( ( ( $order['amount'] ) - $vat)  - $no_tax_amt + $total_discounts) - $zero_rated_amt;
            // echo "vat_sales= ((".$order['amount']." - ".$total_charges."))- ".$vat." )- ".$no_tax_amt." + ".$total_discounts." - ".$zero_rated_amt;
            if($vat_sales < 0){
                $vat_sales = 0;
            }
                $pdf->ln();
                $pdf->Cell(50, 0, "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _", '', 0, 'L');
                $pdf->ln();
                $pdf->Cell(40, 0, "Total Amount", '', 0, 'L');
                $pdf->Cell(30, 0, number_format($pre_total,2), '', 0, 'R');
                // $pdf->ln();
            // $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ");
                    // $print_str .= $this->append_chars("Total Amount","right",PAPER_DET_COL_2," ").
                // $this->append_chars(number_format($pre_total,2),"left",PAPER_DET_COL_3," ")."\r\n";
                if(count($discounts) >0){
                $less_vat = round(($pre_total - ($order['amount'] - $total_charges + $local_tax_amt ) ) - $total_discounts,2);
                // $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ");
                // $pdf->ln();
                
                    if($less_vat >0){
                        $pdf->ln();
                        $pdf->Cell(40, 0, "Less VAT", '', 0, 'L');
                        $pdf->Cell(30, 0, number_format($less_vat,2), '', 0, 'R');
                        // $print_str .= $this->append_chars(ucwords("Less VAT"),"right",PAPER_DET_COL_2," ").$this->append_chars(number_format( $less_vat,2),"left",PAPER_DET_COL_3," ")."\r\n";
                    }

                }else{
                    // $print_str .= $this->append_chars("Sub-Total","right",PAPER_DET_COL_2," ").
                    // $this->append_chars(number_format($pre_total,2),"left",PAPER_DET_COL_3," ")."\r\n";
                }
                if(count($discounts) >0){
                    $hasSMPWD = false;
                    if(count($dcs['persons']) > 0){

                        foreach ($discounts as $dcs_ci => $dcs) {
                            foreach ($dcs['persons'] as $code => $dcp) {
                                $discRateTxt = " (".$dcp['disc_rate']."%)";
                                if($dcs['fix'] == 1){
                                    $discRateTxt = " (".$dcp['disc_rate'].")";
                                }
                                $dcAmt = $dcp['amount'];
                                $pdf->ln();
                                $pdf->Cell(40, 0, "LESS ".$dcs_ci." DISCOUNT", '', 0, 'L');
                                $pdf->Cell(30, 0, number_format($dcAmt,2), '', 0, 'R');
                                  // $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ");
                                // $print_str .= $this->append_chars("LESS ".$dcs_ci." DISCOUNT","right",PAPER_DET_COL_2," ").$this->append_chars(' '.num($dcAmt),"left",PAPER_DET_COL_3," ")."\r\n";
                                
                            }
                        }
                       
                    }
                } 
           
                if(count($charges) > 0){
                    foreach ($charges as $charge_id => $opt) {
                        $charge_amount = $opt['total_amount'];
                        // if($opt['absolute'] == 0){
                        //     $charge_amount = ($opt['amount'] / 100) * ($order['amount'] - $vat);
                        // }
                        $pdf->ln();
                        $pdf->Cell(40, 0, $opt['name'] ."(".$opt['rate']."%)", '', 0, 'L');
                        $pdf->Cell(30, 0, number_format($charge_amount,2), '', 0, 'R');
                            // $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ");     
                        // $print_str .= $this->append_chars($opt['name'] ."(".$opt['rate']."%)","right",PAPER_DET_COL_2," ").$this->append_chars(number_format($charge_amount,2),"left",PAPER_DET_COL_3," ")."\r\n";
                // $print_str .= $this->append_chars("Service Charge (8.5%)","right",PAPER_DET_COL_2," ").
                    }
                    // $print_str .= PAPER_LINE."\r\n";
                }
                // if(PRINT_VERSION == 'V2'){    
                //     $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ");     
                //     $print_str .="<bold>";
                //     $print_str .= $this->append_chars($tot_qty." Item(s) Total Due","right",PAPER_DET_COL_2," ")."</bold><bold>".
                //     $this->append_chars(number_format($order['amount'],2),"left",PAPER_DET_COL_3," ")."</bold>"."\r\n";
                // }else{
                    $pdf->ln();
                    $pdf->Cell(40, 0, $tot_qty." Item(s) Total Due", '', 0, 'L');
                    $pdf->Cell(30, 0, number_format($order['amount'],2), '', 0, 'R');
                    // $print_str .= $this->append_chars($tot_qty." Item(s) Total Due","right",PAPER_DET_COL_2," ").
                    // $this->append_chars(number_format($order['amount'],2),"left",PAPER_DET_COL_3," ")."\r\n";
                // }
                $py_ref = "";
                if (!empty($payments)) {

                   
                    $pay_total = 0;
                    $gft_ctr = 0;
                    $nor_ctr = 0;
                    $py_ref = "";
                    $total_payment = 0;
                  
                    foreach ($payments as $payment_id => $opt) {
                        $pdf->ln();
                        $pdf->Cell(40, 0, ucwords($opt['payment_type']), '', 0, 'L');
                        $pdf->Cell(30, 0, number_format($opt['amount'],2), '', 0, 'R');
                        // $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ");     
                        // $print_str .= $this->append_chars(ucwords($opt['payment_type']),"right",PAPER_DET_COL_2," ").
                        // $this->append_chars(number_format($opt['amount'],2),"left",PAPER_DET_COL_3," ")."\r\n";
                        // $print_str .= $this->append_chars(ucwords($opt['payment_type']),"right",PAPER_TOTAL_COL_1," ").$this->append_chars("P ".number_format($opt['amount'],2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                        
                        if($opt['payment_type'] == 'check'){
                            $pdf->ln();
                            $pdf->Cell(40, 0, "     Check # ".$opt['reference'], '', 0, 'L');
                            // $print_str .= $this->append_chars("     Check # ".$opt['reference'],"right",PAPER_WIDTH," ")."\r\n";
                        }else{
                            if (!empty($opt['reference']) && $opt['payment_type'] != 'deposit') {
                                $py_ref = $opt['reference'];
                                // $print_str .= $this->append_chars("     Reference ".$opt['reference'],"right",PAPER_WIDTH," ")."\r\n";
                            }
                        }

                        if($opt['payment_type'] == 'foodpanda'){
                            if (!empty($opt['approval_code']))
                                    $pdf->ln();
                                    $pdf->Cell(40, 0, "  Order Code: ".$opt['approval_code'], '', 0, 'L');
                                    // $print_str .= $this->append_chars("  Order Code: ".$opt['approval_code'],"right",PAPER_WIDTH," ")."\r\n";
                        }else if($opt['payment_type'] == 'check'){
                                $pdf->ln();
                                $pdf->Cell(40, 0, "     Bank: ".$opt['card_number'], '', 0, 'L');
                            // $print_str .= $this->append_chars("     Bank: ".$opt['card_number'],"right",PAPER_WIDTH," ")."\r\n";
                        }else if($opt['payment_type'] == 'picc'){
                            $pdf->ln();
                            $pdf->Cell(40, 0, "     Name: ".$opt['card_number'], '', 0, 'L');
                            // $print_str .= $this->append_chars("     Name: ".$opt['card_number'],"right",PAPER_WIDTH," ")."\r\n";
                        }else{
                            if (!empty($opt['card_number'])) {
                                if (!empty($opt['card_type'])) {
                                    $pdf->ln();
                                    $pdf->Cell(40, 0, "  Card Type: ".$opt['card_type'], '', 0, 'L');
                                    // $print_str .= $this->append_chars("  Card Type: ".$opt['card_type'],"right",PAPER_WIDTH," ")."\r\n";
                                }
                                $print_str .= $this->append_chars("  Card #: ".$opt['card_number'],"right",PAPER_WIDTH," ")."\r\n";
                                if (!empty($opt['approval_code']))
                                    $pdf->ln();
                                    $pdf->Cell(40, 0, "  Approval #: ".$opt['approval_code'], '', 0, 'L');
                                    // $print_str .= $this->append_chars("  Approval #: ".$opt['approval_code'],"right",PAPER_WIDTH," ")."\r\n";
                            }
                        }
                        // $pay_total += $opt['amount'];
                        // if($opt['payment_type'] == 'gc'){
                        //     $gft_ctr++;
                        // }
                        // else
                        //     $nor_ctr++;
                        if($opt['payment_type'] == 'gc'){
                            if($opt['amount'] > $opt['to_pay']){
                                $total_payment  += $opt['to_pay'];
                            }else{
                                $total_payment  += $opt['amount'];
                            }
                        }
                        else{
                            $total_payment  += $opt['amount'];
                        }


                      

                        
                    }

                    
                    //  if(PRINT_VERSION=="V2" && !$return_print_str){

                    //     $print_str .= "STARTCUT==============================ENDCUT";
                    // }else{
                    //     $print_str .="<b>";
                    // }

                     // $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ");     
                     // $pdf->ln();
                    if($gft_ctr == 1 && $nor_ctr == 0){
                        $pdf->ln();
                        $pdf->Cell(40, 0, "Change", '', 0, 'L');
                        $pdf->Cell(30, 0, number_format(0,2), '', 0, 'R');
                        // $print_str .= $this->append_chars('Change',"right",PAPER_DET_COL_2," ").$this->append_chars(number_format(0,2),"left",PAPER_DET_COL_3," ")."\r\n";
                    }else{
                        $pdf->ln();
                        $pdf->Cell(40, 0, "Change", '', 0, 'L');
                        $pdf->Cell(30, 0, number_format(abs($total_payment - $order['amount']),2), '', 0, 'R');
                        // $print_str .= $this->append_chars('Change',"right",PAPER_DET_COL_2," ").$this->append_chars(number_format(abs($total_payment - $order['amount']),2),"left",PAPER_DET_COL_3," ")."\r\n";
                    }
                    $pdf->ln();
                    $pdf->Cell(50, 0, "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _", '', 0, 'L');
                    $pdf->ln();
                       //  if(PRINT_VERSION=="V2" && !$return_print_str){
                       //    $print_str .= "STARTCUT==============================ENDCUT";

                       // }else{
                       //   $print_str .="</b>";
                       // }
                    

                    $total_discount_snr_count = $total_discount_pwd_count = $total_discount_count = 0;
                    foreach ($discounts as $dcs_ci => $dcs) {
                            if($dcs_ci == 'SNDISC' || $dcs_ci == 'PWDISC'){
                                // echo "<pre>",print_r($dcs['persons']),"</pre>";die();
                                foreach ($dcs['persons'] as $code => $dcp) {   
                                    if($dcs_ci == 'SNDISC'){
                                        $total_discount_snr_count += 1;                            
                                    } 
                                    if($dcs_ci == 'PWDISC'){

                                        $total_discount_pwd_count += 1;                            
                                    } 
                                                                   }
                            }
                    }

                     $pdf->ln();
                     $pdf->Cell(40, 0, ucwords("Total Guest Count"), '', 0, 'L');
                     $pdf->Cell(30, 0, (($order['guest'] >0) ? $order['guest']: 1), '', 0, 'R');
                    // $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ").$this->append_chars(ucwords("Total Guest Count"),"right",PAPER_DET_COL_2," ").$this->append_chars((($order['guest'] >0) ? $order['guest']: 1),"left",PAPER_DET_COL_3," ")."\r\n";
                    
                    if($total_discount_snr_count > 0){
                         $pdf->ln();
                         $pdf->Cell(40, 0, ucwords("Total SC Guest Count"), '', 0, 'L');
                         $pdf->Cell(30, 0, $total_discount_snr_count, '', 0, 'R');
                        // $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ").$this->append_chars(ucwords("Total SC Guest Count"),"right",PAPER_DET_COL_2," ").$this->append_chars($total_discount_snr_count,"left",PAPER_DET_COL_3," ")."\r\n\r\n";
                    }

                    if($total_discount_pwd_count > 0){
                         $pdf->ln();
                         $pdf->Cell(40, 0, ucwords("Total PWD Guest Count:"), '', 0, 'L');
                         $pdf->Cell(30, 0, $total_discount_pwd_count, '', 0, 'R');
                        $print_str .= $this->append_chars(" " ,"right",PAPER_DET_COL_1," ").$this->append_chars(ucwords("Total PWD Guest Count:"),"right",PAPER_DET_COL_2," ").$this->append_chars($total_discount_pwd_count,"left",PAPER_DET_COL_3," ")."\r\n\r\n";
                    }

                    
                    $pdf->ln();
                    $pdf->Cell(50, 0, "_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _", '', 0, 'L');
                    $pdf->ln();
                    // $print_str .= PAPER_LINE_SINGLE."\r\n\r\n";

                    if ($include_footer) {
                        $rec_footer = "";
                        if($branch['rec_footer'] != ""){
                            $branch['rec_footer'] = "      THANK YOU COME AGAIN.         THIS SERVES AS YOUR ACKNOWLEDGEMENT RECEIPT";
                            $wrap = str_replace ("<br>","\r\n", $branch['rec_footer'] );
                            $exp = explode("\r\n", $wrap);
                            foreach ($exp as $v) {
                                $wrap2 = wordwrap($v,35,"|#|");
                                $exp2 = explode("|#|", $wrap2);  
                                foreach ($exp2 as $v2) {
                                    $pdf->ln();
                                     $pdf->Cell(50, 0, $v2, '', 0, 'C');
                                    // $rec_footer .= $this->align_center($v2,PAPER_WIDTH," ")."\r\n";
                                }
                            }                        
                        }
                        if($order['inactive'] == 0){
                            // $print_str .= $rec_footer;
                        }    
                            $pdf->ln();
                            // $print_str .= "\r\n";
                            // $print_str .= $this->align_center("POS Vendor Details",PAPER_WIDTH," ")."\r\n";
                            // $print_str .= PAPER_LINE."\r\n";
                            $pdf->Cell(50, 0, "PointOne Integrated Tech., Inc.", '', 0, 'C');
                            $pdf->ln();
                            $pdf->Cell(50, 0, "1409 Prestige Tower", '', 0, 'C');
                            $pdf->ln();
                            $pdf->Cell(50, 0, "Ortigas Center, Pasig City", '', 0, 'C');
                            $pdf->ln();
                            
                            // $print_str .= $this->append_chars('Permit No.:',"right",PAPER_RD_COL_3," ")
                                       // .  $this->append_chars($branch['permit_no'],"left",PAPER_DET_SUBCOL," ")."\r\n";
                            // $print_str .= $this->append_chars('POS Version:',"right",PAPER_RD_COL_3," ")
                            //            .  $this->append_chars('iPos ver 1.0',"left",PAPER_DET_SUBCOL," ")."\r\n";
                            // $print_str .= $this->append_chars('Date Issued:',"right",PAPER_RD_COL_3," ")
                                       // .  $this->append_chars('December 22, 2014',"left",PAPER_DET_SUBCOL," ")."\r\n";
                                       // .  $this->append_chars(date2Word($order['datetime']),"right",PAPER_TOTAL_COL_2," ")."\r\n";
                            // $print_str .= $this->append_chars('Valid Until:',"right",PAPER_RD_COL_3," ")
                                       // .  $this->append_chars(date2Word('December 22, 2025'),"left",PAPER_DET_SUBCOL," ")."\r\n";
                                       // .  $this->append_chars(date2Word( date('Y-m-d',strtotime($order['datetime'].' +5 year')) ),"right",PAPER_TOTAL_COL_2," ")."\r\n";

                        if($branch['contact_no'] != ""){
                            $pdf->Cell(50, 0, "For feedback, please call us at", '', 0, 'C');
                            $pdf->ln();
                            $pdf->Cell(50, 0, $branch['contact_no'], '', 0, 'C');
                            $pdf->ln();
                            // $print_str .= $this->align_center("For feedback, please call us at",PAPER_WIDTH," ")."\r\n"
                                         // .$this->align_center($branch['contact_no'],PAPER_WIDTH," ")."\r\n";
                        }
                        if($branch['email'] != ""){
                            $pdf->Cell(50, 0, "Or Email us at", '', 0, 'C');
                            $pdf->ln();
                            $pdf->Cell(50, 0, $branch['email'], '', 0, 'C');
                            $pdf->ln();
                            // $print_str .= $this->align_center("Or Email us at",PAPER_WIDTH," ")."\r\n" 
                                       // .$this->align_center($branch['email'],PAPER_WIDTH," ")."\r\n";
                        }
                        if($branch['website'] != ""){
                            $pdf->Cell(50, 0, "Please visit us at", '', 0, 'C');
                            $pdf->ln();
                            $pdf->Cell(50, 0, $branch['website'], '', 0, 'C');
                            $pdf->ln();
                            // $print_str .= $this->align_center("Please visit us at",PAPER_WIDTH," ")."\r\n"
                                         // .$this->align_center($branch['website'],PAPER_WIDTH," ")."\r\n";
                        }
                        $pdf->ln();
                        foreach ($discounts as $dcs_ci => $dcs) {
                            if($dcs_ci == 'SNDISC' || $dcs_ci == 'PWDISC'){
                                foreach ($dcs['persons'] as $code => $dcp) {
                                    // ."\r\n"
                                    // ."\r\n"
                                    if($dcs_ci == 'SNDISC'){
                                        $pdf->Cell(40, 0, "Senior Citizen TIN: ".$dcp['code'], '', 0, 'L');
                                        $pdf->ln();
                                        $pdf->Cell(40, 0, "OSCA ID No.: ".$dcp['name'], '', 0, 'L');
                                        $pdf->ln();
                                        // $print_str .= "\r\n".$this->append_chars("Senior Citizen TIN: ".$dcp['code'],"right",PAPER_TOTAL_COL_1," ");
                                        // $print_str .= "\r\n".$this->append_chars("OSCA ID No.: ".$dcp['name'],"right",PAPER_TOTAL_COL_1," ");
                                    }
                                    if($dcs_ci == 'PWDISC'){
                                        $pdf->Cell(40, 0, "PWD TIN: ".$dcp['code'], '', 0, 'L');
                                        $pdf->ln();
                                        $pdf->Cell(40, 0, "OSCA ID No.: ".$dcp['name'], '', 0, 'L');
                                        $pdf->ln();
                                        // $print_str .= "\r\n".$this->append_chars("PWD TIN: ".$dcp['code'],"right",PAPER_TOTAL_COL_1," ");
                                        // $print_str .= "\r\n".$this->append_chars("OSCA ID No.: ".$dcp['name'],"right",PAPER_TOTAL_COL_1," ");
                                    }

                                   

                                }
                            }
                             if($dcs_ci == 'D1018'){
                                        $pdf->Cell(40, 0, "Name: ".$dcp['name'], '', 0, 'L');
                                        $pdf->ln();
                                        $pdf->Cell(40, 0, "Address:", '', 0, 'L');
                                        $pdf->ln();
                                        $pdf->Cell(40, 0, "TIN: ".$dcp['code'], '', 0, 'L');
                                        $pdf->ln();
                                        $pdf->Cell(40, 0, "VIP ID NO:", '', 0, 'L');
                                        $pdf->ln();
                                        //  $print_str .= "\r\n".$this->append_chars("Name: ".$dcp['name'],"right",PAPER_TOTAL_COL_1," ");
                                        // $print_str .= "\r\n".$this->append_chars("Address: ","right",PAPER_TOTAL_COL_1," ");
                                        // $print_str .= "\r\n".$this->append_chars("TIN: ".$dcp['code'],"right",PAPER_TOTAL_COL_1," ");
                                        // $print_str .= "\r\n".$this->append_chars("VIP ID NO: ","right",PAPER_TOTAL_COL_1," ");

                            }

                            if($dcs_ci == 'D1006'){
                                        $pdf->Cell(40, 0, "PWD TIN:", '', 0, 'L');
                                        $pdf->ln();
                                        $pdf->Cell(40, 0, "National Athlete ID No.: ", '', 0, 'L');
                                        $pdf->ln();
                                         // $print_str .= "\r\n".$this->append_chars("PWD TIN: ","right",PAPER_TOTAL_COL_1," ");
                                        // $print_str .= "\r\n".$this->append_chars("National Athlete ID No.: ","right",PAPER_TOTAL_COL_1," ");
                                     

                            }

                        }

                        if($zero_rated_amt > 0){
                                $pdf->Cell(40, 0, "Name:", '', 0, 'L');
                                $pdf->ln();
                                $pdf->Cell(40, 0, "Address: ", '', 0, 'L');
                                $pdf->ln();
                                $pdf->Cell(40, 0, "TIN:", '', 0, 'L');
                                $pdf->ln();
                                $pdf->Cell(40, 0, "Diplomat ID No.: ", '', 0, 'L');
                                $pdf->ln();
                                // $print_str .= "\r\n".$this->append_chars("Name: ","right",PAPER_TOTAL_COL_1," ");
                                // $print_str .= "\r\n".$this->append_chars("Address: ","right",PAPER_TOTAL_COL_1," ");
                                // $print_str .= "\r\n".$this->append_chars("TIN: ","right",PAPER_TOTAL_COL_1," ");
                                // $print_str .= "\r\n".$this->append_chars("Diplomat ID No: ","right",PAPER_TOTAL_COL_1," ");
                        }
                        
                            if(isset($py_ref) && !empty($py_ref)){
                                $pdf->Cell(40, 0, "Name:", '', 0, 'L');
                                $pdf->ln();
                                $pdf->Cell(40, 0, "Address: ", '', 0, 'L');
                                $pdf->ln();
                                $pdf->Cell(40, 0, "TIN:", '', 0, 'L');
                                $pdf->ln();
                                $pdf->Cell(40, 0, "GC Serial No.: ".$py_ref, '', 0, 'L');
                                $pdf->ln();
                                // $print_str .= "\r\n".$this->append_chars("Name: ","right",PAPER_TOTAL_COL_1," ");
                                // $print_str .= "\r\n".$this->append_chars("Address: ","right",PAPER_TOTAL_COL_1," ");
                                // $print_str .= "\r\n".$this->append_chars("TIN: ","right",PAPER_TOTAL_COL_1," ");
                                // $print_str .= "\r\n".$this->append_chars("GC Serial No: ".$py_ref,"right",PAPER_TOTAL_COL_1," ");

                            }
                        // $print_str .= PAPER_LINE."\r\n";
                        if($order['inactive'] == 0){
                            // $print_str .= "\r\n";
                            $pdf->ln();
                          
                        }

                    }

                  
                    if (!empty($order['ref']) && $order['inactive'] == 0) {
                        // $print_str .= $this->align_center(PAPER_LINE,PAPER_WIDTH," ");
                        $pdf->Cell(40, 0, "_________________________________", '', 0, 'L');
                        $pdf->ln();
                        $pdf->Cell(40, 0, "Customer Signature Over Printed Name", '', 0, 'L');
                        $pdf->ln();
                        // $print_str .= "\r\n\r\n".$this->append_chars("_________________________________","right",PAPER_TOTAL_COL_1," ");
                        // $print_str .= "\r\n".$this->append_chars("Customer Signature Over Printed Name","right",PAPER_TOTAL_COL_1," ");
                        // $print_str .= $this->align_center(PAPER_LINE,PAPER_WIDTH," ");
                        // $print_str .= "\r\n";
                    }
                    
                    // $print_str .="\r\n".$this->align_center("THIS INVOICE/RECEIPT WILL BE VALID FOR FIVE(5) YEARS FROM THE DATE OF THE PERMIT TO USE",PAPER_WIDTH," ")."\r\n";
                    //     $print_str .= "\r\n".$this->append_chars("THIS INVOICE/RECEIPT WILL BE VALID FOR","right",PAPER_TOTAL_COL_1," ");
                    //     $print_str .= "\r\n".$this->append_chars("FIVE(5) YEARS FROM THE DATE OF THE PERMIT","right",PAPER_TOTAL_COL_1," ");
                    //      $print_str .= "\r\n".$this->append_chars("TO USE","right",PAPER_TOTAL_COL_1," ");
                
                } 

                 
            
                // if (!empty($payments)) {

                   
                // } else {
                //     $is_billing = true;
                //     $print_str .= "\r\n".$this->append_chars("","right",PAPER_WIDTH,"=");
                //     if(PRINT_VERSION == 'V1'){
                //         $print_str .= "\r\n\r\n".$this->append_chars("Billing Amount","right",PAPER_TOTAL_COL_1," ").$this->append_chars("P ".number_format($order['amount'],2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                //     }
                //     if(is_array($splits)){
                //         $print_str .= $this->append_chars("Split Amount by ".$splits['by'],"right",PAPER_TOTAL_COL_1," ").$this->append_chars("P ".number_format($splits['total'],2),"left",PAPER_TOTAL_COL_2," ")."\r\n";
                //     }


                //     //for senior deaitls with signature forbiliing
                //     foreach ($discounts as $dcs_ci => $dcs) {
                //         if($dcs_ci == 'SNDISC' || $dcs_ci == 'PWDISC'){
                //             // $print_str .= PAPER_LINE."\r\n";
                //             $print_str .= "\r\n";
                //             $print_str .= $this->align_center("OSCA/PWD Details",PAPER_WIDTH," ")."\r\n";
                //             // $print_str .= PAPER_LINE."\r\n";
                //             // $print_str .= $this->align_center(PAPER_LINE,42," ");
                //             break;
                //         }
                //     }
                //     foreach ($discounts as $dcs_ci => $dcs) {
                //         if($dcs_ci == 'SNDISC' || $dcs_ci == 'PWDISC'){
                //             foreach ($dcs['persons'] as $code => $dcp) {
                //                 // ."\r\n"
                //                 // ."\r\n"
                //                 $print_str .= "\r\n".$this->append_chars("ID NO      : ".$dcp['code'],"right",PAPER_TOTAL_COL_1," ");
                //                 $print_str .= "\r\n".$this->append_chars("NAME       : ".$dcp['name'],"right",PAPER_TOTAL_COL_1," ");
                //                 $print_str .= "\r\n".$this->append_chars("ADDRESS    : ","right",PAPER_TOTAL_COL_1," ");
                //                 $print_str .= "\r\n".$this->append_chars("SIGNATURE  : ","right",PAPER_TOTAL_COL_1," ");
                //                 $print_str .= "\r\n".$this->append_chars("             ____________________","right",PAPER_TOTAL_COL_1," ")."\r\n";
                //                 // $print_str .= "\r\n".$this->append_chars(asterisks($dcp['code']),"right",28," ")."\r\n";
                //             }
                //         }
                //     }


                //     if ($include_footer) {
                //         // $print_str .= "\r\n\r\n";
                //         if($branch['contact_no'] != ""){
                //             $print_str .= $this->align_center("For feedback, please call us at",PAPER_WIDTH," ")."\r\n"
                //                          .$this->align_center($branch['contact_no'],PAPER_WIDTH," ")."\r\n";
                //         }
                //         if($branch['email'] != ""){
                //             $print_str .= $this->align_center("Or Email us at",PAPER_WIDTH," ")."\r\n" 
                //                        .$this->align_center($branch['email'],PAPER_WIDTH," ")."\r\n";
                //         }
                //         if($branch['website'] != "")
                //             $print_str .= $this->align_center("Please visit us at \r\n".$branch['website'],PAPER_WIDTH," ")."\r\n";
                //     }

                // }

             


            }
            //end of showing for cancelled trans
        
        $pdf->Output('gc_report.pdf', 'I');
    }

    public function get_gc_order($asJson=true,$gc_id=null){
            /*
             * -------------------------------------------
             *   Load receipt data
             * -------------------------------------------
            */
            $this->load->model('dine/cashier_gift_card_model');
            $orders = $this->cashier_gift_card_model->get_trans_gc($gc_id);
            // echo $sales_id; 
            // echo "<pre>",print_r($orders),"</pre>";die();
            $order = array();
            $details = array();
            $details2 = array();
            $details_to = array();
            foreach ($orders as $res) {
                $order = array(
                    "gc_id"=>$res->gc_id,
                    'ref'=>$res->trans_ref,
                    "type"=>$res->type,
                    "table_id"=>$res->table_id,
                    "table_name"=>$res->table_name,
                    "guest"=>$res->guest,
                    "serve_no"=>$res->serve_no,
                    "user_id"=>$res->user_id,
                    "customer_id"=>$res->customer_id,
                    "customer_name"=>$res->customer_name,
                    "name"=>$res->username,
                    "terminal_id"=>$res->terminal_id,
                    "terminal_name"=>$res->terminal_name,
                    "terminal_code"=>$res->terminal_code,
                    "shift_id"=>$res->shift_id,
                    "datetime"=>$res->datetime,
                    "update_date"=>$res->update_date,
                    "amount"=>$res->total_amount,
                    "balance"=>round($res->total_amount,2) - round($res->total_paid,2),
                    "paid"=>$res->paid,
                    "printed"=>$res->printed,
                    "inactive"=>$res->inactive,
                    "waiter_id"=>$res->waiter_id,
                    "void_ref"=>$res->void_ref,
                    "reason"=>$res->reason,
                    "ref_no"=>$res->ref_no,
                    "depo_amount"=>$res->depo_amount,
                    "cname"=>$res->fname,
                    "waiter_name"=>ucwords(strtolower($res->waiterfname." ".$res->waitermname." ".$res->waiterlname." ".$res->waitersuffix)),
                    "waiter_username"=>ucwords(strtolower($res->waiterusername)),
                    // "memo"=>ucwords(strtolower($res->memo))
                    // "pay_type"=>$res->pay_type,
                    // "pay_amount"=>$res->pay_amount,
                    // "pay_ref"=>$res->pay_ref,
                    // "pay_card"=>$res->pay_card,
                );
            }
            // $order_menus = $this->cashier_gift_card_model->get_trans_sales_menus(null,array("trans_sales_menus.sales_id"=>$sales_id));
            $order_menus = $this->cashier_gift_card_model->get_trans_gc_gift_cards(null,array("trans_gc_gift_cards.gc_id"=>$gc_id));
            // echo "<pre>",print_r($order_menus),"</pre>";die();
            // $order_items = $this->cashier_gift_card_model->get_trans_sales_items(null,array("trans_sales_items.sales_id"=>$sales_id));
            // $order_mods = $this->cashier_gift_card_model->get_trans_sales_menu_modifiers(null,array("trans_sales_menu_modifiers.sales_id"=>$sales_id));
            // $order_submods = $this->cashier_gift_card_model->get_trans_sales_menu_submodifiers(null,array("trans_sales_menu_submodifiers.sales_id"=>$sales_id));
            $sales_discs = $this->cashier_gift_card_model->get_trans_gc_discounts(null,array("trans_gc_discounts.gc_id"=>$gc_id));
            $sales_tax = $this->cashier_gift_card_model->get_trans_gc_tax(null,array("trans_gc_tax.gc_id"=>$gc_id));
            $sales_payments = $this->cashier_gift_card_model->get_trans_gc_payments(null,array("trans_gc_payments.gc_id"=>$gc_id));
            $sales_no_tax = $this->cashier_gift_card_model->get_trans_gc_no_tax(null,array("trans_gc_no_tax.gc_id"=>$gc_id));
            $sales_zero_rated = $this->cashier_gift_card_model->get_trans_gc_zero_rated(null,array("trans_gc_zero_rated.gc_id"=>$gc_id));
            $sales_charges = $this->cashier_gift_card_model->get_trans_gc_charges(null,array("trans_gc_charges.gc_id"=>$gc_id));
            $sales_local_tax = $this->cashier_gift_card_model->get_trans_gc_local_tax(null,array("trans_gc_local_tax.gc_id"=>$gc_id));
            $payment_fields = $this->cashier_gift_card_model->get_trans_gc_payment_fields(null,array("trans_gc_payment_fields.gc_id"=>$gc_id));
            $pays = array();
            foreach ($sales_payments as $py) {
                $pays[$py->gc_payment_id] = array(
                        "gc_id"      => $py->gc_id,
                        "payment_type"  => $py->payment_type,
                        "amount"        => $py->amount,
                        "to_pay"        => $py->to_pay,
                        "reference"     => $py->reference,
                        "card_type"     => $py->card_type,
                        "card_number"   => $py->card_number,
                        "approval_code"   => $py->approval_code,
                        "user_id"       => $py->user_id,
                        "datetime"      => $py->datetime,
                    );
            }
            foreach ($order_menus as $men) {
                $details[$men->line_id] = array(
                    "id"=>$men->gc_gift_card_id,
                    "line_id"=>$men->line_id,
                    "description_id"=>$men->description_id,
                    "menu_id"=>$men->description_id,
                    "name"=>$men->description_id,
                    "code"=>$men->description_id,
                    "price"=>$men->price,
                    "qty"=>$men->qty,
                    "no_tax"=>$men->no_tax,
                    "discount"=>$men->discount,
                    "remarks"=>$men->remarks,
                    "free_user_id"=>$men->free_user_id,
                    "nocharge"=>$men->nocharge,
                    "is_takeout"=>$men->is_takeout,
                    "kitchen_slip_printed"=>$men->kitchen_slip_printed,
                    "gc_from"=>$men->gc_from,
                    "gc_to"=>$men->gc_to,
                    "brand_id"=>$men->brand_id,
                );
                $mods = array();
               
            }
            // echo "<pre>",print_r($details),"</pre>";die();
           
            // echo "<pre>",print_r($details2),"</pre>";die();


           
            ### CHANGED #############
            $per_item_disc = false;
            foreach ($sales_discs as $dc) {
                if($dc->items != ""){
                    $per_item_disc = true;
                }
            }
            // var_dump($per_item_disc); die();
            if($per_item_disc){
                $discounts = array();
                $persons = array();
                foreach ($sales_discs as $dc) {
                    $discounts[$dc->items] = array(
                            "no_tax"  => $dc->no_tax,
                            "guest" => $dc->guest,
                            "disc_rate" => $dc->disc_rate,
                            "disc_id" => $dc->disc_id,
                            "disc_code" => $dc->disc_code,
                            "disc_type" => $dc->type,
                            "amount" => $dc->amount,
                            "fix" => $dc->fix,
                            "name" => $dc->name,
                            "code" => $dc->code,
                            "bday" => $dc->bday,
                            "items" => $dc->items,
                            // "persons" => array()
                    );
                }
            }else{
                $discounts = array();
                $persons = array();
                foreach ($sales_discs as $dc) {
                    $discounts[$dc->disc_code] = array(
                            "no_tax"  => $dc->no_tax,
                            "guest" => $dc->guest,
                            "disc_rate" => $dc->disc_rate,
                            "disc_id" => $dc->disc_id,
                            "disc_code" => $dc->disc_code,
                            "disc_type" => $dc->type,
                            "fix" => $dc->fix,
                            "items" => $dc->items,
                            "persons" => array()
                    );
                }
                foreach ($sales_discs as $dc) {
                    $pcode = $dc->code;
                    $bday = "";
                    if($dc->bday != "")
                        $bday = sql2Date($dc->bday);
                    $person = array(
                        "name"  => $dc->name,
                        "code"  => $dc->code,
                        "bday"  => $bday,
                        "amount" => $dc->amount,
                        "disc_rate" => $dc->disc_rate,
                    );
                    if(isset($discounts[$dc->disc_code])){
                        $dscp =  $discounts[$dc->disc_code]['persons'];
                        $dscp[$pcode] = $person;
                        $discounts[$dc->disc_code]['persons'] = $dscp;
                    }
                }
            }
            ### CHANGED #############
            $tax = array();
            foreach ($sales_tax as $tx) {
                $tax[$tx->gc_tax_id] = array(
                        "gc_id"  => $tx->gc_id,
                        "name"  => $tx->name,
                        "rate" => $tx->rate,
                        "amount" => $tx->amount
                    );
            }
            $no_tax = array();
            foreach ($sales_no_tax as $nt) {
                $no_tax[$nt->gc_no_tax_id] = array(
                    "gc_id" => $nt->gc_id,
                    "amount" => $nt->amount,
                );
            }
            $zero_rated = array();
            foreach ($sales_zero_rated as $zt) {
                $zero_rated[$zt->gc_zero_rated_id] = array(
                    "gc_id" => $zt->gc_id,
                    "amount" => $zt->amount,
                    "name" => $zt->name,
                    "card_no" => $zt->card_no
                );
            }
            $local_tax = array();
            foreach ($sales_local_tax as $lt) {
                $local_tax[$lt->gc_local_tax_id] = array(
                    "gc_id" => $lt->gc_id,
                    "amount" => $lt->amount,
                );
            }
            $charges = array();
            foreach ($sales_charges as $ch) {
                $charges[$ch->gc_charge_id] = array(
                        "name"  => $ch->charge_name,
                        "code"  => $ch->charge_code,
                        "amount"  => $ch->rate,
                        "absolute" => $ch->absolute,
                        "total_amount" => $ch->amount,
                        "rate"=>$ch->rate
                    );
            }
            $pfields = array();
            foreach ($payment_fields as $pf) {
                $pfields[$pf->field_id] = array(
                        "field_name"  => $pf->field_name,
                        "value"  => $pf->value,
                    );
            }
            if($asJson)
                echo json_encode(array('order'=>$order,"details"=>$details,"discounts"=>$discounts,"taxes"=>$tax,"no_tax"=>$no_tax,"zero_rated"=>$zero_rated,"payments"=>$pays,"charges"=>$charges,"local_tax"=>$local_tax,"details2"=>$details2,"details_to"=>$details_to,"pfields"=>$pfields));
            else
                return array('order'=>$order,"details"=>$details,"discounts"=>$discounts,"taxes"=>$tax,"no_tax"=>$no_tax,"zero_rated"=>$zero_rated,"payments"=>$pays,"charges"=>$charges,"local_tax"=>$local_tax,"details2"=>$details2,"details_to"=>$details_to,"pfields"=>$pfields);
        }

}
