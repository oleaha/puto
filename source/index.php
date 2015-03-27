<?php
require 'design/top.php';

if($_SESSION['status'] == true) { ?>
    <script> location.replace("count.php"); </script>
<?php
}

if(isset($_POST['login'])) {
    if(!empty($_POST['username']) && !empty($_POST['password'])) {
        $result = $individu->select("user", array('salt'), array("username" => $_POST['username']));

        if(isset($result[0]['salt'])) {
            $salt = $result[0]['salt'];
            $password = sha1($salt . sha1($salt . sha1($_POST['password'])));
            $result = $individu->select("user", array('user_id'), array("AND" => array('username' => $_POST['username'], 'password' => $password)));

            if(count($result) == 1) {
                $_SESSION['status'] = true;
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['time'] = time();
                addEvent($count, $_SESSION['username'], 'login-success', 'User logged in successfully');
            } else {
                session_unset($_SESSION);
                $error = true;
                $message = "Invalid username and or password!";
                addEvent($count, $_POST['username'], 'login-error', 'User tried to log in, but had the wrong password. User: '.$_POST['username']);
            }
        } else {
            session_unset($_SESSION);
            $error = true;
            $message = "Invalid username and or password!";
            addEvent($count, $_POST['username'], 'login-error', 'User tried to log in, but had the wrong username. User: '.$_POST['username']);
        }

    } else {
        session_unset($_SESSION);
        $error = true;
        $message = "Invalid username and or password!";
        addEvent($count, $_POST['username'], 'login-error', 'Someone tried to log in without username and password');
    }
}
require 'design/nav.php';

if($error == true) { ?>
    <div class="alert alert-danger col-md-4 col-md-offset-4"><i class="fa fa-warning"></i> <?php echo $message; ?></div>
<?php } ?>
<div class="col-md-4 col-md-offset-4 col-xs-8 col-xs-offset-2 text-center">
    <form action="" method="post">
        <div class="form-group">
            <label for="loginName"><h3 class="no-margin">Username:</h3></label>
            <input type="text" class="form-control" id="loginName" name="username" placeholder="Username" autofocus="true" >
        </div>
        <div class="form-group">
            <label for="loginPassword"><h3 class="no-margin">Password:</h3></label>
            <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-danger btn-block" name="login">Login</button>
    </form>
</div>

<?php require 'design/footer.php'; ?>