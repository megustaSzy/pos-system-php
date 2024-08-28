<?php
session_start();

require 'dbcon.php';

// input field validation
function validate($inputData) {
    global $conn;
    $validatedData = mysqli_real_escape_string($conn, $inputData);
    return trim($validatedData);
}

// redirect from 1 page to another page with the massage (status)

function redirect($url, $status) {
    $_SESSION['status'] = $status;
    header('Location: '.$url);
    exit(0);
}


function alertMessage() {
    if(isset($_SESSION['status'])) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h6>'.$_SESSION['status'].'</h6>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        unset($_SESSION['status']);
    }
}

// insert record using this function
function insert($tableName, $data){
    global $conn;

    $table = validate($tableName);

    // Separate keys and values from data array
    $columns = array_keys($data);
    $values = array_values($data);

    // Prepare placeholders for prepared statement
    $finalColumn = implode(',',$columns);
    $finalValues = "'".implode("', '", $values)."'";

    // Prepare the query
    $query = "INSERT INTO $table ($finalColumn) VALUES ($finalValues)";
    $result = mysqli_query($conn,$query);
    return $result;
}



// update data using this function

function update($tableName, $id, $data) {
    global $conn;

    // Menghindari SQL injection
    $table = mysqli_real_escape_string($conn, $tableName);
    $id = mysqli_real_escape_string($conn, $id);

    $updateData = array();
    foreach ($data as $column => $value) {
        // Menghindari SQL injection
        $column = mysqli_real_escape_string($conn, $column);
        $value = mysqli_real_escape_string($conn, $value);
        $updateData[] = "$column='$value'";
    }

    $updateDataString = implode(', ', $updateData);

    $query = "UPDATE $table SET $updateDataString WHERE id='$id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        // Tangani kesalahan query
        echo "Error: " . mysqli_error($conn);
        return false;
    }

    return true;
}


function getAll($tableName, $status = NULL) {
    global $conn;

    $table = validate($tableName);
    $status = validate($status);

    if($status == 'status') {
        $query = "SELECT * FROM $table WHERE status='0'";
    } else {
        $query = "SELECT * FROM $table";
    }
    return mysqli_query($conn, $query);
}

function getById($tableName, $id) {

    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $query = "SELECT * FROM $table WHERE id='$id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if($result) {
        
        if(mysqli_num_rows($result) == 1) {

            $row = mysqli_fetch_assoc($result);
            $response = [
                'status' => 200,
                'data' => $row,
                'message' => 'Record Found'
            ];
            return $response;


        } else {
            $response = [
                'status' => 404,
                'message' => 'No Data Found'
            ];
            return $response;
        }

    } else{
        $response = [
            'status' => 500,
            'message' => 'Something went wrong'
        ];
        return $response;
    }
}

// delete from database

function delete($tableName, $id) {
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $query = "DELETE FROM $table WHERE id='$id' LIMIT 1";
    $result = mysqli_query($conn, $query);
    return $result;
}

function checkParamId($type) {
    if(isset($_GET[$type])) {
        if($_GET[$type] != '') {

            return $_GET[$type];
        } else {
            return '<h5>No id Found</h5>';
        }

    } else {
        return '<h5>No id Given</h5>';
    }
}

function logoutSession(){
    unset($_SESSION['loggedIn']);
    unset($_SESSION['loggedinUser']);
}

function jsonResponse($status, $status_type, $message) {

    $response = [
        'status' => $status,
        'status_type' => $status_type,
        'message' => $message
    ];
    echo json_encode($response);
    return;
}

function getCount($tableName) {
    global $conn;

    $table = validate($tableName);

    $query = "SELECT * FROM $table";
    $query_run = mysqli_query($conn, $query);
    if($query_run) {

        $totalCount = mysqli_num_rows($query_run);
        return $totalCount;

    } else {
        return 'Something went wrong';
    }
}


?>