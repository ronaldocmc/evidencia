/*
 * == Variáveis globais: == 
 * 
 * @Boolean: is_superusuario
 * 
 * 
 * @String: base_url
 * 
 */

class View extends GenericView {
    // FUNÇÃO GENÉRICA PARA PREENCHER UM SELECT
    // PARA PREENCHER UM MULTIPLE SELECT


    constructor() {
        super();
    }

    init(data, tableFields, primaryKey) {
        super.init(data, tableFields, primaryKey);

        console.log('data', data);

        this.generateSelect(data.funcoes, 'funcao_nome', 'funcao_pk', 'funcao_fk');
        this.generateSelect(data.departamentos, 'departamento_nome', 'departamento_pk', 'departamento_fk');
        this.generateSelect(data.setores, 'setor_nome', 'setor_pk', 'setor_fk');

    }

    hidePasswordInput() {
        $('#div-senha').hide();
    }

    showPasswordInput() {
        $('#div-senha').show();
    }

    getPassword() {
        return $('#funcionario_senha').val();
    }

}

class Request extends GenericRequest {

    constructor() {
        super();
        this.route = '/funcionario';
    }

}

class Control extends GenericControl {

    constructor() {
        super();

        this.primaryKey = "funcionario_pk";
        this.fields = ['funcionario_nome', 'funcionario_cpf', 'funcionario_login', 'funcao_fk', 'setor_fk', 'funcionario_caminho_foto', 'departamento_fk'];
        this.tableFields = ['funcionario_nome', 'funcionario_login', 'funcao_nome'];
        this.verifyDependences = false;
    }

    async init() {
        super.init();

        $(document).on('click', '.btn_edit', () => {
            this.handleFillFields();
            this.myView.hidePasswordInput();
            this.myView.generateImage('show-img-funcionario', this.data.self[this.state.selectedId].funcionario_caminho_foto);
            console.log(this.data.self[this.state.selectedId]);
        });

        $(document).on('click', '.btn_new', () => {
            this.myView.showPasswordInput();
        });

    };

    remove_image() {
        $('#img-input').attr('src', '');
        removeUpload();
    };

    blobToBase64(blob) {
        return new Promise((resolve) => {

            const reader = new FileReader();
            reader.onload = function () {
                let base64 = reader.result;
                resolve(base64);
            };
            reader.readAsDataURL(blob);

        });
    };;

    save() {
        const data = {};
        try {
            const index = this.state.selectedId;

            $('#img-input').cropper('getCroppedCanvas').toBlob(async (blob) => {
                
                data.img = await this.blobToBase64(blob);

                if (!this.state.selectedId) { data.funcionario_senha = this.myView.getPassword(); }

                const response = await super.save(data);

                if (this.state.selectedId) { this.data.self[index].funcionario_caminho_foto = response.data.path; }

                this.remove_image();

            });
        } catch (err) {
            if (!this.state.selectedId) { data.funcionario_senha = this.myView.getPassword(); }
            super.save(data);
        }
    }


}

const myControl = new Control();

myControl.init();

