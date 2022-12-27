<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header bg-gray-lighter">
                <h3 class="block-title"><?=$page_title;?></h3>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-md-8 offset-2 table-responsive">
                        <?php if ($academic_years){?>
                        <table class="table table-bordered table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Academic Year</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0;
        						foreach ($academic_years as $key => $value){
        							
        								if ($value->status)
                                        $status = '<i class="fa fa-check-circle text-success"></i>';
        								else $status = '<i class="fa fa-check-circle text-muted"></i>';        							 
        					?>
                                <tr>
                                    <td class="text-center"><?=++$i;?></td>
                                    <td><?=$value->academic_year;?></td>
                                    <td class="text-center">
                                        <?=$status;?>
                                    </td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                        <?php }else {?>
                        <div class="text-center">
                            <img src="<?=base_url();?>assets/img/no_data.png">
                            <p class="text-muted">-- No Data Found --</p>
                        </div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->