#ifndef FOA_H_INCLUDED
#define FOA_H_INCLUDED

#include <string>
#include <iostream>
#include <cmath>
#include <vector>
#include <functional>
#include <algorithm>
#include <random>
#include <stdlib.h>
#include <time.h>

class OriginalFOA{
public:
    OriginalFOA(int epoch1, int popSize1);
    std::vector<double> solve(std::vector<double> &lb,std::vector<double> &ub, std::function<double(std::vector<double>)> func);
private:
    std::vector<double> normConsecutiveAdjacent(const std::vector<double>& position);
    void trierPopulationEnFonctionDeFitness(std::vector<double>& fitn, std::vector<std::vector<double>>& population);
    double amendPosition(double pos, double lb, double ub);
    int epoch;
    int popSize;
};




#endif // FOA_H_INCLUDED
