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
   function  update_password(){
    $username=$_GET['email'];
    extract($_POST);
    $password=md5($new_password);
    $sql = "UPDATE `user_list` SET  `password`='$password'  where `username` = '{$username}' ";
    @$qry = $this->query($sql);

    if(!$qry){
        $resp['status'] = "failed";
        $resp['msg'] = "Erxror Occured.";
    }else{
        $resp['status'] = "success";
    $resp['msg'] = "Password Changed successfully. Please Login";
    }

    return json_encode($resp);
   }

    function reset_password(){
        extract($_POST);
        $sql = "SELECT * FROM user_list where username = '{$username}' ";
        @$qry = $this->query($sql)->fetchArray();
        if(!$qry){
            $resp['status'] = "failed";
            $resp['msg'] = "A user with this email address does not exists.";
        }else{
            $resp['status'] = "success";
            $fullname=$qry['fullname'];
            
            $action = new Actions();
           $action->send_reset_verification_link($username,'http://localhost/recipe_website/?page=change_user_password&email='.base64_encode($username),$fullname);
        $resp['msg'] = "Click on verification link send to your email address.";
        }
        // file_put_contents('./about.html',htmlentities($about));
        // file_put_contents('./welcome.html',htmlentities($welcome));
        // $resp['status'] = "success";
        
        return json_encode($resp);
    }

    function send_reset_verification_link($email_address='',$link,$u_name=''){

        //  initialize post fields
                        $post_fieds = json_encode(array(
                            
                                "From"=> "geofrey.ongidi@digitalvision.co.ke",
                                "To"=> $email_address,
                                "Subject"=> "Recipe website. Reset Password",
                                "HtmlBody"=> "<strong>Dear $u_name <br></strong> Please click on the link below to reset your password. <br> $link",
                                "MessageStream"=> "notifications"    
                        ));
        
                        // if get token
                            $url = "https://api.postmarkapp.com/email";
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS =>$post_fieds,
                            CURLOPT_HTTPHEADER => array(
                                
                                "Content-Type: application/json",
                                "Accept: application/json",
                                "X-Postmark-Server-Token: b1371069-335f-431b-8f45-88c22d7f1c47"
                            ),
                            ));
                            $response = curl_exec($curl);
                            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                            $err = curl_error($curl);
                            curl_close($curl);
                            if ($err) {
                                
                                return FALSE;
                            } else {                    
                                if($response){
                                    if($file = json_decode($response)){ 
        
                                        //store data in db
                                        return TRUE;
                                       
                                    }else{
                                        return FALSE;
                                    }
                                    //print_r($file);die;
                                }else{
                                    $error = $err?:'';
                                    $code = $httpcode?:'';
                                    return FALSE;
                                }
                            }
                          
    
    }
}
$a = isset($_GET['a']) ?$_GET['a'] : '';
$action = new Actions();
switch($a){
    case 'reset_password':
        echo $action->reset_password();
        break;

    case 'change_user_password':
        echo $action->update_password();
        break;
     
    default:
    // default action here
    break;
}