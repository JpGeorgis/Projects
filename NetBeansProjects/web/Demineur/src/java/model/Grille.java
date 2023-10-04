/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package model;

import java.util.Random;

/**
 *
 * @author Frederic
 */
public class Grille 
{
    private int nbLig;
    private int nbCol;
    private int[][] tab; // -1 : mine , 0 - 8 : info sur les mines autour
    private int[][] visibility; // 0 : non creusée, 1 : creusée, 2 : drapeau

    public Grille(int nbLig, int nbCol, int nbMines) 
    {
        // Création de la Grille
        Random r = new Random();
        setNbLig(nbLig);
        setNbCol(nbCol);
        int[][] visibility = new int[nbLig][nbCol];
        int[][] tab = new int[nbLig][nbCol];
        for (int i = 0; i < nbLig; i++) {
            for (int j = 0; j < nbCol; j++) {
                visibility[i][j] = 0;
                tab[i][j] = 0;
            }
        }
        int m = 0;
        while (m < nbMines) {
            int x = r.nextInt(nbLig);
            int y = r.nextInt(nbCol);
            if (tab[x][y] == 0) // Sinon il y a déjà une mine à cet emplacement
            {
                tab[x][y] = -1;
                m++;
            }
        }
        setTab(tab);
        setVisibility(visibility);
        // On remplit les autres cases
        for (int i = 0; i < nbLig; i++) {
            for (int j = 0; j < nbCol; j++) {
                if (tab[i][j] != -1) {
                    tab[i][j] = nbMinesAround( i, j);
                }
            }
        }
    }

    public int getNbLig() {
        return nbLig;
    }

    public void setNbLig(int nbLig) {
        this.nbLig = nbLig;
    }

    public int getNbCol() {
        return nbCol;
    }

    public void setNbCol(int nbCol) {
        this.nbCol = nbCol;
    }

    public int[][] getTab() {
        return tab;
    }

    public void setTab(int[][] tab) {
        this.tab = tab;
    }

    public int[][] getVisibility() {
        return visibility;
    }

    public void setVisibility(int[][] visibility) {
        this.visibility = visibility;
    }

    /**
     * Compte le nombre de mines autour de la position (x,y) de la Grille this
     *
     * @return Le nombre de mines calculé
     */
    public int nbMinesAround(int x, int y) {
        int nb = 0;
        if (isMine(x - 1, y - 1)) {
            nb++;
        }
        if (isMine(x, y - 1)) {
            nb++;
        }
        if (isMine(x + 1, y - 1)) {
            nb++;
        }
        if (isMine(x - 1, y)) {
            nb++;
        }
        if (isMine(x + 1, y)) {
            nb++;
        }
        if (isMine(x - 1, y + 1)) {
            nb++;
        }
        if (isMine(x, y + 1)) {
            nb++;
        }
        if (isMine(x + 1, y + 1)) {
            nb++;
        }
        return nb;
    }

    /**
     * Test pour savoir s'il y a une mine à la position (x,y) de la Grille this
     *
     * @return La valeur du test booléen
     */
    public boolean isMine(int x, int y) {
        if (x < 0 || x > getNbLig() - 1 || y < 0 || y > getNbCol() - 1) {
            return false;
        }
        if (getTab()[x][y] == -1) {
            return true;
        }
        return false;
    }
    
    public String genererCodeHTMLPourAffichage()
    {
        String s="";
        for(int i=0;i<getNbLig();i++)
        {
            s=s+"<tr>";
            for(int j=0;j<getNbLig();j++)
            {
                s=s+"<td>";
                if (visibility[i][j]==1)
                    if (tab[i][j]==-1)
                        s=s+"X";
                    else
                        s=s+tab[i][j];
                else if (visibility[i][j]==0)
                    s=s+"-";
                else if (visibility[i][j]==2)
                    s=s+"+";
                s=s+"</td>";
            }
            s=s+"</tr>";
        }
        s=s+"<br>";
        return s;
    }
    
    // Creuse ou place un drapeau a la case (l,c)
    // Si placerDrapeau est true, on place un drapeau sinon on creuse
    // La fonction retourne false si on a creuse sur une mine
    public boolean jouerUneCase(int l, int c, boolean placerDrapeau)
    {
        if (placerDrapeau)
        {
            visibility[l][c]=2;
            return true;
        }
        else
        {
            visibility[l][c]=1;
            return tab[l][c]!=-1;
        }
    }
}
