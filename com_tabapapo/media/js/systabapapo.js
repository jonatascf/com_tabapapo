//JS

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


//contar caracteres da mensagem
function ContaCaracteres(){
	var textar = document.getElementById('jform[msg2]');
 	var mensagem = textar.value;
	var qtd = mensagem.length;

var frameread = 'frameread';

	if((qtd > 0)){
		document.getElementById("botenviar").innerHTML = '<a href="#" onClick="ContaCaracteres(); send_msg('+"'"+frameread+"'"+', sala_id, usu_id, tk);" ><i class="icon-ok"></i></a>';
	}
	else{
		document.getElementById("botenviar").innerHTML = '<i class="icon-ok"></i>';	
	}
	if (qtd > 300){
	    textar.value = mensagem.substr(0,300)
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

      //alert("successful");

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
	var inter;
	var htmltext;

	if(iframe.contentDocument) { 
	    doc = iframe.contentDocument; 
	} else {
	    doc = iframe.contentWindow.document; 
	}

	if (msgs) { 
		
		for (i = 0; i < msgs.length; i++) {
			
			document.getElementById("lmsg").value = msgs[i].id;
			lmsg_id = document.getElementById("lmsg").value;
			
			if ((msgs[i].usu_id == usu_id) && (msgs[i].reservado == 0)) {
				type = 'talkto';
				inter = 'said';
			} else {
				type = 'publico';
				inter = 'said';
			}
			
			if ((msgs[i].falacom_id == usu_id) && (msgs[i].reservado == 0)) {
				type = 'talkto';
				inter = 'talk to';
			} else {
				type = 'publico';
				inter = 'talk to';
			}

		//	$ver = $falacom;
		//	if($ver == $nick){
		//		$ver = 'você';
		//	}
			
			if ((msgs[i].reservado != 0) && (msgs[i].falacom_id == usu_id)) {
				type = 'talkto';
				inter = 'talk to'; //reservadamente
			} 
			
			if ((msgs[i].reservado != 0) && (msgs[i].usu_id == usu_id)) {
				type = 'talkto';
				inter = 'talk to';
			}
			
			htmltext = stripHtml(msgs[i].msg);
					
			conteudo += '<div class="' + type + '"><b> ' + msgs[i].params + ' </b><i>said</i>: ' + htmltext + '</div>';
			
		}
	
	doc.getElementById('showmsg').innerHTML = conteudo; 
	
	}

}

function populateUsersOn(id, userson) { 

	var usuarioson = '';
	var iframe = document.getElementById(id); 
	var doc; 

	if(iframe.contentDocument) { 
	    doc = iframe.contentDocument; 
	} else {
	    doc = iframe.contentWindow.document; 
	}

	if (userson) { 
		
		for (i = 0; i < userson.length; i++) {
						
			if ((userson[i].status == 1)) {
				type = 'talkto';
			} else {
				type = 'away';
			}

			usuarioson += '<div class="' + type + '">' + '<b>@ ' + userson[i].params + '</b></div>';	
			
		}

	doc.getElementById('showusers').innerHTML = usuarioson; 

	}
}

function send_msg(id, sala_id, usu_id, tk){

	var iframe = document.getElementById(id);
	var doc;
	var text;
	var aflood;
	var fd;
	var msg;
	var token = tk;
	var myform;
	var mensagem;
	var espacos;
	var url = jQuery("form[name='adminForm']").attr("action");
	 	 url += '&' + token + '=1'+'&'+'task=mensagemEnviar'+'&'+'format=json';

	myform = document.getElementById("adminForm");
	fd = new FormData(myform);
	
	msg = fd.get('jform[msg2]');
	espacos = msg.split(' ');
	
	if(flood == 0){

		if(espacos.length - 1 == msg.length){
		
			Limpar();

			} else {

			   jQuery.ajax({
			        url: url,
			        data:  fd  ,
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

				
				if(iframe.contentDocument) { 
			    doc = iframe.contentDocument; 
			   } else {
			    doc = iframe.contentWindow.document; 
				}
				
				if(fd.get('jform[privado]') == 1) {
					type = 'privado';
				} else {
					type = 'talkto';
				}
			}
		}
		else{
		
			aflood = 'The interval between messages must be longer than 2 seconds.';
			document.getElementById("exibefrase").innerHTML  = aflood;
			setTimeout('inicia()',4000);
			Limpar();
	}
}

function liberaflood(){
	flood = 0;
}

function Ler_users(idframe, tk){

	var token = tk;

	var url = jQuery("form[name='adminForm']").attr("action");
	 	 url += '&' + token + '=1'+'&'+'task=usersLer'+'&'+'format=json';

	var xmsgs = jQuery.ajax({
        url: url,
        datatype: 'json',
        type: 'POST',
        success: function (response) { populateUsersOn(idframe, response.data);
  													setTimeout(function () { Ler_users(idframe, tk); }, 200); }
    });
    
}

function Ler(idframe, usu_id, tk){


	var token = tk;

	var url = jQuery("form[name='adminForm']").attr("action");
	 	 url += '&' + token + '=1'+'&'+'task=mensagemLer'+'&'+'format=json'+'&'+'lmsg='+lmsg_id;

	var xmsgs = jQuery.ajax({
        url: url,
        datatype: 'json',
        type: 'GET',
        success: function (response) { populateChatRoom(idframe, usu_id, response.data);
													setTimeout(function () { Ler(idframe, usu_id, tk); }, 400); }
    });
    
}


function emojis(){
	
	tinymce.init({
   selector: 'jform[msg]',  // change this value according to your HTML
   plugins: 'emoticons',
   toolbar: 'emoticons',
   emoticons_database: 'emojis'
	});
	
}

function sair(){
	window.top.location.href = "index.php";
}