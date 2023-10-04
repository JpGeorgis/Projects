#include "balloon.h"


//constructeurs
Balloon::Balloon()
{
	d_p.x = 0;
	d_p.y = 0;
	d_r = 0;
	d_v = 0;
	d_c = Scalar(0, 0, 0);
}

Balloon::Balloon(Rect w)
{	
	d_c = Scalar(rand()%255, rand() % 255, rand() % 255);
	d_r = rand() % 30 + 20;
	d_p.x = rand() % (w.width - d_r)+d_r;
	d_v = rand() % 8 + 5;
	d_p.y = w.height+d_r;
	
	
}

void Balloon::Ym() {
	d_p.y -= d_v;
}
