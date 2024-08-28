<?php

require '../config/function.php';

$paraRestultId = checkParamId('id');
if(is_numeric($paraRestultId)) {

    $categoryId = validate($paraRestultId);

    $category = getById('categories', $categoryId);
    if($category['status'] == 200) {
        $response = delete('categories',$categoryId);
        if($response) {
            redirect('kategori.php','Kategori Deleted Succesfully');
        } else {
            redirect('kategori.php','Something Went Wrong');
        }

    } else {
        redirect('kategori.php',$category['message']);
    }
    //echo $adminId;

} else {
    redirect('kategori.php','Something Went Wrong');
}

?>