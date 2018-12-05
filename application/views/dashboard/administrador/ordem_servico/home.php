
<!--MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">gerenciamento de ordens de serviço</h2>
                        <!-- <input type="hidden" id="ordem_servico_pk" name="ordem_servico_pk" class="form-control"> -->
                        <button class="au-btn au-btn-icon au-btn--blue reset_multistep" data-toggle="modal" data-target="#ce_ordem_servico" id="btn-nova-ordem">
                            <i class="zmdi zmdi-plus"></i>nova ordem de serviço
                        </button>
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
                                        <p> Aqui você poderá realizar algumas operações para controlar as ordens de serviço <strong>(OS)</strong> que são atendidas pela sua organização.</p><br>
                                        <p>É possível visualizar todas as ordens de serviços, seja qual for a situação dela <strong>(Aberta, Em Andamento, Fechada)</strong>, além de controlar operações de inserção, edição, remoção ou ativação de uma ordem de serviço! Cada ordem de serviço possui um código único que é exibido após a sua criação! Caso a OS esteja sendo solicitada por um cidadão via telefone, não se esqueça de alterar o tipo de procedência <strong>(Externa)</strong> e registrar os dados do cidadão na ordem, além de informá-lo o código da OS.</p><br>

                                        <p> <strong> Importante: </strong> toda ordem de serviço gera um histórico! Ele é registrado com a situação inicial "Aberta" e vai sendo modificado ao longo do tempo, conforme o serviço é prestado. Aqui você também poderá alterar o histórico de uma determinada ordem de serviço, inserindo uma nova situação a ele, caso necessário.</p><br>

                                        <p>Gerenciar as ordens de serviço favorece uma gestão eficiente e na medida certa para sua organização!</p>
                                    </div>
                                    <div class="col-md-5 user-guide">
                                        <p><b>Operações permitidas para OS:</b></p>
                                        <div class="col-md-12 functions-page" >
                                            <div class="row">
                                                <div class="col-md-2 icon-guide">
                                                    <button type="button" disabled="true" class="btn btn-sm btn-primary reset_multistep" title="Editar">
                                                        <div class="d-none d-block">
                                                            <i class="fas fa-plus fa-fw"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                                <div class="col-md-10 text-guide">Inserir uma nova OS</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 icon-guide">
                                                    <button type="button" disabled="true" class="btn btn-sm btn-primary reset_multistep" title="Editar">
                                                        <div class="d-none d-block">
                                                            <i class="fas fa-edit fa-fw"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                                <div class="col-md-10 text-guide">Editar uma OS existente</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 icon-guide">
                                                    <button type="button" class="btn btn-sm btn-danger" disabled="true" title="Desativar">
                                                        <div class="d-none d-block">
                                                            <i class="fas fa-times fa-fw"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                                <div class="col-md-10 text-guide">Desativar uma OS inativa</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 icon-guide">
                                                    <button type="button" class="btn btn-sm btn-success" disabled="true" title="Reativar">
                                                        <div class="d-none d-block">
                                                            <i class="fas fa-power-off fa-fw"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                                <div class="col-md-10 text-guide">Ativar uma OS novamente</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 functions-page" >
                                                <br><p><b>Operações permitidas para Histórico:</b></p>
                                                <div class="col-md-12 functions-page" >
                                                    <div class="row">
                                                        <div class="col-md-2 icon-guide">
                                                            <button type="button" disabled="true" class="btn btn-sm btn-success reset_multistep" title="Editar">
                                                                <div class="d-none d-block">
                                                                    <i class="fas fa-plus fa-fw"></i>
                                                                </div>
                                                            </button>
                                                        </div>
                                                        <div class="col-md-10 text-guide">Inserir um nova situação ao histórico</div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2 icon-guide">
                                                            <button type="button" disabled="true" class="btn btn-sm btn-secondary reset_multistep" title="Editar">
                                                                <div class="d-none d-block">
                                                                    <i class="far fa-clock fa-fw"></i>
                                                                </div>
                                                            </button>
                                                        </div>
                                                        <div class="col-md-10 text-guide">Visualizar todo histórico de uma OS</div>
                                                        <div class="row">
                                                            <div class="col-md-12>">
                                                                <br><p><strong>Qualquer dúvida entre em contato com o suporte  na sua organização!</p></strong>
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
                            <i style="cursor: pointer; color: gray" class="fas fa-info pull-right" data-toggle="collapse" href="#collapseHelp" role="button" aria-expanded="false" aria-controls="collapseHelp"></i>
                        Ordens de Serviço</h2>
                        <div class="">
                            <h5>Filtrar por</h5><br>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="filter-ativo">Mostrar</label>
                                    <select name="filter-ativo" id="filter-ativo" class="form-control">
                                        <option value="todos">Todos</option>
                                        <option value="finalizadas">Finalizadas</option>
                                        <option value="abertas">Não Finalizadas</option>
                                        <option value="ativadas">Ativas</option>
                                        <option value="desativadas">Excluídas</option>
                                    </select><br>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive table--no-card m-b-40">
                            <table id="ordens_servico" class="table table-striped table-datatable">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th id="data_brasileira">Data</th>
                                        <th>Prioridade</th>
                                        <th>Endereço</th>
                                        <th>Serviço</th>
                                        <th>Situação</th> 
                                        <th>Setor</th>                                       
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($ordens_servico != null): ?>
                                        <?php foreach ($ordens_servico as $key => $ordem_servico): ?>
                                            <tr>
                                                <td>
                                                    <?=$ordem_servico->ordem_servico_cod?>
                                                </td>
                                                <td>
                                                    <?=$ordem_servico->data_criacao?>
                                                </td>
                                                <td>
                                                    <?=$ordem_servico->prioridade_nome?>
                                                </td>
                                                <td>
                                                    <?=$ordem_servico->logradouro_nome . ", " .
                                                    $ordem_servico->local_num . " - " .
                                                    $ordem_servico->bairro_nome?>
                                                </td>
                                                <td>
                                                    <?=$ordem_servico->servico_nome?>
                                                </td>
                                                <td>
                                                    <?=$ordem_servico->situacao_nome?>
                                                </td>
                                                <td>
                                                    <?=$ordem_servico->setor_nome?>
                                                </td>

                                                <td>
                                                    <?php if($ordem_servico->ordem_servico_status == 1): ?>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-sm btn-success btn_atividade" data-toggle="modal" value="<?=$key?>" data-target="#atividade" title="Adicionar Situação">
                                                                <div class="d-none d-sm-block">
                                                                    <i class="fas fa-plus fa-fw"></i>
                                                                </div>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar btn-attr-ordem_servico_pk" data-toggle="modal" value="<?=$key?>" data-target="#ce_ordem_servico" title="Editar">
                                                                <div class="d-none d-sm-block">
                                                                    <i class="fas fa-edit fa-fw"></i>
                                                                </div>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-secondary reset_multistep btn_historico btn-attr-ordem_servico_pk" data-toggle="modal" value="<?=$key?>" data-target="#ce_historico_servico" title="Histórico">
                                                                <div class="d-none d-sm-block">
                                                                    <i class="far fa-clock fa-fw"></i>
                                                                </div>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-danger reset_multistep btn-excluir btn-attr-ordem_servico_pk" data-toggle="modal" value="<?=$key?>" data-target="#d_servico" title="Excluir">
                                                                <div class="d-none d-sm-block">
                                                                    <i class="fas fa-times fa-fw"></i>
                                                                </div>
                                                            </button>
                                                        </div>
                                                        <?php elseif($ordem_servico->ordem_servico_status == 0):  ?>
                                                            <div class="btn-group">
                                                                <button disabled type="button" style="cursor:auto;" class="btn btn-sm btn-primary reset_multistep btn_editar btn-attr-ordem_servico_pk" data-toggle="modal" value="<?=$key?>" data-target="#ce_ordem_servico" title="Editar">
                                                                    <div class="d-none d-sm-block">
                                                                        <i class="fas fa-edit fa-fw"></i>
                                                                    </div>
                                                                </button>
                                                                <button disabled style="cursor:auto;" type="button" class="btn btn-sm btn-secondary reset_multistep btn_historico btn-attr-ordem_servico_pk" data-toggle="modal" value="<?=$key?>" data-target="#ce_historico_servico" title="Histórico">
                                                                    <div class="d-none d-sm-block">
                                                                        <i class="far fa-clock fa-fw"></i>
                                                                    </div>
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-success reset_multistep btn-ativar btn-attr-ordem_servico_pk" data-toggle="modal" value="<?=$key?>" data-target="#r_servico" title="Reativar">
                                                                    <div class="d-none d-sm-block">
                                                                        <i class="fas fa-power-off fa-fw"></i>
                                                                    </div>
                                                                </button>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach?>
                                        <?php endif?>
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

        <!-- MODAL NOVA ORDEM SERVIÇO -->
        <div class="modal fade" id="ce_ordem_servico">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id ="titulo">Alterar ordem de serviço</h4>
                        <button type="button" class="close" id="close-modal" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <form class="msform" style="margin-top: 10px !important;">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card bg-light mb-3">
                                      <div class="card-header"><h4 class="card-title"> Informações Gerais </h4></div>
                                      <div class="card-body text-secondary">
                                        <input type="hidden" id="ordem_servico_pk" value="" name="ordem_servico_pk">
                                        <div class="row form-group">
                                            <div class="col-12">
                                                <label for="ordem_servico_desc">Descrição</label>
                                                <textarea class="form-control" id="ordem_servico_desc" name="ordem_servico_desc" class="form-control" required="true" maxlength="200"></textarea>
                                                <small class="form-text text-muted">Por favor, informe a descrição da Ordem de Serviço</small>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-7 col-md-4">
                                                <label for="departamento">Departamento</label>
                                                <select class="form-control" id="departamento" name="departamento" required="true">
                                                    <?php if ($departamentos != null): ?>
                                                        <?php foreach ($departamentos as $d): ?>
                                                            <option value="<?= $d->departamento_pk ?>">
                                                                <?= $d->departamento_nome ?>
                                                            </option>
                                                        <?php endforeach ?>
                                                    <?php endif ?>
                                                </select>
                                            </div>
                                            <div class="col-7 col-md-4">
                                                <label for="tipo_servico">Tipo de Serviço</label>
                                                <select class="form-control" id="tipo_servico" name="tipo_servico" required="true">
                                                </select>
                                            </div>
                                            <div class="col-7 col-md-4">
                                                <label for="servico_pk">Serviço</label>
                                                <select class="form-control" id="servico_pk" name="servico_pk" required="true">
                                                </select>
                                                <small class="form-text text-muted">Por favor, informe o Serviço</small>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-7 col-md-4" id="procedencias_options">
                                                <label for="procedencia_pk">Procedência</label>
                                                <select class="form-control" id="procedencia_pk" name="procedencia_pk" required="true">
                                                    <?php if ($procedencias != null): ?>
                                                        <?php foreach ($procedencias as $pr): ?>
                                                            <option value="<?= $pr->procedencia_pk ?>">
                                                                <?= $pr->procedencia_nome ?>
                                                            </option>
                                                        <?php endforeach ?>
                                                    <?php endif ?>
                                                </select>
                                                <small class="form-text text-muted" id="procedencia_small">Por favor, informe a procedência desta ordem</small>
                                            </div>
                                            <div class="col-7 col-md-4">
                                                <label for="prioridade_pk">Prioridade</label>
                                                <select class="form-control" id="prioridade_pk" name="prioridade_pk" required="true">
                                                    <?php if ($prioridades != null): ?>
                                                        <?php foreach ($prioridades as $p): ?>
                                                            <option value="<?= $p->prioridade_pk ?>">
                                                                <?= $p->prioridade_nome ?>
                                                            </option>
                                                        <?php endforeach ?>
                                                    <?php endif ?>
                                                </select>
                                                <small class="form-text text-muted">Por favor, informe a Prioridade</small>
                                            </div>
                                            <div class="col-7 col-md-4">
                                                <label for="situacao_pk">Situação Inicial</label>
                                                <select class="form-control" id="situacao_pk" name="situacao_pk" required="true">
                                                    <?php if ($situacoes != null): ?>
                                                        <?php foreach ($situacoes as $s): ?>
                                                            <option value="<?= $s->situacao_pk ?>">
                                                                <?= $s->situacao_nome ?>
                                                            </option>
                                                        <?php endforeach ?>
                                                    <?php endif ?>
                                                </select>
                                                <small class="form-text text-muted">Por favor, informe a Situação</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card bg-light mb-3" id="info_cidadao">
                                    <div class="card-header"><h4 class="card-title"> Informações Cidadão </h4></div>
                                    <div class="card-body text-secondary">
                                        <div class="row form-group">
                                            <div class="col col-md-1">
                                                <label for="nome-input" class=" form-control-label" required="true">
                                                    <strong>Nome</strong>
                                                </label>
                                            </div>
                                            <div class="col-12 col-md-11">
                                                <input type="text" id="nome-input" name="pessoa_nome" placeholder="Nome Completo" class="form-control nome-input" required
                                                maxlength="50" minlength="5" required>
                                                <small class="form-text text-muted">Por favor, informe o nome completo do cidadão</small>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-12 col-md-1">
                                                <label for="cpf-input" class=" form-control-label">
                                                    <strong>CPF</strong>
                                                </label>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <input type="text" id="cpf-input" name="pessoa_cpf" placeholder="CPF" class="form-control cpf-input" required>
                                                <small class="form-text text-muted">Por favor, informe o CPF do cidadão</small>
                                            </div>
                                            <div class="col-12 col-md-1">
                                                <label for="telefone-input" class=" form-control-label"><strong>Tel </strong></label>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <input type="text" id="telefone-input" name="contato_tel" placeholder="Telefone" class="form-control telefone-input">
                                                <small class="help-block form-text">Por favor, informe o telefone do cidadão</small>
                                            </div>
                                            <div class="col-12 col-md-1">
                                                <label for="celular-input" class=" form-control-label"><strong>Cel</strong></label>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <input type="text" id="celular-input" name="contato_cel" placeholder="Celular" class="form-control celular-input">
                                                <small class="help-block form-text">Por favor, informe o celular do cidadão</small>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-12 col-md-1">
                                                <label for="email-input" class=" form-control-label">
                                                    <strong>Email</strong>
                                                </label>
                                            </div>
                                            <div class="col-12 col-md-11">
                                                <input type="email" id="email-input" name="contato_email" placeholder="Email" class="form-control email-input" required="true">
                                                <small class="help-block form-text">Por favor, informe o email do cidadão</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card bg-light mb-3">
                                    <div class="card-header"><h4 class="card-title"> Localização </h4></div>
                                    <div class="card-body text-secondary">
                                       <div class="row form-group">
                                        <div class="col-3 col-md-3">
                                            <label for="uf-input" class=" form-control-label">Estado</label>
                                            <select class="form-control loading" id="uf-input" name="estado_pk" required="true"></select>
                                        </div>
                                        <div class="col-8 col-md-6">
                                            <label for="cidade-input" class="form-control-label">Cidade</label>
                                            <select class="form-control loading endereco" id="cidade-input" name="municipio_pk" required="true"></select>
                                        </div>
                                        <div class="col-3 col-md-3">
                                            <label for="setor_pk">Setor</label>
                                            <select class="form-control" id="setor_pk" name="setor_pk" required="true">
                                                <?php if ($setores != null): ?>
                                                    <?php foreach ($setores as $se): ?>
                                                        <option value="<?= $se->setor_pk ?>">
                                                            <?= $se->setor_nome ?>
                                                        </option>
                                                    <?php endforeach ?>
                                                <?php endif ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-12 col-md-4">
                                            <label for="referencia-input" class=" form-control-label">Ponto de Referência</label>
                                            <input type="text" id="referencia-input" name="referencia" class="form-control referencia">
                                            <small class="form-text text-muted">Insira um ponto de referência para buscar o local</small>
                                        </div>
                                        <div class="col-12 col-md-8">
                                            <!-- O que removi do dropdown: autocomplete="off" data-src = '["<?php echo base_url('localizacao/logradouros'); ?>","https://viacep.com.br/ws"]' data-index='["logradouro_pk","logradouro"]' data-value='["logradouro_nome","logradouro"]' data-params  = '[[["this","logradouro_nome","val"],["cidade-input","municipio_pk","val"]],[["uf-input",null,"text"],["cidade-input",null,"text"],["this",null,"val"],["json",null,"param"]]]' data-action='["post","get"]' data-arrayret='["data",null]'> 
                                            <ul class="dropdown-menu" data-return = "#logradouro_pk" data-next="#numero-input">
                                            </ul>-->
                                            <label for="logradouro_nome">Logradouro</label>
                                            <input class="form-control loading endereco" type="text" id="logradouro-input" name="logradouro_nome">
                                            <small class="form-text text-muted">Insira o logradouro para buscar o local</small>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                     <div class="col-12 col-md-2">
                                        <label for="numero-input" class=" form-control-label">N°</label>
                                        <input type="number" id="numero-input" name="local_num" class="form-control numero-input endereco" min="0" required="true">
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <label for="complemento-input" class=" form-control-label">Complemento</label>
                                        <input type="text" id="complemento-input" name="local_complemento" class="form-control endereco" maxlength="30">
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <label for="bairro-input" class="form-control-label loading">Bairro</label>
                                        <input type="hidden" name="bairro_nome" id="bairro_pk">
                                        <div class="dropdown" id="drop-bairro">
                                            <input class="form-control input-dropdown endereco" type="text" id="bairro-input" name="bairro" autocomplete="off" data-src = '["<?php echo base_url('localizacao/bairros'); ?>","https://viacep.com.br/ws"]' data-index='["bairro_pk","bairro"]' data-value='["bairro_nome","bairro"]' data-params  = '[[["cidade-input",null,"val"]],[["uf-input",null,"text"],["cidade-input",null,"text"],["logradouro-input",null,"val"],["json",null,"param"]]]' data-action='["get","get"]' data-arrayret='["data",null]'>
                                            <ul class="dropdown-menu" data-return = "#bairro_pk" data-next="#bairro-input">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group" style="margin-top: 30px !important;">
                                    <input type="hidden" id="latitude">
                                    <input type="hidden" id="longitude">
                                    <div class="col-12">
                                        <div id="map"></div>
                                        <small class="form-text text-muted">Visualize ou selecione o local no mapa</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card bg-light mb-3" id="card_imagem">
                            <div class="card-header"><h4 class="card-title"> Imagem Evidência </h4></div>
                            <div class="card-body text-secondary">
                                <div class="row form-group">
                                    <div class="col-12" id="image-upload-div">
                                        <div class="image-upload-wrap">
                                            <input class="file-upload-input" type='file' onchange="readURL(this);" accept="image/*" id="input-upload" required="true"/>
                                            <div class="drag-text">
                                                <h3>Ou clique/arraste e solte uma imagem aqui</h3>
                                            </div>
                                        </div>
                                        <div class="file-upload-content">
                                            <img id="img-input" class="file-upload-image" src="#" alt="your image" required="true"/>
                                            <div class="col-12">
                                                <button type="button" onclick="remove_image()" class="btn btn-danger">Remover</button>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">Por favor, se necessário, carregue a imagem</small>
                                    </div>
                                </div>
                            </div>
                        </div><!-- fecha card body --> 
                        <!-- fecha card principal -->
                        <?php if ($this->session->user['is_superusuario']): ?>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="senha" class="form-control-label">Digite sua senha</label>
                                    <input type="password" name="senha" id="senha" class="form-control" required>
                                </div>
                            </div>
                        <?php endif ?>
                        <div class="text-center">
                            <button type="button" class="btn btn-primary submit" onclick="send_data()">
                                <i class="fa fa-dot-circle-o"></i> Finalizar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- Modal footer -->
        <div class="modal-footer d-md-none">
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Fechar</button>
        </div>
    </div>
</div>
</div>
<!-- FIM MODAL NOVA ORDEM/ALTERAR ORDEM-->


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
                    <div class="card-body" id= "numero-protocolo" style="text-align: center;"></div>
                </div>
                <div class= "modal-footer">
                    <div class="btn-group">
                        <button id="protocol-copy" style="width: 50px !important;" type="button" class="btn btn-sm btn-success pull-right btn-copy js-tooltip js-copy" data-toggle="tooltip" data-placement="bottom" title="Copiar">
                            <div class="d-none d-sm-block">
                                <i class="fas fa-copy fa-fw"></i>
                            </div>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger btn-fechar pull-right" id="fechar-atividade" data-dismiss="modal">Fechar</button>
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
<div class="modal fade" id="atividade">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Adicionar Situação da Ordem de serviço</h4>
                <h4 class="modal-title 2"></h4> 
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">

                    <div align="center" class="center">
                        <img width="150px" src="<?= base_url('assets/images/loading.gif') ?>" id="ov_loading" alt="Carregando">
                    </div>

                    <div class="qa-message-list py-5" id="otimeline" style="margin-top: 10px !important; padding-top: 10px !important;">
                    </div>

                    <div class= "modal-footer">
                        <button type="button" class="btn btn-sm btn-success pull-right" style="margin-right: 10px;" id="btn-salvar-atividade" onclick="send_data_historico()">
                            <i class="fa fa-dot-circle-o"></i>
                            Salvar
                        </button>
                        <button type="button" class="btn btn-sm btn-danger btn-fechar pull-right" id="fechar-atividade" data-dismiss="modal">
                            Fechar
                        </button>
                        <input type="hidden" id="historico_pk" value="" name="historico_pk">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- MODAL HISTÓRICO ORDEM SERVIÇO -->
<div class="modal fade" id="ce_historico_servico">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Histórico da Ordem de serviço</h4>
                <h4 class="modal-title 2"></h4> 
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div>
                    <div class="row pb-2" style="text-align: center;">
                        <div class="col-12">
                            <div class="card-group">
                                <div class="card col-md-4" style="padding-left: 0px !important; padding-right: 0px !important;">
                                    <div class="card-header">
                                        <strong>Código:</strong>

                                    </div>
                                    <div class="card-body card-block">
                                        <p id="v_codigo"></p>
                                    </div>
                                </div>
                                <div class = "card col-md-4" style="padding-left: 0px !important; padding-right: 0px !important;">
                                    <div class="card-header">
                                        <strong>Descrição:</strong>
                                    </div>
                                    <div class="card-body card-block">
                                     <p id="v_descricao"><p>
                                     </div>
                                 </div>
                                 <div class="card col-md-4" style="padding-left: 0px !important; padding-right: 0px !important;">
                                    <div class="card-header">
                                        <strong>Procedência:</strong>
                                    </div>
                                    <div class="card-body card-block">
                                        <p id="v_procedencia"></p>
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
                                        <p id="v_prioridade"></p>
                                    </div>
                                </div>
                                <div class = "card col-md-4" style="padding-left: 0px !important; padding-right: 0px !important;">
                                    <div class="card-header">
                                        <strong>Servico:</strong>
                                    </div>
                                    <div class="card-body card-block">
                                     <p id="v_servico"><p>
                                     </div>
                                 </div>
                                 <div class="card col-md-4" style="padding-left: 0px !important; padding-right: 0px !important;">
                                    <div class="card-header">
                                        <strong>Setor:</strong>
                                    </div>
                                    <div class="card-body card-block">
                                        <p id="v_setor"></p>
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
                                        <button type="button" class="btn btn-sm btn-primary btn_mapa pull-right" id="btn-mapa-historico">
                                            <i class="fa fa-map-marker"></i>
                                        </button>
                                    </div>
                                    <div class="card-body card-block">
                                        <p id="v_endereco"></p>
                                    </div>
                                    <div class="col-12 col-md-12 pb-1" id="mapa_historico">
                                        <div id="map2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div align="center" class="center">
                        <img width="150px" src="<?= base_url('assets/images/loading.gif') ?>" id="v_loading" alt="Carregando">
                    </div>
                    <div class="container-fluid" id="card_slider_historico">
                    </div>  
                    <div class="qa-message-list" id="timeline" style="padding-top: 5px;">
                    </div>
                    <div class= "modal-footer">
                        <button type="button" class="btn btn-sm btn-danger btn-fechar pull-right" id="fechar-historico" data-dismiss="modal">
                            Fechar
                        </button>
                        <input type="hidden" id="historico_pk" value="" name="historico_pk">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- FECHA MODAL HISTÓRICO -->

<!-- modal histórico -->
<div class="modal fade" id="d_servico">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Desativar Ordem de Serviço</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <h4 style="text-align: center" class="text-danger">
                            <i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                            <p>Ao desativar uma ordem de serviço, as seguintes ações também serão feitas:</p>
                            <ul style="margin-left: 15px">
                                <li>Não será possível editar ou visualizar a ordem de serviço.</li>
                                <li>A ordem de serviço não será exibida em demais módulos.</li>
                            </ul>
                        </div>
                        <?php if ($this->session->user['is_superusuario'] === true): ?>
                            <div class="form-group">
                                <input type="password" class="form-control" autocomplete="false" name="pass-modal-desativar" placeholder="Confirme sua senha"
                                required="required" id="pass-modal-desativar" minlength="8">
                            </div>
                        <?php endif;?>
                        <div class="form-group">
                            <button type="button" class="btn btn-confirmar-senha" id="btn-desativar" name="post" value="">
                                <i class="fa fa-dot-circle-o"></i> Desativar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIM MODAL HISTÓRICO -->

        <!--MODAL REATIVA tipos_servicos -->
        <div class="modal fade" id="r_servico">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Reativar Ordem de Serviço</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <h4 style="text-align: center" class="text-danger">
                                    <i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                                    <p>Ao reativar um serviço, as seguintes ações também serão feitas:</p>
                                    <ul style="margin-left: 15px">
                                        <li>Novas ordens de serviço poderão utilizar novamente o serviço ativado.</li>

                                    </ul>
                                </div>
                                <?php if ($this->session->user['is_superusuario']): ?>
                                    <div class="form-group">
                                        <input type="password" class="form-control" autocomplete="false" name="pass-modal-reativar" placeholder="Confirme sua senha"
                                        required="required" id="pass-modal-reativar">
                                    </div>
                                <?php endif;?>
                                <div class="form-group">
                                    <button type="button" class="btn btn-confirmar-senha" id="btn-reativar" name="post" value=""><i class="fa fa-dot-circle-o"></i> Reativar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> 
            <!-- FIM MODAL REATIVA -->

            <script type="text/javascript">
                var servicos = <?php echo json_encode($servicos !== false ? $servicos : []); ?>;
                var prioridades = <?php echo json_encode($prioridades !== false ? $prioridades : []); ?>;
                var situacoes = <?php echo json_encode($situacoes !== false ? $situacoes : []); ?>;
                var tipos_servico = <?php echo json_encode($tipos_servico !== false ? $tipos_servico : []); ?>;
                var is_superusuario = <?php if($superusuario){ echo "true"; }else{ echo "false";} ?>;
                var procedencias = <?php echo json_encode($procedencias !== false ? $procedencias : []); ?>;
                var ordens_servico = <?php echo json_encode($ordens_servico !== false ? $ordens_servico : []); ?>;
                var organizacao = <?php echo json_encode($this->session->user['id_organizacao']); ?>;
            </script>

            <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
            </script>

<!-- END MAIN CONTENT