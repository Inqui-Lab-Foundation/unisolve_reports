<main id="main-container">

    <div class="content">

        <?php if($this->session->flashdata('message')){?>
        <div class="alert <?=$this->session->flashdata('status');?>" id="msg">
            <?php echo $this->session->flashdata('message')?>
        </div>
        <?php } ?>

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