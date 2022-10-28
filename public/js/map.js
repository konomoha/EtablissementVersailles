$(document).ready(function(){
    var markList = [];
    var markerTab=[];
    var map = L.map('map').setView([48.801408, 2.130122], 13.5);
    
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright%22%3EOpenStreetMap</a>'
    }).addTo(map);

    $('.bouttoncheck').click(function(){

        let type = ($(this).attr('name'));
        let checkbox = $(this);
        let myurl = $(this).attr("data-path");
        console.log(myurl);

        $.ajax({
            type: 'POST',
            url: myurl,
            datatype: 'json',
            data:{
                'monstring' : 'test string'
            },

            success: function(response)
            {

                if(checkbox.is(':checked')){

                    // console.log(response);
                    affichagePoints(response, type)
                }
                else{

                    // console.log('tout est décoché');
                    removePoints(response, type)
                };

            },
            error: function()
            {
                console.log('erreur, ça ne fonctionne pas');
            }});
    })

    function affichagePoints(response, type)
    {

        for(let i = 0; i < response.length; i++)
        { 
            
            if(response[i].type === type){

               let mark = L.marker([response[i].latitude, response[i].longitude]).bindPopup(response[i].nom)
               .openPopup();

                markerTab.push(mark);

                map.addLayer(mark);

            }  
        } 

        console.log(markerTab);
    }

    function removePoints(response, type)
    {

        for(let e = 0; e < response.length; e++){
            
            for(let i = 0; i < markerTab.length; i++){

                if(response[e].type === type){

                    if(response[e].nom === markerTab[i]._popup._content){

                        map.removeLayer(markerTab[i]);
                        markerTab.splice(i, 1);
                     }
                }
            } 
        }
        // console.log(markerTab)
    }

    $('#rechercheEtablissement').keypress(function(e){

        let keycode = (e.keyCode ? e.keyCode : e.which);
        let myurl = $(this).attr("data-path");
        // console.log(myurl);
        if(keycode == '13'){
            $.ajax({
                type: 'POST',
                url: myurl,
                datatype: 'json',
                success: function(response)
                {
                    rechercheEtablissement(response);
                },

                error: function(){
                    console.log('erreur');
                }
            })
        }
    });

    function rechercheEtablissement(response)
    {
        let search = $('#rechercheEtablissement').val();
        let searchList = [];

        if(markList){

            // console.log('ok marklist');
            for(let a = 0; a < markList.length; a++){

                map.removeLayer(markList[a]);
            }
            markList = [];
        }

        for(let i = 0; i < response.length; i++){

            if(search != "" && response[i].nom.toUpperCase().includes(search.toUpperCase())){

                if(searchList.includes(response[i].nom)){

                    console.log(response[i].nom + " est déjà présent dans searchList");
                }
                else{

                    let mark = new L.marker([response[i].latitude, response[i].longitude]).bindPopup(response[i].nom)
                    .openPopup();

                    searchList.push(response[i].nom);
                    markList.push(mark);

                    map.addLayer(mark);
                    // searchList= [];
                    
                }
            }
        }

    }

});