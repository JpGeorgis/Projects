#include "mainwindow.h"
#include "ui_mainwindow.h"

MainWindow::MainWindow(QWidget *parent)
    : QMainWindow(parent)
    , ui(new Ui::MainWindow)
{
    ui->setupUi(this);
}


MainWindow::~MainWindow()
{
    delete ui;
}


void MainWindow::on_actionQuitter_triggered()
{
    this->close();
}



void MainWindow::on_actionFicheContact_triggered()
{
   emit actionFicheContact_triggered();
}


void MainWindow::on_actionModifierContact_triggered()
{
     emit actionModifContact_triggered();
}

