//JAVASCRIPT

var conteudo = '';
var lmsg_id = 0;
var flood = 0;
var imgenvia = new Image();

function rolar() { 

	if(document.getElementById("rolagem").value == 1){
		fread.scrollBy(0,70);
		setTimeout("rolar()",0); 

	}

}


function inicia(){

	var talking = 'You are talking to everyone.';
	
	Limpar();
	ContaCaracteres();
	document.getElementById('jform[msg2]').focus();
	document.getElementById("exibefrase").innerHTML  = talking;

}


function ContaCaracteres(){

	var textar = document.getElementById('jform[msg2]');
 	var mensagem = textar.value;
	var qtd = mensagem.length;

var frameread = 'frameread';

	if((qtd > 0) && (qtd < 2)){

		document.getElementById("botenviar").innerHTML = '<a href="#" onClick="send_msg('+"'"+frameread+"'"+', sala_id, usu_id, tk);" ><i class="icon-ok"></i></a>';

	}	

	if((qtd == 0)){

		document.getElementById("botenviar").innerHTML = '<i class="icon-ok"></i>';

	}

	if (qtd > 300){

	    textar.value = mensagem.substr(0,512)

	}

}


function Limpar(){

	document.getElementById('jform[msg2]').value = '';
	document.getElementById('jform[msg2]').focus();

}


function entrando(sala_id, tk) {

   var sl = '';
	var url = jQuery("form[name='adminForm']").attr("action");
	 	 url += '&' + tk + '=1'+'&'+'task=salaEntrar'+'&'+'format=json';
		 sl = sala_id;

   jQuery.ajax({

			        url: url,
			        data: {sl: sl},
			        datatype: 'json',
			        cache: false,
			        processData: false,
			        contentType: false,
			        type: 'POST'

    });

return true;
	
}

function saindo(sala_id, tk) {

   var sl = '';
	var url = jQuery("form[name='adminForm']").attr("action");
	 	 url += '&' + tk + '=1'+'&'+'task=salaSair'+'&'+'format=json';
		 sl = sala_id;

   jQuery.ajax({
        url: url,
        data: {sl: sl},
        datatype: 'json',
        cache: false,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function () { window.top.location.href = "index.php"; }
    });


}


function stripHtml(html) {

   let tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";

}


function populateChatRoom(idframe, usu_id, msgs) { 

	var iframe = document.getElementById(idframe); 
	var doc;
	var i;
	var type;
	var htmltext;
	var username;

	if(iframe.contentDocument) {

	    doc = iframe.contentDocument; 

	} else {

	    doc = iframe.contentWindow.document; 

	}

	if (msgs) { 
		
		for (i = 0; i < msgs.length; i++) {
			
			document.getElementById("lmsg").value = msgs[i].id;
			lmsg_id = document.getElementById("lmsg").value;
			username = msgs[i].params;
			
			if ((msgs[i].usu_id == usu_id) && (msgs[i].reservado == 0)) {

				type = 'taba-self';

			} else {

				type = 'taba-others';

			}
			
			/*if ((msgs[i].falacom_id == usu_id) && (msgs[i].reservado == 0)) {

				type = 'taba-self';

			} else {

				type = 'taba-others';

			}*/

			if (msgs[i].usu_id == 0) {

				username = 'System message'; 
				type = 'taba-msgsystem';

			}
			
			if ((msgs[i].reservado != 0) && (msgs[i].falacom_id == usu_id)) {

				type = 'taba-direct';

			} 
			
			if ((msgs[i].reservado != 0) && (msgs[i].usu_id == usu_id)) {

				type = 'taba-direct';

			}
			
			htmltext = stripHtml(msgs[i].msg);
			conteudo += '<div class="' + type + '"><spam class="taba-msghead"><b>' + '&nbsp;' + username + '</b> </spam>' + '<spam class="taba-content">&nbsp;' + htmltext + '</spam></div>';
			
		}
	
		doc.getElementById('showmsg').innerHTML = conteudo; 
	
	}

}

function populateUsersOn(id, userson) { 

	var usuarioson = '<div class="taba-msgsystem"><b>&nbsp;All users online [' + userson.length + ']</b></div>';
	var iframe = document.getElementById(id); 
	var doc;
	var i;
	
	if(iframe.contentDocument) {

	    doc = iframe.contentDocument; 

	} else {

	    doc = iframe.contentWindow.document; 

	}

	if (userson) { 
		
		for (i = 0; i < userson.length; i++) {
						
			if ((userson[i].status == 0)) { cla = 'taba-away'; }
			
			if ((userson[i].status == 1)) { cla = 'taba-conected'; }
			
			usuarioson += '<div class="taba-content"><div class="' + cla + '">' + '<b>&nbsp;' + userson[i].params + '</b></div></div>';	
			
		}
		
		doc.getElementById('showusers').innerHTML = usuarioson; 

	}
}

function send_msg(id, sala_id, usu_id, tk){

	var iframe = document.getElementById(id);
	var aflood;
	var fd;
	var msg;
	var myform;
	var espacos;
	var url = jQuery("form[name='adminForm']").attr("action");
	 	 url += '&' + tk + '=1'+'&'+'task=mensagemEnviar'+'&'+'format=json';

	myform = document.getElementById("adminForm");
	fd = new FormData(myform);
	
	msg = fd.get('jform[msg2]');
	espacos = msg.split(' ');
	
	if(flood == 0) {

		if(espacos.length - 1 == msg.length) {
		
			Limpar();

			} else {

			   jQuery.ajax({
			        url: url,
			        data:  fd,
			        datatype: 'json',
			        cache: false,
			        processData: false,
			        contentType: false,
			        type: 'POST',
			        success: function () { flood = 1;
			        								 Limpar();
			        								 ContaCaracteres();          
						 							 setTimeout('liberaflood()',2000); }
			    });

				
			}
		}
		else {
		
			aflood = 'The interval between messages must be longer than 2 seconds.';
			document.getElementById("exibefrase").innerHTML  = aflood;
			setTimeout('inicia()',4000);
			Limpar();
	}
}

function liberaflood() {
	
	flood = 0;

}

function atualizar_status (sala_id, tk) {
	
	var st;	
	
	if (document.getElementById("status").checked) {

		st = 0;
		document.getElementById("statusb").style = "color:#faa63f;";
				

	} else {
		
		st = 1;
		document.getElementById("statusb").style = "color:#72bf44;";
		
	}
	
	var url = jQuery("form[name='adminForm']").attr("action");
	 	 url += '&' + tk + '=1'+'&'+'task=userstatus'+'&'+'format=json'+'&'+'st='+st;

	jQuery.ajax({url: url});
	
}

function Ler_users(idframe, tk){

	var url = jQuery("form[name='adminForm']").attr("action");
	 	 url += '&' + tk + '=1'+'&'+'task=usersLer'+'&'+'format=json';

	var xmsgs = jQuery.ajax({
        url: url,
        datatype: 'json',
        type: 'POST',
        success: function (response) { populateUsersOn(idframe, response.data);
  													setTimeout(function () { Ler_users(idframe, tk); }, 100); }
    });
    
}

function Ler(idframe, usu_id, tk){

	var url = jQuery("form[name='adminForm']").attr("action");
	 	 url += '&' + tk + '=1'+'&'+'task=mensagemLer'+'&'+'format=json'+'&'+'lmsg='+lmsg_id;

	var xmsgs = jQuery.ajax({
        url: url,
        datatype: 'json',
        type: 'GET',
        success: function (response) { populateChatRoom(idframe, usu_id, response.data);
													setTimeout(function () { Ler(idframe, usu_id, tk); }, 100); }
    });
    
}


function emojis(){
	

	
}