<?php

require 'config/function.php';

# here we check if the button is clicked or not

if(isset($_POST['loginBtn'])){

$email = validate($_POST['email']);
$password = validate($_POST['password']);


if($email != '' && $password != ''){

$query = "SELECT * FROM admins WHERE email='$email' LIMIT 1"; 
$result = mysqli_query($connection , $query);
if($result){

    if(mysqli_num_rows($result) == 1){


        $row = mysqli_fetch_assoc($result);
        $hashedPassword = $row['password'];

        if(!password_verify($password, $hashedPassword)){
            redirect('login.php', 'Invalid Password');
        }

        if($row['is_ban'] == 1){
            redirect('login.php', 'The user is banned');
        }

        $_SESSION['loggedIn'] = true;
        $_SESSION['loggedInUser'] = [
            'user_id' => $row['id'],
            'name' => $row['name'],
            'user_id' => $row['id'],
            'user_id' => $row['id'],
            'user_id' => $row['id'],

        ];


    } else {
        redirect('login.php', 'Invalid email address');
    }

}else {
    redirect('login.php', 'Something Went Wrong');
}
}
else {
    redirect('login.php', 'All fields are required to fill in');
}


}
