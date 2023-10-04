#ifndef POINT_H
#define POINT_H
class Point
{
public:
	// constructeurs
	Point() : d_x{ 0 }, d_y{ 0 } {}
	Point(int x, int y) : d_x{ x }, d_y{ y } {}
	// accesseurs
	int x() const { return d_x; }
	int y() const { return d_y; }

private:

	int d_x, d_y;
};
#endif

