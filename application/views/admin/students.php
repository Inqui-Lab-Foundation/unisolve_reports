<link rel="stylesheet" href="<?=base_url();?>assets/js/plugins/datatables/dataTables.bootstrap4.css">
<link rel="stylesheet" href="<?=base_url();?>assets/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css">

<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">

        <?php if($this->session->flashdata('message')){?>
        <div class="alert <?=$this->session->flashdata('status');?>" id="msg">
            <?php echo $this->session->flashdata('message')?>
        </div>
        <?php } ?>

        <div class="block block-rounded">
            <div class="block-header bg-gray-lighter">
                <h3 class="block-title"> <?=$page_title;?></h3>
                <div class="block-options">
                    <?php echo anchor('admin/add_student','<i class="fa fa-plus-circle text-success push-5-r me-1"></i> Add New Student','class="btn btn-alt-success btn-square btn-sm"'); ?>
                </div>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-md-2">
                        <?php echo form_dropdown('academic_year',$academic_years,$ac_active,'class="form-control form-control-sm" id="academic_year"');?>
                    </div>
                    <div class="col-md-3">
                        <?php echo form_dropdown('course',$courses,'all','class="form-control form-control-sm" id="course"');?>
                    </div>
                    <div class="col-md-2">
                        <?php $opt_years = array('all' => 'All Years');
					        echo form_dropdown('year', $opt_years, 'all','class="form-control form-control-sm" id="year"');
        			    ?>
                    </div>
                    <div class="col-md-5">
                        <button class="btn btn-primary btn-square btn-sm" id="filter"><i class="fa fa-filter"></i>
                            Filter</button>
                        <button class="btn btn-warning btn-square btn-sm" id="download" disabled> <i
                                class="fa fa-download"></i> Download</button>
                    </div>
                </div>

                <div class="table-responsive my-5" id="res">
                    <div class="text-center"> <img src="<?=base_url();?>assets/img/students.jpg"
                            class="img-fluid w-50" /> </div>
                </div>
                <div class="col-md-12 text-center" id="process" style="display: none;">
                    <?='<img src="'.base_url().'assets/img/Processing.gif"/>'; ?>
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

    $("#course").change(function() {
        event.preventDefault();

        var academic_year = $("#academic_year").val();
        var course = $("#course").val();

        if (course == ' ' && academic_year == ' ') {
            alert("Please Select Mandatroy Fields");
        } else {
            $.ajax({
                'type': 'POST',
                'url': base_url + 'admin/getCourseYears',
                'data': {
                    'academic_year': academic_year,
                    'course': course,
                    'flag': 'AY'
                },
                'dataType': 'text',
                'cache': false,
                'success': function(data) {

                    $('select[name="year"]').empty();
                    $('select[name="year"]').append(data);
                }
            });
        }
    });

    $("#filter").click(function() {
        event.preventDefault();
        $("#res").hide();
        $("#process").show();

        var academic_year = $("#academic_year").val();
        var course = $("#course").val();
        var year = $("#year").val();

        $.ajax({
            'type': 'POST',
            'url': base_url + 'admin/getStudentsList',
            'data': {
                'academic_year': academic_year,
                'course': course,
                'year': year,
                'download': '0'
            },
            'dataType': 'text',
            'cache': false,
            'success': function(data) {
                $("#process").hide();
                $("#res").show();
                $("#res").html(data);
                $('#js-dataTable-full').DataTable({
                    destroy: true,
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    language: {
                        searchPlaceholder: 'Search...',
                        sSearch: '',
                        lengthMenu: '_MENU_ items/page',
                    }
                });
                $("#download").removeAttr("disabled");
            }
        });
    });

    $("#download").click(function() {
        event.preventDefault();

        var academic_year = $("#academic_year").val();
        var course = $("#course").val();
        var year = $("#year").val();

        $("#download").html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Downloading...'
            );
        $("#download").prop('disabled', true);

        $.ajax({
            'type': 'POST',
            'url': base_url + 'admin/getStudentsList',
            'data': {
                'academic_year': academic_year,
                'course': course,
                'year': year,
                'download': '1'
            },
            'dataType': 'json',
            'cache': false,
            'success': function(data) {
                var filename = academic_year + " " + course + " Students List.xls";
                var $a = $("<a>");
                $a.attr("href", data.file);
                $("body").append($a);
                $a.attr("download", filename);
                $a[0].click();
                $a.remove();
                $("#download").html('<i class="fa fa-download"></i> Download');
                $("#download").prop('disabled', false);
            }
        });
    });

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