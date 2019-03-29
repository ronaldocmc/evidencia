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

}

class Request extends GenericRequest {

    constructor() {
        super();
        this.route = '/funcao';
    }

}

class Control extends GenericControl {

    constructor() {
        super();

        this.primaryKey = "funcao_pk";
        this.fields = ['funcao_nome'];
        this.tableFields = ['funcao_nome'];
        this.verifyDependences = true;
    }
}

const myControl = new Control();

myControl.init();

