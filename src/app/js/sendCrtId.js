$(function(){
  var entry_url = $("#entry_url").val();

  $("#cart_in").click(function(){
      var item_id = $("#item_id").val();
      location.href = entry_url + "cart.php?item_id=" + item_id;
  });
});

const sendCrtId = () => {
  const entry_url = document.getElementById("entry_url").val();

  
};