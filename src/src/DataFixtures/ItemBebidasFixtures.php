<?php
namespace App\DataFixtures;

use App\Entity\Item;
use App\ValueObject\Slug;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ItemBebidasFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $items = [
            // === Bebidas ===
            [
                'name' => 'Fanta',
                'errors' => ['Fantta', 'Fante', 'Fnta'],
            ],
            [
                'name' => 'Fanta de limón',
                'errors' => ['Fanta de limon', 'Fanta limon', 'Fantta limon'],
            ],
            [
                'name' => 'Coca Cola',
                'errors' => ['Cocacola', 'CocaCola', 'Cocca Cola'],
            ],
            [
                'name' => 'Pepsi',
                'errors' => ['Pesi', 'Pepsy', 'Peppsi'],
            ],
            [
                'name' => 'Sprite',
                'errors' => ['Spritte', 'Sprte', 'Spritte'],
            ],
            [
                'name' => 'Nestea',
                'errors' => ['Nesttea', 'Nestia', 'Nesstea'],
            ],
            [
                'name' => 'Aquarius',
                'errors' => ['Aquaruis', 'Acuarius', 'Aquarious'],
            ],
            [
                'name' => 'Schweppes',
                'errors' => ['Schwepes', 'Sweppes', 'Schweppess'],
            ],
            [
                'name' => 'Red Bull',
                'errors' => ['Redbul', 'RedBull', 'Read Bull'],
            ],
            [
                'name' => 'Monster',
                'errors' => ['Monter', 'Monstter', 'Monester'],
            ],
            [
                'name' => 'Burn',
                'errors' => ['Bern', 'Bourn', 'Bunr'],
            ],
            [
                'name' => 'Lipton Ice Tea',
                'errors' => ['Lipton Icetea', 'Lipton Ice T', 'Lipton Icte'],
            ],
            [
                'name' => 'Seven Up',
                'errors' => ['7up', 'Sevn Up', 'SevenUp'],
            ],
            [
                'name' => 'Tónica',
                'errors' => ['Tonica', 'Tonca', 'Tonnica'],
            ],
            [
                'name' => 'Kas Limón',
                'errors' => ['Kas Limon', 'Kas Lmon', 'Kass Limón'],
            ],
            [
                'name' => 'Kas Naranja',
                'errors' => ['Kas Naranha', 'Kas Naraja', 'Kass Naranja'],
            ],
            [
                'name' => 'Trina',
                'errors' => ['Trinna', 'Trinnaa', 'Trinna'],
            ],
            [
                'name' => 'Minute Maid',
                'errors' => ['Minut Maid', 'Minute Maind', 'Minutte Maid'],
            ],
            [
                'name' => 'Bifrutas',
                'errors' => ['Bifruitas', 'Bifrutass', 'Bifrutta'],
            ],
            [
                'name' => 'Don Simón',
                'errors' => ['Don Simon', 'Don Simmon', 'Don Simonn'],
            ],
            [
                'name' => 'Granini',
                'errors' => ['Graninni', 'Graninii', 'Grannini'],
            ],
            [
                'name' => 'Sunny Delight',
                'errors' => ['Sunn Delight', 'Sunny Deligh', 'Suni Delight'],
            ],
            [
                'name' => 'Cacaolat',
                'errors' => ['Cacalot', 'Cacaollat', 'Caccao Lat'],
            ],
            [
                'name' => 'Cola Cao Shake',
                'errors' => ['Colacao Shake', 'Cola Kcao Shake', 'Cola Ccao Shake'],
            ],
            [
                'name' => 'Okey',
                'errors' => ['Okkey', 'Okei', 'Okeey'],
            ],
            [
                'name' => 'Agua mineral',
                'errors' => ['Aguamineral', 'Agua minerral', 'Agua miniral'],
            ],
            [
                'name' => 'Agua con gas',
                'errors' => ['Agua con Gaz', 'Agua cong gas', 'Auga con gas'],
            ],
            [
                'name' => 'Agua sin gas',
                'errors' => ['Agua sin Gaz', 'Auga sin gas', 'Agua s in gas'],
            ],
            [
                'name' => 'Agua saborizada',
                'errors' => ['Agua saborisada', 'Agua sabrizada', 'Aguasaborizada'],
            ],
            [
                'name' => 'Zumo de naranja',
                'errors' => ['Zumo naranja', 'Zumode naranja', 'Sumo de naranja'],
            ],
            [
                'name' => 'Zumo de piña',
                'errors' => ['Zumo pina', 'Zumo de pinia', 'Sumo de piña'],
            ],
            [
                'name' => 'Zumo multifrutas',
                'errors' => ['Zumo multufrutas', 'Zumo multyfrutas', 'Zumo multifruta'],
            ],
            [
                'name' => 'Batido de chocolate',
                'errors' => ['Batido chocolate', 'Batido chocolatte', 'Batidode chocolate'],
            ],
            [
                'name' => 'Batido de vainilla',
                'errors' => ['Batido vainilla', 'Batido de vanila', 'Batidode vainilla'],
            ],
            [
                'name' => 'Cerveza',
                'errors' => ['Cervesa', 'Cerbeza', 'Cervezaa'],
            ],
            [
                'name' => 'Cerveza sin alcohol',
                'errors' => ['Cerveza sin alcol', 'Cerveza sin alcohl', 'Cerveza sin alcoholh'],
            ],
            [
                'name' => 'Sidra',
                'errors' => ['Sidraa', 'Cidra', 'Siddra'],
            ],
            [
                'name' => 'Vino tinto',
                'errors' => ['Vino tino', 'Vino tinnto', 'Vino tinot'],
            ],
            [
                'name' => 'Vino blanco',
                'errors' => ['Vino blnaco', 'Vino blancoo', 'Vino balnco'],
            ],
            [
                'name' => 'Vino rosado',
                'errors' => ['Vino rosdao', 'Vino rosad', 'Vino rosaddo'],
            ],
            [
                'name' => 'Cava',
                'errors' => ['Cavaa', 'Caba', 'Cva'],
            ],
            [
                'name' => 'Champán',
                'errors' => ['Champan', 'Champaan', 'Champàn'],
            ],
            [
                'name' => 'Ron',
                'errors' => ['Ronn', 'Roon', 'Rron'],
            ],
            [
                'name' => 'Whisky',
                'errors' => ['Wiski', 'Wisky', 'Whysky'],
            ],
            [
                'name' => 'Vodka',
                'errors' => ['Vodca', 'Votka', 'Vodkaa'],
            ],
            [
                'name' => 'Ginebra',
                'errors' => ['Ginbra', 'Gineb ra', 'Ginnebra'],
            ],
            [
                'name' => 'Tequila',
                'errors' => ['Tekila', 'Teqila', 'Tequla'],
            ],
            [
                'name' => 'Vermut',
                'errors' => ['Vermout', 'Vermutt', 'Vermoot'],
            ],
            [
                'name' => 'Licor de hierbas',
                'errors' => ['Licor hierbas', 'Licorde hierbas', 'Licor de ierbas'],
            ],
            [
                'name' => 'Crema de whisky',
                'errors' => ['Crema whisky', 'Crema d whisky', 'Cremade whisky'],
            ],
            [
                'name' => 'Tinto de verano',
                'errors' => ['Tinto verano', 'Tintode verano', 'Tinto d verano'],
            ],
            [
                'name' => 'Calimocho',
                'errors' => ['Kalimocho', 'Calimochho', 'Calimoxo'],
            ],
            [
                'name' => 'Clara con limón',
                'errors' => ['Clara limon', 'Clara con limon', 'Claracon limón'],
            ],
            [
                'name' => 'Mojito',
                'errors' => ['Mojitto', 'Mojitto', 'Mohito'],
            ],
            [
                'name' => 'Piña colada',
                'errors' => ['Pina colada', 'Pinnya colada', 'Piña coolada'],
            ],
            [
                'name' => 'Gin Tonic',
                'errors' => ['GinTonik', 'Gin Tonnic', 'Gin Tonik'],
            ],
            [
                'name' => 'Bloody Mary',
                'errors' => ['Blody Mary', 'Bloody Mery', 'Bloodi Mary'],
            ],
            [
                'name' => 'Sangría',
                'errors' => ['Sangria', 'Sangríaa', 'Sangia'],
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