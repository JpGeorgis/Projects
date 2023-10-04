#include <opencv2/core.hpp>
#include <opencv2/imgproc.hpp>
#include <opencv2/highgui.hpp>
#include <iostream>
using namespace cv;
using namespace std;

int main()
{
    Mat image = imread("tigre.jpg", IMREAD_GRAYSCALE);
    if (image.empty())
    {
        cout << "Could not open or find the image!\n" << endl;
        return -1;
    }

    int cell_size = 16;
    int LBP_size = 8;
    int LBP_history_size = 16;

    Mat LBP_descriptors;
    LBP_descriptors.create((image.rows - cell_size + 1) / cell_size,
        (image.cols - cell_size + 1) / cell_size, CV_16UC1);

    for (int i = 0; i <= image.rows - cell_size; i += cell_size)
    {
        for (int j = 0; j <= image.cols - cell_size; j += cell_size)
        {
            Mat cell = image(Range(i, i + cell_size), Range(j, j + cell_size));

            Mat LBP_image;
            LBP_image.create(cell.rows - LBP_size + 1, cell.cols - LBP_size + 1, CV_16UC1);

            for (int x = 0; x <= cell.rows - LBP_size; x++)
            {
                for (int y = 0; y <= cell.cols - LBP_size; y++)
                {
                    Mat window = cell(Range(x, x + LBP_size), Range(y, y + LBP_size));

                    int sum = 0;
                    for (int m = 0; m < LBP_size; m++)
                    {
                        for (int n = 0; n < LBP_size; n++)
                        {
                            if (m != (LBP_size / 2) || n != (LBP_size / 2))
                            {
                                int p = window.at<uchar>(m, n);
                                int q = window.at<uchar>(LBP_size / 2, LBP_size / 2);
                                sum += (p >= q) << ((m * LBP_size + n) % LBP_history_size);
                            }
                        }
                    }
                    LBP_image.at<ushort>(x, y) = sum;
                }
            }

            Mat histogram;
            int histogram_size = 1 << LBP_history_size;
            float range[] = { 0, (float)histogram_size };
            const float* histogram_range = { range };
            calcHist(&LBP_image, 1, 0, Mat(), histogram, 1, &histogram_size, &histogram_range, true, false);
            normalize(histogram, histogram, 1.0, 0.0, NORM_L1);
            Mat histogram_descriptor = histogram.t();
            histogram_descriptor.copyTo(LBP_descriptors(Range((i / cell_size), (i / cell_size + 1)), Range((j / cell_size), (j / cell_size + 1))));
        }
    }
    cout << "LBP descriptors calculated successfully!" << endl;
    return 0;
}
