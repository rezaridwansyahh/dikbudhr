 
    <table class="table table-datatable">
        <thead>
            <tr>
                <th width='20px' >No</th>
                <th>Keluarga</th>
                <th>Tanggal Lahir</th>
            </tr>
        </thead>
        <body>
            <tr>
                <td>
                    1.
                </td>
                <td colspan="4">
                    <a href="javascript:void(0)" id="ayah" class="sowhide">
                        <i class="fa fa-plus-square">&nbsp;&nbsp;</i>
                    </a>
                    <span style="text-transform: uppercase;" class="font-blue"><b>Ayah</b></span>
                <td>    
            </tr>
            <?php
            if(isset($orangtua_data) && is_array($orangtua_data) && count($orangtua_data)):
            foreach ($orangtua_data as $record) {
                ?>
                <tr class="ayah" style="display: none;">
                    <td>
                    </td>
                    <td>
                        <a class='show-modal' data-toggle='modal' tooltip="Edit Data Ayah" href="<?php echo base_url(); ?>pegawai/data_keluarga/addortu/<?php echo $record->PNS_ID; ?>/<?php echo $record->ID; ?>"><?php echo $record->NAMA; ?></a>
                    </td> 
                    <td>
                        <?php echo $record->TANGGAL_LAHIR; ?>
                    </td> 
                </tr>
            <?php 
            }
            endif;
            ?>
            <tr>
                <td>
                    2.
                </td>
                <td colspan="4">
                    <a href="javascript:void(0)" id="ibu" class="sowhide">
                        <i class="fa fa-plus-square">&nbsp;&nbsp;</i>
                    </a>
                    <span style="text-transform: uppercase;" class="font-blue"><b>Ibu</b></span>
                <td>    
            </tr>
            <?php
            if(isset($dataibus) && is_array($dataibus) && count($dataibus)):
            foreach ($dataibus as $record) {
                ?>
                <tr class="ibu" style="display: none;">
                    <td>
                    </td>
                    <td>
                        <a class='show-modal' data-toggle='modal' tooltip="Edit Ibu" href="<?php echo base_url(); ?>pegawai/data_keluarga/addortu/<?php echo $record->PNS_ID; ?>/<?php echo $record->ID; ?>"><?php echo $record->NAMA; ?></a>
                    </td> 
                    <td>
                        <?php echo $record->TANGGAL_LAHIR; ?>
                    </td> 
                </tr>
            <?php 
            }
            endif;
            ?>
            <tr>
                <td>
                    3.
                </td>
                <td colspan="4">
                    <a href="javascript:void(0)" id="istri" class="sowhide">
                        <i class="fa fa-plus-square">&nbsp;&nbsp;</i>
                    </a>
                    <span style="text-transform: uppercase;" class="font-blue"><b>Pasangan</b></span>
                <td>    
            </tr>
            <?php
            if(isset($dataistri) && is_array($dataistri) && count($dataistri)):
            foreach ($dataistri as $record) {
                ?>
                <tr class="istri" style="display: none;">
                    <td>
                    </td>
                    <td>
                        <a class='show-modal' data-toggle='modal' tooltip="Edit Pasangan" href="<?php echo base_url(); ?>pegawai/data_keluarga/addistri/<?php echo $record->PNS_ID; ?>/<?php echo $record->ID; ?>"><?php echo $record->NAMA; ?></a>
                    </td> 
                    <td>
                        <?php echo $record->TANGGAL_LAHIR; ?>
                    </td> 
                </tr>
            <?php 
            }
            endif;
            ?>
            <tr>
                <td>
                    4.
                </td>
                <td colspan="4">
                    <a href="javascript:void(0)" id="anak" class="sowhide">
                        <i class="fa fa-plus-square">&nbsp;&nbsp;</i>
                    </a>
                    <span style="text-transform: uppercase;" class="font-blue"><b>Anak</b></span>
                <td>    
            </tr>
            <?php
            if(isset($dataanak) && is_array($dataanak) && count($dataanak)):
            foreach ($dataanak as $record) {
                ?>
                <tr class="anak" style="display: none;">
                    <td>
                    </td>
                    <td>
                        <a class='show-modal' data-toggle='modal' tooltip="Edit Anak" href="<?php echo base_url(); ?>pegawai/data_keluarga/addanak/<?php echo $record->PNS_ID; ?>/<?php echo $record->ID; ?>"><?php echo $record->NAMA; ?></a>
                    </td> 
                    <td>
                        <?php echo $record->TANGGAL_LAHIR; ?>
                    </td> 
                </tr>
            <?php 
            }
            endif;
            ?>
        </body>
        <tfoot>
            <tr>
                    
            </tr>
        </tfoot>
        <tbody>
           
        </tbody>
    </table>  
 
 <script type="text/javascript">
     $('.sowhide').on('click', function () {
        var idnya = $(this).attr('id');
        if ($.trim($(this).html()) == '<i class="fa fa-minus-square">&nbsp;&nbsp;</i>') {
            $(this).html('<i class="fa fa-plus-square">&nbsp;&nbsp;</i>');
            $('tr[class*="' + idnya + '"]').toggle();
        } else {
            $(this).html('<i class="fa fa-minus-square">&nbsp;&nbsp;</i>');
            $('tr[class*="' + idnya + '"]').toggle();
        }
        return false;
    });
 </script>