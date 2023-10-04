#ifndef CARTE_H
#define CARTE_H
#include <vector>
#include "Antenne.h"
#include "Graphe.h"


class Carte
{
public:
	// constructeurs
	Carte();
    Carte(std::vector<Antenne>,std::vector<double>);
	void ajoutAntenne(Antenne);
    void ajoutFrequence(double);
	void supprimeAntenne(int id);
	Graphe calculConflits();
	void attributFrequence();
    void clearFreq();

    std::vector<Antenne> &getAntennes();
    const std::vector<double> &getFrequences() const;

private:
    std::vector<Antenne> antennes;
    std::vector<double> frequences;
	Graphe grapheConflits;
};
#endif
