#include <opencv2/opencv.hpp>
#include <iostream>

using namespace cv;
using namespace std;

Mat MeanLBP(Mat image) {
    Mat result = Mat::zeros(image.rows - 2, image.cols - 2, CV_8UC1);
    for (int i = 1; i < image.rows - 1; i++) {
        for (int j = 1; j < image.cols - 1; j++) {
            int lbp = 0;
            for (int k = -1; k <= 1; k++) {
                for (int l = -1; l <= 1; l++) {
                    if (k == 0 && l == 0) continue;
                    lbp += ((int)image.at<uchar>(i + k, j + l) >= (int)image.at<uchar>(i, j)) << (l + 1 + (k + 1) * 3);
                }
            }
            result.at<uchar>(i - 1, j - 1) = (uchar)lbp;
        }
    }
    return result;
}

int main() {
    Mat image = imread("nissan.jpg", IMREAD_GRAYSCALE);
    Mat result = MeanLBP(image);

    int histSize = 256;
    float range[] = { 0, 256 };
    const float* histRange = { range };
    bool uniform = true;
    bool accumulate = false;

    Mat hist;
    calcHist(&result, 1, 0, Mat(), hist, 1, &histSize, &histRange, uniform, accumulate);

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

    imwrite("output.jpg", result);
    return 0;
}