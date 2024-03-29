 <!--MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">


            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">Painel de Gerenciamento de Relatórios </h2>
                        <button type="button" class="au-btn au-btn-icon btn au-btn--blue pull-left dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Opções
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item imprimir_relatorio d-none" href="<?= base_url('relatorio/imprimir/'.$relatorio->relatorio_pk); ?>">
                                Imprimir relatório
                            </a>
                            <?php if ($relatorio->relatorio_situacao == 'Criado'): ?>
                                <a class="dropdown-item" href="#" data-toggle="modal" 
                                   data-target="#delegar_para_outra_pessoa">
                                    Alterar funcionário
                                </a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item imprimir_relatorio d-none" href="#" data-toggle="modal" data-target="#d-relatorio">
                                Destruir relatório
                            </a>
                            <?php if ($relatorio->relatorio_situacao == 'Em andamento'): ?>
                                <a class="dropdown-item receive_report d-none" href="#" data-toggle="modal" data-target="#restaurar_os">
                                    Receber relatório
                                </a>  
                            <?php endif; ?>
                        </div>
                    </div>

                    
                </div>
            </div>

            <input type="hidden" name="" id="id-relatorio" value="<?= $relatorio->relatorio_pk; ?>">


            <br>
            <div class="row py-2">
                <div class="col-lg-12">
                    <div class="au-card d-flex flex-column">
                        <h3 class="title-1 m-b-25"> Relatório do dia <?= date('d/m/Y', strtotime($relatorio->relatorio_data_criacao)); ?>
                        <h3 class="title-2 m-b-25" style="margin-bottom: 5px; padding-bottom: 5px;"> Revisor responsável: <?= $funcionario->funcionario_nome; ?> </h3>
                            
                            <?php

                                    if ($relatorio->relatorio_situacao == 'Entregue incompleto') {
                                        $label = 'ENTREGUE INCOMPLETO';
                                        $class = 'warning';
                                    }

                                    if ($relatorio->relatorio_situacao == 'Criado') {
                                        $label = 'CRIADO';
                                        $class = 'primary';
                                    }

                                    if ($relatorio->relatorio_situacao == 'Entregue') {
                                        $label = 'Entregue';
                                        $class = 'success';
                                    }

                                    if ($relatorio->relatorio_situacao == 'Inativo') {
                                        $label = 'INATIVO';
                                        $class = 'danger';
                                    }

                                    if ($relatorio->relatorio_situacao == 'Em andamento') {
                                        $label = 'EM ANDAMENTO';
                                        $class = 'primary';
                                    }
                                    ?>
                            <div class = "col-12 col-md-12">
                            <span style="font-size:9pt; margin: 2px;" class="badge badge-pill badge-<?= $class; ?> pull-right">
                                <?= $label; ?></span></h2>
                            </div>
                        <div class="card-group">

                            <div class="card">
                                <div class="card-header">
                                    <b>Período</b>
                                </div>
                                <div class="card-body">
                                    <?= $filtros['data']; ?>
                                </div>
                            </div>


                            <div class="card">
                                <div class="card-header">
                                    <b>Setores Selecionados</b>
                                </div>
                                <div class="card-body">
                                    <?= $filtros['setor']; ?>
                                </div>
                            </div>


                            <div class="card">
                                <div class="card-header">
                                    <b>Tipos de Serviços</b>
                                </div>
                                <div class="card-body">
                                    <?= $filtros['tipos_servicos']; ?>
                                </div>
                            </div>

                        </div>
                        
                        <!-- <button class="au-btn au-btn-icon btn au-btn--blue pull-left btn-primary col-md-4">
                            Receber relatório
                        </button> -->
                        <!-- <a target="blank" href="<?= base_url('relatorio/imprimir/'.$relatorio->relatorio_pk); ?>" class="au-btn au-btn-icon btn au-btn--blue pull-right col-md-4" style="margin-top: 10px;">
                            <i class="fas fa-print"></i>Imprimir relatório
                        </a> -->
    

                        <div class="table-responsive table--no-card m-b-40 mt-5">
                            <table id="os_table" class="table table-striped table-datatable">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Data</th>
                                        <th>Prioridade</th>
                                        <th>Endereço</th>
                                        <th>Serviço</th>
                                        <th>Setor</th>
                                        <th>Situação Atual</th>
                                        <th>Avaliação</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $count = 0; ?>
                                    <?php if ($ordens_servicos != null): ?>
                                    <?php foreach ($ordens_servicos as $key => $ordem_servico): ?>

                                    <tr>
                                        <td>
                                            <?=$ordem_servico->ordem_servico_cod; ?>
                                        </td>
                                        <td>
                                            <?= $ordem_servico->ordem_servico_criacao; ?>
                                        </td>
                                        <td>
                                            <?=$ordem_servico->prioridade_nome; ?>
                                        </td>
                                        <td>
                                            <span style="text-align: justify;">
                                                <?=$ordem_servico->localizacao_rua.', '.
                                                                $ordem_servico->localizacao_num.' - '.
                                                                $ordem_servico->localizacao_bairro; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?=$ordem_servico->servico_nome; ?>
                                        </td>

                                        <td>
                                            <?=$ordem_servico->setor_nome; ?>
                                        </td>
                                        <td>
                                            <?= $ordem_servico->ordem_servico_comentario; ?>
                                        </td>
                                        <td>
                                            <select class="form-control" id="<?= $ordem_servico->ordem_servico_pk; ?>">
                                                <?php foreach ($situacoes as $situacao): ?>
                                                    <option value="<?= $situacao->situacao_pk; ?>">
                                                        <?= $situacao->situacao_nome; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary save_situacao" id="btn<?=$ordem_servico->ordem_servico_pk; ?>" value="<?= $ordem_servico->ordem_servico_pk; ?>">Salvar</button>
                                        </td>    
                                    </tr>
                                    <?php ++$count; ?>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
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
                                                <option value="" selected="true" disabled="true">Selecione um
                                                    funcionário</option>
                                                <?php
                                                    foreach ($funcionarios as $func):
                                                        ?>
                                                <option value="<?= $func->funcionario_pk; ?>">
                                                    <?= $func->funcionario_nome; ?>
                                                </option>
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
                <button type="button" class="btn btn-primary btn-sm" id="btn-trocar-funcionario"><i class="fa fa-dot-circle-o"></i>
                    Trocar</button>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL DELETA RELATÓRIO -->
<div class="modal fade" id="d-relatorio">
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
                            <i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i>
                            ATENÇÃO</h4>
                        <p>Ao destruir o relatório, as seguintes alterações serão feitas:</p>
                        <ul style="margin-left: 15px">
                            <li>O relatório deixará de existir, sendo as ordens de serviço vinculadas ao relatório,
                                irão voltar para a situação atual como "aberto".</li>

                        </ul>
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-confirmar-senha" id="btn-deletar-relatorio" name="post"
                            value=""><i class="fa fa-dot-circle-o"></i> Destruir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="restaurar_os">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Receber Relatório</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <h4 style="text-align: center" class="text-danger">
                        <i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO
                    </h4>
                    <p>Ao confirmar esta operação:</p>
                    <ul style="margin-left: 15px">
                        <li>Todas as ordens de serviço não concluídas do relatório deste funcionário terão seu status
                            alterados para aberto;</li>
                        <li>Tais ordens de serviço serão removidas do relatório, para que sejam delegadas novamente.</li>
                    </ul>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" autocomplete="false" placeholder="Confirme sua senha"
                        required="required" id="pass-modal-restaurar">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-danger col-md-12" id="btn-restaurar"><i class="fa fa-dot-circle-o"></i>
                        Receber</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var ordens_servico = <?php echo json_encode($ordens_servicos !== false ? $ordens_servicos : []); ?>;
</script>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER