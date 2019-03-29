<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">gerenciamento de serviços
                        </h2>
                        <button class="au-btn au-btn-icon au-btn--blue btn_novo reset_multistep new" data-toggle="modal"
                            data-title="Novo Serviço" data-contentid="save" data-target="#modal">
                            <i class="zmdi zmdi-plus"></i>novo serviço</button>
                    </div>
                    <input type="hidden" name="opcao-editar" id="opcao-editar" value="false">
                    <div class="col-md-12 mt-3">
                        <div class="collapse" id="collapseHelp">
                            <div class="card card-body">
                                <p>Esta é a área para gerenciamento de serviços.</p>
                                <p>O serviço representa a qual grupo específico uma determinada ordem de serviço
                                    pertence.</p>
                                <p>Se por exemplo, é necessário realizar uma coleta de cão morto, o serviço ao qual esta
                                    ordem pertence é ao serviço coleta de animal.</p>
                                <p>Para cadastro de um serviço, é necessário especificar o nome do serviço, uma breve
                                    descrição de o que representa esse serviço, a situação padrão, que será atribuída
                                    como padrão às ordens de serviço pertencente a esse serviço, e por fim, a qual tipo
                                    de serviço este serviço pertence.</p>
                                <p><b>Características:</b></p>
                                <div class="col-md-12">
                                    <ul>
                                        <li>Facilita a geração de relatórios</li>
                                        <li>Facilita que funcionários possam identificar grupos específicos de serviços
                                            e tomar providências semelhantes</li>
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
                            <i style="cursor: pointer; color: gray" class="fas fa-info pull-right"
                                data-toggle="collapse" href="#collapseHelp" role="button" aria-expanded="false"
                                aria-controls="collapseHelp"></i>
                            prioridades</h2>


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
                        <div class="table-responsive table--no-card m-b-40">
                            <table id="servicos" class="table table-striped table-datatable">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Abreviação</th>
                                        <th>Descrição</th>
                                        <!-- <th>Situação Padrão</th>
                                        <th>Tipo de Serviço</th> -->
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
    <div class="modal-dialog modal-lg modal-dialog-centered">
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
                        <li class="active">Identificação da Função</li>

                        <li class="d-none superusuario">Identificação</li>

                    </ul>

                    <div class="card card-step col-12 px-0">
                        <div class="card-header">
                            Identificação da Função
                        </div>
                        <div class="card-body card-block">
                            <div class="row form-group">
                                <div class="col col-md-2">
                                    <label for="servico_nome" class=" form-control-label">
                                        <strong>Nome*</strong>
                                    </label>
                                </div>
                                <div class="col-12 col-md-10">
                                    <input type="text" id="servico_nome" placeholder="Nome do serviço"
                                        class="form-control" required="true" maxlength="50" minlength="3">
                                    <small class="form-text text-muted">Por favor, informe o nome do serviço </small>
                                </div>

                                <div class="col col-md-2">
                                    <label for="servico_abreviacao" class=" form-control-label">
                                        <strong>Abreviação*</strong>
                                    </label>
                                </div>
                                <div class="col-12 col-md-10">
                                    <input type="text" id="servico_abreviacao" placeholder="Abreviação do serviço"
                                        class="form-control" required="true" maxlength="10" minlength="3">
                                    <small class="form-text text-muted">A abreviação será utilizada na codificação da
                                        Ordem de Serviço</small>
                                </div>

                                <div class="col col-md-2">
                                    <label for="servico_desc" class=" form-control-label">
                                        <strong>Descrição*</strong>
                                    </label>
                                </div>
                                <div class="col-12 col-md-10">
                                    <textarea id="servico_desc" class="form-control" required="true"
                                        resizable="false"></textarea>
                                    <small class="form-text text-muted">Por favor, informe a descrição do serviço
                                    </small>
                                </div>


                                <div class="col-12 col-md-2">
                                    <label for="tipo_servico_pk" class=" form-control-label"><strong>Tipo de
                                            Serviço*</strong></label>
                                </div>
                                <div class="col-12 col-md-10">
                                    <select class="form-control" required="true" id="tipo_servico_fk"></select>
                                    <small class="help-block form-text">Por favor, informe o tipo de serviço ao qual
                                        esse serviço pertence.</small>
                                </div>

                                <div class="col-12 col-md-2">
                                    <label for="prioridade_fk" class=" form-control-label"><strong>Situação
                                            Padrão*</strong></label>
                                </div>
                                <div class="col-12 col-md-10">
                                    <select class="form-control" required="true" id="situacao_padrao_fk"></select>
                                    <small class="help-block form-text">Por favor, informe a situação padrão que será
                                        exibida na ordem de serviço desse serviço.</small>
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
                                    <label for="senha" class=" form-control-label"><strong>Senha*</strong></label>
                                </div>
                                <div class="col-12 col-md-10">
                                    <input type="password" id="pass-modal-save" name="senha" placeholder="Senha Pessoal"
                                        class="form-control" autocomplete="new-password" minlength="8" required="true">
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
                    <img width="150px" src="<?=base_url('assets/images/loading.gif')?>" id="v_loading" alt="Carregando">
                </div>
            </div>
            <div id="dependences" class="container"></div>

            <div class="form-group d-none superusuario">
                <input type="password" class="form-control press_enter" autocomplete="false"
                    placeholder="Confirme sua senha" required="required" id="pass-modal-deactivate" minlength="8">
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
                <p>Você está prestes a ativar um setor que foi desativado!</p>

            </div>
            <div class="form-group d-none superusuario">
                <input type="password" class="form-control press_enter" autocomplete="false"
                    placeholder="Confirme sua senha" required="required" id="pass-modal-activate">
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