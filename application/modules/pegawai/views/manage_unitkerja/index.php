<!--<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.min.css">-->
<div id="manage_unitkerja_container" class="admin-box box box-primary">
	<div class="box-header">
        <div class="btn-group">
            <a class="show-modal-custom btn btn-small btn-success" title="Tambah Unit Kerja dibawah kementerian" href="<?php echo base_url(); ?>pegawai/manage_unitkerja/createNew" tooltip="Tambah Riwayat Diklat"><i class="fa fa-plus"> </i> Tambah Baru</a>

            <a class="btn btn-small btn-danger" title="Lihat Unit Kerja dibawah kementerian" href="<?php echo base_url(); ?>pegawai/manage_unitkerja/tabel" tooltip="Tabel"><i class="fa fa-table"> </i>  Lihat Table</a>
            <a class="btn btn-small btn-warning" title="Lihat Unit Kerja dibawah kementerian" href="<?php echo base_url(); ?>pegawai/manage_unitkerja/downloadexcell" tooltip="download excell"><i class="fa fa-download"> </i>  Download excell</a>
        </div>
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
    function confirmMoveNode(data,par){
        swal({
            title: "Anda Yakin?",
            text: "Data "+data.node.text+" akan di pindah!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-danger',
            confirmButtonText: 'Ya, Pindah!',
            cancelButtonText: "Tidak, Batalkan!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function (isConfirm) {
            console.log(data);
            if (isConfirm) {
                
                $.post("<?php echo base_url() ?>pegawai/manage_unitkerja/movenode",{
                            _oldParentId : data.old_parent,
                            _position : data.position,
                            _currNodeId : data.node.id,
                            _parentNodeId : data.parent,
                            _nx:par.children
                        },
                        function (o) {
                            if(o.success){
                               swal("Data berhasil di pindah!", o.msg, "success");          
                            }
                            else {
                               swal("Data gagal di pindah!", o.msg, "error");     
                            }
                            
                            $("#tree_satker").jstree(true).refresh();
                    },'json' );        
                
            } else {
                    $("#tree_satker").jstree(true).refresh();
                    swal("Batal", "", "error");
            }
        });
    }
    $(document).ready(function(){
        //import { promiseAlert, swal } from 'promise-alert';
        //promiseAlert({  
          //  title: 'Name?',
           // text: 'Please enter your name.',
           // type: 'input',
           // showCancelButton: true,
           // closeOnConfirm: false
        //});
        $("#tree_satker").jstree({
                "core" : {
                    "themes" : {
                        "responsive": false
                    }, 
                    // so that create works
                    check_callback: function (op, node, parent, position, more) {
                        if ((op === "move_node" || op === "copy_node") && node.type && node.type == "root") {
                            return false;
                        }
                       
                      //  if ((op === "move_node" || op === "copy_node") && more && more.core && !confirm('Are you sure ...')) {
                     //       return false;
                     //   }
                        return true;
                    },
                    'data' : {
                        'url' : function (node) {
                            return 'ajax_tree';
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
        return {
            "Tambah Baru": {
                "separator_before": false,
                "separator_after": false,
                "label": "Tambah Baru",
                "action": function (obj) { 
                    $(this).attr("href",BASE_URL+"pegawai/manage_unitkerja/createNew/"+$node.id);
                     showModalX.call(this,'sukses-crud-unitkerja',function(){
                        $("#tree_satker").jstree(true).refresh();
                    },this);
                }
            },
            "Ubah"  :{
                "separator_before": false,
                "separator_after": false,
                "label": "Ubah",
                "action": function (obj) { 
                    $(this).attr("href",BASE_URL+"pegawai/manage_unitkerja/edit/"+$node.id);
                     showModalX.call(this,'sukses-crud-unitkerja',function(){
                        $("#tree_satker").jstree(true).refresh();
                    },this);
                    
                }
            },
            "Hapus"  :{
                "separator_before": false,
                "separator_after": false,
                "label": "Hapus",
                "action": function (obj) { 
                   var kode =$(this).attr("kode");
                    swal({
                        title: "Anda Yakin?",
                        text: "Hapus data Unit Kerja",
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
                            $.ajax({
                                    url: "<?php echo base_url() ?>pegawai/manage_unitkerja/delete/"+$node.id,
                                    dataType: "html",
                                    timeout:180000,
                                    success: function (result) {
                                        swal("Data berhasil di hapus!", result, "success");
                                        $("#tree_satker").jstree(true).refresh();
                                },
                                error : function(error) {
                                    swal("Batal", "", "error");
                                } 
                            });        
                            
                        } else {
                            swal("Batal", "", "error");
                        }
                    }); 
                }
            },
        
    }}},
                "state" : { "key" : "demo3" },
                "plugins" : [ "dnd",  "types","contextmenu" ]
            }).bind("move_node.jstree", function (e,data) {
                var par =  $("#tree_satker").jstree(true).get_node(data.parent);
                confirmMoveNode(data,par);
            });
        
    });
</script>