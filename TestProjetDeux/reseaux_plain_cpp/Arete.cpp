#include "Arete.h"

Arete::Arete()
{
    s1=Sommet(0,0,0);
    s2=Sommet(0,0,0);
}

Arete::Arete(Sommet so1, Sommet so2)
{
    s1=so1;
    s2=so2;
}

Sommet Arete::voisin(Sommet s)
{
	if (s.getID() == s1.getID())
		return s2;
	else if (s.getID() == s2.getID())
		return s1;
	else
		return Sommet(-1, -1, -1);
}
