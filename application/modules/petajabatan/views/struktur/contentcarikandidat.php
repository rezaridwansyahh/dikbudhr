<table class="table">
      <tbody>
         <?php
         $nomor_urut=1;
         if(isset($kandidats) && is_array($kandidats) && count($kandidats)):
          foreach ($kandidats as $record) {
            ?>
            <tr>
                <td><?php echo $nomor_urut; ?>.</td>
                <td align="left" style="text-align: left;"><a href="<?php echo base_url(); ?>admin/kepegawaian/pegawai/profilen/<?php echo $record->ID; ?>" class="show-modal"><?php echo $record->GELAR_DEPAN; ?> <?php echo $record->NAMA; ?> <?php echo $record->GELAR_BELAKANG; ?></a></td>
            </tr>
          <?php
            $nomor_urut++;
          }
        else:
        ?>
        <tfoot>
          <tr>
             <td colspan="2">
              Tidak ada kandidat
             </td>     
          </tr>
      </tfoot>
        <?php
        endif;
        ?>
      </tbody>
  </table> 
  <a href="<?php echo base_url(); ?>petajabatan/struktur/aturkandidat/<?php echo $unitkerja; ?>" class="btn bg-maroon margin show-modal">Lihat detil</a>