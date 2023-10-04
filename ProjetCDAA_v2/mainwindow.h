#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QMainWindow>
#include <QObject>
QT_BEGIN_NAMESPACE
namespace Ui { class MainWindow; }
QT_END_NAMESPACE

class MainWindow : public QMainWindow
{
    Q_OBJECT

public:
    MainWindow(QWidget *parent = nullptr);
    ~MainWindow();

signals:
   void actionFicheContact_triggered();
   void actionModifContact_triggered();

private slots:
    void on_actionQuitter_triggered();

    void on_actionFicheContact_triggered();

    void on_actionModifierContact_triggered();


private:
    Ui::MainWindow *ui;
};
#endif // MAINWINDOW_H
