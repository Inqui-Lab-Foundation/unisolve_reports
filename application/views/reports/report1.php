<!-- Main Container -->
<main id="main-container">

    <!-- Page Content -->
    <div class="content">

        <div class="row">

            <div class="col-md-12">
                <div class="block shadow block-bordered">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">TAMIL NADU DISTRICTS</h3>
                    </div>
                    <div class="block-content">
                        <div id="districts_list">
                            <table class="table table-striped table-vcenter table-hover js-dataTable-full font-size-sm">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="25%">District</th>
                                        <th width="5%">No. of Institutions </th>
                                        <th width="15%">Teachers Status</th>
                                        <th width="15%">Student PreSurvey Status</th>
                                        <th width="15%">Student Lessons Status</th>
                                        <th width="15%">Idea Submission Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; $total = 0;
                                    foreach($res as $res1){
                                        $listDownload = anchor('reports/institutionsList/'.$res1->district,'<i class="fa fa-download"></i>','class="text-primary"');
                                        echo "<tr>";
                                        echo "<td>".$i++."</td>";
                                        echo "<td>".$res1->district."</td>";
                                        echo "<td>".$res1->cnt." ".$listDownload."</td>";
                                        // echo "<td>".anchor('reports/teachersStatus/'.$res1->district,'Download','class="btn btn-danger btn-xs"')."</td>";
                                        echo "<td><button type='button' name='teachersStatus_btn_".$i."' id='teachersStatus_btn' value='".$res1->district."' class='teachersStatus_btn btn btn-danger btn-xs'><i class='fa fa-download'></i> Download</button></td>";
                                        // echo "<td>".anchor('reports/studentPreSurvey/'.$res1->district,'Download','class="btn btn-danger btn-xs"')."</td>";
                                        echo "<td><button type='button' name='studentsPreSurvey_btn_".$i."' id='studentsPreSurvey_btn' value='".$res1->district."' class='studentsPreSurvey_btn btn btn-danger btn-xs'><i class='fa fa-download'></i> Download</button></td>";
                                        echo "<td><button type='button' name='studentLessonsStatus_btn_".$i."' id='studentLessonsStatus_btn' value='".$res1->district."' class='studentLessonsStatus_btn btn btn-danger btn-xs'><i class='fa fa-download'></i> Download</button></td>";
                                        echo "<td><button type='button' name='studentIdeasStatus_btn_".$i."' id='studentIdeasStatus_btn' value='".$res1->district."' class='studentIdeasStatus_btn btn btn-danger btn-xs'><i class='fa fa-download'></i> Download</button></td>";
                                        echo "</tr>";

                                        $total = $res1->cnt + $total;
                                    }
                                ?>
                                    <tr>
                                        <th colspan='2' class="text-right">TOTAL INSTITUTIONS</td>
                                        <th><?=$total;?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div>
                            </div>
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
    var base_url = '<?php echo base_url(); ?>';

    var d = new Date();
    // var month = d.getMonth()+1;
    var month = d.toLocaleString('default', { month: 'short' });
    var day = d.getDate();
    var output =  ((''+day).length<2 ? '0' : '') + day + '_' + ((''+month).length<2 ? '0' : '') + month;
 
    $('#error').hide();
    $('#fee_details').prop('disabled', true);

    $('button[class^="teachersStatus_btn"]').click(function() {
        var district = $(this).attr('value');
       
        if (district) {
            $("#districts_list").hide();
            $("#process").show();

            var page = base_url + 'reports/teachersStatus';
            $.ajax({
                'type': 'POST',
                'url': page,
                'data': {
                    'district': district
                },
                'dataType': 'json',
                'cache': false,
                'success': function(data) {
                    // console.log(data);die;
                    var fileName = district + " Teacher Registration Status.xls";
                    var $a = $("<a>");
                    $a.attr("href", data.file);
                    $("body").append($a);
                    $a.attr("download", fileName);
                    $a[0].click();
                    $a.remove();
                    $("#districts_list").show();
                    $("#process").hide();
                    // $("#download").attr("disabled", false);
                }
            });

        }
    });

    $('button[class^="studentsPreSurvey_btn"]').click(function() {
        var district = $(this).attr('value');
       
        if (district) {
            $("#districts_list").hide();
            $("#process").show();

            var page = base_url + 'reports/studentPreSurvey';
            $.ajax({
                'type': 'POST',
                'url': page,
                'data': {
                    'district': district
                },
                'dataType': 'json',
                'cache': false,
                'success': function(data) {
                    // console.log(data);die;
                    var fileName = district + " Students PreSurvey Status.xls";
                    var $a = $("<a>");
                    $a.attr("href", data.file);
                    $("body").append($a);
                    $a.attr("download", fileName);
                    $a[0].click();
                    $a.remove();
                    $("#districts_list").show();
                    $("#process").hide();
                    // $("#download").attr("disabled", false);
                }
            });

        }
    });

    $('button[class^="studentLessonsStatus_btn"]').click(function() {
        var district = $(this).attr('value');
       
        if (district) {
            $("#districts_list").hide();
            $("#process").show();

            var page = base_url + 'reports/studentLessons';
            $.ajax({
                'type': 'POST',
                'url': page,
                'data': {
                    'district': district
                },
                'dataType': 'json',
                'cache': false,
                'success': function(data) {
                    // console.log(data);die;
                    var fileName = district+"_SIDP_Status_"+output+".xls";
                    var $a = $("<a>");
                    $a.attr("href", data.file);
                    $("body").append($a);
                    $a.attr("download", fileName);
                    $a[0].click();
                    $a.remove();
                    $("#districts_list").show();
                    $("#process").hide();
                    // $("#download").attr("disabled", false);
                }
            });

        }
    });

    $('button[class^="studentIdeasStatus_btn"]').click(function() {
        var district = $(this).attr('value');
       
        if (district) {
            $("#districts_list").hide();
            $("#process").show();

            var page = base_url + 'reports/studentIdeas';
            $.ajax({
                'type': 'POST',
                'url': page,
                'data': {
                    'district': district
                },
                'dataType': 'json',
                'cache': false,
                'success': function(data) {
                    // console.log(data);die;
                    var fileName = district+"_SIDP_Ideas_Submission_Status_"+output+".xls";
                    var $a = $("<a>");
                    $a.attr("href", data.file);
                    $("body").append($a);
                    $a.attr("download", fileName);
                    $a[0].click();
                    $a.remove();
                    $("#districts_list").show();
                    $("#process").hide();
                    // $("#download").attr("disabled", false);
                }
            });

        }
    });

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

});
</script>
</script>