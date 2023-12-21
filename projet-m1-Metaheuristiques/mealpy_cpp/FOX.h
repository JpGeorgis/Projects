#ifndef FOX_H_INCLUDED
#define FOX_H_INCLUDED

#include <string>
#include <iostream>
#include <cmath>
#include <vector>
#include <functional>
#include <algorithm>
#include <numeric>
#include <stdlib.h>
#include <time.h>

class OriginalFOX {
public:
    OriginalFOX(int epoch1, int popSize1, double c1, double c2);
    std::vector<double> solve(std::vector<double> &lb,std::vector<double> &ub, std::function<double(std::vector<double>)> func);
private:
    void trierPopulationEnFonctionDeFitness(std::vector<double>& fitn, std::vector<std::vector<double>>& population);
    double amendPosition(double pos, double lb, double ub);
    int epoch;
    int popSize;
    double c1,c2;
};

#endif // FOX_H_INCLUDED
