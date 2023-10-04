<%-- 
    Document   : jsp
    Created on : 19 oct. 2022, 16:38:48
    Author     : joris
--%>

<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Tirage de numéros</title>
</head>

<body>

            <%@ page import="java.util.List, java.util.ArrayList" %>
<%
// On recupere l’attribut dans la requete
List<String> list = (List<String>) request.getAttribute("tirage");
// On affiche le contenu du tableau recupere dans le requete
if (list != null) 
{
	for(int i = 0; i < list.size(); i++) { 
            out.println(list.get(i));
        }
}
%>

</body>
</html>

