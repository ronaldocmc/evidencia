<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">Gerenciamento de Funcionário</h2>
                        <button class="au-btn au-btn-icon au-btn--blue reset_multistep new btn_novo" data-toggle="modal"
                            data-target="#ce_funcionario">
                            <i class="zmdi zmdi-plus"></i>novo funcionário</button>
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
                    <!-- CAMPO HIDDEN PARA O ID -->
                    <input type="hidden" id="pessoa_pk" name="pessoa_pk" class="form-control">
                    <input type="hidden" id="opcao-editar" name="editar" class="form-control" value="false">
                </div>
            </div>

            <div class="row py-2">
                <div class="col-12">
                    <div class="au-card">
                        <h2 class="title-1 m-b-25">
                            <i style="cursor: pointer; color: gray" class="fas fa-info pull-right"
                                data-toggle="collapse" href="#collapseHelp" role="button" aria-expanded="false"
                                aria-controls="collapseHelp"></i>
                            Funcionários</h2>
                        <div class="">
                            <h5>Filtrar por</h5>
                            <br>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="filter-ativo">Mostrar</label>
                                    <select name="filter-ativo" id="filter-ativo" class="form-control">
                                        <option value="todos">Todos</option>
                                        <option value="ativos">Apenas ativos</option>
                                        <option value="desativados">Apenas desativados</option>
                                    </select>
                                    <br>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive table--no-card m-b-40">
                            <table class="table table-striped table-datatable">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Login</th>
                                        <th>Função</th>
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
if ($funcionarios):

    foreach ($funcionarios as $key => $f): ?>
				                                    <tr>
				                                        <td>
				                                            <?=$f->funcionario_nome?>
				                                        </td>
				                                        <td>
				                                            <?=$f->funcionario_login?>
				                                        </td>
				                                        <td>
				                                            <?=$f->funcao_nome?>
				                                        </td>
				                                        <td>
				                                            <div class="btn-group">

				                                                <?php if ($f->ativo == 1): ?>
				                                                <button
				                                                    class="btn btn-sm btn-primary reset_multistep btn-editar btn-attr-pessoa_pk"
				                                                    value="<?=$key?>" data-toggle="modal" data-target="#ce_funcionario"
				                                                    title="Editar">
				                                                    <div class="d-none d-sm-block">
				                                                        <i class="fas fa-edit fa-fw"></i>
				                                                    </div>
				                                                </button>
				                                                <button class="btn btn-sm btn-danger btn-desativar btn-attr-pessoa_pk"
				                                                    value="<?=$key?>" data-toggle="modal" data-target="#d_funcionario"
				                                                    title="Desativar">
				                                                    <div class="d-none d-sm-block">
				                                                        <i class="fas fa-times fa-fw"></i>
				                                                    </div>
				                                                </button>
				                                                <button class="btn btn-sm btn-info btn-attr-pessoa_pk" value="<?=$key?>"
				                                                    data-toggle="modal" data-target="#p_funcionario"
				                                                    title="Alterar senha">
				                                                    <div class="d-none d-sm-block">
				                                                        <i class="fas fa-lock"></i>
				                                                    </div>
				                                                </button>
				                                                <?php else: ?>
                                                <button class="btn btn-sm btn-success btn-reativar btn-attr-pessoa_pk"
                                                    value="<?=$key?>" data-toggle="modal" data-target="#a_funcionario"
                                                    title="Reativar">
                                                    <div class="d-none d-sm-block">
                                                        <i class="fas fa-power-off fa-fw"></i>
                                                    </div>
                                                </button>
                                                <?php endif;?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach;endif;?>
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

<div class="modal fade modal-multistep" id="ce_funcionario">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="titulo">Editar Funcionário</h4>
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
                                <li>Profissional</li>
                                <li>Foto</li>
                                <?php if ($this->session->user['is_superusuario']): ?>
                                <li>Identificação</li>
                                <?php endif;?>
                            </ul>
                            <!-- fieldsets -->
                            <div class="card card-step col-12 px-0">
                                <div class="card-header">
                                    Informações Pessoais
                                </div>
                                <div class="card-body card-block">
                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="nome-input" class=" form-control-label">
                                                <strong>Nome*</strong>
                                            </label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="text" id="nome-input" name="pessoa_nome"
                                                placeholder="Nome Completo" class="form-control nome-input" required
                                                maxlength="50" minlength="5" required>
                                            <small class="form-text text-muted">Por favor, informe o nome completo do
                                                funcionário</small>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-12 col-md-2">
                                            <label for="cpf-input" class=" form-control-label">
                                                <strong>CPF*</strong>
                                            </label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="text" id="cpf-input" name="pessoa_cpf" placeholder="CPF"
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
                            <div class="card card-step col-12 px-0">
                                <div class="card-header">
                                    Acesso
                                </div>
                                <div class="card-body card-block">
                                    <div class="row form-group">
                                        <div class="col-12 col-md-2">
                                            <label for="email-input" class=" form-control-label">
                                                <strong>E-mail*</strong>
                                            </label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="email" id="email-input" name="contato_email"
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
                                            <?php echo form_dropdown('funcao_fk', $funcoes, null, 'class="form-control" required="true" id="funcao-input"'); ?>
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
                                            <select class="form-control" id="departamento-input">
                                                <option value="">Nenhum Departamento</option>
                                                <?php

foreach ($departamentos as $key => $dep):

    echo '<option value="' . $dep->departamento_pk . '">' . $dep->departamento_nome . '</option>';
endforeach
?>
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
                                            <?php if ($setores != null): ?>
                                            <select multiple class="form-control" id="setor-input">
                                                <?php foreach ($setores as $s): ?>
                                                <option value="<?=$s->setor_pk?>">
                                                    <?=$s->setor_nome?>
                                                </option>
                                                <?php endforeach?>
                                            </select>
                                            <?php endif?>
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
                                                    <button type="button" onclick="remove_image()"
                                                        class="btn btn-danger">Remover Imagem</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <button type="button" class="btn btn-secondary previous btn-sm">
                                        <i class="fas fa-arrow-circle-left"></i> Anterior
                                    </button>
                                    <?php if ($this->session->user['is_superusuario']): ?>
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
                            <?php if ($this->session->user['is_superusuario']): ?>
                            <div class="card card-step col-12 px-0">
                                <div class="card-header">
                                    Identificação
                                </div>
                                <div class="card-body card-block">
                                    <div class="row form-group">
                                        <div class="col col-md-2">
                                            <label for="nome-input" class=" form-control-label">
                                                <strong>Senha</strong>
                                            </label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="password" id="pass-modal-edit" name="senha_su"
                                                placeholder="Confirme sua senha" class="form-control" required
                                                minlength="8" autocomplete="new-password">
                                            <small class="form-text text-muted">Por favor, informe novamente sua senha
                                                para confirmar a operação</small>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-primary submit btn-sm" id="pula-para-confirmacao"><i
                        class="fa fa-dot-circle-o"></i> Salvar</button>

                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Fechar</button>

            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="d_funcionario">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Desativar Funcionário</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <h4 style="text-align: center" class="text-danger">
                        <i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO
                    </h4>
                    <p>Ao desativar o funcionário, o mesmo:</p>
                    <ul style="margin-left: 15px">
                        <li>Perderá acesso ao sistema</li>
                        <li>Não poderá exercer suas atividades</li>
                    </ul>
                </div>
                <?php if ($this->session->user['is_superusuario']): ?>
                <div class="form-group">
                    <input type="password" class="form-control" autocomplete="false" placeholder="Confirme sua senha"
                        required="required" id="pass-modal-desativar" pattern="{8,}">
                </div>
                <?php endif;?>
                <div class="form-group">
                    <button type="button" class="btn btn-confirmar-senha" id="btn-deactivate" name="post"><i
                            class="fa fa-dot-circle-o"></i> Desativar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="a_funcionario">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ativar Funcionário</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <h4 style="text-align: center" class="text-danger">
                        <i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO
                    </h4>
                    <p>Ao ativar o funcionário, o mesmo:</p>
                    <ul style="margin-left: 15px">
                        <li>Recuperará o acesso ao sistema</li>
                    </ul>
                </div>
                <?php if ($this->session->user['is_superusuario']): ?>
                <div class="form-group">
                    <input type="password" class="form-control" autocomplete="false" placeholder="Confirme sua senha"
                        required="required" id="pass-modal-ativar">
                </div>
                <?php endif;?>
                <div class="form-group">
                    <button type="button" class="btn btn-confirmar-senha" id="btn-activate" name="post"><i
                            class="fa fa-dot-circle-o"></i> Ativar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="p_funcionario">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Alterar senha</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
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
                <?php if ($this->session->user['is_superusuario']): ?>
                <div class="form-group">
                    <input type="password" class="form-control" autocomplete="false"
                        placeholder="Confirmar senha de superusuario" required="required" id="pass-modal-desativar"
                        pattern="{8,}">
                </div>
                <?php endif;?>
                <div class="form-group">
                    <button type="button" class="btn btn-primary btn-alterar-senha" id="btn-password" name="post"><i
                            class="fa fa-dot-circle-o"></i> Alterar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var funcionarios = <?php echo json_encode($funcionarios !== false ? $funcionarios : []) ?>;
    var funcoes = <?php echo json_encode($funcoes !== false ? $funcoes : []) ?>;
</script>