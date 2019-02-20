var posicao_selecionada = null;


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


remove_image = () => {
  $('#img-input').attr('src', '');
  removeUpload();
}

$(document).on('click','.btn-attr-pessoa_pk',function(){
  $('#pessoa_pk').val(superusuarios[$(this).val()]['pessoa_pk']);
  posicao_selecionada = $(this).val();
});

$('#btn-deactivate').click(() => {
  var data = 
  {
    'pessoa_pk': $('#pessoa_pk').val(),
    'senha': $('#pass-modal-desativar').val()
  }

  btn_load($('#btn-deactivate'));

  $.post(base_url+'/superusuario/deactivate',data).done(function (response) {

    btn_ativar($('#btn-deactivate'));

    if (response.code == 200){
      alerts('success', 'Sucesso!', 'Superusuário desativado com sucesso');
      superusuarios[posicao_selecionada]['usuario_status'] = 0;
      $('#d-superusuario').modal('hide');
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

  $.post(base_url+'/superusuario/activate',data).done(function (response) {

    btn_ativar($('#btn-activate'));


    if (response.code == 200){
      alerts('success', 'Sucesso!', 'Superusuário ativado com sucesso');
      superusuarios[posicao_selecionada]['usuario_status'] = 1;
      $('#a-superusuario').modal('hide');
    }else{
      alerts('failed', 'Erro!', 'Houve um erro ao ativar.');
    }
    update_table();
  });
});

update_table = () => {
  table.clear().draw();

  switch ($('#filter-ativo').val()) {

    case "todos":
      $.each(superusuarios, function (i, user) {
        if(user.usuario_status == 1){
        table.row.add([
          user.pessoa_nome,
          user.contato_email,
          '<div class="btn-group"><button type="button" class="btn btn-sm btn-primary reset_multistep btn-editar-super" data-toggle="modal" value="' + (i) + '" data-target="#ce_superusuario">Editar</button><button type="button" class="btn btn-sm btn-danger btn-attr-pessoa_pk" data-toggle="modal" value="' + (i) + '" data-target="#d-superusuario">Desativar</button></div>'
        ]).draw(false);
      }else{
        table.row.add([
          user.pessoa_nome,
          user.contato_email,
          '<div class="btn-group"><button type="button" class="btn btn-sm btn-success btn-attr-pessoa_pk" data-toggle="modal" value="' + (i) + '" data-target="#a-superusuario">Ativar</button></div>'
        ]).draw(false);
      }
      });
      break;
    case "ativos":
      table.clear().draw();
      $.each(superusuarios, function (i, user) {
        if (user.usuario_status == 1) {
          table.row.add([
            user.pessoa_nome,
            user.contato_email,
            '<div class="btn-group"><button type="button" class="btn btn-sm btn-primary reset_multistep btn-editar-super" data-toggle="modal" value="' + (i) + '" data-target="#ce_superusuario">Editar</button><button type="button" class="btn btn-sm btn-attr-pessoa_pk btn-danger" data-toggle="modal" value="' + (i) + '" data-target="#d-superusuario">Desativar</button></div>'
          ]).draw(false);
        }
      });
      break;
    case "desativados":
    table.clear().draw();
    $.each(superusuarios, function (i, user) {
      if (user.usuario_status == 0) {
        table.row.add([
          user.pessoa_nome,
          user.contato_email,
          '<div class="btn-group">' +
            '<button type="button" class="btn btn-sm btn-success btn-attr-pessoa_pk" data-toggle="modal" value="' + (i) + '" data-target="#a-superusuario">Ativar</button></div>'
        ]).draw(false);
      }
    });
      break;
  }
}


$( ".press_enter" ).on( "keydown", function( event ) {
  if(event.which == 13){
    send_data();
  }
});


send_data = () => {
  try {
    $('#img-input').cropper('getCroppedCanvas').toBlob((blob) => { 
      this.send(blob); 
    });
  } catch (err) {
      this.send(null); 
  }

  
};



send = (imagem) => {
  //pre_loader_show();

  console.log($('#pass-modal-edit').val());
  btn_load($('.submit'));

  const formData = new FormData();
  formData.append('pessoa_nome', $('#nome-input').val());
  formData.append('pessoa_cpf', $('#cpf-input').val());
  formData.append('contato_email', $('#email-input').val());
  formData.append('contato_tel', $('#telefone-input').val());
  formData.append('contato_cel', $('#celular-input').val());
  formData.append('senha', $('#pass-modal-edit').val());
  formData.append('img_su', imagem);

  var URL = ($('#pessoa_pk').val() == "") ? base_url + '/superusuario/insert' : base_url + '/superusuario/update';

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
      
      btn_ativar($('.submit'));

      if (response.code == 400) {
        show_errors(response);
        alerts('failed', 'Erro!', 'O formulário apresenta algum(ns) erro(s)');
        //pre_loader_hide();
      }
      else if(response.code == 401){
        show_errors(response);
        alerts('failed', 'Erro!', 'Senha incorreta.');
      }
      else if(response.code == 200){
        superusuario =
          {
            'pessoa_fk': ($('#pessoa_pk').val() == "") ? response.data.pessoa_fk : $('#pessoa_pk').val(),
            'pessoa_nome': $('#nome-input').val(),
            'pessoa_cpf': $('#cpf-input').val(),
            'contato_email': $('#email-input').val(),
            'contato_tel': $('#telefone-input').val(),
            'contato_cel': $('#celular-input').val(),
            'img_su': imagem,
            'pessoa_pk': ($('#pessoa_pk').val() == "") ? response.data.pessoa_fk : $('#pessoa_pk').val()
          }
        if ($('#pessoa_pk').val() == "") { //verifica se é um insert
          superusuarios.push(superusuario);
          table.row.add([
            superusuario.pessoa_nome,
            superusuario.contato_email,
            '<div class="btn-group"><button type="button" class="btn btn-sm btn-primary reset_multistep btn-editar-super btn-attr-pessoa_pk" data-toggle="modal" value="' + (superusuarios.length - 1) + '" data-target="#ce_superusuario">Editar</button><button type="button" class="btn btn-sm btn-danger btn-attr-pessoa_pk" data-toggle="modal" value="' + (superusuarios.length - 1) + '" data-target="#d-superusuario">Desativar</button></div>'
          ]).draw(false);
          alerts('success', 'Sucesso!', 'Superusuário inserido com sucesso');
        } else {

          for (var i in superusuarios) {
            if (superusuarios[i]['pessoa_pk'] == $('#pessoa_pk').val())
              break;
          }
          superusuarios[i] = (superusuario);
          table.row(i).data([
            superusuario.pessoa_nome,
            superusuario.contato_email,
            '<div class="btn-group"><button type="button" class="btn btn-sm btn-primary reset_multistep btn-editar-super btn-attr-pessoa_pk" data-toggle="modal" value="' + (i) + '" data-target="#ce_superusuario">Editar</button><button type="button" class="btn btn-sm btn-danger btn-attr-pessoa_pk" data-toggle="modal" value="' + (i) + '" data-target="#d-superusuario">Desativar</button></div>'
          ]).draw();
          alerts('success', 'Sucesso!', 'Superusuário modificado com sucesso');
        }
      }
      pre_loader_hide();
      remove_image();
      $('#ce_superusuario').modal('hide');
    },
    error: function (response) {
      alerts('failed', response.message, response.data);
    }
  });
}



/* Quando clica em novo superusuário, muda o título do modal e 
** o exibe 
*/

$('#novo_sup_btn').on('click', function () {
  $('#titulo').html("Novo Superusuário");
  $('#ce_superusuario').modal('show');
  $('#pessoa_pk').val("");
});

/* Quando clica em editar superusuário, muda o titulo do modal
** e pegar os dados do superusuário a ser alterado. Pega os
** dados via requisição JQuery para completar o modal com os
** dados atuais.
*/

$(document).on('click', '.btn-editar-super', function (event) {
  $('#titulo').html("Editar Superusuário");
  $('#pessoa_pk').val(superusuarios[posicao_selecionada]['pessoa_pk']);
  $('#nome-input').val(superusuarios[posicao_selecionada]['pessoa_nome']);
  $('#cpf-input').val(superusuarios[posicao_selecionada]['pessoa_cpf']);
  $('#email-input').val(superusuarios[posicao_selecionada]['contato_email']);
  $('#telefone-input').val(superusuarios[posicao_selecionada]['contato_tel']);
  $('#celular-input').val(superusuarios[posicao_selecionada]['contato_cel']);
  $('#ce_superusuario').modal('show');
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
  $('#senha-input').val("12345678");
}
