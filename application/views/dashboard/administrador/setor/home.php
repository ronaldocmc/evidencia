    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="overview-wrap">
                            <h2 class="title-1">gerenciamento de setores
                            </h2>
                            <button class="au-btn au-btn-icon au-btn--blue reset_multistep" data-toggle="modal" data-target="#ce_setor" >
                                <i class="zmdi zmdi-plus"></i>novo setor</button>
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
                                            <div class="col-md-6">
                                                <p>Bem-vindo a área de Gerenciamento de Setores!</p><br>
                                                <p> Aqui você poderá realizar algumas operações para controlar os setores, que representam áreas regionais do município, onde a organização vai atuar!</p><br>
                                                <p>Os setores representam regiões delimitadas, podendo conter um ou mais bairros do município. Nosso objetivo é permitir identificar áreas onde os funcionários e a ordens de serviço estarão presentes! Assim, gerenciar equipes conforme as demandas de ordem de serviço por setor torna-se uma tarefa organizada!</p>
                                            </div>
                                            <div class="col-md-6 user-guide">
                                                <p><b>Operações permitidas:</b></p>
                                                <div class="col-md-12 functions-page" >
                                                    <div class="row">
                                                        <div class="col-md-2 icon-guide">
                                                            <button type="button" disabled="true" class="btn btn-sm btn-primary reset_multistep" title="Editar">
                                                                <div class="d-none d-block">
                                                                    <i class="fas fa-plus fa-fw"></i>
                                                                </div>
                                                            </button>
                                                        </div>
                                                        <div class="col-md-10 text-guide">Inserir um novo setor</div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2 icon-guide">
                                                            <button type="button" disabled="true" class="btn btn-sm btn-primary reset_multistep" title="Editar">
                                                                <div class="d-none d-block">
                                                                    <i class="fas fa-edit fa-fw"></i>
                                                                </div>
                                                            </button>
                                                        </div>
                                                        <div class="col-md-10 text-guide">Editar um setor existente</div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2 icon-guide">
                                                            <button type="button" class="btn btn-sm btn-danger" disabled="true" title="Desativar">
                                                                <div class="d-none d-block">
                                                                    <i class="fas fa-times fa-fw"></i>
                                                                </div>
                                                            </button>
                                                        </div>
                                                        <div class="col-md-10 text-guide">Desativar um setor inativo</div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2 icon-guide">
                                                            <button type="button" class="btn btn-sm btn-success" disabled="true" title="Reativar">
                                                                <div class="d-none d-block">
                                                                    <i class="fas fa-power-off fa-fw"></i>
                                                                </div>
                                                            </button>
                                                        </div>
                                                        <div class="col-md-10 text-guide">Ativar um setor novamente</div>
                                                    </div>
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
                    <div class="row py-2">
                        <div class="col-lg-12">
                            <div class="au-card d-flex flex-column">

                                <h2 class="title-1 m-b-25">
                                    <i style="cursor: pointer; color: gray" class="fas fa-info pull-right" data-toggle="collapse" href="#collapseHelp" role="button" aria-expanded="false" aria-controls="collapseHelp"></i>
                                Setores</h2>

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
                                    <table  id="setors" class="table table-striped table-datatable">
                                        <thead>
                                            <tr>
                                                <th class="col-8">Nome</th>
                                                <th>Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($setores != null): ?>

                                                <?php foreach ($setores as $key => $setor): ?>
                                                    <tr>
                                                        <td>
                                                            <?=$setor->setor_nome?>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <?php if ($setor->ativo): ?>
                                                                    <button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="<?=$key?>" data-target="#ce_setor" title="Editar">
                                                                        <div class="d-none d-sm-block">
                                                                            <i class="fas fa-edit fa-fw"></i>
                                                                        </div>
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="<?=$key?>" data-target="#d-setor" title="Desativar">
                                                                        <div class="d-none d-sm-block">
                                                                            <i class="fas fa-times fa-fw"></i>
                                                                        </div>
                                                                    </button>
                                                                    <?php else: ?>
                                                                        <button type="button" class="btn btn-sm btn-success btn_reativar" data-toggle="modal" value="<?=$key?>" data-target="#r-setor" title="Reativar">
                                                                            <div class="d-none d-sm-block">
                                                                                <i class="fas fa-power-off fa-fw"></i>
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
                                    <p>Copyright © 2018 Colorlib. All rights reserved. Template by <a href="https://colorlib.com">Colorlib</a>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- MODAL CRIA E ATUALIZA SETOR -->
            <div class="modal fade modal-multistep" id="ce_setor">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <!-- Modal Header -->
                      <div class="modal-header">
                        <h4 class="modal-title">Novo setor</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="msform">
                                    <input type="hidden" id="setor_pk" name="setor_pk" class="form-control">
                                    <!-- progressbar -->
                                    <ul class="progressbar">
                                        <li class="active">Identificação do Setor</li>
                                        <?php if ($this->session->user['is_superusuario'] === true): ?>
                                            <li>Identificação</li>
                                        <?php endif;?>
                                    </ul>
                                    <!-- fieldsets -->
                                    <div class="card card-step col-12 px-0">
                                        <div class="card-header">
                                            Identificação do Setor
                                        </div>
                                        <div class="card-body card-block">
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="nome-input" class=" form-control-label"><strong>Nome</strong></label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" id="nome-input" name="nome" placeholder="Nome do Setor" class="form-control" required="true" maxlength="50" minlength="3">
                                                    <small class="form-text text-muted">Por favor, informe o nome do setor</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-center">
                                            <?php if ($this->session->user['is_superusuario'] === true): ?>
                                                <button type="button" class="btn btn-secondary next btn-sm">
                                                    <i class="fas fa-arrow-circle-right"></i> Próximo
                                                </button>
                                                <?php else: ?>
                                                    <button type="button"  class="btn btn-primary submit btn-sm">
                                                        <i class="fa fa-dot-circle-o"></i> Enviar
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
                                                            <label for="senha-input" class=" form-control-label"><strong>Senha</strong></label>
                                                        </div>
                                                        <div class="col-12 col-md-10">
                                                            <input type="password" id="senha-input" name="senha" placeholder="Senha Pessoal" class="form-control" autocomplete="new-password"   minlength="8" required="true">
                                                            <small class="form-text text-muted">Por favor, informe sua senha de acesso</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-center">
                                                    <button type="button" class="btn btn-secondary previous btn-sm">
                                                        <i class="fas fa-arrow-circle-left"></i> Anterior
                                                    </button>
                                                    <button type="button"  class="btn btn-primary submit btn-sm">
                                                        <i class="fa fa-dot-circle-o"></i> Enviar
                                                    </button>
                                                </div>
                                            </div>
                                        <?php endif;?>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer d-md-none">
                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- MODAL DELETA DEPARTAMENTOS -->
            <div class="modal fade" id="d-setor" >
             <div class="modal-dialog modal-dialog-centered">
                 <div class="modal-content">
                     <div class="modal-header">
                         <h4 class="modal-title">Desativar Setor</h4>
                         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                     </div>
                     <div class="modal-body">
                        <form>
                         <div class="form-group">
                             <h4 style="text-align: center" class="text-danger"><i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                             <p>Ao desativar um setor, as seguintes ações também serão feitas:</p>
                             <ul style="margin-left: 15px">
                                 <li>Os funcionários do setor, se existentes, ficarão sem setor designado</li>
                                 <li>Novas ordens de serviço não poderão ser criadas para este setor</li>
                             </ul>
                         </div>
                         <?php if ($this->session->user['is_superusuario'] === true): ?>
                             <div class="form-group">
                                 <input type="password" class="form-control" autocomplete="false" name="pass-modal-desativar" placeholder="Confirme sua senha" required="required" id="pass-modal-desativar" minlength="8">
                             </div>
                         <?php endif;?>
                         <div class="form-group">
                             <button type="button" class="btn btn-confirmar-senha" id="btn-desativar" name="post" value=""><i class="fa fa-dot-circle-o" id="icone-do-desativar"></i> Desativar</button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>

     <!-- MODAL REATIVA SETORES -->
     <div class="modal fade" id="r-setor" >
         <div class="modal-dialog modal-dialog-centered">
             <div class="modal-content">
                 <div class="modal-header">
                     <h4 class="modal-title">Reativar Setor</h4>
                     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 </div>
                 <div class="modal-body">
                    <form>
                     <div class="form-group">
                         <h4 style="text-align: center" class="text-danger"><i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                         <p>Ao reativar um setor, a seguinte ação será possível:</p>
                         <ul style="margin-left: 15px">
                             <li>Criar novas ordens de serviços designadas a este setor</li>
                         </ul>
                     </div>
                     <?php if ($this->session->user['is_superusuario']): ?>
                         <div class="form-group">
                             <input type="password" class="form-control" autocomplete="false" name="pass-modal-reativar" placeholder="Confirme sua senha" required="required" id="pass-modal-reativar" pattern="{8,}">
                         </div>
                     <?php endif;?>
                     <div class="form-group">
                         <button type="button" class="btn btn-confirmar-senha" id="btn-reativar" name="post" value=""><i class="fa fa-dot-circle-o" id="icone-do-desativar"></i> Reativar</button>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>

 <script type="text/javascript">
    var setores = <?php echo json_encode($setores !== false ? $setores : []); ?>;

    var is_superusuario = <?php echo $this->session->user['is_superusuario'] === true ? 1 : 0; ?>;
</script>
<!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->