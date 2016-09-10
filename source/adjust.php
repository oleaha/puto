<?php
require 'design/top.php';



if(isset($_GET['adjust']) == true) {


    // Get all EANS
    $remove = $count->select("kvitt", array('product_id', 'ean'), array("ean[!]" => "0"));

    $single = true;

    foreach($remove as $product) {

        if($single == true) {

            $ean = $product['ean'];

            $check = $individu->select("product_option_value", 'product_id', array('ean' => $ean));

            // Decrement product option value qty
            if (count($check) == 1) {
                $option_value = $individu->update("product_option_value", array("quantity[-]" => 1), array('ean' => $ean));
            }

            if (count($check) == 0) {
                $check = $individu->select("product", "product_id", array("ean" => $ean));
            }

            // Decrement product total qty
            $product = $individu->update("product", array("quantity[-]" => 1), array("product_id" => $check[0]));

            if ($product) {
                addEvent($count, $_SESSION['username'], 'remove-success', 'Product quantity updated for product ' . $check[0]['product_id'], $ean);
            } else {
                addEvent($count, $_SESSION['username'], 'count-unregistered', 'User tried to remove unregistered EAN.', $ean);
            }
        }
    }
}

/*
if(isset($_POST['buy'])) {

    $ean = trim($_POST['ean']);

    if(!empty($ean)) {
        $error = 5;
        $message = 'Product registered';

        // Get product id
        $product_prod = $individu->select("product_option_value",array("[>]product" => array("product_id" => "product_id")), array('product.product_id', 'product.sku'), array('product_option_value.ean' => $ean));

        if(count($product_prod) == 0) {
            $product_prod = $individu->select("product", "product_id", array("ean" => $ean));
        }

        $product_id = $product_prod[0]['product_id'];

        if($product_id != null) {

            // Get details
            $product = $count->select("warehouseraid", '*', array('product_id' => $product_id));

            //Check if new receipt is created
            if($_SESSION['cart']['receipt'] == 0) {
                $last_id = $count->select("kvitt", "kvittId", array("ORDER" => "kvittId DESC", "LIMIT" => "1"));
                $_SESSION['cart']['receipt'] = ($last_id[0] + 1);
            }

            if($product) {

                // Add line to receipt
                $insert = $count->insert("kvitt", array(
                    "kvittId" => $_SESSION['cart']['receipt'],
                    "product_id" => $product_id,
                    "sku" => $product[0]['sku'],
                    "ean" => $ean,
                    "price" => $product[0]['price'],
                    "payment_method" => "VIPPS",
                ));
            } else {
                // Show product without price
                $sku = $product_prod[0]['sku'];

                // Add line to receipt
                $insert = $count->insert("kvitt", array(
                    "kvittId" => $_SESSION['cart']['receipt'],
                    "product_id" => $product_id,
                    "sku" => $sku,
                    "ean" => $ean,
                    "price" => "0",
                    "payment_method" => "VIPPS",
                ));

            }
        } else {
            $error = 15;
            $message = 'Could not find product';
        }
    } else {
        $error = 15;
        $message = "Illegal EAN";
    }
}

*/

require 'design/nav.php';
?>


<?php require 'design/footer.php'; ?>