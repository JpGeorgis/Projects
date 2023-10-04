#include <vector>
using namespace std;

#include "carte.h"
#include "point.h"


// Destructeur
Carte::~Carte()
{
	for (int i = d_tabDemiCote.size() - 1; i >= 0; i--)
	{
		if (d_tabDemiCote[i]->oppose() != nullptr)
		{
			d_tabDemiCote[i]->d_suivant->d_precedent = d_tabDemiCote[i]->d_precedent;
			d_tabDemiCote[i]->d_precedent->d_suivant = d_tabDemiCote[i]->d_suivant;
		}
		if (d_tabDemiCote[i]->d_suivant == d_tabDemiCote[i])
			delete d_tabDemiCote[i]->d_sommet;
		delete d_tabDemiCote[i];
	}
}

// Ajoute dans la carte un nouveau c�t� compos� de deux demi-c�t�s dc1 et dc2
// dont precedent1 et precedent2 seront les demi-c�t�s pr�c�dents respectifs.
// On suppose precedent1 et precedent2 d�j� pr�sents dans la carte.
// Renvoie l'adresse de dc1.
DemiCote* Carte::ajouteCote(DemiCote* precedent1, DemiCote* precedent2)
{
	DemiCote* dc1 = ajouteDemiCote(precedent1);
	DemiCote* dc2 = ajouteDemiCote(precedent2, dc1);
	return dc1;
}

// Ajoute dans la carte un nouveau c�t� compos� de deux demi-c�t�s dc1 et dc2.
// precedent1 sera le demi-c�t� pr�c�dent de dc1. dc2 sera issu d�un
// nouveau sommet (� cr�er) dont les coordonn�es sont celles du point p2.
// On suppose precedent1 d�j� pr�sent dans la carte.
// Renvoie l'adresse de dc1.
DemiCote* Carte::ajouteCote(DemiCote* precedent1, const Point& p2)
{
	DemiCote* dc1 = ajouteDemiCote(precedent1);
	DemiCote* dc2 = ajouteDemiCote(p2, dc1);
	return dc1;
}

// Ajoute dans la carte un nouveau c�t� compos� de deux demi-c�t�s dc1 et dc2.
// precedent2 sera le demi-c�t� pr�c�dent de dc2. dc1 sera issu d�un
// nouveau sommet (� cr�er) dont les coordonn�es sont celles du point p1.
// On suppose precedent2 d�j� pr�sent dans la carte.
// Renvoie l'adresse de dc1.
DemiCote* Carte::ajouteCote(const Point& p1, DemiCote* precedent2)
{
	return ajouteCote(precedent2, p1);
}

// Ajoute dans la carte un nouveau c�t� compos� de deux demi-c�t�s dc1 et dc2.
// dc1 et dc2 seront issus de deux nouveaux sommets (� cr�er) dont les
// coordonn�es sont celles des points p1 et p2 respectivement.
// Renvoie l'adresse de dc1.
DemiCote* Carte::ajouteCote(const Point& p1, const Point& p2)
{
	DemiCote* dc1 = ajouteDemiCote(p1);
	DemiCote* dc2 = ajouteDemiCote(p2, dc1);
	return dc1;
}

// Ajoute un nouveau demi-c�t� dans la carte dont precedent sera le demi-c�t�
// 	pr�c�dent et oppose sera le demi-c�t� oppos�.
// On suppose que le demi-c�t� precedent est d�j� pr�sent dans la carte.
// Le demi-c�t� oppose est soit d�j� pr�sent dans la carte soit nul (valeur
// par d�faut).
// Renvoie l�adresse du nouveau demi-c�t�.
DemiCote* Carte::ajouteDemiCote(DemiCote* precedent, DemiCote* oppose)
{
	DemiCote* d = new DemiCote(precedent->d_suivant, precedent, oppose, precedent->d_sommet, d_tabDemiCote.size());
	d_tabDemiCote.push_back(d);
	precedent->d_suivant->d_precedent = d;
	precedent->d_suivant = d;
	if (oppose != nullptr)
		oppose->d_oppose = d;
	return d;
}

// Ajoute un nouveau demi-c�t� dans la carte qui sera issu d�un nouveau
// sommet (� cr�er) dont les coordonn�es sont celles du point p.
// oppose sera le demi-c�t� oppos� du nouveau demi-c�t�.
// oppose est soit d�j� pr�sent dans la carte soit nul (valeur par d�faut).
// Renvoie l�adresse du nouveau demi-c�t�.
DemiCote* Carte::ajouteDemiCote(const Point& p, DemiCote* oppose)
{
	Sommet* s = new Sommet(p);
	DemiCote* d = new DemiCote(nullptr, nullptr, oppose, s, d_tabDemiCote.size());
	d_tabDemiCote.push_back(d);
	if (oppose != nullptr)
	{
		oppose->d_oppose = d;
	}
	return d;
}


