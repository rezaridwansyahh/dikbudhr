<?php
$modal_id = uniqid("modal_");
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" integrity="sha512-M2wvCLH6DSRazYeZRIm1JnYyh22purTM+FDB5CsyxtQJYeKq83arPe5wgbNmcFXGqiSH2XR8dT/fJISVA1r/zQ==" crossorigin=""/>

<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="<?php echo base_url(); ?>assets/js/absen/leaflet.js"></script>
<script src="https://npmcdn.com/leaflet-geometryutil"></script>
    <style>
        #mapid {
            height: 180px;
        }
    </style>
<div class="row">
    <div class="col-md-12">     
        <div class='box box-warning'>
            <div class="box-body">
                <div class='col-md-6'>
                    <span class='bold label-danger' id="message"></span>
                    <div id="mapid" class="col-md-12" style="height:320px">
                    </div>
                </div>
                <div class="col-md-6">
                    <form id="form-tap-absen">
                        <input type="hidden" name="token" value="token" />
                        <fieldset>
                            <legend>Lokasi 1</legend>
                            <div class="control-group<?php echo form_error('NIP_PNS') ? ' error' : ''; ?> col-md-6">
                                <?php echo form_label("Titik Pusat (Lat)", 'titik_pusat', array('class' => 'control-label')); ?>
                                <div class='controls'>
                                    <input id='lat' type='text' class="form-control" required='required' name='lat' maxlength='18' value="<?php echo set_value('lat', isset($satker->lat) ? $satker->lat : ''); ?>" />
                                    <span class='help-inline'><?php echo form_error('lat'); ?></span>
                                </div>
                            </div>
                            <div class="control-group col-sm-6">
                                <label for="inputNAMA" class="control-label">Long</label>
                                <div class="input-group date">
                                    <input id='long' type='text' class="form-control" required='required' name='long' maxlength='18' value="<?php echo set_value('long', isset($satker->long) ? $satker->long : ''); ?>" />
                                    <div class="input-group-addon" id="fa-marker">
                                        <i class="fa fa-map-marker"></i>
                                      </div>

                                </div>
                            </div> 
                        </fieldset> 
                        <br>
                            <div id="info_area"></div>
                            <a class="btn btn-block btn-social btn-dropbox btn-tap-absen" id="btn_lapor_kehadiran">
                                  <i class="fa fa-save"></i> Simpan Area
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
    Tap = function() {
    var renderMap = function(idContainer) {
        var center = [-6.5550658, 107.7619786];
        var radiusInM = 20;
        var angleInDegrees = 60;
        const markerIcon = L.icon({
          iconUrl: `https://api.geoapify.com/v1/icon?size=xx-large&type=awesome&color=%233e9cfe&icon=paw&apiKey=`,
          iconSize: [31, 46], // size of the icon
          iconAnchor: [15.5, 42], // point of the icon which will correspond to marker's location
          popupAnchor: [0, -45] // point from which the popup should open relative to the iconAnchor
        });

        var $container = $("#" + idContainer);
        var popup = L.popup();
        map = L.map('mapid', {
            center: center,
            zoom: 20
        });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        $("#message").html('');
        
        // add marker radius
        var marker = L.marker(center).addTo(map);
        L.circle(marker.getLatLng(), {
            icon: markerIcon,
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.2,
            radius: radiusInM * 1
        }).addTo(map);

        for (let i = 1; i < 7; i++) {
            var digree = 60*i;
            var to = L.GeometryUtil.destination(marker.getLatLng(), digree, radiusInM * 1);
            // L.marker(to).addTo(map);
            // console.log(to.lat+" - "+to.lng);
        }
        map.on('click', addMarker);
        function addMarker(e){
            // Add marker to map at click location; add popup window
            var newMarker = new L.marker(e.latlng).addTo(map);
            addcirclemarker(newMarker);
            // console.log(e.latlng);
        }
        function addcirclemarker(marker){
            L.circle(marker.getLatLng(), {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.2,
                radius: radiusInM * 1
            }).addTo(map);
            for (let i = 1; i < 7; i++) {
                var digree = 60*i;
                var to = L.GeometryUtil.destination(marker.getLatLng(), digree, radiusInM * 1);
                L.marker(to).addTo(map);
                console.log(to.lat+" - "+to.lng);
            }
        }
        function mapsetview(latLng){
            map.setView(latLng);
        }
        $('#fa-marker').click(function(){
            var str_lat = $("#lat").val();
            var str_long = $("#long").val();
            latLng = {
                lat: str_lat,
                lng:  str_long
            };
            map.setView(latLng);
            var marker = L.marker(latLng).addTo(map);
            addcirclemarker(marker);
        });
    }
    return {
        init: function(idContainer) {
            renderMap(idContainer);
        }
    }

}();
    $(document).ready(function(o) {
        Tap.init("<?php echo $modal_id ?>");
        // $("#agent").html(navigator.userAgent);
    })


</script>