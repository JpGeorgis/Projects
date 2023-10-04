#ifndef IMAGES_H
#define IMAGES_H

#include <opencv2\opencv.hpp>
#include <opencv2/highgui.hpp>
#include <Windows.h>
#include <iostream>
using namespace cv;
using namespace std;

class Images
{
public:
	// constructeurs
	Images();
	// accesseurs
	string s(int i);

private:
	// coordonnées et rayon
	string d_s[16];
};

#endif
