<!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Área do Cidadão</title>


    <!-- Jquery JS-->
    <script src="<?php echo base_url('assets/vendor/jquery-3.2.1.min.js')?>"></script>
    <!-- Bootstrap -->
    <link href="<?php echo base_url('assets/vendor/bootstrap-4.1/bootstrap.min.css')?>" rel="stylesheet" media="all">
    <script src="<?php echo base_url('assets/vendor/bootstrap-4.1/bootstrap.min.js')?>"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt"
        crossorigin="anonymous">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/images/icon/logo-mini.png') ?>" />

    <script src="<?= base_url('assets/js/constants.js') ?>"></script>
    <style>
    .form-control-borderless {
        border: none;
    }

    .form-control-borderless:hover, .form-control-borderless:active, .form-control-borderless:focus {
        border: none;
        outline: none;
        box-shadow: none;
    }

    body{
        background-color: #e5e5e5;
    }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="row p-1">

                            <div class="text-center col-md-12">
                                <img src="<?= base_url('assets/images/icon/logo.png') ?>">
                                <hr>
                                <h4 class="text-muted">Saiba o que a PRUDENCO está fazendo por você</h4>
                            </div>

                            <div class="col-md-12">
                                <div class="row justify-content-center p-5">
                                    <div class="col-12 col-md-10 col-lg-8">
                                        <div class="card card-sm">
                                            <div class="card-body row align-items-center">
                                                <div class="col-auto">
                                                    <i class="fas fa-search h4 text-body"></i>
                                                </div>
                                                <!--end of col-->
                                                <div class="col">
                                                    <!-- LIMPLR-2018/28 -> para testes -->
                                                    <input id="os_protocol" autofocus class="form-control form-control-lg form-control-borderless"
                                                        type="search" placeholder="Digite aqui o número do protocolo" required="true">
                                                </div>
                                                <!--end of col-->
                                                <div class="col-auto">
                                                    <button id="search" class="btn btn-lg btn-primary" type="submit">Buscar</button>
                                                </div>
                                                <!--end of col-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end of col-->
                                </div>
                            </div>

                        </div>

                        <div id="loading">
                            <div align="center" class="center">
                                <img width="150px" src="<?= base_url('assets/images/loading.gif') ?>" id="v_loading">
                            </div>
                        </div>
                        <div align="center" id="nao_encontrada">
                            <h3> Desculpe, evidência não encontrada. </h3>
                        </div>

                        <hr class="my-5">

                        <section id="os_search">
                            <div class="text-center col-md-12">
                                <h4>Acompanhe o que estamos fazendo</h4>
                                <p>Para isso, fizemos a área de acesso da população no nosso <a href="#">Sistema de
                                        Gerênciamento da Cidade - EVIDÊNCIA</a></p>
                                <p>Aqui você pode ver todo o procedimento que fizemos ou estamos fazendo para resolver
                                    o seu problema.</p>
                            </div>
                        </section>


                        <section id="os">
                            <div class="col-md-12 p-5" id="finalizado">
                                <div class="alert alert-success" role="alert">
                                    <h4 class="alert-heading" align="center">Evidência finalizada!</h4>
                                </div>
                            </div>

                            <div class="row p-5">
                                <div class="col-md-6">
                                    <span class="font-weight-bold">Localização: </span>
                                    <p class="mb-1" id="os_local"></p>
                                    <p class="mb-1" id="os_city"></p>
                                    <span class="font-weight-bold">Descrição: </span>
                                    <p class="mb-1" id="os_desc"></p>
                                </div>

                                <div class="col-md-6 text-right">
                                    <p class="font-weight-bold mb-4">Detalhes</p>
                                    <p class="mb-1"><span class="text-muted">Prioridade: </span> <span id="os_priority"></span></p>
                                    <p class="mb-1"><span class="text-muted">Serviço: </span> <span id="os_serv"></span></p>
                                    <p class="mb-1"><span class="text-muted">Situação Atual: </span> <span id="os_sit"></span></p>
                                </div>
                            </div>

                            <hr>

                            <div class="row p-5">
                                <h4 class="text-muted" id="title_fotos">Histórico de Fotos</h4>
                                <div class="col-md-12" id="fotos"></div>
                            </div>


                            <hr>

                            <div class="row p-5">
                                <h4 class="text-muted">Histórico</h4>
                                <div class="col-md-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="border-0 text-uppercase small font-weight-bold">Situação</th>
                                                <th class="border-0 text-uppercase small font-weight-bold">Comentário</th>
                                                <th class="border-0 text-uppercase small font-weight-bold">Data</th>
                                            </tr>
                                        </thead>
                                        <tbody id="os_historico">

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </section>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>

</html>

<script>
    $(document).ready(() => {
        $('#loading').hide();
        $('#nao_encontrada').hide();
        $('#os').hide();
        $('#finalizado').hide();
    });

    $(search).click(function () {
        $('#os').hide();
        $('#finalizado').hide();
        $('#nao_encontrada').hide();
        $('#loading').show();

        var table = $('#os_historico');
        var div_fotos = $('#fotos');

        table.empty();
        div_fotos.empty();
        var cod = ($('#os_protocol').val());

        $.ajax({
            url: `${base_url}/Cidadao/getOs?protocol=${cod}`,
            method: 'GET'
        }).done(function (response) {
            $('#loading').hide();

            if (response.code == 200) {
                $(os_search).hide();
                $(os).fadeIn(500);

                $('#os_local').html(`${response.data.os[0].localizacao_rua}, ${response.data.os[0].localizacao_num}`);
                $('#os_city').html(`${response.data.os[0].municipio_nome} - ${response.data.os[0].estado_fk}`);
                $('#os_desc').html(`${response.data.os[0].ordem_servico_desc}`);

                $('#os_priority').html(`${response.data.os[0].prioridade_nome}`);
                $('#os_serv').html(`${response.data.os[0].servico_nome}`);
                $('#os_sit').html(`${response.data.os[0].situacao_nome}`);

                if (response.data.os[0].situacao_atual_fk != 1 && response.data.os[0].situacao_atual_fk != 2) {
                    $('#finalizado').fadeIn(1000);
                }

                var fotos = "";

                response.data.historico.forEach((value) => {
                    let html = '';
                    html += `<tr>`;
                    html += `<td>${value.situacao_nome}</td>`;
                    html += `<td>${(value.historico_ordem_comentario) ? value.historico_ordem_comentario : "Nenhum comentário adicionado"}</td>`;
                    html += `<td>${new Date(value.historico_ordem_tempo).toLocaleDateString("pt-BR")}</td>`;
                    html += `</tr>`;

                    table.append(html);
                });

                if (response.data.imagens.length !== 0) {
                    response.data.imagens.forEach((image) => {
                        fotos += `<img width="150px" src="${base_url + '/' + image.imagem_os}"/>`;
                    });
                    div_fotos.append(fotos);
                }
                else {
                    $('#title_fotos').html('Essa evidência não possui fotos.');
                }
            }
            else if (response.code == 404) {
                $('#nao_encontrada').show();
            }
        });
    });


</script>