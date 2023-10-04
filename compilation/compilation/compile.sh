#!/bin/bash
yacc test.yacc
yacc -d test.yacc
lex test.lex
gcc -Wall -c lex.yy.c
gcc -Wall y.tab.c lex.yy.o -lfl -o analyser
