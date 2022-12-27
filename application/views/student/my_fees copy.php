<main id="main-container">

    <div class="content">

        <?php if($this->session->flashdata('message')){?>
        <div class="alert <?=$this->session->flashdata('status');?>" id="msg">
            <?php echo $this->session->flashdata('message')?>
        </div>
        <?php } ?>

        <div class="block block-rounded">
            <div class="block-header bg-color5">
                <h3 class="block-title text-white"> Fee Details </h3>
            </div>
            <div class="block-content">
                <?php if($details) { ?>
                <table class="table table-hover">
                    <thead class="bg-gray-lighter">
                        <tr>
                            <th width="2%">#</th>
                            <th width="8%">AY</th>
                            <th width="18%">Year & Sem</th>

                            <th width="10%" class='text-right'>Fixed Fee</th>
                            <th width="15%" class='text-right'>Additional Fee</th>
                            <!-- <th width="15%" class='text-center'>Concession Type</th> -->
                            <th width="25%" class='text-right'>Concession Fee</th>

                            <th width="10%" class='text-right'>Net Fee</th>
                            <th width="12%" class='text-right'>Balance Fee</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                                foreach($details as $details1){
                                    
                                    $fixed_fee = ($details1->fixed_fee) ? $details1->fixed_fee : '0';
                                    $additional_fee = ($details1->additional_fee) ? $details1->additional_fee : '0';
                                    $concession_fee = ($details1->concession_fee) ? $details1->concession_fee : '0';
                                    $concession_type = ($details1->concession_type) ? "(".$details1->concession_type.")" : null;
                                    $net_fee = ($details1->net_fee) ? $details1->net_fee : '0';
                                    $installment_1_fee = ($details1->net_fee) ? $details1->installment_1_fee : '0';
                                    $installment_2_fee = ($details1->net_fee) ? $details1->installment_2_fee : '0';
                                    if(array_key_exists($details1->current_year,$paidFees)){
                                        $total_pay = $paidFees[$details1->current_year];
                                    }else{
                                        $total_pay = 0;
                                    }
                                    $balance_fee = $net_fee - $total_pay;
                                    
                                    echo "<tr>";
                                    echo "<td>".$i++.".</td>";
                                    echo "<td>".$details1->academic_year."</td>";
                                    echo "<td>".$romanYears[$details1->current_year].'-Year & '.$details1->current_sem."</td>";
                                    // echo "<td>".$details1->current_sem."</td>";
                                    echo "<td class='text-right'>".number_format($fixed_fee,0)."</td>";
                                    echo "<td class='text-right'>".number_format($additional_fee,0)."</td>";
                                    // echo "<td>".$concession_type."</td>";
                                    echo "<td class='text-right'>".$concession_type.' '.number_format($concession_fee,0)."</td>";
                                    echo "<td class='font-weight-bold text-right'>".number_format($net_fee,0)."</td>";
                                    echo "<td class='font-weight-bold text-right'>".number_format($balance_fee,0)."</td>";
                                    echo "</tr>";
                                    
                                    if($details1->installment_1_status){
                                        $pay_now1 = "<span class='text-success font-weight-bold'><i class='fa fa-check'></i> Paid</span>";
                                    }else{
                                        $encryptPrams1 =  $installment_1_fee.','.$details1->current_year.',1';
                                        $encryptPram1 = base64_encode($this->encrypt->encode($encryptPrams1));
                                        $pay_now1 = anchor('student/payment/'.$encryptPram1,'Pay Now','class="btn btn-warning btn-xs"');
                                    }
                                    

                                    echo "<tr>";
                                    echo "<td colspan='6' class='text-right'> Installment - 1</td>";
                                    echo "<td class='font-weight-bold text-right'>".number_format($installment_1_fee,0)."</td>";
                                    echo "<td>".$pay_now1."</td>";
                                    echo "</tr>";
                                    if($details1->installment_2_status){
                                        $pay_now2 = "<span class='text-success font-weight-bold'><i class='fa fa-check'></i> Paid</span>";
                                    }else{
                                        $encryptPrams2 =  $installment_2_fee.','.$details1->current_year.',2';
                                        $encryptPram2 = base64_encode($this->encrypt->encode($encryptPrams2));
                                        $pay_now2 = anchor('student/payment/'.$encryptPram2,'Pay Now','class="btn btn-warning btn-xs"');
                                    }

                                    echo "<tr>";
                                    echo "<td colspan='6' class='text-right'> Installment - 2</td>";
                                    echo "<td class='font-weight-bold text-right'>".number_format($installment_2_fee,0)."</td>";
                                    echo "<td>".$pay_now2."</td>";
                                    echo "</tr>";

                                    if($details1->installment_3_fee){
                                        if($details1->installment_3_status){
                                            $pay_now1 = "<span class='text-success font-weight-bold'><i class='fa fa-check'></i> Paid</span>";
                                        }else{
                                            $encryptPrams3 =  $installment_3_fee.','.$details1->current_year.',3';
                                            $encryptPram3 = base64_encode($this->encrypt->encode($encryptPrams3));
                                            $pay_now3 = anchor('student/payment/'.$encryptPram3,'Pay Now','class="btn btn-warning btn-xs"');
                                        }
    
                                        echo "<tr>";
                                        echo "<td colspan='6' class='text-right'> Installment - 2</td>";
                                        echo "<td class='font-weight-bold text-right'>".number_format($installment_3_fee,0)."</td>";
                                        echo "<td>".$pay_now3."</td>";
                                        echo "</tr>";
                                    }

                                }
                            ?>

                    </tbody>
                </table>
                <?php }else{ ?>
                <div class="col-12 text-center">
                    <img src="<?=base_url();?>assets/img/student_books.jpg" alt="NoData" title="NoData"
                        class="wd-200" />
                    <h6>Fee details are not found.</h6>
                </div>
                <?php } ?>
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
                            <!-- <th class="font-size-xs" width="15%">Reference</th> -->
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
                			        $transaction_status = "<span class='font-size-xs font-weight-bold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info'>Processing</span>";
                			    }

                                $encryptTransacitonID = base64_encode($this->encrypt->encode($transactions1->id));
                			    $receipt_no = ($transactions1->receipt_no) ? anchor('student/downloadReceipt/'.$encryptTransacitonID, $transactions1->receipt_no,'class="font-weight-bold"') : "-";
                			    $receipt_date = ($transactions1->receipt_date != "0000-00-00") ? date('d-m-Y', strtotime($transactions1->receipt_date)) : "-";
                                echo "<tr>";
                                echo "<td>".$i++.".</td>";
                                echo "<td>".$transactions1->year."</td>";
                                echo "<td>".$receipt_no."</td>";
                                echo "<td>".$receipt_date."</td>";
                                echo "<td>".$trans."</td>";
                                // echo "<td>".$ref."</td>";
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