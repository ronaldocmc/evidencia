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
</style>

<!-- MAIN CONTENT--> 
<div class="main-content"> 

	<div class="section__content section__content--p30"> 
		<div class="container-fluid"> 
			<div class="row">
				<div class="col-md-12">
					<h2 class="title-4">Bem-vindo <?= $primeiro_nome ?>!</h2>
				</div>
			</div>
			<hr>
			<!-- STATISTIC-->
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
						<div class="chart-percent-2" style="max-height: 420px !important;">
							<h3 class="title-3 m-b-30">Concluídas % <span style="font-size:12pt;"><small>(23,4%)</small></span></h3>
							<div class="chart-wrap">
								<canvas id="percent-chart2"></canvas>
								<div id="chartjs-tooltip" data="60,40" labels="em andamento, concluidas">
									<table></table>
								</div>
							</div>
							<div class="chart-info">
								<div class="chart-note">
									<span class="dot dot--blue"></span>
									<span>em andamento</span>
								</div>
								<div class="chart-note">
									<span class="dot dot--red"></span>
									<span>concluídas</span>
								</div>
							</div>
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
											<tr>
												<td>COLACAP-2018/5</td>
												<td><span class="block-email">Alta</span></td>
												<td>Coleta de animal pequeno.</td>
												<td>Pietro Barcarollo Schiavinato</td>
												<td><span class="status--process">Finalizado</span></td>
											</tr>
											<tr>
												<td>LIMPLR-2018/28</td>
												<td><span class="block-email">Alta</span></td>
												<td>Limpeza de Rua</td>
												<td>Ronaldo Messias</td>
												<td><span class="status--warning">Em Andamento</span></td>

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
												<tr>
													<td>Gustavo de Deus</td>
													<td data-toggle="tooltip" data-placement="top" title="5/5 (concluídas/total)">100%</td>
													<td>A, B e C</td>
													<td>Limpeza de Rua</td>
													<td data-toggle="tooltip" data-placement="top" title="11:50">16 minutos atrás</td>
													<td><span class="status--process">Disponível</span></td>
												</tr>
												<tr >
													<td>Pietro</td>
													<td data-toggle="tooltip" data-placement="top" title="3/16 (concluídas/total)">12,3%</td>
													<td>E,F,G</td>
													<td>Coleta de Galho</td>
													<td data-toggle="tooltip" data-placement="top" title="12:07">2 horas atrás</td>
													<td><span class="status--denied">Ocupado</span></td>

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