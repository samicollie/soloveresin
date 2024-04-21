<?php
$envFilePath = __DIR__ . '/.env';

// Vérifiez si le fichier .env existe
if (file_exists($envFilePath)) {
    // Lire le contenu du fichier .env
    $envContent = file_get_contents($envFilePath);
    
    // Analyser le contenu pour extraire les variables d'environnement
    $envVariables = explode(PHP_EOL, $envContent);
    foreach ($envVariables as $envVariable) {
        // Ignorer les lignes vides et celles commençant par #
        if (!empty($envVariable) && strpos($envVariable, '#') !== 0) {
            // Diviser la ligne en nom de variable et valeur
            $parts = explode('=', $envVariable, 2);
            // Supprimer les espaces inutiles
            if(count($parts) === 2 && isset($parts[0]) && isset($parts[1])){
                $key = trim($parts[0]);
                $value = trim($parts[1]);
                // Ajouter la variable d'environnement à la superglobale $_ENV
                $_ENV[$key] = $value;
            }
            
        }
    }
}