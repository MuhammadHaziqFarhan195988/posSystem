<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">
                Add Products
                <a href="products.php" class="btn btn-primary float-end">Back to the Admin Page</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertDialog() ?>
            <form action="code.php" method="POST" enctype="multipart/form-data"> <!-- now that we use File as the <input> type, we now have to add enctype to  -->
                                                    <!-- allow the code to handle such values-->
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
    echo '<option value="'.$categoryData['id'].'">'.$categoryData['name'].'</option>';
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
                        <input type="file" name="image" class="form-control" />
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="">Item Name *</label>
                        <input type="text" name="name" required class="form-control" />
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="">Item Price *</label>
                        RM<input type="number" name="price" required class="form-control" />
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="">Item Quantity *</label>
                        <input type="number" name="quantity" required class="form-control" />
                    </div>
                  
                    <div class="col-md-12 mb-3">
                        <label for="">Description</label><!-- dont forget to database dump -->
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label>
                            Status (UnChecked=Visible, Checked=Hidden)
                        </label>
                        <br />
                        <input type="checkbox" name="status" style="width:30px;height:30px";>
                    </div>

                    <div class="col-md-6 mb-3 text-end">
                    <br />    
                    <button type="submit" name="saveProduct" class= "btn btn-primary"> Save info </button>
                        
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>