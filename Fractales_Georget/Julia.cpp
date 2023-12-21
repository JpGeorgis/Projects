#include <opencv2/opencv.hpp>

using namespace cv;

int main() {
    // D�finir les param�tres de l'ensemble de Julia
    const int width = 800;
    const int height = 600;
    const double minX = -2.0;
    const double maxX = 2.0;
    const double minY = -1.5;
    const double maxY = 1.5;
    const int maxIterations = 100;
    const double cr = 0, ci =-1;
    // Cr�er une image OpenCV
    Mat juliaSet(height, width, CV_8UC3);

    // Parcourir chaque pixel de l'image
    for (int y = 0; y < height; ++y) {
        for (int x = 0; x < width; ++x) {
            // Convertir les coordonn�es de l'�cran en coordonn�es du plan complexe
            double real = minX + (maxX - minX) * x / (width - 1);
            double imag = minY + (maxY - minY) * y / (height - 1);

            // Initialiser les variables pour l'algorithme de l'ensemble de Julia
            double zx = real;
            double zy = imag;
            int iteration = 0;

            // Appliquer l'algorithme de l'ensemble de Julia
            while (iteration < maxIterations) {
                double newZx = zx * zx - zy * zy - cr; // Param�tre sp�cifique de l'ensemble de Julia
                double newZy = 2.0 * zx * zy + ci;   // Param�tre sp�cifique de l'ensemble de Julia

                zx = newZx;
                zy = newZy;

                if (zx * zx + zy * zy > 4.0) {
                    break; // La s�quence diverge, sortir de la boucle
                }

                ++iteration;
            }

            // Colorer le pixel en fonction du nombre d'it�rations
            uchar color = static_cast<uchar>(iteration * 255 / maxIterations);
            juliaSet.at<Vec3b>(y, x) = Vec3b(color, color, color);
        }
    }

    // Afficher l'image r�sultante
    imshow("Julia Set", juliaSet);
    imwrite("Julia.png", juliaSet);
    waitKey(0);

    return 0;
}

