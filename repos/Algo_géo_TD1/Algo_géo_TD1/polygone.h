#ifndef POLYGONE_H
#define POLYGONE_H

#include "point.h"

class Sommet
{
friend class Polygone;
public:
	// accesseurs
	Point coordonnees() const {return d_coordonnees;}
	Sommet* suivant() const {return d_suivant;}
	Sommet* precedent() const {return d_precedent;}

private:
	// constructeur à partir d'un point p
	Sommet(const Point &p, Sommet *suivant=nullptr, Sommet *precedent=nullptr)
		: d_coordonnees{p}, d_suivant{suivant}, d_precedent{precedent} {}
	// destructeur vide privé
	~Sommet() = default;

	// coordonnées
	Point d_coordonnees;
	// sommets suivant et precedent
	Sommet *d_suivant, *d_precedent;
};


class Polygone
{
public:
	// constructeur par défaut
	Polygone() : d_premier{nullptr} {}
	// destructeur
	~Polygone();
	// accesseurs
	Sommet* premier() const {return d_premier;}

	// Ajoute un nouveau sommet au polygone. Les coordonnées du sommet à ajouter sont celles du point p.
	// sommetPrecedent est soit un pointeur sur l’un des sommets déjà présents dans le polygone, 
	// soit un pointeur nul si le polygone est vide.
	// Dans le premier cas, le nouveau sommet devient le successeur du sommet d’adresse sommetPrecedent.
	// Dans le deuxième cas, le nouveau sommet devient l’unique sommet du polygone.
	// Dans tous les cas, la méthode renvoie l’adresse du nouveau sommet.
	Sommet* ajouteSommet(const Point &p, Sommet *sommetPrecedent=nullptr);

	// Supprime le sommet d’adresse s du polygone.
	// On suppose que s est effectivement présent dans le polygone.
	void supprimeSommet(Sommet *s);


private:

	// un sommet quelconque du polygone. nullptr si le polygone est vide
	Sommet* d_premier;
};


#endif
