<?php

require '../config/function.php';

$paraResultId = checkParamId('id');
if(is_numeric($paraResultId)){

    $customerId = validate($paraResultId);
    
    $customer = getById('customers', $customerId);
    if($customer['status'] == 200){
        $customerDelete = deletebyId('customers', $customerId);
        if($customerDelete){
            redirect('customers.php', 'Customers Successfully Deleted!');
        } else{
            redirect('customers.php', 'Something Went Wrong...');
        }
    } else {
        redirect('customers.php' , $customer['message']);
    }
} else{
    redirect('customers.php', 'Something Went Wrong.');
}