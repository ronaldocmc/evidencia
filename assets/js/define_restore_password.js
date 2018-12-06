var password = document.getElementById('password');
var second_password = document.getElementById('second_password');
var form = document.getElementById('form');

password.addEventListener('input', function () {
	var val_password = password.value;
	var message = document.getElementById('passwordHelpBlock');

	if (val_password.length < 8) {
		message.innerHTML = 'Senha curta';
	} else {
		message.innerHTML = 'Ok';
	}
});

second_password.addEventListener('input', function () {
	var error = document.getElementById('password_error');
	var val_password = password.value;
	var val_second_password = second_password.value;

	if (val_password != val_second_password) {
		second_password.style.borderColor = "red";
		error.innerHTML = "As senhas não conferem!";
	} else {
		second_password.style.borderColor = "green";
		error.innerHTML = "Tudo certo!";
	}
});