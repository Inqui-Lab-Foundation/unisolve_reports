<link rel="stylesheet" href="<?=base_url();?>assets/js/plugins/datatables/dataTables.bootstrap4.css">
<link rel="stylesheet" href="<?=base_url();?>assets/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css">

<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header bg-gray-lighter">
                <h3 class="block-title"><?=$page_title;?></h3>
            </div>
            <div class="block-content">
                <?php print_r($table);?>
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

<!-- Page JS Plugins -->
<script src="<?=base_url();?>assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url();?>assets/js/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?=base_url();?>assets/js/plugins/datatables/buttons/dataTables.buttons.min.js"></script>
<script src="<?=base_url();?>assets/js/plugins/datatables/buttons/buttons.print.min.js"></script>
<script src="<?=base_url();?>assets/js/plugins/datatables/buttons/buttons.html5.min.js"></script>
<script src="<?=base_url();?>assets/js/plugins/datatables/buttons/buttons.flash.min.js"></script>
<script src="<?=base_url();?>assets/js/plugins/datatables/buttons/buttons.colVis.min.js"></script>

<!-- Page JS Code -->
<script src="<?=base_url();?>assets/js/pages/be_tables_datatables.min.js"></script>