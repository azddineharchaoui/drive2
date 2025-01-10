<?php
require_once('Classes/db.php');

if(isset($_POST['query'])) {
    try {
        $pdo = DatabaseConnection::getInstance()->getConnection();
        $searchQuery = '%' . $_POST['query'] . '%';
        
        $query = "SELECT a.id_article, a.titre, a.contenu, a.date_creation, a.image_url, 
                  u.nom, u.prenom, t.nom AS theme,
                  GROUP_CONCAT(tg.nom) as tags
                  FROM Articles a
                  JOIN Utilisateurs u ON a.id_utilisateur = u.id_utilisateur
                  JOIN Themes t ON a.id_theme = t.id_theme
                  LEFT JOIN Article_Tag at ON a.id_article = at.id_article
                  LEFT JOIN Tags tg ON at.id_tag = tg.id_tag
                  WHERE a.statut = 'Accepté' 
                  AND (a.titre LIKE :search 
                  OR a.contenu LIKE :search 
                  OR t.nom LIKE :search
                  OR tg.nom LIKE :search)
                  GROUP BY a.id_article 
                  ORDER BY a.date_creation DESC
                  LIMIT 6";
        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':search', $searchQuery, PDO::PARAM_STR);
        $stmt->execute();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if(count($articles) > 0) {
            foreach($articles as $article) {
                echo '<div class="bg-white rounded-lg shadow-md overflow-hidden">';
                // Image
                if (!empty($article['image_url']) && file_exists($article['image_url'])) {
                    echo '<img src="'.htmlspecialchars($article['image_url']).'" 
                          alt="'.htmlspecialchars($article['titre']).'" 
                          class="w-full h-48 object-cover">';
                } else {
                    echo '<div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                          </div>';
                }
                echo '<div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800 hover:text-blue-500">
                            <a href="article.php?id='.htmlspecialchars($article['id_article']).'">
                                '.htmlspecialchars($article['titre']).'
                            </a>
                        </h3>
                        <p class="text-sm text-gray-600 mt-2">
                            By <span class="font-medium">'.htmlspecialchars($article['nom'].' '.$article['prenom']).'</span>
                            on <span class="text-gray-500">'.htmlspecialchars($article['date_creation']).'</span>
                            | Theme: <span class="text-blue-500">'.htmlspecialchars($article['theme']).'</span>
                        </p>
                        <p class="text-gray-700 mt-4">
                            '.substr(htmlspecialchars($article['contenu']), 0, 100).'...
                        </p>
                        <div class="mt-4">
                            <a href="article.php?id='.htmlspecialchars($article['id_article']).'" 
                               class="text-blue-500 hover:underline font-medium">Read more →</a>
                        </div>
                      </div>';
                echo '</div>';
            }
        } else {
            echo '<div class="col-span-full text-center py-8">
                    <p class="text-gray-600">Aucun article trouvé pour votre recherche.</p>
                  </div>';
        }
    } catch (Exception $e) {
        echo '<div class="text-red-500">Une erreur est survenue lors de la recherche.</div>';
    }
}
?>