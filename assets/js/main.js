function updateNotification() {
    $.ajax({
        url: '../gis/functions.php',
        data: {
            couche: 'cercificats', status : 'attente'
        },
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var notificationNombre = $('#Attentenombre');
            var Attentenombre = response.length; //nombre sur le tableau geojson
            notificationNombre.text(Attentenombre);
            var DemandeAttenteTitleNot = $('#DemandeAttenteTitleNot');
            DemandeAttenteTitleNot.text(Attentenombre + ' demandes en attente');

            var demandeAttentNot = $('#demandeAttentNot');
            demandeAttentNot.empty();
            var span = $('<span>').addClass('dropdown-item dropdown-header');
            span.text(Attentenombre + ' demandes en attente');
            demandeAttentNot.append(span);

            $.each(response.slice(0, 5), function (index, data){
                var coordinates = data.centroid.replace('POINT(', '').replace(')', '').split(' ');
                var lon = parseFloat(coordinates[0]);
                var lat = parseFloat(coordinates[1]);

                var div = $('<div>').addClass('dropdown-divider');
                var a = $('<a>').addClass('dropdown-item');
                a.text(data.numdemande);
                a.css('cursor', 'pointer');
                a.attr('href', 'maps.php?lat=' + lat + '&lon=' + lon)
                demandeAttentNot.append(div);
                demandeAttentNot.append(a);
            });
        },
        error: function() {
            console.log('Error retrieving notifications');
        }
    });

    $.ajax({
        url: '../gis/functions.php',
        data: {
            couche: 'cercificats', status : 'validee'
        },
        type: 'get',
        dataType: 'json',
        success: function(response) {
            var notificationNombre = $('#Valideenombre');
            var Valideenombre = response.length; //nombre sur le tableau geojson
            notificationNombre.text(Valideenombre);
            var DemandeVlideeTitleNot = $('#DemandeVlideeTitleNot');
            DemandeVlideeTitleNot.text(Valideenombre + ' demandes validée');

            var demandeValideeNot = $('#demandeValideeNot');
            demandeValideeNot.empty();
            var span = $('<span>').addClass('dropdown-item dropdown-header');
            span.text(Valideenombre + ' demandes en attente');
            demandeValideeNot.append(span);
            
            $.each(response.slice(0, 5), function (index, data){
                var coordinates = data.centroid.replace('POINT(', '').replace(')', '').split(' ');
                var lon = parseFloat(coordinates[0]);
                var lat = parseFloat(coordinates[1]);

                var div = $('<div>').addClass('dropdown-divider');
                var a = $('<a>').addClass('dropdown-item');
                a.text(data.numdemande);
                a.css('cursor', 'pointer');
                a.attr('href', 'maps.php?lat=' + lat + '&lon=' + lon)
                demandeValideeNot.append(div);
                demandeValideeNot.append(a);
            });
        },
        error: function() {
            console.log('Error retrieving notifications');
        }
    });
}

$(document).ready(function() {
    updateNotification(); // Appel initial de la fonction pour afficher les notifications
    setInterval(updateNotification, 5000); // Actualisation des notifications toutes les 5 secondes
});