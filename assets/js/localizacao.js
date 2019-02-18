// change_uf = function(UF_cod, UF_sigla,cidade_selected){
//     $('#cidade-input').addClass('loading');
//     $.get(base_url+"/localizacao/get_municipios/"+UF_sigla , function(data) {
//         $('#cidade-input').removeClass('loading');
//         $('#cidade-input').html('');
//         for (var i in data.data)
//         {
//             if(data.data[i]['municipio_pk']==cidade_selected || data.data[i]['municipio_nome'] == cidade_selected)
//             {
//                 $('#cidade-input').append($('<option>',{
//                     value: data.data[i]['municipio_pk'],
//                     text: data.data[i]['municipio_nome'],
//                     selected: true
//                 }));
//             }
//             else
//             {
//                 $('#cidade-input').append($('<option>',{
//                     value: data.data[i]['municipio_pk'],
//                     text: data.data[i]['municipio_nome']
//                 }));
//             }
//         }
//         try
//         {
//             $.get('https://servicodados.ibge.gov.br/api/v1/localidades/estados/'+UF_cod+'/municipios/', function(cidades) {
//                     console.log(cidade_selected);
//                 $('#cidade-input').removeClass('loading');
//                 for (var i = 0; i < cidades.length; i++) {
//                     $("#cidade-input option:contains('"+cidades[i]['nome']+"')").remove();
//                     if(cidades[i]['id']==cidade_selected || cidades[i]['nome'] == cidade_selected)
//                         $('#cidade-input').append($('<option>',{
//                             value: cidades[i]['id'],
//                             text: cidades[i]['nome'],
//                             selected: true
//                         }));
//                     else
//                         $('#cidade-input').append($('<option>',{
//                             value: cidades[i]['id'],
//                             text: cidades[i]['nome']
//                         }));
//                 } 
//             });
//         }
//         catch(error){console.log(error)}
//     });
// }


// $("#uf-input").change(function () {
//     $('#cidade-input').addClass('loading');
//     $.get(base_url+"/localizacao/get_municipios/"+$('#uf-input option:selected').text() , function(data) {
//         $('#cidade-input').removeClass('loading');
//         $('#cidade-input').html('');
//         for (var i in data.data)
//         {
//             if(data.data[i]['municipio_pk']==3541406)
//             {
//                 $('#cidade-input').append($('<option>',{
//                     value: data.data[i]['municipio_pk'],
//                     text: data.data[i]['municipio_nome'],
//                     selected: true
//                 }));
//             }
//             else
//             {
//                 $('#cidade-input').append($('<option>',{
//                     value: data.data[i]['municipio_pk'],
//                     text: data.data[i]['municipio_nome']
//                 }));
//             }
//         }
//         try
//         {
//             $.get('https://servicodados.ibge.gov.br/api/v1/localidades/estados/'+$('#uf-input option:selected').val()+'/municipios/', function(cidades) {
//                 for (var i = 0; i < cidades.length; i++) {
//                     $("#cidade-input option:contains('"+cidades[i]['nome']+"')").remove();                     
//                     if(cidades[i]['id']==3541406)
//                         $('#cidade-input').append($('<option>',{
//                             value: cidades[i]['id'],
//                             text: cidades[i]['nome'],
//                             selected: true
//                         }));
//                     else
//                         $('#cidade-input').append($('<option>',{
//                             value: cidades[i]['id'],
//                             text: cidades[i]['nome']
//                         }));
//                 } 
//             });
//         }
//         catch(error){console.log(error)}
//     });
// });



// jQuery(document).ready(function($) {
//     $.get(base_url+"/localizacao/get_estados", function(data) {
//         $('#uf-input').removeClass('loading');
//         var uf = $('#uf-input').data('value')?$('#uf-input').data('value'):'SP';
//         for (var i in data.data) {
//             if (data.data[i]['estado_pk'] == uf)
//             {
//                 $('#uf-input').append($('<option>',{
//                     value: data.data[i]['estado_pk'],
//                     text: data.data[i]['estado_pk'],
//                     selected: true
//                 }));
//             }
//             else
//             {
//                 $('#uf-input').append($('<option>',{
//                     value: data.data[i]['estado_pk'],
//                     text: data.data[i]['estado_pk']
//                 }));
//             }
//         }
//         $.get(base_url+"/localizacao/get_municipios/"+$('#uf-input option:selected').text() , function(data) {
//             $('#cidade-input').removeClass('loading');
//             var cidade = $('#cidade-input').data('value')?$('#cidade-input').data('value'):3541406;
//             for (var i in data.data)
//             {
//                 if(data.data[i]['municipio_pk']==cidade)
//                 {
//                     $('#cidade-input').append($('<option>',{
//                         value: data.data[i]['municipio_pk'],
//                         text: data.data[i]['municipio_nome'],
//                         selected: true
//                     }));
//                 }
//                 else
//                 {
//                     $('#cidade-input').append($('<option>',{
//                         value: data.data[i]['municipio_pk'],
//                         text: data.data[i]['municipio_nome']
//                     }));
//                 }
//             }
//             try{
//                 $.get('https://servicodados.ibge.gov.br/api/v1/localidades/estados/', function(data) {
//                     for (var i = 0; i < data.length; i++){
//                         $("#uf-input option:contains('"+data[i]['sigla']+"')").remove();
//                         if (data[i]['sigla'] == 'SP'){
//                             $('#uf-input').append($('<option>',{
//                                 value: data[i]['sigla'],
//                                 text: data[i]['sigla'],
//                                 selected: true
//                             }));
//                             try
//                             {
//                                 $.get('https://servicodados.ibge.gov.br/api/v1/localidades/estados/'+data[i]['id']+'/municipios/', function(cidades) {
//                                     for (var i = 0; i < cidades.length; i++) {
//                                         $("#cidade-input option:contains('"+cidades[i]['nome']+"')").remove();
//                                         if(cidades[i]['id']==3541406)
//                                             $('#cidade-input').append($('<option>',{
//                                                 value: cidades[i]['id'],
//                                                 text: cidades[i]['nome'],
//                                                 selected: true
//                                             }));
//                                         else
//                                             $('#cidade-input').append($('<option>',{
//                                                 value: cidades[i]['id'],
//                                                 text: cidades[i]['nome']
//                                             }));
//                                     } 
//                                 });
//                             } catch(error){console.log(error)}
//                         }
//                         else
//                             $('#uf-input').append($('<option>',{
//                                 value: data[i]['sigla'],
//                                 text: data[i]['sigla']
//                             }));
                        
//                     }
//                 });
//             } catch(error){console.log(error)}
//         });    
//     });
    
// });     