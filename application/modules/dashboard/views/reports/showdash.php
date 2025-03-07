<?php
	$this->load->library('convert');
	$convert = new convert();
	$mainmenu = $this->uri->segment(2);
	$menu = $this->uri->segment(3);
	$submenu = $this->uri->segment(4);
?>
<div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-plane"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Jumlah Perjalanan</span>
              <span class="info-box-number"><?php echo isset($jmlperjalanan) ? $jmlperjalanan : ""; ?> <small> Kali</small></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-files-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Belum SPJ</span>
              <span class="info-box-number"><?php echo isset($count_blmspj) ? $count_blmspj : ""; ?> <small> Perjalanan</small></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-4 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-files-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Belum Laporan</span>
               <span class="info-box-number"><?php echo isset($count_blmlaporan) ? $count_blmlaporan : ""; ?> <small> Perjalanan</small></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-money"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Realisasi Anggaran</span>
              <span class="info-box-number"><small>P : <?php echo isset($jumlah_pagu) ? $convert->ToRpnosimbol((Double)$jumlah_pagu) : ""; ?></small></span>
              <span class="info-box-number"><small>R : <?php echo isset($jmlrealisasiperjalanan) ? $convert->ToRpnosimbol((Double)$jmlrealisasiperjalanan) : ""; ?>(<?php echo isset($persentase) ? round($persentase,2): ""; ?>%)</small></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      
<div class='box box-primary'>
    <div class="box-body">

	  <div class="row">
	  	<center><h3>Perjalanan Perpegawai</h3></center>
		  <div class="col-xs-12">
			  <table class="table table-bordered table-striped">
				  <tr>
				  	<th width="10px">No.</th>
				  	<th>NAMA</th>
					  <?php if (isset($propinsis) && is_array($propinsis) && count($propinsis)):?>
					  <?php foreach($propinsis as $rec):?>
						  <th><?php e(ucfirst($rec->keterangan)); ?></th>
						  <?php endforeach;?>
					  <?php endif;?>
					 <th width="10px">Jumlah</th> 
				  </tr>
				   <?php $no = 1;
				   
				   	if (isset($pegawais) && is_array($pegawais) && count($pegawais)):?>
					  <?php foreach($pegawais as $pegawai):?>
						  <tr>
						  	<td><?php e($no); ?>.</td>
						  	<td><?php e(ucfirst($pegawai->nama)); ?></td>
						  	<?php 
						  	$jumlah = 0;
						  	if (isset($propinsis) && is_array($propinsis) && count($propinsis)):?>
							  <?php foreach($propinsis as $rec):
							  	$jml = isset($adatasppd[$rec->id."-".$pegawai->pegawai]) ? $adatasppd[$rec->id."-".$pegawai->pegawai] : 0;
							  	$jumlah = $jumlah + $jml;
							  	?>
								  <td align="center"><?php echo isset($adatasppd[$rec->id."-".$pegawai->pegawai]) ? $adatasppd[$rec->id."-".$pegawai->pegawai] : ""; ?></td>
								  <?php endforeach;?>
							  <?php endif;?>
							  <td align="center"><?php echo $jumlah; ?></td>
						  </tr>
						  <?php $no++;
						  endforeach;?>
					  <?php endif;?>
				  
				  		
				  <tr>
			  </table>
		  </div>	
	  </div>
	  <div id="container" >
	  asdf
	  </div>
	  
	<script type="text/javascript">
	
		var processed_json = new Array();   
			var json = '{1,2,3,4,5,6,7,8,9,10,11,12}]';
			// Populate series
                            $(function () {
                                Highcharts.chart('container', {
                                    chart: {
                                        type: 'column'
                                    },
                                    title: {
                                        text: 'Chart Perjalanan'
                                    },
                                    subtitle: {
                                        text: 'Perbulan'
                                    },
                                    xAxis: {
                                        categories: [
                                            'Januari',
                                            'Februari',
                                            'Maret',
                                            'April',
                                            'Mei',
                                            'Juni',
                                            'Juli',
                                            'Agustus',
                                            'September',
                                            'Oktober',
                                            'November',
                                            'Desember',
                                            
                                        ],
                                        crosshair: true
                                    },
                                    yAxis: {
                                        min: 0,
                                        title: {
                                            text: 'Jumlah (x)'
                                        }
                                    },
                                    tooltip: {
                                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                            '<td style="padding:0"><b>{point.y} kali</b></td></tr>',
                                        footerFormat: '</table>',
                                        shared: true,
                                        useHTML: true
                                    },
                                    plotOptions: {
                                        column: {
                                            pointPadding: 0.2,
                                            borderWidth: 0
                                        }
                                    },
                                    
                                    series: [{
                                        name: 'Bulan',
                                        data: <?php echo $adatasppdbulan; ?>

                                    }]
                                });
                            });
                        </script>
                       <script src="<?php echo base_url(); ?>themes/admin/plugins/highchart/highcharts.js"></script>  
	</div>
</div>