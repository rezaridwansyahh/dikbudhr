
    <div class="row">
        

        <div class="col-md-6">
             <div class="box box-warning">
                    <div class="box-header with-border">
                      <h3 class="box-title">Persentase Jumlah Hari</h3>
                    <!-- /.box-header -->
                    <div class="box-body">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="chart-responsive">
                            <div id="divjumlahhari" style="width: 100%; height: 350px;"> </div>
                          </div>
                          <!-- ./chart-responsive -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <!-- /.box-body -->
                    
                    <!-- /.footer -->
                  </div></div>
        </div>
        <div class="col-md-6">
             <div class="box box-warning">
                    <div class="box-header with-border">
                      <h3 class="box-title">Persentase Jumlah Pengajuan</h3>
                    <!-- /.box-header -->
                    <div class="box-body">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="chart-responsive">
                            <div id="divjumlahpengajuan" style="width: 100%; height: 350px;"> </div>
                          </div>
                          <!-- ./chart-responsive -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <!-- /.box-body -->
                    
                    <!-- /.footer -->
                  </div></div>
        </div>
    </div>
    <div class="row">
      <div class="col-md-12">
             <div class="box box-warning">
                    <div class="box-header with-border">
                      <h3 class="box-title"></h3>

                      
                       
                    <!-- /.box-header -->
                    <div class="box-body">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="chart-responsive">
                            <div id="containerperbulan" style="height:500px;" ></div>
                          </div>
                          <!-- ./chart-responsive -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <!-- /.box-body -->
                    
                    <!-- /.footer -->
                  </div></div>
        </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="box box-warning">
            <table class="table table-bordered">
                <tr>
                    <th>Status</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                </tr>
                <tr>
                    <td>
                        Disetujui
                    </td>
                    <td align="center">
                      <?php echo isset($status_izin["3"]) ? $status_izin["3"] : 0; ?>
                    </td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td>
                        Menunggu
                    </td>
                    <td align="center">
                      <?php echo isset($status_izin["1"]) ? $status_izin["1"] : 0; ?>
                    </td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td>
                        Perubahan
                    </td>
                    <td align="center">
                      <?php echo isset($status_izin["4"]) ? $status_izin["4"] : 0; ?>
                    </td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td>
                        Ditolak
                    </td>
                    <td align="center">
                      <?php echo isset($status_izin["6"]) ? $status_izin["6"] : 0; ?>
                    </td>
                    <td>
                    </td>
                </tr>
            </table>
        </div>
      </div>
        <!-- /.col -->
        <div class="col-md-6">
             <div class="box box-warning">
                    <div class="box-header with-border">
                      <h3 class="box-title"></h3>
 
                    <!-- /.box-header -->
                    <div class="box-body">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="chart-responsive">
                            <div id="chartjenis" style="width: 100%; height: 350px;"> </div>
                          </div>
                          <!-- ./chart-responsive -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <!-- /.box-body -->
                    
                    <!-- /.footer -->
                  </div></div>
        </div>
    </div>
<script type="text/javascript">   
 
var colors=    [ '#FCD202', '#B0DE09','#FF6600', '#0D8ECF', '#2A0CD0', '#CD0D74', '#CC0000', '#00CC00', '#0000CC', '#DDDDDD', '#999999', '#333333', '#990000']

var json_jenisizin = <?php echo $data_jenisizin; ?>;
//inject color attribute with value
for (i = 0; i < json_jenisizin.length; i++) {json_jenisizin[i].color = colors[i];}    
    var chart = AmCharts.makeChart("chartjenis", {
                  "type": "serial",
                  "dataProvider":  json_jenisizin,
                   "theme": "light",
                  "categoryField": "NAMA",
                  "rotate": false,
                  "startDuration": 0,
                  "depth3D": 0,
                  "colorField" : "#ffd900",
                  "angle": 30,
                  
                  "chartCursor": {
                      "categoryBalloonEnabled": false,
                      "cursorAlpha": 0,
                      "zoomable": false,
                  },    
                   "categoryAxis": {
                     "gridPosition": "start",
                     "labelRotation": 45,
                     "axisAlpha": 0,
                     "autoWrap":false,
                     minHorizontalGap:0,
                   },
                   
                    "titles" : [{
                          "text": "NAMA"
                      }, {
                          "text": "",
                          "bold": false
                      }],
                  "trendLines": [],
                  "graphs": [
                      {
                          "balloonText": "<b>[[category]]: [[value]]</b>",
                          "fillAlphas": 0.8,
                          "id": "AmGraph-1",
                          "lineAlpha": 0.2,
                          "title": "Pendidikan",
                          "type": "column",
                          "colorField": "color",
                          "valueField": "jumlah"
                      } 
                  ],
                  "guides": [],
                  "valueAxes": [
                      {
                          "id": "ValueAxis-1",
                          "position": "top",
                          "axisAlpha": 0
                      }
                  ],
                  "allLabels": [],
                  "balloon": {},
                  "titles": [],
                  
                  "export": {
                      "enabled": true
                   }

    });
AmCharts.makeChart("divjumlahhari", {
         "type": "pie",
         "theme": "light",
         "dataProvider":  <?php echo $data_jumlah_hari; ?>,
         "titleField": "NAMA",
         "valueField": "jumlah",
         "pulledField": "pullOut",
         labelsEnabled: false,
         "categoryBalloonEnabled": false,
         "export": {
           "enabled": true
         },
         "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
         "legend": {
             "align": "center",
             "markerType": "circle"
         }

     });
AmCharts.makeChart("divjumlahpengajuan", {
         "type": "pie",
         "theme": "light",
         "dataProvider":  <?php echo $data_jenisizin; ?>,
         "titleField": "NAMA",
         "valueField": "jumlah",
         "pulledField": "pullOut",
         labelsEnabled: false,
         "categoryBalloonEnabled": false,
         "export": {
           "enabled": true
         },
         "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
         "legend": {
             "align": "center",
             "markerType": "circle"
         }

     });
// perbulan
Highcharts.chart('containerperbulan', {
    chart: {
        type: 'line'
    },
    title: {
        text: ''
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    yAxis: {
        title: {
            text: 'PENGAJUAN'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: false
            },
            enableMouseTracking: true
        }
    },
    
    series: [{
        name: 'Jumlah Pengajuan Status presensi/absensi',
        // data: [0, 13860000000, 99600000000, 170340000000, 200040000000, 240000000000, 240000000000, 240000000000]
        data:<?php echo $count_perbulan; ?>  
    }]
});
$('.datepicker').datepicker({
  autoclose: true,format: 'yyyy-mm-dd'
});
 </script>
</div>