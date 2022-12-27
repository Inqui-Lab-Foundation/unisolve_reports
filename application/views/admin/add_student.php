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
                        <h3 class="block-title">Add New Student Details</h3>
                    </div>
                    <div class="block-content">
                        <?=form_open($action,'class="js-validation-login space-y-4 push-50" name="form" novalidate');?>
                        <h6 class="my-2 text-gray pl-5">ADMISSION DETAILS</h6>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Admission Year</label>
                            <div class="col-sm-6">
                                <?php echo form_dropdown('admission_year',$admissionYears, (set_value('admission_year')) ? set_value('admission_year') : '', 'class="form-control" id="admission_year"'); ?>
                                <span class="text-danger"><?php echo form_error('admission_year'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Course & Combination</label>
                            <div class="col-sm-6">
                                <?php echo form_dropdown('course',$courses, (set_value('course')) ? set_value('course') : '', 'class="form-control" id="course"'); ?>
                                <span class="text-danger"><?php echo form_error('course'); ?></span>
                            </div>
                        </div>
                        <h6 class="my-2 text-gray pl-5">ACADEMIC & FEE DETAILS</h6>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Current Academic Year</label>
                            <div class="col-sm-6">
                                <?php echo form_dropdown('academic_year',$academic_years, (set_value('academic_year')) ? set_value('academic_year') : '', 'class="form-control" id="academic_year"'); ?>
                                <span class="text-danger"><?php echo form_error('academic_year'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Current Year & Semester</label>
                            <div class="col-sm-6">
                                <?php echo form_dropdown('year_sem',$yearSem, (set_value('year_sem')) ? set_value('year_sem') : '', 'class="form-control" id="year_sem"'); ?>
                                <span class="text-danger"><?php echo form_error('year_sem'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Fixed Fee</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="fixed_fee" name="fixed_fee"
                                    placeholder="Enter fixed fee"
                                    value="<?php echo (set_value('fixed_fee'))?set_value('fixed_fee'):"";?>" readonly>
                                <span class="text-danger"><?php echo form_error('fixed_fee'); ?></span>
                            </div>
                        </div>
                        <h6 class="my-2 text-gray pl-5">PERSONAL DETAILS</h6>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Reg. No</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="reg_no" name="reg_no" placeholder="Enter reg no"
                                    value="<?php echo (set_value('reg_no'))?set_value('reg_no'):"";?>">
                                <span class="text-danger"><?php echo form_error('reg_no'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Student Name</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="student_name" name="student_name"
                                    placeholder="Enter student name"
                                    value="<?php echo (set_value('student_name'))?set_value('student_name'):'';?>">
                                <span class="text-danger"><?php echo form_error('student_name'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Mobile</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="mobile" name="mobile" placeholder="Enter mobile"
                                    value="<?php echo (set_value('mobile'))?set_value('mobile'):'';?>">
                                <span class="text-danger"><?php echo form_error('mobile'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Father Mobile</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="father_mobile" name="father_mobile"
                                    placeholder="Enter father mobile"
                                    value="<?php echo (set_value('father_mobile'))?set_value('father_mobile'):'';?>">
                                <span class="text-danger"><?php echo form_error('father_mobile'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Official Email</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="official_email" name="official_email"
                                    placeholder="Enter official email"
                                    value="<?php echo (set_value('official_email'))?set_value('official_email'):'';?>">
                                <span class="text-danger"><?php echo form_error('official_email'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Personal Email</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="personal_email" name="personal_email"
                                    placeholder="Enter personal email"
                                    value="<?php echo (set_value('personal_email'))?set_value('personal_email'):'';?>">
                                <span class="text-danger"><?php echo form_error('personal_email'); ?></span>
                            </div>
                        </div>
                        <h6 class="my-2 text-gray pl-5">ADDRESS DETAILS</h6>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Area/Village</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="village" name="village"
                                    placeholder="Enter area / village"
                                    value="<?php echo (set_value('village'))?set_value('village'):'';?>">
                                <span class="text-danger"><?php echo form_error('village'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Post</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="post" name="post" placeholder="Enter post"
                                    value="<?php echo (set_value('post'))?set_value('post'):'';?>">
                                <span class="text-danger"><?php echo form_error('post'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">Taluk</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="taluk" name="taluk" placeholder="Enter taluk"
                                    value="<?php echo (set_value('taluk'))?set_value('taluk'):'';?>">
                                <span class="text-danger"><?php echo form_error('taluk'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">District</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="district" name="district" placeholder="Enter district"
                                    value="<?php echo (set_value('district'))?set_value('district'):'';?>">
                                <span class="text-danger"><?php echo form_error('district'); ?></span>
                            </div>
                        </div>
                        <div class="row pb-2 pl-5">
                            <label class="col-sm-4 col-form-label text-right">State</label>
                            <div class="col-sm-6">
                                <input class="form-control" id="state" name="state" placeholder="Enter state"
                                    value="<?php echo (set_value('state'))?set_value('state'):'';?>">
                                <span class="text-danger"><?php echo form_error('state'); ?></span>
                            </div>
                        </div>
                        <div class="row py-4">
                            <div class="col-md-6 offset-4 pl-5">
                                <button class="btn btn-success btn-square btn-sm" type="submit">Update</button>
                                <?php echo anchor('admin/students','Cancel','class="btn btn-secondary btn-square btn-sm"'); ?>
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

    $("#year_sem").change(function() {
        event.preventDefault();
        var course = $("#course").val();
        var academic_year = $("#academic_year").val();
        var year_sem = $("#year_sem").val();

        if (course == "" || academic_year == "" || year_sem == "") {
            alert("Please select all fields..!");
        } else {
            // $("#download").attr("disabled", true);
            var page = base_url + 'admin/getFixedFee';
            $.ajax({
                'type': 'POST',
                'url': page,
                'data': {
                    'course': course,
                    'academic_year': academic_year,
                    'year_sem': year_sem
                },
                'dataType': 'json',
                'cache': false,
                'success': function(data) {
                    // console.log(data);
                    $('#fixed_fee').val(data);
                }
            });
        }
    });

});
</script>