
<div class='box box-warning' id="form-diklat-struktural-add">     
     <table class="table table-datatable tabel-data">
            <thead>
                <tr>
                    <th width='20px' rowspan="2">No</th>
                    <th rowspan="2">Tahun</th>
                    <th colspan="7">Penilaian PPK</th>
                    <th rowspan="2">PPK Akhir</th>
                </tr>
                <tr>
                    <th width='100px' >Nilai SKP</th>
                    <th width='100px' >Komitmen</th>
                    <th width='100px' >Integritas</th>
                    <th width='100px' >Disiplin</th>
                    <th width='100px' >kerjasama</th>
                    <th width='100px' >Orientasi Pelayanan</th>
                    <th width='100px' >Perilaku</th>
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
<style>
    #table-dokumen tr > td{
        padding:2px;
    }
    #table-dokumen td input{
        padding:0px;
    }
</style>
<script type="text/javascript">

    (function($){
        var grid_daftar = $(".tabel-data").DataTable({
                ordering: false,
                processing: true,
                "bFilter": false,
                "bLengthChange": false,
                serverSide: true,
                "columnDefs": [
                    //{"className": "dt-center", "targets": "_all"}
                    {"className": "dt-center", "targets": [0,2,3]}
                ],
                ajax: {
                    url: "<?php echo base_url() ?>kpo/kpo-satker/ajax_list_ppk",
                    type:'POST',
                    data : {
                        NIP:'<?php echo $NIP;?>'
                    }
                }
        });
         
                
    })(jQuery);
</script>