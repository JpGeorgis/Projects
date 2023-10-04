#include "modifcontact.h"
#include "ui_modifcontact.h"

ModifContact::ModifContact(QWidget *parent) :
    QWidget(parent),
    ui(new Ui::ModifContact)
{
    ui->setupUi(this);
}

ModifContact::~ModifContact()
{
    delete ui;
}


void ModifContact::Affiche(){
    this->show();

}

void ModifContact::on_ModifButton_clicked()
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
