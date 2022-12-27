<!-- Main Container -->
<main id="main-container">

    <!-- Page Content -->
    <div class="content">
        
        <div class="row">
            <?php if($user_type == 2) { ?>
            <div class="col-md-6">
                <div class="block shadow block-bordered">
                    <div class="block-header block-header-default">
                      <h3 class="block-title">Student Fee Details</h3>
                    </div>
                    <div class="block-content">
                        <div class="row">
                        <div class="form-group col-8 mb-0">    
                            <input type="text" class="form-control" placeholder="Enter Reg. Number" id="reg_no" name="reg_no">
                            <span class="text-danger" id="error">Enter Valid Register Number</span>
                        </div>
                        <div class="form-group col-4 mb-0">    
                            <button class="btn btn-danger btn-md" id="fee_details" name="fee_details" type="button">Get Details</button>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?> 
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
    
    $('#reg_no').keyup(function(){
        var reg_no = $("#reg_no").val();
        var len = $.trim(reg_no).length;
        if(len >= 5){
            $('#fee_details').prop('disabled', false);
        }else{
            $('#fee_details').prop('disabled', true);
        }
    });
    
    $("#fee_details").click(function() {
        event.preventDefault();
        var reg_no = $("#reg_no").val();
         
        $.ajax({
            'type': 'POST',
            'url': base_url + 'admin/getStudentsFeeDetails',
            'data': {'reg_no': reg_no},
            'dataType': 'text',
            'cache': false,
            'success': function(data) {
                // console.log(data);
                if(data == '1'){
                    window.location.replace(base_url+'admin/studentFeeDetails/'+reg_no);
                }else{
                    $('#error').show();
                }
            }
        });
    });
    
});
</script>
</script>