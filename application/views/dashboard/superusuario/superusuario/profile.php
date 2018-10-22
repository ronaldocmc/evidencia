<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">Meu Perfil</h2>
                        <button class="au-btn au-btn-icon au-btn--blue reset_multistep" onclick="$('#cp-superusuario').modal('show');">
                            <i class="zmdi zmdi-lock"></i>alterar senha</button>
                    </div>
                </div>
            </div>

            <div class="row py-5">
                <form id="form-profile">
                    <div class="col-12">
                        <div class="au-card">
                            <div class="">
                                Informações Pessoais
                            </div>
                            <input type="hidden" name="pessoa_pk" id="pessoa_pk" value="<?= $this->session->user['id_user'] ?>">
                            <div class="card-body card-block">
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="nome-input" class=" form-control-label">
                                            <strong>Nome*</strong>
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="text" id="nome-input" name="nome" placeholder="Nome Completo" class="form-control nome-input" required maxlength="50"
                                            minlength="5" required value="<?= $usuario->pessoa_nome ?>">
                                        <small class="form-text text-muted">Por favor, informe o nome completo</small>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12 col-md-2">
                                        <label for="cpf-input" class=" form-control-label">
                                            <strong>CPF*</strong>
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="text" id="cpf-input" name="cpf" placeholder="CPF" class="form-control cpf-input" required value="<?= $usuario->pessoa_cpf ?>">
                                        <small class="form-text text-muted">Por favor, informe o CPF</small>
                                    </div>
                                </div>
                            </div>



                            <div class="">
                                Contato
                            </div>
                            <div class="card-body card-block">
                                <div class="row form-group">
                                    <div class="col-12 col-md-2">
                                        <label for="email-input" class=" form-control-label">
                                            <strong>Email*</strong>
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="email" id="email-input" name="email" placeholder="Email" class="form-control email-input" required="true" value="<?= $usuario->contato_email ?>">
                                        <small class="help-block form-text">Por favor, informe o email</small>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12 col-md-2">
                                        <label for="telefone-input" class=" form-control-label">Telefone</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="text" id="telefone-input" name="telefone" placeholder="Telefone" class="form-control telefone-input" value="<?= $usuario->contato_tel ?>">
                                        <small class="help-block form-text">Por favor, informe o telefone</small>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12 col-md-2">
                                        <label for="celular-input" class=" form-control-label">Celular</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="text" id="celular-input" name="celular" placeholder="Celular" class="form-control celular-input" value="<?= $usuario->contato_cel ?>">
                                        <small class="help-block form-text">Por favor, informe o celular</small> 
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="">Imagem</label>
                                </div>
                                <div class="col-12 col-md-6">
                                    <img src="<?php echo $this->session->user['image_user'] ?>" alt="<?= $usuario->pessoa_nome ?>" />
                                    <!-- //$this->session->user['img_user'] -->
                                    <br>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="image-upload-wrap">
                                        <input class="file-upload-input" type='file' onchange="readURL(this);" accept="image/*" />
                                        <div class="drag-text">
                                            <h3>Ou clique/arraste e solte uma imagem aqui</h3>
                                        </div>
                                    </div>
                                    <div class="file-upload-content">
                                        <img id="img-input" class="file-upload-image" src="#" alt="your image" />
                                        <div class="col-12">
                                            <button type="button" onclick="remove_image()" class="btn btn-danger">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="card-body card-block">
                            <div class="row form-group">
                                <div class="file-upload col-12">
                                    
                                    <div class="image-upload-wrap">
                                        <input class="file-upload-input" type='file' onchange="readURL(this);" accept="image/*" />
                                        <div class="drag-text">
                                            <h3>Clique ou Arraste e solte uma imagem aqui</h3>
                                        </div>
                                    </div>
                                    <div class="file-upload-content">
                                        <img id="img-input" class="file-upload-image" src="#" alt="your image" />
                                        <div class="col-12">
                                            <button type="button" onclick="remove_image()" class="btn btn-danger">Remover Imagem</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                            <button class="btn btn-primary col-12" type="button" id="btn-open-modal-save"><i class="fa fa-dot-circle-o"></i> Salvar</button>
                        </div>
                    </div>
                </form>
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

<div class="modal fade" id="s-superusuario">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Salvar Superusuário</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <h4 style="text-align: center" class="text-danger">
                        <i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                    <p>Para a segurança da sua conta, digite sua senha para salvar.</p>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" autocomplete="false" placeholder="Confirme sua senha" required="required" id="pass-modal-edit"
                        pattern="{8,}">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-confirmar-senha" id="btn-save-profile" name="post"><i class="fa fa-dot-circle-o"></i> Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cp-superusuario">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Alterar senha</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Senha atual</label>
                    <input type="password" class="form-control" autocomplete="false" required="required" id="old_password">
                    <label for="">Nova senha</label>
                    <input type="password" class="form-control" autocomplete="false" required="required" id="new_password">
                    <label for="">Confirme a nova senha</label>
                    <input type="password" class="form-control" autocomplete="false" required="required" id="confirm_new_password">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-confirmar-senha" id="btn-change-password"><i class="fa fa-dot-circle-o"></i> Alterar</button>
                </div>
            </div>
        </div>
    </div>
</div>