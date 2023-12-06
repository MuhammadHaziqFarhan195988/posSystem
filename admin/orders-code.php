<?php 

include('../config/function.php');

if(!isset($_SESSION['productItems'])){
    $_SESSION['productItems'] = [];

}

if(!isset($_SESSION['productItemsIds'])){
    $_SESSION['productItemsIds'] = [];
    
}


if(isset($_POST['addItem'])){ #this is for customer adding item in cart

    $productId = validate($_POST['product_id']);
    $quantity = validate($_POST['quantity']);

    $checkProduct = mysqli_query($connection,"SELECT * FROM products WHERE id= '$productId' LIMIT 1");

    if($checkProduct){
        if(mysqli_num_rows($checkProduct) > 0){ #if the product is available in store

            $row = mysqli_fetch_assoc($checkProduct); #fetch the products data from table with specified ID
            if($row['quantity'] < $quantity){
                    redirect('orders-create.php','This item only has'.$row['quantity'].' in stock!');
            }

            $productData=[ #adding items into the cart which is represented as an Array
                'product_id' => $row['id'],
                'name' => $row['name'],
                'image' => $row['image'],
                'price' => $row['price'],
                'quantity' => $quantity,
            ];

            if(!in_array($row['id'], $_SESSION['productItemsIds'])){

                array_push($_SESSION['productItemsIds'], $row['id']);
                array_push($_SESSION['productItems'], $productData);
            }else {

                foreach($_SESSION['productItems'] as $key => $productSessionItem){ 
                    #the purpose of this loop is to ensure users can update their quantity as they wanted to in case if they made a mistake

                    if($productSessionItem['product_id'] == $row['id']){
                        $newQuantity = $productSessionItem['quantity'] + $quantity;

                        $productData=[ 
                            'product_id' => $row['id'],
                            'name' => $row['name'],
                            'image' => $row['image'],
                            'price' => $row['price'],
                            'quantity' => $newQuantity,
                         ];

                         $_SESSION['productItems'][$key] = $productData;
                    }
                }
            }

            redirect('orders-create.php', 'Item Added '.$row['name']);

        }else {
            redirect('orders-create.php', 'No such product found!');
        }
    }else{
        redirect('orders-create.php', 'Something Went Wrong!');
    }
}