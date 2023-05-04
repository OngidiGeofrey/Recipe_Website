
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Recipe List</h3>
        <div class="card-tools align-middle">
            <a class="btn btn-dark btn-sm py-1 rounded-0" href="./?page=manage_recipe" id="create_new">Add New</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-hover table-striped table-bordered">
            <colgroup>
                <col width="5%">
                <col width="10%">
                <col width="15%">
                <col width="30%">
                <col width="10%">
                <col width="20%">
                <col width="10%">
            </colgroup>
            <thead>
                <tr>
                    <th class="text-center p-0">#</th>
                    <th class="text-center p-0">Image</th>
                    <th class="text-center p-0">Date</th>
                    <th class="text-center p-0">Information</th>
                    <th class="text-center p-0">Status</th>
                    <th class="text-center p-0">Author</th>
                    <th class="text-center p-0">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT r.*,u.fullname as author,c.name as cname FROM recipe_list r inner join user_list u on r.user_id = u.user_id inner join category_list c on r.category_id = c.category_id order by strftime('%s',r.date_created) desc";
                $qry = $conn->query($sql);
                $i = 1;
                    while($row = $qry->fetchArray()):
                        $row['description'] = strip_tags(html_entity_decode($row['description']));
                ?>
                <tr>
                    <td class="text-center p-0"><?php echo $i++; ?></td>
                    <td class="py-0 px-1">
                    <center>
                            <img src="<?php echo '../uploads/'.$row['recipe_id'].'.png' ?>" alt="<?php echo $row['title'] ?> Image" width="50px" height="50px" class="my-1">
                        </center>
                    </td>
                    <td class="py-0 px-1 text-end"><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
                    <td class="py-0 px-1">
                        <div class="fs-6 lh-1">
                            <span class="fw-bold"><?php echo $row['cname'] ?></span><br>
                            <span class="fw-bold"><?php echo $row['title'] ?></span><br>
                            <p class="fw-lighter truncate-1 m-0"><?php echo $row['description'] ?></p>
                        </div>
                    </td>
                    <td class="py-0 px-1 text-center">
                        <?php 
                        if($row['status'] == 1){
                            echo  '<span class="py-1 px-3 badge rounded-pill bg-success"><small>Published</small></span>';
                        }else{
                            echo  '<span class="py-1 px-3 badge rounded-pill bg-danger"><small>Unpublished</small></span>';

                        }
                        ?>
                    </td>
                    <td class="py-0 px-1"><?php echo ucwords($row['author']) ?></td>
                    <td class="text-center py-0 px-1">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item" href="../?page=view_recipe&rid=<?php echo $row['recipe_id'] ?>" data-id = '<?php echo $row['recipe_id'] ?>' target="_blank">View Details</a></li>
                            <li><a class="dropdown-item" data-id = '<?php echo $row['recipe_id'] ?>' href="./?page=manage_recipe&id=<?php echo $row['recipe_id'] ?>">Edit</a></li>
                            <li><a class="dropdown-item delete_data" data-id = '<?php echo $row['recipe_id'] ?>' data-name = '<?php echo $row['title'] ?>' href="javascript:void(0)">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(function(){
        $('.edit_data').click(function(){
            uni_modal('Edit Recipe Details',"manage_recipe.php?id="+$(this).attr('data-id'),'large')
        })
        $('.view_data').click(function(){
            uni_modal('Recipe Details',"view_recipe.php?id="+$(this).attr('data-id'),'large')
        })
        $('.delete_data').click(function(){
            _conf("Are you sure to delete <b>"+$(this).attr('data-name')+"</b> from Recipe List?",'delete_data',[$(this).attr('data-id')])
        })
        $('table td,table th').addClass('align-middle')
        $('table').dataTable({
            columnDefs: [
                { orderable: false, targets:3 }
            ]
        })
    })
    function delete_data($id){
        $('#confirm_modal button').attr('disabled',true)
        $.ajax({
            url:'./../Actions.php?a=delete_recipe',
            method:'POST',
            data:{id:$id},
            dataType:'JSON',
            error:err=>{
                console.log(err)
                alert("An error occurred.")
                $('#confirm_modal button').attr('disabled',false)
            },
            success:function(resp){
                if(resp.status == 'success'){
                    location.reload()
                }else{
                    alert("An error occurred.")
                    $('#confirm_modal button').attr('disabled',false)
                }
            }
        })
    }
</script>