#ifndef SUPPFR_H
#define SUPPFR_H

#include <QDialog>

namespace Ui {
class Suppfr;
}

class Suppfr : public QDialog
{
    Q_OBJECT

public:
    explicit Suppfr(QWidget *parent = nullptr);
    ~Suppfr();

private:
    Ui::Suppfr *ui;
};

#endif // SUPPFR_H
