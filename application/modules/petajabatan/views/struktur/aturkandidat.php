<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<!-- daterange picker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datepicker/datepicker3.css">
<div class="callout callout-info">
   <h4>Baperjakat!</h4>
   <p>Anda bisa merubah urutan kandidat, menambah/menghapus calon kandidat yang telah difilter</p>
   <p> <h3>Kandidat "<?php echo $nama_jabatan; ?>"</h3>
    Eselon : <?php echo $eselon; ?></p>
 </div>
 <!-- /.col -->
<div class="row">
  <div class="col-md-12">
    <!-- Application buttons -->
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Filter Lebih lanjut</h3>
      </div>
      <div class="box-body">
        <div class="control-group">
           <div class='controls'>
            <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
            <input type="hidden" name="UNOR_ID" value="<?php echo $unitkerja; ?>">
            <input type="hidden" name="nama_jabatan" value="<?php echo $nama_jabatan; ?>">
            <input type="hidden" name="JABATAN_ID" value="<?php echo $JABATAN_ID; ?>">
            
            Tambahkan Pegawai
             <select name="NIP" id="NIP" class="form-control select2">
                <option value="<?php echo $selectedpegawai->ID; ?>" selected><b><?php echo $selectedpegawai->NAMA; ?></b></option>
              </select>
            </form>
           </div>
         </div>
        
      </div>
      <!-- /.box-body -->
    </div>
   
  </div>
</div>
  <!-- /.col -->
 <div class="row" id="contentaturkandidat">
        <div class="col-md-12">
          <!-- Block buttons -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Daftar Kandidat</h3>
            </div>
            <div class="box-body">
              
                <table class="table table-datatable">
                    <thead>
                        <tr>
                          <th width="10px">
                            No
                          </th>
                          <th width="20%">
                            Nama
                          </th>
                          <th>
                            Assm
                          </th>
                          <th>
                            Hukdis
                          </th>
                          <th>
                            Pendidikan
                          </th>
                          
                          <th>
                            Gol
                          </th>
                          <th>
                            U1
                          </th>
                          <th>
                            U2
                          </th>
                          <th width="120px" align="center">
                            #
                          </th>
                        </tr>
                      </thead>
                    <tfoot>
                        <tr>
                                
                        </tr>
                    </tfoot>
                    <tbody>
                       
                    </tbody>
                </table>  
            </div>
          </div>
          <!-- /.box -->

        </div>
        
      </div>
 
<script>

$(document).ready(function(){   
  var grid_daftar = $(".table-datatable").DataTable({
        ordering: false,
        processing: true,
        "bFilter": false,
        "bLengthChange": false,
        serverSide: true,
        "columnDefs": [
          //{"className": "dt-center", "targets": "_all"}
          {"className": "dt-center", "targets": [0,2,3,6,7]}
        ],
        ajax: {
          url: "<?php echo base_url() ?>petajabatan/struktur/ajax_kandidat",
          type:'POST',
          data : {
            unitkerja:'<?php echo $unitkerja;?>'
          }
        }
    });
     

    $("#NIP").select2({
        placeholder: 'Cari Pegawai...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("admin/kepegawaian/pegawai/ajaxnip");?>',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
            cache: true
        }
    });
    $( "#NIP" ).change(function() {
      submitkandidat();
    });
    function submitkandidat(){
      var json_url = "<?php echo base_url() ?>petajabatan/struktur/savekandidat";
       $.ajax({    
        type: "POST",
        url: json_url,
        data: $("#frm").serialize(),
              dataType: "json",
              success: function(data){ 
                  if(data.success){
                      swal("Pemberitahuan!", data.msg, "success");
                      //$("#modal-global").trigger("sukses-tambah-riwayat-pekerjaan");
                      //$("#modal-global").modal("hide");
                      grid_daftar.ajax.reload();
                      carikandidat("<?php echo $unitkerja; ?>");
                  }
                  else {
                  swal("Pemberitahuan!", data.msg, "error");
                }
        }});
      return false; 
    }

    $("#contentaturkandidat").on('click','.btn-hapus',function(event){
      event.preventDefault();
      var kode =$(this).attr("kode");
        swal({
          title: "Anda Yakin?",
          text: "Hapus data kandidat!",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: 'btn-danger',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: "Tidak, Batalkan!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function (isConfirm) {
          if (isConfirm) {
            var post_data = "kode="+kode;
            $.ajax({
                url: "<?php echo base_url() ?>petajabatan/struktur/deletekandidat/"+kode,
                dataType: "html",
                timeout:180000,
                success: function (result) {
                  swal("Data berhasil di hapus!", result, "success");
                  grid_daftar.ajax.reload();
                  carikandidat("<?php echo $unitkerja; ?>");
              },
              error : function(error) {
                alert(error);
              } 
            });        
            
          } else {
            swal("Batal", "", "error");
          }
        });
    });
    $("#contentaturkandidat").on('click','.btn-tetapkan',function(event){
      event.preventDefault();
      var kode =$(this).attr("kode");
        swal({
          title: "Anda Yakin?",
          text: "Tetapkan kandidat untuk dipilih menteri!",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: 'btn-danger',
          confirmButtonText: 'Ya!',
          cancelButtonText: "Tidak, Batalkan!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function (isConfirm) {
          if (isConfirm) {
            var post_data = "kode="+kode;
            $.ajax({
                url: "<?php echo base_url() ?>petajabatan/struktur/tetapkankandidat/"+kode,
                dataType: "html",
                timeout:180000,
                success: function (result) {
                  swal("Informasi!", result, "success");
                  grid_daftar.ajax.reload();
                  carikandidat("<?php echo $unitkerja; ?>");
              },
              error : function(error) {
                alert(error);
              } 
            });        
            
          } else {
            swal("Batal", "", "error");
          }
        });
    });
    $("#contentaturkandidat").on('click','.btn-batalkan',function(event){
      event.preventDefault();
      var kode =$(this).attr("kode");
        swal({
          title: "Anda Yakin?",
          text: "Batalkan Penetapan!",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: 'btn-danger',
          confirmButtonText: 'Ya!',
          cancelButtonText: "Tidak!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function (isConfirm) {
          if (isConfirm) {
            var post_data = "kode="+kode;
            $.ajax({
                url: "<?php echo base_url() ?>petajabatan/struktur/batalkanpenetapan/"+kode,
                dataType: "html",
                timeout:180000,
                success: function (result) {
                  swal("Informasi!", result, "success");
                  grid_daftar.ajax.reload();
                  carikandidat("<?php echo $unitkerja; ?>");
              },
              error : function(error) {
                alert(error);
              } 
            });        
            
          } else {
            swal("Batal", "", "error");
          }
        });
    });
    $("#contentaturkandidat").on('click','.btnup',function(event){
      var kode =$(this).attr("kode");
        var post_data = "kode="+kode;
          $.ajax({
              url: "<?php echo base_url() ?>petajabatan/struktur/setup/"+kode,
              dataType: "html",
              timeout:180000,
              success: function (result) {
                //swal("Informasi!", result, "success");
                grid_daftar.ajax.reload();
                carikandidat("<?php echo $unitkerja; ?>");
            },
            error : function(error) {
              alert(error);
            } 
          });  
    });
    $("#contentaturkandidat").on('click','.btndown',function(event){
      var kode =$(this).attr("kode");
        var post_data = "kode="+kode;
          $.ajax({
              url: "<?php echo base_url() ?>petajabatan/struktur/setdown/"+kode,
              dataType: "html",
              timeout:180000,
              success: function (result) {
                //swal("Informasi!", result, "success");
                grid_daftar.ajax.reload();
                carikandidat("<?php echo $unitkerja; ?>");
            },
            error : function(error) {
              alert(error);
            } 
          });  
    });
});
</script>
