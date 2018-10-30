<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">Gerenciamento de Funcionário</h2>
                        <button class="au-btn au-btn-icon au-btn--blue reset_multistep new btn_novo" data-toggle="modal" data-target="#ce_funcionario">
                            <i class="zmdi zmdi-plus"></i>novo funcionário</button>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="collapse" id="collapseHelp">
                                <div class="card card-body">
                                    <p>Esta é a área para gerenciamento dos funcionários.</p>
                                    <p>Aqui é possível registrar o funcionário da empresa, informando seus dados pessoais e atuação na empresa.</p>
                                    <p>Os campos obrigatórios estão marcados com negrito e asterisco (*).</p>
                                    <p>Lembrando que ao desativar um funcionário ele não poderá mais utilizar o sistema.</p>
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
                                <i style="cursor: pointer; color: gray" class="fas fa-info pull-right" data-toggle="collapse" href="#collapseHelp" role="button" aria-expanded="false" aria-controls="collapseHelp"></i>
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
                                            <th>E-mail</th>
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
                                                       <?=$f->pessoa_nome?>
                                                   </td>
                                                   <td>
                                                       <?=$f->contato_email?>
                                                   </td>
                                                   <td>
                                                       <?=$f->funcao_nome?>
                                                   </td>
                                                   <td>
                                                       <div class="btn-group">

                                                           <?php if ($f->funcionario_status == 1): ?>
                                                               <button class="btn btn-sm btn-primary reset_multistep btn-editar btn-attr-pessoa_pk" value="<?=$key?>" data-toggle="modal" data-target="#ce_funcionario" title="Editar">
                                                                   <div class="d-none d-sm-block">
                                                                       <i class="fas fa-edit fa-fw"></i>
                                                                   </div>
                                                               </button>
                                                               <button class="btn btn-sm btn-danger btn-desativar btn-attr-pessoa_pk" value="<?=$key?>" data-toggle="modal" data-target="#d_funcionario" title="Desativar">
                                                                   <div class="d-none d-sm-block">
                                                                       <i class="fas fa-times fa-fw"></i>
                                                                   </div>
                                                               </button>
                                                               <?php else: ?>
                                                                <button class="btn btn-sm btn-success btn-reativar btn-attr-pessoa_pk" value="<?=$key?>" data-toggle="modal" data-target="#a_funcionario" title="Reativar">
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
                                            <li>Endereço</li>
                                            <li>Contato</li>
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
                                                        <input type="text" id="nome-input" name="pessoa_nome" placeholder="Nome Completo" class="form-control nome-input" required
                                                        maxlength="50" minlength="5" required>
                                                        <small class="form-text text-muted">Por favor, informe o nome completo do funcionário</small>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <div class="col-12 col-md-2">
                                                        <label for="cpf-input" class=" form-control-label">
                                                            <strong>CPF*</strong>
                                                        </label>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <input type="text" id="cpf-input" name="pessoa_cpf" placeholder="CPF" class="form-control cpf-input" required>
                                                        <small class="form-text text-muted">Por favor, informe o CPF do funcionário</small>
                                                            
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
                                                Endereço
                                            </div>
                                            <div class="card-body card-block">
                                                <div class="row form-group">
                                                    <div class="col-12 col-md-2">
                                                        <label for="uf-input" class="form-control-label">
                                                            Estado
                                                        </label>
                                                    </div>
                                                    <div class="col-12 col-md-2">
                                                        <select class="form-control loading" id="uf-input" name="estado_pk" required="true">
                                                        </select>
                                                        <small class="form-text text-muted"></small>
                                                    </div>
                                                    <div class="col-12 col-md-2">
                                                        <label for="cidade-input" class=" form-control-label">
                                                            Cidade
                                                        </label>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <select class="form-control loading" id="cidade-input" name="municipio_pk" required="true">
                                                        </select>

                                                        <small class="help-block form-text">Por favor, informe a cidade</small>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <div class="col-12 col-md-2">
                                                        <label for="logradouro-input" class=" form-control-label">
                                                            Logradouro
                                                        </label>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <input type="hidden" name="logradouro_nome" id="logradouro_pk">
                                                        <div class="dropdown" id="drop">
                                                            <input type="text" id="logradouro-input" name="logradouro_nome" class="form-control input-dropdown" autocomplete="off" placeholder="Logradouro"
                                                            data-src='["<?php echo base_url('localizacao/logradouros'); ?>","https://viacep.com.br/ws"]' data-index='["logradouro_pk","logradouro"]' data-value='["logradouro_nome","logradouro"]'
                                                            data-params='[[["this","logradouro_nome","val"],["cidade-input","municipio_pk","val"]],[["uf-input",null,"text"],["cidade-input",null,"text"],["this",null,"val"],["json",null,"param"]]]'
                                                            data-action='["post","get"]' data-arrayret='    ["data",null]'>
                                                            <small class="help-block form-text helper-dropdown">Por favor, informe o logradouro do funcionário</small>
                                                            <ul class="dropdown-menu" data-return="#logradouro_pk" data-next="#numero-input">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-1">
                                                        <label for="numero-input" class=" form-control-label">
                                                            Nº
                                                        </label>
                                                    </div>
                                                    <div class="col-12 col-md-3">
                                                        <input type="number" id="numero-input" name="local_num" placeholder="Nº" class="form-control numero-input" min="0">
                                                        <small class="form-text text-muted"></small>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <div class="col-12 col-md-2">
                                                        <label for="complemento-input" class=" form-control-label">
                                                            Complemento
                                                        </label>
                                                    </div>
                                                    <div class="col-12 col-md-5">
                                                        <input type="text" id="complemento-input" name="local_complemento" placeholder="Complemento" class="form-control" maxlength="30">
                                                        <small class="help-block form-text">Por favor, informe o complemento</small>
                                                    </div>
                                                    <div class="col-12 col-md-1">
                                                        <label for="bairro-input" class=" form-control-label">
                                                            Bairro
                                                            <br>
                                                        </label>
                                                    </div>
                                                    <div class="col-12 col-md-4">
                                                        <input type="hidden" name="bairro_nome" id="bairro_pk">
                                                        <div class="dropdown" id="drop">
                                                            <input type="text" id="bairro-input" name="bairro" class="form-control input-dropdown" placeholder="Bairro" autocomplete="off"
                                                            data-src='["<?php echo base_url('localizacao/bairros'); ?>","https://viacep.com.br/ws"]' data-index='["bairro_pk","bairro"]' data-value='["bairro_nome","bairro"]'
                                                            data-params='[[["cidade-input",null,"val"]],[["uf-input",null,"text"],["cidade-input",null,"text"],["logradouro-input",null,"val"],["json",null,"param"]]]'
                                                            data-action='["get","get"]' data-arrayret='["data",null]'>
                                                            <small class="help-block form-text helper-dropdown">Por favor, informe o bairro do funcionário</small>
                                                            <ul class="dropdown-menu" data-return="#bairro_pk" data-next="#bairro-input">
                                                            </ul>
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
                                                Contato
                                            </div>
                                            <div class="card-body card-block">
                                                <div class="row form-group">
                                                    <div class="col-12 col-md-2">
                                                        <label for="email-input" class=" form-control-label">
                                                            <strong>E-mail*</strong>
                                                        </label>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <input type="email" id="email-input" name="contato_email" placeholder="Email" class="form-control email-input" required="true">
                                                        <small class="help-block form-text">Por favor, informe o email do funcionário</small>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <div class="col-12 col-md-2">
                                                        <label for="telefone-input" class=" form-control-label">Telefone</label>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <input type="text" id="telefone-input" name="contato_tel" placeholder="Telefone" class="form-control telefone-input">
                                                        <small class="help-block form-text">Por favor, informe o telefone do funcionário</small>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <div class="col-12 col-md-2">
                                                        <label for="celular-input" class=" form-control-label">Celular</label>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <input type="text" id="celular-input" name="contato_cel" placeholder="Celular" class="form-control celular-input">
                                                        <small class="help-block form-text">Por favor, informe o celular do funcionário</small>
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
                                                        <small class="help-block form-text">Por favor, informe a função do funcionário</small>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <div class="col-12 col-md-2">
                                                        <label for="departamento-input" class=" form-control-label">Departamento</label>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                <select class="form-control" id="departamento-input">
                                                    <option value="">Nenhum Departamento</option>
                                                <?php 

                                                    foreach($departamentos as $key => $value):

                                                        echo '<option value="'.$key.'">'.$value.'</option>';
                                                    endforeach
                                                ?>
                                                </select>
                                                        <small class="help-block form-text">Por favor, informe o departamento do funcionário</small>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <div class="col-12 col-md-2">
                                                        <label for="setor_input" class=" form-control-label">Setor</label>
                                                    </div>
                                                    <div class="col-12 col-md-10">
                                                        <?php if ($setores != null): ?>
                                                        <select multiple class="form-control" id="setor-input">
                                                            <?php foreach ($setores as $k => $s): ?>
                                                                <option value="<?=$k?>">
                                                                    <?=$s?>
                                                                </option>
                                                            <?php endforeach ?>
                                                        </select>
                                                        <?php endif ?>
                                                        <small class="help-block form-text">Por favor, informe o setor do funcionário, caso ele seja funcionário de campo <br>
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
                                                        <button class="btn btn-secondary col-12" type="button" onclick="$('.file-upload-input').trigger( 'click' )">Carregar Foto</button>
                                                        <div class="image-upload-wrap">
                                                            <input class="file-upload-input" name="img" type='file' onchange="readURL(this);" accept="image/*" />
                                                            <div class="drag-text">
                                                                <h3>Arraste e solte uma foto aqui ou clique em Carregar Foto</h3>
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
                                                                <input type="password" id="pass-modal-edit" name="senha_su" placeholder="Confirme sua senha" class="form-control" required
                                                                minlength="8" autocomplete="new-password">
                                                                <small class="form-text text-muted">Por favor, informe novamente sua senha para confirmar a operação</small>
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
                                <button type="button" class="btn btn-primary submit btn-sm" id="pula-para-confirmacao"><i class="fa fa-dot-circle-o"></i> Salvar</button>
                                
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
                                        <i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                                        <p>Ao desativar o funcionário, o mesmo:</p>
                                        <ul style="margin-left: 15px">
                                            <li>Perderá acesso ao sistema</li>
                                            <li>Não poderá exercer suas atividades</li>
                                        </ul>
                                    </div>
                                    <?php if ($this->session->user['is_superusuario']): ?>
                                        <div class="form-group">
                                            <input type="password" class="form-control" autocomplete="false" placeholder="Confirme sua senha" required="required" id="pass-modal-desativar"
                                            pattern="{8,}">
                                        </div>
                                    <?php endif;?>
                                    <div class="form-group">
                                        <button type="button" class="btn btn-confirmar-senha" id="btn-deactivate" name="post"><i class="fa fa-dot-circle-o"></i> Desativar</button>
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
                                            <i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                                            <p>Ao ativar o funcionário, o mesmo:</p>
                                            <ul style="margin-left: 15px">
                                                <li>Recuperará o acesso ao sistema</li>
                                            </ul>
                                        </div>
                                        <?php if ($this->session->user['is_superusuario']): ?>
                                            <div class="form-group">
                                                <input type="password" class="form-control" autocomplete="false" placeholder="Confirme sua senha" required="required" id="pass-modal-ativar">
                                            </div>
                                        <?php endif;?>
                                        <div class="form-group">
                                            <button type="button" class="btn btn-confirmar-senha" id="btn-activate" name="post"><i class="fa fa-dot-circle-o"></i> Ativar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            var funcionarios = <?php echo json_encode($funcionarios !== false ? $funcionarios : []) ?>;
                            var funcoes = <?php echo json_encode($funcoes !== false ? $funcoes : []) ?>;
                        </script>