    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="overview-wrap">
                            <h2 class="title-1">gerenciamento de prioridades </h2>
                            <button class="au-btn au-btn-icon au-btn--blue reset_multistep btn-novo new" data-toggle="modal" data-target="#ce_prioridade" >
                                <i class="zmdi zmdi-plus"></i>nova prioridade</button>
                            </div>
                            <input type="hidden" name="opcao-editar" id="opcao-editar">
                            <div class="col-md-12 mt-3">
                                <div class="collapse" id="collapseHelp">
                                    <div class="card card-body">
                                        <p>Esta é a área para o gerenciamento de prioridades.</p>
                                        <p>As prioridades tem como objetivo atribuir um nome a um prazo a ser cumprido. </p>
                                        <p>Se por exemplo, em sua empresa quando algo é urgente o prazo é no máximo três horas, você pode estar criando através desta área.</p>
                                        <p>Através disso, é possível que o sistema possa identificar ordens de serviços que não foram cumpridas dentro do prazo.</p>
                                        <p><b>Características:</b></p>
                                        <div class="col-md-12">
                                            <ul>
                                                <li>Facilita que o sistema identifique ordens de serviços que não foram cumpridas dentro do prazo</li>
                                                <li>Possibilita que diferentes prioridades sejam criadas, podendo se ter uma maior personalização</li>
                                                
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
                                prioridades</h2>
                                <div class="table-responsive table--no-card m-b-40">
                                    <table  id="prioridades" class="table table-striped table-datatable">
                                        <thead>
                                            <tr>
                                                <th class="col-8">Nome</th>
                                                <th>Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($prioridades != null): ?>


                                                <?php foreach ($prioridades as $key => $prioridade): ?>
                                                    <tr>
                                                        <td>
                                                            <?=$prioridade->prioridade_nome?>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="<?=$key?>" data-target="#ce_prioridade">
                                                                    <div class="d-none d-sm-block">
                                                                        Editar
                                                                    </div>
                                                                    <div class="d-block d-sm-none">
                                                                        <i class="fas fa-edit fa-fw"></i>
                                                                    </div>
                                                                </button>
                                                                <?php if ($prioridade->ativo): ?>
                                                                    <button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="<?=$key?>" data-target="#d-prioridade">
                                                                        <div class="d-none d-sm-block">
                                                                            Desativar
                                                                        </div>
                                                                        <div class="d-block d-sm-none">
                                                                            <i class="fas fa-times fa-fw"></i>
                                                                        </div>
                                                                    </button>
                                                                <?php else: ?>
                                                                    <button type="button" class="btn btn-sm btn-success btn-reativar" data-toggle="modal" value="<?=$key?>" data-target="#r-prioridade">
                                                                        <div class="d-none d-sm-block">
                                                                            Ativar
                                                                        </div>
                                                                        <div class="d-block d-sm-none">
                                                                            <i class="fas fa-times fa-fw"></i>
                                                                        </div>
                                                                    </button>
                                                                <?php endif ?>
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


        <div class="modal fade modal-multistep" id="ce_prioridade">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <!-- Modal Header -->
                      <div class="modal-header">
                        <h4 class="modal-title">Nova prioridade</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="msform">
                                    <input type="hidden" id="prioridade_pk" name="prioridade_pk" class="form-control">
                                    <ul class="progressbar">
                                        <li class="active">Identificação do Setor</li>
                                        <?php if ($this->session->user['is_superusuario'] === true): ?>
                                            <li>Identificação</li>
                                        <?php endif;?>
                                    </ul>
                                    <!-- fieldsets -->
                                    <div class="card card-step col-12 px-0">
                                        <div class="card-header">
                                            Identificação da Prioridade
                                        </div>
                                        <div class="card-body card-block">
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="prioridade_nome" class=" form-control-label"><strong>Nome</strong></label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" id="prioridade_nome" name="prioridade_nome" placeholder="Nome da Prioridade" class="form-control" required="true" maxlength="50" minlength="3">
                                                    <small class="form-text text-muted">Por favor, informe o nome da prioridade</small>
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
                                                            <label for="senha-input" class="form-control-label"><strong>Senha</strong></label>
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

        <!-- MODAL DELETA prioridadeS -->
        <div class="modal fade" id="d-prioridade" >
         <div class="modal-dialog modal-dialog-centered">
             <div class="modal-content">
                 <div class="modal-header">
                     <h4 class="modal-title">Desativar prioridade</h4>
                     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 </div>
                 <div class="modal-body">
                    <form>
                     <div class="form-group">
                         <h4 style="text-align: center" class="text-danger"><i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                     </div>
                     <div class="form-group">
                        <div id="loading-prioridade-deactivate">
                            <div align="center" class="center">
                                <img width="150px" src="<?= base_url('assets/images/loading.gif') ?>" id="v_loading" alt="Carregando">
                            </div>
                        </div>
                        <div id="tipo-servicos-dependentes"></div>

                    </div>
                    <?php if ($this->session->user['is_superusuario'] === true): ?>
                     <div class="form-group">
                         <input type="password" class="form-control" autocomplete="false" name="pass-modal-desativar" placeholder="Confirme sua senha" required="required" id="pass-modal-desativar" minlength="8">
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

    <!-- MODAL REATIVA prioridadeS -->
<div class="modal fade" id="r-prioridade" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Reativar prioridade</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <p>Deseja confirmar a reativação da prioridade?</p>
                    </div>
                    <div class="form-group">
                         <button type="button" class="btn btn-confirmar-senha" id="btn-reativar" name="post" value=""><i class="fa fa-dot-circle-o"></i> Reativar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var prioridades = <?php echo json_encode($prioridades !== false ? $prioridades : []); ?>;

    var is_superusuario = <?php echo $this->session->user['is_superusuario'] === true ? 1 : 0; ?>;
</script>
<!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->