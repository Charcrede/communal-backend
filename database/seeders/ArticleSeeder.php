<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\Media;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        // Auteur et rubriques (à adapter selon ta BDD)
        $userId = '721b4685-ae04-425b-b7be-af8e39f1667f';
        $rubric1 = '8bd194ca-bbe2-47af-91ba-81effbe68a59';
        $rubric2 = '62cfcb0b-15fe-482d-a5b7-4e32225625b8';
        $rubric3 = 'adc87a8f-f977-4c7c-9852-6056586a26a4';
        $rubric4 = 'd3a9d13a-c343-4c4e-87c2-ae6c3c7c2551';

        // Liste des médias disponibles
        $mediaFiles = [
            's2Da4S1iRhr35t9lVxOIXvcGHUE0r3weJOEFx2jx.mp4',
            'iR0YEYXTiqeim0QTHFsvcpTIJXFuWJFe2OgOySsE.png',
            'awEBdOgnKnzDjQklDQLKmSw4nAn1xdK3kroZLzR0.jpg',
            '5lrwfHiKe6IuxAqnP32LYdrBSXbFAstxRyNLbO7r.jpg',
            '05fEubKfLRCKQXULan0dEXNtewVihDPAKQv2gcPP.avif',
        ];

        // Tableau de titres réalistes
        $titles = [
            'Les bienfaits de la méditation quotidienne',
            'Comment cuisiner un plat marocain traditionnel',
            'Les tendances mode 2025',
            'Astuces pour économiser sur ses factures',
            'Top 10 des destinations de voyage en été',
            'L’importance de l’hydratation pour la santé',
            'Le guide complet du potager bio',
            'Comment bien débuter en photographie',
            'Les erreurs à éviter lors d’un entretien d’embauche',
            'Recette facile de pain maison',
            'Les bases du développement web',
            'Comment organiser un événement réussi',
            'Les secrets d’un sommeil réparateur',
            'Initiation au yoga pour débutants',
            'La transition vers l’énergie solaire',
            'Les aliments à privilégier pour la mémoire',
            'Comment créer un budget mensuel efficace',
            'Idées de décoration intérieure modernes',
            'Les avantages du télétravail',
            'Le guide du café parfait'
        ];

        foreach ($titles as $i => $title) {
            $article = Article::create([
                'title' => $title,
                'content' => 'Voici un article détaillé sur : ' . strtolower($title) . '. Il contient des astuces, conseils et informations utiles pour le lecteur.',
                'rubric_id' => $i % 4 == 0 ? $rubric1 : ($i % 4 == 1 ? $rubric2 : ($i % 4 == 2 ? $rubric3 : $rubric4)),
                'created_by' => $userId,
            ]);

            // Choix aléatoire d'un média
            $fileName = $mediaFiles[array_rand($mediaFiles)];
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);

            // Déterminer le type en fonction de l'extension
            if (in_array(strtolower($extension), ['mp4', 'avi', 'mov', 'wmv'])) {
                $type = 'video';
            } elseif (in_array(strtolower($extension), ['jpeg', 'jpg', 'png', 'gif', 'svg', 'avif'])) {
                $type = 'image';
            } else {
                $type = 'unknown';
            }

            // Construire l'url complète relative à storage
            $url = "/storage/articles/" . $fileName;

            Media::create([
                'id' => Str::uuid(),
                'article_id' => $article->id,
                'title' => "Media illustrant " . strtolower($title),
                'description' => "Un média représentatif du sujet : $title",
                'type' => $type,
                'url' => $url,
                'filename' => $fileName,
                'size' => rand(50000, 500000),
            ]);
        }
    }
}
