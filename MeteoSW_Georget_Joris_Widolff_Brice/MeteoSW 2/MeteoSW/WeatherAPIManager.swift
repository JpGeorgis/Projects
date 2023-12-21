//
//  WeatherAPIManager.swift
//  MeteoSW
//
//  Created by Brice Widolff and Joris Georget on 12/12/2023.
//

import Foundation
import SwiftUI

class WeatherAPIManager {
    static let shared = WeatherAPIManager()
    
    private let geocodingBaseURL = "https://geocoding-api.open-meteo.com/v1/search"
    private let weatherForecastBaseURL = "https://api.open-meteo.com/v1/forecast"
    
    func getCoordinates(for city: String, completion: @escaping (Result<(Double, Double, String), Error>) -> Void) {
        let cityName = city.addingPercentEncoding(withAllowedCharacters: .urlQueryAllowed) ?? ""
        let urlString = "\(geocodingBaseURL)?name=\(cityName)&count=10&language=fr&format=json"
        
        guard let url = URL(string: urlString) else {
            completion(.failure(NSError(domain: "Invalid URL", code: 0, userInfo: nil)))
            return
        }
        print(url)
        
        URLSession.shared.dataTask(with: url) { data, response, error in
            if let error = error {
                completion(.failure(error))
                return
            }
            
            if let data = data {
                if let coordinates = self.parseCoordinates(data: data) {
                    completion(.success(coordinates))
                } else {
                    completion(.failure(NSError(domain: "Invalid data format", code: 0, userInfo: nil)))
                }
            }
            
            
        }.resume()
    }
    
    func parseCoordinates(data: Data) -> (Double, Double, String)? {
        do {
            let json = try JSONSerialization.jsonObject(with: data, options: []) as? [String: Any]
            if let results = json?["results"] as? [[String: Any]], let firstResult = results.first {
                if let latitude = firstResult["latitude"] as? Double, let longitude = firstResult["longitude"] as? Double, let pays = firstResult["country"] as? String {
                    return (latitude, longitude, pays)
                }
            }
        } catch {
            print("Error parsing coordinates JSON: \(error)")
        }
        return nil
    }
    
    
    func getHourlyWeatherData(for city: Ville, completion: @escaping ([String]?,[Double]?,[Int]?,[Int]?) -> Void) {
        let urlString = "\(weatherForecastBaseURL)?latitude=\(city.latitude)&longitude=\(city.longitude)&hourly=temperature_2m,relative_humidity_2m,weather_code&timezone=Europe%2FBerlin"
        guard let url = URL(string: urlString) else {
            //completion(.failure(NSError(domain: "Invalid URL", code: 0, userInfo: nil)))
            return
        }
        
        URLSession.shared.dataTask(with: url) { data, response, error in
            if let error = error {
                //completion(.failure(error))
                return
            }
            
            guard let httpResponse = response as? HTTPURLResponse, (200...299).contains(httpResponse.statusCode) else {
                let statusCode = (response as? HTTPURLResponse)?.statusCode ?? 0
                //completion(.failure(NSError(domain: "HTTP Error", code: statusCode, userInfo: nil)))
                return
            }
            
            if let data = data {
                // Assume que `jsonData` est le JSON récupéré

                do {
                    if let json = try JSONSerialization.jsonObject(with: data, options: []) as? [String: Any] {
                        if let hourly = json["hourly"] as? [String: Any],
                           let timeArray = hourly["time"] as? [String],
                           let temperatureArray = hourly["temperature_2m"] as? [Double],
                           let humidityArray = hourly["relative_humidity_2m"] as? [Int],
                           let weatherCodeArray = hourly["weather_code"] as? [Int] {

                            // Utilisez les valeurs récupérées comme nécessaire...
                            
                            print("heure: \(timeArray)")
                            print("temperatureArray: \(temperatureArray)")
                            print("humidity: \(humidityArray)")
                            print("weather code: \(weatherCodeArray)")
                            // ... (et ainsi de suite pour chaque valeur extraite)
                            completion(timeArray,temperatureArray,humidityArray,weatherCodeArray)
                        }
                    }
                    
                } catch {
                    print("Erreur lors du décodage JSON : \(error)")
                }

                    
                /*} catch {
                    completion(.failure(error))
                }*/
            }
        }.resume()
    }
    
    func getCurrentWeatherData(for city: Ville, completion: @escaping (Result<(Double, Int), Error>) -> Void) {
        let urlString = "\(weatherForecastBaseURL)?latitude=\(city.latitude)&longitude=\(city.longitude)&current=temperature_2m,weather_code&timezone=GMT"
        
        guard let url = URL(string: urlString) else {
            completion(.failure(NSError(domain: "Invalid URL", code: 0, userInfo: nil)))
            return
        }
        
        URLSession.shared.dataTask(with: url) { data, response, error in
            if let error = error {
                completion(.failure(error))
                return
            }
            
            if let data = data {
                do {
                    let json = try JSONSerialization.jsonObject(with: data, options: []) as? [String: Any]
                    
                    guard let current = json?["current"] as? [String: Any],
                          let temperature = current["temperature_2m"] as? Double,
                          let weatherCode = current["weather_code"] as? Int else {
                        completion(.failure(NSError(domain: "Invalid data format", code: 0, userInfo: nil)))
                        return
                    }
                    
                    completion(.success((temperature, weatherCode)))
                } catch {
                    completion(.failure(error))
                }
            }
        }.resume()
    }



}

struct HourlyWeather: Decodable {
    let heure: String
    let temperature: Double
    let humidity: Int
    let weatherCode: Int
}

/*struct HourlyData: Codable {
    let time: [String]
    let temperature_2m: [Double]
    let relative_humidity_2m: [Int]
    let weather_code: [Int]
}*/

struct HourlyUnits: Codable {
    let time: String
    let temperature2m: String
    let relativeHumidity2m: String
    let weatherCode: String
    // Ajoutez d'autres propriétés si nécessaire
}

struct HourlyData: Codable {
    let latitude: Double
    let longitude: Double
    let generationTimeMs: Double  // Correspond à la clé "generation_time_ms" dans le JSON
    let utcOffsetSeconds: Int
    let timezone: String
    let timezoneAbbreviation: String
    let elevation: Double
    let hourlyUnits: HourlyUnits
    let hourly: Hourly
}

struct Hourly: Codable {
    let time: [String]
    let temperature_2m: [Double]
    let relative_humidity_2m: [Int]
    let weather_code: [Int]
    // Ajoutez d'autres propriétés si nécessaire
}
