#include "modifierfre.h"
#include "ui_modifierfre.h"
#include <QtCore>
#include <QtGui>

ModifierFre::ModifierFre(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::ModifierFre)
{
    ui->setupUi(this);


}

ModifierFre::~ModifierFre()
{
    delete ui;
}

void ModifierFre::Affiche(Carte ca){
    this->show();
    c=ca;
    ui->ant_list->clear();
    ui->posX->clear();
    ui->posY->clear();
    ui->p1->clear();
    ui->p1m->clear();
    ui->p2->clear();
    ui->p2m->clear();
    ui->nbfre->clear();
    ui->nbfrem->clear();
    initCombo();
}

void ModifierFre::on_validA_clicked()
{
    if(c.getAntennes().size()!=0)
    {
        int i=ui->ant_list->currentIndex();
        c.getAntennes()[i].setPos(ui->posX->toPlainText().toInt(),ui->posY->toPlainText().toInt());
        emit envoieModif(c);
        this->close();
    }
}

void ModifierFre::initCombo(){
    if(c.getAntennes().size()!=0)
    {
        QList<Antenne> listA;
        listA.reserve(c.getAntennes().size());
        std::copy(c.getAntennes().begin(), c.getAntennes().end(), std::back_inserter(listA));
        QStringList list;
        for (int i=0;i<listA.size() ;i++ ) {
            list.push_back(QString::fromStdString(listA[i].getNom()));
        }
        ui->ant_list->addItems(list);
        int k=ui->ant_list->currentIndex();
        Antenne a=c.getAntennes()[k];

        ui->posX->setText(QString::fromStdString(std::to_string(a.getPos().x())));
        ui->posY->setText(QString::fromStdString(std::to_string(a.getPos().y())));
    }

    if(c.getFrequences().size()!=0)
    {
        QList<double> listF;
        listF.reserve(c.getFrequences().size());
        std::copy(c.getFrequences().begin(), c.getFrequences().end(), std::back_inserter(listF));
        QStringList listf;
        for (int j=0;j<listF.size() ;j++ ) {
            listf.push_back(QString::fromStdString( std::to_string(listF[j])));
        }
        double lb=c.getFrequences().front();
        double ub=c.getFrequences().back();
        ui->p1->setText(QString::fromStdString(std::to_string(lb)));
        ui->p2->setText(QString::fromStdString(std::to_string(ub)));
        ui->nbfre->setText(QString::fromStdString(std::to_string(c.getFrequences().size())));

    }
}


void ModifierFre::on_validB_clicked()
{
    if(c.getFrequences().size()!=0)
    {
        double f1=stod(ui->p1m->toPlainText().toStdString());
        double f2=stod(ui->p2m->toPlainText().toStdString());
        int nb=stoi(ui->nbfrem->toPlainText().toStdString());
        c.clearFreq();
        if(nb==1){
            c.ajoutFrequence(f1);
        }
        else{
            int n = (f2-f1)/(nb-1);
            for (int i=f1; i<=f2; i=i+n) {
                c.ajoutFrequence(i);
            }
        }
        emit envoieModif(c);
        this->close();
    }

}

void ModifierFre::on_ant_list_currentIndexChanged(int index)
{
    Antenne a=c.getAntennes()[index];
    ui->posX->setText(QString::fromStdString(std::to_string(a.getPos().x())));
    ui->posY->setText(QString::fromStdString(std::to_string(a.getPos().y())));
}
