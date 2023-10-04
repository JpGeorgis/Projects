#include <opencv2\opencv.hpp>
#include <opencv2/highgui.hpp>
#include <Windows.h>
#include "balloon.h"
#include <iostream>
#include <string> 

using namespace cv;
using namespace std;
Scalar blueLow = Scalar(94, 90, 2);
Scalar blueHigh = Scalar(126, 255, 255);
Scalar red = Scalar(0, 0, 255);

bool D_eucl(Point, Balloon);
void getScreenSize(Rect w);
int x_screen;
int y_screen;

int main() {
		srand(time(NULL));
		Mat image, overlay,imageHSV,mask;
		double alpha = 0.7;
		int Score = 0;
		int flag = 0;
		namedWindow("Display window");
		
		VideoCapture cap(0);

		
		Rect window = getWindowImageRect("Display window");
		getScreenSize(window);
		

		Balloon b(window);
		

		for (;;) {
			
			cap >> image;
			cvtColor(image, imageHSV, COLOR_BGR2HSV);
			inRange(imageHSV, blueLow, blueHigh, mask);
			//tracking souris
			Moments m = moments(mask);
			Point p(m.m10 / m.m00, m.m01 / m.m00);
			circle(image, p, 5, red, -1);
			int size_x = x_screen - (x_screen / image.cols)*p.x;
			int size_y = (y_screen / image.rows)* p.y;
			SetCursorPos(size_x, size_y);
			image.copyTo(overlay);

			// Affichage Ballons
			circle(overlay, b.p(), b.r(), b.c(), -1);
			addWeighted(overlay, alpha, image, 1 - alpha, 0, image);
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
			waitKey(30);

			
			//imshow("Display mask", mask);
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
	if (sqrt(pow(xba,2)+pow(yba,2)) <= b.r())
	{
		return true;
	}
	return false;
}

void getScreenSize(Rect w) {
	x_screen = GetSystemMetrics(SM_CXSCREEN);
	y_screen = GetSystemMetrics(SM_CYSCREEN);
}