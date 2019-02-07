<?php

if(@$_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
    header('Location: /404.html', true, 301);
    die();
}

session_start();

// Set session variables

$_SESSION["test"] = "no";
class Product{
     public $productId;
     public $quantity;

     function __construct($productId,$quantity){
         $this->productId = $productId;
         $this->quantity = $quantity;
     }
}

$dependency_obj = (object)[
    "family-tree-maker-2019-download"=>["family-tree-maker-family-3-pack","minimum-subscription-plan"],
    "family-tree-maker-2019-dvd"=>["family-tree-maker-family-3-pack","minimum-subscription-plan"],
    "family-tree-maker-2019-usb"=>["family-tree-maker-family-3-pack","minimum-subscription-plan"],
];

//POST METHOD
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// SET SESSION FOR RESULT PRODUCT OBJECT
        $_SESSION["result_obj"] = new stdClass();

        // get data from Store and set it to session

        $arrayOfProducts = $_REQUEST['arrayOfProducts'];
        $_SESSION['coupon'] = $_REQUEST['coupon'];
        $_SESSION['country'] = $_REQUEST['country'];
        foreach ($arrayOfProducts as $productId){
            $_SESSION["result_obj"]->$productId = new Product($productId,1);
        }
        echo 1;
    }
// GET METHOD
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $url_data = $_SERVER["QUERY_STRING"];
        parse_str($url_data, $output);

        if(isset($output['action'])){
            $action = $output['action'];
        }
        if(isset($output['productId'])){
            $id = $output['productId'];
        }
        if(isset($output['quantity'] )){
            $quantity = $output['quantity'];
        }


// remove product
        if (isset($action) && $action == "remove" && isset($id)) {

// check and delete dependency product
            if(isset($dependency_obj -> $id)){
                $supplementary_products_array = $dependency_obj -> $id;

                foreach ($supplementary_products_array as $supplementary_product){
                    if(isset($supplementary_product) && isset($_SESSION["result_obj"]->$supplementary_product) ){
                        unset($_SESSION["result_obj"] -> $supplementary_product);
                    }
                }
            }
            unset($_SESSION["result_obj"] -> $id);
            echo json_encode($_SESSION["result_obj"]);
        }

// change quantity
        if(isset($quantity) && isset($id)) {
            $_SESSION["result_obj"]-> $id -> quantity = $quantity;
            echo json_encode($_SESSION["result_obj"]);
        }


    }

//echo 1;



