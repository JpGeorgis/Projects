/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

import java.io.IOException;
import java.io.PrintWriter;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

/**
 *
 * @author joris
 */
public class FormServlet extends HttpServlet {

    /**
     * Processes requests for both HTTP <code>GET</code> and <code>POST</code>
     * methods.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    protected void processRequest(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        response.setContentType("text/html;charset=UTF-8");
        
    }

    // <editor-fold defaultstate="collapsed" desc="HttpServlet methods. Click on the + sign on the left to edit the code.">
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
            throws ServletException, IOException {
        try (PrintWriter out = response.getWriter()) {
            /* TODO output your page here. You may use following sample code. */
            out.println("<!DOCTYPE html>");
            out.println("<html>");
            out.println("<head>");
            out.println("<title>Formulaire</title>");            
            out.println("</head>");
            out.println("<body>");
            out.println("<form action='./Servlet1' method='get'>\n" +
                        "Nom du Fichier pour l'enregistrement: <input type='text' name='nom' value=''> <br>\n" +
                        "<textarea name='contenu' >Voila un exemple de texte pour le fichier</textarea> <br>\n" +
                        "<input type=\"submit\" value=\"Enregistrer\">\n" +
                        "</form>\n" +
                        "<br>\n" +
                        "<br>\n" +
                        "<form action=\"./Servlet2\" method=\"get\">\n" +
                        "Nom du Fichier à charger: <input type=\"text\" name=\"nomF\" value=\"\"> <br>\n" +
                        "<input type=\"submit\" value=\"Charger\">\n" +
                        "</form>\n" +
                        "<br>\n" +
                        "<br>\n" +
                        "<form action=\"./Servlet3\" method=\"get\">\n" +
                        "<input type=\"submit\" value=\"Supprimer tous les fichiers\">\n" +
                        "</form>");
            out.println("</body>");
            out.println("</html>");
        }    
        
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
        processRequest(request, response);
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
