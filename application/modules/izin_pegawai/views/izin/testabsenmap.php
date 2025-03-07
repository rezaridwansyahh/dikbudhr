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
var map = null;
var bounds = null;

function initialize() {
  var myOptions = {
    zoom: 10,
    center: new google.maps.LatLng(-6.225076, 106.802559),
    mapTypeControl: true,
    mapTypeControlOptions: {
      style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
    },
    navigationControl: true,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  map = new google.maps.Map(document.getElementById("map-canvas"),
    myOptions);

  bounds = new google.maps.LatLngBounds();

  var donut = new google.maps.Polygon({
    paths: [drawCircle(new google.maps.LatLng(-6.225076, 106.802559), 0.1, 1)
    ],
    strokeColor: "#0000FF",
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: "#FF0000",
    fillOpacity: 0.35
  });
  donut.setMap(map);

  map.fitBounds(bounds);
}
google.maps.event.addDomListener(window, 'load', initialize);

function drawCircle(point, radius, dir) {
  var d2r = Math.PI / 180; // degrees to radians 
  var r2d = 180 / Math.PI; // radians to degrees 
  var earthsradius = 3963; // 3963 is the radius of the earth in miles

  var points = 32;

  // find the raidus in lat/lon 
  var rlat = (radius / earthsradius) * r2d;
  var rlng = rlat / Math.cos(point.lat() * d2r);


  var extp = new Array();
  if (dir == 1) {
    var start = 0;
    var end = points + 1
  } // one extra here makes sure we connect the
  else {
    var start = points + 1;
    var end = 0
  }
  for (var i = start;
    (dir == 1 ? i < end : i > end); i = i + dir) {
    var theta = Math.PI * (i / (points / 2));
    ey = point.lng() + (rlng * Math.cos(theta)); // center a + radius x * cos(theta) 
    ex = point.lat() + (rlat * Math.sin(theta)); // center b + radius y * sin(theta) 
    extp.push(new google.maps.LatLng(ex, ey));
    bounds.extend(extp[extp.length - 1]);
  }
  // alert(extp.length);
  return extp;
}
</script>