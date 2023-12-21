#include "FOA.h"


OriginalFOA::OriginalFOA(int epoch1 = 10000, int popSize1 = 100)
{
    srand (time(NULL));
    if(epoch1>1 && epoch1<100000)
        epoch = epoch1;
    if(popSize1>10 && popSize1<10000)
        popSize=popSize1;
}

bool compareFitness3(const double& a, const double& b) {
    return a < b;  // Tri par ordre décroissant
}

void OriginalFOA::trierPopulationEnFonctionDeFitness(std::vector<double>& fitn, std::vector<std::vector<double>>& population) {
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
        return compareFitness3(fitn[i], fitn[j]);
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

//corriger la posisition si hors des limites
double OriginalFOA::amendPosition(double pos, double lb, double ub) {
    if (pos < lb) {
        return lb;
    } else if (pos > ub) {
        return ub;
    }
    return pos;
}
std::vector<double> OriginalFOA::normConsecutiveAdjacent(const std::vector<double>& position) {///////////////////////////////////////////////////////////////////////////////////////////////-
    std::vector<double> norms;
    int n_dims = position.size();

    for (int x = 0; x < n_dims - 1; x++) {
        double norm = std::hypot(position[x], position[x + 1]);
        norms.push_back(norm);
    }

    double last_norm = std::hypot(position[n_dims - 1], position[0]);
    norms.push_back(last_norm);

    return norms;
}

//algo du  WOA
std::vector<double> OriginalFOA::solve(std::vector<double> &lb,std::vector<double> &ub, std::function<double(std::vector<double>)> func){

    //intitalisation de la population
    std::vector<std::vector<double>> population(popSize, std::vector<double>(lb.size()));
    for (int i = 0; i < popSize; ++i) {
        for (int j = 0; j < lb.size(); ++j) {
            double randomValue =lb[j] + (ub[j] - lb[j]) * ((double)rand() / RAND_MAX);
            population[i][j] = randomValue;
        }
    }

    std::vector <double> fitn(popSize);//tableau pour l'evaluation de la population
    for(int i = 0; i < this->epoch ; i++){//pour chaque iteration

        for (int j = 0; j < popSize; ++j) {//on evalue la population
            fitn[j] = func(population[j]);
        }
        //pour chaque individu
        for (int j = 0; j < popSize; ++j) {
            //pour les nouvelles coordonnées
            std::vector<double> coord_new;

            //pour chaque dimension
            for(int k = 0; k < population[j].size();k++){
                std::default_random_engine generator;
                std::normal_distribution<double> distribution(lb[k], ub[k]);
                double pos_new = population[j][k] + (double)rand() / RAND_MAX * distribution(generator);
                coord_new.push_back(pos_new);
            }

            //nouvelles coordonnées
            std::vector<double> coord_calc;
            coord_calc = normConsecutiveAdjacent(coord_new);
            for(int k = 0; k < population[j].size();k++){//pour chaque coordonnées
                coord_calc[k] = amendPosition(coord_calc[k],ub[k],lb[k]);
            }
            //greedy selection
            double fitn_new = func(coord_calc);
            if(fitn[j]>fitn_new){
                fitn[j]=fitn_new;
                population[j]=coord_calc;
            }
        }
    }
    trierPopulationEnFonctionDeFitness(fitn, population);
    std::vector<double> res = population[0];
    res.push_back(fitn[0]);
    return res;
}
