//jQuery(document).ready(function() {
    
  //  const params = Joomla.getOptions('params');

//});


jQuery(document).ready(function() {
        title = jQuery("#jform_title").val();
        jQuery("#jform_status").val(title);
 //     jQuery.post("", "campo1=dado1&campo2=dado2&campo3=dado3", function( data ) {console.log(data);});
     
	});
    
var conteudo = '';
var usuarioson = '';
var lmsg_id = 0;
var flood = 0; //continuar
var imgenvia = new Image();

	var re;



function rolar() { 
	if(document.getElementById("rolagem").value == 1){
		fread.scrollBy(0,70);
		setTimeout("rolar()",0); 
	}
}


function inicia(){
	//document.getElementById("load").style.visibility = 'hidden';
	document.getElementById('jform[msg2]').focus();
	document.getElementById("exibefrase").innerHTML  = 'Você está falando com Todos.';
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
		document.getElementById("botenviar").innerHTML = '<a href="#" onClick="VerificaMsg();send_msg('+"'"+frameread+"'"+', sala_id, usu_id, tk);"><i class="icon-ok"></i></a>';
	}
	if(qtd == 0){
		document.getElementById("botenviar").innerHTML = '<i class="icon-ok"></i>';	
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
        
							   // do something with the result
        }
    });

return true;

}

function populateChatRoom(idframe, usu_id, msgs) { 

	var iframe = document.getElementById(idframe); 
	var doc;
	var i;
	var type;
	var inter;

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
				inter = 'fala com';
			} else {
				type = 'publico';
				inter = 'fala com';
			}
			
			if ((msgs[i].falacom_id == usu_id) && (msgs[i].reservado == 0)) {
				type = 'talkto';
				inter = 'fala com';
			} else {
				type = 'publico';
				inter = 'fala com';
			}

		//	$ver = $falacom;
		//	if($ver == $nick){
		//		$ver = 'você';
		//	}
			
			if ((msgs[i].reservado != 0) && (msgs[i].falacom_id == usu_id)) {
				type = 'privado';
				inter = 'fala reservadamente com';
			} 
			
			if ((msgs[i].reservado != 0) && (msgs[i].usu_id == usu_id)) {
				type = 'privado';
				inter = 'fala reservadamente com';
			}
					
			conteudo += '<div class="' + type + '"><b> ' + msgs[i].usu_id + ' </b><i>' + inter + msgs[i].falacom_id + '</i>:' + msgs[i].msg + '</div>';
			
		}
	
	//conteudo = msgs[12].id;
	doc.getElementById('showmsg').innerHTML = conteudo; 
//	clearTimeout(re);
//	setTimeOut( function () { Ler(idframe, usu_id, tk); },1000);
	
	
	//document.getElementById('msg').focus();
	}
}


function populateUsersOn(id, text) { 


	var iframe = document.getElementById(id); 
	var doc; 

	if(iframe.contentDocument) { 
	    doc = iframe.contentDocument; 
	} else {
	    doc = iframe.contentWindow.document; 
	}

	doc.getElementById('showusers').innerHTML = text; 

}

function send_msg(id, sala_id, usu_id, tk){

	if(flood == 0){
	var iframe = document.getElementById(id);
	var doc;
	var text;
	var msg;
	var token = tk; //continuar
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

	
//	Joomla.submitbutton('tabapapoform.submit');
	
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
	conteudo +=  text;
	doc.getElementById('showmsg').innerHTML =  conteudo;
	
	}
	else{
		document.getElementById("exibefrase").innerHTML  = '<span>O sistema anti-flood está ativado.</span>';
	}
	
			//doc.scrollBy(0,70);
	//document.getElementById('jform[msg]').value = "";
	//document.getElementById('jform[msg]').focus();
}

function liberaflood(){
	flood = 0;
}

function reenvia(idframe, usu_id, tk){
	Ler(idframe, usu_id, tk);
}


function Ler(idframe, usu_id, tk){


	var token = tk; //continuar

	var url = jQuery("form[name='adminForm']").attr("action");
	 	 url += '&' + token + '=1'+'&'+'task=mensagemLer'+'&'+'format=json'+'&'+'lmsg='+lmsg_id;

	var xmsgs = jQuery.ajax({
        url: url,
        datatype: 'json',
        type: 'GET',
        success: function (response) { populateChatRoom(idframe, usu_id, response.data);
        											//clearTimeout(re);
													setTimeout(function () { Ler(idframe, usu_id, tk); }, 1000); }
    });
    

	//re = setTimeout(function () { reenvia(idframe, usu_id, tk);}, 10000);


}



function displaySearchResults(result) {

      //alert("successful");
      
            		//	alert("Result: "+result['sala_id']+", Message: "+result.message);
       
        

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