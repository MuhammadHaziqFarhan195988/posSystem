<?php

if(isset($_SESSION['loggedIn'])){
 $email = validate($_SESSION['loggedInUser']['email']);

 $query = "SELECT * FROM admins WHERE email='$email' LIMIT 1";
 $result = mysqli_query($connection, $query);

 if(mysqli_num_rows($result) == 0){
    logoutSession();
 redirect('../login.php', 'Access Denied!');
 } else {
    $row = mysqli_fetch_assoc($result);
    if($row['is_ban'] == 1){
        logoutSession();
 redirect('../login.php', 'Your account is banned! Please contact administrator if you believe this is a mistake');
    }
 }
} else {
    redirect('../login.php', 'Login to continue...');
}