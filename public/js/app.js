$(document).ready(function(){
    
    function affichageGlobal(){

        let myurl = $('#testjs').attr("data-path");

        $.ajax({
            type: 'POST',
            url: myurl,
            datatype: 'json',
            data:{
                'monstring' : 'youhou!'
            },

            success: function(response)
            {
                console.log(response)
            },
            error: function()
            {
                console.log('ça marche pas');
            }
        })
    };

    // affichageGlobal();
    // id="testjs" data-path="{{path('app_test')}}"

});