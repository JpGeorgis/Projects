#include <opencv2/opencv.hpp>
#include <iostream>

using namespace cv;
using namespace std;

// Fonction pour calculer le LBP
Mat LBP(Mat src) {
    Mat dst = Mat::zeros(src.rows - 2, src.cols - 2, CV_8UC1);
    for (int i = 1; i < src.rows - 1; i++) {
        for (int j = 1; j < src.cols - 1; j++) {
            uchar center = src.at<uchar>(i, j);
            uchar code = 0;
            code |= (src.at<uchar>(i - 1, j - 1) > center) << 7;
            code |= (src.at<uchar>(i - 1, j) > center) << 6;
            code |= (src.at<uchar>(i - 1, j + 1) > center) << 5;
            code |= (src.at<uchar>(i, j + 1) > center) << 4;
            code |= (src.at<uchar>(i + 1, j + 1) > center) << 3;
            code |= (src.at<uchar>(i + 1, j) > center) << 2;
            code |= (src.at<uchar>(i + 1, j - 1) > center) << 1;
            code |= (src.at<uchar>(i, j - 1) > center) << 0;
            dst.at<uchar>(i - 1, j - 1) = code;
        }
    }
    return dst;
}

int main(int argc, char** argv) {
    // Charger une image en niveaux de gris
    Mat src = imread("nissan.jpg", IMREAD_GRAYSCALE);
    if (!src.data) {
        cout << "Impossible de charger l'image !" << endl;
        return -1;
    }

    // Appliquer le LBP
    Mat dst = LBP(src);

    int histSize = 256;
    float range[] = { 0, 256 };
    const float* histRange = { range };
    bool uniform = true;
    bool accumulate = false;

    Mat hist;
    calcHist(&dst, 1, 0, Mat(), hist, 1, &histSize, &histRange, uniform, accumulate);

    int hist_w = 512;
    int hist_h = 400;
    int bin_w = cvRound((double)hist_w / histSize);

    Mat histImage(hist_h, hist_w, CV_8UC1, Scalar(0, 0, 0));

    normalize(hist, hist, 0, histImage.rows, NORM_MINMAX, -1, Mat());

    for (int i = 1; i < histSize; i++) {
        line(histImage, Point(bin_w * (i - 1), hist_h - cvRound(hist.at<float>(i - 1))),
            Point(bin_w * (i), hist_h - cvRound(hist.at<float>(i))), Scalar(255, 255, 255), 2, 8, 0);
    }

    imshow("Histogram", histImage);
    waitKey(0);

    // Afficher l'image originale et le résultat
    imshow("Image originale", src);
    imshow("LBP", dst);
    waitKey();
    return 0;
}
