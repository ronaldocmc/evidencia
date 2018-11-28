function wich_alert(response) {
	if (response.code == 200) {
		if (response.use_success) {
			if (response.message !== undefined) {
				alerts('success', 'Sucesso', response.message);
			} else {
				alerts('succes', 'Sucesso', 'Operação relaizada com sucesso');
			}
		}
	} else if (response.code == 400) {
		if (response.message !== undefined) {
			alerts('failed', 'Falha', response.message);
		} else {
			alerts('failed', 'Falha', 'Os dados enviados são inválidos');
		}
	} else if (response.message !== null) {
		alerts('failed', 'Falha', response.message);
	} else {
		alerts('failed', 'Falha', 'Erro na operação realizada');
	}
}