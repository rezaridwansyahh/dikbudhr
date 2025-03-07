<div class="admin-box box box-primary expanded-box">
            <?php echo form_open($this->uri->uri_string(),"id=form_search_detil","form"); ?>
                <input type="hidden" class="form-control" name="kategori" value="<?php echo $kategori; ?>">
                <input type="hidden" class="form-control" name="bulan" value="<?php echo $bulan; ?>">
                <input type="hidden" class="form-control" name="tahun" value="<?php echo $tahun; ?>">
            <?php
            echo form_close();    
            ?>
        <div class="box-body">
            <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data-detil table-hover">
                <thead>
                <tr>
                    <th style="width:10px">No</th>
                    <th>Pejabat</th>
                    <th>Jumlah</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot align="right">
                    <tr>
                        <th colspan="2" style="text-align:right">Total:</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
     
<script type="text/javascript">
    $tabledetil = $(".table-data-detil").DataTable({
        
        dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        "columnDefs": [
                        {"className": "text-center", "targets": [0,2]},
                        { "targets": [0,2], "orderable": false }
                    ],
        ajax: {
          url: "<?php echo base_url() ?>admin/arsip/sk_ds/getresumekategoriverifikasi",
          type:'POST',
          "data": function ( d ) {
                d.search['advanced_search_filters']=  $("#form_search_detil").serializeArray();
            }
        },
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // computing column Total of the complete result 
                var kol1 = api
                    .column( 1 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                }, 0 );
        
                var kol2 = api
                  .column( 2 )
                  .data()
                  .reduce( function (a, b) {
                      return intVal(a) + intVal(b);
                  }, 0 );
          
              $( api.column( 2 ).footer() ).html(formatNumber(kol2));
          },
    });
    $("#form_search_detil").submit(function(){
        $tabledetil.ajax.reload(null,true);
        return false;
    });
    function formatNumber(num) {
      return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }
</script>
</div>
