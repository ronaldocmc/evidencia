class View extends GenericView {

    constructor() {
        super();
    }

    render(data) {

        let fields, buttons;

        table.clear().draw();

        data.forEach((d, i) => {

            let id = this.findPositionInOriginalArray(d);

            fields = this.generateFields(this.state.tableFields, d);
            
            buttons = this.generateButtons(d.relatorio_situacao, id);

            table.row.add([...fields, buttons]).draw(false);
        });
    }

    generateButtons(condition, i) {
    	let buttons = `<div class='btn-group'>
    					<a class="btn btn-sm btn-primary report_detail" 
    						href="${base_url}/relatorio/detalhes/${this.state.self[i].relatorio_pk}">
			               	<div class="d-none d-sm-block">
			                   Detalhes
			               	</div>
			               	<div class="d-block d-sm-none">
			                   <i class="fas fa-eye fa-fw"></i>
			               	</div>
			            </a>`; 

    	if (condition === 'Inativo') {
    		buttons += `<a class="btn btn-sm btn-danger" disabled="true" style="color: white";>
			               <div class="d-none d-sm-block">
			                   Inativo
			               </div>
			               <div class="d-block d-sm-none">
			               		<i class="fas fa-minus-circle"></i>
			               </div>
			            </a>`;
    	} else {
    		buttons += `<a class="btn btn-sm btn-success imprimir_relatorio" target="_blank"
    						href="${base_url}/relatorio/imprimir/${this.state.self[i].relatorio_pk})">
			               <div class="d-none d-sm-block">
			                   Imprimir
			               </div>
			               <div class="d-block d-sm-none">
			               		<i class="fas fa-print"></i>
			               </div>
			           </a>`;
    	}
            
		buttons += `</div>`;
		return buttons;
    }

    filter(data, target) {
        const type = $(target).val();
        const renderData = data.filter(d => (d.relatorio_situacao == type || type == -1));
        this.render(renderData);
    }

}

class Request extends GenericRequest {

    constructor() {
        super();
        this.route = '/relatorio';
    }

}

class Control extends GenericControl {

    constructor() {
        super();

        this.primaryKey = "relatorio_pk";
        this.fields = ['ativo', 'departamento_fk', 'funcionario_nome', 'funcionario_pk',
        			   'organizacao_fk', 'quantidade_os', 'relatorio_criador', 'relatorio_data_criacao',
        			   'relatorio_data_entrega', 'relatorio_data_fim_filtro', 'relatorio_data_inicio_filtro',
        			    'relatorio_func_responsavel', 'relatorio_pk', 'relatorio_situacao'];
        this.tableFields = ['funcionario_nome', 'quantidade_os', 'relatorio_situacao',
        					'relatorio_data_criacao', 'relatorio_data_entrega'];
        this.verifyDependences = true;
    }

    init() {
    	super.init();

    	$(document).on("click", "#btn-restaurar", () => {
			this.receiveReports();
		});
    }

    receiveReports() {
    	btn_load($('#btn-restaurar'));
		var senha = $("#pass-modal-restaurar").val();

		if(senha == ""){
			alerts('failed','Erro!','Informe a senha!');
			btn_ativar($('#btn-restaurar'));
			return;
		}

		var data = 
		{
			'senha' : senha
		}

		$.post(base_url+'/Relatorio/receive_report/', data).done(function (response) {
			btn_ativar($('#btn-restaurar'));
			if (response.code == 200) {
				alerts('success','Sucesso!','Relatórios recebidos com sucesso.');
				$('#restaurar_os').modal('hide');
			}
			else if (response.code == 404) {
				alerts('success','Sucesso!','Não há relatórios para serem recebidos!');
				$('#restaurar_os').modal('hide');
			}
			else if (response.code == 401) {
				alerts('failed','Erro!','Senha incorreta');
			}

			$("#pass-modal-restaurar").val("");
			window.location.href = base_url+'/Relatorio/';
		}, "json");
    }
}

const myControl = new Control();

myControl.init();