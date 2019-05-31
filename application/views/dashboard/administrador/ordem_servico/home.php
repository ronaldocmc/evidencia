<!--MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">gerenciamento de ordens de serviço</h2>
                        <button class="au-btn au-btn-icon au-btn--blue btn-exportar" data-title="Exportar" data-contentid="export" data-toggle="modal"
                            data-target="#modal" id="btn-exportar">
                            <i class="zmdi zmdi-task"></i>exportar
                        </button>
                        <button class="au-btn au-btn-icon au-btn--blue btn_novo reset_multistep new" data-toggle="modal" data-title="Nova Ordem de Serviço" data-contentid="save"
                            data-target="#modal">
                            <i class="zmdi zmdi-plus"></i>nova ordem de serviço</button>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="collapse" id="collapseHelp">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h3>Guia do Usuário</h3>
                                    </div>
                                </div>
                                <div class="card-body card-user-guide">
                                    <div class="col-md-7">
                                        <p>Bem-vindo a área de Gerenciamento de Ordens de Serviço!</p>
                                        <p> Aqui você poderá realizar algumas operações para controlar as ordens de
                                            serviço <strong>(OS)</strong> que são atendidas pela sua organização.</p>
                                        <br>
                                        <p>É possível visualizar todas as ordens de serviços, seja qual for a situação
                                            dela <strong>(Aberta, Em Andamento, Fechada)</strong>, além de controlar
                                            operações de inserção, edição, remoção ou ativação de uma ordem de serviço!
                                            Cada ordem de serviço possui um código único que é exibido após a sua
                                            criação!
                                        </p><br>

                                        <p> <strong> Importante: </strong> toda ordem de serviço gera um histórico! Ele
                                            é registrado com a situação inicial "Aberta" e vai sendo modificado ao longo
                                            do tempo, conforme o serviço é prestado. Aqui você também poderá alterar o
                                            histórico de uma determinada ordem de serviço, inserindo uma nova situação a
                                            ele, caso necessário.</p><br>

                                        <p>Gerenciar as ordens de serviço favorece uma gestão eficiente e na medida
                                            certa para sua organização!</p>
                                    </div>
                                    <div class="col-md-5 user-guide">
                                        <p><b>Operações permitidas para OS:</b></p>
                                        <div class="col-md-12 functions-page">
                                            <div class="row">
                                                <div class="col-md-2 icon-guide">
                                                    <button type="button" disabled="true"
                                                        class="btn btn-sm btn-primary reset_multistep" title="Editar">
                                                        <div class="d-none d-block">
                                                            <i class="fas fa-plus fa-fw"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                                <div class="col-md-10 text-guide">Inserir uma nova OS</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 icon-guide">
                                                    <button type="button" disabled="true"
                                                        class="btn btn-sm btn-primary reset_multistep" title="Editar">
                                                        <div class="d-none d-block">
                                                            <i class="fas fa-edit fa-fw"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                                <div class="col-md-10 text-guide">Editar uma OS existente</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 functions-page">
                                                <br>
                                                <p><b>Operações permitidas para Histórico:</b></p>
                                                <div class="col-md-12 functions-page">
                                                    <div class="row">
                                                        <div class="col-md-2 icon-guide">
                                                            <button type="button" disabled="true"
                                                                class="btn btn-sm btn-success reset_multistep"
                                                                title="Editar">
                                                                <div class="d-none d-block">
                                                                    <i class="fas fa-plus fa-fw"></i>
                                                                </div>
                                                            </button>
                                                        </div>
                                                        <div class="col-md-10 text-guide">Inserir um nova situação ao
                                                            histórico</div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2 icon-guide">
                                                            <button type="button" disabled="true"
                                                                class="btn btn-sm btn-secondary reset_multistep"
                                                                title="Editar">
                                                                <div class="d-none d-block">
                                                                    <i class="far fa-clock fa-fw"></i>
                                                                </div>
                                                            </button>
                                                        </div>
                                                        <div class="col-md-10 text-guide">Visualizar todo histórico de
                                                            uma OS</div>
                                                        <div class="row">
                                                            <div class="col-md-12>">
                                                                <br>
                                                                <p><strong>Qualquer dúvida entre em contato com o
                                                                        suporte na sua organização!</p></strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





            <div class="row py-5">
                <div class="col-lg-12">
                    <div class="au-card d-flex flex-column">
                        <h2 class="title-1 m-b-25">
                            <i style="cursor: pointer; color: gray" class="fas fa-info pull-right"
                                data-toggle="collapse" href="#collapseHelp" role="button" aria-expanded="false"
                                aria-controls="collapseHelp"></i>
                            Ordens de Serviço</h2>
                        <div class="">
                            <h5>Filtrar por</h5><br>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="filter-ativo">Mostrar</label>
                                    <select name="filter-ativo" id="filter-ativo" class="form-control">
                                        <!-- <option value="semana">Ultima Semana</option> -->
                                        <option value="-1">Todas</option>
                                        <option value="1">Abertas</option>
                                        <option value="2">Em andamento</option>
                                        <option value="5">Finalizadas</option>
                                        <option value="4">Recusadas (Não Procede)</option>
                                        <option value="3">Recusadas (Repetido)</option>
                                    </select><br>
                                </div>
                            </div>
                        </div>
                        <div id="loading">
                            <div class="center" style="text-align: center">
                                <img src="<?= base_url('assets/images/loading.gif'); ?>" id="v_loading">
                            </div>
                        </div>
                        <div class="table-responsive table--no-card m-b-40" style="display: none;">
                            <table id="ordens_servico" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th id="id_as">id</th>
                                        <th>Código</th>
                                        <th>Data</th>
                                        <th>Prioridade</th>
                                        <th>Endereço</th>
                                        <th>Serviço</th>
                                        <th>Situação</th>
                                        <th>Setor</th>
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="copyright">
                        <p>Copyright © 2018 Colorlib. All rights reserved. Template by
                            <a href="https://colorlib.com">Colorlib</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">TITLE</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="content">
            </div>
        </div>
    </div>
</div>

<div id="save" class="d-none">
<form class="msform" style="margin-top: 10px !important;">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card bg-light mb-3">
                                <div class="card-header">
                                    <h4 class="card-title"> Informações Gerais </h4>
                                </div>
                                <div class="card-body text-secondary">
                                    <input type="hidden" id="ordem_servico_pk" value="" name="ordem_servico_pk">
                                    <input type="hidden" id="localizacao_pk" value="" name="localizacao_pk">
                                    <div class="row form-group">
                                        <div class="col-12">
                                            <label for="ordem_servico_desc"><strong>Descrição*</strong></label>
                                            <textarea class="form-control" id="ordem_servico_desc"
                                                name="ordem_servico_desc" class="form-control" required="true"
                                                maxlength="200"></textarea>
                                            <small class="form-text text-muted">Por favor, informe a descrição da Ordem
                                                de Serviço</small>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-7 col-md-4">
                                            <label for="departamento"><strong>Departamento*</strong></label>
                                            <select class="form-control" onchange="myControl.handleSelects($(this).val());"id="departamento_fk" name="departamento"
                                                required="true">

                                            </select>
                                        </div>
                                        <div class="col-7 col-md-4">
                                            <label for="tipo_servico"><strong>Tipo de Serviço*</strong></label>
                                            <select class="form-control" id="tipo_servico_fk" name="tipo_servico"
                                                required="true">
                                            </select>
                                        </div>
                                        <div class="col-7 col-md-4">
                                            <label for="servico_pk"><strong>Serviço*</strong></label>
                                            <select class="form-control" id="servico_fk" name="servico_fk"
                                                required="true">
                                            </select>
                                            <small class="form-text text-muted">Por favor, informe o Serviço</small>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-7 col-md-4">
                                            <label for="prioridade_pk"><strong>Prioridade*</strong></label>
                                            <select class="form-control" id="prioridade_fk" name="prioridade_fk"
                                                required="true">
                                            </select>
                                            <small class="form-text text-muted">Por favor, informe a Prioridade</small>
                                        </div>
                                        <div class="col-7 col-md-4">
                                            <label for="situacao_pk"><strong>Situação Inicial*</strong></label>
                                            <select class="form-control" id="situacao_inicial_fk" name="situacao_inicial_fk"
                                                required="true">
                                            </select>
                                            <small class="form-text text-muted">Por favor, informe a Situação</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="card bg-light mb-3" id="info_cidadao">
                                <div class="card-header">
                                    <h4 class="card-title"> Informações Cidadão </h4>
                                </div>
                                <div class="card-body text-secondary">
                                    <div class="row form-group">
                                        <div class="col col-md-1">
                                            <label for="nome-input" class=" form-control-label" required="true">
                                                Nome
                                            </label>
                                        </div>
                                        <div class="col-12 col-md-11">
                                            <input type="text" id="nome-input" name="pessoa_nome"
                                                placeholder="Nome Completo" class="form-control nome-input" required
                                                maxlength="50" minlength="5" required>
                                            <small class="form-text text-muted">Por favor, informe o nome completo do
                                                cidadão</small>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-12 col-md-1">
                                            <label for="cpf-input" class=" form-control-label">
                                                CPF
                                            </label>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <input type="text" id="cpf-input" name="pessoa_cpf" placeholder="CPF"
                                                class="form-control cpf-input" required>
                                            <small class="form-text text-muted">Por favor, informe o CPF do
                                                cidadão</small>
                                        </div>
                                        <div class="col-12 col-md-1">
                                            <label for="telefone-input" class=" form-control-label">Tel</label>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <input type="text" id="telefone-input" name="contato_tel"
                                                placeholder="Telefone" class="form-control telefone-input">
                                            <small class="help-block form-text">Por favor, informe o telefone do
                                                cidadão</small>
                                        </div>
                                        <div class="col-12 col-md-1">
                                            <label for="celular-input" class=" form-control-label">Cel</label>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <input type="text" id="celular-input" name="contato_cel"
                                                placeholder="Celular" class="form-control celular-input">
                                            <small class="help-block form-text">Por favor, informe o celular do
                                                cidadão</small>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-12 col-md-1">
                                            <label for="email-input" class=" form-control-label">
                                                Email
                                            </label>
                                        </div>
                                        <div class="col-12 col-md-11">
                                            <input type="email" id="email-input" name="contato_email"
                                                placeholder="Email" class="form-control email-input" required="true">
                                            <small class="help-block form-text">Por favor, informe o email do
                                                cidadão</small>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="card bg-light mb-3">
                                <div class="card-header">
                                    <h4 class="card-title"> Localização </h4>
                                </div>
                                <div class="card-body text-secondary">
                                    <div class="row form-group">
                                        <div class="col-md-8">
                                            <label for="localizacao_municipio"
                                                class="form-control-label"><strong>Cidade*</strong></label>
                                            <select class="form-control endereco" id="localizacao_municipio" name="localizacao_municipio"
                                                required="true">

                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="setor_fk"><strong>Setor*</strong></label>
                                            <select class="form-control" id="setor_fk" name="setor_fk" required="true">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-10">
                                            <label for="localizacao_rua"><strong>Endereço*</strong></label>
                                            <input class="form-control" type="text"
                                                id="localizacao_rua" name="localizacao_rua">
                                            <small class="form-text text-muted">Insira o logradouro para buscar o
                                                local</small>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="localizacao_num"
                                                class=" form-control-label"><strong>N°</strong></label>
                                            <input type="number" id="localizacao_num" name="localizacao_num"
                                                class="form-control localizacao_num endereco" min="0" required="true">
                                        </div>
                                    </div>
                                    <div class="row form-group">

                                        <div class="col-md-6">
                                            <label for="localizacao_bairro"
                                                class="form-control-label"><strong>Bairro</strong></label>
                                            <input type="text" name="localizacao_bairro" id="localizacao_bairro" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="localizacao_ponto_referencia" class=" form-control-label">Ponto de
                                                Referência</label>
                                            <input type="text" id="localizacao_ponto_referencia" name="localizacao_ponto_referencia"
                                                class="form-control referencia">
                                            <small class="form-text text-muted">Insira um ponto de referência para
                                                buscar o local</small>
                                        </div>
                                    </div>
                                    <div class="row form-group" style="margin-top: 30px !important;">
                                        <input type="hidden" id="localizacao_lat"  name="localizacao_lat">
                                        <input type="hidden" id="localizacao_long"  name="localizacao_long">
                                        <input type="hidden" id="ordem_servico_cod"  name="ordem_servico_cod">
                                        <!-- <input type="hidden" id="servico_nome"  name="servico_nome">
                                        <input type="hidden" id="setor_nome"  name="setor_nome">
                                        <input type="hidden" id="prioridade_nome" name="prioridade_nome"> -->
                                        <div class="col-12">
                                        <div id="map"></div>
                                            <small class="form-text text-muted">Visualize ou selecione o local no
                                                mapa</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card bg-light mb-3" id="card_imagem">
                                <div class="card-header"><h4 class="card-title"> Imagem Evidência </h4></div>
                                <div class="card-body text-secondary">
                                    <div class="row form-group">
                                        <div class="col-12" id="image-upload-div">
                                            <div class="image-upload-wrap" style="">
                                                <input class="file-upload-input" type="file" onchange="readURL(this);" accept="image/*" id="input-upload">
                                                <div class="drag-text">
                                                    <h3>Ou clique/arraste e solte uma imagem aqui</h3>
                                                </div>
                                            </div>
                                            <div class="file-upload-content" style="display: none;">
                                                <img id="img-input" class="file-upload-image" src="" alt="your image">
                                                <div class="col-12" id="images_buttons" >
                                                    <button type="button" class="btn btn-danger clean_input_images">Remover</button>
                                                    <button type="button" class="btn btn-success save_images">Salvar</button>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">Por favor, se necessário, carregue a imagem</small>
                                            <div class="col-12" id="images_saved" style="margin-top: 20px; display:flex;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- fecha card principal -->
                            <?php if ($this->session->user['is_superusuario']): ?>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="senha" class="form-control-label">Digite sua senha</label>
                                    <input type="password" name="senha" id="senha" class="form-control" required>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="text-center">
                                <button type="button" class="btn btn-primary submit_os">
                                    <i class="fa fa-dot-circle-o"></i> Finalizar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
</div>



<!-- MODAL APAGAR -->
<div id="delete" class="d-none">
    <div class="modal-body">
        <div class="card">
            <div class="card-header"><b>Detalhes da Ordem</b></div>
            <div class="card-body" id="show_details_ordem" style="display:flex;"></div>
         </div>
        <div class="modal-footer">
            <div class="btn-group">
                <button type="button" id="confirm_delete" class="btn btn-sm btn-danger pull-right js-tooltip"
                    data-toggle="tooltip" data-placement="bottom" title="Apagar permanentemente">
                    <div class="d-none d-sm-block">
                        <i class="fas fa-trash"></i> Apagar
                    </div>
                </button>
                <button type="button" class="btn btn-sm btn-secondary btn-fechar pull-right"
                    data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL PROTOCOLO DE ATENDIMENTO -->
<div class="modal fade" id="protocolo">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="width: 380px !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Protocolo de Atendimento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><strong>Informe para o cidadão:</strong></div>
                    </div>
                    <div class="card-body" id="numero-protocolo" style="text-align: center;"></div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <button id="protocol-copy" style="width: 50px !important;" type="button"
                            class="btn btn-sm btn-success pull-right btn-copy js-tooltip js-copy" data-toggle="tooltip"
                            data-placement="bottom" title="Copiar">
                            <div class="d-none d-sm-block">
                                <i class="fas fa-copy fa-fw"></i>
                            </div>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger btn-fechar pull-right" id="fechar-atividade"
                            data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style type="text/css">
    .icon-copy {
        width: 16px;
        height: 16px;
        padding: 0;
        margin: 0;
        vertical-align: middle;
    }
</style>



<!-- MODAL ADICIONAR SITUAÇÃO ATUAL ORDEM SERVIÇO -->
<div id="create_history" class="d-none">
    <div class="modal-body">
        <div class="form-group">
            <!-- <div id="loading">
                <div align="center" class="center">
                    <img src="<?= base_url('assets/images/loading.gif'); ?>" id="v_loading">
                </div>
            </div> -->
            <div class="qa-message-list py-5" id="otimeline" style="margin-top: 10px !important; padding-top: 10px !important;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success btn_save_activity pull-right" style="margin-right: 10px;">
                    <i class="fa fa-dot-circle-o"></i>
                    Salvar
                </button>
                <button type="button" class="btn btn-sm btn-danger btn-fechar pull-right" id="fechar-atividade" data-dismiss="modal">
                    Fecharf
                </button>
                <input type="hidden" id="historico_pk" value="" name="historico_pk">
            </div>
        </div>
    </div>
</div>





<!-- <div class="modal fade" id="atividade">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Adicionar Situação da Ordem de serviço</h4>
                <h4 class="modal-title 2"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div> -->
             <!-- <div align="center" class="center">
                <img width="150px" src="<?=base_url('assets/images/loading.gif'); ?>" id="ov_loading"
                            alt="Carregando">
                    </div> -->
        <!-- </div>
    </div>
</div> -->

<div id="export" class="d-none">
    <div class="modal-body">
        <div class="row pb-5">
            <div class="col-md-6">

                De:<input type="date" class="form-control" id="data_inicial" name="data_inicial" required>
            </div>
            <div class="col-md-6">

                Até:<input type="date" class="form-control" id="data_final" name="data_final" required>
            </div>
        </div>
        <button type="button" class="btn au-btn btn-primary form-control action_export"><i
                class="fa fa-dot-circle-o"></i> Exportar dados
        </button>
    </div>
</div>


<!-- MODAL HISTÓRICO ORDEM SERVIÇO -->
<div id="info" class="d-none">
    <div class="modal-body">
        <div class="row pb-2" style="text-align: center;">
            <div class="col-12">
                <div class="card-group">
                    <div class="card col-md-4" style="padding-left: 0px !important; padding-right: 0px !important;">
                        <div class="card-header">
                            <strong>Código:</strong>
                        </div>
                        <div class="card-body card-block">
                            <p id="ordem_servico_cod_historic"></p>
                        </div>
                        </div>
                        <div class="card col-md-4"style="padding-left: 0px !important; padding-right: 0px !important;">
                            <div class="card-header">
                                <strong>Descrição:</strong>
                        </div>
                        <div class="card-body card-block">
                            <p id="ordem_servico_desc_historic"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pb-2" style="text-align: center;">
            <div class="col-12">
                <div class="card-group">
                    <div class="card col-md-4" style="padding-left: 0px !important; padding-right: 0px !important;">
                        <div class="card-header">
                            <strong>Prioridade:</strong>
                        </div>
                        <div class="card-body card-block">
                            <p id="prioridade_nome_historic"></p>
                        </div>
                    </div>
                    <div class="card col-md-4"
                        style="padding-left: 0px !important; padding-right: 0px !important;">
                        <div class="card-header">
                            <strong>Servico:</strong>
                        </div>
                        <div class="card-body card-block">
                            <p id="servico_nome_historic"></p>
                        </div>
                    </div>
                    <div class="card col-md-4"
                        style="padding-left: 0px !important; padding-right: 0px !important;">
                        <div class="card-header">
                            <strong>Setor:</strong>
                        </div>
                        <div class="card-body card-block">
                            <p id="setor_nome_historic"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pb-2" style="text-align: center;">
            <div class="col-12">
                <div class="card-group">
                    <div class="card" style="padding-left: 0px !important; padding-right: 0px !important;">
                        <div class="card-header">
                            <strong>Endereço:</strong>
                            <button type="button" class="btn btn-sm btn-primary pull-right"
                                id="btn-mapa-historico">
                                <i class="fa fa-map-marker"></i>
                            </button>
                        </div>
                        <div class="card-body card-block">
                            <p id="address_historic"></p>
                        </div>
                        <div class="col-12 col-md-12 pb-1" id="mapa_historico" hidden="true">
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div align="center" class="center">
            <img width="150px" src="<?=base_url('assets/images/loading.gif'); ?>" id="v_loading"
                alt="Carregando">
        </div> -->
        <div class="container-fluid" id="card_slider_historic"></div>
        <div class="qa-message-list" id="timeline_historic" style="padding-top: 5px;"></div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-danger btn-fechar pull-right" id="fechar-historico"
                data-dismiss="modal">
                Fechar
            </button>
            <input type="hidden" id="historico_pk" value="" name="historico_pk">
        </div>
    </div>
</div>


<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
</script>

