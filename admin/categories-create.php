<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">
                Add Categories
                <a href="categories.php" class="btn btn-primary float-end">Back to the Admin Page</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertDialog() ?>
            <form action="code.php" method="POST">

                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label for="">Item Type *</label>
                        <input type="text" name="name" required class="form-control" />
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
                    <button type="submit" name="saveCategory" class= "btn btn-primary"> Save info </button>
                        <!--This is where we left of --> 
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>