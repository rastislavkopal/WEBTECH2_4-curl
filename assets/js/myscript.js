$(document).ready( function () {
    showUsers();
} );

function displayMessage(response)
{
    if($('#uploader_div').css('display') != 'none')
        $('#uploader_div').hide('slow');
    var message = $('<div class="alert alert-error error-message" style="display: none;">');
    var close = $('<button type="button" class="close" data-dismiss="alert">&times</button>');
    message.append(close); // adding the close button to the message
    message.append(response);
    message.appendTo($('body')).fadeIn(300).delay(3000).fadeOut(1500);
}

function showUsers()
{

    $.getJSON('https://wt78.fei.stuba.sk/zadanie4/controllers/api_records.php', function(json) {
            console.log(json)
            let columns = Object.keys(json[0])

            var myCols = [];
            myCols.push({"data": "id", "title": "ID"});
            myCols.push({"data": "first_name", "title" : "meno"});
            myCols.push({"data": "last_name", "title": "priezvisko"});

            let skip = 3;
            for (let i in columns){
                if (skip > 0){
                    skip--;
                    continue;
                }
                myCols.push({"data": columns[i], "title": "Prednaska " + (i-2)});
            }


            $("#table_id").DataTable({
                data: json,
                "searching": false,
                "paging": false,
                "bInfo": false,
                "scrollY":"80%",
                "scrollCollapse": true,
                "destroy": true,
                "columns": myCols,
                "order": [[1, 'asc']]
            });
    });
}

