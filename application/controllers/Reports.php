<?php 
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller {
    
    function __construct() {
		parent::__construct();
		$this->CI = & get_instance();
		$this->load->model('data_model','',TRUE);
		$this->load->library(array('table','form_validation','session'));
		$this->load->helper(array('EMAIL'));
	    date_default_timezone_set('Asia/Kolkata');
	}
		
	public function index1(){
	    if($this->session->userdata('admin_logs')){
	        redirect('admin/dashboard');
	    } else {
			
    	    if ($this->uri->segment(2) == 'invalid'){
    			$data['msg'] = 'Invalid EMAIL / Password..!!';
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

    function reset(){
		$this->session->unset_userdata('instance');
		session_destroy();
		redirect('reports/index', 'refresh');
	}

	function index(){
		$this->session->unset_userdata('instance');
		session_destroy();
		// redirect('reports/index', 'refresh');

		$this->reports_template->show('reports/index');
	}

	function setInstance($instance){
		$this->session->set_userdata('instance', $instance);
		redirect('reports/instanceHome', 'refresh');
	}

	function instanceHome(){
		if ($this->session->userdata('instance')){
			$data['instance'] = $this->session->userdata('instance');
			
			$data['page_title'] = 'Dashboard';		 
			$data['res'] = $this->data_model->getDistricts()->result();

			$data['instances'] = array(" " => "") + $this->globals->instances();
			$data['instancesLive'] = array(" " => "") + $this->globals->instancesLive();
			
			$this->reports_template->show('reports/report1',$data);

		}else {
			redirect('reports');
		}
	}

    function index2(){
		$instance = "tn";
		$this->session->set_userdata('instance', $instance);
		print_r($instance);
		
		if ($this->session->userdata()){
			$instance = $this->session->userdata();
			print_r($instance['instance']);
		}else{
			echo "No Session";
		}
			// $sess = $this->session->userdata('admin_logs');
		  die;
			$data['page_title'] = 'Dashboard';		 
			$data['res'] = $this->data_model->getDistricts()->result();
			$this->reports_template->show('reports/report1',$data);
	}

 	function downloadReports(){
			$data['page_title'] = 'Dashboard';		 
			$res = $this->data_model->getDistricts()->result();

			$districts = array();
		   	foreach($res as $res1){
		   	   $districts[$res1->district] = $res1->district; 
		   	}
			
			$data['districts'] = array("" => "Select") + $districts;

			$data['reportTypes'] = array(" " => "Select") + $this->globals->reportTypes();;

			$this->reports_template->show('reports/reports',$data);
	}
	
	function institutionsList($district){
		if($district){
			$data['page_title'] = $district.' - Institutions List';
			$res = $this->data_model->getInstitutionsList($district)->result();
			 
			$table_setup = array ('table_open'=> '<table class="table table-striped table-vcenter table-hover js-dataTable-full font-size-sm"  border="1">');    
			$this->table->set_template($table_setup);
			$this->table->set_heading(
								array('data' =>'S.No', 'style'=>'width:50;background-color:#002060; color:#fff'),
								array('data' =>'UDISE CODE', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'DISTRICT', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'SCHOOL NAME', 'style'=>'width:600;background-color:#002060; color:#fff'),
								array('data' =>'HM NAME', 'style'=>'width:500;background-color:#002060; color:#fff'),
								array('data' =>'HM MOBILE', 'style'=>'width:200;background-color:#002060; color:#fff')
							);
			$i=1; $total = 0;
			foreach ($res as $res1){
				$this->table->add_row($i++,
						$res1->organization_code,
						$res1->district,
						$res1->organization_name,
						$res1->principal_name,
						$res1->principal_mobile
				);
			}
			
			$detailsTable = $this->table->generate();
			header("Content-type: application/octet-stream");
			header("Content-Disposition: attachment; filename=".$data['page_title'].".xls");
			header("Pragma: no-cache");
			header("Expires: 0"); 
			echo $detailsTable;
		}else{
			redirect('reports/index');
		}
	}

	function teachersRegistrationStatus(){
		$district = $this->input->post('district');
		if($district){
			$data['page_title'] = $district.' - Teacher Registration Status';
			$res = $this->data_model->getInstitutionsList($district)->result();
			 
			$table_setup = array ('table_open'=> '<table class="table table-striped table-vcenter table-hover js-dataTable-full font-size-sm"  border="1">');    
			$this->table->set_template($table_setup);
			$this->table->set_heading(
								array('data' =>'S.No', 'style'=>'width:50;background-color:#002060; color:#fff'),
								array('data' =>'UDISE CODE', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'DISTRICT', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'SCHOOL NAME', 'style'=>'width:600;background-color:#002060; color:#fff'),
								array('data' =>'HM NAME', 'style'=>'width:500;background-color:#002060; color:#fff'),
								array('data' =>'HM MOBILE', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'TEACHER NAME', 'style'=>'width:500;background-color:#002060; color:#fff'),
								array('data' =>'MOBILE', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'EMAIL', 'style'=>'width:500;background-color:#002060; color:#fff')
							);
			$i=1; $total = 0;
			foreach ($res as $res1){
				
				$teacher = $this->data_model->getTeacherDetails($res1->organization_code)->row();
				if($teacher){
					$teacher_name = array('data' => $teacher->full_name);
					$mobile = array('data' => $teacher->mobile);
					$email = array('data' => $teacher->username);
				}else{
					$teacher_name = array('data' => "NOT REGISTERED",'style'=>'background-color:#ff4444; color:#fff');
					$mobile = array('data' => "NOT REGISTERED",'style'=>'background-color:#ff4444; color:#fff');
					$email = array('data' => "NOT REGISTERED",'style'=>'background-color:#ff4444; color:#fff');
				}

				$this->table->add_row($i++,
						$res1->organization_code,
						$res1->district,
						$res1->organization_name,
						$res1->principal_name,
						$res1->principal_mobile,
						$teacher_name,
						$mobile,
						$email
				);
			}
			
			$detailsTable = $this->table->generate();
			$response =  array('op' => 'ok',
                 'file' => "data:application/vnd.ms-excel;base64,".base64_encode($detailsTable)
                 );
            die(json_encode($response));
		}else{
			redirect('reports/index');
		}
	}

	function teachersPreSurvey($district){
			if($district){
				$data['page_title'] = $district.' Teachers PreSurvey';
				$res = $this->data_model->getInstitutionsList($district)->result();
				// $res = $this->data_model->getTeachersPreSurvey($district)->result();
				// echo "<pre>";print_r($res); die;
				$table_setup = array ('table_open'=> '<table class="table table-striped table-vcenter table-hover js-dataTable-full font-size-sm"  border="1">');    
				$this->table->set_template($table_setup);
				$this->table->set_heading(
									array('data' =>'S.No', 'style'=>'width:5%;'),
									array('data' =>'UDISE CODE', 'style'=>'width:5%;'),
									array('data' =>'DISTRICT', 'style'=>'width:15%;'),
									array('data' =>'SCHOOL NAME', 'style'=>'width:40%;'),
									array('data' =>'HM NAME'),
									array('data' =>'HM MOBILE'),
									array('data' =>'HM EMAIL'),
									array('data' =>'TEACHER NAME'),
									array('data' =>'MOBILE'),
									array('data' =>'EMAIL ID'),
									array('data' =>'STATUS'),
									);
				$i=1; $total = 0;
				foreach ($res as $res1){
					$this->table->add_row($i++,
							$res1->organization_code,
							$res1->district,
							$res1->organization_name,
							$res1->principal_name,
							$res1->principal_mobile,
							$res1->principal_email,
							$res1->full_name,
							$res1->mobile,
							$res1->username,
							$res1->updated_at
					);
				}
				$this->table->add_row('','<b>Total</b>','<b>'.$total.'</b>');
				
				$detailsTable = $this->table->generate();
				header("Content-type: application/octet-stream");
				header("Content-Disposition: attachment; filename=".$data['page_title'].".xls");
				header("Pragma: no-cache");
				header("Expires: 0"); 
				echo $detailsTable;
			}else{
				redirect('reports/index');
			}
		 
	}

	function teachersStatus(){
		$district = $this->input->post('district');
		if($district){
			$data['page_title'] = $district.' - Teacher Registration Status';
			$res = $this->data_model->getInstitutionsList($district)->result();
			 
			$table_setup = array ('table_open'=> '<table class="table table-striped table-vcenter table-hover js-dataTable-full font-size-sm"  border="1">');    
			$this->table->set_template($table_setup);
			$this->table->set_heading(
								array('data' =>'S.No', 'style'=>'width:50;background-color:#002060; color:#fff'),
								array('data' =>'UDISE CODE', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'DISTRICT', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'SCHOOL NAME', 'style'=>'width:600;background-color:#002060; color:#fff'),
								array('data' =>'HM NAME', 'style'=>'width:500;background-color:#002060; color:#fff'),
								array('data' =>'HM MOBILE', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'TEACHER NAME', 'style'=>'width:500;background-color:#002060; color:#fff'),
								array('data' =>'MOBILE', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'EMAIL', 'style'=>'width:500;background-color:#002060; color:#fff'),
								array('data' =>'SIDP TEACHER COURSE STATUS', 'style'=>'width:200;background-color:#FF0000; color:#fff'),
								array('data' =>'SIDP TEAMS COUNT', 'style'=>'width:200;background-color:#FF0000; color:#fff'),
								array('data' =>'SIDP STUDENTS COUNT', 'style'=>'width:200;background-color:#FF0000; color:#fff'),
							);
			$i=1; $total = 0;
			foreach ($res as $res1){
				
				$teacher = $this->data_model->getTeacherDetails($res1->organization_code)->row();
				if($teacher){
					$mentor_id = $teacher->mentor_id;
					$user_id = $teacher->user_id;

					$teacher_name = array('data' => $teacher->full_name);
					$mobile = array('data' => $teacher->mobile);
					$email = array('data' => $teacher->username);
					
					// COURSE STATUS
					$getTeacherCourseStatus = $this->data_model->getTeacherCourseStatus($user_id)->row();
					if($getTeacherCourseStatus){
						if($getTeacherCourseStatus->count == 9){
							$teacher_course_status = array('data' => 'COMPLETED','style'=>'background-color:#4CAF50; color:#000');
						}else{
							$teacher_course_status = array('data' => 'IN PROGRESS','style'=>'background-color:#FFF176; color:#000');
						}
					}else{
						$teacher_course_status = array('data' => 'NOT STARTED','style'=>'background-color:#F4511E; color:#000');
					}

					// TEAMS COUNT
					$getTeamsCount = $this->data_model->getTeamsCount($mentor_id)->row();
					if($getTeamsCount){
						if($getTeamsCount->count){
							$teams_count = $getTeamsCount->count;
						}else{
							$teams_count = array('data' => 0,'style'=>'background-color:#F4511E; color:#000');	
						}
					}else{
						$teams_count = array('data' => 0,'style'=>'background-color:#F4511E; color:#000');
					}

					// STUDENTS COUNT
					$getStudentsCount = $this->data_model->getStudentsCount($mentor_id)->row();
					if($getStudentsCount){
						if($getStudentsCount->count){
							$students_count = $getStudentsCount->count;
						}else{
							$students_count = array('data' => 0,'style'=>'background-color:#F4511E; color:#000');	
						}
					}else{
						$students_count = array('data' => 0,'style'=>'background-color:#F4511E; color:#000');
					}
					
				}else{
					$teacher_name = array('data' => "NOT REGISTERED",'style'=>'background-color:#F4511E; color:#000');
					$mobile = array('data' => "NOT REGISTERED",'style'=>'background-color:#F4511E; color:#000');
					$email = array('data' => "NOT REGISTERED",'style'=>'background-color:#F4511E; color:#000');
					$teacher_course_status = array('data' => 'NOT STARTED','style'=>'background-color:#F4511E; color:#000');
					$teams_count = array('data' => 0,'style'=>'background-color:#F4511E; color:#000');
					$students_count = array('data' => 0,'style'=>'background-color:#F4511E; color:#000');
				}
 
				$this->table->add_row($i++,
						$res1->organization_code,
						$res1->district,
						$res1->organization_name,
						$res1->principal_name,
						$res1->principal_mobile,
						$teacher_name,
						$mobile,
						$email,
						$teacher_course_status,
						$teams_count,
						$students_count
				);
			}
			
			$detailsTable = $this->table->generate();
			$response =  array('op' => 'ok',
                 'file' => "data:application/vnd.ms-excel;base64,".base64_encode($detailsTable)
                 );
            die(json_encode($response));
		}else{
			redirect('reports/index');
		}
	}

	function studentPreSurvey(){
		$district = $this->input->post('district');
		if($district){
			$data['page_title'] = $district.' - Student PreSurvey Status';
			$res = $this->data_model->getInstitutionsList($district)->result();
			 
			$table_setup = array ('table_open'=> '<table class="table table-striped table-vcenter table-hover js-dataTable-full font-size-sm"  border="1">');    
			$this->table->set_template($table_setup);
			$this->table->set_heading(
								array('data' =>'S.No', 'style'=>'width:50;background-color:#002060; color:#fff'),
								array('data' =>'UDISE CODE', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'DISTRICT', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'SCHOOL NAME', 'style'=>'width:600;background-color:#002060; color:#fff'),
								array('data' =>'HM NAME', 'style'=>'width:500;background-color:#002060; color:#fff'),
								array('data' =>'HM MOBILE', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'TEACHER NAME', 'style'=>'width:500;background-color:#002060; color:#fff'),
								array('data' =>'MOBILE', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'EMAIL', 'style'=>'width:500;background-color:#002060; color:#fff'),
								array('data' =>'SIDP TEAMS COUNT', 'style'=>'width:200;background-color:#FF0000; color:#fff'),
								array('data' =>'SIDP STUDENTS COUNT', 'style'=>'width:200;background-color:#FF0000; color:#fff'),
								array('data' =>'PRE SURVEY STATUS', 'style'=>'width:200;background-color:#FF0000; color:#fff'),
								array('data' =>'PRE SURVEY COMPLETED COUNT', 'style'=>'width:200;background-color:#FF0000; color:#fff'),
								array('data' =>'PRE SURVEY NOT STARTED COUNT', 'style'=>'width:200;background-color:#FF0000; color:#fff')
							);
			$i=1; $total = 0;
			foreach ($res as $res1){
				
				$teacher = $this->data_model->getTeacherDetails($res1->organization_code)->row();
				if($teacher){
					$mentor_id = $teacher->mentor_id;
					$user_id = $teacher->user_id;

					$teacher_name = array('data' => $teacher->full_name);
					$mobile = array('data' => $teacher->mobile);
					$email = array('data' => $teacher->username);
										 
					// TEAMS COUNT
					$getTeamsCount = $this->data_model->getTeamsCount($mentor_id)->row();
					if($getTeamsCount){
						if($getTeamsCount->count){
							$teams_count = $getTeamsCount->count;
						}else{
							$teams_count = array('data' => 0,'style'=>'background-color:#F4511E; color:#000');	
						}
					}else{
						$teams_count = array('data' => 0,'style'=>'background-color:#F4511E; color:#000');
					}

					// STUDENTS COUNT
					$getStudentsCount = $this->data_model->getStudentsCount($mentor_id)->row();
					if($getStudentsCount){
						if($getStudentsCount->count){
							$students_count = $getStudentsCount->count;
							$students_count1 = $getStudentsCount->count;
						}else{
							$students_count = array('data' => 0,'style'=>'background-color:#F4511E; color:#000');	
							$students_count1 = 0;
						}
					}else{
						$students_count = array('data' => 0,'style'=>'background-color:#F4511E; color:#000');
						$students_count1 = 0;
					}

					// STUDENTS PRE SURVEY COUNT
					$getStudentsPreSurveyCount = $this->data_model->getStudentsPreSurveyCount($mentor_id)->row();
					if($getStudentsPreSurveyCount){
						if($getStudentsPreSurveyCount->count){
							if($getStudentsPreSurveyCount->count == $students_count1){
								$pre_survey_status = array('data' => 'COMPLETED','style'=>'background-color:#4CAF50; color:#000');
								$pre_survey_completed_count = $getStudentsPreSurveyCount->count;	
								$pre_survey_not_started_count = 0;
							}else{
								$pre_survey_status = array('data' => 'IN PROGRESS','style'=>'background-color:#FFF176; color:#000');
								$pre_survey_completed_count = $getStudentsPreSurveyCount->count;
								$pre_survey_not_started_count = $students_count1 - $getStudentsPreSurveyCount->count;
							}							
						}else{
							$pre_survey_status = array('data' => "NOT STARTED",'style'=>'background-color:#F4511E; color:#000');
							$pre_survey_completed_count = 0;
							$pre_survey_not_started_count = $students_count1;
						}
					}else{
						$pre_survey_status = array('data' => "NOT STARTED",'style'=>'background-color:#F4511E; color:#000');
						$pre_survey_completed_count = 0;
						$pre_survey_not_started_count = $students_count1;
					}
					
				}else{
					$teacher_name = array('data' => "NOT REGISTERED",'style'=>'background-color:#F4511E; color:#000');
					$mobile = array('data' => "NOT REGISTERED",'style'=>'background-color:#F4511E; color:#000');
					$email = array('data' => "NOT REGISTERED",'style'=>'background-color:#F4511E; color:#000');
					$teams_count = array('data' => 0,'style'=>'background-color:#F4511E; color:#000');
					$students_count = array('data' => 0,'style'=>'background-color:#F4511E; color:#000');
					$pre_survey_status = array('data' => "NOT REGISTERED",'style'=>'background-color:#F4511E; color:#000');
					$pre_survey_completed_count = array('data' => 0,'style'=>'background-color:#F4511E; color:#000');
					$pre_survey_not_started_count = array('data' => 0,'style'=>'background-color:#F4511E; color:#000');
				}
 
				$this->table->add_row($i++,
						$res1->organization_code,
						$res1->district,
						$res1->organization_name,
						$res1->principal_name,
						$res1->principal_mobile,
						$teacher_name,
						$mobile,
						$email,
						$teams_count,
						$students_count,
						$pre_survey_status,
						$pre_survey_completed_count,
						$pre_survey_not_started_count

				);
			}
			
			$detailsTable = $this->table->generate();
			$response =  array('op' => 'ok',
                 'file' => "data:application/vnd.ms-excel;base64,".base64_encode($detailsTable)
                 );
            die(json_encode($response));
		}else{
			redirect('reports/index');
		}
	}

	function studentPreSurveyDetail(){
			$data['page_title'] = 'Student PreSurvey Status';
			$res = $this->data_model->studentPreSurveyDetail()->result();
			// echo "<pre>"; 
			// print_r($res);
			// die;

			// foreach ($res as $res1){
			// 	echo $res1->quiz_response_id;
			// 	echo "<br/>";
			// 	$response = json_decode($res1->response);
			// 	// print_r($response);
			// 	foreach ($response as $response1){
			// 		print_r($response1);
			// 	}
			// 	echo "<br/>";
			// }

			// die;
			$table_setup = array ('table_open'=> '<table class="table table-striped table-vcenter table-hover js-dataTable-full font-size-sm"  border="1">');    
			$this->table->set_template($table_setup);

			// STUDENT PRE SURVEY
			// array('data' =>'Congratulations! We are excited to see you begin your problem-solving journey. How are you feeling right now?', 'style'=>'width:500;background-color:#FF0000; color:#fff'),
			// array('data' =>'How confident are you talking to new people in your community / surroundings?', 'style'=>'width:500;background-color:#FF0000; color:#fff'),
			// array('data' =>'How do you feel about going to school everyday?', 'style'=>'width:500;background-color:#FF0000; color:#fff'),
			// array('data' =>'How well do you know about the people and places in your community/ surroundings?', 'style'=>'width:500;background-color:#FF0000; color:#fff'),
			// array('data' =>'What do you think about working together in a team to complete a task?', 'style'=>'width:500;background-color:#FF0000; color:#fff'),
			// array('data' =>'Did you participate in any online course before?', 'style'=>'width:500;background-color:#FF0000; color:#fff'),
			// array('data' =>'Did you participate in any science exhibition or  worked on projects before?', 'style'=>'width:500;background-color:#FF0000; color:#fff'),
			// array('data' =>'Do you enjoy working in a team and making your friends feel better?', 'style'=>'width:500;background-color:#FF0000; color:#fff'),
			// array('data' =>'Do you enjoy talking to a group of students or giving a speech on stage? ', 'style'=>'width:500;background-color:#FF0000; color:#fff'),
			// array('data' =>'Are you aware of Sustainable Development Goals?', 'style'=>'width:500;background-color:#FF0000; color:#fff'),

			$this->table->set_heading(
								array('data' =>'S.No', 'style'=>'width:50;background-color:#002060; color:#fff'),
								array('data' =>'UDISE CODE', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'DISTRICT', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'SCHOOL NAME', 'style'=>'width:600;background-color:#002060; color:#fff'),
								array('data' =>'STUDENT NAME', 'style'=>'width:500;background-color:#002060; color:#fff'),
								array('data' =>'LOGIN ID', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'AGE', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'GRADE', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'GENDER', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'Congratulations! We hope you had a great time all along your problem-solving journey. How was your experience so far?', 'style'=>'width:500;background-color:#FF0000; color:#fff'),
								array('data' =>'Do you feel confident about talking to new people in your community/ surroundings after your problem-solving journey?', 'style'=>'width:500;background-color:#FF0000; color:#fff'),
								array('data' =>'Did this program make going to school everyday more exciting for you?', 'style'=>'width:500;background-color:#FF0000; color:#fff'),
								array('data' =>'Which parts of the on-line journey did you like the most?', 'style'=>'width:500;background-color:#FF0000; color:#fff'),
								array('data' =>'Which parts of the on-line journey did you not like or found it difficult?', 'style'=>'width:500;background-color:#FF0000; color:#fff'),
								array('data' =>'What were the most exciting parts of the off-line problem-solving journey for you and your team ?', 'style'=>'width:500;background-color:#FF0000; color:#fff'),
								array('data' =>'What were the most exciting parts of the off-line problem-solving journey for you and your team ?', 'style'=>'width:500;background-color:#FF0000; color:#fff'),
								array('data' =>'Which was the most difficult part of the offline problem-solving journe for you?', 'style'=>'width:500;background-color:#FF0000; color:#fff'),
								array('data' =>'Would you like to work on solving more problems in the future?', 'style'=>'width:500;background-color:#FF0000; color:#fff'),
								array('data' =>'GIVEN DATE', 'style'=>'width:200;background-color:#FF0000; color:#fff')
							);
			$i=1; $total = 0;
			foreach ($res as $res1){
				
				$organization_code = array('data' => $res1->organization_code);
				$district = array('data' => $res1->district);
				$organization_name = array('data' => $res1->organization_name);

				$full_name = array('data' => $res1->full_name);
				$username = array('data' => $res1->username);
				$Age = array('data' => $res1->Age);
				$Grade =  array('data' => $res1->Grade);
				$Gender = array('data' => $res1->Gender);

				$given_date = array('data' => date('d-m-Y h:i A', strtotime($res1->created_at)));
				$response = json_decode($res1->response);
				$ans1 = '-'; $ans2 = '-'; $ans3 = '-'; $ans4 = '-'; $ans5 = '-'; $ans6 = '-'; $ans7 = '-'; $ans8 = '-'; $ans9 = '-'; $ans10 = '-';
				foreach($response as $response1){

					if($response1->question_no ==  1){
						$ans1 = $response1->selected_option;
					}
					if($response1->question_no ==  2){
						$ans2 = $response1->selected_option;
					}
					if($response1->question_no ==  3){
						$ans3 = $response1->selected_option;
					}
					if($response1->question_no ==  4){
						$ans4 = $response1->selected_option;
					}
					if($response1->question_no ==  5){
						$ans5 = $response1->selected_option;
					}
					if($response1->question_no ==  6){
						$ans6 = $response1->selected_option;
					}
					if($response1->question_no ==  7){
						$ans7 = $response1->selected_option;
					}
					if($response1->question_no ==  8){
						$ans8 = $response1->selected_option;
					}
					if($response1->question_no ==  9){
						$ans9 = $response1->selected_option;
					}
					// if($response1->question_no ==  10){
					// 	$ans10 = $response1->selected_option;
					// }
					
				}
				$this->table->add_row($i++,
									$organization_code,
									$district,
									$organization_name,
									$full_name,
									$username,
									$Age,
									$Grade,
									$Gender,
									$ans1,
									$ans2,
									$ans3,
									$ans4,
									$ans5,
									$ans6,
									$ans7,
									$ans8,
									$ans9,
									$given_date,
									 
								);
						 
			}
			
			$detailsTable = $this->table->generate();
			$file_name = "Student Post Survey Details.xls";
    			header('Content-Disposition: attachment; filename='.$file_name.'');
		        header('Content-type: application/force-download');
			    header('Content-Transfer-Encoding: binary');
			    header('Pragma: public');
			    print "\xEF\xBB\xBF"; // UTF-8 BOM
			    echo $detailsTable;
			// $response =  array('op' => 'ok',
            //      'file' => "data:application/vnd.ms-excel;base64,".base64_encode($detailsTable)
            //      );
            // die(json_encode($response));
		 
	}

	function studentLessons(){
		// $mentor_id = 4208;
		// $ideas_res = $this->data_model->getIdeasCount($mentor_id)->result();
		// $ideas_draft = 0; $ideas_submitted = 0;
		// foreach($ideas_res as $ideas_res1){
		// 	if($ideas_res1->status == "DRAFT"){
		// 		$ideas_draft = $ideas_res1->count;
		// 	}
		// 	if($ideas_res1->status == "SUBMITTED"){
		// 		$ideas_submitted = $ideas_res1->count;
		// 	}
		// }
		// echo $ideas_draft;
		// echo $ideas_submitted;
		// print_r($ideas_res);
		// die;
		$district = $this->input->post('district');
		if($district){
			$data['page_title'] = $district.' - Student PreSurvey Status';
			$res = $this->data_model->getInstitutionsList1($district)->result();
		 
			$instance = $this->session->userdata('instance');
			$table_setup = array ('table_open'=> '<table class="table table-striped table-vcenter table-hover js-dataTable-full font-size-sm"  border="1">');    
			$this->table->set_template($table_setup);

			$set_heading = array(
								array('data' =>'S.No', 'style'=>'width:50;background-color:#002060; color:#fff'),
								array('data' =>'UDISE CODE', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'DISTRICT', 'style'=>'width:200;background-color:#002060; color:#fff')
							);

			if($instance == "ka"){
				array_push($set_heading, array('data' =>'CATEGORY', 'style'=>'width:200;background-color:#FF0000; color:#fff'));
				array_push($set_heading, array('data' =>'BLOCK', 'style'=>'width:200;background-color:#FF0000; color:#fff'));
			}

			if($instance == "ts"){
				array_push($set_heading, array('data' =>'ORG TYPE', 'style'=>'width:200;background-color:#FF0000; color:#fff'));
			}
			 
			$set_heading1 = array(
				array('data' =>'SCHOOL NAME', 'style'=>'width:600;background-color:#002060; color:#fff'),
				array('data' =>'HM NAME', 'style'=>'width:500;background-color:#002060; color:#fff'),
				array('data' =>'HM MOBILE', 'style'=>'width:200;background-color:#002060; color:#fff'),
				array('data' =>'TEACHER NAME', 'style'=>'width:500;background-color:#002060; color:#fff'),
				array('data' =>'MOBILE', 'style'=>'width:200;background-color:#002060; color:#fff'),
				array('data' =>'EMAIL', 'style'=>'width:500;background-color:#002060; color:#fff'),
				array('data' =>'TEAMS COUNT', 'style'=>'width:200;background-color:#FF0000; color:#fff'),
				array('data' =>'STUDENTS COUNT', 'style'=>'width:200;background-color:#FF0000; color:#fff'),
				array('data' =>'STUDENTS STATUS', 'style'=>'width:200;background-color:#37474F; color:#fff'),
				array('data' =>'COMPLETED COUNT', 'style'=>'width:200;background-color:#37474F; color:#fff'),
				array('data' =>'IN PROGRESS COUNT', 'style'=>'width:200;background-color:#37474F; color:#fff'),
				array('data' =>'NOT STARTED COUNT', 'style'=>'width:200;background-color:#37474F; color:#fff'),
				array('data' =>'IDEAS SUBMISSION STATUS', 'style'=>'width:200;background-color:#5D4037; color:#fff'),
				array('data' =>'IDEAS SUBMITTED COUNT', 'style'=>'width:200;background-color:#5D4037; color:#fff'),
				array('data' =>'IDEAS DRAFT COUNT', 'style'=>'width:200;background-color:#5D4037; color:#fff'),
				array('data' =>'IDEAS NOT STARTED COUNT', 'style'=>'width:200;background-color:#5D4037; color:#fff')
			);
			$set_heading = array_merge($set_heading, $set_heading1);
			$this->table->set_heading($set_heading);
			$i=1; $total = 0;
			foreach ($res as $res1){
				
				$teacher = $this->data_model->getTeacherDetails($res1->organization_code)->row();
				if($teacher){
					$mentor_id = $teacher->mentor_id;
					$user_id = $teacher->user_id;

					$teacher_name = array('data' => $teacher->full_name);
					$mobile = array('data' => $teacher->mobile);
					$email = array('data' => $teacher->username);
										 
					// TEAMS COUNT
					$getTeamsCount = $this->data_model->getTeamsCount($mentor_id)->row();
					if($getTeamsCount){
						if($getTeamsCount->count){
							$teams_count = $getTeamsCount->count;
							
							// IDEAS STATUS
							$ideas_res = $this->data_model->getIdeasCount($mentor_id)->result();
							$ideas_draft = 0; $ideas_submitted = 0;
							foreach($ideas_res as $ideas_res1){
								if($ideas_res1->status == "DRAFT"){
									$ideas_draft = $ideas_res1->count;
								}
								if($ideas_res1->status == "SUBMITTED"){
									$ideas_submitted = $ideas_res1->count;
								}
							}
							$ideas_not_started = $teams_count - ($ideas_draft + $ideas_submitted);
							if($teams_count == $ideas_submitted){								
								$ideas_status = array('data' => "COMPLETED 🟢",'style'=>'background-color:#4CAF50; color:#000');
							}else{
								$ideas_status = array('data' => "IN PROGRESS 🟡",'style'=>'background-color:#FFF176; color:#000');
							}

						}else{
							$teams_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');	
							$ideas_status = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
							$ideas_submitted = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
							$ideas_draft = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
							$ideas_not_started = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
						}
					}else{
						$teams_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
						$ideas_status = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
						$ideas_submitted = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
						$ideas_draft = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
						$ideas_not_started = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
					}

					// STUDENTS COUNT
					$getStudentsCount = $this->data_model->getStudentsCount($mentor_id)->row();
					if($getStudentsCount){
						if($getStudentsCount->count){
							$students_count = $getStudentsCount->count;
							$students_count1 = $getStudentsCount->count;
						}else{
							$students_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');	
							$students_count1 = 0;
						}
					}else{
						$students_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
						$students_count1 = 0;
					}

					// STUDENTS PRE SURVEY COUNT
					$getStudentsLessonsCount = $this->data_model->getStudentsLessonsCount($mentor_id)->result();
					if($getStudentsLessonsCount){
						$started = count($getStudentsLessonsCount);
						$not_started = $students_count1 - $started;
						$completed = 0; $inprogress = 0; 
						foreach($getStudentsLessonsCount as $getStudentsLessonsCount1){
							if($getStudentsLessonsCount1->count == 35){
								$completed++;
							}else{
								$inprogress++;
							}
						}
							if($students_count1 == $completed){
								$lessons_status = array('data' => "COMPLETED 🟢",'style'=>'background-color:#4CAF50; color:#000');
							}else{
								$lessons_status = array('data' => "IN PROGRESS 🟡",'style'=>'background-color:#FFF176; color:#000');
							}
						
						if($completed){
							$lessons_completed_count = array('data' => $completed);
						}else{
							$lessons_completed_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
						}

						if($inprogress){
							$lessons_in_progress_count = array('data' => $inprogress);
						}else{
							$lessons_in_progress_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
						}
						
						if($not_started){
							$lessons_not_started_count = array('data' => $not_started);
						}else{
							$lessons_not_started_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
						}

					}else{
												
						$lessons_status = array('data' => "NOT REGISTERED 🔴",'style'=>'background-color:#F4511E; color:#000');
						$lessons_completed_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
						$lessons_in_progress_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
						if($students_count1){
							$lessons_not_started_count = array('data' => $students_count1);
						}else{
							$lessons_not_started_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
						}
						
					}

					
					
				}else{
					// $teacher_name = array('data' => "NOT REGISTERED",'style'=>'background-color:#F4511E; color:#000');
					// $mobile = array('data' => "NOT REGISTERED",'style'=>'background-color:#F4511E; color:#000');
					// $email = array('data' => "NOT REGISTERED",'style'=>'background-color:#F4511E; color:#000');
					// $teams_count = array('data' => 0,'style'=>'background-color:#F4511E; color:#000');
					// $students_count = array('data' => 0,'style'=>'background-color:#F4511E; color:#000');
					// $lessons_status = array('data' => "NOT REGISTERED",'style'=>'background-color:#F4511E; color:#000');
					// $lessons_completed_count = array('data' => 0);
					// $lessons_in_progress_count = array('data' => 0);
					// $lessons_not_started_count = array('data' => 0);

					$teacher_name = array('data' => "NOT REGISTERED 🔴",'style'=>'background-color:#F4511E; color:#000');
					$mobile = array('data' => "NOT REGISTERED 🔴",'style'=>'background-color:#F4511E; color:#000');
					$email = array('data' => "NOT REGISTERED 🔴",'style'=>'background-color:#F4511E; color:#000');
					$teams_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
					$students_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
					$lessons_status = array('data' => "NOT REGISTERED 🔴",'style'=>'background-color:#F4511E; color:#000');
					$lessons_completed_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
					$lessons_in_progress_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
					$lessons_not_started_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
					$ideas_status = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
					$ideas_submitted = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
					$ideas_draft = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
					$ideas_not_started = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
				}
				
				$principal_name = ($res1->principal_name) ? $res1->principal_name : $res1->organization_name;
				$principal_mobile = ($res1->principal_mobile) ? $res1->principal_mobile : 0;

				$add_row = array($i++,$res1->organization_code,$res1->district);
				if($instance == 'ka'){
					array_push($add_row, $res1->category);
					array_push($add_row, $res1->block_name);
				}
				if($instance == 'ts'){
					array_push($add_row, $res1->org_type);
				}
				$add_other_fields = array($res1->organization_name,
										$principal_name,
										$principal_mobile,
										$teacher_name,
										$mobile,
										$email,
										$teams_count,
										$students_count,
										$lessons_status,
										$lessons_completed_count,
										$lessons_in_progress_count,
										$lessons_not_started_count,
										$ideas_status,
										$ideas_submitted,
										$ideas_draft,
										$ideas_not_started);
				$add_row = array_merge($add_row, $add_other_fields);
				$this->table->add_row($add_row);
			}
			
			$detailsTable = $this->table->generate();
			$response =  array('op' => 'ok',
                 'file' => "data:application/vnd.ms-excel;base64,".base64_encode($detailsTable)
                 );
            die(json_encode($response));
		}else{
			redirect('reports/index');
		}
	}


	function studentProgress(){
		$district = $this->input->post('district');
		if($district){
			$data['page_title'] = $district.' - Student Progress Status';
			$instance = $this->session->userdata('instance');
			$res = $this->data_model->studentProgress($district, $instance)->result();
		 
			$table_setup = array ('table_open'=> '<table class="table table-striped table-vcenter table-hover js-dataTable-full font-size-sm"  border="1">');    
			$this->table->set_template($table_setup);

			$set_heading = array(
								array('data' =>'S.No', 'style'=>'width:50;background-color:#002060; color:#fff'),
								array('data' =>'UDISE CODE', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'SCHOOL NAME', 'style'=>'width:600;background-color:#002060; color:#fff'),
								array('data' =>'DISTRICT', 'style'=>'width:300;background-color:#002060; color:#fff')
							);

			if($instance == "ka"){
				array_push($set_heading, array('data' =>'CATEGORY', 'style'=>'width:200;background-color:#002060; color:#fff'));
				array_push($set_heading, array('data' =>'BLOCK', 'style'=>'width:200;background-color:#002060; color:#fff'));
			}

			if($instance == "ts"){
				array_push($set_heading, array('data' =>'ORG TYPE', 'style'=>'width:200;background-color:#002060; color:#fff'));
			}
			 
			$set_heading1 = array(
				// array('data' =>'HM NAME', 'style'=>'width:500;background-color:#002060; color:#fff'),
				// array('data' =>'HM MOBILE', 'style'=>'width:200;background-color:#002060; color:#fff'),
				array('data' =>'TEACHER NAME', 'style'=>'width:500;background-color:#002060; color:#fff'),
				array('data' =>'MOBILE', 'style'=>'width:200;background-color:#002060; color:#fff'),
				array('data' =>'EMAIL', 'style'=>'width:500;background-color:#002060; color:#fff'),
				array('data' =>'TEAM NAME', 'style'=>'width:200;background-color:#FF0000; color:#fff'),
				array('data' =>'STUDENT NAME', 'style'=>'width:200;background-color:#FF0000; color:#fff'),
				array('data' =>'AGE', 'style'=>'width:200;background-color:#FF0000; color:#fff'),
				array('data' =>'GENDER', 'style'=>'width:200;background-color:#FF0000; color:#fff'),
				array('data' =>'GRADE', 'style'=>'width:200;background-color:#FF0000; color:#fff'),
				array('data' =>'COURSE COMPLETION %', 'style'=>'width:200;background-color:#37474F; color:#fff'),
				array('data' =>'COURSE STATUS', 'style'=>'width:200;background-color:#37474F; color:#fff'),
				array('data' =>'IDEA STATUS', 'style'=>'width:200;background-color:#37474F; color:#fff')
				// array('data' =>'IN PROGRESS COUNT', 'style'=>'width:200;background-color:#37474F; color:#fff'),
				// array('data' =>'NOT STARTED COUNT', 'style'=>'width:200;background-color:#37474F; color:#fff'),
				// array('data' =>'IDEAS SUBMISSION STATUS', 'style'=>'width:200;background-color:#5D4037; color:#fff'),
				// array('data' =>'IDEAS SUBMITTED COUNT', 'style'=>'width:200;background-color:#5D4037; color:#fff'),
				// array('data' =>'IDEAS DRAFT COUNT', 'style'=>'width:200;background-color:#5D4037; color:#fff'),
				// array('data' =>'IDEAS NOT STARTED COUNT', 'style'=>'width:200;background-color:#5D4037; color:#fff')
			);
			$set_heading = array_merge($set_heading, $set_heading1);
			$this->table->set_heading($set_heading);
			$i=1; $total = 0;
			foreach ($res as $res1){
				$add_row = array($i++,$res1->organization_code,$res1->organization_name,$district);
				if($instance == 'ka'){
					array_push($add_row, $res1->category);
					array_push($add_row, $res1->block_name);
				}
				if($instance == 'ts'){
					array_push($add_row, $res1->org_type);
				}
				$per = number_format(($res1->course_status / 35) * 100,0);
				if($res1->course_status == 35){
					$course_status = array('data' => "COMPLETED",'style'=>'color:#4CAF50');
				}else if($res1->course_status == 0){
					$course_status = array('data' => "NOT STARTED",'style'=>'color:#F4511E');
				}else{
					$course_status = array('data' => "IN PROGRESS",'style'=>'color:#5179d6');
				}
				

				if($res1->idea_status == "SUBMITTED"){
					$idea_status = array('data' => "SUBMITTED",'style'=>'color:#4CAF50');
				}else if($res1->idea_status == "DRAFT"){
					$idea_status = array('data' => "DRAFT",'style'=>'color:#5179d6');
				}else{
					$idea_status = array('data' => "NOT INITIATED",'style'=>'color:#F4511E');
				}
				$add_other_fields = array($res1->mentor_name,
										$res1->mobile,
										$res1->username,
										$res1->team_name,
										$res1->student_name,
										$res1->Age,
										$res1->Gender,
										$res1->Grade,
										$per.'%',
										$course_status,
										$idea_status
										// $teams_count,
										// $students_count,
										// $lessons_status,
										// $lessons_completed_count,
										// $lessons_in_progress_count,
										// $lessons_not_started_count,
										// $ideas_status,
										// $ideas_submitted,
										// $ideas_draft,
										// $ideas_not_started
									);
				$add_row = array_merge($add_row, $add_other_fields);
				$this->table->add_row($add_row);
			}
			
			$detailsTable = $this->table->generate();
			$response =  array('op' => 'ok',
                 'file' => "data:application/vnd.ms-excel;base64,".base64_encode($detailsTable)
                 );
            die(json_encode($response));
		}else{
			redirect('reports/index');
		}
	}


	function studentIdeas(){
		$district = $this->input->post('district');
		if($district){
			$data['page_title'] = $district.' - Student PreSurvey Status';
			$res = $this->data_model->getInstitutionsList($district)->result();
			
			$table_setup = array ('table_open'=> '<table class="table table-striped table-vcenter table-hover js-dataTable-full font-size-sm"  border="1">');    
			$this->table->set_template($table_setup);
			$set_heading = array(array('data' =>'S.No', 'style'=>'width:50;background-color:#002060; color:#fff'),
								array('data' =>'UDISE CODE', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'DISTRICT', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'SCHOOL NAME', 'style'=>'width:600;background-color:#002060; color:#fff'),
								array('data' =>'HM NAME', 'style'=>'width:500;background-color:#002060; color:#fff'),
								array('data' =>'HM MOBILE', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'TEACHER NAME', 'style'=>'width:500;background-color:#002060; color:#fff'),
								array('data' =>'MOBILE', 'style'=>'width:200;background-color:#002060; color:#fff'),
								array('data' =>'EMAIL', 'style'=>'width:500;background-color:#002060; color:#fff'),
								array('data' =>'TEAMS COUNT', 'style'=>'width:200;background-color:#FF0000; color:#fff'),
								array('data' =>'IDEA SUBMISSION STATUS', 'style'=>'width:200;background-color:#FF0000; color:#fff'),
								array('data' =>'IDEA SUBMITTED COUNT', 'style'=>'width:200;background-color:#FF0000; color:#fff'),
								array('data' =>'NOT SUBMITTED COUNT', 'style'=>'width:200;background-color:#FF0000; color:#fff')
							);
			$this->table->set_heading($set_heading);
			$category = array('data' =>$category, 'style'=>'width:200;background-color:#FF0000; color:#fff');
			$block = array('data' =>$block, 'style'=>'width:200;background-color:#FF0000; color:#fff');
			if($instance == "ka"){
				array_push($set_heading, $category);
				array_push($set_heading, $block);
			}
			$i=1; $total = 0;
			foreach ($res as $res1){
				
				$teacher = $this->data_model->getTeacherDetails($res1->organization_code)->row();
				if($teacher){
					$mentor_id = $teacher->mentor_id;
					$user_id = $teacher->user_id;

					$teacher_name = array('data' => $teacher->full_name);
					$mobile = array('data' => $teacher->mobile);
					$email = array('data' => $teacher->username);
										 
					// TEAMS COUNT
					$getTeamsCount = $this->data_model->getTeamsCount($mentor_id)->row();
					if($getTeamsCount){
						if($getTeamsCount->count){
							$teams_count = $getTeamsCount->count;
							// GET IDEAS SUBMITTED COUNT
							$ideas_submitted_count = $this->data_model->getIdeasCount($mentor_id)->row()->count;		
							$ideas_not_submitted_count = $teams_count - $ideas_submitted_count;
							if($teams_count == $ideas_submitted_count){								
								$ideas_status = array('data' => "COMPLETED 🟢",'style'=>'background-color:#4CAF50; color:#000');
							}else{
								$ideas_status = array('data' => "IN PROGRESS 🟡",'style'=>'background-color:#FFF176; color:#000');
							}
						}else{
							$teams_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');	
							$ideas_status = array('data' => "NOT REGISTERED 🔴",'style'=>'background-color:#F4511E; color:#000');
							$ideas_submitted_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
							$ideas_not_submitted_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
						}
					}else{
						$teams_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
						$ideas_status = array('data' => "NOT REGISTERED 🔴",'style'=>'background-color:#F4511E; color:#000');
						$ideas_submitted_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
						$ideas_not_submitted_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
					}					 
					
				}else{
					
					$teacher_name = array('data' => "NOT REGISTERED 🔴",'style'=>'background-color:#F4511E; color:#000');
					$mobile = array('data' => "NOT REGISTERED 🔴",'style'=>'background-color:#F4511E; color:#000');
					$email = array('data' => "NOT REGISTERED 🔴",'style'=>'background-color:#F4511E; color:#000');
					$teams_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
					$ideas_status = array('data' => "NOT REGISTERED 🔴",'style'=>'background-color:#F4511E; color:#000');
					$ideas_submitted_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
					$ideas_not_submitted_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
					// $lessons_not_started_count = array('data' => "ZERO 🔴",'style'=>'background-color:#F4511E; color:#000');
				}
				
				$principal_name = ($res1->principal_name) ? $res1->principal_name : $res1->organization_name;
				$principal_mobile = ($res1->principal_mobile) ? $res1->principal_mobile : 0;

				$this->table->add_row($i++,
						$res1->organization_code,
						$res1->district,
						$res1->organization_name,
						$principal_name,
						$principal_mobile,
						$teacher_name,
						$mobile,
						$email,
						$teams_count,
						$ideas_status,
						$ideas_submitted_count,
						$ideas_not_submitted_count

				);
			}
			
			$detailsTable = $this->table->generate();
			$response =  array('op' => 'ok',
                 'file' => "data:application/vnd.ms-excel;base64,".base64_encode($detailsTable)
                 );
            die(json_encode($response));
		}else{
			redirect('reports/index');
		}
	}

	function draft($district){
        		
		$data['page_title'] = $district.' Teachers PreSurvey';
		$res = $this->data_model->getDistricts()->result();

		$table_setup = array ('table_open'=> '<table class="table table-striped table-vcenter table-hover js-dataTable-full font-size-sm"  border="1">');    
		$this->table->set_template($table_setup);
		$this->table->set_heading(
							array('data' =>'S.No', 'style'=>'width:5%;'),
							array('data' =>'District','style'=>'width:25%;'),
							array('data' =>'Institutions Count','style'=>'width:20%;')
							);
		$i=1; $total = 0;
		foreach ($res as $res1){
			$total = $total + $res1->cnt;
			$this->table->add_row($i++,
					$res1->district,
					$res1->cnt
			);
		}
		$this->table->add_row('','<b>Total</b>','<b>'.$total.'</b>');
		
		$detailsTable = $this->table->generate();
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=".$data['page_title'].".xls");
		header("Pragma: no-cache");
		header("Expires: 0"); 
		echo $detailsTable;
		
	 
	}

	
	function testChallenge(){
		$challenges = $this->data_model->getDetails('challenge_responses_17122022', null)->result();
		$update = 0; $new = 0;
		foreach($challenges as $challenges1){
			
			$team = $this->data_model->getDetailsbyfield('team_id', $challenges1->team_id,'challenge_responses')->row();
			if($team){
				$update++;
				$challenge_response_id = $team->challenge_response_id;
				$updateData = array("challenge_id" =>$challenges1->challenge_id, 
								"team_id" =>$challenges1->team_id, 
								"response" =>$challenges1->response, 
								"initiated_by" =>$challenges1->initiated_by, 
								"status" =>$challenges1->status, 
								"created_by" =>$challenges1->created_by, 
								// "created_at" =>$challenges1->created_at, 
								"updated_by" =>$challenges1->updated_by, 
								"updated_at" =>$challenges1->updated_at, 
								"sdg" =>$challenges1->sdg, 
								"others" =>$challenges1->others, 
								"evaluated_by" =>$challenges1->evaluated_by, 
								"evaluated_at" =>$challenges1->evaluated_at, 
								"submitted_at" =>$challenges1->submitted_at, 
								"evaluation_status" =>$challenges1->evaluation_status
							);
				$res = $this->data_model->updateDetails($challenge_response_id, $updateData, 'challenge_responses');
				
			}else{
				$new++;
				$insertData = array("challenge_id" =>$challenges1->challenge_id, 
								"team_id" =>$challenges1->team_id, 
								"response" =>$challenges1->response, 
								"initiated_by" =>$challenges1->initiated_by, 
								"status" =>$challenges1->status, 
								"created_by" =>$challenges1->created_by, 
								"created_at" =>$challenges1->created_at, 
								"updated_by" =>$challenges1->updated_by, 
								"updated_at" =>$challenges1->updated_at, 
								"sdg" =>$challenges1->sdg, 
								"others" =>$challenges1->others, 
								"evaluated_by" =>$challenges1->evaluated_by, 
								"evaluated_at" =>$challenges1->evaluated_at, 
								"submitted_at" =>$challenges1->submitted_at, 
								"evaluation_status" =>$challenges1->evaluation_status
							);
				$res = $this->data_model->insertDetails('challenge_responses',$insertData);
				
			}


			// 
			// // print_r($insertData);
			// $res = $this->data_model->insertDetails('challenge_responses',$insertData);
			// $res = $this->data_model->updateDetails(1375, $details, 'challenge_responses');
			//     if($res) {
            //         echo $challenges1->challenge_response_id." SUCCESS";
            //     }else {
            //         echo $challenges1->challenge_response_id." FAIl";
            //     } 
			// echo "<br>";
		}
		echo $update;
		echo $new;
	}

	function testOrg(){
		$mentor_id = 96;
		$ideas_res = $this->data_model->getIdeasCount($mentor_id)->result();
		echo $this->db->last_query();
		print_r($ideas_res); 
		die;
		$challenges = $this->data_model->getDetails('organizations', null)->result();
		$update = 0; $new = 0;
		foreach($challenges as $challenges1){
			
			$team = $this->data_model->getDetailsbyfield('team_id', $challenges1->team_id,'challenge_responses')->row();
			if($team){
				$update++;
				$challenge_response_id = $team->challenge_response_id;
				$updateData = array("challenge_id" =>$challenges1->challenge_id, 
								"team_id" =>$challenges1->team_id, 
								"response" =>$challenges1->response, 
								"initiated_by" =>$challenges1->initiated_by, 
								"status" =>$challenges1->status, 
								"created_by" =>$challenges1->created_by, 
								// "created_at" =>$challenges1->created_at, 
								"updated_by" =>$challenges1->updated_by, 
								"updated_at" =>$challenges1->updated_at, 
								"sdg" =>$challenges1->sdg, 
								"others" =>$challenges1->others, 
								"evaluated_by" =>$challenges1->evaluated_by, 
								"evaluated_at" =>$challenges1->evaluated_at, 
								"submitted_at" =>$challenges1->submitted_at, 
								"evaluation_status" =>$challenges1->evaluation_status
							);
				$res = $this->data_model->updateDetails($challenge_response_id, $updateData, 'challenge_responses');
				
			}else{
				$new++;
				$insertData = array("challenge_id" =>$challenges1->challenge_id, 
								"team_id" =>$challenges1->team_id, 
								"response" =>$challenges1->response, 
								"initiated_by" =>$challenges1->initiated_by, 
								"status" =>$challenges1->status, 
								"created_by" =>$challenges1->created_by, 
								"created_at" =>$challenges1->created_at, 
								"updated_by" =>$challenges1->updated_by, 
								"updated_at" =>$challenges1->updated_at, 
								"sdg" =>$challenges1->sdg, 
								"others" =>$challenges1->others, 
								"evaluated_by" =>$challenges1->evaluated_by, 
								"evaluated_at" =>$challenges1->evaluated_at, 
								"submitted_at" =>$challenges1->submitted_at, 
								"evaluation_status" =>$challenges1->evaluation_status
							);
				$res = $this->data_model->insertDetails('challenge_responses',$insertData);
				
			}


			// 
			// // print_r($insertData);
			// $res = $this->data_model->insertDetails('challenge_responses',$insertData);
			// $res = $this->data_model->updateDetails(1375, $details, 'challenge_responses');
			//     if($res) {
            //         echo $challenges1->challenge_response_id." SUCCESS";
            //     }else {
            //         echo $challenges1->challenge_response_id." FAIl";
            //     } 
			// echo "<br>";
		}
		echo $update;
		echo $new;
	}


	function surveyReprots(){
		// $instance = $this->session->userdata('instance');
		$survey_id = 2;
		$questons = $this->data_model->getQuestions($survey_id)->result();
		$quesArray = array();
		foreach($questons as $questons1){
			array_push($quesArray,$questons1->question);
		}
		// $quesArray = array('Congratulations! We are excited to see you begin your problem-solving journey. How are you feeling right now?',
		// 					'How confident are you talking to new people in your community / surroundings?',
		// 					'How do you feel about going to school everyday?',
		// 					'How well do you know about the people and places in your community/ surroundings?',
		// 					'What do you think about working together in a team to complete a task?',
		// 					'Did you participate in any online course before?',
		// 					'Did you participate in any science exhibition or  worked on projects before?',
		// 					'Do you enjoy working in a team and making your friends feel better?',
		// 					'Do you enjoy talking to a group of students or giving a speech on stage?',
		// 					'Are you aware of Sustainable Development Goals?');
		$res = $this->data_model->getSurveyReports($survey_id)->result();
		// echo "<pre>";
		// print_r($questons);die;
		$table_setup = array ('table_open'=> '<table class="table table-striped table-vcenter table-hover js-dataTable-full font-size-sm"  border="1">');    
		$this->table->set_template($table_setup);
		$headings = array('No','Student Name');
		$headings = array_merge($headings,$quesArray);
		// print_r($quesArray); die;
		array_push($headings,'Given Date');
		$this->table->set_heading($headings);
		
		$i = 1;
		foreach($res as $res1){
			$response = json_decode($res1->response);
 
			$res_array = array($i++,
			$res1->full_name);
			foreach($response as $response1){
				array_push($res_array,$response1->selected_option);	
			}
			array_push($res_array,date('d-m-Y h:i A', strtotime($res1->created_at)));	
			$this->table->add_row($res_array);

			// echo "<br/>";
		}
		echo $detailsTable = $this->table->generate();
		
		// foreach($challenges as $challenges1){
			
		// 	$team = $this->data_model->getDetailsbyfield('team_id', $challenges1->team_id,'challenge_responses')->row();
		// 	if($team){
		// 		$update++;
		// 		$challenge_response_id = $team->challenge_response_id;
		// 		$updateData = array("challenge_id" =>$challenges1->challenge_id, 
		// 						"team_id" =>$challenges1->team_id, 
		// 						"response" =>$challenges1->response, 
		// 						"initiated_by" =>$challenges1->initiated_by, 
		// 						"status" =>$challenges1->status, 
		// 						"created_by" =>$challenges1->created_by, 
		// 						// "created_at" =>$challenges1->created_at, 
		// 						"updated_by" =>$challenges1->updated_by, 
		// 						"updated_at" =>$challenges1->updated_at, 
		// 						"sdg" =>$challenges1->sdg, 
		// 						"others" =>$challenges1->others, 
		// 						"evaluated_by" =>$challenges1->evaluated_by, 
		// 						"evaluated_at" =>$challenges1->evaluated_at, 
		// 						"submitted_at" =>$challenges1->submitted_at, 
		// 						"evaluation_status" =>$challenges1->evaluation_status
		// 					);
		// 		$res = $this->data_model->updateDetails($challenge_response_id, $updateData, 'challenge_responses');
				
		// 	}else{
		// 		$new++;
		// 		$insertData = array("challenge_id" =>$challenges1->challenge_id, 
		// 						"team_id" =>$challenges1->team_id, 
		// 						"response" =>$challenges1->response, 
		// 						"initiated_by" =>$challenges1->initiated_by, 
		// 						"status" =>$challenges1->status, 
		// 						"created_by" =>$challenges1->created_by, 
		// 						"created_at" =>$challenges1->created_at, 
		// 						"updated_by" =>$challenges1->updated_by, 
		// 						"updated_at" =>$challenges1->updated_at, 
		// 						"sdg" =>$challenges1->sdg, 
		// 						"others" =>$challenges1->others, 
		// 						"evaluated_by" =>$challenges1->evaluated_by, 
		// 						"evaluated_at" =>$challenges1->evaluated_at, 
		// 						"submitted_at" =>$challenges1->submitted_at, 
		// 						"evaluation_status" =>$challenges1->evaluation_status
		// 					);
		// 		$res = $this->data_model->insertDetails('challenge_responses',$insertData);
				
		// 	}


		// 	// 
		// 	// // print_r($insertData);
		// 	// $res = $this->data_model->insertDetails('challenge_responses',$insertData);
		// 	// $res = $this->data_model->updateDetails(1375, $details, 'challenge_responses');
		// 	//     if($res) {
        //     //         echo $challenges1->challenge_response_id." SUCCESS";
        //     //     }else {
        //     //         echo $challenges1->challenge_response_id." FAIl";
        //     //     } 
		// 	// echo "<br>";
		// }
		// echo $update;
		// echo $new;
	}
	 
	
	
}