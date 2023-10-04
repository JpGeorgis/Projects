#ifndef GRAPHE_H
#define GRAPHE_H
#include "Sommet.h"
#include "Arete.h"
#include "Antenne.h"
#include <vector>

class Graphe
{
public:
	// constructeurs
	Graphe();
    Graphe(std::vector<Antenne>);
	
	void Coloration();
	bool isInf(int,int);
    std::vector<Arete> getAretes();
    std::vector<Sommet> getSommets();
	double dist(Antenne, Antenne);
    void degre(std::vector<Antenne>);



private:

    std::vector<Arete> aretes;
    std::vector<Sommet> sommets;
};
#endif

