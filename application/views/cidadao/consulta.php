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
                        <div align="center" class="center" id="nao_encontrada">
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

                            <!-- <div class="col-md-12">
                                <div class="alert alert-warning" role="alert">
                                    <h4 class="alert-heading">Evidência sem dono!</h4>
                                    <p>Essa evidência não foi associada a nenhum cidadão. Se foi você que fez esse
                                        relato,
                                        você pode se associar a ela <a href="#">clicando aqui</a>.</p>
                                    <hr>
                                    <p class="mb-0"><a href="#" data-toggle="modal" data-target="#modalInfo">Por que
                                            me associar a uma evidência?</a></p>
                                </div>
                            </div> -->

                            <div class="col-md-12 p-5" id="finalizado">
                                <div class="alert alert-success" role="alert">
                                    <h4 class="alert-heading">Evidência finalizada!</h4>
                                    <p>Essa evidência já foi finalizada</p>
                                </div>
                            </div>

                            <div class="row p-5">
                                <div class="col-md-6">
                                    <span class="font-weight-bold">Localização: </span>
                                    <p class="mb-1" id="os_local"></p>
                                    <!-- <p>Acme Inc</p> -->
                                    <p class="mb-1" id="os_city"></p>
                                    <span class="font-weight-bold">Descrição: </span>
                                    <p class="mb-1" id="os_desc"></p>
                                </div>

                                <div class="col-md-6 text-right">
                                    <p class="font-weight-bold mb-4">Detalhes</p>
                                    <p class="mb-1"><span class="text-muted">Prioridade: </span> <span id="os_priority"></span></p>
                                    <p class="mb-1"><span class="text-muted">Departamento: </span> <span id="os_dept"></span></p>
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




    <!-- MODAL -->

    <div class="modal fade bd-example-modal-lg" id="modalInfo" tabindex="-1" role="dialog" aria-labelledby="modalInfo"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="TituloModalLongoExemplo">Associar a uma evidência</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 class="text-muted">O que é uma evidência?</h4>
                    <p>Aqui na Prudenco nós apelidamos as ocorrências de serviços pela cidade de Evidência, e esse
                        apelido foi passado para o nosso <a href="#">Sistema de gestão da cidade</a>.</p>


                    <h4 class="text-muted">Por que me associar?</h4>
                    <p>Nós queremos melhorar o nosso contato com a população. Quando você se associa a evidências, você
                        passa a ter acesso a uma área do nosso sistema específica para associados.</p>
                    <p>Nessa área você tem acesso a todas as suas evidências e também a um canal de comunicação direto
                        com a gente, possibilitando abrir novos chamados através da internet.</p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary">Desejo me associar</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    function contaFotos(response) {
        var temFoto = true;

        response.historico.forEach((value) => {
            if (!value.foto) {
                temFoto = false;
                return;
            }
        });

        return temFoto;
    }

    $(document).ready(() => {
        $('#loading').hide();
        $('#nao_encontrada').hide();
        $('#os').hide();
        $('#finalizado').hide();
    });

    $(search).click(function () {
        $('#finalizado').hide();
        $('#loading').show();
        $('#nao_encontrada').hide();

        var table = $('#os_historico');
        var div_fotos = $('#fotos');

        table.empty();
        div_fotos.empty();
        var cod = ($('#os_protocol').val()).split("-");

        $.ajax({
            url: `${base_url}/Cidadao/getOs?protocol=${cod[1].substr(0,3)}`,
            method: 'GET'
        }).done(function (response) {
            $('#loading').hide();

            if (response.code == 200) {
                $(os_search).hide();
                $(os).fadeIn(500);

                $('#os_local').html(`${response.os.logradouro_nome},${response.os.local_num}`);
                $('#os_city').html(`${response.os.municipio_nome} - ${response.os.estado_fk}`);
                $('#os_desc').html(`${response.os.ordem_servico_desc}`);

                $('#os_priority').html(`${response.os.prioridade_nome}`);
                $('#os_serv').html(`${response.os.servico_nome}`);
                $('#os_sit').html(`${response.os.situacao_atual_nome}`);
                $('#os_dept').html(`${response.os.departamento_nome}`);


                if (response.os.situacao_atual != 1 && response.os.situacao_atual != 2) {
                    $('#finalizado').fadeIn(1000);
                }

                var fotos = "";

                response.historico.forEach((value) => {
                    let html = '';
                    html += `<tr>`;
                    html += `<td>${value.situacao}</td>`;
                    html += `<td>${(value.comentario) ? value.comentario : "Nenhum comentário adicionado"}</td>`;
                    html += `<td>${new Date(value.data).toLocaleDateString("pt-BR")}</td>`;
                    html += `</tr>`;
                    if (value.foto) {
                        fotos += `<img width="150px" src="${value.foto}"/>`;
                    }

                    table.append(html);
                });

                if (contaFotos(response)) {
                    $('#title_fotos').html('Histórico de Fotos');
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