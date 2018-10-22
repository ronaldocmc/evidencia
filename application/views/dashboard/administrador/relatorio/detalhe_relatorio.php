    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">


                <div class="row">
                    <div class="col-md-12">
                        <div class="overview-wrap">
                            <h2 class="title-1">Painel de Gerenciamento de Relatórios </h2>
                        </div>


                        <button class="au-btn au-btn-icon au-btn--blue" data-toggle="modal" data-target="#delegar_para_outra_pessoa" >
                            <i class="zmdi zmdi-refresh"></i>Trocar Funcionário</button>
                            <button class="au-btn au-btn-icon btn au-btn--blue pull-right" data-toggle="modal" data-target="#d-relatorio" >
                                <i class="zmdi zmdi-delete"></i>Destruir Relatório</button>
                            </div>
                        </div>

                        <input type="hidden" name="" id="id-relatorio" value="<?= $relatorio->relatorio_pk ?>">

                        

                        <div class="row py-2">
                            <div class="col-lg-12">
                                <div class="au-card d-flex flex-column">
                                    <h2 class="title-1 m-b-25">Relatório do <?= $funcionario->pessoa_nome ?> do dia <?= date("d/m/Y", strtotime($relatorio->data_criacao)) ?></h2>
                                    <div class="card-group">

                                        <div class="card">
                                            <div class="card-header">
                                                <b>Período</b>
                                            </div>
                                            <div class="card-body">
                                                <?= $filtros['data'] ?>
                                            </div>
                                        </div>


                                        <div class="card">
                                            <div class="card-header">
                                                <b>Setores Selecionados</b>
                                            </div>
                                            <div class="card-body">
                                                <?= $filtros['setor'] ?>
                                            </div>
                                        </div> 


                                        <div class="card">
                                            <div class="card-header">
                                                <b>Tipos de Serviços</b>
                                            </div>
                                            <div class="card-body">
                                                <?= $filtros['tipos_servicos'] ?>
                                            </div>
                                        </div>

                                        
                                    </div>

                                    <div>
                                        <br><br>
                                    </div>


                                    <div class="table-responsive table--no-card m-b-40">
                                       <table id="ordens_servico" class="table table-striped table-datatable">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th id="data_brasileira">Data</th>
                                                <th>Prioridade</th>
                                                <th>Endereço</th>
                                                <th>Serviço</th>
                                                <th>Setor</th> 
                                                <th>Opções</th>                

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $count = 0; ?>
                                            <?php if ($ordens_servicos != null): ?>
                                                <?php foreach ($ordens_servicos as $key => $ordem_servico): ?>

                                                    <tr>
                                                        <td>
                                                            <?=$ordem_servico->ordem_servico_cod?>
                                                        </td>
                                                        <td>
                                                            <span style="display: none"><?=$ordem_servico->data_criacao?></span><?= $ordem_servico->data_criacao ?>
                                                        </td>
                                                        <td>
                                                            <?=$ordem_servico->prioridade_nome?>
                                                        </td>
                                                        <td>
                                                            <span style="text-align: justify;">
                                                                <?=$ordem_servico->logradouro_nome . ", " .
                                                                $ordem_servico->local_num . " - " .
                                                                $ordem_servico->bairro_nome?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <?=$ordem_servico->servico_nome?>
                                                        </td>

                                                        <td>
                                                            <?=$ordem_servico->setor_nome?>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-sm btn-primary reset_multistep btn_editar btn-attr-ordem_servico_pk" data-toggle="modal" value="<?=$count?>" data-target="#ce_ordem_servico">
                                                                    <div class="d-none d-sm-block">
                                                                        Detalhes
                                                                    </div>
                                                                    <div class="d-block d-sm-none">
                                                                        <i class="fas fa-eye fa-fw"></i>
                                                                    </div>
                                                                </button>

                                                            </div>
                                                        </td>

                                                    </tr>
                                                    <?php $count++; ?>
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

        <!-- MODAL CRIA E ATUALIZA TIPO SERVICO -->
        <div class="modal fade" id="ce_ordem_servico">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id ="titulo">Visualizar ordem de serviço</h4>
                        <button type="button" class="close" id="close-modal" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <form class="msform">
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row col-md-12 py-3">
                                                <h4>Informações</h4>
                                            </div>
                                            <input type="hidden" id="ordem_servico_pk" value="" name="ordem_servico_fk">
                                            <div class="row form-group">
                                                <div class="col-7 col-md-6">
                                                    <label for="os_code">Código</label>
                                                    <input type="text" name="codigo_os" id="codigo_os" class="form-control" disabled="true">
                                                </div>
                                                <div class="col-7 col-md-6">
                                                    <label for="os_data">Data</label>
                                                    <input type="text" name="os_data" id="os_data" class="form-control" disabled="true">
                                                </div>
                                            </div>
                                            <div class="row form-group">

                                                <div class="col-12">
                                                    <label for="ordem_servico_desc">Descrição</label>
                                                    <textarea class="form-control" id="ordem_servico_desc" name="ordem_servico_desc" class="form-control" required="true" maxlength="200" disabled="true"></textarea>

                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-7 col-md-4">
                                                    <label for="departamento">Departamento</label>

                                                    <input class="form-control" type="text" name="departamento" id="departamento" value="" disabled="true">
                                                    
                                                </div>
                                                <div class="col-7 col-md-4">
                                                    <label for="tipo_servico">Tipo de Serviço</label>
                                                    <input class="form-control" type="text" name="tipo_servico" id="tipo_servico" disabled="true">
                                                </div>
                                                <div class="col-7 col-md-4">
                                                    <label for="servico_pk">Serviço</label>
                                                    <input type="text" name="servico_pk" id="servico_pk" class="form-control" disabled="true">
                                                    
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-7 col-md-4" id="procedencias_options">
                                                    <label for="procedencia_pk">Procedência</label>
                                                    <input type="text" name="procedencia_pk" id="procedencia_pk" class="form-control" disabled="true" >
                                                    
                                                </div>
                                                <div class="col-7 col-md-4">
                                                    <label for="prioridade_pk">Prioridade</label>
                                                    <input type="text" name="prioridade_pk" id="prioridade_pk" class="form-control" disabled="true">
                                                    
                                                </div>
                                                <div class="col-7 col-md-4">
                                                    <label for="situacao_pk">Situação</label>
                                                    <input class="form-control" type="text" name="situacao_pk" id="situacao_pk" disabled="true">
                                                    
                                                </div>
                                            </div>
                                            <div class="row col-md-12 py-3">
                                                <h4>Localização</h4>
                                            </div>
                                            <div class="row form-group">

                                                <div class="col-3 col-md-3">
                                                    <label for="uf-input" class=" form-control-label">Estado</label>
                                                    <input type="text" name="estado_pk" id="estado_pk" class="form-control" disabled="true">
                                                    
                                                </div>
                                                <div class="col-8 col-md-6">
                                                    <label for="cidade-input" class="form-control-label">Cidade</label>
                                                    <input type="text" name="municipio_pk" id="cidade-input" class="form-control" disabled="true">
                                                </div>
                                                <div class="col-3 col-md-3">
                                                    <label for="setor_pk">Setor</label>
                                                    <input type="text" name="setor_pk" id="setor_pk" class="form-control" disabled="true">
                                                    
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-12 col-md-9">
                                                    <label for="logradouro_nome">Logradouro</label>
                                                    <input class="form-control" type="text" id="logradouro-input" name="logradouro_nome" disabled="true">
                                                    
                                                </div>
                                                <div class="col-12 col-md-3">
                                                    <label for="numero-input" class=" form-control-label">N°</label>
                                                    <input type="number" id="numero-input" name="local_num" class="form-control numero-input endereco" min="0" required="true" disabled="true">
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-12 col-md-6">
                                                    <label for="complemento-input" class=" form-control-label">Complemento</label>
                                                    <input type="text" id="complemento-input" name="local_complemento" class="form-control endereco" maxlength="30" disabled="true">
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label for="bairro-input" class="form-control-label loading">Bairro</label>
                                                    <input type="hidden" name="bairro_nome" id="bairro_pk">
                                                    <div class="dropdown" id="drop-bairro">
                                                        <input class="form-control input-dropdown endereco" type="text" id="bairro-input" name="bairro" disabled="true">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <input type="hidden" id="latitude">
                                                <input type="hidden" id="longitude">
                                                <div class="col-12">
                                                    <div id="map"></div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Modal footer -->
                    <div class="modal-footer d-md-none">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Delegar para outra pessoa -->
        <div class="modal fade" id="delegar_para_outra_pessoa">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">Trocar Funcionário</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="msform">
                                <!-- progressbar -->
                                <ul class="progressbar">
                                    <li class="active">Trocar funcionário</li>

                                </ul>
                                <!-- fieldsets -->
                                <div class="card card-step col-12 px-0">
                                    <div class="card-header">
                                        Trocar Funcionário
                                    </div>
                                    <div class="card-body card-block">
                                        <div class="row form-group">
                                            <div class="col col-md-2">
                                                <label for="funcionario" class=" form-control-label"><strong>Funcionário*</strong></label>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <select id="novo-funcionario" class="form-control" required="true">
                                                    <option value="" selected="true" disabled="true">Selecione um funcionário</option>
                                                    <?php
                                                    foreach($funcionarios as $func):
                                                        ?>
                                                        <option value="<?= $func->funcionario_pk ?>"><?= $func->pessoa_nome ?></option>
                                                        <?php
                                                    endforeach;
                                                    ?>
                                                </select>
                                                <small class="form-text text-muted">Por favor, selecione um funcionário</small>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" id="btn-trocar-funcionario"><i class="fa fa-dot-circle-o"></i> Salvar</button>
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- MODAL DELETA RELATÓRIO -->
    <div class="modal fade" id="d-relatorio" >
       <div class="modal-dialog modal-dialog-centered">
           <div class="modal-content">
               <div class="modal-header">
                   <h4 class="modal-title">Destruir Relatório</h4>
                   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               </div>
               <div class="modal-body">
                <form>
                   <div class="form-group">
                       <h4 style="text-align: center" class="text-danger">
                        <i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                        <p>Ao destruir o relatório, as seguintes alterações serão feitas:</p>
                        <ul style="margin-left: 15px">
                            <li>O relatório deixará de existir, sendo as ordens de serviço vinculadas ao relatório, irão voltar para a situação atual como "aberto".</li>

                        </ul>
                    </div>

                    <div class="form-group">
                       <button type="button" class="btn btn-confirmar-senha" id="btn-deletar-relatorio" name="post" value=""><i class="fa fa-dot-circle-o"></i> Apagar</button>
                   </div>
               </form>
           </div>
       </div>
   </div>
</div>

<script type="text/javascript">
    var ordens_servico = <?php echo json_encode($ordens_servicos !== false ? $ordens_servicos : []); ?>;
</script>
<!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->