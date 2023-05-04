<?php 
session_start();
require_once('DBConnection.php');

Class Actions extends DBConnection{
    function __construct(){
        parent::__construct();
    }
    function __destruct(){
        parent::__destruct();
    }
    function login(){
        extract($_POST);
        $sql = "SELECT * FROM admin_list where username = '{$username}' and `password` = '".md5($password)."' ";
        @$qry = $this->query($sql)->fetchArray();
        if(!$qry){
            $resp['status'] = "failed";
            $resp['msg'] = "Invalid username or password.";
        }else{
            $resp['status'] = "success";
            $resp['msg'] = "Login successfully.";
            foreach($qry as $k => $v){
                if(!is_numeric($k))
                $_SESSION[$k] = $v;
            }
        }
        return json_encode($resp);
    }
    function logout(){
        session_destroy();
        header("location:./admin");
    }
    function save_admin(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
        if(!in_array($k,array('id'))){
            if(!empty($id)){
                if(!empty($data)) $data .= ",";
                $data .= " `{$k}` = '{$v}' ";
                }else{
                    $cols[] = $k;
                    $values[] = "'{$v}'";
                }
            }
        }
        if(empty($id)){
            $cols[] = 'password';
            $values[] = "'".md5($username)."'";
        }
        if(isset($cols) && isset($values)){
            $data = "(".implode(',',$cols).") VALUES (".implode(',',$values).")";
        }
        

       
        @$check= $this->query("SELECT count(admin_id) as `count` FROM admin_list where `username` = '{$username}' ".($id > 0 ? " and admin_id != '{$id}' " : ""))->fetchArray()['count'];
        if(@$check> 0){
            $resp['status'] = 'failed';
            $resp['msg'] = "Username already exists.";
        }else{
            if(empty($id)){
                $sql = "INSERT INTO `admin_list` {$data}";
            }else{
                $sql = "UPDATE `admin_list` set {$data} where admin_id = '{$id}'";
            }
            @$save = $this->query($sql);
            if($save){
                $resp['status'] = 'success';
                if(empty($id))
                $resp['msg'] = 'New User successfully saved.';
                else
                $resp['msg'] = 'User Details successfully updated.';
            }else{
                $resp['status'] = 'failed';
                $resp['msg'] = 'Saving User Details Failed. Error: '.$this->lastErrorMsg();
                $resp['sql'] =$sql;
            }
        }
        return json_encode($resp);
    }
    function delete_admin(){
        extract($_POST);

        @$delete = $this->query("DELETE FROM `admin_list` where rowid = '{$id}'");
        if($delete){
            $resp['status']='success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'User successfully deleted.';
        }else{
            $resp['status']='failed';
            $resp['error']=$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function user_register(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
        if(!in_array($k,array('id','password'))){
            if(!empty($id)){
                if(!empty($data)) $data .= ",";
                $data .= " `{$k}` = '{$v}' ";
                }else{
                    $cols[] = $k;
                    $values[] = "'{$v}'";
                }
            }
        }
        if(empty($id)){
            $cols[] = 'password';
            if(isset($password))
            $values[] = "'".md5($password)."'";
            else
            $values[] = "'".md5($username)."'";
        }
        if(isset($cols) && isset($values)){
            $data = "(".implode(',',$cols).") VALUES (".implode(',',$values).")";
        }
        

        
        @$check= $this->query("SELECT count(user_id) as `count` FROM user_list where `username` = '{$username}' ".($id > 0 ? " and user_id != '{$id}' " : ""))->fetchArray()['count'];
        if(@$check> 0){
            $resp['status'] = 'failed';
            $resp['msg'] = "Username already exists.";
        }else{
            if(empty($id)){
                $sql = "INSERT INTO `user_list` {$data}";
            }else{
                $sql = "UPDATE `user_list` set {$data} where user_id = '{$id}'";
            }
            @$save = $this->query($sql);
            if($save){
                $resp['status'] = 'success';
                if(empty($id))
                $resp['msg'] = 'Account Successfully created.';
                else
                $resp['msg'] = 'Account Successfully updated.';
            }else{
                $resp['status'] = 'failed';
                $resp['msg'] = 'Failed to create the account. Error: '.$this->lastErrorMsg();
                $resp['sql'] =$sql;
            }
        }
        return json_encode($resp);
    }
    function user_login(){
        extract($_POST);
        $sql = "SELECT * FROM user_list where username = '{$username}' and `password` = '".md5($password)."' ";
        @$qry = $this->query($sql)->fetchArray();
        if(!$qry){
            $resp['status'] = "failed";
            $resp['msg'] = "Invalid username or password.";
        }else{
            $resp['status'] = "success";
            $resp['msg'] = "Login successfully.";
            foreach($qry as $k => $v){
                if(!is_numeric($k))
                $_SESSION[$k] = $v;
            }
        }
        return json_encode($resp);
    }
    function user_logout(){
        session_destroy();
        header("location:./");
    }
    function update_credentials(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id','old_password')) && !empty($v)){
                if(!empty($data)) $data .= ",";
                if($k == 'password') $v = md5($v);
                $data .= " `{$k}` = '{$v}' ";
            }
        }
        if(!empty($password) && md5($old_password) != $_SESSION['password']){
            $resp['status'] = 'failed';
            $resp['msg'] = "Old password is incorrect.";
        }else{
            $sql = "UPDATE `admin_list` set {$data} where admin_id = '{$_SESSION['admin_id']}'";
            @$save = $this->query($sql);
            if($save){
                $resp['status'] = 'success';
                $_SESSION['flashdata']['type'] = 'success';
                $_SESSION['flashdata']['msg'] = 'Credential successfully updated.';
                foreach($_POST as $k => $v){
                    if(!in_array($k,array('id','old_password')) && !empty($v)){
                        if(!empty($data)) $data .= ",";
                        if($k == 'password') $v = md5($v);
                        $_SESSION[$k] = $v;
                    }
                }
            }else{
                $resp['status'] = 'failed';
                $resp['msg'] = 'Updating Credentials Failed. Error: '.$this->lastErrorMsg();
                $resp['sql'] =$sql;
            }
        }
        return json_encode($resp);
    }
    function update_user_credentials(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id','old_password')) && !empty($v)){
                if(!empty($data)) $data .= ",";
                if($k == 'password') $v = md5($v);
                $data .= " `{$k}` = '{$v}' ";
            }
        }
        if(!empty($password) && md5($old_password) != $_SESSION['password']){
            $resp['status'] = 'failed';
            $resp['msg'] = "Old password is incorrect.";
        }else{
            $sql = "UPDATE `user_list` set {$data} where user_id = '{$_SESSION['user_id']}'";
            @$save = $this->query($sql);
            if($save){
                $resp['status'] = 'success';
                $_SESSION['flashdata']['type'] = 'success';
                $_SESSION['flashdata']['msg'] = 'Credential successfully updated.';
                foreach($_POST as $k => $v){
                    if(!in_array($k,array('id','old_password')) && !empty($v)){
                        if(!empty($data)) $data .= ",";
                        if($k == 'password') $v = md5($v);
                        $_SESSION[$k] = $v;
                    }
                }
            }else{
                $resp['status'] = 'failed';
                $resp['msg'] = 'Updating Credentials Failed. Error: '.$this->lastErrorMsg();
                $resp['sql'] =$sql;
            }
        }
        return json_encode($resp);
    }
    function save_category(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id'))){
                $v = trim($v);
                $v = $this->escapeString($v);
            if(empty($id)){
                $cols[] = "`{$k}`";
                $vals[] = "'{$v}'";
            }else{
                if(!empty($data)) $data .= ", ";
                $data .= " `{$k}` = '{$v}' ";
            }
            }
        }
        if(isset($cols) && isset($vals)){
            $cols_join = implode(",",$cols);
            $vals_join = implode(",",$vals);
        }
        if(empty($id)){
            $sql = "INSERT INTO `category_list` ({$cols_join}) VALUES ($vals_join)";
        }else{
            $sql = "UPDATE `category_list` set {$data} where category_id = '{$id}'";
        }
        @$check= $this->query("SELECT COUNT(category_id) as count from `category_list` where `name` = '{$name}' ".($id > 0 ? " and category_id != '{$id}'" : ""))->fetchArray()['count'];
        if(@$check> 0){
            $resp['status'] ='failed';
            $resp['msg'] = 'Food Category already exists.';
        }else{
            @$save = $this->query($sql);
            if($save){
                $resp['status']="success";
                if(empty($id))
                    $resp['msg'] = "Food Category successfully saved.";
                else
                    $resp['msg'] = "Food Category successfully updated.";
            }else{
                $resp['status']="failed";
                if(empty($id))
                    $resp['msg'] = "Saving New Food Category Failed.";
                else
                    $resp['msg'] = "Updating Food Category Failed.";
                $resp['error']=$this->lastErrorMsg();
                $resp['sql']=$sql;
            }
        }
        return json_encode($resp);
    }
    function delete_category(){
        extract($_POST);

        @$delete = $this->query("DELETE FROM `category_list` where category_id = '{$id}'");
        if($delete){
            $resp['status']='success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'Food Category successfully deleted.';
        }else{
            $resp['status']='failed';
            $resp['error']=$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function save_recipe(){
        extract($_POST);
        $data = "";
        $_POST['user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;
        foreach($_POST as $k => $v){
            if(in_array($k,array('description','ingredients','steps','other_info')))
            $v = htmlentities($v);
            if(!in_array($k,array('id'))){
                $v = trim($v);
                $v = $this->escapeString($v);
                $v = addslashes(trim($v));
            if(empty($id)){
                $cols[] = "`{$k}`";
                $vals[] = "'{$v}'";
            }else{
                if(!empty($data)) $data .= ", ";
                $data .= " `{$k}` = '{$v}' ";
            }
            }
        }
        if(isset($cols) && isset($vals)){
            $cols_join = implode(",",$cols);
            $vals_join = implode(",",$vals);
        }
        if(empty($id)){
            $sql = "INSERT INTO `recipe_list` ({$cols_join}) VALUES ($vals_join)";
        }else{
            $sql = "UPDATE `recipe_list` set {$data} where recipe_id = '{$id}'";
        }
      
        
        @$save = $this->query($sql);
        if($save){
            $resp['status']="success";
            if(empty($id))
                $resp['msg'] = "Recipe successfully saved.";
            else
                $resp['msg'] = "Recipe successfully updated.";
            if(empty($id)){
                $recipe_id = $this->query("SELECT last_insert_rowid()")->fetchArray()[0];
            }else{
                $recipe_id = $id;
            }
            $dir = __DIR__.'/uploads/';
            if(!is_dir($dir))
                mkdir($dir);
            if(isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])){
                $upload = $_FILES['image']['tmp_name'];
                $type = mime_content_type($upload);
                if(!in_array($type,array('image/png','image/jpeg'))){
                    $resp['msg'] = "Recipe successfully saved but image upload failed due to invalid file type";
                }else{
                    $gdImage = ($type == 'image/png') ? imagecreatefrompng($upload) :   imagecreatefromjpeg($upload);
                    if($gdImage){
                        $filePath = $dir.$recipe_id.'.png';
                        if(is_file($filePath))
                        unlink($filePath);
                        imagepng($gdImage,$filePath);
                        imagedestroy($gdImage);
                    }else{
                            $resp['msg'] = "Recipe successfully saved but image upload failed due to unknown reason";
                    }
                }

            }
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = $resp['msg'];
        }else{
            $resp['status']="failed";
            if(empty($id))
                $resp['msg'] = "Saving New Recipe Failed.";
            else
                $resp['msg'] = "Updating Recipe Failed.";
            $resp['error']=$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function delete_recipe(){
        extract($_POST);

        @$delete = $this->query("DELETE FROM `recipe_list` where recipe_id = '{$id}'");
        if($delete){
            $resp['status']='success';
            $_SESSION['flashdata']['type'] = 'success';
            $_SESSION['flashdata']['msg'] = 'Recipe successfully deleted.';
            if(is_file(__DIR__.'uploads/'.$id.'.png'))
            unlink(__DIR__.'uploads/'.$id.'.png');
        }else{
            $resp['status']='failed';
            $resp['error']=$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function save_comment(){
        extract($_POST);
        $data = "";
        $_POST['user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;
        foreach($_POST as $k => $v){
            if(!in_array($k,array('id'))){
                $v = addslashes(trim($v));
            if(empty($id)){
                $cols[] = "`{$k}`";
                $vals[] = "'{$v}'";
            }else{
                if(!empty($data)) $data .= ", ";
                $data .= " `{$k}` = '{$v}' ";
            }
            }
        }
        if(isset($cols) && isset($vals)){
            $cols_join = implode(",",$cols);
            $vals_join = implode(",",$vals);
        }
        if(empty($id)){
            $sql = "INSERT INTO `comment_list` ({$cols_join}) VALUES ($vals_join)";
        }else{
            $sql = "UPDATE `comment_list` set {$data} where comment_id = '{$id}'";
        }
       
        @$save = $this->query($sql);
        if($save){
            $resp['status']="success";
            $_SESSION['comment_flashdata']['type']="success";
            if(empty($id)){
                $resp['msg'] = "Comment successfully saved.";
            }else{
                $resp['msg'] = "Comment successfully updated.";
            }
            $_SESSION['comment_flashdata']['msg']=$resp['msg'];
        }else{
            $resp['status']="failed";
            if(empty($id))
                $resp['msg'] = "Saving New Comment Failed.";
            else
                $resp['msg'] = "Updating Comment Failed.";
            $resp['error']=$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function delete_comment(){
        extract($_POST);

        @$delete = $this->query("DELETE FROM `comment_list` where comment_id = '{$id}'");
        if($delete){
            $resp['status']='success';
            $_SESSION['comment_flashdata']['type'] = 'success';
            $_SESSION['comment_flashdata']['msg'] = 'Comment successfully deleted.';
        }else{
            $resp['status']='failed';
            $resp['error']=$this->lastErrorMsg();
        }
        return json_encode($resp);
    }
    function save_settings(){
        extract($_POST);
        file_put_contents('./about.html',htmlentities($about));
        file_put_contents('./welcome.html',htmlentities($welcome));
        $resp['status'] = "success";
        $resp['msg'] = "Settings successfully updated.";
        return json_encode($resp);
    }
}
$a = isset($_GET['a']) ?$_GET['a'] : '';
$action = new Actions();
switch($a){
    case 'login':
        echo $action->login();
    break;
    case 'customer_login':
        echo $action->customer_login();
    break;
    case 'logout':
        echo $action->logout();
    break;
    case 'customer_logout':
        echo $action->customer_logout();
    break;
    case 'save_admin':
        echo $action->save_admin();
    break;
    case 'delete_admin':
        echo $action->delete_admin();
    break;
    case 'delete_user':
        echo $action->delete_user();
    break;
    case 'update_credentials':
        echo $action->update_credentials();
    break;
    case 'update_user_credentials':
        echo $action->update_user_credentials();
    break;
    case 'save_category':
        echo $action->save_category();
    break;
    case 'delete_category':
        echo $action->delete_category();
    break;
    case 'save_recipe':
        echo $action->save_recipe();
    break;
    case 'delete_recipe':
        echo $action->delete_recipe();
    break;
    case 'save_comment':
        echo $action->save_comment();
    break;
    case 'delete_comment':
        echo $action->delete_comment();
    break;
    case 'save_settings':
        echo $action->save_settings();
    break;
    case 'user_register':
        echo $action->user_register();
    break;
    case 'login_user':
        echo $action->user_login();
    break;
    case 'user_logout':
        echo $action->user_logout();
    break;
    default:
    // default action here
    break;
}