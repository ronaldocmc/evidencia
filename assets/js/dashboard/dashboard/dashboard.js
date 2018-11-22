
$('#tabela-funcionario').click(function(){
	$('#table-funcionario').show();
	$('.heatmap').hide();
});


$('#tabela-grafico').click(function(){
	$('.heatmap').show();
	$('#table-funcionario').hide();
});


$( document ).ready(function() {

	preencheAtualizacao('texto-atualizacao');
});

function preencheAtualizacao(id_element){
	var data = new Date();
	var dia = data.getDate();
	var mes = data.getMonth();
	var ano = data.getFullYear();
	var hora = data.getHours();
	var minutos = data.getMinutes();
	var seg = data.getSeconds();

	var atual = dia+'/'+(mes+1)+"/"+ano+" "+hora+":"+minutos+":"+seg;
	$('#'+id_element).text('última atualização às '+atual);
}