/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package td3;

import static spark.Spark.get;
import static spark.Spark.post;

/**
 *
 * @author e1901451
 */
public class TD3
{
    static boolean etat=false;
    
    public static void main(String[] args)
    {
        // Pour traiter la requete HTTP GET http://localhost:4567/
        get("/", (request, response) -> "Hello World");
        // Pour traiter la requete HTTP GET http://localhost:4567/hello
        get("/hello", (request, response) -> {
            return "Hello Name";
        });
        // Pour traiter la requete HTTP POST http://localhost:4567/hello?foo=5
        post("/hello", (request, response) -> {
            String val=request.queryParams("foo");
            return "Hello: " + val;
        });
        
        get("/thermometre", (request, response) -> {
            return "Temperature :";
        });
        get("/radiateur", (request, response) -> {
            String res;
            if(etat)
                res= "{ \"Etat\" : \"ON\" }";
            else
                res= "{ \"Etat\" : \"OFF\" }";
            return res;
        });
        post("/radiateur", (request, response) -> {
            String val=request.queryParams("etat");
            if(val.equals("1"))
            {
                etat=true;
                return "Etat : ON ";
            }
            else if(val.equals("0"))
            {
                etat=false;
                return "Etat : OFF ";
            }
            else
                return "Valeure saisie invalide";
        });
    }
}
