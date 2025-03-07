<div id="loading-all" class="modal fade bs-modal-lg">Loadin...</div>
 <div data-backdrop="static" data-keyboard="false" class="modal fade bs-modal-lg" id="modal-custom-global" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModal-custom-Label">Detail</h4>
				</div>
				<div class="modal-body" id="modal-custom-body">
				Loading content...
				</div>
				
		</div>
	    </div>
    </div>
<!-- Modal -->
    <div data-backdrop="static" data-keyboard="false" class="modal fade bs-modal-lg" id="modal-global" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Detail</h4>
				</div>
				<div class="modal-body" id="modal-body">
				Loading content...
				</div>
				<div class="modal-footer">

		    </div>
		</div>
	    </div>
    </div>
	
<footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0
    </div>
    <strong>Copyright © 2017 <a href="#">Kementerian Pendidikan dan Kebudayaan</a>.</strong> All rights
    reserved.
</footer>
</div>	
	<div id="debug"><!-- Stores the Profiler Results --></div>
	<!-- Bootstrap 3.3.6 -->
	<script src="<?php echo base_url(); ?>themes/admin/js/bootstrap.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo base_url(); ?>themes/admin/dist/js/app.min.js"></script>

	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<!-- script src="<?php echo base_url(); ?>themes/admin/dist/js/pages/dashboard2.js"></script -->
	<!-- AdminLTE for demo purposes -->
	<script src="<?php echo base_url(); ?>themes/admin/dist/js/demo.js"></script>
	<?php echo Assets::js(); ?>
<script type="text/javascript">
$('body').on('click','.show-modal',function (event) { 
//$(".show-modal").unbind('click').on('click', function (event) {
	$('.perhatian').fadeOut(300, function(){});
	  event.preventDefault();
	  var currentBtn = $(this);
	  var title = currentBtn.attr("tooltip");
	  //alert(currentBtn.attr("href"));
	  $.ajax({
	  url: currentBtn.attr("href"),
	  type: 'post',
	  beforeSend: function (xhr) {
		  $("#loading-all").show();
	  },
	  success: function (content, status, xhr) {
		  var json = null;
		  var is_json = true;
		  try {
		  	json = $.parseJSON(content);
		  } catch (err) {
		  	is_json = false;
		  }
		  if (is_json == false) {
		  	$("#modal-body").html(content);
		  	$("#myModalLabel").html(title);
		  	$("#modal-global").modal('show');
		  	$("#loading-all").hide();
		  } else {
		  	alert("Error");
		  }
	  }
	  }).fail(function (data, status) {
	  if (status == "error") {
		  alert("Error");
	  } else if (status == "timeout") {
		  alert("Error");
	  } else if (status == "parsererror") {
		  alert("Error");
	  }
	  });
  });
$('body').on('click','.modal-custom-global',function (event) { 
//$(".show-modal").unbind('click').on('click', function (event) {
	$('.perhatian').fadeOut(300, function(){});
	  event.preventDefault();
	  var currentBtn = $(this);
	  var title = currentBtn.attr("tooltip");
	  //alert(currentBtn.attr("href"));
	  $.ajax({
	  url: currentBtn.attr("href"),
	  type: 'post',
	  beforeSend: function (xhr) {
		  $("#loading-all").show();
	  },
	  success: function (content, status, xhr) {
		  var json = null;
		  var is_json = true;
		  try {
		  	json = $.parseJSON(content);
		  } catch (err) {
		  	is_json = false;
		  }
		  if (is_json == false) {
		  	$("#modal-custom-body").html(content);
		  	$("#myModal-custom-Label").html(title);
		  	$("#modal-custom-global").modal('show');
		  	$("#loading-all").hide();
		  } else {
		  	alert("Error");
		  }
	  }
	  }).fail(function (data, status) {
	  if (status == "error") {
		  alert("Error");
	  } else if (status == "timeout") {
		  alert("Error");
	  } else if (status == "parsererror") {
		  alert("Error");
	  }
	  });
  });
function showmodalnew(callableName,callableFn,parent) {
        $('.perhatian').fadeOut(300, function(){});
            var title = $(parent).attr("tooltip");
            $.ajax({
            url: $(parent).attr("href"),
            type: 'post',
            beforeSend: function (xhr) {
                $("#loading-all").show();
            },
            success: function (content, status, xhr) {
                
                var json = null;
                var is_json = true;
                try {
                    json = $.parseJSON(content);
                } catch (err) {
                    is_json = false;
                }
                if (is_json == false) {
                    $("#modal-body").html(content);
                    $("#myModalLabel").html(title);
                    $("#modal-global").modal('show');
                    $("#loading-all").hide();
                    $("#modal-global").on(callableName,callableFn);
 	
                    $("#loading-all").hide();
                } else {
                    alert("Error");
                }
            }
            }).fail(function (data, status) {
            if (status == "error") {
                alert("Error");
            } else if (status == "timeout") {
                alert("Error");
            } else if (status == "parsererror") {
                alert("Error");
            }
            });
        }
$('body').on('click','.popup',function () { 
	var url =$(this).attr("url");
	popitup(url);
});
function popitup(url,title = "Print window",w = 700,h=600) {
	var left = (screen.width/2)-(w/2);
  	var top = (screen.height/2)-(h/2);

	  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);

}
</script>
 
</body>
</html>
