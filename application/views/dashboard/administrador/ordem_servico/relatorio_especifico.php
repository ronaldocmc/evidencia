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
                        <h2 class="title-1">relatório específico</h2>
                    </div>

                    <div class="pt-5 pb-2">
                        <b>Organização</b>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="departamento_pk">Departamento</label>
                            <select size="3" multiple="multiple" class="form-control" id="departamento_pk">
                                <option value="-1">Todos</option>
                                <?php if ($departamentos != null): ?>
                                <?php foreach ($departamentos as $d): ?>
                                <option value="<?=$d->departamento_pk?>">
                                    <?=$d->departamento_nome?>
                                </option>
                                <?php endforeach?>
                                <?php endif?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="setor_pk">Setor</label>
                            <select size="3" multiple="multiple" class="form-control" id="setor_pk">
                                <option value="-1">Todos</option>
                                <?php if ($setores != null): ?>
                                <?php foreach ($setores as $s): ?>
                                <option value="<?=$s->setor_pk?>">
                                    <?=$s->setor_nome?>
                                </option>
                                <?php endforeach?>
                                <?php endif?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="procedencia_pk">Procedência</label>
                            <select size="3" multiple="multiple" class="form-control" id="procedencia_pk">
                                <option value="-1">Todos</option>
                                <?php if ($procedencias != null): ?>
                                <?php foreach ($procedencias as $p): ?>
                                <option value="<?=$p->procedencia_pk?>">
                                    <?=$p->procedencia_nome?>
                                </option>
                                <?php endforeach?>
                                <?php endif?>
                            </select>
                        </div>
                    </div>

                    <div class="pt-5 pb-2">
                        <b>Ordem de Serviço</b>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="situacao_pk">Status</label>
                            <select size="3" multiple="multiple" class="form-control" id="situacao_pk">
                                <option value="-1">Todos</option>
                                <?php if ($situacoes != null): ?>
                                <?php foreach ($situacoes as $s): ?>
                                <option value="<?=$s->situacao_pk?>">
                                    <?=$s->situacao_nome?>
                                </option>
                                <?php endforeach?>
                                <?php endif?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="prioridade_pk">Prioridade</label>
                            <select size="3" multiple="multiple" class="form-control" id="prioridade_pk">
                                <option value="-1">Todos</option>
                                <?php if ($prioridades != null): ?>
                                <?php foreach ($prioridades as $p): ?>
                                <option value="<?=$p->prioridade_pk?>">
                                    <?=$p->prioridade_nome?>
                                </option>
                                <?php endforeach?>
                                <?php endif?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="tipo_servico_pk">Tipo de Serviço</label>
                            <select size="3" multiple="multiple" class="form-control" id="tipo_servico_pk">
                                <option value="-1">Todos</option>
                                <?php if ($tipos_servicos != null): ?>
                                <?php foreach ($tipos_servicos as $ts): ?>
                                <option value="<?=$ts->tipo_servico_pk?>">
                                    <?=$ts->tipo_servico_nome?>
                                </option>
                                <?php endforeach?>
                                <?php endif?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="servico_pk">Serviço</label>
                            <select size="3" multiple="multiple" class="form-control" id="servico_pk">
                                <option value="-1">Todos</option>
                                <?php if ($servicos != null): ?>
                                <?php foreach ($servicos as $s): ?>
                                <option value="<?=$s->servico_pk?>">
                                    <?=$s->servico_nome?>
                                </option>
                                <?php endforeach?>
                                <?php endif?>
                            </select>
                            <!-- <small class="form-text text-muted">Filtrar por prioridade</small> -->
                        </div>
                    </div>

                    <div class="pt-5 pb-2">
                        <b>Horário</b>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="data_criacao">De</label>
                            <input type="date" class="form-control" id="data_criacao">
                        </div>
                        <div class="col-md-3">
                            <label for="data_fin">Até</label>
                            <input type="date" class="form-control" id="data_fin">
                        </div>

                        <div class="col-md-3">
                            <label for="hr_inicial">Hora inicial</label>
                            <input type="time" class="form-control" id="hr_inicial">
                        </div>

                        <div class="col-md-3">
                            <label for="hr_final">Hora final</label>
                            <input type="time" class="form-control" id="hr_final">
                        </div>
                    </div>

                    <div class="pt-5 pb-2">
                        <b>Localização</b>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="estado_pk">Estado</label>
                            <select size="3" multiple="multiple" class="form-control" id="estado_pk">
                                <option value="-1">Todos</option>
                                <?php if ($estados != null): ?>
                                <?php foreach ($estados as $e): ?>
                                <option value="<?=$e->estado_pk?>">
                                    <?=$e->estado_nome?>
                                </option>
                                <?php endforeach?>
                                <?php endif?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="municipio_pk">Cidade</label>
                            <select size="3" multiple="multiple" class="form-control" id="municipio_pk">
                                <option value="-1">Todos</option>
                                <?php if ($municipios != null): ?>
                                <?php foreach ($municipios as $m): ?>
                                <option value="<?=$m->municipio_pk?>">
                                    <?=$m->municipio_nome?>
                                </option>
                                <?php endforeach?>
                                <?php endif?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="bairro_pk">Bairro</label>
                            <select size="3" multiple="multiple" class="form-control" id="bairro_pk">
                                <option value="-1">Todos</option>
                                <?php if ($bairros != null): ?>
                                <?php foreach ($bairros as $b): ?>
                                <option value="<?=$b->bairro_pk?>">
                                    <?=$b->bairro_nome?>
                                </option>
                                <?php endforeach?>
                                <?php endif?>
                            </select>
                        </div>
                    </div>

                    <div class="row pt-5 d-flex justify-content-center">
                        <div class="col-md-4">
                            <button id="visulizar" class="au-btn au-btn-icon au-btn--blue form-control">
                                Visualizar
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button id="gerar_pdf" class="au-btn au-btn-icon au-btn--blue form-control">
                                Gerar PDF
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row py-5">
                <div class="col-lg-12">
                    <div class="au-card d-flex flex-column">
                        <h2 class="title-1 m-b-25">resultado</h2>
                        <div class="table-responsive table--no-card m-b-40">
                            <table id="prioridades" class="table table-striped table-datatable">
                                <thead>
                                    <tr>
                                        <th>Descrição</th>
                                        <th>Serviço</th>
                                        <th>Situação Atual</th>
                                        <th>Prioridade</th>                                        
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($ordens_servico != null): ?>
                                    <?php foreach ($ordens_servico as $key => $ordem_servico): ?>
                                    <tr>
                                        <td>
                                            <?=$ordem_servico->ordem_servico_desc?>
                                        </td>
                                        <td>
                                            <?=$ordem_servico->servico_nome?>
                                        </td>
                                        <td>
                                            <?=$ordem_servico->situacao_nome?>
                                        </td>
                                        <td>
                                            <?=$ordem_servico->prioridade_nome?>
                                        </td>

                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-success reset_multistep btn_editar" data-toggle="modal" value="<?=$key?>" data-target="#ce_servico">
                                                    <div class="d-none d-sm-block">
                                                        Visualizar
                                                    </div>
                                                    <div class="d-block d-sm-none">
                                                        <i class="fas fa-edit fa-fw"></i>
                                                    </div>
                                                </button>
                                            </div>
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
    var bairros = <?php echo json_encode($bairros !== false ? $bairros : []); ?>;
    var setores = <?php echo json_encode($setores !== false ? $setores : []); ?>;
    var estados = <?php echo json_encode($estados !== false ? $estados : []); ?>;
    var municipios = <?php echo json_encode($municipios !== false ? $municipios : []); ?>;
    var procedencias = <?php echo json_encode($procedencias !== false ? $procedencias : []); ?>;
    var ordens_servico = <?php echo json_encode($ordens_servico !== false ? $procedencias : []); ?>;
</script>



<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER