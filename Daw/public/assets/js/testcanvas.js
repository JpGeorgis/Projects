const canevas = document.querySelector('#fond');

canevas.width = "100vw";
canevas.height = "100vh";

const context = canevas.getContext('2d');

let bulles = [];
let xMousePos = 0;
let yMousePos = 0;

//options sur les bulles du canvas
const optionsBulles = {
	nbreBulles : '50',
	couleur : 'rgb(107,166,214)',
	rayonDefaut : '50'
};

function Particle(tab)
{
	this.x = Math.random() * (canevas.width - 200) + 100;
	this.y = Math.random() * (canevas.height - (canevas.height - 100)) + (canevas.height - 100);
	let colBulle = Math.round(Math.random() * 4);
	this.color = tab[colBulle];
	this.radius = Math.random()* (optionsBulles.rayonDefaut - 10) + 10;
	this.speed = Math.random()* 0.75 + 0.25;
};

function initialisationBulles(tab)
{
	for(let i = 0; i < optionsBulles.nbreBulles; i++)
	{
		bulles.push(new Particle(tab));
	}
}

function actualiserCouleurs(tab)
{
	for(let i = 0; i < optionsBulles.nbreBulles; i++)
	{
		let choix = Math.round(Math.random() * 4);
		bulles[i].color = tab[choix]; 
	}
}

canevas.addEventListener('mousemove', function(e)
{
	xMousePos = e.clientX + window.scrollX;
	yMousePos = e.clientY + window.scrollY;	
});

function animation()
{
	//Appel de la fonction animation à chaque frame
	requestAnimationFrame(animation);

	//demande de rafraichissement du contenu du canvas à chaque frame
	context.clearRect(0, 0, canevas.width, canevas.height);

	canevas.width = window.innerWidth;
	canevas.height = window.innerHeight;

	//animation bulles
	for(let i = 0; i < bulles.length; i++)
	{
		context.beginPath();
		context.arc(bulles[i].x, bulles[i].y, bulles[i].radius, 0, 2*Math.PI);
		context.strokeStyle = bulles[i].color;
		context.fillStyle = bulles[i].color;
		context.stroke();
		context.fill();	
	}

	for(let i = 0; i < bulles.length; i++)
	{
		let position = Math.pow(xMousePos - bulles[i].x, 2) + Math.pow(yMousePos - bulles[i].y, 2);
		let pop = false;
		if(position <= Math.pow(bulles[i].radius,2))
			pop=true;
		bulles[i].y -= bulles[i].speed;
		if(bulles[i].y < bulles[i].radius || pop)
		{
			bulles[i].x = Math.random() * (canevas.width - 200) + 100;
			bulles[i].y = Math.random() * (canevas.height - (canevas.height - 100)) + (canevas.height - 100);
		}
	}
}

function chargementTheme()
{
	const theme = document.querySelector("link");
	if(theme.getAttribute("href")==="assets/css/gris.css")
	{
		const couleurBullesGris = ['rgb(30,30,30)', 'rgb(218,218,218)', 'rgb(169,169,169)', 'rgb(243,243,243)'];
		initialisationBulles(couleurBullesGris);
		animation();
	}
	else
	{
		const couleurBullesBleues = ['rgb(16,40,51)', 'rgb(207,237,247)', 'rgb(240,242,243)', 'rgb(125,96,50)'];
		initialisationBulles(couleurBullesBleues);
		animation();
	}
}

function changementTheme()
{
	const theme = document.querySelector("link");
	if(theme.getAttribute("href")==="assets/css/gris.css")
	{
		const couleurBullesGris = ['rgb(30,30,30)', 'rgb(218,218,218)', 'rgb(169,169,169)', 'rgb(243,243,243)'];
		actualiserCouleurs(couleurBullesGris);
	}
	else
	{
		const couleurBullesBleues = ['rgb(16,40,51)', 'rgb(207,237,247)', 'rgb(240,242,243)', 'rgb(125,96,50)'];
		actualiserCouleurs(couleurBullesBleues);
	}
}
