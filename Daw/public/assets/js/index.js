var choix = 1;
var nomMatiere = ['Biologie', 'Géographie', 'Histoire', 'Informatique', 'Littérature', 'Mathématiques'];

function videoPrecedente()
{
	document.querySelector("#video"+choix).style.display='none';
	var texte = document.querySelector("#presentation");
	choix--;
	if(choix < 0)
	{
		choix = nomMatiere.length - 1;
		document.querySelector("#video"+choix).style.display='block';
		texte.innerHTML=nomMatiere[choix];
	}

	else
	{
		document.querySelector("#video"+choix).style.display='block';
		texte.innerHTML=nomMatiere[choix];
	}
}

function videoSuivante()
{
	document.querySelector("#video"+choix).style.display='none';
	var texte = document.querySelector("#presentation");
	choix++;
	if(choix >= nomMatiere.length)
	{
		choix = 0;
		document.querySelector("#video"+choix).style.display='block';
		texte.innerHTML=nomMatiere[choix];
	}

	else
	{
		document.querySelector("#video"+choix).style.display='block';
		texte.innerHTML=nomMatiere[choix];
	}
}

var bordureG = document.getElementById("bloc_gauche");

bordureG.addEventListener("mouseover", function()
{
	document.getElementById("partie_gauche").style.display='block';
	document.getElementById("fleche_gauche").style.display='block';
});

bordureG.addEventListener("mouseout", function()
{
	document.getElementById("partie_gauche").style.display='none';
	document.getElementById("fleche_gauche").style.display='none';
});

var bordureD = document.getElementById("bloc_droit");

bordureD.addEventListener("mouseover", function()
{
	document.getElementById("partie_droite").style.display='block';
	document.getElementById("fleche_droite").style.display='block';
});

bordureD.addEventListener("mouseout", function()
{
	document.getElementById("partie_droite").style.display='none';
	document.getElementById("fleche_droite").style.display='none';
});