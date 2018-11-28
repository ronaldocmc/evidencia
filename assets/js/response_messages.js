function wich_alert(response) {
	if (response.code == 200) {
		if (response.object !== undefined && response.operation !== undefined) {
			alerts('success', 'Sucesso', response.object + ': ' + response.operation + ' realizado com sucesso');
		} else if (response.message !== undefined) {
			alerts('success', 'Sucesso', response.message);
		} else {
			alerts('succes', 'Sucesso', 'Operação relaizada com sucesso');
		}
	} else if (response.code == 400) {
		if (response.message !== undefined) {
			alerts('failed', 'Erro', response.message);
		} else {
			alerts('failed', 'Erro', 'Dados enviados inválidos');
		}
	} else if (response.message !== null) {
		alerts('failed', 'Erro', response.message);
	} else {
		alerts('failed', 'Erro', 'Erro na operação realizada');
	}
}