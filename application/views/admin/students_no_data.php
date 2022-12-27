<main id="main-container">

    <div class="content">

        <?php if($this->session->flashdata('message')){?>
        <div class="alert <?=$this->session->flashdata('status');?>" id="msg">
            <?php echo $this->session->flashdata('message')?>
        </div>
        <?php } ?>

        <div class="block block-rounded">
            <div class="block-header bg-color5">
                <h3 class="block-title text-white">Student Details</h3>
                <div class="block-options">
                </div>
            </div>
            <div class="block-content text-center py-5">
                <?='<img src="'.base_url().'assets/img/7408.jpg" class="img-fluid w-25"/>'; ?>
                <h6> <?=$reg_no;?> Student details are not found..!</h6>
                <?php echo anchor('admin/add_student','Click here to <i class="fa fa-plus"></i> Add New Student Details','class="text-success font-weight-bold"'); ?>
            </div>
        </div>

    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->