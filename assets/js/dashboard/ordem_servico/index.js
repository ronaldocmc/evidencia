/*
 * == Variáveis globais: == 
 * 
 * @Boolean: is_superusuario
 * @String: base_url
 * 
 */

var table = $('#ordens_servico').DataTable();
var is_superusuario = false; 


class View extends GenericView {
    // FUNÇÃO GENÉRICA PARA PREENCHER UM SELECT
    // PARA PREENCHER UM MULTIPLE SELECT


    constructor() {
        super();
    }

    init(data, tableFields, primaryKey) {
        super.init(data, tableFields, primaryKey);

        this.generateSelect(data.departamentos, 'departamento_nome', 'departamento_pk', 'departamento_fk');
        this.generateSelect(data.tipos_servicos, 'tipo_servico_nome', 'tipo_servico_pk', 'tipo_servico_fk');
        this.generateSelect(data.servicos, 'servico_nome', 'servico_pk', 'servico_fk');
        this.generateSelect(data.procedencias, 'procedencia_nome', 'procedencia_pk', 'procedencia_fk');
        this.generateSelect(data.prioridades, 'prioridade_nome', 'prioridade_pk', 'prioridade_fk');
        this.generateSelect(data.situacoes, 'situacao_nome', 'situacao_pk', 'situacao_inicial_fk');
        this.generateSelect(data.municipios, 'municipio_nome', 'municipio_pk', 'localizacao_municipio');
        this.generateSelect(data.setores, 'setor_nome', 'setor_pk', 'setor_fk');

    }


    generateButtons(condition, i) {
        return `<div class='btn-group'>` +
            (
                this.createButton('edit', 'save', 'primary', 'Editar', i, 'fa-edit') +
                // this.createButton('deactivate', 'deactivate', 'danger', 'Desativar', i, 'fa-times') +
                this.createButton('create_history', 'create_history', 'success', 'Criar histórico', i, 'fa-calendar-plus') +
                this.createButton('info', 'info', 'info', 'Ver informações', i, 'fa-eye')
            ) +
            `</div>`;
    }

    checkElementDom(id) {
        return document.getElementById(id);
    }

    renderTimelineHistoric(data = null) {
        let render = '';

        data.forEach((d, i) => {
            render += this.createTimeLine(
                d.funcionario_caminho_foto,
                d.funcionario_nome,
                d.historico_ordem_tempo,
                d.situacao_nome,
                d.historico_ordem_comentario)
        });

        return render;
    }

    renderCurrentSituation(data) {
        let render = '';

        render += this.createTimeLine(
            data.funcionario_caminho_foto,
            data.funcionario_nome,
            data.ordem_servico_atualizacao,
            data.situacao_atual_nome,
            data.ordem_servico_comentario
        );

        return render;
    }

    renderTimeLineInput() {
        var d = new Date();
        var dataHora = (d.toLocaleString());
        let render = '';

        render += '<div class="message-item">' +
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
            '<div class="col-12" id="image-upload-div" style="margin-left: 2px;">' +
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
            '</div>' +
            '</div>';

        return render;
    }

    renderCarouselCards(data){
        let render = '';
        let active = "active";

        data.map((img) => {
            render +=  this.createCarouselCards(img.imagem_os, img.situacao_nome, img.imagem_os_timestamp, active);
            active = "";
        });
        
        return render;
    }

    renderCarousel(data){
        let render = '';
        
        render = this.createCarousel(data);
        $('#card_slider_historic').html(render);

    }

    createTimeLine(photo_path = null, worker_name, date, situation, comment) {

        return '<div class="message-item">' +
            '<div class="message-inner">' +
            '<div class="message-head clearfix">' +
            '<div class="avatar pull-left">' +
            '<a href="#"><img class="message-foto-perfil" src="' + (photo_path || base_url + '/assets/uploads/perfil_images/default.png') + '"></a>' +
            '</div>' +
            '<div class="user-detail">' +
            '<h5 class="handle">' + worker_name + '</h5>' +
            '<div class="post-meta">' +
            '<div class="asker-meta">' +
            '<span class="qa-message-what"></span>' +
            '<span class="qa-message-when">' +
            '<span class="qa-message-when-data">' + reformatDate(date) + '</span>' +
            '</span>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="qa-message-content">' +
            '<b>Situação: </b>' + situation +
            '<br>' + (comment || "Nenhum comentário adicionado.") +
            '</div>' +
            '</div>' +
            '</div>';
    }

    createCarouselCards(src, situacao, data, active) {

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

    createCarousel(data) {
        let render="";

        if (data.length > 2) {
            render +=
                '<div id="myCarousel" class="carousel slide"data-ride="carousel">' +
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
                    alerts('success', 'Sucesso', 'Operação realizada com sucesso');
        } else {
            render +=
                '<div id="card_imagens">' +
                '<div class="carousel-inner row w-100 mx-auto"></div>' +
                '</div>';
        }

        return render;
    }

    createImageCard(src, val){
        return (
            '<div class="col-md-3">' + 
                '<img src="'+ src +'" class="img-thumbnail" alt="image_os" width="150px" height="150px" style="position:relative;">' +
                '<div class="btn-group" style="position:relative; display:block; z-index:1; left:65%;  margin: -40px 10px 0 0;">' + 
                    '<button type="button" class="btn btn-sm btn-danger btn_remove_image" data-title="Remover Imagem" value="' + val.toString() + '">' + 
                        '<i class="fa fa-trash" aria-hidden="true"></i>' + 
                    '</button>' + 
                '</div>' + 
            '<div>'
             
        )
    }


}

class Request extends GenericRequest {

    constructor() {
        super();
        this.route = '/ordem_servico';
    }

}


class Control extends GenericControl {
    

    constructor() {
        super();
        this.primaryKey = "ordem_servico_pk";
        this.fields = ['ordem_servico_desc', 'servico_fk', 'procedencia_fk', 'prioridade_fk', 'situacao_inicial_fk', 'setor_fk', 'localizacao_municipio', 'localizacao_rua', 'localizacao_num', 'localizacao_bairro', 'localizacao_ponto_referencia','localizacao_lat', 'localizacao_long', 'setor_nome', 'servico_nome', 'prioridade_nome', 'procedencia_nome', 'ordem_servico_cod'];
        this.tableFields = ['ordem_servico_pk', 'ordem_servico_cod', 'ordem_servico_criacao', 'prioridade_nome', 'localizacao_rua', 'servico_nome', 'situacao_atual_nome', 'setor_nome'];
        this.verifyDependences = false;
        this.images = [];
        
    }

    init() {
        super.init();
        
        $(document).on('click', '.btn_create_history', () => { this.handleNewSituation() });
        $(document).on('click', '.btn_info', () => { this.handleTimelineHistoric() });
        $(document).on('click', '#btn-mapa-historico', () => { this.handleMapHistoric() });
        $(document).on('click', '.remove_images', () => { this.remove_image()});
        $(document).on('click', '.save_images', () => { this.readImages() });

        $(document).on('click', '.submit', () => { this.save(
            {
                imagens: this.images, 
                situacao_atual_fk: $('#situacao_inicial_fk').val(),

            })
        });

        $('.action_export').click(function () { this.exportData() });

    }

    remove_image() {
        $('#img-input').attr('src', '');
        removeUpload();
    };


    readImages(){
        let render = '';
        var images = this.images;
        try {
                var imageData = $('#img-input').cropper('getCroppedCanvas').toDataURL();
                images.push(imageData);
                render = this.myView.createImageCard(imageData, images.length-1);
                $('#images_saved').append(render);
                this.remove_image();

        } catch (err) {
            console.log(err);
        }
    }

    // blobToBase64(blob) {
    //     return new Promise((resolve) => {

    //         const reader = new FileReader();

    //         reader.onload = function () {
    //             let base64 = reader.result;
    //             resolve(base64);
    //         };
    //         reader.readAsDataURL(blob);
    //     });
    // };

    exportData() {
        data = {
            'data_inicial': $('#data_inicial').val(),
            'data_final': $('#data_final').val()
        };

        let string = `/export/execute?data_inicial=${$('#data_inicial').val()}&data_final=${$('#data_final').val()}`;
        window.open(base_url + string, "target=_blank");
    };

    async save_new_situation(moreFields = null) {
        this.myView.initLoad();

        const sendData = this.myView.createJsonWithFields(this.fields);
        sendData[this.primaryKey] = this.state.selectedId ? this.data.self[this.state.selectedId][this.primaryKey] : '';

        // if (moreFields != null) {
        //     Object.assign(sendData, moreFields);
        // }


        const response = await this.myRequests.send('/send', sendData);

        this.myView.endLoad();



        this.handleResponse(response, sendData);

        return response;
    }


    async handleNewSituation() {
        var render = '';

        await this.myView.checkElementDom('#otimeline');

        render += this.myView.renderCurrentSituation(this.data.self[this.state.selectedId]);
        render += this.myView.renderTimeLineInput();

        $('#otimeline').html(render);
        this.myView.generateSelect(this.data.situacoes, 'situacao_nome', 'situacao_pk', 'situacao_pk_historico');

    }

    async handleTimelineHistoric() {
 
        var render = '';
        var cards = ';'
        const sendData = this.myView.createJsonWithFields(this.fields);

        sendData[this.primaryKey] = this.state.selectedId ? this.data.self[this.state.selectedId][this.primaryKey] : '';
        const response = await this.myRequests.send('/get_historico/'+sendData[this.primaryKey]);

        if (!response) {
            this.myView.showMessage('failed', 'Falha', 'Entre em contato com a central!');
            return;
        }

        //Handle with historic
        this.fillHistoricFields(this.data.self[this.state.selectedId], this.fields);
        if(response.data.historicos.length > 0){
            render += this.myView.renderTimelineHistoric(response.data.historicos);
        }

        //Handle with images
        this.myView.renderCarousel(this.data.self[this.state.selectedId].imagens);

        if(this.data.self[this.state.selectedId].imagens.length > 0){
            cards = this.myView.renderCarouselCards(this.data.self[this.state.selectedId].imagens);
            $('.carousel-inner').html(cards);
        }
        
        render += this.myView.renderCurrentSituation(this.data.self[this.state.selectedId]);

        $('#timeline_historic').html(render);
        $('#card_slider_historico').show();

    }

    handleMapHistoric(){
        if($('#btn-mapa-historico').hasClass('btn-primary')){
            this.showDivMapHistoric(); 
        }else{
            this.hideDivMapHistoric();
        }
    }

    showDivMapHistoric(){
            
        $('#mapa_historico').removeAttr('hidden');;
        $('#btn-mapa-historico').removeClass('btn-primary');
        $('#btn-mapa-historico').addClass('btn-danger');
        $('#btn-mapa-historico').children().removeClass('fa fa-map-marker');
        $('#btn-mapa-historico').children().addClass('fa fa-times');
        

    }

    hideDivMapHistoric(){
        $('#mapa_historico').attr('hidden', true);
        $('#btn-mapa-historico').removeClass('btn-danger');
        $('#btn-mapa-historico').addClass('btn-primary');
        $('#btn-mapa-historico').children().removeClass('fa fa-times');
        $('#btn-mapa-historico').children().addClass('fa fa-map-marker');

    }
    
    fillHistoricFields(object, fields) {
        let local ='';

        fields.forEach(field => {
            if(!field.indexOf('localizacao')){
                object[field] !== null ? local += object[field] + " " : local += ""; 
            }else{
                $(`#${field}_historic`).text(object[field]);
            }     
        });

        $('#address_historic').text(local);
    }

}

const myControl = new Control();
let map;

myControl.init();

initMap = () => {

    map = new GenericMap({
        mapId: 'map',
        insideHideDiv: true,
        config: {
            center: { lat: -22.121265, lng: -51.383400 },
            zoom: 13
        },
        markerConfig: {
            unique: true,
            clickable: true,
            target: 'v_evidencia'
        },
        input: {
            sublocality: 'localizacao_bairro',
            locality: 'localizacao_municipio',
            street: 'localizacao_rua',
            street_number: 'localizacao_num',
            state: false,
            lat: 'localizacao_lat',
            long:'localizacao_long'
        },

        data: [],

        useGeocoder: true,
        useCreateMarker: true,
    });


    // Comportamento de um marker quando clicado
    map.handleMarkerClick = function (event) {
        alert("Clicou!");
        console.log(event);
    }

    // Comportamento de um clique no mapa
    map.handleClick = async function (event) {
        const { useGeocoder, useCreateMarker } = this.state.steps;

        const location = { lat: event.latLng.lat(), lng: event.latLng.lng() };

        // this.state.map.setCenter(event.latLng);

        this.state.lastPositionClicked = location;

        if (useCreateMarker) {
            this.createMarker(location);
        }

        if (useGeocoder) {
            const response = await this.translateLocation(location);
            this.fillInputs(response.address_components, location);
        }
    }

    map.handleDivOpen = function () {

        $('#modal').on('shown.bs.modal', (event) => {

            if (myControl.getSelectedId()) {

                const { localizacao_lat, localizacao_long } = myControl.data.self[myControl.getSelectedId()];
                const location = { lat: parseFloat(localizacao_lat), lng: parseFloat(localizacao_long) };
                // map.setMap(new google.maps.Map(document.getElementById(this.state.mapId), this.state.mapConfig));
                map.initMap();
                map.createMarker(location);
                map.getMap().setCenter(location);
            } else {
                map.initMap();
            }
        });

    }

    map.handleCity = function (id, name) {
        // let exists = false;

        myControl.data.municipios.forEach((municipio) => {
            if (name == municipio.municipio_nome) {
                console.log("Encontrou");
                $(`#${id}`).val(municipio.municipio_pk);
                exists = true;
            }
        });

        // if(!exists){
        //     alert("Infelizmente a cidade em questão não está sob responsabilidade da empresa");
        // }
    }

    map.initMap();
}
