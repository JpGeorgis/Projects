//
//  ListVilles.swift
//  MeteoSW
//
//  Created by Brice Widolff and Joris Georget on 12/12/2023.
//
import UIKit
import Combine
import SwiftUI
class CityList: ObservableObject {
    @Published var villes: [Ville] = []

    init() {
        // Exemple d'initialisation avec des données statiques
        self.villes = []
    }
    
    // Méthode pour ajouter une ville à la liste
    func addVille(_ ville: Ville) {
        villes.append(ville)
    }
    
    // Méthode pour supprimer une ville de la liste
    func removeVille(at index: Int) {
        if index < villes.count {
            villes.remove(at: index)
        }
    }
    
    func saveCities() {
           do {
               let data = try JSONEncoder().encode(villes)
               if let documentsDirectory = FileManager.default.urls(for: .documentDirectory, in: .userDomainMask).first {
                   let fileURL = documentsDirectory.appendingPathComponent("cities.json")
                   try data.write(to: fileURL)
               }
           } catch {
               print("Error saving cities: \(error)")
           }
       }
    
    func loadCities() {
            if let documentsDirectory = FileManager.default.urls(for: .documentDirectory, in: .userDomainMask).first {
                let fileURL = documentsDirectory.appendingPathComponent("cities.json")
                
                do {
                    let data = try Data(contentsOf: fileURL)
                    let decodedCities = try JSONDecoder().decode([Ville].self, from: data)
                    villes = decodedCities
                } catch {
                    print("Error loading cities: \(error)")
                }
            }
            
        }


    
}
