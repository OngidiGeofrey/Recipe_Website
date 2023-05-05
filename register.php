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
            <div class="col-md-8 border-start py-5 my-4">
                <div class="col-sm-6 offset-sm-2">
                    <h4><b>Create  Account</b></h4>
                    <form action="" id="sign-up">
                        <div class="form-group">
                            <label for="fullname" class="control-label">Fullname</label>
                            <input type="text" name="fullname" class="form-control form-control-sm rounded-0" required>
                        </div>
                        <div class="form-group">
                            <label for="username" class="control-label">Username</label>
                            <input type="text" name="username" class="form-control form-control-sm rounded-0" required>
                        </div>
                        <div class="form-group">
                            <label for="password" class="control-label">Password</label>
                            <input type="password" name="password" class="form-control form-control-sm rounded-0" required>
                        </div>
                        <div class="form-group my-1 pt-2">
                            <div class=" w-100 d-flex justify-content-end">
                                <button class="btn btn-sm btn-primary rounded-0">Create Account</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        
        $('#sign-up').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            _this.find('button').attr('disabled',true)
            _this.find('button[type="submit"]').text('logging in...')
            $.ajax({
                url:'./Actions.php?a=user_register',
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
                        _el.addClass('alert alert-success')
                        location.replace('http://localhost/recipe_website/?page=verify_OTP&email')
                        _el.addClass('alert alert-success')
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