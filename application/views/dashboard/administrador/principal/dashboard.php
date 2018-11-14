<style type="text/css">
.fs-16 {
	font-size:16pt;
}
.fs-10 {
	font-size:10pt;
}

.status--warning {
	color: #ffc107;
}

thead {
	font-size: 10pt;
}

tbody {
	/*text-align: center; */
	font-size: 10pt;
}


.acesso-rapido {
	-webkit-transition: all 0.5s ease-in-out;
	color: white;
	height: 300px;
}

.icones{
	background-color: #fff;
	border-radius: 100%;
	margin: 0 auto;
	display: flex;
    align-items: center;
    justify-content: center;
	width: 70px;
	height: 70px;
	-webkit-transform: scale(1.5);
	font-size: 20pt;
}

.fas {
    -webkit-transition: 0.6s ease-out;
    -moz-transition:  0.6s ease-out;
    transition:  0.6s ease-out;
}

.fas:hover {
	-webkit-transform: rotateZ(720deg);
	-moz-transform: rotateZ(720deg);
	transform: rotateZ(720deg);
}

.color-red {
	color: #fa4251;
}

.color-green{
	color: #28a745;
}

.color-orange {
	color: #ff8300;
}

.color-blue {
	color: #00b5e9;
}

.acesso-rapido:hover {
	cursor: pointer;
	-webkit-transform: scale(1.1); 
}



.geral {
	text-align: center;
    margin: 0 auto;
    padding: 2em 0 3em;
}

.acesso-rapido h2{
	color:white;
}

.text{
	padding: 50px 10px;
}

.bag {
	margin-top:;
}

</style>

<!-- MAIN CONTENT--> 
<div class="main-content"> 

	<div class="section__content section__content--p30"> 
		<div class="container-fluid"> 
			<!-- TITLE -->
			<div class="row">
				<div class="col-md-12">
					<h2 class="title-4">Bem-vindo <?= $primeiro_nome ?>!</h2>
				</div>
			</div>
			<!-- END TITLE -->
			<hr class="m-b-35">

			<!-- ACESSO RÁPIDO -->
			<div class="row">
				<div class="col-md-12">
					<h2 class="title-5 m-b-10">Acesso Rápido</h2>
				</div>
			</div>
			<hr>
			<div class="container">
				<div class="row">
					<div class="col-sm-12 col-md-6 col-lg-3">
						<div class="statistic__item statistic__item--blue acesso-rapido">
							<div class="geral" onclick="window.location = '<?= base_url('ordem_servico') ?>';">
								<div class="bag">
									<div class="icones color-blue">
										<i class="fas fa-thumbtack"></i>
									</div>
									<div class="text">
										<h2>nova ordem</h2>
										
									</div>
								</div>
							</div>
						</div>

					</div>
					<div class="col-sm-12 col-md-6 col-lg-3">
						<div class="statistic__item statistic__item--orange acesso-rapido">
							<div class="geral" onclick="window.location = '<?= base_url('relatorio/novo_relatorio') ?>';">
								<div class="bag">
									<div class="icones color-orange">
										<i class="fas fa-tasks"></i>
									</div>
									<div class="text">
										<h2>novo relatório</h2>
										
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-6 col-lg-3">
						<div class="statistic__item statistic__item--red acesso-rapido">
							<div class="geral" onclick="window.location = '<?= base_url('relatorio/mapa') ?>';">
								<div class="bag">
									<div class="icones color-red">
										<i class="fas fa-map-marker-alt"></i>
									</div>
									<div class="text">
										<h2>mapa</h2>
										
									</div>
								</div>
							</div>
						</div>
						
					</div>
					<div class="col-sm-12 col-md-6 col-lg-3">
						<div class="statistic__item statistic__item--green acesso-rapido">
							<div class="geral" onclick="alert('REFRESH');">
								<div class="bag">
									<div class="icones color-green">
										<i class="fas fa-refresh"></i>
									</div>
									<div class="text">
										<h2>atualizar</h2>
										<small>última atualização às 14/11/2018 10:20:21</small>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<!-- END ACESSO RÁPIDO -->

			<!-- STATISTIC-->
			<div class="row">
				<div class="col-md-12">
					<h2 class="title-5 m-b-20">Informações Gerais</h2>
				</div>
			</div>
			<hr>

			<div class="statistic statistic2">
				<div class="container">
					<div class="row">

						<?php 
						$quantidade = count($cards);
						$grid = 12/$quantidade;


						foreach($cards as $card):
							?>
							<div class="col-md-6 col-lg-<?= $grid ?>">
								<div class="statistic__item statistic__item--<?= $card['color'] ?>
								"
								<?php if(array_key_exists('tooltip', $card)): ?>
									data-toggle="tooltip" data-placement="top" title="<?= $card['tooltip'] ?>"
								<?php endif; ?>
								>
								<h2 class="number"><?= $card['title'] ?></h2>
								<span class="desc"><?= $card['label'] ?></span>
								<div class="icon">
									<i class="fas <?= $card['icon'] ?>"></i>
								</div>
							</div>
						</div>

					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<!-- END STATISTIC-->

		<?php if (count($ordens_em_execucao) > 0): ?>
			<!-- ESTATISTICAS CHART -->
			<div class="statistic-chart">
				<div class="row">
					<div class="col-md-12">
						<h3 class="title-5 m-b-35">estatísticas</h3>
						<hr>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6 col-lg-6">
						<!-- CHART PERCENT-->

						<?php $chart = $charts['doughnut']; ?>

						<div class="chart-percent-2" style="max-height: 420px !important;">

							<h3 class="title-3 m-b-30"><?= $chart['title'] ?><span style="font-size:12pt;"><small> (<?= $chart['percent'] ?> %)</small></span></h3>
							<div class="chart-wrap">
								<canvas id="percent-chart2"></canvas>
								<div id="chartjs-tooltip" data="<?= $chart['data'] ?>" labels="<?= $chart['labels'] ?>">
									<table></table>
								</div>
							</div>
							<?php foreach($chart['label'] as $label): ?>
								<div class="chart-note">
									<div class="chart-info">
										<span class="dot dot--<?= $label['color'] ?>"></span>
										<span><?= $label['label'] ?></span>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
						<!-- END CHART PERCENT-->
					</div>
					<div class="col-md-6 col-lg-6">

						<div class="chart-percent-2" style="max-height: 420px !important;">
							<h3 class="title-2 m-b-40">Tipos Serviços</h3>
							<div class="chart-wrap">
								<canvas id="sales-chart"></canvas>
							</div>
							<div id="chartjs-tooltip"></div>
							<div class="chart-info"></div>
							<div class="chart-info"></div>
						</div>
					</div>
				</div>

			</div>
			<!-- END ESTATISTICAS CHART -->
			<?php else: ?>
				<div class="col-lg-12 m-b-40">
					<div class="au au-card question" style="text-align: center;">
						<h4 class="m-b-20">
							Não há nenhuma ordem em execução no dia de hoje pois não há relatórios.
							
						</h4>
						<h4 class="m-b-10">Deseja criar um novo relatório?</h4>
						<h4><a class="btn btn-success btn-sm" href="<?= base_url('Relatorio/novo_relatorio')?>">Sim</a> <a class="btn btn-danger btn-sm" href="#" onclick="$('.question').hide()">Não</a></h4>
					</div>
				</div>
			<?php endif; ?>

			<div>
				<div class="row">
					<div class="col-md-12">
						<h3 class="title-5 m-b-35">Tabelas</h3>
						<hr>
					</div>
				</div>

				<!-- ORDENS EM EXECUÇÃO -->
				<div class="au-card d-flex flex-column">
					<div class="row">

						<div class="col-md-12">
							<h2 class="title-2 m-b-30 fs-16" style="text-align: left;">Ordens em Execução</h2>
						</div>
						<!-- <div class=col-md-3>
							<ul class="nav nav-pills">
								<li class="active"><button type="button" class="btn btn-primary btn-sm">Tabela</button></li>
								<li><button type="button" class="btn btn-outline-success btn-sm">Mapa</button></li>
							</ul> 

							<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active btn-sm" id="tabela-mapa" data-toggle="pill" href="#" role="tab" aria-controls="pills-home"
									aria-selected="true">Tabela</a>
								</li>
								<li class="nav-item">
									<a class="nav-link btn-sm" id="piltabela-mapa" data-toggle="pill" href="#" role="tab" aria-controls="pills-profile"
									aria-selected="false">Mapa</a>
								</li>
							</ul>

						</div> -->

					</div class="table-section">

					<div class="row">
						<div class="col-md-12">
							<div id="table-ordens">
								<div class="table-responsive table-data2 table--no-card m-b-40">
									<table class="table table-data2 table-datatable">
										<thead>
											<tr class="tr-shadow">
												<th>Código</th>
												<th>Prioridade</th>
												<th>Serviço</th>
												<th>Funcionário</th>
												<th>Situação</th>                     
											</tr>
										</thead>
										<tbody>
											<?php foreach($ordens_em_execucao as $os): ?>

												<tr>
													<td><?= $os->codigo ?></td>
													<td><span class="block-email">
														<?= $os->prioridade ?></span></td>
														<td><?= $os->servico ?></td>
														<td><?= $os->funcionario ?></td>
														<td><span class="
															<?php 
															if($os->situacao == 'Finalizado'){
																echo 'status--process';
																} else {
																	echo 'status--warning';
																}
																?>
																">
																<?= $os->situacao ?></span></td>
															</tr>

														<?php endforeach; ?>
													</tbody>
												</table>
											</div>
										</div>

										<div id="map" style="display:none;"></div>

									</div>
								</div>
							</div>
							<!-- END ORDENS EM EXECUÇÃO -->

							<!-- FUNCIONÁRIOS -->
							<div class="au-card d-flex flex-column m-t-50">
								<div class="row">

									<div class="col-md-9">
										<h2 class="title-2 m-b-30 fs-16" style="text-align: left;">Funcionários</h2>
									</div>
									<div class=col-md-3>
										<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
											<li class="nav-item">
												<a class="nav-link btn-sm" id="tabela-funcionario" data-toggle="pill" href="#" role="tab" aria-controls="pills-home"
												aria-selected="true">Tabela</a>
											</li>
											<li class="nav-item">
												<a class="nav-link btn-sm active" id="tabela-grafico" data-toggle="pill" href="#" role="tab" aria-controls="pills-profile"
												aria-selected="false">Gráfico</a>
											</li>
										</ul>

<!-- 
								<button type="button" class="btn btn-primary btn-sm">Tabela</button>
								<button type="button" class="btn btn-outline-warning btn-sm">Gráfico</button> -->
							</div>

						</div>

						<div class="row">
							<div class="col-md-12">
								<div id="table-funcionario" style="display: none;">
									<div class="table-responsive table-data2 table--no-card m-b-40">
										<table class="table table-striped table-datatable">
											<thead>
												<tr>
													<th>Nome</th>
													<th>Performance</th>
													<th>Setores</th>
													<th>Serviços</th>
													<th>Última ordem concluída a</th>
													<th>Status</th>                     
												</tr>
											</thead>
											<tbody>
												<?php foreach($funcionarios as $f): ?>

													<tr>
														<td><?= $f['nome'] ?></td>
														<td data-toggle="tooltip" data-placement="top" title="<?= $f['performance']['tooltip'] ?>"><?= $f['performance']['label'] ?></td>
														<td><?= $f['setores'] ?></td>
														<td><?= $f['servicos'] ?></td>
														<td data-toggle="tooltip" data-placement="top" title="<?= $f['ultima_ordem']['tooltip'] ?>"><?= $f['ultima_ordem']['label'] ?></td>
														<td><span class="<?= $f['status']['class'] ?>"><?= $f['status']['label'] ?></span></td>
													</tr>

												<?php endforeach; ?>
												
											</tbody>
										</table>
									</div>
								</div>

								<div class="heatmap" style="display: block;">
										<!-- <div class="row">
											<div class="col-md-12 col-lg-12 m-b-40"> -->
												<div class="heatmap" id="heatmap"></div>
											<!-- </div>
											</div> -->
										</div>

									</div>
								</div>

							</div>

							<!-- END FUNCIONÁRIOS -->
						</div>


						<!-- FOOTER -->
						<div class="row"> 
							<div class="col-md-12"> 
								<div class="copyright"> 
									<p>Copyright © 2018 Colorlib. All rights reserved. Template by <a href="https://colorlib.com">Colorlib</a>.</p> 
								</div> 
							</div> 
						</div> 
						<!-- END FOOTER -->
					</div>
				</div>
			</div>

			<script src="https://cdn.plot.ly/plotly-1.2.0.min.js"></script>

			<script type="text/javascript">
				var xValues = ['A', 'B', 'C', 'D', 'E'];

				var yValues = ['W', 'X', 'Y', 'Z'];

				var zValues = [
				[0.00, 0.00, 0.75, 0.75, 0.00],
				[0.00, 0.00, 0.75, 0.75, 0.00],
				[0.75, 0.75, 0.75, 0.75, 0.75],
				[0.00, 0.00, 0.00, 0.75, 0.00]
				];

				var colorscaleValue = [
				[0, '#3D9970'],
				[1, '#001f3f']
				];

				var data = [{
					x: xValues,
					y: yValues,
					z: zValues,
					type: 'heatmap',
					colorscale: colorscaleValue,
					showscale: true
				}];

				var layout = {
					title: 'Performance Funcionários',
					annotations: [],
					xaxis: {
						ticks: '',
						side: 'top'
					},
					yaxis: {
						ticks: '',
						ticksuffix: ' ',
						width: 700,
						height: 700,
						autosize: false
					},
				};

				for ( var i = 0; i < yValues.length; i++ ) {
					for ( var j = 0; j < xValues.length; j++ ) {
						var currentValue = zValues[i][j];
						if (currentValue != 0.0) {
							var textColor = 'white';
						}else{
							var textColor = 'black';
						}
						var result = {
							xref: 'x1',
							yref: 'y1',
							x: xValues[j],
							y: yValues[i],
							text: zValues[i][j],
							font: {
								family: 'Arial',
								size: 12,
								color: 'rgb(50, 171, 96)'
							},
							showarrow: false,
							font: {
								color: textColor
							}
						};
						layout.annotations.push(result);
					}
				}


				Plotly.newPlot('heatmap', data, layout);
			</script>