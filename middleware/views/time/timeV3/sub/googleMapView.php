<div id="timeBalanceGoogleMapView" style="width:100%; height:450px"></div>

<script type="text/javascript">
    $(function(){            
        var drawMap = function(){
          var map=new google.maps.Map(document.getElementById('timeBalanceGoogleMapView'), {
            zoom: 16.5,
            center: {lat: <?php echo $this->getRow['lat']; ?>, lng: <?php echo $this->getRow['long']; ?>}
          });
          var image = {
            url: 'assets/tes/img/mapicon.png',
            anchor: new google.maps.Point(0, 32)
          };

          //var infowindow=new google.maps.InfoWindow();
            /*var img='assets/tes/img/icon-tec-map.png', address='', r92='', p95='', n80='', tulsh='', gaz='';
            var tmpMapMarker=mapMarkers[i];
            if(tmpMapMarker.adminpassword === 1){
              img='assets/tes/img/icon-tecBaaz-map.png';
            }
            if(tmpMapMarker.r92 !== null){
              r92='<br><b>' + r92_string + ' : </b>' + tmpMapMarker.r92;
            }
            if(tmpMapMarker.p95 !== null){
              p95='<br><b>' + p95_string + ' : </b>' + tmpMapMarker.p95;
            }
            if(tmpMapMarker.n80 !== null){
              n80='<br><b>' + n80_string + ' : </b>' + tmpMapMarker.n80;
            }
            if(tmpMapMarker.tulsh !== null){
              tulsh='<br><b>' + tulsh_string + ' : </b>' + tmpMapMarker.tulsh;
            }
            if(tmpMapMarker.gaz !== null){
              gaz='<br><b>' + gaz_string + ' : </b>' + tmpMapMarker.gaz;
            }
            if(tmpMapMarker.addressline1 !== null){
              address='<br><b>Хаяг : </b>' + tmpMapMarker.addressline1;
            }
            var contentString='<div style="width: 300px;"><img width="80px" src="' + img +
                    '" align="left" style="margin-right:5px"><div class="" style="padding-left: 85px;"><b style="text-transform:uppercase;">' +
                    tmpMapMarker.storename +
                    '</b>' + address + ' <br><b>Цагийн хуваарь : </b> 24/7 '
                    + r92 + p95 + n80 + tulsh + gaz + '</div></div>';*/
            
            var contentString='';
            var marker=new google.maps.Marker({
              position: {lat: <?php echo $this->getRow['lat']; ?>, lng: <?php echo $this->getRow['long']; ?>},
              animation: google.maps.Animation.DROP,
              map: map,
              //icon: image,
              title: 'Цаг бүртгэсэн байршил'
            });

            /*google.maps.event.addListener(marker, 'click', (function(marker, contentString){
              return function(){
                infowindow.setContent(contentString);
                infowindow.open(map, marker);
              };
            })(marker, contentString));*/
        };
        
        $.getScript("https://maps.google.com/maps/api/js?key="+gmapApiKey+"&libraries=drawing&sensor=true&language=mn").done(function(){
          drawMap();
        });
    });
</script>