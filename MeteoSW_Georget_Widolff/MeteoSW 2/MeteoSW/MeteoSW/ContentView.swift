//
//  ContentView.swift
//  MeteoSW
//
//  Created by Brice Widolff and Joris Georget on 27/11/2023.
//

import SwiftUI

struct ContentView: View {
    @ObservedObject var observedCityList = CityList()
    @State private var cityList: CityList = CityList()
    @StateObject private var addCityViewModel = AddCityViewModel()

    init() {
        _cityList = State(initialValue: observedCityList)
    }

    var body: some View {
        NavigationView {
            List {
                ForEach(cityList.villes) { ville in
                    NavigationLink(destination: CityWeatherView(ville: ville)) {
                        VStack(alignment: .leading) {
                            Text(ville.nom)
                                .font(.headline)
                            HStack {
                                Text("\(String(format: "%.1f", ville.temperature ?? 0.0)) °C")
                                weatherIcon(for: ville.weatherCode)
                            }
                        }
                    }.onAppear{
                        updateWeatherDataForCities()
                    }
                }
                .onDelete(perform: deleteVille)
            }
            .listStyle(InsetGroupedListStyle())
            .toolbar {
                ToolbarItem(placement: .principal) {
                    Text("Liste des Villes")
                        .font(.headline)
                }
                ToolbarItem(placement: .navigationBarTrailing) {
                    Button(action: {
                        addCityViewModel.isSheetPresented = true
                    }) {
                        Image(systemName: "plus.circle.fill")
                    }
                }
            }
            .sheet(isPresented: $addCityViewModel.isSheetPresented) {
                            AddCityView(isPresented: $addCityViewModel.isSheetPresented, cityList: $cityList)
                        }
            .onChange(of: addCityViewModel.isSheetPresented) { newValue in
                            // La feuille est fermée, actualisez la vue
                            if !newValue {
                                updateWeatherDataForCities()
                            }
            }
            .onAppear {
                cityList.loadCities()
                updateWeatherDataForCities()
            }
        }
    }
    
    func deleteVille(at indexSet: IndexSet) {
        indexSet.forEach { index in
            cityList.removeVille(at: index)
            cityList.saveCities()
        }
    }
    func weatherIcon(for code: Int) -> Image {
        guard let weather = CityWeatherView.Icon(rawValue: code) else {
            return Image(systemName: "questionmark")
        }
        return weather.image
    }
    func updateWeatherDataForCities() {
        for city in cityList.villes {
            getCurrentWeather(for: city)
        }
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


    

}


struct ContentView_Previews: PreviewProvider {
    static var previews: some View {
        ContentView()
    }
}
