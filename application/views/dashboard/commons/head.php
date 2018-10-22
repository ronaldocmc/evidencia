<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/images/icon/logo-mini.png') ?>" />

    <!-- Title Page-->
    <title>Evidência</title>
    <!-- Fontfaces CSS-->
    <link href="<?php echo base_url('assets/css/font-face.css');?>" rel="stylesheet" media="all">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/font-awesome-4.7/css/font-awesome.min.css')?>">
    <link href="<?php echo base_url('assets/vendor/font-awesome-5/css/fontawesome-all.min.css')?>" rel="stylesheet" media="all">
    <link href="<?php echo base_url('assets/vendor/mdi-font/css/material-design-iconic-font.min.css')?>" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="<?php echo base_url('assets/vendor/bootstrap-4.1/bootstrap.min.css')?>" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="<?php echo base_url('assets/vendor/animsition/animsition.min.css')?>" rel="stylesheet" media="all">
    <link href="<?php echo base_url('assets/vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css')?>" rel="stylesheet" media="all">
    <link href="<?php echo base_url('assets/vendor/wow/animate.css')?>" rel="stylesheet" media="all">
    <link href="<?php echo base_url('assets/vendor/css-hamburgers/hamburgers.min.css')?>" rel="stylesheet" media="all">
    <link href="<?php echo base_url('assets/vendor/slick/slick.css')?>" rel="stylesheet" media="all">
    <link href="<?php echo base_url('assets/vendor/select2/select2.min.css')?>" rel="stylesheet" media="all">
    <link href="<?php echo base_url('assets/vendor/perfect-scrollbar/perfect-scrollbar.css')?>" rel="stylesheet" media="all">
   

    <!-- Main CSS-->
    <link href="<?php echo base_url('assets/css/theme.css')?>  " rel="stylesheet" media="all">

    <?php

    if(isset($this->session->css))
    {
        foreach ($this->session->css as $css) 
        { ?>
            
            <link href="<?= $css ?>  " rel="stylesheet" media="all">

  <?php }
    }

    ?>

</head>

<body class="">
    <div class="page-wrapper">