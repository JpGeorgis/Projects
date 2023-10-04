#include "mainwindow.h"
#include "ajoutfre.h"
#include "modifierfre.h"
#include "suppfr.h"
#include <QApplication>

int main(int argc, char *argv[])
{
    QApplication a(argc, argv);
    MainWindow w;
    AjoutFre f;
    ModifierFre m;
    Suppfr s;
    w.show();
    //ajout
    QObject::connect(&w , SIGNAL ( actionAjout_fr_quences_triggered(Carte ) ) , &f , SLOT ( Affiche(Carte   ) ) ) ;
    QObject::connect(&f , SIGNAL ( envoieAjout(Carte) ) , &w , SLOT ( recup(Carte   ) ) ) ;

    //modif
    QObject::connect(&w , SIGNAL ( actionModifier_fr_quences_triggered(Carte ) ) , &m , SLOT ( Affiche(Carte   ) ) ) ;
    QObject::connect(&m , SIGNAL ( envoieModif(Carte) ) , &w , SLOT ( recup(Carte   ) ) ) ;
    //supp
    QObject::connect(&w , SIGNAL ( actionSupprimer_fr_quences_triggered(Carte ) ) , &s , SLOT ( Affiche(Carte   ) ) ) ;
    QObject::connect(&s , SIGNAL ( envoieSupp(Carte) ) , &w , SLOT ( recup(Carte   ) ) ) ;


    return a.exec();
}
