<main id="main-container">
    <!-- Hero -->
    <div class="bg-image" style="background-image: url('<?=base_url();?>assets/img/ngi_backgroud.jpg');">
        <div class="bg-black-50">
            <div class="content content-full text-center">
                <div class="my-3">
                    <img class="img-avatar img-avatar-thumb" src="<?=base_url();?>assets/img/avatar.jpg" alt="">
                </div>
                <h1 class="h2 text-white mb-0"><?=$personal_details->student_name;?></h1>
                <h5 class="h5 text-white mb-0"><?=$personal_details->reg_no;?></h5>
            </div>
        </div>
    </div>
    <!-- END Hero -->
    <div class="content">

        <?php if($this->session->flashdata('message')){?>
        <div class="alert <?=$this->session->flashdata('status');?>" id="msg">
            <?php echo $this->session->flashdata('message')?>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col-md-12 text-right">
                <?php // echo anchor('','Update Fee Details','class="btn btn-danger btn-sm my-2"'); ?>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="block-header bg-color5">
                <h3 class="block-title text-white">Admission Details</h3>
                <div class="block-options">
                </div>
            </div>
            <div class="block-content">
                <table class="table table-hover table-bordered">
                    <tr>
                        <td width="20%" class="text-right">Admission Year :</td>
                        <th width="30%"><?=($personal_details->admission_year)?$personal_details->admission_year:'-';?>
                        </th>
                        <td width="20%" class="text-right">Course & Combination :</td>
                        <th width="30%">
                            <?php $course = ($personal_details->course) ? $personal_details->course : '-';
                                  $combination = ($personal_details->combination) ? ' - '.$personal_details->combination : '';
                                  echo $course.$combination;
                            ?>
                        </th>
                    </tr>

                </table>
            </div>
        </div>

        <div class="block block-rounded">
            <div class="block-header bg-color5">
                <h3 class="block-title text-white">Personal Details</h3>
                <div class="block-options">
                    <?php // echo anchor('student/update_personal_details','<i class="fa fa-edit"></i> UPDATE','class="btn btn-alt-info btn-sm"');?>
                </div>
            </div>
            <div class="block-content">
                <table class="table table-hover table-bordered">
                    <tr>
                        <td width="20%" class="text-right">Father Mobile :</td>
                        <th width="30%">
                            <?=($personal_details->father_mobile) ? $personal_details->father_mobile : "-";?></th>
                        <td width="20%" rowspan='5' class="text-right"> Address :</td>
                        <th width="30%" rowspan='5'>
                            <?php 
                                echo ($personal_details->village) ? $personal_details->village : null;
                                echo ($personal_details->post) ? '<br>'.$personal_details->post : null;
                                echo ($personal_details->taluk) ? '<br>'.$personal_details->taluk : null;
                                echo ($personal_details->district) ? '<br>'.$personal_details->district : null;
                                echo ($personal_details->state) ? '<br>'.$personal_details->state : null;
                            ?>
                        </th>
                    </tr>
                    <tr>
                        <td class="text-right">Mobile :</td>
                        <th><?=($personal_details->mobile) ? $personal_details->mobile : "-";?></th>
                    </tr>
                    <tr>
                        <td width="20%" class="text-right">Official Email :</td>
                        <th width="30%">
                            <?=($personal_details->official_email) ? $personal_details->official_email : "-";?></th>
                    </tr>
                    <tr>
                        <td width="20%" class="text-right">Personal Email :</td>
                        <th width="30%">
                            <?=($personal_details->personal_email) ? $personal_details->personal_email : "-";?></th>
                    </tr>
                    <tr>
                        <td width="20%" class="text-right">Date of Birth :</td>
                        <th width="30%">
                            <?php echo ($personal_details->date_of_birth != "0000-00-00") ? date('d-m-Y', strtotime($personal_details->date_of_birth)) : '-';?>
                        </th>
                    </tr>
                    <tr>

                    </tr>

                </table>
            </div>
        </div>

        <div class="block block-rounded">
            <div class="block-header bg-color5">
                <h3 class="block-title text-white"> Fee Details </h3>
            </div>
            <div class="block-content">
                <?php if($fee_details) { ?>
                <?php $i = 1;
                    foreach($fee_details as $fee_details1){
                        $fixed_fee = ($fee_details1->fixed_fee) ? $fee_details1->fixed_fee : '0';
                        $additional_fee = ($fee_details1->additional_fee) ? $fee_details1->additional_fee : '0';
                        $concession_fee = ($fee_details1->concession_fee) ? $fee_details1->concession_fee : '0';
                        $concession_type = ($fee_details1->concession_type) ? "(".$fee_details1->concession_type.")" : null;
                        $net_fee = ($fee_details1->net_fee) ? $fee_details1->net_fee : '0';
                        $installment_1_fee = ($fee_details1->net_fee) ? $fee_details1->installment_1_fee : '0';
                        $installment_2_fee = ($fee_details1->net_fee) ? $fee_details1->installment_2_fee : '0';
                        if(array_key_exists($fee_details1->current_year,$paidFees)){
                            $total_pay = $paidFees[$fee_details1->current_year];
                        }else{
                            $total_pay = 0;
                        }
                        $balance_fee = $net_fee - $total_pay;

                        $hostel_deposit_fee = ($fee_details1->hostel_deposit_fee) ? $fee_details1->hostel_deposit_fee : '0';
                        $hostel_fee = ($fee_details1->hostel_fee) ? $fee_details1->hostel_fee : 0;
                        $hostel_total_fee = $hostel_deposit_fee + $hostel_fee;
                        $hostel_paid_fee = 0;
                        $hostel_balance_fee = $hostel_total_fee + $hostel_paid_fee;
                        $transportation_fee = ($fee_details1->transportation_fee) ? $fee_details1->transportation_fee : 0;
                        $transportation_paid_fee = 0;
                        $transportation_balance_fee = $transportation_fee + $transportation_paid_fee;
                        
                ?>
                <div id="my-block" class="block block-rounded block-bordered">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            <?=$fee_details1->academic_year.' ('.$romanYears[$fee_details1->current_year].'-Year & '.$fee_details1->current_sem.')';?>
                        </h3>
                        <div class="block-options">
                            <?php
                                echo anchor('admin/editStudentFee/'.$fee_details1->id, '<i class="si si-pencil"></i> Edit Fee Details','class="btn btn-dark btn-xs"');
                            ?>
                            <button type="button" class="btn-block-option" data-toggle="block-option"
                                data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
                            <button type="button" class="btn-block-option" data-toggle="block-option"
                                data-action="content_toggle"><i class="si si-arrow-up"></i></button>
                        </div>
                    </div>
                    <div class="block-content">
                        <table class="table table-bordered">
                            <tr class="bg-gray-light">
                                <th width="40%" colspan="2">Details</th>
                                <th width="20%" class="text-right pr-5">Total Amount</th>
                                <th width="20%" class="text-right pr-5">Paid Amount</th>
                                <th width="20%" class="text-right pr-5">Balance Amount</th>
                            </tr>
                            <tr>
                                <th colspan="2">Tuition Fee </th>
                                <th class="text-right pr-5 text-primary"><?=number_format($net_fee,0);?></th>
                                <th class="text-right pr-5 text-success"><?=number_format($total_pay,0);?></th>
                                <th class="text-right pr-5 text-danger"><?=number_format($balance_fee,0);?></th>
                            </tr>
                            <tr>
                                <td class="pl-5">Fixed Fee </td>
                                <td><?=number_format($fixed_fee,0);?></td>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="pl-5">Additional Fee </td>
                                <td><?=number_format($additional_fee,0);?></td>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="pl-5">Consession Fee </td>
                                <td><?=number_format($concession_fee,0);?></td>
                                <td colspan="3">&nbsp;</td>
                            </tr>

                            <tr>
                                <th colspan="2">Hostel Fee
                                    <?php echo ($fee_details1->hostel_block) ? "(".$hostelRoomTypes[$fee_details1->hostel_block].")" : ""; ?>
                                </th>
                                <th class="text-right pr-5 text-primary"><?=number_format($hostel_total_fee,0);?></th>
                                <th class="text-right pr-5 text-success"><?=number_format($hostel_paid_fee,0);?></th>
                                <th class="text-right pr-5 text-danger"><?=number_format($hostel_balance_fee,0);?></th>
                            </tr>
                            <tr>
                                <td class="pl-5">Deposit Fee </td>
                                <td><?=number_format($hostel_deposit_fee,0);?></td>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="pl-5">Hostel Fee </td>
                                <td><?=number_format($hostel_fee,0);?></td>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                                <th colspan="2">Transportation Fee
                                    <?php echo ($fee_details1->transportation_route) ? "<br/> (".$transportationRoutes[$fee_details1->transportation_route].")" : ""; ?>
                                </th>
                                <th class="text-right pr-5 text-primary"><?=number_format($transportation_fee,0);?></th>
                                <th class="text-right pr-5 text-success"><?=number_format($transportation_paid_fee);?>
                                </th>
                                <th class="text-right pr-5 text-danger"><?=number_format($transportation_balance_fee);?>
                                </th>
                            </tr>
                            <tr>
                                <td class="pl-5"> Transportation Fee </td>
                                <td><?=number_format($transportation_fee,0);?></td>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                        </table>

                    </div>
                </div>
                <?php  } ?>
                <?php }else{ ?>
                <div class="col-12 text-center">
                    <img src="<?=base_url();?>assets/img/student_books.jpg" alt="NoData" title="NoData"
                        class="wd-200" />
                    <h6>Fee details are not found.</h6>
                </div>
                <?php } 
                // print_r($fee_details); ?>

            </div>
        </div>

        <div class="block block-rounded">
            <div class="block-header bg-color5">
                <h3 class="block-title text-white"> Fees Receipts </h3>
            </div>
            <div class="block-content">
                <?php if($transactions){ ?>
                <table class="table table-hover">
                    <thead class="bg-gray-lighter">
                        <tr>
                            <th class="font-size-xs" width="5%">No</th>
                            <th class="font-size-xs" width="5%">Year</th>
                            <th class="font-size-xs" width="20%">Receipt No</th>
                            <th class="font-size-xs" width="10%">Date</th>
                            <th class="font-size-xs" width="15%">Mode of Payment</th>
                            <th class="font-size-xs" width="15%">Reference</th>
                            <th class="font-size-xs" width="10%">Amount</th>
                            <th class="font-size-xs" width="15%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                            foreach($transactions as $transactions1){
                                
                                $trans = null; $ref = null;
                                $trans = '<span class="font-weight-bold">'.$transactionTypes[$transactions1->mode_of_payment]."</span>";
                			        
                			    if($transactions1->mode_of_payment == "1"){
                                    $ref = $transactions1->ref_number;
                			    }else if($transactions1->mode_of_payment == "2"){
                			        $ref = "No:".$transactions1->ref_number.'<br> Dt:'.date('d-m-Y', strtotime($transactions1->ref_date)).' <br> Bank: '.$transactions1->bank_branch;
                			    }else if($transactions1->mode_of_payment == "3"){
                			        $ref = "No:".$transactions1->ref_number.'<br> Dt:'.date('d-m-Y', strtotime($transactions1->ref_date)).' <br> Bank: '.$transactions1->bank_branch;
                			    }else if($transactions1->mode_of_payment == "4"){
                			        $ref = null; 
                			    }else{
                                    $ref = "No:".$transactions1->ref_number.'<br> Dt:'.date('d-m-Y', strtotime($transactions1->ref_date)).' <br> Bank: '.$transactions1->bank_branch;
                			    }
                			    
                			    if($transactions1->transaction_status == 1){
                			        $transaction_status = "<span class='font-size-xs font-weight-bold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success'>Verified</span>";
                			    }else if($transactions1->transaction_status == 2){
                			        $transaction_status = "<span class='font-size-xs font-weight-bold d-inline-block py-1 px-3 rounded-pill bg-warning-light text-warning'>Cancelled</span><br><span class='text-dark'>".nl2br($transactions1->remarks)."</span>";
                			    }else{
                			        $transaction_status = "<span class='font-size-xs font-weight-bold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info'>Processing</span> <br>".anchor('admin/approvePayment/'.$transactions1->id,'Approve','class="btn btn-info btn-sm mt-2"').' '.anchor('admin/deletePayment/'.$transactions1->id.'/'.$transactions1->reg_no,'Delete','class="btn btn-danger btn-sm mt-2"');
                			    }
                                $encryptParms = $transactions1->id.','.$transactions1->reg_no;
                                $encryptTransacitonID = base64_encode($this->encrypt->encode($encryptParms));
                			    $receipt_no = ($transactions1->receipt_no) ? anchor('admin/downloadReceipt/'.$encryptTransacitonID, $transactions1->receipt_no,'class="font-weight-bold"') : "-";
                			    $receipt_date = ($transactions1->receipt_date != "0000-00-00") ? date('d-m-Y', strtotime($transactions1->receipt_date)) : "-";
                                echo "<tr>";
                                echo "<td>".$i++.".</td>";
                                echo "<td>".$transactions1->year."</td>";
                                echo "<td>".$receipt_no."</td>";
                                echo "<td>".$receipt_date."</td>";
                                echo "<td>".$trans."</td>";
                                echo "<td>".$ref."</td>";
                                echo "<td>".number_format($transactions1->amount)."</td>";
                                echo "<td>".$transaction_status."</td>";
                                echo "</tr>";
                            }
                        ?>

                    </tbody>
                </table>
                <?php } else { ?>
                <div class="text-center">
                    <i class="fa fa-receipt fa-4x text-gray"></i>
                    <h6 class='text-center mt-2'>No transactions found.</h6>
                </div>

                <?php } ?>
            </div>
        </div>

        <h6 class="text-bold text-danger"> Note: If any issues/changes in above mentioned fee details, please contact
            Accounts Team at the earliest. </h6>
    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<script>
$(document).ready(function() {
    // $("#successMessage").show().delay(5000).fadeOut();
    var base_url = '<?php echo base_url(); ?>';

});
</script>