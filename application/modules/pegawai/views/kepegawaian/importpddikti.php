<?php
$nip = "";

if(isset($_GET['nip'])){
    $nip = $_GET['nip'];
}
?>

<div class='box box-warning' id="form-riwayat-assesmen-add">
    <form id="sinkronpddikti">
        <div class="box-body">
            <div class="form-group">
                <label>NIP</label>
                <input type="text" class="form-control" name="nip" id="nip">
            </div>        
        </div>

        <div class="modal-footer">
            <input type='submit' name='save' id="btnsinkron" class='btn btn-primary' value="Ambil Data" /> 
        </div>

    </form>

    <div id="content">

    </div>


</div>

<script>
    $(document).ready(function(){
        let nip = "<?=$nip?>";

        if(nip!=""){
            submitdata();
        }

        $("#sinkronpddikti").submit(function(){
            submitdata();
            return false; 
        });	

        function submitdata(){
            var json_url = "<?php echo base_url() ?>admin/kepegawaian/pegawai/getpddikti";
            $.ajax({    
                type: "POST",
                url: json_url,
                data: $("#sinkronpddikti").serialize(),
                dataType: "json",
                success: function(data){ 
                    console.log(data);

                    let html_content = "<h4>Data dari PDDIKTI</h4>";

                    Object.entries(data).forEach(([detail_key, detail_value]) => {
                        //console.log(detail_key)
                        if(detail_key != "additional_data" || detail_key != "data_dikti"){
                            html_content = html_content + "<div class='form-group col-4'><label class='col-form-label' for='" + detail_key + "'>" + detail_key + "</label><input type='text' class='form-control' id='" + detail_key + "' value='"+detail_value+"'></div>";
                        }
                        
                    })

                    $("#content").html(html_content)
                }});
            return false; 

        }
    });
</script>