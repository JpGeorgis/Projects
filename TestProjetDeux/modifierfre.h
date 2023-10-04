#ifndef MODIFIERFRE_H
#define MODIFIERFRE_H

#include <QDialog>
#include "reseaux_plain_cpp/Carte.h"
#include "reseaux_plain_cpp/Antenne.h"
namespace Ui {
class ModifierFre;
}

class ModifierFre : public QDialog
{
    Q_OBJECT

public:
    explicit ModifierFre(QWidget *parent = nullptr);
    ~ModifierFre();
    void initCombo();
private slots:
    void Affiche(Carte);
    void on_validA_clicked();

    void on_validB_clicked();

    void on_pushButton_2_clicked();

    void on_ant_list_currentIndexChanged(int index);

signals:
    void envoieModif(Carte);
private:
    Ui::ModifierFre *ui;
    Carte c;
};

#endif // MODIFIERFRE_H
