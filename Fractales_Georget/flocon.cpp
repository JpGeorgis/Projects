/*#include<opencv2/opencv.hpp>
#include <iostream>
#include <opencv2/imgproc/imgproc.hpp>
#include <Windows.h>

using namespace cv;
using namespace std;

Mat image;

void dessiner(Point a, Point b, int n) {
    if (n == 0) {
        line(image, a, b, Scalar(255, 255, 255), 2, LINE_AA);
        return;
    }

    Point c(a.x + (b.x - a.x) / 3, a.y + (b.y - a.y) / 3);
    Point d(a.x + 2 * (b.x - a.x) / 3, a.y + 2 * (b.y - a.y) / 3);
    Point e((c.x + d.x) * cos(60 * CV_PI / 180) - (d.y - c.y) * sin(60 * CV_PI / 180),
        (c.y + d.y) * cos(60 * CV_PI / 180) + (d.x - c.x) * sin(60 * CV_PI / 180));



    dessiner(a, c, n - 1);
    dessiner(c, e, n - 1);
    dessiner(e, d, n - 1);
    dessiner(d, b, n - 1);

}




int main()
{

    image = Mat::zeros(500, 500, CV_8UC3);

    Point a(250, 50);
    Point b(100, 350);
    Point c(400, 350);
    int niveau = 4;
    dessiner(a, b, niveau);
    dessiner(c, a, niveau);
    dessiner(b, c, niveau);

    imshow("Flocon", image);
    imwrite("Flocon.png", image);
    waitKey(0);
    destroyAllWindows();

    return 0;

}*/