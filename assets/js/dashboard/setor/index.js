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
        this.route = '/setor';
    }

}

class Control extends GenericControl {

    constructor() {
        super();

        this.primaryKey = "setor_pk";
        this.fields = ['setor_nome'];
        this.tableFields = ['setor_nome'];
        this.verifyDependences = false;
    }
}

const myControl = new Control();

myControl.init();

