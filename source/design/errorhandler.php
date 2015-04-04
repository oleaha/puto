<?php if($error != false) { ?>
    <div class="col-sm-8 col-sm-offset-2">
<?php if($error == 15) { ?>
    <div class="alert alert-danger alert-dismissable col-md-10 col-md-offset-1 pull-left" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <i class="fa fa-warning"></i> <?php echo $message; ?>
        <audio autoplay>
            <source src="<?php echo ROOT; ?>/lib/pacdies.mp3" type="audio/mpeg">
        </audio>
    </div>
<?php } else if($error == 10) { ?>
    <div class="alert alert-info alert-dismissable col-md-10 col-md-offset-1 pull-left" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <i class="fa fa-info"></i> <?php echo $message; ?></div>
<?php } else if ($error == 5) { ?>
    <div class="alert alert-success alert-dismissable col-md-10 col-md-offset-1 pull-left" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <i class="fa fa-thumbs-up"></i> <?php echo $message; ?></div>
<?php } ?>
    </div>
<?php } ?>