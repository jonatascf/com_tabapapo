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
	//document.getElementById("load").style.visibility = 'hidden';
	document.getElementById('jform[msg2]').focus();
	document.getElementById("exibefrase").innerHTML  = 'You are talking to everyone.';
}

//	var textar = document.getElementById('jform[msg]');
	//var textar = document.getElementById('jform[msg]');
	//textar.addEventListener('keypress', function() {ContaCaracteres(); if (event.keyCode==13){ VerificaMsg();} } );



//contar caracteres da mensagem
function ContaCaracteres(){
	var textar = document.getElementById('jform[msg2]');
 	var mensagem = textar.value;
	var qtd = mensagem.length;

var frameread = 'frameread';

	if((qtd > 0) && (qtd < 2)){
		//document.getElementById("botenviar").innerHTML = '<a href="#" onClick="VerificaMsg();send_msg('+"'"+frameread+"'"+', sala_id, usu_id, tk);"><i class="icon-ok"></i></a>';
	}
	if(qtd == 0){
		//document.getElementById("botenviar").innerHTML = '<i class="icon-ok"></i>';	
	}
	if (qtd > 300){
	    textar.value = mensagem.substr(0,300)
	}
}


function VerificaMsg(){

	var textar = document.getElementById('jform[msg2]');
	var mensagem = textar.value;
	var espacos = mensagem.split(' ');

	if(espacos.length - 1 == mensagem.length){
		setTimeout('Limpar()',10);
	}else{
		//Enviar();
		Limpar();
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
        type: 'POST',
        success: function (result,status,xhr) { displaySearchResults(sala_id);
        
							   // do something with the result
        }
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
        type: 'POST',
        success: function (result,status,xhr) { displaySearchResults(sala_id);
        
        }
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
				inter = 'talk to';
			} else {
				type = 'publico';
				inter = 'talk to';
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
				type = 'privado';
				inter = 'talk to'; //reservadamente
			} 
			
			if ((msgs[i].reservado != 0) && (msgs[i].usu_id == usu_id)) {
				type = 'privado';
				inter = 'talk to';
			}
			
			htmltext = stripHtml(msgs[i].msg);
					
			conteudo += '<div class="' + type + '"><b> ' + msgs[i].usu_id + ' </b><i>' + inter + msgs[i].falacom_id + '</i>: ' + htmltext + '</div>';
			
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

			usuarioson += '<div class="' + type + '">' + '<b>@ ' + userson[i].usu_id + '</b></div>';	
			
		}

	doc.getElementById('showusers').innerHTML = usuarioson; 

	}
}

function send_msg(id, sala_id, usu_id, tk){

	if(flood == 0){
	var iframe = document.getElementById(id);
	var doc;
	var text;
	var msg;
	var token = tk;
	var myform;
   var fd;
	var url = jQuery("form[name='adminForm']").attr("action");
	 	 url += '&' + token + '=1'+'&'+'task=mensagemEnviar'+'&'+'format=json';

	myform = document.getElementById("adminForm");
   fd = new FormData(myform);
   msg = fd.get('jform[msg2]');

   jQuery.ajax({
        url: url,
        data:  fd  ,
        datatype: 'json',
        cache: false,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function () { flood = 1;
			 							 setTimeout('liberaflood()',1500); }
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
	
	text = '<div class="'+type+'">'+usu_id+' disse '+msg+'</div>';

	}
	else{
		document.getElementById("exibefrase").innerHTML  = '<span>O sistema anti-flood está ativado.</span>';
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
  													setTimeout(function () { Ler_users(idframe, tk); }, 400); }
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



function displaySearchResults(result) {


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