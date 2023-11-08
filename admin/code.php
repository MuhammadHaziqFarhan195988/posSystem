<?php

include('../config/function.php');

if(isset($_POST['saveAdmin'])) #we're going to take all the data that was submitted
#through the submit button, for that we refer to the name saveAdmin
{# as you see we're taking name value via name = name
    $name = validate($_POST['name']);
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
       'password' => $bcrypt_password,
       'phonenumber' => $phoneNumber,	
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

if(isset($_POST['updateAdmin']))
{
    $adminId = validate($_POST['adminId']);

    $adminData = getById('admins',$adminId);
    if($adminData['status'] != 200){
        redirect('admins-edit.php?id='.$adminId, 'Please fill the required fields');
    } #we stopped at 12:36

    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phoneNumber = validate($_POST['phonenumber']);
    $is_ban = isset($_POST['is_ban']) == true ? 1:0; 

    if($password != ''){
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    }else {
        $hashedPassword = $adminData['data']['password'];
    }

    if($name != '' && $email != '')
    {
        $data = [
            'name' => $name, 
            'email'=> $email,
            'password' => $hashedPassword,
            'phonenumber' => $phoneNumber,	
            'is_ban' => $is_ban
         ];
         $result = updateDB('admins',$adminId, $data);
     
         if($result){
             redirect('admins-edit.php?id='.$adminId, 'Admin Updated Successfully!');
         }else {
             redirect('admins-edit.php?id='.$adminId, 'Something Went Wrong!');
         } #
    }


    if($name != '' && $email != '' && $password != ''){ 

    } else{
        redirect('admins-create.php', 'Please fill the required fields');
    }
}
