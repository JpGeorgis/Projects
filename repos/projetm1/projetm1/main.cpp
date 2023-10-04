#include "GWO.h"
#include "WOA.h"
#include "FOA.h"
#include "FOX.h"
#include "PSO.h"
#include "NGO.h"
#include <time.h>
#include <chrono>
#include <thread>
double calcul(const std::vector<double>& solution) {
   double s = 0;
   /*
    for (int i = 0; i < solution.size(); i++) {
        s += pow(solution[i], 2);
    }*/
    
    for (int i = 0; i < 10 - 1; i++) {
        float zi = solution[i] - 0 + 1;
        float zip1 = solution[i + 1] - 0 + 1;
        s += 100 * (pow(pow(zi, 2) - zip1, 2)) + pow(zi - 1, 2);
    }
    s += 390;
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
    std::vector<double> lb = {-100,-100, -100, -100, -100, -100, -100, -100, -100, -100,-100,-100, -100, -100, -100, -100, -100, -100, -100, -100, -100,-100, -100, -100, -100, -100, -100, -100, -100, -100, -100,-100, -100, -100, -100, -100, -100, -100, -100, -100, -100,-100, -100, -100, -100, -100, -100, -100, -100, -100,-100,-100, -100, -100, -100, -100, -100, -100, -100, -100,-100,-100, -100, -100, -100, -100, -100, -100, -100, -100, -100,-100, -100, -100, -100, -100, -100, -100, -100, -100, -100,-100, -100, -100, -100, -100, -100, -100, -100, -100, -100,-100, -100, -100, -100, -100, -100, -100, -100, -100 };
    std::vector<double> ub = {100, 100, 100, 100, 100, 100, 100, 100, 100, 100,100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100,100, 100, 100, 100, 100, 100, 100, 100, 100, 100,100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100 };

    OriginalPSO* test = new OriginalPSO(100000,50);

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
        //auto start = std::chrono::high_resolution_clock::now();
        testPSO();
        /*auto end = std::chrono::high_resolution_clock::now();
        auto int_s = std::chrono::duration_cast<std::chrono::milliseconds>(end - start);
        std::cout << "\nTime : " << int_s.count() << " seconds " << std::endl;*/
        printf("\nTemps d'execution: %.2fs\n", (float)(clock() - tStart) / CLOCKS_PER_SEC);
        return 0;
    
    
}

