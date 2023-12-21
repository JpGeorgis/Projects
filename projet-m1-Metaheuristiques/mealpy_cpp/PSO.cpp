#include "PSO.h"

OriginalPSO::OriginalPSO(int epoch1, int popSize1, double c11, double c22, double w_min1, double w_max1) {
    srand (time(NULL));
    if (epoch1 > 1 && epoch1 < 100000)
        epoch = epoch1;
    else
        epoch = 1000;
    if (popSize1 > 10 && popSize1 < 10000)
        popSize = popSize1;
    else
        popSize = 50;
    if (c11 >= 1 && c11 <= 3)
        c1 = c11;
    else
        c1 = 2.05;
    if (c22 >= 1 && c22 <= 3)
        c2 = c22;
    else
        c2 = 2.05;
    if (w_min1 >= 0.1 && w_min1 <= 0.5)
        w_min = w_min1;
    else
        w_min = 0.4;
    if (w_max1 >= 0.8 && w_max1 <= 2.0)
        w_max = w_max1;
    else
        w_max = 0.9;
}
OriginalPSO::OriginalPSO(int epoch1, int popSize1) {
    srand(time(NULL));
    if (epoch1 > 1 && epoch1 < 100000)
        epoch = epoch1;
    else
        epoch = 1000;
    if (popSize1 > 10 && popSize1 < 10000)
        popSize = popSize1;
    else
        popSize = 50;
    c1 = 2.05;
    c2 = 2.05;
    w_min = 0.4;
    w_max = 0.9;
}

std::vector<double> OriginalPSO::solve(std::vector<double>& lb, std::vector<double>& ub, std::function<double(std::vector<double>)> func) {
    //intitalisation de la population
    std::vector <double> fitn(popSize);
    std::vector <double> fitn_pbest(popSize);
    // meilleure position de chq particule
    std::vector <std::vector <double>> pbest(popSize, std::vector<double>(lb.size()));
    std::vector<std::vector<double>> population(popSize, std::vector<double>(lb.size()));
    std::vector<std::vector<double>> velocity(popSize, std::vector<double>(lb.size()));
    std::vector<std::vector<double>> v_max(popSize, std::vector<double>(lb.size()));
    std::vector<std::vector<double>> v_min(popSize, std::vector<double>(lb.size()));
    std::vector <double> gbest(lb.size());
    // meilleure fitness globale
    double fitn_gbest = std::numeric_limits<double>::infinity();

    for (int i = 0; i < popSize; i++) {
        for (int j = 0; j < lb.size(); j++) {
            //initialisation position
            double randomValue = lb[j] + (ub[j] - lb[j]) * ((double)rand() / RAND_MAX);
            population[i][j] = randomValue;
            //initialisation vmin, vmax
            v_max[i][j] = 0.5 * (ub[j] - lb[j]);
            v_min[i][j] = -v_max[i][j];
            //initialisation vitesse
            double randomValue1 = v_min[i][j] + (v_max[i][j] - v_min[i][j]) * ((double)rand() / RAND_MAX);
            velocity[i][j] = randomValue1;
        }
        //evaluation fitness pour la particule i
        fitn_pbest[i] = std::numeric_limits<double>::infinity();
        fitn[i] = func(population[i]);
        if (fitn_pbest[i] > fitn[i]) {
            fitn_pbest[i] = fitn[i];
            pbest[i] = population[i];
        }
        if (fitn_gbest > fitn_pbest[i]) {
            fitn_gbest = fitn_pbest[i];
            gbest = population[i];
        }

    }

    for (int i = 0; i < epoch; i++) {
        //weight down
        double w = (epoch - (double)i) / epoch * (w_max - w_min) + w_min;

        for (int j = 0; j < popSize; j++) {

            for (int k = 0; k < velocity[j].size(); k++) {
                double r1 = (double)rand() / RAND_MAX;
                double r2 = (double)rand() / RAND_MAX;
                //mise à jour de la vitesse
                velocity[j][k] = w * velocity[j][k] + c1 * r1 * (pbest[j][k] - population[j][k])
                    + c2 * r2 * (gbest[k] - population[j][k]);
                //mise à jour de la position
                population[j][k] = population[j][k] + velocity[j][k];
                population[j][k] = amendPosition(population[j][k], lb[k], ub[k]);
            }
            //evaluation fitness
            fitn[j] = func(population[j]);
            if (fitn_pbest[j] > fitn[j]) {
                fitn_pbest[j] = fitn[j];
                pbest[j] = population[j];
                
            }
		if (fitn_gbest > fitn_pbest[j]) {
                    fitn_gbest = fitn_pbest[j];
                    gbest = pbest[j];
                }


        }
    }

    gbest.push_back(fitn_gbest);
    return gbest;
}

double OriginalPSO::amendPosition(double pos, double lb, double ub) {
    if (pos < lb) {
        return lb;
    }
    else if (pos > ub) {
        return ub;
    }
    return pos;
}
