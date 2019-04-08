/*
 * == Variáveis globais: == 
 * 
 * @Boolean: is_superusuario
 * 
 * 
 * @String: base_url
 * 
 */

var table = $('#ordens_servico').DataTable();

class View extends GenericView {
    // FUNÇÃO GENÉRICA PARA PREENCHER UM SELECT
    // PARA PREENCHER UM MULTIPLE SELECT


    constructor() {
        super();
    }

    init(data, tableFields, primaryKey) {
        super.init(data, tableFields, primaryKey);

        console.log(data);

        this.generateSelect(data.departamentos, 'departamento_nome', 'departamento_pk', 'departamento_fk');
        this.generateSelect(data.tipos_servicos, 'tipo_servico_nome', 'tipo_servico_pk', 'tipo_servico_fk');
        this.generateSelect(data.servicos, 'servico_nome', 'servico_pk', 'servico_fk');
        this.generateSelect(data.procedencias, 'procedencia_nome', 'procedencia_pk', 'procedencia_fk');
        this.generateSelect(data.prioridades, 'prioridade_nome', 'prioridade_pk', 'prioridade_fk');
        this.generateSelect(data.situacoes, 'situacao_nome', 'situacao_pk', 'situacao_inicial_fk');

        this.generateSelect(data.municipios, 'municipio_nome', 'municipio_pk', 'localizacao_municipio');
        this.generateSelect(data.setores, 'setor_nome', 'setor_pk', 'setor_fk');

    }


    // generateButtons(condition, i) {
    //     return `<div class='btn-group'>` +
    //         (
    //             condition == 1 ?
    //                 this.createButton('edit', 'save', 'primary', 'Editar', i, 'fa-edit') +
    //                 this.createButton('deactivate', 'deactivate', 'danger', 'Desativar', i, 'fa-times') +
    //                 this.createButton('change_password', 'password', 'success', 'Alterar senha', i, 'fa-lock')
    //                 :
    //                 this.createButton('activate', 'activate', 'success', 'Ativar', i, 'fa-power-off')
    //         ) +
    //         `</div>`;
    // }

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
        this.fields = ['ordem_servico_desc', 'servico_fk', 'procedencia_fk', 'prioridade_fk', 'situacao_inicial_fk', 'setor_fk', 'localizacao_rua', 'localizacao_num', 'localizacao_bairro', 'localizacao_ponto_referencia' ];
        this.tableFields = ['ordem_servico_pk', 'ordem_servico_cod', 'ordem_servico_criacao', 'prioridade_nome', 'localizacao_rua', 'servico_nome', 'situacao_atual_nome', 'setor_nome'];
        this.verifyDependences = false;
    }

    remove_image() {
        $('#img-input').attr('src', '');
        removeUpload();
    };

    blobToBase64(blob) {
        return new Promise((resolve) => {

            const reader = new FileReader();
            reader.onload = function () {
                let base64 = reader.result;
                resolve(base64);
            };
            reader.readAsDataURL(blob);
        });
    };;

}

const myControl = new Control();

myControl.init();


initMap = () => {
    const map = new GenericMap({
        mapId: 'map',
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
            sublocality: false,
            locality: false,
            street: 'rua',
            street_number: false,
            state: false,
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
            this.fillInputs(response.address_components);
        }
    }

    map.initMap();
}
