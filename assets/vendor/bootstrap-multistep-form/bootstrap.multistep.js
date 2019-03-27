
//jQuery time
var current_fs, next_fs, previous_fs; //fieldsets
var left, opacity, scale; //fieldset properties which we will animate
var animating; //flag to prevent quick multi-click glitches
var editar = false;

$(document).ready(function() {
	distance = 100/$('.msform').children('.card-step').length +"%";
	$(".progressbar li").css({'width': distance });

	quantidade_de_cards = 0;
	
	$('ul.progressbar li').each( function (index, object){
		//object.prop('id', ''+index) ;
		object.id = index+1;
		quantidade_de_cards++;
	});	

	$('form .card-step').each( function (index, object){
		//object.prop('id', ''+index) ;
		object.id = index+1;
	});	

	
	editar = false;

});

//quando o usuário clicar em alterar um funcionário OU superusuário:
$(document).on('click','.btn-attr-pessoa_pk',function(){
   $('#opcao-editar').val('true'); //setamos que é uma edição de um funcionário
   $('#botao-finalizar').hide(); //escodemos o botão 

   $('.modal-footer .submit').show(); //mostramos o botão de "Salvar"
});

//quando o usuário clicar em editar em organização:
$(document).on('click', '.btn_editar', function(){
   $('#opcao-editar').val('true'); //setamos que é uma edição de um funcionário
   $('#botao-finalizar').hide(); //escodemos o botão 

   $('.modal-footer #pula-para-confirmacao').show(); //mostramos o botão de "Salvar"
});

//quando for um novo funcionário:
$('.new').on('click', function () {
  $('#opcao-editar').val('false'); //setamos que não é um editar
  // var html = '<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Fechar</button>';
  // $('.modal-footer').html(html);
  $('.modal-footer .submit').hide(); //escondemos o botão de "Salvar"
  $('.modal-footer #pula-para-confirmacao').hide(); //escondemos o botão de "Salvar"
  $('#botao-finalizar').show(); //mostramos o botão de finalizar no último step
});



$(".modal-multistep").on('show.bs.modal', function (e) {
	distance = 100/$(this).children('.card-step').length +"%";
	$(".progressbar li").css({'width': distance });

	


});

$(document).on('click','.reset_multistep',function(){
	$(".progressbar li").removeClass("active");
	$(".progressbar li").eq(0).addClass("active");
	$('.card-step').first().css({'display' : 'block' , 'opacity': '1' , 'position': 'relative' , 'transform': 'scale(1)'});	
	$('.card-step').not(":eq(0)").css({'display' : 'none'});	
	$('.msform input').val("");
	$('.msform textarea').val("");
	$('.msform input[type="checkbox"]').prop('checked',false);
	$('.msform .form-control').removeClass('is-invalid');
	$('.msform .form-control').removeClass('is-valid');
	$('.dropdown-menu').removeClass('show');
	$('.helper-dropdown').show();
});

$('.form-control').keyup(function () {
	if($(this)[0].checkValidity())
	{
		$(this).removeClass("is-invalid");
		$(this).removeClass("is-valid").addClass("is-valid");
	}
	else 
	{
		$(this).removeClass("is-valid");
		$(this).removeClass("is-invalid").addClass("is-invalid");
	}
});

$(document).on('click','.next',function(){
	var editar = $('#opcao-editar').val();
	
	current_fs = $(this).closest('.card-step');
	
	i = 0; 		
	current_fs.find('select, textarea, input').each(function(){
		if($(this)[0].checkValidity())
		{
			$(this).removeClass("is-invalid");
		}
		else 
		{
			i++;
			$(this).removeClass("is-invalid").addClass("is-invalid");
			$(this).next('.invalid-tooltip').html($(this)[0].validationMessage);
		}
	});
	if (i>0)
	{
		alerts('warning','Atenção','Preencha os campos vermelho');
	}
	else
	{
		if(animating) return false;
		animating = true;

		next_fs = $(this).closest('.card-step').next();
		
		if(editar)
		{
			$(".progressbar li").eq($(".card-step").index(current_fs)).removeClass("active");
		}

		//activate next step on progressbar using the index of next_fs
		$(".progressbar li").eq($(".card-step").index(next_fs)).addClass("active");
		

		//show the next fieldset
		next_fs.show(); 
		//hide the current fieldset with style
		current_fs.animate({opacity: 0}, {
			step: function(now, mx) {
				//as the opacity of current_fs reduces to 0 - stored in "now"
				//1. scale current_fs down to 80%
				scale = 1 - (1 - now) * 0.2;
				//2. bring next_fs from the right(50%)
				left = (now * 50)+"%";
				//3. increase opacity of next_fs to 1 as it moves in
				opacity = 1 - now;
				current_fs.css({
					'transform': 'scale('+scale+')',
					'position': 'absolute'
				});
				next_fs.css({'left': left, 'opacity': opacity , 'position': 'relative','transform': 'scale(1)'});
			}, 
			duration: 800, 
			complete: function(){
				current_fs.hide();
				animating = false;
			}, 
			//this comes from the custom easing plugin
			easing: 'easeInOutBack'
		});
	}
});

$(document).on('click','.previous',function(){
	var editar = $('#opcao-editar').val();
	
	if(animating) return false;
	animating = true;
	
	current_fs = $(this).closest('.card-step');
	previous_fs = $(this).closest('.card-step').prev();
	
	//de-activate current step on progressbar

	$(".progressbar li").eq($(".card-step").index(current_fs)).removeClass("active");

	if(editar){
		$(".progressbar li").eq($(".card-step").index(previous_fs)).addClass("active");
	}	
	//show the previous fieldset
	previous_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale previous_fs from 80% to 100%
			scale = 0.8 + (1 - now) * 0.2;
			//2. take current_fs to the right(50%) - from 0%
			left = ((1-now) * 50)+"%";
			//3. increase opacity of previous_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({'left': left});
			previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity
		});
		}, 
		duration: 800, 
		complete: function(){
			current_fs.hide();
			animating = false;
			previous_fs.css({'position': 'relative'});
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
	});
});


show_errors = function(response) {
	var k=0;
	for (var i in response.data){
		if (k==0){
			previous_fs =  $('[name='+i+']').closest('.card-step');
		}
		k++;
		$('[name='+i+']').parent().children('.form-text').text(response.data[i]);
		$('[name='+i+']').addClass('is-invalid');
	}

	current_fs = $('.card-step').last();
	if($('.card-step').index(previous_fs)!=-1 && ($('.card-step').index(previous_fs) != $('.card-step').index(current_fs)))
	{
		if(animating) return false;
		animating = true;
		
		
		
		//de-activate current step on progressbar
		$(".progressbar li").removeClass("active");
		index = $('.card-step').index(previous_fs);
		for (j=0; j<=index;j++){
			$(".progressbar li").eq(j).addClass("active");
		}
		
		
		//show the previous fieldset
		previous_fs.show(); 
		//hide the current fieldset with style
		current_fs.animate({opacity: 0}, {
			step: function(now, mx) {
				//as the opacity of current_fs reduces to 0 - stored in "now"
				//1. scale previous_fs from 80% to 100%
				scale = 0.8 + (1 - now) * 0.2;
				//2. take current_fs to the right(50%) - from 0%
				left = ((1-now) * 50)+"%";
				//3. increase opacity of previous_fs to 1 as it moves in
				opacity = 1 - now;
				current_fs.css({'left': left});
				previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity
			});
			}, 
			duration: 800, 
			complete: function(){
				current_fs.hide();
				animating = false;
				previous_fs.css({'position': 'relative'});
			}, 
			//this comes from the custom easing plugin
			easing: 'easeInOutBack'
		});
	}

}

$('#pula-para-confirmacao').click(function(){
	var i = 0;
	current_id = $('ul.progressbar li.active').attr('id');
	clicked_id = quantidade_de_cards; //é o id do card de confirmação
	let element_exists = document.getElementById("pass-modal-edit");
	console.log('element exists: '+element_exists);

	if(element_exists == null){
		is_superusuario = false;
	}else{
		is_superusuario = true;
	}
	
	if(current_id == quantidade_de_cards) // ou seja, ele está no card de confirmação:
	{ 
		//devemos salvar:
		send_data();
	}
	else //se ele não estiver no card de confirmação:
	{
		if(is_superusuario){

			alerts('warning','', 'Você deve confirmar a senha para salvar as alterações.');
		

			if(current_id != clicked_id){
				//estamos pegando o card do índice que estmaos e do card que o cara clicou
				$('form .card-step').each( function (index, object){
					
					if(current_id == object.id)
					{
						current_fs = $(object);
					}
				 	if(clicked_id == object.id)
					{
						next_fs = $(object);
					}

				});
		}

			//checando as validações dos campos do card atual:
			current_fs.find('select, textarea, input').each(function(){
			if($(this)[0].checkValidity())
			{
				$(this).removeClass("is-invalid");
			}
			else 
			{
				i++;
				$(this).removeClass("is-invalid").addClass("is-invalid");
				$(this).next('.invalid-tooltip').html($(this)[0].validationMessage);
			}
			});
			if (i>0)
			{
				alerts('warning','Atenção','Preencha os campos vermelho');
			}
			else //se não tiver erro:
			{
				if(animating) return false;
				animating = true;
				
				//activate next step on progressbar using the index of next_fs
				$(".progressbar li").eq($(".card-step").index(current_fs)).removeClass("active");
				$(".progressbar li").eq($(".card-step").index(next_fs)).addClass("active");
				
				//show the next fieldset
				next_fs.show(); 
				//hide the current fieldset with style
				current_fs.animate({opacity: 0}, {
					step: function(now, mx) {
						//as the opacity of current_fs reduces to 0 - stored in "now"
						//1. scale current_fs down to 80%
						scale = 1 - (1 - now) * 0.2;
						//2. bring next_fs from the right(50%)
						left = (now * 50)+"%";
						//3. increase opacity of next_fs to 1 as it moves in
						opacity = 1 - now;
						current_fs.css({
							'transform': 'scale('+scale+')',
							'position': 'absolute'
						});
						next_fs.css({'left': left, 'opacity': opacity , 'position': 'relative','transform': 'scale(1)'});
					}, 
					duration: 800, 
					complete: function(){
						current_fs.hide();
						animating = false;
					}, 
					//this comes from the custom easing plugin
					easing: 'easeInOutBack'
				});
			}
		}
	}//fecha o se ele não tiver no card de confirmação


});

$('ul.progressbar li').click(function(){
	//se for editar:
	editar = $('#opcao-editar').val();
	if(editar == "false") editar = false;
	if(editar == "true") editar = true;
	


	
	if(editar){
		console.log(editar);
		//1 - poder saltar pelos passos
		var i = 0;
		var current_id = $('ul.progressbar li.active').attr('id');
		var clicked_id = $(this).attr('id');
		console.log('current id:'+current_id + " clicked id : "+clicked_id);

		if(current_id != clicked_id){
				//estamos pegando o card do índice que estmaos e do card que o cara clicou
				$('form .card-step').each( function (index, object){
					
					if(current_id == object.id)
					{
						current_fs = $(object);
					}
				 	if(clicked_id == object.id)
					{
						next_fs = $(object);
					}

				});

				//checando as validações dos campos do card atual:
				current_fs.find('select, textarea, input').each(function(){
				if($(this)[0].checkValidity())
				{
					$(this).removeClass("is-invalid");
				}
				else 
				{
					i++;
					$(this).removeClass("is-invalid").addClass("is-invalid");
					$(this).next('.invalid-tooltip').html($(this)[0].validationMessage);
				}
				});
				if (i>0)
				{
					alerts('warning','Atenção','Preencha os campos vermelho');
				}
				else //se não tiver erro:
				{
					if(animating) return false;
					animating = true;
					
					//activate next step on progressbar using the index of next_fs
					$(".progressbar li").eq($(".card-step").index(current_fs)).removeClass("active");
					$(".progressbar li").eq($(".card-step").index(next_fs)).addClass("active");
					
					//show the next fieldset
					next_fs.show(); 
					//hide the current fieldset with style
					current_fs.animate({opacity: 0}, {
						step: function(now, mx) {
							//as the opacity of current_fs reduces to 0 - stored in "now"
							//1. scale current_fs down to 80%
							scale = 1 - (1 - now) * 0.2;
							//2. bring next_fs from the right(50%)
							left = (now * 50)+"%";
							//3. increase opacity of next_fs to 1 as it moves in
							opacity = 1 - now;
							current_fs.css({
								'transform': 'scale('+scale+')',
								'position': 'absolute'
							});
							next_fs.css({'left': left, 'opacity': opacity , 'position': 'relative','transform': 'scale(1)'});
						}, 
						duration: 800, 
						complete: function(){
							current_fs.hide();
							animating = false;
						}, 
						//this comes from the custom easing plugin
						easing: 'easeInOutBack'
					});
				}
			
		}



		//2 - botão concluir
		
	} //fechar editar
});

