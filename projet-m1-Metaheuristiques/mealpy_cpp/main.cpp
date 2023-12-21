#include "GWO.h"
#include "WOA.h"
#include "FOA.h"
#include "FOX.h"
#include "PSO.h"
#include "NGO.h"
#include <time.h>

double calcul(const std::vector<double>& solution) {
    double s = 0;
    for (int i = 0; i < solution.size(); i++) {
        s += pow(solution[i], 2);
    }
    return s;
}

void testGWO() {
    std::vector<double> lb = { -10, -15, -4, -2, -8 };
    std::vector<double> ub = { 10, 15, 12, 8, 20 };

    OriginalGWO* test = new OriginalGWO(1000, 50);

    std::vector<double> res = test->solve(lb, ub, calcul);

    std::cout << "Solution:";
    for (int i = 0; i < res.size() - 1; i++)
        std::cout << res[i] << "|";
    std::cout << std::endl << "Valeurs :" << res[res.size() - 1];
}

void testWOA() {
    std::vector<double> lb = { -10, -15, -4, -2, -8 };
    std::vector<double> ub = { 10, 15, 12, 8, 20 };

    OriginalWOA* test = new OriginalWOA(1000, 50);

    std::vector<double> res = test->solve(lb, ub, calcul);

    std::cout << "Solution:";
    for (int i = 0; i < res.size() - 1; i++)
        std::cout << res[i] << "|";
    std::cout << std::endl << "Valeurs :" << res[res.size() - 1];
}

void testFOA() {
    std::vector<double> lb = { -10, -15, -4, -2, -8 };
    std::vector<double> ub = { 10, 15, 12, 8, 20 };

    OriginalFOA* test = new OriginalFOA(1000, 50);

    std::vector<double> res = test->solve(lb, ub, calcul);

    std::cout << "Solution:";
    for (int i = 0; i < res.size() - 1; i++)
        std::cout << res[i] << "|";
    std::cout << std::endl << "Valeurs : " << res[res.size() - 1];
}


void testFOX() {
    std::vector<double> lb = { -10, -15, -4, -2, -8 };
    std::vector<double> ub = { 10, 15, 12, 8, 20 };

    OriginalFOX* test = new OriginalFOX(1000, 50, 0.18, 0.82);

    std::vector<double> res = test->solve(lb, ub, calcul);

    std::cout << "Solution:";
    for (int i = 0; i < res.size() - 1; i++)
        std::cout << res[i] << "|";
    std::cout << std::endl << "Valeurs :" << res[res.size() - 1];
}

void testPSO(){
    std::vector<double> lb = {-10, -15, -4, -2, -8};
    std::vector<double> ub = {10, 15, 12, 8, 20};

    OriginalPSO* test = new OriginalPSO(1000,50);

    std::vector<double> res = test->solve(lb,ub,calcul);

    std::cout<<"Solution:" ;
    for(int i=0;i<res.size()-1;i++)
        std::cout<<res[i]<<"|";
    std::cout<<std::endl<<"Valeurs : "<<res[res.size()-1];
}

void testNGO() {
    std::vector<double> lb = { -10, -15, -4, -2, -8 };
    std::vector<double> ub = { 10, 15, 12, 8, 20 };

    OriginalNGO* test = new OriginalNGO(1000, 50);

    std::vector<double> res = test->solve(lb, ub, calcul);

    std::cout << "Solution:";
    for (int i = 0; i < res.size() - 1; i++)
        std::cout << res[i] << "|";
    std::cout << std::endl << "Valeurs :" << res[res.size() - 1];
}

int main() {

        clock_t tStart = clock();
        /* Do your stuff here */
        testNGO();
        printf("\nTemps d'execution: %.2fs\n", (double)(clock() - tStart) / CLOCKS_PER_SEC);
        return 0;
    
    
}

