$(document).ready(function(){
    //alert("Hiii");
    var db_name;

    $("#database").change(function(){
        $("#servers").empty();
        $("#info").empty();
        db_name = $(this).val();
        //alert("Fetching Tables");
        //var jsonMimeType = "application/json;charset=UTF-8";
        $.ajax({
            url: './fetch_servers.php',
            type: "POST",
            
            data: JSON.stringify({"db_name":db_name}),
            contentType: "application/json",
            dataType: JSON.stringify(),
            
            success:function(response){
                response = JSON.parse(response);
                for(var key in response) {
                  //alert(key);
                  //alert(response[key]);
                  $("#servers").append(`<input type="checkbox" class =" checkbox" name="server" id='${response[key]}' value='${response[key]}'>${response[key]}<br>`);

                }    
                
            },
            error:function(xhr){
                var jsonResponse = JSON.parse(xhr.responseText);
                $(".alert").html(jsonResponse.message);
            }
        });
        
    });

   //select all checkboxes
    $("#select_all").change(function(){  //"select all" change 
        $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
    });

  

    $("#submitButton").click(function(){
        // alert("Hi---2");
        var selectedServers = [];
        $.each($("input[name='server']:checked"), function(){
            selectedServers.push($(this).val());
        });

        $.ajax({
            url: './fetch_info.php',
            type: "POST",
            data: JSON.stringify({"selected_servers":selectedServers, "db_name":db_name}),
            contentType: "application/json",
            dataType: JSON.stringify(),
            
            success:function(response){
                $("#info").append( ` <thead><tr><th>Server Name</th><th>Speed In GHz</th><th>Memory In Bytes</th></tr></thead>`);
                var table = $('#info').DataTable();
                response = JSON.parse(response);
                
                for(var key in response) {
                    var row = [];
                    for(var k in response[key]){
                        row.push(response[key][k]);
                    }
                    table.row.add(row).draw();

                }    

            },
            error:function(xhr){
                var jsonResponse = JSON.parse(xhr.responseText);
                $(".alert").html(jsonResponse.message);
            }
        });
        
    });




  
});



