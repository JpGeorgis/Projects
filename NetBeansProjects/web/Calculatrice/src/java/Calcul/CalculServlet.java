/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Calcul;

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
public class CalculServlet extends HttpServlet {

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
         response.setContentType("text/html;charset=UTF-8");
        try (PrintWriter out = response.getWriter()) {
            String n1;
            String n2;
            String op;
            int res=0;
            /* TODO output your page here. You may use following sample code. */
            out.println("<!DOCTYPE html>");
            out.println("<html>");
            out.println("<head>");
            out.println("<title>Calculatrice</title>");
            out.println(" <meta charset='UTF-8'> ");
            out.println("<meta name='viewport' content='width=device-width, initial-scale=1.0'>");
            out.println("</head>");
            out.println("<body>");
            out.println("<form action='' method='get'>");
            out.println("Nombre 1 : <input type='number' name='n1' value='0'> <br>");
            out.println("Nombre 2 : <input type='number' name='n2' value='0'> <br>");
            out.println("Operateur : <select name='op'>");
            out.println("<option value='+'>+</option>");
            out.println("<option value='-'>-</option>");
            out.println("<option value='/'>/</option>");
            out.println("<option value='*'>*</option>");
            out.println("</select><br>");
            out.println("<input type='submit' value='Go'><br>");
            n1=request.getParameter("n1");
            n2=request.getParameter("n2");
            op=request.getParameter("op");
            
             switch (op) {
                 case "+":
                     res=Integer.parseInt(n1)+Integer.parseInt(n2);
                     break;
                 case "-":
                     res=Integer.parseInt(n1)-Integer.parseInt(n2);
                     break;
                 case "*":
                     res=Integer.parseInt(n1)*Integer.parseInt(n2);
                     break;
                 case "/":
                     res=Integer.parseInt(n1)/Integer.parseInt(n2);
                     break;
                 default:
                     break;
             }
            out.println("Result : <input type='number' name='result' value='"+res+"'");
            out.println("</form>");
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
