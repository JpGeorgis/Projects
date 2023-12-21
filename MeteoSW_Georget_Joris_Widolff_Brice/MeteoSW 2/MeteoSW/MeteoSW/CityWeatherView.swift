//
//  CityWeatherView.swift
//  MeteoSW
//
//  Created by Brice Widolff and Joris Georget on 13/12/2023.
//
import SwiftUI

struct CityWeatherView: View {
    let ville: Ville
    @State private var currentTemperature: Double?
    @State private var currentWeatherCode: Int?
    @State private var hourlyWeatherData: [HourlyWeather] = []
    @State private var isLoading = true // Pour afficher un indicateur de chargement
    
    var body: some View {
            VStack {
                HStack {
                    Text("Météo du \(currentDate)")
                        .font(.title)
                        .padding()
                        .frame(alignment: .leading)
                    Spacer()
                }

                if isLoading {
                    ProgressView("Chargement des données météorologiques...")
                } else {
                    HStack {
                        VStack(alignment: .center, spacing: 4) {
                            weatherIcon(for: ville.weatherCode ?? 0)
                                .font(.system(size: 72)) // Ajustez la taille de l'icône
                            if let currentTemperature = ville.temperature {
                                Text("\(String(format: "%.1f", currentTemperature)) °C")
                                    .font(.system(size: 24, weight: .bold)) // Ajustez la taille de la température
                            }
                        }
                    }
                    .padding()

                    HStack {
                        Text("Météo par Heure")
                            .font(.title)
                            .padding()
                            .frame(alignment: .leading)
                        Spacer()
                    }

                    if !hourlyWeatherData.isEmpty {
                        ScrollView(.horizontal, showsIndicators: false) {
                            HStack(spacing: 0) {
                                ForEach(hourlyWeatherData, id: \.heure) { hourlyData in
                                    HStack {
                                        VStack {
                                            VStack(alignment: .leading) {
                                                Text(formattedHour(dateString: hourlyData.heure) ?? "")
                                                    .font(.system(size: 16))
                                                    .fontWeight(.bold)
                                                Text(formattedDate(dateString: hourlyData.heure) ?? "")
                                                    .font(.system(size: 12))
                                                    .foregroundColor(.gray)
                                            }
                                            .frame(width: 120)
                                            .alignmentGuide(.top) { $0[.bottom] }

                                            VStack(alignment: .center, spacing: 4) {
                                                HStack {
                                                    Image(systemName: "drop.fill")
                                                        .foregroundColor(.black)
                                                        .font(.system(size: 18))
                                                    Text("\(hourlyData.humidity)%")
                                                        .font(.system(size: 18))
                                                        .foregroundColor(.black)
                                                }
                                            }
                                            .frame(width: 120)
                                            .alignmentGuide(.bottom) { $0[.top] }
                                            .alignmentGuide(.trailing) { $0[.leading] }
                                        }

                                        VStack(alignment: .center, spacing: 4) {
                                            weatherIcon(for: hourlyData.weatherCode)
                                                .font(.system(size: 36))
                                            Text("\(String(format: "%.1f", hourlyData.temperature)) °C")
                                                .font(.system(size: 18, weight: .bold))
                                        }
                                    }
                                    .padding()
                                    .frame(width: 225)
                                    .alignmentGuide(.leading) { _ in 0 }
                                }
                            }
                            .padding(0)
                            
                        }
                    } else {
                        Text("Aucune donnée météorologique disponible.")
                    }
                }
                Spacer()
            }
            .onAppear {
                getCurrentWeather(for: ville)
                loadWeatherForCity(for: ville)
            }
        
            .navigationTitle("\(ville.nom), \(ville.pays)")
        }
    var currentDate: String {
            let formatter = DateFormatter()
            formatter.dateFormat = "dd MMMM yyyy HH:mm"
            return formatter.string(from: Date())
        }
    func getCurrentWeather(for city: Ville) {
        WeatherAPIManager.shared.getCurrentWeatherData(for: city) { result in
            DispatchQueue.main.async {
                switch result {
                case .success(let (temperature, weatherCode)):
                    city.setTemp(temp: temperature)
                    city.setIcon(icon: weatherCode)
                case .failure(let error):
                    print("Error fetching current weather data: \(error)")
                }
            }
        }
    }
    func formattedHour(dateString: String) -> String? {
        let dateFormatter = DateFormatter()
        dateFormatter.dateFormat = "yyyy-MM-dd'T'HH:mm"
        guard let date = dateFormatter.date(from: dateString) else { return nil }
        
        let hourFormatter = DateFormatter()
        hourFormatter.dateFormat = "HH:mm"
        return hourFormatter.string(from: date)
    }
    func formattedDate(dateString: String) -> String? {
            let dateFormatter = DateFormatter()
            dateFormatter.dateFormat = "yyyy-MM-dd'T'HH:mm"
            guard let date = dateFormatter.date(from: dateString) else { return nil }
            
            let dateFormatterOutput = DateFormatter()
            dateFormatterOutput.dateFormat = "dd/MM/yyyy"
            return dateFormatterOutput.string(from: date)
        }
    
    func loadWeatherForCity(for city: Ville) {
        isLoading = true
        
        WeatherAPIManager.shared.getHourlyWeatherData(for: city) { timeArray, temperatureArray, humidityArray, weatherCodeArray in
            DispatchQueue.main.async {
                isLoading = false
                
                if let timeArray = timeArray,
                   let temperatureArray = temperatureArray,
                   let humidityArray = humidityArray,
                   let weatherCodeArray = weatherCodeArray {
                    
                    self.hourlyWeatherData = timeArray.enumerated().map { index, time in
                        HourlyWeather(
                            heure: time,
                            temperature: temperatureArray[index],
                            humidity: humidityArray[index],
                            weatherCode: weatherCodeArray[index]
                        )
                    }
                } else {
                    print("Error: Unable to fetch hourly weather data for the city")
                    // Gérer l'erreur de chargement des données météorologiques horaires
                }
            }
        }
    }

    
    struct CityWeatherView_Previews: PreviewProvider {
        static var previews: some View {
            let ville = Ville(nom: "Paris")
            return CityWeatherView(ville: ville)
        }
    }
    
    struct HourlyWeatherData {
        let time: String
        let temperature_2m: Double 
        let relative_humidity_2m: Int
        let weather_code: Int
    }
    
    struct WeatherResponse: Codable {
        let hourly: HourlyData
    }
    
    /*struct HourlyData: Codable {
        let time: [String]
        let temperature_2m: [Double]
        let relative_humidity_2m: [Int]
        let weather_code: [Int]
    }*/

    
    func weatherIcon(for code: Int) -> Image {
        guard let weather = Icon(rawValue: code) else {
            return Image(systemName: "questionmark")
        }
        return weather.image
    }
    
    enum Icon: Int {
        case clearSky = 0
        case mainlyClear = 1
        case partlyCloudy = 2
        case overcast = 3
        case fog = 45
        case depositingRimeFog = 48
        case drizzleLight = 51
        case drizzleModerate = 53
        case drizzleDense = 55
        case freezingDrizzleLight = 56
        case freezingDrizzleDense = 57
        case rainSlight = 61
        case rainModerate = 63
        case rainHeavy = 65
        case freezingRainLight = 66
        case freezingRainHeavy = 67
        case snowFallSlight = 71
        case snowFallModerate = 73
        case snowFallHeavy = 75
        case snowGrains = 77
        case rainShowersSlight = 80
        case rainShowersModerate = 81
        case rainShowersViolent = 82
        case snowShowersSlight = 85
        case snowShowersHeavy = 86
        case thunderstormSlight = 95
        case thunderstormSlightHail = 96
        case thunderstormHeavyHail = 99
        
        var image: Image {
            switch self {
            case .clearSky:
                return Image(systemName: "sun.max.fill")
            case .mainlyClear, .partlyCloudy, .overcast:
                return Image(systemName: "cloud.sun.fill")
            case .fog, .depositingRimeFog:
                return Image(systemName: "cloud.fog.fill")
            case .drizzleLight, .drizzleModerate, .drizzleDense:
                return Image(systemName: "cloud.drizzle.fill")
            case .freezingDrizzleLight, .freezingDrizzleDense:
                return Image(systemName: "cloud.sleet.fill")
            case .rainSlight, .rainModerate, .rainHeavy:
                return Image(systemName: "cloud.rain.fill")
            case .freezingRainLight, .freezingRainHeavy:
                return Image(systemName: "cloud.sleet.fill")
            case .snowFallSlight, .snowFallModerate, .snowFallHeavy, .snowGrains:
                return Image(systemName: "snow")
            case .rainShowersSlight, .rainShowersModerate, .rainShowersViolent:
                return Image(systemName: "cloud.heavyrain.fill")
            case .snowShowersSlight, .snowShowersHeavy:
                return Image(systemName: "cloud.snow.fill")
            case .thunderstormSlight, .thunderstormSlightHail, .thunderstormHeavyHail:
                return Image(systemName: "cloud.bolt.fill")
            }
        }
    }
}
