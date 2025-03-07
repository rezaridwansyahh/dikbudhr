<!--<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.min.css">-->
<div id="manage_unitkerja_container" class="admin-box box box-primary">
	<div class="box-header">
        
    </div>
	<div class="box-body">
        <div id="tree_satker" class="tree-demo"> </div>
    </div>
</div>    
<script type="application/javascript">  
    var BASE_URL = '<?php echo base_url()?>';
    $container =$("#manage_unitkerja_container");
    $container.on('click','.show-modal-custom',function(event){
        showModalX.call(this,'sukses-crud-unitkerja',function(){
            $("#tree_satker").jstree(true).refresh();
        },this);
        event.preventDefault();
    });
    
    $(document).ready(function(){
        $("#tree_satker").jstree({
                "core" : {
                    "themes" : {
                        "responsive": false
                    }, 
                    
                    'data' : {
                        'url' : function (node) {
                            return BASE_URL+'pegawai/manage_unitkerja/ajax_tree';
                        },
                        'data' : function (node) {
                            return { 'parent' : node.id };
                        }
                    }
                },
                "types" : {
                    "default" : {
                        "icon" : "fa fa-folder icon-state-warning icon-lg"
                    },
                    "file" : {
                        "icon" : "fa fa-file icon-state-warning icon-lg"
                    }
                },
                "contextmenu":{         
    "items": function($node) {
        var tree = $("#tree").jstree(true);
        }},
                "state" : { "key" : "demo3" },
                "plugins" : [ "dnd",  "types","contextmenu" ]
            }).bind("select_node.jstree", function (event, data) { 
                $("#NAMA_UNIT_KERJA").val(data.node.text);
                $("#KODE_UNIT_KERJA").val(data.node.id.replace("node_", ""));
                $('#NAMA_UNIT_KERJA').focus();
                $("#modal-global").modal("hide");
            });
        
    });
</script>