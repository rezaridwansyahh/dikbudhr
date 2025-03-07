<?php
$modal_id = uniqid("modal_");
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/absen/leaflet.css" />
<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="<?php echo base_url(); ?>assets/js/absen/leaflet.js"></script>
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
                    <div id="mapid" class="col-md-12" style="height:320px">
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
    Tap = function() {
    var renderMap = function(idContainer) {
        var $container = $("#" + idContainer);
        var popup = L.popup();
        var map = L.map('mapid', {
            center: [51.505, -0.09],
            zoom: 13
        });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        $("#message").html('');
    
        function geolocationErrorOccurred(geolocationSupported, popup, latLng) {
            $(".btn-tap-absen", $container).hide();
            if (geolocationSupported) {
                $("#message").html('<b>Error:</b> The Geolocation service failed.');
            } else $("#message").html('<b>Error:</b> This browser doesn\'t support geolocation.');

            popup.setLatLng(latLng);
            popup.setContent(geolocationSupported ?
                '<b>Error:</b> The Geolocation service failed.' :
                '<b>Error:</b> This browser doesn\'t support geolocation.');
            popup.openOn(geolocationMap);
        }
        var latLng = {};
        if (navigator.geolocation) {
            $("#message").html('Process pencarian lokasi...');
            navigator.geolocation.getCurrentPosition(function(position) {
                $("#message").html('');
                $(".btn-tap-absen", $container).show();
                // console.log(position);
                latLng = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                popup.setLatLng(latLng);
                popup.setContent('Lokasi Anda ');
                popup.openOn(map);
                $("#info").html(latLng.lat + "," + latLng.lng);
                $.post("http://localhost/dikbudhrd/izin_pegawai/absen/check_area", latLng, function(o) {
                    if (o.success) {
                        $("#info").html(latLng.lat + "," + latLng.lng + " (<b>" + o.message + "</b>)");
                    } else {
                        $("#info").html(latLng.lat + "," + latLng.lng + " (<b>" + o.message + "</b>)");
                    }
                }, 'json');
                map.setView(latLng);
            }, function() {
                geolocationErrorOccurred(true, map.getCenter());
            });
        } else {
            //No browser support geolocation service
            geolocationErrorOccurred(false, map.getCenter());
        }
 
    }
    return {
        init: function(idContainer) {
            renderMap(idContainer);
        }
    }

}();
    $(document).ready(function(o) {
        Tap.init("<?php echo $modal_id ?>");
        $("#agent").html(navigator.userAgent);
    })

</script>