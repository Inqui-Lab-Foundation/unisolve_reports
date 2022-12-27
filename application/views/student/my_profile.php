<main id="main-container">
    <!-- Hero -->
    <div class="bg-image" style="background-image: url('<?=base_url();?>assets/img/ngi_backgroud.jpg');">
        <div class="bg-black-50">
            <div class="content content-full text-center">
                <div class="my-3">
                    <img class="img-avatar img-avatar-thumb" src="<?=base_url();?>assets/img/avatar.jpg" alt="">
                </div>
                <h1 class="h2 text-white mb-0"><?=$details->student_name;?></h1>
                <h5 class="h5 text-white mb-0"><?=$details->reg_no;?></h5>
            </div>
        </div>
    </div>
    <!-- END Hero -->
    <!-- Page Content -->

    <div class="content">

        <?php if($this->session->flashdata('message')){?>
        <div class="alert <?=$this->session->flashdata('status');?>" id="msg">
            <?php echo $this->session->flashdata('message')?>
        </div>
        <?php } ?>

        <div class="row pb-2">
            <div class="col-md-12 text-right">
            <?php
                echo anchor('student/updateContactInfo','Update Contact Info','class="btn btn-danger btn-square btn-sm"');
            ?>
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
                        <th width="30%"><?=($details->admission_year)?$details->admission_year:'-';?></th>
                        <td width="20%" class="text-right">Course & Combination :</td>
                        <th width="30%">
                            <?php $course = ($details->course) ? $details->course : '-';
                                  $combination = ($details->combination) ? ' - '.$details->combination : '';
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
                        <th width="30%"><?=($details->father_mobile) ? $details->father_mobile : "-";?></th>
                        <td width="20%" rowspan='5' class="text-right"> Address :</td>
                        <th width="30%" rowspan='5'>
                            <?php 
                                echo ($details->village) ? $details->village : null;
                                echo ($details->post) ? '<br>'.$details->post : null;
                                echo ($details->taluk) ? '<br>'.$details->taluk : null;
                                echo ($details->district) ? '<br>'.$details->district : null;
                                echo ($details->state) ? '<br>'.$details->state : null;
                            ?>
                        </th>
                    </tr>
                    <tr>
                        <td class="text-right">Mobile :</td>
                        <th><?=($details->mobile) ? $details->mobile : "-";?></th>
                    </tr>
                    <tr>
                        <td width="20%" class="text-right">Official Email :</td>
                        <th width="30%"><?=($details->official_email) ? $details->official_email : "-";?></th>
                    </tr>
                    <tr>
                        <td width="20%" class="text-right">Personal Email :</td>
                        <th width="30%"><?=($details->personal_email) ? $details->personal_email : "-";?></th>
                    </tr>
                    <tr>
                        <td width="20%" class="text-right">Date of Birth :</td>
                        <th width="30%">
                            <?php echo ($details->date_of_birth != "0000-00-00") ? date('d-m-Y', strtotime($details->date_of_birth)) : '-';?>
                        </th>
                    </tr>
                    <tr>

                    </tr>

                </table>
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