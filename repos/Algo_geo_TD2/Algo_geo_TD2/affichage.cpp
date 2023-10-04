#include "affichage.h"
#include "point.h"
#include "carte.h"
#include <vector>
#include <SFML/Graphics.hpp>

using namespace sf;
using namespace std;
// trace un segment entre deux points a et b

void segment(const Point& a, const Point& b, RenderWindow& win)
{
	sf::VertexArray lines(sf::LinesStrip, 2);
	lines[0].position = Vector2f(a.x(), a.y());
	lines[0].color = Color(255, 255, 255);
	lines[1].position = Vector2f(b.x(), b.y());
	lines[1].color = Color(255, 255, 255);;
	win.draw(lines);
}
void sommet(const Point& a, RenderWindow& win)
{
	int r = 3;
	CircleShape circle(r);
	circle.setPosition(Vector2f(a.x() - (r + 1), a.y() - r));
	circle.setFillColor(Color(0, 0, 255));
	win.draw(circle);
}
// trace la carte C dans une fenêtre graphique
void trace(const Carte& C, vector<Point>& T, RenderWindow& win)
{
	for (int j = 0; j < C.nbDemiCote(); j++) {
		segment(C.demiCote(j)->coordonnees(), C.demiCote(j)->oppose()->coordonnees(),win);
	}
	for (int i = 0; i < T.size(); i++)
	{
		sommet(T[i], win);
	}
}


