function openForm() {
 var x = document.getElementById("myForm");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
function relForm() {
  var form = document.getElementById("form-container");
  
  form.onsubmit = function(){
    location.reload(true);
  }
}
//$(document).ready(function(){
//    $("#but_submit").click(function(){
//        var username = $("#uname").val().trim();
//        var password = $("#pwd").val().trim();
//
//        if( username != "" && password != "" ){
//            $.ajax({
//                url:'login.php',
//                type:'post',
//                data:{username:username,password:password},
//                success:function(response){
//                    var msg = "";
//                   if(response == 1){
//                        window.location.reload(true);
//                    }
//                }
//            });
//        }
//    });
//});