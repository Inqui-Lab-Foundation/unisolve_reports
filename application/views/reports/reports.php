<!-- Main Container -->
<main id="main-container">

    <!-- Page Content -->
    <div class="content">

        <div class="row">

            <div class="col-md-4">
                <div class="block shadow block-bordered">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">TAMIL NADU DISTRICT REPORTS</h3>
                    </div>
                    <div class="block-content">
                        <?php echo form_open('class="user"'); ?>
                        <div class="mb-4">
                            <label class="form-label" for="val-username">Districts</label>
                            <?php echo form_dropdown('districts',$districts, (set_value('districts')) ? set_value('districts') : '', 'class="form-control" id="districts"'); ?>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="val-username">Report Types</label>
                            <?php echo form_dropdown('report_type',$reportTypes, (set_value('report_type')) ? set_value('report_type') : '', 'class="form-control" id="report_type"'); ?>
                        </div>
                        <div class="mb-4">
                            <button class="btn btn-danger btn-square btn-sm" id="download" disabled> <i
                                    class="fa fa-download"></i> Download</button>
                        </div>
                        </form>
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
    var base_url = '<?php echo base_url(); ?>';

    $('#error').hide();
    $('#fee_details').prop('disabled', true);

    $('#reg_no').keyup(function() {
        var reg_no = $("#reg_no").val();
        var len = $.trim(reg_no).length;
        if (len >= 5) {
            $('#fee_details').prop('disabled', false);
        } else {
            $('#fee_details').prop('disabled', true);
        }
    });

    $("#fee_details").click(function() {
        event.preventDefault();
        var reg_no = $("#reg_no").val();

        $.ajax({
            'type': 'POST',
            'url': base_url + 'admin/getStudentsFeeDetails',
            'data': {
                'reg_no': reg_no
            },
            'dataType': 'text',
            'cache': false,
            'success': function(data) {
                // console.log(data);
                if (data == '1') {
                    window.location.replace(base_url + 'admin/studentFeeDetails/' + reg_no);
                } else {
                    $('#error').show();
                }
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
</script>