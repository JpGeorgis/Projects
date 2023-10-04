#include "Graphe.h"
#include <algorithm>
#include <math.h>

Graphe::Graphe()
{
}

Graphe::Graphe(std::vector<Antenne> antennes)
{
	for (int i = 0; i < antennes.size(); i++) {
		sommets.push_back(Sommet(i,1,0));
	}
	degre(antennes);

}

void Graphe::Coloration()
{
	//Ordonner les sommets par ordre decroissant de degres.
    /*sort(sommets.begin(), sommets.end(), isInf);
	for (int i = 1; i < sommets.size(); i++) {
		for (int j = 0; j < aretes.size(); j++) {

				Sommet s=aretes[j].voisin(sommets[i]);
				if (s.getID() != -1) {
					if(!(s.getColor()<sommets[i].getColor()))
						s.setColor(sommets[i].getColor() + 1);
				}
		}
    }*/
}

bool Graphe::isInf(int i, int j)
{
	return (sommets[i].getDeg() > sommets[j].getDeg());
}

std::vector<Arete> Graphe::getAretes()
{
    return std::vector<Arete>();
}

std::vector<Sommet> Graphe::getSommets()
{
    return std::vector<Sommet>();
}

double Graphe::dist(Antenne a, Antenne b)
{ 
	double x = a.getPos().x() - b.getPos().x();
	double y = a.getPos().y() - b.getPos().y();
	return sqrt(pow(x,2)+pow(y,2));
}

void Graphe::degre(std::vector<Antenne> antennes)
{
	for (int i = 0; i < antennes.size()-1; i++) {
		for (int j = i + 1; j < antennes.size(); j++) {
			if (antennes[i].getdE() > dist(antennes[i], antennes[j])) {
				sommets[i].incrDeg();
				sommets[j].incrDeg();
				aretes.push_back(Arete(sommets[i], sommets[j]));
			}
		}
	}
}

