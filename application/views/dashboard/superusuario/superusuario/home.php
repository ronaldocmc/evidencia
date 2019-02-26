<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">Gerenciamento de Superusuários</h2>
                        <button class="au-btn au-btn-icon au-btn--blue reset_multistep new" id="novo_sup_btn">
                            <i class="zmdi zmdi-plus"></i>novo superusuário</button>
                        </div>
                        <!-- CAMPO HIDDEN PARA O ID -->
                        <input type="hidden" id="superusuario_pk" name="superusuario_pk" class="form-control">
                        <input type="hidden" id="opcao-editar" name="opcao-editar" class="form-control" value="false">
                    </div>
                </div>

                <div class="row py-5">
                    <div class="col-12">
                        <div class="au-card">
                            <h2 class="title-1 m-b-25">Superusuários</h2>
                            <div class="">
                            <h5>Filtrar por</h5><br>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="filter-ativo">Mostrar</label>
                                    <select name="filter-ativo" id="filter-ativo" class="form-control" onchange="update_table()">
                                        <option value="todos">Todos</option>
                                        <option value="ativos">Apenas ativos</option>
                                        <option value="desativados">Apenas desativados</option>
                                    </select><br>
                                </div>
                                </div>
                            </div>
                            <div class="table-responsive table--no-card m-b-40">
                                <table class="table table-striped table-datatable">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Login</th>
                                            <th>Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php 
                                        if($superusuarios):
                                        foreach ($superusuarios as $key => $s): ?>
                                            <tr>
                                                <td> <?=$s->superusuario_nome?> </td>
                                                <td> <?=$s->superusuario_login?> </td>
                                                <td>
                                                    <?php if ($s->ativo == 1) {?>
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-primary reset_multistep btn-editar-super btn-attr-superusuario_pk" value="<?=$key?>">
                                                            <div class="d-none d-sm-block">
                                                                Editar
                                                            </div>
                                                            <div class="d-block d-sm-none">
                                                                <i class="fas fa-edit fa-fw"></i>
                                                            </div>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger btn-attr-superusuario_pk" value="<?=$key?>" data-toggle="modal" data-target="#d-superusuario">
                                                            <div class="d-none d-sm-block">
                                                                Desativar
                                                            </div>
                                                            <div class="d-block d-sm-none">
                                                                <i class="fas fa-times fa-fw"></i>
                                                            </div>
                                                        </button>
                                                    <?php } else {?>
                                                            <button class="btn btn-sm btn-success btn-attr-superusuario_pk" value="<?=$key?>" data-toggle="modal" data-target="#a-superusuario">
                                                            <div class="d-none d-sm-block">
                                                                Ativar
                                                            </div>
                                                            <div class="d-block d-sm-none">
                                                                <i class="fas fa-times fa-fw"></i>
                                                            </div>
                                                        </button>
                                                        <?php }?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; endif;?>
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

<div class="modal fade modal-multistep" id="ce_superusuario">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="titulo">Editar Superusuário</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="msform">
                            <!-- progressbar -->
                            <ul class="progressbar">
                                <li class="active">Informações Pessoais</li>
                                <li>Acesso</li>
                                <li>Identificação</li>
                            </ul>
                            <!-- fieldsets -->
                            <div class="card card-step col-12 px-0">
                                <div class="card-header">
                                    Informações Pessoais
                                </div>
                                <div class="card-body card-block">
                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="nome-input" class=" form-control-label"><strong>Nome*</strong></label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="text" id="nome-input" name="nome" placeholder="Nome Completo" class="form-control nome-input" required maxlength="50" minlength="5" required>
                                            <small class="form-text text-muted">Por favor, informe o nome completo do superusuário</small>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="email-input" class=" form-control-label"><strong>E-mail*</strong></label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="text" id="email-input" name="email" placeholder="Endereço de e-mail válido" class="form-control email-input" required maxlength="50" minlength="5" required>
                                            <small class="form-text text-muted">Por favor, informe o e-mail do superusuário</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <button type="button" class="btn btn-secondary next btn-sm">
                                        <i class="fas fa-arrow-circle-right"></i> Próximo
                                    </button>
                                </div>
                            </div>
                            <div class="card card-step col-12 px-0">
                                <div class="card-header">
                                    Acesso
                                </div>
                                <div class="card-body card-block">
                                    <div class="row form-group">
                                        <div class="col-12 col-md-2">
                                            <label for="login-input" class=" form-control-label"><strong>Login*</strong></label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="login" id="login-input" name="login" placeholder="login" class="form-control login-input" required="true">
                                            <small class="help-block form-text">Por favor, informe o login do superusuário</small>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="card-footer text-center">
                                    <button type="button" class="btn btn-secondary previous btn-sm">
                                        <i class="fas fa-arrow-circle-left"></i> Anterior
                                    </button>
                                    <button type="button" class="btn btn-secondary next btn-sm">
                                        <i class="fas fa-arrow-circle-right"></i> Próximo
                                    </button>
                                </div>
                            </div>
                            
                            <div class="card card-step col-12 px-0">
                                <div class="card-header">
                                    Identificação
                                </div>
                                <div class="card-body card-block">
                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="nome-input" class=" form-control-label"><strong>Senha*</strong></label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="password" id="pass-modal-edit" name="password-modal-edit" placeholder="Confirme sua senha" class="form-control press_enter" required minlength="8" autocomplete="new-password">
                                            <small class="form-text text-muted">Por favor, informe novamente sua senha para confirmar a operação</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <button type="button" class="btn btn-secondary previous btn-sm">
                                        <i class="fas fa-arrow-circle-left"></i> Anterior
                                    </button>
                                    <button type="button" onclick="send_data();" class="btn btn-primary submit btn-sm" id="botao-finalizar">
                                        <i class="fa fa-dot-circle-o"></i> Salvar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary submit btn-sm" id="pula-para-confirmacao">
                    <i class="fa fa-dot-circle-o"></i> Salvar
                </button>
                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Fechar</button>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="d-superusuario" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Desativar Superusuário</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <h4 style="text-align: center" class="text-danger"><i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                    <p>Ao desativar o superusuário, o mesmo:</p>
                    <ul style="margin-left: 15px">
                        <li>Perderá acesso ao sistema</li>
                        <li>Não poderá exercer suas atividades de gerencia</li>
                    </ul>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control press_enter" autocomplete="false" placeholder="Confirme sua senha" required="required" id="pass-modal-desativar" pattern="{8,}">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-confirmar-senha" id="btn-deactivate" name="post">
<i class="fa fa-dot-circle-o"></i> Desativar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="a-superusuario" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ativar Superusuário</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <h4 style="text-align: center" class="text-danger"><i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                    <p>Ao ativar o superusuário, o mesmo:</p>
                    <ul style="margin-left: 15px">
                        <li>Recuperará o acesso ao sistema</li>
                    </ul>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control press_enter" autocomplete="false" placeholder="Confirme sua senha" required="required" id="pass-modal-ativar" pattern="{8,}">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-confirmar-senha" id="btn-activate" name="post">
<i class="fa fa-dot-circle-o"></i> Ativar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var superusuarios = <?php echo json_encode($superusuarios !== false ? $superusuarios : [] ) ?>;
    console.log(superusuarios);
</script>