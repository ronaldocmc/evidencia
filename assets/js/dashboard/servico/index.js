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

    createJsonWithFields(fields, data) {
        const dataContainer = super.createJsonWithFields(fields);

        let situacao = data.situacoes.find(situacao => {
            return situacao.situacao_pk === dataContainer['situacao_padrao_fk'];
        });
        let tipo_servico = data.tipos_servicos.find(tipo_servico => {
            return tipo_servico.tipo_servico_pk === dataContainer['tipo_servico_fk'];
        });

        dataContainer['situacao_nome'] = situacao.situacao_nome;
        dataContainer['tipo_servico_nome'] = tipo_servico.tipo_servico_nome;

        return dataContainer;
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
        this.tableFields = ['servico_nome', 'servico_abreviacao', 'servico_desc', 'tipo_servico_nome'];
        this.verifyDependences = false;
    }

    
}

const myControl = new Control();

myControl.init();

