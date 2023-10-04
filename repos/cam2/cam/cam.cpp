#include <opencv2\opencv.hpp>
#include <opencv2/highgui.hpp>
#include <Windows.h>
#include "balloon.h"
#include <iostream>
#include <string> 

using namespace cv;
using namespace std;
Scalar blueLow = Scalar(94, 90, 2),blueHigh = Scalar(126, 255, 255);
Scalar redLow = Scalar(0, 70, 50),redHigh = Scalar(10, 255, 255);
Scalar red = Scalar(0, 0, 255),blue = Scalar(255, 0, 0),white = Scalar(200, 200, 200);

bool D_eucl(Point, Balloon);
int countPix(Mat);

int main() {
		srand(time(NULL));
		Mat image, overlay,imageHSV,maskB,maskR;
		double alpha1 = 0.4,alpha2 = 0.7;
		int Score = 0,flag = 0,r2x=0,r4x=0;
		int nRed, nBlue, xc,yc,ry2,ry1;
		
		namedWindow("Display window");
		
		VideoCapture cap(0);
		if (!cap.isOpened()) {

			cout << "cannot open camera";

		}
		Rect window = getWindowImageRect("Display window");
		
		cap >> image;

		int nPixels = image.total();

		Balloon b(window);
		
		xc = window.width / 2;
		yc = window.height / 4;
		ry1 = window.height - 55;
		ry2 = window.height - 30;
		Point p(0, 0),r1(50, ry1),r2(0, ry2),r3(window.width-50, ry1),r4(0, ry2);
		Point r5(50, ry1), r6(250, ry2),r7(window.width - 50, ry1), r8(window.width - 250, ry2);

		for (;;) {
			
			cap >> image;
			cvtColor(image, imageHSV, COLOR_BGR2HSV);
			inRange(imageHSV, blueLow, blueHigh, maskB);
			inRange(imageHSV, redLow, redHigh, maskR);
			nBlue =countPix(maskB);
			nRed =countPix(maskR);
			r2x = nBlue*250/nPixels;
			r4x = window.width - (nRed * 250 / nPixels);
			r2.x = r2x + 50;
			r4.x = r4x - 50;
			p.x = xc;
			p.y = yc;
			image.copyTo(overlay);
			circle(overlay, p, 30, red, -1);
			addWeighted(overlay, alpha1, image, 1 - alpha1, 0, image);
			//sous rectangle blanc --> bleu
			rectangle(overlay, r5, r6, white, -1);
			addWeighted(overlay, alpha1, image, 1 - alpha1, 0, image);
			//sous rectangle blanc --> rouge
			rectangle(overlay, r7, r8, white, -1);
			addWeighted(overlay, alpha1, image, 1 - alpha1, 0, image);
			//rect bleu
			rectangle(overlay, r1, r2, blue, -1);
			addWeighted(overlay, alpha1, image, 1 - alpha1, 0, image);
			//rect rouge 
			rectangle(overlay, r3, r4, red, -1);
			addWeighted(overlay, alpha1, image, 1 - alpha1, 0, image);
			
			if (nBlue > nRed) {
				xc-=8;
			}
			else if (nBlue < nRed) {
				xc+=8;
			}
			// Affichage Ballons
			circle(overlay, b.p(), b.r(), b.c(), -1);
			addWeighted(overlay, alpha1, image, 1 - alpha1, 0, image);
			b.Ym();
			// test de Collision
			if (D_eucl(p, b))
			{
				Balloon b1(window);
				b = b1;
				Score += 1;
			}
			// test de dépassement de fenêtre
			else if (b.p().y < b.r())
			{
				Balloon b1;
				flag = 1;
				b = b1;
				
			}
			// affichage image
			flip(image, image, 1);
			putText(image, "Score : " + to_string(Score), Point(10, 50), FONT_HERSHEY_DUPLEX, 1.0, CV_RGB(0, 0, 0), 5);
			putText(image, "Score : " + to_string(Score), Point(10, 50), FONT_HERSHEY_DUPLEX, 1.0, CV_RGB(118, 185, 0), 2);
			if (flag == 1)
			{

				putText(image, "Perdu !", Point(window.width / 2 - 100, window.height / 2), FONT_HERSHEY_DUPLEX, 2.0, CV_RGB(0, 0, 0), 10);
				putText(image, "Perdu !", Point(window.width / 2 - 100, window.height / 2), FONT_HERSHEY_DUPLEX, 2.0, CV_RGB(255, 0, 0), 5);
			}
			imshow("Display window", image);
			waitKey(20);
			char touche = (char)waitKey(30);
			if (touche == 'q')
			{
				break;
			}
		}
		return 0;
}
bool D_eucl(Point a, Balloon b) {
	int xba = b.p().x-a.x;
	int yba = b.p().y-a.y;
	if (sqrt(pow(xba,2)+pow(yba,2)) <= b.r()+25)
	{
		return true;
	}
	return false;
}

int countPix(Mat m) {
	return countNonZero(m);
}