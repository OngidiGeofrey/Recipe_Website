
<?php 
if(!isset($_SESSION['user_id']))
    echo "<script>location.href='./?page=login_registration'</script>";
?>
<div class="container py-5 nt-4">
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">My Shopping  List</h3>
         
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
                <th class="text-center p-0">order ID</th>
                <th class="text-center p-0">product photo</th>
                <th class="text-center p-0">product Name</th>
                <th class="text-center p-0">Amount</th>
                <th class="text-center p-0">Action</th>
                 
                 
                
                </tr>
            </thead>
            <tbody>
                <?php 

                $user_id= $_SESSION['user_id'] ;

                // $sql = "SELECT * FROM recipe_list r inner join user_list u on r.user_id = u.user_id inner join category_list c on r.category_id = c.category_id where r.user_id = '{$_SESSION['user_id']}' order by strftime('%s',r.date_created) desc";
                // $qry = $conn->query($sql);

                // Check if the recipe is already in the user's meal planner
                $sql = "SELECT * FROM shopping_list WHERE user_id LIKE '$user_id'";
                $qry = $conn->query($sql); 
                $row = $qry->fetchArray();
               
                $i = 1;
                    while($row = $qry->fetchArray()):
                       $recipe_id= $row['recipe_id'];
                       
                        $sql = "SELECT title,status,description,cost FROM recipe_list WHERE recipe_id = '$recipe_id'";
                        $qry = $conn->query($sql);
                        $row1 = $qry->fetchArray();
                    //    ;
                ?>
                <tr>
                    <td class="text-center p-0"><?php echo $i++; ?></td>
                    <td class="py-0 px-1">
                        <center>
                            <img src="<?php echo './uploads/'.$recipe_id.'.png' ?>" alt="<?php echo $row['title'] ?>" width="50px" height="50px" class="my-1">
                        </center>
                    </td>
                    <td class="py-0 px-1 text-end"><?php echo $row1['title']; ?></td>
                    <td class="py-0 px-1">
                        <div class="fs-6 lh-1">
                            <span class="fw-bold"><?php echo 'USD '.$row1['cost'] ?></span><br>
                            
                             
                        </div>
                    </td>
                    
                    
                    <td class="text-center py-0 px-1">
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle btn-sm rounded-0 py-0" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item" href="./?page=view_recipe&rid=<?php echo $row['recipe_id'] ?>" data-id = '<?php echo $row['recipe_id'] ?>' target="_blank">View Details</a></li>
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
            url:'./Actions.php?a=delete_recipe',
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