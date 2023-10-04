#ifndef ANTENNE_H
#define ANTENNE_H
#include "Point.h"
#include <string>
class Antenne
{
public:
	// constructeurs
	Antenne();
	Antenne(double x,double y, double f, double dE);
    Antenne(std::string n, double x,double y, double dE);
    Antenne(std::string n,double x, double y);
	int getId();
	void setId(int);
	Point getPos();
    void setPos(double,double);
	double getF();
	void setF(double);
	double getdE();
	void setdE(double);
	~Antenne();

    const std::string &getNom() const;
    void setNom(const std::string &newNom);

private:

	int id;
	Point pos;
	double f;
	double dE;
    std::string nom;
};
#endif

