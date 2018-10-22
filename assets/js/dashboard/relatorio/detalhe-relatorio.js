
//Variáveis globais utilizadas no JS
var main_map;
var main_marker = null;
var posicao_selecionada = null;
var primeiro_editar = false;
var adicionar_imagem = 1; 
//-----------------------------------//

// $(document).on('ready', function(){
// 	$('input.form-control:text').attr('disabled', true);
// });


$(document).on('click','#btn-trocar-funcionario',function(event) {
	var id_relatorio = $('#id-relatorio').val();

	var data = 
	{	
		'funcionario_fk': $('#novo-funcionario').val()
	}

	$.post(base_url+'/Relatorio/change_employee/'+id_relatorio,data).done(function (response) {
		if (response.code == 501)
		{
			alerts('failed','Erro!','Ocorreu alguma falha no banco de dados. Tente novamente mais tarde');
		}
		else if(response.code == 200)
		{
			location.reload();
		}
	});
});


$(document).on('click','#btn-deletar-relatorio',function(event) {
	var id_relatorio = $('#id-relatorio').val();
	var data = {}
	$.post(base_url+'/Relatorio/destroy/'+id_relatorio, data).done(function (response) {
		if (response.code == 501)
		{
			alerts('failed','Erro!','Ocorreu alguma falha no banco de dados. Tente novamente mais tarde');
		}
		else if (response.code == 500)
		{
			alerts('failed','Erro!','Ocorreu alguma falha no banco de dados. Tente novamente mais tarde');
		}
		else if (response.code == 404)
		{
			alerts('failed','Erro!','Relatório não encontrado.');
		}
		else if(response.code == 200)
		{
			window.location.href = base_url;
		}
	});
});


//Função que inicializa o google maps na página
function initMap() 
{
    main_map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -22.121265, lng: -51.383400},
        zoom: 13
    });

    var geocoder = new google.maps.Geocoder();

    main_map.addListener('click', function(event) {
        var latlng = {lat: event.latLng.lat(), lng: event.latLng.lng()};
        populaLatLong(latlng);

        geocoder.geocode({'location': latlng}, function(results, status) {
            if (status === 'OK') {
                if (results[0]) {
                    // console.log(results[0].geometry.location);
                    main_map.setCenter(results[0].geometry.location);
                    preencheCampos(results[0].address_components);
                } else {
                    console.log('No results found');
                }
            } else {
                console.log('Geocoder failed due to: ' + status);
            }
        });

        criarMarcacao(event.latLng);
    });
}


    //Função que aguarda o clique no botão editar e preenche os campos do modal
    $(document).on('click', '.btn_editar', function (event) 
    {
        primeiro_editar = true;


        $('#ordem_servico_pk').val(ordens_servico[$(this).val()]['ordem_servico_pk']);
        posicao_selecionada = $(this).val();

        //var data = get_departamento_and_tiposervico(ordens_servico[posicao_selecionada]['tipo_servico_fk'])//Aqui eu vou fazer uma função que vai requisitar percorrer departamentos e encontrar o fk
        var servico_selecionado_pk = ordens_servico[posicao_selecionada]['data_criacao'];
        console.log('Data:'+ordens_servico[posicao_selecionada]['data_criacao']);
        $('#os_data').val(ordens_servico[posicao_selecionada]['data_criacao']);
        $('#codigo_os').val(ordens_servico[posicao_selecionada]['ordem_servico_cod']);
        $('#ordem_servico_pk').val(parseInt(ordens_servico[posicao_selecionada]['ordem_servico_pk']));
        $('#ordem_servico_desc').val(ordens_servico[posicao_selecionada]['ordem_servico_desc']);
        $('#departamento').val(ordens_servico[posicao_selecionada]['departamento_nome']);
        $('#tipo_servico').val(ordens_servico[posicao_selecionada]['tipo_servico_nome']);
        $('#servico_pk').val(ordens_servico[posicao_selecionada]['servico_nome']);
        $('#situacao_pk').val(ordens_servico[posicao_selecionada]['situacao_atual']);
        console.log($('#situacao_pk').val());
        $('#prioridade_pk').val(ordens_servico[posicao_selecionada]['prioridade_nome']);
        $('#procedencia_pk').val(ordens_servico[posicao_selecionada]['procedencia_nome']);
        $('#setor_pk').val(ordens_servico[posicao_selecionada]['setor_nome']);
        $("#latitude").val(ordens_servico[posicao_selecionada]['coordenada_lat']);
        $("#longitude").val(ordens_servico[posicao_selecionada]['coordenada_long']);
        $("#image-upload-div").hide();
        $("#bairro-input").val(ordens_servico[posicao_selecionada]['bairro_nome']);
        $("#logradouro-input").val(ordens_servico[posicao_selecionada]['logradouro_nome']);   
        $("#numero-input").val(ordens_servico[posicao_selecionada]['local_num']);    
        $("#estado_pk").val(ordens_servico[posicao_selecionada]['estado_fk']);
        $("#cidade-input").val(ordens_servico[posicao_selecionada]['municipio_nome']);
        $("#complemento-input").val(ordens_servico[posicao_selecionada]['local_complemento']);

        var data_local;
        var local = "";

        var latlng = {lat: parseFloat(ordens_servico[posicao_selecionada]['coordenada_lat']), lng: parseFloat(ordens_servico[posicao_selecionada]['coordenada_long'])}
        populaLatLong(latlng);
        main_map.setCenter(latlng);
        criarMarcacao(latlng);

        $("#logradouro-input").removeClass('loading');
        $("#bairro-input").removeClass('loading');
        $('#ce_ordem_servico').modal('show');

    });

    function populaLatLong(location) {
        $("#latitude").val(location.lat);
        $("#longitude").val(location.lng);
    }

    function criarMarcacao(location) {
        if(main_marker != null){
            main_marker.setMap(null);
        }

        main_marker = new google.maps.Marker({
          position: location,
          map: main_map
      });
    //main_map.setCenter('('+location.lat+', '+location.lng+')');
	}
