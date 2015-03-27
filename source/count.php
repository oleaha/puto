<?php
require 'design/top.php';

if(isset($_GET['delete'])) {

    $ean = $count->select('count', array('ean'), array('id' => $_GET['delete']));
    $delete = $count->delete('count', array('id' => $_GET['delete']));

    if($delete) {
        $error = 5;
        $message = 'EAN deleted!';
        addEvent($count, $_SESSION['username'], 'delete-success', 'User deleted counted EAN', $ean[0]['ean']);
    } else {
        $error = 15;
        $message = "#4: Could not delete EAN.";
        addEvent($count, $_SESSION['username'], 'delete-error', 'User tried to delete EAN, but something went wrong', $ean[0]['ean']);
    }
}

if(isset($_POST['count'])) {
    $ean = trim($_POST['ean']);

    // Validate EAN
    if(!empty($ean) && is_numeric($ean)) {
        // Se if product is in OC
        $result = $individu->select("product_option_value", 'product_id', array('ean' => $ean));

        // Could not find EAN in product_option_value table, check product table
        if (count($result) == 0) {
            $result = $individu->select('product', 'product_id', array('ean' => $ean));
        }
        if(count($result) == 0) {
            $error = 15;
            $message = "#1: Product is not registerd in <a href='http://individu.no/gossip' target='_blank'>OpenCart!</a>";
            addEvent($count, $_SESSION['username'], 'count-unregistered', 'User tried to count unregistered EAN.', $ean);
        }
        // Add count
        else if(count($result) == 1) {
            $addCount = $count->insert("count", array('date' => date("Y-m-d H:i:s"), 'username' => $_SESSION['username'], 'ean' => $ean));
            if($addCount) {
                $error = 5;
                $message = "EAN Counted";
                addEvent($count, $_SESSION['username'], 'count-success', 'User counted EAN', $ean);
            } else {
                $error = 15;
                $message = "Something horrible hapend. Contact OAH";
                addEvent($count, $_SESSION['username'], 'count-db-error', 'Something went wrong with SQL insert. Query: '.$count->last_query(), $ean);
            }
        }
        // More than one EAN is registered
        else {
            $error = 15;
            $message = "#2: Multiple EANs found. Please check <a href='http://individu.no/gossip' target='_blank'>OpenCart Admin!</a>";
            addEvent($count, $_SESSION['username'], 'count-multiple', 'Multiple EANs found, check OC admin!', $ean);
        }
    }
    // Illegal EAN
    else {
        $error = 15;
        $message = "#3: Illegal EAN input!";
        addEvent($count, $_SESSION['username'], 'count-illegal', 'Illegal EAN entered', $ean);
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
                <button type="submit" class="btn btn-success btn-lg btn-ean" name="count"><i class="fa fa-barcode fa-3x"></i></button>
            </div>
        </form>
    </div>
    <div class="statistics col-xs-12">
        <div class="col-xs-6">
            <h3>Last 20:</h3>
            <table class="table table-condensed table-hover">
                <thead>
                    <tr>
                        <th>Date:</th>
                        <th>User:</th>
                        <th>EAN:</th>
                        <th>SKU:</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $counts = $count->select('count', array('id', 'date', 'username', 'ean'), array('ORDER' => 'date DESC', 'LIMIT' => 20));

                foreach($counts as $counted) { ?>

                    <tr>
                        <td><?php echo $counted['date']; ?></td>
                        <td><?php echo $counted['username']; ?></td>
                        <td><?php echo $counted['ean']; ?></td>
                        <td><?php echo $counted['ean']; ?></td>
                        <td class="text-center"><a href="?delete=<?php echo $counted['id']; ?>"><i class="fa fa-minus-circle" style="color: #843534"></i></a></td>
                    </tr>

                <?php
                }
                ?>
                </tbody>
            </table>
        </div>
        <div class="col-xs-6">
            <h3>Log for <?php echo $_SESSION['username']; ?>:</h3>
            <table class="table table-condensed table-hover">
                <thead>
                <tr>
                    <th>Date:</th>
                    <th>Action:</th>
                    <th>Message:</th>
                    <th>EAN:</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $logs = $count->select('log', array('date', 'action', 'message', 'ean'), array('username' => $_SESSION['username'], 'ORDER' => 'date DESC','LIMIT' => 20));
                foreach($logs as $log) { ?>
                    <tr>
                        <td><?php echo $log['date']; ?></td>
                        <td><?php echo $log['action']; ?></td>
                        <td><?php echo $log['message']; ?></td>
                        <td><?php echo $log['ean']; ?></td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
<?php require 'design/footer.php'; ?>