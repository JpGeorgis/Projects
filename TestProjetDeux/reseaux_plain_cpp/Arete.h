#ifndef ARETE_H
#define ARETE_H
#include "Sommet.h"
class Arete
{
public:
	// constructeurs
	Arete();
	Arete(Sommet s1, Sommet s2);
	// accesseurs
	Sommet som1(){ return s1; }
	Sommet som2(){ return s2; }
	Sommet voisin(Sommet s);

private:

	Sommet s1, s2;
};
#endif
