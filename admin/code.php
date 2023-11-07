<?php

include('../config/function.php');

if(isset($_POST['saveAdmin'])) #we're going to take all the data that was submitted
#through the submit button, for that we refer to the name saveAdmin
{
    $name = validate($_POST['name']);# as you see we're taking name value via name = name
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phoneNumber = validate($_POST['phonenumber']);
    $is_ban = isset($_POST['is_ban']) == true ? 1:0; 

    if($name != '' && $email != '' && $password != ''){ #nullchecker
        $emailCheck = mysqli_query($connection, "SELECT * FROM admins WHERE email = '$email'");
        if($emailCheck){
        if(mysqli_num_rows($emailCheck) > 0){
            redirect('admins-create.php', 'Email already used by another user');
        }
    }
    $bcrypt_password = password_hash($password, PASSWORD_BCRYPT);

    $data = [
       'name' => $name, 
       'email'=> $email,
       'password' => $password,
       'phone' => $phoneNumber,	
       'is_ban' => $is_ban
    ];
    $result = insert('admins', $data);

    if($result){
        redirect('admins.php', 'Admin Created Successfully!');
    }else {
        redirect('admins-create.php', 'Something Went Wrong!');
    }
    
    
}else{
        redirect('admins-create.php', 'Please fill the required fields');
    }
}

