Introduction
	Ce répertoire contient le code source associé au rapport sur la classification des signaux ECG à l'aide de réseaux de neurones. Le but de ce projet est d'explorer l'utilisation de modèles de réseaux de neurones récurrents (RNN) et de réseaux de neurones convolutionnels (CNN) pour détecter automatiquement les anomalies cardiaques à partir des signaux ECG.

Structure du Répertoire

Source :
	Le code source est disponible dans le dossier "Sources" et également dans la partie "Sources" du rapport via des liens. Vous y trouverez les scripts Python utilisés pour mettre en œuvre les modèles de classification, entraîner les réseaux de neurones, évaluer les performances, et générer des graphiques pertinents.

Jeu de Données ECG200 :
	Les données ECG utilisées dans ce projet proviennent du jeu de données ECG200. Les liens vers les ensembles d'entraînement et de test sont spécifiés dans le code et le rapport pour permettre la reproductibilité.

Environnement Colab (si nécessaire) :
	En cas de difficultés à exécuter le code localement, des liens vers des notebooks Google Colab sont fournis. Vous pouvez exécuter le code directement dans l'environnement Colab sans configuration supplémentaire.
Instructions d'Utilisation

Exécution Locale :
	Si vous souhaitez exécuter le code localement, assurez-vous d'avoir les dépendances nécessaires installées. Le code utilise TensorFlow, Keras, NumPy, et d'autres bibliothèques couramment utilisées en apprentissage automatique.

Notebooks Google Colab :
	Pour une exécution facile, vous pouvez utiliser les liens vers les notebooks Colab fournis. Cela vous permettra d'exécuter le code dans un environnement cloud sans aucune installation locale.
Dossiers de Sauvegarde :

Les modèles entraînés du CNN sont sauvegardés dans le répertoire. Les meilleurs modèles basés sur la perte de validation sont stockés sous le nom "best-model.h5".
