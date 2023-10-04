#include <opencv2/core.hpp>
#include <opencv2/imgproc.hpp>
#include <opencv2/highgui.hpp>
#include <iostream>
using namespace cv;
using namespace std;

Mat agregQrCodes(Mat , Mat , Mat );
Mat insertQr(Mat, Mat);
Mat invertQr(Mat);
Mat getQr(Mat);
Mat getQrFromP(Mat,int);
int main()
{
    Mat qr1 = imread("netflix.png", IMREAD_GRAYSCALE);
    Mat qr2 = imread("disney.png", IMREAD_GRAYSCALE);
    Mat qr3 = imread("twitter.png", IMREAD_GRAYSCALE);
    Mat qr4 = imread("uha.png", IMREAD_GRAYSCALE);
    Mat qrc,qrp, qr1p, qr2p, qr3p;
    imshow("qr1 netflix", qr1);
    imshow("qr2 disney", qr2);
    imshow("qr3 twitter", qr3);
    imshow("qr4 uha", qr4);

    qrc = agregQrCodes(qr1,qr2,qr3);
    qrp = insertQr(qrc, qr4);

    imshow("qrp", qrp);
    qrc = invertQr(qrp);
    for (int i = 0; i < 3; i++) {
        if (i == 0) {
            qr1p = getQrFromP(qrc,i);
        }
        else if (i==1) {
            qr2p = getQrFromP(qrc,i);
        }
        else {
            qr3p = getQrFromP(qrc,i);
        }
    }
    imshow("qr1p netflix", qr1p);
    imshow("qr2p disney", qr2p);
    imshow("qr3p twitter", qr3p);
    waitKey(0);
                       
    destroyAllWindows();
    return 0;
}

Mat agregQrCodes(Mat a, Mat b, Mat c) {
    Mat d(231,231, CV_8U);
    for (int i = 0; i < d.rows; i++) {
        for (int j = 0; j < d.cols; j++) {
            int aa= a.at<uchar>(i, j),bb= b.at<uchar>(i, j),cc= c.at<uchar>(i, j);
            if (aa == 255)
                aa = 1;
            else
                aa = 0;
            if (bb == 255)
                bb = 1;
            else
                bb = 0;
            if (cc == 255)
                cc = 1;
            else
                cc = 0;
            d.at<uchar>(i,j) = aa + 2 * bb + 4 * cc;
            //cout <<(int) d.at<uchar>(i, j) << endl;
        }
    }
    return d;
}

Mat insertQr(Mat a, Mat b) {
    Mat c(231, 231, CV_8U);
    for (int i = 0; i < c.rows; i++) {
        for (int j = 0; j < c.cols; j++) {
            int aa = a.at<uchar>(i, j), bb = b.at<uchar>(i, j);
            if (bb==0 && aa == 7) {
                c.at<uchar>(i, j) = 60;
            }
            else if (bb == 255 && aa == 7) {
                c.at<uchar>(i, j) = 196;
            }
            else if (bb == 0 && aa == 6) {
                c.at<uchar>(i, j) = 52;
            }
            else if (bb == 255 && aa == 6) {
                c.at<uchar>(i, j) = 204;
            }
            else if (bb == 0 && aa == 5) {
                c.at<uchar>(i, j) = 44;
            }
            else if (bb == 255 && aa == 5) {
                c.at<uchar>(i, j) = 212;
            }
            else if (bb == 0 && aa == 4) {
                c.at<uchar>(i, j) = 36;
            }
            else if (bb == 255 && aa == 4) {
                c.at<uchar>(i, j) = 220;
            }
            else if (bb == 0 && aa == 3) {
                c.at<uchar>(i, j) = 28;
            }
            else if (bb == 255 && aa == 3) {
                c.at<uchar>(i, j) = 228;
            }
            else if (bb == 0 && aa == 2) {
                c.at<uchar>(i, j) = 20;
            }
            else if (bb == 255 && aa == 2) {
                c.at<uchar>(i, j) = 236;
            }
            else if (bb == 0 && aa == 1) {
                c.at<uchar>(i, j) = 12;
            }
            else if (bb == 255 && aa == 1) {
                c.at<uchar>(i, j) = 244;
            }
            else if (bb == 0 && aa == 0) {
                c.at<uchar>(i, j) = 4;
            }
            else if (bb == 255 && aa == 0) {
                c.at<uchar>(i, j) = 252;
            }
        }
    }
    return c;
}

Mat invertQr(Mat a) {
    Mat b(231, 231, CV_8U);
    for (int i = 0; i < b.rows; i++) {
        for (int j = 0; j < b.cols; j++) {
            int aa = a.at<uchar>(i, j);
            if ((aa >= 0 && aa <= 8) || (aa >= 249 && aa <= 255)) {
                b.at<uchar>(i, j) = 0;
            }
            if ((aa >= 9 && aa <= 16) || (aa >= 241 && aa <= 248)) {
                b.at<uchar>(i, j) = 1;
            }
            if ((aa >= 17 && aa <= 24) || (aa >= 233 && aa <= 240)) {
                b.at<uchar>(i, j) = 2;
            }
            if ((aa >= 25 && aa <= 32) || (aa >= 225 && aa <= 232)) {
                b.at<uchar>(i, j) = 3;
            }
            if ((aa >= 33 && aa <= 40) || (aa >= 217 && aa <= 224)) {
                b.at<uchar>(i, j) = 4;
            }
            if ((aa >= 41 && aa <= 48) || (aa >= 209 && aa <= 216)) {
                b.at<uchar>(i, j) = 5;
            }
            if ((aa >= 49 && aa <= 56) || (aa >= 201 && aa <= 208)) {
                b.at<uchar>(i, j) = 6;
            }
            if ((aa >= 57 && aa <= 64) || (aa >= 192 && aa <= 200)) {
                b.at<uchar>(i, j) = 7;
            }
        }
    }
    return b;
}

Mat getQr(Mat a) {
    Mat b(231,231, CV_8UC3);
    for (int i = 0; i < b.rows; i++) {
        for (int j = 0; j < b.cols; j++) {
            int aa = a.at<uchar>(i, j);
            if (aa == 0) {
                b.at<Vec3b>(i, j)[0]= 0;
                b.at<Vec3b>(i, j)[1] = 0;
                b.at<Vec3b>(i, j)[2] = 0;
            }
            else if (aa==1) {
                b.at<Vec3b>(i, j)[0] = 255;
                b.at<Vec3b>(i, j)[1] = 0;
                b.at<Vec3b>(i, j)[2] = 0;
            }
            else if (aa == 2) {
                b.at<Vec3b>(i, j)[0] = 0;
                b.at<Vec3b>(i, j)[1] = 255;
                b.at<Vec3b>(i, j)[2] = 0;
            }
            else if (aa == 3) {
                b.at<Vec3b>(i, j)[0] = 255;
                b.at<Vec3b>(i, j)[1] = 255;
                b.at<Vec3b>(i, j)[2] = 0;
            }
            else if (aa == 4) {
                b.at<Vec3b>(i, j)[0] = 0;
                b.at<Vec3b>(i, j)[1] = 0;
                b.at<Vec3b>(i, j)[2] = 255;
            }
            else if (aa == 5) {
                b.at<Vec3b>(i, j)[0] = 255;
                b.at<Vec3b>(i, j)[1] = 0;
                b.at<Vec3b>(i, j)[2] = 255;
            }
            else if (aa == 6) {
                b.at<Vec3b>(i, j)[0] = 0;
                b.at<Vec3b>(i, j)[1] = 255;
                b.at<Vec3b>(i, j)[2] = 255;
            }
            else if (aa == 7) {
                b.at<Vec3b>(i, j)[0] = 255;
                b.at<Vec3b>(i, j)[1] = 255;
                b.at<Vec3b>(i, j)[2] = 255;
            }
        }
    }

    return b;
}

Mat getQrFromP(Mat a,int ind) {
    Mat b(231, 231, CV_8U);
    Mat c= getQr(a);
        for (int i = 0; i < b.rows; i++) {
            for (int j = 0; j < b.cols; j++) {
                b.at<uchar>(i, j) = c.at<Vec3b>(i, j)[ind];
            }
        }
    return b;
}