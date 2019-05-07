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
				<div class="row quick-access">
				</div>
			</div>

			<!-- END ACESSO RÁPIDO -->

			<!-- STATISTIC-->
			<!-- <div class="row">
				<div class="col-md-12">
					<h2 class="title-5 m-b-20">Informações Gerais</h2>
				</div>
			</div> -->
			<hr>

			<!-- <div class="statistic statistic2">
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
			</div> -->
		</div>
		<!-- END STATISTIC-->


		<!-- <?php if (count($ordens_em_execucao) > 0): ?>
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

							<h3 class="title-3 m-b-30"><?= $chart['title'] ?><span style="font-size:12pt;"></h3>
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
							<div class="top-campaign">
								<h3 class="title-3 m-b-30">Tipos Serviços</h3>
								<div class="table-responsive">
									<table class="table table-top-campaign">
										<tbody>
											<?php foreach($tipos_servicos as $t): ?>
												<tr>
													<td><?= $t->nome ?></td>
													<td><?= $t->quantidade ?></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>			
					</div>

				</div>
				<!-- END ESTATISTICAS CHART -->
				<?php else: ?>
					<div class="col-lg-12 m-b-40">
						<div class="au au-card question" style="text-align: center;">
							<h4 class="m-b-20">
								Não há nenhuma ordem em execução hoje, pois nenhum relatório foi gerado.

							</h4>
							<h4 class="m-b-10">Deseja criar um novo relatório?</h4>
							<h4><a class="btn btn-success btn-sm" href="<?= base_url('Relatorio/novo_relatorio')?>">Sim</a> <a class="btn btn-danger btn-sm" href="#" onclick="$('.question').hide()">Não</a></h4>
						</div>
					</div>
				<?php endif; ?>
				<?php if(count($ordens_em_execucao) > 0): ?>
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
									<div class="col-md-3" style="display:none;">
										<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
											<li class="nav-item">
												<a class="nav-link btn-sm active" id="tabela-funcionario" data-toggle="pill" href="#" role="tab" aria-controls="pills-home"
												aria-selected="true">Tabela</a>
											</li>
											<li class="nav-item">
												<a class="nav-link btn-sm" id="tabela-grafico" data-toggle="pill" href="#" role="tab" aria-controls="pills-profile"
												aria-selected="false">Gráfico</a>
											</li>
										</ul>

									</div>

								</div>

								<div class="row">
									<div class="col-md-12">
										<div id="table-funcionario" style="display: block;">
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

										<div class="heatmap" style="display: none;">
											<?php if($heatmap == false){ ?>

												<div class="heatmap"><h5 class="title-4" style="text-align: center;">Relatório não foi gerado no dia de hoje, portanto não é possível mostrar estatísticas.</h5></div>

											<?php }else{ ?>
												<div class="heatmap" id="heatmap"></div>
											<?php } ?>
										</div>

									</div>
								</div>

							</div>

							<!-- END FUNCIONÁRIOS -->

						<?php endif; ?>
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
			

			<!-- <script src="https://cdn.plot.ly/plotly-1.2.0.min.js"></script> -->

			<script type="text/javascript">
				// var xValues = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];

				// var yValues = ['Pietro'];

				// var zValues = [
				// [0.00, 0.00, 0.75, 0.75, 0.00, 0.25, 0.25, 0.00, 0.00, 0.00, 0.00],
				// ];

				// var colorscaleValue = [
				// [0, '#3D9970'],
				// [1, '#001f3f']
				// ];

				// var data = [{
				// 	x: xValues,
				// 	y: yValues,
				// 	z: zValues,
				// 	type: 'heatmap',
				// 	colorscale: colorscaleValue,
				// 	showscale: true
				// }];

				// var layout = {
				// 	title: 'Performance Funcionários',
				// 	annotations: [],
				// 	xaxis: {
				// 		ticks: '',
				// 		side: 'top'
				// 	},
				// 	yaxis: {
				// 		ticks: '',
				// 		ticksuffix: ' ',
				// 		width: 700,
				// 		height: 700,
				// 		autosize: false
				// 	},
				// };

				// for ( var i = 0; i < yValues.length; i++ ) {
				// 	for ( var j = 0; j < xValues.length; j++ ) {
				// 		var currentValue = zValues[i][j];
				// 		if (currentValue != 0.0) {
				// 			var textColor = 'white';
				// 		}else{
				// 			var textColor = 'black';
				// 		}
				// 		var result = {
				// 			xref: 'x1',
				// 			yref: 'y1',
				// 			x: xValues[j],
				// 			y: yValues[i],
				// 			text: zValues[i][j],
				// 			font: {
				// 				family: 'Arial',
				// 				size: 12,
				// 				color: 'rgb(50, 171, 96)'
				// 			},
				// 			showarrow: false,
				// 			font: {
				// 				color: textColor
				// 			}
				// 		};
				// 		layout.annotations.push(result);
				// 	}
				// }


				// Plotly.newPlot('heatmap', data, layout);
			</script> -->