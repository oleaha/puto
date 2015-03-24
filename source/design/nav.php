<body>
<nav class="navbar navbar-inverse">
    <div class="holder">
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
                <li><a href="count.php">Count Product</a></li>
                <li><a href="validate.php">Validate count</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href=""><i class="fa fa-user"></i> <?php echo $_SESSION['username']; ?></a></li>
                <li><a href="?logout=true"><i class="fa fa-ban"></i> Log out</a></li>
            </ul>
            <?php } ?>
        </div><!-- /.navbar-collapse -->
    </div>
</nav>
<div class="col-md-12 col-xs-12">