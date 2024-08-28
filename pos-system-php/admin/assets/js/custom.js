$(document).ready(function () {

    alertify.set('notifier','position', 'top-right');

    $(document).on('click', '.increment', function () {

        var $quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId = $(this).closest('.qtyBox').find('.prodId').val();
        var currentValue = parseInt($quantityInput.val());

        if(!isNaN(currentValue)) {
            var qtyVal = currentValue + 1;
            $quantityInput.val(qtyVal);
            quantityInDec(productId, qtyVal);
        }
    });

    $(document).on('click', '.decrement', function () {

        var $quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId = $(this).closest('.qtyBox').find('.prodId').val();
        var currentValue = parseInt($quantityInput.val());

        if(!isNaN(currentValue) && currentValue > 1) {
            var qtyVal = currentValue - 1;
            $quantityInput.val(qtyVal);
            quantityInDec(productId, qtyVal);
        }
    });

    function quantityInDec(prodId, qty) {
        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: {
                'productInDec': true,
                'product_id': prodId,
                'quantity': qty
            },
            success: function (response) {
                var res = JSON.parse(response);
                //console.log(res);

                if(res.status == 200) {
                    // window.location.reload();
                    $('#productArea').load(' #productContent');
                    alertify.success(res.message);
                } else {
                    $('#productArea').load(' #productContent');
                    alertify.error(res.message);
                }

            }
        });
    }

    $(document).on('click', '.saveCustomer', function() {
        $('#addCustomerModal').modal('show');
    });

    $(document).on('click', '.proceedToPlace', function () {

        console.log('proceedToPlace');

        var cphone = $('#cphone').val();
        var payment_mode = $('#payment_mode').val();

        if (payment_mode === '') {
            Swal.fire({
                title: 'Select Payment Mode',
                text: 'Select your payment mode',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return false;
        }

        if (cphone === '' || !$.isNumeric(cphone)) {
            Swal.fire({
                title: 'Enter Phone Number',
                text: 'Enter Valid Phone Number',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return false;
        }

        var data = {
            'proceedToPlaceBtn': true,
            'cphone': cphone,
            'payment_mode': payment_mode,
        };

        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: data,
            success: function (response) {
                var res = JSON.parse(response);
                if(res.status === 200) {
                    window.location.href = "order-summary.php";
                } else if(res.status === 404) {
                    Swal.fire({
                        title: res.message,
                        text: res.message,
                        icon: res.status_type,
                        buttons: {
                            catch: {
                                text: "Add Customer",
                                value: "catch"
                            },
                            cancel: "Cancel"
                        }
                    }).then((value) => {
                        switch(value) {
                            case "catch":
                                $('#addCustomerModal').modal('show');
                                break;
                            default:
                        }
                    });
                } else {
                    Swal.fire({
                        title: res.message,
                        text: res.message,
                        icon: res.status_type
                    });
                }
            }
        });
    });

    $(document).on('click', '.saveCustomer', function () {

        var c_name = $('#c_name').val();
        var c_phone = $('#c_phone').val();
        var c_email = $('#c_email').val();

        if(c_name != '' && c_phone != '')
        {
            if($.isNumeric(c_phone))
            {
                var data = {
                    'saveCustomerBtn': true,
                    'name': c_name,
                    'phone': c_phone,
                    'email': c_email,
                };

                $.ajax({
                    type: "POST",
                    url: "orders-code.php",
                    data: data,
                    success: function (response) {
                        var res = JSON.parse(response);

                        if(res.status == 200){
                            swal(res.message, res.message, res.status_type);
                            $('#addCustomerModal').modal('hide');
                        } else {
                            swal(res.message, res.message, res.status_type);
                        }
                    } 
                });

            } else 
            {
                swal("Enter Valid Phone Number","","warning");
            }
        } else 
        {
            swal("Please Fill Required fields","","warning");
        }
    });

    $(document).on('click', '#saveOrder', function () {
        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: {
                'saveOrder': true
            },
            success: function (response) {
                var res = JSON.parse(response);

                if(res.status == 200){
                    swal(res.message,res.message,res.status_type);
                    $('#orderPlaceSuccesMessage').text(res.message);
                    $('#orderSuccessModal').modal('show');
                } else {
                    swal(res.message,res.message,res.status_type);
                }
            }
        })
    });


});

function printMyBillingArea() {
    var divContents = document.getElementById("myBillingArea").innerHTML;
    var a = window.open('', '');
    a.document.write('<html><title>POS System in PHP</title>');
    a.document.write('<body style="font-family: fangsong;">');
    a.document.write(divContents);
    a.document.write('</body></html>');
    a.document.close();
    a.print();
}

window.jsPDF = window.jspdf.jsPDF;
var docPDF = new jsPDF();

function downloadPDF(invoiceNo) {
    var elementHTML = document.querySelector("#myBillingArea");
    docPDF.html( elementHTML, {
        callback: function() {
            docPDF.save(invoiceNo+'.pdf');
        },
        x: 15,
        y: 15,
        width: 170,
        windowWidth: 650
    })
}