<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<!-- Main Container -->
<main id="main-container">

    <!-- Page Content -->
    <div class="content">

        <div class="row">

            <div class="col-md-12">
                <div class="block shadow block-bordered">
                    <div class="block-header block-header-default">
                        <h3 class="block-title"><?=$instances[$instance].' '.$page_title;?></h3>
                        <div class="block-options">
                            <?php echo anchor('reports/reportsList','<i class="fas fa-download"></i> Downloadable Reports','class="btn btn-sm btn-dark"'); ?>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="block block-rounded block-bordered d-flex flex-column mb-2">
                                    <div
                                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                                        <dl class="mb-0">
                                            <dt class="fs-3 fw-bold h2 mb-1">
                                                <?=number_format($totalRegisteredStudents->TotalStudents,0);?></dt>
                                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Total number of
                                                students enrolled
                                            </dd>
                                        </dl>
                                        <div class="item item-rounded-lg bg-body-light">
                                            <i class="fas fa-users fa-3x text-dark"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="block block-rounded block-bordered block-themed">
                                    <div class="block-header bg-modern">
                                        <h3 class="block-title"> AGE WISE ENROLLED STUDENTS</h3>
                                    </div>
                                    <div class="block-content block-content-full">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-vcenter mb-0">
                                                <thead>
                                                    <?php $total_age = 0;
                                                    foreach($studentsAge as $studentsAge1){
                                                        echo "<tr>";
                                                        echo "<th>".$studentsAge1->Age." Yrs</th>";
                                                        echo "<th>".number_format($studentsAge1->TotalStudents,0)."</th>";
                                                        echo "</tr>";

                                                        $total_age = $total_age + $studentsAge1->TotalStudents;
                                                    }

                                                        echo "<tr class='bg-gray'>";
                                                        echo "<th> Total Students </th>";
                                                        echo "<th>".number_format($total_age,0)."</th>";
                                                        echo "</tr>";
                                                ?>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>



                                <div class="block block-rounded block-bordered block-themed">
                                    <div class="block-header bg-modern">
                                        <h3 class="block-title"> GENDER WISE ENROLLED STUDENTS</h3>
                                    </div>
                                    <div class="block-content block-content-full">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-vcenter mb-0">
                                                        <thead>
                                                            <?php $total_gender = 0; $female = 0; $male = 0; $non_binary = 0;
                                                    foreach($studentsGender as $studentsGender1){

                                                        if($studentsGender1->Gender == "FEMALE"){
                                                            $female = $studentsGender1->TotalStudents;
                                                        }

                                                        if($studentsGender1->Gender == "MALE"){
                                                            $male = $studentsGender1->TotalStudents;
                                                        }

                                                        if($studentsGender1->Gender == "OTHERS"){
                                                            $non_binary = $studentsGender1->TotalStudents;
                                                        }

                                                        echo "<tr>";
                                                        echo "<th>".$studentsGender1->Gender."</th>";
                                                        echo "<th>".number_format($studentsGender1->TotalStudents,0)."</th>";
                                                        echo "</tr>";

                                                        $total_gender = $total_gender + $studentsGender1->TotalStudents;
                                                    }

                                                        echo "<tr class='bg-gray'>";
                                                        echo "<th> Overall </th>";
                                                        echo "<th>".number_format($total_gender,0)."</th>";
                                                        echo "</tr>";
                                                ?>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <canvas id="genderChart"></canvas>
                                                <script>
                                                const ctx = document.getElementById('genderChart');

                                                new Chart(ctx, {
                                                    type: 'doughnut',
                                                    data: {
                                                        labels: [
                                                            'Female',
                                                            'Male',
                                                            'Non-Binary'
                                                        ],
                                                        datasets: [{
                                                            label: 'Enrolled Students',
                                                            data: [<?=$female;?>, <?=$male;?>,
                                                                <?=$non_binary;?>
                                                            ],
                                                            backgroundColor: [
                                                                'rgb(255, 99, 132)',
                                                                'rgb(54, 162, 235)',
                                                                'rgb(255, 205, 86)'
                                                            ],
                                                            hoverOffset: 4
                                                        }]
                                                    }
                                                });
                                                </script>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="block block-rounded block-bordered block-themed">
                                    <div class="block-header bg-modern">
                                        <h3 class="block-title"> GRADE WISE ENROLLED STUDENTS</h3>
                                    </div>
                                    <div class="block-content block-content-full">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-vcenter mb-0">
                                                        <thead>
                                                            <?php $total_grade = 0; $grades = array(); $gradeValues = array();
                                                    foreach($studentsGrade as $studentsGrade1){
                                                        array_push($grades,$studentsGrade1->Grade);
                                                        array_push($gradeValues,$studentsGrade1->TotalStudents);
                                                        echo "<tr>";
                                                        echo "<th> Grade - ".$studentsGrade1->Grade."</th>";
                                                        echo "<th>".number_format($studentsGrade1->TotalStudents,0)."</th>";
                                                        echo "</tr>";

                                                        $total_grade = $total_grade + $studentsGrade1->TotalStudents;
                                                    }

                                                        echo "<tr class='bg-gray'>";
                                                        echo "<th> Total Students </th>";
                                                        echo "<th>".number_format($total_grade,0)."</th>";
                                                        echo "</tr>";
                                                ?>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <canvas id="grade-chart"></canvas>
                                                <script>
                                                // Bar chart
                                                const grades = JSON.parse(`<?= json_encode($grades) ?>`);
                                                const gradeValues = JSON.parse(`<?= json_encode($gradeValues) ?>`);
                                                var myoption = {
                                                    tooltips: {
                                                        enabled: true
                                                    },
                                                    hover: {
                                                        animationDuration: 1
                                                    },
                                                    animation: {
                                                        duration: 1,
                                                        onComplete: function() {
                                                            var chartInstance = this.chart,
                                                                ctx = chartInstance.ctx;
                                                            ctx.textAlign = 'center';
                                                            ctx.fillStyle = "rgba(0, 0, 0, 1)";
                                                            ctx.textBaseline = 'bottom';

                                                            this.data.datasets.forEach(function(dataset, i) {
                                                                var meta = chartInstance.controller
                                                                    .getDatasetMeta(i);
                                                                meta.data.forEach(function(bar, index) {
                                                                    var data = dataset.data[
                                                                        index];
                                                                    ctx.fillText(data, bar
                                                                        ._model.x, bar
                                                                        ._model.y - 5);

                                                                });
                                                            });
                                                        }
                                                    }
                                                };
                                                new Chart(document.getElementById("grade-chart"), {
                                                    type: 'bar',
                                                    data: {
                                                        labels: grades,
                                                        datasets: [{
                                                            label: "Enrolled Students.",
                                                            data: gradeValues,
                                                            backgroundColor: [
                                                                'rgba(255, 99, 132, 0.2)',
                                                                'rgba(255, 159, 64, 0.2)',
                                                                'rgba(255, 205, 86, 0.2)',
                                                                'rgba(75, 192, 192, 0.2)',
                                                                'rgba(54, 162, 235, 0.2)',
                                                                'rgba(153, 102, 255, 0.2)',
                                                                'rgba(201, 203, 207, 0.2)'
                                                            ],
                                                            borderColor: [
                                                                'rgb(255, 99, 132)',
                                                                'rgb(255, 159, 64)',
                                                                'rgb(255, 205, 86)',
                                                                'rgb(75, 192, 192)',
                                                                'rgb(54, 162, 235)',
                                                                'rgb(153, 102, 255)',
                                                                'rgb(201, 203, 207)'
                                                            ],
                                                            borderWidth: 1
                                                        }]
                                                    }
                                                });
                                                </script>
                                            </div>
                                        </div>

                                    </div>
                                </div>
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

    var d = new Date();
    // var month = d.getMonth()+1;
    var month = d.toLocaleString('default', {
        month: 'short'
    });
    var day = d.getDate();
    var output = (('' + day).length < 2 ? '0' : '') + day + '_' + (('' + month).length < 2 ? '0' : '') + month;

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
                    var fileName = district + "_SIDP_Status_" + output + ".xls";
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

    $('button[class^="studentDetailedStatus_btn"]').click(function() {
        var district = $(this).attr('value');

        if (district) {
            $("#districts_list").hide();
            $("#process").show();

            var page = base_url + 'reports/studentProgress';
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
                    var fileName = district + "_Progress_Status_" + output + ".xls";
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
                    var fileName = district + "_SIDP_Ideas_Submission_Status_" + output +
                        ".xls";
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