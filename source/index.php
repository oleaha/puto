<?php
require 'design/top.php';

if(isset($_POST['login'])) {
    if(!empty($_POST['username']) && !empty($_POST['password'])) {
        $result = $individu->select("user", array('salt'), array("username" => $_POST['username']));

        if(isset($result[0]['salt'])) {
            $salt = $result[0]['salt'];
            $password = sha1($salt . sha1($salt . sha1($_POST['password'])));
            $result = $individu->select("user", array('user_id'), array("AND" => array('username' => $_POST['username'], 'password' => $password)));

            if(count($result) == 1) {
                $_SESSION['status'] = true;
                // TOOD: Log this
            } else {
                $error = true;
                $message = "Invalid username and or password!";
                // TODO: Log this
            }

        } else {
            $error = true;
            $message = "Invalid username and or password!";
            // TODO: Log this
        }

    } else {
        $error = true;
        $message = "Invalid username and or password!";
        // TODO: Log this
    }
}


require 'design/nav.php';
?>

<div class="col-md-2 col-md-offset-5 col-xs-8 col-xs-offset-2 text-center">
    <form action="" method="post">
        <div class="form-group">
            <label for="loginName"><h3 class="no-margin">Username:</h3></label>
            <input type="text" class="form-control" id="loginName" name="username" placeholder="Username">
        </div>
        <div class="form-group">
            <label for="loginPassword"><h3 class="no-margin">Password:</h3></label>
            <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-danger btn-block" name="login">Login</button>
    </form>
</div>

<?php require 'design/footer.php'; ?>