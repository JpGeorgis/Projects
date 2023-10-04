#include <opencv2/core.hpp>
#include <opencv2/imgproc.hpp>
#include <opencv2/highgui.hpp>
#include <iostream>
#include <math.h>
using namespace cv;
using namespace std;

vector<Point> ajoutePoints(vector<Point>,int,int);
Point createPoint(int,int);
void colorPoints(Mat, vector<Point>);
void voronoi(Mat, vector<Point>, int, int);
int d(Point, Point);
void affichePoints(Mat, vector<Point>);

int main()
{
    srand(time(NULL));
    int n = 1000, m = 1000;
    Mat ma(n, m, CV_8UC3, Scalar(255, 255, 255));
    vector<Point> pixels=ajoutePoints(pixels,n,m);
    colorPoints(ma, pixels);
    voronoi(ma,pixels,n,m);
    affichePoints(ma, pixels);
    namedWindow("First OpenCV Application", WINDOW_AUTOSIZE);
    imshow("First OpenCV Application", ma);
    waitKey(0);
    return 0;
}

vector<Point> ajoutePoints(vector<Point> pixels, int n, int m) {
    for (int i = 0; i < 20; i++) {
        Point p = createPoint(n,m);
        pixels.push_back(p);
    }
    return pixels;
}

Point createPoint(int n, int m)
{
    int x = rand() % (n - 50) + 50;
    int y = rand() % (m - 50) + 50;
    return Point(x, y);
}

void colorPoints(Mat ma, vector<Point> pixels)
{
    for (int i = 0; i < pixels.size(); i++) {
        int b = rand() % 255, g = rand() % 255, r = rand() % 255;
        ma.at<Vec3b>(pixels[i])[0] = b;
        ma.at<Vec3b>(pixels[i])[1] = g;
        ma.at<Vec3b>(pixels[i])[2] = r;
        circle(ma,pixels[i],4, Scalar(b, g, r),-1);
    }
}

void voronoi(Mat ma, vector<Point> pixels, int N, int M)
{
    vector<vector<int>> I(N, vector<int>(M, 0));
    int inf;
    for (int x = 0; x < N; x++) {
        for (int y = 0; y < M; y++) {
            Point p(x, y);
            inf = d(p, pixels[0]);
            for (int m = 1; m < pixels.size(); m++) {
                if (d(p, pixels[m]) <= inf) {
                    inf = d(p, pixels[m]);
                    I[x][y] = m;
                }
            }
            ma.at<Vec3b>(x, y)[0] = ma.at<Vec3b>(pixels[I[x][y]])[0];
            ma.at<Vec3b>(x, y)[1] = ma.at<Vec3b>(pixels[I[x][y]])[1];
            ma.at<Vec3b>(x, y)[2] = ma.at<Vec3b>(pixels[I[x][y]])[2];
            
        }
    }

}

int d(Point P, Point p) {
    return sqrt(pow(P.x-p.x,2)+ pow(P.y - p.y, 2));
}

vector<int> InitI(vector<int> I, int n, int m)
{
    for (int i = 0; i < n*m; i++) {
        I.push_back(i);
    }
    return I;
}

void affichePoints(Mat ma, vector<Point> pixels) {
    for (int i = 0; i < pixels.size(); i++) {
        circle(ma, Point(pixels[i].y,pixels[i].x), 4, Scalar(0, 0, 0), -1);
    }
}