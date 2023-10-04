#ifndef BALLOON_H
#define BALLOON_H

#include <opencv2\opencv.hpp>
#include <opencv2/highgui.hpp>
#include <Windows.h>
#include <iostream>
using namespace cv;
using namespace std;

class Balloon
{
public:
	// constructeurs
	Balloon();
	Balloon(Rect w);
	// accesseurs
	int r() const { return d_r; }
	int v() const { return d_v; }
	Point p() const { return d_p; }
	Scalar c() const { return d_c; }
	void Ym();

private:
	// coordonnées et rayon
	int d_r;
	int d_v;
	Point d_p;
	Scalar d_c;
};

#endif