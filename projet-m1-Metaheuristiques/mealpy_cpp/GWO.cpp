#include "GWO.h"

OriginalGWO::OriginalGWO(int epoch1 = 10000, int popSize1 = 100)
{
    srand (time(NULL));
    if(epoch1>1 && epoch1<100000)
        epoch = epoch1;
    if(popSize1>10 && popSize1<10000)
        popSize=popSize1;
}

bool compareFitness(const double& a, const double& b) {
    return a < b;  // Tri par ordre décroissant
}

void OriginalGWO::trierPopulationEnFonctionDeFitness(std::vector<double>& fitn, std::vector<std::vector<double>>& population) {
    // Vérifier que les vecteurs ont la même taille
    if (fitn.size() != population.size()) {
        // Gérer l'erreur ou lancer une exception
        return;
    }

    // Créer un vecteur d'indices pour conserver l'ordre initial
    std::vector<int> indices(fitn.size());
    for (int i = 0; i < indices.size(); i++) {
        indices[i] = i;
    }

    // Trier les indices en fonction des valeurs de fitn
    std::sort(indices.begin(), indices.end(), [&fitn](int i, int j) {
        return compareFitness(fitn[i], fitn[j]);
    });

    // Réorganiser fitn et population en utilisant l'ordre des indices triés
    std::vector<double> fitnTri(fitn.size());
    std::vector<std::vector<double>> populationTri(population.size());

    for (int k = 0; k < indices.size(); k++) {
        int indice = indices[k];
        fitnTri[k] = fitn[indice];
        populationTri[k] = population[indice];
    }

    // Mettre à jour les vecteurs d'origine avec les vecteurs triés
    fitn = fitnTri;
    population = populationTri;
}

//corriger la posisition si hors des limites
double OriginalGWO::amendPosition(double pos, double lb, double ub) {
    if (pos < lb) {
        return lb;
    } else if (pos > ub) {
        return ub;
    }
    return pos;
}

//algo du gwo
std::vector<double> OriginalGWO::solve(std::vector<double> &lb,std::vector<double> &ub, std::function<double(std::vector<double>)> func){

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
        //tri pour recuperer les 3 meilleurs individus
        trierPopulationEnFonctionDeFitness(fitn, population);
        std::vector<std::vector<double>> list_best;
        list_best.push_back(population[0]);
        list_best.push_back(population[1]);
        list_best.push_back(population[2]);
        double a = 2*(1 - i/this->epoch);
        //tableau pour la nouvelle population
        //std::vector<std::vector<double>> pop_new;
        //pour chaque individu
        for (int j = 0; j < popSize; ++j) {
            //valeurs pour les calculs
            double A1 = a * (2 * (double)rand() / RAND_MAX - 1);
            double A2 = a * (2 * (double)rand() / RAND_MAX - 1);
            double A3 = a * (2 * (double)rand() / RAND_MAX - 1);
            double C1 = 2 * (double)rand() / RAND_MAX;
            double C2 = 2 * (double)rand() / RAND_MAX;
            double C3 = 2 * (double)rand() / RAND_MAX;
            //tableau pour la nouvelle population
            std::vector<double> coord_new;
            //pour chaque coordonnée de l'individu
            for(int k = 0; k < population[j].size();k++){
                //on calcule une nouvelle coordonée
                double X1 = list_best[0][k] - A1 * std::abs(C1 * list_best[0][k] - population[j][k]);
                double X2 = list_best[1][k] - A2 * std::abs(C2 * list_best[1][k] - population[j][k]);
                double X3 = list_best[2][k] - A3 * std::abs(C3 * list_best[2][k] - population[j][k]);
                double Xnew = (X1 + X2 + X3) / 3.0;
                Xnew = amendPosition(Xnew, lb[k], ub[k]);
                coord_new.push_back(Xnew);
            }
            //pop_new.push_back(coord_new);
            //greedy selection
            double fitn_new = func(coord_new);
            if(fitn[j]>fitn_new){
                fitn[j]=fitn_new;
                population[j]=coord_new;
            }
        }
    }
    trierPopulationEnFonctionDeFitness(fitn, population);
    std::vector<double> res = population[0];
    res.push_back(fitn[0]);
    return res;
}

