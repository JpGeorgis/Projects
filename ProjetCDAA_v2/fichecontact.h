#ifndef FICHECONTACT_H
#define FICHECONTACT_H

#include <QWidget>

namespace Ui {
class FicheContact;
}

class FicheContact : public QWidget
{
    Q_OBJECT

public:
    explicit FicheContact(QWidget *parent = nullptr);
    ~FicheContact();

public slots:
   void Affiche();


private slots:
   void on_AddButton_clicked();

private:
    Ui::FicheContact *ui;
};

#endif // FICHECONTACT_H
