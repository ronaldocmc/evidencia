<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">gerenciamento de tipos de serviços </h2>
                        
                        <?php if($departamentos != null){ ?>
                            <button class="au-btn au-btn-icon au-btn--blue reset_multistep btn_novo new" data-toggle="modal" data-target="#ce_tipo_servico">
                                <i class="zmdi zmdi-plus"></i>novo tipo de serviço</button>
                            <?php }else{ ?>
                                ERRO
                            <?php } ?>
                        </div>
                        <input type="hidden" name="opcao-editar" id="opcao-editar" value="false">
                        <div class="col-md-12 mt-3">
                            <div class="collapse" id="collapseHelp">
                                <div class="card card-body">
                                    <p>O tipo de serviço representa a qual grupo, classificação o serviço será classificado.</p>
                                    <p>Se por exemplo, sua empresa realiza o serviço de Coleta de Animal, o tipo de serviço seria Coleta.</p>
                                    <p>Para o cadastro de um novo tipo de serviço, é necessário inserir o nome do tipo de serviço, a descrição de o que é esse tipo de serviço, a prioridade padrão, ou seja, qual a prioridade que geralmente é atribuída aos serviços deste grupo. Por fim, é necessário indicar o departamento que será responsável pelos serviços deste grupo.</p>
                                    <p><b>Características:</b></p>
                                    <div class="col-md-12">
                                        <ul>
                                            <li>Facilita a geração de relatórios</li>
                                            <li>Facilita o agrupamento de serviços, atribuindo um tipo de serviço a um departamento específico</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-lg-12">
                        <div class="au-card d-flex flex-column">
                            <h2 class="title-1 m-b-25">
                                <i style="cursor: pointer; color: gray" class="fas fa-info pull-right" data-toggle="collapse" href="#collapseHelp" role="button" aria-expanded="false" aria-controls="collapseHelp"></i>
                            tipos de serviços</h2>
                            <div class="">
                                <h5>Filtrar por</h5><br>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="filter-ativo">Mostrar</label>
                                        <select name="filter-ativo" id="filter-ativo" class="form-control">
                                            <option value="todos">Todos</option>
                                            <option value="ativos">Apenas ativos</option>
                                            <option value="desativados">Apenas desativados</option>
                                        </select><br>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive table--no-card m-b-40">
                                <table id="prioridades" class="table table-striped table-datatable">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Abreviação</th>
                                            <th>Descrição</th>
                                            <th>Prioridade Padrão</th>
                                            <th>Departamento</th>
                                            <th>Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($tipos_servicos != null): ?>
                                            <?php foreach ($tipos_servicos as $key => $ts): ?>
                                                <tr>
                                                    <td>
                                                        <?=$ts->tipo_servico_nome?>
                                                    </td>
                                                    <td>
                                                        <?=$ts->tipo_servico_abreviacao?>
                                                    </td>
                                                    <td>
                                                        <?=$ts->tipo_servico_desc?>
                                                    </td>
                                                    <td>
                                                        <?=$ts->prioridade_nome?>
                                                    </td>
                                                    <td>
                                                        <?=$ts->departamento_nome?>
                                                    </td>

                                                    <td>
                                                        <div class="btn-group">
                                                            <?php if ($ts->tipo_servico_status): ?>
                                                                <button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="<?=$key?>" data-target="#ce_tipo_servico">
                                                                    <div class="d-none d-sm-block">
                                                                        Editar
                                                                    </div>
                                                                    <div class="d-block d-sm-none">
                                                                        <i class="fas fa-edit fa-fw"></i>
                                                                    </div>
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="<?=$key?>" data-target="#d_tipo_servico">
                                                                    <div class="d-none d-sm-block">
                                                                        Desativar
                                                                    </div>
                                                                    <div class="d-block d-sm-none">
                                                                        <i class="fas fa-times fa-fw"></i>
                                                                    </div>
                                                                </button>
                                                                <?php else: ?>
                                                                    <button type="button" class="btn btn-sm btn-success btn_reativar" data-toggle="modal" value="<?=$key?>" data-target="#r_tipo_servico">
                                                                        <div class="d-none d-sm-block">
                                                                            Reativar
                                                                        </div>
                                                                        <div class="d-block d-sm-none">
                                                                            <i class="fas fa-check-circle fa-fw"></i>
                                                                        </div>
                                                                    </button>
                                                                <?php endif;?>
                                                            </div>
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


            <!-- MODAL CRIA E ATUALIZA TIPO SERVICO -->
            <div class="modal fade modal-multistep" id="ce_tipo_servico">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Novo tipo de serviço</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form class="msform">
                                        <input type="hidden" id="tipo_servico_pk" name="tipo_servico_pk" class="form-control">

                                        <!-- progressbar -->
                                        <ul class="progressbar">
                                            <li class="active">Identificação do tipo de serviço</li>
                                            <?php if ($this->session->user['is_superusuario'] === true): ?>
                                                <li>Identificação</li>
                                            <?php endif;?>
                                        </ul>
                                        <!-- fieldsets -->
                                        <div class="card card-step col-12 px-0">
                                            <div class="card-header">
                                                Identificação do tipo de serviço
                                            </div>
                                            <div class="card-body card-block">
                                                <div class="row form-group">
                                                    <div class="col col-md-2">
                                                        <label for="nome-input" class=" form-control-label">
                                                            <strong>Nome*</strong>
                                                        </label>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <input type="text" id="nome-input" name="nome" placeholder="Nome do tipo de serviço" class="form-control" required="true" maxlength="50"
                                                        minlength="3">
                                                        <small class="form-text text-muted">Por favor, informe o nome do tipo de serviço </small>
                                                    </div>

                                                    <div class="col col-md-2">
                                                        <label for="abreviacao-input" class=" form-control-label">
                                                            <strong>Abreviação*</strong>
                                                        </label>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <input type="text" id="abreviacao-input" name="abreviacao" placeholder="Abreviação do tipo de serviço" class="form-control" required="true" maxlength="10"
                                                        minlength="3">
                                                        <small class="form-text text-muted">A abreviação será utilizada na codificação de uma Ordem de Serviço</small>
                                                    </div>

                                                    <div class="col col-md-2">
                                                        <label for="descricao-input" class=" form-control-label">
                                                            <strong>Descrição*</strong>
                                                        </label>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <textarea id="descricao-input" name="descricao" class="form-control" required="true" resizable="false"></textarea>
                                                        <small class="form-text text-muted">Por favor, informe a descrição do tipo de serviço </small>
                                                    </div>


                                                    <div class="col-12 col-md-2">
                                                        <label for="prioridade_fk" class=" form-control-label"><strong>Prioridade Padrão*</strong></label>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <?php echo form_dropdown('prioridade_pk', $prioridades, null, 'class="form-control" required="true" id="prioridade_fk"'); ?>
                                                        <small class="help-block form-text">Por favor, informe a prioridade padrão que será exibida na ordem de serviço desse tipo.</small>
                                                    </div>
                                                    <div class="col-12 col-md-2">
                                                        <label for="departamento_fk" class=" form-control-label"><strong>Departamento*</strong></label>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <?php echo form_dropdown('departamento_pk', $departamentos, null, 'class="form-control" required="true" id="departamento_fk"'); ?>
                                                        <small class="help-block form-text">Por favor, informe o departamento responsável por esse tipo de serviço.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer text-center">
                                                <?php if ($this->session->user['is_superusuario'] === true): ?>
                                                    <button type="button" class="btn btn-secondary next btn-sm">
                                                        <i class="fas fa-arrow-circle-right"></i> Próximo
                                                    </button>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-primary submit btn-sm" id="botao-finalizar">
                                                            <i class="fa fa-dot-circle-o"></i> Finalizar
                                                        </button>
                                                    <?php endif;?>
                                                </div>
                                            </div>
                                            <?php if ($this->session->user['is_superusuario'] === true): ?>
                                                <div class="card card-step col-12 px-0">
                                                    <div class="card-header">
                                                        Identificação
                                                    </div>
                                                    <div class="card-body card-block">
                                                        <div class="row form-group">
                                                            <div class="col col-md-2">
                                                                <label for="senha-input" class=" form-control-label">
                                                                    <strong>Senha*</strong>
                                                                </label>
                                                            </div>
                                                            <div class="col-12 col-md-10">
                                                                <input type="password" id="senha-input" name="senha" placeholder="Senha Pessoal" class="form-control" autocomplete="new-password"
                                                                minlength="8" required="true">
                                                                <small class="form-text text-muted">Por favor, informe sua senha de acesso</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer text-center">
                                                        <button type="button" class="btn btn-secondary previous btn-sm">
                                                            <i class="fas fa-arrow-circle-left"></i> Anterior
                                                        </button>
                                                        <button type="button" class="btn btn-primary submit btn-sm" id="botao-finalizar">
                                                            <i class="fa fa-dot-circle-o"></i> Finalizar
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php endif;?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button"  class="btn btn-primary btn-sm" id="pula-para-confirmacao">
                                    <i class="fa fa-dot-circle-o"></i> Salvar
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- MODAL DELETA TIPO SERVICO -->
                <div class="modal fade" id="d_tipo_servico">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Desativar tipo de serviço</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>

                            <div class="modal-body">


                            <div id="tipo-servico-deactivate">
                                <form>
                                    <div class="form-group">
                                        <h4 style="text-align: center" class="text-danger">
                                            <i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                                            <div id="servicos-dependentes"></div>
                                            <p>Ao desativar um tipo de serviço, as seguintes ações também serão feitas:</p>
                                            <ul style="margin-left: 15px">
                                                <li>Não será possível utilizar o tipo de serviço desativado nas novas ordens de serviço.</li>
                                                <li>As ordens de serviço antigas serão mantidas.</li>
                                            </ul>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div id="loading-tipo-servico-deactivate">
                                                <div align="center" class="center">
                                                    <img width="150px" src="<?= base_url('assets/images/loading.gif') ?>" id="v_loading" alt="Carregando">
                                                </div>
                                                <div id="servicos-dependentes"></div>
                                            </div>
                                        </div>

                                        <?php if ($this->session->user['is_superusuario'] === true): ?>
                                            <div class="form-group">
                                                <input type="password" class="form-control" autocomplete="false" name="pass-modal-desativar" placeholder="Confirme sua senha"
                                                required="required" id="pass-modal-desativar" minlength="8">
                                            </div>
                                        <?php endif;?>
                                        <div class="form-group">
                                            <button type="button" class="btn btn-confirmar-senha" id="btn-desativar" name="post" value=""><i class="fa fa-dot-circle-o"></i> Desativar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MODAL REATIVA tipos_servicos -->
                <div class="modal fade" id="r_tipo_servico">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Reativar tipo de serviço</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <h4 style="text-align: center" class="text-danger">
                                            <i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                                            <p>Ao reativar um tipo de serviço, as seguintes ações também serão feitas:</p>
                                            <ul style="margin-left: 15px">
                                                <li>Novas ordens de serviço poderão utilizar novamente o tipo de serviço ativado.</li>


                                            </ul>
                                            <br>
                                            <p><b>Importante:</b> nenhum serviço inativo vinculado a este tipo de serviço será reativado!</p>
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
                        var tipos_servicos = <?php echo json_encode($tipos_servicos !== false ? $tipos_servicos : []); ?>;

                        var is_superusuario = <?php echo $this->session->user['is_superusuario'] === true ? 1 : 0; ?>;
                    </script>
                    <!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->