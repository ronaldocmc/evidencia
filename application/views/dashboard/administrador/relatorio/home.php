<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">Gerenciamento de Relatórios</h2>
                        <a class="au-btn au-btn-icon au-btn--blue" href="<?= base_url('relatorio/novo_relatorio') ?>">
                            <i class="zmdi zmdi-plus"></i>novo relatório</a>
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
                                            <th>Relatório ID</th>
                                            <th>Funcionário</th>
                                            <th>Função do Funcionário</th>
                                            <th>Quantidade</th>
                                            <th>Data de Criação</th>
                                            <th>Detalhes</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        if ($relatorios):
                                            foreach ($relatorios as $key => $r): ?>
                                               <tr>
                                                   <td>
                                                       <?=$r->relatorio_pk?>
                                                   </td>
                                                   <td>
                                                       <?=$r->pessoa_nome ?>
                                                   </td>
                                                   <td>
                                                       <?=$r->funcao_nome?>
                                                   </td>
                                                   <td>
                                                       <?= $r->quantidade_os ?>
                                                   </td>
                                                   <td>
                                                       <?= $r->data_criacao ?>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a target="_blank" class="btn btn-sm btn-primary" href="<?= base_url('relatorio/detalhes_relatorio/'.$r->relatorio_pk) ?>">
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

    

            <script type="text/javascript">
                var relatorios = <?php echo json_encode($relatorios !== false ? $relatorios : []) ?>;
            </script>