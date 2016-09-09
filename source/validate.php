<?php
require 'design/top.php';

if(isset($_POST['change_products'])) {

	if(!empty($_POST['adjust'])) {
		$error = 5;
		$message = "The following products where updated: ";

		foreach ($_POST['adjust'] as $product) {
			list($ean, $qty, $sku) = explode('::', $product);

			// Check if EAN is in product_option_value, if not, update product
			$sanity = $individu->count('product_option_value', array('ean' => $ean));

			if($sanity > 0) {
				$update_database = $individu->update('product_option_value', array("quantity" => $qty), array("AND" => array('ean' => $ean, 'subtract' => '1')));
				if($update_database) {
					$update_product_status = $individu->update('product', array('status' => '1'), array('sku' => $sku));
					$message .= $sku.", ";
				}
			} else {
				$sanity = $individu->count('product', array('ean' => $ean));

				if($sanity > 0) {
					$update_database = $individu->update('product', array('quantity' => $qty), array('AND' => array('ean' => $ean, 'subtract' => '1')));
					if($update_database) {
						$message .= $sku.", ";
					}
				}
			}
			
		}
	} else {
		$error = 15;
		$message = "Could not perform update";
	}
}

if(isset($_POST['validate'])) {

    $from_date  = $_POST['start'];
    $to_date    = $_POST['end'];

    # Array containing product that have a difference
    $diff_products      = array();
    $counted_products   = $count->query("SELECT COUNT(id) as number, ean FROM count WHERE date BETWEEN '".$from_date."' AND '".$to_date."' GROUP BY ean");

    foreach ($counted_products as $counted) {
        $oc_product = $individu->select(
        	// Table
        	'product_option_value', 
        	// Join
        	array(
        		'[><]product' => array('product_id' => 'product_id'),
        		'[><]option_value_description' => array('option_value_id' => 'option_value_id'),
        	),
        	// Columns
        	array(
        		'product_option_value.quantity', 
        		'product.sku', 
        		'product.status',
        		'option_value_description.name',
        	),
        	// Where
        	array(
        		'product_option_value.ean' => $counted['ean']
        		)
        );

        if(count($oc_product) == 0) {
            $oc_product = $individu->select('product', array('product_id', 'sku', 'quantity', 'status'), array('ean' => $counted['ean']));
        }

        if($counted['number'] != $oc_product[0]['quantity']) {
            array_push($diff_products, array(
                    'sku' => $oc_product[0]['sku'],
                    'counted' => $counted['number'],
                    'registered' => $oc_product[0]['quantity'],
                    'ean' => $counted['ean'],
                    'status' => $oc_product[0]['status'],
                    'size' => $oc_product[0]['name'],
                )
            );
        }
    }
}

require 'design/nav.php';
?>

    <div class="col-md-8 col-md-offset-2 text-center">
    <?php require 'design/errorhandler.php'; ?>
        <h1>Validate count</h1>
        <p>Select start and end date to validate.</p>
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
    <div class="col-md-10 col-md-offset-1" style="background-color: rgba(255, 255, 255, 0.7); margin-top: 3em; color: #000000">
        <form action="" method="post">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Status:</th>
                    <th>SKU:</th>
                    <th>Size:</th>
                    <th>Countet qty:</th>
                    <th>Registered qty:</th>
                    <th>Diff:</th>
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
                        <td><?php echo $product['size']; ?></td>
                        <td><?php echo $product['counted']; ?></td>
                        <td><?php echo $product['registered']; ?></td>
                        <td>
                        	<?php 
                        	$diff = intval($product['counted']) - intval($product['registered']);

                        	if ($diff > 0) {
                        		echo '<span class="label label-success">'.$diff.'</span>';
                        	} else {
                        		echo '<span class="label label-danger">'.$diff.'</span>';
                        	}

                        	?>
                        </td>
                        <td>
                        <input type="checkbox" class="checkbox" name="adjust[]" value="<?php echo $product['ean']; ?>::<?php echo $product['counted']; ?>::<?php echo $product['sku']; ?>"></td>
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