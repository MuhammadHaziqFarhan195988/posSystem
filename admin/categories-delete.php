<?php

require '../config/function.php';

$paraResultId = checkParamId('id');
if(is_numeric($paraResultId)){

    $categoryId = validate($paraResultId);
    
    $category = getById('categories', $categoryId);
    if($category['status'] == 200){
        $categoryDelete = deletebyId('categories', $categoryId);
        if($categoryDelete){
            redirect('categories.php', 'Categories Successfully Deleted!');
        } else{
            redirect('categories.php', 'Something Went Wrong...');
        }
    } else {
        redirect('categories.php' , $category['message']);
    }
} else{
    redirect('categories.php', 'Something Went Wrong.');
}