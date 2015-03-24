<?php
require 'design/top.php';

if(isset($_POST['validate'])) {

    $from_date = $_POST['start'];
    $to_date = $_POST['end'];

    $diff_products = array();

    $counted_products = $count->query("SELECT COUNT(id) as number, ean FROM count WHERE date BETWEEN '".$from_date."' AND '".$to_date."' GROUP BY ean");

    foreach ($counted_products as $counted) {
        $oc_product = $individu->select('product_option_value', array('[><]product' => array('product_id' => 'product_id')) , array('product_option_value.quantity', 'product.sku', 'product.status'), array('product_option_value.ean' => $counted['ean']));

        if($counted['number'] != $oc_product[0]['quantity']) {
            array_push($diff_products, array(
                    'sku' => $oc_product[0]['sku'],
                    'counted' => $counted['number'],
                    'registered' => $oc_product[0]['quantity'],
                    'ean' => $counted['ean'],
                    'status' => $oc_product[0]['status']
                )
            );
        }
    }
}

require 'design/nav.php';
?>

    <div class="col-md-6 col-md-offset-3 text-center">
        <form action="" method="post" class="form-inline">
            <div class="input-daterange input-group" id="datepicker">
                <input type="text" class="input-lg form-control" name="start"/>
                <span class="input-group-addon">to</span>
                <input type="text" class="input-lg form-control" name="end" />
            </div>
            <div class="form-group">
                <button type="submit" class="btn-success btn btn-lg" name="validate">Validate!</button>
            </div>
        </form>
    </div>

<?php
if(isset($_POST['validate'])) {
?>
    <div class="col-md-8 col-md-offset-2" style="background-color: rgba(255, 255, 255, 0.7); margin-top: 3em; color: #000000">
        <form action="" method="post">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Status:</th>
                    <th>SKU:</th>
                    <th>Countet qty:</th>
                    <th>Registered qty:</th>
                    <th><button class="btn btn-warning btn-block" type="submit" name="change_products">Adjust quantity</button></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($diff_products as $product) { ?>
                    <tr>
                        <?php if($product['status'] != 1) {
                        ?>
                        <td><button class="btn btn-danger btn-xs">Not active</button></td>
                        <?php
                        } else {
                        ?>
                        <td><button class="btn btn-success btn-xs">Active</button></td>
                        <?php
                        } ?>
                        <td><a href="http://individu.no/<?php echo $product['sku']; ?>"><?php echo $product['sku']; ?></a></td>
                        <td><?php echo $product['counted']; ?></td>
                        <td><?php echo $product['registered']; ?></td>
                        <td><input type="checkbox" class="checkbox" name="validate-<?php echo $product['ean']; ?>"></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>
    </div>
<?php
}
?>

    <script type="text/javascript">
        $('.input-daterange').datepicker({
            format: "yyyy-mm-dd",
            weekStart: 1,
            clearBtn: true,
            todayHighlight: true,
            todayBtn: "linked"
        });
    </script>

<?php
require 'design/footer.php';
?>