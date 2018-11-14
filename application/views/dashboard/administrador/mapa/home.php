<style>
.carousel-caption {
    color: white !important;
    background-color: rgba(0, 0, 0, .45);
}
</style>

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">mapa</h2>
                        <!-- <button id="filtrar" class="au-btn au-btn-icon au-btn--blue reset_multistep">Filtrar</button> -->
                        <!-- <button class="au-btn au-btn-icon au-btn--blue reset_multistep" data-toggle="modal" data-target="#ce_ordem_servico">
                            <i class="zmdi zmdi-plus"></i>nova ordem de serviço
                        </button> -->
                    </div>
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <!-- <b>Filtros</b> -->
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="servico_pk">Departamento</label>
                            <select class="form-control" id="departamento_pk" name="departamento_pk" required="true">
                                <option value="-1">Todos</option>
                                <?php if ($departamentos != null): ?>
                                    <?php foreach ($departamentos as $d): ?>
                                        <option value="<?=$d->departamento_pk?>">
                                            <?=$d->departamento_nome?>
                                        </option>
                                    <?php endforeach?>
                                <?php endif?>
                            </select>
                            <!-- <small class="form-text text-muted">Filtrar por departamento</small> -->
                        </div>

                        <div class="col-md-3">
                            <label for="servico_pk">Tipo de serviço</label>
                            <select class="form-control" id="tipo_servico_pk" name="tipo_servico_pk" required="true">
                                <option value="-1">Todos</option>
                                <?php if ($tipos_servicos != null): ?>
                                    <?php foreach ($tipos_servicos as $t): ?>
                                        <option value="<?=$t->tipo_servico_pk?>">
                                            <?=$t->tipo_servico_nome?>
                                        </option>
                                    <?php endforeach?>
                                <?php endif?>
                            </select>
                            <!-- <small class="form-text text-muted">Filtrar por departamento</small> -->
                        </div>

                        <div class="col-md-3">
                            <label for="servico_pk">Serviço</label>
                            <select class="form-control" id="servico_pk" name="servico_pk" required="true">
                                <option value="-1">Todos</option>
                                <?php if ($servicos != null): ?>
                                    <?php foreach ($servicos as $s): ?>
                                        <option value="<?=$s->servico_pk?>">
                                            <?=$s->servico_nome?>
                                        </option>
                                    <?php endforeach?>
                                <?php endif?>
                            </select>
                            <!-- <small class="form-text text-muted">Filtrar por serviços</small> -->
                        </div>

                        <div class="col-md-3">
                            <label for="situacao_pk">Situação</label>
                            <select class="form-control" id="situacao_pk" name="situacao_pk" required="true">
                                <option value="-1">Todos</option>
                                <?php if ($situacoes != null): ?>
                                    <?php foreach ($situacoes as $s): ?>
                                        <option value="<?=$s->situacao_pk?>">
                                            <?=$s->situacao_nome?>
                                        </option>
                                    <?php endforeach?>
                                <?php endif?>
                            </select>
                            <!-- <small class="form-text text-muted">Filtrar por situação</small> -->
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="row py-3">
                        <div class="col-md-3">
                            <label for="dia">De</label>
                            <input type="date" class="form-control" id="de">
                            <!-- <small class="form-text text-muted">Dia inicial</small> -->
                        </div>
                        <div class="col-md-3">
                            <label for="dia">Até</label>
                            <input type="date" class="form-control" id="ate">
                            <!-- <small class="form-text text-muted">Dia inicial</small> -->
                        </div>

                        <div class="col-md-2">
                            <label for="dia">Hora inicial</label>
                            <input type="time" id="h_inicial" class="form-control">
                            <!-- <small class="form-text text-muted">Dia inicial</small> -->
                        </div>

                        <div class="col-md-2">
                            <label for="dia">Hora final</label>
                            <input type="time" id="h_final" class="form-control">
                            <!-- <small class="form-text text-muted">Dia inicial</small> -->
                        </div>
                        <div class="col-md-2">
                            <label for="prioridade_pk">Prioridade</label>
                            <select class="form-control" id="prioridade_pk" name="prioridade_pk" required="true">
                                <option value="-1">Todos</option>
                                <?php if ($prioridades != null): ?>
                                    <?php foreach ($prioridades as $p): ?>
                                        <option value="<?=$p->prioridade_pk?>">
                                            <?=$p->prioridade_nome?>
                                        </option>
                                    <?php endforeach?>
                                <?php endif?>
                            </select>
                            <!-- <small class="form-text text-muted">Filtrar por prioridade</small> -->
                        </div>
                    </div>
                    <button id="filtrar" class="au-btn au-btn-icon au-btn--blue reset_multistep pull-right">Filtrar</button>
                    <!-- <div class="divider"></div> 
                     <div class="row py-3">
                        <div class="col-md-4">
                            <label for="funcionario">Funcionário</label>
                            <input type="text" class="form-control" placeholder="Nome do funcionário">
                             <small class="form-text text-muted">Dia inicial</small>
                        </div>
                        <div class="col-md-4">
                            <label id="inserido_por">Inserido por</label>
                            <select class="form-control">
                                <option value="#">Fiscal</option>
                                <option value="#">Atendente</option>
                                <option value="#">Administrador</option>
                            </select>
                             <small class="form-text text-muted">Dia inicial</small> 
                        </div>
                        
                    </div> -->
                </div>
            </div>
        </div>
        <div class="row py-5">
            <div class="col-lg-12">
                <div class="au-card d-flex flex-column">
                    <div id="map" style="height: 800px"></div>
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

<!-- MODAL -->
<div class="modal fade" id="v_evidencia">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ver Evidência</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <table class="table hide" style="text-align: center;">
                        <thead>
                            <th>Código</th>
                            <th>Descricao</th>
                            <th>Serviço</th>
                            <th>Setor</th>
                        </thead>
                        <tbody>
                            <td id="v_codigo"></td>
                            <td id="v_descricao"></td>
                            <td id="v_servico"></td>
                            <td id="v_setor"></td>
                        </tbody>
                    </table>
                    <div align="center" class="center">
                        <img width="150px" src="<?= base_url('assets/images/loading.gif') ?>" id="v_loading" alt="Carregando">
                    </div>
                    <div class="container-fluid" id="card_slider" style="margin-top: 30px;"></div>
                    <div class="qa-message-list py-2" id="timeline">
                        <div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <script type="text/javascript">
            var servicos = <?php echo json_encode($servicos !== false ? $servicos : []); ?>;
            var prioridades = <?php echo json_encode($prioridades !== false ? $prioridades : []); ?>;
            var situacoes = <?php echo json_encode($situacoes !== false ? $situacoes : []); ?>;
            var departamentos = <?php echo json_encode($departamentos !== false ? $departamentos : []); ?>;
            var tipos_servicos = <?php echo json_encode($tipos_servicos !== false ? $tipos_servicos : []); ?>;
        </script>

        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
        </script>


        <!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER