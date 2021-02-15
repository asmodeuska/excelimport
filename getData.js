
function printNames(name){
	$("#fullTable").append(name);
}

function createTable(json,i){
	if(("#"+i).length!=0){
		$("#"+i).remove();
	}
	$("#content").append('<div class="border border-2 bg-info border-primary rounded mb-3" id="'+i+'"></div>');
	$("#"+i).append('<h3>['+i+'] összesen <span id="row_counter'+i+'">'+json[i].length+'</span> sor</h3>');
	$("#"+i).append('<div class="d-flex" id="filter_'+i+'"> </div>');
	$("#"+i).append('<div id="table_'+i+'"> </div>');
	$("#"+i).append('<div class="d-flex" id="buttons_'+i+'"> </div>');
	$("#buttons_"+i).html("");
	$("#filter_"+i).html("");
	$("#buttons_"+i).append('<input class="btn my-1 mx-1 p-2 btn-success" type="button" value="xlsx letöltése" id="download-xlsx'+i+'">');
	$("#buttons_"+i).append('<input class="btn my-1 mx-1 p-2 btn-success" type="button" value="pdf letöltése" id="download-pdf'+i+'">');
	$("#buttons_"+i).append('<button class="btn my-1 mx-1 p-2 btn-light" id="add-row'+i+'">Új sor</button>');
	$("#buttons_"+i).append('<div class="ml-auto p-2 my-1 mx-1" id="notification_'+i+'"></div>');
	$("#buttons_"+i).append('<button class="btn ml-auto p-2 my-1 mx-1 btn-light" id="update_'+i+'">Módosítások mentése</button>');

	$("#filter_"+i).append('<select class="my-1 mx-1 p-2" id="filter-field'+i+'"><option></option>');
	/*$.each(json['columns'+i], function(key,value) {
	});
	for (var colls of c){
				$("#filter-field"+i).append('<option value="'+colls+'">'+colls+'</option');
			}*/
	json['columns'+i].map(obj=>{
		$("#filter-field"+i).append('<option value="'+obj+'">'+obj+'</option');
	});
	$("#filter-field"+i).append('</select>');
	$("#filter_"+i).append('<select class="my-1 mx-1 p-2" id="filter-type'+i+'">');
	$("#filter-type"+i).append('<option value="like">like</option>');
	$("#filter-type"+i).append('<option value="<"><</option>');
	$("#filter-type"+i).append('<option value="<="><=</option>');
	$("#filter-type"+i).append('<option value=">">></option>');
	$("#filter-type"+i).append('<option value=">=">>=</option>');
	$("#filter-type"+i).append('<option value="!=">!=</option>');
	$("#filter-type"+i).append('<option value="=">=</option>');
	$("#filter_"+i).append('</select>');
	$("#filter_"+i).append('<input class="my-1 mx-1 p-2" id="filter-value'+i+'" type="text" placeholder="szűrni való érték"></div>');
	$("#filter_"+i).append('<button class="my-1 mx-1 p-2 btn btn-light" type="button" id="filter-clear'+i+'">Filter törlés</button>');
	$("#filter_"+i).append('<button class="my-1 mx-1 p-2 btn btn-light" id="clear-row'+i+'">Össze sor törlése</button>');
	$("#filter_"+i).append('<button class="my-1 mx-1 p-2 btn btn-danger" id="clear-table'+i+'">Tábla törlése</button>');
	$("#filter_"+i).append('<h6 class="ml-auto my-1 mx-1 p-2" id="filter_count'+i+'"></h6>')
	$("#filter_"+i).append('<input class="ml-auto my-1 mx-1 p-2" placeholder="keresés a táblában..." type="text" id="input-text'+i+'">');
	
	var fieldEl = document.getElementById("filter-field"+i);
	var typeEl = document.getElementById("filter-type"+i);
	var valueEl = document.getElementById("filter-value"+i);


	//Trigger setFilter function with correct parameters
	function updateFilter(){
		var filterVal = fieldEl.options[fieldEl.selectedIndex].value;
		var typeVal = typeEl.options[typeEl.selectedIndex].value;
		
		var filter = filterVal == "function" ? customFilter : filterVal;

		if(filterVal == "function" ){
		typeEl.disabled = true;
		valueEl.disabled = true;
		}
		else{
		typeEl.disabled = false;
		valueEl.disabled = false;
		}
		if(filterVal){
		table.setFilter(filter,typeVal, valueEl.value);
		}
		$("#filter_count"+i).html(table.getDataCount("active")+" sor");
	}

	function searchFilter(){
		var filters = [];
		var columns = table.getColumns();
		var search = $("#input-text"+i).val();
		if(!search){
			table.clearFilter();
			$("#filter_count"+i).html(table.getDataCount("active")+" sor");
		}
		else{
			columns.forEach(function(column){
				filters.push({
					field:column.getField(),
					type:"like",
					value:search,
				});
			});

			table.setFilter([filters]);
			$("#filter_count"+i).html(table.getDataCount("active")+" sor");
		}
	}



	//Update filters on value change
	document.getElementById("filter-field"+i).addEventListener("change", updateFilter);
	document.getElementById("filter-type"+i).addEventListener("change", updateFilter);
	document.getElementById("filter-value"+i).addEventListener("keyup", updateFilter);
	document.getElementById("input-text"+i).addEventListener("keyup", searchFilter);


	//Clear filters on "Clear Filters" button click
	document.getElementById("filter-clear"+i).addEventListener("click", function(){
		fieldEl.value = "";
		typeEl.value = "like";
		valueEl.value = "";
		table.clearFilter();
	});



	document.getElementById("clear-row"+i).addEventListener("click", function(){
		table.clearData();
	});

	document.getElementById("clear-table"+i).addEventListener("click", function(){
		var tableName = i;
		$.ajax({
			type: "POST",
			url: "deleteTable.php",
			data: {i : i},
			success: function (resp) {
				$("#"+i).remove();
				$("#tr_"+i).remove();
				alert(resp);
			},
		  });
	});

	document.getElementById("update_"+i).addEventListener("click", function(){
		data = table.getData();
		var formData = new FormData();
		formData.append('table',JSON.stringify(data));
		formData.append('tableName',i);
		//console.log(data);
		$.ajax({
			type: "POST",
			url: "updateTableDB.php",
			contentType: false,
			cache: false,
			processData:false,
			data: formData,
			success: function (resp) {
			//console.log(resp);
				$("#notification_"+i).html("");
				$("#notification_"+i).append(resp);
				setTimeout(function() { 
					$("#notification_"+i).html("");
				}, 5000);
			},
		  });
		var data = table.getData();
		
	});

	document.getElementById("add-row"+i).addEventListener("click", function(){
		table.clearFilter();
		var tmp = {};
		json['columns'+i].map(obj=>{
			tmp[obj]="";
		});
		table.addRow(tmp);
	});
	document.getElementById('download-xlsx'+i).addEventListener("click", function(){
		table.download("xlsx", i+".xlsx", {sheetName:i});
	});
	document.getElementById('download-pdf'+i).addEventListener("click", function(){
		table.download("pdf", i+".pdf");
	});

	var table = new Tabulator("#table_"+i, {
		data:json[i], 
		columns:json['columns'+i].map(obj => {
			return {
			  title: obj,
			  field: obj,
			  sorter: "string",
			  hozAlign: "center",
			  editor: true,
 			  formatterParams:{
				allowEmpty:true
			  }
			};
			
		  }),
		addRowPos:"top",
		history:true, 
		movableColumns:true,
		resizableRows:true,
		layout:"fitColumns",
		pagination:"local",
		paginationSize:50,
		paginationSizeSelector:[5, 10, 15, 20, 50, 100, 250, 500],

	});
	table.addColumn(
		{title:"Törlés", formatter:"buttonCross", download:false, hozAlign: "center", width:80, cellClick:function(e, cell){
				cell.getRow().delete();
				var b = $("#row_counter"+i).text();
				var a = parseInt(b);
				$("#row_counter"+i).html(--a);
				var b = $("#filter_count"+i).text();
				var a = parseInt(b);
				$("#filter_count"+i).html(--a);
				
			}}
	);
	table.redraw(true);
	
}

$( document ).ready(function() {
    $.ajax({
	type: "POST",
	url: "getTableNames.php",
	success: function (data) {
		if(data.length!=0){
			var json = $.parseJSON(data);
			if (json.length>0){
				//$("#searchDiv").append('<form><input type="text" id="searchBar" name="search"><input type="submit" value="Keresés"></form>');
				var c = '<div class="d-flex justify-content-center"><form id="requestTable"><table id="fullTable" class="table table-hover table-striped table-bordered"><tr><td class="text-center align-middle ">Táblanév</td><td class="text-center align-middle "> <label for="select-all">Összes Kiválasztása</label><br> <input type="checkbox"  id="select-all"> </td></tr></table></div><div class="d-flex justify-content-center"><input type="button" id="lekerdez" class="btn btn-primary mb-2" value="Lekérdezés" name="lekerdez"></div></form>';
				$("#tableSelect").append(c);
			}
			for (var tables of json){
				printNames(tables);
			} 
		}
	},
	});
});



$(document).on("click", "#lekerdez", function () {
	if (!$('.isOneChecked').is(':checked')) {
		return;
	}
	var arr = $('#requestTable').serializeArray();
	for(var i= 0; i<arr.length; i++){
		$.ajax({
			type: "POST",
			data: arr[i],
			url: "printTables.php",
			success: function (data) {
				//console.log(data);
				data = JSON.parse(data);
				//console.log(data);
				//console.log(Object.keys(data)[0]);
				createTable(data,Object.keys(data)[0]);		
			},
		});
	}
  });

$(document).on("click", "#select-all", function () {   
    if(this.checked) {
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true;                        
        });
    } else {
        $(':checkbox').each(function() {
            this.checked = false;                       
        });
    }
});

