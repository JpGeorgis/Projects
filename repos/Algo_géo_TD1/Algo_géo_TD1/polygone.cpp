#include "polygone.h"
#include "point.h"

using namespace std;

// Impl�mentation des m�thodes de la classe Polygone

// destructeur
Polygone::~Polygone()
{
	while (d_premier != nullptr)
	{
		supprimeSommet(d_premier);
	}
}

// Ajoute un nouveau sommet au polygone. Les coordonn�es du sommet � ajouter sont celles du point p.
// sommetPrecedent est soit un pointeur sur l�un des sommets d�j� pr�sents dans le polygone, 
// soit un pointeur nul si le polygone est vide.
// Dans le premier cas, le nouveau sommet devient le successeur du sommet d�adresse sommetPrecedent.
// Dans le deuxi�me cas, le nouveau sommet devient l�unique sommet du polygone.
// Dans tous les cas, la m�thode renvoie l�adresse du nouveau sommet.
Sommet* Polygone::ajouteSommet(const Point &p, Sommet* sommetPrecedent)
{
	if (d_premier == nullptr)
	{
		d_premier = new Sommet(p);
		d_premier->d_suivant = d_premier;
		d_premier->d_precedent = d_premier;
		return d_premier;
	}
	else
	{
		Sommet* s = new Sommet(p, sommetPrecedent->d_suivant, sommetPrecedent);
		sommetPrecedent->suivant()->d_precedent = s;
		sommetPrecedent->d_suivant = s;
		return s;

	}
}


// Supprime le sommet d�adresse s du polygone.
// On suppose que s est effectivement pr�sent dans le polygone.
void Polygone::supprimeSommet(Sommet* s)
{
	s->precedent()->d_suivant = s->suivant();
	s->suivant()->d_precedent = s->precedent();
	if (s == d_premier)
	{
		d_premier = s->suivant();
	}
	delete s;
}





