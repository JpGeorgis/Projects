#include "mainwindow.h"
#include "ui_mainwindow.h"
#include <iostream>

MainWindow::MainWindow(QWidget *parent)
    : QMainWindow(parent)
    , ui(new Ui::MainWindow)
{
    ui->setupUi(this);
    c = Carte();
    initCombo();
}

MainWindow::~MainWindow()
{
    delete ui;
}



void MainWindow::on_actionQuitter_triggered()
{
    QApplication::quit();
}


void MainWindow::on_actionAjout_fr_quences_triggered()
{
    emit actionAjout_fr_quences_triggered(c);
}


void MainWindow::on_actionModifier_fr_quences_triggered()
{
    emit actionModifier_fr_quences_triggered(c);
}
void MainWindow::on_actionSupprimer_fr_quences_triggered()
{
    emit actionSupprimer_fr_quences_triggered(c);
}

void MainWindow::initCombo(){
    //liste des antennes
    QList<Antenne> listA;
    listA.reserve(c.getAntennes().size());
    std::copy(c.getAntennes().begin(), c.getAntennes().end(), std::back_inserter(listA));
    ui->listAnt->clear();
    QStringList list;
    for (int i=0;i<listA.size() ;i++ ) {
        list.push_back(QString::fromStdString(listA[i].getNom()));
    }
    ui->listAnt->addItems(list);

    //liste des frequences
    QList<double> listF;
    listF.reserve(c.getFrequences().size());
    std::copy(c.getFrequences().begin(), c.getFrequences().end(), std::back_inserter(listF));
    ui->listFreq->clear();
    QStringList listfr;
    for (int i=0;i<listF.size() ;i++ ) {
        listfr.push_back(QString::fromStdString(std::to_string(listF[i])));
    }
    ui->listFreq->addItems(listfr);
}

void MainWindow::recup(Carte ca){
    c=ca;
    initCombo();
}

