#include "mainwindow.h"
#include "fichecontact.h"
#include "modifcontact.h"
#include <QApplication>
#include <QLocale>
#include <QTranslator>


int main(int argc, char *argv[])
{
    QApplication a(argc, argv);

    QTranslator translator;
    const QStringList uiLanguages = QLocale::system().uiLanguages();
    for (const QString &locale : uiLanguages) {
        const QString baseName = "ProjetCDAA_v2_" + QLocale(locale).name();
        if (translator.load(":/i18n/" + baseName)) {
            a.installTranslator(&translator);
            break;
        }
    }
    MainWindow w;
    w.show();
    FicheContact fiche;
    ModifContact modif;
    QObject::connect(&w , SIGNAL ( actionFicheContact_triggered() ) , &fiche , SLOT ( Affiche() ) ) ;
    QObject::connect(&w , SIGNAL ( actionModifContact_triggered() ) , &modif , SLOT ( Affiche() ) ) ;

    return a.exec();
}
