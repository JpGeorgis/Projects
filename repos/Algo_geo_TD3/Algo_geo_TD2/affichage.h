#ifndef AFFICHAGE_H
#define AFFICHAGE_H

#include "point.h"
#include "Carte.h"
#include <vector>
#include <SFML/Graphics.hpp>
using namespace sf;
using namespace std;
// trace un segment entre deux points a et b
void segment(const Point& a, const Point& b, RenderWindow& win);

void sommet(const Point& a, RenderWindow& win);
// trace la carte C dans une fenêtre graphique
void trace(const Carte &C, vector<Point>& T, RenderWindow& win);



#endif
