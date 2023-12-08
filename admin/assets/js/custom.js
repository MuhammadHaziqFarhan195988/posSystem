$(document).ready(function () { //script for increment
    
    alertify.set('notifier','position', 'top-right');

    $(document).on('click','.increment' , function () {
        
        var $quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId = $(this).closest('.qtyBox').find('.prodId').val(); //for backend

        var currentValue = parseInt($quantityInput.val());

        if(!isNaN(currentValue)){
            var qtyVal = currentValue + 1;
            $quantityInput.val(qtyVal);
            quantityIncDec(productId, qtyVal);
        }

    });    

    $(document).on('click','.decrement' , function () { //script for decrement
        
        var $quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId = $(this).closest('.qtyBox').find('.prodId').val(); //for backend adding .val() in the end because it can't fetch
                                                                          //without one for some reason
        var currentValue = parseInt($quantityInput.val());

        if(!isNaN(currentValue) && currentValue > 1){
            var qtyVal = currentValue - 1;
            $quantityInput.val(qtyVal);
            quantityIncDec(productId, qtyVal);
        }

    });

// now to update the session
function quantityIncDec(prodId, qty){ //represented by Increment and Decrement

    $.ajax({
        type: "POST",
        url: "orders-code.php",
        data: {
            'productIncDec': true,
            'product_id' : prodId,
            'quantity' : qty
        },
        
        success: function (response) { //suppose that the response to this command is a success then
            var res = JSON.parse(response); //convert a java script value to Javascript Object Notation
            //console.log(res);
            if(res.status == 200){
              // window.location.reload();
             $('#productArea').load(' #productContent');
                alertify.success(res.message);
                
            } else {
                alertify.error(res.message);
            }
        }
    });
}

    //proceed to place order button clicked
    $(document).on('click','.proceedToPlace', function () {
        
        var payment_mode = $('#payment_mode').val();
        //check whether payment mode is selected
        if(payment_mode != ''){
            swal("Select Payment Mode", "Select your payment mode", "warning");
            return false;
        }


    });

});


