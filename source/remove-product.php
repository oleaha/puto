<?php
require 'design/top.php';

if(isset($_POST['remove'])) {
    $ean = trim($_POST['ean']);

    if(!empty($ean) && is_numeric($ean)) {

        $check = $individu->select("product_option_value", 'product_id', array('ean' => $ean));


        // Decrement product option value qty
        if(count($check) == 1) {
            $option_value = $individu->update("product_option_value", array("quantity[-]" => 1), array('ean' => $ean));
        }

        if(count($check) == 0) {
            $check = $individu->select("product", "product_id", array("ean" => $ean));
        }

        // Decrement product total qty
        $product = $individu->update("product", array("quantity[-]" => 1), array("product_id" => $check[0]));

        if($product) {
            $error = 5;
            $message = "Product quantity updated!";
            addEvent($count, $_SESSION['username'], 'remove-success', 'Product quantity updated for product '.$check[0]['product_id'], $ean);
        } else {
            $error = 15;
            $message = "EAN not found";
            addEvent($count, $_SESSION['username'], 'count-unregistered', 'User tried to remove unregistered EAN.', $ean);
        }
    }
}
require 'design/nav.php';
?>
    <div class="ean-count col-sm-10 col-sm-offset-1 text-center">
        <?php require 'design/errorhandler.php'; ?>

        <form action="" method="post" class="form-inline">
            <div class="form-group">
                <input type="text" class="ean-field form-control input-lg" id="ean" name="ean" placeholder="EAN" autocomplete="off" autofocus="on">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success btn-lg btn-ean" name="remove"><i class="fa fa-barcode fa-3x"></i></button>
            </div>
        </form>
    </div>
<?php require 'design/footer.php'; ?>