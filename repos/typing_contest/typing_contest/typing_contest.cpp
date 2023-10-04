// typing_contest.cpp : Ce fichier contient la fonction 'main'. L'exécution du programme commence et se termine à cet endroit.

#include <iostream>
#include <conio.h>
#include <chrono>
#include <windows.h>
#include <vector>

struct err
{
    char l;
    int o;
};

using namespace std;

string text = "test saisie texte";
string erreurs = "";

int main()
{
    int i = 0, e = 0;
    char c;
    cout << "Appuyez sur une touche pour commencer :" << endl;
    _getch();
    cout << text << endl;
    std::chrono::high_resolution_clock::time_point a = std::chrono::high_resolution_clock::now();
    while (i < text.size())
    {
        c = _getch();
        cout << c;
        if (c != text[i])
        {
            //cout<< "\a";
            Beep(1000, 50);
            e++;
            erreurs += text[i];
        }
        i++;
    }
    std::chrono::high_resolution_clock::time_point b = std::chrono::high_resolution_clock::now();
    unsigned int time = std::chrono::duration_cast<std::chrono::milliseconds>(b - a).count();
    cout << endl << "Vous avez fait " << e << " erreurs en " << time << " millisecondes";
    cout << endl << "lettres fausses : " << erreurs;
    /*char cherche='a';
    */
    //prendre premiere lettre, compter nbr occurrence, effacer toutes les occurrence, recommencer jusqu'a ce que erreur.size()==0
    vector<err> taberr;
    while (erreurs.size() > 0)
    {
        err x;
        x.l = erreurs[0];
        x.o = 0;
        while (erreurs.rfind(erreurs[0]) != 0)
        {
            x.o++;
            erreurs.erase(erreurs.rfind(erreurs[0]), 1);
        }
        taberr.push_back(x);

    }/**/
    for (int i = 0; i < taberr.size(); i++)
    {
        cout << endl << taberr[i].l << " : " << taberr[i].o;
    }
}
