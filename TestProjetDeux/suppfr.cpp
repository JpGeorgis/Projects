#include "suppfr.h"
#include "ui_suppfr.h"

Suppfr::Suppfr(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::Suppfr)
{
    ui->setupUi(this);
}

Suppfr::~Suppfr()
{
    delete ui;
}
