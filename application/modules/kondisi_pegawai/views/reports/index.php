<script src="<?php echo base_url(); ?>assets/js/amcharts/amcharts.js" type="text/javascript" ></script>
<script src="<?php echo base_url(); ?>assets/js/amcharts/serial.js" type="text/javascript" ></script>  
<script src="<?php echo base_url(); ?>assets/js/amcharts/pie.js" type="text/javascript" ></script>  
<script type="text/javascript" src="https://www.amcharts.com/lib/3/themes/light.js"></script>
 
<div class='controls'>
 	<select name="tahun" id="tahun" class="form-control select2" style="width:100%">
 	<?php for($i=2018;$i<=2019;$i++){ ?>     
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
		  <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">UMUR <?php echo $tahun; ?></h3>
 
            </div>
            <!-- /.box-header -->
	        <div class="box-body no-padding">	
	         	<div id="container" ></div>
	         		<div id="chartumur" style="width: 100%; height: 320px;"> </div>
	      	</div>
         </div>
        
	</div>
	<div class="col-md-6">
		<div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Grafik Jenis Kelamin</h3>
 
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="chart-responsive">
                    <div id="divjeniskelamin" style="width: 100%; height: 300px;"></div>
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
//inject color attribute with value
for (i = 0; i < inputpendidikan.length; i++) {inputpendidikan[i].color = colors[i];}	
	var chart = AmCharts.makeChart("chartpendidikan", {
				  "type": "serial",
				  "dataProvider":  inputpendidikan,
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
	

var jsonasn = <?php echo $jsonasn; ?>;	
for (i = 0; i < jsonasn.length; i++) {jsonasn[i].color = colors[i];}	
var chartRekapGolongan =  AmCharts.makeChart("chartgolongan", {
				  "type": "serial",
				  "dataProvider":   jsonasn,
				   "theme": "light",
				  "categoryField": "TAHUN",
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
	AmCharts.makeChart("divjeniskelamin", {
		 "type": "pie",
		 "theme": "light",
		 "dataProvider":  <?php echo $jsonjk; ?>,
		 "titleField": "Jenis_Kelamin",
		 "valueField": "jumlah",
		 "pulledField": "pullOut",
		 labelsEnabled: false,
		 "radius": "42%",
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

	AmCharts.makeChart("divgolongan", {
		 "type": "pie",
		 "theme": "light",
		 "dataProvider":  <?php echo $jsongol; ?>,
		 "titleField": "golongan",
		 "valueField": "jumlah",
		 "pulledField": "pullOut",
		 labelsEnabled: false,
		 "innerRadius": "60%",
		 "radius": "42%",
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
	AmCharts.makeChart("divjabatan", {
		 "type": "pie",
		 "theme": "light",
		 "dataProvider":  <?php echo $jsonjab; ?>,
		 "titleField": "jabatan",
		 "valueField": "jumlah",
		 "pulledField": "pullOut",
		 labelsEnabled: false,
		 "innerRadius": "60%",
		 "radius": "42%",
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

	// UMUR
	var jsonumurf = <?php echo $jsonumurf; ?>;	

var chartRekapGolongan =  AmCharts.makeChart("chartumur", {
				  "type": "serial",
				  "dataProvider":   jsonumurf,
				   "theme": "light",
				  "categoryField": "NAMA",
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
						  "balloonText": "<b>Perempuan : [[value]]</b>",
						  "colorField": "color",
						  "fillAlphas": 0.7,
						  "id": "AmGraph-umur",
						  "lineAlpha": 0.2,
						  "type": "column",
						  "bullet": "round",
						  "valueField": "JUMLAH"
					  },
					  {
						  "balloonText": "<b>Laki-laki : [[value]]</b>",
						  "colorField": "color",
						  "fillAlphas": 0.7,
						  "id": "AmGraph-umurmm",
						  "lineAlpha": 0.2,
						  "type": "column",
						  "bullet": "round",
						  "valueField": "JUMLAHM"
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
</script>
<script>
	$("#tahun").change(function(){
        var varvalue = $("#tahun").val();
        if(varvalue != ""){
            window.location = "<?php echo base_url(); ?>admin/reports/kondisi_pegawai/?tahun="+varvalue;  
        }
     }); 
</script>