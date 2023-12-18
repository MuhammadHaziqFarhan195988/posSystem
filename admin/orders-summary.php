<?php 

include('includes/header.php'); 
#to check whether or not we have the product in our session variable
if(!isset($_SESSION['productItems'])){
    echo '<script> window.location.href = "orders-create.php";</script>';
# we redirect them back to create order page
}
# notice how we essentially execute a html statement of script tag?
# essentially php is all about server puppetiering, we tell server what to do
# while html is telling browser what to do
?>


<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="mb-0"></h4>
                        Order Summary
                        <a href="orders-create.php" class = "btn btn-danger float-end">Back to create order</a>
                    </h4>
                </div>
                <div class="card-body">
                    <?php alertDialog(); ?>

                    <div id="myBillingArea">

                    <?php 
                    if(isset($_SESSION['cphone'])){
                        $phone = validate($_SESSION['cphone']);
                        $invoiceNo = validate($_SESSION['invoice_no']);
                       
                        $customerQuery = mysqli_query($connection, "SELECT * FROM customers WHERE phone = '$phone' LIMIT 1");
                        if($customerQuery){
                            if(mysqli_num_rows($customerQuery) > 0){
                                $cRowData = mysqli_fetch_assoc($customerQuery);
                                ?>
                                <table style="width: 100%; margin-bottom: 20px;">
                                <tbody>
                                    <tr>
                                        <td style="text-align: center;" colspan="2">
                                            <h4 style="font-size: 23px; line-height: 30px; margin:2px; padding: 0;"> Haziq ShopFix
                                            </h4>
                                            <p style="font-size: 16px; line-height: 24px; margin:2px; padding: 0;">13, Jalan Jed 6, Taman Jed, Selangor</p>
                                            <p style="font-size: 16px; line-height: 24px; margin:2px; padding: 0;"> ShopFix Sdn Bhd</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                        <h5 style="font-size: 20px; line-height: 30px; margin:0px; padding: 0;"> Customer Details
                                            </h5>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Customer Name: <?= $cRowData['name'] ?></p>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Customer Phone No.: <?= $cRowData['phone'] ?></p>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Customer Email Id: <?= $cRowData['email'] ?></p>
                                       
                                        </td>
                                        <td align="right">
                                            <h5 style="font-size: 20px; line-height: 30px; margin:0px; padding: 0;"> Invoice Details</h5>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Invoice No: <?= $invoiceNo ?></p>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Invoice Date: <?= date('d M Y'); ?></p>
                                            <p style="font-size: 14px; line-height: 20px; margin:0px; padding: 0;">Address: 13, Jalan Jed 6, Taman Jed, Selangor</p>
                                            <!-- we stopped at 19:37 -->
                                       
                                        </td>

                                    </tr>
                                </tbody>
                                </table>
                                <?php
                            }else {
                                echo "<h5>No customer found!</h5>";
                                return;
                            }
                        }
                    }
                    ?>

                    <?php
                    if(isset($_SESSION['productItems']))
                    {
                        $sessionProducts= $_SESSION['productItems'];
                        ?>
                        <div class="table-responsive mb-3">
                        <table style="width:100%;" cellpadding="5">
                    <thead>
                        <tr>
                            <th align="left" style="border-bottom: 1px solid #ccc;" width="5%">ID</th>
                            <th align="left" style="border-bottom: 1px solid #ccc;">Product Name</th>
                            <th align="left" style="border-bottom: 1px solid #ccc;" width="10%">Price</th>
                            <th align="left" style="border-bottom: 1px solid #ccc;" width="10%">Quantity</th>
                            <th align="left" style="border-bottom: 1px solid #ccc;" width="15%">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $totalAmount = 0; #the key is being mapped to row
                        foreach($sessionProducts as $key=> $row ) : #it goes through an entire cart
                            $totalAmount += $row['price'] * $row['quantity']
                            ?>
                            <tr>
                                <td style="border-bottom: 1px solid #ccc;"><?= $i++; ?></td> 
                                <td style="border-bottom: 1px solid #ccc;"><?= $row['name']; ?></td>
                                <td style="border-bottom: 1px solid #ccc;"><?= number_format($row['price'], 0) ?></td>
                                <td style="border-bottom: 1px solid #ccc;"><?= $row['quantity'] ?></td>
                                <td style="border-bottom: 1px solid #ccc;" class = "fw-bold">
                            <?= number_format($row['price'] * $row['quantity'], 0) ?></td>    
                            </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="4" align="right" style="font-weight: bold;">Overall Total: </td>
                                <td colspan="1" style="font-weight: bold;"><?= number_format($totalAmount, 0); ?></td>
                            </tr>
                            <tr>
                                <td colspan="5">Payment Mode: <?= $_SESSION['payment_mode'];?></td> 
                                <!-- this data is from proceedToPlaceBtn from orders-code.php -->
                            </tr>
                    </tbody>
                    </table>

                        </div>
                        <?php
                    }else {
                        echo '<h5 class= "text-center">No Items Added</h5>';
                    }
                    ?>
                    </div>


                    </div>
            </div>
        </div>
    </div>
</div>


<?php include('includes/footer.php'); ?>