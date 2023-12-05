<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">
                Edit Customer
                <a href="customers.php" class="btn btn-danger float-end">Back to the Customer Page</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertDialog() ?>
            <form action="code.php" method="POST">

                <?php 
                $paramValue = checkParamId('id'); #id from the url, or item id assigned by the mySQL
                if(!is_numeric($paramValue)){
                    echo '<h5>'.$paramValue.'</h5>';
                    return false;
                }
                
                $customerData = getById('customers', $paramValue);
                if($customerData['status'] == 200)
                {
                        ?>
                        <input type="hidden" name="customerId" value="<?= $customerData['data']['id']; ?>">
                        <div class="row">

<div class="col-md-12 mb-3">
    <label for="">Customer name *</label>
    <input type="text" name="name" required value="<?= $customerData['data']['name']; ?>" required class="form-control"  />
</div>

<div class="col-md-6 mb-3">
    <label for="">Customer Email</label>
    <input type="email" name="email" value="<?= $customerData['data']['email']; ?>" class="form-control" />
</div>
<div class="col-md-6 mb-3">
    <label for="">Phone Number</label>
    <input type="text" name="phone" value="<?= $customerData['data']['phone']; ?>" class="form-control" />
</div>
<div class="col-md-6">
    <label>
        Status (UnChecked=Visible, Checked=Hidden)
    </label>
    <br />
    <input type="checkbox" name="status" <?= $customerData['data']['status'] == true ? 'checked':''; ?> style="width:30px;height:30px";>
</div>

<div class="col-md-6 mb-3 text-end">
<br />    
<button type="submit" name="updateCustomer" class= "btn btn-primary"> Save info </button>
    
</div>
                                </div>
                        <?php
                   }
                   else {
                   echo '<h5>'.$customerData['message'].'</h5>';
                   }

                ?>
                
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>