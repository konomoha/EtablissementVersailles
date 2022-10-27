$(document).ready(function(){
    var map = L.map('map').setView([48.79866947468732, 2.1310106623163603], 13);
    console.log(map);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright%22%3EOpenStreetMap</a>'
    }).addTo(map);

    let path = "{{ path('app_map')}}";
    $.ajax({
        type : 'POST',
        url : path,
        datatype : 'json',
        success : function(response)
        {
            console.log(response);
        },
        error : function(){
            console.log("marche pas")
        }
    })
    for(let i = 0; i>length(response); i++)
    {
        L.marker([response.latitude, response.longitude]).addTo(map)
        .bindPopup('A pretty CSS3 popup.<br> Easily customizable.')
        .openPopup();
    }
})