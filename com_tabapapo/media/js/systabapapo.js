/**
 * @package Tabapapo Component for Joomla! 3.9
 * @version 0.8.5
 * @author Jonatas C. Ferreira
 * @copyright (C) 2021 Tabaoca.org
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

var conteudo = '';
var flood = 0;


function start_page() {

	var mydata = new FormData();
	var tk = top.document.getElementById('jform_tk').value;
	var url = jQuery("form[name='adminForm']").attr("action");
	 	 url += '&' + tk + '=1'+'&'+'task=salasListar'+'&'+'format=json';

	mydata.append('page_actual', top.document.getElementById('jform_page_actual').value);
	mydata.append('list_limit', top.document.getElementById('jform_list_limit').value);
	mydata.append('search', top.document.getElementById('jform_search').value);
	mydata.append('filter', top.document.getElementById('jform_list_filter').value);
	mydata.append('direction', top.document.getElementById('jform_list_asc').value);

   jQuery.ajax({ url: url,
   				 data: mydata,   
			     datatype: 'json',
			     cache: false,
			     processData: false,
			     contentType: false,
			     type: 'POST',
			     success: function (response) {	list_rooms(response.data[1]);
												pagination_chat(response.data[0]);
												atualizaIframe();
																								
												//setTimeout(function () { start_page();}, 100);
												
												}

    });

}

function list_rooms(items) {
	
	var i;
	var iframe = document.getElementById("framelist"); 
	var doc;
	var params;
	var show_private;
	var show_dice;
	var users_limit;
	var edit;
	var button_room;
	var button_edit;
	var table;
	
	table = '<table class="listRoom">' +
			'<thead><tr class="taba-content"><th>' + Joomla.JText._('COM_TABAPAPO_FIELD_TITLE_LABEL') + '<i class="arrow-down-3"></i>' +
			'</th><th>' + Joomla.JText._('COM_TABAPAPO_FIELD_CATEGORY_LABEL') + '<i class="arrow-down-3"></i>' +
			'</th><th>' + Joomla.JText._('COM_TABAPAPO_FIELD_OWNER_LABEL') + '<i class="arrow-down-3"></i>' +
			'</th><th>' + Joomla.JText._('COM_TABAPAPO_FIELD_EDIT_LABEL') +
			'</th><th>' + Joomla.JText._('COM_TABAPAPO_FIELD_PRIVATE_LABEL') +
			'</th><th>' + Joomla.JText._('COM_TABAPAPO_FIELD_DICE_LABEL') +
			'</th><th>' + Joomla.JText._('COM_TABAPAPO_FIELD_USERS_LIMIT_LABEL') +
			'</th></tr></thead><tbody>';

	if(iframe.contentDocument) {

	    doc = iframe.contentDocument; 

	} else {

	    doc = iframe.contentWindow.document; 

	}
	
	if (items) {
		
		for (i = 0; i < items.length; i++) {
				
			params = JSON.parse(items[i].params);
			users_limit = params.users_limit;
			
			if (params.users_limit == '') {
				
				users_limit = 10; 
				
			} else {
				
				users_limit = params.users_limit;
				
			}
			
			if (params.show_private == '') {
				
				show_private = Joomla.JText._('JGLOBAL_USE_GLOBAL');
				
			} else if (params.show_private == '0')  {
				
				show_private = Joomla.JText._('COM_TABAPAPO_NO');
				
			} else if (params.show_private == '1')  {
				
				show_private = Joomla.JText._('COM_TABAPAPO_YES');
				
			}
			
			if (params.show_dice == '') {
				
				show_dice = Joomla.JText._('JGLOBAL_USE_GLOBAL');
				
			} else if (params.show_dice == '0')  {
				
				show_dice = Joomla.JText._('COM_TABAPAPO_NO');
				
			} else if (params.show_dice == '1')  {
				
				show_dice = Joomla.JText._('COM_TABAPAPO_YES');
				
			}
			
			if (document.getElementById('jform_username').value == items[i].created_by) {
				
				button_edit = '<button class="btn btn-success" onclick="' + "window.open('?option=com_tabapapo&view=tabapapoadd&layout=tabapapoadd&id=" + items[i].id + "','_parent');" + '">'+ Joomla.JText._('COM_TABAPAPO_FIELD_EDIT_LABEL') + '</button>';
				
			}
			else {
				
				button_edit = '-';
				
			}
			
			button_room = '<button class="btn btn-success" onclick="' + "window.open('?option=com_tabapapo&view=tabapapo&layout=tabapapo&id=" + items[i].id + "','_parent');" + '">'+ items[i].title + '</button>';
			
			table += '<tr class="taba-start-hover"><td><i class="icon-clock" title="' + items[i].created + '"></i>' + ' ' + button_room + '</td>'+
					 "<td>" + items[i].category_title + "</td>" +
					 "<td>" + items[i].created_by +"</td>"+
					 "<td>" + button_edit + "</td>" +
					 "<td>" + show_private + "</td>" +
					 "<td>" + show_dice + "</td>" +
					 "<td>" + items[i].users_on + '/' + users_limit + "</td></tr>";
		}
	
	
		table += "</tbody></table>";


		doc.getElementById('showlist').innerHTML = table;
		
	}
}

function pagination_chat(num_rows) {
	
	var html = '<ul class="pagination ms-auto mb-4 me-0">';
	var page_actual = document.getElementById('jform_page_actual').value;
	var list_limit = document.getElementById('jform_list_limit').value;
	var totalPage = Math.ceil(num_rows / list_limit);
	var i;
	var n;
	var x;
	var link;
	
	
	if(page_actual <= totalPage) {
	
		if (page_actual == 1) {
						
			html += '<li class="disable page-item"><span class="page-link"><span class="icon-angle-double-left"></span></span></li>'+
					'<li class="disable page-item"><span class="page-link"><span class="icon-angle-left"></span></span></li>';
			
		} else {
						
			
			
			n = page_actual - 1;
			
			html += '<li class="taba-hover page-item"><span onclick="change_page(1);" class="taba-hover page-link"><span class="icon-angle-double-left"></span></span></li>'+
					'<li class="taba-hover page-item"><span onclick="change_page(' + n + ');" class="taba-hover page-link"><span class="icon-angle-left"></span></span></li>';
			
		}
		
		
		for(i = 0; i < totalPage; i++){

			n = i + 1;
			
			if (n == page_actual) {
				
				html += '<li class="active page-item"><span class="taba-hover page-link">' + n + '</span></li>';
			
			} else {
				
				link = '<span onclick="change_page(' + n + ');" class="taba-hover page-link">' + n + '</span>';
				html += '<li class="page-item">' + link + '</li>';
				
			}
			
		}
		
		if (page_actual == totalPage) {
			
			html += '<li class="disable page-item"><span class="taba-hover page-link"><span class="icon-angle-right"></span></span></li>'+
					'<li class="disable page-item"><span class="taba-hover page-link"><span class="icon-angle-double-right"></span></span></li></ul>';
			
		} else {
			
			x = parseInt(page_actual, 10) + 1;
			html += '<li class="page-item"><span onclick="change_page(' + x + ');" class="taba-hover page-link"><span class="icon-angle-right"></span></span></li>'+
					'<li class="page-item"><span onclick="change_page(' + totalPage + ');" class="taba-hover page-link"><span class="icon-angle-double-right"></span></span></li></ul>';
			
			
		}

		document.getElementById("pagination").innerHTML = html;
	
	}
}

function change_page(page_n) {
	
	document.getElementById('jform_page_actual').value = page_n;
	start_page();
	
}


function create_chat(){

	var myform = new FormData(document.getElementById("adminForm"));
	var tk = document.getElementById('jform_tk').value;
	var url = jQuery("form[name='adminForm']").attr("action");
	 	 url += '&' + tk + '=1'+'&'+'task=createChat'+'&'+'format=json';


	jQuery.ajax({
		url: url,
		data: myform,
		datatype: 'json',
		cache: false,
		processData: false,
		contentType: false,
		type: 'POST',
		success: function () { }
	});

}

function atualizaIframe() {
    
    var iframe = document.getElementById("framelist");
    var tamanho;
	var doc;
	
	if(iframe.contentDocument) {

	    doc = iframe.contentDocument; 

	} else {

	    doc = iframe.contentWindow.document; 

	}
	
	tamanho = doc.getElementById("showlist").scrollHeight;
	
	document.getElementById("framelist").height = tamanho;

}

function begin_room() {

	clear_msgbox();
	verify_msg();
	row_frame();
	
	if (document.getElementById('jform_description').value != '') {
		
		document.getElementById('divdesc').hidden = false;
		
	}
	
	document.getElementById('resize-bottom-chat').addEventListener('mousedown', initDrag_chat, false);
	document.getElementById('resize-bottom-users').addEventListener('mousedown', initDrag_users, false);

	document.getElementById('jform_status').addEventListener('click', function () {atualizar_status();document.getElementById('jform_msg').focus();});
	document.getElementById('jform_privado').addEventListener('click', function () {document.getElementById('jform_msg').focus();});

	document.getElementById('jform_msg').addEventListener('change', function () {verify_msg();});
	document.getElementById('jform_msg').addEventListener('input', function () {verify_msg();});
	document.getElementById('jform_msg').addEventListener('focus', function () {verify_msg();});
	document.getElementById('jform_msg').addEventListener('keyup', function () {verify_msg();});
	document.getElementById('jform_msg').addEventListener('keydown', function () {verify_msg();});
	document.getElementById('jform_msg').addEventListener('keypress', function () {verify_msg(); if (event.keyCode==13){ send_msg();}});
	document.getElementById('jform_msg').focus();
	
	if (document.getElementById('jform_talkto_id').value == 0){
	
		top.document.getElementById('cb_private').style.visibility = 'hidden';
		system_frase(document.getElementById('jform_talkto_name').value);
		
	}
	else {
		
		top.document.getElementById('cb_private').style.visibility = 'visible';
		system_frase(document.getElementById('jform_talkto_name').value);
		
	}

}


function row_frame() { 

	if(document.getElementById("jform_rolagem").value == 1){
		fread.scrollBy(0,70);
		setTimeout("row_frame()",0); 

	}

}


function verify_msg() {

	var textar = document.getElementById('jform_msg');
 	var mensagem = textar.value;
	var qtd = mensagem.length;

var frameread = 'frameread';

	if((qtd > 0) && (qtd < 2)) {

		document.getElementById("botenviar").innerHTML = '<i class="icon-ok taba-send taba-hover" title="' + Joomla.JText._('COM_TABAPAPO_SEND') + '" style="color:#faa63f;" onClick="send_msg();"></i>';

	}	

	if((qtd == 0)) {

		document.getElementById("botenviar").innerHTML = '<i class="icon-ok taba-send" title="' + Joomla.JText._('COM_TABAPAPO_SEND') + '"></i>';

	}

	if (qtd > 512) {

	    textar.value = mensagem.substr(0,512)

	}

}


function clear_msgbox() {

	document.getElementById('jform_msg').value = '';
	document.getElementById('jform_msg').focus();

}


function getin_room() {

	var mydata = new FormData();
	var tk = document.getElementById('jform_tk').value;
	var url = jQuery("form[name='adminForm']").attr("action");
	 	 url += '&' + tk + '=1'+'&'+'task=salaEntrar'+'&'+'format=json';

	mydata.append('sala_id', document.getElementById('jform_sala_id').value);

   jQuery.ajax({ url: url,
   				  data: mydata,   
			        datatype: 'json',
			        cache: false,
			        processData: false,
			        contentType: false,
			        type: 'POST',
			        success: function () {	read_msgs("frameread", tk);
   											read_users("frameusers", tk); }

    });

return true;
	
}

function send_msg(){

	var myform = new FormData(document.getElementById("adminForm"));
	var aflood;
	var msg;
	var espacos;
	var tk = document.getElementById('jform_tk').value;
	var url = jQuery("form[name='adminForm']").attr("action");
	 	url += '&' + tk + '=1'+'&'+'task=mensagemEnviar'+'&'+'format=json';

	select_private();

	if (document.getElementById('jform_talkto_id').value == 0) {

		document.getElementById('jform_privadob').value = 0;

	}

	msg = document.getElementById('jform_msg').value;
	espacos = msg.split(' ');
	
	if(flood == 0) {

		if(espacos.length - 1 == msg.length) {
		
			clear_msgbox();

			} else {

			   jQuery.ajax({
			        url: url,
			        data: myform,
			        datatype: 'json',
			        cache: false,
			        processData: false,
			        contentType: false,
			        type: 'POST',
			        success: function () { flood = 1;
			        								 clear_msgbox();
			        								 verify_msg();          
						 							 setTimeout('liberaflood()',2000); }
			    });

				
			}
		}
		else {
		
			aflood = Joomla.JText._('COM_TABAPAPO_INTERVAL');
			document.getElementById("exibefrase").innerHTML  = aflood;
			setTimeout('begin_room()',4000);
			clear_msgbox();
	}
}

function read_users(idframe, tk){

	var mydata = new FormData();
	var url = jQuery("form[name='adminForm']").attr("action");
	 	 url += '&' + tk + '=1'+'&'+'task=usersLer'+'&'+'format=json';

	mydata.append('sala_id', document.getElementById('jform_sala_id').value);

	jQuery.ajax({
			        url: url,
			        data: mydata,
			        datatype: 'json',
			        cache: false,
			        processData: false,
			        contentType: false, 
			        type: 'POST',
			        success: function (response) { populateUsersOn(idframe, response.data, document.getElementById('jform_users_limit').value);
	       								  setTimeout(function () { read_users(idframe, tk); }, 40); 
        											}

    });
    
}

function read_msgs(idframe, tk){

	var mydata = new FormData();

	var url = jQuery("form[name='adminForm']").attr("action");
	 	 url += '&' + tk + '=1'+'&'+'task=mensagemLer'+'&'+'format=json';

	mydata.append('sala_id', document.getElementById('jform_sala_id').value);
	mydata.append('lmsg_id', document.getElementById('jform_lmsg_id').value);

	jQuery.ajax({
			        url: url,
			        data: mydata,
			        datatype: 'json',
			        cache: false,
			        processData: false,
			        contentType: false,
			        type: 'POST',
			        success: function (response) { populateChatRoom(idframe, response.data);
													setTimeout(function () { read_msgs(idframe, tk); }, 40); }
    });
    
}

function atualizar_status () {
	
	var mydata = new FormData();
	var tk = document.getElementById('jform_tk').value;
	
	var url = jQuery("form[name='adminForm']").attr("action");
	 	 url += '&' + tk + '=1'+'&'+'task=userstatus'+'&'+'format=json';
	
	mydata.append('sala_id', document.getElementById('jform_sala_id').value);
	
	if (document.getElementById('jform_status').checked) {
		
		mydata.append('status', 0);
		
	}
	else {
		
		mydata.append('status', 1);
		
	}

	jQuery.ajax({url: url,
					 data: mydata,
        			 datatype: 'json',
					 cache: false,
					 processData: false,
					 contentType: false,        			 
        			 type: 'POST',
        			 success: 
        			 	function () {
							
							if (document.getElementById('jform_status').checked) {

								document.getElementById("statusb").style = "color:#faa63f;";
										

							} else {
								
								document.getElementById("statusb").style = "color:#72bf44;";
								
							}
        			 		
        			 	}

	});
	
}

function roll_dice(dice) {
	
	var mydata = new FormData();
	var tk = document.getElementById('jform_tk').value;
	
	var url = jQuery("form[name='adminForm']").attr("action");
	 	 url += '&' + tk + '=1'+'&'+'task=rolldice'+'&'+'format=json';
	
	mydata.append('sala_id', document.getElementById('jform_sala_id').value);
	mydata.append('dice', dice);

	jQuery.ajax({url: url,
					 data: mydata,
        			 datatype: 'json',
			       cache: false,
			       processData: false,
			       contentType: false,        			 
        			 type: 'POST',
        			 success: 
        			 	function () {
							
							document.getElementById('divdice').hidden = true;
							document.getElementById('jform_msg').focus();
       			 		
        			 	}

	});
	
}

function saindo() {

	var mydata = new FormData();
	var tk = document.getElementById('jform_tk').value;
	
	var url = jQuery("form[name='adminButtons']").attr("action");
	 	 url += '&' + tk + '=1'+'&'+'task=exitRoom'+'&'+'format=json';
	 
	mydata.append('sala_id', document.getElementById('jform_sala_id').value);

   jQuery.ajax({ url: url,
			        data: mydata,
			        datatype: 'json',
			        cache: false,
			        processData: false,
			        contentType: false,
			        type: 'POST',
			        success: function () {  }
   });


}

function populateChatRoom(idframe, msgs) { 

	var iframe = document.getElementById(idframe); 
	var doc;
	var i;
	var type;
	var textmsg;
	var usu_id = document.getElementById('jform_usu_id').value;
	var username;
	var talktoname;
	var params;
	var paramsjson;
	var tempo;

	if(iframe.contentDocument) {

	    doc = iframe.contentDocument; 

	} else {

	    doc = iframe.contentWindow.document; 

	}

	if (msgs) { 
		
		for (i = 0; i < msgs.length; i++) {
			
			tempo = msgs[i].tempo;
			
			document.getElementById('jform_lmsg_id').value = msgs[i].id;
			
			params = msgs[i].params;
			paramsjson = JSON.parse(params);
			username = paramsjson.usu_name;
			talktoname = paramsjson.talkto_name;
			textmsg = msgs[i].msg;			

			conteudo += '<div class="taba-frame">';
			
			if (msgs[i].usu_id == 0) {

				type = 'taba-msgsystem';

				conteudo += '<div class="' + type + '"><spam class="taba-msghead">' + ' ' + '<i = class="icon-chevron-right" title="' + 'System Message: ' + tempo + '"></i></spam>' + '<spam class="taba-msg">' + textmsg + '</spam></div>';

			}
			else {
				
				if (msgs[i].falacom_id == 0) {
				
					if (msgs[i].reservado == 0) {
				
						if (msgs[i].usu_id == usu_id) {

							type = 'taba-self';

						} else {

							type = 'taba-others';

						}
						
					}

					conteudo += '<div class="' + type + '"><spam class="taba-msghead"title="' + tempo + '">' + ' ' + '<b>' + username + '</b> </spam>' + '<spam class="taba-msg">' + textmsg + '</spam></div>';

				}
				else {
					
					if (msgs[i].reservado == 0) {

						type = 'taba-direct';
							
						conteudo += '<div class="' + type + '"><spam class="taba-msghead" title="' + tempo + '">' + ' ' + '<b>' + username + ' ' + '<i class="icon-arrow-right-4"></i>' + ' ' + talktoname + '</b> </spam>' + '<spam class="taba-msg">&nbsp;' + textmsg + '</spam></div>';
							
						}
					else {
					
						if ((msgs[i].usu_id == usu_id) || (msgs[i].falacom_id == usu_id)) {

							type = 'taba-direct-private';

							conteudo += '<div class="' + type + '"><spam class="taba-msghead" title="' + tempo + '">' + '<i class="taba-private icon-unpublish" title="' + 'Private Message' + '"></i>' + ' ' + '<b>' + username + ' ' + '<i class="icon-arrow-right-4"></i>' + ' ' + talktoname + '</b> </spam>' + '<spam class="taba-msg">' + textmsg + '</spam></div>';

						} 

					}
					
				}
				
			}

		}
		
		conteudo += '</div>';
		doc.getElementById('showmsg').innerHTML = conteudo; 
	
	}

}

function populateUsersOn(id, userson, users_limit) { 

	var usuarioson = '<ul class="taba-ul">';
	var iframe = document.getElementById(id); 
	var doc;
	var i;
	var owner;
	var params;
	var paramsjson;
	var username;
	var tempo;
	
	
	document.getElementById('users-head').innerHTML = '<span><i class="icon-users"></i>' + ' ' + '<b>' + Joomla.JText._('COM_TABAPAPO_USERSON_ALLUSERS') + ' [ ' + userson.length + '/' + users_limit + ' ]</b></span>';
	
	
	if(iframe.contentDocument) {

	    doc = iframe.contentDocument; 

	} else {

	    doc = iframe.contentWindow.document; 

	}

	if (userson) { 
		
		for (i = 0; i < userson.length; i++) {
						
			owner = '';
			
			tempo = userson[i].tempo;
			
			params = userson[i].params;
			paramsjson = JSON.parse(params);
			username = paramsjson.usu_name;

			if (userson[i].status == 0) { cla = 'taba-away'; }
			
			if (userson[i].status == 1) { cla = 'taba-conected'; }
			
			if (document.getElementById('jform_owner').value == userson[i].usu_id) { owner = '<i class="icon-key" title="' + Joomla.JText._('COM_TABAPAPO_USERSON_OWNER') + '"></i>'; }
			
			usuarioson += '<li class="taba-hover"><div class="taba-content"><div class="taba-msg ' + cla + '" onclick="select_talkto(' + "'" + userson[i].usu_id + "'" + ',' + "'" + username + "'" + ');">' + '<i class="icon-user" title="' + tempo + '"></i>' + ' ' + '<b>' + username + '</b>' + ' ' + owner +'</div></div></li>';	
			
		}
		
		usuarioson += '</ul>'
		
		if (doc.getElementById('showusers').innerHTML != usuarioson){
			
			doc.getElementById('showusers').innerHTML = usuarioson; 
		
		}
	}
}

function liberaflood() {
	
	flood = 0;

}

function select_talkto(talkto_id, usu_name) {

	top.document.getElementById('jform_talkto_id').value = talkto_id;
	top.document.getElementById('jform_talkto_name').value = usu_name;
	
	if (talkto_id == '0'){
	
		top.document.getElementById('cb_private').style.visibility = 'hidden';
		top.document.getElementById('jform_privado').checked = false;
		
	}
	else {
		
		if (top.document.getElementById('jform_show_private').value == '1') {
			top.document.getElementById('cb_private').style.visibility = 'visible';
		}
		
	}
	
	system_frase(usu_name);
	top.document.getElementById('jform_msg').focus();

}

function select_private() {

	if (document.getElementById('jform_privado').checked) {

	     document.getElementById('jform_privadob').value = 1;

	} else {

	     document.getElementById('jform_privadob').value = 0;

	}
  
}

function system_frase(usu_name) {

	if (usu_name == '') {
	
		usu_name = Joomla.JText._('COM_TABAPAPO_TABAPAPOCHAT_EVERYBODY');
	
	}
	
	top.document.getElementById('exibefrase').innerHTML = '<span>' + Joomla.JText._('COM_TABAPAPO_TABAPAPOCHAT_TALKINGTO') + ' ' + '<b>' + usu_name + '.</b></span>';

}


function show_info(type) {

	if (document.getElementById(type).hidden == true) {
		
		document.getElementById(type).hidden = false;
		
	}
	else {
		
		document.getElementById(type).hidden = true;
	
	}

	document.getElementById('jform_msg').focus();
	
}

function insert_emoji(text) {
    
    var el = document.getElementById('jform_msg');
    var val = el.value, endIndex, range;
    
    if (typeof el.selectionStart != "undefined" && typeof el.selectionEnd != "undefined") {
        endIndex = el.selectionEnd;
        el.value = val.slice(0, el.selectionStart) + text + val.slice(endIndex);
        el.selectionStart = el.selectionEnd = endIndex + text.length;
    } else if (typeof document.selection != "undefined" && typeof document.selection.createRange != "undefined") {
        el.focus();
        range = document.selection.createRange();
        range.collapse(false);
        range.text = text;
        range.select();
    }
    
    verify_msg();
    document.getElementById('jform_msg').focus();
    
}


var startX;
var startY;
//var startWidth;
//var startHeight;
var startHeightdivchat;
var startHeightdivusers;
var startHeightframeread;
var startHeightframeusers;

function initDrag_chat(e) {
	startX = e.clientX;
	startY = e.clientY;
	//startWidth = parseInt(document.defaultView.getComputedStyle(document.getElementById('divframes')).width, 10);
	startHeightdivchat = parseInt(document.defaultView.getComputedStyle(document.getElementById('divframechat')).height, 10);
	startHeightframeread = parseInt(document.defaultView.getComputedStyle(document.getElementById('frameread')).height, 10);
//	startHeightframeusers = parseInt(document.defaultView.getComputedStyle(document.getElementById('frameusers')).height, 10);
	document.documentElement.addEventListener('mousemove', doDrag_chat, false);
	document.documentElement.addEventListener('mouseup', stopDrag_chat, false);
}

function doDrag_chat(e) {
	//document.getElementById('divframes').style.width = (startWidth + e.clientX - startX) + 'px';
	document.getElementById('divframechat').style.height = (startHeightdivchat + e.clientY - startY) + 'px';
	document.getElementById('frameread').style.height = (startHeightframeread + e.clientY - startY) + 'px';
	//document.getElementById('frameusers').style.height = (startHeightframeusers + e.clientY - startY) + 'px';
}

function initDrag_users(e) {
	startX = e.clientX;
	startY = e.clientY;
	//startWidth = parseInt(document.defaultView.getComputedStyle(document.getElementById('divframes')).width, 10);
	startHeightdivusers = parseInt(document.defaultView.getComputedStyle(document.getElementById('divframeusers')).height, 10);
//	startHeightframeread = parseInt(document.defaultView.getComputedStyle(document.getElementById('frameread')).height, 10);
	startHeightframeusers = parseInt(document.defaultView.getComputedStyle(document.getElementById('frameusers')).height, 10);
	document.documentElement.addEventListener('mousemove', doDrag_users, false);
	document.documentElement.addEventListener('mouseup', stopDrag_users, false);
}

function doDrag_users(e) {
	//document.getElementById('divframes').style.width = (startWidth + e.clientX - startX) + 'px';
	document.getElementById('divframeusers').style.height = (startHeightdivusers + e.clientY - startY) + 'px';
	//document.getElementById('frameread').style.height = (startHeightframeread + e.clientY - startY) + 'px';
	document.getElementById('frameusers').style.height = (startHeightframeusers + e.clientY - startY) + 'px';
}

function stopDrag_chat(e) {
   document.documentElement.removeEventListener('mousemove', doDrag_chat, false);
   document.documentElement.removeEventListener('mouseup', stopDrag_chat, false);
}

function stopDrag_users(e) {
   document.documentElement.removeEventListener('mousemove', doDrag_users, false);
   document.documentElement.removeEventListener('mouseup', stopDrag_users, false);
}
