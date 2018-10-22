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
                        <input type="hidden" id="pessoa_pk" name="pessoa_pk" class="form-control">
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
                                            <th>nome</th>
                                            <th>e-mail</th>
                                            <th>opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php 
                                        if($superusuarios):
                                        foreach ($superusuarios as $key => $s): ?>
                                            <tr>
                                                <td> <?=$s->pessoa_nome?> </td>
                                                <td> <?=$s->contato_email?> </td>
                                                <td>
                                                    <?php if ($s->usuario_status == 1) {?>
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-primary reset_multistep btn-editar-super btn-attr-pessoa_pk" value="<?=$key?>">
                                                            <div class="d-none d-sm-block">
                                                                Editar
                                                            </div>
                                                            <div class="d-block d-sm-none">
                                                                <i class="fas fa-edit fa-fw"></i>
                                                            </div>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger btn-attr-pessoa_pk" value="<?=$key?>" data-toggle="modal" data-target="#d-superusuario">
                                                            <div class="d-none d-sm-block">
                                                                Desativar
                                                            </div>
                                                            <div class="d-block d-sm-none">
                                                                <i class="fas fa-times fa-fw"></i>
                                                            </div>
                                                        </button>
                                                    <?php } else {?>
                                                            <button class="btn btn-sm btn-success btn-attr-pessoa_pk" value="<?=$key?>" data-toggle="modal" data-target="#a-superusuario">
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
                                <li>Contato</li>
                                <li>Imagem</li>
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
                                        <div class="col-12 col-md-2">
                                            <label for="cpf-input" class=" form-control-label"><strong>CPF*</strong></label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="text" id="cpf-input" name="cpf" placeholder="CPF" class="form-control cpf-input" required>
                                            <small class="form-text text-muted">Por favor, informe o CPF do superusuário</small>
                                            <a href="#" class="btn btn-primary" onclick="gerarCPF()">Gerar</a>
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
                                    Contato
                                </div>
                                <div class="card-body card-block">
                                    <div class="row form-group">
                                        <div class="col-12 col-md-2">
                                            <label for="email-input" class=" form-control-label"><strong>Email*</strong></label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="email" id="email-input" name="email" placeholder="Email" class="form-control email-input" required="true">
                                            <small class="help-block form-text">Por favor, informe o email do superusuário</small>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-12 col-md-2">
                                            <label for="telefone-input" class=" form-control-label">Telefone</label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="text" id="telefone-input" name="telefone" placeholder="Telefone" class="form-control telefone-input" >
                                            <small class="help-block form-text">Por favor, informe o telefone do superusuário</small>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-12 col-md-2">
                                            <label for="celular-input" class=" form-control-label">Celular</label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="text" id="celular-input" name="celular" placeholder="Celular" class="form-control celular-input" >
                                            <small class="help-block form-text">Por favor, informe o celular do superusuário</small>
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
                                    Imagem
                                </div>
                                <div class="card-body card-block">
                                    <div class="row form-group">
                                        <div class="file-upload col-12">
                                            <button class="btn btn-secondary col-12" type="button" onclick="$('.file-upload-input').trigger( 'click' )">Salvar Imagem</button>
                                            <div class="image-upload-wrap">
                                                <input class="file-upload-input" type='file' onchange="readURL(this);" accept="image/*" />
                                                <div class="drag-text">
                                                    <h3>Arraste e solte uma imagem aqui ou selecione Salvar Imagem</h3>
                                                </div>
                                            </div>
                                            <div class="file-upload-content">
                                                <img id="img-input" class="file-upload-image" src="#" alt="your image"/>
                                                <div class="col-12">
                                                    <button type="button" onclick="remove_image()" class="btn btn-danger">Remover Imagem</button>
                                                </div>
                                            </div>
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