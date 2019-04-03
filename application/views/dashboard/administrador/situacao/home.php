<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">gerenciamento de situações </h2>
                        <button class="au-btn au-btn-icon au-btn--blue reset_multistep btn-novo new" data-toggle="modal" data-target="#ce_situacao">
                            <i class="zmdi zmdi-plus"></i>nova situação</button>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="collapse" id="collapseHelp">
                                <div class="card card-body">
                                    <p>Esta é a área para o gerenciamento de situações.</p>
                                    <p>A situação representa o status da ordem de serviço. Exemplo: assim que uma ordem de serviço chega ao sistema, ela é atribuída como aberta, e quando está concluída, está com a situação concluída/finalizada.</p>
                                    <p>Para o cadastro da situação, é possível adicionar se a foto é ou não obrigatória, indicando para o sistema que para ativar aquela determinada situação, a foto deve ser obrigatória.</p>
                                    <p>Através das situações é possível os funcionários de sua empresa saberem a situação das ordens de serviço para saberem qual o procedimento adotar.</p>
                                    <p><b>Características:</b></p>
                                    <div class="col-md-12">
                                        <ul>
                                            <li>Facilita relatórios e filtros</li>
                                            <li>Facilita funcionários da empresa saberem qual prodecimento adotar</li>
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
                            situação</h2>
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
                                            <th>Descrição</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($situacoes != null): ?>

                                            <?php foreach ($situacoes as $key => $situacao): ?>

                                                <tr>
                                                    <td>
                                                        <?=$situacao->situacao_nome?>
                                                    </td>
                                                    <td>
                                                        <?=$situacao->situacao_descricao?>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <?php if ($situacao->ativo): ?>
                                                                <button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="<?=$situacao->situacao_pk?>" data-target="#ce_situacao">
                                                                    <div class="d-none d-sm-block">
                                                                        Editar
                                                                    </div>
                                                                    <div class="d-block d-sm-none">
                                                                        <i class="fas fa-edit fa-fw"></i>
                                                                    </div>
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="<?=$situacao->situacao_pk?>" data-target="#d-situacao">
                                                                    <div class="d-none d-sm-block">
                                                                        Desativar
                                                                    </div>
                                                                    <div class="d-block d-sm-none">
                                                                        <i class="fas fa-times fa-fw"></i>
                                                                    </div>
                                                                </button>
                                                                <?php else: ?>
                                                                    <button type="button" class="btn btn-sm btn-success btn_reativar" data-toggle="modal" value="<?=$situacao->situacao_pk?>" data-target="#r-situacao">
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


            <!-- MODAL CRIA E ATUALIZA PRIORIDADE -->
            <div class="modal fade modal-multistep" id="ce_situacao">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Novo situação</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form class="msform">
                                        <input type="hidden" id="situacao_pk" name="situacao_pk" class="form-control">
                                        <input type="hidden" name="opcao-editar" id="opcao-editar">
                                        <!-- progressbar -->
                                        <ul class="progressbar">
                                            <li class="active">Identificação da situação</li>
                                            <?php if ($this->session->user['is_superusuario'] === true): ?>
                                                <li>Identificação</li>
                                            <?php endif;?>
                                        </ul>
                                        <!-- fieldsets -->
                                        <div class="card card-step col-12 px-0">
                                            <div class="card-header">
                                                Identificação da situação
                                            </div>
                                            <div class="card-body card-block">
                                                <div class="row form-group">
                                                    <div class="col col-md-2">
                                                        <label for="nome-input" class=" form-control-label">
                                                            <strong>Nome*</strong>
                                                        </label>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <input type="text" id="nome-input" name="nome" placeholder="Nome da situação" class="form-control" required="true" maxlength="50"
                                                        minlength="3">
                                                        <small class="form-text text-muted">Por favor, informe o nome da situação </small>
                                                    </div>

                                                    <div class="col col-md-2 mt-2">
                                                        <label for="descricao-input" class=" form-control-label">
                                                            <strong>Descrição*</strong>
                                                        </label>
                                                    </div>
                                                    <div class="col-12 col-md-10 mt-2">
                                                        <textarea id="descricao-input" name="descricao" class="form-control" required="true" resizable="false"></textarea>
                                                        <small class="form-text text-muted">Por favor, informe a descrição da situação </small>
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
                                                        <button type="button" class="btn btn-primary btn-sm submit" id="botao-finalizar">
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


                <!-- MODAL DELETA prioridadeS -->
                <div class="modal fade" id="d-situacao">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Desativar situação</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group" id="alerta">
                                        <h4 style="text-align: center" class="text-danger">
                                            <i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                                            <p>Ao desativar uma situação, as seguintes ações também serão feitas:</p>
                                            <ul style="margin-left: 15px">
                                                <li>Não será possível utilizar a situação desativa nas novas ordens de serviço.</li>
                                            </ul>
                                        </div>
                                        <div class="form-group">
                                            <!-- <div id="loading-situacao-deactivate">
                                                <div align="center" class="center">
                                                    <img width="150px" src="<?= base_url('assets/images/loading.gif') ?>" id="v_loading" alt="Carregando">
                                                </div>
                                            </div> -->

                                            <div id="servicos-dependentes"></div>
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

                    <!-- MODAL REATIVA SITUACOES -->
                    <div class="modal fade" id="r-situacao">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Reativar Situação</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="form-group">
                                            <h4 style="text-align: center" class="text-danger">
                                                <i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                                                <p>Ao reativar uma situação, as seguintes ações também serão feitas:</p>
                                                <ul style="margin-left: 15px">
                                                    <li>Novas ordens de serviço poderão utilizar a situação.</li>

                                                </ul>
                                            </div>
                                            <?php if ($this->session->user['is_superusuario'] === true): ?>
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
                            var situacoes = <?php echo json_encode($situacoes !== false ? $situacoes : []); ?>;

                            var is_superusuario = <?php echo $this->session->user['is_superusuario'] === true ? 1 : 0; ?>;
                        </script>
                        <!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->