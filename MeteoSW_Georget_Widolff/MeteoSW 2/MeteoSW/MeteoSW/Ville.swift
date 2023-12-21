//
//  Ville.swift
//  MeteoSW
//
//  Created by Brice Widolff and Joris Georget on 27/11/2023.
//

import UIKit
import SwiftUI
import Combine

class Ville: Identifiable, Encodable, Decodable {
    let nom: String
    let latitude: Double
    let longitude: Double
    let pays: String
    var temperature: Double
    var weatherCode: Int

    init(nom: String) {
        self.nom = nom
        self.latitude = 0
        self.longitude = 0
        self.pays = ""
        self.temperature = 0
        self.weatherCode = 0
    }

    init(nom: String, latitude: Double, longitude: Double) {
        self.nom = nom
        self.latitude = latitude
        self.longitude = longitude
        self.pays = ""
        self.temperature = 0
        self.weatherCode = 0
    }

    init(nom: String, latitude: Double, longitude: Double, pays: String) {
        self.nom = nom
        self.latitude = latitude
        self.longitude = longitude
        self.pays = pays
        self.temperature = 0
        self.weatherCode = 0
    }

    required init(from decoder: Decoder) throws {
        let container = try decoder.container(keyedBy: CodingKeys.self)
        self.nom = try container.decode(String.self, forKey: .nom)
        self.latitude = try container.decode(Double.self, forKey: .latitude)
        self.longitude = try container.decode(Double.self, forKey: .longitude)
        self.pays = try container.decode(String.self, forKey: .pays)
        self.temperature = try container.decode(Double.self, forKey: .temperature)
        self.weatherCode = try container.decode(Int.self, forKey: .weatherCode)
    }

    func setTemp(temp: Double) {
        self.temperature = temp
    }

    func setIcon(icon: Int) {
        self.weatherCode = icon
    }

    private enum CodingKeys: String, CodingKey {
        case nom
        case latitude
        case longitude
        case pays
        case temperature
        case weatherCode
    }
}



