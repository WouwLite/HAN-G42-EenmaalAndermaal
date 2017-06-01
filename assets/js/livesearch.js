// $(document).ready(function(){
//     $("#filter").on("keyup", function() {
//         var value = $(this).val();
//
//         $("table tr").each(function(index) {
//             if (index !== 0) {
//
//                 $row = $(this);
//
//                 var id = $row.find("td:first").text();
//
//                 if (id.indexOf(value) !== 0) {
//                     $row.hide();
//                 }
//                 else {
//                     $row.show();
//                 }
//             }
//         });
//     });
// });




$(document).ready(function(){
    $("#filter").keyup(function(){

        // Retrieve the input field text and reset the count to zero
        var filter = $(this).val(), count = 0;

        // Loop through the comment list
        $("table tbody tr").each(function(){

            // If the list item does not contain the text phrase fade it out
            if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                $(this).fadeOut();

            // Show the list item if the phrase matches and increase the count by 1
            } else {
                $(this).show();
                count++;
            }
        });

        // Update the count
        var numberItems = count;
        $("#filter-count").text("Aantal gebruikers: "+count);
    });
});