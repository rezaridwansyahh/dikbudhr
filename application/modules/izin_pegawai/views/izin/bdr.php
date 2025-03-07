<?php
$modal_id = uniqid("modal_");
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/absen/leaflet.css" />
<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="<?php echo base_url(); ?>assets/js/absen/leaflet.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/absen/tap.fn.js"></script>
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
    $(document).ready(function(o) {
        Tap.init("<?php echo $modal_id ?>");
        $("#agent").html(navigator.userAgent);
    })

</script>