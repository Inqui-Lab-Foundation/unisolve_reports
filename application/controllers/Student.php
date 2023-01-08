<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {
    
    function __construct() {
		parent::__construct();
		$this->CI = & get_instance();
		$this->load->model('data_model','',TRUE);
		$this->load->library(array('table','form_validation','session','email'));
		$this->load->helper(array('email'));
	    date_default_timezone_set('Asia/Kolkata');
	}
	
	 
	
	public function login(){

		$data['college'] = $this->uri->segment(1);
	    
	    if($this->session->userdata('student_logs')){
	        redirect('student/dashboard');
	    } else {
			
    	    if ($this->uri->segment(2) == 'invalid'){
    			$data['msg'] = 'Invalid Reg.No / Password..!!';
    			$data['cls'] = 'alert alert-danger text-center';
    	    }elseif ($this->uri->segment(2) == 'timeout'){
    			$data['msg'] = 'Session timeout..!!';
    			$data['cls'] = 'alert alert-danger text-center';
    	    }elseif ($this->uri->segment(2) == 'logout-success'){
    			$data['msg'] = 'Successfully logout..!!';
    			$data['cls'] = 'alert alert-danger text-center';			
    	    }else {
    			$data['msg'] = '';
    			$data['cls'] = '';
    		}
    			
    	    $data['action'] = 'student/login';
    	    $this->form_validation->set_rules('reg_no','Registered Number','required');
    	    $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');
    		if ($this->form_validation->run() === false){
    		    $this->login_template->show('student/login', $data);
    		} else {
    		  //  $email = $this->input->post('email');
    			redirect('student/dashboard');
    		}
	    }
    }
    
    function check_database($password)
	{
		$reg_no = $this->input->post('reg_no');
		
   		//query the database
   		$result = $this->data_model->studentLogin($reg_no, md5($password));
		 
   		if($result)
   		{
     	  $sess_array = array();
     	  $sess_array = array(
				'id' => $result->id,
         		'reg_no' => $result->reg_no,
				'student_name' => $result->student_name
       		);
       	   $this->session->set_userdata('student_logs', $sess_array);

		   $this->session->set_userdata('agreed_terms', $result->agreed_terms);
		   $this->session->set_userdata('agreed_terms_datetime', date('Y-m-d H:i:s'));
           return TRUE;
		}
   		else
   		{
     		$this->form_validation->set_message('check_database', 'Entered invalid Reg.No or Password..!!');
     		return false;
   		}
 	}
 	
    function logout(){
        if ($this->session->userdata('student_logs')){
			$sess = $this->session->userdata('student_logs');
			$data['id'] = $sess['id'];
			$data['student_name'] = $sess['student_name'];
				
			$this->session->unset_userdata('student_logs');
	   		session_destroy();
	   		redirect('student/logout-success', 'refresh');

  		}else {
			redirect('student/timeout');
		}
	}
    
    function dashboard(){
        if ($this->session->userdata('student_logs')){
			$sess = $this->session->userdata('student_logs');
			
			$data['id'] = $sess['id'];
			$data['reg_no'] = $sess['reg_no'];
			$data['student_name'] = $sess['student_name'];
			$data['agreed_terms'] = $this->session->userdata('agreed_terms');
				
			$data['page_title'] = 'Dashboard';
			$data['menu'] = 'dashboard';
			
			$studentDataCheck = $this->data_model->studentDataCheck($data['reg_no'])->row();
			
			if($studentDataCheck->mobile && $studentDataCheck->personal_email){
				$data['action'] = 'student/payment/0';
				$years = $this->data_model->studentAcademicYears($data['reg_no'])->result();
				$romanYears = array(" " => "-") + $this->globals->romanYears();
				$year_values = array();
				foreach($years as $years){
					$year_values[$years->current_year] = $romanYears[$years->current_year].'-Year';
				}
				$data['years'] = array(' ' => 'Select Year') + $year_values;
				$this->student_template->show('student/dashboard',$data);
			}else{
				$data['details'] = $studentDataCheck;
				$this->student_template->show('student/edit_basic_profile',$data);
			}

		}else {
			redirect('student/timeout');
		}
	} 
	
	function getInstallemnts(){
        if ($this->session->userdata('student_logs')){
			$sess = $this->session->userdata('student_logs');
			$data['id'] = $sess['id'];
			$data['reg_no'] = $sess['reg_no'];
			$data['student_name'] = $sess['student_name'];
				
			$year = $this->input->post('year'); 

			$result[] = '<option value=" ">Select Payment Type</option>';

			$details = $this->data_model->getInstallemnts($data['reg_no'], $year)->row();
			$payment_types = array(); $test = 0;
			if($details->installment_1_fee && !$details->installment_1_status){
				$payment_types['1-1'] = "Tuition Fee Inst.-1"; 
				$result[] = '<option value="1-1-'.$details->installment_1_fee.'">Tuition Fee Inst.-1 [Rs.'.number_format($details->installment_1_fee).']</option>';
				$test = $test + $details->installment_1_fee;
			}
			if($details->installment_2_fee && !$details->installment_2_status){
				$payment_types['1-2'] = "Tuition Fee Inst.-2"; 
				$result[] = '<option value="1-2-'.$details->installment_2_fee.'">Tuition Fee Inst.-2 [Rs.'.number_format($details->installment_2_fee).']</option>';
				$test = $test + $details->installment_2_fee;
			}
			if($details->installment_3_fee && !$details->installment_3_status){
				$payment_types['1-3'] = "Tuition Fee Inst.-3"; 
				$result[] = '<option value="1-3-'.$details->installment_3_fee.'">Tuition Fee Inst.-3 [Rs.'.number_format($details->installment_3_fee).']</option>';
				$test = $test + $details->installment_3_fee;
			}
			
			$total = intval($details->installment_1_fee) + intval($details->installment_2_fee) + intval($details->installment_3_fee);

            if($test >= $total){
				$result[] = '<option value="1-0-'.$total.'">Total Tuition Fee [Rs.'.number_format($total).']</option>';
				$payment_types['1-0'] = $total. "Total Tuition Fee"; 
			}

			$hostel_fee = $details->hostel_deposit_fee + $details->hostel_fee;
			$hostel_check = 0;
			
			if($details->hostel_inst_1_fee && !$details->hostel_inst_1_fee_status){
				$payment_types['2-1'] = "Hostel Fee Inst.-1"; 
				$result[] = '<option value="2-1-'.$details->hostel_inst_1_fee.'">Hostel Fee Inst.-1 [Rs.'.number_format($details->hostel_inst_1_fee).']</option>';
				$hostel_check = $hostel_check + $details->hostel_inst_1_fee;
			}

			if($details->hostel_inst_2_fee && !$details->hostel_inst_2_fee_status){
				$payment_types['2-2'] = "Hostel Fee Inst.-2"; 
				$result[] = '<option value="2-2-'.$details->hostel_inst_2_fee.'">Hostel Fee Inst.-2 [Rs.'.number_format($details->hostel_inst_2_fee).']</option>';
				$hostel_check = $hostel_check + $details->hostel_inst_2_fee;
			}

			$hostel_total = intval($details->hostel_inst_1_fee) + intval($details->hostel_inst_2_fee);

			// echo $hostel_fee;die;
			if(($hostel_check >= $hostel_total) && $hostel_fee){
				$payment_types['2-0'] = $hostel_total. "Total Hostel Fee"; 
				$result[] = '<option value="2-0-'.$hostel_total.'">Total Hostel Fee [Rs.'.number_format($hostel_total).']</option>';
			}

			$transportation_fee = $details->transportation_fee;
			$transportation_check = 0;
			
			if($details->transportation_inst_1_fee && !$details->transportation_inst_1_fee_status){
				$payment_types['3-1'] = "Transportation Fee Inst.-1"; 
				$result[] = '<option value="3-1-'.$details->transportation_inst_1_fee.'">Transportation Fee Inst.-1 [Rs.'.number_format($details->transportation_inst_1_fee).']</option>';
				$transporation_check = $transporation_check + $details->transportation_inst_1_fee;
			}

			if($details->transportation_inst_2_fee && !$details->transportation_inst_2_fee_status){
				$payment_types['3-2'] = "Transportation Fee Inst.-2"; 
				$result[] = '<option value="3-2-'.$details->transportation_inst_2_fee.'">Transportation Fee Inst.-2 [Rs.'.number_format($details->transportation_inst_2_fee).']</option>';
				$transporation_check = $transporation_check + $details->transportation_inst_2_fee;
			}

			$transporation_total = intval($details->transportation_inst_1_fee) + intval($details->transportation_inst_2_fee);
			if(($transporation_check >= $transporation_total) && $transportation_fee){
				$result[] = '<option value="3-0-'.$transporation_total.'">Total Transportation Fee [Rs.'.number_format($transporation_total).']</option>';
				$payment_types['3-0'] = $transporation_total. "Total Transportation Fee"; 
			}


			print_r($result);
			 
		}else {
			redirect('student/timeout');
		}
	}

	function my_profile(){
        if ($this->session->userdata('student_logs')){
			$sess = $this->session->userdata('student_logs');
			$data['id'] = $sess['id'];
			$data['reg_no'] = $sess['reg_no'];
			$data['student_name'] = $sess['student_name'];
				
			$data['page_title'] = 'My Profile';
			$data['menu'] = 'my_profile';
			$data['sub_menu'] = null;
			
			$data['details'] = $this->data_model->getDetails('students',$data['id'])->row();
			
            $this->student_template->show('student/my_profile',$data);
			 
		}else {
			redirect('student/timeout');
		}
	}
	
	function update_personal_details(){
        if ($this->session->userdata('student_logs')){
			$sess = $this->session->userdata('student_logs');
			$data['id'] = $sess['id'];
			$data['reg_no'] = $sess['reg_no'];
			$data['student_name'] = $sess['student_name'];
				
			$data['page_title'] = 'Update Personal Details';
			$data['menu'] = 'my_profile';
			
			$data['action'] = 'student/update_personal_details';
			
            $this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'required');
            $this->form_validation->set_rules('place_of_birth', 'Place of Birth', 'required');
            $this->form_validation->set_rules('nationality', 'Nationality', 'required');
            $this->form_validation->set_rules('blood_group', 'Blood Group', 'required');
            $this->form_validation->set_rules('caste', 'Caste', 'required');
		    $this->form_validation->set_rules('religion', 'Religion', 'required');
		    $this->form_validation->set_rules('pan_number', 'PAN', 'required');
		    $this->form_validation->set_rules('aadhar_card', 'AADHAR', 'required');
		    
		    if ($this->form_validation->run() === FALSE){
		        
		        $data['details'] = $this->data_model->getSelectDetails('date_of_birth,place_of_birth, nationality, blood_group, religion, caste, pan_number, aadhar_card','id',$data['id'], 'students')->row();
		        $this->student_template->show('student/update_personal_details',$data);
			}else{
			    
			    $date_of_birth = $this->input->post('date_of_birth');
			    $place_of_birth = $this->input->post('place_of_birth');
			    $nationality = $this->input->post('nationality');
			    $blood_group = $this->input->post('blood_group');
			    $religion = $this->input->post('religion');
			    $caste = $this->input->post('caste');
                $pan_number = $this->input->post('pan_number');
                $aadhar_card = $this->input->post('aadhar_card');
                
                $updateData = array('date_of_birth'=>$date_of_birth, "place_of_birth" =>$place_of_birth, 'nationality'=>$nationality, 'blood_group'=>$blood_group, 'religion'=>$religion,  'caste'=>$caste, 'pan_number'=>$pan_number, 'aadhar_card'=>$aadhar_card, 'updated_by'=>$data['reg_no'], 'updated_at'=>date('Y-m-d H:i:s'));
                $res = $this->data_model->updateDetails($data['id'],$updateData,'students');
			    if($res) {
                    $this->session->set_flashdata('message', 'Personal details are updated successfully..!!');
                    $this->session->set_flashdata('status', 'alert-success');
                }else {
                    $this->session->set_flashdata('message', 'Oops..!! Something went wrong. Please try again later..!!');
                    $this->session->set_flashdata('status', 'alert-danger');
                }
                redirect('student/my_profile');
			}
			
            
			 
		}else {
			redirect('student/timeout');
		}
	}
	
	function agree(){
        if ($this->session->userdata('student_logs')){
			$sess = $this->session->userdata('student_logs');
			$data['id'] = $sess['id'];
			$data['reg_no'] = $sess['reg_no'];
			$data['student_name'] = $sess['student_name'];
			    
                $updateData = array('agreed_terms'=>'1', 'agreed_terms_datetime'=>date('Y-m-d H:i:s'));
                $res = $this->data_model->updateDetails($data['id'],$updateData,'students');
			    
				 
				$this->session->set_userdata('agreed_terms', '1');
				$this->session->set_userdata('agreed_terms_datetime', date('Y-m-d H:i:s'));

                redirect('student/dashboard');
			 
		}else {
			redirect('student/timeout');
		}
	}

	function fees(){
        if ($this->session->userdata('student_logs')){
			$sess = $this->session->userdata('student_logs');
			$data['id'] = $sess['id'];
			$data['reg_no'] = $sess['reg_no'];
			$data['student_name'] = $sess['student_name'];
				
			$data['page_title'] = 'My Fees';
			$data['menu'] = 'fees';
			$data['sub_menu'] = null;
			
			$studentDataCheck = $this->data_model->studentDataCheck($data['reg_no'])->row();
			
			if($studentDataCheck->mobile && $studentDataCheck->personal_email){
				$data['romanYears'] = array(" " => "-") + $this->globals->romanYears();
				$data['transactionTypes'] = $this->globals->transactionTypes();
				$data['hostelRoomTypes'] = array("" => "") + $this->globals->hostelRoomTypes();
				$data['transportationRoutes'] = array("" => "") + $this->globals->transportationRoutes();

				$data['details'] = $this->data_model->getDetailsbyfield('reg_no',$data['reg_no'],'fees')->result();
				$data['transactions'] = $this->data_model->getDetailsbyfield('reg_no',$data['reg_no'],'fee_transactions')->result();
				$paid = $this->data_model->paidFees($data['reg_no'])->result();
					$paidFees = array();
					foreach($paid as $paid1){
						$paidFees[$paid1->year][$paid1->fee_category] = $paid1->paid_amount;
					}
				$data['paidFees'] = $paidFees;
				$this->student_template->show('student/fees',$data);
			}else{
				$data['details'] = $studentDataCheck;
				$this->student_template->show('student/edit_basic_profile',$data);
			}
			 
		}else {
			redirect('student/timeout');
		}
	}

	function fees1(){
        if ($this->session->userdata('student_logs')){
			$sess = $this->session->userdata('student_logs');
			$data['id'] = $sess['id'];
			$data['reg_no'] = $sess['reg_no'];
			$data['student_name'] = $sess['student_name'];
				
			$data['page_title'] = 'My Fees';
			$data['menu'] = 'fees';
			$data['sub_menu'] = null;
			
			$studentDataCheck = $this->data_model->studentDataCheck($data['reg_no'])->row();
			
			if($studentDataCheck->mobile && $studentDataCheck->personal_email){
				$data['romanYears'] = array(" " => "-") + $this->globals->romanYears();
				$data['transactionTypes'] = $this->globals->transactionTypes();
				$data['hostelRoomTypes'] = array("" => "") + $this->globals->hostelRoomTypes();
				$data['transportationRoutes'] = array("" => "") + $this->globals->transportationRoutes();

				$data['details'] = $this->data_model->getDetailsbyfield('reg_no',$data['reg_no'],'fees')->result();
				$data['transactions'] = $this->data_model->getDetailsbyfield('reg_no',$data['reg_no'],'fee_transactions')->result();
				$paid = $this->data_model->paidFees($data['reg_no'])->result();
					$paidFees = array();
					foreach($paid as $paid1){
						$paidFees[$paid1->year][$paid1->fee_category] = $paid1->paid_amount;
					}
				$data['paidFees'] = $paidFees;
				$this->student_template->show('student/fees',$data);
			}else{
				$data['details'] = $studentDataCheck;
				$this->student_template->show('student/edit_basic_profile',$data);
			}
			 
		}else {
			redirect('student/timeout');
		}
	}

	function updateContactInfo(){
		if ($this->session->userdata('student_logs')){
			$sess = $this->session->userdata('student_logs');
			$data['id'] = $sess['id'];
			$data['reg_no'] = $sess['reg_no'];
			$data['student_name'] = $sess['student_name'];
				
			$data['page_title'] = 'My Profile';
			$data['menu'] = 'fees';
			$data['sub_menu'] = null;
			
			$data['action'] = 'admin/updateContactInfo';

			$data['details'] = $this->data_model->studentDataCheck($data['reg_no'])->row();
            
            $this->form_validation->set_rules('mobile', 'Mobile', 'required|regex_match[/^[0-9]{10}$/]');
			$this->form_validation->set_rules('father_mobile', 'Parent Mobile', 'required|regex_match[/^[0-9]{10}$/]');
		    $this->form_validation->set_rules('personal_email', 'Personal Email', 'required|valid_email');
		    
		    if ($this->form_validation->run() === FALSE){
		        $this->student_template->show('student/edit_basic_profile',$data);
			}else{
			    
			    $mobile = $this->input->post('mobile');
			    $father_mobile = $this->input->post('father_mobile');
			    $personal_email = $this->input->post('personal_email');
			    
                $updateData = array('mobile'=>$mobile,'father_mobile'=>$father_mobile, 'personal_email'=>$personal_email, 'updated_by'=>$data['username'], 'updated_at'=>date('Y-m-d H:i:s'));
			    
			    $res = $this->data_model->updateDetails($data['details']->id, $updateData, 'students');
			    if($res) {
                    $this->session->set_flashdata('message', 'Details are updated successfully..!!');
                    $this->session->set_flashdata('status', 'alert-success');
                }else {
                    $this->session->set_flashdata('message', 'Oops..!! Something went wrong. Please try again later..!!');
                    $this->session->set_flashdata('status', 'alert-danger');
                }
                redirect('student/my_profile');
			}
			
		}else {
			redirect('student/timeout');
		}
	}


	function receipts(){
        if ($this->session->userdata('student_logs')){
			$sess = $this->session->userdata('student_logs');
			$data['id'] = $sess['id'];
			$data['reg_no'] = $sess['reg_no'];
			$data['student_name'] = $sess['student_name'];
				
			$data['page_title'] = 'Receipts';
			$data['menu'] = 'receipts';
			$data['sub_menu'] = null;
			
			$data['romanYears'] = array(" " => "-") + $this->globals->romanYears();
			$data['transactionTypes'] = $this->globals->transactionTypes();
			$data['hostelRoomTypes'] = array("" => "") + $this->globals->hostelRoomTypes();
			$data['transportationRoutes'] = array("" => "") + $this->globals->transportationRoutes();

			$data['transactions'] = $this->data_model->getDetailsbyfield('reg_no',$data['reg_no'],'fee_transactions')->result();
			$paid = $this->data_model->paidFees($data['reg_no'])->result();
			    $paidFees = array();
                foreach($paid as $paid1){
                    $paidFees[$paid1->year][$paid1->fee_category] = $paid1->paid_amount;
                }
			$data['paidFees'] = $paidFees;
			$this->student_template->show('student/receipts',$data);
			 
		}else {
			redirect('student/timeout');
		}
	}

	function onlinePayment(){
        if ($this->session->userdata('student_logs')){
			$sess = $this->session->userdata('student_logs');
			$data['id'] = $sess['id'];
			$data['reg_no'] = $sess['reg_no'];
			$data['student_name'] = $sess['student_name'];
				
			$data['page_title'] = 'My Fees';
			$data['menu'] = 'fees';
			$data['sub_menu'] = null;
			$transactionTypes = $this->globals->transactionTypes();

			$year = $this->input->post('year');
			$payment_type = $this->input->post('payment_type');
	 		$decryptPrams = explode('-',$payment_type);
			print_r($decryptPrams); die;
			$details = $this->data_model->getSelectDetails('id, college, course, combination, father_mobile, mobile, official_email, personal_email','id',$data['id'], 'students')->row();
			
			$reg_no = trim(strtoupper($data['reg_no']));
    		$studentName = $data['student_name'];
			$studentMobile = "9035761122";
			$studentEmail = "";
			// $studentMobile = ($details->mobile) ? $details->mobile : $details->father_mobile;
    		// $studentEmail = $details->personal_email;
            $college = $details->college;
			$course = $details->course;
    		$combination = $details->combination;
			$college = $details->college;
			$fee = $decryptPrams[1];
			$fee_type = $decryptPrams[0];
			$fee_category = $decryptPrams[0];	// TUITION FEE
			
			$datenow = date("d/m/Y h:m:s");
            $transactionDate = str_replace(" ", "%20", $datenow);
            $transactionId = str_replace(".","",microtime(true)).rand(000,999);

			$paid_amount = $this->data_model->paidFees1($data['reg_no'], $year)->row()->paid_amount;

			$paid_amount = ($paid_amount) ? $paid_amount : 0;

			// RECEIPT INSERTION
			$insertDetails = array('reg_no' => $reg_no,
								'student_name' => $studentName,
								'year'=> $year, 
								'course'=>$course,
								'college'=>$college,
								'fee_type'=>$fee_type,
								'transaction_id' => $transactionId,
								'receipt_no' => '', 
								'receipt_date' => '', 
								'mode_of_payment'=>'1',
								'already_paid'=>$paid_amount,
								'amount'=>$fee,
								'ref_date'=>date('Y-m-d'),
								'ref_number'=>'',
								'bank_branch'=>'',
								'remarks'=>'',
								'transaction_status'=>'0', 
								'created_by'=>$studentName,
								'created_on'=>date('Y-m-d H:i:s'),
			);
			$sid = $this->data_model->insertDetails('fee_transactions',$insertDetails);

			// END RECEIPT INSERTION

			$merchantId = "9132";
    		$transactionPassword = "Test@123";
    		$productId = "NSE";
    		$requestHashKey = "KEY123657234";
    		$requestAESKey = "A4476C2062FFA58980DC8F79EB6A799E";
    		$requestSaltKey = "A4476C2062FFA58980DC8F79EB6A799E";

			$this->load->library('transaction_request');

			$encrypt_param = $data['reg_no'].','.$fee_type;
			$encrypt_params = base64_encode($this->encrypt->encode($encrypt_param));
			$retunrURL = base_url().'student/payment_response/'.$encrypt_params;

			
			$this->transaction_request->setLogin($merchantId);
            $this->transaction_request->setPassword($transactionPassword);
            $this->transaction_request->setProductId($productId);
            $this->transaction_request->setAmount($fee);
            $this->transaction_request->setTransactionCurrency("INR");
            $this->transaction_request->setTransactionAmount(0);
            $this->transaction_request->setReturnUrl($retunrURL);
            $this->transaction_request->setClientCode('001');
            $this->transaction_request->setTransactionId($transactionId);              // GENERATED UNIQUE TRANSACTION ID
            $this->transaction_request->setTransactionDate($transactionDate);          // CURRENT DATE AND TIME  
            $this->transaction_request->setCustomerReg($reg_no);                       // REG.NO
            $this->transaction_request->setCustomerName($studentName);                 // STUDENT NAME
            $this->transaction_request->setCustomerCourse($course);            		   // COURSE
            $this->transaction_request->setCustomerEmailId($studentEmail);             // STUDENT EMAIL
            $this->transaction_request->setCustomerMobile($studentMobile);             // STUDENT MOBILE
            $this->transaction_request->setCustomerYear($year);                        // YEAR
            $this->transaction_request->setCustomerFeeCategory($fee_category);         // FEE CATEGORY
            $this->transaction_request->setCustomerCollege($college);    			   // COLLEGE
            // $this->transaction_request->setCustomerBillingAddress($studentAddress); // STUDENT ADDRESS
            $this->transaction_request->setCustomerAccount("639827");
            $this->transaction_request->setReqHashKey($requestHashKey);
            $this->transaction_request->seturl("https://paynetzuat.atomtech.in/paynetz/epi/fts");
            $this->transaction_request->setRequestEncypritonKey($requestAESKey);
            $this->transaction_request->setSalt($requestSaltKey);
            
            $url = $this->transaction_request->getPGUrl();
		    header("Location: $url");
			 
		}else {
			redirect('student/timeout');
		}
	}

	function payment($encryptPram){
        if ($this->session->userdata('student_logs')){
			$sess = $this->session->userdata('student_logs');
			$data['id'] = $sess['id'];
			$data['reg_no'] = $sess['reg_no'];
			$data['student_name'] = $sess['student_name'];
				
			$data['page_title'] = 'My Fees';
			$data['menu'] = 'fees';
			$data['sub_menu'] = null;
			$transactionTypes = $this->globals->transactionTypes();

			if($encryptPram){
				$decryptPram = $this->encrypt->decode(base64_decode($encryptPram));
				$decryptPrams = explode(',',$decryptPram);
				echo "fees";
				$year = $decryptPrams[1];
				$fee = $decryptPrams[0];
				$fee_type = $decryptPrams[2];	  // INSTALLMENT NO.
				$fee_category = $decryptPrams[3]; // FEE CATEROGY
			}else{
				$year = $this->input->post('year');
				$payment_type = $this->input->post('payment_type');
				$decryptPrams = explode('-',$payment_type);
				echo "dashboard";
				$fee = $decryptPrams[2];
				$fee_type = $decryptPrams[1];	// INSTALLMENT NO.
				$fee_category = $decryptPrams[0]; // FEE CATEROGY
			}

			$details = $this->data_model->getSelectDetails('id, college, course, combination, father_mobile, mobile, official_email, personal_email','id',$data['id'], 'students')->row();

			$reg_no = trim(strtoupper($data['reg_no']));
    		$studentName = $data['student_name'];
			$studentMobile = "9035761122";
			$studentEmail = "seenu619@gmail.com";
			// $studentMobile = ($details->mobile) ? $details->mobile : $details->father_mobile;
    		// $studentEmail = $details->personal_email;
            $college = $details->college;
			$course = $details->course;
    		$combination = $details->combination;
			$college = $details->college;
			
			
			$datenow = date("d/m/Y h:m:s");
            $transactionDate = str_replace(" ", "%20", $datenow);
            $transactionId = str_replace(".","",microtime(true)).rand(000,999);

			$paid_amount = $this->data_model->paidFees1($data['reg_no'], $year, $fee_category)->row()->paid_amount;
			$paid_amount = ($paid_amount) ? $paid_amount : 0;

			// RECEIPT INSERTION
			$insertDetails = array('reg_no' => $reg_no,
								'student_name' => $studentName,
								'year'=> $year, 
								'course'=>$course,
								'college'=>$college,
								'fee_type'=>$fee_type,
								'fee_category'=>$fee_category,
								'transaction_id' => $transactionId,
								'receipt_no' => '', 
								'receipt_date' => '', 
								'mode_of_payment'=>'1',
								'already_paid'=>$paid_amount,
								'amount'=>$fee,
								'ref_date'=>date('Y-m-d'),
								'ref_number'=>'',
								'bank_branch'=>'',
								'remarks'=>'',
								'transaction_status'=>'0', 
								'created_by'=>$studentName,
								'created_on'=>date('Y-m-d H:i:s'),
			);
			$sid = $this->data_model->insertDetails('fee_transactions',$insertDetails);

			// END RECEIPT INSERTION

			// CONFIG PAYMENT GATEWAY KEYS
			$merchantId = "9132";
    		$transactionPassword = "Test@123";
    		$productId = "NSE";
    		$requestHashKey = "KEY123657234";
    		$requestAESKey = "A4476C2062FFA58980DC8F79EB6A799E";
    		$requestSaltKey = "A4476C2062FFA58980DC8F79EB6A799E";

			$this->load->library('transaction_request');

			$encrypt_param = $data['reg_no'].','.$fee_type.','.$fee_category;
			$encrypt_params = base64_encode($this->encrypt->encode($encrypt_param));
			$retunrURL = base_url().'student/payment_response/'.$encrypt_params;

			
			$this->transaction_request->setLogin($merchantId);
            $this->transaction_request->setPassword($transactionPassword);
            $this->transaction_request->setProductId($productId);
            $this->transaction_request->setAmount($fee);
            $this->transaction_request->setTransactionCurrency("INR");
            $this->transaction_request->setTransactionAmount(0);
            $this->transaction_request->setReturnUrl($retunrURL);
            $this->transaction_request->setClientCode('001');
            $this->transaction_request->setTransactionId($transactionId);              // GENERATED UNIQUE TRANSACTION ID
            $this->transaction_request->setTransactionDate($transactionDate);          // CURRENT DATE AND TIME  
            $this->transaction_request->setCustomerReg($reg_no);                       // REG.NO
            $this->transaction_request->setCustomerName($studentName);                 // STUDENT NAME
            $this->transaction_request->setCustomerCourse($course);            		   // COURSE
            $this->transaction_request->setCustomerEmailId($studentEmail);             // STUDENT EMAIL
            $this->transaction_request->setCustomerMobile($studentMobile);             // STUDENT MOBILE
            $this->transaction_request->setCustomerYear($year);                        // YEAR
            $this->transaction_request->setCustomerFeeCategory($fee_category);         // FEE CATEGORY
            $this->transaction_request->setCustomerCollege($college);    			   // COLLEGE
            // $this->transaction_request->setCustomerBillingAddress($studentAddress);    // STUDENT ADDRESS
            $this->transaction_request->setCustomerAccount("639827");
            $this->transaction_request->setReqHashKey($requestHashKey);
            $this->transaction_request->seturl("https://paynetzuat.atomtech.in/paynetz/epi/fts");
            $this->transaction_request->setRequestEncypritonKey($requestAESKey);
            $this->transaction_request->setSalt($requestSaltKey);
            
            $url = $this->transaction_request->getPGUrl();
		    header("Location: $url");
			 
		}else {
			redirect('student/timeout');
		}
	}
	
	function payment_response($encrypt_praram){
		    
		    $decryptPram = $this->encrypt->decode(base64_decode($encrypt_praram));
			$decryptPrams = explode(',',$decryptPram);
			$reg_no = $decryptPrams[0];
			$fee_type = $decryptPrams[1];
			$fee_category = $decryptPrams[2];
    		 
			$this->load->library('transaction_response');
			$responseHashKey = "KEYRESP123657234";
    	    $responseAESKey = "75AEF0FA1B94B3C10D4F5B268F757F11";
    		$responseSaltKey = "75AEF0FA1B94B3C10D4F5B268F757F11";

			$this->load->library('transaction_request');
            $this->transaction_response->setRespHashKey($responseHashKey);
            $this->transaction_response->setResponseEncypritonKey($responseAESKey);
            $this->transaction_response->setSalt($responseSaltKey);
        
            $arrayofdata = $this->transaction_response->decryptResponseIntoArray($_POST['encdata']);

			// $reg_no = $arrayofdata['udf9'];
            $txn_id = $arrayofdata['mer_txn'];
			$year = $arrayofdata['udf11'];
			$college = $arrayofdata['udf12'];
			$fee_category = $arrayofdata['udf13'];

			// RELOGIN
			$result = $this->data_model->studentLogin($reg_no, md5("INDIA"));
			if($result){
				$sess_array = array('id' => $result->id,
								'reg_no' => $result->reg_no,
								'student_name' => $result->student_name
							);
				$this->session->set_userdata('student_logs', $sess_array);

				$this->session->set_userdata('agreed_terms', $result->agreed_terms);
				$this->session->set_userdata('agreed_terms_datetime', date('Y-m-d H:i:s'));
				// END RELOGIN

				// print_r($arrayofdata);

				if($arrayofdata['f_code'] == "Ok"){
					if($fee_category == '1'){
						$param1 = $college;
						$CRN = $this->data_model->getReceiptNo($college,'1')->row()->count;
					}elseif($fee_category == '2'){
						$param1 = 'HOSTEL';
						$CRN = $this->data_model->getReceiptNo(null,'2')->row()->count;
					}elseif($fee_category == '3'){
						$param1 = 'TRNSP';
						$CRN = $this->data_model->getReceiptNo(null,'3')->row()->count;
					} else {
						$param1 = null;
						$CRN = null;
					}
					
					
					$ay = $this->globals->currentAcademicYear();

					$receipt_no = null;
            		$cnt_number = $CRN + 1;
            		
                    $strlen = strlen(($cnt_number));
                    if($strlen == 1){  $cnt_number = "0000".$cnt_number; }
                    if($strlen == 2){  $cnt_number = "000".$cnt_number; }
                    if($strlen == 3){  $cnt_number = "00".$cnt_number; }
                    if($strlen == 4){  $cnt_number = "0".$cnt_number; }

					$receipt_no = $param1."/".$ay."/".$cnt_number;
 
					$updateDetails = array('receipt_no' => $receipt_no, 
								'receipt_date' => date('Y-m-d H:i:s'), 
								'ref_number' =>$arrayofdata['bank_txn'], 
								'bank_branch'=>$arrayofdata['bank_name'], 
								'discriminator'=>$arrayofdata['discriminator'], 
								'remarks'=>$arrayofdata['desc'], 
								'verified_by'=>$result->student_name,
								'verified_on'=>date('Y-m-d H:i:s'), 
								'transaction_status'=>'1', 
								'txn_status'=>$arrayofdata['f_code'], 
								'txn_response'=>json_encode($arrayofdata)
					);

					$feeValues = array("1" => array("0"=>"net_fee_status", "1"=>"installment_1_status", "2"=>"installment_2_status"),
								"2" => array("1"=>"hostel_inst_1_fee_status", "2"=>"hostel_inst_2_fee_status"),
								"3" => array("1"=>"transportation_inst_1_fee_status", "2"=>"transportation_inst_2_fee_status"),
								);
				
					$updateFeeDetails = array($feeValues[$fee_category][$fee_type] => '1');

					// if($fee_type == '0'){
					// 	$updateFeeDetails = array('net_fee_status' => '1');
					// }
					// if($fee_type == '1'){
					// 	$updateFeeDetails = array('installment_1_status' => '1');
					// }
					// if($fee_type == '2'){
					// 	$updateFeeDetails = array('installment_2_status' => '1');
					// }
					// if($fee_type == '3'){
					// 	$updateFeeDetails = array('installment_3_status' => '1');
					// }

					$feeUpdate = $this->data_model->updateFeeStatus($reg_no, $year, $updateFeeDetails);

				}elseif($arrayofdata['f_code'] == "C"){ 
					$updateDetails = array('ref_number' =>$arrayofdata['ipg_txn_id'], 
								'bank_branch'=>$arrayofdata['bank_name'], 
								'discriminator'=>$arrayofdata['discriminator'], 
								'remarks'=>$arrayofdata['desc'], 
								'verified_by'=>$sessArr['student_name'],
								'verified_on'=>date('Y-m-d H:i:s'), 
								'transaction_status'=>'2', 
								'txn_status'=>$arrayofdata['f_code'], 
								'txn_response'=>json_encode($arrayofdata)
					);
				}elseif($arrayofdata['f_code'] == "F"){ 
					$updateDetails = array('ref_number' =>$arrayofdata['ipg_txn_id'], 
								'bank_branch'=>$arrayofdata['bank_name'], 
								'discriminator'=>$arrayofdata['discriminator'], 
								'remarks'=>$arrayofdata['desc'], 
								'verified_by'=>$sessArr['student_name'],
								'verified_on'=>date('Y-m-d H:i:s'), 
								'transaction_status'=>'3', 
								'txn_status'=>$arrayofdata['f_code'], 
								'txn_response'=>json_encode($arrayofdata)
					);
				}else{
					$updateDetails = array( 
							'remarks'=>'Oops..!! Something went wrong. Please try again later..!!', 
							'verified_by'=>$sessArr['student_name'],
							'verified_on'=>date('Y-m-d H:i:s'), 
							'transaction_status'=>'2'
						);
				}

				$res = $this->data_model->updateTransactionDetails($reg_no, $txn_id, $updateDetails);

				if($res) {
                    $this->session->set_flashdata('message', 'Payment successfully..!!');
                    $this->session->set_flashdata('status', 'alert-success');
                }else {
                    $this->session->set_flashdata('message', 'Oops..!! Something went wrong. Please try again later..!!');
                    $this->session->set_flashdata('status', 'alert-danger');
                }
				
				redirect('student/fees');
			}else{
				redirect('student/timeout');
			}			 
	}

	function downloadReceipt($encryptTransacitonID){
        if ($this->session->userdata('student_logs')){
			$sess = $this->session->userdata('student_logs');
			$data['id'] = $sess['id'];
			$data['reg_no'] = $sess['reg_no'];
			$data['student_name'] = $sess['student_name'];
			
			$data['page_title'] = 'My Fees';
			$data['menu'] = 'fees';
			$data['sub_menu'] = null;

			// echo phpinfo();die;
			
			$transacitonID = $this->encrypt->decode(base64_decode($encryptTransacitonID));

			$studentDetails = $this->data_model->getDetailsbyfield('reg_no', $data['reg_no'], 'students')->row();
			$transactionDetails = $this->data_model->getDetailsbyfield('id', $transacitonID, 'fee_transactions')->row();
			$feeDetails = $this->data_model->getStudentFee($data['reg_no'], $transactionDetails->year)->row();
			$feeStructure = $this->data_model->feeStructure($feeDetails->college, $feeDetails->academic_year, $feeDetails->course, $feeDetails->combination)->row();
			// $paid_amount = $this->data_model->paidFees1($data['reg_no'], $transactionDetails->year)->row()->paid_amount;

            $college = $studentDetails->college;
			
			$this->load->library('fpdf'); // Load library
			
			ini_set("session.auto_start", 0);
			ini_set('memory_limit', '-1');
// 			define('FPDF_FONTPATH','plugins/font');
    	    $pdf = new FPDF('p','mm','A5');
            $pdf->enableheader = 0;
            $pdf->enablefooter = 0;
    	    $pdf->AddPage();
			
			stream_context_set_default([
				"ssl"=>array(
					"verify_peer"=>false,
					"verify_peer_name"=>false,
				)
				]);

    	    $pdf->Image(base_url().'assets/img/NCMS_RECEIPT.png', 0, 0, 148, 'PNG');
    	    $pdf->setDisplayMode('fullpage');
			 
			$row = 8;
			$rowHeight = 5;
			$pdf->SetTextColor(33,33,33);
			$pdf->setFont ('Arial','B',14);
            $pdf->SetXY(20, 30); 
            $pdf->Cell(0,10,"RECEIPT",0,0,'C', false);
            
            $y = $pdf->getY();
            $pdf->setFont ('Arial','B',8);
            $pdf->SetXY(10, $y+10); 
            $pdf->Cell(0,10,"Receipt No: ".$transactionDetails->receipt_no,0,0,'L', false);
            $pdf->SetXY(96, $y+10); 
            $pdf->Cell(0,10, "Date: ".date('d-m-Y', strtotime($transactionDetails->receipt_date)),0,0,'L', false); 

			$y = $pdf->getY();
            $pdf->setFont ('Arial','B',8);
            $pdf->SetXY(10, $y+$rowHeight); 
            $pdf->Cell(0,10,"Student Name : ".$studentDetails->student_name,0,0,'L', false);
            $pdf->SetXY(96, $y+$rowHeight); 
            $pdf->Cell(0,10, "REG/USN No.: ".$studentDetails->reg_no,0,0,'L', false); 

			$y = $pdf->getY();
            $pdf->setFont ('Arial','B',8);
            $pdf->SetXY(10, $y+$rowHeight); 
            $pdf->Cell(0,10,"Branch/Class : ".$feeDetails->course,0,0,'L', false);
            $pdf->SetXY(96, $y+$rowHeight); 
            $pdf->Cell(0,10, "SEM: ".$feeDetails->current_sem,0,0,'L', false); 

			$y = $pdf->getY();
            $pdf->setFont ('Arial','B',8);
            $pdf->SetXY(10, $y+$rowHeight); 
            $pdf->Cell(0,10,"Section : ".'',0,0,'L', false);
            $pdf->SetXY(96, $y+$rowHeight); 
            $pdf->Cell(0,10, "Batch : ".$studentDetails->admission_year,0,0,'L', false); 
            
            $y = $pdf->getY();
            $pdf->setFont ('Arial','B',9);
            $pdf->SetXY(10, $y+$row+3); 
            $pdf->Cell(0,$row,"SLNo",1,0,'L', false);
            $pdf->setFont ('Arial','B',9);
            $pdf->SetXY(20, $y+$row+3); 
            $pdf->Cell(0,$row,"Particulars",1,0,'L', false); 
			$pdf->SetXY(110, $y+$row+3); 
            $pdf->Cell(0,$row,"Amounts (Rs.)",1,0,'C', false); 
            
			$displayFee = array(); $grand_total = 0;
			 
            if(($college == "NCMS") && ($transactionDetails->fee_category == '1')){
				$already_paid = $transactionDetails->already_paid;
				$current_paid = $transactionDetails->amount;
				$amount = $transactionDetails->amount;

				$feeStr = array(
					'admin_fee' => $feeStructure->admin_fee,
					'other_fee' => $feeStructure->other_fee,
					'tuition_fee' => $feeStructure->tuition_fee
				);
				 

				// ALREADY PAID CALC
				if($already_paid > 0){
					foreach($feeStr as $key => $value){
						// echo $value." ";
						$bal = $already_paid - $value;
						if($bal >= 0){
                            $feeStr[$key] = 0;
						}else{
							if($bal <= 0){
								$bal = $value - $already_paid;
								$feeStr[$key] = $bal;
								$bal = 0;
							}else{
								$feeStr[$key] = $value;
							}
						}
						$already_paid = $bal;
						// echo "<br>";
					  }
				}

				// PAID AMOUNT CALC
				  foreach($feeStr as $key => $value){
					// echo $value." ";
					// echo $current_paid;
					$bal = $current_paid - $value;
					if($value != 0){
						if($bal >= 0){
							$displayFee[$key] = $value;
						}else{
							// echo "Nn";
							if($current_paid){
								$displayFee[$key] = $current_paid;
							}
							$bal = 0;
						}
						$current_paid = $bal;
					}
					// echo "<br>";
				  }
				// print_r($displayFee);

				// PRINT IN PDF
				$i = 1; $total = 0;
				foreach($displayFee as $key => $value){
					$det = ucwords(str_replace('_', ' ', $key));
					$val = number_format($value,2);
					$y = $pdf->getY();
					$pdf->setFont ('Arial','',9);
					$pdf->SetXY(10, $y+$row); 
					$pdf->Cell(0,$row, $i++.".",1,0,'L', false);
					$pdf->SetXY(20, $y+$row); 
					$pdf->Cell(0,$row,$det,1,0,'L', false); 
					$pdf->SetXY(110, $y+$row); 
					$pdf->Cell(0,$row, $val, 1,0,'R', false);

					$total = $total + $value;
				}

				$grand_total = $total;
			}
            
			if($transactionDetails->fee_category == '2'){
				$hostel_deposit_fee = $feeDetails->hostel_deposit_fee;
				$hostel_fee = $feeDetails->hostel_fee;

				$current_paid = $transactionDetails->amount;
				
				if($transactionDetails->fee_type == '1'){
					if($hostel_deposit_fee){
						$y = $pdf->getY();
						$pdf->setFont ('Arial','',9);
						$pdf->SetXY(10, $y+$row); 
						$pdf->Cell(0,$row, "1.",1,0,'L', false);
						$pdf->SetXY(20, $y+$row); 
						$pdf->Cell(0,$row,'Hostel Caution Deposit Fee',1,0,'L', false); 
						$pdf->SetXY(110, $y+$row); 
						$pdf->Cell(0,$row, number_format($feeDetails->hostel_deposit_fee,2), 1,0,'R', false);
						$current_paid = $current_paid - $feeDetails->hostel_deposit_fee;
					}

					$y = $pdf->getY();
					$pdf->setFont ('Arial','',9);
					$pdf->SetXY(10, $y+$row); 
					$pdf->Cell(0,$row, "1.",1,0,'L', false);
					$pdf->SetXY(20, $y+$row); 
					$pdf->Cell(0,$row,'Hostel Fee',1,0,'L', false); 
					$pdf->SetXY(110, $y+$row); 
					$pdf->Cell(0,$row, number_format($current_paid, 2), 1,0,'R', false);
				}

				if($transactionDetails->fee_type == '2'){
					$y = $pdf->getY();
					$pdf->setFont ('Arial','',9);
					$pdf->SetXY(10, $y+$row); 
					$pdf->Cell(0,$row, "1.",1,0,'L', false);
					$pdf->SetXY(20, $y+$row); 
					$pdf->Cell(0,$row,'Hostel Fee',1,0,'L', false); 
					$pdf->SetXY(110, $y+$row); 
					$pdf->Cell(0,$row, number_format($current_paid, 2), 1,0,'R', false);
				}
								
				$grand_total = $transactionDetails->amount;
			}

			if($transactionDetails->fee_category == '3'){
				$current_paid = $transactionDetails->amount;
				$val = number_format($current_paid,2);
				$y = $pdf->getY();
				$pdf->setFont ('Arial','',9);
				$pdf->SetXY(10, $y+$row); 
				$pdf->Cell(0,$row, "1.",1,0,'L', false);
				$pdf->SetXY(20, $y+$row); 
				$pdf->Cell(0,$row,'Transportation Fee',1,0,'L', false); 
				$pdf->SetXY(110, $y+$row); 
				$pdf->Cell(0,$row, $val, 1,0,'R', false);
				$grand_total = $current_paid;
			}
			
			$y = $pdf->getY();
			$pdf->setFont ('Arial','B',9);
			$pdf->SetXY(10, $y+$row); 
			// $pdf->Cell(0,$row, $i++.".",1,0,'L', false);
			// $pdf->SetXY(20, $y+$row); 
			$pdf->Cell(0,$row, "Total",1,0,'C', false); 
			$pdf->SetXY(110, $y+$row); 
			$pdf->Cell(0,$row, number_format($grand_total,2), 1,0,'R', false);

			$y = $pdf->getY();
			$pdf->setFont ('Arial','',9);
			$pdf->SetXY(10, $y+10); 
			$pdf->Cell(0,$row,"(In Words) :".$this->globals->getIndianCurrency($grand_total),0,0,'L', false);


			$y = $pdf->getY();
            $pdf->setFont ('Arial','',9);
            $pdf->SetXY(10, $y+8); 
            $pdf->Cell(0,$row,"Paid By : ".$this->globals->discriminatorValues()[$transactionDetails->discriminator].' (Ref.No:'.$transactionDetails->ref_number.')',0,0,'L', false);

		            
            $y = $pdf->getY();
            $pdf->setFont ('Arial','B',10);
            $pdf->SetXY(100, $y+20); 
            $pdf->Cell(0,$row,"Accountant",0,0,'L', false);
            

			$fileName = $transactionDetails->receipt_no.'.pdf';
		    $pdf->output($fileName,'D'); 
			 
		}else {
			redirect('student/timeout');
		}
	}

	function changePassword(){
        if ($this->session->userdata('student_logs')){
			$sess = $this->session->userdata('student_logs');
			$data['id'] = $sess['id'];
			$data['reg_no'] = $sess['reg_no'];
			$data['student_name'] = $sess['student_name'];
				
			$data['page_title'] = 'Change Password';
			$data['menu'] = 'changePassword';
			$data['sub_menu'] = null;
			
			$data['action'] = 'student/changePassword';
			
            $this->form_validation->set_rules('old_password', 'Old Password', 'required|min_length[4]');
		    $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[4]');
		    
		    if ($this->form_validation->run() === FALSE){
		        $this->student_template->show('student/changePassword',$data);        
			}else{
			    $old_password = $this->input->post('old_password');
                $new_password = $this->input->post('new_password');
                if(strcmp($old_password, $new_password)){
                    $res = $this->data_model->studentLogin($data['reg_no'], md5($old_password));
                    if($res){
                        $updateData = array('password'=>md5($new_password), 'updated_by'=>$data['reg_no'], 'updated_at'=>date('Y-m-d H:i:s'));
			            $res1 = $this->data_model->updateDetails($data['id'], $updateData,'students');
			            if($res1){
                		    $this->session->set_flashdata('message', 'Your password has been changed successfully.');
                		    $this->session->set_flashdata('status', 'alert-success');
                		}else {
                		    $this->session->set_flashdata('message', 'Oops..!! Something went wrong. Please try again later..!!');
                		    $this->session->set_flashdata('status', 'alert-danger');
        			    }
                    }else{
                        $this->session->set_flashdata('message', 'The old password you have entered is incorrect..!!');
        		        $this->session->set_flashdata('status', 'alert-danger');
                    }
                }else{
                    $this->session->set_flashdata('message', 'Old and New Password should not be same..!!');
    			    $this->session->set_flashdata('status', 'alert-danger');   
                }
                
                redirect('student/changePassword');
			}
			 
		}else {
			redirect('student/timeout');
		}
	}
	
	function TermsAndConditions(){
		$this->load->view('TermsAndConditions');
	}

	function PrivacyPolicy(){
		$this->load->view('PrivacyPolicy');
	}

	function RefundPolicy(){
		$this->load->view('RefundPolicy');
	}
}