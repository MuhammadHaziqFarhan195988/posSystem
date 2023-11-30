<?php
session_start();

require 'dbconnection.php';

//input field validation
function validate($credentialData)
{

    global $connection;
    $validatedData = mysqli_real_escape_string($connection, $credentialData);

    return trim($validatedData);
}


//redirect from one page to another page with message which returns status
function redirect($url, $status)
{


    $_SESSION['status'] = $status;
    header('Location: ' . $url);
    exit(0);
}


// Display messages status after any process

function alertDialog()
{
    if (isset($_SESSION['status'])) {

        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <h6>' . $_SESSION['status'] . '</h6>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
        unset($_SESSION['status']);
    }
}


// one of the four CRUD element which is create is being defined in a function below

function insert($tableName, $data)
{
    global $connection;

    $table = validate($tableName);

    $columns = array_keys($data);
    $values = array_values($data);

    $finalColumn = implode(',', $columns);
    $finalValues = "'" . implode("', '", $values) . "'";

    $query = "INSERT INTO $table ($finalColumn) VALUES ($finalValues)";
    $result = mysqli_query($connection, $query);
    return $result;
}

//update query using this function

function updateDB($tableName, $id, $data)
{
    global $connection;

    $table = validate($tableName);
    $id = validate($id);

    $updateDataString = "";

    foreach ($data as $column => $value) {
        # code...
        $updateDataString .= $column . '=' . "'$value',";
    }

    $finalUpdateData = substr(trim($updateDataString), 0, -1);

    $query = "UPDATE $table SET $finalUpdateData WHERE id = '$id'";
    $result = mysqli_query($connection, $query);
    return $result;
}

function getAll($tableName, $status = NULL)
{
    global $connection;

    $table = validate($tableName);
    $status = validate($status);

    if ($status == 'status') {
        $query = "SELECT * FROM $table WHERE status='0'"; //0 means show the data 1 means hide the data
    } else {
        $query = "SELECT * FROM $table";
    }
    return mysqli_query($connection, $query);
}

function getById($tableName, $id)
{

    global $connection;

    $table = validate($tableName);
    $id = validate($id);

    $query = "SELECT * FROM $table WHERE id ='$id' LIMIT 1";
    $result = mysqli_query($connection, $query);

    if ($result) {

        if (mysqli_num_rows($result) == 1) {

            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $response = [
                'status' => 200,
                'data' => $row,
                'message' => 'Data found'
            ];
            return $response;
        } else {
            $response = [
                'status' => 404,
                'message' => 'Data not found'
            ];
        }
    } else {
        $response = [
            'status' => 500,
            'message' => 'Something went wrong'
        ];
        return $response;
    }
}
//delete data from database

function deletebyId($tableName, $id)
{
    global $connection;
    $table = validate($tableName);
    $id = validate($id);

    $query = "DELETE FROM $table WHERE id ='$id' LIMIT 1";
    $result = mysqli_query($connection, $query);
    return $result;
}



function checkParamId($type){

    if(isset($_GET[$type])){

        if($_GET[$type] != ''){

            return $_GET[$type];
        }else {
            return '<h5>No Id Found</h5>';
        }
    }else {

        return '<h5>No Id Given</h5>';
    }

}

function logoutSession() {

    unset($_SESSION['loggedIn']);
    unset($_SESSION['loggedInUser']);
}
