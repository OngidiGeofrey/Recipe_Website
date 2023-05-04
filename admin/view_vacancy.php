<?php
require_once("../DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT v.*,d.name as dept,dd.name as desg FROM `vacancy_list` v inner join `designation_list` dd on v.designation_id = dd.designation_id inner join `category_list` d on dd.category_id = d.category_id where v.vacancy_id = '{$_GET['id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
    $description = html_entity_decode($description);
}
?>
<style>
    #uni_modal .modal-footer{
        display:none !important;
    }
</style>
<div class="container-fluid">
    <div class="col-12">
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Title:</b></div>
            <div class="fs-5 ps-4"><?php echo isset($title) ? $title : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Category:</b></div>
            <div class="fs-5 ps-4"><?php echo isset($dept) ? $dept : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Designation:</b></div>
            <div class="fs-5 ps-4"><?php echo isset($desg) ? $desg : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Slots:</b></div>
            <div class="fs-5 ps-4"><?php echo isset($slots) ? number_format($slots) : '' ?></div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Status:</b></div>
            <div class="fs-5 ps-4">
                <?php 
                    if(isset($status) && $status == 1){
                        echo "<small><span class='badge rounded-pill bg-success'>Active</span></small>";
                    }else{
                        echo "<small><span class='badge rounded-pill bg-danger'>Inactive</span></small>";
                    }
                ?>
            </div>
        </div>
        <div class="w-100 mb-1">
            <div class="fs-6"><b>Description:</b></div>
            <div class="fs-6 ps-4"><?php echo isset($description) ? $description : '' ?></div>
        </div>
        <div class="w-100 d-flex justify-content-end">
            <button class="btn btn-sm btn-dark rounded-0" type="button" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>