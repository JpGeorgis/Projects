#include "kernel.h"

/* Objective function
0: Levy 3-dimensional
1: Shifted Rastigrin's Function
2: Shifted Rosenbrock's Function
3: Shifted Griewank's Function
4: Shifted Sphere's Function
*/
// parametre 1 individu avec ses positions
float host_fitness_function(float x[]) {
    float res = 0;
    float somme = 0;
    float produit = 0;

    switch (SELECTED_OBJ_FUNC)  {
        case 0: {
            float y1 = 1 + (x[0] - 1)/4;
            float yn = 1 + (x[NUM_OF_DIMENSIONS-1] - 1)/4;

            res += pow(sin(phi*y1), 2);

            for (int i = 0; i < NUM_OF_DIMENSIONS-1; i++) {
                float y = 1 + (x[i] - 1)/4;
                float yp = 1 + (x[i+1] - 1)/4;
                res += pow(y - 1, 2)*(1 + 10*pow(sin(phi*yp), 2)) + pow(yn - 1, 2);
            }
            break;
        }
        case 1: {
            for (int i = 0; i < NUM_OF_DIMENSIONS; i++) {
                float zi = x[i] - 0;
                res += pow(zi, 2) - 10*cos(2*phi*zi) + 10;
            }
            res -= 330;
            break;
        }
        case 2:
            for (int i = 0; i < NUM_OF_DIMENSIONS-1; i++) {
                float zi = x[i] - 0 + 1;
                float zip1 = x[i+1] - 0 + 1;
                res += 100 * ( pow(pow(zi, 2) - zip1, 2)) + pow(zi - 1, 2);
            }
            res += 390;
            break;
        case 3:
            for (int i = 0; i < NUM_OF_DIMENSIONS; i++) {
                float zi = x[i] - 0;
                somme += pow(zi, 2)/4000;
                produit *= cos(zi/pow(i+1, 0.5));
            }
            res = somme - produit + 1 - 180; 
            break;
        case 4:
            for(int i = 0; i < NUM_OF_DIMENSIONS; i++) {
                float zi = x[i] - 0;
                res += pow(zi, 2);
            }
            res -= 450;
            break;
    }

    return res;
}

//std::random_device dev;
//std::mt19937 rng(dev());

// Obtenir un random entre low et high
float getRandom(float low, float high) {
	//std::uniform_real_distribution<std::mt19937::result_type> rand(low, std::nextafter(high, DBL_MAX));
	//return rand(rng);
    return low + float(((high - low) + 1)*rand()/(RAND_MAX + 1.0));
}
// Obtenir un random entre 0.0f and 1.0f inclusif
float getRandomClamped() {
	//std::uniform_real_distribution<std::mt19937::result_type> rand(0.0f, std::nextafter(1.0f, DBL_MAX));
	//return rand(rng);
    return (float) rand()/(float) RAND_MAX;
}

float fitness_function(float x[]) {
	float res = 0;
	float somme = 0;
	float produit = 0;

	switch (SELECTED_OBJ_FUNC) {
	case 0:
	{
		float y1 = 1 + (x[0] - 1) / 4;
		float yn = 1 + (x[NUM_OF_DIMENSIONS - 1] - 1) / 4;

		res += pow(sin(phi*y1), 2);

		for (int i = 0; i < NUM_OF_DIMENSIONS - 1; i++) {
			float y = 1 + (x[i] - 1) / 4;
			float yp = 1 + (x[i + 1] - 1) / 4;
			res += pow(y - 1, 2)*(1 + 10 * pow(sin(phi*yp), 2)) + pow(yn - 1, 2);
		}
		break;
	}
	case 1:
		for (int i = 0; i < NUM_OF_DIMENSIONS; i++) {
			float zi = x[i] - 0;
			res += pow(zi, 2) - 10 * cos(2 * phi*zi) + 10;
		}
		res -= 330;
		break;

	case 2:
		for (int i = 0; i < NUM_OF_DIMENSIONS - 1; i++) {
			float zi = x[i] - 0 + 1;
			float zip1 = x[i + 1] - 0 + 1;
			res += 100 * (pow(pow(zi, 2) - zip1, 2)) + pow(zi - 1, 2);
		}
		res += 390;
		break;
	case 3:
		for (int i = 0; i < NUM_OF_DIMENSIONS; i++) {
			float zi = x[i] - 0;
			somme += pow(zi, 2) / 4000;
			produit *= cos(zi / pow(i + 1, 0.5));
		}
		res = somme - produit + 1 - 180;
		break;
	case 4:
		for (int i = 0; i < NUM_OF_DIMENSIONS; i++) {
			float zi = x[i] - 0;
			res += pow(zi, 2);
		}
		res -= 450;
		break;
	}

	return res;
}

void kernelUpdateParticle(float *positions, float *velocities,
	float *pBests, float *gBest, float r1,
	float r2)
{

	for (int i = 0; i < NUM_OF_PARTICLES*NUM_OF_DIMENSIONS; ++i)
	{
		//float rp = getRandomClamped();
		//float rg = getRandomClamped();

		float rp = r1; // random weight for personnal =>  computed from @getRandomClamped
		float rg = r2; // random weight for global =>  computed from @getRandomClamped


		// Mise à jour de velocities et positions
		velocities[i] = OMEGA * velocities[i] +
			c1 * rp * (pBests[i] - positions[i]) +
			c2 * rg * (gBest[i % NUM_OF_DIMENSIONS] - positions[i]);

		// Update posisi particle
		//Mise à jour de la position de la particule courante
		//incrémentant la position de la particule courante avec la vitesse de la particule courante
		positions[i] += velocities[i];
	}	
}


void kernelUpdatePBest(float *positions, float *pBests, float* gBest)
{
	float tempParticle1[NUM_OF_DIMENSIONS];
	float tempParticle2[NUM_OF_DIMENSIONS];

	for (int i = 0; i < NUM_OF_PARTICLES*NUM_OF_DIMENSIONS; i+= NUM_OF_DIMENSIONS)
	{
		for (int j = 0; j < NUM_OF_DIMENSIONS; j++)
		{
			tempParticle1[j] = positions[i + j];
			tempParticle2[j] = pBests[i + j];
		}

		if (fitness_function(tempParticle1) < fitness_function(tempParticle2))
		{
			for (int k = 0; k < NUM_OF_DIMENSIONS; k++)
				pBests[i + k] = positions[i + k];
		}
	}
}

void cuda_pso(float *positions, float *velocities, float *pBests, float *gBest)
{

	int size = NUM_OF_PARTICLES * NUM_OF_DIMENSIONS;

	// declare all the arrays on the device
	float *devPos;
	float *devVel;
	float *devPBest;
	float *devGBest;

	float temp[NUM_OF_DIMENSIONS];


	// Thread & Block number
	int threadsNum = 32;
	int blocksNum = ceil(size / threadsNum);

	// Copy particle datas from host to device
	/**
	 * Copy in GPU memory the data from the host
	 * */
	// PSO main function
	// MAX_ITER = 30000;

	for (int iter = 0; iter < MAX_ITER; iter++)
	{

		kernelUpdateParticle (positions, velocities,
			pBests, gBest,
			getRandomClamped(),
			getRandomClamped());

		kernelUpdatePBest (positions, pBests,
			gBest);


		for (int i = 0; i < size; i += NUM_OF_DIMENSIONS)
		{
			for (int k = 0; k < NUM_OF_DIMENSIONS; k++) //ssB1 
				temp[k] = pBests[i + k];

			if (host_fitness_function(temp) < host_fitness_function(gBest))
			{
				for (int k = 0; k < NUM_OF_DIMENSIONS; k++)
					gBest[k] = temp[k];
			}
		}

	}
}
