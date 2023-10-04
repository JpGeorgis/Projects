function listeNav() {
  var x = document.getElementById("mySidenav");
  if (x.style.display === "none") {
    x.style.display = "block";
    var xx = document.getElementById("header");
    xx.style.marginBottom = "17vh";
    
    
    
  } else {
    x.style.display = "none";
    var xx = document.getElementById("header");
    xx.style.marginBottom = "0";
  }
}