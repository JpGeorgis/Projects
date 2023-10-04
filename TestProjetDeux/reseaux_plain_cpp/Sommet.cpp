#include "Sommet.h"

Sommet::Sommet()
{
    ID = 0;
    color = 0;
    deg = 0;
}

Sommet::Sommet(int id, int col, int degre)
{
	ID = id;
	color = col;
	deg = degre;
}

void Sommet::setID(int id)
{
	ID = id;
}

void Sommet::setColor(int c)
{
	color = c;
}

void Sommet::setDeg(int d)
{
	deg = d;
}

void Sommet::incrDeg()
{
	deg++;
}
