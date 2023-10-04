#include "enveloppe.h"
#include "point.h"
#include "Carte.h"
#include <iostream>
#include <vector>
#include <algorithm>

using namespace std;


// Construit dans le polygone P l’enveloppe convexe des trois points a,b,c. On suppose P initialement vide.
// La fonction renvoie l’adresse du sommet de coordonnées c.
DemiCote* triangulation(const Point &a, const Point &b, const Point &c, Carte &P)
{
    DemiCote* dc= P.ajouteCote(a,b);
    if (c.aGauche(a, b) == -1) {
        P.ajouteCote(dc,c);
        return P.ajouteCote(c,dc->oppose());
    }
    else {

    return ;
    }
    
    
   
}

bool tri(const Point &a, const Point &b) {
    return (a.x() < b.x());
}

// Construit dans le polygone P l’enveloppe convexe de l'ensemble de points donné dans T.
// On suppose P initialement vide.
void triangulation(vector<Point>&T, Carte &P)
{
    
    sort(T.begin(), T.end(), tri);

    Sommet * pg =enveloppe(T[0], T[1], T[2], P);

    for (int i = 3; i < T.size(); i++)
    {
        Sommet* borne1 = pg;//abscisse la plus grande
        Sommet* borne2 = borne1->suivant();//on part dans le sens trigo
        while (T[i].aGauche(borne1->coordonnees(),borne2->coordonnees())==-1)
        {
            borne1 = borne1->suivant();
            borne2 = borne2->suivant();
        }
        Sommet* b1 = borne1;

        borne1 =pg;
        borne2 = borne1->precedent();
        while (T[i].aGauche(borne1->coordonnees(), borne2->coordonnees()) == 1)
        {
            borne1 = borne1->precedent();
            borne2 = borne2->precedent();
        }
        Sommet* b2 = borne1;
       while(b2->suivant()!=b1)
        {
            P.supprimeSommet(b2->suivant());
        }
       pg= P.ajouteSommet(T[i],b2);
    }
}
