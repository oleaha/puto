<?php
require 'design/top.php';

if(isset($_POST['buy'])) {

    $ean = trim($_POST['ean']);

    if(!empty($ean) && is_numeric($ean)) {
        $error = 5;
        $message = 'Product registered';

        // Get product id

        $product_prod = $individu->select("product_option_value",array("[>]product" => array("product_id" => "product_id")), array('product.product_id', 'product.sku'), array('product_option_value.ean' => $ean));

        if(count($product_prod) == 0) {
            $product_prod = $individu->select("product", "product_id", array("ean" => $ean));
        }

        $product_id = $product_prod[0]['product_id'];
        $product_sku = $product_prod[0]['sku'];

        // Get details

        $product = $count->select("warehouseraid", '*', array('product_id' => $product_id));

        var_dump($product);

        /*
        //var_dump($product_sku);

        // Find price!

        $price = $individu->select("product_special", "price", array("AND" => array("product_id" => $product_id, "date_end" => '2016-12-31')));

        //echo $individu->last_query();

        //var_dump($price);



        if(isset($_SESSION['cart'])) {
            $_SESSION['cart'][] = $product_sku.",".($price[0]*1.25);
            $_SESSION['total'] = ($_SESSION['total'] + ($price[0]*1.25));
        } else {
            $_SESSION['cart'] = array();
            $_SESSION['total'] = 0;
            $_SESSION['cart'][] = $product_sku.",".($price[0]*1.25);
            $_SESSION['total'] = ($_SESSION['total'] + ($price*1.25));
        }

        */
    }
}

if(isset($_POST['clear-list'])) {

    unset($_SESSION['cart']);
    unset($_SESSION['total']);
    $error = 5;
    $message = 'New customer';
}


require 'design/nav.php';
?>


<div class="col-md-12">
    <form action="" method="post" class="form-inline">
        <div class="col-md-8 text-center">
            <div class="ean-count text-center">

                <?php require 'design/errorhandler.php'; ?>
                <div class="form-group">
                    <input type="text" class="ean-field form-control input-lg" id="ean" name="ean" placeholder="EAN" autocomplete="off" autofocus="on">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-lg btn-ean" name="buy"><i class="fa fa-barcode fa-3x"></i></button>
                </div>

            </div>
        </div>
        <div class="col-md-4 receipt">
            <h3>Receipt <button type="submit" name="clear-list" class="btn btn-danger btn-xs">New customer</button></h3>
            <table class="table">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($_SESSION['cart'] as $item) {
                    $p = explode(",", $item);
                    ?>
                    <tr>
                        <td><?php echo $p[0]; ?></td>
                        <td><?php echo $p[1]; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <td align="right">Sum:</td>
                    <td><?php echo $_SESSION['total']; ?></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </form>
</div>


<?php require 'design/footer.php'; ?>


