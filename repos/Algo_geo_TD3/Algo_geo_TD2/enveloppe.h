#ifndef ENVELOPPE_H
#define ENVELOPPE_H

#include "point.h"
#include "Carte.h"

#include <vector>

using namespace std;

// Construit dans le polygone P l’enveloppe convexe des trois points a,b,c. On suppose P initialement vide.
// La fonction renvoie l’adresse du sommet de coordonnées c.
DemiCote* triangulation(const Point &a, const Point &b, const Point &c, Carte &P);

// Construit dans le polygone P l’enveloppe convexe de l'ensemble de points donné dans T.
// On suppose P initialement vide.
void triangulation(vector<Point>&T, Carte &P);



#endif
