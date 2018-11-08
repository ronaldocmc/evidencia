<style type="text/css">
.fs-16 {
	font-size:16pt;
}
.fs-10 {
	font-size:10pt;
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
						<div class="col-md-6 col-lg-3">
							<div class="statistic__item statistic__item--green">
								<h2 class="number">21</h2>
								<span class="desc">novas</span>
								<div class="icon">
									<i class="fas fa-plus"></i>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-3">
							<div class="statistic__item statistic__item--orange" data-toggle="tooltip" data-placement="top" title="5/23 (concluídas/total)">
								<h2 class="number">23,4%</h2>
								<span class="desc">concluídas</span>
								<div class="icon">
									<i class="fas fa-tasks"></i>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-3">
							<div class="statistic__item statistic__item--red" data-toggle="tooltip" data-placement="top" title="Pedro, José, Cláudio">
								<h2 class="text">5</h2>
								<span class="desc">revisores</span>
								<div class="icon">
									<i class="fas fa-users"></i>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-3">
							<div class="statistic__item statistic__item--blue" data-toggle="tooltip" data-placement="top" title="A, B, D e E">
								<h2 class="text">6</h2>
								<span class="desc">setores</span>
								<div class="icon">
									<i class="fas fa-map-marker-alt"></i>
								</div>
							</div>
						</div>
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
				<div class="row">
					<div class="col-md-12 col-lg-12 m-b-40">
						<div class="myDiv" id="myDiv"></div>
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
				<div class="au-card  d-flex flex-column">
					<div class="row">

						<div class="col-md-9">
							<h2 class="title-2 text-center m-b-30 fs-16">Ordens em Execução</h2>
						</div>
						<div class=col-md-3>
							<button type="button" class="btn btn-primary btn-sm">Tabela</button>
							<button type="button" class="btn btn-outline-success btn-sm">Mapa</button>
						</div>

					</div class="table-section">

					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive table--no-card m-b-40">
								<table id="ordens_servico" class="table table-striped table-datatable">
									<thead>
										<tr>
											<th>Código</th>
											<th>Prioridade</th>
											<th>Serviço</th>
											<th>Funcionário</th>
											<th>Situação</th>                     
										</tr>
									</thead>
									<tbody>
										<tr class="table-success">
											<td>COLACAP-2018/5</td>
											<td>Alta</td>
											<td>Coleta de animal pequeno.</td>
											<td>Pietro Barcarollo Schiavinato</td>
											<td>Finalizado</td>
										</tr>
										<tr>
											<td>LIMPLR-2018/28</td>
											<td>Alta</td>
											<td>Limpeza de Rua</td>
											<td>Ronaldo Messias</td>
											<td>Em Andamento</td>

										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!-- END ORDENS EM EXECUÇÃO -->

					<!-- FUNCIONÁRIOS -->
					<div class="au-card d-flex flex-column m-t-50">
						<div class="row">

							<div class="col-md-9">
								<h2 class="title-2 text-center m-b-30 fs-16">Funcionários</h2>
							</div>
							<div class=col-md-3>
								<button type="button" class="btn btn-primary btn-sm">Tabela</button>
								<button type="button" class="btn btn-outline-warning btn-sm">Gráfico</button>
							</div>

						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive table--no-card m-b-40">
									<table id="ordens_servico" class="table table-striped table-datatable">
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
											<tr class="table-success">
												<td>Gustavo de Deus</td>
												<td data-toggle="tooltip" data-placement="top" title="5/5 (concluídas/total)">100%</td>
												<td>A, B e C</td>
												<td>Limpeza de Rua</td>
												<td data-toggle="tooltip" data-placement="top" title="11:50">16 minutos atrás</td>
												<td>Disponível</td>
											</tr>
											<tr >
												<td>Pietro</td>
												<td data-toggle="tooltip" data-placement="top" title="3/16 (concluídas/total)">12,3%</td>
												<td>E,F,G</td>
												<td>Coleta de Galho</td>
												<td data-toggle="tooltip" data-placement="top" title="12:07">2 horas atrás</td>
												<td>Ocupado</td>

											</tbody>
										</table>
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


			Plotly.newPlot('myDiv', data, layout);
		</script>