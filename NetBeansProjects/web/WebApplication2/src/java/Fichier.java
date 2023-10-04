/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author joris
 */
public class Fichier {

    public Fichier() {
        this.nom = "";
        this.contenu = "";
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public String getContenu() {
        return contenu;
    }

    public void setContenu(String contenu) {
        this.contenu = contenu;
    }

    public Fichier(String nom, String contenu) {
        this.nom = nom;
        this.contenu = contenu;
    }
    private String nom;
    private String contenu;
   
   
}
