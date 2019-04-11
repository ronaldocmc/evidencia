
/* 
    Props {
      config: Object {
          center,
          zoom
      },
      mapId: String,

    }

*/

// example:
// {
//  center: { lat: -22.121265, lng: -51.383400 },
//  zoom: 13
// }

class GenericMap {

    constructor(props) {

        this.state = {
            map: undefined,
            mapId: props.mapId || 'map',
            geocoder: null,
            insideHideDiv: props.insideHideDiv,

            mapConfig: props.config,
            markerConfig: props.markerConfig,

            marker: null,

            fields: {
                sublocality: props.input.sublocality,
                locality: props.input.locality,
                route: props.input.street,
                street_number: props.input.street_number,
                administrative_area_level_1: props.input.state,
            },

            markers: props.data,

            lastPositionClicked: {
                latitude: null,
                longitude: null,
            },

            steps: {
                useGeocoder: props.useGeocoder,
                useCreateMarker: props.useCreateMarker,
            }
        }

    }

    initMap() {
        const { mapId, mapConfig } = this.state;

        this.state.map = new google.maps.Map(document.getElementById(mapId), mapConfig);
        this.state.geocoder = new google.maps.Geocoder();
        this.state.map.addListener('click', (event) => this.handleClick(event));

        if (this.state.insideHideDiv) {
            this.handleDivOpen();
        }

        this.renderMarkers();
    }

    renderMarkers() {
        const { markers } = this.state;

        markers.forEach(marker => {

            this.createFilledMarker(marker);

        });


        // var marker = new google.maps.Marker({
        //     position: {
        //         lat: parseFloat(ordem.localizacao_lat),
        //         lng: parseFloat(ordem.localizacao_long)
        //     },
        //     map: main_map,
        //     icon: imagem,
        //     id: ordem.ordem_servico_pk,
        //     departamento: ordem.departamento_fk,
        //     tipo_servico: ordem.tipo_servico_pk,
        //     servico: ordem.servico_fk,
        //     situacao: ordem.situacao_atual_fk,
        //     data_criacao: ordem.ordem_servico_criacao,
        //     prioridade: ordem.prioridade_fk,
        //     setor: ordem.setor_fk,
        //     title: ordem.localizacao_rua + ", " + ordem.localizacao_num + " - " + ordem.localizacao_bairro
        // });

        // marker.addListener('click', function () {
        //     main_map.panTo(marker.getPosition());
        //     request_data(this.id, marker.setor);
        //     $('#v_evidencia').modal('show');
        // });

    }

    getImage(prioridade) {
        let imagem = '../assets/img/icons/Markers/Status/';

        switch (prioridade) {
            case "1": {
                imagem += "prioridade_baixa.png";
                break;
            }
            case "2": {
                imagem += "prioridade_alta.png";
                break;
            }
            case "4": {
                imagem += "prioridade_media.png";
                break;
            }
        }
        return imagem;
    }


    fillInputs(address) {
        const { sublocality, locality, route, street_number, administrative_area_level_1 } = this.state.fields;

        address.forEach((data) => {

            if (this.is(data, 'sublocality') && sublocality != false) {
                $(`#${sublocality}`).val(data.long_name);
            }
            if ((this.is(data, 'locality') || this.is(data, 'political')) && locality != false) {
                if (typeof this.handleCity == 'function') {
                    this.handleCity(locality, data.long_name);
                } else {
                    $(`#${locality}`).val(data.long_name);
                }
            }
            if (this.is(data, 'route') && route != false) {
                $(`#${route}`).val(data.long_name);
            }
            if (this.is(data, 'street_number') && street_number != false) {
                $(`#${street_number}`).val(data.long_name);
            }
            if (this.is(data, 'administrative_area_level_1') && administrative_area_level_1 != false) {

                if ($(`#${administrative_area_level_1} option:selected`).text() != endereco[i].short_name) {

                    $(`#${administrative_area_level_1} option`).filter(function () {
                        return this.text == endereco[i].short_name;
                    }).attr('selected', true);
                    estado = endereco[i].short_name;
                }
            }
        });

    }

    async translateLocation(location) {
        const { geocoder } = this.state;

        return new Promise((resolve, reject) => {

            geocoder.geocode({ location }, function (results, status) {
                if (status === 'OK') {
                    if (results[0]) {

                        resolve(results[0]);
                    } else {

                        reject("ERROR");
                    }
                } else {

                }
            });
        });
    }

    createMarker(location) {
        if (this.state.marker != null) {
            this.state.marker.setMap(null);
        }

        this.state.marker = new google.maps.Marker({
            position: location,
            map: this.state.map
        });
    }

    createFilledMarker(marker) {

        let newMarker = new google.maps.Marker({
            position: {
                lat: parseFloat(marker.localizacao_lat),
                lng: parseFloat(marker.localizacao_long)
            },
            map: this.state.map,
            // icon: this.getImage(marker.prioridade_fk),
            props: marker,
        });

        if (this.state.markerConfig.clickable) {
            newMarker.addListener('click', () => { this.handleMarkerClick(marker); });
        }

        return newMarker;
    }

    is(data, type) {
        return (data.types.indexOf(type) !== -1)
    }

    getMap() {
        return this.state.map;
    }

    setMap(map) {
        this.state.map = map;
    }
}