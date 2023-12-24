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

        var data = { // when we clicked the proceed to place button 
            'proceedToPlaceBtn' : true, // the state then was proceedToPlace which then
            'cphone': cphone,//we are enabling proceedToPlaceBtn to be true so the orders-code.php can handle
            'payment_mode' : payment_mode,// the same data but with different state name

        };

        $.ajax({ // to search and attribute customer to orders, otherwise add new customer
            type: "POST",
            url: "orders-code.php",
            data: data,
            success: function (response) {
                
                var res = JSON.parse(response);
                if(res.status == 200){
                    window.location.href = "orders-summary.php";
                    
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
                                $('#c_phone').val(cphone); //let's just say you don't want to retype the phone number again when adding new customers
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



    //Add Customers to customer table via Error Message in Order-Create.php
    $(document).on('click','.saveCustomer', function () { //you see, inside of on() parameter there are events, 
                                                          //the events that we are expecting is click
        //1. we have to put our input values of the customer that we want to add

        //based on the id that we have labeled inside of order-create.php
        // we now have to put hashtag behind the id name
        var c_name = $('#c_name').val(); 
        var c_phone = $('#c_phone').val(); 
        var c_email = $('#c_email').val(); 
                                         
        if(c_name != '' && c_phone != ''){

            if($.isNumeric(c_phone)) {
                // after we have confirm it's a valid data, it's time to send to MySQL
                var data = {
                    'saveCustomerBtn' : true, // in order to trigger function named saveCustomerBtn, it has to be true
                    'name' : c_name,
                    'phone' : c_phone,
                    'email' : c_email,
                };

                $.ajax({
                    type: "POST",
                    url: "orders-code.php", //we redirect to orders-code.php for logic of submit data
                    data: data,
                    success: function (response) {
                        var res = JSON.parse(response);

                        if(res.status == 200){
                            swal(res.message, res.message, res.status_type);
                            $('#addCustomerModal').modal('hide');
                        }else if(res.status == 422) {
                            swal(res.message, res.message, res.status_type);
                        }
                        else {
                            swal(res.message, res.message, res.status_type);
                        }
                    }
                    }
                );

            }else{
                swal("Enter Valid Phone Number!", "", "warning");
            }
        }
        else {
            swal("Please fill the required fields!", "", "warning");
        }
    }); 

    $(document).on('click','#saveOrder' , function () { //jqOn does something, i think it problem related to eventHandler
        // in order to retrieve id from the button, we must add hash (#)

$.ajax({
    type: "POST",
    url: "orders-code.php",
    data: {
        "saveOrder" :true
    },
    success: function (response) {
        var res = JSON.parse(response);

        if(res.status == 200){
            swal(res.message,res.message,res.status_type);
            $('#orderPlaceSuccessMessage').text(res.message);
            $('#orderSuccessModal').modal('show');
        } else {
            swal(res.message,res.message,res.status_type);
        }
    }
});

    });


});

function printMyBillingArea() {
    var divContents = document.getElementById("myBillingArea").innerHTML;
    var a = window.open('','');
    a.document.write('<html><title>POS System in PHP</title>');
    a.document.write('<body style="font-family: fangsong;">');
    a.document.write(divContents);
    a.document.write('</body></html>');
}

window.jsPDF = window.jspdf.jsPDF;
var docPDF = new JsPDF;

function downloadPDF(invoiceNo) {
    
    var elementHTML = document.querySelector("#myBillingArea"); //Please remember # for every id in HTML
    docPDF.html(elementHTML, {
        callback: function() {
            docPDF.save(invoiceNo+'.pdf');
        },
        x: 15,
        t: 15,
        width: 170,
        windowWidth: 650
    })
}

