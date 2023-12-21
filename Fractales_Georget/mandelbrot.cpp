/*#include<opencv2/opencv.hpp>
#include <iostream>
#include <opencv2/imgproc/imgproc.hpp>
#include <Windows.h>

using namespace cv;
using namespace std;

int main() {

    double x1 = -2.1, x2 = 0.6;
    double y1 = -1.2, y2 = 1.2;
    int image_x = 270, image_y = 240;
    int iteration_max = 50;

    // Calcul des facteurs de zoom
    double zoom_x = image_x / (x2 - x1);
    double zoom_y = image_y / (y2 - y1);

    Mat mandelbrotImage(image_y, image_x, CV_8UC3, Scalar(0, 0, 0));

    for (int x = 0; x < image_x; ++x) {
        for (int y = 0; y < image_y; ++y) {
            double c_r = x / zoom_x + x1;
            double c_i = y / zoom_y + y1;

            double z_r = 0, z_i = 0;
            int j = 0;

            while (z_r * z_r + z_i * z_i < 4 && j < iteration_max) {
                double tmp = z_r;
                z_r = z_r * z_r - z_i * z_i + c_r;
                z_i = 2 * z_i * tmp + c_i;
                ++j;
            }

            if (j == iteration_max) {
                mandelbrotImage.at<Vec3b>(y, x) = Vec3b(0, 0, 0);
            }
            else {
                double ratio = (double)j / iteration_max;
                mandelbrotImage.at<Vec3b>(y, x) = Vec3b(255 * ratio, 255 * ratio, 255 * ratio);
            }
        }
    }

    imshow("Ensemble de Mandelbrot", mandelbrotImage);
    imwrite("mandelbrot.png", mandelbrotImage);
    waitKey(0);
    destroyAllWindows();

    return 0;

}*/