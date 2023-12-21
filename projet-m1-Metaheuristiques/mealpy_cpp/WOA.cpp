#include "WOA.h"


OriginalWOA::OriginalWOA(int epoch1 = 10000, int popSize1 = 100)
{
    srand (time(NULL));
    if(epoch1>1 && epoch1<100000)
        epoch = epoch1;
    if(popSize1>10 && popSize1<10000)
        popSize=popSize1;
}

bool compareFitness1(const double& a, const double& b) {
    return a < b;  // Tri par ordre décroissant
}

void OriginalWOA::trierPopulationEnFonctionDeFitness(std::vector<double>& fitn, std::vector<std::vector<double>>& population) {
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
        return compareFitness1(fitn[i], fitn[j]);
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
double OriginalWOA::amendPosition(double pos, double lb, double ub) {
    if (pos < lb) {
        return lb;
    } else if (pos > ub) {
        return ub;
    }
    return pos;
}

//algo du  WOA
std::vector<double> OriginalWOA::solve(std::vector<double> &lb,std::vector<double> &ub, std::function<double(std::vector<double>)> func){

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
        //tri pour recuperer le meilleur individu
        trierPopulationEnFonctionDeFitness(fitn, population);
        std::vector<double> best_individu = population[0];

        double a = 2 - 2 * i / (this->epoch - 1);
        //pour chaque individu
        for (int j = 0; j < popSize; ++j) {
            //valeurs pour les calculs

            double r = (double)rand()/RAND_MAX;
            double A = 2 * a * r - a;
            double C = 2 * r;
            double l = -1.0 + static_cast<double>(rand()) / (RAND_MAX / 2.0);
            double p = (double)rand()/RAND_MAX;
            double b = 1;

            //tableau pour les nouvelles coordonnées
            std::vector<double> coord_new;
            //pour chaque coordonnée de l'individu
            for(int k = 0; k < population[j].size();k++){
                //on calcule une nouvelle coordonée
                if(p < 0.5){
                    if(abs(A)<1.0){
                        //calcul des nouvelles coordonnées
                        double D = abs(C*best_individu[k]-population[j][k]);
                        double pos_new = best_individu[k]-A*D;
                        pos_new = amendPosition(pos_new, lb[k], ub[k]);
                        coord_new.push_back(pos_new);
                    }
                    else{
                        //calcul des nouvelles coordonnées
                        int ind = rand() % (popSize);
                        double D = abs(C*population[ind][k]-population[j][k]);
                        double pos_new = population[ind][k]-A*D;
                        pos_new = amendPosition(pos_new, lb[k], ub[k]);
                        coord_new.push_back(pos_new);
                    }
                }
                else{
                    //calcul des nouvelles coordonnées
                    double D1 = abs(best_individu[k] - population[j][k]);
                    double pos_new = best_individu[k] + exp(b * l) *cos(2 * PI * l) * D1;
                    pos_new = amendPosition(pos_new, lb[k], ub[k]);
                    coord_new.push_back(pos_new);
                }
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
