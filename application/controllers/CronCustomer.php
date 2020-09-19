<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CronCustomer extends CustomBaseStep {
	private $access_control;
	public function __construct()
	{
		parent::__construct();
		
	}
	public function index()
	{
        // require models
        require_once APPPATH."/libraries/SimpleXlsx.php";
        $this->load->model('ghCustomer');
        $this->load->model('ghApartment');
        $this->load->model('ghRoom');
        $this->load->model('ghContract');
        $file_name = 'DSKH-SINVA-2020.xlsx';
        if ( $xlsx = SimpleXLSX::parse('./documents/'.$file_name) ) {
            // echo "<pre>"; print_r($xlsx->rows()); die;
            $customer = [];
            foreach($xlsx->rows() as $index => $row) {
                if($index == 0) continue;
                // Customer 
                $customer['name'] = $row[1];
                $customer['birthdate'] = $row[2] ? strtotime($row[2]):0;
                $customer['gender'] = empty($row[3]) ? (($row[3] == 'Nam')? 'male':'female'):null;
                $customer['status'] = $row[4] ? 'sinva-rented': 'sinva-info-form';
                $customer['phone'] = trim($row[6]);
                $customer['address_street'] = trim($row[5]);
                $customer['email'] = trim($row[8]);
                $customer['note'] = trim($row[18]);
                $customer_id = $this->ghCustomer->insert($customer);

                // Contract
                if($row[4]) {
                    $room = $this->ghRoom->get(['id' => $row[4]]);
                    if($room) {
                        $room = $room[0];
                        $apartment = $this->ghApartment->get(['id' => $room['apartment_id']]);
                        if($apartment) {
                            $apartment = $apartment[0];
                            $contract['apartment_id'] = $apartment['id'];
                            $contract['consultant_id'] = 0;
                            $contract['room_id'] = $row[4];
                            $contract['status'] = 'Active';
                            $contract['customer_id'] = $customer_id;
                            $contract['service_set'] = json_encode($apartment);
                            $contract['room_price'] = $row[10] ? $row[10]: $room['price'];
                            $contract['time_check_in'] = $row[12] ? strtotime($row[12]):0;
                            $contract['number_of_month'] = $row[13];
                            $contract['note'] = trim($row[18]);
                            $this->ghContract->insert($contract);
                        }
                    }
                    
                    // echo "<pre>"; print_r($data);
                }
                
            }
           
        } else {
            echo SimpleXLSX::parseError();
        }
    }


}

/* End of file Apartment.php */
/* Location: ./application/controllers/role-manager/Apartment.php */