    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="overview-wrap">
                            <h2 class="title-1">gerenciamento de organizações </h2>
                            <button class="au-btn au-btn-icon au-btn--blue reset_multistep btn_nova new" data-toggle="modal" data-target="#ce_organizacao" >
                                <i class="zmdi zmdi-plus"></i>nova organização</button>
                            </div>
                            <input type="hidden" id="opcao-editar" name="opcao-editar" class="form-control" value="false">
                        </div>
                    </div>
                    <div class="row py-5">
                        <div class="col-lg-12">
                            <div class="au-card d-flex flex-column">
                                <h2 class="title-1 m-b-25">organizações</h2>
                                <div class="">
                                    <h5>Filtrar por</h5><br>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="filter-ativo">Mostrar</label>
                                            <select name="filter-ativo" id="filter-ativo" class="form-control">
                                                <option value="todos">Todos</option>
                                                <option value="ativos">Apenas ativos</option>
                                                <option value="desativados">Apenas desativados</option>
                                            </select><br>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive table--no-card m-b-40">
                                    <table  id="organizacoes" class="table table-striped table-datatable">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Domínio</th>
                                                <th>Endereço</th>
                                                <th>Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($organizacoes !== false): ?>
                                                <?php foreach ($organizacoes as $key => $organizacao): ?>
                                                    <tr>
                                                        <td>
                                                            <?=$organizacao->organizacao_nome?>
                                                        </td>
                                                        <td>
                                                            <?=$organizacao->organizacao_pk?>
                                                        </td>
                                                        <td>
                                                        <?=ucwords(mb_strtolower($organizacao->localizacao_rua, 'UTF-8')) . ", " . $organizacao->localizacao_num . " - " . ucwords(mb_strtolower($organizacao->localizacao_bairro, 'UTF-8')) . " - " . $organizacao->municipio_nome . "/SP"?>
                                                        </td>
                                                        <td>
                                                            <?php if ($organizacao->ativo): ?>
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar" data-toggle="modal" value="<?=$key?>" data-target="#ce_organizacao">
                                                                        <div class="d-none d-sm-block">
                                                                            Editar
                                                                        </div>
                                                                        <div class="d-block d-sm-none">
                                                                            <i class="fas fa-edit fa-fw"></i>
                                                                        </div>
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" value="<?=$key?>" data-target="#d-organizacao">
                                                                        <div class="d-none d-sm-block">
                                                                            Desativar
                                                                        </div>
                                                                        <div class="d-block d-sm-none">
                                                                            <i class="fas fa-times fa-fw"></i>
                                                                        </div>
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm btn-success btn-acessar" data-toggle="modal" value="<?=$key?>" data-target="#a-organizacao">
                                                                        <div class="d-none d-sm-block">
                                                                            Acessar
                                                                        </div>
                                                                        <div class="d-block d-sm-none">
                                                                            <i class="fas fa-sign-in-alt fa-fw"></i>
                                                                        </div>
                                                                    </button>
                                                                </div>
                                                                <?php else: ?>
                                                                    <div class="btn-group">
                                                                        <button type="button" class="btn btn-sm btn-success btn_reativar" value="<?=$key?>" data-toggle="modal" data-target="#r-organizacao">
                                                                            <div class="d-none d-sm-block">
                                                                                Reativar
                                                                            </div>
                                                                            <div class="d-block d-sm-none">
                                                                                <i class="fas fa-check-circle fa-fw"></i>
                                                                            </div>
                                                                        </button>
                                                                    </div>
                                                                <?php endif?>
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


            <!-- MODAL CRIA E ATUALIZA ORGANIZAÇÕES -->
            <div class="modal fade modal-multistep" id="ce_organizacao">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <!-- Modal Header -->
                      <div class="modal-header">
                        <h4 class="modal-title">Nova organização</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="msform">
                                    <input type="hidden" id="organizacao_pk" name="organizacao_pk" class="form-control">
                                    <input type="hidden" id="local_pk" name="local_pk" class="form-control">
                                    <input type="hidden" id="logradouro_fk" name="logradouro_pk" class="form-control">
                                    <!-- progressbar -->
                                    <ul class="progressbar">
                                        <li class="active">Identificação da Organização</li>
                                        <li>Localização</li>
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
                                                    <label for="dominio-input" class=" form-control-label"><strong>Domínio*</strong></label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" id="dominio-input" name="dominio" placeholder="Nome da organização" class="form-control" required="true" maxlength="10" minlength="3">
                                                    <small class="form-text text-muted">Por favor, informe o nome reduzido que represente a organização. Exemplo: prudenco</small>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col col-md-2">
                                                    <label for="nome-input" class=" form-control-label"><strong>Nome*</strong></label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" id="nome-input" name="organizacao_nome" placeholder="Nome da organização" class="form-control" required="true" maxlength="60" minlength="3">
                                                    <small class="form-text text-muted">Por favor, informe o nome completo da organização</small>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-12 col-md-2">
                                                    <label for="cnpj-input" class=" form-control-label"><strong>CNPJ*</strong></label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <input type="text" id="cnpj-input" name="organizacao_cnpj" placeholder="CNPJ" class="form-control cnpj-input" required="true" minlength="18" maxlength="18">
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
                                                <!-- <div class="col-12 col-md-2">
                                                    <label for="uf-input" class="form-control-label"><strong>Estado*</strong></label>
                                                </div>
                                                <div class="col-12 col-md-2">
                                                    <select class="form-control" id="uf-input" name="estado_pk">
                                                    </select>
                                                    <small class="form-text text-muted"></small>
                                                </div> -->
                                                <div class="col-12 col-md-2">
                                                    <label for="cidade-input" class=" form-control-label"><strong>Cidade*</strong></label>
                                                </div>
                                                <div class="col-12 col-md-10">
                                                    <select class="form-control" id="cidade-input" name="municipio_pk">
                                                        <?php foreach ($municipios as $m): ?>
                                                            <option value="<?=$m->municipio_pk?>"><?=$m->municipio_nome?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                    <small class="help-block form-text">Por favor, informe a cidade</small>
                                                </div>
                                                <input type="hidden" name="estado-input" id="estado-input">
                                                <input type="hidden" name="municipio-nome" id="municipio-nome">
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-12 col-md-2">
                                                    <label for="logradouro-input" class=" form-control-label"><strong>Logradouro*</strong></label>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <input type="hidden" name="logradouro_nome" id="logradouro_pk">
                                                    <div class="dropdown" id="drop">
                                                        <input type="text" id="logradouro-input" name="logradouro_nome"  class="form-control input-dropdown">
                                                        <small class="help-block form-text helper-dropdown">Por favor, informe o logradouro da organização</small>
                                                        <ul class="dropdown-menu" data-return = "#logradouro_pk" data-next="#numero-input">
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-1">
                                                    <label for="numero-input" class=" form-control-label"><strong>Nº*</strong></label>
                                                </div>
                                                <div class="col-12 col-md-3">
                                                    <input type="number" id="numero-input" name="local_num" placeholder="Nº" class="form-control numero-input" min="0" required="true">
                                                    <small class="form-text text-muted"></small>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <!-- <div class="col-12 col-md-2">
                                                    <label for="complemento-input" class=" form-control-label">Complemento</label>
                                                </div>
                                                <div class="col-12 col-md-5">
                                                    <input type="text" id="complemento-input" name="local_complemento" placeholder="Complemento" class="form-control" maxlength="30">
                                                    <small class="help-block form-text">Por favor, informe o complemento</small>
                                                </div> -->
                                                <div class="col-12 col-md-2">
                                                    <label for="bairro-input" class=" form-control-label"><strong>Bairro*</strong><br></label>
                                                </div> 
                                                <div class="col-12 col-md-10">
                                                   <input type="hidden" name="bairro_nome" id="bairro_pk">
                                                   <div class="dropdown" id="drop">
                                                    <input type="text" id="bairro-input" name="bairro"  class="form-control input-dropdown">
                                                    <small class="help-block form-text helper-dropdown">Por favor, informe o bairro da organização</small>
                                                    <ul class="dropdown-menu" data-return = "#bairro_pk" data-next="#bairro-input">
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
                                        Identificação
                                    </div>
                                    <div class="card-body card-block">
                                        <div class="row form-group">
                                            <div class="col col-md-2">
                                                <label for="senha-input" class=" form-control-label"><strong>Senha*</strong></label>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <input type="password" id="senha-input" name="senha" placeholder="Senha Pessoal" class="form-control press_enter" autocomplete="new-password"   minlength="8" required="true">
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
                    <button type="button" class="btn btn-primary btn-sm" id="pula-para-confirmacao">
                        <i class="fa fa-dot-circle-o"></i> Salvar
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DESATIVA ORGANIZAÇÕES -->
    <div class="modal fade" id="d-organizacao" >
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h4 class="modal-title">Desativar Organização</h4>
                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
             </div>
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
                     <input type="password" class="form-control press_enter" autocomplete="false" name="pass-modal-desativar" placeholder="Confirme sua senha" required="required" id="pass-modal-desativar" pattern="{8,}">
                 </div>
                 <div class="form-group">
                     <button type="button" class="btn btn-confirmar-senha" id="btn-desativar" name="post" value=""><i class="fa fa-dot-circle-o"></i> Desativar</button>
                 </div>
             </form>
         </div>
     </div>
 </div>
</div>

<!-- MODAL REATIVA ORGANIZACOES -->
<div class="modal fade" id="r-organizacao" >
 <div class="modal-dialog modal-dialog-centered">
     <div class="modal-content">
         <div class="modal-header">
             <h4 class="modal-title">Reativar Organização</h4>
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
         </div>
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
                 <input type="password" class="form-control press_enter" autocomplete="false" name="pass-modal-reativar" placeholder="Confirme sua senha" required="required" id="pass-modal-reativar" minlength="8">
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
    var organizacoes = <?php echo json_encode($organizacoes !== false ? $organizacoes : []); ?>;
    console.log(organizacoes);

</script>
<!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->