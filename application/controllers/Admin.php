<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    function __construct() {
		parent::__construct();
		$this->CI = & get_instance();
		$this->load->model('data_model','',TRUE);
		$this->load->library(array('table','form_validation','session','email'));
		$this->load->helper(array('email'));
	    date_default_timezone_set('Asia/Kolkata');
	}
		
	public function index(){
	    if($this->session->userdata('admin_logs')){
	        redirect('admin/dashboard');
	    } else {
			
    	    if ($this->uri->segment(2) == 'invalid'){
    			$data['msg'] = 'Invalid Email / Password..!!';
    			$data['cls'] = 'alert alert-danger text-center';
    	    }elseif ($this->uri->segment(2) == 'timeout'){
    			$data['msg'] = 'Session timeout..!!';
    			$data['cls'] = 'alert alert-danger text-center';
    	    }elseif ($this->uri->segment(2) == 'logout'){
    			$data['msg'] = 'Successfully logout..!!';
    			$data['cls'] = 'alert alert-danger text-center';			
    	    }else {
    			$data['msg'] = '';
    			$data['cls'] = '';
    		}
    			
    	    $data['action'] = 'admin';
    	    $this->form_validation->set_rules('username','Username','required');
    	    $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');
    		if ($this->form_validation->run() === false){
    		    $this->login_template->show('admin/login', $data);
    		} else {
    		    $username = $this->input->post('username');
    			redirect('admin/dashboard');
    		}
	    }
    }
    
	function check_database($password){
		//Field validation succeeded.  Validate against database
		$username = $this->input->post('username');
		
   		//query the database
   		$result = $this->data_model->login($username, md5($password));
   		if($result)
   		{
     	  $sess_array = array();
     	  foreach($result as $row)
          {
       		$sess_array = array(
				'id' => $row->id,
         		'username' => $row->username,
				'user_type' => $row->user_type, 
				'name' => $row->name,
       		);
       	   $this->session->set_userdata('admin_logs', $sess_array);
          }
          return TRUE;
		}
   		else
   		{
     		$this->form_validation->set_message('check_database', 'Invalid username or password');
     		return false;
   		}
 	}
	 
    function logout(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$this->session->unset_userdata('admin_logs');
	   		session_destroy();
	   		redirect('admin/logout', 'refresh');

  		}else {
			redirect('admin/timeout');
		}
	}
    
    function dashboard(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Dashboard';
			$data['menu'] = 'dashboard';
			$data['sub_menu'] = null;
			
			$this->admin_template->show('admin/dashboard',$data);
		}else {
			redirect('admin/timeout');
		}
	}

	function students(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Students';
			$data['menu'] = 'students';
			$data['sub_menu'] = null;

			$academic_years = $this->academicYearsDropdown();
			$data['academic_years'] = $academic_years['dropdown'];
			$data['ac_active'] = $academic_years['ac_active'];

			$courses = $this->coursesList();
			$data['courses'] = array("all" => "All Courses & Combinations") + $courses;

			$this->admin_template->show('admin/students',$data);
		}else {
			redirect('admin/timeout');
		}
	}

    function add_student(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Add New Staff';
			$data['menu'] = 'students';
			$data['sub_menu'] = 'students';

			$data['action'] = "admin/add_student";
            
			$this->form_validation->set_rules('admission_year', 'Admission Year', 'required');
			$this->form_validation->set_rules('course', 'Course & Combination', 'required');
			$this->form_validation->set_rules('academic_year', 'Academic Year', 'required');
			$this->form_validation->set_rules('year_sem', 'Year & Semester', 'required');
			$this->form_validation->set_rules('reg_no', 'Reg. No', 'required');
			$this->form_validation->set_rules('student_name', 'Student Name', 'required');
            $this->form_validation->set_rules('mobile', 'Mobile', 'required|regex_match[/^[0-9]{10}$/]|is_unique[students.mobile]');
			$this->form_validation->set_rules('father_mobile', 'Father Mobile', 'regex_match[/^[0-9]{10}$/]');
			$this->form_validation->set_rules('official_email', 'Official Email', 'valid_email');
		    $this->form_validation->set_rules('personal_email', 'Personal Email', 'required|valid_email|is_unique[students.personal_email]');
		    $this->form_validation->set_rules('state', 'State', 'required');
		    
    		if ($this->form_validation->run() === FALSE){
    		    
    		    $data['admissionYears'] = array("" => "Select") + $this->globals->admissionYears('2020');      
				$data['yearSem'] = array("" => "Select") + $this->globals->yearSem();      
				
				$academic_years = $this->academicYearsDropdown();
				$data['academic_years'] = array("" => "Select") + $academic_years['dropdown'];
				$data['ac_active'] = $academic_years['ac_active'];

				$courses = $this->coursesList();
    			$courses_dropdown = array("" => "Select");
    			$data['courses'] = $courses_dropdown + $courses;
                
                $this->admin_template->show('admin/add_student',$data);        
			}else{
				
				$reg_no = $this->input->post('reg_no');
			    $admission_year = $this->input->post('admission_year');
				$course_val = $this->input->post('course');
				$courseArray = explode('-',$course_val);
				$course = trim($courseArray[0]);
				if(array_key_exists("1",$courseArray)){
					$combination = trim($courseArray[1]);
				}else{
					$combination = "";
					
				}
				$academic_year = $this->input->post('academic_year');
				$year_sem = $this->input->post('year_sem');
				$yearSemArray = explode('-',$year_sem);
				$year = trim($yearSemArray[0]);
				if(array_key_exists("1",$yearSemArray)){
					$semester = trim($yearSemArray[1]);
				}else{
					$semester = "";
				}
				$student_name = $this->input->post('student_name');
			    $father_mobile = $this->input->post('father_mobile');
				$mobile = $this->input->post('mobile');
				$official_email = $this->input->post('official_email');
                $personal_email = $this->input->post('personal_email');
			    $village = $this->input->post('village');
			    $post = $this->input->post('post');
			    $taluk = $this->input->post('taluk');
			    $district = $this->input->post('district');
				$state = $this->input->post('state');
				
				$res = $this->data_model->getFee($academic_year, $course, $combination, $year)->row();
				$fixed_fee = $res->total_fee;
				
				$inst = ($fixed_fee) / 2; 
				
				$insertData = array("reg_no"=>$reg_no, 'password' => md5("NCMS"), 'admission_year'=>$admission_year, 'course'=>$course, 'combination'=>$combination, 'student_name'=>$student_name, 'father_mobile'=>$father_mobile, 'mobile'=>$mobile, 'official_email'=>$official_email, 'personal_email'=>$personal_email, 'village'=>$village, 'post'=>$post, 'taluk'=>$taluk, 'district'=>$district, 'state'=>$state, 'status'=>'1' ,'updated_by'=>$data['username'], 'updated_at'=>date('Y-m-d H:i:s'));
			    $sid = $this->data_model->insertDetails('students',$insertData);
			    
			    $insertData1 = array("reg_no"=>$reg_no, 'academic_year' => $academic_year, 'current_year'=>$year, 'current_sem'=>$semester, 'course'=>$course, 'combination'=>$combination, 'student_name'=>$student_name, 'mobile'=>$mobile, 'fixed_fee'=>$fixed_fee, 'net_fee'=>$fixed_fee, 'installment_1_fee'=>$inst,  'installment_2_fee'=>$inst,'status'=>'1');
			    $res = $this->data_model->insertDetails('fees',$insertData1);
				
			    if($res) {
                    $this->session->set_flashdata('message', 'Student details are added successfully..!!');
                    $this->session->set_flashdata('status', 'alert-success');
                }else {
                    $this->session->set_flashdata('message', 'Oops..!! Something went wrong. Please try again later..!!');
                    $this->session->set_flashdata('status', 'alert-danger');
                } 
			    redirect('admin/student_details/'.$reg_no);	    
			}
		}else {
			redirect('admin/timeout');
		}
	}
	
	function getFixedFee(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Courses';
			$data['menu'] = 'courses';
			$data['sub_menu'] = null;
			
			$academic_year = $this->input->post('academic_year');
			$course_val = $this->input->post('course');
			$courseArray = explode('-',$course_val);
			$course = trim($courseArray[0]);
			if(array_key_exists("1",$courseArray)){
				$combination = trim($courseArray[1]);
			}else{
				$combination = "";
			}
			$year_sem = $this->input->post('year_sem');
			$yearSemArray = explode('-',$year_sem);
			$year = trim($yearSemArray[0]);
			if(array_key_exists("1",$yearSemArray)){
				$semester = trim($yearSemArray[1]);
			}else{
				$semester = "";
			}

			$res = $this->data_model->getFee($academic_year, $course, $combination, $year)->row();

			if($res){
				$total_fee = $res->total_fee;
			}else{
				$total_fee = 0;
			}
			
			echo $total_fee;

		}else {
			redirect('admin/timeout');
		}
	}

	function getStudentsList(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
			
			$academic_year = $this->input->post('academic_year');
			$course_val = $this->input->post('course');
			$courseArray = explode('-',$course_val);
			$course = trim($courseArray[0]);
			if(array_key_exists("1",$courseArray)){
				$combination = trim($courseArray[1]);
			}else{
				$combination = "";
			}
			$year = $this->input->post('year');
			$download = $this->input->post('download');
			$students = $this->data_model->getStudentsList($academic_year, $course, $combination, $year)->result();
			
			if($download){
				$table_setup = array ('table_open'=> '<table class="table table-striped table-vcenter table-hover js-dataTable-full font-size-sm"  border="1" id="js-dataTable-full">');    
			}else{
				$table_setup = array ('table_open'=> '<table class="table table-striped table-vcenter table-hover js-dataTable-full font-size-sm" id="js-dataTable-full">');
			}
                
                $this->table->set_template($table_setup);
    			$this->table->set_heading(
    								array('data' =>'S.No', 'style'=>'width:5%;'),
    								array('data' =>'Reg. No.','style'=>'width:15%;'),
    								array('data' =>'Name','style'=>'width:20%;'),
									array('data' =>'Mobile','style'=>'width:10%;'),
    								array('data' =>'Course','style'=>'width:15%;'),
    								array('data' =>'Combination','style'=>'width:15%;'),
    								array('data' =>'Year','style'=>'width:10%;'),
    								array('data' =>'Net Fee','style'=>'width:20%;')
    				                );
    			$i=1;
    			foreach ($students as $students1){
					if($download){
						$reg_no = $students1->reg_no;
					}else{
						$reg_no = anchor('admin/student_details/'.$students1->reg_no, $students1->reg_no);
					}
    				$this->table->add_row($i++,
    				        $reg_no,
    						$students1->student_name,
							$students1->mobile,
    						$students1->course,
    						$students1->combination,
    						$students1->current_year,
    						$students1->net_fee
    				);
    			}

				if($download){
					$detailsTable = $this->table->generate();
    			    $response =  array('op' => 'ok',
                        'file' => "data:application/vnd.ms-excel;base64,".base64_encode($detailsTable)
                        );
                    die(json_encode($response));    
				}else{
					echo $data['table']=$this->table->generate();
				}
		}else {
			redirect('admin/timeout');
		}
	}

	function student_details($reg_no){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Students';
			$data['menu'] = 'students';
			$data['sub_menu'] = null;

			$data['romanYears'] = array("" => "-") + $this->globals->romanYears();
			$data['hostelRoomTypes'] = array("" => "") + $this->globals->hostelRoomTypes();
			$data['transportationRoutes'] = array("" => "") + $this->globals->transportationRoutes();
			$data['transactionTypes'] = $this->globals->transactionTypes();
			
			$data['personal_details'] = $this->data_model->getDetailsbyfield('reg_no',$reg_no,'students')->row();
			$data['fee_details'] = $this->data_model->getDetailsbyfield('reg_no',$reg_no,'fees')->result();
			$data['transactions'] = $this->data_model->getDetailsbyfield('reg_no',$reg_no,'fee_transactions')->result();
			$paid = $this->data_model->paidFees($reg_no)->result();
            $paidFees = array();
            foreach($paid as $paid1){
                $paidFees[$paid1->year] = $paid1->paid_amount;
            }
            $data['paidFees'] = $paidFees;

			$this->admin_template->show('admin/student_details',$data);
		}else {
			redirect('admin/timeout');
		}
	}
    
    function studentUpdate($id){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Student Details Update';
			$data['menu'] = 'students';
			$data['sub_menu'] = null;
			
			$data['action'] = 'admin/studentUpdate/'.$id;
			$data['details'] = $this->data_model->getDetails('students',$id)->row();
            
            $this->form_validation->set_rules('student_name', 'Student Name', 'required');
            // $this->form_validation->set_rules('reg_no', 'Reg. No', 'required');
            $this->form_validation->set_rules('admission_no', 'Adm. No', 'required');
            $this->form_validation->set_rules('adm_year', 'Adm. Year', 'required');
            $this->form_validation->set_rules('adm_date', 'Adm. Date', 'required');
            $this->form_validation->set_rules('category', 'Category', 'required');
            $this->form_validation->set_rules('aided_unaided', 'Type', 'required');
            $this->form_validation->set_rules('mobile', 'Mobile', 'regex_match[/^[0-9]{10}$/]');
		    $this->form_validation->set_rules('official_email', 'Official Email', 'required|valid_email');
		    $this->form_validation->set_rules('personal_email', 'Personal Email', 'valid_email');
		    
		    if ($this->form_validation->run() === FALSE){
		        $this->admin_template->show('admin/update_student_details',$data);
			}else{
			    
			    $student_name = $this->input->post('student_name');
			 //   $reg_no = $this->input->post('reg_no');
			    $admission_no = $this->input->post('admission_no');
			    $adm_year = $this->input->post('adm_year');
			    $adm_date = $this->input->post('adm_date');
			    $category = $this->input->post('category');
			    $aided_unaided = $this->input->post('aided_unaided');
			    $mobile = $this->input->post('mobile');
			    $official_email = $this->input->post('official_email');
			    $personal_email = $this->input->post('personal_email');
			    
                $updateData = array('updated_by'=>$data['username'], 'updated_at'=>date('Y-m-d H:i:s'));
                    
                if($data['details']->student_name != $student_name){
                    $updateData['student_name'] = $student_name;    
                }
			    
			//    if($data['details']->reg_no != $reg_no){
            //         $updateData['reg_no'] = $reg_no;    
            //     }
                
                if($data['details']->admission_no != $admission_no){
                    $updateData['admission_no'] = $admission_no;    
                }
                
                if($data['details']->adm_year != $adm_year){
                    $updateData['adm_year'] = $adm_year;    
                }
                
                if($data['details']->adm_date != $adm_date){
                    $updateData['adm_date'] = $adm_date;    
                }
                
                if($data['details']->category != $category){
                    $updateData['category'] = $category;    
                }
                
                if($data['details']->aided_unaided != $aided_unaided){
                    $updateData['aided_unaided'] = $aided_unaided;    
                }
                
                if($data['details']->official_email != $official_email){
                    $updateData['official_email'] = $official_email;    
                }
                
                if($data['details']->personal_email != $personal_email){
                    $updateData['personal_email'] = $personal_email;    
                }
                
                if($data['details']->mobile != $mobile){
                    $updateData['mobile'] = $mobile;    
                }
			    
			    $res = $this->data_model->updateDetails($id, $updateData, 'students');
			    if($res) {
                    $this->session->set_flashdata('message', 'Details are updated successfully..!!');
                    $this->session->set_flashdata('status', 'alert-success');
                }else {
                    $this->session->set_flashdata('message', 'Oops..!! Something went wrong. Please try again later..!!');
                    $this->session->set_flashdata('status', 'alert-danger');
                }
                redirect('admin/student_details/'.$id);
			}
			
		}else {
			redirect('admin/timeout');
		}
	}
	
	function studentResetPassword($id){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Student Details Update';
			$data['menu'] = 'students';
			$data['sub_menu'] = null;
			
			$updateData = array('password'=>md5("BMSCW"),'updated_by'=>$data['username'], 'updated_at'=>date('Y-m-d H:i:s'));
                     
			$res = $this->data_model->updateDetails($id, $updateData, 'students');
			if($res) {
                    $this->session->set_flashdata('message', 'Student Password is reset successfully..!!');
                    $this->session->set_flashdata('status', 'alert-success');
            }else {
                    $this->session->set_flashdata('message', 'Oops..!! Something went wrong. Please try again later..!!');
                    $this->session->set_flashdata('status', 'alert-danger');
            }
            redirect('admin/student_details/'.$id);
            
		}else {
			redirect('admin/timeout');
		}
	}
	    
    function search(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
			
			$data['page_title'] = 'Principal and HOD List';	
			$data['menu'] = 'students';
			$data['sub_menu'] = null;
			
			$reg_no = trim($this->input->post("search"));
			
		    $data['romanYears'] = array(" " => "-") + $this->globals->romanYears();
			$data['hostelRoomTypes'] = array(" " => "Select") + $this->globals->hostelRoomTypes();
			$data['transportationRoutes'] = array(" " => "Select") + $this->globals->transportationRoutes();

			$data['transactionTypes'] = $this->globals->transactionTypes();
			$data['personal_details'] = $this->data_model->getDetailsbyfield('reg_no',$reg_no,'students')->row();
			if($data['personal_details']){
				$data['fee_details'] = $this->data_model->getDetailsbyfield('reg_no',$reg_no,'fees')->result();
				$data['transactions'] = $this->data_model->getDetailsbyfield('reg_no',$reg_no,'fee_transactions')->result();
			    $paid = $this->data_model->paidFees($reg_no)->result();
                $paidFees = array();
                foreach($paid as $paid1){
                    $paidFees[$paid1->year] = $paid1->paid_amount;
                }
                $data['paidFees'] = $paidFees;
				$this->admin_template->show('admin/student_details',$data);
			}else{
                $data['reg_no'] = $reg_no;
                $this->admin_template->show('admin/students_no_data',$data);
			}
			
		}else {
			redirect('admin/timeout');
		}
	}

	function editStudentFee($id){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Edit Student Fee Details';
			$data['menu'] = 'students';
			$data['sub_menu'] = null;
			
			$data['action'] = 'admin/editStudentFee/'.$id;
			$data['romanYears'] = array("" => "-") + $this->globals->romanYears();
			$data['hostelRoomTypes'] = array("" => "Select") + $this->globals->hostelRoomTypes();
			$data['hostelRoomFees'] = array("" => "Select") + $this->globals->hostelRoomFees();
			$data['transportationRoutes'] = array("" => "Select") + $this->globals->transportationRoutes();
			$data['transportationRouteFees'] = array("" => "Select") + $this->globals->transportationRouteFees();
			$data['feeDetails'] = $this->data_model->getDetails('fees',$id)->row();

			$this->form_validation->set_rules('fixed_fee', 'Fixed Fee', 'required');
            $this->form_validation->set_rules('additional_fee', 'Additional Fee', 'required');
            $this->form_validation->set_rules('concession_fee', 'Concession Fee', 'required');
            // $this->form_validation->set_rules('hostel_block', 'Hostel Block', 'required');
            $this->form_validation->set_rules('hostel_deposit_fee', 'Deposit Fee', 'required');
            $this->form_validation->set_rules('hostel_fee', 'Hostel Fee', 'required');
			// $this->form_validation->set_rules('transportation_route', 'Transportation Route', 'required');
            $this->form_validation->set_rules('transportation_fee', 'Transportation Fee', 'required');
 
		    if ($this->form_validation->run() === FALSE){
		        $this->admin_template->show('admin/update_student_fee_details',$data);
			}else{
			    
				$updateData = array();
			    $updateData['fixed_fee'] = $this->input->post('fixed_fee');
			    $updateData['additional_fee'] = $this->input->post('additional_fee');
			    $updateData['concession_fee'] = $this->input->post('concession_fee');
			    $updateData['net_fee'] = $this->input->post('net_fee');
				$updateData['installment_1_fee'] = $this->input->post('installment_1_fee');
				$updateData['installment_2_fee'] = $this->input->post('installment_2_fee');

			    $updateData['hostel_block'] = $this->input->post('hostel_block');
			    $updateData['hostel_deposit_fee'] = $this->input->post('hostel_deposit_fee');
			    $updateData['hostel_fee'] = $this->input->post('hostel_fee');
				$updateData['hostel_inst_1_fee'] = $this->input->post('hostel_inst_1_fee');
				$updateData['hostel_inst_2_fee'] = $this->input->post('hostel_inst_2_fee');

			    $updateData['transportation_route'] = $this->input->post('transportation_route');
			    $updateData['transportation_fee'] = $this->input->post('transportation_fee');
				$updateData['transportation_inst_1_fee'] = $this->input->post('transportation_inst_1_fee');
				$updateData['transportation_inst_2_fee'] = $this->input->post('transportation_inst_2_fee');
			    
                $updateData['updated_by'] = $data['username'];
				$updateData['updated_at'] = date('Y-m-d H:i:s');
                      
			    $res = $this->data_model->updateDetails($id, $updateData, 'fees');
			    if($res) {
                    $this->session->set_flashdata('message', 'Fee Details are updated successfully..!!');
                    $this->session->set_flashdata('status', 'alert-success');
                }else {
                    $this->session->set_flashdata('message', 'Oops..!! Something went wrong. Please try again later..!!');
                    $this->session->set_flashdata('status', 'alert-danger');
                }
                redirect('admin/student_details/'.$data['feeDetails']->reg_no);
			}
			
		}else {
			redirect('admin/timeout');
		}
	}

	function downloadReceipt($encryptPram){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
			
			$decryptPram = $this->encrypt->decode(base64_decode($encryptPram));
			$decryptPrams = explode(',',$decryptPram);

			$transacitonID = $decryptPrams[0];
			$reg_no = $decryptPrams[1];
 
			$studentDetails = $this->data_model->getDetailsbyfield('reg_no', $reg_no, 'students')->row();
			$transactionDetails = $this->data_model->getDetailsbyfield('id', $transacitonID, 'fee_transactions')->row();
			$feeDetails = $this->data_model->getStudentFee($reg_no, $transactionDetails->year)->row();
			$feeStructure = $this->data_model->feeStructure($feeDetails->academic_year, $feeDetails->course, $feeDetails->combination)->row();


			$this->load->library('fpdf'); // Load library
			
			ini_set("session.auto_start", 0);
			ini_set('memory_limit', '-1');
// 			define('FPDF_FONTPATH','plugins/font');
    	    $pdf = new FPDF('p','mm','A5');
            $pdf->enableheader = 0;
            $pdf->enablefooter = 0;
    	    $pdf->AddPage();
			
    	    // $pdf->Image(base_url().'assets/img/NCMS_RECEIPT.png', 0, 0, 148, 'PNG');
    	    $pdf->setDisplayMode('fullpage');
			 
			$row = 8;
			$rowHeight = 5;
			$pdf->SetTextColor(33,33,33);
			$pdf->setFont ('Arial','B',14);
            $pdf->SetXY(20, 40); 
            $pdf->Cell(0,10,"RECEIPT",0,0,'C', false);
            
            $y = $pdf->getY();
            $pdf->setFont ('Arial','B',8);
            $pdf->SetXY(10, $y+10); 
            $pdf->Cell(0,10,"Receipt No: ".$transactionDetails->receipt_no,0,0,'L', false);
            $pdf->SetXY(100, $y+10); 
            $pdf->Cell(0,10, "Date: ".date('d-m-Y', strtotime($transactionDetails->receipt_date)),0,0,'L', false); 

			$y = $pdf->getY();
            $pdf->setFont ('Arial','B',8);
            $pdf->SetXY(10, $y+$rowHeight); 
            $pdf->Cell(0,10,"Student Name : ".$studentDetails->student_name,0,0,'L', false);
            $pdf->SetXY(100, $y+$rowHeight); 
            $pdf->Cell(0,10, "REG/USN No.: ".$studentDetails->reg_no,0,0,'L', false); 

			$y = $pdf->getY();
            $pdf->setFont ('Arial','B',8);
            $pdf->SetXY(10, $y+$rowHeight); 
            $pdf->Cell(0,10,"Branch/Class : ".$feeDetails->course,0,0,'L', false);
            $pdf->SetXY(100, $y+$rowHeight); 
            $pdf->Cell(0,10, "SEM: ".$feeDetails->current_sem,0,0,'L', false); 

			$y = $pdf->getY();
            $pdf->setFont ('Arial','B',8);
            $pdf->SetXY(10, $y+$rowHeight); 
            $pdf->Cell(0,10,"Section : ".'',0,0,'L', false);
            $pdf->SetXY(100, $y+$rowHeight); 
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
			 
            if(($transactionDetails->fee_category == '1')){
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
		    $pdf->output($fileName,'i'); 
			
		}else {
			redirect('admin/timeout');
		}
	}
	
	function feeSearch(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
			
			$data['page_title'] = 'Principal and HOD List';	
			$data['menu'] = 'students';
			$data['sub_menu'] = null;
			
			$reg_no = trim($this->input->post("search"));
		    
		    $data['details'] = $this->data_model->getSelectDetails('id, reg_no, student_name, mobile, official_email, personal_email, aided_unaided, category','reg_no',$reg_no,'students')->row();
			if($data['details']){
			    $id = $data['details']->id;
			    $data['academics'] = $this->data_model->studentSemesters($id)->row();
                $data['fees'] = $this->data_model->getDetailsbyfield('reg_no', $reg_no, 'fees')->result();
                $paid = $this->data_model->paidFees($reg_no)->result();
                $paidFees = array();
                foreach($paid as $paid1){
                    $paidFees[$paid1->year] = $paid1->paid_amount;
                }
                $data['paidFees'] = $paidFees;
                $data['transactions'] = $this->data_model->studentTransactions($reg_no)->result();
                $data['transactionTypes'] = array(" " => "-") + $this->globals->transactionTypes();
                
                $data['action'] = 'admin/studentFeeDetails/'.$reg_no;
			}
			
		    // $academic_years = $this->academicYears();
			// $data['academic_years'] = $academic_years['dropdown'];
			// $data['ac_active'] = $academic_years['ac_active'];

			// $courses = $this->coursesList();
			// $courses_dropdown = array("all" => "All Courses");
			// $data['courses'] = $courses_dropdown + $courses;

			// $data['details'] = $this->data_model->getDetailsbyfield('reg_no', $reg_no, 'students')->row();
			// if($data['details']){
			//     $id = $data['details']->id;
			//     $data['academics'] = $this->data_model->studentSemesters($id)->result();	    
			// }
			
			$this->admin_template->show('admin/student_fees',$data); 
			
		}else {
			redirect('admin/timeout');
		}
	}
	
    function staff(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Accounts Staff';
			$data['menu'] = 'system';
			$data['sub_menu'] = "staff";
			
			$userRoles = $this->globals->userRoles();
			$details = $this->data_model->getAccountsStaff(null, 'staff')->result();
			
			$table_setup = array ('table_open'=> '<table class="table table-striped table-vcenter table-hover js-dataTable-full font-size-sm" id="js-dataTable-full">');
			$this->table->set_template($table_setup);
            $this->table->set_heading('#', '<i class="si si-user"></i>', 'Staff Name', 'Designation','Mobile','Email','Status');
    		// $this->table->set_heading(
    		// 						array('data' =>'S.No', 'style'=>'width:5%;'),
    		// 						array('data' =>'<i class="fa fa-user"></i>', 'style'=>'width:10%;'),
    		// 						array('data' =>'Staff Name','style'=>'width:20%;'),
    		// 						array('data' =>'Designation','style'=>'width:20%;'),
    		// 						array('data' =>'Department','style'=>'width:20%;'),
    		// 						array('data' =>'Official Email','style'=>'width:25%;')
    		// 		                );
    			$i=1;
    			foreach ($details as $details1){
    			    
    			    $img = base_url().'assets/img/avatar.jpg';
					$url = glob('./assets/staff_pics/profile'.$details1->id.'-*jpg');
					if ($url){
						if (file_exists($url[0])){
						    $img = base_url().$url[0];
					    }
					}
					
					if($details1->status){
					   $status = '<span class="font-w500 d-inline-block py-1 px-3 rounded-pill bg-success-light text-success">Active</span>';
					}else{
					    $status = '<span class="font-w500 d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger">Inactive</span>';
					}
						
    			    // $HOD = ($details1->isHOD) ? " & HOD" : null;
    			    // $Principal = ($details1->isPrincipal) ? "Principal & " : null;
    				$this->table->add_row($i++,
    				        '<img src="'.$img.'" alt="Profile" class="img-avatar img-avatar32">',
    						$details1->name,
    						$userRoles[$details1->user_type],
							$details1->mobile,
    						$details1->username,
    						array("data"=>$status,"class"=>"font-size-xs")
    						
    				);
    			}
    		
    		$data['table']=$this->table->generate();
    			
			$this->admin_template->show('admin/accounts_staff',$data);
		}else {
			redirect('admin/timeout');
		}
	}
	
	function staff_details($id){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Staff';
			$data['menu'] = 'staff';
			$data['sub_menu'] = null;
            
            $data['staffType'] = $this->globals->staffType();
            $data['designations'] = $this->globals->designations();
            
			$data['details'] = $this->data_model->getDetails('staff_details',$id)->row();

			$this->admin_template->show('admin/staff_details',$data);
		}else {
			redirect('admin/timeout');
		}
	}
	
	function add_staff(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Add New Staff';
			$data['menu'] = 'staff';
			$data['sub_menu'] = null;
    
            $data['action'] = 'admin/add_staff'; 
			
			$this->form_validation->set_rules('staff_name', 'Staff Name', 'required');
            // $this->form_validation->set_rules('employee_id', 'Employee ID', 'required');
            $this->form_validation->set_rules('department', 'Department', 'required');
            $this->form_validation->set_rules('designation', 'Designation', 'required');
            $this->form_validation->set_rules('course_type', 'Course Type', 'required');
            $this->form_validation->set_rules('staff_type', 'Staff Type', 'required');
            $this->form_validation->set_rules('mobile', 'Mobile', 'regex_match[/^[0-9]{10}$/]');
		    $this->form_validation->set_rules('official_mail', 'Official Email', 'required|valid_email');
    		if ($this->form_validation->run() === FALSE){
    		    
    		    $data['departments'] = array("" => "Select") + $this->globals->departments();        
		        $data['staffType'] = array("" => "Select") + $this->globals->staffType();
                $data['designations'] = array("" => "Select") + $this->globals->designations();
                
                $this->admin_template->show('admin/add_staff',$data);        
			}else{
			    $employee_id = $this->input->post('employee_id');
			    $staff_name = $this->input->post('staff_name');
			    $department = $this->input->post('department');
			    $designation = $this->input->post('designation');
			    $isHOD = ($this->input->post('isHOD')) ? $this->input->post('isHOD') : '0';
			    $isPrincipal = ($this->input->post('isPrincipal')) ? $this->input->post('isPrincipal') : '0';
			    $course_type = $this->input->post('course_type');
			    $staff_type = $this->input->post('staff_type');
			    $mobile = $this->input->post('mobile');
                $official_mail = $this->input->post('official_mail');
			    
			    $insertData = array("employee_id" =>$employee_id, 'staff_name' => $staff_name, 'department'=>$department, 'designation'=>$designation, 'isHOD'=>$isHOD, 'isPrincipal'=>$isPrincipal, 'course_type'=>$course_type, 'staff_type'=>$staff_type, 'mobile'=>$mobile,'official_mail'=>$official_mail, 'updated_by'=>$data['username'], 'updated_on'=>date('Y-m-d H:i:s'));
			    $res = $this->data_model->insertDetails('staff_details',$insertData);
			    if($res) {
                    $this->session->set_flashdata('message', 'Staff details are added successfully..!!');
                    $this->session->set_flashdata('status', 'alert-success');
                }else {
                    $this->session->set_flashdata('message', 'Oops..!! Something went wrong. Please try again later..!!');
                    $this->session->set_flashdata('status', 'alert-danger');
                } 
			    redirect('admin/staff');	    
			}
		}else {
			redirect('admin/timeout');
		}
	}
	
	function staffUpdate($id){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Staff';
			$data['menu'] = 'staff';
			$data['sub_menu'] = null;
			
			$data['action'] = 'admin/staffUpdate/'.$id;
			$data['details'] = $this->data_model->getDetails('staff_details',$id)->row();
            
            $this->form_validation->set_rules('staff_name', 'Staff Name', 'required');
            // $this->form_validation->set_rules('employee_id', 'Employee ID', 'required');
            $this->form_validation->set_rules('department', 'Department', 'required');
            $this->form_validation->set_rules('designation', 'Designation', 'required');
            $this->form_validation->set_rules('course_type', 'Course Type', 'required');
            $this->form_validation->set_rules('staff_type', 'Staff Type', 'required');
            $this->form_validation->set_rules('mobile', 'Mobile', 'regex_match[/^[0-9]{10}$/]');
		    $this->form_validation->set_rules('official_mail', 'Official Email', 'required|valid_email');
		    
		    if ($this->form_validation->run() === FALSE){
		        
		        $data['departments'] = array("" => "Select") + $this->globals->departments();        
		        $data['staffType'] = array("" => "Select") + $this->globals->staffType();
                $data['designations'] = array("" => "Select") + $this->globals->designations();
                
		        $this->admin_template->show('admin/update_staff_details',$data);
			}else{
			    
			    $employee_id = $this->input->post('employee_id');
			    $staff_name = $this->input->post('staff_name');
			    $department = $this->input->post('department');
			    $designation = $this->input->post('designation');
			    $isHOD = $this->input->post('isHOD');
			    $isPrincipal = $this->input->post('isPrincipal');
			    $course_type = $this->input->post('course_type');
			    $staff_type = $this->input->post('staff_type');
			    $mobile = $this->input->post('mobile');
                $official_mail = $this->input->post('official_mail');
                
                if($data['details']->official_mail == $official_mail){
                    $updateData = array("employee_id" =>$employee_id, 'staff_name' => $staff_name, 'department'=>$department, 'designation'=>$designation, 'isHOD'=>$isHOD, 'isPrincipal'=>$isPrincipal, 'course_type'=>$course_type, 'staff_type'=>$staff_type, 'mobile'=>$mobile, 'updated_by'=>$data['username'], 'updated_on'=>date('Y-m-d H:i:s'));
                }else{
                    $updateData = array("employee_id" =>$employee_id, 'staff_name' => $staff_name, 'department'=>$department, 'designation'=>$designation, 'isHOD'=>$isHOD, 'isPrincipal'=>$isPrincipal, 'course_type'=>$course_type, 'staff_type'=>$staff_type, 'mobile'=>$mobile,'official_mail'=>$official_mail, 'updated_by'=>$data['username'], 'updated_on'=>date('Y-m-d H:i:s'));
                }
			    
			    
			    $res = $this->data_model->updateDetails($id, $updateData, 'staff_details');
			    if($res) {
                    $this->session->set_flashdata('message', 'Details are updated successfully..!!');
                    $this->session->set_flashdata('status', 'alert-success');
                }else {
                    $this->session->set_flashdata('message', 'Oops..!! Something went wrong. Please try again later..!!');
                    $this->session->set_flashdata('status', 'alert-danger');
                }
                redirect('admin/staff_details/'.$id);
			}
			
		}else {
			redirect('admin/timeout');
		}
	}
	
	function staffResetPassword($id){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Staff';
			$data['menu'] = 'staff';
			$data['sub_menu'] = null;
			
			$updateData = array("password" =>md5("BMSCW"), 'updated_by'=>$data['username'], 'updated_on'=>date('Y-m-d H:i:s'));
			$res = $this->data_model->updateDetails($id, $updateData,'staff_details');
			
			if($res) {
                $this->session->set_flashdata('message', 'Password reset successfully..!!');
                $this->session->set_flashdata('status', 'alert-success');
            }else {
                $this->session->set_flashdata('message', 'Oops..!! Something went wrong. Please try again later..!!');
                $this->session->set_flashdata('status', 'alert-danger');
            }
            
            redirect('admin/staff_details/'.$id);
			
		}else {
			redirect('admin/timeout');
		}
	}
	
	function staffUpdateStatus($id, $status){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Staff';
			$data['menu'] = 'staff';
			$data['sub_menu'] = null;
			
			$updateData = array("status" =>$status, 'updated_by'=>$data['username'], 'updated_on'=>date('Y-m-d H:i:s'));
			$res = $this->data_model->updateDetails($id, $updateData,'staff_details');
			
			if($res) {
                $this->session->set_flashdata('message', 'Status updated successfully..!!');
                $this->session->set_flashdata('status', 'alert-success');
            }else {
                $this->session->set_flashdata('message', 'Oops..!! Something went wrong. Please try again later..!!');
                $this->session->set_flashdata('status', 'alert-danger');
            }
            
            redirect('admin/staff_details/'.$id);
			
		}else {
			redirect('admin/timeout');
		}
	}
	 
	function academic_years(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Academic Years';
			$data['menu'] = 'system';
			$data['sub_menu'] = 'academic_years';
						 
			$data['academic_years'] = $this->data_model->getDetails('academic_years',null)->result();

			$this->admin_template->show('admin/academic_years',$data);
		}else {
			redirect('admin/timeout');
		}
	}

	function courses(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Courses';
			$data['menu'] = 'system';
			$data['sub_menu'] = 'courses';
			
			$data['courses'] = $this->data_model->getCourses()->result();
			 
			$this->admin_template->show('admin/courses',$data);
		}else {
			redirect('admin/timeout');
		}
	}
	
	function feeStructure(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Fee Structure';
			$data['menu'] = 'feeStructure';
			$data['sub_menu'] = '';
			
			$data['fees'] = $this->data_model->getDetails('fee_structure', NULL)->result();
			 
		$this->admin_template->show('admin/fee_structure',$data);
		}else {
			redirect('admin/timeout');
		}
	}
	
	function academicYearsDropdown(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$details = $this->data_model->getDetails('academic_years',null)->result();
			$result = array();
		   	foreach($details as $key => $value){
		   	   $result[$value->academic_year] = $value->academic_year; 
		   	   if($value->status == 1)
		   	     $ac_active = $value->academic_year;
		   	}
			return $final_result = array('dropdown' => $result, 'ac_active' => $ac_active);
		}else {
			redirect('admin/timeout');
		}
	}

	function coursesList(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Courses';
			$data['menu'] = 'courses';
			$data['sub_menu'] = null;
			
			$courses = $this->data_model->getDetails('courses', null)->result();
			$coursesList = array();
			foreach($courses as $courses1){
				$combination = ($courses1->combination) ? ' - '.$courses1->combination : null;
				$coursesList[$courses1->course.$combination] = $courses1->course.$combination;
			}
			return $coursesList; 
		}else {
			redirect('admin/timeout');
		}
	}

	function getCourseYears(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$academic_year = $this->input->post('academic_year');
			$course_val = $this->input->post('course');
			$courseArray = explode('-',$course_val);
			
			$course = trim($courseArray[0]);
			if(array_key_exists("1",$courseArray)){
				$combination = trim($courseArray[1]);
			}else{
				$combination = "";
			}
			
			$flag = $this->input->post('flag');
			
			$details = $this->data_model->getCourseYears($academic_year, $course, $combination)->result();

			$result = array();
			
			if($flag == 'S')
    		    $result[] = '<option value=" ">Select</option>';
    	    
    	    if($flag == 'A')
    			$result[] = '<option value="all">All</option>';
    		
    		if($flag == 'AY')
    			$result[] = '<option value="all">All Years</option>';
    			
    		if($flag == 'SA'){
        		$result[] = '<option value=" ">Select</option>';
        	    $result[] = '<option value="all">All</option>';
        	}
        	
			foreach($details as $details1){
			  $result[] = '<option value="'.$details1->current_year.'">'.$details1->current_year.'</option>';
			}	
			
			print_r($result);

		}else {
			redirect('admin/timeout');
		}
	}

	function combinationsDropdown(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$academic_year = $this->input->post('academic_year');
			$course = $this->input->post('course');
			$flag = $this->input->post('flag');
			
			$combinations = $this->data_model->getCombinations($academic_year, $course)->result();

			$result = array();
			// $result[] = '<option value="all">All Combinations</option>';
			
			
			if($flag == 'S')
    		    $result[] = '<option value=" ">Select</option>';
    	    
    	    if($flag == 'A')
    			$result[] = '<option value="all">All</option>';
    		
    		if($flag == 'AC')
    			$result[] = '<option value="all">All Combinations</option>';
    			
    		if($flag == 'SA'){
        		$result[] = '<option value=" ">Select</option>';
        	    $result[] = '<option value="all">All</option>';
        	}
        	
    		if($flag == 'CA'){
        		$result[] = '<option value=" ">Choose Combination</option>';
        	    $result[] = '<option value="all">All Combinations</option>';
        	}
        	
			foreach($combinations as $combinations1){
			  $result[] = '<option value="'.$combinations1->id.'">'.$combinations1->combination_name.'</option>';
			}	
			
			print_r($result);

		}else {
			redirect('admin/timeout');
		}
	}
	
	function combinationsDropdown1(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$course_id = $this->input->post('course');
			$combination_id = $this->input->post('combination_id');
			
			$combinations = $this->data_model->getDetailsbyfield('course_id', $course_id,'combinations')->result();

			$result = array();

            $result[] = '<option value=" ">Select</option>';
    	    
			foreach($combinations as $combinations1){
			    if($combinations1->id == $combination_id){
		            $selected = "selected";
		        }else{
		            $selected = "";
		        }
			    $result[] = '<option value="'.$combinations1->id.'" '.$selected.'>'.$combinations1->combination_name.'</option>';
			}	
			
			print_r($result);

		}else {
			redirect('admin/timeout');
		}
	}
	
	function combinationsbyDept($course_id, $flag){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$combinations = $this->data_model->getDetailsbyfield('course_id', $course_id,'combinations')->result();

			$result = array();

			if($flag == 'S')
    		    $result[""] = "Select";
    	    
    	    if($flag == 'A')
    			$result["all"] = "All";
    		
    		if($flag == 'AC')
    			$result["all"] = "All Combinations";
    			
    		if($flag == 'CA'){
    		    $result[""] = "Choose Combination";
    		    $result["all"] = "All Combinations";
        	}
        	
			foreach($combinations as $combinations1){
			  $result[$combinations1->id] = $combinations1->combination_name;
			}	
			
			return $result;

		}else {
			redirect('admin/timeout');
		}
	}
	
	function reports(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
			
			$data['page_title'] = 'Reports';	
			$data['menu'] = 'reports';
			$data['sub_menu'] = null;

			$this->admin_template->show('admin/reports',$data);
			
		}else {
			redirect('admin/timeout');
		}
	}
	
	function students_statistics($download = null){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['menu'] = 'reports';
			$data['sub_menu'] = null;
			
			$academic_years = $this->academicYears();
			$ay_id = $academic_years['ac_active'];
			$current_ay = $academic_years['dropdown'][$ay_id];
			
			$data['page_title'] = 'Students Statistics ['.$current_ay.']';
			
			$students = $this->data_model->getStudentsCount($ay_id)->result();
			
			if($download){
                $table_setup = array ('table_open'=> '<table class="table table-striped table-vcenter table-hover js-dataTable-full font-size-sm"  border="1">');    
            }else{
                $table_setup = array ('table_open'=> '<table class="table table-striped table-vcenter table-hover">');
            }
			    $this->table->set_template($table_setup);
    			$this->table->set_heading(
    								array('data' =>'S.No', 'style'=>'width:5%;'),
    								array('data' =>'Course','style'=>'width:25%;'),
    								array('data' =>'Combination','style'=>'width:25%;'),
    								array('data' =>'Semester','style'=>'width:25%;'),
    								array('data' =>'Students Count','style'=>'width:20%;')
    				                );
    			$i=1; $total = 0;
    			foreach ($students as $students1){
    			    $total = $total + $students1->cnt;
    				$this->table->add_row($i++,
    				        $students1->course_name,
    						$students1->combination_name,
    						$students1->semester,
    						$students1->cnt
    				);
    			}
    		    $this->table->add_row('','','','<b>Total</b>','<b>'.$total.'</b>');
    		if($download){
			    $detailsTable = $this->table->generate();
			    header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=".$data['page_title'].".xls");
                header("Pragma: no-cache");
                header("Expires: 0"); 
                echo $detailsTable;
			}else{
			    $data['table']=$this->table->generate();
			    $this->admin_template->show('admin/students_statistics',$data);
			}

		}else {
			redirect('admin/timeout');
		}
	}
	
	 
	function students_report(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Students';
			$data['menu'] = 'students';
			$data['sub_menu'] = null;

			$academic_years = $this->academicYears();
			$data['academic_years'] = $academic_years['dropdown'];
			$data['ac_active'] = $academic_years['ac_active'];

			$courses = $this->coursesList();
			$courses_dropdown = array("all" => "All Courses");
			$data['courses'] = $courses_dropdown + $courses;

			$this->admin_template->show('admin/students_report',$data);
		}else {
			redirect('admin/timeout');
		}
	}
	
	function students_report_download(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Students';
			$data['menu'] = 'students';
			$data['sub_menu'] = null;

			$academic_year = $this->input->post('academic_year');
			$course_id = $this->input->post('course');
			$combination_id = $this->input->post('combination');
			$semester = $this->input->post('semester');
			$section = $this->input->post('section');
			
 			//$select = "id, academic_year, reg_no, course, combination, student_name, mobile, email, official_email";
			$select = "students.id, students.reg_no, academic_years.academic_year, students.course, students.combination, students.student_name, students.mobile, students.official_email, students.personal_email, courses.id as course_id, courses.course_name, courses.course_type, combinations.id as combination_id, combinations.combination_name, students_sections.semester, students_sections.section_id, sections.section";
			
			$table_headings = array('S.No', 'Academic Year', 'Reg. No', 'Course', 'Combination', 'Semester', 'Section', 'Student Name', 'Mobile', 'Email', 'Official Email');
			
			$selectedValues = $this->input->post('selectedValues');
			
			foreach($selectedValues as $selectedValues1){
			    
			    if($selectedValues1 == "admission_details"){
		            $select = $select.",students.admission_no, students.adm_date"; 
		            $table_headings[] = 'Admission No';
		            $table_headings[] = 'Admission Date';
			    }
			    
			    if($selectedValues1 == "date_place_of_birth"){
		            $select = $select.",students.date_of_birth, students.place_of_birth"; 
		            $table_headings[] = 'Date of Birth';
		            $table_headings[] = 'Place of Birth';
			    }
			    if($selectedValues1 == "caste_category"){
		            $select = $select.",students.caste, students.category"; 
		            $table_headings[] = 'Caste';
		            $table_headings[] = 'Category';
			    }
			    if($selectedValues1 == "nationality"){
		            $select = $select.",students.nationality"; 
		            $table_headings[] = 'Nationality';
			    }
			    if($selectedValues1 == "religion"){
		            $select = $select.",students.religion";
		            $table_headings[] = 'Religion';
			    }
			    if($selectedValues1 == "aadhar"){
		            $select = $select.",students.aadhar_card"; 
		            $table_headings[] = 'Aadhar';
			    }
			    
			    if($selectedValues1 == "present_address"){
		            $select = $select.",students.pre_location,students.pre_city,students.pre_district,students.pre_state,students.pre_pincode"; 
		            $table_headings[] = 'Present Location';
		            $table_headings[] = 'Present City';
		            $table_headings[] = 'Present District';
		            $table_headings[] = 'Present State';
		            $table_headings[] = 'Present Pincode';
			    }
			    
			    if($selectedValues1 == "permanent_address"){
		            $select = $select.",students.per_location,students.per_city,students.per_district,students.per_state,students.per_pincode"; 
		            $table_headings[] = 'Permanent Location';
		            $table_headings[] = 'Permanent City';
		            $table_headings[] = 'Permanent District';
		            $table_headings[] = 'Permanent State';
		            $table_headings[] = 'Permanent Pincode';
			    }
			    
			    if($selectedValues1 == "father_details"){
		            $select = $select.",students.father_name, students.father_occupation, students.father_qualification, students.father_phone, students.father_mobile, students.father_email, students.father_income"; 
		            $table_headings[] = 'Father Name';
		            $table_headings[] = 'Father Occupation';
		            $table_headings[] = 'Father Qualification';
		            $table_headings[] = 'Father Phone';
		            $table_headings[] = 'Father Mobile';
		            $table_headings[] = 'Father Email';
		            $table_headings[] = 'Father Annual Income';
			    }
			    
			    if($selectedValues1 == "mother_details"){
		            $select = $select.",students.mother_name, students.mother_occupation, students.mother_qualification, students.mother_phone, students.mother_mobile, students.mother_email, students.mother_income"; 
		            $table_headings[] = 'Mother Name';
		            $table_headings[] = 'Mother Occupation';
		            $table_headings[] = 'Mother Qualification';
		            $table_headings[] = 'Mother Phone';
		            $table_headings[] = 'Mother Mobile';
		            $table_headings[] = 'Mother Email';
		            $table_headings[] = 'Mother Annual Income';
			    }
			    
			    if($selectedValues1 == "guardian_details"){
		            $select = $select.",students.guardian_name, students.guardian_occupation, students.guardian_qualification, students.guardian_phone, students.guardian_mobile, students.guardian_email, students.guardian_income"; 
		            $table_headings[] = 'Guardian Name';
		            $table_headings[] = 'Guardian Occupation';
		            $table_headings[] = 'Guardian Qualification';
		            $table_headings[] = 'Guardian Phone';
		            $table_headings[] = 'Guardian Mobile';
		            $table_headings[] = 'Guardian Email';
		            $table_headings[] = 'Guardian Annual Income';
			    }
			    
			    if($selectedValues1 == "previous_exam_details"){
		            $select = $select.",students.inst_name, students.inst_address, students.exam_board, students.register_number, students.passed_year_month, students.max_marks, students.total_marks, students.percentage, students.class_obtained, students.subjects_studied_p1a, students.subjects_studied_p1b, students.subjects_studied_p2a, students.subjects_studied_p2b, students.subjects_studied_p2c, students.subjects_studied_p2d"; 
		            $table_headings[] = 'Name of the Institution';
		            $table_headings[] = 'Address of the Institution';
		            $table_headings[] = '12th Exam Board';
		            $table_headings[] = '12th Register Number';
		            $table_headings[] = 'Year & Month of Passing';
		            $table_headings[] = 'Max Marks';
		            $table_headings[] = 'Total marks secured';
		            $table_headings[] = 'Percentage(%)';
		            $table_headings[] = 'Class Obtained';
		            $table_headings[] = 'Subject Part-1 (A)';
		            $table_headings[] = 'Subject Part-1 (B)';
		            $table_headings[] = 'Subject Part-2 (A)';
		            $table_headings[] = 'Subject Part-2 (B)';
		            $table_headings[] = 'Subject Part-2 (C)';
		            $table_headings[] = 'Subject Part-2 (D)';
			    }
			    
			    if($selectedValues1 == "fee_details"){
		            $select = $select.",students.adm_year, students.category, students.aided_unaided, students.proposed_amount, students.additional_amount, students.concession_type, students.concession_fee, students.final_amount"; 
		            $table_headings[] = 'Admission Year';
		            $table_headings[] = 'Category';
		            $table_headings[] = 'Aided/UnAided';
		            $table_headings[] = 'Proposed Fee Amount';
		            $table_headings[] = 'Additional Amount (if any)';
		            $table_headings[] = 'Concession Type';
		            $table_headings[] = 'Concession Amount (if any)';
		            $table_headings[] = 'Finalised Amount';
			    }
			    
			    if($selectedValues1 == "other_details"){
		            $select = $select.",students.other_state,students.sports,students.ncc,students.nss"; 
		            $table_headings[] = 'Other State';
		            $table_headings[] = 'Sports';
		            $table_headings[] = 'NCC';
		            $table_headings[] = 'NSS';
			    }
			    
			} 
			
			$students = $this->data_model->studentReportDownload($select, $academic_year, $course_id, $combination_id, $semester, $section)->result();
			
			$table_setup = array ('table_open'=> '<table class="table table-bordered font14" border="1">');
			$this->table->set_template($table_setup);
			$this->table->set_heading($table_headings);
			
			$i = 1; $total = 0;
			foreach ($students as $students1){
				$result_array = array($i++, 
				                    $students1->academic_year,
				                    $students1->reg_no,
				                    $students1->course_name,
				                    $students1->combination_name,
				                    $students1->semester,
				                    $students1->section,
				                    $students1->student_name,
				                    $students1->mobile,
				                    $students1->personal_email,
				                    $students1->official_email
				                    );
                    
                foreach($selectedValues as $selectedValues1){
    			    if($selectedValues1 == "date_place_of_birth"){
    		            $result_array[] = $students1->date_of_birth;
    		            $result_array[] = $students1->place_of_birth;
    			    }
    			    if($selectedValues1 == "caste_category"){
    		            $result_array[] = $students1->caste;
    		            $result_array[] = $students1->category;
    			    }
    			    if($selectedValues1 == "nationality"){
    		            $result_array[] = $students1->nationality;
    			    }
    			    if($selectedValues1 == "religion"){
    		            $result_array[] = $students1->religion;
    			    }
    			    if($selectedValues1 == "aadhar"){
    		            $result_array[] = $students1->aadhar_card;
    			    }
    			    
    			    if($selectedValues1 == "present_address"){
    		            $result_array[] = $students1->pre_location;
    		            $result_array[] = $students1->pre_city;
    		            $result_array[] = $students1->pre_district;
    		            $result_array[] = $students1->pre_state;
    		            $result_array[] = $students1->pre_pincode;
    			    }
    			    
    			    if($selectedValues1 == "permanent_address"){
    		            $result_array[] = $students1->per_location;
    		            $result_array[] = $students1->per_city;
    		            $result_array[] = $students1->per_district;
    		            $result_array[] = $students1->per_state;
    		            $result_array[] = $students1->per_pincode;
    			    }
    			    
    			    if($selectedValues1 == "father_details"){
    		            $result_array[] = $students1->father_name;
    		            $result_array[] = $students1->father_occupation;
    		            $result_array[] = $students1->father_qualification;
    		            $result_array[] = $students1->father_phone;
    		            $result_array[] = $students1->father_mobile;
    		            $result_array[] = $students1->father_email;
    		            $result_array[] = $students1->father_income; 
    			    }
    			    
    			    if($selectedValues1 == "mother_details"){
    		            $result_array[] = $students1->mother_name;
    		            $result_array[] = $students1->mother_occupation;
    		            $result_array[] = $students1->mother_qualification;
    		            $result_array[] = $students1->mother_phone;
    		            $result_array[] = $students1->mother_mobile;
    		            $result_array[] = $students1->mother_email;
    		            $result_array[] = $students1->mother_income; 
    			    }
    			    
    			    if($selectedValues1 == "guardian_details"){
    		            $result_array[] = $students1->guardian_name;
    		            $result_array[] = $students1->guardian_occupation;
    		            $result_array[] = $students1->guardian_qualification;
    		            $result_array[] = $students1->guardian_phone;
    		            $result_array[] = $students1->guardian_mobile;
    		            $result_array[] = $students1->guardian_email;
    		            $result_array[] = $students1->guardian_income; 
    			    }
    			    
    			    if($selectedValues1 == "previous_exam_details"){
    		            $result_array[] = $students1->inst_name;
    		            $result_array[] = $students1->inst_address;
    		            $result_array[] = $students1->exam_board;
    		            $result_array[] = $students1->register_number;
    		            $result_array[] = $students1->passed_year_month;
    		            $result_array[] = $students1->max_marks;
    		            $result_array[] = $students1->total_marks;
    		            $result_array[] = $students1->percentage;
    		            $result_array[] = $students1->class_obtained;
    		            $result_array[] = $students1->subjects_studied_p1a;
    		            $result_array[] = $students1->subjects_studied_p1b;
    		            $result_array[] = $students1->subjects_studied_p2a;
    		            $result_array[] = $students1->subjects_studied_p2b;
    		            $result_array[] = $students1->subjects_studied_p2c;
    		            $result_array[] = $students1->subjects_studied_p2d; 
    			    }
    			    
    			    if($selectedValues1 == "fee_details"){
    			        $result_array[] = $students1->adm_year;
    		            $result_array[] = $students1->category;
    		            $result_array[] = $students1->aided_unaided;
    		            $result_array[] = $students1->proposed_amount;
    		            $result_array[] = $students1->additional_amount;
    		            $result_array[] = $students1->concession_type;
    		            $result_array[] = $students1->concession_fee; 
    		            $result_array[] = $students1->final_amount; 
    			    }
    			    
    			    if($selectedValues1 == "other_details"){
    		            $result_array[] = $students1->other_state;
    		            $result_array[] = $students1->sports;
    		            $result_array[] = $students1->ncc;
    		            $result_array[] = $students1->nss;
    			    }
    			    
    			}
    			
    			$this->table->add_row($result_array);    
			}
			
			$studentsTable = $this->table->generate();
    		
			$response =  array('op' => 'ok',
                 'file' => "data:application/vnd.ms-excel;base64,".base64_encode($studentsTable)
                 );

            die(json_encode($response));
			
		}else {
			redirect('admin/timeout');
		}
	}
	
 
	function student_fee_details(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Student Fee Details';
			$data['menu'] = 'reports';
			$data['sub_menu'] = null;
			
			$academic_years = $this->academicYears();
			$data['academic_years'] = $academic_years['dropdown'];
			$data['ac_active'] = $academic_years['ac_active'];

			$courses = $this->coursesList();
			$courses_dropdown = array("all" => "All Courses");
			$data['courses'] = $courses_dropdown + $courses;
			
			$this->admin_template->show('admin/student_fee_details',$data);
		}else {
			redirect('admin/timeout');
		}
	}
	
	function student_fee_report(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'Students';
			$data['menu'] = 'students';
			$data['sub_menu'] = null;

			$academic_year = $this->input->post('academic_year');
			$course_id = $this->input->post('course');
			$combination_id = $this->input->post('combination');
			$semester = $this->input->post('semester');
			$section = $this->input->post('section');
			
 			// $select = "id, academic_year, reg_no, course, combination, student_name, mobile, email, official_email";
			$select = "students.id, students.reg_no, academic_years.academic_year, students.course, students.combination, students.student_name, students.mobile, students.official_email, students.personal_email, courses.id as course_id, courses.course_name, courses.course_type, combinations.id as combination_id, combinations.combination_name, students_sections.semester, students_sections.section_id, sections.section";
			
			$table_headings = array('S.No', 'Academic Year', 'Reg. No', 'Course', 'Combination', 'Semester', 'Section', 'Student Name', 'Mobile', 'Email', 'Official Email', 'Year', 'Proposed Fee Amount', 'Additional Amount', 'Concession Type','Concession Amount','Finalised Amount');
			
			$students = $this->data_model->studentReportDownload($select, $academic_year, $course_id, $combination_id, $semester, $section)->result();
			
			$table_setup = array ('table_open'=> '<table class="table table-bordered font14" border="1">');
			$this->table->set_template($table_setup);
			$this->table->set_heading($table_headings);
			
			$i = 1; $total = 0;
			foreach ($students as $students1){
			    $fees = $this->data_model->getDetailsbyfield('reg_no', $students1->reg_no, 'fees')->row();
			    if($fees){
			        $fee_year = $fees->fee_year;
			        $proposed_fee_amount = $fees->proposed_fee_amount;
                    $additional_amount = $fees->additional_amount;
				    $concession_type = $fees->concession_type;
				    $concession_amount = $fees->concession_amount;
				    $finalised_amount = $fees->finalised_amount;
			    }else{
			        $fee_year = 0;
			        $proposed_fee_amount = 0;
			        $additional_amount = 0;
			        $concession_type = 0;
			        $concession_amount = 0;
			        $finalised_amount = 0;
			    }
				$result_array = array($i++, 
				                    $students1->academic_year,
				                    $students1->reg_no,
				                    $students1->course_name,
				                    $students1->combination_name,
				                    $students1->semester,
				                    $students1->section,
				                    $students1->student_name,
				                    $students1->mobile,
				                    $students1->personal_email,
				                    $students1->official_email,
				                    $fee_year,
				                    $proposed_fee_amount,
                			        $additional_amount,
                			        $concession_type,
                			        $concession_amount,
                			        $finalised_amount
				                );
                    
    			$this->table->add_row($result_array);    
			}
			
			$studentsTable = $this->table->generate();
    		
			$response =  array('op' => 'ok',
                 'file' => "data:application/vnd.ms-excel;base64,".base64_encode($studentsTable)
                 );

            die(json_encode($response));
			
		}else {
			redirect('admin/timeout');
		}
	}
	
	function getStudentsFeeDetails(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
			
			$reg_no = $this->input->post('reg_no');
			
			$res = $this->data_model->getSelectDetails('count(id) as cnt','reg_no',$reg_no,'students')->row()->cnt;
			
			print_r($res);
			

		}else {
			redirect('admin/timeout');
		}
	}
	
	function getStudentsFeeDetailsbyID(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
			
			$id = $this->input->post('id');
			
			$fees = $this->data_model->getDetailsbyfield('id', $id, 'fees')->row();
			
			print_r(json_encode($fees));

		}else {
			redirect('admin/timeout');
		}
	}

	function report1(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
			
			$data['page_title'] = 'Fee Collection Report';	
			$data['menu'] = 'reports';
			$data['sub_menu'] = "report1";

			$this->admin_template->show('admin/report1',$data);
			
		}else {
			redirect('admin/timeout');
		}
	}

	function report1Download(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
			
			$data['page_title'] = 'Fee Collection Report';	
			$data['menu'] = 'reports';
			$data['sub_menu'] = "report1";

			$from_date = $this->input->post('from_date')." 00:00:00";
			$to_date = $this->input->post('to_date')." 23:59:59";

			$discriminatorValues = $this->globals->discriminatorValues();
			$transactionTypes = $this->globals->transactionTypes();
			$feeCategory = $this->globals->feeCategory();

			// $table_headings = array('S.No', 'Date', 'Student Name','Mobile','Class', 'Aided/Un-Aided','Category','Receipt No.', 'Bank Name', 'DD / Cheque / Challan No. & Date', 'Paid (Rs.)', 'Balance (Rs.)');
			$details = $this->data_model->report1($from_date, $to_date)->result();
			 
			$table_setup = array ('table_open'=> '<table class="table table-bordered font14" border="1">');
			$table_headings = array('S.No', 'Date', 'Receipt No', 'Reg No.', 'Student Name', 'Fee Category', 'Payment Type', 'Ref. Details', 'Ref. ID', 'Amount (Rs.)');
			$this->table->set_template($table_setup);
			$this->table->set_heading($table_headings);

			$i = 1;
			foreach($details as $details1){
				$row = array($i++, 
						date('d-m-Y', strtotime($details1->receipt_date)), 
						$details1->receipt_no,
						$details1->reg_no, 
						$details1->student_name, 
						$feeCategory[$details1->fee_category],
						$transactionTypes[$details1->mode_of_payment],
						$discriminatorValues[$details1->discriminator].' - '.$details1->bank_branch,
						$details1->ref_number,
						number_format($details1->amount,0)
					);
				$this->table->add_row($row);
			}

			$detailsTable = $this->table->generate();
			 
			$response =  array('op' => 'ok',
                 'file' => "data:application/vnd.ms-excel;base64,".base64_encode($detailsTable)
                 );
            die(json_encode($response));

		}else {
			redirect('admin/timeout');
		}
	}
	
	function report2(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
			
			$data['page_title'] = 'Headwise Fee Collection Report';	
			$data['menu'] = 'reports';
			$data['sub_menu'] = "report1";

			$this->admin_template->show('admin/report2',$data);
			
		}else {
			redirect('admin/timeout');
		}
	}

	function report2Download(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
			
			$data['page_title'] = 'Fee Collection Report';	
			$data['menu'] = 'reports';
			$data['sub_menu'] = "report1";

			$from_date = $this->input->post('from_date')." 00:00:00";
			$to_date = $this->input->post('to_date')." 23:59:59";

			$discriminatorValues = $this->globals->discriminatorValues();
			$transactionTypes = $this->globals->transactionTypes();
			$feeCategory = $this->globals->feeCategory();

			// $table_headings = array('S.No', 'Date', 'Student Name','Mobile','Class', 'Aided/Un-Aided','Category','Receipt No.', 'Bank Name', 'DD / Cheque / Challan No. & Date', 'Paid (Rs.)', 'Balance (Rs.)');
			$details = $this->data_model->report1($from_date, $to_date)->result();

			$table_setup = array ('table_open'=> '<table class="table table-bordered font14" border="1">');
			$table_headings = array('S.No', 'Date', 'Receipt No', 'Reg No.', 'Student Name', 'Fee Category', 'Payment Type', 'Ref. Details', 'Ref. ID', 'Admin Fee', 'Other Fee', 'Tuition Fee', 'Hostel Caution Deposit Fee','Hostel Fee','Transportation Fee' ,'Amount (Rs.)');
			$this->table->set_template($table_setup);
			$this->table->set_heading($table_headings);

			$i = 1; 
			foreach($details as $details1){ 
				
				$feeDetails = $this->data_model->getStudentFee($details1->reg_no, $details1->year)->row();
				$feeStructure = $this->data_model->feeStructure($feeDetails->academic_year, $feeDetails->course, $feeDetails->combination)->row();

				$admin_fee = $other_fee = $tuition_fee = $hostel_deposit_fee = $hostel_fee = $transportation_fee = 0;
				
				if($details1->fee_category == '1'){
					$displayFee = array();
					$already_paid = $details1->already_paid;
					$current_paid = $details1->amount;
					$amount = $details1->amount;

					$feeStr = array(
						'admin_fee' => $feeStructure->admin_fee,
						'other_fee' => $feeStructure->other_fee,
						'tuition_fee' => $feeStructure->tuition_fee
					);

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
					foreach($feeStr as $key => $value){
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
					  }

					//   print_r($displayFee);
					  $admin_fee = array_key_exists('admin_fee',$displayFee) ? $displayFee['admin_fee'] : '0';
					  $other_fee = array_key_exists('other_fee',$displayFee) ? $displayFee['other_fee'] : '0';
					  $tuition_fee = array_key_exists('tuition_fee',$displayFee) ? $displayFee['tuition_fee'] : '0';
				}

				if($details1->fee_category == '2'){
					if($details1->fee_type == '1'){
						$hostel_deposit_fee = $feeDetails->hostel_deposit_fee;
						$hostel_fee = ($details1->amount - $feeDetails->hostel_deposit_fee);
					}else{
						$hostel_fee = $details1->amount;
					}	
				}

				if($details1->fee_category == '3'){
					$transportation_fee = $details1->amount;
				}
				$row = array($i++, 
						date('d-m-Y', strtotime($details1->receipt_date)), 
						$details1->receipt_no,
						$details1->reg_no, 
						$details1->student_name, 
						$feeCategory[$details1->fee_category],
						$transactionTypes[$details1->mode_of_payment],
						$discriminatorValues[$details1->discriminator].' - '.$details1->bank_branch,
						$details1->ref_number,
						number_format($admin_fee,0),
						number_format($other_fee,0),
						number_format($tuition_fee,0),
						number_format($hostel_deposit_fee,0),
						number_format($hostel_fee,0),
						number_format($transportation_fee,0),
						number_format($details1->amount,0)
					);
				$this->table->add_row($row);
			}

			$detailsTable = $this->table->generate();

			$response =  array('op' => 'ok',
                 'file' => "data:application/vnd.ms-excel;base64,".base64_encode($detailsTable)
                 );
            die(json_encode($response));

		}else {
			redirect('admin/timeout');
		}
	}

	function AdmissionScrollReport($download = 0){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'ADMISSION SCROLL REPORT';
			$data['menu'] = 'reports';
			
			$academic_years = $this->academicYears();
			$data['academic_years'] = $academic_years['dropdown'];
			$data['ac_active'] = $academic_years['ac_active'];
            
            $data['download_action'] = 'admin/AdmissionScrollReport/1';
            
            $transactions = $this->data_model->transactions('1')->result();
            $transactionTypes = $this->globals->transactionTypes();
            
			if($download){
			    $table = "<table class='table table-bordered' border='1' id='dataTable' >";
			}else{
			    $table = "<table class='table table-bordered font14' border='1' id='dataTable' >";
			}
			$table .= '<thead>';
			if($download){
			    $table .= '<tr><th colspan="11" class="font20">ADMISSION SCROLL</th></tr>';
			}
			$table .= '<tr><th>S.No</th>
			               <th> Student Name </th>
			               <th> Course </th>
			               <th> Combination </th>
			               <th> Receipt No. </th>
			               <th> Date </th>
			               <th> Mode of Payment </th>
			               <th> Reference No. </th>
			               <th> Reference Date </th>
			               <th> Bank Name </th>
			               <th> Amount </th>
			          </tr>';
			
			$table .= '</thead>';
    	    $table .= '<tbody>';
    	    $i = 1;
    		foreach($transactions as $transactions1){
    		 $table .= '<tr>'; 
    		 $table .= '<td>'.$i++.'</td>';   
    		 $table .= '<td>'.$transactions1->student_name.'</td>';   
    		 $table .= '<td>'.$transactions1->course_name.'</td>';   
    		 $table .= '<td>'.$transactions1->combination_name.'</td>';   
    		 $table .= '<td>'.$transactions1->receipt_no.'</td>';   
    		 $table .= '<td>'.date('d-m-Y', strtotime($transactions1->transaciton_date)).'</td>';   
    		 $table .= '<td>'.$transactionTypes[$transactions1->transaction_type].'</td>';   
    		 $table .= '<td>'.$transactions1->reference_no.'</td>';   
    		 $table .= '<td>'.date('d-m-Y', strtotime($transactions1->reference_date)).'</td>';   
    		 $table .= '<td>'.$transactions1->bank_name.'</td>';   
    		 $table .= '<td>'.number_format($transactions1->amount,0).'</td>';   
    		 $table .= '</tr>';
    		}
    		$table .= '</tbody>';
    		$table .= '</table>';
    		$data['table'] = $table;
    		
    		// $data['table'] = $table;
 			// if(!$download){
 			//     $this->admin_template->show('admin/courseReport',$data);    
 			// }else{
			    $file_name = $data['page_title'].".xls";
    			header('Content-Disposition: attachment; filename='.$file_name.'');
		        header('Content-type: application/force-download');
			    header('Content-Transfer-Encoding: binary');
			    header('Pragma: public');
			    print "\xEF\xBB\xBF"; // UTF-8 BOM
			    echo $table;
			// }
			
		}else {
			redirect('admin/timeout');
		}
	}
	
	function DayBookReport(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'DAY BOOK REPORT';
			$data['menu'] = 'reports';
			
            $this->admin_template->show('admin/dayBookReport',$data);
		
		}else {
			redirect('admin/timeout');
		}
	}
	
	function dayBookReportDownload(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$data['page_title'] = 'DAY BOOK REPORT';
			$data['menu'] = 'reports';
			
			$data['feeTypes'] = $this->globals->feeTypes();
			
			$table_headings = array('S.No', 'Date', 'Student Name','Mobile','Class', 'Aided/Un-Aided','Category','Receipt No.', 'Bank Name', 'DD / Cheque / Challan No. & Date', 'Paid (Rs.)', 'Balance (Rs.)');

			array_splice($table_headings,count($table_headings),0,$data['feeTypes']); 
			
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');
			
			$details = $this->data_model->dayBookReport($from_date, $to_date)->result();
			// echo "<pre>"; print_r($details); die;
			$table_setup = array ('table_open'=> '<table class="table table-bordered font14" border="1">');
			$this->table->set_template($table_setup);
			$this->table->set_heading($table_headings);
			
			$i = 1;
			foreach($details as $details1){
			    
			    if($details1->category == "GM" || $details1->category == "2A" || $details1->category == "2B" || $details1->category == "3A" || $details1->category == "3B"){
    			    $category = "1";
    			}else{
    			    $category = "2";
    			}
    			
    			$fee = $this->data_model->feeStructure($details1->course, $details1->combination, $category, $details1->aided_unaided)->row();
    			
			    $result_array = array($i++, 
			                           date('d-m-Y', strtotime($details1->transaciton_date)), 
			                           $details1->student_name, 
			                           $details1->mobile,
			                           $details1->course.' - '.$details1->combination,
			                           $details1->aided_unaided,
			                           $details1->category.' '.$category,
			                           $details1->receipt_no,
			                           $details1->bank_name,
			                           $details1->reference_no.' '.$details1->reference_date,
			                         //  $details1->final_amount,
			                         //  $details1->paid_amount,
			                           $details1->amount,
			                           $details1->balance_amount
			                         );
			    $already_paid = $details1->paid_amount;
			    $paid_amount = $details1->amount;
			    if($fee){
			        $balance_amount = 0; $test = 0; $balance_amount1 = 0;
			        for($f=1; $f<=32; $f++){
        		       $field = "field_".$f;
        		      // PREVISOULY PAID AMOUNT - LESS
        		        if($already_paid > 0){
        		           $fee_amount = $fee->$field;
        		          // $balance_amount = 0;
        		           $balance_amount = $already_paid - $fee->$field;
        		           if($balance_amount > 0){
        		               $display_amount = 0;
        		           }else{
        		               $feeField = -($balance_amount);
        		               
        		               // PAID AMT
        		               if($paid_amount < 0){
            		             $display_amount = 0;
            		           }else{
                		          $balance_amount1 = $paid_amount - $feeField;
                    		       if($balance_amount1 < 0){
                    		        $display_amount = $paid_amount;
                    		       }else{
                    		        $display_amount = $feeField;    
                    		       }
                    		       $paid_amount = $balance_amount1;    
                		       }
                		       // PAID AMT
        		               
        		           }
        		           $already_paid = $balance_amount;
        		           
        		      // PREVISOULY PAID AMOUNT - LESS
        		        }else{
        		            // NOW PAID AMOUNT - ADD
            		       if($paid_amount < 0){
            		          //   $fee_amount = "0<br>".$fee->$field."<br>".$balance_amount;    
            		             $display_amount = 0;
            		       }else{
                		       $balance_amount = $paid_amount - $fee->$field;
                		      // $fee_amount = $fee->$field;
                		      // $fee_amount = $paid_amount."<br>".$fee->$field."<br>".$balance_amount;
                		       if($balance_amount < 0){
                		        $display_amount = $paid_amount;
                		       }else{
                		        $display_amount = $fee->$field;    
                		       }
                		       $paid_amount = $balance_amount;    
            		       }
            		       // NOW PAID AMOUNT
        		        }
        		      
        		       $fee_amount = $fee->$field.'<br>'.$display_amount."<br>".$balance_amount;
        		       $fee_amount = $display_amount;
        		       $test = $test + $display_amount;
        		       array_push($result_array, $fee_amount);
        		   }    
			    }
			    $this->table->add_row($result_array);
			}
			
			$detailsTable = $this->table->generate();
			 
			$response =  array('op' => 'ok',
                 'file' => "data:application/vnd.ms-excel;base64,".base64_encode($detailsTable)
                 );

            die(json_encode($response));
			
		
		}else {
			redirect('admin/timeout');
		}
	}
	
	function DCBReport($download = 0){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
				
			$currentAcademicYear = $this->globals->currentAcademicYear();
			 
			$data['page_title'] = $currentAcademicYear.' REPORT DEMAND COLLECTION BALANCE (DCB)';
			$data['menu'] = 'reports';
			
			$data['download_action'] = 'admin/DCBReport/1';
			$students = $this->data_model->DCBReport($currentAcademicYear)->result();
			
			$fees = $this->data_model->feeDetails()->result();
			$feeDetails = array();
			foreach($fees as $fees1){
			    $feeDetails[$fees1->reg_no] = $fees1->paid_amount;
			}
			$table_setup = array ('table_open'=> '<table class="table table-bordered font14" border="1" id="dataTable" >');
			$this->table->set_template($table_setup);
			
			$print_fields = array('S.No', 'Reg.No', 'Student Name', 'Course', 'Combination', 'Mobile', 'Year','Finalised Amount','Paid Amount','Balance Amount');
			$this->table->set_heading($print_fields);
			
			$i = 1; $total_final_amount = 0; $total_paid_amount = 0; $total_balance_amount = 0;
			foreach($students as $students1){
			    $finalised_amount = (int)$students1->finalised_amount;
			    $paid_amount = (array_key_exists($students1->reg_no,$feeDetails)) ? $feeDetails[$students1->reg_no] : '0';
			    $balance_amount = $finalised_amount - (int)$paid_amount;    
			    
			    $result_array = array($i++, 
				                    $students1->reg_no,
				                    $students1->student_name,
				                    $students1->course,
				                    $students1->combination,
				                    $students1->mobile,
				                    $students1->fee_year,
				                    // ($students1->adm_date != "0000-00-00") ? date('d-m-Y', strtotime($admissions1->adm_date)) : '',
				                    number_format($finalised_amount,0),
				                    number_format($paid_amount,0),
				                    number_format($balance_amount,0)
				                    );   
				$this->table->add_row($result_array);    
				$total_final_amount = $total_final_amount + $finalised_amount;
				$total_paid_amount =  $total_paid_amount + $paid_amount;
				$total_balance_amount =  $total_balance_amount + $balance_amount;
			}
			$data['table'] = $this->table->generate();
			if(!$download){
			    $this->admin_template->show('admin/DCBReport',$data);
			}else{
			    $file_name = $data['page_title'].".xls";
    			header('Content-Disposition: attachment; filename='.$file_name.'');
		        header('Content-type: application/force-download');
			    header('Content-Transfer-Encoding: binary');
			    header('Pragma: public');
			    print "\xEF\xBB\xBF"; // UTF-8 BOM
			    echo $data['table'];
			}
			
            
		
		}else {
			redirect('admin/timeout');
		}
	}
	
	function studentFeeDetails($reg_no){
	   if($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
			
			$data['page_title'] = 'Students Fees';
			$data['menu'] = 'fees';
			
			$data['action'] = 'admin/studentFeeDetails/'.$reg_no;
			
			$data['details'] = $this->data_model->getSelectDetails('id, reg_no, student_name, mobile, official_email, personal_email, aided_unaided, category','reg_no',$reg_no,'students')->row();
			if($data['details']){
			    $id = $data['details']->id;
			    $data['academics'] = $this->data_model->studentSemesters($id)->row();
                $data['fees'] = $this->data_model->getDetailsbyfield('reg_no', $reg_no, 'fees')->result();
                $Fees = array();
                foreach($data['fees'] as $fees1){
                    $Fees[$fees1->fee_year] = $fees1->finalised_amount;
                }
                
                $paid = $this->data_model->paidFees($reg_no)->result();
                $paidFees = array();
                if($paid){
                    foreach($paid as $paid1){
                        $paidFees[$paid1->year] = $paid1->paid_amount;
                    }    
                }
                $data['paidFees'] = $paidFees;
                $data['transactions'] = $this->data_model->studentTransactions($reg_no)->result();
                $data['transactionTypes'] = array(" " => "-") + $this->globals->transactionTypes();
			}
			
			$this->form_validation->set_rules('mode_of_payment', 'Mode of Payment', 'required');
	        if($this->form_validation->run() === FALSE){
	            
	            $this->admin_template->show('admin/student_fees',$data);
	        }else{
	            
	            $mode_of_payment = $this->input->post('mode_of_payment');
	            
	            if($data['details']->aided_unaided == "Aided"){
	                $tag = "BMSCW-A-";   
	            }else{
	                $tag = "BMSCW-UA-";
	            }
	            
	            $receipt_no = null;
	            $cnt = $this->data_model->getReceiptsCount($data['details']->aided_unaided)->row()->cnt;
        	    $cnt_number = $cnt +1;
                $strlen = strlen(($cnt_number));
                if($strlen == 1){  $cnt_number = "000".$cnt_number; }
                if($strlen == 2){  $cnt_number = "00".$cnt_number; }
                if($strlen == 3){  $cnt_number = "0".$cnt_number; }
        	    $receipt_no = $tag.$cnt_number;
	             
	            if($mode_of_payment == "Cash"){
	                
	                $year = $this->input->post('cash_year');
	                $final_amount = array_key_exists($year, $Fees) ? $Fees[$year] : 0;
	                $paid_amount = array_key_exists($year, $paidFees) ? $paidFees[$year] : 0;
	                $current_balance_amount = $final_amount - $paid_amount;    
	                
	                $transactionDetails = array('admissions_id' => $data['details']->id,
	                                       'reg_no' => $data['details']->reg_no,
	                                       'year' => $year,
	                                       'mobile' => $data['details']->mobile,
	                                       'aided_unaided' => $data['details']->aided_unaided,
	                                       'receipt_no' => $receipt_no,
	                                       'transaciton_date' => date('Y-m-d'),
	                                       'transaction_type' => '1',
	                                       'bank_name' => '',
	                                       'reference_no' => '',
	                                       'reference_date' => date('Y-m-d', strtotime($this->input->post('cash_date'))),
	                                       'paid_amount' => $this->input->post('paid_amount'),
	                                       'amount' => $this->input->post('cash_amount'),
	                                       'balance_amount' => $current_balance_amount - $this->input->post('cash_amount'),
	                                       'remarks' => '',
	                                       'transaction_status' => '1',
	                                       'created_by' => $data['name'],
	                                       'created_on' => date('Y-m-d h:i:s')
	                                    );    
	            }
	            if($mode_of_payment == "ChequeDD"){
	                
	                $year = $this->input->post('cheque_year');
	                $final_amount = array_key_exists($year, $Fees) ? $Fees[$year] : 0;
	                $paid_amount = array_key_exists($year, $paidFees) ? $paidFees[$year] : 0;
	                $current_balance_amount = $final_amount - $paid_amount;    
	                
	                $transactionDetails = array('admissions_id' => $data['details']->id,
	                                       'reg_no' => $data['details']->reg_no,
	                                       'year' => $year,
	                                       'mobile' => $data['details']->mobile,
	                                       'aided_unaided' => $data['details']->aided_unaided,
	                                       'receipt_no' => '',
	                                       'transaciton_date' => '',
	                                       'transaction_type' => '2',
	                                       'bank_name' => $this->input->post('cheque_dd_bank'),
	                                       'reference_no' => $this->input->post('cheque_dd_number'),
	                                       'reference_date' => date('Y-m-d', strtotime($this->input->post('cheque_dd_date'))),
	                                       'paid_amount' => $this->input->post('paid_amount'),
	                                       'amount' => $this->input->post('cheque_dd_amount'),
	                                       'balance_amount' => $current_balance_amount - $this->input->post('cheque_dd_amount'),
	                                       'remarks' => '',
	                                       'transaction_status' => '0',
	                                       'created_by' => $data['name'],
	                                       'created_on' => date('Y-m-d h:i:s')
	                                    );    
	            }
	            if($mode_of_payment == "OnlinePayment"){
	                
	                $year = $this->input->post('online_year');
	                $final_amount = array_key_exists($year, $Fees) ? $Fees[$year] : 0;
	                $paid_amount = array_key_exists($year, $paidFees) ? $paidFees[$year] : 0;
	                $current_balance_amount = $final_amount - $paid_amount;    
	                
	                $transactionDetails = array('admissions_id' => $data['details']->id,
	                                       'reg_no' => $data['details']->reg_no,
	                                       'year' => $year,
	                                       'mobile' => $data['details']->mobile,
	                                       'aided_unaided' => $data['details']->aided_unaided,
	                                       'receipt_no' => $receipt_no,
	                                       'transaciton_date' => date('Y-m-d'),
	                                       'transaction_type' => '3',
	                                       'bank_name' => '',
	                                       'reference_no' => $this->input->post('transaction_id'),
	                                       'reference_date' => date('Y-m-d', strtotime($this->input->post('transaction_date'))),
	                                       'paid_amount' => $this->input->post('paid_amount'),
	                                       'amount' => $this->input->post('transaction_amount'),
	                                       'balance_amount' => $current_balance_amount - $this->input->post('transaction_amount'),
	                                       'remarks' => '',
	                                       'transaction_status' => '1',
	                                       'created_by' => $data['name'],
	                                       'created_on' => date('Y-m-d h:i:s')
	                                    );    
	            }
	            
	            $result = $this->data_model->insertDetails('transactions', $transactionDetails);
	            
	            if($result){
    	           $this->session->set_flashdata('message', 'Fee Payment details udpated successfully...!');
    	           $this->session->set_flashdata('status', 'alert-success');
    	        }else{
    	           $this->session->set_flashdata('message', 'Oops something went wrong please try again.!');
    	           $this->session->set_flashdata('status', 'alert-warning');
    	        }  
	            redirect('admin/studentFeeDetails/'.$reg_no, 'refresh');
	        }

		}else {
			redirect('admin/timeout');
		}
	}
	
	function approvePayment($transaction_id){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
			
			$transactionDetails = $this->data_model->getDetailsbyfield('id', $transaction_id, 'transactions')->row();
			
			if($transactionDetails->aided_unaided == "Aided"){
	            $tag = "BMSCW-A-";   
	        }else{
	            $tag = "BMSCW-UA-";
	        }
	            $receipt_no = null;
	            $cnt = $this->data_model->getReceiptsCount($transactionDetails->aided_unaided)->row()->cnt;
        	    $cnt_number = $cnt +1;
                $strlen = strlen(($cnt_number));
                if($strlen == 1){  $cnt_number = "000".$cnt_number; }
                if($strlen == 2){  $cnt_number = "00".$cnt_number; }
                if($strlen == 3){  $cnt_number = "0".$cnt_number; }
        	    $receipt_no = $tag.$cnt_number;
	            
	            $updateDetails = array('receipt_no' => $receipt_no,
	                                       'transaciton_date' => date('Y-m-d'),
	                                       'transaction_status' => '1',
	                                       'created_by' => $data['name'],
	                                       'created_on' => date('Y-m-d h:i:s')
	                                    );    
	            
	            $result = $this->data_model->updateDetails($transaction_id, $updateDetails,'transactions');
	            
	            if($result){
    	           $this->session->set_flashdata('message', 'Fee Payment details udpated successfully...!');
    	           $this->session->set_flashdata('status', 'alert-success');
    	        }else{
    	           $this->session->set_flashdata('message', 'Oops something went wrong please try again.!');
    	           $this->session->set_flashdata('status', 'alert-warning');
    	        }  
	            
	            redirect('admin/studentFeeDetails/'.$transactionDetails->reg_no, 'refresh');
	            
		}else {
			redirect('admin/timeout');
		}
	}
	
	function deletePayment($transaction_id, $reg_no){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
			
			$details = $this->data_model->delDetails('transactions', $transaction_id);
			
			if($details){
    	       $this->session->set_flashdata('message', 'Transaction details are deleted successfully...!');
    	       $this->session->set_flashdata('status', 'alert-success');
    	    }else{
    	       $this->session->set_flashdata('message', 'Oops something went wrong please try again.!');
    	       $this->session->set_flashdata('status', 'alert-warning');
    	    }  
	            
	        redirect('admin/studentFeeDetails/'.$reg_no, 'refresh');
	            
		}else {
			redirect('admin/timeout');
		}
	}
	
	function downloadReceipt1($transaction_id, $reg_no){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
			
			$studentDetails = $this->data_model->getDetailsbyfield('reg_no', $reg_no, 'students')->row();
			$transactionDetails = $this->data_model->getDetailsbyfield('id', $transaction_id, 'transactions')->row();
			
			$paid_amount = $this->data_model->paidFees1($reg_no, $transactionDetails->year)->row()->paid_amount;
			
			$courses = $this->coursesList();
			$combinations_values = $this->combinationsbyDept($studentDetails->course, 'S');
			
			$this->load->library('fpdf'); // Load library
			
			ini_set("session.auto_start", 0);
			ini_set('memory_limit', '-1');
			// define('FPDF_FONTPATH','plugins/font');
    	    $pdf = new FPDF('p','mm','A5');
            $pdf->enableheader = 0;
            $pdf->enablefooter = 0;
    	    $pdf->AddPage();
    	    $pdf->Image(base_url().'assets/img/BMSCW_FEE_RECEIPT.png', 0, 0, 148);
    	    $pdf->setDisplayMode('fullpage');
			
			$row = 8;
			
			$pdf->SetTextColor(33,33,33);
			$pdf->setFont ('Arial','BU',12);
            $pdf->SetXY(20, 25); 
            $pdf->Cell(0,10,"FEE RECEIPT",0,0,'C', false);
            
            $y = $pdf->getY();
            $pdf->setFont ('Arial','B',10);
            $pdf->SetXY(10, $y+10); 
            $pdf->Cell(0,10,"Receipt No: ".$transactionDetails->receipt_no,0,0,'L', false);
            $pdf->SetXY(100, $y+10); 
            $pdf->Cell(0,10, "Date: ".date('d-m-Y', strtotime($transactionDetails->transaciton_date)),0,0,'L', false); 
            
            $y = $pdf->getY();
            $pdf->setFont ('Arial','B',9);
            $pdf->SetXY(10, $y+$row); 
            $pdf->Cell(0,$row,"Register No",1,0,'L', false);
            $pdf->setFont ('Arial','',9);
            $pdf->SetXY(50, $y+$row); 
            $pdf->Cell(0,$row,$studentDetails->reg_no,1,0,'L', false); 
            
            $y = $pdf->getY();
            $pdf->setFont ('Arial','B',9);
            $pdf->SetXY(10, $y+$row); 
            $pdf->Cell(0,$row,"Name of the Student",1,0,'L', false);
            $pdf->setFont ('Arial','',9);
            $pdf->SetXY(50, $y+$row); 
            $pdf->Cell(0,$row,$studentDetails->student_name,1,0,'L', false); 
            
            $y = $pdf->getY();
            $pdf->setFont ('Arial','B',9);
            $pdf->SetXY(10, $y+$row); 
            $pdf->Cell(0,$row,"Course & Combination",1,0,'L', false);
            $pdf->setFont ('Arial','',9);
            $pdf->SetXY(50, $y+$row); 
            $pdf->Cell(0,$row,$courses[$studentDetails->course].'-'.$combinations_values[$studentDetails->combination],1,0,'L', false); 
            
            $y = $pdf->getY();
            $pdf->setFont ('Arial','B',9);
            $pdf->SetXY(10, $y+$row); 
            $pdf->Cell(0,$row,"Category",1,0,'L', false);
            $pdf->setFont ('Arial','',9);
            $pdf->SetXY(50, $y+$row); 
            $pdf->Cell(0,$row,$studentDetails->category,1,0,'L', false); 
            
            $y = $pdf->getY();
            $pdf->setFont ('Arial','B',9);
            $pdf->SetXY(10, $y+$row); 
            $pdf->Cell(0,$row,"Mobile",1,0,'L', false);
            $pdf->setFont ('Arial','',9);
            $pdf->SetXY(50, $y+$row); 
            $pdf->Cell(0,$row,$studentDetails->mobile,1,0,'L', false); 
            
            $y = $pdf->getY();
            $pdf->setFont ('Arial','B',9);
            $pdf->SetXY(10, $y+$row); 
            $pdf->Cell(0,$row,"Fee Category",1,0,'L', false);
            $pdf->setFont ('Arial','',9);
            $pdf->SetXY(50, $y+$row); 
            $pdf->Cell(0,$row,"College Fee (A)",1,0,'L', false); 
            
            $y = $pdf->getY();
            $pdf->setFont ('Arial','B',9);
            $pdf->SetXY(10, $y+$row); 
            $pdf->Cell(0,$row,"Mode of Payment",1,0,'L', false);
            $pdf->setFont ('Arial','',9);
            $pdf->SetXY(50, $y+$row); 
            $transactionTypes = $this->globals->transactionTypes();
            $pdf->Cell(0,$row,$transactionTypes[$transactionDetails->transaction_type],1,0,'L', false); 
            
            $final_amount = $studentDetails->final_amount;
            $paid_amount = $transactionDetails->amount;
            $balance = $transactionDetails->balance_amount;
            
            if($transactionDetails->transaction_type == 1){
                
                $y = $pdf->getY();
                $pdf->setFont ('Arial','B',9);
                $pdf->SetXY(10, $y+$row); 
                $pdf->Cell(0,$row,"Amount",1,0,'L', false);
                $pdf->setFont ('Arial','',9);
                $pdf->SetXY(50, $y+$row); 
                $pdf->Cell(0,$row,"Rs.".number_format($transactionDetails->amount,0)."/-",1,0,'L', false); 
                
                $y = $pdf->getY();
                $pdf->setFont ('Arial','B',9);
                $pdf->SetXY(10, $y+$row); 
                $pdf->Cell(0,$row,"Rupees (in words)",1,0,'L', false);
                $pdf->setFont ('Arial','',8);
                $pdf->SetXY(50, $y+$row); 
                $pdf->Cell(0,$row,$this->globals->getIndianCurrency($paid_amount),1,0,'L', false); 
                
                $y = $pdf->getY();
                $pdf->setFont ('Arial','B',9);
                $pdf->SetXY(10, $y+$row); 
                $pdf->Cell(0,$row,"Balance Amount",1,0,'L', false);
                $pdf->setFont ('Arial','',9);
                $pdf->SetXY(50, $y+$row); 
                $pdf->Cell(0,$row,"Rs.".number_format($balance,0)."/-",1,0,'L', false); 
            }
            
            if($transactionDetails->transaction_type == 2){
                
                $y = $pdf->getY();
                $pdf->setFont ('Arial','B',9);
                $pdf->SetXY(10, $y+$row); 
                $pdf->Cell(0,$row,"Cheque/DD No & Date",1,0,'L', false);
                $pdf->setFont ('Arial','',9);
                $pdf->SetXY(50, $y+$row); 
                $pdf->Cell(0,$row, $transactionDetails->reference_no.' Dt:'.date('d-m-Y', strtotime($transactionDetails->reference_date)),1,0,'L', false); 
                
                $y = $pdf->getY();
                $pdf->setFont ('Arial','B',9);
                $pdf->SetXY(10, $y+$row); 
                $pdf->Cell(0,$row,"Name of the Bank",1,0,'L', false);
                $pdf->setFont ('Arial','',9);
                $pdf->SetXY(50, $y+$row); 
                $pdf->Cell(0,$row, $transactionDetails->bank_name,1,0,'L', false); 
                
                $y = $pdf->getY();
                $pdf->setFont ('Arial','B',9);
                $pdf->SetXY(10, $y+$row); 
                $pdf->Cell(0,$row,"Amount",1,0,'L', false);
                $pdf->setFont ('Arial','',9);
                $pdf->SetXY(50, $y+$row); 
                $pdf->Cell(0,$row,"Rs.".number_format($transactionDetails->amount,0)."/-",1,0,'L', false); 
                
                $y = $pdf->getY();
                $pdf->setFont ('Arial','B',9);
                $pdf->SetXY(10, $y+$row); 
                $pdf->Cell(0,$row,"Rupees (in words)",1,0,'L', false);
                $pdf->setFont ('Arial','',8);
                $pdf->SetXY(50, $y+$row); 
                $pdf->Cell(0,$row,$this->globals->getIndianCurrency($paid_amount),1,0,'L', false); 
                
                $y = $pdf->getY();
                $pdf->setFont ('Arial','B',9);
                $pdf->SetXY(10, $y+$row); 
                $pdf->Cell(0,$row,"Balance Amount",1,0,'L', false);
                $pdf->setFont ('Arial','',9);
                $pdf->SetXY(50, $y+$row); 
                $pdf->Cell(0,$row,"Rs.".number_format($balance,0)."/-",1,0,'L', false); 
            }
            
            if($transactionDetails->transaction_type == 3){
                
                $y = $pdf->getY();
                $pdf->setFont ('Arial','B',9);
                $pdf->SetXY(10, $y+$row); 
                $pdf->Cell(0,$row,"Reference No & Date",1,0,'L', false);
                $pdf->setFont ('Arial','',9);
                $pdf->SetXY(50, $y+$row); 
                $pdf->Cell(0,$row, $transactionDetails->reference_no.' Dt:'.date('d-m-Y', strtotime($transactionDetails->reference_date)),1,0,'L', false); 
                
                $y = $pdf->getY();
                $pdf->setFont ('Arial','B',9);
                $pdf->SetXY(10, $y+$row); 
                $pdf->Cell(0,$row,"Amount",1,0,'L', false);
                $pdf->setFont ('Arial','',9);
                $pdf->SetXY(50, $y+$row); 
                $pdf->Cell(0,$row,"Rs.".number_format($transactionDetails->amount,0)."/-",1,0,'L', false); 
                
                $y = $pdf->getY();
                $pdf->setFont ('Arial','B',9);
                $pdf->SetXY(10, $y+$row); 
                $pdf->Cell(0,$row,"Rupees (in words)",1,0,'L', false);
                $pdf->setFont ('Arial','',8);
                $pdf->SetXY(50, $y+$row); 
                $pdf->Cell(0,$row,$this->globals->getIndianCurrency($paid_amount),1,0,'L', false); 
                
                $y = $pdf->getY();
                $pdf->setFont ('Arial','B',9);
                $pdf->SetXY(10, $y+$row); 
                $pdf->Cell(0,$row,"Balance Amount",1,0,'L', false);
                $pdf->setFont ('Arial','',9);
                $pdf->SetXY(50, $y+$row); 
                $pdf->Cell(0,$row,"Rs.".number_format($balance,0)."/-",1,0,'L', false); 
            }
			
            $y = $pdf->getY();
            $pdf->setFont ('Arial','B',9);
            $pdf->SetXY(10, $y+$row); 
            $pdf->Cell(0,$row,"Remarks",1,0,'L', false);
            $pdf->setFont ('Arial','',9);
            $pdf->SetXY(50, $y+$row); 
            $pdf->Cell(0,$row,$transactionDetails->remarks,1,0,'L', false); 
            
            $y = $pdf->getY();
            $pdf->setFont ('Arial','B',10);
            $pdf->SetXY(20, $y+20); 
            $pdf->Cell(0,$row,"Clerk",0,0,'L', false);
            $pdf->setFont ('Arial','B',10);
            $pdf->SetXY(100, $y+20); 
            $pdf->Cell(0,$row,"Principal",0,0,'L', false);
            
            $fileName = $transactionDetails->receipt_no.'.pdf';
		    $pdf->output($fileName,'D'); 
	            
		}else {
			redirect('admin/timeout');
		}
	}
	
	function updateFeeDetails(){
        if ($this->session->userdata('admin_logs')){
			$sess = $this->session->userdata('admin_logs');
			$data['id'] = $sess['id'];
			$data['username'] = $sess['username'];
			$data['user_type'] = $sess['user_type'];
			$data['name'] = $sess['name'];
			
			$id = $this->input->post('id');
			$reg_no = $this->input->post('reg_no');
			$proposed_fee_amount = $this->input->post('proposed_fee_amount');
			$concession_amount = $this->input->post('concession_amount');
			$concession_type = $this->input->post('concession_type');
			$additional_amount  = $this->input->post('additional_amount');
			$finalised_amount  = $this->input->post('finalised_amount'); 
			
			$updateData = array('proposed_fee_amount'=>$proposed_fee_amount, 'concession_amount'=>$concession_amount, 'concession_type'=>$concession_type, 'additional_amount'=>$additional_amount, 'finalised_amount'=>$finalised_amount);
			$res = $this->data_model->updateDetails($id, $updateData,'fees');
			
			if ($res) {
        	    $result = 1;
    		    $this->session->set_flashdata('message', 'Fee details are updated successfully..!!');
    			$this->session->set_flashdata('status', 'alert-success');
            }else {
            	$result = 0;
            	$this->session->set_flashdata('message', 'Oops..!! Something went wrong. Please try again later..!!');
                $this->session->set_flashdata('status', 'alert-warning');
    		}
    			
			print_r($result);
		}else {
			redirect('admin/timeout');
		}
	}
	
}