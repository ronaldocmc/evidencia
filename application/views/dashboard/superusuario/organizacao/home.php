    <!-- MAIN CONTENT-->
    <style type="text/css">
        ul.ui-autocomplete {
            z-index: 1100;
        }
    </style>

    </script><link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="overview-wrap">
                            <h2 class="title-1">gerenciamento de Filiais</h2>
                            <button class="au-btn au-btn-icon au-btn--blue btn_novo reset_multistep new d-none" data-toggle="modal" data-title="Nova filial" data-contentid="save"
                            data-target="#modal">
                                <i class="zmdi zmdi-plus"></i>nova filial
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row py-5">
                    <div class="col-lg-12">
                        <div class="au-card d-flex flex-column">
                            <h2 class="title-1 m-b-25">filiais</h2>
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
                                <table  id="organizacoes" class="table table-striped table-datatable">
                                    <thead>
                                        <tr>
                                            <th>Domínio</th>
                                            <th>Nome</th>
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
                            <p>Copyright © 2018 Colorlib. All rights reserved. Template by <a href="https://colorlib.com">Colorlib</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
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
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <form class="msform">
                        <!-- progressbar -->
                        <ul class="progressbar">
                            <li class="active">Identificação da Organização</li>
                            <li>Localização</li>
                            <li>Área de Atuação</li>
                            <li>Identificação</li>
                        </ul>
                        <!-- fieldsets -->
                        <div class="card card-step col-12 px-0">
                            <div class="card-header">
                                Identificação da Organização
                            </div>
                            <div class="card-body card-block">
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="organizacao_pk" class=" form-control-label"><strong>Domínio*</strong></label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="text" id="organizacao_pk" name="organizacao_pk" placeholder="Nome Reduzido" class="form-control" required="true" maxlength="10" minlength="3">
                                        <small class="form-text text-muted">Por favor, informe o nome reduzido que represente a organização. Exemplo: prudenco</small>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="organizacao_nome" class=" form-control-label"><strong>Nome*</strong></label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="text" id="organizacao_nome" name="organizacao_nome" placeholder="Nome da organização" class="form-control" required="true" maxlength="60" minlength="3">
                                        <small class="form-text text-muted">Por favor, informe o nome completo da organização</small>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12 col-md-2">
                                        <label for="organizacao_cnpj" class=" form-control-label"><strong>CNPJ*</strong></label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="text" id="organizacao_cnpj" name="organizacao_cnpj" placeholder="CNPJ" class="form-control cnpj-input" required="true" minlength="18" maxlength="18">
                                        <small class="form-text text-muted">Por favor, informe o CNPJ da organização</small>
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
                                Localização
                            </div>
                            <div class="card-body card-block">
                                <div class="row form-group">
                                    <div class="col-12 col-md-2">
                                        <label for="municipio_pk" class=" form-control-label"><strong>Cidade*</strong></label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <select class="form-control" id="municipio_pk" name="municipio_pk">
                                            
                                        </select>
                                        <small class="help-block form-text">Por favor, informe a cidade</small>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12 col-md-2">
                                        <label for="localizacao_rua" class=" form-control-label"><strong>Logradouro*</strong></label>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <input type="text" id="localizacao_rua" name="localizacao_rua"  class="form-control input-dropdown">
                                        <small class="help-block form-text helper-dropdown">Por favor, informe o logradouro da organização</small>
                                    </div>
                                    <div class="col-12 col-md-1">
                                        <label for="localizacao_num" class=" form-control-label"><strong>Nº*</strong></label>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <input type="number" id="localizacao_num" name="localizacao_num" placeholder="Nº" class="form-control numero-input" min="0" required="true">
                                        <small class="form-text text-muted"></small>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12 col-md-2">
                                        <label for="localizacao_bairro" class=" form-control-label"><strong>Bairro*</strong><br></label>
                                    </div> 
                                    <div class="col-12 col-md-10">
                                        <input type="text" id="localizacao_bairro" name="localizacao_bairro"  class="form-control">
                                        <small class="help-block form-text">Por favor, informe o bairro da organização</small>
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
                                Cidades de Atuação
                            </div>
                            <div class="card-body card-block">
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <label for="new_city">Digite a Cidade</label><br>
                                        <input type="text" id="new_city" class="form-control col-md-8" style="display:inline-block">
                                        <button class="btn btn-primary" id="add_city">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>  

                                    <div class="col-xs-6" id="cities">
                                        <p><strong>Cidades Adicionadas</strong></p>
                                        
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
                                        <label for="senha" class=" form-control-label"><strong>Senha*</strong></label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="password" id="senha" name="senha" placeholder="Senha Pessoal" class="form-control" autocomplete="new-password"   minlength="8" required="true">
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
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-primary btn-sm submit" id="pula-para-confirmacao">
                <i class="fa fa-dot-circle-o"></i> Salvar
            </button>
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Fechar</button>
        </div>
    </div>

    <!-- MODAL DESATIVA ORGANIZAÇÕES -->
    <div id="deactivate" class="d-none">
        <div class="modal-body">
            <form>
                <div class="form-group">
                    <h4 style="text-align: center" class="text-danger"><i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                    <p>Ao desativar a organização, as seguintes ações também serão feitas:</p>
                    <ul style="margin-left: 15px">
                        <li>Nenhum funcionário da organização poderá acessar o sistema</li>
                        <li>Nenhuma ação relativa a organização poderá ser realizada</li>
                    </ul>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control press_enter" autocomplete="false" name="pass-modal-desativar" placeholder="Confirme sua senha" required="required" id="pass-modal-deactivate">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-confirmar-senha action_deactivate"><i class="fa fa-dot-circle-o"></i> Desativar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL REATIVA ORGANIZACOES -->
    <div id="activate" class="d-none">
        <div class="modal-body">
            <form>
                <div class="form-group">
                    <h4 style="text-align: center" class="text-danger"><i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                    <p>Ao reativar a organização, as seguintes ações também serão feitas:</p>
                    <ul style="margin-left: 15px">
                        <li>Os funcionários da organização poderão acessar novamente o sistema</li>
                        <li>As ações relativas a organização poderão ser realizada</li>
                    </ul>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control press_enter" autocomplete="false" name="pass-modal-reativar" placeholder="Confirme sua senha" required="required" id="pass-modal-activate" minlength="8">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-confirmar-senha action_activate"><i class="fa fa-dot-circle-o"></i> Reativar</button>
                </div>
            </form>
        </div>
    </div>

<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->