<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">
                Edit Products
                <a href="products.php" class="btn btn-primary float-end">Back to the Admin Page</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertDialog(); ?>
            <form action="code.php" method="POST" enctype="multipart/form-data"> <!-- now that we use File as the <input> type, we now have to add enctype to  -->
                                 <!-- allow the code to handle such values-->
                                 <?php
                    $paramValue = checkParamId('id');
                    if(!is_numeric($paramValue)){
                        echo '<h5> Id is not an integer</h5>';
                        return false;
                    }

                    $product = getById('products', $paramValue);
                    if($product){

                        if($product['status'] == 200){
                            ?>
                       
             <div class="row">
                <div class="col-md-12 mb-3">

                <label>Select Category</label> <!-- Create a dropdown -->
                <select name="category_id" class="form-select">
                 <option value="">Select Category</option> <!-- Create a loop to go through every data in SQL 'category' -->
                    <?php 
                    $categories = getAll('categories');
                    if($categories){
                    if(mysqli_num_rows($categories) > 0){
                    foreach($categories as $categoryData){
                        ?>
<option value="<?= $categoryData['id']; ?>"> <?= $categoryData['name']; ?></option>
                        <?php
                    
                    }
                    } else {
                    echo '<option value="">Error 404 data not found missing categories SQL table</option>';
                    } #Go through entire record in SQL table 'categories' else return error
                    } else {
                    echo '<option value="">Error, getAll() function is not executing </option>';
                    #Execute getAll() else return error
                    }
                    ?>
                </select> 
                </div>

                    <div class="col-md-4 mb-3">
                        <label for="">Item Image</label>
                        <input type="file" name="image"  class="form-control" />
                        <img src="../<?= $product['data']['image']; ?>" style="width:40px;height:40px;" alt="Img">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="">Item Name *</label>
                        <input type="text" name="name" required value="<?= $product['data']['name']; ?>" class="form-control" />
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="">Item Price *</label>
                        RM<input type="number" name="price"  required value="<?= $product['data']['price']; ?>" class="form-control" />
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="">Item Quantity *</label>
                        <input type="number" name="quantity"  required value="<?= $product['data']['quantity']; ?>" class="form-control" />
                    </div>
                  
                    <div class="col-md-12 mb-3">
                        <label for="">Description</label><!-- dont forget to database dump -->
                        <textarea name="description" class="form-control" rows="3"><?= $product['data']['description']; ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label>
                            Status (UnChecked=Visible, Checked=Hidden)
                        </label>
                        <br />
                        <input type="checkbox" name="status" <?= $product['data']['status'] == true? 'checked':'';  ?> style="width:30px;height:30px";>
                    </div>

                    <div class="col-md-6 mb-3 text-end">
                    <br />    
                    <button type="submit" name="updateProduct" class= "btn btn-primary"> Save info </button>
                        
                    </div>
                </div>
                    <?php
                     } else {
                        echo '<h5>'.$product['message'].'</h5>';
                    
                    }
                } else {
                    echo '<h5>Something Went Wrong</h5>';
                    return false;
                }
                ?>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>