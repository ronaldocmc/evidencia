
$(document).on('focus',"li", function() {
        $(this).children('a.dropdown-item').addClass('active');
    }).on('blur',"li", function() {
        $(this).children('a.dropdown-item').removeClass('active');
    });


$(document).on('keyup',"a, .dropdown-item, .active",function(event){
	if (event.keyCode==40 || event.keyCode==9){
		$(this).parent('li').next('li').children('a').trigger('focus');
	}
	else if (event.keyCode==38)
	{
		$(this).parent('li').prev('li').children('a').trigger('focus');
	}
	else if (event.keyCode==13)
	{
		$($(this).closest('.dropdown-menu').data("return")).val($(this).attr("value"));
		$($(this).closest('.dropdown-menu').data("next")).trigger('focus');
		$(this).closest('.dropdown-menu').parent().find(".input-dropdown").val($(this).text());
		$(this).closest('.dropdown-menu').parent().find(".helper-dropdown").show();
		$('.dropdown-menu').removeClass('show');
	}
});

$(document).on('click',"a, .dropdown-item, .active",function(event){
	$($(this).closest('.dropdown-menu').data("return")).val($(this).attr("value"));
	$($(this).closest('.dropdown-menu').data("next")).trigger('focus');
	$(this).closest('.dropdown-menu').parent().find(".input-dropdown").val($(this).text());
	$(this).closest('.dropdown-menu').parent().find(".helper-dropdown").show();
	$('.dropdown-menu').removeClass('show');
});


var last_list = '';
function request(source, type, data, ret, index, value, list, match) {
	last_list = list;
	$.ajax({
		    	url: source,
		    	type: type,
		    	data: data,
		    	async: true
		    })
		    .done(function(data) {
		    	if (ret!=null)
		    	{
		    		data = data[ret];
		    	}
		    	for (var i in data)
		    	{
		    		ins_res = data[i][value].toLowerCase().replace(/\b\w/g, l => l.toUpperCase());
		    		if (list.find('li').length<5 && list.find('a:contains("'+ins_res+'")').length == 0 && data[i][value].toUpperCase().includes(match.toUpperCase()))
		    		{
		    			list.append('<li><a class="dropdown-item" value ="'+data[i][index]+'" href="#">'+ins_res+'</a></li>');
		    			
		    		}
		    	}
		    });
	
}


$('.form-control').on( "focusout",function(){
	if (!$(this).hasClass('input-dropdown'))
	{
		$('.dropdown-menu').removeClass('show');
    	$(".helper-dropdown").find('.helper-dropdown').show();	
    }
});


$( document ).ajaxStop(function() {
	  if (last_list!='' && last_list.find('li').length==0)
	  {
	  		last_list.removeClass('show');
	    	last_list.parent().find('.helper-dropdown').show();		
	  }
	  else if(last_list!=''){
	  		last_list.addClass('show');
			last_list.parent().find('.helper-dropdown').hide();
	  }
	  last_list='';
});

$(document).on('keyup',".input-dropdown",function(event){
    var nome = $('#logradouro-input').val();
    if (event.keyCode==40) //seta para baixo
    {
        $(this).parent().find("li a.dropdown-item:first").trigger('focus');
    }
    else if (event.keyCode==13) //enter
    {
    	$($(this).parent().find('.dropdown-menu').data("return")).val($(this).parent().find("li a.dropdown-item:first").attr("value"));
		$($(this).parent().find('.dropdown-menu').data("next")).trigger('focus');
		$(this).val($(this).parent().find("li a.dropdown-item:first").text());
		$(this).parent().find(".helper-dropdown").show();
		$(this).parent().find('.dropdown-menu').removeClass('show');	
    }
    else
    {
	    var src = $(this).data("src");
	    var params = $(this).data("params");
	    var indexs = $(this).data("index");
	    var values = $(this).data("value");
	    var actions = $(this).data("action");
	    var arrayrets = $(this).data("arrayret");
	    var itens = [];
	    var list = $(this).parent().find('.dropdown-menu');
		

	    list.html('');
	    for(var k in src)
	    {
		    var tratados = {};
		    if(actions[k] == 'post')
		    {
		    	var sources = src[k];
			    for (var i in params[k])
			    {
			    	if (params[k][i][2]=='val')
			    	{
			    		tratados[params[k][i][1]] = params[k][i][0]=="this"?$(this).val():$('#'+params[k][i][0]).val();
			    	}
			    	else if (params[k][i][2]=='text')
			    	{
			    		tratados[params[k][i][1]] = params[k][i][0]=="this"?$(this).text():$('#'+params[k][i][0]+" :selected").text();
			    	}
			    	else
			    	{
			    		tratados[params[k][i][1]] = params[k][i][0];
			    	}
			    }
			}
			else
			{
				cond = '';
				for (var i in params[k])
				{
					if (params[k][i][2]=='val')
			    	{
			    		cond += '/'+(params[k][i][0]=="this"?$(this).val():$('#'+params[k][i][0]).val());
			    	}
			    	else if (params[k][i][2]=='text')
			    	{
			    		cond += '/'+(params[k][i][0]=="this"?$(this).text():$('#'+params[k][i][0]+" :selected").text());
			    	}
			    	else cond += '/'+params[k][i][0];
			    }
			    sources = src[k] + cond;
			}
			request(sources,actions[k],tratados,arrayrets[k],indexs[k],values[k],list,$(this).val());
		}
	}
});
