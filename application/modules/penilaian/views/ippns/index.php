<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap.min.css">
<style>
    .btn-primary {
        background-color: #3c8dbc;
        border-color: #367fa9;
    }
</style>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">IPPNS</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    
    <div class="box-body">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Daftar</a></li>
                <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Kesimpulan</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="form-horizontal">
                        <!-- Filter Untuk Level Kementerian atau Unit Organisasi -->
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">Satker</label>
                            <div class="col-sm-10">
                                <select id="filter_all" class="form-control">
                                    <option value="kementerian">Kementerian</option>
                                    <option value="unor">Unit Organisasi</option>
                                    <!-- <option value="individu">Individu</option> -->
                                </select>
                            </div>
                        </div>

                        <div id='unor_form_all' class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">Unit Organisasi</label>
                            <div class="col-sm-10">


                                <select id="unit_id_key_all" name="unit_id_key_all" width="100%"
                                    class=" col-md-10 format-control">
                                    <?php 
                                        if($selectedSatker){
                                            echo "<option value='$selectedSatker->ID' SELECTED>$selectedSatker->NAMA_UNOR_FULL</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button id="search_all" type="button" class="btn btn-info pull-right">Search</button>
                            </div>
                        </div>

                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">IPPNS</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <table class="table table-bordered formatHTML5" style="width:100%" id="IPPNSTableAll">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>	
                                            <th>NIP</th>	
                                            <th>Pangkat</th>
                                            <th>Jenis  Kelamin</th>
                                            <th>Jenis Jabatan</th>			
                                            <th>Jenjang Jabatan</th>	
                                            <th>Nama Jabatan</th>	
                                            <th>Unit Kerja</th>
                                            <th>Pendidikan Formal</th>
                                            <th>Skor (Pendidikan)</th>
                                            <th>Diklatpim</th>	
                                            <th>Diklat Teknis (20 JP)</th>	
                                            <th>Diklat Fungsional</th>
                                            <th>Seminar</th>	
                                            <th>Skor (Kompetensi)</th>
                                            <th>Range Kinerja</th>
                                            <th>Skor (Kinerja)</th>
                                            <th>Hukdis</th>
                                            <th>Skor (Hukdis)</th>
                                            <th>Nilai PIP</th>
                                            <th>Kategori</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">Satker</label>
                            <div class="col-sm-10">
                                <select id="filter" class="form-control">
                                    <option value="kementerian">Kementerian</option>
                                    <option value="unor">Unit Organisasi</option>
                                    <!-- <option value="individu">Individu</option> -->
                                </select>
                            </div>
                        </div>

                        <div id='unor_form' class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">Unit Organisasi</label>
                            <div class="col-sm-10">


                                <select id="unit_id_key" name="unit_id_key" width="100%" class=" col-md-10 format-control">
                                    <?php 
                                        if($selectedSatker){
                                            echo "<option value='$selectedSatker->ID' SELECTED>$selectedSatker->NAMA_UNOR_FULL</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button id="search" type="button" class="btn btn-info pull-right">Search</button>
                            </div>
                        </div>

                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">IPPNS</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <canvas id="pns-chart" width="800" height="250"></canvas>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /.tab-pane -->
                
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        




    </div>
       
    
</div>



<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>



<script type="text/javascript">
	$("#unit_id_key").select2({
		placeholder: 'Cari Unit Kerja...',
		width: '100%',
		minimumInputLength: 8,
		allowClear: true,
		ajax: {
			url: '<?php echo site_url("pegawai/kepegawaian/ajax_unit_list");?>',
			dataType: 'json',
			data: function(params) 
            
            {
                console.log(params)
				return {
					term: params.term || '',
					page: params.page || 1
				}
			},
			cache: true
		}
	}).change(function(){
		console.log($(this).val())
	});

    $("#unit_id_key_all").select2({
		placeholder: 'Cari Unit Kerja...',
		width: '100%',
		minimumInputLength: 8,
		allowClear: true,
		ajax: {
			url: '<?php echo site_url("pegawai/kepegawaian/ajax_unit_list");?>',
			dataType: 'json',
			data: function(params) 
            
            {
                console.log(params)
				return {
					term: params.term || '',
					page: params.page || 1
				}
			},
			cache: true
		}
	}).change(function(){
		console.log($(this).val())
	});
</script>

<!-- Javascript Function Tab 1 -->
<script>
    $(document).ready(function(){
       

        $('#IPPNSTableAll').DataTable({
            scrollX: true,
            scrollY: true,
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'pdf'
            ],
            buttons: [{
                    extend: "csv",
                    className: "btn btn-primary"
                },
                {
                    extend: "excel",
                    className: "btn btn-primary"
                },
                {
                    extend: "pdfHtml5",
                    className: "btn btn-primary"
                }
            ]
        });
        $("#unor_form_all").hide();

        $("#filter_all").change(function () {
            if ($("#filter_all").val() == "unor") {
                $("#unor_form_all").show();
            } else {
                $("#unor_form_all").hide();
            }
        })

        $("#search_all").click(function(){
            $("#overlay").fadeIn(300);
            var filter = $("#filter_all").val();
            var url = "ippns/get_ippns_value"
            if (filter == "unor") {
                var id = $("#unit_id_key_all").val();
                url = url + "?id=" + id;
            }

            $.get(url, function (data) {
                var ippns = JSON.parse(data);
                console.log("coeg")
                console.log(ippns);

                var tableIppns = [];

                ippns.forEach(element => {
                    tableIppns.push([
                        element.NAMA,element.NIP_BARU,element.NAMA_PANGKAT,
                        element.JENIS_KELAMIN,element.NAMA_JENIS_JABATAN,
                        element.JABATAN_NAMA,element.NAMA_PANGKAT,element.NAMA_UNOR,
                        element.ABBREVIATION,element.SKOR_PENDIDIKAN,element.done_struktural,
                        element.diklat_teknis,element.diklat_fungsional,element.done_kursus,
                        element.SKOR_KOMPETENSI,element.RANGE_KINERJA,element.SKOR_KINERJA,
                        element.TINGKAT_HUKUMAN,element.SKOR_DISIPLIN,element.SKOR_TOT,
                        element.Kategori
                    ]);
                });

                $("#IPPNSTableAll").dataTable().fnDestroy();
                $('#IPPNSTableAll').DataTable({
                    data: tableIppns,
                    scrollX: true,
                    scrollY: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'csv', 'excel', 'pdf'
                    ],
                    buttons: [{
                            extend: "csv",
                            className: "btn btn-primary"
                        },
                        {
                            extend: "excel",
                            className: "btn btn-primary"
                        },
                        {
                            extend: "pdfHtml5",
                            className: "btn btn-primary"
                        }
                    ]
                });
            }).done(function () {
                setTimeout(function () {
                    $("#overlay").fadeOut(300);
                }, 500);
            });
        });
    });
</script>

<script>
    $(document).ready(function () {
        
        $("#unor_form").hide();
        
        $("#filter").change(function () {
            if ($("#filter").val() == "unor") {
                $("#unor_form").show();
            } else {
                $("#unor_form").hide();
            }
        })
       
        $("#search").click(function () {
            $("#overlay").fadeIn(300);
            var filter = $("#filter").val();
            var url = "ippns/get_ippns_value"
            if (filter == "unor") {
                var id = $("#unit_id_key").val();
                url = url + "?id=" + id;
            }

            $.get(url, function (data) {
                var ippns = JSON.parse(data);

                console.log(ippns);

                let sangatTinggi = ippns.filter(ippns=>ippns.Kategori=='Sangat Tinggi').reduce((acc, ippns) => acc + 1, 0);
                let tinggi = ippns.filter(ippns=>ippns.Kategori=='Tinggi').reduce((acc, ippns) => acc + 1, 0);
                let sangatRendah = ippns.filter(ippns=>ippns.Kategori=='Sangat Rendah').reduce((acc, ippns) => acc + 1, 0);
                let rendah = ippns.filter(ippns=>ippns.Kategori=='Rendah').reduce((acc, ippns) => acc + 1, 0);
                let sedang = ippns.filter(ippns=>ippns.Kategori=='Sedang').reduce((acc, ippns) => acc + 1, 0);
                
                new Chart(document.getElementById("pns-chart"), {
                    type: 'pie',
                    data: {
                        labels: ["Sangat Tinggi", "Tinggi", "Sedang", "Rendah", "Sangat Rendah"],
                        datasets: [{
                            label: "Kategori",
                            backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
                            data: [sangatTinggi,tinggi,sedang,rendah,sangatRendah]
                        }]
                    },
                    options: {
                        title: {
                            display: true,
                            text: 'Sebaran hasil skor IP ASN'
                        }
                    }
                });


                

            }).done(function () {
                setTimeout(function () {
                    $("#overlay").fadeOut(300);
                }, 500);
            });
        });
    }); 
</script>