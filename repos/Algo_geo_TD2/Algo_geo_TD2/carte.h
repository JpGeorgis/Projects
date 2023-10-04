#ifndef CARTE_H
#define CARTE_H

#include <vector>
#include "point.h"

using namespace std;

class Sommet
{
	friend class Carte;
	friend class DemiCote;

private:

	// Construit un sommet avec les coordonnées de p
	Sommet(const Point &p) : d_coordonnees{p} {};

	// Destructeur de sommet privé
	~Sommet() = default;

	Point d_coordonnees;
};

class DemiCote
{
	friend class Carte;

public:

    // Renvoie l'adresse du demi-côté suivant dans le sens trigonométrique
	DemiCote* suivant() const {return d_suivant;}

    // Renvoie l'adresse du demi-côté precedent dans le sens trigonométrique
	DemiCote* precedent() const {return d_precedent;}

    // Renvoie l'adresse du demi-côté opposé
	DemiCote* oppose() const {return d_oppose;}

	// Renvoie les coordonnées du sommet dont est issu le demi-côté
    Point coordonnees() const {return d_sommet->d_coordonnees;}

	// Renvoie l'index du demi-côté
    int index() const {return d_index;}

    // Renvoie la marque du demi-côté
	int marque() const {return d_marque;}

    // Met la marque du demi-côté à m
	void changeMarque(int m) const {const_cast<int&>(d_marque) = m;}

private:

	// Construit un demi-côté dont suivant est le demi-côté suivant,
	// precedent est le demi-côté précédent, oppose est le demi-côté opposé,
	// sommet est le sommet dont il est issu et index est l'index
	DemiCote(DemiCote* suivant, DemiCote* precedent, DemiCote* oppose, Sommet* sommet, int index)
			 : d_suivant{suivant}, d_precedent{precedent}, d_oppose{oppose},
			 d_sommet{sommet}, d_index{index}, d_marque{0} {};

	// Destrusteur de demi-côté privé
	~DemiCote() = default;
	DemiCote* d_suivant;
	DemiCote* d_precedent;
	DemiCote* d_oppose;
	Sommet* d_sommet;
	int d_index;       // la position du demi-côté dans le tableau d_tabDemiCote
	int d_marque;	   // la marque du demi-côté (0 par défaut)
};

class Carte
{
public:

    // Constructeur par défaut
    Carte() : d_demiCoteParticulier{nullptr} {}

	// Destructeur
	~Carte();

    // Renvoie le nombre de demi-côtés de la carte
	int nbDemiCote() const 	{return d_tabDemiCote.size();}

    // Renvoie l'adresse du ième demi-côté du tableau de demi-côtés.
	// On suppose 0 <= i < nbDemiCotes().
	DemiCote* demiCote(int i) const	{return d_tabDemiCote[i];}

	// Renvoie l'adresse du demi-côté particulier de la carte
	DemiCote* demiCoteParticulier() const {return d_demiCoteParticulier;}

	// Modifie le demi-côté particulier de la carte
	void changeDemiCoteParticulier(DemiCote* particulier)
	{d_demiCoteParticulier = particulier;}

    // Ajoute dans la carte un nouveau côté composé de deux demi-côtés dc1 et dc2
    // dont precedent1 et precedent2 seront les demi-côtés précédents respectifs.
    // On suppose precedent1 et precedent2 déjà présents dans la carte.
    // Renvoie l'adresse de dc1.
	DemiCote* ajouteCote(DemiCote* precedent1, DemiCote* precedent2);

    // Ajoute dans la carte un nouveau côté composé de deux demi-côtés dc1 et dc2.
    // precedent1 sera le demi-côté précédent de dc1. dc2 sera issu d’un
    // nouveau sommet (à créer) dont les coordonnées sont celles du point p2.
    // On suppose precedent1 déjà présent dans la carte.
    // Renvoie l'adresse de dc1.
	DemiCote* ajouteCote(DemiCote* precedent1, const Point& p2);

    // Ajoute dans la carte un nouveau côté composé de deux demi-côtés dc1 et dc2.
    // precedent2 sera le demi-côté précédent de dc2. dc1 sera issu d’un
    // nouveau sommet (à créer) dont les coordonnées sont celles du point p1.
    // On suppose precedent2 déjà présent dans la carte.
    // Renvoie l'adresse de dc1.
	DemiCote* ajouteCote(const Point& p1, DemiCote* precedent2);

    // Ajoute dans la carte un nouveau côté composé de deux demi-côtés dc1 et dc2.
    // dc1 et dc2 seront issus de deux nouveaux sommets (à créer) dont les
	// coordonnées sont celles des points p1 et p2 respectivement.
    // Renvoie l'adresse de dc1.
	DemiCote* ajouteCote(const Point& p1, const Point& p2);


private:

	// Ajoute un nouveau demi-côté dans la carte dont precedent sera le demi-côté
	// 	précédent et oppose sera le demi-côté opposé.
	// On suppose que le demi-côté precedent est déjà présent dans la carte.
	// Le demi-côté oppose est soit déjà présent dans la carte soit nul (valeur
	// par défaut).
	// Renvoie l’adresse du nouveau demi-côté.
	DemiCote* ajouteDemiCote(DemiCote* precedent, DemiCote* oppose=nullptr);

	// Ajoute un nouveau demi-côté dans la carte qui sera issu d’un nouveau
	// sommet (à créer) dont les coordonnées sont celles du point p.
	// oppose sera le demi-côté opposé du nouveau demi-côté.
	// oppose est soit déjà présent dans la carte soit nul (valeur par défaut).
	// Renvoie l’adresse du nouveau demi-côté.
	DemiCote* ajouteDemiCote(const Point &p, DemiCote* oppose=nullptr);


	vector<DemiCote*> d_tabDemiCote;
	DemiCote* d_demiCoteParticulier;
};


#endif
