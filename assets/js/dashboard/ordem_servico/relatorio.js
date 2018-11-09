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
                        data_criacao: ordem.data_inicial, //Usava o reformatDate();
                        prioridade: ordem.prioridade,
                        setor: ordem.setor,
                        title: ordem.rua + ", " + ordem.numero + " - " + ordem.bairro + ". " + ordem.ponto_referencia
                    });

                    marker.addListener('click', function () {
                        main_map.panTo(marker.getPosition());
                        request_data(this.id, marker.setor); 
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
    $('#card_slider').html('');
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


function request_data(id, setor) {
    remove_data();


    $.ajax({
        url: base_url + '/ordem_servico/json_especifico/' + id + '/' + 0,
        dataType: "json",
        success: function (response) {
       
            $("#v_descricao").html(response.ordem.descricao);
            $("#v_prioridade").html(response.ordem.prioridade);
            $("#v_setor").html(setor);
            $("#v_servico").html(response.ordem.servico);

            

            var html = "";
            var indicators = "";
            var active = " active";
            var timeline = "";


            if(response.ordem.historico.length > 2){
                html +=     '<div id="myCarousel" class="carousel slide"data-ride="carousel">' +
                '<div class="carousel-inner row w-100 mx-auto"></div>' +
                '<a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">' +
                '<span class="carousel-control-prev-icon" aria-hidden="true"></span>' +
                '<span class="sr-only">Previous</span>' +
                '</a>' +
                '<a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">' +
                '<span class="carousel-control-next-icon" style="color: black;" aria-hidden="true"></span>' +
                '<span class="sr-only">Next</span>'+
                '</a>'+
                '</div>';
            }else{

                html += '<div id="card_imagens">' +
                '<div class="carousel-inner row w-100 mx-auto"></div>' +
                '</div>';
            }

            $('#card_slider').html(html);
            html = "";
            response.ordem.historico.map((historico, i) => {

                if (historico.comentario == null) {
                    historico.comentario = "Nenhum comentário adicionado.";
                }
                if(historico.funcionario_foto != null){
                    timeline += create_timeline(historico.comentario, historico.funcionario, base_url + historico.funcionario_foto.replace('./', '/'), historico.situacao, reformatDate(historico.data));
                }else{
                    timeline += create_timeline(historico.comentario, historico.funcionario, base_url + '/assets/uploads/perfil_images/default.png', historico.situacao, reformatDate(historico.data));
                }
                if (historico.foto != null) {
                    html += create_cards(historico.comentario,  base_url + historico.foto.replace('./', '/'), historico.funcionario, historico.situacao, reformatDate(historico.data), active);
                    active ="";
                } else {
                    html += create_cards(historico.comentario,  base_url + '/assets/uploads/imagens_situacoes/no-image.png', historico.funcionario, historico.situacao, reformatDate(historico.data), active);
                    active ="";
                }

            });


            $('#v_loading').hide();
            $('.carousel-inner').html(html);
            $('#timeline').html(timeline);
        }
    });
}



function create_timeline(comentario, funcionario, funcionario_foto, situacao, data) {
    return '<div class="message-item">' +
    '<div class="message-inner">' +
    '<div class="message-head clearfix">' +
    '<div class="avatar pull-left"><a href="#"><img class="message-foto-perfil" src="' + funcionario_foto + '"></a></div>' +
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

function create_cards(description, src, funcionario, situacao, data, active) {

    return '<div class="carousel-item col-md-4' + active + '">' +
    '<div class="card">' +
    '<img class="card-img-top img-fluid" src="' + src +'">'+
    '<div class="card-body">' +
    '<h4 class="card-title">'+ situacao + '</h4>' +
    '<p class="card-text">'+ description +'</p>' +  
    '<p class="card-text"><small class="text-muted">'+ data + '</small></p>' +
    '<p class="card-text"><small class="text-muted"><b>'+ funcionario + '</b></small></p>' +
    '</div>' +
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

//Função que ativa todos os markers no mapa
function activeAll() {
    markers.map((marker, i) => {
        marker.setMap(main_map);
        marker.setVisible(true);
    });
}

//Funçao que remove todos os markers do mapa (Não utilizada por enquanto).
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

    //Variáveis de comparação que serão setadas de acordo com o que o usuário inserir
    let data_inicial, data_final;

    //Verifico se as horas foram preenchidas, caso contrário, será o padrão 6h00 e 22h00 
    if(h_inicial.val() != "" && h_final.val() != ""){
        data_inicial    = new Date(de.val() + "T" + h_inicial.val());
        data_final = new Date(ate.val() + "T" + h_final.val());
     }else{
        data_inicial    = new Date(de.val() + "T" + "06:00:00");
        data_final = new Date(ate.val() + "T" + "22:00:00");
     }

     //Verificando se a data foi preenchida para realizar a filtragem
    if(de.val() != "" && ate.val() != ""){

        //Criando o objeto Date para efetuar a comparação. 
        let data_os = marker.data_criacao.replace(" ", "T");
        let data_ordem = new Date(data_os);

        //Se a data da ordem for menor que a data inicial, não queremos. Se for maior que a final, também não queremos
        if(data_ordem.getTime() < data_inicial.getTime() || data_ordem.getTime() > data_final.getTime()){
            marker.setMap(null);
            marker.setVisible(false);

        }
    }
}

}

