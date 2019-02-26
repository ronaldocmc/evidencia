function btn_load(button_submit) {
    button_submit.attr('disabled', 'disabled');
    button_submit.css('cursor', 'default');
    button_submit.find('i').removeClass();
    button_submit.find('i').addClass('fa fa-refresh fa-spin');
}


function btn_ativar(button_submit) {
    button_submit.removeAttr('disabled');
    button_submit.css('cursor', 'pointer');
    button_submit.find('i').removeClass();
    button_submit.find('i').addClass('fa fa-dot-circle-o');
}



//Variáveis globais utilizadas no JS
var main_map, other_map;
var main_marker = null;
var other_marker = null;
var other_maker_from_activity = null;
var posicao_selecionada = null;
var primeiro_editar = false;
var adicionar_imagem_historico = 1;
var adicionar_mapa_historico = 1;

// var adicionar_imagem_situacao= 1; 
// var adicionar_mapa_situacao = 1; 
//-----------------------------------//

//Função que aguarda a mudança de departamento. Caso o usuário altere o departamento, é necessário atualizar outros campos, operação
//que é feita pela função muda_dpto();
$("#departamento").change(function () {
    if (primeiro_editar == true) { //atualizando a flag de controle de operação 
        muda_depto();
    }
}).change();

//Função que aguarda a mudança de tipo de serviço. Caso o usuário altere o tipo de serviço, é necessário atualizar o campo serviços e situação
//, operação que é feita pela função muda_tipo_servico();    
$("#tipo_servico").change(function () {
    if (primeiro_editar == true) {
        muda_tipo_servico();
    }
}).change();

//Função que aguarda a mudança de situação. Caso o usuário altere a situação, é necessário verificar se para aquele serviço/situação
//a foto não é obrigatória, caso seja atribuimos o required
$("#situacao_pk").change(function () {
    var situacao = $("#situacao_pk option:selected").val();

    for (var i = 0; i < situacoes.length; i++) {
        if (situacoes[i].situacao_pk == situacao) {

            if (situacoes[i].situacao_foto_obrigatoria == 1) {
                $("#input-upload").attr('required', true);
                $("#img-input").attr('required', true);
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
$("#procedencia_pk").change(function () {

    var procedencia_selected = $('#procedencia_pk option:selected').val();
    for (var i = 0; i < procedencias.length; i++) {
        if (procedencias[i].procedencia_pk == procedencia_selected) {
            $('#procedencias_options #procedencia_small').text(procedencias[i].procedencia_desc);
            if (procedencia_selected == 2) {
                $('#info_cidadao').show();
                $('#nome-input').attr("required", "true");
            } else {
                $('#nome-input').attr("required", "false");
                $('#info_cidadao').hide();
            }
        }

    }

}).change();

//Função que adiciona as opções de tipos de serviço, conforme o departamento selecionado pelo usuário
function add_options_tipo_servico() {
    var depto = $("#departamento option:selected").val();

    for (var i = 0; i < tipos_servico.length; i++) {
        if (tipos_servico[i].departamento_fk == depto) {
            $("#tipo_servico")
                .append('<option value="' + parseInt(tipos_servico[i].tipo_servico_pk) + '">' + tipos_servico[i].tipo_servico_nome + '</option>');
        }
    }
}

//Função que adiciona as opções de serviço, conforme tipo de serviço selecionado pelo usuário
function add_options_servico() {
    var tipo_servico = $("#tipo_servico option:selected").val();

    for (var i = 0; i < servicos.length; i++) {
        if (servicos[i].tipo_servico_fk == tipo_servico) {

            $("#servico_pk").append('<option value="' + parseInt(servicos[i].servico_pk) + '">' + servicos[i].servico_nome + '</option>');
        }
    }
}

//Função que recupera a posição selecionada na datatable, isto é, qual ordem de serviço o usuário selecionou para editar, ativa ou excluir
$(document).on('click', '.btn-attr-ordem_servico_pk', function () {
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
        if (servicos[i].servico_pk == servico) {
            $("#situacao_pk").val(parseInt(servicos[i].situacao_padrao_fk));
            $("#prioridade_pk").val(parseInt(servicos[i].prioridade_padrao_fk));

            if (servicos[i].situacao_foto_obrigatoria == "1") {
                $("#input-upload").prop('required', true);
                $("#img-input").prop('required', true);
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
get_departamento_and_tiposervico = (servico_atual_pk) => {
    let data;
    let tipo_servico_atual = servicos.filter(s => (s.servico_pk == servico_atual_pk))[0];

    data =
        {
            'departamento_nome': tipo_servico_atual.departamento_nome,
            'departamento_pk': tipo_servico_atual.departamento_fk,
            'tipo_servico_pk': tipo_servico_atual.tipo_servico_pk,
            'tipo_servico_nome': tipo_servico_atual.tipo_servico_nome
        }
    return data;

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
            blobToBase64(blob, this.send_historico);
        });


    } catch (err) {
        this.send_historico(null);
    }

}

//Função que converte um blob em base64, utiliza callback porque precisa ser sincrona
var blobToBase64 = function (blob, cb) {
    var reader = new FileReader();
    reader.onload = function () {
        var base64 = reader.result;
        // var base64 = dataUrl.split(',')[1];
        cb(base64);
    };
    reader.readAsDataURL(blob);
};


send_data = () => {
    try {

        //btn_load($('#pula-para-confirmacao'));
        // btn_load($('.submit'));f
        $('#img-input').cropper('getCroppedCanvas').toBlob((blob) => {
            blobToBase64(blob, this.send);
        });
    } catch (err) {
        this.send(null);
    }
}

//Função que inicializa o google maps na página
function initMap() {
    main_map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: -22.121265, lng: -51.383400 },
        zoom: 13
    });

    criarMarcacao({ lat: -22.121265, lng: -51.383400 });
    var geocoder = new google.maps.Geocoder();

    main_map.addListener('click', function (event) {
        var latlng = { lat: event.latLng.lat(), lng: event.latLng.lng() };
        populaLatLong(latlng);

        geocoder.geocode({ 'location': latlng }, function (results, status) {
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
        center: { lat: -22.121265, lng: -51.383400 },
        zoom: 13
    });

    var geocoder2 = new google.maps.Geocoder();
    criarMarcacao2({ lat: -22.121265, lng: -51.383400 });

    var geocoder3 = new google.maps.Geocoder();

    $('#mapa_historico').hide();

    //Função que preenhce o endereço conforme o clique do usuário do mapa
    function preencheCampos(endereco) {
        var cidade;
        var estado, estado_mudou = false;

        for (var i = 0; i < endereco.length; i++) {

            if (endereco[i].types.indexOf("sublocality") !== -1) {
                $("#bairro_pk").val(endereco[i].long_name);
            }
            else if (endereco[i].types.indexOf("street_number") !== -1) {
                $("#numero-input").val(endereco[i].long_name);
            }
            else if (endereco[i].types.indexOf("route") !== -1) {
                $("#logradouro-input").val(endereco[i].long_name);
            }
            else if (endereco[i].types.indexOf("locality") !== -1) {
                cidade = endereco[i].long_name;
            }
            else if (endereco[i].types.indexOf("administrative_area_level_1") !== -1) {
                if ($("#uf-input option:selected").text() != endereco[i].short_name) {
                    estado_mudou = true;
                    $("#uf-input option").filter(function () {
                        return this.text == endereco[i].short_name;
                    }).attr('selected', true);
                    estado = endereco[i].short_name;
                }
            }
        }

        if (estado_mudou) {
            change_uf($("#uf-input").val(), estado, cidade);
        }
        else {
            $("#cidade-input option").filter(function () {
                return this.text == cidade;
            }).attr('selected', true);
        }
    }

    function populaLatLong(location) {
        $("#latitude").val(location.lat);
        $("#longitude").val(location.lng);
    }

    function criarMarcacao2(location) {
        if (other_marker != null) {
            other_marker.setMap(null);
        }

        other_marker = new google.maps.Marker({
            position: location,
            map: other_map
        });
    }

    function criarMarcacao(location) {
        if (main_marker != null) {
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
    $(".referencia").focusout(function () {
        var local = "";

        //Se o logradouro não foi preenchido então buscamos pelo ponto de referência. 
        if ($('#logradouro-input').val() == "") {

            local = $("#cidade-input option:selected").text() + " ";
            local += $("#referencia-input").val() + " ";
            local += $("#numero-input").val() + " ";
            local += $("#bairro-input").val();


            geocoder.geocode({ 'address': local }, function (results, status) {
                if (status === 'OK') {
                    main_map.setCenter(results[0].geometry.location);
                    criarMarcacao(results[0].geometry.location);

                    // console.log(results[0].address_components);

                    for (i = 0; i < results[0].address_components.length; i++) {

                        switch (results[0].address_components[i].types[0]) {
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
                    var latlng = { lat: results[0].geometry.location.lat(), lng: results[0].geometry.location.lng() }
                    populaLatLong(latlng);
                } else {
                    console.log('Geocode was not successful for the following reason: ' + status);
                }
            });
        }
    });


    //Função que recupera o local inserido pelo usuário e indica ele no mapa
    $(".endereco").focusout(function () {
        var local = "";

        local = $("#cidade-input option:selected").text() + " ";
        local += $("#logradouro-input").val() + " ";
        local += $("#numero-input").val() + " ";
        local += $("#bairro-input").val();

        geocoder.geocode({ 'address': local }, function (results, status) {
            if (status === 'OK') {
                main_map.setCenter(results[0].geometry.location);
                criarMarcacao(results[0].geometry.location);
                var latlng = { lat: results[0].geometry.location.lat(), lng: results[0].geometry.location.lng() }
                populaLatLong(latlng);
            } else {
                console.log('Geocode was not successful for the following reason: ' + status);
            }
        });
    });


    function remove_data() {
        $("#v_descricao").html('');
        $("#v_prioridade").html('');
        $("#v_procedencia").html('');
        $("#v_setor").html('');
        $("#v_servico").html('');
        $('#v_codigo').html('');

        $('#card_slider_historico').html('');
        $('#card_slider_ordem').html('');
        $('#timeline').html('');
        $('#v_loading').show();
    }

    function remove_data_atividade() {
        $('#otimeline').html('');
        $('#ov_loading').show();
    }


    //Funções que estendem a visualização do mapa e da imagem no registro histórico
    $(document).on('click', '#btn-mapa-historico', function (event) {

        if (adicionar_mapa_historico == 1) {

            $('#mapa_historico').show();
            $('#btn-mapa-historico').removeClass('btn-primary');
            $('#btn-mapa-historico').addClass('btn-danger');
            adicionar_mapa_historico++;

        }
        else {
            $('#mapa_historico').hide();
            $('#btn-mapa-historico').removeClass('btn-danger');
            $('#btn-mapa-historico').addClass('btn-primary');
            adicionar_mapa_historico--;

        }
    });

    // $(document).on('click', '#btn-foto-historico', function (event) 
    // {
    //     if(adicionar_imagem_historico == 1){

    //         $('#card_slider_historico').show();
    //         $('#btn-foto-historico').html('<i class="fa fa-camera" aria-hidden="true"></i>');
    //         $('#btn-foto-historico').removeClass('btn-primary');
    //         $('#btn-foto-historico').addClass('btn-danger');
    //         adicionar_imagem_historico++;
    //     }
    //     else
    //     {
    //         $('#card_slider_historico').hide();
    //         $('#btn-foto-historico').html('<i class="fa fa-camera" aria-hidden="true"></i>');
    //         $('#btn-foto-historico').removeClass('btn-danger');
    //         $('#btn-foto-historico').addClass('btn-primary'); 
    //         adicionar_imagem_historico--;

    //     }
    // });


    //Função que  preenche os campos do "Editar Histórico"
    $(document).on('click', '.btn_historico', function (event) {
        document.getElementById("ce_historico_servico").focus();
        var imagem;
        var local_ordem = '';
        remove_data();

        $("#v_descricao").html(ordens_servico[posicao_selecionada]['ordem_servico_desc']);
        $("#v_prioridade").html(ordens_servico[posicao_selecionada]['prioridade_nome']);
        $("#v_procedencia").html(ordens_servico[posicao_selecionada]['procedencia_nome']);
        $("#v_setor").html(ordens_servico[posicao_selecionada]['setor_nome']);
        $("#v_servico").html(ordens_servico[posicao_selecionada]['servico_nome']);
        $("#v_codigo").html(ordens_servico[posicao_selecionada]['ordem_servico_cod']);

        var latlng = { lat: parseFloat(ordens_servico[posicao_selecionada]['coordenada_lat']), lng: parseFloat(ordens_servico[posicao_selecionada]['coordenada_long']) }

        geocoder2.geocode({ 'location': latlng }, function (results, status) {
            if (status === 'OK') {
                other_map.setCenter(results[0].geometry.location);
                criarMarcacao2(results[0].geometry.location);

                let numero, rua, bairro, cidade, estado;
                for (i = 0; i < results[0].address_components.length; i++) {

                    switch (results[0].address_components[i].types[0]) {
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
    $(document).on('click', '.btn_atividade', function (event) {
        document.getElementById("atividade").focus();
        var imagem;
        var local_ordem = '';
        $('#ordem_servico_pk').val(ordens_servico[$(this).val()]['ordem_servico_pk']);
        posicao_selecionada = $(this).val();

        remove_data_atividade();

        var latlng = { lat: parseFloat(ordens_servico[posicao_selecionada]['coordenada_lat']), lng: parseFloat(ordens_servico[posicao_selecionada]['coordenada_long']) }

        geocoder2.geocode({ 'location': latlng }, function (results, status) {
            if (status === 'OK') {

                let numero, rua, bairro, cidade, estado;
                for (i = 0; i < results[0].address_components.length; i++) {

                    switch (results[0].address_components[i].types[0]) {
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





    get_historico = (id) => {
        $('.carousel').carousel();
        btn_load($('#btn-salvar-historico'));
        $('.close').attr('disabled', 'disabled');
        $('.close').css('cursor', 'default');
        $('#fechar-historico').attr('disabled', 'disabled');
        $('#fechar-historico').css('cursor', 'default');

        var html = "";
        var indicators = "";
        var active = " active";
        var timeline = "";

        var d = new Date();
        dataHora = (d.toLocaleString());

        $.ajax({
            url: base_url + '/Ordem_Servico/get_historico/' + id,
            dataType: "json",
            success: function (response) {

                console.log(response.data);

                btn_ativar($('#btn-salvar-historico'));
                $('.close').removeAttr('disabled');
                $('.close').css('cursor', 'pointer');
                $('#fechar-historico').removeAttr('disabled');
                $('#fechar-historico').css('cursor', 'pointer');

                if (ordens_servico[posicao_selecionada].imagens.length > 2) {
                    html += '<div id="myCarousel" class="carousel slide"data-ride="carousel">' +
                        '<div class="carousel-inner row w-100 mx-auto"></div>' +
                        '<a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">' +
                        '<span class="carousel-control-prev-icon" style="color: black; background-color: black; width: 50px; height: 50px;" aria-hidden="true"></span>' +
                        '<span class="sr-only"">Previous</span>' +
                        '</a>' +
                        '<a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">' +
                        '<span class="carousel-control-next-icon" style="color: black; background-color: black; width: 50px; height: 50px;" aria-hidden="true"></span>' +
                        '<span class="sr-only">Next</span>' +
                        '</a>' +
                        '</div>';
                } else {
                    html += '<div id="card_imagens">' +
                        '<div class="carousel-inner row w-100 mx-auto"></div>' +
                        '</div>';
                }


                $('#card_slider_historico').html(html);

                // html = "";
                if (response.data.historicos != []) {

                    response.data.historicos.map((historico, i) => {


                        timeline += create_timeline(historico.historico_ordem_comentario, historico.funcionario_caminho_foto, historico.funcionario_nome, historico.situacao_nome, reformatDate(historico.historico_ordem_tempo));

                    });
                }
                timeline += create_timeline(ordens_servico[posicao_selecionada].ordem_servico_comentario, ordens_servico[posicao_selecionada].funcionario_caminho_foto, ordens_servico[posicao_selecionada].funcionario_nome, ordens_servico[posicao_selecionada].situacao_atual_nome, reformatDate(ordens_servico[posicao_selecionada].ordem_servico_atualizacao));

                if (ordens_servico[posicao_selecionada].imagens.length > 0) {

                    ordens_servico[posicao_selecionada].imagens.map((img) => {
                        html += create_cards(img.imagem_os, img.situacao_nome, img.imagem_os_timestamp, active);
                        active = "";
                    });

                }

                $('#v_loading').hide();
                $('.carousel-inner').html(html);
                $('#card_slider_historico').show();
                $('#timeline').html(timeline);
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


    get_atividade = (id) => {
        btn_load($('#btn-salvar-atividade'));
        $('.close').attr('disabled', 'disabled');
        $('.close').css('cursor', 'default');
        $('#fechar-atividade').attr('disabled', 'disabled');
        $('#fechar-atividade').css('cursor', 'default');

        var html = "";
        var indicators = "";
        var active = " active";
        var timeline = "";

        var d = new Date();
        dataHora = (d.toLocaleString());

        $.ajax({
            url: base_url + '/Ordem_Servico/get_historico/' + id,
            dataType: "json",
            success: function (response) {
                btn_ativar($('#btn-salvar-atividade'));
                $('.close').removeAttr('disabled');
                $('.close').css('cursor', 'pointer');
                $('#fechar-atividade').removeAttr('disabled');
                $('#fechar-atividade').css('cursor', 'pointer');

                if (response.data.historicos != []) {

                    response.data.historicos.map((historico, i) => {

                        timeline += create_timeline(historico.historico_ordem_comentario, historico.funcionario_caminho_foto, historico.funcionario_nome, historico.situacao_nome, reformatDate(historico.historico_ordem_tempo));

                    });
                }

                timeline += create_timeline(ordens_servico[posicao_selecionada].ordem_servico_comentario, ordens_servico[posicao_selecionada].funcionario_caminho_foto, ordens_servico[posicao_selecionada].funcionario_nome, ordens_servico[posicao_selecionada].situacao_atual_nome, reformatDate(ordens_servico[posicao_selecionada].ordem_servico_atualizacao));


                timeline +=
                    '<div class="message-item">' +
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
                    '</div>' +
                    '</div>' +
                    '<small class="form-text text-muted">Por favor, se necessário, carregue a imagem</small>' +
                    '</div>' +
                    '</div>' +
                    '</div></div>';

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

    function popula_situacoes() {
        for (var i = situacoes.length - 1; i >= 0; i--) {
            $("#situacao_pk_historico").append('<option value="' + parseInt(situacoes[i].situacao_pk) + '">' + situacoes[i].situacao_nome + '</option>');
        }
    }

    function create_timeline(comentario, src, funcionario, situacao, data) {
        return '<div class="message-item">' +
            '<div class="message-inner">' +
            '<div class="message-head clearfix">' +
            '<div class="avatar pull-left"><a href="#"><img class="message-foto-perfil" src="' + (src || base_url + '/assets/uploads/perfil_images/default.png') + '"></a></div>' +
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
            '<br>' + (comentario || "Nenhum comentário adicionado.") +
            '</div>' +
            '</div></div>';
    }

    function create_cards(src, situacao, data, active) {

        return '<div class="carousel-item' + active + ' col-md-4">' +
            '<div class="card historico">' +
            '<img class="card-img-top img-fluid" src="' + src + '">' +
            '<div class="card-body">' +
            '<h4 class="card-title">' + situacao + '</h4>' +
            '<p class="card-text"><small class="text-muted">' + reformatDate(data) + '</small></p>' +
            '</div>' +
            '</div>' +
            '</div>';
    }


    //Função que realiza o envio de dados via ajax para o servidor, solicitando a inserção de um novo histórico
    send_historico = (imagem) => {
        //pre_loader_show();
        const formData = new FormData();

        formData.append('ordem_servico_comentario', $('#historico_comentario').val());
        formData.append('situacao_atual_fk', parseInt($('#situacao_pk_historico').val()));
        formData.append('image_os', imagem);

        var URL = base_url + '/ordem_servico/insert_situacao/' + ordens_servico[posicao_selecionada]['ordem_servico_pk'];
        $.ajax({
            url: URL,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                btn_ativar($('#btn-salvar-historico'));
                btn_ativar($('#btn-salvar-atividade'));

                console.log(response);
                if (response.code == 400) {
                    show_errors(response);
                    alerts('failed', response.data, 'O formulário apresenta algum(ns) erro(s)');
                    //pre_loader_hide();
                } else if (response.code == 401) {
                    show_errors(response);
                    alerts('failed', response.data, 'Acesso não autorizado');
                    //pre_loader_hide();
                } else if (response.code == 403) {
                    show_errors(response);
                    alerts('failed', response.data, 'Acesso proíbido');
                    //pre_loader_hide();
                } else if (response.code == 404) {
                    show_errors(response);
                    alerts('failed', response.data, 'Dados não encontrado');
                    //pre_loader_hide();
                } else if (response.code == 501) {
                    show_errors(response);
                    alerts('failed', response.data, 'Erro na edição de dados');
                    //pre_loader_hide();
                } else if (response.code == 503) {
                    show_errors(response);
                    alerts('failed', response.data, 'Erro na edição de dados');
                    //pre_loader_hide();
                }
                else if (response.code == 200) {
                    alerts('success', "Sucesso!", "Histórico criado com sucesso!");
                    document.location.reload(false);

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

    //Função que realiza o envio de dados via ajax para o servidor, solicitando a inserção de uma nova ordem ou do update de ordem
    send = (imagem) => {

        //pre_loader_show();
        const formData = new FormData();

        if ($('#nome-input').val() == "" && $('#cpf-input').val() == "" && $('#email-input').val() == "" && $('#celular-input').val() == "" && $('#telefone-input').val() == "") {
            $('#procedencia_pk').val("1");
        }


        if (is_superusuario) {
            formData.append('senha', $("#senha").val());
        }

        formData.append('ordem_servico_pk', $('#ordem_servico_pk').val());
        formData.append('prioridade_fk', $('#prioridade_pk').val());
        formData.append('procedencia_fk', $('#procedencia_pk').val());
        formData.append('servico_fk', $('#servico_pk option:selected').val());
        formData.append('setor_fk', $('#setor_pk').val());
        formData.append('ordem_servico_desc', $('#ordem_servico_desc').val());
        formData.append('situacao_inicial_fk', $('#situacao_pk').val());
        formData.append('situacao_atual_fk', $('#situacao_pk').val());

        formData.append('localizacao_lat', $('#latitude').val());
        formData.append('localizacao_long', $('#longitude').val());
        formData.append('localizacao_rua', $('#logradouro-input').val());
        formData.append('localizacao_num', $('#numero-input').val());
        formData.append('localizacao_bairro', $('#bairro_pk').val());
        formData.append('localizacao_municipio', $('#cidade-input').val());
        formData.append('localizacao_ponto_referencia', $('#referencia-input').val());

        if ($('#ordem_servico_pk').val() !== '' && $('#ordem_servico_pk').val() !== undefined) {
          formData.append('localizacao_pk', $('#localizacao_pk').val());
        }

        if ($('#procedencia_pk').val() == "2") {

            formData.append('pessoa_nome', $('#nome-input').val());
            formData.append('pessoa_cpf', $('#cpf-input').val());
            formData.append('contato_email', $('#email-input').val());
            formData.append('contato_cel', $('#celular-input').val());
            formData.append('contato_tel', $('#telefone-input').val());

        }

        formData.append('img', imagem);

        // procedencia
        var URL = base_url + '/ordem_servico/save';
        var ab, data_criacao;

        $.ajax({
            url: URL,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                btn_ativar($('.submit'));

                if (response.code == 400) {
                    show_errors(response);
                    alerts('failed', response.message, JSON.stringify(response.data));
                    //pre_loader_hide();
                } else if (response.code == 401) {
                    show_errors(response);
                    alerts('failed', response.message, 'Acesso não autorizado');
                    //pre_loader_hide();
                } else if (response.code == 403) {
                    show_errors(response);
                    alerts('failed', response.message, 'Acesso proíbido');
                    //pre_loader_hide();
                } else if (response.code == 404) {
                    show_errors(response);
                    alerts('failed', response.message, 'Dados não encontrado');
                    //pre_loader_hide();
                } else if (response.code == 501) {
                    show_errors(response);
                    alerts('failed', response.message, 'Erro na edição de dados');
                    //pre_loader_hide();
                } else if (response.code == 503) {
                    show_errors(response);
                    alerts('failed', response.message, 'Erro na edição de dados');
                }
                else if (response.code == 200) {
                  alerts('success', 'Sucesso', 'Operação realizada com sucesso');
                  document.location.reload(false);
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
    $('#btn-nova-ordem').on('click', function () {
        primeiro_editar = true;
        if ($('#procedencia_pk').val() == "2") {
            $('#info_cidadao').show();
        }

        $('#logradouro-input').removeClass('loading');
        $("#bairro-input").removeClass('loading');
        $('#titulo').html("Nova ordem de serviço");
        muda_depto();
        $('#ce_ordem_servico').modal('show');
        $("#card_imagem").show();
        $('#ordem_servico_pk').val("");
    });


    //Função que gera o protocolo para o cliente 
    function create_protocol(ordem_servico_cod) {
        // let encrypt = random_string(); 
        let current_date = new Date();
        let cod_os = ordem_servico_cod.split("/");
        let ano = cod_os[0].split("-");
        let horario = "";

        if (current_date.getMinutes() < 10) {
            horario += current_date.getHours() + "0" + current_date.getMinutes();
        } else {
            horario += current_date.getHours() + "" + current_date.getMinutes();
        }

        return (organizacao.toUpperCase() + "-" + cod_os[1] + horario + "/" + ano[1]);

    }

    function random_string() {
        let text = "";

        for (let i = 0; i < 3; i++) {
            text += String.fromCharCode(Math.floor(Math.random() * (90 - 65 + 1)) + 65);
        }
        console.log(text);
        return text;
    }

    //Função que aguarda o clique no botão editar e preenche os campos do modal
    $(document).on('click', '.btn_editar', function (event) {
        document.getElementById("ce_ordem_servico").focus();

        primeiro_editar = true;

        $('#titulo').val("Alterar dados ordem de serviço");

        $('#ordem_servico_pk').val(ordens_servico[$(this).val()]['ordem_servico_pk']);
        posicao_selecionada = $(this).val();

        var data = get_departamento_and_tiposervico(ordens_servico[posicao_selecionada]['servico_fk'])//Aqui eu vou fazer uma função que vai requisitar percorrer departamentos e encontrar o fk
        var servico_selecionado_pk = ordens_servico[posicao_selecionada]['servico_fk'];


        $('#ordem_servico_pk').val(parseInt(ordens_servico[posicao_selecionada]['ordem_servico_pk']));
        $('#ordem_servico_desc').val(ordens_servico[posicao_selecionada]['ordem_servico_desc']);
        $('#departamento').val(data.departamento_pk);
        muda_depto();
        $('#tipo_servico').val(data.tipo_servico_pk);
        muda_tipo_servico();
        $('#servico_pk').val(servico_selecionado_pk);
        $('#situacao_pk').val(parseInt(ordens_servico[posicao_selecionada]['situacao_inicial_fk']));
        $('#prioridade_pk').val(parseInt(ordens_servico[posicao_selecionada]['prioridade_fk']));
        $('#procedencia_pk').val(parseInt(ordens_servico[posicao_selecionada]['procedencia_fk']));

        if ($('#procedencia_pk').val() == "2") {
            $('#nome-input').val(ordens_servico[posicao_selecionada]['pessoa_nome']);
            $('#cpf-input').val(ordens_servico[posicao_selecionada]['pessoa_cpf']);
            $('#email-input').val(ordens_servico[posicao_selecionada]['contato_email']);
            $('#celular-input').val(ordens_servico[posicao_selecionada]['contato_cel']);
            $('#telefone-input').val(ordens_servico[posicao_selecionada]['contato_tel']);
            $('#info_cidadao').show();
        }

        $('#setor_pk').val(parseInt(ordens_servico[posicao_selecionada]['setor_fk']));
        $("#latitude").val(ordens_servico[posicao_selecionada]['localizacao_lat']);
        $("#longitude").val(ordens_servico[posicao_selecionada]['localizacao_long']);
        $("#localizacao_pk").val(ordens_servico[posicao_selecionada]['localizacao_fk']);
        $("#bairro_pk").val(ordens_servico[posicao_selecionada]['localizacao_bairro']);
        $("#logradouro-input").val(ordens_servico[posicao_selecionada]['localizacao_rua']);
        $("#numero-input").val(ordens_servico[posicao_selecionada]['localizacao_num']);
        $("#referencia-input").val(ordens_servico[posicao_selecionada]['localizacao_ponto_referencia'] || "");
        $("#cidade-input option:selected").text(ordens_servico[posicao_selecionada]['municipio_nome']);

        $("#card_imagem").hide();

        $("#logradouro-input").removeClass('loading');

        var data_local;
        var local = "";

        // $.get(
        //     base_url + '/ordem_servico/local',
        //     { local_pk: ordens_servico[posicao_selecionada]['local_fk'] })
        //     .done(function (response) {
        //         if (response.code == 200) {
        //             data_local = response;

        //             $("#bairro-input").val(data_local.data.bairro_nome);


        //             $("#logradouro-input").val(data_local.data.logradouro_nome);

        //             $("#numero-input").val(data_local.data.local_num);
        //             $("#uf-input option:selected").text(data_local.data.estado_nome);
        //             $("#complemento-input").val(data_local.data.local_complemento);
        //             $("#referencia-input").val(data_local.data.local_referencia); //Valorando agora a referencia também
        //             $("#cidade-input option:selected").text(data_local.data.municipio_nome);

        //             // local = $("#cidade-input option:selected").text() + " ";
        //             // local += $("#logradouro-input").val() + " ";
        //             // local += $("#numero-input").val() + " ";
        //             // local += $("#bairro-input").val();

        //             var latlng = { lat: parseFloat(ordens_servico[posicao_selecionada]['coordenada_lat']), lng: parseFloat(ordens_servico[posicao_selecionada]['coordenada_long']) }
        //             populaLatLong(latlng);
        //             main_map.setCenter(latlng);
        //             criarMarcacao(latlng);

        //             $("#logradouro-input").removeClass('loading');
        //             $("#bairro-input").removeClass('loading');
        //             $('#ce_ordem_servico').modal('show');
        //             $('.submit').removeAttr('disabled');
        //             $('.submit').css('cursor', 'pointer');
        //             $('.close').removeAttr('disabled');
        //             $('.close').css('cursor', 'pointer');
        //             $('#fechar-historico').removeAttr('disabled');
        //             $('#fechar-historico').css('cursor', 'pointer');
        //             // primeiro_editar = true;


        //         }
        //         else {
        //             show_errors(response);
        //             alerts('failed', response.message, 'Não foi possível obter o local');
        //             //pre_loader_hide();
        //         }
        //     });
    });

    $("#close-modal").click(function () {
        $("#info_cidadao").hide();
        primeiro_editar = true;
    });


    $('#ce_ordem_servico').on('hide.bs.modal', function (event) {
        $("#tipo_servico option").remove();
        $("#servico_pk option").remove();
        $("#card_imagem").show();
        primeiro_editar = false;
    });

    //Função que pega a posição selecionada na datatable para exclusão
    $(document).on('click', '.btn-excluir', function (event) {
        $('#ordem_servico_pk').val(ordens_servico[$(this).val()]['ordem_servico_pk']);
        posicao_selecionada = $(this).val();
    });

    //Função que pega a posição selecionada na datatable para ativação
    $(document).on('click', '.btn-ativar', function (event) {
        $('#ordem_servico_pk').val(ordens_servico[$(this).val()]['ordem_servico_pk']);
        posicao_selecionada = $(this).val();
    });


    //Função que envia uma requisição ajax com os dados para desativar uma ordem
    $(document).on('click', '#btn-desativar', function (event) {
        var data;
        btn_load($('#btn-desativar'));
        if (is_superusuario) {
            data =
                {
                    'ordem_servico_pk': $('#ordem_servico_pk').val(),
                    'senha': $('#pass-modal-desativar').val()
                }
        }
        else {
            data =
                {
                    'ordem_servico_pk': $('#ordem_servico_pk').val(),
                }
        }

        $.post(base_url + '/ordem_servico/deactivate', data).done(function (response) {
            btn_ativar($('#btn-desativar'));
            if (response.code == 200) {
                alerts('success', 'Sucesso!', 'Ordem de Serviço desativada com sucesso');
                ordens_servico[posicao_selecionada]['ordem_servico_status'] = 0;


            } else {
                alerts('failed', 'Erro!', 'Houve um erro ao desativar.');
            }
            update_table();
            $('#d_servico').modal('hide');
        });
    });

    $(document).on('click', '#btn-reativar', function (event) {
        var data;
        btn_load($('#btn-reativar'));
        if (is_superusuario) {
            data =
                {
                    'ordem_servico_pk': $('#ordem_servico_pk').val(),
                    'senha': $('#pass-modal-reativar').val()
                }
        }
        else {
            data =
                {
                    'ordem_servico_pk': $('#ordem_servico_pk').val(),
                }
        }

        $.post(base_url + '/ordem_servico/activate', data).done(function (response) {
            btn_ativar($('#btn-reativar'));
            if (response.code == 200) {
                alerts('success', 'Sucesso!', 'Ordem de Serviço ativada com sucesso');
                ordens_servico[posicao_selecionada]['ordem_servico_status'] = 1;
            } else {
                alerts('failed', 'Erro!', 'Houve um erro ao ativar.');
            }
            update_table();
            $('#r_servico').modal('hide');
        });
    });

    $('#filter-ativo').on('change', function () {
        update_table();
    });


    function copyToClipboard(text, el) {

        var copyTest = document.queryCommandSupported('copy');
        var elOriginalText = el.attr('data-original-title');

        if (copyTest === true) {
            var copyTextArea = document.createElement("textarea");
            copyTextArea.value = text;
            document.body.appendChild(copyTextArea);
            copyTextArea.select();
            try {
                var successful = document.execCommand('copy');
                var msg = successful ? 'Copiado!' : 'Erro! Não copiado.';
                el.attr('data-original-title', msg).tooltip('show');
            } catch (err) {
                console.log('Não foi permitido copiar!');
            }
            document.body.removeChild(copyTextArea);
            el.attr('data-original-title', elOriginalText);
        } else {

            // Fallback if browser doesn't support .execCommand('copy')
            window.prompt("Copy to clipboard: Ctrl+C or Command+C, Enter", text);
        }
    }

    $(document).ready(function () {
        $('.js-tooltip').tooltip();

        // Copy to clipboard
        // Grab any text in the attribute 'data-copy' and pass it to the 
        // copy function
        $('.js-copy').click(function () {
            var text = $(this).attr('data-copy');
            var el = $(this);
            copyToClipboard(text, el);
        });
    });


    //Função que atualiza a datatable com dados modificados pelo usuário em tempo real
    update_table = () => {
        table.clear().draw();
        let url = '';
        let filtro = null;

        switch ($('#filter-ativo').val()) {
            case "semana":
                url = base_url + '/ordem_servico/filtro_tabela';
                filtro = {
                    filtro: 'semana'
                };
                pre_loader_show();
                $.post(url, filtro)
                    .done(function (response) {
                        if (response.code == 200) {

                            table.clear().draw();

                            ordens_servico = response.data;

                            $.each(ordens_servico, function (i, os) {
                                let endereco = os.logradouro_nome + ", " + os.local_num + " - " + os.bairro_nome;

                                if (os.ordem_servico_status == 1) {
                                    table.row.add([
                                        os.ordem_servico_cod,
                                        os.data_criacao,
                                        os.prioridade_nome,
                                        endereco,
                                        os.servico_nome,
                                        os.situacao_nome,
                                        os.setor_nome,
                                        '<div class="btn-group"><button type="button" class="btn btn-sm btn-success btn_atividade" data-toggle="modal" value="<?=$key?>" data-target="#atividade" title="Adicionar Situação"><div class="d-none d-sm-block"><i class="fas fa-plus fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_ordem_servico" title="Editar">' +
                                        '<div class="d-none d-sm-block"><i class="fas fa-edit fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-secondary reset_multistep btn_historico btn-attr-ordem_servico_pk" data-toggle="modal" value="' + (i) + '" data-target="#ce_historico_servico" title="Histórico">' +
                                        '<div class="d-none d-sm-block"><i class="far fa-clock fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-danger btn-excluir" data-toggle="modal" value="' + (i) + '" data-target="#d_servico" title="Desativar">' +
                                        '<div class="d-none d-sm-block"><i class="fas fa-times fa-fw"></i></div></button></div>'
                                    ]).draw(false);
                                } else {
                                    table.row.add([
                                        os.ordem_servico_cod,
                                        os.data_criacao,
                                        os.prioridade_nome,
                                        endereco,
                                        os.servico_nome,
                                        os.situacao_nome,
                                        os.setor_nome,
                                        '<div class="btn-group"><button type="button" disabled style="cursor:auto;" class="btn btn-sm btn-success btn_atividade" data-toggle="modal" value="<?=$key?>" data-target="#atividade" title="Adicionar Situação"><div class="d-none d-sm-block"><i class="fas fa-plus fa-fw"></i></div></button><button disabled style="cursor:auto;" type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_ordem_servico" title="Editar">' +
                                        '<div class="d-none d-sm-block"><i class="fas fa-edit fa-fw"></i></div></button><button disabled  style="cursor:auto;" type="button" class="btn btn-sm btn-secondary reset_multistep btn_historico btn-attr-ordem_servico_pk" data-toggle="modal" value="' + (i) + '" data-target="#ce_historico_servico" title="Histórico">' +
                                        '<div class="d-none d-sm-block"><i class="far fa-clock fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-success btn-ativar" data-toggle="modal" value="' + (i) + '" data-target="#r_servico" title="Reativar">' +
                                        '<div class="d-none d-sm-block"><i class="fas fa-power-off fa-fw"></i></div></button></div>'
                                    ]).draw(false);
                                }
                            });
                            $("#data_brasileira").click();

                        } else if (response.code == 404) {
                            alerts('warning', 'Aviso', 'Nenhuma ordem de serviço foi encontrada');
                        }
                    })
                    .fail(function (response) {
                        alerts('failed', 'Erro', 'Algo deu errado, tente novamente');
                    });
                pre_loader_hide();
                break;

            case "todos":
                url = base_url + '/ordem_servico/filtro_tabela';
                filtro = {
                    filtro: 'todos'
                };
                pre_loader_show();
                $.post(url, filtro)
                    .done(function (response) {
                        if (response.code == 200) {

                            table.clear().draw();

                            ordens_servico = response.data;

                            $.each(ordens_servico, function (i, os) {
                                let endereco = os.logradouro_nome + ", " + os.local_num + " - " + os.bairro_nome;

                                if (os.ordem_servico_status == 1) {
                                    table.row.add([
                                        os.ordem_servico_cod,
                                        os.data_criacao,
                                        os.prioridade_nome,
                                        endereco,
                                        os.servico_nome,
                                        os.situacao_nome,
                                        os.setor_nome,
                                        '<div class="btn-group"><button type="button" class="btn btn-sm btn-success btn_atividade" data-toggle="modal" value="<?=$key?>" data-target="#atividade" title="Adicionar Situação"><div class="d-none d-sm-block"><i class="fas fa-plus fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_ordem_servico" title="Editar">' +
                                        '<div class="d-none d-sm-block"><i class="fas fa-edit fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-secondary reset_multistep btn_historico btn-attr-ordem_servico_pk" data-toggle="modal" value="' + (i) + '" data-target="#ce_historico_servico" title="Histórico">' +
                                        '<div class="d-none d-sm-block"><i class="far fa-clock fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-danger btn-excluir" data-toggle="modal" value="' + (i) + '" data-target="#d_servico" title="Desativar">' +
                                        '<div class="d-none d-sm-block"><i class="fas fa-times fa-fw"></i></div></button></div>'
                                    ]).draw(false);
                                } else {
                                    table.row.add([
                                        os.ordem_servico_cod,
                                        os.data_criacao,
                                        os.prioridade_nome,
                                        endereco,
                                        os.servico_nome,
                                        os.situacao_nome,
                                        os.setor_nome,
                                        '<div class="btn-group"><button type="button" disabled style="cursor:auto;" class="btn btn-sm btn-success btn_atividade" data-toggle="modal" value="<?=$key?>" data-target="#atividade" title="Adicionar Situação"><div class="d-none d-sm-block"><i class="fas fa-plus fa-fw"></i></div></button><button disabled style="cursor:auto;" type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_ordem_servico" title="Editar">' +
                                        '<div class="d-none d-sm-block"><i class="fas fa-edit fa-fw"></i></div></button><button disabled  style="cursor:auto;" type="button" class="btn btn-sm btn-secondary reset_multistep btn_historico btn-attr-ordem_servico_pk" data-toggle="modal" value="' + (i) + '" data-target="#ce_historico_servico" title="Histórico">' +
                                        '<div class="d-none d-sm-block"><i class="far fa-clock fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-success btn-ativar" data-toggle="modal" value="' + (i) + '" data-target="#r_servico" title="Reativar">' +
                                        '<div class="d-none d-sm-block"><i class="fas fa-power-off fa-fw"></i></div></button></div>'
                                    ]).draw(false);
                                }
                            });
                            $("#data_brasileira").click();

                        } else if (response.code == 404) {
                            alerts('warning', 'Aviso', 'Nenhuma ordem de serviço foi encontrada');
                        }
                    })
                    .fail(function (response) {
                        alerts('failed', 'Erro', 'Algo deu errado, tente novamente');
                    });
                pre_loader_hide();
                break;

            case "ativadas":
                url = base_url + '/ordem_servico/filtro_tabela';
                filtro = {
                    filtro: 'ativadas'
                };
                pre_loader_show();
                $.post(url, filtro)
                    .done(function (response) {
                        if (response.code == 200) {

                            table.clear().draw();

                            ordens_servico = response.data;

                            $.each(ordens_servico, function (i, os) {
                                let endereco = os.logradouro_nome + ", " + os.local_num + " - " + os.bairro_nome;

                                table.row.add([
                                    os.ordem_servico_cod,
                                    os.data_criacao,
                                    os.prioridade_nome,
                                    endereco,
                                    os.servico_nome,
                                    os.situacao_nome,
                                    os.setor_nome,
                                    '<div class="btn-group"><button type="button" class="btn btn-sm btn-success btn_atividade" data-toggle="modal" value="<?=$key?>" data-target="#atividade" title="Adicionar Situação"><div class="d-none d-sm-block"><i class="fas fa-plus fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_ordem_servico" title="Editar">' +
                                    '<div class="d-none d-sm-block"><i class="fas fa-edit fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-secondary reset_multistep btn_historico btn-attr-ordem_servico_pk" data-toggle="modal" value="' + (i) + '" data-target="#ce_historico_servico" title="Histórico">' +
                                    '<div class="d-none d-sm-block"><i class="far fa-clock fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-danger btn-excluir" data-toggle="modal" value="' + (i) + '" data-target="#d_servico" title="Desativar">' +
                                    '<div class="d-none d-sm-block"><i class="fas fa-times fa-fw"></i></div></button></div>'
                                ]).draw(false);
                            });
                            $("#data_brasileira").click();

                        } else if (response.code == 404) {
                            alerts('warning', 'Aviso', 'Nenhuma ordem de serviço foi encontrada');
                        }
                    })
                    .fail(function (response) {
                        alerts('failed', 'Erro', 'Algo deu errado, tente novamente');
                    });
                pre_loader_hide();
                break;

            case "desativadas":
                url = base_url + '/ordem_servico/filtro_tabela';
                filtro = {
                    filtro: 'desativadas'
                };
                pre_loader_show();
                $.post(url, filtro)
                    .done(function (response) {
                        if (response.code == 200) {

                            table.clear().draw();
                            ordens_servico = response.data;

                            $.each(ordens_servico, function (i, os) {

                                let endereco = os.logradouro_nome + ", " + os.local_num + " - " + os.bairro_nome;

                                table.row.add([
                                    os.ordem_servico_cod,
                                    os.data_criacao,
                                    os.prioridade_nome,
                                    endereco,
                                    os.servico_nome,
                                    os.situacao_nome,
                                    os.setor_nome,
                                    '<div class="btn-group"><button type="button" disabled style="cursor:auto;" class="btn btn-sm btn-success btn_atividade" data-toggle="modal" value="<?=$key?>" data-target="#atividade" title="Adicionar Situação"><div class="d-none d-sm-block"><i class="fas fa-plus fa-fw"></i></div></button><button disabled type="button" style="cursor:auto;" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_ordem_servico" title="Editar">' +
                                    '<div class="d-none d-sm-block"><i class="fas fa-edit fa-fw"></i></div></button><button disabled type="button" style="cursor:auto;" class="btn btn-sm btn-secondary reset_multistep btn_historico btn-attr-ordem_servico_pk" data-toggle="modal" value="' + (i) + '" data-target="#ce_historico_servico" title="Histórico">' +
                                    '<div class="d-none d-sm-block"><i class="far fa-clock fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-success btn-ativar" data-toggle="modal" value="' + (i) + '" data-target="#r_servico" title="Reativar">' +
                                    '<div class="d-none d-sm-block"><i class="fas fa-power-off fa-fw"></i></div></button></div>'
                                ]).draw(false);

                            });

                            $("#data_brasileira").click();

                        } else if (response.code == 404) {
                            alerts('warning', 'Aviso', 'Nenhuma ordem de serviço foi encontrada');
                        }
                    })
                    .fail(function (response) {
                        alerts('failed', 'Erro', 'Algo deu errado, tente novamente');
                    });
                pre_loader_hide();
                break;

            case "finalizadas":
                url = base_url + '/ordem_servico/filtro_tabela';
                filtro = {
                    filtro: 'finalizadas'
                };

                pre_loader_show();
                $.post(url, filtro)
                    .done(function (response) {
                        if (response.code == 200) {

                            table.clear().draw();
                            ordens_servico = response.data;

                            $.each(ordens_servico, function (i, os) {

                                let endereco = os.logradouro_nome + ", " + os.local_num + " - " + os.bairro_nome;

                                table.row.add([
                                    os.ordem_servico_cod,
                                    os.data_criacao,
                                    os.prioridade_nome,
                                    endereco,
                                    os.servico_nome,
                                    os.situacao_nome,
                                    os.setor_nome,
                                    '<div class="btn-group"><button type="button" class="btn btn-sm btn-success btn_atividade" data-toggle="modal" value="<?=$key?>" data-target="#atividade" title="Adicionar Situação"><div class="d-none d-sm-block"><i class="fas fa-plus fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_ordem_servico" title="Editar">' +
                                    '<div class="d-none d-sm-block"><i class="fas fa-edit fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-secondary reset_multistep btn_historico btn-attr-ordem_servico_pk" data-toggle="modal" value="' + (i) + '" data-target="#ce_historico_servico" title="Histórico">' +
                                    '<div class="d-none d-sm-block"><i class="far fa-clock fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-danger btn-ativar" data-toggle="modal" value="' + (i) + '" data-target="#d_servico" title="Desativar">' +
                                    '<div class="d-none d-sm-block"><i class="fas fa-times fa-fw"></i></div></button></div>'
                                ]).draw(false);
                            });

                            $("#data_brasileira").click();

                        } else if (response.code == 404) {
                            alerts('warning', 'Aviso', 'Nenhuma ordem de serviço foi encontrada');
                        }
                    })
                    .fail(function (response) {
                        alerts('failed', 'Erro', 'Algo deu errado, tente novamente');
                    });

                pre_loader_hide();

                break;

            case "abertas":
                url = base_url + '/ordem_servico/filtro_tabela';
                filtro = {
                    filtro: 'abertas'
                };
                pre_loader_show();
                $.post(url, filtro)
                    .done(function (response) {
                        if (response.code == 200) {

                            ordens_servico = response.data;
                            table.clear().draw();
                            $.each(ordens_servico, function (i, os) {

                                let endereco = os.logradouro_nome + ", " + os.local_num + " - " + os.bairro_nome;

                                table.row.add([
                                    os.ordem_servico_cod,
                                    os.data_criacao,
                                    os.prioridade_nome,
                                    endereco,
                                    os.servico_nome,
                                    os.situacao_nome,
                                    os.setor_nome,
                                    '<div class="btn-group"><button type="button" class="btn btn-sm btn-success btn_atividade" data-toggle="modal" value="<?=$key?>" data-target="#atividade" title="Adicionar Situação"><div class="d-none d-sm-block"><i class="fas fa-plus fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="' + (i) + '" data-target="#ce_ordem_servico" title="Editar">' +
                                    '<div class="d-none d-sm-block"><i class="fas fa-edit fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-secondary reset_multistep btn_historico btn-attr-ordem_servico_pk" data-toggle="modal" value="' + (i) + '" data-target="#ce_historico_servico" title="Histórico">' +
                                    '<div class="d-none d-sm-block"><i class="far fa-clock fa-fw"></i></div></button><button type="button" class="btn btn-sm btn-danger btn-ativar" data-toggle="modal" value="' + (i) + '" data-target="#d_servico" title="Desativar">' +
                                    '<div class="d-none d-sm-block"><i class="fas fa-times fa-fw"></i></div></button></div>'
                                ]).draw(false);
                            });

                            $("#data_brasileira").click();

                        } else if (response.code == 404) {
                            alerts('warning', 'Aviso', 'Nenhuma ordem de serviço foi encontrada');
                        }
                    })
                    .fail(function (response) {
                        alerts('failed', 'Erro', 'Algo deu errado, tente novamente');
                    });
                pre_loader_hide();

                break;
        }

    }

}