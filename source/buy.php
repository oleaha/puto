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

        if($product_id != 0 || !$product_id != null) {

            // Get details
            $product = $count->select("warehouseraid", '*', array('product_id' => $product_id));

            //Check if new receipt is created
            if($_SESSION['cart']['receipt'] == 0) {
                $last_id = $count->select("kvitt", "kvittId", array("ORDER" => "kvittId DESC", "LIMIT" => "1"));
                $_SESSION['cart']['receipt'] = ($last_id[0] + 1);
            }

            // Add line to receipt
            $insert = $count->insert("kvitt", array(
                "kvittId" => $_SESSION['cart']['receipt'],
                "product_id" => $product_id,
                "sku" => $product[0]['sku'],
                "ean" => $ean,
                "price" => $product[0]['price'],
                "payment_method" => "VIPPS",
            ));

            echo $count->last_query();


            if(isset($_SESSION['cart'])) {
                $_SESSION['cart']['products'][] = $product[0]['gender']."-".$product[0]['sku'].",".$product[0]['price'];
                $_SESSION['cart']['total'] = $_SESSION['cart']['total'] + $product[0]['price'];
            } else {
                $_SESSION['cart']['products'] = array();
                $_SESSION['cart']['total'] = 0;
                $_SESSION['cart']['receipt'] = 0;

                $_SESSION['cart']['products'][] = $product[0]['gender']."-".$product[0]['sku'].",".$product[0]['price'];
                $_SESSION['cart']['total'] = $_SESSION['cart']['total'] + $product[0]['price'];
            }
        } else {
            $error = 15;
            $message = 'Could not find product';

        }
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
            <h3>Receipt #<?php echo $_SESSION['cart']['receipt']; ?>
                <button type="submit" name="clear-list" class="btn btn-danger btn-xs">New customer</button>
                <button class="btn btn-info btn-xs">Print</button>
            </h3>
            <table class="table">
                <tr>
                    <td>
                        <label for="method">Betalingsmåte:</label>
                        <select name="payment_method" class="form-control input-sm" id="method">
                            <option value="VIPPS">Vipps</option>
                            <option value="CASH">Kontakt</option>
                            <option value="Invoice">Faktura</option>
                        </select>
                    </td>
                </tr>
            </table>

            <table class="table">
                <thead>
                <tr>
                    <th>Product</th>
                    <th><span class="pull-right">Price</span></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($_SESSION['cart']['products'] as $item) {
                    $p = explode(",", $item);
                    ?>
                    <tr>
                        <td><?php echo $p[0]; ?></td>
                        <td><input type="number" name="price" value="<?php echo $p[1]; ?>" class="form-control input-sm pull-right" style="width:75px;"></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <td align="right"></td>
                    <td><span class="pull-right">Mva: <span style="padding-left: 20px;"><?php echo ($_SESSION['cart']['total'] * 0.2); ?></span></span></td>
                </tr>
                <tr>
                    <td align="right"> </td>
                    <td style="font-weight: bold;"><span class="pull-right">Sum: <span style="padding-left: 20px"></span><?php echo $_SESSION['cart']['total']; ?></span></span></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </form>
</div>


<?php require 'design/footer.php'; ?>


