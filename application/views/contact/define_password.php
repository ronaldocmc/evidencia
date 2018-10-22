<link rel="stylesheet" href="<?= base_url('assets/css/password_force.css') ?>">
<body>
	<div class="container vh-100">
		<div class="col-md-4 offset-md-4" style="margin-top: 50px">
			<h1 align="center">
				<b>Evidência</b>
				<br> Recuperar senha</h1>
			<p align="center">Dicas para uma senha segura:</p>
			<ul class="text-muted">
				<li>Utilize caracteres especiais como: ! @ # $ %;</li>
				<li>Utilize letras maiúsculas e minúsculas;</li>
				<li>Utilize números.</li>
			</ul>


			<form class="form" method="POST" id="form" action="<?php echo base_url('contact/define_password/' . $token) ?>">

				<div class="col-md-12">

					<label for="new_password">Nova senha</label>
					<input type="password" id="password" name="new_password" class="form-control" aria-describedby="passwordHelpBlock" required>
					<meter max="4" id="password-strength-meter"></meter>
					<p class="form-text text-muted" id="password-strength-text"></p>
					<small id="passwordHelpBlock" class="form-text text-muted">
						Sua senha deve possuir no mínimo 8 caracteres.
					</small>
				</div>
				<div class="col-md-12">
					<label for="new_password_repeat">Repita a nova senha</label>
					<input type="password" name="new_password_repeat" class="form-control" id="second_password" required>
					<small id="password_error" class="form-text text-muted">

					</small>
				</div>

				<div class="col-md-12">
					<br>
					<input type="submit" id="post" name="post" value="Alterar" class="btn btn-primary btn-block">
				</div>
			</form>
		</div>
	</div>
	<footer class="mt-3" style="text-align: center">Desenvolvido por Prudenco</footer>
</body>

</html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>

<script>
	var strength = {
		0: "Extremamente baixa",
		1: "Baixa",
		2: "Média",
		3: "Alta",
		4: "Extremamente alta"
	}

	var password = document.getElementById('password');
	var second_password = document.getElementById('second_password');
	var meter = document.getElementById('password-strength-meter');
	var text = document.getElementById('password-strength-text');
	var form = document.getElementById('form');

	password.addEventListener('input', function () {
		var val = password.value;
		var result = zxcvbn(val);

		// Update the password strength meter
		meter.value = result.score;

		// Update the text indicator
		if (val !== "") {
			text.innerHTML = "Segurança: " + strength[result.score];
		} else {
			text.innerHTML = "Utilize uma senha segura!";
		}
	});

	second_password.addEventListener('input', function () {
		var error = document.getElementById('password_error');
		var val_password = password.value;
		var val_second_password = second_password.value;

		if (val_second_password.length >= 8) {

			if (val_password != val_second_password) {
				password.style.borderColor = "red";
				second_password.style.borderColor = "red";
				error.innerHTML = "As senhas não conferem!";
			} else {
				password.style.borderColor = "green";
				second_password.style.borderColor = "green";
				error.innerHTML = "Tudo certo!";
			}

		}
	});


</script>