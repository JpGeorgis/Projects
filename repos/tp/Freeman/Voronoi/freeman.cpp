#include <opencv2/opencv.hpp>
#include <iostream>
#include <vector>

using namespace cv;
using namespace std;

// Définition des directions de la chaîne de Freeman
enum FreemanDirection {
    N = 0,
    NE = 1,
    E = 2,
    SE = 3,
    S = 4,
    SW = 5,
    W = 6,
    NW = 7
};

// Fonction pour calculer la chaîne de Freeman d'un contour
vector<FreemanDirection> getFreemanChain(const vector<Point>& contour) {
    // Initialisation des variables
    vector<FreemanDirection> chain;
    int prev_dx = 0, prev_dy = -1;

    // Itération sur chaque point du contour
    for (int i = 1; i < contour.size(); i++) {
        int dx = contour[i].x - contour[i - 1].x;
        int dy = contour[i].y - contour[i - 1].y;

        // Calcul de la direction à partir de la différence entre les coordonnées
        FreemanDirection direction = static_cast<FreemanDirection>((prev_dy + 1) * 3 + (prev_dx + 1) + (dy + 1));

        // Ajout de la direction à la chaîne
        chain.push_back(direction);

        // Mise à jour des variables pour la direction suivante
        prev_dx = dx;
        prev_dy = dy;
    }

    return chain;
}

int main() {
    // Lecture de l'image
    Mat image = imread("nissan.jpg", IMREAD_GRAYSCALE);
    if (image.empty()) {
        cout << "Impossible de lire l'image" << endl;
        return -1;
    }

    Mat drawing = Mat::zeros(image.size(), CV_8UC3);

    // Détection des contours
    Mat edges;
    Canny(image, edges, 100, 200);
    vector<vector<Point>> contours;
    findContours(edges, contours, RETR_EXTERNAL, CHAIN_APPROX_NONE);

    // Calcul de la chaîne de Freeman pour chaque contour
    for (int i = 0; i < contours.size(); i++) {
        vector<FreemanDirection> chain = getFreemanChain(contours[i]);

        // Affichage de la chaîne de Freeman
        cout << "Contour " << i + 1 << " : ";
        for (int j = 0; j < chain.size(); j++) {
            cout << chain[j] << " ";
        }
        cout << endl;

        // Dessin du contour et de la chaîne de Freeman sur l'image
        //Mat drawing = Mat::zeros(image.size(), CV_8UC3);
        drawContours(drawing, contours, i, Scalar(0, 255, 0), 2);
        Point pt = contours[i][0];
        for (int j = 0; j < chain.size(); j++) {
            int dx = 0, dy = 0;
            switch (chain[j]) {
            case N:
                dy = -1;
                break;
            case NE:
                dx = 1;
                dy = -1;
                break;
            case E:
                dx = 1;
                break;
            case SE:
                dx = 1;
                dy = 1;
                break;
            case S:
                dy = 1;
                break;
            case SW:
                dx = -1;
                dy = 1;
                break;
            case W:
                dx = -1;
                break;
            case NW:
                dx = -1;
                dy = -1;
                break;
            }
            Point pt2(pt.x + dx, pt.y + dy);
            //line(drawing, pt, pt2, Scalar(0, 0, 255), 2);
            pt = pt2;
        }
        //imshow("Contour " + to_string(i + 1), drawing);
    }
    imshow("Contour", drawing);

    waitKey();
    return 0;
}