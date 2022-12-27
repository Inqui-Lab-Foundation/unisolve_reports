<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header bg-gray-lighter">
                <h3 class="block-title">Courses and Combinations</h3>
            </div>
            <div class="block-content">
                <table class="table table-bordered table-hover table-vcenter">
                    <thead class="">
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Course</th>
                            <th width="30%">Combination</th>
                            <th width="20%">Course Type</th>
                            <th width="30%">No. of Years </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; 
                            foreach($courses as $courses1){
                                if($courses1->course_type == "UG"){
                                    $ct = "<span class='badge badge-info'>".$courses1->course_type."</span>";
                                }else{
                                    $ct = "<span class='badge badge-success'>".$courses1->course_type."</span>";
                                }
                                
                                echo "<tr>";
                                echo "<td>".$i++.".</td>";
                                echo "<td>".$courses1->course."</td>";
                                echo "<td>".$courses1->combination."</td>";
                                echo "<td>".$ct."</td>";
                                echo "<td>".$courses1->years." Years </td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->