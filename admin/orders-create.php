<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">
                Create Orders
                <a href="orders.php" class="btn btn-primary float-end">Back to the Orders Page</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertDialog() ?>
            <form action="orders-code.php" method="POST">

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label for="">Select Product *</label>
                        <select name="product_id" required class="form-select mySelect2">
                            <option value="">-- Select Product --</option>
                            <?php
                            $products = getAll('products');
                            if($products){
                                    if(mysqli_num_rows($products) > 0){
                                        foreach($products as $prodItem){
                                        ?>
                                        <option value="<?= $prodItem['id']; ?>"><?= $prodItem['name']; ?></option>
                                        <?php
                                        }
                                    } else {
                                        echo '<option value=""> No product found </option>';
                                    }

                            } else {
                                echo '<option value=""> Something Went Wrong </option>';
                            }
                            ?>
                        </select>
                    </div>
                  
                    <div class="col-md-2 mb-3">
                        <label for="">Quantity</label>
                        <input type="number" name="quantity" value="1" class="form-control" />
                    </div>
                   
                    <div class="col-md-3 mb-3 text-end">
                    <br />    
                    <button type="submit" name="addItem" class= "btn btn-primary"> Add Item </button>
                        <!--This is where we left of --> 
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-3">
            <div class="card-header">
                <h4 class="mb-0">Products</h4>
            </div>
            <div class="card-body">
                <?php
                if(isset($_SESSION['productItems'])){
                    $sessionProducts = $_SESSION['productItems'];
                    ?>
                  <div class="table-responsive mb-3">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            foreach($sessionProducts as $key => $item) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $item['name']; ?></td>
                                <td><?= $item['price']; ?></td>
                                
                                <td>
                                <!-- this is bootstrap -->
                                    <div class="input-group"> 
                                        <button class="input-group-text">-</button>
                                        <input type="text" value="<?= $item['quantity']; ?>" class="qty quantityInput" />
                                        <button class="input-group-text">+</button>
                                    </div>
                                </td>
                                <td><?= number_format($item['price'] * $item['quantity'], 0); ?></td>
                                <td>
                                    <a href="order-item-delete.php?index=<?= $key; ?>" class="btn btn-danger">
                                    Remove
                                </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                            </table>
                    </div>
                    <?php
                } 
                else{
                    echo '<h5>No Item Added</h5>';
                }
                ?>
            </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>