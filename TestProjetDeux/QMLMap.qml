import QtQuick 2.0
import QtQuick.Window 2.1
import QtLocation 5.6
import QtPositioning 5.6

Item {
    visible: true
    Plugin {
        id: mapPlugin
        name: "osm"
        PluginParameter {
                    name: "osm.mapping.providersrepository.disabled"
                    value: "true"
        }
        PluginParameter {
                    name: "osm.mapping.providersrepository.address"
                    value: "http://maps-redirect.qt.io/osm/5.6/street"
        }
    }

    Map {
        anchors.fill: parent
        plugin: mapPlugin
        center: QtPositioning.coordinate(47.7486, 7.33944) // Mulhouse
        zoomLevel: 14
    }
}
