#include "PSO.h"
#include <time.h>
#include <thread>
#include <iostream>
#include <fstream>
#include<windows.h>

std::vector<double> lb = {};
std::vector<double> ub = {};
int popSize = 50;
int numDim = 10;
int numRun = 20;
const float phi = 3.1415;
std::ofstream fichierSortie;
std::vector<std::string> nomsFichiers = { "resultsRosen.txt", "resultsRasti.txt", "resultsGriewank.txt", "resultsSphere.txt" };

double Rosenbrock(const std::vector<double>& solution) {
    double s = 0;
    for (int i = 0; i < solution.size() - 1; i++) {
        float zi = solution[i] - 0 + 1;
        float zip1 = solution[i + 1] - 0 + 1;
        s += 100 * (pow(pow(zi, 2) - zip1, 2)) + pow(zi - 1, 2);
    }
    s += 390;
    return s;
}

double Sphere(const std::vector<double>& solution) {
    double s = 0;
    for (int i = 0; i < solution.size() - 1; i++) {
        float zi = solution[i] - 0;
        s += pow(zi, 2);
    }
    s -= 450;
    return s;
}

double Rastigrin(const std::vector<double>& solution) {
    double s = 0;
    for (int i = 0; i < solution.size() - 1; i++) {
        float zi = solution[i] - 0;
        s += pow(zi, 2) - 10 * cos(2 * phi * zi) + 10;
    }
    s -= 330;
    return s;
}

double Griewank(const std::vector<double>& solution) {
    double s = 0;
    float somme = 0;
    float produit = 0;
    for (int i = 0; i < solution.size() - 1; i++) {
        float zi = solution[i] - 0;
        somme += pow(zi, 2) / 4000;
        produit *= cos(zi / pow(i + 1, 0.5));
    }
    s = somme - produit + 1 - 180;
    return s;
}

void fillBornes(int n_dim, int func) {
    double lower_bound = -5.0;
    double upper_bound = 5.0;
    if (func == 0 || func == 3) {
        lower_bound *= 20.0;
        upper_bound *= 20.0;
    }
    else if (func == 2) {
        lower_bound *= 120.0;
        upper_bound *= 120.0;
    }else{}

    for (int i = 0; i < n_dim; i++) {
        lb.push_back(lower_bound);
        ub.push_back(upper_bound);
    }
}
void clearBornes() {
    lb.clear();
    ub.clear();
}

std::vector<double> testPSO(int func) {
    std::vector<double> res;
    OriginalPSO* test = new OriginalPSO(10000 * numDim, popSize);
    if (func == 0) {
        res = test->solve(lb, ub, Rosenbrock);
    }
    else if (func == 1) {
        res = test->solve(lb, ub, Rastigrin);
    }
    else if (func == 2) {
        res = test->solve(lb, ub, Griewank);
    }
    else {
        res = test->solve(lb, ub, Sphere);
    }

    return res;
}

int main() {
    
    for (int func = 0; func < 4; func++) {
        clock_t t = clock();
        std::ofstream fichierSortie(nomsFichiers[func]);
        if (fichierSortie.is_open()) {
            for (int p = 0; p < 9; p++) {
                if (p == 1 || p == 4 || p == 7) {
                    numDim *= 5;
                }
                else if (p == 2 || p == 5 || p == 8) {
                    numDim *= 10;
                }
                if (p >= 3 && p < 6) {
                    popSize *= 2;
                }
                else if (p >= 6) {
                    popSize *= 10;
                }
                fichierSortie << "PopSize : " << popSize << " & Num of Dim : " << numDim << std::endl;
                fillBornes(numDim, func);
                for (int i = 0; i < numRun; i++) {
                    std::vector<double> res;
                    fichierSortie << "Run " << i + 1 << " : " << std::endl;
                    clock_t tStart = clock();
                    res = testPSO(func);
                    fichierSortie << "Solution : ";
                    for (int i = 0; i < res.size() - 1; i++) {
                        fichierSortie << res[i] << "|";
                    }
                    fichierSortie << "\nValeurs : " << res[res.size() - 1] << std::endl;
                    fichierSortie << "Temps d'execution : " << (float)(clock() - tStart) / CLOCKS_PER_SEC << "s\n\n" << std::endl;
                    Sleep(2000);
                }
                popSize = 50;
                numDim = 10;
                clearBornes();
            }
            fichierSortie.close();
            std::cout << "Le texte a ete ecrit avec succes dans le fichier." << std::endl;
        }
        else {
            std::cerr << "Erreur : Impossible d'ouvrir le fichier." << std::endl;
        }
        fichierSortie << "Temps d'execution : " << (float)(clock() - t) / CLOCKS_PER_SEC << "s\n\n" << std::endl;
    }
    return 0;
}

