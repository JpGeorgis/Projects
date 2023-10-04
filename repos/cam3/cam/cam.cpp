#include <opencv2\opencv.hpp>
#include <opencv2/highgui.hpp>
#include <Windows.h>
#include <iostream>
#include <string> 
#include "images.h"

using namespace cv;
using namespace std;
Scalar blueLow = Scalar(94, 90, 2), blueHigh = Scalar(126, 255, 255);
Scalar red = Scalar(0, 0, 255);

Mat printImage(Mat, Mat, Point );
Mat printBig(Point, Rect, Mat, Images);

int main() {
		Mat image, imageHSV, maskB;
		Images im;
		namedWindow("Display window");
		VideoCapture cap(0);
		if (!cap.isOpened()) {

			cout << "cannot open camera";

		}
		Rect window = getWindowImageRect("Display window");
		cap >> image;
		
		int step=window.width/16;
		Point p(0, 0);

		for (;;) {
			
			cap >> image;
			cvtColor(image, imageHSV, COLOR_BGR2HSV);
			inRange(imageHSV, blueLow, blueHigh, maskB);
			Moments m = moments(maskB);
			p.x = m.m10 / m.m00;
			p.y=m.m01 / m.m00;
			int y = 0;			
			for (int i = 0; i < 16; i++) {
				Mat img = imread(im.s(i));
				image = printImage(image, img , Point(window.height - 100, y));
				y += step;
			}
			
			circle(image, p, 5, red, -1);
			if(p.x>=0&&p.y>=0)
				image = printBig(p, window, image, im);
			
			flip(image, image, 1);
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

Mat printImage(Mat video, Mat image, Point p) {
	
		int k = p.x, l = p.y;
		for (int i = 0; i < image.rows; i++) {
			for (int j = 0; j < image.cols; j++) {
				if (k < video.rows && l < video.cols) {
					video.at<Vec3b>(k, l)[0]= image.at<Vec3b>(i, j)[0];
					video.at<Vec3b>(k, l)[1] = image.at<Vec3b>(i, j)[1];
					video.at<Vec3b>(k, l)[2] = image.at<Vec3b>(i, j)[2];
				}
				l++;
			}
			k++;
			l = p.y;
		}
	return video;
}

Mat printBig(Point p,Rect w, Mat video, Images im) {
	int step = w.width / 16;
	Mat image,img;
	image = imread(im.s(p.x/step));
	resize(image,img,Size(),2,2);
	video = printImage(video, img, Point(100,220));
	return video;
}
