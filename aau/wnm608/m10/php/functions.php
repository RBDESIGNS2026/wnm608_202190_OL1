<?php

function makeConn() {
    $conn = new mysqli("localhost", "u957237009_rbrownadmin", "Naiomi831!", "u957237009_nkactusadmin");

    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function makeQuery($conn, $query) {
    $result = $conn->query($query);

    if(!$result){
        die("Query failed: " . $conn->error);
    }

    $data = [];

    while($row = $result->fetch_object()){
        $data[] = $row;
    }

    return $data;
}
?>