#include <iostream>
#include <conio.h>
#include <chrono>
#include <windows.h>
#include <vector>

using namespace std;

int main()
{
    string source = "test de frappe de caracteres";
    string erreurs = "";
    char c;
    int nb_errors = 0;
    cout << "Appuyez sur une touche pour demarrer le chrono" << endl;
    _getch();
    cout << source << endl << endl;
    std::chrono::high_resolution_clock::time_point start = std::chrono::high_resolution_clock::now();
    for (int i = 0; i < source.size(); i++)
    {
        c = _getch();
        cout << c;
        if (c != source[i])
        {
            Beep(1000, 50);
            nb_errors++;
            erreurs += source[i];
        }
    }
    std::chrono::high_resolution_clock::time_point end = std::chrono::high_resolution_clock::now();
    unsigned int diffTime = std::chrono::duration_cast<std::chrono::milliseconds>(end - start).count();
    cout << endl << endl << nb_errors << " erreurs sur " << source.size() << " caracteres en " << diffTime << " millisecondes" << endl;
    if (nb_errors > 0) {
        
    
        for (int i = 0; i < erreurs.size(); i++) 
        {
            int nbocc = 1;
            int cmp = 0;
            for (int k = 0; k < source.size(); k++) 
            {
                    if (erreurs[i] == source[k])
                    {
                        cmp++;
                    }
            }
            if (erreurs[i] != '~') 
            {
                for (int j = i+1; j < erreurs.size(); j++) 
                {
                    if (erreurs[i] == erreurs[j])
                    {
                        nbocc++;
                        erreurs[j] = '~';
                    }
                }
                
                cout  << nbocc << "/" << cmp << " fautes sur : " << erreurs[i] << endl;
            }
        }
        cout << endl << "caracteres faux : " << endl;
        for (int i = 0; i < erreurs.size(); i++)
        {
            if(erreurs[i]!='~')
                cout << erreurs[i];
        }
    }
    return 0;
}
