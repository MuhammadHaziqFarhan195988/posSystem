<?php include('includes/header.php'); ?>  
<!-- this are for viewing all the orders in the database -->
<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
    <div class="card-header">
        <div class="row">
            <div class="col-md-4">
            <h4 class="mb-0">Orders</h4>
        </div>
                <div class="col-md-8">
                   <form action="" method="GET">
                    <div class="row g-1">
                                <div class="col-md-4">
                                    <input type="date"
                                    name="date"
                                    class="form-control"
                                    value="<?= isset($_GET['date']) == true ? $_GET['date']:''; ?>"/>
                                </div>
                                <div class="col-md-4">
                                    <select name="payment_status" class="form-select">
                                        <option value="">Select Payment</option>
                                        <option value="Cash Payment"
                                        <?= isset($_GET['payment_status']) == true ? 
                                        ($_GET['payment_status'] == 'Cash Payment' ? 'selected':''):'';
                                        
                                        ?>
                                        >Cash Payment</option>
                                        <option value="Online Payment"
                                        <?= isset($_GET['payment_status']) == true ? 
                                        ($_GET['payment_status'] == 'Online Payment' ? 'selected':''):'';
                                        
                                        ?>
                                        >Online Payment</option>
                                    </select>
                                </div>
                    
                                <div class="col-md-4"> <!-- we stopped at 3:59 -->
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="orders.php" class="btn btn-danger">Reset</a>
                                </div>
                    </div>
                   </form>
                </div>
            
        </div>
    </div>
        <div class="card-body">

        <?php

        if(isset($_GET['date']) || isset($_GET['payment_status'])){

            $orderDate = validate($_GET['date']);
            $paymentStatus = validate($_GET['payment_status']);

            if($orderDate != '' && $paymentStatus== ''){
                 $query = "SELECT o.*,c.* FROM orders o, customers c WHERE c.id = o.customer_id AND o.order_date='$orderDate' ORDER BY o.id DESC";
            } elseif($orderDate == '' && $paymentStatus != ''){
                $query = "SELECT o.*,c.* FROM orders o, customers c WHERE c.id = o.customer_id AND o.payment_mode='$paymentStatus' ORDER BY o.id DESC";
            } elseif($orderDate != '' && $paymentStatus != ''){
                $query = "SELECT o.*,c.* FROM orders o, customers c WHERE c.id = o.customer_id AND o.order_date='$orderDate' AND o.payment_mode='$paymentStatus' ORDER BY o.id DESC";
            }else {
                $query = "SELECT o.*,c.* FROM orders o, customers c WHERE c.id = o.customer_id ORDER BY o.id DESC";
            }
        }else{
            $query = "SELECT o.*,c.* FROM orders o, customers c WHERE c.id = o.customer_id ORDER BY o.id DESC";
        }
        
        $orders = mysqli_query($connection, $query); 
        #query for selecting all data in the orders table but keep in mind the SQL result
        # is far differ from what is rendered to the website, this is due to how we selectively choose 
        # which data to render despite having the data available
        if($orders){

            if(mysqli_num_rows($orders) > 0){

                ?>
                <table class = "table table-striped table-bordered align-items-center justify-content-center">
                <thead>
                    <tr>
                        <th>Tracking No.</th>
                        <th>C name</th>
                        <th>C phone</th>
                        <th>Order Date</th>
                        <th>Order Status</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                    </tr>
                </thead> 
                <tbody>
                    <?php foreach($orders as $orderItem) : ?>
                        <tr>
                            <td class="fw-bold"><?= $orderItem['tracking_no']; ?></td>
                            <td><?= $orderItem['name']; ?></td>
                            <td><?= $orderItem['phone']; ?></td>
                            <td><?= date('d m, Y', strtoTime($orderItem['order_date'])); ?></td>
                            <td><?= $orderItem['order_status']; ?></td>
                            <td><?= $orderItem['payment_mode']; ?></td>
                            <td>
                                <a href="orders-view.php?track=<?= $orderItem['tracking_no']; ?>" class= "btn btn-info mb-0 px-2 btn-sm">View</a>
                                <a href="orders-view-print.php?track=<?= $orderItem['tracking_no']; ?>" class= "btn btn-primary mb-0 px-2 btn-sm">Print</a>
                                <!-- if you notice there is a ? after the .php and that is because
                                we want it to go to the orders-view page but only after the item that we
                                are looking for, you can try same thing in google search bar -->
                                
                            </td>
                        </tr>
                        <?php endforeach; ?>
                </tbody>
                </table>
                <?php
            }else {
                echo "<h5>No Record Avaiable!</h5>";
            }
        }else{
            echo "<h5>Something Went Wrong!</h5>";
        }
        
        ?>

        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>