<div id="md_set_map_canvas" style="max-width:100%; height:100%; margin: 0; padding: 0;"></div>
<?php
echo Form::hidden(array('name' => 'gMapLatitude', 'id' => 'gMapLatitude', 'value' => $this->latitude));
echo Form::hidden(array('name' => 'gMapLongitude', 'id' => 'gMapLongitude', 'value' => $this->longitude));
?>
<div class="custom-search">
    <input class="form-control"id="txtLat" type="text" placeholder="Latitude">
    <input class="form-control" id="txtLng" type="text" placeholder="Longitude">
    <button class="custom-location-search btn"><span class="icon-search4"></span></button>
</div>

<input id="pac-input" style=""></input>
<style type="text/css">
.custom-search {
    position: absolute;
    z-index: 1;
    top: 94%;
    padding: 1px;
}
.custom-search button span {
    font-size: 12px;
}
.custom-search button {
    padding: 2px 6px;
    margin-left: 0;
    top: -1px;
    border-radius: 0;
    position: relative;
    background: #e2696a;
    color: #fff;
    margin-top: 0px;
    border-color: #e2696a;
}
.custom-search input {
    padding: 2px 5px;
    display: inline-block;
    border-radius: 0;
    height: auto;
    width: inherit;
}
</style>
<script type="text/javascript">
    
    $(function() {
        if (window.google && google.maps) {
            initialize();
        } else {
            $.getScript("https://maps.google.com/maps/api/js?libraries=places&sensor=true&callback=initialize&key=" + gmapApiKey + "&language=mn").done(function() {
                initialize();
            });
        }
    });
    
    function initialize() {
      
        var myOption = new google.maps.LatLng(<?php echo $this->latitude; ?>, <?php echo $this->longitude; ?>);

        var mapOptions = {
            zoom: 6,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: myOption
        };
        map = new google.maps.Map(document.getElementById('md_set_map_canvas'), mapOptions);
  
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(input);

        var marker = new google.maps.Marker({
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            position: myOption,
            icon: 'mdcommon/svgIconByColor/<?php echo $this->markerColor; ?>/marker'
        });

        map.addListener('click', function(event) {
            marker.setMap(null);
            marker = new google.maps.Marker({
                position: event.latLng,
                map: map,
                icon: 'mdcommon/svgIconByColor/<?php echo $this->markerColor; ?>/marker',
                draggable: true
            });
            $("#gMapLatitude").val(event.latLng.lat());
            $("#gMapLongitude").val(event.latLng.lng());
        });

        google.maps.event.addListener(marker, 'dragend', function (event) {
            $("#gMapLatitude").val(event.latLng.lat());
            $("#gMapLongitude").val(event.latLng.lng());
        });

        searchBox.addListener('places_changed', function() {
            
            var places = searchBox.getPlaces();
            if (places.length == 0) {
                return;
            }

            marker.setMap(null);

            var bounds = new google.maps.LatLngBounds();
            
            places.forEach(function(place) {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                
                var icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                };

                // Create a marker for each place.
                marker = new google.maps.Marker({
                    map: map,
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    position: place.geometry.location
                });

                $("#gMapLatitude").val(marker.position.lat());
                $("#gMapLongitude").val(marker.position.lng());

                google.maps.event.addListener(marker, 'dragend', function (event) {
                    $("#gMapLatitude").val(event.latLng.lat());
                    $("#gMapLongitude").val(event.latLng.lng());
                });

                // marker.position.lat();
                // markers.push();
                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });
        
        $(document.body).on('click', '.custom-location-search', function() {
          
            var latlng = {lat: parseFloat($("#txtLat").val()), lng: parseFloat($("#txtLng").val())};
            map.setCenter(latlng);
            var marker = new google.maps.Marker({
                position: latlng,
                map: map, 
                icon: 'mdcommon/svgIconByColor/<?php echo $this->markerColor; ?>/marker',
                draggable: true
            });
            
            $("#gMapLatitude").val(marker.getPosition().lat());
            $("#gMapLongitude").val(marker.getPosition().lng());
        });
    }
</script>
