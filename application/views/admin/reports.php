<main id="main-container">
    <div class="content">
        
        <div class="row">
            <div class="col-md-6">
                <div class="block block-rounded">
                <div class="block-header block-header-default">
                  <h3 class="block-title">REPORTS</h3>
                </div>
                 <table class="table table-vcenter table-hover">
                    <tr><th><?php echo anchor('admin/students_statistics','Students Statistics'); ?></th></tr>
                    <tr><th><?php echo anchor('admin/staff_statistics','Staff Statistics'); ?></th></tr>
                    <tr><th><?php echo anchor('admin/principal_hod_list','Principal and HOD List'); ?></th></tr>
                    <!--<tr><th><?php echo anchor('admin/subject_statistics','Subjects Statistics'); ?></th></tr>-->
                    <tr><th><?php echo anchor('admin/subjects_confirm','Staff - Subjects Confirmation Status'); ?></th></tr>
                    <tr><th><?php echo anchor('admin/students_report','Students Info Download'); ?></th></tr>
                  </table>
              </div>        
            </div>
            <?php if($user_type == 2){ ?>
            <div class="col-md-6">
                <div class="block block-rounded">
                <div class="block-header block-header-default">
                  <h3 class="block-title">Fee Reports </h3>
                </div>
                 <table class="table table-vcenter table-hover">
                    <tr><th><?php echo anchor('admin/student_fee_details','Student Fee Details'); ?></th></tr>
                    <tr><th><?php echo anchor('admin/AdmissionScrollReport/1','Admission Scroll Report'); ?></th></tr>
                    <tr><th><?php echo anchor('admin/DayBookReport','Day Book Report'); ?></th></tr>
                    <tr><th><?php echo anchor('admin/DCBReport/1','DCB Report'); ?></th></tr>
                  </table>
              </div>        
            </div>
            <?php } ?>
        </div>
        
         
    </div>
</main>