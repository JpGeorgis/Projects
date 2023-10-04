/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package controler;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.List;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import model.Grille;
/**
 *
 * @author Frederic
 */
public class Demineur extends HttpServlet {


    /**
     * Handles the HTTP <code>GET</code> method.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException 
    {
        // Recupere la session
        HttpSession session = request.getSession(true);
        // Recupere la grille
        Grille g = (Grille) session.getAttribute("grille");
        // Si la grille n'a pas encore ete creee, on l'a cree
        if (g == null) 
        {
            // Creation de la grille
            g = new Grille(10,10,50);
        }

        // On recupere les parametres de la requete
        String action = request.getParameter("action");
        if (action != null) 
        {
            String str;
            str=request.getParameter("ligne");
            int ligne = Integer.parseInt(str);
            str=request.getParameter("colonne");
            int colonne = Integer.parseInt(str);
            
            // Si les parametres ont ete recuperes, on joue la case
            boolean pasPerdu;
            pasPerdu = g.jouerUneCase(ligne,colonne, (action.indexOf("marquer") != -1));
            if (pasPerdu==false)
            {
                // Afficher un message indiquant que le joueur a perdu
                // Code a completer
            }
        }
        
        // On enregistre la grille dans la session
        session.setAttribute("grille", g);

        
        // Redirection vers la page JSP pour afficher la vue du demineur
        getServletContext().getRequestDispatcher("/vueDemineur.jsp").forward(request, response);
    }

    /**
     * Handles the HTTP <code>POST</code> method.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
    }

    /**
     * Returns a short description of the servlet.
     *
     * @return a String containing servlet description
     */
    @Override
    public String getServletInfo() {
        return "Short description";
    }// </editor-fold>

}
