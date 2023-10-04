#include "ajoutfre.h"
#include "ui_ajoutfre.h"
#include "QFile"
#include "QTextStream"
#include "iostream"

AjoutFre::AjoutFre(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::AjoutFre)
{
    ui->setupUi(this);

}

AjoutFre::~AjoutFre()
{
    delete ui;
}

void AjoutFre::Affiche(Carte ca){
    this->show();
    c=ca;
    ui->Name_Ant->clear();
    ui->Pos_AntX->clear();
    ui->Pos_AntY->clear();
    ui->plage1->clear();
    ui->plage2->clear();
    ui->nb_plages->clear();
}

void AjoutFre::on_BtnValAnt_clicked()
{
    Antenne *a=new Antenne(ui->Name_Ant->toPlainText().toStdString(),stod(ui->Pos_AntX->toPlainText().toStdString()),stod(ui->Pos_AntY->toPlainText().toStdString()));
    c.ajoutAntenne(*a);
    emit envoieAjout(c);
    this->close();
}


void AjoutFre::on_submitPlages_clicked()
{
    double f1=stod(ui->plage1->toPlainText().toStdString());
    double f2=stod(ui->plage2->toPlainText().toStdString());
    int nb=stoi(ui->nb_plages->toPlainText().toStdString());
    if(nb==1){
        c.ajoutFrequence(f1);
    }
    else{
        int n = (f2-f1)/(nb-1);
        for (int i=f1; i<=f2; i=i+n) {
            c.ajoutFrequence(i);
        }
    }

    emit envoieAjout(c);
    this->close();
}


/*QgsCoordinateReferenceSystem crs = QgsCoordinateReferenceSystem::fromOgcWmsCrs("EPSG:4326"); // Système de coordonnées GPS

  // Création de l'objet de transformation
  QgsCoordinateTransform transform(crs, QgsCoordinateReferenceSystem::fromEpsgId(3857));

  // Conversion des coordonnées
  QgsPointXY point_lambert(50000, 60000); // Coordonnées Lambert
  QgsPointXY point_pixel = transform.transform(point_lambert); // Coordonnées pixels

  // Affichage des coordonnées converties
  std::cout << "Coordonnées pixels : " << point_pixel.x() << ", " << point_pixel.y() << std::endl;
*/

void AjoutFre::on_BtnLoadFichier_clicked()
{

    QString file = ui->NomFichier->toPlainText();
    QFile bdd(file);
    if(!bdd.open(QIODevice::ReadOnly | QIODevice::Text))
    {
        ui->plage2->setText(("erreur ouverture"));
        return;
    }
    QTextStream stream(&bdd);
    QString line = stream.readLine();//pour ignorer la 1ere ligne
    while(!stream.atEnd()){
        QString line = stream.readLine();
        QStringList columns = line.split(';');
        QString Nom = columns.at(0);
        QString x = columns.at(1);
        QString y = columns.at(2);
        QString r = columns.at(3);
        //Conversion x,y en lambert
        Antenne *a=new Antenne(Nom.toStdString(),stod(x.toStdString()),stod(y.toStdString()),stod(r.toStdString()));
        c.ajoutAntenne(*a);
    }
    bdd.close();
    emit envoieAjout(c);
    this->close();

}
