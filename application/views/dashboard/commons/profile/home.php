<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">Meu Perfil</h2>
                        <button class="au-btn au-btn-icon au-btn--blue reset_multistep" onclick="$('#cp-pessoa').modal('show');">
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


                            <?php if ($usuario->local_fk !==NULL): ?>
                            <div class="">
                                Endereço
                            </div>
                            <div class="card-body card-block">
                                <div class="row form-group">
                                    <div class="col-12 col-md-2">
                                        <label for="uf-input" class="form-control-label"><strong>Estado</strong></label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <select class="form-control loading" id="uf-input" name="estado_pk" required="true" data-value="<?= $usuario->estado_fk ?>">
                                        </select>
                                        <small class="form-text text-muted"></small>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="cidade-input" class=" form-control-label"><strong>Cidade</strong></label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <select class="form-control loading" id="cidade-input" name="municipio_pk" required="true" data-value="<?= $usuario->municipio_pk ?>">
                                        </select>

                                        <small class="help-block form-text">Por favor, informe a cidade</small>
                                    </div> 
                                </div>
                                <div class="row form-group">
                                    <div class="col-12 col-md-2">
                                        <label for="logradouro-input" class=" form-control-label"><strong>Logradouro</strong></label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <input type="hidden" name="logradouro_nome" id="logradouro_pk" value="">
                                        <div class="dropdown" id="drop">
                                            <input type="text" id="logradouro-input" name="logradouro_nome"  class="form-control input-dropdown" autocomplete="off" placeholder="Logradouro" data-src = '["<?php echo base_url('localizacao/logradouros'); ?>","https://viacep.com.br/ws"]' data-index='["logradouro_pk","logradouro"]' data-value='["logradouro_nome","logradouro"]' data-params  = '[[["this","logradouro_nome","val"],["cidade-input","municipio_pk","val"]],[["uf-input",null,"text"],["cidade-input",null,"text"],["this",null,"val"],["json",null,"param"]]]' data-action='["post","get"]' data-arrayret='["data",null]' value="<?= ucwords(mb_strtolower($usuario->logradouro_nome,'UTF-8')) ?>">
                                            <small class="help-block form-text helper-dropdown">Por favor, informe o logradouro do funcionário</small>
                                            <ul class="dropdown-menu" data-return = "#logradouro_pk" data-next="#numero-input">
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-1">
                                        <label for="numero-input" class=" form-control-label"><strong>Nº</strong></label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input type="number" id="numero-input" name="local_num" placeholder="Nº" class="form-control numero-input" min="0" required="true" value="<?= $usuario->local_num ?>">
                                        <small class="form-text text-muted"></small>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12 col-md-2">
                                        <label for="complemento-input" class=" form-control-label">Complemento</label>
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <input type="text" id="complemento-input" name="local_complemento" placeholder="Complemento" class="form-control" maxlength="30" value="<?= $usuario->local_complemento ?>">
                                        <small class="help-block form-text">Por favor, informe o complemento</small>
                                    </div>
                                    <div class="col-12 col-md-1">
                                        <label for="bairro-input" class=" form-control-label"><strong>Bairro</strong><br></label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                         <input type="hidden" name="bairro_nome" id="bairro_pk">
                                        <div class="dropdown" id="drop">
                                            <input type="text" id="bairro-input" name="bairro"  class="form-control input-dropdown" placeholder="Bairro" autocomplete="off" data-src = '["<?php echo base_url('localizacao/bairros'); ?>","https://viacep.com.br/ws"]' data-index='["bairro_pk","bairro"]' data-value='["bairro_nome","bairro"]' data-params  = '[[["cidade-input",null,"val"]],[["uf-input",null,"text"],["cidade-input",null,"text"],["logradouro-input",null,"val"],["json",null,"param"]]]' data-action='["get","get"]' data-arrayret='["data",null]' value="<?= ucwords(mb_strtolower($usuario->bairro_nome,'UTF-8')) ?>">
                                            <small class="help-block form-text helper-dropdown">Por favor, informe o bairro do funcionário</small>
                                            <ul class="dropdown-menu" data-return = "#bairro_pk" data-next="#bairro-input">
                                            </ul>
                                        </div>
                                    </div>                                                    
                                </div>
                            </div>
                            <?php endif; ?>

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

<div class="modal fade" id="cp-pessoa">
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