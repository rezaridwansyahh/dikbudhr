$(".input-group-addon-password").css('cursor', 'pointer').addClass('input-password-hide'); 
$(".input-group-addon-password").find('.fa').removeClass('fa-eye').addClass('fa-eye-slash') 
$('body').on('click','.input-group-addon-password',function () { 
  var x = document.getElementById("passwordanda");
  if (x.type === "password") {
    $(".input-group-addon-password").find('.fa').removeClass('fa-eye-slash').addClass('fa-eye') 
    x.type = "text";
  } else {
    $(".input-group-addon-password").find('.fa').removeClass('fa-eye').addClass('fa-eye-slash') 
    x.type = "password";
  }

});     

