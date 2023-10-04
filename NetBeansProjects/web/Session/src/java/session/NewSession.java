/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package session;

import java.io.IOException;
import java.io.PrintWriter;
import java.util.ArrayList;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

/**
 *
 * @author joris
 */
public class NewSession extends HttpServlet {

    /**
     * Processes requests for both HTTP <code>GET</code> and <code>POST</code>
     * methods.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */


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
           HttpSession session = request.getSession(true);
           Boolean nouvelleSession=session.isNew();
           try (PrintWriter out = response.getWriter()) {
                
                /* TODO output your page here. You may use following ssample code. */
                out.println("<!DOCTYPE html>");
                out.println("<html>");
                out.println("<head>");
                out.println("<title>Session</title>");
                out.println(" <meta charset='UTF-8'> ");
                out.println("<meta name='viewport' content='width=device-width, initial-scale=1.0'>");
                out.println("</head>");
                out.println("<body>");
                out.println("<form action='./Servlet2' method='get'>");
                out.println("Nom : <input type=\"text\" name=\"nom\" value=\"\"> <br>");
                out.println("Prenom : <input type=\"text\" name=\"prenom\" value=\"\"> <br>");
                out.println("Note : <input type=\"number\" name=\"n\" value=\"0\"> <br>");
                out.println("<input type=\"submit\" value=\"Submit\">");
                out.println("</form>");
                
                if(nouvelleSession){
                    ArrayList<Etu> listEd = new ArrayList<>();
                    session.setAttribute("list", listEd);
                }
                else{
                    ArrayList<Etu> listEd =( ArrayList<Etu>) session.getAttribute("list");
                    out.println("<table>\n" +
                                "    <tr>\n" +
                                "        <th>Nom</th>\n" +
                                "        <th>Prenom</th>\n" +
                                "        <th>Note</th>\n" +
                                "    </tr>\n");
                    for(int i=0;i<listEd.size();i++){
                        out.println("    <tr>\n" +
                                "        <td>"+listEd.get(i).nom+"</td>\n" +
                                "        <td>"+listEd.get(i).prenom+"</td>\n" +
                                "        <td>"+listEd.get(i).note+"</td>\n" +
                                "    </tr>\n");
                    } 
                    out.println("\n" +
                                "</table>");
                    
                }
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
