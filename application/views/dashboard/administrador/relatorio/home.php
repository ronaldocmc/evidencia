<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">Gerenciamento de Relatórios</h2>
                        <a class="au-btn au-btn-icon au-btn--blue reset_multistep new" href="<?= base_url('Relatorio/novo_relatorio') ?>"> Novo Relatório
                        </a>
                        <button class="au-btn au-btn-icon au-btn--blue reset_multistep new btn_novo" data-toggle="modal" data-target="#restaurar_os"> 
                            Receber Relatórios
                        </button>
                    </div>
                        <div class="col-md-12 mt-3">
                            <div class="collapse" id="collapseHelp">
                                <div class="card card-body">
                                    <p>Esta é a área para gerenciamento dos relatórios.</p>
                                    <p>Aqui é possível criar um novo relatório para delgar a um determinado funcionário.</p>
                                    <p>Ao clicar em detalhes, é possível ver os detalhes do relatório criado.</p>
                                    <p>Para editar um relatório criado, é necessário acessar os detalhes do relatório.</p>
                                </div>
                            </div>
                        </div>

                
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-12">
                        <div class="au-card">
                            <h2 class="title-1 m-b-25">
                                <i style="cursor: pointer; color: gray" class="fas fa-info pull-right" data-toggle="collapse" href="#collapseHelp" role="button" aria-expanded="false" aria-controls="collapseHelp"></i>
                            Relatórios</h2>
                            <div class="">
                                <h5>Filtrar por</h5>
                                <br>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="filter-ativo">Mostrar</label>
                                        <select name="filter-ativo" id="filter-ativo" class="form-control">
                                            <option value="todos">Todos</option>
                                            <option value="0">Apenas em andamento</option>
                                            <option value="1">Apenas finalizados</option>
                                        </select>
                                        <br>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive table--no-card m-b-40">
                                <table class="table table-striped table-datatable">
                                    <thead>
                                        <tr>
                                            <th>Funcionário</th>
                                            <th>Quantidade</th>
                                            <th>Situação</th>
                                            <th>Data de Criação</th>
                                            <th>Data de Entrega</th>
                                            <th>Detalhes</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        if ($relatorios):
                                            foreach ($relatorios as $key => $r): ?>
                                               <tr>
                                                   <td>
                                                       <?=$r->pessoa_nome ?>
                                                   </td>
                                                   <td>
                                                       <?= $r->quantidade_os ?>
                                                   </td>
                                                   <td>
                                                     <?= $r->status_string ?>
                                                   </td>
                                                   <td>
                                                       <?= $r->data_criacao ?>
                                                    </td>
                                                    <td>
                                                       <?= $r->data_entrega ?>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a class="btn btn-sm btn-primary" href="<?= base_url('relatorio/detalhes_relatorio/'.$r->relatorio_pk) ?>">
                                                                   <div class="d-none d-sm-block">
                                                                       Detalhes
                                                                   </div>
                                                                   <div class="d-block d-sm-none">
                                                                       <i class="fas fa-eye fa-fw"></i>
                                                                   </div>
                                                               </a>
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

<div class="modal fade" id="restaurar_os">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Receber Relatórios</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <h4 style="text-align: center" class="text-danger">
                        <i class="fa fa-exclamation-triangle animated tada infinite" aria-hidden="true"></i> ATENÇÃO
                    </h4>
                    <p>Ao confirmar esta operação:</p>
                    <ul style="margin-left: 15px">
                        <li>Todas as ordens de serviço não concluídas em relatórios de funcionários terão seu status alterados para aberto;</li>
                        <li>Tais ordens de serviço serão removidas do relatório, para que sejam delegadas novamente.</li>
                    </ul>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" autocomplete="false" placeholder="Confirme sua senha" required="required" id="pass-modal-restaurar">
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-danger col-md-12" id="btn-restaurar"><i class="fa fa-dot-circle-o"></i> Receber</button>
                </div>
            </div>
        </div>
    </div>
</div>

            <script type="text/javascript">
                var relatorios = <?php echo json_encode($relatorios !== false ? $relatorios : []) ?>;
            </script>