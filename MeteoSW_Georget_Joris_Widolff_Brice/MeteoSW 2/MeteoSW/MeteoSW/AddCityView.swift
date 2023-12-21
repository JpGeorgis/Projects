//
//  AddCityView.swift
//  MeteoSW
//
//  Created by Brice Widolff and Joris Georget on 12/12/2023.
//
import SwiftUI

class AddCityViewModel: ObservableObject {
    @Published var isSheetPresented = false
}

struct AddCityView: View {
    @ObservedObject var viewModel = AddCityViewModel()
    @Binding var isPresented: Bool // Ajoutez une propriété de liaison pour contrôler la fermeture de la vue modale
    @Binding var cityList: CityList
    @State private var cityName = ""
    @State private var coordinates: (Double, Double, String)? // Variable pour stocker les coordonnées récupérées
    var body: some View {
        NavigationView {
            VStack {
                HStack {
                    TextField("Nom de la ville", text: $cityName)
                        .padding()
                        .textFieldStyle(RoundedBorderTextFieldStyle())
                        .autocapitalization(.words)

                    Button("Rechercher") {
                        fetchCoordinates(for: cityName)
                    }
                    .padding()
                }
                
                if let coords = coordinates {
                    Text("\(cityName), \(coords.2)")
                        .padding()

                    Button("Ajouter la ville") {
                        if let name = cityName.isEmpty ? nil : cityName, !name.isEmpty {
                            let v = Ville(nom: name, latitude: coords.0, longitude: coords.1, pays: coords.2)
                            cityList.addVille(v)
                            cityList.saveCities()
                            getCurrentWeather(for: v)
                            cityName = ""
                            isPresented = false
                        }
                    }
                    .padding()
                }
                Spacer()
            }
            .navigationBarTitleDisplayMode(.inline)
            .navigationBarBackButtonHidden(true)
            .navigationBarItems(leading: Button(action: { isPresented = false }) {
                Image(systemName: "chevron.left")
            })
            .toolbar {
                ToolbarItem(placement: .principal) {
                    Text("Ajouter une ville")
                        .font(.headline)
                }
            }
        }
        .onDisappear {
            viewModel.isSheetPresented = false
        }
    }
    

    
    func fetchCoordinates(for city: String) {
        WeatherAPIManager.shared.getCoordinates(for: city) { result in
            switch result {
                case .success(let coords):
                    self.coordinates = coords
                case .failure(let error):
                    print("Error fetching coordinates: \(error)")
                    // Gérer l'erreur si nécessaire
            }
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

struct AddCityView_Previews: PreviewProvider {
    @State static var isPresented = true // État de la vue modale
    @State static var cityList = CityList() // Liste de villes

    static var previews: some View {
        AddCityView(isPresented: $isPresented, cityList: $cityList)
    }
}
