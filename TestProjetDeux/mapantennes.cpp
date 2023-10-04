#include "mapantennes.h"

MapAntennes::MapAntennes(QObject *parent) : QObject(parent)
{

}

void MapAntennes::addPlace(double latitude, double longitude)
{
    Q_EMIT add(latitude, longitude);
}
