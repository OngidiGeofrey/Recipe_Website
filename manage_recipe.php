<?php
require_once("./DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM `recipe_list` where recipe_id = '{$_GET['id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
}
?>
<div class="container py-5 mt-4">
<div class="card">
    <div class="card-header">
        <h5 class="card-title"><?php echo isset($_GET['id'])? "Update" :"Create New" ?> Recipe Content</h5>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <form action="" id="recipe-form">
                <input type="hidden" name="id" value="<?php echo isset($recipe_id) ? $recipe_id : '' ?>">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" class="control-label">Recipe Title</label>
                                <input type="text" name="title" autofocus id="title" required class="form-control form-control-sm rounded-0" value="<?php echo isset($title) ? $title : '' ?>">
                            </div>
                            <div class="form-group">
                                <label for="category_id" class="control-label">Category</label>
                                <select name="category_id" id="category_id" class="form-select form-select-sm rounded-0" required>
                                    <option <?php echo (!isset($category_id)) ? 'selected' : '' ?> disabled>Please Select Here</option>
                                    <?php
                                    $category = $conn->query("SELECT * FROM category_list order by `name` asc");
                                    while($row= $category->fetchArray()):
                                    ?>
                                        <option value="<?php echo $row['category_id'] ?>" <?php echo (isset($category_id) && $category_id == $row['category_id'] ) ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status" class="control-label">Status</label>
                                <select name="status" id="status" class="form-select form-select-sm rounded-0" required>
                                    <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Published</option>
                                    <option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Unpublished</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="image" class="control-label">Recipe Image</label>
                                <input type="file" name="image" id="image" <?php echo !isset($recipe_id)? "required" : "" ?> class="form-control form-control-sm rounded-0" accept="image/png, image/jpeg">
                            </div>
                            <div class="form-group">
                                <label for="description" class="control-label">Description</label>
                                <textarea name="description" id="description" cols="30" rows="3" class="form-control rounded-0 summernote" data-placeholder="Write the description here." data-height="30vh" required><?php echo isset($description) ? $description : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="ingredients" class="control-label">Ingredients</label>
                                <textarea name="ingredients" id="ingredients" cols="30" rows="3" class="form-control rounded-0 summernote" data-placeholder="Write the ingredients here." data-height="30vh" required><?php echo isset($ingredients) ? $ingredients : '' ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="step" class="control-label">Steps</label>
                                <textarea name="step" id="step" cols="30" rows="3" class="form-control rounded-0 summernote" data-placeholder="Write the steps here." data-height="40vh" required><?php echo isset($step) ? $step : '' ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="other_info" class="control-label">Other Information</label>
                                <textarea name="other_info" id="other_info" cols="30" rows="3" class="form-control rounded-0 summernote" data-placeholder="Write the other information here." data-height="40vh" required><?php echo isset($other_info) ? $other_info : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card-footer">
        <div class="col-12 d-flex justify-content-end">
            <div class="col-auto">
                <button class="btn btn-primary rounded-0 me-2" form="recipe-form">Save</button>
                <a class="btn btn-dark rounded-0" href="./?page=posts">Cancel</a>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    $(function(){
        $('#recipe-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('.card-footer button').attr('disabled',true)
            $('.card-footer button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'./Actions.php?a=save_recipe',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                     $('.card-footer button').attr('disabled',false)
                     $('.card-footer button[type="submit"]').text('Save')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                            location.reload()
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                    $('#page-container').animate({scrollTop:0},'fast')
                     $('.card-footer button').attr('disabled',false)
                     $('.card-footer button[type="submit"]').text('Save')
                }
            })
        })
    })
</script>