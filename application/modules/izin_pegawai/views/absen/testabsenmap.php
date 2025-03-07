<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyByPx-qhpqoLp8tfelpEppFIedOvu2h8aM&libraries=geometry" type="text/javascript"></script>

<?php
$modal_id = uniqid("modal_");
?>
    <style>
        #mapid {
            height: 180px;
        }
    </style>
    <div class="callout danger callout-default">
       <h4>Perhatian</h4>
       <p><b>Mohon hidupkan gps/allow share loc dr hp/komputer anda</b></p>
     </div>
        
<div class="row">
    <div class="col-md-12">     
        <div class='box box-warning'>
            <div class="box-body">
                <div class='col-md-6'>
                    <strong>Lokasi Anda : </strong>
                    <span class='bold label-info' id="info" style="padding:2px"></span>
                    <br>
                    <span class='bold label-danger' id="message"></span>
                    <div id="map-canvas" class="col-md-12" style="height:320px">

                    </div>
                </div>
                <div class="col-md-6">
                    <form id="form-tap-absen">
                        <input type="hidden" name="token" value="token" />
                            <strong class=''>
                            <center><b>Transaksi lapor kehadiran Bekerja dari rumah (BDR), <?php echo $hari; ?> <?php echo $tanggal_indonesia; ?>, Waktu anda "<?php echo $WAKTU; ?>"</b></center>
                            </strong>
                        <table class='table-history table table-bordered compact table-condensed table-hover'>
                            <thead>
                                <tr>
                                    <th>
                                        Waktu
                                    </th>
                                    <th>
                                        Lokasi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                            <br>
                        Silahkan melakukan pengisian log harian melalui aplikasi <a href="http://skp.sdm.kemdikbud.go.id/skp/site/login.jsp" target="_blank">e-SKP</a>
                            
                            <a class="btn btn-block btn-social btn-dropbox btn-tap-absen" id="btn_lapor_kehadiran">
                                  <i class="fa fa-save"></i> Lapor Kehadiran BDR
                                </a>
                    </form>
                </div>
            </div>
            <div class="box-footer">
                <span id="agent"></span>
            </div>
    </div>
</div>
<script>
    var map;
function circlePath(center,radius,points){
    var a=[],p=360/points,d=0;
    for(var i=0;i<points;++i,d+=p){
        a.push(google.maps.geometry.spherical.computeOffset(center,radius,d));
    }
    return a;
}
      function initialize() {
        const myLatLng = { lat: -6.5550658, lng: 107.7619786 };
        var mapOptions = {
            center: new google.maps.LatLng(-6.5550658, 107.7619786),
          // center: new google.maps.LatLng(-6.225076, 106.802559),
          zoom: 13
        };
        map = new google.maps.Map(document.getElementById("map-canvas"),
            mapOptions);

        // new google.maps.Polygon({map:map,path:circlePath(map.getCenter(),1000,360)}) // membuat cyrcle
        var marker = new google.maps.Marker({
            position: myLatLng,
            map,
            title: "My position",
        });
        google.maps.event.addListener(marker, 'click', (function(event) {
             var infowindow = new google.maps.InfoWindow({
                    content: "My position"
                });
                infowindow.open(map, event.feature);
          })
        );
      }
      // google.maps.event.addDomListener(window, 'load', initialize);
initialize();
var corners = [[-6.5550658, 107.7619786],
               [-6.5539919, 107.7629334],
               [-6.5531153, 107.7617801],
               [-6.5534923, 107.7614837],
               [-6.5533271, 107.7612302],
               [-6.5550658, 107.7619786]
              ];
var coordinates = [];

for (var i=0; i<corners.length; i++){
    var position = new google.maps.LatLng(corners[i][0], corners[i][1]);
        
    coordinates.push(position);        
    }
function create_polygon(coordinates) {
    var icon = {
        //path: google.maps.SymbolPath.CIRCLE,
        path: "M -1 -1 L 1 -1 L 1 1 L -1 1 z",
        strokeColor: "#FF0000",
        strokeOpacity: 0,
        fillColor: "#FF0000",
        fillOpacity: 1,
        scale: 5
    };

     var polygon = new google.maps.Polygon({
        map: map,
        paths: coordinates,
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.5,
        zIndex: -1
    });

    var marker_options = {
        map: map,
        icon: icon,
        flat: true,
        draggable: true,
        raiseOnDrag: false,
        geodesic:true
    };
    
    for (var i=0; i<coordinates.length; i++){
        marker_options.position = coordinates[i];
        var point = new google.maps.Marker(marker_options);
        
        google.maps.event.addListener(point, "drag", update_polygon_closure(polygon, i));
    }
    
    function update_polygon_closure(polygon, i){
        return function(event){
            console.log(event.latLng.lat() ,'--',  event.latLng.lng() );
           polygon.getPath().setAt(i, event.latLng); 
        }
    }
};
create_polygon(coordinates);
</script>