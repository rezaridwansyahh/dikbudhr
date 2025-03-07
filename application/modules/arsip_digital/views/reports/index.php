<div class="admin-box box box-primary expanded-box">
        <div class="box-header">
            <center>REKAPITULASI JUMLAH DOKUMEN DIGITAL KEPEGAWAIAN PER <?php echo date("d-m-Y"); ?></center>
         </div>
        <div class="box-body">
            &nbsp;<button class='btn btn-warning download_xls pull-right' target="_blank"><i class="fa fa-download" aria-hidden="true"></i> Download .xls</button>
                        &nbsp;&nbsp;
            <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
                <thead>
                <tr>
                    <th style="width:5px">No</th>
                    <th>Unit</th>
                    <th width="10%">Jumlah Dokumen</th>
                    <?php
                    $ajml_kategori = array();
                    if(isset($reckategori) && is_array($reckategori) && count($reckategori)):
                        foreach ($reckategori as $recordkat) {
                            $ajml_kategori[$recordkat->ID] = 0;
                    ?>  
                            <th>
                                <?php echo $recordkat->KATEGORI; ?>
                            </th>
                    <?php
                        }
                    endif;
                    ?>
                </thead>
                <body>
                    <?php
                    $no = 1;
                    $jmldok = 0;
                    if(isset($data_unitkerja) && is_array($data_unitkerja) && count($data_unitkerja)):
                        foreach ($data_unitkerja as $record) {
                    ?>
                        <tr>
                            <td>
                                <?php echo $no; ?>
                            </td>
                            <td>
                                <a href="<?php echo base_url(); ?>admin/reports/arsip_digital/detiles2/<?php echo $record->ID; ?>" title="<?php echo $record->NAMA_UNOR; ?>" tooltip="<?php echo $record->NAMA_UNOR; ?>" class="show-modal"><?php echo $record->NAMA_UNOR; ?></a>
                            </td>
                            <td align="center">
                                <?php
                                if(isset($adata_arsip[$record->ID])){
                                    $jmldok = $jmldok + (int)$adata_arsip[$record->ID];
                                } 
                                echo isset($adata_arsip[$record->ID]) ? $adata_arsip[$record->ID] : 0; ?>
                            </td>
                            <?php
                            if(isset($reckategori) && is_array($reckategori) && count($reckategori)):
                                foreach ($reckategori as $recordkat) {
                                    if(isset($adata_arsip_es1[$record->ID."_".$recordkat->ID])){
                                        $ajml_kategori[$recordkat->ID] = $ajml_kategori[$recordkat->ID] + (int)$adata_arsip_es1[$record->ID."_".$recordkat->ID];    
                                    }
                                    
                            ?>
                                
                                    <td align="center">
                                        <?php echo isset($adata_arsip_es1[$record->ID."_".$recordkat->ID]) ? $adata_arsip_es1[$record->ID."_".$recordkat->ID] : 0; ?>
                                    </td>
                            <?php
                                }
                            endif;
                            ?>
                        </tr>
                    <?php
                        $no++;
                        }
                    endif;
                    ?>
                </body>
                <tfoot align="right">
                <tr>
                    <th colspan="2" style="text-align:right">Total:</th>
                    <th align="center"><?php echo $jmldok; ?></th>
                    <?php
                            if(isset($reckategori) && is_array($reckategori) && count($reckategori)):
                                foreach ($reckategori as $recordkat) {
                            ?>
                                
                                    <th align="center">
                                        <?php echo $ajml_kategori[$recordkat->ID]; ?>
                                    </th>
                            <?php
                                }
                            endif;
                            ?>
                </tr>
            </table>
        </div>
     
<script type="text/javascript">
    $grid_daftararsip = $(".table-data").DataTable({
        "pageLength": 50,
    });
$(".download_xls").click(function(){
    var xyz = $("#form_search").serialize();
    window.open("<?php echo base_url('admin/reports/arsip_digital/download');?>?"+xyz);
});
</script>