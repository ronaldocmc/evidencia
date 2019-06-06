class View extends GenericView {

    constructor() {
        super();
    }

    init(data, tableFields, primaryKey) {
        this.state.tableFields = tableFields;
        this.state.self = data.self;
        this.primaryKey = primaryKey;
        
        this.conditionalRender();
    
        this.render(data, tableFields);
        $('.table-responsive').show();
        $('#loading').hide();
        // Para remover o style fantasma na tabela
        $('.table-striped').removeAttr('style');
    }

    render(data, tableFields) {
        tableFields.forEach( field => {
            $(`#${field}`).val(data.self[0][field]);
        });

        this.generateSelect(data.municipios, 'municipio_nome', 'municipio_pk', 'localizacao_municipio');
    }

}

class Request extends GenericRequest {

    constructor() {
        super();
        this.route = '/organizacao';
    }

}

class Control extends GenericControl {

    constructor() {
        super();

        this.primaryKey = "organizacao_pk";
        this.tableFields = ['organizacao_pk', 'organizacao_nome', 'organizacao_cnpj', 'localizacao_rua', 'localizacao_num', 'localizacao_bairro'];
        this.fields = ['organizacao_pk', 'organizacao_nome', 'organizacao_cnpj', 'localizacao_rua', 'localizacao_num', 'localizacao_bairro', 'localizacao_municipio'];
        this.verifyDependences = false;
    }

    init() {
        super.init();

        // clicks de cidade
        $(document).on("click", "#btn-edit", () => {
            if (this.myView.state.is_superusuario !== '0') {
                $('#confirm_edit').modal('show');
            } else {
                this.save();
            }
        });

        $(document).on("click", "#btn-confirmar-edicao", () => {
            this.save();
        });

    }

    async save() {
        this.myView.initLoad();

        const sendData = this.myView.createJsonWithFields(this.fields);
        
        if (this.myView.state.is_superusuario)
            sendData["senha"] = this.myView.getPassword("save")["senha"];

        const response = await this.myRequests.send("/save", sendData);

        delete sendData["senha"];

        this.myView.endLoad();
    }
}

const myControl = new Control();

myControl.init();
