let view;
let main_map;
let markers = null;

let departamento = $('#departamento_pk');
let tipo_servico = $('#tipo_servico_pk');
let servico = $('#servico_pk');
let prioridade = $('#prioridade_pk');
let situacao = $('#situacao_pk');
let setor = $('#setor_pk');
let de = $('#de');
let ate = $('#ate');

$(document).ready(function () {
    view = new GenericView();
    view.conditionalRender();

    btn_load($('#filtrar'));

    let date = new Date();

    let filters = {
        data_inicial: (date.getFullYear()) + '-' + (date.getMonth() + 1) + '-' + (date.getDate() - 7) + ' 00:01:00',
        data_final: (date.getFullYear()) + '-' + (date.getMonth() + 1) + '-' + (date.getDate()) + ' 23:59:00'
    };

    $('#de').val(formatDate(lastWeek()));
    $('#ate').val(formatDate(new Date()));

    let url = base_url + '/Ordem_Servico/get_map';

    $.post(url, filters)
        .done(function (response) {
            markers = [];
            response.data.map(function (ordem) {
                popula_markers(ordem);
            });
        })
        .fail(function (response) {

        });

    btn_ativar($('#filtrar'));
});

function lastWeek() {
    var today = new Date();
    var lastweek = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 7);
    return lastweek;
}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}

function seleciona_imagem(prioridade) {
    let imagem = './assets/img/icons/Markers/Status/';

    switch (prioridade) {
        case "1": {
            imagem += "prioridade_baixa.png";
            break;
        }
        case "2": {
            imagem += "prioridade_alta.png";
            break;
        }
        case "4":{
            imagem += "prioridade_media.png";
            break;
        }
    }

    return imagem;
}

function remove_data() {
    $("#v_descricao").html('');
    $("#v_codigo").html('');
    $("#v_procedencia").html('');
    $("#v_setor").html('');
    $("#v_servico").html('');
    $('#card_slider').html('');
    $('#timeline').html('');
    $('#v_loading').show();
}

function request_data(id, setor) {
    remove_data();
    btn_load($('#filtrar'));
    $.ajax({
        url: base_url + '/Ordem_Servico/get_specific/' + id,
        dataType: "json",
        success: function (response) {
            $("#v_descricao").html(response.data.ordem_servico[0].ordem_servico_desc);
            $("#v_codigo").html(response.data.ordem_servico[0].ordem_servico_cod);
            $("#v_setor").html(response.data.ordem_servico[0].setor_nome);
            $("#v_servico").html(response.data.ordem_servico[0].servico_nome);

            let carousel = "";
            let indicators = "";
            let active = " active";
            let timeline = "";
            let cards = '';

            carousel = view.renderCarousel(response.data.imagens);
            timeline = view.renderTimelineHistoric(response.data.historico);
            timeline += view.renderCurrentSituation(createCurrentSituationOject(response.data.ordem_servico[0]));

            if (response.data.imagens.length > 0) {
                cards = view.renderCarouselCards(response.data.imagens);
            }

            $('#v_loading').hide();
            $('#card_slider').html(carousel);
            $('.carousel-inner').html(cards);
            $('#timeline').html(timeline);
        }
    });
    btn_ativar($('#filtrar'));
}

function createCurrentSituationOject(os) {
    let data = {
        funcionario_caminho_foto: os.funcionario_caminho_foto,
        funcionario_nome: os.funcionario_nome,
        ordem_servico_atualizacao: os.ordem_servico_atualizacao,
        situacao_atual_nome: os.situacao_nome,
        ordem_servico_comentario: os.ordem_servico_comentario
    };

    return data;
}

departamento.change(function () {
    if (departamento.val() != -1) {
        muda_depto();
    } else {
        all_tipos_servicos();
    }
}).change();

tipo_servico.change(function () {
    if (tipo_servico.val() != -1) {
        muda_tipo_servico();
    } else {
        all_servicos();
    }
}).change();

function all_tipos_servicos() {
    $("#tipo_servico_pk option").remove();

    tipo_servico.append('<option value="-1">Todos</option>');
    for (var i = 0; i < tipos_servicos.length; i++) {
        tipo_servico.append('<option value="' + tipos_servicos[i].tipo_servico_pk + '">' + tipos_servicos[i].tipo_servico_nome + '</option>');
    }
    all_servicos();

}

function all_servicos() {
    $("#servico_pk option").remove();

    servico.append('<option value="-1">Todos</option>');
    for (var i = 0; i < servicos.length; i++) {
        servico.append('<option value="' + servicos[i].servico_pk + '">' + servicos[i].servico_nome + '</option>');
    }
}

function add_options_tipo_servico() {

    $("#tipo_servico_pk option").remove();

    tipo_servico.append('<option value="-1">Todos</option>');

    var depto = $("#departamento_pk option:selected").val();
    for (var i = 0; i < tipos_servicos.length; i++) {
        if (tipos_servicos[i].departamento_fk == depto) {
            tipo_servico.append('<option value="' + tipos_servicos[i].tipo_servico_pk + '">' + tipos_servicos[i].tipo_servico_nome + '</option>');
        }

    }
}

function add_options_servico() {
    $("#servico_pk option").remove();

    servico.append('<option value="-1">Todos</option>');

    var tipo_servico = $("#tipo_servico_pk option:selected").val();

    for (var i = 0; i < servicos.length; i++) {

        if (servicos[i].tipo_servico_fk == tipo_servico) {

            servico.append('<option value="' + servicos[i].servico_pk + '">' + servicos[i].servico_nome + '</option>');

        }

    }

}

function muda_depto() {

    var depto = $("#departamento_pk option:selected").val();

    add_options_tipo_servico();

    add_options_servico();

}

function muda_tipo_servico() {
    add_options_servico();
}

$('#filtrar').click(function () {
    $('#p_ordens').hide();
    btn_load($('#filtrar'));

    let filters = get_filters();
    let url = base_url + '/Ordem_Servico/get_map';

    $.post(url, filters)
        .done(function (response) {
            removeAll();
            markers = [];
            response.data.map(function (ordem) {
                popula_markers(ordem);
            });
        })
        .fail(function (response) {
            
        });

    btn_ativar($('#filtrar'));
});

function removeAll() {
    if (markers !== null) {
        markers.map((marker) => {
            marker.setMap();
            marker.setVisible(false);
        });
    }
}

function get_filters() {
    let filters = {
        departamento_fk: departamento.val() != -1 ? departamento.val() : null,
        tipo_servico_pk: tipo_servico.val() != -1 ? tipo_servico.val() : null,
        servico_fk: servico.val() != -1 ? servico.val() : null,
        prioridade_fk: prioridade.val() != -1 ? prioridade.val() : null,
        setor_fk: setor.val() != -1 ? setor.val() : null,
        situacao_atual_fk: situacao.val() != -1 ? situacao.val() : null,
        data_inicial: de.val() != -1 ? de.val() + ' 00:01:00' : null,
        data_final: ate.val() != -1 ? ate.val() + ' 23:59:00' : null,
    };

    return filters;
}

function popula_markers(ordem) {
    let imagem = seleciona_imagem(ordem.prioridade_fk);

    var marker = new google.maps.Marker({
        position: {
            lat: parseFloat(ordem.localizacao_lat),
            lng: parseFloat(ordem.localizacao_long)
        },
        map: main_map,
        icon: imagem,
        id: ordem.ordem_servico_pk,
        departamento: ordem.departamento_fk,
        tipo_servico: ordem.tipo_servico_pk,
        servico: ordem.servico_fk,
        situacao: ordem.situacao_atual_fk,
        data_criacao: ordem.ordem_servico_criacao,
        prioridade: ordem.prioridade_fk,
        setor: ordem.setor_fk,
        title: ordem.localizacao_rua + ", " + ordem.localizacao_num + " - " + ordem.localizacao_bairro
    });

    marker.addListener('click', function () {
        main_map.panTo(marker.getPosition());
        request_data(this.id, marker.setor);
        $('#v_evidencia').modal('show');
    });

    markers.push(marker);
}

function initMap() {

    main_map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: -22.114184, lng: -51.405798 },
        zoom: 14
    });
    
}