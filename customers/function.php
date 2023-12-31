<?php 
// error_reporting(0);
require("../inc/dbcon.php");


// function validation
function error422($message){
    $data = [
        'status' => 422,
        'message' => $message,
    ];
    header("HTTP/1.0 Method Not Allowed");
    echo json_encode($data);
    exit();
}

// function store customer
function storeCustomer($customerInput){
    global $conn;
    $name = mysqli_real_escape_string($conn, $customerInput['name']);
    $email = mysqli_real_escape_string($conn, $customerInput['email']);
    $phone = mysqli_real_escape_string($conn, $customerInput['phone']);

    if(empty(trim($name))){

        return error422('Enter your name');

    }elseif(empty(trim($email))){

        return error422('Enter your email');

    }elseif(empty(trim($phone))){

        return error422('Enter your phone');

    }else{
        $query = "INSERT INTO customers (name, email, phone)
                  VALUES ('$name', '$email', '$phone')";

        $result = mysqli_query($conn, $query);

        if($result){
            $data = [
                'status' => 201,
                'message' => 'Customer Created Successfully'
            ]; 
            header("HTTP/1.0 201 Created");
            echo json_encode($data);
        }else{
            $data = [
                'status' => 405,
                'message' => "Internal Server Error",
                
            ];
            header("HTTP/1.0 500 Internal Server Error");
            echo json_encode($data);
        }
    }
}


// function get all customers
function getCustomerList(){
    global $conn;

    $query = "SELECT * FROM customers";
    $query_run = mysqli_query($conn, $query);

    if($query_run){

        if(mysqli_num_rows($query_run) > 0){

            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);

            $data = [
                'status' => 200,
                'message' => "All Customers",
                'data' => $res
            ];

            header("HTTP/1.0 200 OK");
            return json_encode($data);

        }else{
            
            $data = [
                'status' => 404,
                'message' => "No Customer Found"
            ];

            header("HTTP/1.0 500 No Customer Found");
            return json_encode($data);
        }

    }else{
        $data = [
            'status' => 500,
            'message' => "Internal Server Error"
        ];
        header("HTTP/1.0 500 Internal Server Error");
        return  json_encode($data);
    }

}

//  function update customer
function updateCustomer($customerInput, $customerParams){

    global $conn;

    if(!isset($customerParams['id'])){
        return error422('Customer id not found in URL');
    }elseif($customerParams['id'] == null){
        return error422('Enter customer id');
    }

    $customerId = mysqli_real_escape_string($conn, $customerParams['id']);

    $name = mysqli_real_escape_string($conn, $customerInput['name']);
    $email = mysqli_real_escape_string($conn, $customerInput['email']);
    $phone = mysqli_real_escape_string($conn, $customerInput['phone']);

    if(empty(trim($name))){
        return error422('Enter your name');
    }elseif(empty(trim($email))){
        return error422('Enter your email');
    }elseif(empty(trim($phone))){
        return error422('Enter your phone');
    }
    else{
        $query = "UPDATE customers SET name='$name', email='$email', phone='$phone' 
                WHERE id='$customerId' LIMIT 1";

        $result = mysqli_query($conn, $query);

        if($result){
            $data = [
                'status' => 200,
                'message' => 'Customer Update Successfully'
            ]; 
            header("HTTP/1.0 200 Success");
            echo json_encode($data);
        }else{
            $data = [
                'status' => 405,
                'message' => "Internal Server Error",
                
            ];
            header("HTTP/1.0 500 Internal Server Error");
            echo json_encode($data);
        }
    }

 

}

   // function delete customer
    function deleteCustomer($customerParams){

        global $conn;

        if(!isset($customerParams['id'])){
            return error422('Customer id not found in URL');
        }elseif($customerParams['id'] == null){
            return error422('Enter customer id');
        }

        $customerId = mysqli_real_escape_string($conn, $customerParams['id']);
        if( existId($customerId)){

            $query = "DELETE FROM customers WHERE id='$customerId' LIMIT 1";
            $result = mysqli_query($conn, $query);
    
            if($result){
                $data = [
                    'status' => 200,
                    'message' => "Delete success",
                    'data' => "Display"
                    
                ];
                header("HTTP/1.0 200 OK");
                return  json_encode($data);
        }
      

        }else{
            $data = [
                'status' => 404,
                'message' => "Not Found",
                
            ];
            header("HTTP/1.0 404 Not Found");
            echo json_encode($data);
        }

    }

    // function to check id exists
    function existId($id){
        global $conn;
        $query = "SELECT id FROM customers WHERE id=$id LIMIT 1";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) == 1){
            return true;
        }else{
            return false;
        }
      

    }


?>