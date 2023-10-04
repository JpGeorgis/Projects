        theme = () => {
            if (document.querySelector("link").getAttribute('href')==="{% assets(css/bleu.css) %})" {

                let links = document.querySelector("link"); 
                // selon celui que tu veux récupérer je sais pas comment marche ton truc
                links.setAttribute('href', "{% assets(css/gris.css) %}"); 
                // lien vers ton autre theme 



            }
            else{

                let links = document.querySelector("link"); 
                // selon celui que tu veux récupérer je sais pas comment marche ton truc
                links.setAttribute('href', "{% assets(css/bleu.css) %}"); 
                // lien vers ton autre theme 
            }

        }