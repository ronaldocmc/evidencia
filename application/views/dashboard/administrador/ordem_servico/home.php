 <script type="text/javascript">
    async function pre_loader_show(){
      $('.inner').show();
      // $('#preloader .inner').delay(1000).fadeIn();
      $('#preloader .inner').delay(10).fadeIn();
      $('#preloader').delay(5).fadeIn('slow');
    }

    pre_loader_show();
 </script>
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
                </div>
            </div>
            <div class="row py-5">
                <div class="col-lg-12">
                    <div class="au-card d-flex flex-column">
                        <h2 class="title-1 m-b-25">ordens de serviço</h2>
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
                                                    <span style="display: none"><?=$ordem_servico->data_criacao?></span><?=date('d/m/Y H:i:s', strtotime($ordem_servico->data_criacao))?>
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
                                                            <button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar btn-attr-ordem_servico_pk" data-toggle="modal" value="<?=$key?>" data-target="#ce_ordem_servico">
                                                                <div class="d-none d-sm-block">
                                                                    Editar
                                                                </div>
                                                                <div class="d-block d-sm-none">
                                                                    <i class="fas fa-edit fa-fw"></i>
                                                                </div>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-secondary reset_multistep btn_historico btn-attr-ordem_servico_pk" data-toggle="modal" value="<?=$key?>" data-target="#ce_historico_servico">
                                                                <div class="d-none d-sm-block">
                                                                    Histórico
                                                                </div>
                                                                <div class="d-block d-sm-none">
                                                                    <i class="fas fa-edit fa-fw"></i>
                                                                </div>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-danger reset_multistep btn-excluir btn-attr-ordem_servico_pk" data-toggle="modal" value="<?=$key?>" data-target="#d_servico">
                                                                <div class="d-none d-sm-block">
                                                                    Excluir
                                                                </div>
                                                                <div class="d-block d-sm-none">
                                                                    <i class="fas fa-times fa-fw"></i>
                                                                </div>
                                                            </button>
                                                        </div>
                                                        <?php elseif($ordem_servico->ordem_servico_status == 0):  ?>
                                                            <div class="btn-group">
                                                                <button disabled type="button" style="cursor:auto;" class="btn btn-sm btn-primary reset_multistep btn_editar btn-attr-ordem_servico_pk" data-toggle="modal" value="<?=$key?>" data-target="#ce_ordem_servico">
                                                                    <div class="d-none d-sm-block">
                                                                        Editar
                                                                    </div>
                                                                    <div class="d-block d-sm-none">
                                                                        <i class="fas fa-edit fa-fw"></i>
                                                                    </div>
                                                                </button>
                                                                <button disabled style="cursor:auto;" type="button" class="btn btn-sm btn-secondary reset_multistep btn_historico btn-attr-ordem_servico_pk" data-toggle="modal" value="<?=$key?>" data-target="#ce_historico_servico">
                                                                    <div class="d-none d-sm-block">
                                                                        Histórico
                                                                    </div>
                                                                    <div class="d-block d-sm-none">
                                                                        <i class="fas fa-edit fa-fw"></i>
                                                                    </div>
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-success reset_multistep btn-ativar btn-attr-ordem_servico_pk" data-toggle="modal" value="<?=$key?>" data-target="#r_servico">
                                                                    <div class="d-none d-sm-block">
                                                                        Ativar
                                                                    </div>
                                                                    <div class="d-block d-sm-none">
                                                                        <i class="fas fa-times fa-fw"></i>
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
                    <form class="msform">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
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
                                                <div class="col-12 col-md-8">
                                                    <label for="logradouro_nome">Logradouro</label>
                                                    <input class="form-control input-dropdown loading endereco" type="text" id="logradouro-input" name="logradouro_nome" autocomplete="off" data-src = '["<?php echo base_url('localizacao/logradouros'); ?>","https://viacep.com.br/ws"]' data-index='["logradouro_pk","logradouro"]' data-value='["logradouro_nome","logradouro"]' data-params  = '[[["this","logradouro_nome","val"],["cidade-input","municipio_pk","val"]],[["uf-input",null,"text"],["cidade-input",null,"text"],["this",null,"val"],["json",null,"param"]]]' data-action='["post","get"]' data-arrayret='["data",null]'>
                                                    <ul class="dropdown-menu" data-return = "#logradouro_pk" data-next="#numero-input">
                                                    </ul>
                                                    <small class="form-text text-muted">Insira o logradouro ou um ponto de referência para buscar o local</small>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <label for="referencia-input" class=" form-control-label">Ponto de Referência</label>
                                                    <input type="text" id="referencia-input" name="referencia" class="form-control referencia">
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
                                        <div class="row form-group">
                                            <input type="hidden" id="latitude">
                                            <input type="hidden" id="longitude">
                                            <div class="col-12">
                                                <div id="map"></div>
                                            </div>
                                            <small class="form-text text-muted">Visualize ou selecione o local no mapa</small>
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
                                </div>
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
                    <div class="form-group">
                        <table class="table hide">
                            <thead>
                                <th>Prioridade</th>
                                <th>Procedência</th>
                                <th>Serviço</th>
                                <th>Setor</th>
                            </thead>
                            <tbody>
                                <td id="v_prioridade"></td>
                                <td id="v_procedencia"></td>
                                <td id="v_servico"></td>
                                <td id="v_setor"></td>
                            </tbody>
                        </table>
                        <div class=card-group>
                            <div class = "card col-12 col-md-6" style="padding-left: 0px !important; padding-right: 0px !important;">
                                <div class="card-header">
                                    <strong>Descrição:</strong>
                                    <button type="button" class="btn btn-sm btn-primary btn_foto pull-right" id="btn-foto-historico">
                                        <i class="fa fa-camera" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div class="card-body card-block" id="endereco_historico">
                                 <p id="v_descricao"><p>
                                 </div>
                             </div>
                             <div class="card col-12 col-md-6" style="padding-left: 0px !important; padding-right: 0px !important;">
                                <div class="card-header">
                                    <strong>Endereço:</strong>
                                    <button type="button" class="btn btn-sm btn-primary btn_mapa pull-right" id="btn-mapa-historico">
                                        <i class="fa fa-map-marker"></i>
                                    </button>
                                </div>
                                <div class="card-body card-block" id="endereco_historico">
                                    <p id="v_endereco"></p>
                                </div>
                            </div>
                        </div>
                        <div align="center" class="center">
                            <img width="150px" src="<?= base_url('assets/images/loading.gif') ?>" id="v_loading" alt="Carregando">
                        </div>
                        <div class="col-12 col-md-12" id="mapa_historico" style="margin-top: 20px; padding-top: 10px;">
                            <div id="map2"></div>
                        </div>
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                            </ol>
                            <div class="carousel-inner">
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Anterior</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Próximo</span>
                            </a>
                        </div>
                        <div class="qa-message-list py-5" id="timeline" style="margin-top: 20px; padding-top: 5px;">
                        </div>
                        <div class= "modal-footer">
                            <button type="button" class="btn btn-sm btn-success pull-right" style="margin-right: 10px;" id="btn-salvar-historico" onclick="send_data_historico()">
                                <i class="fa fa-dot-circle-o"></i>
                                Salvar
                            </button>
                            <button type="button" class="btn btn-sm btn-danger btn-fechar pull-right" data-dismiss="modal">
                                Fechar
                            </button>
                            <input type="hidden" id="historico_pk" value="" name="historico_pk">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- MODAL DELETA TIPO SERVICO -->
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


        <script type="text/javascript">
            var servicos = <?php echo json_encode($servicos !== false ? $servicos : []); ?>;
            var prioridades = <?php echo json_encode($prioridades !== false ? $prioridades : []); ?>;
            var situacoes = <?php echo json_encode($situacoes !== false ? $situacoes : []); ?>;
            var tipos_servico = <?php echo json_encode($tipos_servico !== false ? $tipos_servico : []); ?>;
            var is_superusuario = <?php if($superusuario){ echo "true"; }else{ echo "false";} ?>;
            var procedencias = <?php echo json_encode($procedencias !== false ? $procedencias : []); ?>;
            var ordens_servico = <?php echo json_encode($ordens_servico !== false ? $ordens_servico : []); ?>;
</script>

<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
</script>


<!-- END MAIN CONTENT-->