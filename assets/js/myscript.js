$(document).ready( function () {

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


// function createPlacement()
// {
//     let data = $('#formAddPlacement').serializeArray();
//
//     for (let i = 0; i < data.length; i++)
//         if (!data[i].value.length) {
//             displayMessage("Hodnota '" + data[i]["name"] + "' nesmie byt prazdna");
//             return;
//         }
//
//     var formData = {
//         "person_id": data[0]["value"],
//         "oh_id": data[1]["value"],
//         "placing": data[2]["value"],
//         "discipline": data[3]["value"]
//     };
//
//     $.ajax({
//         url: 'https://wt78.fei.stuba.sk/zadanie2/controllers/AddPlacement.php',
//         type: 'POST',
//         data: formData,
//         dataType: 'text',
//        success: displayMessage,
//     });
// }
