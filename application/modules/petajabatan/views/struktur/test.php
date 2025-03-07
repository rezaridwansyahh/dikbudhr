<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/orgchart-master/dist/css/jquery.orgchart.css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/orgchart-master/examples/css/style.css"/>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/orgchart-master/dist/js/jquery.orgchart.js"></script>
<a class="btn btn-warning btn-reset-zoom-pan">Reset Zoom/Pan</a>
<div id="chart-container"></div>
<script type="text/javascript" src="https://cdn.rawgit.com/jakerella/jquery-mockjax/master/dist/jquery.mockjax.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.debug.js"></script>
<script type="text/javascript"  >
    var ajaxURLs = {
      'children': "<?php echo base_url(); ?>/petajabatan/struktur/load_tree/children/",
      'parent': "<?php echo base_url(); ?>/petajabatan/struktur/load_tree/parent/",
      'siblings': function(nodeData) {
        return "<?php echo base_url(); ?>/petajabatan/struktur/load_tree/siblings/" + nodeData.id;
      },
      'families': function(nodeData) {
        return "<?php echo base_url(); ?>/petajabatan/struktur/load_tree/families/" + nodeData.id;
      }
    };
    $(".btn-reset-zoom-pan").click(function(){
        $('.orgchart').css('transform',''); // remove the tansform settings
    });
    $('#chart-container').orgchart({
        'pan' : true,
        'exportButton' : true,
        'zoom':true,
        'data': "<?php echo base_url(); ?>/petajabatan/struktur/load_tree",
        'ajaxURL' : ajaxURLs,
        'depth': 2,
        'nodeContent': 'title',
        'nodeId': 'id',
        'exportFilename': 'Data Struktur',
        'exportFileextension': 'pdf',
        'createNode': function($node, data) {
            var secondMenu = '<div class="jabatan-menu">'+data.pejabat_nama+'</div>';
            $node.append(secondMenu);
        }
    });
</script>
<style>
    #chart-container .content {
        min-height:0;
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
        height: 20px;
        line-height: 18px;
        overflow: hidden;
        text-align: center;
        text-overflow: ellipsis;
        white-space: nowrap;
        width: 100%;
    }
</style>
