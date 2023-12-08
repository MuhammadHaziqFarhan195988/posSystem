<?php
require '../config/function.php';

$paramResult = checkParamId('index'); #based on parameter index using value $key(i think) but i don't see the variables which has index in it
if(is_numeric($paramResult)){

$indexValue = validate($paramResult);

if(isset($_SESSION['productItems']) && isset($_SESSION['productItemsIds'])){

    unset($_SESSION['productItems'][$indexValue]);
    unset($_SESSION['productItemsIds'][$indexValue]);

    redirect('orders-create.php','Item Removed');
}else {
    redirect('orders-create.php','There is no item or Item already Removed');
}

}else {
    redirect('orders-create.php','param not numeric');
}

