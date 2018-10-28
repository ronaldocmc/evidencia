var main_map;
var main_marker = null;
var markers;
var markers_situacao;
var ordens_servico;
var colors = ['ff0000', 'ffff00', 'ff00ff', '0000ff', '00ff00'];

function initMap() {

    main_map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: -22.114184, lng: -51.405798 },
        zoom: 14
    });

    
    $(document).ready(function () {
        $('#filtrar').prop('disabled',true);
        $('.carousel').carousel();
        $.ajax({
            url: base_url + '/ordem_servico/json',
            dataType: "json",
            success: function (response) {
                ordens_servico = response.ordens;
                markers = response.ordens.map(function (ordem, i) {

                    let imagem = '../assets/img/icons/Markers/Status/';



                    if(ordem.departamento == "1"){
                        imagem += "Coleta/";
                    }

                    if(ordem.departamento == "2"){
                        imagem += "Limpeza/";
                    }

                    if(ordem.prioridade == "1"){
                        imagem += "Baixa/";
                    }

                    if(ordem.prioridade == "2"){
                        imagem += "Alta/";
                    }

                    if(ordem.prioridade == "4"){
                        imagem += "Media/";
                    }

                    if(ordem.situacao == "1"){
                        imagem += "Aberta/"
                    }

                    if(ordem.situacao == "2"){
                        imagem += "Andamento/"
                    }

                    if(ordem.situacao == "3"){
                        imagem += "Recusado/"
                    }

                    if(ordem.situacao == "4"){
                        imagem += "Recusado/"
                    }

                    if(ordem.situacao == "5"){
                        imagem += "Finalizado/"
                    }

                    switch(ordem.servico){
                        case "1":
                        imagem +=  "Marker_Fossa.png";
                        break;
                        case "2":
                        imagem +=  "Marker_Fossa.png";
                        break;
                        case "3":
                        imagem +=  "Marker_Animal_Morto.png";
                        break;
                        case "4":
                        imagem +=  "Marker_Sofa.png";
                        break;
                        case "5":
                        imagem +=  "Marker_Galhos.png";
                        break;
                        case "6": 
                        imagem += "Marker_Lixo.png";
                        break;
                        case "7": 
                        imagem += "Marker_Lixao.png";
                        break;
                        case "8": 
                        imagem += "Marker_Eletro.png";
                        break;
                        case "9": 
                        imagem += "Marker_Feira.png";
                        break;
                        case "10": 
                        imagem += "Marker_Mobiliario.png";
                        break;
                        case "11": 
                        imagem += "Marker_Madeira.png";
                        break;
                        case "12": 
                        imagem += "Marker_Carpinagem.png";
                        break;
                        case "13": 
                        imagem += "Marker_Carpinagem.png";
                        break;
                        case "14": 
                        imagem += "Marker_Entulho.png";
                        break;
                        case "15": 
                        imagem += "Marker_Escola.png";
                        break;
                        case "16": 
                        imagem += "Marker_Grama.png";
                        break;
                        case "17": 
                        imagem = "";
                        break;
                        case "18": 
                        imagem += "Marker_Cacamba.png";
                        break;

                    }

                    // var pinColor = colors[ordem.situacao];
                    // var imagem = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor);

                    var marker = new google.maps.Marker({
                        position: { lat: parseFloat(ordem.latitude), lng: parseFloat(ordem.longitude) },
                        map: main_map,
                        icon: imagem,
                        id: ordem.id,
                        departamento: ordem.departamento,
                        tipo_servico: ordem.tipo_servico,
                        servico: ordem.servico,
                        situacao: ordem.situacao,
                        data_criacao: ordem.data_inicial,
                        prioridade: ordem.prioridade
                    });

                    marker.addListener('click', function () {
                        main_map.panTo(marker.getPosition());
                        request_data(this.id);
                        $('#v_evidencia').modal('show');
                    }); 
                    return marker;
                });

$('#filtrar').prop('disabled',false);
}
});
});

function remove_data() {
    $("#v_descricao").html('');
    $("#v_prioridade").html('');
    $("#v_procedencia").html('');
    $("#v_setor").html('');
    $("#v_servico").html('');

    $('.carousel-inner').html('');
    $('.carousel-indicators').html('');
    $('#timeline').html('');
    $('#v_loading').show();
}

function toDataURL(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onload = function () {
        var reader = new FileReader();
        reader.onloadend = function () {
            callback(reader.result);
        }
        reader.readAsDataURL(xhr.response);
    };
    xhr.open('GET', url);
    xhr.responseType = 'blob';
    xhr.send();
}


function request_data(id) {
    remove_data();

    $.ajax({
        url: base_url + '/ordem_servico/json_especifico/' + id + '/' + 0,
        dataType: "json",
        success: function (response) {



            $("#v_descricao").html(response.ordem.descricao);
            $("#v_prioridade").html(response.ordem.prioridade);
            $("#v_procedencia").html('App');
            $("#v_setor").html('1');
            $("#v_servico").html(response.ordem.servico);

            var html = "";
            var indicators = "";
            var active = "active";
            var timeline = "";


            response.ordem.historico.map((historico, i) => {

                indicators += '<li data-target="#carouselExampleIndicators" data-slide-to="' + i + '"></li>';
                if (historico.comentario == null) {
                    historico.comentario = "Nenhum comentário adicionado.";
                }
                if(historico.funcionario_foto != null){
                    timeline += create_timeline(historico.comentario, historico.foto, historico.funcionario, historico.funcionario_foto, historico.situacao, historico.data);
                }else{
                    timeline += create_timeline(historico.comentario, historico.foto, historico.funcionario, 'default.png', historico.situacao, historico.data);
                }

                if (historico.foto != null) {
                    html += create_carousel_item(historico.comentario, historico.foto, historico.funcionario, historico.situacao, historico.data, active);
                    active = "";
                } else {
                    html += create_carousel_item(historico.comentario, 'assets/uploads/imagens_situacoes/no-image.png', historico.funcionario, historico.situacao, historico.data, active);
                }

            });

                // console.log(html);

                $('#v_loading').hide();
                $('.carousel-inner').html(html);
                $('.carousel-indicators').html(indicators);
                $('#timeline').html(timeline);
            }
        });
}

function create_timeline(comentario, src, funcionario, funcionario_foto, situacao, data) {
    return '<div class="message-item">' +
    '<div class="message-inner">' +
    '<div class="message-head clearfix">' +
    '<div class="avatar pull-left"><a href="#"><img class="message-foto-perfil" src="../assets/uploads/perfil_images/min/' + funcionario_foto + '"></a></div>' +
    '<div class="user-detail">' +
    '<h5 class="handle">' + funcionario + '</h5>' +
    '<div class="post-meta">' +
    '<div class="asker-meta">' +
    '<span class="qa-message-what"></span>' +
    '<span class="qa-message-when">' +
    '<span class="qa-message-when-data">' + data + '</span>' +
    '</span>' +
    '<span class="qa-message-who">' +
    '<span class="qa-message-who-pad"> por </span>' +
    '<span class="qa-message-who-data"><a href="#">' + funcionario + '</a></span>' +
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

    return '<div align="center" class="carousel-item ' + active + '">' +
    '<img class="d-block" style="height: 400px;" src="../'  + src + '" alt="Evidência">' +
    '<div class="carousel-caption d-none d-lg-block">' +
    '<p>' + description + '</p>' +
    '<p>Funcionário: ' + funcionario + '</p>' +
    '<p>Situação: ' + situacao + '</p>' +
    '<p>Data: ' + data + '</p>' +
    '</div>' +
    '</div>';
}

function criarMarcacao(location) {
    main_marker = new google.maps.Marker({
        position: location,
        map: main_map
    });
}

var departamento = $('#departamento_pk');
var tipo_servico = $('#tipo_servico_pk');
var servico = $('#servico_pk');
var prioridade = $('#prioridade_pk');
var situacao = $('#situacao_pk');
var de = $('#de');
var ate = $('#ate');
var h_inicial = $('#h_inicial');
var h_final = $('#h_final');



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
    activeAll();
    // console.log("Listando");
    // console.log("Departamento: " + departamento.val());
    // console.log("Tipo_servico: " + tipo_servico.val());
    // console.log("Servico: " + servico.val());
    // console.log("Prioridade: " + prioridade.val());
    // console.log("Situação: " + situacao.val());
    // console.log("Data de: " + de.val());
    // console.log("Data Ate: " + ate.val());
    
    markers.map((marker, i) => {
        filter(marker);
    });    
    
    
});

function activeAll() {
    markers.map((marker, i) => {
        marker.setMap(main_map);
        marker.setVisible(true);
    });
}

function removeAll(){
    markers.map((marker, i) => {
        marker.setMap(main_map);
        marker.setVisible(false);
    });
}

function filter(marker) {

    if (departamento.val() != -1 && marker.departamento != departamento.val()) {
        marker.setMap(null);
        marker.setVisible(false);
    }
    if (tipo_servico.val() != -1 && marker.tipo_servico != tipo_servico.val()) {
        marker.setMap(null);
        marker.setVisible(false);
    }
    if (servico.val() != -1 && marker.servico != servico.val()) {
        marker.setMap(null);
        marker.setVisible(false);
    }
    if (prioridade.val() != -1 && marker.prioridade != prioridade.val()) {
        marker.setMap(null);
        marker.setVisible(false);
    }
    if (situacao.val() != -1 && marker.situacao != situacao.val()) {
        marker.setMap(null);
        marker.setVisible(false);
    }

    let data_inicial = new Date(de.val() + "T" + h_inicial.val());
    let data_final = new Date(ate.val() + "T" + h_final.val());

    if(de.val() != "" && ate.val() != ""){

        let data_ordem = new Date(marker.data_criacao);

        if(data_ordem < data_inicial || data_ordem > data_final){
            marker.setMap(null);
            marker.setVisible(false);
        }
    }
}

}

function addMarkers(){
    markers_situacao = ordens_servico.map(function (ordem, i) {

        let imagem = '../assets/img/icons/Markers/Status/';

        if(ordem.departamento == "1"){
            imagem += "Coleta/";
        }

        if(ordem.departamento == "2"){
            imagem += "Limpeza/";
        }

        if(ordem.prioridade == "1"){
            imagem += "Baixa/";
        }

        if(ordem.prioridade == "2"){
            imagem += "Alta/";
        }

        if(ordem.prioridade == "4"){
            imagem += "Media/";
        }





        switch(ordem.servico){
            case "1":
            imagem +=  "Marker_Rua.png";
            break;
            case "2":
            imagem +=  "Marker_Fossa.png";
            break;
            case "3":
            imagem +=  "Marker_Animal_Morto.png";
            break;
            case "4":
            imagem +=  "Marker_Sofa.png";
            break;
            case "5":
            imagem +=  "Marker_Galhos.png";
            break;
            case "6": 
            imagem += "Marker_Lixo.png";
            break;
            case "7": 
            imagem += "Marker_Lixao.png";
            break;
            case "8": 
            imagem += "Marker_Eletro.png";
            break;
            case "9": 
            imagem += "Marker_Feira.png";
            break;
            case "10": 
            imagem += "Marker_Mobiliario.png";
            break;
            case "11": 
            imagem += "Marker_Madeira.png";
            break;
            case "12": 
            imagem += "Marker_Carpinagem.png";
            break;
            case "13": 
            imagem += "Marker_Carpinagem.png";
            break;
            case "14": 
            imagem += "Marker_Entulho.png";
            break;
            case "15": 
            imagem += "Marker_Escola.png";
            break;
            case "16": 
            imagem += "Marker_Grama.png";
            break;
            case "17": 
            imagem = "";
            break;
            case "18": 
            imagem += "Marker_Cacamba.png";
            break;

        }

        var marker = new google.maps.Marker({
            position: { lat: parseFloat(ordem.latitude), lng: parseFloat(ordem.longitude) },
            map: main_map,
            icon: imagem,
            id: ordem.id,
            departamento: ordem.departamento,
            tipo_servico: ordem.tipo_servico,
            servico: ordem.servico,
            situacao: ordem.situacao,
            prioridade: ordem.prioridade
        });

        marker.addListener('click', function () {
            main_map.panTo(marker.getPosition());
            request_data(this.id);
            $('#v_evidencia').modal('show');
        });
        return marker;
    });
}

