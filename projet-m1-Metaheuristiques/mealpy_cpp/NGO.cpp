#include "NGO.h"

OriginalNGO::OriginalNGO(int epoch1, int popSize1)
{
    srand (time(NULL));
    if(epoch1>1 && epoch1<100000)
        epoch = epoch1;
    if(popSize1>10 && popSize1<10000)
        popSize=popSize1;
}

double OriginalNGO::amendPosition(double pos, double lb, double ub)
{
    if (pos < lb) {
        return lb;
    }
    else if (pos > ub) {
        return ub;
    }
    return pos;
}

int OriginalNGO::getVoisin(int idx)
{
    //voisin de idx
    if (idx == popSize - 1) {
        return idx-1;
    }
    else{
         return idx+1;
    }/* //individu aléatoire dans la population
    int i=idx;
    while (idx == i) {
        i = rand() % popSize;
    }
    return i;*/
}

std::vector<double> OriginalNGO::solve(std::vector<double> &lb,std::vector<double> &ub, std::function<double(std::vector<double>)> func)
{
    //intitalisation de la population
    std::vector <double> fitn(popSize);

    std::vector <double> gbest(lb.size());
    std::vector<std::vector<double>> population(popSize, std::vector<double>(lb.size()));
    double fitn_gbest = std::numeric_limits<double>::infinity();
    for (int i = 0; i < popSize; ++i) {
        for (int j = 0; j < lb.size(); ++j) {
            //initialisation individu aléatoirement
            double randomValue = lb[j] + (ub[j] - lb[j]) * ((double)rand() / RAND_MAX);
            population[i][j] = randomValue;
        }
        //evaluation fitness pour l'individu i
        fitn[i] = func(population[i]);
        if (fitn_gbest > fitn[i]) {
            fitn_gbest = fitn[i];
            gbest = population[i];
        }
    }

    for(int i = 0; i < epoch ; i++){//pour chaque iteration

        for (int j = 0; j < popSize; j++) {//on evalue la population
            double r = (double)rand() / RAND_MAX;
            //tableau pour la nouvelle population
            std::vector<double> coord_new;
            std::vector<double> r1(popSize);
            for(int k = 0; k < population[j].size();k++){
                int idv = getVoisin(j);
                r1[k] = (double)rand() / RAND_MAX;
                int r2 = rand() % 2 +1;
                double Xnew;
                if(r<0.5){
                    Xnew = population[j][k] + r1[k] * (population[idv][k] - r2 * population[j][k]);
                }
                else{
                    Xnew = population[j][k] + r1[k] * (population[j][k] - population[idv][k]);
                }
                Xnew = amendPosition(Xnew, lb[k], ub[k]);
                coord_new.push_back(Xnew);
            }
            double fitn_new = func(coord_new);
            if (fitn[j] > fitn_new) {
                fitn[j]= fitn_new;
                population[j]=coord_new;
            }

            std::vector<double> coord_new2;
            double R = 0.02 * (1.0-(epoch+1.0)/epoch);
            std::vector<double> r3(popSize);
            for(int k=0;k<lb.size();k++){
                r3[k] = (double)rand() / RAND_MAX;
                double Xnew = population[j][k] + (-R+2*R*r3[k]) * population[j][k];
                Xnew = amendPosition(Xnew, lb[k], ub[k]);
                coord_new2.push_back(Xnew);
            }

            double fitn_new2 = func(coord_new2);
            if (fitn[j] > fitn_new2) {
                fitn[j] = fitn_new2;
                population[j]=coord_new2;
            }
            if (fitn_gbest > fitn[j]) {
                fitn_gbest = fitn[j];
                gbest = population[j];
            }
        }
    }
    gbest.push_back(fitn_gbest);
    return gbest;
}
