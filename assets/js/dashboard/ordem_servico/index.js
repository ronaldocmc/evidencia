function btn_load(button_submit){
    button_submit.attr('disabled', 'disabled');
    button_submit.css('cursor', 'default');
    button_submit.find('i').removeClass();
    button_submit.find('i').addClass('fa fa-refresh fa-spin');
}


function btn_ativar(button_submit){
    button_submit.removeAttr('disabled');
    button_submit.css('cursor', 'pointer');
    button_submit.find('i').removeClass();
    button_submit.find('i').addClass('fa fa-dot-circle-o');
}


//Variáveis globais utilizadas no JS
var main_map, other_map, other_map_from_activity;
var main_marker = null;
var other_marker = null;
var other_maker_from_activity = null;
var posicao_selecionada = null;
var primeiro_editar = false;
var adicionar_imagem = 1; 
var adicionar_mapa = 1; 
//-----------------------------------//
$(document).ready(function() {
    $("#data_brasileira").click();
    $("#data_brasileira").click();
    pre_loader_hide();
});



//Função que aguarda a mudança de departamento. Caso o usuário altere o departamento, é necessário atualizar outros campos, operação
//que é feita pela função muda_dpto();
$("#departamento").change( function () {
    if(primeiro_editar == true){ //atualizando a flag de controle de operação 
        muda_depto();
    }
}).change();

//Função que aguarda a mudança de tipo de serviço. Caso o usuário altere o tipo de serviço, é necessário atualizar o campo serviços e situação
//, operação que é feita pela função muda_tipo_servico();    
$("#tipo_servico").change( function () {
    if(primeiro_editar == true){
        muda_tipo_servico();
    }
}).change();

//Função que aguarda a mudança de situação. Caso o usuário altere a situação, é necessário verificar se para aquele serviço/situação
//a foto não é obrigatória, caso seja atribuimos o required
$("#situacao_pk").change( function () {
    var situacao = $("#situacao_pk option:selected").val();

    for (var i = 0; i < situacoes.length; i++) {
        if(situacoes[i].situacao_pk == situacao) {

            if (situacoes[i].situacao_foto_obrigatoria == 1) {
                $("#input-upload").attr('required',true);
                $("#img-input").attr('required',true);
            }
            else {
                $("#input-upload").prop("required", false);
                $("#img-input").prop("required", false);
            }
            break;
        }
    }
}).change();

//Função que aguarda a mudança de procedência. Caso o usuário altere a procedencia, é necessário atualizar a descrição da procedência, ou seja,
//se Interna a descrição será "Registrada por funcionário", se externa "Registrado pela atendente". 
$("#procedencia_pk").change( function () {

    var procedencia_selected = $('#procedencia_pk option:selected').val();
    for(var i = 0; i < procedencias.length; i++ ){
        if(procedencias[i].procedencia_pk == procedencia_selected){
            $('#procedencias_options #procedencia_small').text(procedencias[i].procedencia_desc);
        }
    }

}).change();

//Função que adiciona as opções de tipos de serviço, conforme o departamento selecionado pelo usuário
function add_options_tipo_servico() {
    var depto = $("#departamento option:selected").val();

    for (var i = 0; i < tipos_servico.length; i++) {
        if(tipos_servico[i].departamento_fk == depto) {
            $("#tipo_servico")
            .append('<option value="' + parseInt(tipos_servico[i].tipo_servico_pk) + '">' + tipos_servico[i].tipo_servico_nome + '</option>');
        }
    }
}

//Função que adiciona as opções de serviço, conforme tipo de serviço selecionado pelo usuário
function add_options_servico() {
    var tipo_servico = $("#tipo_servico option:selected").val();

    for (var i = 0; i < servicos.length; i++) {
        if(servicos[i].tipo_servico_fk == tipo_servico) {

            $("#servico_pk").append('<option value="' + parseInt(servicos[i].servico_pk) + '">' + servicos[i].servico_nome + '</option>');
            // console.log(servicos[i].servico_pk);
            // console.log(servicos[i].servico_nome);
        }
    }
}

//Função que recupera a posição selecionada na datatable, isto é, qual ordem de serviço o usuário selecionou para editar, ativa ou excluir
$(document).on('click','.btn-attr-ordem_servico_pk',function(){
  $('#ordem_servico_pk').val(ordens_servico[$(this).val()]['ordem_servico_pk']);
  posicao_selecionada = $(this).val();
});

    //Função que chama outras função já descritas anteriormente para mudar e adicionar as opções de serviço e tipo de serviço
    //conforme o departamento selecionado pelo usuário
    function muda_depto() {

        $("#tipo_servico option").remove();
        add_options_tipo_servico();

        $("#servico_pk option").remove();
        add_options_servico();
        muda_servico();
    }
    
    //Função que chama outras função já descritas anteriormente para mudar e adicionar as opções de serviço
    //conforme o serviço selecionado pelo usuário
    function muda_tipo_servico() {
        $("#servico_pk option").remove();
        add_options_servico();
        muda_servico();
    }

    function muda_servico() {
        var servico = $("#servico_pk option:selected").val();

        for (var i = 0; i < servicos.length; i++) {
            if(servicos[i].servico_pk == servico) {
                $("#situacao_pk").val(parseInt(servicos[i].situacao_padrao_fk));
                $("#prioridade_pk").val(parseInt(servicos[i].prioridade_padrao_fk));

                if (servicos[i].situacao_foto_obrigatoria == "1") {
                    $("#input-upload").prop('required',true);
                    $("#img-input").prop('required',true);
                }
                else {
                    $("#input-upload").removeAttr("required");
                    $("#img-input").removeAttr("required");
                }
                break;
            }
        }
    }


    //Função que retorna um array com dados de departamento e tipo de serviço de um determinado serviço selecionado
    get_departamento_and_tiposervico = (tipo_servico_atual) =>
    {
        var data;
        for (var i in tipos_servico) 
        {
            //console.log(ordens_servico[i]);
            if (tipos_servico[i].tipo_servico_pk == tipo_servico_atual){
                data = 
                {
                    'departamento_nome' : tipos_servico[i].departamento_nome,
                    'departamento_pk'   : tipos_servico[i].departamento_fk,
                    'tipo_servico_pk'   : tipos_servico[i].tipo_servico_pk,
                    'tipo_servico_nome' : tipos_servico[i].tipo_servico_nome
                }
                return data;
            }
        }
    }

    //Função que remove a imagem que foi carregada caso o usuário desista de enviar a requisição de inserção/update, ou clicar no botão remover imagem
    remove_image = () => {
        $('#img-input').attr('src', '');
        removeUpload();
    }

    //Função que recupera a imagem inserida pelo usuário, convertendo-a para um blob e em seguida para Base64 
    //que é um tipo de dado mais leve, dps chama a função send que envia para o servidor os dados (ajax)
    send_data_historico = () => {
        try {
            btn_load($('#btn-salvar-historico'));
            btn_load($('#btn-salvar-atividade'));

            $('#img-input').cropper('getCroppedCanvas').toBlob((blob) => { 
                blobToBase64(blob,this.send_historico);
            });


        } catch (err) {
          this.send_historico(null); 
      }    

  }

//Função que converte um blob em base64, utiliza callback porque precisa ser sincrona
var blobToBase64 = function(blob, cb) {
    var reader = new FileReader();
    reader.onload = function() {
        var base64 = reader.result;
    // var base64 = dataUrl.split(',')[1];
    cb(base64);
};
reader.readAsDataURL(blob);
};


send_data = () => {
    try {

        //btn_load($('#pula-para-confirmacao'));
        btn_load($('.submit'));
        $('#img-input').cropper('getCroppedCanvas').toBlob((blob) => {
            blobToBase64(blob,this.send);
        });
    } catch (err) {
      this.send(null); 
  }
}

//Função que inicializa o google maps na página
function initMap() 
{
    main_map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -22.121265, lng: -51.383400},
        zoom: 13
    });

    criarMarcacao({lat: -22.121265, lng: -51.383400});
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

    other_map = new google.maps.Map(document.getElementById('map2'), {
        center: {lat: -22.121265, lng: -51.383400},
        zoom: 13
    });

    other_map_from_activity = new google.maps.Map(document.getElementById('omap2'), {
        center: {lat: -22.121265, lng: -51.383400},
        zoom: 13
    });


    var geocoder2 = new google.maps.Geocoder();
    criarMarcacao2({lat: -22.121265, lng: -51.383400});

    var geocoder3 = new google.maps.Geocoder();
    criarMarcacao3({lat: -22.121265, lng: -51.383400});

    $('#mapa_historico').hide();
    $('#omapa_historico').hide();

    //Função que preenhce o endereço conforme o clique do usuário do mapa
    function preencheCampos(endereco){
        var cidade;
        var estado, estado_mudou = false;

        for (var i = 0; i < endereco.length; i++) {

            if(endereco[i].types.indexOf("sublocality") !== -1){
                $("#bairro-input").val(endereco[i].long_name);
            }
            else if(endereco[i].types.indexOf("street_number") !== -1){
                $("#numero-input").val(endereco[i].long_name);    
            }
            else if(endereco[i].types.indexOf("route") !== -1){
                $("#logradouro-input").val(endereco[i].long_name);   
            }
            else if(endereco[i].types.indexOf("locality") !== -1){
                cidade = endereco[i].long_name;  
            }
            else if(endereco[i].types.indexOf("administrative_area_level_1") !== -1){
                if($("#uf-input option:selected").text() != endereco[i].short_name){
                    estado_mudou = true;
                    $("#uf-input option").filter(function() {
                        return this.text == endereco[i].short_name; 
                    }).attr('selected', true);  
                    estado = endereco[i].short_name;
                }
            }
        }

        if(estado_mudou){
            change_uf($("#uf-input").val(),estado,cidade);
        }
        else{
            $("#cidade-input option").filter(function() {
                return this.text == cidade; 
            }).attr('selected', true); 
        }
    }

    function populaLatLong(location) {
        $("#latitude").val(location.lat);
        $("#longitude").val(location.lng);
    }

    function criarMarcacao2(location) {
        if(other_marker != null){
            other_marker.setMap(null);
        }

        other_marker = new google.maps.Marker({
          position: location,
          map: other_map
      });
    }

    function criarMarcacao3(location) {
        if(other_maker_from_activity != null){
            other_maker_from_activity.setMap(null);
        }

        other_maker_from_activity = new google.maps.Marker({
          position: location,
          map: other_map_from_activity
      });
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


//Função que permite o usuário recuperar um local via um ponto de referencia, funciona unicamente se o endereço 
//não estiver preenchido. 
$(".referencia").focusout(function() {
    var local = "";

    //Se o logradouro não foi preenchido então buscamos pelo ponto de referência. 
    if($('#logradouro-input').val() == ""){

        local = $("#cidade-input option:selected").text() + " ";
        local += $("#referencia-input").val() + " ";
        local += $("#numero-input").val() + " ";
        local += $("#bairro-input").val();


        geocoder.geocode({'address': local}, function(results, status) {
          if (status === 'OK') {
            main_map.setCenter(results[0].geometry.location);
            criarMarcacao(results[0].geometry.location);

             // console.log(results[0].address_components);

             for(i = 0; i < results[0].address_components.length; i++){

                switch(results[0].address_components[i].types[0]){
                case 'street_number':  //Número da Rua
                $('#numero-input').val(parseInt(results[0].address_components[i].long_name));
                break;
                case 'route':
                    $('#logradouro-input').val(results[0].address_components[i].long_name); //Logradouro
                    break;
                case 'political': //Bairro
                $('#bairro-input').val(results[0].address_components[i].long_name);
                break;
                case 'administrative_area_level_2': //Cidade
                $('#cidade-input option:selected').text(results[0].address_components[i].long_name);
                break;  
                case 'administrative_area_level_1': //Estado
                $('#uf-input option:selected').text(results[0].address_components[i].short_name);
                break;    
            }
        }
        var latlng = {lat: results[0].geometry.location.lat(), lng: results[0].geometry.location.lng()}
        populaLatLong(latlng);
    } else {
        console.log('Geocode was not successful for the following reason: ' + status);
    }

});
    }
});


//Função que recupera o local inserido pelo usuário e indica ele no mapa
$(".endereco").focusout(function() {
    var local = "";

    local = $("#cidade-input option:selected").text() + " ";
    local += $("#logradouro-input").val() + " ";
    local += $("#numero-input").val() + " ";
    local += $("#bairro-input").val();

    geocoder.geocode({'address': local}, function(results, status) {
      if (status === 'OK') {
        main_map.setCenter(results[0].geometry.location);
        criarMarcacao(results[0].geometry.location);
        var latlng = {lat: results[0].geometry.location.lat(), lng: results[0].geometry.location.lng()}
        populaLatLong(latlng);
    } else {
        console.log('Geocode was not successful for the following reason: ' + status);
    }
});
});


//Função que recolhe o dropdown se o usuário sair do foco do campo
$('.input-dropdown').focusout(function(){

    $('.dropdown-menu').removeClass('show');

});


function remove_data() {
    $("#v_descricao").html('');
    $("#v_prioridade").html('');
    $("#v_procedencia").html('');
    $("#v_setor").html('');
    $("#v_servico").html('');
    $('#v_codigo').html('');

    $('.carousel-inner').html('');
    $('.carousel-indicators').html('');
    $('#timeline').html('');
    $('#v_loading').show();
}

function remove_data_atividade() {
    $("#ov_descricao").html('');
    $("#ov_prioridade").html('');
    $("#ov_procedencia").html('');
    $("#ov_setor").html('');
    $("#ov_servico").html('');
    $("#ov_codigo").html('');

    $('.carousel-inner').html('');
    $('.carousel-indicators').html('');
    $('#otimeline').html('');
    $('#ov_loading').show();
}

//Função que exibe e oculta as fotos do histórico pertencente a uma determinada OS.
$(document).on('click', '#btn-foto-historico', function (event) 
{
    if(adicionar_imagem == 1){

        $('#carouselExampleIndicators').show();
        $('#btn-foto-historico').html('<i class="fa fa-camera" aria-hidden="true"></i>');
        $('#btn-foto-historico').removeClass('btn-primary');
        $('#btn-foto-historico').addClass('btn-danger');
        adicionar_imagem++;
    }
    else
    {
        $('#carouselExampleIndicators').hide();
        $('#btn-foto-historico').html('<i class="fa fa-camera" aria-hidden="true"></i>');
        $('#btn-foto-historico').removeClass('btn-danger');
        $('#btn-foto-historico').addClass('btn-primary'); 
        adicionar_imagem--;

    }
});


$(document).on('click', '#obtn-foto-historico', function (event) 
{
    if(adicionar_imagem == 1){

        $('#ocarouselExampleIndicators').show();
        $('#obtn-foto-historico').html('<i class="fa fa-camera" aria-hidden="true"></i>');
        $('#obtn-foto-historico').removeClass('btn-primary');
        $('#obtn-foto-historico').addClass('btn-danger');
        adicionar_imagem++;
    }
    else
    {
        $('#ocarouselExampleIndicators').hide();
        $('#obtn-foto-historico').html('<i class="fa fa-camera" aria-hidden="true"></i>');
        $('#obtn-foto-historico').removeClass('btn-danger');
        $('#obtn-foto-historico').addClass('btn-primary'); 
        adicionar_imagem--;

    }
});


//Função que  preenche os campos do "Editar Histórico"
$(document).on('click', '.btn_historico', function (event) 
{
    document.getElementById("ce_historico_servico").focus();
    var imagem;
    var local_ordem='';

    $('#ordem_servico_pk').val(ordens_servico[$(this).val()]['ordem_servico_pk']);
    posicao_selecionada = $(this).val();

    remove_data();

    $("#v_descricao").html(ordens_servico[posicao_selecionada]['ordem_servico_desc']);
    $("#v_prioridade").html(ordens_servico[posicao_selecionada]['prioridade_nome']);
    $("#v_procedencia").html(ordens_servico[posicao_selecionada]['procedencia_nome']);
    $("#v_setor").html(ordens_servico[posicao_selecionada]['setor_nome']);
    $("#v_servico").html(ordens_servico[posicao_selecionada]['servico_nome']);
    $("#v_codigo").html(ordens_servico[posicao_selecionada]['ordem_servico_cod']);

    var latlng = {lat: parseFloat(ordens_servico[posicao_selecionada]['coordenada_lat']), lng: parseFloat(ordens_servico[posicao_selecionada]['coordenada_long'])}

    geocoder2.geocode({'location': latlng}, function(results, status) {
      if (status === 'OK') {
        other_map.setCenter(results[0].geometry.location);
        criarMarcacao2(results[0].geometry.location);

        let numero, rua, bairro, cidade, estado;
        for(i = 0; i < results[0].address_components.length; i++){

            switch(results[0].address_components[i].types[0]){
                    case 'street_number':  //Número da Rua
                    numero = results[0].address_components[i].long_name;
                    break;
                    case 'route':
                        rua = results[0].address_components[i].long_name; //Logradouro
                        break;
                    case 'political': //Bairro
                    bairro = (results[0].address_components[i].long_name);
                    break;
                    case 'administrative_area_level_2': //Cidade
                    cidade = (results[0].address_components[i].long_name);
                    break;  
                    case 'administrative_area_level_1': //Estado
                    estado = (results[0].address_components[i].short_name);
                    break;    
                }
            }

            local_ordem = rua + ", " + numero + ", " + bairro + " - " + cidade + " - " + estado; 
            $('#v_endereco').text(local_ordem);
        } else {
            console.log('Geocode was not successful for the following reason: ' + status);
        }
    });

    //Função que faz uma requisição para o servidor dos dados de um histórico
    get_historico(ordens_servico[posicao_selecionada]['ordem_servico_pk']);
});


//Função que  preenche os campos do "Alterar Atividade"
$(document).on('click', '.btn_atividade', function (event) 
{
    document.getElementById("atividade").focus();
    var imagem;
    var local_ordem='';
    $('#ordem_servico_pk').val(ordens_servico[$(this).val()]['ordem_servico_pk']);
    posicao_selecionada = $(this).val();

    remove_data_atividade();

    $("#ov_descricao").html(ordens_servico[posicao_selecionada]['ordem_servico_desc']);
    $("#ov_prioridade").html(ordens_servico[posicao_selecionada]['prioridade_nome']);
    $("#ov_procedencia").html(ordens_servico[posicao_selecionada]['procedencia_nome']);
    $("#ov_setor").html(ordens_servico[posicao_selecionada]['setor_nome']);
    $("#ov_servico").html(ordens_servico[posicao_selecionada]['servico_nome']);
    $("#ov_codigo").html(ordens_servico[posicao_selecionada]['ordem_servico_cod']);

    var latlng = {lat: parseFloat(ordens_servico[posicao_selecionada]['coordenada_lat']), lng: parseFloat(ordens_servico[posicao_selecionada]['coordenada_long'])}

    geocoder2.geocode({'location': latlng}, function(results, status) {
      if (status === 'OK') {
        other_map_from_activity.setCenter(results[0].geometry.location);
        criarMarcacao3(results[0].geometry.location);

        let numero, rua, bairro, cidade, estado;
        for(i = 0; i < results[0].address_components.length; i++){

            switch(results[0].address_components[i].types[0]){
                    case 'street_number':  //Número da Rua
                    numero = results[0].address_components[i].long_name;
                    break;
                    case 'route':
                        rua = results[0].address_components[i].long_name; //Logradouro
                        break;
                    case 'political': //Bairro
                    bairro = (results[0].address_components[i].long_name);
                    break;
                    case 'administrative_area_level_2': //Cidade
                    cidade = (results[0].address_components[i].long_name);
                    break;  
                    case 'administrative_area_level_1': //Estado
                    estado = (results[0].address_components[i].short_name);
                    break;    
                }
            }

            local_ordem = rua + ", " + numero + ", " + bairro + " - " + cidade + " - " + estado; 
            $('#ov_endereco').text(local_ordem);
        } else {
            console.log('Geocode was not successful for the following reason: ' + status);
        }
    });

    //Função que faz uma requisição para o servidor dos dados de um histórico
    get_atividade(ordens_servico[posicao_selecionada]['ordem_servico_pk']);
});



//Função que exibe e oculta as fotos do histórico pertencente a uma determinada OS.
$(document).on('click', '#btn-mapa-historico', function (event) 
{

    if(adicionar_mapa == 1){

        $('#mapa_historico').show();
        $('#btn-mapa-historico').removeClass('btn-primary');
        $('#btn-mapa-historico').addClass('btn-danger');
        adicionar_mapa++;

    }
    else
    {
        $('#mapa_historico').hide();
        $('#btn-mapa-historico').removeClass('btn-danger');
        $('#btn-mapa-historico').addClass('btn-primary'); 
        adicionar_mapa--;

    }
});


$(document).on('click', '#obtn-mapa-historico', function (event) 
{

    if(adicionar_mapa == 1){

        $('#omapa_historico').show();
        $('#obtn-mapa-historico').removeClass('btn-primary');
        $('#obtn-mapa-historico').addClass('btn-danger');
        adicionar_mapa++;

    }
    else
    {
        $('#omapa_historico').hide();
        $('#obtn-mapa-historico').removeClass('btn-danger');
        $('#obtn-mapa-historico').addClass('btn-primary'); 
        adicionar_mapa--;

    }
});


get_historico = (id) => 
{


    btn_load($('#btn-salvar-historico'));
    $('.close').attr('disabled', 'disabled');
    $('.close').css('cursor', 'default');
    $('#fechar-historico').attr('disabled', 'disabled');
    $('#fechar-historico').css('cursor', 'default');

    var html = "";
    var indicators = "";
    var active = "active";
    var timeline = "";

    var d = new Date();
    dataHora = (d.toLocaleString());  

    $.ajax({
        url: base_url + '/Ordem_Servico/json_especifico/' + id + '/' + 1,
        dataType: "json",
        success: function (response) {
            btn_ativar($('#btn-salvar-historico'));
            $('.close').removeAttr('disabled');
            $('.close').css('cursor', 'pointer');
            $('#fechar-historico').removeAttr('disabled');
            $('#fechar-historico').css('cursor', 'pointer');

            response.ordem.historico.map((historico, i) => {
                indicators += '<li data-target="#carouselExampleIndicators" data-slide-to="' + i + '"></li>';
                if (historico.comentario == null) {
                    historico.comentario = "Nenhum comentário adicionado.";
                }

                if(historico.funcionario_foto == null){
                    historico.funcionario_foto = './assets/uploads/perfil_images/default.png';
                }

                timeline += create_timeline(historico.comentario, historico.funcionario_foto, historico.funcionario, historico.situacao, historico.data);

                if (historico.foto != null) {
                    html += create_carousel_item(historico.comentario, historico.foto, historico.funcionario, historico.situacao, historico.data, active);
                    active = "";
                } else {
                    html += create_carousel_item(historico.comentario, 'no-image.png', historico.funcionario, historico.situacao, historico.data, active);
                }
            });

            $('.carousel-inner').html(html);
            $('.carousel-indicators').html(indicators);
            $('#carouselExampleIndicators').hide();
            $('#timeline').html(timeline);
            $('#v_loading').hide();
            $('#ordem_servico_pk').val(id);
            $('#historico_pk').val(ordens_servico[posicao_selecionada]['historico_ordem_pk'])
            popula_situacoes();   
            $('#ce_historico_servico').show();

        }, //Fecha success
        error: function (response) {
            $('.close').removeAttr('disabled');
            $('.close').css('cursor', 'pointer');
            $('#fechar-historico').removeAttr('disabled');
            $('#fechar-historico').css('cursor', 'pointer');
            alerts('failed', response.message, response.data);
        }
    });

}


get_atividade = (id) => 
{


    btn_load($('#btn-salvar-atividade'));
    $('.close').attr('disabled', 'disabled');
    $('.close').css('cursor', 'default');
    $('#fechar-atividade').attr('disabled', 'disabled');
    $('#fechar-atividade').css('cursor', 'default');

    var html = "";
    var indicators = "";
    var active = "active";
    var timeline = "";

    var d = new Date();
    dataHora = (d.toLocaleString());  

    $.ajax({
        url: base_url + '/Ordem_Servico/json_especifico/' + id + '/' + 1,
        dataType: "json",
        success: function (response) {
            btn_ativar($('#btn-salvar-atividade'));
            $('.close').removeAttr('disabled');
            $('.close').css('cursor', 'pointer');
            $('#fechar-atividade').removeAttr('disabled');
            $('#fechar-atividade').css('cursor', 'pointer');


            response.ordem.historico.map((historico, i) => {
                if(i == response.ordem.historico.length -1){ //ou seja, for o último:
                    indicators += '<li data-target="#carouselExampleIndicators" data-slide-to="' + i + '"></li>';
                    if (historico.comentario == null) {
                        historico.comentario = "Nenhum comentário adicionado.";
                    }

                    if(historico.funcionario_foto == null){
                        historico.funcionario_foto = './assets/uploads/perfil_images/default.png';
                    }

                    timeline += create_timeline(historico.comentario, historico.funcionario_foto, historico.funcionario, historico.situacao, reformatDate(historico.data));

                    if (historico.foto != null) {
                        html += create_carousel_item(historico.comentario, historico.foto, historico.funcionario, historico.situacao, reformatDate(historico.data), active);
                        active = "";
                    } else {
                        html += create_carousel_item(historico.comentario, 'no-image.png', historico.funcionario, historico.situacao, reformatDate(historico.data), active);
                    }
                }
            });

            timeline += 
            '<div class="message-item">'   +
            '<div class="message-inner">' +
            '<div class="message-head clearfix">' +
            '<div class="user-detail">' +
            '<h5 class="handle">' + "Adicionar Situação" + '</h5>' +
            '<div class="post-meta">' +
            '<div class="asker-meta">' +
            '<span class="qa-message-what">' + "Registrar hoje às " + dataHora + '</span>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="qa-message-content">' +
            '<div class="col-8 col-md-12">' +
            '<div style="width: 250px; margin-bottom: 10px">' +
            '<label for="situacao_pk">Nova Situação</label>' +
            '<select class="form-control" id="situacao_pk_historico" name="situacao_fk" required="true">' +
            '</select>' +
            '</div>' +
            '</div>' + 
            '<div class="col-md-12">' +
            '<label for="ordem_servico_desc">Novo comentário</label>' +
            '<textarea class="form-control" id="historico_comentario" name="comentario" ' +
            'class="form-control" required="true" maxlength="200"></textarea>' +
            '<small class="form-text text-muted">Por favor, informe a descrição da Ordem de Serviço</small>' +
            '</div>' +
            '</div>' +
            '<div class="row form-group">' +
            '<div class="col-12" id="image-upload-div" style="margin-left: 12px;">' +
            '<div class="image-upload-wrap" style="height: 100px;"">' +
            '<input class="file-upload-input" type="file" onchange="readURL(this);" accept="image/*" id="input-upload" required="false"/>' +
            '<div class="drag-text">' +
            '<h3 style="padding: 20px;">Ou clique/arraste e solte uma imagem aqui</h3>' +
            '</div>' +
            '</div>' +
            '<div class="file-upload-content">' +
            '<img id="img-input" class="file-upload-image" src="#" alt="your image" required="false"/>' +
            '<div class="col-12">' +
            '<button type="button" onclick="remove_image()" class="btn btn-danger">Remover</button>' +
            '</div>'+
            '</div>' +
            '<small class="form-text text-muted">Por favor, se necessário, carregue a imagem</small>' +
            '</div>' +
            '</div>' +
            '</div></div>';

            $('.carousel-inner').html(html);
            $('.carousel-indicators').html(indicators);
            $('#ocarouselExampleIndicators').hide();
            $('#otimeline').html(timeline);
            $('#ov_loading').hide();
            $('#ordem_servico_pk').val(id);
            $('#historico_pk').val(ordens_servico[posicao_selecionada]['historico_ordem_pk']);
            popula_situacoes();   
            $('#atividade').modal('show');

        }, //Fecha success
        error: function (response) {
            $('.close').removeAttr('disabled');
            $('.close').css('cursor', 'pointer');
            $('#fechar-atividade').removeAttr('disabled');
            $('#fechar-atividade').css('cursor', 'pointer');
            alerts('failed', response.message, response.data);
        }
    });

}

function popula_situacoes(){
    for (var i = situacoes.length-1; i >= 0; i--) {
        $("#situacao_pk_historico").append('<option value="' + parseInt(situacoes[i].situacao_pk) + '">' + situacoes[i].situacao_nome + '</option>');
    }
}

function create_timeline(comentario, src, funcionario, situacao, data) {
    return '<div class="message-item">' +
    '<div class="message-inner">' +
    '<div class="message-head clearfix">' +
    '<div class="avatar pull-left"><a href="./index.php?qa=user&qa_1=Oleg+Kolesnichenko"><img class="message-foto-perfil" src="'+ src +'"></a></div>' +
    '<div class="user-detail">' +
    '<h5 class="handle">' + funcionario + '</h5>' +
    '<div class="post-meta">' +
    '<div class="asker-meta">' +
    '<span class="qa-message-what"></span>' +
    '<span class="qa-message-when">' +
    '<span class="qa-message-when-data">' + data + '</span>' +
    '</span>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '<div class="qa-message-content">' +
    '<b>Situação: </b>' + situacao +
    '<br>' + comentario +
    '</div>' +
    '</div></div>';
}

function create_carousel_item(description, src, funcionario, situacao, data, active) {

    return '<div class="carousel-item ' + active + '">' +
    '<img class="d-block w-100" src="' + src +'" alt="Evidência">' +
    '<div class="carousel-caption d-none d-lg-block">' +
    '<p>' + description + '</p>' +
    '<p>Funcionário: ' + funcionario + '</p>' +
    '<p>Situação: ' + situacao + '</p>' +
    '<p>Data: ' + data + '</p>' +
    '</div>' +
    '</div>';
}


//Função que realiza o envio de dados via ajax para o servidor, solicitando a inserção de um novo histórico
send_historico = (imagem) => 
{
  //pre_loader_show();
  const formData = new FormData();

  formData.append('comentario', $('#historico_comentario').val());
  formData.append('historico_pk', parseInt($('#historico_pk').val()));
  formData.append('situacao_fk', parseInt($('#situacao_pk_historico').val()));
  formData.append('servico_fk', parseInt(ordens_servico[posicao_selecionada]['servico_pk']));
  formData.append('ordem_servico_fk', parseInt(ordens_servico[posicao_selecionada]['ordem_servico_pk']));
  formData.append('img', imagem);

  var URL = base_url + '/ordem_servico/new_historico_os';
  $.ajax({
    url: URL,
    method: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response){
        btn_ativar($('#btn-salvar-historico'));
        btn_ativar($('#btn-salvar-atividade'));
        
        console.log(response);
        if (response.code == 400) {
            show_errors(response);
            alerts('failed', response.data, 'O formulário apresenta algum(ns) erro(s)');
            //pre_loader_hide();
        } else if(response.code == 401){
            show_errors(response);
            alerts('failed', response.data, 'Acesso não autorizado');
            //pre_loader_hide();
        } else if(response.code == 403){
            show_errors(response);
            alerts('failed', response.data, 'Acesso proíbido');
            //pre_loader_hide();
        } else if(response.code == 404){
            show_errors(response);
            alerts('failed', response.data, 'Dados não encontrado');
            //pre_loader_hide();
        } else if(response.code == 501){
            show_errors(response);
            alerts('failed', response.data, 'Erro na edição de dados');
            //pre_loader_hide();
        } else if(response.code == 503){
            show_errors(response);
            alerts('failed', response.data, 'Erro na edição de dados');
            //pre_loader_hide();
        }
        else if(response.code == 200)
        {
            for (var i in ordens_servico) 
            {
                if (ordens_servico[i]['ordem_servico_pk'] == $('#ordem_servico_pk').val()){
                    break;
                }
            }

            let endereco =  ordens_servico[i]['logradouro_nome'] + ", " + ordens_servico[i]['local_num'] + " - " + ordens_servico[i]['bairro_nome'];

            table.row(i).data([
              ordens_servico[i]['ordem_servico_cod'],
              ordens_servico[i]['data_criacao'],
              ordens_servico[i]['prioridade_nome'],
              endereco,
              ordens_servico[i]['servico_nome'],
              $('#situacao_pk_historico option:selected').text(),
              ordens_servico[i]['setor_nome'],
              '<div class="btn-group">  <button type="button" class="btn btn-sm btn-success btn_atividade" data-toggle="modal" value="<?=$key?>" data-target="#atividade" title="Adicionar Situação"><div class="d-none d-sm-block"><i class="fas fa-plus fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="'+ (i) +'" data-target="#ce_ordem_servico" title="Editar">' +
              '<div class="d-none d-sm-block"><i class="fas fa-edit fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-secondary reset_multistep btn_historico" data-toggle="modal" value="'+ (i) +'" data-target="#ce_historico_servico" title="Histórico">' +
              '<div class="d-none d-sm-block"><i class="far fa-clock fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-danger btn-excluir" data-toggle="modal" value="'+(i)+'" data-target="#d_servico" title="Desativar">' +
              '<div class="d-none d-sm-block"><i class="fas fa-times fa-fw"></i></div></button></div>'
              ]).draw();
            //pre_loader_hide();
            remove_image();
   
            $('#atividade').modal('hide');
            $('.modal-backdrop').hide();
            
            alerts('success', "Sucesso!", response.data.mensagem); 

        }

            }, //Fecha success
            error: function (response) {
                btn_ativar($('#btn-salvar-historico'));
                
                alerts('failed', "Erro!", response.data.mensagem);
                remove_image();

                
                $('#atividade').modal('hide');
                $('.modal-backdrop').hide();
            }
    }); // Fecha AJAX
}

function get_abreviacao(servico, tipo_servico) {
    var abreviacao;

    for (var i = 0; i < tipos_servico.length; i++) {
        if (tipos_servico[i].tipo_servico_pk == tipo_servico) {
            abreviacao = tipos_servico[i].tipo_servico_abreviacao;
            break;
        }
    }

    for (var i = 0; i < servicos.length; i++) {
        if (servicos[i].servico_pk == servico) {
            abreviacao += servicos[i].servico_abreviacao;
            break;
        }
    }

    return abreviacao + "-";
}

//Função que realiza o envio de dados via ajax para o servidor, solicitando a inserção de uma nova ordem ou do update de ordem
send = (imagem) => 
{

  //pre_loader_show();
  const formData = new FormData();
  if(is_superusuario){
    formData.append('senha', $("#senha").val());
}

  // console.log( $('#servico_pk option:selected').val());
  formData.append('descricao', $('#ordem_servico_desc').val());
  formData.append('servico_fk', $('#servico_pk option:selected').val());
  formData.append('prioridade_fk', $('#prioridade_pk').val());
  formData.append('situacao_fk', $('#situacao_pk').val());
  formData.append('estado_pk', $('#uf-input option:selected').text());
  formData.append('municipio_pk', $('#cidade-input').val());
  formData.append('logradouro_nome', $('#logradouro-input').val());
  formData.append('local_num', $('#numero-input').val());
  formData.append('ponto_referencia', $('#referencia-input').val());
  formData.append('complemento', $('#complemento-input').val());
  formData.append('bairro', $('#bairro-input').val());
  formData.append('latitude', $('#latitude').val());
  formData.append('longitude', $('#longitude').val());
  formData.append('procedencia', $('#procedencia_pk').val());
  formData.append('setor', $('#setor_pk').val());
  formData.append('abreviacao', get_abreviacao($('#servico_pk option:selected').val(), $('#tipo_servico option:selected').val()));
  formData.append('img', imagem);


// console.log(formData);
  // procedencia
  var URL = ($('#ordem_servico_pk').val() == "") ? base_url + '/ordem_servico/insert' : base_url + '/ordem_servico/update_os';
  var ab, data_criacao; 
  if ($('#ordem_servico_pk').val() != "") {
      formData.append('ordem_servico_pk', $('#ordem_servico_pk').val());

  }   

  $.ajax({
      url: URL,
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response){
        btn_ativar($('.submit'));
        console.log(response);
        if (response.code == 400) {
          show_errors(response);
          alerts('failed', response.message, JSON.stringify(response.data));
          //pre_loader_hide();
      } else if(response.code == 401){
          show_errors(response);
          alerts('failed', response.message, 'Acesso não autorizado');
          //pre_loader_hide();
      } else if(response.code == 403){
          show_errors(response);
          alerts('failed', response.message, 'Acesso proíbido');
          //pre_loader_hide();
      } else if(response.code == 404){
          show_errors(response);
          alerts('failed', response.message, 'Dados não encontrado');
          //pre_loader_hide();
      } else if(response.code == 501){
          show_errors(response);
          alerts('failed', response.message, 'Erro na edição de dados');
          //pre_loader_hide();
      } else if(response.code == 503){
          show_errors(response);
          alerts('failed', response.message, 'Erro na edição de dados');
          //pre_loader_hide();
      }
      else if(response.code == 200)
      { 
        console.log(response);
         
         if ($('#ordem_servico_pk').val() != ""){
            cod = ordens_servico[posicao_selecionada]['ordem_servico_cod'];
            data_criacao = ordens_servico[posicao_selecionada]['data_criacao'];
         }else{
            cod = response.data.ordem_servico_cod;
            data_criacao = response.data.data_criacao;
         } 

         console.log(cod);

        os =
        {
          'ordem_servico_cod': cod,
          'data_criacao' : data_criacao,
          'endereco' : response.data.endereco_os,
          'coordenada_lat' :  $('#latitude').val(),
          'coordenada_long' : $('#longitude').val(),
          'historico_ordem_pk' : response.data.historico_ordem_pk,
          'local_fk' : response.data.local_pk,
          'ordem_servico_desc' : $('#ordem_servico_desc').val(),
          'ordem_servico_pk' : ($('#ordem_servico_pk').val() == "") ? response.data.ordem_servico_pk : $('#ordem_servico_pk').val(),
          'ordem_servico_status': 1,
          'prioridade_nome' : $('#prioridade_pk option:selected').text(),
          'prioridade_pk' :  $('#prioridade_pk').val(),
          'procedencia_nome' : $('#procedencia_pk option:selected').text(),
          'procedencia_pk' : $('#procedencia_pk').val(),
          'servico_nome' : $('#servico_pk option:selected').text(),
          'servico_pk' : $('#servico_pk').val(),
          'situacao_nome' : $('#situacao_pk option:selected').text(),
          'situacao_inicial_pk' : $('#situacao_pk').val(),
          'situacao_atual_pk' : $('#situacao_pk').val(),
          'tipo_servico_fk' : $('#tipo_servico').val(),
          'setor_nome':$('#setor_pk option:selected').text(),
          'setor_pk':$('#setor_pk').val()
      }

      if ($('#ordem_servico_pk').val() == "") 
              { //verifica se é um insert

                 ordens_servico.push(os);
                 table.row.add([
                  os.ordem_servico_cod,
                  os.data_criacao,
                  os.prioridade_nome,
                  os.endereco,
                  os.servico_nome,
                  os.situacao_nome,
                  os.setor_nome,
                  '<div class="btn-group"><button type="button" class="btn btn-sm btn-success btn_atividade" data-toggle="modal" value="<?=$key?>" data-target="#atividade" title="Adicionar Situação"><div class="d-none d-sm-block"><i class="fas fa-plus fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="'+ (ordens_servico.length - 1) +'" data-target="#ce_ordem_servico" title="Editar">' +
                  '<div class="d-none d-sm-block"><i class="fas fa-edit fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-secondary reset_multistep btn_historico" data-toggle="modal" value="'+ (ordens_servico.length - 1) +'" data-target="#ce_servico" title="Histórico">' +
                  '<div class="d-none d-sm-block"><i class="far fa-clock fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-danger btn-excluir" data-toggle="modal" value="'+(ordens_servico.length - 1)+'" data-target="#d_servico" title="Desativar">' +
                  '<div class="d-none d-sm-block"><i class="fas fa-times fa-fw"></i></div></button></div>'
                  ]).draw(false);
                 alerts('success', 'Sucesso', response.data.mensagem);
                 remove_image();
             } 
             else 
             {
                for (var i in ordens_servico) 
                {

                  if (ordens_servico[i]['ordem_servico_pk'] == $('#ordem_servico_pk').val()){
                      os['situacao_atual_pk'] = ordens_servico[i]['situacao_atual_pk'];
                      break;
                  }
              }
              ordens_servico[i] = (os);
              table.row(i).data([
                os.ordem_servico_cod,
                os.data_criacao,
                os.prioridade_nome,
                os.endereco,
                os.servico_nome,
                os.situacao_nome,
                os.setor_nome,
                '<div class="btn-group"><button type="button" class="btn btn-sm btn-success btn_atividade" data-toggle="modal" value="<?=$key?>" data-target="#atividade" title="Adicionar Situação"><div class="d-none d-sm-block"><i class="fas fa-plus fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="'+ (i) +'" data-target="#ce_ordem_servico" title="Editar">' +
                '<div class="d-none d-sm-block"><i class="fas fa-edit fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-secondary reset_multistep btn_historico" data-toggle="modal" value="'+ (i) +'" data-target="#ce_servico" title="Histórico">' +
                '<div class="d-none d-sm-block"><i class="far fa-clock fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-danger btn-excluir" data-toggle="modal" value="'+(i)+'" data-target="#d_servico" title="Desativar">' +
                '<div class="d-none d-sm-block"><i class="fas fa-times fa-fw"></i></div></button></div>'
                ]).draw();
              alerts('success', 'Sucesso', response.data.mensagem); 

          }
          //pre_loader_hide();
          remove_image();
          $('#ce_ordem_servico').modal('hide');
          primeiro_editar = false; 


      }

      }, //Fecha success
      error: function (response) {
          alerts('failed', response.data.message, response.data.join(' , '));
          remove_image();
          $('#ce_historico_servico').modal('hide');
      }
  }); // Fecha AJAX
} //Fecha Send


    //Função que aguarda o usuário clicar o botão nova ordem e abre o modal
    $('#btn-nova-ordem').on('click', function () 
    {
      primeiro_editar = true;
      $("#image-upload-div").show();
      $('#logradouro-input').removeClass('loading');  
      $("#bairro-input").removeClass('loading');
      $('#titulo').html("Nova ordem de serviço");
      muda_depto();
      $('#ce_ordem_servico').modal('show');
      $('#ordem_servico_pk').val("");
  });




    //Função que aguarda o clique no botão editar e preenche os campos do modal
    $(document).on('click', '.btn_editar', function (event) 
    {
        document.getElementById("ce_ordem_servico").focus();

        $('.submit').attr('disabled', 'disabled');
        $('.submit').css('cursor', 'default');
        $('.close').attr('disabled', 'disabled');
        $('.close').css('cursor', 'default');
        $('#fechar-historico').attr('disabled', 'disabled');
        $('#fechar-historico').css('cursor', 'default');



        primeiro_editar = true;

        $('#titulo').val("Alterar dados ordem de serviço");

        $('#ordem_servico_pk').val(ordens_servico[$(this).val()]['ordem_servico_pk']);
        posicao_selecionada = $(this).val();

        // console.log("PK:" + ordens_servico[$(this).val()]['ordem_servico_pk']);

        var data = get_departamento_and_tiposervico(ordens_servico[posicao_selecionada]['tipo_servico_fk'])//Aqui eu vou fazer uma função que vai requisitar percorrer departamentos e encontrar o fk
        var servico_selecionado_pk = ordens_servico[posicao_selecionada]['servico_pk'];

        $('#ordem_servico_pk').val(parseInt(ordens_servico[posicao_selecionada]['ordem_servico_pk']));
        $('#ordem_servico_desc').val(ordens_servico[posicao_selecionada]['ordem_servico_desc']);
        $('#departamento').val(data.departamento_pk);
        add_options_tipo_servico();
        $('#tipo_servico').val(ordens_servico[posicao_selecionada]['tipo_servico_fk']);
        add_options_servico();
        $('#servico_pk').val(servico_selecionado_pk);
        $('#situacao_pk').val(parseInt(ordens_servico[posicao_selecionada]['situacao_inicial_pk']));
        $('#prioridade_pk').val(parseInt(ordens_servico[posicao_selecionada]['prioridade_pk']));
        $('#procedencia_pk').val(parseInt(ordens_servico[posicao_selecionada]['procedencia_pk']));
        $('#setor_pk').val(parseInt(ordens_servico[posicao_selecionada]['setor_pk']));
        $("#latitude").val(ordens_servico[posicao_selecionada]['coordenada_lat']);
        $("#longitude").val(ordens_servico[posicao_selecionada]['coordenada_long']);
        $("#image-upload-div").hide();

        var data_local;
        var local = "";

        // console.log("Local:" + " " + ordens_servico[posicao_selecionada]['local_fk']);

        $.get(
            base_url + '/ordem_servico/local', 
            {local_pk : ordens_servico[posicao_selecionada]['local_fk']}) 
        .done(function(response){
            if(response.code == 200){
                data_local = response;
                console.log(data_local);
                $("#bairro-input").val(data_local.data.bairro_nome);
                

                $("#logradouro-input").val(data_local.data.logradouro_nome);   

                $("#numero-input").val(data_local.data.local_num);    
                $("#uf-input option:selected").text(data_local.data.estado_nome);
                $("#complemento-input").val(data_local.data.local_complemento);
                $("#referencia-input").val(data_local.data.local_referencia); //Valorando agora a referencia também
                $("#cidade-input option:selected").text(data_local.data.municipio_nome);
                
                // local = $("#cidade-input option:selected").text() + " ";
                // local += $("#logradouro-input").val() + " ";
                // local += $("#numero-input").val() + " ";
                // local += $("#bairro-input").val();

                var latlng = {lat: parseFloat(ordens_servico[posicao_selecionada]['coordenada_lat']), lng: parseFloat(ordens_servico[posicao_selecionada]['coordenada_long'])}
                populaLatLong(latlng);
                main_map.setCenter(latlng);
                criarMarcacao(latlng);

                $("#logradouro-input").removeClass('loading');
                $("#bairro-input").removeClass('loading');
                $('#ce_ordem_servico').modal('show');
                $('.submit').removeAttr('disabled');
                $('.submit').css('cursor', 'pointer');
                $('.close').removeAttr('disabled');
                $('.close').css('cursor', 'pointer');
                $('#fechar-historico').removeAttr('disabled');
                $('#fechar-historico').css('cursor', 'pointer');
                // primeiro_editar = true;


            }
            else
            {
                show_errors(response);
                alerts('failed', response.message, 'Não foi possível obter o local');
                //pre_loader_hide();
            }
        }); 
    });

    $( "#close-modal" ).click(function() {
        $("#logradouro-input").addClass('loading');
        $("#bairro-input").addClass('loading');
        primeiro_editar = true;
    });


    $('#ce_ordem_servico').on('hide.bs.modal', function (event) {
        $("#tipo_servico option").remove();
        $("#servico_pk option").remove();
        primeiro_editar = false; 
    });

    //Função que pega a posição selecionada na datatable para exclusão
    $(document).on('click', '.btn-excluir', function (event) 
    {
        $('#ordem_servico_pk').val(ordens_servico[$(this).val()]['ordem_servico_pk']);
        posicao_selecionada = $(this).val();
    });

    //Função que pega a posição selecionada na datatable para ativação
    $(document).on('click', '.btn-ativar', function (event) 
    {
        $('#ordem_servico_pk').val(ordens_servico[$(this).val()]['ordem_servico_pk']);
        posicao_selecionada = $(this).val();
    });


    //Função que envia uma requisição ajax com os dados para desativar uma ordem
    $(document).on('click', '#btn-desativar', function (event){
        var data;
        btn_load($('#btn-desativar'));
        if(is_superusuario){
         data = 
         {
            'ordem_servico_pk': $('#ordem_servico_pk').val(),
            'senha': $('#pass-modal-desativar').val()
        }
    }   
    else{
        data = 
        {
            'ordem_servico_pk': $('#ordem_servico_pk').val(),
        }
    }

    $.post(base_url+'/ordem_servico/deactivate',data).done(function (response) {
        btn_ativar($('#btn-desativar'));
        if (response.code == 200){
          alerts('success', 'Sucesso!', 'Ordem de Serviço desativada com sucesso');
          ordens_servico[posicao_selecionada]['ordem_servico_status'] = 0;

          
      }else{
          alerts('failed', 'Erro!', 'Houve um erro ao desativar.');
      }
      update_table();
      $('#d_servico').modal('hide');
  });
});

    $(document).on('click', '#btn-reativar', function (event){
        var data;
        btn_load($('#btn-reativar'));
        if(is_superusuario){
         data = 
         {
            'ordem_servico_pk': $('#ordem_servico_pk').val(),
            'senha': $('#pass-modal-reativar').val()
        }
    }   
    else
    {
        data = 
        {
            'ordem_servico_pk': $('#ordem_servico_pk').val(),
        }
    }

    $.post(base_url+'/ordem_servico/activate',data).done(function (response) {
        btn_ativar($('#btn-reativar'));
        if (response.code == 200){
          alerts('success', 'Sucesso!', 'Ordem de Serviço ativada com sucesso');
          ordens_servico[posicao_selecionada]['ordem_servico_status'] = 1;
      }else{
          alerts('failed', 'Erro!', 'Houve um erro ao ativar.');
      }
      update_table();
      $('#r_servico').modal('hide');
  });
});

    $('#filter-ativo').on('change',function() {
      update_table();
  });


    //Função que atualiza a datatable com dados modificados pelo usuário em tempo real
    update_table = () => 
    {
      table.clear().draw();

      switch ($('#filter-ativo').val()) 
      {

        case "todos":
        $.each(ordens_servico, function (i, os) {

            let endereco = os.logradouro_nome+ ", " + os.local_num + " - " + os.bairro_nome; 

            if(os.ordem_servico_status == 1){
                table.row.add([
                    os.ordem_servico_cod,
                    os.data_criacao,
                    os.prioridade_nome,
                    endereco,
                    os.servico_nome,
                    os.situacao_nome,
                    os.setor_nome,
                    '<div class="btn-group"><button type="button" class="btn btn-sm btn-success btn_atividade" data-toggle="modal" value="<?=$key?>" data-target="#atividade" title="Adicionar Situação"><div class="d-none d-sm-block"><i class="fas fa-plus fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="'+ (i) +'" data-target="#ce_ordem_servico" title="Editar">' +
                    '<div class="d-none d-sm-block"><i class="fas fa-edit fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-secondary reset_multistep btn_historico" data-toggle="modal" value="'+ (i) +'" data-target="#ce_historico_servico" title="Histórico">' +
                    '<div class="d-none d-sm-block"><i class="far fa-clock fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-danger btn-excluir" data-toggle="modal" value="'+(i)+'" data-target="#d_servico" title="Desativar">' +
                    '<div class="d-none d-sm-block"><i class="fas fa-times fa-fw"></i></div></button></div>'
                    ]).draw(false);
            }else{
                table.row.add([
                    os.ordem_servico_cod,
                    os.data_criacao,
                    os.prioridade_nome,
                    endereco,
                    os.servico_nome,
                    os.situacao_nome,
                    os.setor_nome,
                    '<div class="btn-group"><button type="button" disabled style="cursor:auto;" class="btn btn-sm btn-success btn_atividade" data-toggle="modal" value="<?=$key?>" data-target="#atividade" title="Adicionar Situação"><div class="d-none d-sm-block"><i class="fas fa-plus fa-fw"></i></div></button><button disabled style="cursor:auto;" type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="'+ (i) +'" data-target="#ce_ordem_servico" title="Editar">' +
                    '<div class="d-none d-sm-block"><i class="fas fa-edit fa-fw"></i></div></button><button disabled  style="cursor:auto;" type="button" class="btn btn-sm btn-secondary reset_multistep btn_historico" data-toggle="modal" value="'+ (i) +'" data-target="#ce_historico_servico" title="Histórico">' +
                    '<div class="d-none d-sm-block"><i class="far fa-clock fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-success btn-ativar" data-toggle="modal" value="'+(i)+'" data-target="#r_servico" title="Reativar">' +
                    '<div class="d-none d-sm-block"><i class="fas fa-power-off fa-fw"></i></div></button></div>'
                    ]).draw(false);
            }
        });
        break;

        case "ativadas":
        table.clear().draw();
        $.each(ordens_servico, function (i, os) {

            let endereco = os.logradouro_nome+ ", " + os.local_num + " - " + os.bairro_nome; 

            if(os.ordem_servico_status == 1) {
                table.row.add([
                    os.ordem_servico_cod,
                    os.data_criacao,
                    os.prioridade_nome,
                    endereco,
                    os.servico_nome,
                    os.situacao_nome,
                    os.setor_nome,
                    '<div class="btn-group"><button type="button" class="btn btn-sm btn-success btn_atividade" data-toggle="modal" value="<?=$key?>" data-target="#atividade" title="Adicionar Situação"><div class="d-none d-sm-block"><i class="fas fa-plus fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="'+ (i) +'" data-target="#ce_ordem_servico" title="Editar">' +
                    '<div class="d-none d-sm-block"><i class="fas fa-edit fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-secondary reset_multistep btn_historico" data-toggle="modal" value="'+ (i) +'" data-target="#ce_historico_servico" title="Histórico">' +
                    '<div class="d-none d-sm-block"><i class="far fa-clock fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-danger btn-excluir" data-toggle="modal" value="'+(i)+'" data-target="#d_servico" title="Desativar">' +
                    '<div class="d-none d-sm-block"><i class="fas fa-times fa-fw"></i></div></button></div>'
                    ]).draw(false);
            }
        });
        break;

        case "desativadas":
        table.clear().draw();
        $.each(ordens_servico, function (i, os) {

            let endereco = os.logradouro_nome+ ", " + os.local_num + " - " + os.bairro_nome; 

            if(os.ordem_servico_status == 0) {
                table.row.add([
                    os.ordem_servico_cod,
                    os.data_criacao,
                    os.prioridade_nome,
                    endereco,
                    os.servico_nome,
                    os.situacao_nome,
                    os.setor_nome,
                    '<div class="btn-group"><button type="button" disabled style="cursor:auto;" class="btn btn-sm btn-success btn_atividade" data-toggle="modal" value="<?=$key?>" data-target="#atividade" title="Adicionar Situação"><div class="d-none d-sm-block"><i class="fas fa-plus fa-fw"></i></div></button><button disabled type="button" style="cursor:auto;" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="'+ (i) +'" data-target="#ce_ordem_servico" title="Editar">' +
                    '<div class="d-none d-sm-block"><i class="fas fa-edit fa-fw"></i></div></button><button disabled type="button" style="cursor:auto;" class="btn btn-sm btn-secondary reset_multistep btn_historico" data-toggle="modal" value="'+ (i) +'" data-target="#ce_historico_servico" title="Histórico">' +
                    '<div class="d-none d-sm-block"><i class="far fa-clock fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-success btn-ativar" data-toggle="modal" value="'+(i)+'" data-target="#r_servico" title="Reativar">' +
                    '<div class="d-none d-sm-block"><i class="fas fa-power-off fa-fw"></i></div></button></div>'
                    ]).draw(false);
            }
        });
        break;

        case "finalizadas":
        table.clear().draw();
        $.each(ordens_servico, function (i, os) {

            let endereco = os.logradouro_nome+ ", " + os.local_num + " - " + os.bairro_nome; 

            if(os.situacao_nome === "Finalizado") {
                table.row.add([
                    os.ordem_servico_cod,
                    os.data_criacao,
                    os.prioridade_nome,
                    endereco,
                    os.servico_nome,
                    os.situacao_nome,
                    os.setor_nome,
                    '<div class="btn-group"><button type="button" class="btn btn-sm btn-success btn_atividade" data-toggle="modal" value="<?=$key?>" data-target="#atividade" title="Adicionar Situação"><div class="d-none d-sm-block"><i class="fas fa-plus fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="'+ (i) +'" data-target="#ce_ordem_servico" title="Editar">' +
                    '<div class="d-none d-sm-block"><i class="fas fa-edit fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-secondary reset_multistep btn_historico" data-toggle="modal" value="'+ (i) +'" data-target="#ce_historico_servico" title="Histórico">' +
                    '<div class="d-none d-sm-block"><i class="far fa-clock fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-danger btn-ativar" data-toggle="modal" value="'+(i)+'" data-target="#d_servico" title="Desativar">' +
                    '<div class="d-none d-sm-block"><i class="fas fa-times fa-fw"></i></div></button></div>'
                    ]).draw(false);
            }
        });
        break;

        case "abertas":
        table.clear().draw();
        $.each(ordens_servico, function (i, os) {

            let endereco = os.logradouro_nome+ ", " + os.local_num + " - " + os.bairro_nome; 

            if(os.situacao_nome !== "Finalizado") {
                table.row.add([
                    os.ordem_servico_cod,
                    os.data_criacao,
                    os.prioridade_nome,
                    endereco,
                    os.servico_nome,
                    os.situacao_nome,
                    os.setor_nome,
                    '<div class="btn-group"><button type="button" class="btn btn-sm btn-success btn_atividade" data-toggle="modal" value="<?=$key?>" data-target="#atividade" title="Adicionar Situação"><div class="d-none d-sm-block"><i class="fas fa-plus fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="'+ (i) +'" data-target="#ce_ordem_servico" title="Editar">' +
                    '<div class="d-none d-sm-block"><i class="fas fa-edit fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-secondary reset_multistep btn_historico" data-toggle="modal" value="'+ (i) +'" data-target="#ce_historico_servico" title="Histórico">' +
                    '<div class="d-none d-sm-block"><i class="far fa-clock fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-danger btn-ativar" data-toggle="modal" value="'+(i)+'" data-target="#d_servico" title="Desativar">' +
                    '<div class="d-none d-sm-block"><i class="fas fa-times fa-fw"></i></div></button></div>'
                    ]).draw(false);
            }
        });
        break;
    }
}

}



