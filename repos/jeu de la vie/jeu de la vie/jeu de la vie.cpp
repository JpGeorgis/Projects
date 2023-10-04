#include <iostream>
using namespace std;
int** createGrid(int , int );
void initGrid(int**, int, int);
void afficheGrid(int**, int, int, int);
void nextGen(int**, int**, int, int);
int tailleVoisinage(int**, int, int, int, int, int);
int valeur(int**, int, int, int, int);

int main()
{   
    int** grid,**grid1;
    int x = 0, y = 0, tours = 0;
    cout << "Nombre de lignes ?" << endl;
    cin >> x;
    cout << "Nombre de colonnes ?" << endl;
    cin >> y;
    cout << "Nombre de tours ?" << endl;
    cin >> tours;
    grid = createGrid(x, y);
    grid1 = createGrid(x, y);
    initGrid(grid, x, y);
    afficheGrid(grid, x, y, 0);
    for(int tour=1;tour<=tours;tour++)
    {
           
        if (tour % 2 == 0) 
        {
            nextGen(grid1, grid, x, y);
            afficheGrid(grid, x, y, tour);
       }
           
       else
       {
           nextGen(grid, grid1, x, y);
           afficheGrid(grid1, x, y, tour);
       }
    }
    
    return 0;
}

int** createGrid(int a, int b) 
{
    int i;
    int** tab;
    tab = (int**)malloc(sizeof(int*) * a);
    for (i = 0; i < a; i++) {
        tab[i] = (int*)calloc(b, sizeof(int));
    }
    return tab;
}

void initGrid(int** tab, int a, int b) 
{
    for (int i = 0; i < a; i++)
    {
        for (int j = 0; j < b; j++)
        {
            tab[i][j] = rand() % 2;
        }
    }
    
}

void afficheGrid(int** tab, int a, int b, int t)
{
    cout << "affichage grille tour numero :" << t << "\n\n";
    for (int i = 0; i < a; i++)
    {
        for (int j = 0; j < b; j++)
        {
            if (tab[i][j] == 0)
                cout << "O ";
            else
                cout << "X ";

        }
        cout << "\n";
    }
}

void nextGen(int** tab, int** tab1, int a, int b)
{
    int tv;
    for (int i = 0; i < a; i++)
    {
        for (int j = 0; j < b; j++)
        {
            if (i!=0 && j!=0 && i!=a-1 && j!=b-1) {//mid cells
                tv = tailleVoisinage(tab, i, j, a, b, 0);
            }
            //border cells
            else if (i == 0 && j == 0) {//top left corner
                tv = tailleVoisinage(tab, i, j, a, b, 1);
            }
            else if (i == 0 && j == b - 1) {//top right corner
                tv = tailleVoisinage(tab, i, j, a, b, 2);
            }
            else if (i == a-1 && j == b - 1) {//bottom right corner
                tv = tailleVoisinage(tab, i, j, a, b, 3);
            }
            else if (i == a - 1 && j == 0) {//bottom left corner
                tv = tailleVoisinage(tab, i, j, a, b, 4);
            }
            else if (j == 0 && (i != 0 || i != a - 1)) {// left line 
                tv = tailleVoisinage(tab, i, j, a, b, 5);
            }
            else if (j == b - 1 && (i != 0 || i != a - 1)) {//right line
                tv = tailleVoisinage(tab, i, j, a, b, 6);
            }
            else if (i == 0 && (j != 0 || j != b - 1)) {//top line
                tv = tailleVoisinage(tab, i, j, a, b, 7);
            }
            else if (i == a - 1 && (j != 0 || j != b - 1)) {//bottom line
                tv = tailleVoisinage(tab, i, j, a, b, 8);
            }
            if ((tab[i][j] == 1) && (tv == 3 || tv == 2))
                tab1[i][j] = 1;
            else if ((tab[i][j] == 0) && (tv == 3))
                tab1[i][j] = 1;
            else if ((tab[i][j] == 1) && (tv < 2 || tv > 3))
                tab1[i][j] = 0;
            else
                tab1[i][j] = tab[i][j];


        }
    }
}

int tailleVoisinage(int** tab, int i, int j, int a, int b, int s)
{
    if (s == 0)
        return valeur(tab, i - 1, j - 1, a, b) + valeur(tab, i, j - 1, a, b) + valeur(tab, i + 1, j - 1, a, b) + valeur(tab, i - 1, j, a, b) + valeur(tab, i + 1, j, a, b) + valeur(tab, i - 1, j + 1, a, b) + valeur(tab, i, j + 1, a, b) + valeur(tab, i + 1, j + 1, a, b);
    else if (s == 1)
        return valeur(tab, i + 1, j, a, b) + valeur(tab, i, j + 1, a, b) + valeur(tab, i + 1, j + 1, a, b);
    else if (s == 4)
        return valeur(tab, i - 1, j, a, b) + valeur(tab, i, j + 1, a, b) + valeur(tab, i - 1, j + 1, a, b);
    else if (s == 3)
        return valeur(tab, i - 1, j, a, b) + valeur(tab, i, j - 1, a, b) + valeur(tab, i - 1, j - 1, a, b);
    else if (s == 2)
        return valeur(tab, i + 1, j, a, b) + valeur(tab, i, j - 1, a, b) + valeur(tab, i + 1, j - 1, a, b);
    else if (s == 7)
        return valeur(tab, i, j - 1, a, b) + valeur(tab, i + 1, j - 1, a, b) + valeur(tab, i + 1, j, a, b) + valeur(tab, i + 1, j + 1, a, b) + valeur(tab, i, j + 1, a, b);
    else if (s == 8)
        return valeur(tab, i, j - 1, a, b) + valeur(tab, i - 1, j - 1, a, b) + valeur(tab, i - 1, j, a, b) + valeur(tab, i - 1, j + 1, a, b) + valeur(tab, i, j + 1, a, b);
    else if (s == 5)
        return valeur(tab, i - 1, j, a, b) + valeur(tab, i - 1, j + 1, a, b) + valeur(tab, i, j + 1, a, b) + valeur(tab, i + 1, j + 1, a, b) + valeur(tab, i + 1, j, a, b);
    else if (s == 6)
        return valeur(tab, i - 1, j, a, b) + valeur(tab, i - 1, j - 1, a, b) + valeur(tab, i, j - 1, a, b) + valeur(tab, i + 1, j - 1, a, b) + valeur(tab, i + 1, j, a, b);
    else
        return -1;
}

int valeur(int** tab, int i, int j, int a, int b)
{
    if (tab[i][j] == 0)
        return 0;
    else
        return 1;
}