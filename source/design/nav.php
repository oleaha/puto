<body>
<div class="container col-md-10 col-md-offset-1 col-xs-12">
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">INDIVIDU.NO</a>
            </div>

            <?php if(isset($_SESSION['status']) && $_SESSION['status'] == true) { ?>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="#">Count Product</a></li>
                    <li><a href="#">Validate count</a></li>
                </ul>
                <?php } ?>
            </div><!-- /.navbar-collapse -->
        </div>
    </nav>

    <?php if($error == true) { ?>
    <div class="alert alert-danger col-md-4 col-md-offset-4"><i class="fa fa-warning"></i> <?php echo $message; ?></div>
<?php } ?>