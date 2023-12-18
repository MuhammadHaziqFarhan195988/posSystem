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
    } 

    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phoneNumber = validate($_POST['phonenumber']);
    $is_ban = isset($_POST['is_ban']) == true ? 1:0; 

    #Used to verify if the email is already been used when editing the account
    $EmailCheckQuery = "SELECT * FROM admins WHERE email='$email' AND id!='$adminId'";
    $checkResult = mysqli_query($connection, $EmailCheckQuery);
    if($checkResult){
        if(mysqli_num_rows($checkResult) > 0){
            redirect('admins-edit.php?id='.$adminId, 'Email Already used by another user');
    }
    }

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


if(isset($_POST['saveCategory'])){
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $status = isset($_POST['status']) == true ? 1:0;

    $data = [
        'name' => $name, 
        'description'=> $description,
        'status' => $status,
     ];
     $result = insert('categories', $data);
 
     if($result){
         redirect('categories.php', 'Category Created Successfully!');
     }else {
         redirect('categories-create.php', 'Something Went Wrong!');
     }
}

if(isset($_POST['updateCategory'])){
    $categoryId = validate($_POST['categoryId']);
    $name = validate($_POST['name']);
    $description = validate($_POST['description']);
    $status = isset($_POST['status']) == true ? 1:0;

    $data = [
        'name' => $name, 
        'description'=> $description,
        'status' => $status,
     ];
     $result = updateDB('categories', $categoryId ,$data); #update using mySQL query
 
     if($result){
         redirect('categories-edit.php?id='.$categoryId, 'Category Updated Successfully!');
     }else {
         redirect('categories-edit.php?id='.$categoryId, 'Something Went Wrong!');
     }
}


if(isset($_POST['saveProduct'])){
    $categoryId = validate($_POST['category_id']);
    $name = validate($_POST['name']);
    $price = validate($_POST['price']);
    $quantity = validate($_POST['quantity']);
    $description = validate($_POST['description']);
    $status = isset($_POST['status']) == true ? 1:0;
    if($_FILES['image']['size'] > 0){#check if size greater than 0, to basically indicate that the image is there
        $path = "../assets/uploads/products";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION); #i assume that this represents getting infomation about the image file, but this one is getting info
        #about extension file such as png, jpeg, jpg

        $filename = time().'.'.$image_ext; #file name will be automatically generated base on time() and an extension will be added

        move_uploaded_file($_FILES['image']['tmp_name'], $path."/".$filename); #insert this to assets folder

        $finalImage = "assets/uploads/products/".$filename;
    }  else {
        $finalImage = '';
    }
    
   

    $data = [
        'category_id' => $categoryId,
        'image' => $finalImage,
        'name' => $name, 
        'price' => $price,
        'quantity' => $quantity,
        'description'=> $description,
        'status' => $status,
     ];
     $result = insert('products', $data);
 
     if($result){
         redirect('products.php', 'Product Created Successfully!');
     }else {
         redirect('products-create.php', 'Something Went Wrong!');
     }
}

if(isset($_POST['updateProduct']))
{
    $product_id = validate($_POST['product_id']);
    $productData = getById('products', $product_id);
    if(!$productData){
redirect('product.php', 'No such product found');
    }
    $categoryId = validate($_POST['category_id']);
    if($_FILES['image']['size'] > 0){#check if size greater than 0, to basically indicate that the image is there
        $path = "../assets/uploads/products";
        $image_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION); #i assume that this represents getting infomation about the image file, but this one is getting info
        #about extension file such as png, jpeg, jpg

        $filename = time().'.'.$image_ext; #file name will be automatically generated base on time() and an extension will be added

        move_uploaded_file($_FILES['image']['tmp_name'], $path."/".$filename); #insert this to assets folder
        $finalImage = "assets/uploads/products/".$filename;

        $deleteImage = "../".$productData['data']['image']; #the purpose of this code is that, if user decided to upload new image
                                                            #then the old image is deleted, if not then set the new image as image
        if(file_exists($deleteImage)){ #if old image exists
unlink($deleteImage); #delete old image
        }
    }  else { #if there are no image file prior to this
        $finalImage = $productData['data']['image']; #image inside of the file which has recently uploaded to product, are to be left alone
    }
    $image = validate($_POST['image']);
    $name = validate($_POST['name']);
    $price = validate($_POST['price']);
    $quantity = validate($_POST['quantity']);
    $description = validate($_POST['description']);
    $status = isset($_POST['status']) == true ? 1:0;

    $data = [
        'category_id' => $categoryId,
        'image' => $finalImage,
        'name' => $name, 
        'price' => $price,
        'quantity' => $quantity,
        'description'=> $description,
        'status' => $status,
     ];
     $result = updateDB('products', $product_id , $data);
 
     if($result){
         redirect('products-edit.php?id='.$product_id, 'Product Updated Successfully!');
     }else {
         redirect('products-edit.php?id='.$product_id, 'Something Went Wrong!');
     }
}

if(isset($_POST['saveCustomer'])){
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    $status = isset($_POST['status']) == true ? 1:0;

    if($name != '' && $email != '' ){ #nullchecker
        $emailCheck = mysqli_query($connection, "SELECT * FROM customers WHERE email = '$email'");
        if($emailCheck){
        if(mysqli_num_rows($emailCheck) > 0){
            redirect('customers-create.php', 'Email already exists');
        }
    } 
    

    $data = [
       'name' => $name, 
       'email'=> $email,
       'phone' => $phone,	
       'status' => $status,
    ];
    $result = insert('customers', $data);

    if($result){
        redirect('customers.php', 'Customer Created Successfully!');
    }else {
        redirect('customers-create.php', 'Something Went Wrong!');
    }
    
    
}else{
        redirect('customers-create.php', 'Please fill the required fields');
    }
}

if(isset($_POST['updateCustomer'])){
    $customerId = validate($_POST['customerId']);
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    $status = isset($_POST['status']) == true ? 1:0;

    if($name != '' && $email != '' ){ #nullchecker
        $emailCheck = mysqli_query($connection, "SELECT * FROM customers WHERE email = '$email'");
        if($emailCheck){
        if(mysqli_num_rows($emailCheck) > 0){
            redirect('customers-edit.php?id='.$customerId, 'Email already used by another user');
        }
    }

    $data = [
        'name' => $name, 
       'email'=> $email,
       'phone' => $phone,	
       'status' => $status,
     ];
     $result = updateDB('customers', $customerId ,$data); #update using mySQL query
 
     if($result){
         redirect('customers-edit.php?id='.$customerId, 'Customer Updated Successfully!');
     }else {
         redirect('customers-edit.php?id='.$customerId, 'Something Went Wrong!');
     }

    }else {
        redirect('customers-edit.php?id='.$customerId, 'Please fill required fields');
    }
}