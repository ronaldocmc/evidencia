class View extends GenericView {

    constructor() {
        super();
    }

    init(data, tableFields, primaryKey) {
        this.state.tableFields = tableFields;
        this.state.self = data.self;
        this.primaryKey = primaryKey;
        
        this.conditionalRender();
    
        this.render(data.self);
        $('.table-responsive').show();
        $('#loading').hide();
        // Para remover o style fantasma na tabela
        $('.table-striped').removeAttr('style');
    
    	this.generateSelect(data.municipios, 'municipio_nome', 'municipio_pk', 'municipio_pk');
    }

}

class Request extends GenericRequest {

    constructor() {
        super();
        this.route = '/Organizacao';
    }

    async init () {
    	const response = await this.send('/index', {});

        return response.data;
    }

}

class Control extends GenericControl {

    constructor() {
        super();

        this.primaryKey = "organizacao_pk";
        this.fields = ['organizacao_pk', 'organizacao_nome', 'organizacao_cnpj', 'localizacao_rua',
        			   'localizacao_num', 'localizacao_bairro'];
        this.tableFields = ['organizacao_pk', 'organizacao_nome'];
        this.verifyDependences = false;
    }

    fillFields(object, fields) {
    	super.fillFields(object, fields);

		this.myView.generateSelect(this.data.municipios, 'municipio_nome', 'municipio_pk', 'municipio_pk');
	}

    async save() {
		this.myView.initLoad();

		const sendData = this.myView.createJsonWithFields(this.fields, this.data);
		sendData['localizacao_municipio'] = $('#municipio_pk').val();

		sendData["senha"] = $(`#senha`).val();

		const response = await this.myRequests.send("/save", sendData);

		this.myView.endLoad();

		delete sendData["senha"];

		this.handleResponse(response, sendData);

		return response;
	}

}

const myControl = new Control();

myControl.init();