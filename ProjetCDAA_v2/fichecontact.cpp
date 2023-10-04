#include "fichecontact.h"
#include "ui_fichecontact.h"

FicheContact::FicheContact(QWidget *parent) :
    QWidget(parent),
    ui(new Ui::FicheContact)
{
     ui->setupUi(this);
}

FicheContact::~FicheContact()
{
    delete ui;
}



void FicheContact::Affiche(){
    this->show();

}




void FicheContact::on_AddButton_clicked()
{
    ui->NomFiche->setText(ui->NomLineEdit->text());
    ui->PrenomFiche->setText(ui->PrenomLineEdit->text());
    ui->EntrepriseFiche->setText(ui->EntrepriseLineEdit->text());
    ui->MailFiche->setText(ui->MailLineEdit->text());
    ui->TelFiche->setText(ui->TelLineEdit->text());
    ui->DateFiche->setDate(ui->DateEdit->date());

    QString filename = "";
    filename=ui->uRIPhotoLineEdit->text();
    QPixmap pix;


    if(pix.load(filename)){

            ui->PhotoLabel->setPixmap(pix);
        }
}

