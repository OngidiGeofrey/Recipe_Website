<style>
    .container {
max-width: auto;
margin: 0 auto;
color: black;
}

.form-group label {
font-weight: bold;
}

.form-control {
border-radius: 3px;
}

.btn {
border-radius: 2px;
}
.col-md-4 {
  border: 2px solid green;
  padding: 20px;
  box-shadow: 5px 5px 5px rgba(144, 238, 144, 1);
}
.col-sm-6 {
  border: 2px solid green;
  padding: 20px;
  box-shadow: 5px 5px 5px rgba(144, 238, 144, 1);
}

#login-form button {
background-color: #03720c;
;
}

#sign-up button {
background-color: #03720c;
}

</style>

<div class="container py-5 mt-4">
    <div class="col-12">
        <div class="row">
            <div class="col-md-4  py-5 my-4">
                <h3><b>Login Here</b></h3>
                <hr>
             
                <form action="" id="login-form">
                    <div class="form-group">
                        <label for="username" class="control-label">Username</label>
                        <input type="text" name="username" class="form-control form-control-sm rounded-0" required>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label">Password</label>
                        <input type="password" name="password" class="form-control form-control-sm rounded-0" required>
                    </div>
                    <div class="form-group my-1 pt-2">
                        <div class=" w-100 d-flex justify-content-left">
                            <button class="btn btn-sm btn-primary rounded-0">Login</button>
                            <a  style="margin-left:50px; text-decoration:none" href="<?php echo 'http://localhost/recipe_website/?page=forgot_password'?>">Forgot Password?</a>
                            
                        </div>
                        <br>
                        <div class=" w-100 d-flex justify-content-left">
                            <a style=" text-decoration:none" href="<?php echo 'http://localhost/recipe_website/?page=register'?>">SIGN UP</a>
                        </div>
                    </div>
                </form>
            </div>
             
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#login-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            _this.find('button').attr('disabled',true)
            _this.find('button[type="submit"]').text('logging in...')
            $.ajax({
                url:'./Actions.php?a=login_user',
                method:'POST',
                data:$(this).serialize(),
                dataType:'JSON',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                     _this.find('button').attr('disabled',false)
                     _this.find('button[type="submit"]').text('Save')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        location.replace('./')
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                     _this.find('button').attr('disabled',false)
                     _this.find('button[type="submit"]').text('Save')
                }
            })
        })
        
    })
</script>