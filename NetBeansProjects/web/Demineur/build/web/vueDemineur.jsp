<%-- 
    Document   : vueDemineur
    Created on : 19-Oct-2022, 17:15:16
    Author     : joris
--%>

<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>JSP Page</title>
    </head>
    <body>
        <table>
        <%@ page import="model.Grille" %> 
        <%
            
            // Code java pour afficher la grille du jeu
            Grille g=(Grille)session.getAttribute("grille");
            String s=g.genererCodeHTMLPourAffichage();
            out.println(s);
        %>
        </table>
        <form action="demineur">
            Ligne : <input type="number" name="ligne" id="ligne"><br>
            Colonne : <input type="number" name="colonne" id="colonne"><br>
            <input type="radio" name="action" value="reveler" checked>Révéler une case<br>
            <input type="radio" name="action" value="marquer">Marquer une mine<br>
            <input type="submit" value="Submit">
        </form>

    </body>
</html>
