<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">gerenciamento de funcionarios
                        </h2>
                        <button id="btn_new" class="au-btn au-btn-icon au-btn--blue btn_novo reset_multistep new d-none" data-toggle="modal"
                            data-title="Novo Serviço" data-contentid="save" data-target="#modal">
                            <i class="zmdi zmdi-plus"></i>novo funcionario</button>
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
                                        <p>Bem-vindo a área de Gerenciamento de Funcionários!</p><br>
                                        <p> Aqui você poderá realizar algumas operações para controlar os funcionários
                                            da sua organização!</p><br>
                                        <p>Nesta área é possível registrar dados dos funcionários, como dados pessoais e
                                            dados departamentais! É importante ressaltar que alguns dados são
                                            obrigatórios e estão indicados com um asterisco <strong>(*)</strong>. <p>
                                                Aqui, gerenciar os funcionários conforme seu departamento e função
                                                dentro da organização, torna-se uma tarefa prática e segura!</p>
                                            <br>
                                            <p><strong>Qualquer dúvida entre em contato com o suporte na sua
                                                    organização!</p></strong>
                                    </div>
                                    <div class="col-md-6 user-guide">
                                        <p><b>Operações permitidas:</b></p>
                                        <div class="col-md-12 functions-page">
                                            <div class="row">
                                                <div class="col-md-2 icon-guide">
                                                    <button type="button" disabled="true"
                                                        class="btn btn-sm btn-primary reset_multistep" title="Editar">
                                                        <div class="d-none d-block">
                                                            <i class="fas fa-plus fa-fw"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                                <div class="col-md-10 text-guide">Inserir um novo funcionário</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 icon-guide">
                                                    <button type="button" disabled="true"
                                                        class="btn btn-sm btn-primary reset_multistep" title="Editar">
                                                        <div class="d-none d-block">
                                                            <i class="fas fa-edit fa-fw"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                                <div class="col-md-10 text-guide">Editar dados do funcionário existente
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 icon-guide">
                                                    <button type="button" class="btn btn-sm btn-danger" disabled="true"
                                                        title="Desativar">
                                                        <div class="d-none d-block">
                                                            <i class="fas fa-times fa-fw"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                                <div class="col-md-10 text-guide">Desativar um funcionário
                                                    afastado/inativo</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 icon-guide">
                                                    <button type="button" class="btn btn-sm btn-success" disabled="true"
                                                        title="Reativar">
                                                        <div class="d-none d-block">
                                                            <i class="fas fa-power-off fa-fw"></i>
                                                        </div>
                                                    </button>
                                                </div>
                                                <div class="col-md-10 text-guide">Ativar um funcionário novamente</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12>">
                                                    <br>
                                                    <p><strong>Atenção:</strong> Após desativar um funcionário ele não
                                                        possuirá mais acesso a nenhum módulo do sistema! </p></strong>
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
            </div>
            <div class="row py-2">
                <div class="col-lg-12">
                    <div class="au-card d-flex flex-column">

                        <h2 class="title-1 m-b-25">
                            <i style="cursor: pointer; color: gray" class="fas fa-info pull-right"
                                data-toggle="collapse" href="#collapseHelp" role="button" aria-expanded="false"
                                aria-controls="collapseHelp"></i>
                            funcionarios</h2>


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
                            <table id="funcionarios" class="table table-striped table-datatable">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Login</th>
                                        <th>Função</th>
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
                        <li class="active">Informações Pessoais</li>
                        <li>Acesso</li>
                        <li>Profissional</li>
                        <li>Foto</li>

                        <li class="d-none superusuario">Identificação</li>
                    </ul>

                    <!-- STEP 1 -->

                    <div class="card card-step col-12 px-0">
                                <div class="card-header">
                                    Informações Pessoais
                                </div>
                                <div class="card-body card-block">
                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="funcionario_nome" class=" form-control-label">
                                                <strong>Nome*</strong>
                                            </label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="text" id="funcionario_nome" name="pessoa_nome"
                                                placeholder="Nome Completo" class="form-control nome-input" required
                                                maxlength="50" minlength="5" required>
                                            <small class="form-text text-muted">Por favor, informe o nome completo do
                                                funcionário</small>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-12 col-md-2">
                                            <label for="funcionario_cpf" class=" form-control-label">
                                                <strong>CPF*</strong>
                                            </label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="text" id="funcionario_cpf" name="pessoa_cpf" placeholder="CPF"
                                                class="form-control cpf-input" required>
                                            <small class="form-text text-muted">Por favor, informe o CPF do
                                                funcionário</small>

                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <button type="button" class="btn btn-secondary next btn-sm">
                                        <i class="fas fa-arrow-circle-right"></i> Próximo
                                    </button>
                                </div>
                            </div>

                            <!-- STEP 2 -->

                            <div class="card card-step col-12 px-0">
                                <div class="card-header">
                                    Acesso
                                </div>
                                <div class="card-body card-block">
                                    <div class="row form-group">
                                        <div class="col-12 col-md-2">
                                            <label for="funcionario_login" class=" form-control-label">
                                                <strong>E-mail*</strong>
                                            </label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="email" id="funcionario_login" name="contato_email"
                                                placeholder="Email" class="form-control email-input" required="true">
                                            <small class="help-block form-text">Por favor, informe o login do
                                                funcionário</small>
                                        </div>
                                    </div>
                                    <div class="row form-group" id="div-senha">
                                        <!-- <div id="div-senha"> -->
                                            <div class="col-12 col-md-2">
                                                <label for="email-input" class=" form-control-label">
                                                    <strong>Senha</strong>
                                                </label>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <input type="password" id="funcionario_senha" name="funcionario_senha"
                                                    placeholder="Senha" class="form-control">
                                                <small class="help-block form-text">Por favor, informe a senha para o
                                                    funcionário</small>
                                            </div>
                                            <div class="col-12 col-md-2">
                                                <label for="email-input" class=" form-control-label">
                                                    <strong>Confirme</strong>
                                                </label>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <input type="password" id="funcionario_confirmar_senha"
                                                    name="funcionario_confirmar_senha" placeholder="Confirmar senha"
                                                    class="form-control">
                                                <small class="help-block form-text">Por favor, confirme a senha</small>
                                            </div>
                                        <!-- </div> -->
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

                            <!-- STEP 3 -->

                            <div class="card card-step col-12 px-0">
                                <div class="card-header">
                                    Profissional
                                </div>
                                <div class="card-body card-block">
                                    <div class="row form-group">
                                        <div class="col-12 col-md-2">
                                            <label for="funcao-input" class=" form-control-label">
                                                <strong>Função*</strong>
                                            </label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <select class="form-control" required="true" id="funcao_fk"></select>
                                            <small class="help-block form-text">Por favor, informe a função do
                                                funcionário</small>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-12 col-md-2">
                                            <label for="departamento-input"
                                                class=" form-control-label">Departamento</label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <select class="form-control" id="departamento_fk">
                                                <option value="">Nenhum Departamento</option>
                                            </select>
                                            <small class="help-block form-text">Por favor, informe o departamento do
                                                funcionário</small>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-12 col-md-2">
                                            <label for="setor_input" class=" form-control-label">Setor</label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <select multiple class="form-control" id="setor_fk"></select>
                                            <small class="help-block form-text">Por favor, informe o setor do
                                                funcionário, caso ele seja funcionário de campo <br>
                                                <strong>
                                                    Segure CTRL para selecionar mais de um setor</strong>
                                            </small>
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


                            <!-- STEP 4 -->

                            <div class="card card-step col-12 px-0">
                                <div class="card-header">
                                    Foto
                                </div>
                                <div class="card-body card-block">
                                    <div class="row form-group">
                                        <div class="file-upload col-12">
                                        <div style="text-align: center">
                                                <img width="300px" src="" id="show-img-funcionario" alt="">
                                            <br>
                                            </div>
                                        <button class="btn btn-secondary col-12" type="button"
                                                onclick="$('.file-upload-input').trigger( 'click' )">Carregar
                                                Foto</button>

                                            <div class="image-upload-wrap">
                                                <input class="file-upload-input" name="img" type='file'
                                                    onchange="readURL(this);" accept="image/*" />
                                                <div class="drag-text">
                                                    <h3>Arraste e solte uma foto aqui ou clique em Carregar Foto</h3>
                                                </div>
                                            </div>
                                            <div class="file-upload-content">
                                                <img id="img-input" class="file-upload-image" src="#"
                                                    alt="your image" />
                                                <div class="col-12">
                                                    <button type="button" onclick="myControl.remove_image()"
                                                        class="btn btn-danger">Remover Imagem</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <button type="button" class="btn btn-secondary previous btn-sm">
                                        <i class="fas fa-arrow-circle-left"></i> Anterior

                                    <button type="button" class="btn btn-secondary next btn-sm d-none superusuario">
                                        <i class="fas fa-arrow-circle-right"></i> Próximo
                                    </button>

                                    <button type="button" class="btn btn-primary submit btn-sm not_superusuario" id="botao-finalizar">
                                        <i class="fa fa-dot-circle-o"></i> Finalizar
                                    </button>
                                </div>
                            </div>

                    <!-- STEP 5 -->

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
            <div id="dependences" style="margin: 20px 0" class="container"></div>

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

<div id="password" class="d-none">
    <div class="modal-body">
        <div class="form-group">
            <h4 style="text-align: center" class="text-danger">
                <i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO
            </h4>
            <p style="text-align: center">A senha deve possuir 8 ou mais caracteres!</p>

            <label>Digite a nova senha</label>
            <input id="p-senha" type="password" class="form-control">

            <label>Confirme a nova senha</label>
            <input id="p-confirmar-senha" type="password" class="form-control">

            <p id="p-msg" style="color: red; text-align: center"></p>
        </div>

        <div class="form-group d-none superusuario">
            <input type="password" class="form-control" autocomplete="false"
                placeholder="Confirmar senha de superusuario" required="required" id="pass-modal-desativar"
                pattern="{8,}">
        </div>

        <div class="form-group">
            <button type="button" class="btn btn-primary action_change_password load" id="btn_password" name="post"><i
                    class="fa fa-dot-circle-o"></i> Alterar</button>
        </div>
    </div>
</div>


<script type="text/javascript">
    const is_superusuario = <?php echo $this->session->user['is_superusuario'] === true ? 1 : 0; ?>;
</script>