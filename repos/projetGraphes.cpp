#include <cstdlib>
#include <iostream>
#include <vector>
#include <fstream>
#include <string>
#include <cmath>
#include <time.h>
#include<sstream>


using namespace std;
using std::stringstream;


int ordreGrapheMtx=0; //ordre du graphe recup dans un fichier MTX
int aretesGrapheMtx=0; //taille du graphe recup dans un fichier MTX
int **grapheMtx; //représenation du graphe MTX avec ses aretes (comme dans fichier MTX)
int **adjMtx;//matrice d'adjacence graphe MTX
int *centreMtx; //tableau des centres graphe MTX
int n=0; //nombre de sommets voulus pour petit graphe que l'on demandera à l'utilisateur
int **adj;  // matrice d'adjacence petit graphe
int *centre; //tableau des centres petit graphe
int *couleurExact;//couleur algo colorExact
int *couleurDsatur;//couleur DSATUR petit graphe
int *couleurDsaturMTX;//couleur DSATUR graphe MTX
int *DSAT; //degre de saturation petit graphe
int *Degre; //degre des sommets petit graphe
int *DSATMTX; //degre de saturation graphe MTX
int *DegreMTX; //degre des sommets graphe MTX
int *sommetsGlouton; //tableau sommets ordre decroissant des centres pour glouton petit graphe
int *couleurGlouton; //couleur glouton petit graphe
int *sommetsGloutonMtx; //tableau sommets ordre decroissant des centres pour glouton graphe MTX
int *couleurGloutonMtx; //couleur glouton graphe MTX
int deg=0; //contiendra la degenerescence petit graphe
int degMtx=0; //contiendra degenerescence graphe MTX
bool trouve=false; //permet d'arreter colorExact quand une couleur est trouvee petit graphe

//genere le graphe et remplit la matrice d'adjacence
void generegraphe(int n)
{
  int c=0;
  for(int i=0;i<=n-1;i++){
    for(int j=0;j<=n-1;j++){
        c=rand()%2;
        if(i==j)
          adj[i][j]=0;
        else{
          adj[i][j]=c;
          adj[j][i]=c;
        }
      }
    }
}

//calcul de la degenerescence d'un petit graphe
int degenerescencePetitGraphe(int t){
  int verif=0;
  int comp=0;
  int nbvoisins=0;
  int k=1;
  int sommets=t;
  int **tab;
  tab=new int*[t];
  for (int i = 0; i < t; i++)
     tab[i] = new int[t];
  for(int i=0;i<t;i++){
    for(int j=0;j<t;j++){
      tab[i][j]=adj[i][j];
    }
  }
  while(sommets>0){
    while(verif==0){
    for(int i=0;i<=t-1;i++){
      if(tab[i][0]!=-2){
        for(int j=0;j<=t-1;j++){
          if(tab[i][j]==1){
            nbvoisins++;
           }
        }
        if(nbvoisins<=k){
          sommets--;
          centre[i]=k;
          for(int l=0;l<=t-1;l++){
            tab[i][l]=-2;
            if(tab[l][i]!=-2)
              tab[l][i]=-1;
          }
        }
        nbvoisins=0;
      }
    }
    for(int i=0;i<=t-1;i++){
      if(tab[i][0]!=-2){
        for(int j=0;j<=t-1;j++){
          if(tab[i][j]==1){
            nbvoisins++;
           }
        }
      }
      if(nbvoisins>k){
        comp++;
      }
      nbvoisins=0;
    }
    if(comp==sommets){
      verif=1;
    }
    comp=0;
  }
    verif=0;
    k++;
}
delete[] tab;
deg=k-1;
return deg;
}

//calcul la degenerescence graphe MTX
int degenerescenceMtx(int t){
  int verif=0;
  int comp=0;
  int nbvoisins=0;
  int k=1;
  int sommets=t;
  int **tab;
  tab=new int*[t];
  for (int i = 0; i < t; i++)
     tab[i] = new int[t];
  for(int i=0;i<t;i++){
    for(int j=0;j<t;j++){
      tab[i][j]=adjMtx[i][j];
    }
  }
  while(sommets>0){
    while(verif==0){
    for(int i=0;i<=t-1;i++){
      if(tab[i][0]!=-2){
        for(int j=0;j<=t-1;j++){
          if(tab[i][j]==1){
            nbvoisins++;
           }
        }
        if(nbvoisins<=k){
          sommets--;
          centreMtx[i]=k;
          for(int l=0;l<=t-1;l++){
            tab[i][l]=-2;
            if(tab[l][i]!=-2)
              tab[l][i]=-1;
          }
        }
        nbvoisins=0;
      }
    }
    for(int i=0;i<=t-1;i++){
      if(tab[i][0]!=-2){
        for(int j=0;j<=t-1;j++){
          if(tab[i][j]==1){
            nbvoisins++;
           }
        }
      }
      if(nbvoisins>k){
        comp++;
      }
      nbvoisins=0;
    }
    if(comp==sommets){
      verif=1;
    }
    comp=0;
  }
    verif=0;
    k++;
}
delete[] tab;
degMtx=k-1;
return degMtx;
}

// teste si la couleur c peut être donnee au sommet x
bool convient(int x, int c)
{
     for(int i=0;i<x;i++)
      if(adj[x][i] && (couleurExact[i]==c)) return false;
     return true;
}

// fonction recursive pour tester toutes les couleurs possible pour le sommet x
void colorRR(int x, int k)
{
     if(x==n)
     { //cout << "Coloration en " << k << " couleurs trouvée" << endl;
      // for(int i=0;i<n;i++) cout << "couleur de " << i << " : " << couleurExact[i] << endl;
       trouve=true;
     }
     else
     for(int c=1;c<=k;c++)
      if(convient(x,c))
	{couleurExact[x]=c;
         colorRR(x+1,k);
	 if(trouve) return;}
}

// teste si le graphe possede une coloration en k couleurs en essayant toutes les combinaisons
void colorexact(int k)
{
    for(int i=0;i<n;i++)
     couleurExact[i]=0;
     colorRR(0,k);
}

// calcule le nombre chromatique en testant à partir de d couleurs et diminuant k tant que c'est possible
int nbChromatique(int d)
{
  int k=d+1;
  do {
      k--;
      trouve=false;
      colorexact(k);
     }
  while(trouve);
  return k+1;
}

// teste si la couleur c peut être donnee au sommet x (DSATUR)
bool convientDSAT(int x, int c)
{
     for(int i=0;i<n;i++)
      if(adj[x][i] && (couleurDsatur[i]==c)) return false;
     return true;
}

int dsatMax()
{
  int maxDeg=-1,maxDSAT=-1,smax=0;
  for(int i=0;i<n;i++)
  if(couleurDsatur[i]==0 && (DSAT[i]>maxDSAT || (DSAT[i]==maxDSAT && Degre[i]>maxDeg)))
   { maxDSAT=DSAT[i]; maxDeg=Degre[i]; smax=i;}
  return smax;
}

//coloration dsatur petit graphe
int DSATUR()
{
  int nb=0,c,x,cmax=0;
  for(int i=0;i<n;i++)
  {
    couleurDsatur[i]=0; DSAT[i]=0; Degre[i]=0;
    for(int j=0;j<n;j++)
     if(adj[i][j]) Degre[i]++;
    DSAT[i]=Degre[i];
  }

  while(nb<n)
  {
    c=1;
    x=dsatMax();
    while(!convientDSAT(x,c)) c++;
    for(int j=0; j<n;j++)
     {
       if(adj[x][j] && convientDSAT(j,c)) DSAT[j]++;
     }
    couleurDsatur[x]=c;
    if(cmax<c) cmax=c;
    nb++;
  }

  return cmax;
}

// teste si la couleur c peut être donnee au sommet x graphe MTX(DSATUR)
bool convientDSATMTX(int x, int c)
{
     for(int i=0;i<ordreGrapheMtx;i++)
      if(adjMtx[x][i] && (couleurDsaturMTX[i]==c)) return false;
     return true;
}

int dsatMaxMTX()
{
  int maxDeg=-1,maxDSAT=-1,smax=0;
  for(int i=0;i<ordreGrapheMtx;i++)
  if(couleurDsaturMTX[i]==0 && (DSATMTX[i]>maxDSAT || (DSATMTX[i]==maxDSAT && DegreMTX[i]>maxDeg)))
   { maxDSAT=DSATMTX[i]; maxDeg=DegreMTX[i]; smax=i;}
  return smax;
}

//dsatur gros graphe Mtx
int DSATURMTX()
{
  int nb=0,c,x,cmax=0;
  for(int i=0;i<ordreGrapheMtx;i++)
  {
    couleurDsaturMTX[i]=0; DSATMTX[i]=0; DegreMTX[i]=0;
    for(int j=0;j<ordreGrapheMtx;j++)
     if(adjMtx[i][j]) DegreMTX[i]++;
    DSATMTX[i]=DegreMTX[i];
  }

  while(nb<ordreGrapheMtx)
  {
    c=1;
    x=dsatMaxMTX();
    while(!convientDSATMTX(x,c)) c++;
    for(int j=0; j<ordreGrapheMtx;j++)
     {
       if(adjMtx[x][j] && convientDSATMTX(j,c)) DSATMTX[j]++;
     }
    couleurDsaturMTX[x]=c;
    if(cmax<c) cmax=c;
    nb++;
  }

  return cmax;
}

//met les sommets dans l'ordre decroissant des centres pour glouton petit graphe
void ordonneTabGlouton(){
  int tab[n];
  int sup=n;
  int c=deg;
  int x=0;
  for(int i=0;i<n;i++){
    tab[i]=centre[i];
  }
  while(sup>0){
    for(int i=0;i<n;i++){
     if(tab[i]==c){
       sommetsGlouton[x]=i;
       x++;
       tab[i]=-1;
       sup--;
      }
    }
    c--;
  }
}

//coloration glouton petit graphe
int Glouton(){
  int col=1;
  int tot=0;
  int colVoisins[n];
  int x=0;
  int l=0;
  int v=0;
  bool b=false;
  for(int i=0;i<n;i++){
    couleurGlouton[i]=-1;
  }
  couleurGlouton[sommetsGlouton[0]]=1;
  for(int i=1;i<n;i++){
    for(int j=0;j<n;j++){
      if(adj[sommetsGlouton[i]][j]==1 && couleurGlouton[j]!=-1){
        colVoisins[x]=couleurGlouton[j];
        x++;
      }
    }
     while(l<x){
       while(v<x && b==false){
        if(colVoisins[v]==col){
          col++;
          b=true;
        }
        v++;
       }
      b=false;
      v=0;
      l++;
      }
    l=0;
    couleurGlouton[sommetsGlouton[i]]=col;
    x=0;
    col=1;
  }
  tot=couleurGlouton[0];
  for(int i=1;i<n;i++){
    if(couleurGlouton[i]>tot)
      tot=couleurGlouton[i];
  }

return tot;
}

//grapheMtx
//met les sommets dans l'ordre decroissant des centres pour glouton
void ordonneTabGloutonMTX(){
  int *tab=new int[ordreGrapheMtx];
  int sup=ordreGrapheMtx;
  int c=degMtx;
  int x=0;
  for(int i=0;i<ordreGrapheMtx;i++){
    tab[i]=centreMtx[i];
  }
  while(sup>0){
    for(int i=0;i<ordreGrapheMtx;i++){
     if(tab[i]==c){
       sommetsGloutonMtx[x]=i;
       x++;
       tab[i]=-1;
       sup--;
      }
    }
    c--;
  }
  delete[] tab;
}

//coloration glouton grapheMtx
int GloutonMtx(){
  int col=1;
  int tot=0;
  int *colVoisins=new int[ordreGrapheMtx];
  int x=0;
  int l=0;
  int v=0;
  bool b=false;
  for(int i=0;i<ordreGrapheMtx;i++){
    couleurGloutonMtx[i]=-1;
  }
  couleurGloutonMtx[sommetsGloutonMtx[0]]=1;
  for(int i=1;i<ordreGrapheMtx;i++){
    for(int j=0;j<ordreGrapheMtx;j++){
      if(adjMtx[sommetsGloutonMtx[i]][j]==1 && couleurGloutonMtx[j]!=-1){
        colVoisins[x]=couleurGloutonMtx[j];
        x++;
      }
    }
     while(l<x){
       while(v<x && b==false){
        if(colVoisins[v]==col){
          col++;
          b=true;
        }
        v++;
       }
      b=false;
      v=0;
      l++;
      }
    l=0;
    couleurGloutonMtx[sommetsGloutonMtx[i]]=col;
    x=0;
    col=1;
  }
  tot=couleurGloutonMtx[0];
  for(int i=1;i<ordreGrapheMtx;i++){
    if(couleurGloutonMtx[i]>tot)
      tot=couleurGloutonMtx[i];
  }
delete[] colVoisins;
return tot;
}

//recupere la taille et ordre du graphe dans fichier MTX
void recupTailleEtOrdreMtx(string chemin){
  ifstream fichier(chemin, ios::in);  // on ouvre le fichier en lecture
    if(fichier)  // si l'ouverture a réussi
    {
      string ligne;
      int cptLigne=0;
      int cptCarac=0;
      while(getline(fichier, ligne)){
        stringstream ss(ligne);
        string souschaine;
        if(cptLigne==2){
         while(getline(ss,souschaine,' ')){
          if(cptCarac==0)
            ordreGrapheMtx=stoi(souschaine);
          else if(cptCarac==2)
            aretesGrapheMtx=stoi(souschaine);
          cptCarac++;
          }
        }
        cptLigne++;
      }
      fichier.close();  // on ferme le fichier
    }
    else  // sinon
      cerr << "Impossible d'ouvrir le fichier !" << endl;
}

//rempli le tableau d'aretes représentant le graphe MTX
void rempliTabGrapheMtx(string cheminMtx){
  ifstream fichier(cheminMtx, ios::in);  // on ouvre le fichier en lecture
    if(fichier)  // si l'ouverture a réussi
    {
      string ligne;
      int cptLigne=0;
      int cptCarac=0;
      int i=0;
      while(getline(fichier, ligne)){
        stringstream ss(ligne);
        string souschaine;
        if(cptLigne>2){
          while(getline(ss,souschaine,' ')){
            if(cptCarac==0)
              grapheMtx[i][0]=stoi(souschaine);
            else if(cptCarac==1)
              grapheMtx[i][1]=stoi(souschaine);
            cptCarac++;
          }
          i++;
        }
        cptCarac=0;
        cptLigne++;
      }
      fichier.close();  // on ferme le fichier
     }
     else  // sinon
      cerr << "Impossible d'ouvrir le fichier !" << endl;

}

//rempli la matrice d'adjacence du grapheMtx a partir du tableau representant ce graphe
void rempliAdjMtx(){
  rempliTabGrapheMtx("inf-power.mtx");
  for(int i=0;i<ordreGrapheMtx;i++){
    for(int j=0;j<ordreGrapheMtx;j++){
      adjMtx[i][j]=0;
    }
  }
  for(int i=0;i<aretesGrapheMtx;i++){
    adjMtx[grapheMtx[i][0]-1][grapheMtx[i][1]-1]=1;
    adjMtx[grapheMtx[i][1]-1][grapheMtx[i][0]-1]=1;
  }
}


int main(){
  srand (time(NULL));
  cout<<"Demonstration petit graphe, entrez le nombre de sommets que vous souhaitez: \n\n";
  cin>>n; //demande à l'utilisateur nombre de sommets pour petit graphe
  adj=new int*[n];
  for (int i = 0; i < n; i++)
     adj[i] = new int[n];
  couleurDsatur=new int[n];
  couleurGlouton=new int[n];
  couleurExact=new int[n];
  DSAT=new int[n];
  Degre=new int[n];
  sommetsGlouton=new int[n];
  centre=new int[n];
  generegraphe(n); //genere le graphe et rempli matrice d'adjacence
  for(int i=0;i<n;i++){
    for(int j=0;j<n;j++){
      cout<<adj[i][j]<<" ";
    }
    cout<<"\n";
  }
  cout<<"\n\n";
  //calcul degenerescence
  cout<<"degenerescence du graphe est de: "<<degenerescencePetitGraphe(n)<<"\n\n";
  cout<<"Tableau des centres\n\n";
  for(int i=0;i<n;i++){
    cout<<centre[i]<<" ";
  }

  //coloration avec colorExact et calcul nombre chromatique
  cout<<"\n\n";
  cout << "Algo ColorExact : ";
  int nbc=nbChromatique(n);
  cout << "Nombre chromatique : " << nbc<<"\n\n";
  for(int i=0;i<n;i++){
     cout << "couleur de " << i << " : " << couleurExact[i]<<"\n";
  }
  cout<<"\n\n";
//coloration DSATUR
  int res=DSATUR();
  cout<<"DSATUR: coloration en " << res << " couleurs : \n";
  for(int i=0;i<n;i++)
     cout << "couleur de " << i << " : " << couleurDsatur[i]<<"\n";
cout<<"\n\n";

//coloration Glouton
ordonneTabGlouton();
cout<<"Tableau ordonne des centres pour glouton: \n\n";
for(int i=0;i<n;i++){
  cout<<sommetsGlouton[i]<<" ";
}
cout<<"\n\n";
cout<<"Nb couleur pour glouton: "<<Glouton()<<"\n\n";
for(int i=0;i<n;i++)
   cout<<"couleur de "<<i<<" : "<<couleurGlouton[i]<<"\n";


//gros graphe fichier MTX
cout<<"\n\n"<<"Demonstration gros graphe Mtx exemple 1\n\n";

recupTailleEtOrdreMtx("inf-power.mtx");//recupere les donnees du graphe
cout<<aretesGrapheMtx<<" "<<ordreGrapheMtx<<"\n\n";
grapheMtx=new int*[aretesGrapheMtx];
for (int i = 0; i < aretesGrapheMtx; i++)
    grapheMtx[i] = new int[2];

rempliTabGrapheMtx("inf-power.mtx");//rempli le tableau d'arete representant ce graphe
adjMtx=new int*[ordreGrapheMtx];
for (int i = 0; i < ordreGrapheMtx; i++)
  adjMtx[i] = new int[ordreGrapheMtx];
centreMtx=new int[ordreGrapheMtx];
rempliAdjMtx();//rempli sa matrice d'adjacence

//calcu degenerescence
cout<<"degenerescence du graphe est de: "<<degenerescenceMtx(ordreGrapheMtx)<<"\n\n";

DSATMTX=new int[ordreGrapheMtx];
couleurDsaturMTX=new int[ordreGrapheMtx];
DegreMTX=new int[ordreGrapheMtx];

//coloration DSATUR
int re=DSATURMTX();
cout<<"DSATUR: coloration en " << re << " couleurs : \n";
cout<<"\n\n";

sommetsGloutonMtx=new int[ordreGrapheMtx];
couleurGloutonMtx=new int[ordreGrapheMtx];
ordonneTabGloutonMTX();

//coloration glouton
cout<<"Nb couleur pour glouton: "<<GloutonMtx()<<"\n\n";


//on supprime de la mémoire tous les tableaux dynamiques 
delete[] couleurGloutonMtx;
delete[] sommetsGloutonMtx;
delete[] couleurDsaturMTX;
delete[] DSATMTX;
delete[] DegreMTX;
for (int i = 0; i < ordreGrapheMtx; i++)
  delete[] adjMtx[i];
delete[] adjMtx;
for (int i = 0; i < aretesGrapheMtx; i++)
  delete[] grapheMtx[i];
delete[] grapheMtx;
delete[] centreMtx;
delete[] centre;
delete[] couleurDsatur;
delete[] DSAT;
delete[] Degre;
delete[] couleurGlouton;
delete[] sommetsGlouton;
for (int i = 0; i < n; i++)
  delete[] adj[i];
delete[] adj;
delete[] couleurExact;

return 0;

}
