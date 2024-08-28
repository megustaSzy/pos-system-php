<?php

require '../config/function.php';

$paraRestultId = checkParamId('id');
if(is_numeric($paraRestultId)) {

    $customerId = validate($paraRestultId);

    $customer = getById('customers', $customerId);

    if($customer['status'] == 200) {

        $response = delete('customers',$customerId);
        if($response) {
            redirect('customers.php','Customer Deleted Succesfully');
        } else {
            redirect('customers.php','Something Went Wrong');
        }

    } else {
        redirect('customers.php',$category['message']);
    }
    //echo $adminId;

} else {
    redirect('customers.php','Something Went Wrong');
}

?>