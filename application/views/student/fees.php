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
                <?php $i = 1;
                    foreach($details as $details1){
                        $fixed_fee = ($details1->fixed_fee) ? $details1->fixed_fee : '0';
                        $additional_fee = ($details1->additional_fee) ? $details1->additional_fee : '0';
                        $concession_fee = ($details1->concession_fee) ? $details1->concession_fee : '0';
                        $concession_type = ($details1->concession_type) ? "(".$details1->concession_type.")" : null;
                        $net_fee = ($details1->net_fee) ? $details1->net_fee : '0';
                        $installment_1_fee = ($details1->installment_1_fee) ? $details1->installment_1_fee : '0';
                        $installment_2_fee = ($details1->installment_2_fee) ? $details1->installment_2_fee : '0';
                        if(array_key_exists($details1->current_year,$paidFees)){
                            if(array_key_exists(1,$paidFees[$details1->current_year])){
                                $total_pay = $paidFees[$details1->current_year][1];
                            }else{
                                $total_pay = 0;
                            }
                        }else{
                            $total_pay = 0;
                        }
                        $balance_fee = $net_fee - $total_pay;

                        $hostel_deposit_fee = ($details1->hostel_deposit_fee) ? $details1->hostel_deposit_fee : '0';
                        $hostel_fee = ($details1->hostel_fee) ? $details1->hostel_fee : 0;
                        $hostel_total_fee = $hostel_deposit_fee + $hostel_fee;
                        if(array_key_exists($details1->current_year,$paidFees)){
                            if(array_key_exists(2,$paidFees[$details1->current_year])){
                                $hostel_paid_fee = $paidFees[$details1->current_year][2];
                            }else{
                                $hostel_paid_fee = 0;
                            }
                        }else{
                            $hostel_paid_fee = 0;
                        }
                        $hostel_balance_fee = $hostel_total_fee - $hostel_paid_fee;
                        $hostel_inst_1_fee = ($details1->hostel_inst_1_fee) ? $details1->hostel_inst_1_fee : '0';
                        $hostel_inst_2_fee = ($details1->hostel_inst_2_fee) ? $details1->hostel_inst_2_fee : '0';
                         
                        
                        $transportation_fee = ($details1->transportation_fee) ? $details1->transportation_fee : 0;
                        if(array_key_exists($details1->current_year,$paidFees)){
                            if(array_key_exists(3,$paidFees[$details1->current_year])){
                                $transportation_paid_fee = $paidFees[$details1->current_year][3];
                            }else{
                                $transportation_paid_fee = 0;
                            }
                        }else{
                            $transportation_paid_fee = 0;
                        }
                        $transportation_balance_fee = $transportation_fee - $transportation_paid_fee;
                        $transportation_inst_1_fee = ($details1->transportation_inst_1_fee) ? $details1->transportation_inst_1_fee : '0';
                        $transportation_inst_2_fee = ($details1->transportation_inst_2_fee) ? $details1->transportation_inst_2_fee : '0';
                        
                ?>
                <div id="my-block" class="block block-rounded block-bordered">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            <?=$details1->academic_year.' ('.$romanYears[$details1->current_year].'-Year & '.$details1->current_sem.')';?>
                        </h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option"
                                data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
                            <button type="button" class="btn-block-option" data-toggle="block-option"
                                data-action="content_toggle"><i class="si si-arrow-up"></i></button>
                        </div>
                    </div>
                    <div class="block-content">
                    <table class="table table-hover table-vcenter">
                            <thead>
                                <tr class="">
                                    <th width="5%">&nbsp;</th>
                                    <th width="30%" colspan="2">Details</th>
                                    <th width="20%" class="text-right pr-5">Total Amount</th>
                                    <th width="20%" class="text-right pr-5">Paid Amount</th>
                                    <th width="25%" class="text-right pr-5">Balance Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr data-toggle="collapse" data-target="#tuition_fee" class="accordion-toggle">
                                    <th><button class="btn btn-default btn-xs"><span class="fa fa-list"></span></button>
                                    </th>
                                    <th colspan="2">Tuition Fee </th>
                                    <th class="text-right pr-5 text-primary"><?=number_format($net_fee,0);?></th>
                                    <th class="text-right pr-5 text-success"><?=number_format($total_pay,0);?></th>
                                    <th class="text-right pr-5 text-danger"><?=number_format($balance_fee,0);?></th>
                                </tr>
                                <tr>
                                    <td colspan="3" class="hiddenRow">
                                        <div class="accordian-body collapse" id="tuition_fee">
                                            <table class="table">
                                                <tr>
                                                    <td class="pl-5">Fixed Tuition Fee </td>
                                                    <td class="text-right"><?=number_format($fixed_fee,0);?>
                                                    </td>
                                                    <td colspan="3">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="pl-5">Additional Fee </td>
                                                    <td class="text-right"><?=number_format($additional_fee,0);?>
                                                    </td>
                                                    <td colspan="3">&nbsp;</td>
                                                </tr> 
                                                <tr>
                                                    <td class="pl-5">Consession Fee (-) </td>
                                                    <td class="text-right"><?=number_format($concession_fee,0);?></td>
                                                    <td colspan="3">&nbsp;</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                    <td colspan="3" class="hiddenRow">&nbsp</td>
                                </tr>
                                <tr data-toggle="collapse" class="accordion-toggle" data-target="#hostel_fee">
                                    <th><button class="btn btn-default btn-xs"><span class="fa fa-list"></span></button>
                                    </th>
                                    <th colspan="2">Hostel Fee
                                        <?php echo ($details1->hostel_block) ? "(".$details1->hostel_block.")" : ""; ?>
                                    </th>
                                    <th class="text-right pr-5 text-primary"><?=number_format($hostel_total_fee,0);?>
                                    </th>
                                    <th class="text-right pr-5 text-success"><?=number_format($hostel_paid_fee,0);?>
                                    </th>
                                    <th class="text-right pr-5 text-danger"><?=number_format($hostel_balance_fee,0);?>
                                    </th>
                                </tr>
                                <tr>
                                    <td colspan="3" class="hiddenRow">
                                        <div class="accordian-body collapse" id="hostel_fee">
                                            <table class="table table-striped">
                                                <tr>
                                                    <td class="pl-5">Caution Deposit Fee </td>
                                                    <td class="text-right"><?=number_format($hostel_deposit_fee,0);?>
                                                    </td>
                                                    <td colspan="3">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="pl-5">Hostel Fee </td>
                                                    <td class="text-right"><?=number_format($hostel_fee,0);?></td>
                                                    <td colspan="3">&nbsp;</td>
                                                </tr>
                                            </table>

                                        </div>
                                    </td>
                                    <td colspan="3" class="hiddenRow">&nbsp</td>

                                </tr>
                                <tr data-toggle="collapse" data-target="#transportation_fee" class="accordion-toggle">
                                    <th><button class="btn btn-default btn-xs"><span class="fa fa-list"></span></button>
                                    </th>
                                    <th colspan="2">Transportation Fee
                                        <?php echo ($details1->transportation_route) ? "<br/> (".$details1->transportation_route.")" : ""; ?>
                                    </th>
                                    <th class="text-right pr-5 text-primary"><?=number_format($transportation_fee,0);?>
                                    </th>
                                    <th class="text-right pr-5 text-success">
                                        <?=number_format($transportation_paid_fee);?>
                                    </th>
                                    <th class="text-right pr-5 text-danger">
                                        <?=number_format($transportation_balance_fee);?>
                                    </th>
                                </tr>
                                <tr>
                                    <td colspan="3" class="hiddenRow">
                                        <div class="accordian-body collapse" id="transportation_fee">
                                            <table class="table">
                                                <tr>
                                                    <td width="70%" class="pl-5"> Transportation Fee </td>
                                                    <td width="30%" class="text-right">
                                                        <?=number_format($transportation_fee,0);?></td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                    <td colspan="3" class="hiddenRow">&nbsp</td>
                                </tr>
                            </tbody>
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
                // print_r($details); ?>

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