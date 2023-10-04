#ifndef SOMMET_H
#define SOMMET_H
class Sommet
{
public:
	// constructeurs
	Sommet();
	Sommet(int id, int col, int degre);

	// accesseurs
	int getID(){return ID;}
	int getColor() { return color; }
	int getDeg() { return deg; }
	void setID(int id);
	void setColor(int c);
	void setDeg(int d);
	void incrDeg();

private:

	int ID;
	int color;
	int deg;
};
#endif

