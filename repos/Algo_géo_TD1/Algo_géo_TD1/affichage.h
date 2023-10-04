#ifndef AFFICHAGE_H
#define AFFICHAGE_H

#include "point.h"
#include "polygone.h"
#include <vector>
#include <SFML/Graphics.hpp>
using namespace sf;
using namespace std;
// trace un segment entre deux points a et b
void segment(const Point & a, const Point & b, RenderWindow& win);

void sommet(const Point& a, RenderWindow& win);

// trace le polygone P dans une fenêtre graphique
void trace(const Polygone &P, vector<Point>& T, RenderWindow& w);



#endif
