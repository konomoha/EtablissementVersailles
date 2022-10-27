$(document).ready(function(){

    var map = L.map('map').setView([48.801408, 2.130122], 14);
    // console.log(map);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright%22%3EOpenStreetMap</a>'
    }).addTo(map);
    
    function affichageGlobal(){

        let myurl = $('#map').attr("data-path");

        $.ajax({
            type: 'POST',
            url: myurl,
            datatype: 'json',
            data:{
                'monstring' : 'youhou!'
            },

            success: function(response)
            {
                // console.log(response)
                affichagePoint(response);
            },
            error: function()
            {
                console.log('ça marche pas');
            }
        })
    };

    affichageGlobal();

    function affichagePoint(response){

        for(let i = 0; i < response.length; i++)
        {
            L.marker([response[i].latitude, response[i].longitude]).addTo(map)
            .bindPopup(response[i].nom)
            .openPopup();
        }
    }
    
});