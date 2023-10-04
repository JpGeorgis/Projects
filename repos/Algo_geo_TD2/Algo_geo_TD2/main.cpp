#include <SFML/Graphics.hpp>
#include <iostream>
#include"point.h"
#include "affichage.h"
#include "Carte.h"
using namespace sf;
int main()
{
	RenderWindow w(VideoMode(1000, 1000), "Cartes");
	w.clear(Color::Black);
	Carte c;
	vector<Point> T;
	T.push_back(Point(200, 200));
	T.push_back(Point(500, 200));
	T.push_back(Point(700, 300));
	T.push_back(Point(400, 500));
	T.push_back(Point(100, 400));
	T.push_back(Point(400, 400));
	T.push_back(Point(300, 300));
	T.push_back(Point(500, 300));
	T.push_back(Point(700, 500));
	
	c.ajouteCote(T[0], T[6]);
	c.ajouteCote(T[6], T[4]);
	c.ajouteCote(T[4], T[7]);
	c.ajouteCote(T[4], T[5]);
	c.ajouteCote(T[0], T[7]);
	c.ajouteCote(T[7], T[5]);
	c.ajouteCote(T[1], T[7]);
	c.ajouteCote(T[3], T[5]);
	c.ajouteCote(T[5], T[2]);
	c.ajouteCote(T[1], T[2]);
	c.ajouteCote(T[2], T[3]);
	c.ajouteCote(T[8], T[3]);
	c.ajouteCote(T[8], T[2]);
	trace(c, T, w);



	w.display();
	while (w.isOpen())
	{
		Event event;
		while (w.pollEvent(event))
		{
			if (event.type == Event::Closed)
				w.close();
		}

	}
}

