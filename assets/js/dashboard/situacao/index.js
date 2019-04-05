/*
 * == Variáveis globais: ==
 *
 * @Boolean: is_superusuario
 * @String: base_url
 *
 */

class View extends GenericView {
    constructor() {
      super();
    }
  
    /* custom logic */
  }
  
  class Request extends GenericRequest {
    constructor() {
      super();
      this.route = "/situacao";
    }
    /* custom logic */
  }
  
  class Control extends GenericControl {
    constructor() {
      super();
  
      this.primaryKey = "situacao_pk";
      this.fields = [
           "situacao_nome", "situacao_descricao"
      ];
      this.tableFields = [
           "situacao_nome", "situacao_descricao"
      ];
      this.verifyDependences =  true ;
    }
    
    /* custom logic */
  }
  
  const myControl = new Control();
  
  myControl.init();