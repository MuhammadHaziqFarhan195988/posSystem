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
        console.log('Proceed to place');
        var cphone = $('#cphone').val();
        var payment_mode = $('#payment_mode').val();
        //check whether payment mode is selected
        if(payment_mode == ''){
            swal("Select Payment Mode", "Select your payment mode", "warning");
            return false;
        }

        if(cphone == '' && !$.isNumeric(cphone)){
            swal("Enter Phone Number", "Enter Valid Phone Number", "warning");
            return false;
        }

        var data = {
            'proceedToPlaceBtn' : true,
            'cphone': cphone,
            'payment_mode' : payment_mode,

        };

        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: data,
            success: function (response) {
                
                var res = JSON.parse(response);
                if(res.status == 200){
                    window.location.href = "order-summmary.php";
                    
                }else if(res.status == 404){
                    
                    swal(res.message, res.message, res.status_type, {
                        buttons:{
                            catch: {
                                text: "Add Customer",
                                value: "catch"
                            },
                            cancel: "Cancel"

                        }
                    })
                    .then((value) => {
                        switch(value){

                            case "catch":
                                $('#addCustomerModal').modal('show');
                         //   console.log('Pop the customer add modal');
                            break;
                            default:
                        }
                    });
                } else {
                    
                    swal(res.message, res.message, res.status_type);
                }
            }
        });


    });

});


