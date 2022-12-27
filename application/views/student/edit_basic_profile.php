<main id="main-container">

    <div class="content">

        <?php if($this->session->flashdata('message')){?>
        <div class="alert <?=$this->session->flashdata('status');?>" id="msg">
            <?php echo $this->session->flashdata('message')?>
        </div>
        <?php } ?>

        <div class="block block-rounded">

            <div class="block-content row">
                <div class="col-md-6 text-center">
                    <img src="<?=base_url();?>assets/img/student_books.jpg" alt="NoData" title="NoData"
                        class="wd-200" />
                    <h4 class="text-gray-dark font-weight-semibold col-md-10 offset-1">Before proceeding further kindly
                        update your <span class="text-success">contact
                            details</span> for quick communication.</h6>
                </div>
                <div class="col-md-4">
                    <?=form_open('student/updateContactInfo','class="js-validation-login space-y-4 push-50" name="form" novalidate');?>
                    <h6 class="my-2 text-danger pl-5">UPDATE CONTACT INFORMATION</h6>
                    <div class="row pb-2 pt-3 pl-5">
                        <label class="col-form-label text-right">Student Mobile</label>
                        <input class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile"
                            value="<?php echo (set_value('mobile'))?set_value('mobile'):$details->mobile;?>">
                        <span class="text-danger"><?php echo form_error('mobile'); ?></span>
                    </div>
                    <div class="row pb-2 pl-5">
                        <label class="col-form-label text-right">Parent Mobile</label>
                        <input class="form-control" id="father_mobile" name="father_mobile"
                            placeholder="Enter father mobile"
                            value="<?php echo (set_value('father_mobile'))?set_value('father_mobile'):$details->father_mobile;?>">
                        <span class="text-danger"><?php echo form_error('father_mobile'); ?></span>
                    </div>
                    <div class="row pb-2 pl-5">
                        <label class="col-form-label text-right">Student Email</label>
                        <input type="email" class="form-control" id="personal_email" name="personal_email"
                            placeholder="Enter personal email"
                            value="<?php echo (set_value('personal_email'))?set_value('personal_email'):$details->personal_email;?>">
                        <span class="text-danger"><?php echo form_error('personal_email'); ?></span>
                    </div>
                    <div class="row pb-2 pl-5 py-2">
                        <button class="btn btn-success btn-square btn-lg" type="submit">UPDATE</button>
                        <?php echo anchor('student/my_profile','CANCEL','class="btn btn-secondary btn-square btn-lg ml-2"'); ?>
                    </div>
                    </form>
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

});
</script>