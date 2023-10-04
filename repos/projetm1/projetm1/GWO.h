#ifndef GWO_H_INCLUDED
#define GWO_H_INCLUDED

#define PI 3.14159265

#include <string>
#include <iostream>
#include <cmath>
#include <vector>
#include <functional>
#include <algorithm>
#include <stdlib.h>
#include <time.h>



class OriginalGWO {
public:
    OriginalGWO(int epoch1, int popSize1);
    std::vector<double> solve(std::vector<double> &lb,std::vector<double> &ub, std::function<double(std::vector<double>)> func);
private:
    void trierPopulationEnFonctionDeFitness(std::vector<double>& fitn, std::vector<std::vector<double>>& population);
    double amendPosition(double pos, double lb, double ub);
    int epoch;
    int popSize;
};

#endif // GWO_H_INCLUDED

