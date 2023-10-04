if [ -d docs/ ]; then
  rm -rd docs/
fi
php scripts/phpDocumentor.phar -d . -t docs --ignore scripts/
rm -rd .phpdoc/
open docs/index.html
