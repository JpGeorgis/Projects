#include "Carte.h"

Carte::Carte()
{
    antennes=std::vector<Antenne>();
    frequences =std::vector<double>();
    grapheConflits = Graphe();
}

Carte::Carte(std::vector<Antenne> a, std::vector<double> f)
{
	antennes = a;
	frequences = f;
	grapheConflits = Graphe();
}

void Carte::ajoutAntenne(Antenne a)
{
	antennes.push_back(a);
}

void Carte::ajoutFrequence(double f){
    frequences.push_back(f);
}

void Carte::supprimeAntenne(int id)
{
	for (int i = 0; i < antennes.size();i++) {
		if (antennes[i].getId() == id) {
			antennes.erase(antennes.begin()+i);
		}
	}
}

Graphe Carte::calculConflits()
{
	return Graphe(antennes);
}

void Carte::attributFrequence()
{

}

void Carte::clearFreq()
{
    frequences.clear();
}

std::vector<Antenne> &Carte::getAntennes()
{
    return antennes;
}

const std::vector<double> &Carte::getFrequences() const
{
    return frequences;
}


