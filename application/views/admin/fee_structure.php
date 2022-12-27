<!-- Main Container -->
<main id="main-container">
    <!-- Page Content -->
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header bg-gray-lighter">
                <h3 class="block-title">Fee Structure</h3>
            </div>
            <div class="block-content">
                <table class="table table-vcenter table-hover">
                    <thead class="">
                        <tr>
                            <th>No</th>
                            <th>Course</th>
                            <th>Year</th>
                            <th>Admission Fee</th>
                            <th>Other Fee</th>
                            <th>Tuition Fee</th>
                            <th>Total Fee</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; 
                            foreach($fees as $fees1){
                                echo "<tr>";
                                echo "<td>".$i++.".</td>";
                                echo "<td>".$fees1->course.' '.$fees1->combination."</td>";
                                echo "<td>".$fees1->year."</td>";
                                echo "<td>".$fees1->admin_fee."</td>";
                                echo "<td>".$fees1->other_fee."</td>";
                                echo "<td>".$fees1->tuition_fee."</td>";
                                echo "<td>".$fees1->total_fee."</td>";
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