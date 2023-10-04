#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QMainWindow>
#include "reseaux_plain_cpp/Carte.h"
QT_BEGIN_NAMESPACE
namespace Ui { class MainWindow; }
QT_END_NAMESPACE

class MainWindow : public QMainWindow
{
    Q_OBJECT

public:
    MainWindow(QWidget *parent = nullptr);
    ~MainWindow();
    void initCombo();
    Carte c;

private slots:
    void on_actionQuitter_triggered();

    void on_actionAjout_fr_quences_triggered();

    void on_actionModifier_fr_quences_triggered();

    void on_actionSupprimer_fr_quences_triggered();

    void recup(Carte);

signals:
    void actionQuitter_triggered();

    void actionAjout_fr_quences_triggered(Carte);

    void actionModifier_fr_quences_triggered(Carte);

    void actionSupprimer_fr_quences_triggered(Carte);

private:
    Ui::MainWindow *ui;
};
#endif // MAINWINDOW_H
