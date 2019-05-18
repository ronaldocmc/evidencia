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

$("#btn-edit").click(function()
{
    if ($(this).val())
    {
        $('#confirm_edit').modal("show");
    }
    else{
        var i = 0;
        $(this).find('select, textarea, input').each(function(){
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

        if(i > 0)
        {
            alerts('warning','Atenção','Preencha os campos vermelho');
            return;
        }
        var data = 
        {
            'organizacao_nome': $('#nome-input').val(),
            'organizacao_cnpj': $('#cnpj-input').val(),
            'localizacao_rua': $('#logradouro-input').val(),
            'localizacao_num': $('#numero-input').val(),
            'localizacao_bairro': $('#bairro-input').val(),
            'localizacao_municipio': $('#localizacao_municipio').val()
        }

        btn_load($('#btn-edit'));

        pre_loader_show();

        $.post(base_url+'/organizacao/save',data).done(function (response) 
        {

            btn_ativar($('#btn-edit'));

            if (response.code == 400)
            {
                for (var i in response.data)
                {
                    $('[name='+i+']').parent().children('.form-text').text(response.data[i]);
                    $('[name='+i+']').addClass('is-invalid');
                }
                alerts('failed','Erro!','O formulário apresenta algum(ns) erro(s) de validação');
            }
            else if (response.code == 401)
            {
               alerts('failed','Erro!','Senha informada incorreta');
           }
           else if (response.code == 500 || response.code == 501 || response.code == 502)
           {
               alerts('failed','Erro!','Ocorreu alguma falha interna no servidor. Tente novamente mais tarde');
           }
           else if(response.code == 200)
           {

            alerts('success','Sucesso!','Aguarde um momento, pois iremos enviá-lo à página inicial.');

            window.location.replace(base_url+'/dashboard/funcionario_administrador');
        }

        pre_loader_hide();

    }, "json");
    }
});


$("#btn-confirmar-edicao").click(function()
{
    var i = 0;
    $(this).find('select, textarea, input').each(function(){
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

    if(i > 0)
    {
        alerts('warning','Atenção','Preencha os campos vermelho');
        return;
    }
    var data = 
    {
        'organizacao_nome': $('#nome-input').val(),
        'organizacao_cnpj': $('#cnpj-input').val(),
        'logradouro_nome': $('#logradouro-input').val(),
        'local_num': $('#numero-input').val(),
        'local_complemento': $('#complemento-input').val(),
        'estado_pk' :$('#uf-input :selected').text(),
        'bairro': $('#bairro-input').val(),
        'municipio_pk': $('#cidade-input').val(),
        'senha': $('#pass-modal-editar').val(),
        'municipio_nome': $('#cidade-input :selected').text()
    }

    
    btn_load($('#btn-confirmar-edicao'));

    $.post(base_url+'/organizacao/save',data).done(function (response) 
    {

        btn_ativar($('#btn-confirmar-edicao'));

        if (response.code == 400)
        {
            for (var i in response.data)
            {
                $('[name='+i+']').parent().children('.form-text').text(response.data[i]);
                $('[name='+i+']').addClass('is-invalid');
            }
            alerts('failed','Erro!','O formulário apresenta algum(ns) erro(s) de validação');
        }
        else if (response.code == 401)
        {
           alerts('failed','Erro!','Senha informada incorreta');
       }
       else if (response.code == 500 || response.code == 501 || response.code == 502)
       {
           alerts('failed','Erro!','Ocorreu alguma falha interna no servidor. Tente novamente mais tarde');
       }
       else
       {
        window.location.replace(base_url+'/dashboard/funcionario_administrador');
    }
}, "json");
});
