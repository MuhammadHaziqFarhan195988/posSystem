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

if(isset($_POST['productIncDec'])){
    $productId = validate($_POST['product_id']);
    $quantity = validate($_POST['quantity']);

    $flag = false;
    foreach($_SESSION['productItems'] as $key => $item){
        if($item['product_id'] == $productId){ //inside of this array, when you want to access a specific value inside the array,
                                                // we use key, which stores the value of integer like i for
            $flag = true;
        $_SESSION['productItems'][$key]['quantity'] = $quantity;                    //refer quantity as column
    } //refering to the global array variable Session pick the array with productItems in it, with productItems we want a specific
    }; // item in the array cell so it's number key, and inside of that item there are attributes such as quantity
if($flag){ #if true then tell success
    jsonResponse(200, 'success', 'Quantity Updated');
    
}else{
    jsonResponse(500, 'error', 'Something Went Wrong. Please refresh');
}

}

if(isset($_POST['proceedToPlaceBtn'])){ #Handling the customer
$phone = validate($_POST['cphone']);
$payment_mode = validate($_POST['payment_mode']);
 
// Checking for Customer
$query = "SELECT * FROM customers WHERE phone='$phone' LIMIT 1";
$checkCustomer = mysqli_query($connection,$query);
if($checkCustomer){
    if(mysqli_num_rows($checkCustomer) > 0){
        $_SESSION['invoice_no'] = "INV-".rand(111111,999999);
        $_SESSION['cphone'] = $phone;
        $_SESSION['payment_mode'] = $payment_mode;

        jsonResponse(200, 'success', 'Customer Found');
    } else {
        $_SESSION['cphone'] = $phone;
        jsonResponse(404, 'warning', 'We cannot find that customer');
    }
}
else {
    jsonResponse(500, 'error', 'Something Went Wrong');
}

}

if(isset($_POST['saveCustomerBtn'])){ 
    $name = validate($_POST['name']);
    $phone = validate($_POST['phone']);
    $email = validate($_POST['email']);

    if($name != '' && $phone != ''){ #this is a nullchecker, though we already did so in custom.js
        #if successful then send data to MySQL
        $data = [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
        ];

        $result = insert('customers', $data); #inside of insert function, to customers table insert $data

        if($result){
            jsonResponse(200, 'success', 'Customer Created Successfully!');
        }else {
            jsonResponse(500, 'error', 'Something Went Wrong!');
        }
    } else {
        jsonResponse(422, 'warning', 'Please fill the required fields!');
    }
}


if(isset($_POST['saveOrder'])){
#get all data from customer data invoice details
    $phone = validate($_SESSION['cphone']); #stored in SESSION instead of POST because we already 
    $invoice_no = validate($_SESSION['invoice_no']); # sent(POST) and stored data of cphone into SESSION
    $payment_mode = validate($_SESSION['payment_mode']); #refer to proceedToPlaceBtn
    $order_placed_by_id = $_SESSION['loggedInUser']['user_id'];

    $checkCustomer = mysqli_query($connection, "SELECT * FROM customers WHERE phone='$phone' LIMIT 1");
    if(!$checkCustomer){
        jsonResponse(500, 'error' , 'Something Went Wrong!');
    }

    if(mysqli_num_rows($checkCustomer) > 0){
        $customerData = mysqli_fetch_assoc($checkCustomer);

        if(!isset($_SESSION['productItems'])){#check whether the product is in the session
            jsonResponse(500, 'warning' , 'No Items to place order!');
        } 

        $sessionProducts = $_SESSION['productItems'];
        $totalAmount = 0;
        foreach($sessionProducts as $amtItem){
            $totalAmount += $amtItem['price'] * $amtItem['quantity'];
        }

        $data = [
            'customer_id' => $customerData['id'],
            'tracking_no' => rand(11111,99999), #strange how it still use random() 
            'invoice_no'  => $invoice_no, #oh wait tracking number differ from invoice no, right?
            'total_amount' => $totalAmount,
            'order_date' => date('Y-m-d'),
            'order_status' => 'booked',
            'payment_mode' => $payment_mode,
            'order_placed_by_id' => $order_placed_by_id
        ];
        $result = insert('orders', $data);
        $lastOrderId = mysqli_insert_id($connection);

        foreach($sessionProducts as $prodItem){
            $productId = $prodItem['product_id'];
            $price = $prodItem['price'];
            $quantity = $prodItem['quantity'];

            //inserting order items
            $dataOrderItem = [ //according to the mySQL database
                'order_id' => $lastOrderId,
                'product_id' => $productId,
                'price' => $price,
                'quantity' => $quantity,
            ];
            $orderItemQuery = insert('order_items', $dataOrderItem); // pass to database

            //checking for the books quantity and decreasing quantity and make total quantity
            $checkProductQuantityQuery = mysqli_query($connection,"SELECT * FROM products WHERE id='$productId'");
            $productQtyData = mysqli_fetch_assoc($checkProductQuantityQuery);
            //once we get the quantity we then decrease it in our stock
            $totalProductQuantity = $productQtyData['quantity'] - $quantity;

            $dataUpdate = [
                'quantity' => $totalProductQuantity
            ];
            $updateProductQty = updateDB('products', $productId, $dataUpdate);
        }

        unset($_SESSION['productItems']); #we then empty data in the Session variable 
        unset($_SESSION['productItemIds']); #so that the server can use it to pick up next item data
        unset($_SESSION['cphone']);
        unset($_SESSION['payment_mode']);
        unset($_SESSION['invoice_no']);

        jsonResponse(200, 'success', 'Order Placed Successfully!');

    } else
    {
        jsonResponse(404, 'warning', 'No customer found!');
    }
}
