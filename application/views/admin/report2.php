<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header bg-gray-lighter">
                <h3 class="block-title"><?=$page_title;?></h3>
            </div>
            <div class="block-content">
                <?php echo form_open('class="user"'); ?>
                <div class="form-row">
                    <div class="form-group col-3">
                        <label class="form-label">From Date:</label>
                        <input type="date" class="form-control" placeholder="Enter Date" id="from_date" name="from_date"
                            value="<?=date('Y-m-d');?>">
                        <span class="text-danger"><?php echo form_error('from_date'); ?></span>
                    </div>
                    <div class="form-group col-3">
                        <label class="form-label">To Date:</label>
                        <input type="date" class="form-control" placeholder="Enter Date" id="to_date" name="to_date"
                            value="<?=date('Y-m-d');?>">
                        <span class="text-danger"><?php echo form_error('to_date'); ?></span>
                    </div>
                    <div class="form-group col-3 pt-4">
                        <button type="button" class="btn btn-danger btn-sm" name="download" id="download"><i
                                class="fas fa-download"></i> Download </button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<script>
$(document).ready(function() {

    var base_url = '<?php echo base_url(); ?>';

    $("#download").click(function() {
        event.preventDefault();
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();

        if (from_date == "" || to_date == "") {
            alert("Please select all fields..!");
        } else {
            // $("#download").attr("disabled", true);
            var page = base_url + 'admin/report2Download';
            $.ajax({
                'type': 'POST',
                'url': page,
                'data': {
                    'from_date': from_date,
                    'to_date': to_date
                },
                'dataType': 'json',
                'cache': false,
                'success': function(data) {
                    // console.log(data);die;
                    var fileName = "Headwise Fee Collection Report.xls";
                    var $a = $("<a>");
                    $a.attr("href", data.file);
                    $("body").append($a);
                    $a.attr("download", fileName);
                    $a[0].click();
                    $a.remove();
                    $("#download").attr("disabled", false);
                }
            });
        }
    });
});
</script>