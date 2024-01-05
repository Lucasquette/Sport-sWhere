$(document).ready(function() {
    
    if (typeof map === 'undefined') {
        console.error('La carte Mapbox n\'est pas initialisée.');
        return;
    }

    var transportMode = 'driving'; // Mode de transport par défaut
    var currentMarkers = [];
    var nbRecherchePlusLoin = 1;

    $('.btnTransport').click(function() {
        $('.btnTransport').removeClass('active');
        $(this).addClass('active');

        switch ($(this).attr('id')) {
            case 'car':
                transportMode = 'driving';
                break;
            case 'bike':
                transportMode = 'cycling';
                break;
            case 'walk':
                transportMode = 'walking';
                break;
        }
    });

    $('#btn-go').click(function() {
        removeCurrentMarkers();

        var sport = $('#input-sport').val();
        var adresse = $('#input-adresse').val();
        var mapboxAccessToken = 'pk.eyJ1IjoibHVjYXNxdWV0dGVzIiwiYSI6ImNscTd5dDRjNjFjY2cyamt6cTF5OWswc2EifQ.fGb8-XeCWUNgclOcz7HXHQ';

        
        
        

        // Géocoder l'adresse
        var geocodingUrl = `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(adresse)}.json?country=FR&access_token=${mapboxAccessToken}`;

        $.ajax({
            url: geocodingUrl,
            method: 'GET',
            success: function(geocodingData) {
                if (geocodingData.features && geocodingData.features.length > 0) {
                    var startLocation = geocodingData.features[0].center; // [longitude, latitude]
                    $('#btn-plusloin').show();
                    // Centrer la carte sur le point de départ et ajuster le zoom
                    map.flyTo({
                        center: startLocation,
                        zoom: 14
                    });

                    // Ajouter un marqueur pour le point de départ
                    addMarkerDepart(startLocation, '#13B2E2', 'Départ');
                    
                    findClosestSportLocation(startLocation, sport, mapboxAccessToken);
                } else {
                    console.error('Adresse non trouvée.');
                }
            },
            error: function(err) {
                console.error('Erreur de géocodage:', err);
            }
        });



        $('#btn-plusloin').click(function(){
            findClosestSportLocation
        })
    });


    function findClosestSportLocation(adresseCoords, sport, accessToken) {
        $.ajax({
            url: './PHP/commInfra_sport.php',
            type: 'POST',
            data: { sport: sport },
            dataType: 'json',
            success: function(sportLocations) {
                var closestLocation = getClosestLocation(adresseCoords, sportLocations);
                if (closestLocation) {
                    console.log('Le lieu de sport le plus proche est:', closestLocation);
                    var endLocation = parseCoords(closestLocation.col94);
                    addMarkerArriver(endLocation, closestLocation.col2, closestLocation.col96);
                
                    getDirections(adresseCoords, endLocation, accessToken);
                }
            },
            error: function(xhr, status, error) {
                console.error("Erreur AJAX : ", error);
            }
        });
    }

    function getDirections(startCoords, endCoords, accessToken) {
        
        var directionsUrl = `https://api.mapbox.com/directions/v5/mapbox/${transportMode}/${startCoords[0]},${startCoords[1]};${endCoords[0]},${endCoords[1]}?geometries=geojson&access_token=${accessToken}`;

        


        fetch(directionsUrl)
            .then(response => response.json())
            .then(data => {
                // Afficher l'itinéraire sur la carte
                var route = data.routes[0].geometry;

                // Vérifier si une source d'itinéraire existe déjà
                if (map.getSource('route')) {
                    
                    map.removeLayer('route');
                    map.removeSource('route');
                }

                // Ajouter la source d'itinéraire
                map.addSource('route', {
                    'type': 'geojson',
                    'data': {
                        'type': 'Feature',
                        'properties': {},
                        'geometry': route
                    }
                });

                // Ajouter la couche d'itinéraire
                map.addLayer({
                    'id': 'route',
                    'type': 'line',
                    'source': 'route',
                    'layout': {
                        'line-join': 'round',
                        'line-cap': 'round'
                    },
                    'paint': {
                        'line-color': '#114B80',
                        'line-width': 7
                    }
                });

                var temps = data.routes[0].duration;
                temps = Math.round(temps / 60); 
                var distance = data.routes[0].distance; 
                distance = (distance / 1000).toFixed(2); 
    
                var modeTransport;
                switch(transportMode){
                    case 'driving' :
                        modeTransport = 'en voiture';
                        break;
                    case 'cycling': 
                        modeTransport = "à vélo";
                        break;
                    case 'walking':
                        modeTransport = 'à pieds';
                        break;
                }


                $('.tmps_parcours').html(`
                    <h3>Estimation ${modeTransport}</h3>  
                    <p>Temps:   <span>${temps} minutes</span><br>
                    Distance:   <span>${distance} km</span></p>`);
            })
            .catch(err => console.error('Erreur lors de la demande d’itinéraire:', err));
    }

    function getClosestLocation(adresseCoords, locations) {
        var closest = null;
        var closestDistance = Infinity;

        locations.forEach(function(location) {
            var locationCoords = parseCoords(location.col94);
            var distance = turf.distance(adresseCoords, locationCoords, {units: 'kilometers'});
            if (distance < closestDistance) {
                closestDistance = distance;
                closest = location;
            }
        });

        return closest;
    }

    function parseCoords(coordStr) {
        var coords = coordStr.split(',').map(function(coord) {
            return parseFloat(coord.trim());
        });
        return [coords[1], coords[0]]; // Assurez-vous que l'ordre est [longitude, latitude]
    }

    function addMarkerDepart(coords, color, title) {
        var el = document.createElement('div');
        el.className = 'markerDepart';
        el.style.backgroundColor = color;
        el.style.width = '30px';
        el.style.height = '30px';
        el.style.borderRadius = '50%';
        el.title = title;

        var marker = new mapboxgl.Marker(el)
            .setLngLat(coords)
            .addTo(map);

        currentMarkers.push(marker); // Ajoute le nouveau marqueur à la liste
    }

    function addMarkerArriver(coords, nomInfrastructure, ville){
        const popup = new mapboxgl.Popup().setHTML(
            `<h3>${ville}</h3><p>${nomInfrastructure}</p>`
        );
          
        const marker = new mapboxgl.Marker()
            .setLngLat(coords)
            .setPopup(popup)
            .addTo(map);   
        
        currentMarkers.push(marker);
    }


    function removeCurrentMarkers() {
        currentMarkers.forEach(function(marker) {
            marker.remove(); // Supprime chaque marqueur de la carte
        });
        currentMarkers = []; // Réinitialise la liste des marqueurs
    }
});