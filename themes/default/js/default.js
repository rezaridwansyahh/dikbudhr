$( "#frmpengaduan" ).submit(function( event ) {
	$('#form_message').html("<center>Kirim...</center>");
	 var post_data =  $("#frmpengaduan").serialize();
	  $.ajax({
			  url: base_url+"contact/savepengaduan",
			  type:"POST",
			  data: post_data,
			  dataType: "html",
			  timeout:180000,
			  success: function (result) {
				$('#form_message').show();
				$('#form_message').html(result);
				$('#form_message').fadeOut(6000);  
				$("#form_message").trigger('reset'); 
		  },
		  error : function(error) {
			  alert(error);
		  } 
	  });    
	  return false;
});