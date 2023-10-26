function listeNav() {
  const nav = document.getElementById("mySidenav");
  const header = document.querySelector("header");
  if (nav.style.display !== "block") {
    nav.style.display = "block";
    header.style.marginBottom = "12.5vh";
  } else {
    nav.style.display = "none";
    header.style.marginBottom = "0";
  }
}