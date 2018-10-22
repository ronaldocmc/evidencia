<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"> Painel de Gerenciamento de Relatórios </h2>
                        
                    </div>
                    <div class="col-md-12 mt-3">

                    </div>
                </div>
            </div>
            <div class="row py-2">
                <div class="col-lg-12">
                    <div class="au-card d-flex flex-column">
                        <h2 class="title-1 m-b-25">Relatório do <?= $funcionario->pessoa_nome ?> do dia <?= date("d/m/Y", strtotime($relatorio->data_criacao)) ?></h2>
                        <div class="row">
                            <div class="col-md-6">
                                <button class="au-btn au-btn-icon au-btn--blue" data-toggle="modal" data-target="#delegar-para-outro" id="btn-delegar-para-outro">
                                    <i class="zmdi zmdi-spinner"></i>DELEGAR PARA OUTRO FUNCIONÁRIO
                                </button>
                                <button type="button" class="btn btn-sm btn-danger btn-desativar" data-toggle="modal" data-target="#d-prioridade">
                                    <div class="d-none d-sm-block">
                                        Apagar
                                    </div>
                                    <div class="d-block d-sm-none">
                                        <i class="fas fa-times fa-fw"></i>
                                    </div>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="au-btn au-btn-icon au-btn--blue pull-right" data-toggle="modal" data-target="#destruir-relatorio" id="btn-destruir-relatorio">
                                    <i class="zmdi zmdi-delete"></i>DELETAR RELATÓRIO
                                </button>
                            </div>
                        </div>
                        <br><br>
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

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($ordens_servicos != null): ?>
                                        <?php foreach ($ordens_servicos as $key => $ordem_servico): ?>
                                            <tr>
                                                <td>
                                                    <?=$ordem_servico->ordem_servico_cod?>
                                                </td>
                                                <td>
                                                    <span style="display: none"><?=$ordem_servico->data_criacao?></span><?=date('d/m/Y H:i:s', strtotime($ordem_servico->data_criacao))?>
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

                                                
                                            </tr>
                                        <?php endforeach?>
                                    <?php endif?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="destruir-relatorio">
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
                                        <button type="button" class="btn btn-confirmar-senha" id="btn-desativar" name="post" value="">Destruir</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>




        <!-- MODAL DELETA prioridadeS -->
        <div class="modal fade" id="d-prioridade" >
         <div class="modal-dialog modal-dialog-centered">
             <div class="modal-content">
                 <div class="modal-header">
                     <h4 class="modal-title">Desativar prioridade</h4>
                     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 </div>
                 <div class="modal-body">
                    <form>
                     <div class="form-group">
                         <h4 style="text-align: center" class="text-danger"><i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO</h4>
                         <p>Ao desativar um prioridade, as seguintes ações também serão feitas:</p>
                         <ul style="margin-left: 15px">
                             <li>Todas os tipos de serviços serão desativados também</li>
                             <li>Nenhuma ordem de serviço com estes tipos poderão ser registradas</li>
                         </ul>
                     </div>
                     <div class="form-group">

                    </div>

                 <div class="form-group">
                     <button type="button" class="btn btn-confirmar-senha" id="btn-desativar" name="post" value=""><i class="fa fa-dot-circle-o"></i> Apagar</button>
                 </div>
             </form>
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

