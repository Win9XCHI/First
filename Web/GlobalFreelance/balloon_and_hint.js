var map;
		function initialize() {
		  var mapOptions = {
			zoom: 8,
			center: new google.maps.LatLng(48.45, 35.02)
		  };
		  map = new google.maps.Map(document.getElementById('map'), mapOptions);
		}
        google.maps.event.addDomListener(window, 'load', initialize);
        
        function SetMarkers(uluru, name) {
            var marker = new google.maps.Marker({
          position: uluru,
          map: map,
            title: name
             });
        }
        
        var uluru = {lat: 31.89, lng: 49.20};
        var name = 'ім. Т. Шевченка';
        SetMarkers(uluru, name);
        name = 'Київ';
        uluru = {lat: 30.48, lng: 50.43};
        SetMarkers(uluru, name);
        google.maps.event.addListener(marker, 'click', function() {});
































