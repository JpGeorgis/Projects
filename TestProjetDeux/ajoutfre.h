#ifndef AJOUTFRE_H
#define AJOUTFRE_H

#include <QDialog>
#include "reseaux_plain_cpp/Carte.h"
#include "reseaux_plain_cpp/Antenne.h"

namespace Ui {
class AjoutFre;
}

class AjoutFre : public QDialog
{
    Q_OBJECT

public:
    explicit AjoutFre(QWidget *parent = nullptr);
    ~AjoutFre();

private slots:
    void on_BtnValAnt_clicked();
    void Affiche(Carte);
    void on_submitPlages_clicked();

    void on_buttonValider_clicked();

    void on_BtnValAnt_2_clicked();

    void on_BtnLoadFichier_clicked();

signals:
    void envoieAjout(Carte);
private:
    Ui::AjoutFre *ui;
    Carte c;
};

#endif // AJOUTFRE_H
