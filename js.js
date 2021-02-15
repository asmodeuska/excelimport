var file;
function storeFile(){
	file = document.getElementById('file').files[0];
}
function saveTable(){
	myTable = document.getElementsByTagName("table")[0];
	myClone = myTable.cloneNode(true);
}

function reset(){
	document.getElementById("table").innerHTML = "";
	document.getElementById("table").appendChild(myClone);
	saveTable();
}

function visible(num){
	var c = document.getElementById(num).hidden;
	//console.log(c);
	if (c){
		document.getElementById(num).hidden=false;
	}
	else{
		document.getElementById(num).hidden=true;
	}
}

function toDB(num){
	var valid = $('#form_'+num)[0].checkValidity();
	if(valid){
		var select = $("select");
		var form = $('#form_'+num).find(select);
		arr = [];
		form.each(function(index){
			arr[index]=$(this).find(":selected").val();
		})
		var tableName = document.getElementById('DBtableName'+num).value;
		var title = document.getElementById("title"+num).value;
		var formData = new FormData();
		formData.append('arr',JSON.stringify(arr));
		formData.append('sheetNumber',num);
		formData.append('file',file);
		formData.append('tableName',tableName);
		document.getElementById("progress"+num).innerHTML="Importalas adatbazisba...";
		$.ajax({
			type: "POST",
			url: "toDB.php",
			contentType: false,
			cache: false,
			processData:false,
			data: formData,
			success: function (data) {
				  document.getElementById("progress"+num).innerHTML=data;
			},
		  });
	  }
}



function toPDF(num){
	document.getElementById("progress"+num).innerHTML="Importalas PDFbe...";
	var formData = new FormData();
	formData.append('num', num);
	formData.append('file', file);
		$.ajax({
		type: "POST",
		url: "toPDF.php",
		contentType: false,
		cache: false,
	    processData:false,
		data: formData,
		success: function (data) {
			document.getElementById("progress"+num).innerHTML="Kész!";
		},
	});
}

$(document).ready(function() {
  $("#file").change(function(){
	var f = document.getElementById('file').files[0];
	if(f){
    $('#content').html("");
	var formData= new FormData();
	formData.append('file',f)
  $.ajax({
	type: "POST",
	url: "r.php",
	contentType: false,
     cache: false,
   processData:false,
	data: formData,
	success: function (data) {
		$("#content").append(data);
		storeFile();
	},
  });
  }
});
});