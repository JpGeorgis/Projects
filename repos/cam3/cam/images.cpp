#include "images.h"
using namespace std;
Images::Images()
{
	string s[16] = {"../images/1.jpg", "../images/2.jpg",
					"../images/3.jpg", "../images/4.jpg",
					"../images/5.jpg", "../images/6.jpg",
					"../images/7.jpg", "../images/8.jpg",
					"../images/9.jpg", "../images/10.jpg",
					"../images/11.jpg", "../images/12.jpg",
					"../images/13.jpg", "../images/14.jpg",
					"../images/15.jpg", "../images/16.jpg"
	};
	for (int i = 0; i < 16; i++) {
		d_s[i] = s[i];
	}
}

string Images::s(int i)
{
	return d_s[i];
}
