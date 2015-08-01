<?php

use Faker\Factory as Faker;
use Motibu\Models\Skill;
use Motibu\Models\SkillCategory;


class SkillsTableSeeder extends Seeder{

    private $categories = [
        'en' => [
            '21151' => 'Agriculture, forestry and fishery',
            '14376' => 'Architecture and building',
            '16747' => 'Arts',
            '17953' => 'Business and administration',
            '15230' => 'Computing',
            '17860' => 'Education',
            '14277' => 'Electrical engineering',
            '14509' => 'Environmental protection',
            '14271' => 'Health',
            '17970' => 'Humanities',
            '17414' => 'Journalism and information',
            '14324' => 'Law',
            '18281' => 'Life sciences',
            '17160' => 'Manufacturing and processing materials',
            '14759' => 'Manufacturing and processing of food',
            '18432' => 'Manufacturing and processing of textiles, clothes, footwear, leather',
            '14546' => 'Mathematics and statistics',
            '14526' => 'Metal processing and mechanical engineering',
            '18132' => 'Personal services',
            '20673' => 'Physical sciences',
            '15103' => 'Security services',
            '17979' => 'Social and behavioural science',
            '16732' => 'Social services',
            '14888' => 'Transport services',
            '14440' => 'Veterinary',
        ],
        'de' => [
            '14376' => 'Architektur und Bauwesen',
            '17860' => 'Bildung',
            '15230' => 'EDV',
            '14277' => 'Elektrotechnik',
            '17970' => 'Geisteswissenschaften',
            '14271' => 'Gesundheit',
            '14759' => 'Herstellung und Verarbeitung von Lebensmitteln',
            '17160' => 'Herstellung und Verarbeitung von Materialien',
            '18432' => 'Herstellung und Verarbeitung von Textilien, Bekleidung, Schuhen, Leder',
            '17414' => 'Journalistik und Information',
            '16747' => 'Kunst',
            '21151' => 'Landwirtschaft, Forstwirtschaft und Fischerei',
            '18281' => 'Lebenswissenschaften',
            '14546' => 'Mathematik und Statistik',
            '14526' => 'Metallverarbeitung und Maschinenbau',
            '18132' => 'Persönliche Dienstleistungen',
            '20673' => 'Physikalische Wissenschaften',
            '14324' => 'Rechtswissenschaften',
            '15103' => 'Sicherheitsdienste',
            '16732' => 'Sozialdienste',
            '17979' => 'Sozial- und Verhaltenswissenschaft',
            '14509' => 'Umweltschutz',
            '17953' => 'Unternehmen und Verwaltung',
            '14888' => 'Verkehrsdienstleistungen',
            '14440' => 'Veterinär/in',
        ],
        'fr' => [
            '21151' => 'Agriculture, sylviculture et pêche',
            '14376' => 'Architecture et bâtiment',
            '16747' => 'Arts',
            '17953' => 'Commerce et administration',
            '14324' => 'Droit',
            '17860' => 'Éducation',
            '14277' => 'Électrotechnique',
            '17160' => 'Industries de transformation et de traitement des matériaux',
            '14759' => 'Industries de transformation et de traitement des produits alimentaires',
            '18432' => 'Industries de transformation et de traitement des textiles, vêtements, chaussures et du cuir',
            '17414' => 'Journalisme et information',
            '17970' => 'Lettres',
            '14546' => 'Mathématiques et statistiques',
            '14509' => 'Protection de l’environnement',
            '14271' => 'Santé',
            '18281' => 'Sciences de la vie',
            '15230' => 'Sciences informatiques',
            '20673' => 'Sciences physiques',
            '17979' => 'Sciences sociales et du comportement',
            '14440' => 'Sciences vétérinaires',
            '18132' => 'Services aux particuliers',
            '15103' => 'Services de sécurité',
            '14888' => 'Services de transport',
            '16732' => 'Services sociaux',
            '14526' => 'Transformation des métaux et constructions mécaniques',
        ],
        'it' => [
            '21151' => 'Agricoltura, silvicoltura e pesca',
            '14376' => 'Architettura ed edilizia',
            '16747' => 'Arti',
            '17953' => 'Economia e amministrazione',
            '14759' => 'Fabbricazione e lavorazione di alimenti',
            '17160' => 'Fabbricazione e lavorazione di materiali',
            '18432' => 'Fabbricazione e lavorazione di tessuti, abbigliamento, calzature, cuoio',
            '17414' => 'Giornalismo e informazione',
            '15230' => 'Informatica',
            '14277' => 'Ingegneria elettrotecnica',
            '17860' => 'Istruzione',
            '14526' => 'Lavorazione dei metalli e ingegneria meccanica',
            '14324' => 'Legge',
            '14546' => 'Matematica e statistica',
            '14509' => 'Protezione dell’ambiente',
            '14271' => 'Salute',
            '18281' => 'Scienze della vita',
            '20673' => 'Scienze fisiche',
            '17979' => 'Scienze sociali e comportamentali',
            '18132' => 'Servizi alla persona',
            '15103' => 'Servizi di sicurezza',
            '14888' => 'Servizi di trasporto',
            '16732' => 'Servizi sociali',
            '17970' => 'Studi umanistici',
            '14440' => 'Veterinaria',
        ]
     ];

     public function run() {
        foreach($this->categories['en'] as $id => $name) {
            $skillInsert = [];
            $cat = SkillCategory::create([
              'name' => $this->categories['en'][$id],
              'name_de' => $this->categories['de'][$id],
              'name_fr' => $this->categories['fr'][$id],
              'name_it' => $this->categories['it'][$id],
              'esco_id' => $id
            ]);

            $escoJsonEn = json_decode(\File::get('./app/dox/skills/'.$id.'.json'),true);
            $escoJsonDe = json_decode(\File::get('./app/dox/skills/'.$id.'_de.json'),true);
            $escoJsonFr = json_decode(\File::get('./app/dox/skills/'.$id.'_fr.json'),true);
            $escoJsonIt = json_decode(\File::get('./app/dox/skills/'.$id.'_it.json'),true);

            $index = 0;
            foreach($escoJsonEn as $skill) {
                $skillInsert[] = [
                    'name' => $skill['label'],
                    'name_de' => $escoJsonDe[$index]['label'],
                    'name_fr' => $escoJsonFr[$index]['label'],
                    'name_it' => $escoJsonIt[$index]['label'],
                    'language' => $skill['language'],
                    'esco_uri' => $skill['labelUri'],
                    'skill_category_id' => $cat->id
                ];
                $index++;
            }
            Skill::insert($skillInsert);
        }
     }
}