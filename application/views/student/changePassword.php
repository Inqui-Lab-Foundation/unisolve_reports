<main id="main-container">
     
    <!-- Page Content -->

    <div class="content">
    
    <div clas="row">
      <div class="col-md-8 offset-2">
        
        <?php if($this->session->flashdata('message')){?> 
            <div align="center" class="alert <?=$this->session->flashdata('status');?>" id="msg">
                <?php echo $this->session->flashdata('message')?>
            </div>
        <?php } ?>
        
        <div class="block block-rounded">
            <div class="block-header bg-gray-lighter">
                <h3 class="block-title">Change Password</h3>
            </div>
            <div class="block-content">
                <?=form_open($action,'class="js-validation-login push-50" name="form" novalidate');?>
                <div class="form-group">
                    <label class="d-block">Old Password</label>
                    <input type="password" class="form-control" placeholder="Enter your old password" id="old_password" name="old_password" value="<?=(set_value('old_password'))?set_value('old_password'):'';?>">
                    <?=form_error('old_password','<div class="text-danger">','</div>');?>
                </div>
                <div class="form-group">
                    <label class="d-block">New Password</label>
                    <input type="password" class="form-control" placeholder="Enter your new password" id="new_password" name="new_password" value="<?=(set_value('new_password'))?set_value('new_password'):'';?>">
                    <?=form_error('new_password','<div class="text-danger">','</div>');?>
                </div>
                <button class="btn btn-success round-pill btn-block col-md-4 mg-t-20">Change Password</button>
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

});
</script>