<?php
use Core\{Session, FormHelper};
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= $this->content('head'); ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="<?= PROOT ?>front-resources/images/logo-16x16.png" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>
        <?= $this->siteTitle(); ?>
    </title>
    <script src="<?= PROOT ?>front-resources/js/pace.min.js"></script>

    <!-- Fonts -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css?family=Major+Mono+Display" rel="stylesheet">
    <link rel="stylesheet" href="<?=PROOT?>front-resources/css/fontawesome/css/all.min.css">

    <!-- Icons -->
    <link href="<?= PROOT ?>front-resources/css/all.css" rel="stylesheet">

    <!-- Styles -->
    <link href="<?= PROOT ?>front-resources/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="<?= PROOT ?>front-resources/css/style.css?v=<?= VERSION; ?>" rel="stylesheet">
    <link href="<?= PROOT ?>front-resources/css/alertMsg.css?v=<?= VERSION; ?>" rel="stylesheet">
</head>

<body>
    <?php include 'main_menu.php'; ?>
    <?= $this->content('body'); ?>
    <!-- Core -->
    <script src="<?= PROOT ?>front-resources/vendor/jquery/jquery.min.js"></script>
    <script src="<?= PROOT ?>front-resources/vendor/popper/popper.min.js"></script>
    <script src="<?= PROOT ?>front-resources/js/bootstrap/bootstrap.min.js"></script>
    <!-- Optional -->
    <script src="<?= PROOT ?>front-resources/js/alertMsg.js?v=<?= VERSION; ?>"></script>
    <script src="<?= PROOT ?>front-resources/js/app.js"></script>
</body>

</html>
