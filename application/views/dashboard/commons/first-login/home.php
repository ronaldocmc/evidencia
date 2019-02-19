<div class="modal col-md-12 fade" id="first_login" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="titulo">Seja bem-vindo ao Evidência!</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="msform">
                            <!-- progressbar -->
                            <ul class="progressbar">
                                <li class="active">ATENÇÃO</li>
                                <li>Login</li>
                                <li>Senha</li>
                                <li>Confirmar</li>
                            </ul>
                            <!-- fieldsets -->
                            <div class="card card-step col-12 px-0">
                                <!-- CAMPO HIDDEN -->
                                <input type="hidden" value="<?= $recuperacao_token ?>" name="token" id="token">
                                <input type="hidden" value="<?= $superusuario_fk ?>" name="superusuario_fk" id="superusuario_fk">
                                 <input type="hidden" value="<?= $organizacao_fk ?>" name="organizacao_fk" id="organizacao_fk">
                                <div class="container mt-1 form-group">
                                    <h4 style="text-align: center" class="text-danger py-2"><i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                                    <p>Esse é seu primeiro login, por questões de segurança nos próximos passos você criará um login e uma senha.</p>
                                    <ul style="margin-left: 15px">
                                        <li>Seu usuário tem acesso ao sistema, então tome alguns cuidados!</li>
                                        <li>Utilize uma senha segura, com caracteres especiais, letras maiúsculas, minúsculas e números.</li>
                                        <li>Jamais divulgue sua senha, cada usuário deve utilizar sua conta para que seja possível identificar o responsável por cada mudança.</li>
                                    </ul>
                                </div>
                                <div class="card-footer text-center">
                                    <button type="button" class="btn btn-secondary next btn-sm">
                                        <i class="fas fa-arrow-circle-right"></i> Próximo
                                    </button>
                                </div>
                            </div>
                            <div class="card card-step col-12 px-0">
                                <div class="card-header">
                                    Login
                                </div>
                                <div class="card-body card-block">
                                    <div class="row form-group">
                                        <div class="col-12">
                                            <p>Para acessar o sistema, você utilizará a sintaxe <b><span class="login">login</span>@<?=$organizacao_fk ?></b></p>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <label for="login-input" class=" form-control-label"><strong>Login</strong></label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="text" id="login-input" name="acesso_login" placeholder="Login" class="form-control" disabled="true" value="<?= $superusuario_login ?>">
                                            <small class="help-block form-text">Utilize este login para acessar o sistema</small>
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
                                    Senha
                                </div>
                                <div class="card-body card-block">
                                        <div class="row form-group">
                                            <div class="col-12 col-md-2">
                                                <label for="senha-input" class=" form-control-label"><strong>Senha</strong></label>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <input type="password" id="senha-input" name="acesso_senha" placeholder="senha" class="form-control" required="true" minlength="8">
                                                <small class="help-block form-text">Crie uma senha segura (mínimo 8 caracteres)</small>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-12 col-md-2">
                                                <label for="confirme-senha-input" class=" form-control-label"><strong>Repita sua senha</strong></label>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <input type="password" id="confirme-senha-input" name="confirme-senha" placeholder="Confirme a senha" class="form-control" required="true" minlength="8">
                                                <small class="help-block form-text">Repita a senha acima</small>
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
                                    Confirmar
                                </div>
                                <div class="card-body card-block">
                                    <div class="row form-group" style="text-align: center !important;">
                                        <p>Tudo pronto para criar seu acesso!</p><br>
                                        <p>Login: <span class="login"></span><?= $superusuario_login ?></p>
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <button type="button" class="btn btn-secondary previous btn-sm">
                                        <i class="fas fa-arrow-circle-left"></i> Anterior
                                    </button>
                                    <button type="button" id="submit" class="btn btn-primary submit btn-sm">
                                        <i class="fa fa-dot-circle-o"></i> Enviar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!--Importando o Recaptcha para verificação de usuário real-->
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo RECAPTCHA_SITE_KEY ?>"></script>
