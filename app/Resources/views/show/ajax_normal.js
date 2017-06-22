function role_function(role_name, user_id){
$.post( "/change_role/" + user_id + "/" + role_name , function( data ) {
  console.log(data["role"]);
  if(data["role"]){
    $( "#" + role_name + "_status" ).html("Posiada" );
    $( "#" + role_name + "_button" ).html("Zabierz" );
  }else{
    $( "#" + role_name + "_status" ).html("Nie posiada" );
    $( "#" + role_name + "_button" ).html("Dodaj" );
  }
});
}

