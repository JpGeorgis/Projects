var choix = 0;
var nomMatiere = ['Biologie', 'Chimie', 'Géographie', 'Histoire', 'Littérature', 'Physique'];

function start() {

	setTimeout(function() 
	{
		var video = document.querySelector("#video"+choix);
		var texte = document.querySelector("#presentation");

		video.style.display = "none";

		choix++;
		if(choix == nomMatiere.length)
			choix = 0;

		document.querySelector("#video"+choix).style.display="block";
		texte.innerHTML=nomMatiere[choix];
		// Again
		start();

		// chaque 8 secondes
	}, 8000);
}

// Begins
start();