    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="overview-wrap">
                            <h2 class="title-1">gerenciamento de departamentos
                            </h2>
                            <button class="au-btn au-btn-icon au-btn--blue btn_novo reset_multistep new" data-toggle="modal" data-target="#ce_departamento" >
                                <i class="zmdi zmdi-plus"></i>novo departamento</button>
                            </div>
                            <input type="hidden" name="opcao-editar" id="opcao-editar" value="false">
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
                                            <p>Bem-vindo a área de Gerenciamento de Departamentos!</p><br>
                                            <p> Aqui você poderá realizar algumas operações para controlar os departamentos da sua organização.</p><br>
                                            <p>Organizamos o seu departamento de modo que ele possua serviços específicos conforme um tipo de serviço definido. Assim, controlar a prestação de serviços da sua organização torna-se uma tarefa fácil e rápida! </p>
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
                                                    <div class="col-md-10 text-guide">Inserir um departamento</div>
                                                </div>
<!--                                                 <div class="row"> 
                                                    <div class="col-md-1 icon-guide" style="">
                                                        <button type="button" disabled="true" class="btn btn-sm btn-primary reset_multistep" title="Inserir" style="color: #4272d7 !important;">
                                                            <div class="d-none d-sm-block">
                                                                <i class="fas fa-plus" style="color: white;"></i>
                                                            </div>
                                                        </button>
                                                    </div>
                                                    <div class="col-md-11 text-guide">Inserir um departamento</div>
                                                </div> -->
                                                <div class="row">
                                                    <div class="col-md-2 icon-guide">
                                                        <button type="button" disabled="true" class="btn btn-sm btn-primary reset_multistep" title="Editar">
                                                            <div class="d-none d-block">
                                                                <i class="fas fa-edit fa-fw"></i>
                                                            </div>
                                                        </button>
                                                    </div>
                                                    <div class="col-md-10 text-guide">Editar departamento existente</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2 icon-guide">
                                                        <button type="button" class="btn btn-sm btn-danger" disabled="true" title="Desativar">
                                                                <div class="d-none d-block">
                                                                    <i class="fas fa-times fa-fw"></i>
                                                                </div>
                                                        </button>
                                                    </div>
                                                    <div class="col-md-10 text-guide">Desativar departamento inativo</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2 icon-guide">
                                                        <button type="button" class="btn btn-sm btn-success" disabled="true" title="Reativar">
                                                                <div class="d-none d-block">
                                                                    <i class="fas fa-power-off fa-fw"></i>
                                                                </div>
                                                        </button>
                                                    </div>
                                                    <div class="col-md-10 text-guide">Ativar departamento novamente</div>
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
                                departamentos</h2>


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
                                    <table  id="departamentos" class="table table-striped table-datatable">
                                        <thead>
                                            <tr>
                                                <th class="col-8">Nome</th>
                                                <th>Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($departamentos != null): ?>


                                                <?php foreach ($departamentos as $key => $departamento): ?>
                                                    <tr>
                                                        <td>
                                                            <?=$departamento->departamento_nome?>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <?php if ($departamento->ativo): ?>
                                                                    <button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="<?=$key?>" data-target="#ce_departamento" title="Editar">
                                                                        <div class="d-none d-sm-block">
                                                                            <i class="fas fa-edit fa-fw"></i>
                                                                        </div>
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="<?=$key?>" data-target="#d-departamento" title="Desativar">
                                                                        <div class="d-none d-block">
                                                                            <i class="fas fa-times fa-fw"></i>
                                                                        </div>
                                                                    </button>
                                                                    <?php else: ?>
                                                                        <button type="button" class="btn btn-sm btn-success btn_reativar" value="<?=$key?>" title="Reativar">
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


            <!-- MODAL CRIA E ATUALIZA DEPARTAMENTO -->
            <div class="modal fade modal-multistep" id="ce_departamento">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <!-- Modal Header -->
                      <div class="modal-header">
                        <h4 class="modal-title">Novo departamento</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="msform">
                                    <input type="hidden" id="departamento_pk" name="departamento_pk" class="form-control">
                                    <!-- progressbar -->
                                    <ul class="progressbar">
                                        <li class="active">Identificação do Departamento</li>
                                        <?php if ($this->session->user['is_superusuario'] === true): ?>
                                            <li>Identificação</li>
                                        <?php endif;?>
                                    </ul>
                                    <!-- fieldsets -->
                                    <div class="card card-step col-12 px-0">
                                        <div class="card-header">
                                            Identificação do Departamento
                                        </div>
                                        <div class="card-body card-block">
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="nome-input" class=" form-control-label"><strong>Nome*</strong></label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" id="nome-input" name="nome" placeholder="Nome do Departamento" class="form-control" required="true" maxlength="50" minlength="3">
                                                    <small class="form-text text-muted">Por favor, informe o nome do departamento</small>
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
                                                            <label for="senha-input" class=" form-control-label"><strong>Senha*</strong></label>
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
                                                    <button type="button"  class="btn btn-primary submit btn-sm" id="botao-finalizar">
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


            <!-- MODAL DELETA DEPARTAMENTOS -->
            <div class="modal fade" id="d-departamento" >
             <div class="modal-dialog modal-dialog-centered">
                 <div class="modal-content">
                     <div class="modal-header">
                         <h4 class="modal-title">Desativar Departamento</h4>
                         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                     </div>
                     <div class="modal-body">
                        <form>
                         <div class="form-group">
                             <h4 style="text-align: center" class="text-danger"><i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                             <!-- <p>Ao desativar um departamento, as seguintes ações também serão feitas:</p>
                             <ul style="margin-left: 15px">
                                 <li>Todas os tipos de serviços serão desativados também</li>
                                 <li>Nenhuma ordem de serviço com estes tipos poderão ser registradas</li>
                             </ul> -->
                         </div>

                        <div id="loading-departamento-deactivate">
                            <div align="center" class="center">
                                <img width="150px" src="<?= base_url('assets/images/loading.gif') ?>" id="v_loading" alt="Carregando">
                            </div>
                        </div>
                        <div id="tipo-servicos-dependentes"></div>

                        <?php if ($this->session->user['is_superusuario'] === true): ?>
                         <div class="form-group">
                             <input type="password" class="form-control press_enter" autocomplete="false" name="pass-modal-desativar" placeholder="Confirme sua senha" required="required" id="pass-modal-desativar" minlength="8">
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

 <?php //if ($this->session->user['is_superusuario']): ?>
 <!-- MODAL REATIVA DEPARTAMENTOS -->
 <div class="modal fade" id="r-departamento" >
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h4 class="modal-title">Reativar Departamento</h4>
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
             </div>
             <div class="modal-body">
                <form>
                 <div class="form-group">
                     <h4 style="text-align: center" class="text-danger"><i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                     <p>Ao reativar um departamento, as seguintes ações também serão feitas:</p>
                     <ul style="margin-left: 15px">
                         <li>Todas os tipos de serviços serão reativados também</li>
                         <li>Toda ordem de serviço com estes tipos poderão ser registradas novamente</li>
                     </ul>
                 </div>
                 <?php if ($this->session->user['is_superusuario']): ?>
                     <div class="form-group">
                         <input type="password" class="form-control press_enter" autocomplete="false" name="pass-modal-reativar" placeholder="Confirme sua senha" required="required" id="pass-modal-reativar" pattern="{8,}">
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

<?php //endif;?>
<script type="text/javascript">
    var departamentos = <?php echo json_encode($departamentos !== false ? $departamentos : []); ?>;

    var is_superusuario = <?php echo $this->session->user['is_superusuario'] === true ? 1 : 0; ?>;
</script>
<!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->