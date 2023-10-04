QT += core gui
QT += quick
QT += quickwidgets

greaterThan(QT_MAJOR_VERSION, 4): QT += widgets

CONFIG += c++17

# You can make your code fail to compile if it uses deprecated APIs.
# In order to do so, uncomment the following line.
#DEFINES += QT_DISABLE_DEPRECATED_BEFORE=0x060000    # disables all the APIs deprecated before Qt 6.0.0

SOURCES += \
    ajoutfre.cpp \
    main.cpp \
    mainwindow.cpp \
    mapantennes.cpp \
    modifierfre.cpp \
    reseaux_plain_cpp/Affichage.cpp \
    reseaux_plain_cpp/Antenne.cpp \
    reseaux_plain_cpp/Arete.cpp \
    reseaux_plain_cpp/Carte.cpp \
    reseaux_plain_cpp/Graphe.cpp \
    reseaux_plain_cpp/Point.cpp \
    reseaux_plain_cpp/Sommet.cpp \
    suppfr.cpp

HEADERS += \
    ajoutfre.h \
    mainwindow.h \
    mapantennes.h \
    modifierfre.h \
    reseaux_plain_cpp/Affichage.h \
    reseaux_plain_cpp/Antenne.h \
    reseaux_plain_cpp/Arete.h \
    reseaux_plain_cpp/Carte.h \
    reseaux_plain_cpp/Graphe.h \
    reseaux_plain_cpp/Point.h \
    reseaux_plain_cpp/Sommet.h \
    suppfr.h

FORMS += \
    ajoutfre.ui \
    mainwindow.ui \
    modifierfre.ui \
    suppfr.ui

# Default rules for deployment.
qnx: target.path = /tmp/$${TARGET}/bin
else: unix:!android: target.path = /opt/$${TARGET}/bin
!isEmpty(target.path): INSTALLS += target

RESOURCES += \
    qmlMap.qrc
