<h3>Welcome to Try My Recipe</h3>
<hr>
<div class="col-12">
    <div class="row gx-3 row-cols-4">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="w-100 d-flex align-items-center">
                        <div class="col-auto pe-1">
                            <span class="fa fa-th-list fs-3 text-primary"></span>
                        </div>
                        <div class="col-auto flex-grow-1">
                            <div class="fs-5"><b>Food Categories</b></div>
                            <div class="fs-6 text-end fw-bold">
                                <?php 
                                $category = $conn->query("SELECT count(category_id) as `count` FROM `category_list` ")->fetchArray()['count'];
                                echo $category > 0 ? number_format($category) : 0 ;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="w-100 d-flex align-items-center">
                        <div class="col-auto pe-1">
                            <span class="fa fa-utensils fs-3 text-dark"></span>
                        </div>
                        <div class="col-auto flex-grow-1">
                            <div class="fs-5"><b>Published Recipes</b></div>
                            <div class="fs-6 text-end fw-bold">
                                <?php 
                                $recipes = $conn->query("SELECT count(recipe_id) as `count` FROM `recipe_list` where status = 1 ")->fetchArray()['count'];
                                echo $recipes > 0 ? number_format($recipes) : 0 ;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="w-100 d-flex align-items-center">
                        <div class="col-auto pe-1">
                            <span class="fa fa-user-friends fs-3 text-success"></span>
                        </div>
                        <div class="col-auto flex-grow-1">
                            <div class="fs-5"><b>Users</b></div>
                            <div class="fs-6 text-end fw-bold">
                                <?php 
                                $users = $conn->query("SELECT count(user_id) as `count` FROM `user_list` ")->fetchArray()['count'];
                                echo $users > 0 ? number_format($users) : 0 ;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="w-100 d-flex align-items-center">
                        <div class="col-auto pe-1">
                            <span class="fa fa-users fs-3 text-primary"></span>
                        </div>
                        <div class="col-auto flex-grow-1">
                            <div class="fs-5"><b>Admin Users</b></div>
                            <div class="fs-6 text-end fw-bold">
                                <?php 
                                $admin = $conn->query("SELECT count(admin_id) as `count` FROM `admin_list`")->fetchArray()['count'];
                                echo $admin > 0 ? number_format($admin) : 0 ;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('.restock').click(function(){
            uni_modal('Add New Stock for <span class="text-primary">'+$(this).attr('data-name')+"</span>","manage_stock.php?pid="+$(this).attr('data-pid'))
        })
        $('table#inventory').dataTable()
    })
</script>