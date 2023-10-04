#include "enveloppe.h"
#include "point.h"
#include "polygone.h"
#include <iostream>
#include <vector>

using namespace std;


// Construit dans le polygone P l’enveloppe convexe des trois points a,b,c. On suppose P initialement vide.
// La fonction renvoie l’adresse du sommet de coordonnées c.
Sommet* enveloppe(const Point &a, const Point &b, const Point &c, Polygone &P)
{
	P.ajouteSommet(a);
	P.ajouteSommet(b, P.premier()->precedent());
	P.ajouteSommet(c, P.premier()->precedent());

	return P.premier()->precedent();
}


// Construit dans le polygone P l’enveloppe convexe de l'ensemble de points donné dans T.
// On suppose P initialement vide.
void enveloppe(vector<Point>&T, Polygone &P)
{
    //trier tab par abscisse
    for (int i = 0; i < T.size() - 1; i++)
    {
        for (int j = i + 1; j < T.size(); j++)
        {
            if (T[j].x() < T[i].x())
            {
                Point tmp = T[i];
                T[i] = T[j];
                T[j] = tmp;
            }
            else if (T[j].x() == T[i].x())
            {
                if (T[j].y() > T[i].y())
                {
                    Point tmp = T[i];
                    T[i] = T[j];
                    T[j] = tmp;
                }
            }
        }
    }

    for (int i = 0; i < T.size() ; i++)
    {
        cout << "val :" << T[i].x() << "," << T[i].y() << endl;
    }
    //enveloppe sur les 3 premiers

    Sommet* pg = enveloppe(T[0], T[1], T[2], P);
    for (int i = 3; i < T.size(); i++)
    {
        Sommet* borne1 = pg;//abscisse la plus grande
        Sommet* borne2 = borne1->suivant();//on part dans le sens trigo
        while (T[i].aGauche(borne1->coordonnees(), borne2->coordonnees()) == -1)
        {
            borne1 = borne1->suivant();
            borne2 = borne2->suivant();
        }
        Sommet* b1 = borne1;

        borne1 = pg->precedent();
        borne2 = borne1->precedent();
        while (T[i].aGauche(borne1->coordonnees(), borne2->coordonnees()) == -1)
        {
            borne1 = borne1->precedent();
            borne2 = borne2->precedent();
        }
        Sommet* b2 = borne2;
        while (b2->suivant() != b1)
        {
            P.supprimeSommet(b2->suivant());
        }
        pg = P.ajouteSommet(T[i], b2);
    }

}
