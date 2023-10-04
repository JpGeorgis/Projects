#ifndef MODIFCONTACT_H
#define MODIFCONTACT_H

#include <QWidget>

namespace Ui {
class ModifContact;
}

class ModifContact : public QWidget
{
    Q_OBJECT

public:
    explicit ModifContact(QWidget *parent = nullptr);
    ~ModifContact();

public slots:
   void Affiche();

private slots:
   void on_ModifButton_clicked();

private:
    Ui::ModifContact *ui;
};

#endif // MODIFCONTACT_H
