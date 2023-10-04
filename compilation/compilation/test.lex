%{
  #include <stdio.h>
  #include <stdlib.h>
  #include <string.h>
  #include <errno.h>
  #include <stdbool.h>
  #include "y.tab.h"

  #define SIZE_CH 4096

  typedef struct{
    size_t position;
    size_t longeur;
    char *categorie;
    size_t nv_titre;
  } symbol;

  FILE *filestream = NULL;

  size_t backup_pos=1;

  symbol sym_tab[200] = {0};
  size_t size = 0;
  size_t titre_off=0;
  size_t titre_off_back = 0;
  size_t count_star = 0;

  bool is_item = false;
  bool text_switch = false;

  size_t rtrim(char *, size_t );
  char *clean_backslash(char*);
  extern int yylval;
  char CH[SIZE_CH] = {0};
%}

ENDLINE [\n|\r\n]
CHAR ("\\*"|[^#\*_\n\r])
STRING ([^\t #\*_\n\r]|"\\*"){CHAR}*

%start TITRE
%start LIST


%%
<TITRE>{ENDLINE} {
  //printf("Fin de titre\n");
  //printf("</h%d>\n",titre_off_back);
  fprintf(filestream, "</h%d>\n", titre_off_back);
  BEGIN INITIAL;
  return FINTIT;
}

<LIST>\n[ ]*\n+ {
  add_text(sym_tab, size, yytext, yyleng, "fin de liste", titre_off);
  //printf("</li>\n</ul>\n");
  fprintf(filestream, "</li>\n</ul>\n");
  BEGIN INITIAL;
  return FINLIST;
}

{ENDLINE} {}

{STRING} {
  strncat(CH,yytext,SIZE_CH);
  char buff[100] = {0};
  if(is_item){
    strcpy(buff, "Item");
    is_item = false;
  }else if(titre_off>0){
    strcpy(buff, "Titre");
  }else{
    strcpy(buff, "Normal");
  }

  if(count_star > 0)text_switch = true;

  char *without_backslash = clean_backslash(yytext);

  if(count_star == 0){
    //printf("%s", without_backslash);
    fprintf(filestream, "%s", without_backslash);
  }else if(count_star == 1){
    //printf("<i>%s</i>", without_backslash);
    fprintf(filestream, "<i>%s</i>", without_backslash);
    strcat(buff, " en italique");
  }else if(count_star == 2){
    //printf("<b>%s</b>", without_backslash);
    fprintf(filestream, "<b>%s</b>", without_backslash);
    strcat(buff, " en gras");
  }else if(count_star == 3){
    //printf("<b><i>%s</i></b>", without_backslash);
    fprintf(filestream, "<b><i>%s</i></b>", without_backslash);
    strcat(buff, " en gras italique");
  }
  add_text(sym_tab, size, yytext, yyleng, buff, titre_off);
  titre_off = 0;
  free(without_backslash);
  return TXT;
}


<INITIAL>^\*[ ]+ {
 //printf("<ul>\n<li>");
 fprintf(filestream, "<ul>\n<li>");
 is_item = true;
 BEGIN LIST;
 return DEBLIST;
}

<LIST>^\*[ ]+ {
 is_item = true;

 //printf("</li>\n<li>");
 fprintf(filestream, "</li>\n<li>");

 return ITEMLIST;
}

"*" {
  if(text_switch == true )count_star--;
  else count_star ++;
  if(count_star == 0) text_switch = false;
  //printf("Etoile\n");
  // add_el(sym_tab+size, 0,0, "Etoile", "", 0);
  // size++;
  return ETOILE;
}


<INITIAL>{ENDLINE}([ ]*{ENDLINE})+ {
  //puts("");
  fprintf(filestream, "\n");
  return LIGVID;
}

<INITIAL>{ENDLINE} {
  //printf("<br/>\n");
  fprintf(filestream, "<br/>\n");
}

<INITIAL>^[ ]{0,3}#{1,6}[ ]+ {
  char* tmp = strchr(yytext, '#');
  yyleng -= (tmp-yytext);
  yytext = tmp;
  yyleng = rtrim(yytext,(size_t)yyleng);

  titre_off = yyleng;
  titre_off_back = yyleng;
  //printf("<h%d>",titre_off_back);
  fprintf(filestream, "<h%d>", titre_off_back);
  BEGIN TITRE;
  return BALTIT;
}

[ |\t]+ {}

. {
  fprintf(filestream, "Erreur lexicale : Caractère %c non autorisé\n",yytext[0]);
  printf("Erreur lexicale : Caractère %c non autorisé\n",yytext[0]);
  fclose(filestream);
  exit(0xdeadbeef);
}

%%

int yywrap()
{
  return 1;
}

int counthashtag(char *curr){
  int count = 0;
  for(size_t i=0;i<strlen(curr);i++){
    if(curr[i] == '#') count++;
  }
  return count;
}

size_t rtrim(char *in, size_t size){
  size_t i=1;
  while(in[size-i] == ' ' ){
    in[size-i] = '\0';
    i++;
  }
  return size-i+1;
}

void add_el(symbol *s, size_t position, size_t longeur, char *categorie, size_t nv){
  s->position = position;
  s->longeur = longeur;
  s->categorie = strdup(categorie);
  s->nv_titre = nv;
}

void add_text(symbol *s,size_t index, char *text, int len,char *cat,size_t nv){
  symbol *symbol_cur = s+index;
  add_el(symbol_cur, backup_pos, len, cat, nv);
  size++;
  backup_pos = symbol_cur->position + symbol_cur->longeur;
  return;
}



void print_debug(){
  symbol *cur = NULL;
  size_t c=0;
  puts("\n");
  printf("│%10s\t│%10s\t│%25s│%15s│\n", "char offset", "longeur", "type", "niveau titre");
  while (c<size) {
    cur = sym_tab+c;
    printf("│%10lu\t│%10lu\t│%25s│%15lu│\n", cur->position, cur->longeur, cur->categorie, cur->nv_titre);
    c+=1;
  }
  printf("CH:\n%s\n",CH);
}

char *clean_backslash(char *content){
  size_t len = strlen(content);
  char* ret = malloc(sizeof(char) * (len+1));
  size_t c = 0;
  if(ret == NULL){
    fprintf(stderr, "%s\n", strerror(errno));
    exit(errno);
  }
  memset(ret, 0x0,len+1);
  for(size_t i=0;i<len;i++){
    if(i<len-1 && content[i] == '\\' && content[i+1] == '*'){
      ret[c] = '*';
      i++;
    }else{
      ret[c] = content[i];
    }
    c++;
  }
  return ret;
}
