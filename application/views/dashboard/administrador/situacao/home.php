<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">gerenciamento de situações
                        </h2>
                        <button class="au-btn au-btn-icon au-btn--blue btn_novo reset_multistep new d-none" data-toggle="modal" data-title="Novo Situação" data-contentid="save"
                            data-target="#modal">
                            <i class="zmdi zmdi-plus"></i>novo situação</button>
                    </div>
                    <input type="hidden" name="opcao-editar" id="opcao-editar" value="false">
                    <div class="col-md-12 mt-3">
                        <div class="collapse" id="collapseHelp">
                            <!-- User Guide Section -->
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h3>Guia do Usuário</h3>
                                    </div>
                                </div>
                                <div class="card-body card-user-guide">
                                    <div class="col-md-6">
                                        <p> Bem-vindo a área de Gerenciamento de Situações!</p><br>
                                        <p> Aqui você poderá realizar algumas operações para controlar os situações
                                            da sua organização.</p><br>
                                        <p> Organizamos a sua situação de modo que ela possua serviços específicos
                                            conforme um tipo de serviço definido. Assim, controlar a prestação de
                                            serviços da sua organização torna-se uma tarefa fácil e rápida! </p>
                                    </div>
                                    <div class="col-md-6 user-guide">
                                        <p><b>Operações permitidas:</b></p>
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
                                                <div class="col-md-10 text-guide">Inserir uma situação</div>
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
                                                <div class="col-md-10 text-guide">Editar situação existente</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 icon-guide">
                                                    <button type="button" class="btn btn-sm btn-danger" disabled="true"
                                                        title="Desativar">
                                                        <div class="d-none d-block">
                                                            <i class="fas fa-times fa-fw"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                                <div class="col-md-10 text-guide">Desativar situação inativa</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 icon-guide">
                                                    <button type="button" class="btn btn-sm btn-success" disabled="true"
                                                        title="Reativar">
                                                        <div class="d-none d-block">
                                                            <i class="fas fa-power-off fa-fw"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                                <div class="col-md-10 text-guide">Ativar situação novamente</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12>">
                                                    <br>
                                                    <p>
                                                        <strong>Qualquer dúvida entre em contato com o suporte na sua organização!</strong>
                                                    </p>
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
            <div class="row py-2">
                <div class="col-lg-12">
                    <div class="au-card d-flex flex-column">

                        <h2 class="title-1 m-b-25">
                            <i style="cursor: pointer; color: gray" class="fas fa-info pull-right"
                                data-toggle="collapse" href="#collapseHelp" role="button" aria-expanded="false"
                                aria-controls="collapseHelp"></i>
                            Situações</h2>


                        <div class="">
                            <h5>Filtrar por</h5><br>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="filter-ativo">Mostrar</label>
                                    <select name="filter-ativo" id="filter-ativo" class="form-control">
                                        <option value="-1">Todos</option>
                                        <option value="1">Apenas ativos</option>
                                        <option value="0">Apenas desativados</option>
                                    </select><br>
                                </div>
                            </div>
                        </div>
                        <div id="loading">
                            <div align="center" class="center">
                                <img src="<?= base_url('assets/images/loading.gif') ?>" id="v_loading">
                            </div>
                        </div>
                        <div class="table-responsive table--no-card m-b-40" style="display: none;">
                            <table id="situaçãos" class="table table-striped table-datatable">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th class="col-6">Descrição</th>
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
                        <p>Copyright © 2018 Colorlib. All rights reserved. Template by <a
                                href="https://colorlib.com">Colorlib</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="modal">
    <div class="modal-dialog  modal-lg modal-dialog-centered">
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
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <form class="msform">


                    <ul class="progressbar">
                        <li class="active">Identificação da Situação</li>

                        <li class="d-none superusuario">Identificação</li>

                    </ul>

                    <div class="card card-step col-12 px-0">
                        <div class="card-header">
                            Identificação da Situação
                        </div>
                        <div class="card-body card-block">
                            <div class="row form-group">
                                <div class="col col-md-2">
                                    <label for="situacao_nome"
                                        class=" form-control-label"><strong>Nome*</strong></label>
                                </div>
                                <div class="col-12 col-md-10">
                                    <input type="text" id="situacao_nome" name="nome"
                                        placeholder="Nome da Situação" class="form-control" required="true"
                                        maxlength="50" minlength="3">
                                    <small class="form-text text-muted">Por favor, informe o nome da
                                        Situação</small>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-2">
                                    <label for="situacao_descricao"
                                        class=" form-control-label"><strong>Descrição*</strong></label>
                                </div>
                                <div class="col-12 col-md-10">
                                    <input type="text" id="situacao_descricao" name="nome"
                                        placeholder="Descrição" class="form-control" required="true"
                                        maxlength="100" minlength="3">
                                    <small class="form-text text-muted">Por favor, informe a descrição da
                                        Situação</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">

                            <button type="button" class="btn btn-secondary next btn-sm d-none superusuario">
                                <i class="fas fa-arrow-circle-right"></i> Próximo
                            </button>

                            <button type="button" class="btn btn-primary submit btn-sm load d-none not_superusuario">
                                <i class="fa fa-dot-circle-o"></i> Finalizar
                            </button>

                        </div>
                    </div>

                    <div class="card card-step col-12 px-0 d-none superusuario">
                        <div class="card-header">
                            Identificação
                        </div>
                        <div class="card-body card-block">
                            <div class="row form-group">
                                <div class="col col-md-2">
                                    <label for="senha"
                                        class=" form-control-label"><strong>Senha*</strong></label>
                                </div>
                                <div class="col-12 col-md-10">
                                    <input type="password" id="pass-modal-save" name="senha" placeholder="Senha Pessoal"
                                        class="form-control" autocomplete="new-password" minlength="8"
                                        required="true">
                                    <small class="form-text text-muted">Por favor, informe sua senha de
                                        acesso</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <button type="button" class="btn btn-secondary previous btn-sm">
                                <i class="fas fa-arrow-circle-left"></i> Anterior
                            </button>
                            <button type="button" class="btn btn-primary submit load btn-sm">
                                <i class="fa fa-dot-circle-o"></i> Finalizar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal footer -->
    <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Fechar</button>
    </div>
</div>


<div id="deactivate" class="d-none">
    <div class="modal-body">
        <form>
            <div class="form-group">
                <h4 style="text-align: center" class="text-danger"><i
                        class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i>
                    ATENÇÃO</h4>
            </div>

            <div id="loading-deactivate">
                <div align="center" class="center">
                    <img width="150px" src="<?=base_url('assets/images/loading.gif')?>" id="v_loading"
                        alt="Carregando">
                </div>
            </div>
            <div id="dependences" class="container"></div>

            <div class="form-group d-none superusuario">
                <input type="password" class="form-control press_enter" autocomplete="false"
                    placeholder="Confirme sua senha" required="required"
                    id="pass-modal-deactivate" minlength="8">
            </div>

            <div class="form-group">
                <button type="button" class="btn btn-confirmar-senha action_deactivate load" name="post" value=""><i
                        class="fa fa-dot-circle-o" id="icone-do-desativar"></i> Desativar</button>
            </div>
        </form>
    </div>
</div>

<div id="activate" class="d-none">
    <div class="modal-body">
        <form>
            <div class="form-group">
                <h4 style="text-align: center" class="text-danger"><i
                        class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i>
                    ATENÇÃO</h4>
                <p>Tem certeza que deseja reativar essa situação?</p>
            </div>
            <div class="form-group d-none superusuario">
                <input type="password" class="form-control press_enter" autocomplete="false"
                    placeholder="Confirme sua senha" required="required"
                    id="pass-modal-activate">
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-confirmar-senha action_activate load" name="post" value=""><i
                        class="fa fa-dot-circle-o"></i> Reativar</button>
            </div>
        </form>
    </div>
</div>


<script type="text/javascript">
    const is_superusuario = <?php echo $this->session->user['is_superusuario'] === true ? 1 : 0; ?>;
</script>