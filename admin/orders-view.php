<?php include('includes/header.php'); ?>  
<!-- this are for viewing all the orders in the database -->
<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Order View
                <a href="orders.php" class = "btn btn-danger mx-2 btn-sm float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
    <?php alertDialog() ?>

    <?php #notice how the page is only rendered when the tracking number exists, otherwise it displays blank
     if(isset($_GET['track'])){ # <-- take a look at this
        if($_GET['track'] == ''){
            ?>
<div class="text-center">
            <h5>No Tracking Number Found</h5>
            <a href="orders.php" class="btn btn-primary mt-4 w-25">Go back to orders</a>
        </div>
            <?php
            return false;
        }
        
        $trackingNo = validate($_GET['track']);

        $query = "SELECT o.*,c.* FROM orders o, customers c WHERE c.id = o.customer_id 
        AND tracking_no='$trackingNo' ORDER BY o.id DESC";
     #now we check the MySQL query to retrieve data of one particular item to view on
        $orders = mysqli_query($connection, $query);
        if($orders){

            if(mysqli_num_rows($orders) > 0){
                $orderData = mysqli_fetch_assoc($orders);
                $orderId = $orderData['id'];

                ?>
                <div class="card card-body shadow border-1 mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Order Details</h4>
                            <label class="mb-1">
                                Tracking No: <span class = "fw-bold"> <?= $orderData['tracking_no']; ?></span>
                            </label>
                            <br />
                            <label class="mb-1">
                                Order Date: <span class = "fw-bold"> <?= $orderData['order_date']; ?></span>
                            </label>
                            <br />
                            <label class="mb-1">
                                Order Status: <span class = "fw-bold"> <?= $orderData['order_status']; ?></span>
                            </label>
                            <br />
                            <label class="mb-1">
                                Payment Method: <span class = "fw-bold"> <?= $orderData['payment_mode']; ?></span>
                            </label>
                            <br />
                        </div>
                        <div class="col-md-6">
                            <h4> User Details</h4>
                            <label class="mb-1">
                                Full Name: <span class = "fw-bold"> <?= $orderData['name']; ?></span>
                            </label>
                            <br />
                            <label class="mb-1">
                                Email Address: <span class = "fw-bold"> <?= $orderData['email']; ?></span>
                            </label>
                            <br />
                            <label class="mb-1">
                               Phone No: <span class = "fw-bold"> <?= $orderData['phone']; ?></span>
                            </label>
                            <br />
                        </div>
                    </div>
                </div>

                <?php # were going to mingle with 3 tables products, order_items and orders
    $orderItemQuery = "SELECT oi.quantity as orderItemQuantity, oi.price as orderItemPrice, o.*, oi.*, p.* 
    FROM orders as o, order_items as oi, products as p
    WHERE oi.order_id = o.id AND p.id = oi.product_id AND o.tracking_no='$trackingNo'";
    $orderItemsRes = mysqli_query($connection, $orderItemQuery);
    if($orderItemsRes){
        if(mysqli_num_rows($orderItemsRes) > 0){
            ?>
            <h4 class = "my-3"> Order Items Details</h4>
            <table class = "table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orderItemsRes as $orderItemRow) : ?>
                    <tr>
                        <td>
                            <img src="<?= $orderItemRow['image'] != '' ? '../'.$orderItemRow['image']: '../assets/img/no-img.jpg'; ?>"
                            style="width:50px;height:50px;"
                            alt="Img" />
                            <?= $orderItemRow['name']; ?>
                        </td>
                        <td width="15%" class="fw-bold text-center">
                            <?= number_format($orderItemRow['orderItemPrice']) ?>
                        </td>
                        <td width="15%" class="fw-bold text-center">
                            <?= $orderItemRow['orderItemQuantity'] ?>
                        </td>
                        <td width="15%" class="fw-bold text-center">
                            <?= number_format($orderItemRow['orderItemPrice'] * $orderItemRow['orderItemQuantity']) ?>
                            <!-- this is from modified variable inside of SQL query -->
                        </td>
                    </tr>
                    <?php endforeach ; ?> 

                    <tr>
                        <td class = "text-end fw-bold">
                        Total Price:
                        </td>
                        <td colspan="3" class="text-end fw-bold">RM: <?= number_format($orderItemRow['total_amount'], 0); ?></td>

                    </tr>
            </tbody>
            </table>
            <?php

        } else {
            echo 'No Item Found';
       
        }
    }else {
        echo 'Something Went Wrong';
        return false;
    }
                ?>

                <?php
            }else {
                echo "<h5>No Record Found </h5>";
                return false;
            }
        } else {
            echo "<h5>Something Went Wrong </h5>";
        }
   
    }
    else {
        ?>
        <div class="text-center">
            <h5>No Tracking Number Found</h5>
            <a href="orders.php" class="btn btn-primary mt-4 w-25">Go back to orders</a>
        </div>
        <?php
        
    }
    ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>