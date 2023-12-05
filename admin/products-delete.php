<?php

require '../config/function.php';

$paraResultId = checkParamId('id');
if(is_numeric($paraResultId)){

    $productId = validate($paraResultId);
    
    $product = getById('products', $productId);
    if($product['status'] == 200){
        $productDelete = deletebyId('products', $productId);
        if($productDelete){
            $deleteImage = "../".$product['data']['image'];
            if(file_exists($deleteImage)){
                unlink($deleteImage);
            }
            redirect('products.php', 'Product Successfully Deleted!');
        } else{
            redirect('products.php', 'Something Went Wrong...');
        }
    } else {
        redirect('products.php' , $category['message']);
    }
} else{
    redirect('products.php', 'Something Went Wrong.');
}