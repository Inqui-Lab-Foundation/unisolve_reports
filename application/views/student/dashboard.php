<main id="main-container">
    <div class="content">

        <?php if($this->session->flashdata('message')){ ?>
        <div class="alert <?=$this->session->flashdata('status');?>" id="msg">
            <?php echo $this->session->flashdata('message')?>
        </div>
        <?php } ?>

        <div class="block block-rounded">
            <div class="block-content block-content-full justify-content-between align-items-center">
                <div class="row">
                    <div class="col-9">
                        <?php
                    $time = date("H");
                    $timezone = date("e");
                    if ($time < "12") {
                        $wish = "Good morning!";
                    } else if ($time >= "12" && $time < "17") {
                        $wish = "Good afternoon!";
                    } else if ($time >= "17") {
                        $wish = "Good evening!";
                    } else {
                        $wish = "Hello!";
                    }
                    ?>
                        <h3 class="text-white1 pb-0 my-3"> <?=$wish.' '.$student_name;?> </h3>
                        <p class="text-white1"> Welcome back to Campus Portal </p>
                    </div>
                    <div class="col-3 text-right">
                        <img class="img-avatar img-avatar-thumb" src="<?=base_url();?>assets/img/avatar.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="block block-rounded">
                    <div class="block-header bg-color5">
                        <h3 class="block-title text-white"> Pay Fee </h3>
                    </div>

                    <div class="block-content">
                        <?=form_open($action,'class="space-y-4" name="form" novalidate');?>
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Reg No.</label>
                            <div class="col-sm-8 col-form-label">
                                <h6 class="mb-0"><?=$reg_no;?></h6>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Student Name</label>
                            <div class="col-sm-8 col-form-label">
                                <h6 class="mb-0"><?=$student_name;?></h6>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Year</label>
                            <div class="col-sm-8">
                                <?php 
        					        echo form_dropdown('year', $years, '','class="form-control form-control-xs" id="year"');
                			    ?>
                                <?=form_error('year','<div class="text-danger">','</div>');?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">Payment Type</label>
                            <div class="col-sm-8">
                                <?php $payment_options = array("" => "Select Payment Type");
        					        echo form_dropdown('payment_type', $payment_options, '','class="form-control form-control-xs" id="payment_type"');
                			    ?>
                                <?=form_error('payment_type','<div class="text-danger">','</div>');?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-8">
                                <button class="btn btn-danger btn-square btn-sm" type="submit">Submit</button>
                            </div>
                        </div>
                        <?=form_close();?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<div class="modal fade" id="student_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content tx-14">
            <div class="modal-header bg-warning-light">
                <h6 class="modal-title text-bold"> Secure Guidelines</h6>
            </div>
            <div class="modal-body">
                <ol>
                    <li> Keep your login credentails confidential and do not disclose it to anybody. </li>
                    <li> Privary Policy </li>
                    <li> Terms and Conditions </li>
                    <li> Specifically and expressly consent to the use of website tracking methods, including cookies,
                        and to the safe and secure transmission of your personal information. </li>
                    <li>These documents are designed to inform you of your rights and obligations when using the NGI
                        Payments Service.</li>
                </ol>
                <p class="mb-2">By clicking 'ACCEPT & CONTINUE' you are confirming you have read and understood the
                    guidelines above.
                </p>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="1" id="agreed">
                    <label class="form-check-label" for="agreed">
                        I accept the
                        <?php echo anchor('student/TermsAndConditions','Terms and Conditions, ','target="_blank"'); ?>
                        <?php echo anchor('student/PrivacyPolicy','Privacy Policy, ','target="_blank"'); ?>
                        <?php echo anchor('student/RefundPolicy','Refund / Cancellation Policy','target="_blank"'); ?>
                    </label>
                </div>
                <?php echo anchor('student/agree','Agree and Continue','class="btn btn-danger btn-sm disabled" id="agree_btn"');?>


            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {

    var base_url = '<?php echo base_url(); ?>';

    var agreed_terms = '<?php echo $agreed_terms; ?>';

    $(":submit").attr("disabled", true);

    if (agreed_terms == '0') {
        $('#student_modal').modal('show');
    }

    $("#agreed").click(function() {
        if ($("#agreed").is(':checked')) {
            $('#agree_btn').removeClass('disabled');
        } else {
            $('#agree_btn').attr('class', $('#agree_btn').attr('class') + ' disabled');
        }
    });

    $("#viewHodCommentsBtn").click(function() {
        $('html, body').animate({
            scrollTop: $("#viewHodComments").offset().top
        }, 2000);
    });

    $("#due_date_update").click(function() {
        event.preventDefault();
        $('#due_date_modal').modal('show');
    });

    $("#insert").click(function() {
        event.preventDefault();
        var id = '1';
        var next_due_date = $("#next_due_date").val();
        var remarks = $("#remarks").val();

        $.ajax({
            'type': 'POST',
            'url': base_url + 'admin/updateNextDueDate',
            'data': {
                "id": id,
                "next_due_date": next_due_date,
                "remarks": remarks
            },
            'dataType': 'text',
            'cache': false,
            'beforeSend': function() {
                $('#insert').val("Inserting...");
                $("#insert").attr("disabled", true);
            },
            'success': function(data) {
                $('#insert').val("Inserted");
                $('#due_date_modal').modal('hide');
                var url = base_url + 'admin/admissionDetails/' + id
                window.location.replace(url);
            }
        });

    });

    $("#year").change(function() {
        event.preventDefault();

        var year = $("#year").val();

        if (year == ' ') {
            alert("Please Select Course");
        } else {
            $.ajax({
                'type': 'POST',
                'url': base_url + 'student/getInstallemnts',
                'data': {
                    'year': year
                },
                'dataType': 'text',
                'cache': false,
                'success': function(data) {
                    $('select[name="payment_type"]').empty();
                    $('select[name="payment_type"]').append(data);
                }
            });
        }
    });

    $("#payment_type").change(function() {
        event.preventDefault();
        var year = $("#year").val();
        var payment_type = $("#payment_type").val();
        if (year != " " && payment_type != " ") {
            $(":submit").removeAttr("disabled");
        } else {
            $(":submit").attr("disabled", true);
        }
    });

});
</script>