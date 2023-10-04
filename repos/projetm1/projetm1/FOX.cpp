#include "FOX.h"

OriginalFOX::OriginalFOX(int epoch1 = 10000, int popSize1 = 100, double newc1 = 0.18, double newc2 = 0.82)
{
    srand(time(NULL));
    if (epoch1 > 1 && epoch1 < 100000)
        epoch = epoch1;
    if (popSize1 > 10 && popSize1 < 10000)
        popSize = popSize1;
    if (newc1 > -100 && newc1 < 100)
        this->c1 = newc1;
    if (newc2 > -100 && newc2 < 100)
        this->c2 = newc2;
}

double OriginalFOX::amendPosition(double pos, double lb, double ub) {
    if (pos < lb) {
        return lb;
    }
    else if (pos > ub) {
        return ub;
    }
    return pos;
}


bool compareFitness2(const double& a, const double& b) {
    return a < b;  // Tri par ordre décroissant
}

void OriginalFOX::trierPopulationEnFonctionDeFitness(std::vector<double>& fitn, std::vector<std::vector<double>>& population) {
    // Vérifier que les vecteurs ont la même taille
    if (fitn.size() != population.size()) {
        // Gérer l'erreur ou lancer une exception
        return;
    }

    // Créer un vecteur d'indices pour conserver l'ordre initial
    std::vector<int> indices(fitn.size());
    for (int i = 0; i < indices.size(); ++i) {
        indices[i] = i;
    }

    // Trier les indices en fonction des valeurs de fitn
    std::sort(indices.begin(), indices.end(), [&fitn](int i, int j) {
        return compareFitness2(fitn[i], fitn[j]);
        });

    // Réorganiser fitn et population en utilisant l'ordre des indices triés
    std::vector<double> fitnTri(fitn.size());
    std::vector<std::vector<double>> populationTri(population.size());

    for (int k = 0; k < indices.size(); ++k) {
        int indice = indices[k];
        fitnTri[k] = fitn[indice];
        populationTri[k] = population[indice];
    }

    // Mettre à jour les vecteurs d'origine avec les vecteurs triés
    fitn = fitnTri;
    population = populationTri;
}

//algo du FOX
std::vector<double> OriginalFOX::solve(std::vector<double>& lb, std::vector<double>& ub, std::function<double(std::vector<double>)> func) {
    //initialisation de la population
    std::vector<std::vector<double>> population(popSize, std::vector<double>(lb.size()));
    for (int i = 0; i < popSize; ++i) {
        for (int j = 0; j < lb.size(); ++j) {
            double randomValue = lb[j] + (ub[j] - lb[j]) * ((double)rand() / RAND_MAX);
            population[i][j] = randomValue;
        }
    }

    std::vector<double> fitn(popSize);
    int mint = 10000000;
    for (int i = 0; i < this->epoch; i++) {//pour chaque iteration
        for (int j = 0; j < popSize; ++j) {//on evalue la population
            fitn[j] = func(population[j]);
        }
        //tri pour recuperer le meilleur individu
        trierPopulationEnFonctionDeFitness(fitn, population);
        std::vector<double> best_individu(lb.size());
        best_individu = population[0];


        double aa = 2 * (1 - (i / epoch));

        std::vector<double> pos_new;
        double r = (double)rand() / RAND_MAX;
        double p = (double)rand() / RAND_MAX;

        for (int j = 0; j < popSize; ++j) {//pour chaque individu
            std::vector<double> t1(population[j].size());
            std::vector<double> sps(population[j].size());
            std::vector<double> dis(population[j].size());
            if (r > 0.5) {
                for (int k = 0; k < population[j].size(); k++) {//pour chaque dimension
                    t1[k] = rand() / RAND_MAX;//random entre 0 et 1
                    sps[k] = best_individu[k] / t1[k];
                    dis[k] = 0.5 * sps[k] * t1[k];
                }

                //calcul de la moyenne
                double sum = std::accumulate(t1.begin(), t1.end(), 0.0);
                double tt = sum / t1.size();

                double t = tt / 2;
                double jump = 0.5 * 9.81 * pow(t, 2);

                //calcul de la nouvelle valeure
                if (p > c1) {
                    for (int k = 0; k < population[j].size(); k++) {//pour chaque dimension
                        double res = dis[k] * jump * c1;
                        res = amendPosition(res, lb[k], ub[k]);
                        pos_new.push_back(res);
                    }
                }
                else {
                    for (int k = 0; k < population[j].size(); k++) {//pour chaque dimension
                        double res = dis[k] * jump * c2;
                        res = amendPosition(res, lb[k], ub[k]);
                        pos_new.push_back(res);
                    }
                }

                if (mint > tt)
                    mint = tt;
            }
            else {
                //calcul de la nouvelle valeure
                for (int k = 0; k < population[j].size(); k++) {//pour chaque dimension
                    double res = best_individu[k] + rand() * (mint * aa);
                    res = amendPosition(res, lb[k], ub[k]);
                    pos_new.push_back(res);
                }
            }
            //greedy selection
            double fitn_new = func(pos_new);
            if (fitn[j] > fitn_new) {
                fitn[j] = fitn_new;
                population[j] = pos_new;
            }
        }
    }
    trierPopulationEnFonctionDeFitness(fitn, population);
    std::vector<double> res = population[0];
    res.push_back(fitn[0]);
    return res;
}
