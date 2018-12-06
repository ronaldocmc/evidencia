<style type="text/css">

</style>

<body style="background-color: #e5e5e5;">

	<div class="shadow-lg mt-5 px-0 card col-md-4" style="margin: 0 auto; float: none; margin-bottom: 10px;">
		<div class="card-header">
        	<h4>
        		<img src= "<?=base_url('/assets/images/icon/logo-mini.png')?>" alt="Avatar">
        		<?php if ($define !== null): ?>
					Definir senha
				<?php else: ?>
					 Recuperar senha
				<?php endif ?>
        	</h4>
    	</div>

    	<div class="card-body mb-0 text-secondary">
    		<div class="row form-group">
    			<div class="col-md-12 mb-0">
    				<?php if ($define !== null): ?>
                    <form class="form" method="POST" action="<?php echo base_url('contact/define_password/' . $token) ?>">
                    <?php else: ?>
                    <form class="form" method="POST" action="<?php echo base_url('contact/new_password/' . $token) ?>">
                    <?php endif ?>

                        	<div class="row">
                        		
                        		<div class="col-md-12">
									<label for="new_password">Nova senha</label>
									<input type="password" id="password" name="new_password" class="form-control" required minlength="8">
									<small id="passwordHelpBlock" class="form-text text-muted">
										Sua senha deve possuir no mínimo 8 caracteres.
									</small>
								</div>

								<div class="pt-2 col-md-12">
									<label for="new_password_repeat">Repita a nova senha</label>
									<input type="password" name="new_password_repeat" class="form-control" id="second_password" required minlength="8">
									<small id="password_error" class="form-text text-muted"></small>
								</div>

                        	</div>	
                        	<div class="card-footer mb-0 bg-transparent text-center">
                        		<button type="button" id="submit" class="btn btn-primary submit btn-sm">
                                    <i class="fa fa-dot-circle-o"></i> Enviar
                                </button>
                        	</div>
                    </form>

    			</div>
    		</div>
    	</div>
	</div>
</body>

</html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
