#include "affichage.h"
#include "point.h"
#include "polygone.h"
#include <vector>
#include <SFML/Graphics.hpp>

using namespace sf;
using namespace std;



// trace un segment entre deux points a et b
void segment(const Point & a, const Point &b , RenderWindow& win)
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
	circle.setPosition(Vector2f(a.x()-(r+1), a.y()-r));
	circle.setFillColor(Color(0, 0, 255));
	win.draw(circle);
}

// trace le polygone P dans une fenêtre graphique
void trace(const Polygone &P, vector<Point>& T, RenderWindow& w)
{
	
	Sommet* s = P.premier();
	while (s->suivant() != P.premier())
	{
		segment(s->coordonnees(), s->suivant()->coordonnees(),w);
		
		s = s->suivant();
		
	}
	
	segment(s->coordonnees(), P.premier()->coordonnees(),w);

	
	for (int i = 0; i < T.size(); i++)
	{
		sommet(T[i],w);
	}
	
	
	
}




