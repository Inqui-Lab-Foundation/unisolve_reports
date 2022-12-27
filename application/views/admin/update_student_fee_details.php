<main id="main-container">

    <div class="content">

        <div class="row">
            <div class="col-md-8 offset-2">
                <?php if($this->session->flashdata('message')){?>
                <div class="alert <?=$this->session->flashdata('status');?>" id="msg">
                    <?php echo $this->session->flashdata('message')?>
                </div>
                <?php } ?>
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title"><?=$feeDetails->student_name;?> Fee Details</h3>
                    </div>
                    <div class="block-content">
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Academic Year</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="academic_year" name="academic_year"
                                    placeholder="Enter academic year"
                                    value="<?php echo (set_value('fixed_fee'))?set_value('academic_year'):$feeDetails->academic_year;?>"
                                    readonly>
                                <span class="text-danger"><?php echo form_error('academic_year'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Course & Combination</label>
                            <div class="col-sm-6">
                                <?php $course = ($feeDetails->course) ? $feeDetails->course : '-';
                                  $combination = ($feeDetails->combination) ? ' - '.$feeDetails->combination : '';
                            ?>
                                <input class="form-control" id="course_combination" name="course_combination"
                                    value="<?php echo $course.$combination;?>" readonly>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Year & Semester</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="current_year" name="current_year"
                                    value="<?php echo $romanYears[$feeDetails->current_year].' Year & '.$feeDetails->current_sem;?>"
                                    readonly>
                            </div>
                        </div>
                        <?=form_open($action,'class="js-validation-login space-y-4 push-50" name="form" novalidate');?>
                        <h6 class="my-2 text-gray pl-5">TUITION FEE</h6>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Fixed Fee</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="fixed_fee" name="fixed_fee"
                                    placeholder="Enter fixed fee"
                                    value="<?php echo (set_value('fixed_fee'))?set_value('fixed_fee'):$feeDetails->fixed_fee;?>">
                                <span class="text-danger"><?php echo form_error('fixed_fee'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Additional Fee <span
                                    class="font-size-xs">(+)</span></label>
                            <div class="col-sm-6">
                                <input class="form-control" id="additional_fee" name="additional_fee"
                                    placeholder="Enter additional fee"
                                    value="<?php echo (set_value('additional_fee'))?set_value('additional_fee'):$feeDetails->additional_fee;?>">
                                <span class="text-danger"><?php echo form_error('additional_fee'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Consession Fee <span
                                    class="font-size-xs">(-)</span></label>
                            <div class="col-sm-6">
                                <input class="form-control" id="concession_fee" name="concession_fee"
                                    placeholder="Enter concession fee"
                                    value="<?php echo (set_value('concession_fee'))?set_value('concession_fee'):$feeDetails->concession_fee;?>">
                                <span class="text-danger"><?php echo form_error('concession_fee'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Net Tuition Fee</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="net_fee" name="net_fee"
                                    placeholder="Enter concession fee"
                                    value="<?php echo (set_value('net_fee'))?set_value('net_fee'):$feeDetails->net_fee;?>"
                                    readonly>
                                <span class="text-danger"><?php echo form_error('net_fee'); ?></span>
                            </div>
                        </div>

                        <div class="row pb-2 pl-5 mt-2">
                            <label class="col-sm-4 col-form-label text-right">&nbsp;</label>
                            <div class="col-sm-3">
                                <label class="form-label">Installment-I</label>
                                <input class="form-control" id="installment_1_fee" name="installment_1_fee"
                                    value="<?php echo (set_value('installment_1_fee'))?set_value('installment_1_fee'):$feeDetails->installment_1_fee;?>"
                                    readonly>
                                <span class="text-danger"><?php echo form_error('installment_1_fee'); ?></span>
                            </div>
                            <div class="col-sm-3">
                                <label class="form-label">Installment-II</label>
                                <input class="form-control" id="installment_2_fee" name="installment_2_fee"
                                    value="<?php echo (set_value('installment_2_fee'))?set_value('installment_2_fee'):$feeDetails->installment_2_fee;?>"
                                    readonly>
                                <span class="text-danger"><?php echo form_error('installment_2_fee'); ?></span>
                            </div>
                        </div>


                        <h6 class="my-2 text-gray mt-3 pl-5">HOSTEL FEE</h6>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Room Type</label>
                            <div class="col-sm-6">
                                <?php echo form_dropdown('hostel_block',$hostelRoomTypes, (set_value('hostel_block')) ? set_value('hostel_block') : $feeDetails->hostel_block, 'class="form-control" id="hostel_block"'); ?>
                                <span class="text-danger"><?php echo form_error('hostel_block'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Caution Deposit Fee</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="hostel_deposit_fee" name="hostel_deposit_fee"
                                    placeholder="Enter hostel deposit fee"
                                    value="<?php echo (set_value('hostel_deposit_fee'))?set_value('hostel_deposit_fee'):$feeDetails->hostel_deposit_fee;?>">
                                <span class="text-danger"><?php echo form_error('hostel_deposit_fee'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Hostel Fee</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="hostel_fee" name="hostel_fee"
                                    placeholder="Enter hostel fee"
                                    value="<?php echo (set_value('hostel_fee'))?set_value('hostel_fee'):$feeDetails->hostel_fee;?>">
                                <span class="text-danger"><?php echo form_error('hostel_fee'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5 mt-2">
                            <label class="col-sm-4 col-form-label text-right">&nbsp;</label>
                            <div class="col-sm-3">
                                <label class="form-label">Installment-I</label>
                                <input class="form-control" id="hostel_inst_1_fee" name="hostel_inst_1_fee"
                                    value="<?php echo (set_value('hostel_inst_1_fee'))?set_value('hostel_inst_1_fee'):$feeDetails->hostel_inst_1_fee;?>"
                                    readonly>
                                <span class="text-danger"><?php echo form_error('hostel_inst_1_fee'); ?></span>
                            </div>
                            <div class="col-sm-3">
                                <label class="form-label">Installment-II</label>
                                <input class="form-control" id="hostel_inst_2_fee" name="hostel_inst_2_fee"
                                    value="<?php echo (set_value('hostel_inst_2_fee'))?set_value('hostel_inst_2_fee'):$feeDetails->hostel_inst_2_fee;?>"
                                    readonly>
                                <span class="text-danger"><?php echo form_error('hostel_inst_2_fee'); ?></span>
                            </div>
                        </div>
                        <h6 class="my-2 text-gray pl-5">TRANSPORTATION FEE</h6>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Route</label>
                            <div class="col-sm-6">
                                <?php echo form_dropdown('transportation_route',$transportationRoutes, (set_value('transportation_route')) ? set_value('transportation_route') : $feeDetails->transportation_route, 'class="form-control" id="transportation_route"'); ?>
                                <span class="text-danger"><?php echo form_error('transportation_route'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Transportation Fee</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="transportation_fee" name="transportation_fee"
                                    placeholder="Enter transportation fee"
                                    value="<?php echo (set_value('transportation_fee'))?set_value('transportation_fee'):$feeDetails->transportation_fee;?>">
                                <span class="text-danger"><?php echo form_error('transportation_fee'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5 mt-2">
                            <label class="col-sm-4 col-form-label text-right">&nbsp;</label>
                            <div class="col-sm-3">
                                <label class="form-label">Installment-I</label>
                                <input class="form-control" id="transportation_inst_1_fee"
                                    name="transportation_inst_1_fee"
                                    value="<?php echo (set_value('transportation_inst_1_fee'))?set_value('transportation_inst_1_fee'):$feeDetails->transportation_inst_1_fee;?>"
                                    readonly>
                                <span class="text-danger"><?php echo form_error('transportation_inst_1_fee'); ?></span>
                            </div>
                            <div class="col-sm-3">
                                <label class="form-label">Installment-II</label>
                                <input class="form-control" id="transportation_inst_2_fee"
                                    name="transportation_inst_2_fee"
                                    value="<?php echo (set_value('transportation_inst_2_fee'))?set_value('transportation_inst_2_fee'):$feeDetails->transportation_inst_2_fee;?>"
                                    readonly>
                                <span class="text-danger"><?php echo form_error('transportation_inst_2_fee'); ?></span>
                            </div>
                        </div>
                        <div class="row py-4">
                            <div class="col-md-6 offset-3 pl-5">
                                <button class="btn btn-success btn-square btn-sm" type="submit">Update</button>
                                <?php echo anchor('admin/student_details/'.$feeDetails->reg_no,'Cancel','class="btn btn-secondary btn-square btn-sm"'); ?>
                            </div>
                        </div>
                        <?=form_close();?>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<script>
$(document).ready(function() {
    // $("#successMessage").show().delay(5000).fadeOut();
    var base_url = '<?php echo base_url(); ?>';
    var transportationRouteFees = <?php echo json_encode($transportationRouteFees); ?>;
    var hostelRoomFees = <?php echo json_encode($hostelRoomFees); ?>;

    $("#fixed_fee").change(function() {
        event.preventDefault();
        tuitionFeeTotal();
    });

    $("#additional_fee").change(function() {
        event.preventDefault();
        tuitionFeeTotal();
    });

    $("#concession_fee").change(function() {
        event.preventDefault();
        tuitionFeeTotal();
    });

    function tuitionFeeTotal() {
        var fixed_fee = $("#fixed_fee").val();
        var additional_fee = $("#additional_fee").val();
        var concession_fee = $("#concession_fee").val();

        fixed_fee = (fixed_fee) ? fixed_fee : 0;
        additional_fee = (additional_fee) ? additional_fee : 0;
        concession_fee = (concession_fee) ? concession_fee : 0;

        var inst = (parseInt(fixed_fee) + parseInt(additional_fee)) / 2;

        var inst_1 = inst - parseInt(concession_fee);
        var inst_2 = inst;

        var net_fee = parseInt(fixed_fee) + parseInt(additional_fee) - parseInt(concession_fee);
        $('#net_fee').val(net_fee);
        $('#installment_1_fee').val(inst_1);
        $('#installment_2_fee').val(inst_2);
    }

    $("#hostel_block").change(function() {
        event.preventDefault();
        var hostel_block = $("#hostel_block").val();
        if (hostel_block) {
            var fee = hostelRoomFees[hostel_block];
            $('#hostel_fee').val(fee);
        } else {
            $('#hostel_fee').val('0');
        }
        hostelFeeTotal();
    });

    $("#hostel_deposit_fee").change(function() {
        event.preventDefault();
        hostelFeeTotal();
    });

    $("#hostel_fee").change(function() {
        event.preventDefault();
        hostelFeeTotal();
    });

    function hostelFeeTotal() {
        var hostel_deposit_fee = $("#hostel_deposit_fee").val();
        var hostel_fee = $("#hostel_fee").val();

        hostel_fee = (hostel_fee) ? hostel_fee : 0;
        hostel_deposit_fee = (hostel_deposit_fee) ? hostel_deposit_fee : 0;

        var inst = parseInt(hostel_fee) / 2;

        var inst_1 = parseInt(inst) + parseInt(hostel_deposit_fee);
        var inst_2 = inst;

        $('#hostel_inst_1_fee').val(inst_1);
        $('#hostel_inst_2_fee').val(inst_2);
    }

    $("#transportation_route").change(function() {
        event.preventDefault();
        var transportation_route = $("#transportation_route").val();
        if (transportation_route) {
            var fee = transportationRouteFees[transportation_route];
            $('#transportation_fee').val(fee);
        } else {
            $('#transportation_fee').val('0');
        }
        transportationFeeTotal();
    });

    $("#transportation_fee").change(function() {
        event.preventDefault();
        transportationFeeTotal();
    });

    function transportationFeeTotal() {
        var transportation_fee = $("#transportation_fee").val();

        transportation_fee = (transportation_fee) ? transportation_fee : 0;

        var inst = parseInt(transportation_fee) / 2;

        var inst_1 = parseInt(inst);
        var inst_2 = parseInt(inst);

        $('#transportation_inst_1_fee').val(inst_1);
        $('#transportation_inst_2_fee').val(inst_2);
    }
});
</script>