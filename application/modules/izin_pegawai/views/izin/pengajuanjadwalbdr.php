<?php
$month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
?>

  <div class="box">
    <div class="box-body">
      

      <div class="nav-tabs-customs">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Pengajuan BDR Ke Atasan</a></li>
          <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Daftar permintaan BDR dari Bawahan</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab_1">
          <br>
            <div class="">
              

              <button id="ajukan" type="button" class="btn btn-primary" data-toggle="modal"
                data-target="#modal-default">
                Ajukan
              </button>

              <br><br>

              
              <table class="table" id="table_pengajuan_sendiri">
                <thead>
                  <tr>
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>Status Pengajuan</th>
                    <th>Detail</th>
                  </tr>
                </thead>

              </table>
            </div>
          </div>
          <!-- /.tab-pane -->

          <!-- Menu di Tab 2, untuk atasan -->
          <div class="tab-pane" id="tab_2">
          <br>
            <table class="table" id="pengajuan_table">
              <thead>
                <tr>
                  <th>Nama</th>
                  <th>NIP</th>
                  <th>Bulan</th>
                  <th>Tahun</th>
                  <th>Status Pengajuan</th>
                  <th>Detail</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- modal untuk pemrosesan BDR oleh atasan  -->
  <div class="modal fade" id="modal-pemrosesan">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Pemrosesan Jadwal BDR</h4>
        </div>
        <div class="modal-body">
          <table id="daftar_pengajuan" class='table'>
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>WFH</th>
                <th>Hari</th>
              </tr>
            </thead>
          </table>
          
          <br>

          <div class="form-group">
            <label>Masukkan Keterangan</label>
            <input type="text" id="alasan" class="form-control" placeholder="Masukkan Pesan untuk bawahan mengenai pengajuan ini, misal alasan penolakan atau pesan lainnya">
          </div>
        </div>
        <div class="modal-footer">
          
          <button type="button" id="terima_usul" class="btn btn-primary">Terima</button>
          <button type="button" id="tolak_usul" class="btn btn-danger">Tolak</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- END -->

  <!-- modal untuk pengajuan BDR untuk atasan -->
  <div class="modal fade" id="modal-default">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Ajukan BDR</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Tahun</label>
            <select id="tahun" class="datetime-changer form-control">
              <?php
                      for($i=2020;$i<2022;$i++){
                          echo "<option value='$i'>$i</option>";
                      }
                  ?>
            </select>
          </div>

          <div class="form-group">
            <label>Bulan</label>
            <select id="bulan" class="datetime-changer form-control">
              <?php
                      foreach ($month as $key => $value) {
                          echo "<option value='".($key+1)."'>$value</option>";
                      }                
                  ?>
            </select>
          </div>
          <div class="form-group date-choose">
            <label>Pilih Tanggal</label>
            <input type="text" class="form-control" id="wfh_date">
          </div>

          <div class="form-group">
            <label>Pilih Lokasi Kerja</label>
            <select id="tipe_kerja" class="form-control">
              <option value="WFH">WFH</option>
              <option value="WFO">WFO</option>
              <option value="DL">DINAS</option>
            </select>
          </div>


          <button id="pengajuan" class="btn btn-primary">Tambah</button>
          <br><br>

          <table id="list_day" class="table">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Tipe</th>
                <th>Hapus</th>
              </tr>
            </thead>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="save_pengajuan">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- END -->

  <!-- modal untuk history  -->
  <div class="modal fade" id="modal-history">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Riwayat Jadwal BDR</h4>
        </div>
        <div class="modal-body">
          <table id="daftar_pengajuan_pribadi" class='table'>
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>WFH</th>
                <th>Hari</th>
              </tr>
            </thead>
          </table>

          <br>

          <div class="form-group">
            <label>Keterangan Atasan</label>
            <input type="text" id="keterangan_atasan" class="form-control" placeholder="Masukkan Pesan untuk bawahan mengenai pengajuan ini, misal alasan penolakan atau pesan lainnya">
          </div>
          
          
        </div>
        <div class="modal-footer">
          <button type="button" id="batalkan_usul" class="btn btn-danger">Batalkan</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- END -->


<script>
  $(document).ready(function(){
    var datatable_data = [];
    var bulan = <?=json_encode($month)?>;
    var hari = ["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"];
    var nip = "<?=$dataset['nip']?>";
    var currentDate = new Date();

    $("#tahun").val(currentDate.getFullYear());
    $("#bulan").val(currentDate.getMonth()+1)

    function LastDayOfMonth(Year, Month) {
      return new Date((new Date(Year, Month, 1)) - 1);
    }



    /**
     * Pengajuan BDR
     */
    $("#ajukan").click(function(){
      datatable_data = [];
      if ($.fn.DataTable.isDataTable("#list_day")) {
        $('#list_day').DataTable().clear().destroy();
      }
    });

    /**
     * Inisialisasi table pengajuan yang diajukan bawahan
     */
    
    function loadPengajuanBawahan() {
      
      if ($.fn.DataTable.isDataTable("#pengajuan_table")) {
        $('#pengajuan_table').DataTable().clear().destroy();
      }
      $('#pengajuan_table').DataTable({
        ajax: "daftarpengajuanbawahan?nip=" + nip,
        fnCreatedRow: function (nRow, aData, iDataIndex, cells) {
          var row = $(nRow).find(':last-child')[0];
          console.log('ready data')
          $(row).click(function () {
            var pengajuan = JSON.parse(aData.meta);
            //console.log(pengajuan)
            var table_config = {
              destroty: true,
              columns: [{
                  data: "tanggal"
                },
                {
                  data: "tipe"
                },
                {
                  data: null,
                  render: (data, type, row, meta) => {
                    //console.log(row)
                    var date = new Date(row.tanggal);
                    var nama_hari = hari[date.getDay() - 1]
                    return nama_hari;
                  }
                }

              ],
              data: pengajuan
            };

            if ($.fn.DataTable.isDataTable("#daftar_pengajuan")) {
              $('#daftar_pengajuan').DataTable().clear().destroy();
            }

            $('#daftar_pengajuan').DataTable(table_config);

            $("#tolak_usul").click(function () {
              update_status_pengajuan(aData.id, "DITOLAK", $("#alasan").val());
            });

            $("#terima_usul").click(function () {
              update_status_pengajuan(aData.id, "DITERIMA", $("#alasan").val());
            });





          })

        },
        columns: [{
            data: "display_name"
          },
          {
            data: "nip"
          },
          {
            data: "bulan"
          },
          {
            data: "tahun"
          },
          {
            data: "status_pengajuan"
          },
          {
            data: null,
            defaultContent: '<button data-toggle="modal" data-target="#modal-pemrosesan" class="btn btn-danger">Aksi</button>',
            orderable: false
          }
        ]
      });
    }

    loadPengajuanBawahan();
    


    /**
     * Inisialiasi table pengajuan yang diajukan sendiri
     */

    function loadPengajuanPribadi() {
      if ($.fn.DataTable.isDataTable("#table_pengajuan_sendiri")) {
        $('#table_pengajuan_sendiri').DataTable().clear().destroy();
      }
      $('#table_pengajuan_sendiri').DataTable({
        ajax: "daftarpengajuanpribadi?nip=" + nip,
        fnCreatedRow: function (nRow, aData, iDataIndex, cells) {
          var row = $(nRow).find(':last-child')[0];
          $(row).click(function () {
            var pengajuan = JSON.parse(aData.meta);
            //console.log(pengajuan)
            var table_config = {
              destroty: true,
              columns: [{
                  data: "tanggal"
                },
                {
                  data: "tipe"
                },
                {
                  data: null,
                  render: (data, type, row, meta) => {
                    //console.log(row)
                    var date = new Date(row.tanggal);
                    var nama_hari = hari[date.getDay() - 1]
                    return nama_hari;
                  }
                }

              ],
              data: pengajuan
            };

            if ($.fn.DataTable.isDataTable("#daftar_pengajuan_pribadi")) {
              $('#daftar_pengajuan_pribadi').DataTable().clear().destroy();
            }

            $('#daftar_pengajuan_pribadi').DataTable(table_config);

            if (aData.status != 'DIAJUKAN') {
              $("#batalkan_usul").hide();
            } else {
              $("#batalkan_usul").show();
            }

            $("#keterangan_atasan").val(aData.pesan)





          })

        },
        columns: [{
            data: null,
            render: (data, type, row, meta) => {
              //console.log(row)

              var nama_hari = bulan[row.bulan - 1]
              return nama_hari;
            }
          },
          {
            data: "tahun"
          },
          {
            data: "status_pengajuan"
          },
          {
            data: null,
            defaultContent: '<button data-toggle="modal" data-target="#modal-history" class="btn btn-danger">Detail</button>',
            orderable: false
          }
        ]
      });
    }
    
    loadPengajuanPribadi();

   

    /**
     * Mengirim Pengajuan
     */
    $("#save_pengajuan").click(function(){
      var list_pengajuan = datatable_data;
      var atasan = "<?=$atasan_langsung?>";

      $.post("savejadwalbdr", 
        {
          meta: list_pengajuan,
          nip_atasan: atasan,
          nip: nip,
          tahun: $("#tahun").val(),
          bulan: $("#bulan").val(),
          wfo: list_pengajuan.filter(function(obj){return obj.tipe_kerja === "WFO";}).length,
          wfh: list_pengajuan.filter(function(obj){return obj.tipe_kerja === "WFH";}).length,
          dl: list_pengajuan.filter(function(obj){return obj.tipe_kerja === "DL";}).length,
          display_name: "<?=$current_user->display_name?>"
        }
      ).done(function (data) {
        
        $("#modal-default").modal('hide');
        loadPengajuanPribadi();
      });

    });


    /**
     * Konfigurasi Table
     */
    var table={
      destroty:true,
      columns: [
        {
          data: "tanggal"
        },
        {
          data:"tipe"
        },
        {
          data: null,
          defaultContent: '<button class="btn btn-danger">Hapus</button>',
          orderable: false
        }

      ],
      data:datatable_data,
      fnCreatedRow: function (nRow, aData, iDataIndex, cells) {

      }


    };

    /**
     * Menambah data pengajuan
     */
    $("#pengajuan").click(function(){
      //var date = new Date($(""))
      
      var wfh_date = $("#wfh_date").val();
      var tipe_kerja = $("#tipe_kerja").val();
      var wfh_object = {
        "tanggal": wfh_date,
        "tipe": tipe_kerja
      };

      var same_date = datatable_data.filter(function(obj){
        return obj.tanggal === wfh_date;
      }).length;

      if(same_date>0){
        alert("tidak boleh memasukan tanggal yang sama")
      }else{
        datatable_data.push(wfh_object)
        table.data=datatable_data;

        if ($.fn.DataTable.isDataTable("#list_day")) {
          $('#list_day').DataTable().clear().destroy();
        }
        
        $('#list_day').DataTable(table);
      }
    });

    

    function generateDatePicker() {
      var year = parseInt($("#tahun").val());
      var month = parseInt($("#bulan").val());
      console.log(month)
      var start = new Date(year + "-" + month + "-01");

      var end = LastDayOfMonth(year, month);


      //end = end.setDate(end.getDate()-1);

      console.log(start)
      console.log(end)
      $("#wfh_date").datepicker('remove')

      $("#wfh_date").datepicker({
        daysOfWeekDisabled: [0, 6],
        startDate: start,
        endDate: end
      })

    }

    

    function update_status_pengajuan(id,status,pesan){
      $.post("ubahstatuspengajuan", {
          status_pengajuan: status,
          id: id,
          pesan: pesan
        })
        .done(function (data) {
          alert("Data Loaded: " + data);
        });

    }

    generateDatePicker();

    $(".datetime-changer").change(function(){
      $(".date-choose").show();
      generateDatePicker();
      
      
    });

  });
</script>

