class View extends GenericView {

    constructor() {
        super();
    }

    init(data, tableFields, primaryKey) {
        this.state.data = data;
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

        data.self_municipios.forEach( (municipio, pos) => {
            this.generateCity(municipio, pos);
        });
    }

    generateCity(municipio, pos) {
        $('#cities').append(`<div id="${municipio.municipio_nome.split(' ').join('_')}"> </div>`);
        $(`#${municipio.municipio_nome.split(' ').join('_')}`).append(this.generateParagraph(municipio.municipio_nome));
        $(`#${municipio.municipio_nome.split(' ').join('_')}`).append(this.createButton('remove', municipio.municipio_pk, 'danger', 'remover', pos, 'fa-times'));
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
        this.municipios_nome = null;
    }

    async init() {
        await super.init();

        this.municipios_nome = await this.getMunicipiosNome();

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

        $(document).on("click", "#add_city", () => {
            this.addCity();
        });

        $(document).on("click", ".btn_remove", (e) => {
            this.removeCity(e.currentTarget);
        });

        $('#new_city').autocomplete({
            source: this.municipios_nome
        });
    }

    async getMunicipiosNome() {
        let names = [];

        await this.myView.state.data.municipios.forEach( municipio => {
            names.push(municipio.municipio_nome);
        });

        return names;
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

    async addCity() {
        let city = null;
        let position;
        let used = false;

        await this.myView.state.data.municipios.forEach( (municipio, pos) => {
            if (municipio.municipio_nome === $('#new_city').val()) {
                city = municipio;
                position = pos;

                return;
            }
        });

        if (city === null) {
            this.myView.showMessage('failed', 'Erro', 'Cidade não encontrada');
            return;
        } else {
            
            await this.myView.state.data.self_municipios.forEach( municipio => {
                if (municipio.municipio_pk === city.municipio_pk) {
                    used = true;
                    return;
                }
            });

            if (used) {
                this.myView.showMessage('failed', 'Erro', 'Cidade já adicionada');
                return;
            }
        }
        
        let sendData = {municipio_fk: city.municipio_pk};
        let response = await this.myRequests.send('/add_city', sendData);

        this.handleReponse(response, 'add', city, position);
    }

    async removeCity(button) {
        let position = button.value;
        let city = this.myView.state.data.self_municipios[position];
        let sendData = {municipio_fk: city.municipio_pk}        

        let response = await this.myRequests.send('/remove_city', sendData);
        
        this.handleReponse(response, 'remove', city, position);
    }

    handleReponse(response, action, city, position) {
        if (!response) {
            this.myView.showMessage("failed", "Falha", "Entre em contato com a central!");
            return;
        }

        if (response.code == 200) {
            this.myView.showMessage("success", "Sucesso", "Operação realizada!");

            this.doAction(action, city, position);
        } else {
            this.myView.showMessage("failed", "Falha", response.data.mensagem);
        }
    }

    doAction(action, city, position) {
        switch(action) {
            case 'add':
                this.myView.generateCity(city, position);
                this.myView.state.data.self_municipios.push(city);
                break;

            case 'remove':
                $(`#${city.municipio_nome.split(' ').join('_')}`).remove();

                this.myView.state.data.self_municipios.splice(position, 1);
                break;
        }

        $('#new_city').val('');
    }
}

const myControl = new Control();

myControl.init();
