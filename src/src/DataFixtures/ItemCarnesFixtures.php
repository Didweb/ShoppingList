<?php
namespace App\DataFixtures;

use App\Entity\Item;
use App\ValueObject\Slug;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ItemCarnesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $items = [
            // === Carnes ===
            [
                'name' => 'Pollo entero',
                'errors' => ['Poyo entero', 'Pollo enteroo', 'Polo entero'],
            ],
            [
                'name' => 'Pechuga de pollo',
                'errors' => ['Pechugade pollo', 'Pechuga pollo', 'Pechhuga de pollo'],
            ],
            [
                'name' => 'Muslo de pollo',
                'errors' => ['Muslo pollo', 'Muslo de poyo', 'Muslode pollo'],
            ],
            [
                'name' => 'Alitas de pollo',
                'errors' => ['Aletas de pollo', 'Alitas pollo', 'Allitas de pollo'],
            ],
            [
                'name' => 'Contramuslo de pollo',
                'errors' => ['Contramuslo pollo', 'Contramuslode pollo', 'Contramulo de pollo'],
            ],
            [
                'name' => 'Carne picada de pollo',
                'errors' => ['Carne pica de pollo', 'Carne picadade pollo', 'Carn picada de pollo'],
            ],
            [
                'name' => 'Pollo campero',
                'errors' => ['Poyo campero', 'Pollo camppero', 'Pollo campro'],
            ],
            [
                'name' => 'Pollo corral',
                'errors' => ['Poyo corral', 'Pollo corrall', 'Pollo corrall'],
            ],
            [
                'name' => 'Pavo fileteado',
                'errors' => ['Pabo fileteado', 'Pavo fileteo', 'Pavofileteado'],
            ],
            [
                'name' => 'Carne picada de pavo',
                'errors' => ['Carne picadade pavo', 'Carne picad de pavo', 'Carn picada pavo'],
            ],
            [
                'name' => 'Pavo entero',
                'errors' => ['Pabo entero', 'Pavo enteroo', 'Pavo enttro'],
            ],
            [
                'name' => 'Conejo entero',
                'errors' => ['Conejo enteroo', 'Conejoo entero', 'Conego entero'],
            ],
            [
                'name' => 'Conejo troceado',
                'errors' => ['Conejo trozeado', 'Conej troceado', 'Conejo trociado'],
            ],
            [
                'name' => 'Costillas de cerdo',
                'errors' => ['Costiyas de cerdo', 'Costillas cerdo', 'Costilllas de cerdo'],
            ],
            [
                'name' => 'Chuletas de cerdo',
                'errors' => ['Chuletas cerdo', 'Chuletas d cerdo', 'Chuletaas de cerdo'],
            ],
            [
                'name' => 'Lomo de cerdo',
                'errors' => ['Lomode cerdo', 'Lomo cerdo', 'Lom de cerdo'],
            ],
            [
                'name' => 'Solomillo de cerdo',
                'errors' => ['Solomilo de cerdo', 'Solomillode cerdo', 'Solomillo cerdo'],
            ],
            [
                'name' => 'Secreto de cerdo',
                'errors' => ['Secreto cerdo', 'Secretoo de cerdo', 'Secretode cerdo'],
            ],
            [
                'name' => 'Panceta',
                'errors' => ['Pancceta', 'Pancetaa', 'Panzeta'],
            ],
            [
                'name' => 'Codillo de cerdo',
                'errors' => ['Codillode cerdo', 'Codillo cerdo', 'Codillo d cerdo'],
            ],
            [
                'name' => 'Morcillo de ternera',
                'errors' => ['Morcillode ternera', 'Morcillo ternra', 'Morciilo de ternera'],
            ],
            [
                'name' => 'Entrecot de ternera',
                'errors' => ['Entrecot ternera', 'Entrecott de ternera', 'Entrecote de ternera'],
            ],
            [
                'name' => 'Chuletón de ternera',
                'errors' => ['Chuleton de ternera', 'Chuleton ternra', 'Chuletond de ternera'],
            ],
            [
                'name' => 'Solomillo de ternera',
                'errors' => ['Solomilo de ternera', 'Solomilllo de ternera', 'Solomillo ternra'],
            ],
            [
                'name' => 'Filete de ternera',
                'errors' => ['Filete ternera', 'Filette de ternera', 'Filet de ternera'],
            ],
            [
                'name' => 'Carne picada de ternera',
                'errors' => ['Carne picada ternera', 'Carn picada de ternera', 'Carne picadade ternera'],
            ],
            [
                'name' => 'Carne picada mixta',
                'errors' => ['Carne picada mixta', 'Carn picada mixta', 'Carne picadamixta'],
            ],
            [
                'name' => 'Hamburguesa de ternera',
                'errors' => ['Hamburguesa ternera', 'Hamburgues de ternera', 'Hamburgesa de ternera'],
            ],
            [
                'name' => 'Hamburguesa de pollo',
                'errors' => ['Hamburguesa pollo', 'Hamburgues de pollo', 'Hamburgesa de pollo'],
            ],
            [
                'name' => 'Albóndigas de carne',
                'errors' => ['Albondigas de carne', 'Albóndigas carne', 'Albóndigas decarne'],
            ],
            [
                'name' => 'Pinchos morunos',
                'errors' => ['Pinchos morunnos', 'Pinchos morunos', 'Pinchos morruno'],
            ],
            [
                'name' => 'Longaniza fresca',
                'errors' => ['Longaniza frsca', 'Longganiza fresca', 'Longaniz fresca'],
            ],
            [
                'name' => 'Chorizo fresco',
                'errors' => ['Choriso fresco', 'Chorizo frsco', 'Chorizo frescco'],
            ],
            [
                'name' => 'Salchichas frescas',
                'errors' => ['Salchichas frsacas', 'Salchichas frescca', 'Salchicha fresca'],
            ],
            [
                'name' => 'Butifarra',
                'errors' => ['Butifara', 'Butifarara', 'Butiffarra'],
            ],
            [
                'name' => 'Callos',
                'errors' => ['Calloss', 'Callosss', 'Calloss'],
            ],
            [
                'name' => 'Morcilla',
                'errors' => ['Morciila', 'Morcila', 'Morrilla'],
            ],
            [
                'name' => 'Hígado de cerdo',
                'errors' => ['Higado de cerdo', 'Higad de cerdo', 'Higadode cerdo'],
            ],
            [
                'name' => 'Hígado de ternera',
                'errors' => ['Higado de ternera', 'Higad de ternera', 'Higadode ternera'],
            ],
            [
                'name' => 'Riñones de cordero',
                'errors' => ['Riñones cordero', 'Riñones decordero', 'Rinones de cordero'],
            ],
            [
                'name' => 'Cordero lechal',
                'errors' => ['Cordero lechal', 'Cordero lecal', 'Cordero lechal'],
            ],
            [
                'name' => 'Pierna de cordero',
                'errors' => ['Pierna decordero', 'Pierna cordero', 'Pierna de corderp'],
            ],
            [
                'name' => 'Costillar de cordero',
                'errors' => ['Costillar cordero', 'Costillar decordero', 'Costilla de cordero'],
            ],
            [
                'name' => 'Paletilla de cordero',
                'errors' => ['Paletilla cordero', 'Paletillade cordero', 'Paletila de cordero'],
            ],
            [
                'name' => 'Chuletillas de cordero',
                'errors' => ['Chuletillas cordero', 'Chuletillas decordero', 'Chuletillass de cordero'],
            ],
            [
                'name' => 'Cabrito',
                'errors' => ['Ccabrito', 'Cabritto', 'Cabrido'],
            ],
            [
                'name' => 'Jabalí',
                'errors' => ['Jabali', 'Jabalii', 'Jaballi'],
            ],
            [
                'name' => 'Pato',
                'errors' => ['Patto', 'Patoo', 'Pattto'],
            ],
            [
                'name' => 'Foie',
                'errors' => ['Foi', 'Foee', 'Foe'],
            ],
            [
                'name' => 'Codorniz',
                'errors' => ['Codornis', 'Codornizz', 'Codornz'],
            ],
            [
                'name' => 'Perdiz',
                'errors' => ['Perdizz', 'Perdizzz', 'Perdis'],
            ],
            [
                'name' => 'Cecina',
                'errors' => ['Secina', 'Cecinna', 'Cecinia'],
            ],
            [
                'name' => 'Jamón ibérico',
                'errors' => ['Jamon iberico', 'Jamón iberico', 'Jamonn ibérico'],
            ],
            [
                'name' => 'Paleta ibérica',
                'errors' => ['Paleta iberica', 'Palet ibérica', 'Palet iberica'],
            ],
            [
                'name' => 'Lomo ibérico',
                'errors' => ['Lomo iberico', 'Lom ibérico', 'Lomo iberiko'],
            ],
            [
                'name' => 'Chorizo ibérico',
                'errors' => ['Chorizo iberico', 'Chorizo iberiko', 'Chorizo ibberico'],
            ],
            [
                'name' => 'Salchichón ibérico',
                'errors' => ['Salchichon iberico', 'Salchichon iberiko', 'Salchichon iberrico'],
            ],
            [
                'name' => 'Sobrasada',
                'errors' => ['Sobrassada', 'Sobrassadda', 'Sobrasadda'],
            ],
            [
                'name' => 'Fuet',
                'errors' => ['Fet', 'Fuett', 'Futt'],
            ],
        ];

        foreach ($items as $data) {
            // Canonical correcto
            $canonical = new Item();
            $canonical->setName($data['name']);
            $canonical->setSlug(new Slug($data['name']));
            $canonical->setCanonical($canonical);
            $manager->persist($canonical);

            // Faltas ortográficas → canonical apunta al correcto
            foreach ($data['errors'] as $errorVariant) {
                $variant = new Item();
                $variant->setName($errorVariant);
                $variant->setSlug(new Slug($errorVariant));
                $variant->setCanonical($canonical);
                $manager->persist($variant);
            }
        }

        $manager->flush();
    }

}