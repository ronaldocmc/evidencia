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

        this.generateSelect(data.situacoes, 'situacao_nome', 'situacao_pk', 'situacao_padrao_fk');
        this.generateSelect(data.tipos_servicos, 'tipo_servico_nome', 'tipo_servico_pk', 'tipo_servico_fk');
    }

}

class Request extends GenericRequest {

    constructor() {
        super();
        this.route = '/servico';
    }

}

class Control extends GenericControl {

    constructor() {
        super();

        this.primaryKey = "servico_pk";
        this.fields = ['servico_nome', 'situacao_nome', 'tipo_servico_nome', 'servico_desc', 'servico_abreviacao', 'situacao_padrao_fk', 'tipo_servico_fk'];
        this.tableFields = ['servico_nome', 'servico_abreviacao', 'servico_desc', 'situacao_nome'];
        this.verifyDependences = false;
    }

    
}

const myControl = new Control();

myControl.init();

