#pragma once

#include <cfloat>
#include <random>
#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#include <math.h>
#include <iostream>
#include <string>


// Constantes
/* Objective function
0: Levy 3-dimensional
1: Shifted Rastigrin's Function
2: Shifted Rosenbrock's Function
3: Shifted Griewank's Function
4: Shifted Sphere's Function
*/
const int SELECTED_OBJ_FUNC = 2;
const int NUM_OF_PARTICLES = 50;
const int NUM_OF_DIMENSIONS = 10;
const int MAX_ITER = NUM_OF_DIMENSIONS * pow(10, 4);
const float START_RANGE_MIN = -100.0f;
const float START_RANGE_MAX = 100.0f;
const float OMEGA = 0.5;
const float c1 = 1.5;
const float c2 = 1.5;
const float phi = 3.1415;

// Les 3 fonctions très utiles
float getRandom(float low, float high);
float getRandomClamped();
float host_fitness_function(float x[]);

// Fonction externe qui va tourner sur le GPU
extern "C" void cuda_pso(float *positions, float *velocities, float *pBests, float *gBest);

