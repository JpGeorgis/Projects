#include <opencv2/opencv.hpp>
#include <iostream>
#include <vector>
#include <Windows.h>
#include <filesystem>
#include <chrono>
#include <thread>
using namespace cv;
using namespace std;

//fonction pour calculer l'histogramme d'une image
vector<double> HistoLBP(Mat src) {
	vector<double> histo(256);
	for (int i = 1; i < src.rows - 1; i++) {
		for (int j = 1; j < src.cols - 1; j++) {
			int r = 0;
			if (src.at<uchar>(i - 1, j - 1) > src.at<uchar>(i, j))
				r = r + 1;
			if (src.at<uchar>(i - 1, j) > src.at<uchar>(i, j))
				r = r + 2;
			if (src.at<uchar>(i - 1, j + 1) > src.at<uchar>(i, j))
				r = r + 4;
			if (src.at<uchar>(i, j + 1) > src.at<uchar>(i, j))
				r = r + 8;
			if (src.at<uchar>(i + 1, j + 1) > src.at<uchar>(i, j))
				r = r + 16;
			if (src.at<uchar>(i + 1, j) > src.at<uchar>(i, j))
				r = r + 32;
			if (src.at<uchar>(i + 1, j - 1) > src.at<uchar>(i, j))
				r = r + 64;
			if (src.at<uchar>(i, j - 1) > src.at<uchar>(i, j))
				r = r + 128;
			histo[r] += 1;
		}
	}
	return histo;
}

double calculDistance(vector<double> test, vector<double> Mod) {
	int d = 0;
	for (int i = 0; i < 256; i++) {
		d += abs(Mod[i] - test[i]);
	}
	return d;
}

double distanceEuclidienne(vector<double> a, vector<double> b) {
	double sommeCarresDifferences = 0.0;

	for (int i = 0; i < 256; i++) {
		int difference = a[i] - b[i];
		sommeCarresDifferences += difference * difference;
	}

	return sqrt(sommeCarresDifferences);
}

double distanceSAD(vector<double> a, vector<double> b) {
	int sad = 0;

	for (int i = 0; i < 256; i++) {
		sad += std::abs(a[i] - b[i]);
	}

	return sad;
}

double distanceSSD(vector<double> a, vector<double> b) {
	double sommeDesCarres = 0.0;

	for (size_t i = 0; i < 256; i++) {
		double difference = a[i] - b[i];
		sommeDesCarres += difference * difference;
	}

	return sommeDesCarres;
}

double distanceBhatta(vector<double> a, vector<double> b) {
	double sumA = 0;
	double sumB = 0;

	for (int i = 0; i < a.size(); i++) {
		sumA += a[i];
		sumB += b[i];
	}

	double result = 0.0;

	for (int i = 0; i < a.size(); i++) {
		double pa = a[i] / sumA;
		double pb = b[i] / sumB;
		result += sqrt(pa * pb);
	}

	result = -log(result);
	return result;
}

double calculateMean(vector<double>& vec) {
	double sum = 0.0;
	for (const double& value : vec) {
		sum += value;
	}
	return sum / vec.size();
}

// Fonction pour calculer la corrélation entre deux vecteurs
double distanceCorrelation(vector<double> vec1, vector<double> vec2) {

	int n = vec1.size();
	double mean1 = calculateMean(vec1);
	double mean2 = calculateMean(vec2);
	double numerator = 0.0;
	double denominator1 = 0.0;
	double denominator2 = 0.0;

	for (int i = 0; i < n; i++) {
		double diff1 = vec1[i] - mean1;
		double diff2 = vec2[i] - mean2;
		numerator += diff1 * diff2;
		denominator1 += diff1 * diff1;
		denominator2 += diff2 * diff2;
	}

	// Éviter une division par zéro
	if (denominator1 == 0.0 || denominator2 == 0.0) {
		std::cerr << "La variance est nulle dans l'un des vecteurs !" << std::endl;
		return -1.0; // Valeur d'erreur
	}

	return numerator / (sqrt(denominator1) * sqrt(denominator2));
}

double distanceIntersection(vector<double> a, vector<double> b) {
	double result = 0;
	for (int i = 0; i < 256; i++) {
		result += min(a[i], b[i]);
	}
	return result;
}

int main() {
	string path = "./Dataset20k";
	vector <Mat> train;
	vector<vector<double>> Model;

	int targetHeight = 40;
	int targetWidth = 50;

	//chargement des images pour entrainner
	for (const auto& entry : std::filesystem::directory_iterator(path + "/Train/Free")) {
		//train.push_back(imread(entry.path().u8string(), IMREAD_GRAYSCALE));
		Mat read = imread(entry.path().u8string(), IMREAD_GRAYSCALE);
		cv::resize(read, read, cv::Size(targetWidth, targetHeight));
		train.push_back(read);
	}

	for (const auto& entry : std::filesystem::directory_iterator(path + "/Train/Full")) {
		//train.push_back(imread(entry.path().u8string(), IMREAD_GRAYSCALE));
		Mat read = imread(entry.path().u8string(), IMREAD_GRAYSCALE);
		cv::resize(read, read, cv::Size(targetWidth, targetHeight));
		train.push_back(read);
	}

	//calculer histo des images de training
	for (int i = 0; i < train.size(); i++) {
		Model.push_back(HistoLBP(train[i]));
	}

	//chargement des images pour les tests
	vector<Mat> test;
	for (const auto& entry : std::filesystem::directory_iterator(path + "/Test/Free")) {
		//test.push_back(imread(entry.path().u8string(), IMREAD_GRAYSCALE));
		Mat read = imread(entry.path().u8string(), IMREAD_GRAYSCALE);
		cv::resize(read, read, cv::Size(targetWidth, targetHeight));
		test.push_back(read);
	}

	for (const auto& entry : std::filesystem::directory_iterator(path + "/Test/Full")) {
		//test.push_back(imread(entry.path().u8string(), IMREAD_GRAYSCALE));
		Mat read = imread(entry.path().u8string(), IMREAD_GRAYSCALE);
		cv::resize(read, read, cv::Size(targetWidth, targetHeight));
		test.push_back(read);
	}
	auto start = std::chrono::high_resolution_clock::now();
	//calculer histo des images test
	vector<vector<double>> HistoTest;
	for (int i = 0; i < train.size(); i++) {
		HistoTest.push_back(HistoLBP(test[i]));
	}

	vector <bool> freetest;
	vector <bool> fulltest;
	//compare les histos des test et des train
	for (int i = 0; i < train.size(); i++) {

		double dmin = INT_MAX;
		double r;
		int indice = 0;
		for (int j = 0; j < Model.size(); j++) {
			r = distanceBhatta(HistoTest[i], Model[j]);
			if (r < dmin) {
				dmin = r;
				indice = j;
			}
		}

		//retourne le résultat
		if (i < test.size() / 2) {
			if (indice < train.size() / 2)
				freetest.push_back(1);
			else
				freetest.push_back(0);
		}
		else {
			if (indice < train.size() / 2)
				fulltest.push_back(0);
			else
				fulltest.push_back(1);
		}
	}

	//calcul du pourcentage
	int p1 = 0, p2 = 0;
	for (int i = 0; i < freetest.size(); i++)
	{
		if (freetest[i])
			p1++;
	}
	for (int i = 0; i < fulltest.size(); i++)
	{
		if (fulltest[i])
			p2++;
	}
	cout << "Pourcentage de place occupees justes : " << p1 << endl << "Pourcentage de places vides justes : " << p2;
	auto end = std::chrono::high_resolution_clock::now();
	auto int_s = std::chrono::duration_cast<std::chrono::seconds>(end - start);

	cout << "Time : " << int_s.count() << " seconds " << endl;
}
