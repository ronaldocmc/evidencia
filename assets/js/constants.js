var host = window.location.host;

if(host == 'localhost'){
	var base_url = window.location.protocol + "//" + host + "/" + window.location.pathname.split('/')[1];
} else{
	var base_url = window.location.protocol + "//" + host + "/";
}

const graph_colors = ["#e6194b","#3cb44b","#ffe119","#0082c8","#f58231","#911eb4","#46f0f0","#f032e6","#d2f53c","#fabebe","#008080","#e6beff","#aa6e28","#fffac8","#800000","#aaffc3","#808000","#ffd8b1","#000080","#808080"];