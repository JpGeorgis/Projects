#ifndef MAPANTENNES_H
#define MAPANTENNES_H

#include <QObject>

//https://stackoverflow.com/questions/66779645/adding-new-places-to-the-qml-map-is-not-working

class MapAntennes : public QObject
{
    Q_OBJECT
public:
    explicit MapAntennes(QObject *parent = nullptr);
    void addPlace(double latitude, double longitude);
Q_SIGNALS:
    void add(double latitude, double longitude);
};

#endif // MAPANTENNES_H

