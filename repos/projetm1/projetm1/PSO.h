#include <string>
#include <iostream>
#include <cmath>
#include <vector>
#include <functional>
#include <algorithm>
#include <stdlib.h>
#include <time.h>

class OriginalPSO {
private:
    int epoch;
    int popSize;
    double c1;
    double c2;
    double w_min;
    double w_max;

public:
    OriginalPSO(int, int, double, double, double, double);
    OriginalPSO(int, int);
    double amendPosition(double, double, double);
    std::vector<double> solve(std::vector<double>&, std::vector<double>&, std::function<double(std::vector<double>)>);
};
