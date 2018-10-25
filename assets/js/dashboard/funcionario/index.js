var posicao_selecionada = null;

var botao = null;

$(document).on('click','.btn_novo',function() {
  botao = ".submit";
  $('.modal-title').html("Novo Funcionário");  
});

$( ".press_enter" ).on( "keydown", function( event ) {
  if(event.which == 13){
    $(botao).trigger("click");
  }
});


jQuery(document).ready(function($) {
  // if($('#departamento-input').children('option').val()==0 || $('#funcao-input').children('option').val()==0 )
  // {
  //   $('.new').prop('disabled',true);
  //   $('.new').addClass('btn disabled');
  //   if($('#departamento-input').children('option').val()==0)
  //   {
  //     alerts('failed','Não há departamentos cadastrados','Cadastre departamentos primeiro e depois registre funcionários');
  //   }
  //   else
  //   {
  //      alerts('failed','Não há funções cadastrados','Cadastre funções primeiro e depois registre funcionários');
  //   }
  // }
});

change_funcao = () => {
  if ($('#funcao-input').val()==3)
  {
    $('#setor-input').removeAttr('disabled');
  }
  else
  {
     $('#setor-input').prop('disabled','disabled')
  }
}


$('#funcao-input').change(function(event) {
  change_funcao();
});

remove_image = () => {
  $('#img-input').attr('src', '');
  removeUpload();
}

$(document).on('click','.btn-attr-pessoa_pk',function(){
  $('#pessoa_pk').val(funcionarios[$(this).val()]['pessoa_pk']);
  posicao_selecionada = $(this).val();
  $('#opcao-editar').val('true');
});


function btn_load(button_submit){
  button_submit.attr('disabled', 'disabled');
  button_submit.css('cursor', 'default');
  button_submit.find('i').removeClass();
  button_submit.find('i').addClass('fa fa-refresh fa-spin');
}


function btn_ativar(button_submit){
  button_submit.removeAttr('disabled');
  button_submit.css('cursor', 'pointer');
  button_submit.find('i').removeClass();
  button_submit.find('i').addClass('fa fa-dot-circle-o');
}

$(document).on('click','.btn-desativar',function(){
  botao = "#btn-deactivate";
  $('.modal-title').html("Desativar Funcionário"); 
});

$(document).on('click','.btn-reativar',function(){
  botao = "#btn-activate";
  $('.modal-title').html("Reativar Funcionário"); 
});

$('#btn-deactivate').click(() => {
  var data = 
  {
    'pessoa_pk': $('#pessoa_pk').val(),
    'senha': $('#pass-modal-desativar').val()
  }

    btn_load($('#btn-deactivate'));
    $('#pass-modal-desativar').val('')

  $.post(base_url+'/funcionario/deactivate',data).done(function (response) {

    btn_ativar($('#btn-deactivate'));

    if (response.code == 200){
      alerts('success', 'Sucesso!', 'Funcionário desativado com sucesso');
      funcionarios[posicao_selecionada]['funcionario_status'] = 0;
      $('#d_funcionario').modal('hide');
      pre_loader_hide();
    }else{
      alerts('failed', 'Erro!', 'Houve um erro ao desativar.');
    }
    update_table();
  });
});

$('#btn-activate').click(() => {
  var data = 
  {
    'pessoa_pk': $('#pessoa_pk').val(),
    'senha': $('#pass-modal-ativar').val()
  }

  btn_load($('#btn-activate'));

  $('#pass-modal-ativar').val('')

  $.post(base_url+'/funcionario/activate',data).done(function (response) {

    btn_ativar($('#btn-activate'));

    if (response.code == 200){
      alerts('success', 'Sucesso!', 'Funcinário ativado com sucesso');
      funcionarios[posicao_selecionada]['funcionario_status'] = 1;
      $('#a_funcionario').modal('hide');
    }else{
      alerts('failed', 'Erro!', 'Houve um erro ao ativar.');
    }
    update_table();
  });
});

$('#filter-ativo').on('change',function() {
  update_table();
});

update_table = () => {
  table.clear().draw();

  switch ($('#filter-ativo').val()) {
    case "todos":
      $.each(funcionarios, function (i, func) {
        if(func.funcionario_status == 1){
          table.row.add([
            func.pessoa_nome,
            func.contato_email,
            func.funcao_nome,
            '<div class="btn-group">'+
              '<button type="button" class="btn btn-sm btn-primary reset_multistep btn-editar btn-attr-pessoa_pk" data-toggle="modal" value="' + (i) + '" data-target="#ce_funcionario" title="Editar">'+
                  '<div class="d-none d-sm-block">'+
                    '<i class="fas fa-edit fa-fw"></i>'+
                  '</div>'+
              '</button>'+
              '<button type="button" class="btn btn-sm btn-danger btn-attr-pessoa_pk" data-toggle="modal" value="' + (i) + '" data-target="#d_funcionario" title="Desativar">'+
                  '<div class="d-none d-sm-block">'+
                    '<i class="fas fa-times fa-fw"></i>'+
                  '</div>'+
              '</button>'+
            '</div>'
          ]).draw(false);
        }else{
          table.row.add([
            func.pessoa_nome,
            func.contato_email,
            func.funcao_nome,
            '<div class="btn-group">'+
              '<button class="btn btn-sm btn-success btn-reativar btn-attr-pessoa_pk" value="'+(i)+'" data-toggle="modal" data-target="#a_funcionario" title="Reativar">'+
                  '<div class="d-none d-sm-block">'+
                    '<i class="fas fa-power-off fa-fw"></i>'+
                  '</div>'+
              '</button>'+
            '</div>'
            ]).draw(false);
      }
      });
      break;
    case "ativos":
      table.clear().draw();
      $.each(funcionarios, function (i, func) {
        if (func.funcionario_status == 1) {
          table.row.add([
            func.pessoa_nome,
            func.contato_email,
            func.funcao_nome,
            '<div class="btn-group">'+
              '<button type="button" class="btn btn-sm btn-primary reset_multistep btn-editar btn-attr-pessoa_pk" data-toggle="modal" value="' + (i) + '" data-target="#ce_funcionario" title="Editar">'+
                  '<div class="d-none d-sm-block">'+
                    '<i class="fas fa-edit fa-fw"></i>'+
                  '</div>'+
              '</button>'+
              '<button type="button" class="btn btn-sm btn-danger btn-attr-pessoa_pk" data-toggle="modal" value="' + (i) + '" data-target="#d_funcionario" title="Desativar">'+
                  '<div class="d-none d-sm-block">'+
                    '<i class="fas fa-times fa-fw"></i>'+
                  '</div>'+
              '</button>'+
            '</div>'
          ]).draw(false);
        }
      });
      break;
    case "desativados":
    table.clear().draw();
    $.each(funcionarios, function (i, func) {
      if (func.funcionario_status == 0) {
        table.row.add([
            func.pessoa_nome,
            func.contato_email,
            func.funcao_nome,
            '<div class="btn-group">'+
              '<button class="btn btn-sm btn-success btn-reativar btn-attr-pessoa_pk" value="'+(i)+'" data-toggle="modal" data-target="#a_funcionario" title="Reativar">'+
                  '<div class="d-none d-sm-block">'+
                    '<i class="fas fa-power-off fa-fw"></i>'+
                  '</div>'+
              '</button>'+
            '</div>'
            ]).draw(false);
      }
    });
      break;
  }
}


function send(){
  try {
    $('#img-input').cropper('getCroppedCanvas').toBlob((blob) => { 
      this.send(blob); 
    });
  } catch (err) {
      this.send(null); 
  }
}

function send_data(){
  try {
    $('#img-input').cropper('getCroppedCanvas').toBlob((blob) => { 
      this.send(blob); 
    });
  } catch (err) {
      this.send(null); 
  }
}


$('.submit').on('click',() => {
  send_data();
});



send = (imagem) => {
  //pre_loader_show();
  btn_load($('#pula-para-confirmacao'));
  btn_load($('.submit'));

  const formData = new FormData();
  formData.append('pessoa_nome', $('#nome-input').val());
  formData.append('pessoa_cpf', $('#cpf-input').val());
  formData.append('contato_email', $('#email-input').val());
  formData.append('contato_tel', $('#telefone-input').val());
  formData.append('contato_cel', $('#celular-input').val());
  formData.append('funcao_fk', $('#funcao-input').val());
  formData.append('logradouro_nome', $('#logradouro-input').val());
  formData.append('local_num', $('#numero-input').val());
  formData.append('local_complemento', $('#complemento-input').val());
  formData.append('estado_pk' ,$('#uf-input :selected').text());
  formData.append('bairro', $('#bairro-input').val());
  formData.append('setor_fk',$('#setor-input').val());
  formData.append('municipio_pk', $('#cidade-input').val());
  formData.append('municipio_nome',$('#cidade-input :selected').text());
  formData.append('departamento_fk', $('#departamento-input').val());
  formData.append('senha', $('#pass-modal-edit').val());
  formData.append('img', imagem);

  var URL = ($('#pessoa_pk').val() == "") ? base_url + '/funcionario/insert' : base_url + '/funcionario/update';

  if ($('#pessoa_pk').val() != "") {
    formData.append('pessoa_pk', $('#pessoa_pk').val());
  }

  $.ajax({
    url: URL,
    method: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      btn_ativar($('#pula-para-confirmacao'));
      btn_ativar($('.submit'));

      if (response.code !== 200) {
        show_errors(response);
        alerts('failed', 'Erro!', 'O formulário apresenta algum(ns) erro(s)');
        //pre_loader_hide();
      }
      else {
        funcionario =
          {
            'pessoa_fk': ($('#pessoa_pk').val() == "") ? response.data.pessoa_fk : $('#pessoa_pk').val(),
            'pessoa_nome': $('#nome-input').val(),
            'pessoa_cpf': $('#cpf-input').val(),
            'contato_email': $('#email-input').val(),
            'contato_tel': $('#telefone-input').val(),
            'contato_cel': $('#celular-input').val(),
            'logradouro_nome': $('#logradouro-input').val(),
            'local_num': $('#numero-input').val(),
            'local_complemento': $('#complemento-input').val(),
            'estado_pk': $('#uf-input :selected').text(),
            'municipio_pk': $('#cidade-input').val(),
            'municipio_nome': $('#cidade-input :selected').text(),
            'bairro_nome':$('#bairro-input').val(),
            'funcao_fk' : $('#funcao-input').val(),
            'funcao_nome' : funcoes[$('#funcao-input').val()],
            'departamento_fk' : $('#departamento-input').val(),
            'setor_fk': $('#setor-input').val(),
            'funcionario_status' : 1,
            'pessoa_pk': ($('#pessoa_pk').val() == "") ? response.data.pessoa_fk : $('#pessoa_pk').val()
          }
        if ($('#pessoa_pk').val() == "") { //verifica se é um insert
          funcionarios.push(funcionario);
          alerts('success', 'Sucesso!', 'Funcionário inserido com sucesso');
          update_table();
        } else {
          funcionarios[posicao_selecionada] = funcionario
          update_table();
          alerts('success', 'Sucesso!', 'Funcionário modificado com sucesso');
        }
        $('#ce_funcionario').modal('hide');
      }
      //pre_loader_hide();
      remove_image();

    },
    error: function (response) {
      alerts('failed', response.message, response.data);
    }
  });
}



/* Quando clica em novo funcionário, muda o título do modal e 
** o exibe 
*/

$('.new').on('click', function () {
  $('#titulo').html("Novo Funcionário");
  $('#pessoa_pk').val("");
  $('#opcao-editar').val('false');
});

/* Quando clica em editar funcionários, muda o titulo do modal
** e pegar os dados do funcionários a ser alterado. Pega os
** dados via requisição JQuery para completar o modal com os
** dados atuais.
*/

$(document).on('click', '.btn-editar', function (event) {
  $('#titulo').html("Editar Funcionário");
  botao = ".submit";
  if (funcionarios[posicao_selecionada].setor_fk != null) {
    for (var i = 0; i < funcionarios[posicao_selecionada].setor_fk.length; i++) {
      $('#setor-input option[value=' + funcionarios[posicao_selecionada].setor_fk[i] + ']').prop('selected', true);
    }  
  }
  else {
    $('#setor-input').val([]);
  }
  $('#pessoa_pk').val(funcionarios[posicao_selecionada]['pessoa_pk']);
  $('#nome-input').val(funcionarios[posicao_selecionada]['pessoa_nome']);
  $('#cpf-input').val(funcionarios[posicao_selecionada]['pessoa_cpf']);
  $('#email-input').val(funcionarios[posicao_selecionada]['contato_email']);
  $('#telefone-input').val(funcionarios[posicao_selecionada]['contato_tel']);
  $('#celular-input').val(funcionarios[posicao_selecionada]['contato_cel']);
  $('#funcao-input').val(funcionarios[posicao_selecionada]['funcao_pk']);
  $('#departamento-input').val(funcionarios[posicao_selecionada]['departamento_fk']);
  $('#logradouro-input').val(funcionarios[posicao_selecionada]['logradouro_nome'].toLowerCase().replace(/\b\w/g, l => l.toUpperCase()));
  $('#numero-input').val(funcionarios[posicao_selecionada]['local_num']);
  $('#complemento-input').val(funcionarios[posicao_selecionada]['local_complemento']);
  $('#bairro-input').val(funcionarios[posicao_selecionada]['bairro_nome'].toLowerCase().replace(/\b\w/g, l => l.toUpperCase()));


  if ($("#uf-input :selected").text() != funcionarios[$(this).val()]["estado_pk"])
  {
    $("#uf-input option").filter(function() {
        return this.text == funcionarios[id_org]["estado_pk"]; 
    }).attr('selected', true);

    change_uf($("#uf-input").val(),$("#uf-input option:selected").text(),funcionarios[$(this).val()]["municipio_pk"]);
  }
  else
  {
    $("#cidade-input").val(funcionarios[$(this).val()]["municipio_pk"]);
  } 
  change_funcao();
});




// EXTRA

function randomiza(n) {
  var ranNum = Math.round(Math.random()*n);
  return ranNum;
}

function mod(dividendo,divisor) {
  return Math.round(dividendo - (Math.floor(dividendo/divisor)*divisor));
}

function gerarCPF() {
  comPontos = true; // TRUE para ativar e FALSE para desativar a pontuação.
  
  var n = 9;
  var n1 = randomiza(n);
  var n2 = randomiza(n);
  var n3 = randomiza(n);
  var n4 = randomiza(n);
  var n5 = randomiza(n);
  var n6 = randomiza(n);
  var n7 = randomiza(n);
  var n8 = randomiza(n);
  var n9 = randomiza(n);
  var d1 = n9*2+n8*3+n7*4+n6*5+n5*6+n4*7+n3*8+n2*9+n1*10;
  d1 = 11 - ( mod(d1,11) );
  if (d1>=10) d1 = 0;
  var d2 = d1*2+n9*3+n8*4+n7*5+n6*6+n5*7+n4*8+n3*9+n2*10+n1*11;
  d2 = 11 - ( mod(d2,11) );
  if (d2>=10) d2 = 0;
  retorno = '';
  if (comPontos) cpf = ''+n1+n2+n3+'.'+n4+n5+n6+'.'+n7+n8+n9+'-'+d1+d2;
  else cpf = ''+n1+n2+n3+n4+n5+n6+n7+n8+n9+d1+d2;

  $('#cpf-input').val(cpf);
  $('#nome-input').val("Pessoa Teste " + n1 + n2 + n3);
  $('#email-input').val("pietro_cheetos@hotmail.com");
}
