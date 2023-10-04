// Algo_géo_TD1.cpp : Ce fichier contient la fonction 'main'. L'exécution du programme commence et se termine à cet endroit.
#include <SFML/Graphics.hpp>
#include"point.h"
#include "polygone.h"
#include "enveloppe.h"
#include <iostream>
#include "affichage.h"
using namespace sf;
int main()
{
	RenderWindow w(VideoMode(1000, 1000), "Enveloppe convexe");
	w.clear(Color::Black);
	
    Polygone p;
	vector<Point> T;
	T.push_back(Point(200, 200));
	T.push_back(Point(500, 200));
	T.push_back(Point(700, 300));
	T.push_back(Point(400, 500));
	T.push_back(Point(100, 400));
	T.push_back(Point(400, 400));
	T.push_back(Point(300, 300));
	T.push_back(Point(500, 300));
	enveloppe(T,p);
    trace(p,T,w);
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

// Exécuter le programme : Ctrl+F5 ou menu Déboguer > Exécuter sans débogage
// Déboguer le programme : F5 ou menu Déboguer > Démarrer le débogage

// Astuces pour bien démarrer : 
//   1. Utilisez la fenêtre Explorateur de solutions pour ajouter des fichiers et les gérer.
//   2. Utilisez la fenêtre Team Explorer pour vous connecter au contrôle de code source.
//   3. Utilisez la fenêtre Sortie pour voir la sortie de la génération et d'autres messages.
//   4. Utilisez la fenêtre Liste d'erreurs pour voir les erreurs.
//   5. Accédez à Projet > Ajouter un nouvel élément pour créer des fichiers de code, ou à Projet > Ajouter un élément existant pour ajouter des fichiers de code existants au projet.
//   6. Pour rouvrir ce projet plus tard, accédez à Fichier > Ouvrir > Projet et sélectionnez le fichier .sln.
