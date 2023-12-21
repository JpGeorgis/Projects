#include <opencv2/opencv.hpp>

cv::Mat img;

std::vector<std::vector<double>> field;
int rez = 20;
int cols, rows;

void drawLine(cv::Point v1, cv::Point v2, cv::Mat& image) {
    cv::line(image, v1, v2, cv::Scalar(0, 0, 255), 4);
}

void setup() {
    img = cv::imread("k.jpg");

    cols = img.cols / rez;
    rows = img.rows / rez;

    for (int i = 0; i < cols; i++) {
        std::vector<double> k;
        for (int j = 0; j < rows; j++) {
            k.push_back(0);
        }
        field.push_back(k);
    }
}

void drawPixels(cv::Mat& image) {
    for (int i = 0; i < cols; i++) {
        for (int j = 0; j < rows; j++) {
            int x = i * rez;
            int y = j * rez;
            cv::Vec3b c = img.at<cv::Vec3b>(y, x);
            double b = (c[0] + c[1] + c[2]) / 3.0;
            field[i][j] = b;
            cv::Scalar color = cv::Scalar(255 - b, 255 - b, 255 - b);
            cv::rectangle(image, cv::Rect(x, y, rez, rez), color);
        }
    }
}

void drawMarchingSquares(cv::Mat& image) {
    for (int i = 0; i < cols - 1; i++) {
        for (int j = 0; j < rows - 1; j++) {
            int x = i * rez;
            int y = j * rez;

            cv::Point a(x + rez * 0.5, y);
            cv::Point b(x + rez, y + rez * 0.5);
            cv::Point c(x + rez * 0.5, y + rez);
            cv::Point d(x, y + rez * 0.5);

            int threshold = 90;
            int c1 = field[i][j] < threshold ? 0 : 1;
            int c2 = field[i + 1][j] < threshold ? 0 : 1;
            int c3 = field[i + 1][j + 1] < threshold ? 0 : 1;
            int c4 = field[i][j + 1] < threshold ? 0 : 1;

            int state = c1 * 8 + c2 * 4 + c3 * 2 + c4 * 1;

            switch (state) {
            case 1:
                drawLine(c, d, image);
                break;
            case 2:
                drawLine(b, c, image);
                break;
            case 3:
                drawLine(b, d, image);
                break;
            case 4:
                drawLine(a, b, image);
                break;
            case 5:
                drawLine(a, d, image);
                drawLine(b, c, image);
                break;
            case 6:
                drawLine(a, c, image);
                break;
            case 7:
                drawLine(a, d, image);
                break;
            case 8:
                drawLine(a, d, image);
                break;
            case 9:
                drawLine(a, c, image);
                break;
            case 10:
                drawLine(a, b, image);
                drawLine(c, d, image);
                break;
            case 11:
                drawLine(a, b, image);
                break;
            case 12:
                drawLine(b, d, image);
                break;
            case 13:
                drawLine(b, c, image);
                break;
            case 14:
                drawLine(c, d, image);
                break;
            }
        }
    }
}

int main() {
    setup();

    cv::Mat canvas = img.clone();
    drawPixels(canvas);
    drawMarchingSquares(canvas);

    cv::imshow("Marching Squares", canvas);
    cv::imwrite("MarchingSquares.png", canvas);
    cv::waitKey(0);

    return 0;
}
