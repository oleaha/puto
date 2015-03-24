<?php
require_once '../system/database.php';
require_once '../system/log.php';

?>

<?php
if(isset($_GET['logout']) == true) {
    session_unset();
    session_destroy();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Count - INDIVIDU.NO</title>

    <!-- Template styles -->
    <link href="<?php echo ROOT; ?>/lib/css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo ROOT; ?>/lib/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo ROOT; ?>/lib/css/bootstrap-datepicker.min.css" rel="stylesheet">

    <script src="<?php echo ROOT; ?>/lib/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo ROOT; ?>/lib/js/bootstrap-datepicker.min.js"></script>

    <script src="<?php echo ROOT; ?>/lib/js/bootstrap.min.js"></script>
    <link href="<?php echo ROOT; ?>/lib/css/style.css" rel="stylesheet">

</head>

<?php
if($_SESSION['time'] > $_SESSION['time'] + 1200) {
session_destroy($_SESSION['status']);
session_destroy($_SESSION['username']);
}

if(strpos($_SERVER['PHP_SELF'], 'index.php') == false) {

    if($_SESSION['status'] == null) { ?>
        <script> location.replace("index.php"); </script>
<?php
} }
?>