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

if(isset($_POST['update'])) {
    $counter = 0;
    foreach($_POST['id'] as $product) {
        $count->update("kvitt",
            array(
                "price" => $_POST['price'][$counter],
                "payment_method" => $_POST['payment_method']),
            array("AND" => array(
                "id" => $product,
                "kvittId" => $_SESSION['cart']['receipt']
            )));
        $counter++;
    }
}

if(isset($_POST['clear-list'])) {

    unset($_SESSION['cart']);
    unset($_SESSION['total']);
    $error = 5;
    $message = 'New customer';
}

if(isset($_POST['send_receipt'])) {
    $email = $_POST['email'];

    $products = null;
    $total = 0;

    if(isset($_SESSION['cart']['receipt'])) {
        $products = $count->select("kvitt", "*", array("kvittId" => $_SESSION['cart']['receipt']));
    }

    $subject = "Kvittering for kjøp hos INDIVIDU.NO";
    $headers = "From: kundeservice@individu.no";

    $message = "Hei! \n Takk for din bestilling! Under følger kvittering for ditt kjøp hos INDIVIDU.NO \n\n";
    $message .= "Dato for kjøp: 10.09.2016 \n";

    $total = 0;
    foreach($products as $product) {
        $message .= "Sku: ".$product['sku']." - Pris: ".$product['price'].",-\n";
        $total = $total + $product['price'];
    }

    $message .= "\n\n MVA(25%): ".($total * 0.2).",- \n Totalt: ".$total.",-\n\n";

    $message .= "Lagersalg";

    mail($email,$subject, $message, $headers);

    $error = 5;
    $message = 'Mail sent!';
}

if(isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $deleted = $count->delete("kvitt", array("id" => $id));

    if($deleted) {
        $error = 5;
        $message = 'Slettet';
    } else {
        $error = 15;
        $message = 'Det skjedde en feil :(';
    }
}

require 'design/nav.php';
?>


    <div class="col-md-12">
        <form action="buy.php" method="post" class="form-inline">
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
                    <a class="btn btn-info btn-xs" role="button" target="_blank" href="print.php?r=<?php echo $_SESSION['cart']['receipt']; ?>">Print</a>
                    <button class="btn btn-success btn-xs" type="submit" name="update">Oppdater</button>
                </h3>
                <table class="table">
                    <?php

                    $products = null;
                    $total = 0;

                    if(isset($_SESSION['cart']['receipt'])) {
                        $products = $count->select("kvitt", "*", array("kvittId" => $_SESSION['cart']['receipt']));
                    }
                    ?>
                    <tr>
                        <td>
                            <label for="method">Bet. måte:</label>
                            <select name="payment_method" class="form-control input-sm" id="method">
                                <option value="VIPPS" <?php if($products[0]['payment_method'] == "VIPPS") echo "selected"; ?>>Vipps</option>
                                <option value="CASH" <?php if($products[0]['payment_method'] == "CASH") echo "selected"; ?>>Kontant</option>
                                <option value="INV" <?php if($products[0]['payment_method'] == "INV") echo "selected"; ?>>Faktura</option>
                            </select>
                        </td>
                        <td>
                            <label for="email">Epost: </label>
                            <input type="email" name="email" id="email" class="form-control input-sm">
                        </td>
                        <td class="text-center">
                            <label for="send">Kvittering: </label>
                            <button type="submit" name="send_receipt" id="send" class="btn btn-success btn-sm">Send</button>
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
                    <?php

                    foreach($products as $product) {
                        $total = $total + $product['price'];
                    ?>
                        <tr>
                            <td style="padding-top: 12px;"><a href="?delete=<?php echo $product['id']; ?>"><i class="fa fa-minus-circle" style="color: #d9534f"></i></a> <?php echo $product['sku']; ?></td>
                            <td>
                                <input type="hidden" name="id[]" value="<?php echo $product['id']; ?>">
                                <input type="number" name="price[]" value="<?php echo $product['price']; ?>" class="form-control input-sm pull-right" style="width:75px;">
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td align="right"></td>
                        <td><span class="pull-right">Mva: <span style="padding-left: 20px;"><?php echo ($total * 0.2); ?></span></span></td>
                    </tr>
                    <tr>
                        <td align="right"> </td>
                        <td style="font-weight: bold;"><span class="pull-right">Sum: <span style="padding-left: 20px"></span><?php echo $total; ?></span></span></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </form>
    </div>

<?php require 'design/footer.php'; ?>