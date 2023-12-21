#include <string>
#include <iostream>
#include <cmath>
#include <vector>
#include <functional>
#include <algorithm>
#include <stdlib.h>
#include <time.h>

class OriginalNGO {
private:
    int epoch;
    int popSize;

public:
    OriginalNGO(int, int);
    double amendPosition(double, double, double);
    int getVoisin(int);
    std::vector<double> solve(std::vector<double>&, std::vector<double>&, std::function<double(std::vector<double>)>);
};
