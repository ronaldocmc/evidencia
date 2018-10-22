<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/images/icon/logo-mini.png') ?>" />
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<style type="text/css">
    body { background-color: #ddd}
    .error-template {padding: 40px 15px;text-align: center;}
    .error-actions {margin-top:15px;margin-bottom:15px;}
    .error-actions .btn { margin-right:10px; }
</style>
<!------ Include the above in your HEAD tag ---------->

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="error-template">
                <h1>
                    Oops!</h1>
                <h2>
                    <?= $response->__get('code') ?></h2>
                <div class="error-details">
                    <?= $response->__get('message') ?>
                </div>
                <div class="error-actions">
                    <a href="<?=base_url(); ?>" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-home"></span>
                        Voltar para o portal </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php die(); ?>