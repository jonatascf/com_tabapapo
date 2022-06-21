//JAVASCRIPT


function start_form () {
	
	
	
}

function create_chat(){

	var myform = new FormData(document.getElementById("adminForm"));
	var tk = document.getElementById('tk').value;
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

function list_categories() {
	
	var tk = document.getElementById('tk').value;
	var url = jQuery("form[name='adminForm']").attr("action");
	 	 url += '&' + tk + '=1'+'&'+'task=categoriesList'+'&'+'format=json';


	jQuery.ajax({
		url: url,
		datatype: 'json',
		cache: false,
		processData: false,
		contentType: false,
		type: 'POST',
		success: function (response) {	mount_categories(response.data); }
	});
	
}

function mount_categories (categories) {
	
	var i;
	var html = '<select id="jform[catid]" name="jform[catid]">';
	
	for (i = 0; i < categories.length; i++) {
		
		html += '<option value="' + categories.id + '">' + categories.title + '</option>';
		
	}
	
	html += '</select>';
	
	getElementById('categories').innerHTML = html;
	
}
