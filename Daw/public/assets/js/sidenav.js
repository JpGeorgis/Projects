function listeNav() {
  const nav = document.getElementById("mySidenav");
  const header = document.querySelector("header");
  if (nav.style.display !== "block") {
    nav.style.display = "block";
  } else {
    nav.style.display = "none";
  }
}