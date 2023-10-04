%{
  #include <stdio.h>
  #include <stdlib.h>
  #include <string.h>
  #include <errno.h>

  #define OUTPUT_FILE "output.html"
  void yyerror(char *s);
  extern FILE *filestream;
%}

%token TXT
%token BALTIT
%token FINTIT
%token LIGVID
%token DEBLIST
%token ITEMLIST
%token FINLIST
%token ETOILE
%start fichier

%%
fichier: el {}
         |el fichier {};

el : TXT
     | LIGVID
     | titre
     | liste
     | texte_formatte;


titre : BALTIT TXT FINTIT;
liste : DEBLIST liste_textes suite_liste ;
suite_liste : ITEMLIST liste_textes suite_liste
             | FINLIST ;

texte_formatte : italique
                 | gras
                 | grasitalique ;

italique : ETOILE TXT ETOILE;

gras : ETOILE ETOILE TXT ETOILE ETOILE ;

grasitalique : ETOILE ETOILE ETOILE TXT ETOILE ETOILE ETOILE ;

liste_textes : TXT
             | texte_formatte
             | TXT liste_textes
             | texte_formatte liste_textes;


%%
int main()
{
  filestream = fopen(OUTPUT_FILE,"w"); // global variable
  if(filestream == NULL){
    fprintf(stderr, "%s\n", strerror(errno));
    return errno;
  }
  yyparse();
  print_debug();
  fclose(filestream);
  return 0;
}

void yyerror(char *s){
  fprintf(stderr, "%s\n", s);
}
