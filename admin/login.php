<?php
session_start();
if(isset($_SESSION['admin_id']) && $_SESSION['admin_id'] > 0){
    header("Location:./");
    exit;
}
require_once('../DBConnection.php');
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN |Recipe Haven</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/script.js"></script>
    <style>
        html, body{
            height:100%;
        }
        .card {
  background-color: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 0.25rem;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  transition: box-shadow 0.3s ease;
  font-size:20px;
}

.card:hover {
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.card-title {
  font-size: 1.25rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
  color: #03720c;
}

.btn-primary {
  color: #fff;
  background-color: #03720c;
  border-color: #ffff;
  border-radius: 3.25rem;
  width: 170px;
  font-size: 1rem;
  font-weight: bold;
  text-transform: uppercase;
  padding: 0.6rem 1rem;
  transition: background-color 0.3s ease;
}

.btn-primary:hover {
  background-color: #ff6600;
  border-color: #0062cc;
}

.card-body {
  background-image: url("../images/background.jpg");
  background-repeat: no-repeat;
  background-size: cover;
  opacity: 2.0;
  height: 350px;
  opacity: 1.0;
}
  

    </style>
</head>
<body class="bg-dark bg-gradient">
   <div class="h-100 d-flex jsutify-content-center align-items-center">
       <div class='w-100'>
        <h3 class="py-5 text-center" style="text-align: center; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 35px; color: #05c118;">Recipe Haven</h3>
        <div class="card my-3 col-md-4 offset-md-4">
            <div class="card-body">
                <form action="" id="login-form">
                    <center><small>Please enter your credentials.</small></center>
                    <div class="form-group">
                        <label for="username" class="control-label">Username</label>
                        <input type="text" id="username" autofocus name="username" class="form-control form-control-sm rounded-0" required>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control form-control-sm rounded-0" required>
                    </div>
                    <div class="form-group d-flex w-100 justify-content-end">
                        <button class="btn btn-sm btn-primary rounded-0 my-1">Login</button>
                    </div>
                </form>
            </div>
        </div>
       </div>
   </div>
</body>
<script>
    $(function(){
        $('#login-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            _this.find('button').attr('disabled',true)
            _this.find('button[type="submit"]').text('Loging in...')
            $.ajax({
                url:'./../Actions.php?a=login',
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
                        setTimeout(() => {
                            location.replace('./');
                        }, 2000);
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
</html>