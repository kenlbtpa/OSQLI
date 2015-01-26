<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $scope->title ?></title>

	<link rel="stylesheet" type="text/css" href="<?=VENDOR_PATH?>/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?=VENDOR_PATH?>/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?=VENDOR_PATH?>/site.css">
  </head>
  <body>
  	<?php require_once TEMPLATES_PATH . '/header.php'; ?>
  	<?= $scope->content; ?>
  </body>
</html>
