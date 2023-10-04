#include "Antenne.h"

Antenne::Antenne()
{
	pos = Point(0, 0);
	f = 0;
	dE = 0;
	id = 0;
    nom="";
}

Antenne::Antenne(double x, double y, double fr, double dEm)
{
	pos=Point(x, y);
	f = fr;
	dE = dEm;
	id = 0;
    nom="n";
}

Antenne::Antenne(std::string n,double x, double y, double dEm)
{
    pos=Point(x, y);
    f = 0;
    dE = dEm;
    id = 0;
    nom=n;
}

Antenne::Antenne(std::string n,double x, double y)
{
	pos = Point(x, y);
	f = 0;
	dE = 0;
	id = 0;
    nom=n;
}

int Antenne::getId()
{
	return id;
}

void Antenne::setId(int i)
{
	id = i;
}

Point Antenne::getPos()
{
	return pos;
}

void Antenne::setPos(double x,double y)
{
    pos = Point(x,y);
}

double Antenne::getF()
{
	return f;
}

void Antenne::setF(double fr)
{
	f = fr;
}

double Antenne::getdE()
{
	return dE;
}

void Antenne::setdE(double dEm)
{
	dE = dEm;
}

Antenne::~Antenne()
{
}

const std::string &Antenne::getNom() const
{
    return nom;
}

void Antenne::setNom(const std::string &newNom)
{
    nom = newNom;
}

