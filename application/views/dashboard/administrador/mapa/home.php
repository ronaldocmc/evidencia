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
                        <h2 class="title-1">ordens de serviço</h2>

                        <!-- <button class="au-btn au-btn-icon au-btn--blue reset_multistep" data-toggle="modal" data-target="#ce_ordem_servico">
                            <i class="zmdi zmdi-plus"></i>nova ordem de serviço
                        </button> -->
                    </div>
                    <div class="col-md-12 py-3">
                        <b>Filtros</b>
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

                        <div class="col-md-3">
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

                        <div class="col-md-3">
                            <label for="dia">Hora inicial</label>
                            <input type="time" class="form-control">
                            <!-- <small class="form-text text-muted">Dia inicial</small> -->
                        </div>

                        <div class="col-md-3">
                            <label for="dia">Hora final</label>
                            <input type="time" class="form-control">
                            <!-- <small class="form-text text-muted">Dia inicial</small> -->
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="row py-3">
                        <div class="col-md-4">
                            <label for="funcionario">Funcionário</label>
                            <input type="text" class="form-control" placeholder="Nome do funcionário">
                            <!-- <small class="form-text text-muted">Dia inicial</small> -->
                        </div>
                        <div class="col-md-4">
                            <label id="inserido_por">Inserido por</label>
                            <select class="form-control">
                                <option value="#">Fiscal</option>
                                <option value="#">Atendente</option>
                                <option value="#">Administrador</option>
                            </select>
                            <!-- <small class="form-text text-muted">Dia inicial</small> -->
                        </div>
                        <div class="col-md-4">
                            <label for="dia"> </label>
                            <button id="filtrar" class="au-btn au-btn-icon au-btn--blue form-control">Filtrar</button>
                            <!-- <small class="form-text text-muted">Dia inicial</small> -->
                        </div>
                    </div>

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
                    <table class="table hide">
                        <thead>
                            <th>Prioridade</th>
                            <th>Procedência</th>
                            <th>Serviço</th>
                            <th>Setor</th>
                        </thead>
                        <tbody>
                            <td id="v_prioridade"></td>
                            <td id="v_procedencia"></td>
                            <td id="v_servico"></td>
                            <td id="v_setor"></td>
                        </tbody>
                        <p id="v_descricao"></p>
                    </table>

                    <div align="center" class="center">
                    <img width="150px" src="<?= base_url('assets/images/loading.gif') ?>" id="v_loading" alt="Carregando">
                    </div>

                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                        </ol>
                        <div class="carousel-inner">
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Anterior</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Próximo</span>
                        </a>
                    </div>
                    <div class="qa-message-list py-5" id="timeline">
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