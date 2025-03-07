<script src="<?php echo base_url(); ?>assets/js/amcharts/amcharts.js" type="text/javascript" ></script>
<script src="<?php echo base_url(); ?>assets/js/amcharts/serial.js" type="text/javascript" ></script>  
<script src="<?php echo base_url(); ?>assets/js/amcharts/pie.js" type="text/javascript" ></script>  
<script type="text/javascript" src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<style type="text/css">
	#chartdiv {
	width	: 100%;
	height	: 500px;
}			
</style> 
<div class="callout callout-info">
   <h4>Keterangan!</h4>
   <p>Update Terakhir <?php echo trim($this->settings_lib->item('site.updateresume')); ?></p>
 </div>
<div class='controls'>
 	<select name="tahun" id="tahun" class="form-control select2" style="width:100%">
 	<?php for($i=2018;$i<=(int)date("Y");$i++){ ?>     
    	<option  value='<?php echo $i; ?>' <?php echo $tahun == $i ? "selected" : ""; ?>><?php echo $i; ?></option>
    <?php } ?>
	</select>
</div>
<br>
<div class="row">
	<div class="col-md-6">
		<div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Statistik PNS <?php echo $tahun; ?></h3>
 
            </div>
            <!-- /.box-header -->
	        <div class="box-body no-padding">	
	         	<div id="container" ></div>
	         		<div id="chartgolongan" style="width: 100%; height: 320px;"> </div>
	      	</div>
         </div>
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Grafik Pendidikan</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">	
			   <div id="container" ></div>
				   <div id="chartpendidikan" style="width: 100%; height: 320px;"> </div>
			   </div>
          </div> 
		   
	</div>
	<div class="col-md-6">
		 

        <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Golongan</h3>
 
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="chart-responsive">
                    <div id="divgolongan" style="width: 100%; height: 300px;"></div>
                  </div>
                  <!-- ./chart-responsive -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-body -->
            
            <!-- /.footer -->
        </div>
        <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Jenis Jabatan</h3>
 
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="chart-responsive">
                    <div id="divjabatan" style="width: 100%; height: 300px;"></div>
                  </div>
                  <!-- ./chart-responsive -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-body -->
            
            <!-- /.footer -->
        </div>
	</div>
</div>

<script src="<?php echo base_url() ?>themes/admin/plugins/chartjs/Chart.min.js"></script>
 
<script type="text/javascript">  
var colors=    [ '#FCD202', '#B0DE09','#FF6600', '#0D8ECF', '#2A0CD0', '#CD0D74', '#CC0000', '#00CC00', '#0000CC', '#DDDDDD', '#999999', '#333333', '#990000']
 

var inputpendidikan = <?php echo $jsonpendidikan; ?>;
 

var jsonasn = <?php echo $jsonasn; ?>;	
for (i = 0; i < jsonasn.length; i++) {jsonasn[i].color = colors[i];}	
var chartRekapGolongan =  AmCharts.makeChart("chartgolongan", {
				  "type": "serial",
				  "dataProvider":   jsonasn,
				   "theme": "light",
				  "categoryField": "BULAN",
				  "rotate": false,
				  "startDuration": 0,
				  "depth3D": 2,
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
						  "text": "TAHUN"
					  }, {
						  "text": "",
						  "bold": false
					  }],
				  "trendLines": [],
				  "graphs": [
					  {
						  "balloonText": "<b>[[category]]: [[value]]</b>",
						  "colorField": "color",
						  "fillAlphas": 0.1,
						  "id": "AmGraph-1",
						  "lineAlpha": 1,
						  "type": "line",
						  "bullet": "round",
						  "valueField": "JUMLAH"
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
var chart = AmCharts.makeChart("divgolongan", {
        "type": "serial",
        "theme": "none",
        "valueAxes": [{
            "id":"v1",
            "axisAlpha": 0,
            "position": "left"
        }],
        "legend": {
            "useGraphSettings": true
          },
        "graphs": [{
            "id": "g1",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#FFFFFF",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "Golongan 1",
            "balloonText": "[[title]] : <b>[[value]]</b>",
            "useLineColorForBulletBorder": true,
            "valueField": "1"
        },
        {
            "id": "g2",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#00FF00",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "Golongan 2",
            "balloonText": "[[title]] : <b>[[value]]</b>",
            "useLineColorForBulletBorder": true,
            "valueField": "2"
        },
        {
            "id": "g3",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#00FF00",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "Golongan 3",
            "balloonText": "[[title]] : <b>[[value]]</b>",
            "useLineColorForBulletBorder": true,
            "valueField": "3"
        },
        {
            "id": "g4",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#00FF00",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "Golongan 4",
            "balloonText": "[[title]] : <b>[[value]]</b>",
            "useLineColorForBulletBorder": true,
            "valueField": "4"
        },
        {
            "id": "g6",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#00FF00",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "Belum ada",
            "balloonText": "[[title]] : <b>[[value]]</b>",
            "useLineColorForBulletBorder": true,
            "valueField": "Belum ada"
        }],
         
        "chartCursor": {
            "cursorPosition": "mouse",
            "pan": true,
             "valueLineEnabled":true,
             "valueLineBalloonEnabled":true
        },
        "categoryField": "BULAN",
        "categoryAxis": {
            "dashLength": 1,
            "position": "top"
        },
        exportConfig:{
          menuRight: '20px',
          menuBottom: '50px',
          menuItems: [{
          icon: 'http://www.amcharts.com/lib/3/images/export.png',
          format: 'png'   
          }]  
        },
        "dataProvider":  <?php echo $jsongol; ?>,
    }
);	 

	 
	var chart = AmCharts.makeChart("divjabatan", {
        "type": "serial",
        "theme": "none",
        "valueAxes": [{
            "id":"v1",
            "axisAlpha": 0,
            "position": "left"
        }],
        "legend": {
            "useGraphSettings": true
          },
        "graphs": [{
            "id": "g1",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#FFFFFF",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "Struktural",
            "balloonText": "[[title]] : <b>[[value]]</b>",
            "useLineColorForBulletBorder": true,
            "valueField": "1"
        },
        {
            "id": "g2",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#00FF00",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "JFT",
            "balloonText": "[[title]] : <b>[[value]]</b>",
            "useLineColorForBulletBorder": true,
            "valueField": "2"
        },
        {
            "id": "g3",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#00FF00",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "Staff Khusus",
            "balloonText": "[[title]] : <b>[[value]]</b>",
            "useLineColorForBulletBorder": true,
            "valueField": "3"
        },
        {
            "id": "g4",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#00FF00",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "JFU",
            "balloonText": "[[title]] : <b>[[value]]</b>",
            "useLineColorForBulletBorder": true,
            "valueField": "4"
        },
        {
            "id": "g6",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#00FF00",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "Belum ada",
            "balloonText": "[[title]] : <b>[[value]]</b>",
            "useLineColorForBulletBorder": true,
            "valueField": "Belum ada"
        }],
         
        "chartCursor": {
            "cursorPosition": "mouse",
            "pan": true,
             "valueLineEnabled":true,
             "valueLineBalloonEnabled":true
        },
        "categoryField": "BULAN",
        "categoryAxis": {
            "dashLength": 1,
            "position": "top"
        },
        exportConfig:{
          menuRight: '20px',
          menuBottom: '50px',
          menuItems: [{
          icon: 'http://www.amcharts.com/lib/3/images/export.png',
          format: 'png'   
          }]  
        },
        "dataProvider":  <?php echo $jsonjab; ?>,
    }
);  
 

var chart = AmCharts.makeChart("chartpendidikan", {
        "type": "serial",
        "theme": "none",
        "valueAxes": [{
            "id":"v1",
            "axisAlpha": 0,
            "position": "left"
        }],
        "legend": {
		    "useGraphSettings": true
		  },
        "graphs": [{
			"id": "g1",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#FFFFFF",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "SD",
            "balloonText": "[[title]] : <b>[[value]]</b>",
            "useLineColorForBulletBorder": true,
            "valueField": "SD"
        },
        {
			"id": "g2",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#00FF00",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "SLTP",
            "balloonText": "[[title]] : <b>[[value]]</b>",
            "useLineColorForBulletBorder": true,
            "valueField": "SLTP"
        },
        {
			"id": "g3",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#00FF00",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "SLTA",
            "balloonText": "[[title]] : <b>[[value]]</b>",
            "useLineColorForBulletBorder": true,
            "valueField": "SLTA"
        },
        {
			"id": "g4",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#00FF00",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "Diploma",
            "balloonText": "[[title]] : <b>[[value]]</b>",
            "useLineColorForBulletBorder": true,
            "valueField": "Diploma"
        },
        {
			"id": "g5",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#00FF00",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "Strata",
            "balloonText": "[[title]] : <b>[[value]]</b>",
            "useLineColorForBulletBorder": true,
            "valueField": "Strata"
        },
        {
			"id": "g6",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#00FF00",
            "bulletSize": 5,
            "hideBulletsCount": 50,
            "lineThickness": 2,
            "title": "Belum ada",
            "balloonText": "[[title]] : <b>[[value]]</b>",
            "useLineColorForBulletBorder": true,
            "valueField": "Belum ada"
        }],
         
        "chartCursor": {
            "cursorPosition": "mouse",
            "pan": true,
             "valueLineEnabled":true,
             "valueLineBalloonEnabled":true
        },
        "categoryField": "BULAN",
        "categoryAxis": {
            "dashLength": 1,
            "position": "top"
        },
        exportConfig:{
          menuRight: '20px',
          menuBottom: '50px',
          menuItems: [{
          icon: 'http://www.amcharts.com/lib/3/images/export.png',
          format: 'png'	  
          }]  
        },
        "dataProvider":  <?php echo $jsonpendidikan; ?>,
    }
);

chart.addListener("rendered", zoomChart);

zoomChart();
function zoomChart(){
    //chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
    chart.zoomToIndexes(0, 20);
}
</script>

<script>
	$("#tahun").change(function(){
        var varvalue = $("#tahun").val();
        if(varvalue != ""){
            window.location = "<?php echo base_url(); ?>admin/reports/kondisi_pegawai/bulanan?tahun="+varvalue;  
        }
     }); 
</script>