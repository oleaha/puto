<?php
require 'design/top.php';

#TODO: Handle request
$error = 0;
if(isset($_POST['search'])) {

	if(!empty($_POST['ean'])) {

		/*
		1: Find all EANS in count log
		2: Prepare results
		*/


		$result = array();
		$counted_products = $count->query("SELECT COUNT(id) as number, ean FROM count WHERE ean = '".$_POST['ean']."' ");

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

	        echo $individu->last_query();

	        if(count($oc_product) == 0) {
	            $oc_product = $individu->select('product', array('product_id', 'sku', 'quantity'), array('ean' => $counted['ean']));
	        }

	        if($counted['number'] != $oc_product[0]['quantity']) {
	            array_push($result, array(
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
	} elseif(!empty($_POST['sku'])) {
		echo "Yolo";
		/*
		1: Find all EANS with sku
		2: Find all EANS in count log
		3: Prepare result
		*/

	} else {
		$error = 15;
		$message = "No input";
	}
}

require 'design/nav.php';
?>

<div class="col-md-4 col-md-offset-4">
    <?php require 'design/errorhandler.php'; ?>
    <form action="" method="post" class="">

    	<label for="basic-url">Search by EAN:</label>
		<div class="input-group">
   	    	<span class="input-group-addon" id="basic-addon3">EAN:</span>
			<input type="text" class="form-control" id="basic-url" name="ean" aria-describedby="basic-addon3">
		</div>

		<label for="basic-url">or by SKU:</label>
		<div class="input-group">
			<span class="input-group-addon" id="basic-addon3">SKU:</span>
			<input type="text" class="form-control" id="basic-url" name="sku" aria-describedby="basic-addon3">
		</div>

		<div class="input-group" style="padding-top: 5px;">
            <input type="submit" class="btn-success btn btn-lg" name="search">
        </div>
	</form>
</div>

<?php if(isset($_POST['search']) && $error != 15) { ?>

 <div class="col-md-10 col-md-offset-1" style="background-color: rgba(255, 255, 255, 0.7); margin-top: 3em; color: #000000">
        <form action="" method="post">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>SKU:</th>
                    <th>EAN:</th>
                    <th>Size:</th>
                    <th>Countet qty:</th>
                    <th>Registered qty:</th>
                    <th>Action:</th>
                </tr>
                </thead>
                <tbody>
                	<?php foreach($result as $product) { ?>
                    <tr>
                        <td><a href="http://individu.no/<?php echo $product['sku']; ?>"><?php echo $product['sku']; ?></a></td>
                        <td><?php echo $product['ean']; ?></td>
                        <td><?php echo $product['size']; ?></td>
                        <td><?php echo $product['counted']; ?></td>
                        <td><?php echo $product['registered']; ?></td>
                        <td></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>
    </div>
<?php } ?>