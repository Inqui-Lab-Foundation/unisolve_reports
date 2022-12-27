<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Globals {

    public function colleges(){
       return array('ndc' => 'NDC',
                    'ncet' => 'NCET',
                    'ncms' => 'NCMS',
                    'pucyel' => 'PUC-YEL',
                    'puccvp' => 'PUC-CVP'
        );
    }

    public function userRoles(){
        return array('1' => 'Super Admin',
                     '2' => 'Management',
                     '3' => 'FM Finance',
                     '4' => 'Manager',
                     '5' => 'Staff'
         );
     }

    public function romanYears(){
        return array('1' => 'I',
                     '2' => 'II',
                     '3' => 'III',
                     '4' => 'IV',
                     '5' => 'V'
         );
     }

    public function designations() {
        $designations = array('1' => 'Professor',
                              '2' => 'Associate Professor',
                              '3' => 'Assistant Professor',
                              '4' => 'Asst. Director in Phy.Edn.',
                              '5' => 'Librarian'
                              );
        return $designations;   
    }
    
    public function departments(){
        return array("Kannada" => "Kannada",
                     "Sanskrit" => "Sanskrit",
                     "Hindi" => "Hindi",
                     "English" => "English",
                     "Physics" => "Physics",
                     "Chemistry" => "Chemistry",
                     "Mathematics" => "Mathematics",
                     "Computer Science" => "Computer Science",
                     "Botany" => "Botany",
                     "Zoology" => "Zoology",
                     "Microbiology" => "Microbiology",
                     "Biotechnology" => "Biotechnology",
                     "History" => "History",
                     "Economics" => "Economics",
                     "Sociology" => "Sociology",
                     "Journalism" => "Journalism",
                     "Psychology" => "Psychology",
                     "Commerce" => "Commerce",
                     "Bus. Admn." => "Bus. Admn.",
                     "Library" => "Library",
                     "Physical Edn." => "Physical Edn.",
                     "B.Voc-Info. Tech." => "B.Voc-Info. Tech.",
                     "B.Voc-Retail Mgmt." => "B.Voc-Retail Mgmt.",
                     "M.Com" => "M.Com",
                     "M.Sc Maths" => "M.Sc Maths",
                     "M.Sc Organic Chemistry" => "M.Sc Organic Chemistry"
                    );
    }
    
    public function reportTypes(){
        return array("1" => "Institutions List",
                    "2" => "Teachers Registration Status",
                    "3" => "Teachers PreSurvey Status",
                    );
    }
    public function publicationTypes() {
        $details = array('1' => 'National Journal', 
                        '2' => 'International Journal', 
                        '3' => 'National Conference', 
                        '4' => 'International Conference');
        return $details;   
    }
    
    public function activityTypes() {
        return array('1' => 'Technical Events', 
                    '2' => 'Industry Interaction', 
                    '3' => 'Cocurricular Activities', 
                    '4' => 'Extra Curricular Activities');
    }
     
    public function academicYears($start) {
        $result = array(); $end = date('Y');
        for($start; $start <= $end; $start++){
            $startInc = $start + 1;
            $ay = $start.'-'.$startInc;
            $result[$ay] = $ay;
        }
        $result = array_reverse($result);
        return $result;   
    }

    public function admissionYears($start) {
        $result = array(); $end = date('Y');
        for($start; $start <= $end; $start++){
            $startInc = $start + 1;
            $startIncShort = substr( $startInc, -2 );
            $ay = $start.'-'.$startIncShort;
            $result[$ay] = $ay;
        }
        $result = array_reverse($result);
        return $result;   
    }
    
    public function yearSem() {
        return array('1-1SEM/2SEM' => 'I Year -  1SEM/2SEM', 
                    '2-3SEM/4SEM' => 'II Year - 3SEM/4SEM', 
                    '3-5SEM/6SEM' => 'III Year - 5SEM/6SEM');
    }

    public function newsDisplay(){
        return array(
            "1" => "Notification",
            "2" => "Exam Timetable",
            "3" => "Exam Circular",
            "4" => "Calendar of Events",
            "5" => "Tender"
        );
    }
    
    public function staffType(){
        return array(
            "1" => "Regular",
            "2" => "Tenure",
            "3" => "Visiting",
            "4" => "Guest"
        );
    }
    
    public function subjectType(){
        return array(
            "1" => "Core",
            "2" => "Practical",
            "3" => "Open Elective",
            "4" => "Language",
            "5" => "SEC"
            
        );
    }
    
    public function program() {
        $designations = array('1' => 'SSC/CBSE',
                              '2' => 'HSC/12TH',
                              '3' => 'Diploma',
                              '4' => 'Certification',
                              '5' => 'Under Graduation',
                              '6' => 'Post Graduation',
                              '7' => 'Ph.D',
                              '8' => 'Other'
                              );
        return $designations;   
    }

    public function feeCategory(){
        return array("1" => "Tuition Fee", 
                       "2" => "Hostel Fee", 
                       "3" => "Transportation Fee");
    }
    
    public function categories(){
        return array("GM"=>"GM","SC"=>"SC","ST"=>"ST","C-1"=>"C-1","2A"=>"2A","2B"=>"2B","3A"=>"3A","3B"=>"3B");
    }
    
    public function transactionTypes(){
        return array("1" => "Online Payment", "2"=>"Cheque/DD", "3"=>"NEFT/IMPS Transfer", "4" => "Cash");    
    }
    
    function getIndianCurrency(float $number)
    {
        $no = floor($number);
        $decimal = round($number - $no, 2) * 100;
        $decimal_part = $decimal;
        $hundred = null;
        $hundreds = null;
        $digits_length = strlen($no);
        $decimal_length = strlen($decimal);
        $i = 0;
        $str = array();
        $str2 = array();
        $words = array(0 => '', 1 => 'One', 2 => 'Two',
            3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
            7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
            13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
            70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
        $digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
        
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }

        $d = 0;
        while( $d < $decimal_length ) {
            $divider = ($d == 2) ? 10 : 100;
            $decimal_number = floor($decimal % $divider);
            $decimal = floor($decimal / $divider);
            $d += $divider == 10 ? 1 : 2;
            if ($decimal_number) {
                $plurals = (($counter = count($str2)) && $decimal_number > 9) ? 's' : null;
                $hundreds = ($counter == 1 && $str2[0]) ? ' and ' : null;
                @$str2 [] = ($decimal_number < 21) ? $words[$decimal_number].' '. $digits[$decimal_number]. $plural.' '.$hundred:$words[floor($decimal_number / 10) * 10].' '.$words[$decimal_number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str2[] = null;
        }
        
        $Rupees = implode('', array_reverse($str));
        $paise = implode('', array_reverse($str2));
        $paise = ($decimal_part > 0) ? $paise . ' Paise' : '';
        return ($Rupees ? $Rupees . 'Rupees Only' : '') . $paise;
    }
    
    public function discriminatorValues(){
        return array("NB"=>"Net Banking",
                     "CC"=>"Credit Cards",
                     "DC"=>"Debit Card",
                     "MX"=>"American Express",
                     "MV"=>"Cards BharatQR",
                     "PL"=>"Paypal",
                     "EZ"=>"Ezeepay",
                     "CH"=>"Challan",
                     "NR"=>"Neft",
                     "UP"=>"Unified Payment Interface",
                     "MW"=>"Wallet",
                     "LT"=>"Loyalty",
                     "EP"=>"epay Later",
                     "NA"=>"NA"
                    );
    }

    public function feeTypes(){
        $details = array("Tuition Fee", 
                        "Admissions Fee",
                        "Lab Fee", 
                        "Medical Exam Fee",
                        "T.C. Fee", 
                        "Study Certificate",
                        "Reading Room Fee",
                        "Sports Fee", 
                        "Library Fee", 
                        "SWF Fee", 
                        "TWF Fee", 
                        "Flag Fee", 
                        "Indian Red Cross Society Fee", 
                        "The Bharat Scouts And Guidess", 
                        "Association Fee", 
                        "Magazine Fee", 
                        "Identity Card Fee", 
                        "Group Insurances", 
                        "Student FeedBack", 
                        "IES Fee", 
                        "Placement Fee",
                        "NSS Fee", 
                        "Maintenance Charges",
                        "Admissions Fee(U)", 
                        "Registration Fee" ,
                        "Processing Fee", 
                        "Sports Dev. Fee", 
                        "Cultural Activities Fee", 
                        "OMR Application Fee", 
                        "Management Fee",
                        "Eligibility Fee", 
                        "Alumni Association Fee");
        return $details;
    }
    
    public function currentAcademicYear(){
      return "2022-2023";
    }
    

    public function transportationRoutes(){
        return array("1"=>"Nandi Croos, Yaluvahalli,Devi settyhalli",
                    "2"=>"Kupalli, Nandi",
                    "3"=>"Chikkaballapur,Vijayapura,Devanahalli",
                    "4"=>"Nandhi Upachar",
                    "5"=>"Neeleri, Malegenahalli,",
                    "6"=>"Manchanahalli, Posettihalli, Siddalagatta, Jangam Kote Cross, Sulibele,Nallur Cross, Kannamangala Gate",
                    "7"=>"Anagatha, Kuduvathi,Karahalli Cross",
                    "8"=>"Alur Duddanahalli,Chapparakalu,Vishwanathapura",
                    "9"=>"Hoskote,H Cross",
                    "10"=>"Doddaballapur,Yelahanka,Hunsemaranahalli Gate",
                    "11"=>"Kaiwara Cross,Chintamani",
                    "12"=>"Gouribidnur,Navarang,Yaswanthpur,Nagavara"
                );
    }

    public function transportationRouteFees(){
        return array("1"=>"8000",
                    "2"=>"12000",
                    "3"=>"17000",
                    "4"=>"18000",
                    "5"=>"20000",
                    "6"=>"20500",
                    "7"=>"21000",
                    "8"=>"21500",
                    "9"=>"23000",
                    "10"=>"25000",
                    "11"=>"26500",
                    "12"=>"28500"
                );
    }

    public function hostelRoomTypes(){
        return array("1"=>"3 Common Bathroom (Boys)",
                    "2"=>"3 Attach Bathroom (Boys)",
                    "3"=>"3 Attach Bathroom (Girls)"
            );
    }

    public function hostelRoomFees(){
        return array("1"=>"100000",
                    "2"=>"110000",
                    "3"=>"100000"
            );
    }
}
?>