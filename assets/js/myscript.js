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
            myCols.push({"data": "first_name", "title" : "meno", "orderData": [ 0, 1 ]});
            myCols.push({"data": "last_name", "title": "priezvisko", "orderData": [ 1, 0 ]});

            let skip = 2;
            for (let i in columns){
                console.log(columns[i])
                if (skip > 0){
                    skip--;
                    continue;
                }
                if (columns[i] != "attendance" && columns[i] != "sum_minutes" && columns[i] != "first_name" && columns[i] != "last_name")
                    myCols.push({"data": columns[i], "title": "Prednaska " + (i-2) + getDateFromCsvString(columns[i]) });
            }

            myCols.push({"data": "attendance", "title": "Dochádzka"});
            myCols.push({"data": "sum_minutes", "title": "Suma minút"});

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

// string like: 20210216_AttendanceList_WebTe2
function getDateFromCsvString(str)
{
    return "<br>" + str.substr(6,2) + "." + str.substr(4,2) + ". " + str.substr(0,4);
}

