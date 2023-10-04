<?php

namespace App;

/**
 * Classe permettant de lire un fichier .env.
 * Il injecte automatiquement les variables dans el $_ENV.
 */
class DotEnvReader
{
    /**
     * @var string Chemin du fichier .env à lir
     */
    protected string $path;

    /**
     * Constructeur de la classe, il permet de définir le chemin vers le fichier à lire
     * @param string $path Chemin vers le fichier
     */
    public function __construct(string $path)
    {
        if(!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('%s does not exist', $path));
        }
        $this->path = $path;
    }

    /**
     * Démarre la lecture du fichier .env
     * @return void
     */
    public function load(): void
    {
        if (!is_readable($this->path)) { // If isn't readable
            throw new \RuntimeException(sprintf('%s file is not readable', $this->path)); // raise error
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // get lines
        foreach ($lines as $line) { // For each line

            if (str_starts_with(trim($line), '#')) { // if start with # -> Comment
                continue; // ignore line
            }

            list($name, $value) = explode('=', $line, 2); // get $name=$value
            $name = trim($name); // name without space
            $value = trim($value); // value without space

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) { //if key not already defined
                putenv(sprintf('%s=%s', $name, $value)); // put env var
                $_ENV[$name] = $value; // add to $_ENV
                $_SERVER[$name] = $value; // add to $_SERVER
            }
        }
    }
}