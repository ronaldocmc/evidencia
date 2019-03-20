<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">gerenciamento de serviços </h2>

                        <?php if ($tipos_servicos != null) {?>
                            <button class="au-btn au-btn-icon au-btn--blue reset_multistep btn_novo new" data-toggle="modal" data-target="#ce_servico">
                                <i class="zmdi zmdi-plus"></i>novo serviço</button>
                            <?php } else {?>
                                Indisponível
                            <?php }?>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="collapse" id="collapseHelp">
                                <div class="card card-body">
                                    <p>Esta é a área para gerenciamento de serviços.</p>
                                    <p>O serviço representa a qual grupo específico uma determinada ordem de serviço pertence.</p>
                                    <p>Se por exemplo, é necessário realizar uma coleta de cão morto, o serviço ao qual esta ordem pertence é ao serviço coleta de animal.</p>
                                    <p>Para cadastro de um serviço, é necessário especificar o nome do serviço, uma breve descrição de o que representa esse serviço, a situação padrão, que será atribuída como padrão às ordens de serviço pertencente a esse serviço, e por fim, a qual tipo de serviço este serviço pertence.</p>
                                    <p><b>Características:</b></p>
                                    <div class="col-md-12">
                                        <ul>
                                            <li>Facilita a geração de relatórios</li>
                                            <li>Facilita que funcionários possam identificar grupos específicos de serviços e tomar providências semelhantes</li>
                                        </ul>
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
                            serviços</h2>
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
                                            <th>Situação Padrão</th>
                                            <th>Tipo de Serviço</th>
                                            <th>Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($servicos != null): ?>
                                            <?php foreach ($servicos as $key => $servico): ?>
                                                <tr>
                                                    <td>
                                                        <?=$servico->servico_nome?>
                                                    </td>
                                                    <td>
                                                        <?=$servico->servico_abreviacao?>
                                                    </td>
                                                    <td>
                                                        <?=$servico->servico_desc?>
                                                    </td>
                                                    <td>
                                                        <?=$servico->situacao_nome?>
                                                    </td>
                                                    <td>
                                                        <?=$servico->tipo_servico_nome?>
                                                    </td>

                                                    <td>
                                                        <div class="btn-group">
                                                            <?php if ($servico->ativo ): ?>
                                                                <button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="<?=$key?>" data-target="#ce_servico">
                                                                    <div class="d-none d-sm-block">
                                                                        Editar
                                                                    </div>
                                                                    <div class="d-block d-sm-none">
                                                                        <i class="fas fa-edit fa-fw"></i>
                                                                    </div>
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="<?=$key?>" data-target="#d_servico">
                                                                    <div class="d-none d-sm-block">
                                                                        Desativar
                                                                    </div>
                                                                    <div class="d-block d-sm-none">
                                                                        <i class="fas fa-times fa-fw"></i>
                                                                    </div>
                                                                </button>
                                                                <?php else: ?>
                                                                    <button type="button" class="btn btn-sm btn-success btn_reativar" data-toggle="modal" value="<?=$key?>" data-target="#r_servico">
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
            <div class="modal fade modal-multistep" id="ce_servico">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Novo serviço</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <input type="hidden" name="opcao-editar" id="opcao-editar">
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form class="msform">
                                        <input type="hidden" id="servico_pk" name="servico_pk" class="form-control">

                                        <!-- progressbar -->
                                        <ul class="progressbar">
                                            <li class="active">Identificação do serviço</li>
                                            <?php if ($this->session->user['is_superusuario'] === true): ?>
                                                <li>Identificação</li>
                                            <?php endif;?>
                                        </ul>
                                        <!-- fieldsets -->
                                        <div class="card card-step col-12 px-0">
                                            <div class="card-header">
                                                Identificação do serviço
                                            </div>
                                            <div class="card-body card-block">
                                                <div class="row form-group">
                                                    <div class="col col-md-2">
                                                        <label for="nome-input" class=" form-control-label">
                                                            <strong>Nome*</strong>
                                                        </label>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <input type="text" id="nome-input" placeholder="Nome do serviço" class="form-control" required="true" maxlength="50"
                                                        minlength="3">
                                                        <small class="form-text text-muted">Por favor, informe o nome do serviço </small>
                                                    </div>

                                                    <div class="col col-md-2">
                                                        <label for="abreviacao-input" class=" form-control-label">
                                                            <strong>Abreviação*</strong>
                                                        </label>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <input type="text" id="abreviacao-input" placeholder="Abreviação do serviço" class="form-control" required="true" maxlength="10"
                                                        minlength="3">
                                                        <small class="form-text text-muted">A abreviação será utilizada na codificação da Ordem de Serviço</small>
                                                    </div>

                                                    <div class="col col-md-2">
                                                        <label for="descricao-input" class=" form-control-label">
                                                            <strong>Descrição*</strong>
                                                        </label>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <textarea id="descricao-input" class="form-control" required="true" resizable="false"></textarea>
                                                        <small class="form-text text-muted">Por favor, informe a descrição do serviço </small>
                                                    </div>


                                                    <div class="col-12 col-md-2">
                                                        <label for="tipo_servico_pk" class=" form-control-label"><strong>Tipo de Serviço*</strong></label>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <select class="form-control" required="true" id="tipo_servico_fk">
                                                            <?php
                                                            foreach($tipos_servicos as $key => $tipo_servico):
                                                                echo '<option value="'.$key.'">'.$tipo_servico.'</option>';
                                                            endforeach
                                                            ?>
                                                        </select>
                                                        <small class="help-block form-text">Por favor, informe o tipo de serviço ao qual esse serviço pertence.</small>
                                                    </div>

                                                    <div class="col-12 col-md-2">
                                                        <label for="prioridade_fk" class=" form-control-label"><strong>Situação Padrão*</strong></label>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <?php echo form_dropdown('situacao_pk', $situacoes, null, 'class="form-control" required="true" id="situacao_fk"'); ?>
                                                        <small class="help-block form-text">Por favor, informe a situação padrão que será exibida na ordem de serviço desse serviço.</small>
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
                <div class="modal fade" id="d_servico">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Desativar serviço</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <h4 style="text-align: center" class="text-danger">
                                            <i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>

                                            <p>Ao desativar um serviço, as seguintes ações também serão feitas:</p>
                                            <ul style="margin-left: 15px">
                                                <li>Não será possível utilizar o serviço desativado nas novas ordens de serviço.</li>
                                                <li>As ordens de serviço antigas serão mantidas.</li>
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

                        <!-- MODAL REATIVA tipos_servicos -->
                        <div class="modal fade" id="r_servico">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Reativar serviço</h4>
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
            var is_superusuario = <?php echo $this->session->user['is_superusuario'] === true ? 1 : 0; ?>;
        </script>
                            <!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->