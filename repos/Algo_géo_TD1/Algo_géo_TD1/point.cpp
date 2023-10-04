#include "point.h"

// renvoie 1, -1 ou 0 selon que le point auquel la m�thode est appliqu�e est
// � gauche de, � droite de, ou sur la droite (ab) orient�e de a vers b.
int Point::aGauche(const Point &a, const Point &b) const
{
	int tmp = ((b.x() - a.x()) * (d_y - a.y()) - (d_x - a.x()) * (b.y() - a.y()));
	if (tmp > 0)
		return 1;
	else if (tmp < 0)
		return -1;
	else
		return 0;
}
