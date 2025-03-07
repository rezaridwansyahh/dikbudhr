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
	<div class="col-md-12">
		<div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Kondisi Pegawai Tahun <?php echo $tahun; ?> [Golongan]</h3>
 
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">	
                 	<div id="container" ></div>
                 		<div id="chartgolongan" style="width: 100%; height: 500px;"> </div>
              		</div>
          </div>
	</div>
</div>
<script src="<?php echo base_url() ?>themes/admin/plugins/chartjs/Chart.min.js"></script>
 
<script type="text/javascript">  
var colors=    [ '#FCD202', '#B0DE09','#FF6600', '#0D8ECF', '#2A0CD0', '#CD0D74', '#CC0000', '#00CC00', '#0000CC', '#DDDDDD', '#999999', '#333333', '#990000']
var jsongolongan = <?php echo $jsongolongan; ?>;	
for (i = 0; i < jsongolongan.length; i++) {jsongolongan[i].color = colors[i];}	
var chartRekapGolongan =  AmCharts.makeChart("chartgolongan", {
				  "type": "serial",
				  "dataProvider":   jsongolongan,
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
						  "text": "NAMA"
					  }, {
						  "text": "",
						  "bold": false
					  }],
				  "trendLines": [],
				  "graphs": [
					  {
						  "balloonText": "<b>[[TAHUN_2]]: [[value]]</b>",
						  "colorField": "color",
						  "fillAlphas": 0.8,
						  "id": "AmGraph-2",
						  "lineAlpha": 0.2,
						  "type": "column",
						  "valueField": "JUMLAH_2"
					  }  ,{
						  "balloonText": "<b>[[TAHUN_1]]: [[value]]</b>",
						  "colorField": "color",
						  "fillAlphas": 0.8,
						  "id": "AmGraph-1",
						  "lineAlpha": 0.2,
						  "type": "column",
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

 </script>
<script>
	$("#tahun").change(function(){
        var varvalue = $("#tahun").val();
        if(varvalue != ""){
            window.location = "<?php echo base_url(); ?>admin/reports/kondisi_pegawai/pendidikan/?tahun="+varvalue;  
        }
     }); 
</script>