<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/orgchart-master/dist/css/jquery.orgchart.css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/orgchart-master/examples/css/style.css"/>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/orgchart-master/dist/js/jquery.orgchart.js"></script>
<?php if($id_periode == ""){ ?>
<div class="callout callout-danger">
    <h4>Perhatian</h4>
    <p>Belum ada periode baperjakat yang aktif</p>
</div>
<?php }else{
?>
    <div class="callout callout-info">
        <h4>Periode</h4>
        <p>Baperjakat Periode <?php echo $baperjakataktif->KETERANGAN; ?></p>
    </div>

    <div class="control-group">
         <div class='controls'>
             <select name="Unit_Kerja_ID" id="Unit_Kerja_ID" class="form-control select2" style="width:700px">
                    <?php 
                        if($selectedunor){
                            echo "<option selected value='".$selectedunor->ID."'>".$selectedunor->NAMA_UNOR."</option>";
                        }
                    ?>
                </select>
         </div>
     </div>
    <div id="chart-container"></div>
<?php } ?>
<script type="text/javascript" src="https://cdn.rawgit.com/jakerella/jquery-mockjax/master/dist/jquery.mockjax.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.debug.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
  <style>
  .sortable { list-style-type: none; margin: 10px; margin-right: 10px; padding: 0; width: 90%;}
  .sortable li { padding-left: -100px; font-size: 1.4em; height: 40px; align-content: left;}
  .sortable li span { margin-left: 0px; }
  #chart-container {
    
    height: 520px!important;
}
  </style>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script type="text/javascript"  >
    var ajaxURLs = {
      'children': "<?php echo base_url(); ?>/petajabatan/struktur/load_tree_baper/children/",
      'parent': "<?php echo base_url(); ?>/petajabatan/struktur/load_tree_baper/parent/",
      'siblings': function(nodeData) {
        return "<?php echo base_url(); ?>/petajabatan/struktur/load_tree_baper/siblings/" + nodeData.id;
      },
      'families': function(nodeData) {
        return "<?php echo base_url(); ?>/petajabatan/struktur/load_tree_baper/families/" + nodeData.id;
      }
    };
    $(".btn-reset-zoom-pan").click(function(){
        $('.orgchart').css('transform',''); // remove the tansform settings
    });
    $('#chart-container').orgchart({
        'pan' : true,
        'exportButton' : true,
        'zoom':true,
        'data': "<?php echo base_url(); ?>/petajabatan/struktur/load_tree_baper/-/<?php echo $UNOR_ID; ?>",
        'ajaxURL' : ajaxURLs,
        'depth': 2,
        'nodeContent': 'title',
        'nodeId': 'id',
        'exportFilename': 'Data-Struktur-<?php echo $selectedunor->ID; ?>',
        'exportFileextension': 'png',
        'createNode': function($node, data) {
            var secondMenu = '<div class="jabatan-menu">'+data.pejabat_nama+'</div>';
            $node.append(secondMenu);


        }
    });
  
    function theFunction (kandidatid) {
         
        $('#divcandidat_'+kandidatid).html('<ul class="sortable"><li class="ui-state-default">Item 1</li><li class="ui-state-default">Item 2</li><li class="ui-state-default">Item 3</li></ul>');
    } 
    function carikandidat(kandidatid){
         var ValUnit_Kerja_ID = kandidatid;
         var json_url = "<?php echo base_url() ?>petajabatan/struktur/carikandidat/"+ValUnit_Kerja_ID;
         $.ajax({    type: "GET",
            url: json_url,
            data: "unitkerja="+ValUnit_Kerja_ID,
            success: function(data){ 
                $('#divcandidat_'+kandidatid).html(data);
            }});
         return false; 
      return false;
    }
</script>
<script>
    $("#Unit_Kerja_ID").select2({
        placeholder: 'Cari Unit Kerja...',
        width: '100%',
        minimumInputLength: 0,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/kepegawaian/ajax_satker_list");?>',
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
    $("#Unit_Kerja_ID").change(function(){
        var varvalue = $("#Unit_Kerja_ID").val();
        if(varvalue != ""){
            window.location = "<?php echo base_url(); ?>petajabatan/struktur/baperjakat/"+varvalue;  
        }
     }); 
    //alert('<?php echo site_url("admin/masters/unitkerja/ajaxkodeinternal");?>');
     
</script>
<style>

    #chart-container .content {
        min-height:0;
        height: 500px;
    }
    #chart-container .orgchart {
        /*height: 100%;
        width: 100%;*/
    }
    #chart-container .orgchart .node {
        width:250px;
    }
    #chart-container .orgchart {
        background-image :none;
    }
    #chart-container .orgchart .node .title {
        height:auto;
        overflow:auto;
        white-space:normal;
        background-color: #367fa9;
        color : #fff;
    }
    #chart-container .orgchart .node .title i.fa {
        display:none;
    }
    #chart-container .orgchart .node .content {
        height:auto;
        overflow:auto;
        white-space:normal;
    }
    .orgchart .node .content {
        background-color: #fff;
        border: 1px solid rgba(217, 83, 79, 0.8);
        border-radius: 0 0 0px 0px;
        color: #333;
        font-size: 11px;
        height: 20px;
        line-height: 18px;
        overflow: hidden;
        text-align: center;
        text-overflow: ellipsis;
        white-space: nowrap;
        border-bottom:none;
        width: 100%;
    }
    .orgchart .node .jabatan-menu {
        background-color: #fff;
        border: 1px solid rgba(217, 83, 79, 0.8);
        border-radius: 0 0 4px 4px;
        color: #333;
        font-size: 11px;
        
        line-height: 18px;
        text-align: center;
        text-overflow: ellipsis;
        white-space: nowrap;
        width: 100%;
    }
</style>

