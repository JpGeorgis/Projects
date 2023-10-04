
import java.util.ArrayList;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author joris
 */
public class ListedesFichiers {

    public ListedesFichiers() {
    }

    public ListedesFichiers(ArrayList<Fichier> list) {
        this.list = list;
    }
    private ArrayList<Fichier> list;

    public ArrayList<Fichier> getList() {
        return list;
    }

    public void setList(ArrayList<Fichier> list) {
        this.list = list;
    }
    
    public void ajoutModif(String n, String c){
        boolean isIn=false;
        for(int i=0;i<list.size();i++){
            if(list.get(i).getNom().equals(n)){
                list.get(i).setContenu(c);
                isIn=true;
            }
        }
        if(!isIn) {
            list.add(new Fichier(n,c));
        }
    }
    
    public String charger(String n){
        String c="";
         for(int i=0;i<list.size();i++){
            if(list.get(i).getNom().equals(n)){
                c=list.get(i).getContenu();
            }
        }
        return c;
    }
    public void supprimer(){
        list.clear();
    }
}
