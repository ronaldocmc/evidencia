
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css">

<!-- MAIN CONTENT-->
<div class="main-content">
	<div class="section__content section__content--p30">
		<div class="container-fluid">
			
			<!-- TITLE -->
			<div class="row">
				<div class="col-md-12">
					<h2 class="title-4">Bem-vindo <?=$primeiro_nome?>!</h2>
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

			<hr class="m-b-15">
			<div class="container">
				<div class="row quick-access"></div>
			</div>
			<!-- END ACESSO RÁPIDO -->

			<!-- STATISTIC -->
			<hr class="m-b-20">
			<div class="row">
				<div class="col-md-12">
					<h2 class="title-5 m-b-20">Estatísticas Gerais</h2>
				</div>
			</div>

			<div class="au-card d-flex flex-column m-t-10">
				<div class="col-md-9">
						<h2 class="title-2 m-b-30 fs-16" style="text-align: left;">Nossos Números</h2>
				</div> 	
				<div class="col-lg-12 col-sm-12 col-md-12" style="display: flex;"> <!--  padding-left: 10%; -->
					<div class="card" style="width: 16rem; margin-right: 40px;"> 
						<div class="card-body">
							<h5 class="card-title">Total de Ordens de Serviço</h5>
							<h3 id="total_ordens"></h4>
						</div>
					</div>
					<div class="card" style="width: 16rem; margin-right: 40px;">
						<div class="card-body">
							<h5 class="card-title">Taxa de Crescimento Semanal</h5>
							<h3 id="taxa_crescimento"></h4>
						</div>
					</div>
					<div class="card" style="width: 16rem;">
						<div class="card-body">
							<h5 class="card-title">Tempo Médio de Finalização</h5>
							<h3 id="media_finalizacao"></h4>
						</div>
					</div>
				</div>
			</div>
			<br>
			<div class="au-card d-flex flex-column m-t-10">							
				<div class="col-md-9">
						<h2 class="title-2 m-b-30 fs-16" style="text-align: left;">Ordens de Serviço</h2>
				</div> 
				<div class="row">
					<div class="col-md-12" style="display: flex">
						<div class="col-md-6 chart-container">
							<canvas id="ordens_semana" name="multi-axis"></canvas>
						</div>
						<div class="col-md-6 chart-container">
							<canvas id="ordens_setor_semana" name="bar-stacked"></canvas> 
						</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-12>" style="display: flex; width: 100%;">
						<div class="col-md-6 chart-container">
							<canvas id="ordens_mes" name="line-boundaries"></canvas>
						</div>
						<div class="col-md-6 chart-container">
							<canvas id="ordens_tipo_servico" name="pie"></canvas>
						</div>
					</div> 
				</div>
				<br>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive table--no-card m-b-40" style="display: none;">
							<table id="ordens_servico" class="table table-striped">
								<thead>
									<tr>
										<th id="id_as">id</th>
										<th>Código</th>
										<th>Prioridade</th>
										<th>Serviço</th>
										<th>Funcionário</th>
										<th>Situação</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
					<div class="col-md-12">
						<div class="map"><div id="map"></div></div>
					</div>
				</div>
			</div> 
			
			<!-- DADOS DE FUNCIONÁRIOS
			<div class="au-card d-flex flex-column m-t-50">
				<div class="row">
					<div class="col-md-9">
						<h2 class="title-2 m-b-30 fs-16" style="text-align: left;">Funcionários</h2>
					</div>
					Menu iterativo que o usuário clica em qual opção quer ver
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
								<table id="funcionarios" class="table table-striped table-datatable">
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
								</table> 
							</div>
						</div>
						<div class="heatmap">  estava com display none
							 <div class="heatmap"><h5 class="title-4" style="text-align: center;">Relatório não foi gerado no dia de hoje, portanto não é possível mostrar estatísticas.</h5></div>
							<div class="heatmap" id="heatmap"></div>
						</div>
						<div class="col-md-12">
							<canvas id="myChart" width="400" height="400"></canvas>
							<canvas id="ordens_funcionario" name="step-size" width="400" height="400"></canvas>
						</div>
					</div>
				</div>
			</div>
			END FUNCIONÁRIOS -->

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

<script src= "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-annotation/0.5.7/chartjs-plugin-annotation.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-annotation/0.5.7/chartjs-plugin-annotation.min.js"></script>
<script src="https://cdn.plot.ly/plotly-1.2.0.min.js"></script>

<script type="text/javascript">
	var xValues = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];

	var yValues = ['Pietro'];

	var zValues = [
	[0.00, 0.00, 0.75, 0.75, 0.00, 0.25, 0.25, 0.00, 0.00, 0.00, 0.00],
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