<?php
namespace App\DataFixtures;

use App\Entity\Item;
use App\ValueObject\Slug;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ItemLimpiezaFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $items = [
            ['name' => 'Detergente líquido', 'errors' => ['Detergente liquido', 'Detergente liquído', 'Detergente líquidoo']],
            ['name' => 'Detergente en polvo', 'errors' => ['Detergente en polvo', 'Detergente en polbvo', 'Detergente en polvo']],
            ['name' => 'Jabón en barra', 'errors' => ['Jabon en barra', 'Jabón en barrra', 'Jabón en barra']],
            ['name' => 'Limpiador multiusos', 'errors' => ['Limpiador multi usos', 'Limpiador multiusos', 'Limpiador multiúsos']],
            ['name' => 'Lejía', 'errors' => ['Lejia', 'Lejíia', 'Lejía']],
            ['name' => 'Suavizante', 'errors' => ['Suavisante', 'Suavizante', 'Suavizante']],
            ['name' => 'Ambientador', 'errors' => ['Ambientador', 'Ambiéntador', 'Ambientadr']],
            ['name' => 'Limpiador para baños', 'errors' => ['Limpiador para banos', 'Limpiador para baňos', 'Limpiador para baños']],
            ['name' => 'Limpiador de acero inoxidable', 'errors' => ['Limpiador de acero inoxidadble', 'Limpiador de acero inoxidable', 'Limpiador de acero inox']],
            ['name' => 'Limpiador de superficies', 'errors' => ['Limpiador de superfícies', 'Limpiador de superfiicies', 'Limpiador de superficies']],
            ['name' => 'Esponja abrasiva', 'errors' => ['Esponja abbrasiva', 'Esponja abrasiva', 'Esponja abbrasiva']],
            ['name' => 'Limpiador de vidrios antirreflejante', 'errors' => ['Limpiador de vidrios antirrefleiante', 'Limpiador de vidrios antirreflejannte', 'Limpiador de vidrios antirreflejante']],
            ['name' => 'Limpiador para cocina', 'errors' => ['Limpiador para cochina', 'Limpiador para cocína', 'Limpiador para cocina']],
            ['name' => 'Esponja para platos', 'errors' => ['Esponja para plátos', 'Esponja para platoss', 'Esponja para platos']],
            ['name' => 'Limpiador para madera', 'errors' => ['Limpiador para maderaa', 'Limpiador para madrea', 'Limpiador para madera']],
            ['name' => 'Limpiador de tapicería', 'errors' => ['Limpiador de tapisería', 'Limpiador de tapiceria', 'Limpiador de tapicería']],
            ['name' => 'Paño para polvo', 'errors' => ['Paño para polbvo', 'Paño para polvvo', 'Paño para polvo']],
            ['name' => 'Limpiador desengrasante', 'errors' => ['Limpiador desengrassante', 'Limpiador desengrasannte', 'Limpiador desengrasante']],
            ['name' => 'Limpiador para pisos', 'errors' => ['Limpiador para pìsos', 'Limpiador para pisoss', 'Limpiador para pisos']],
            ['name' => 'Limpiador de suelos', 'errors' => ['Limpiador de suelos', 'Limpiador de suelos', 'Limpiador de suelos']],
            ['name' => 'Limpiador para vidrio', 'errors' => ['Limpiador para vidrío', 'Limpiador para vidrio', 'Limpiador para vidrio']],
            ['name' => 'Jabón para manos', 'errors' => ['Jabon para manos', 'Jabón para mannos', 'Jabón para manos']],
            ['name' => 'Limpiador de plástico', 'errors' => ['Limpiador de plastico', 'Limpiador de plastíco', 'Limpiador de plástico']],
            ['name' => 'Limpiador en gel', 'errors' => ['Limpiador en jel', 'Limpiador en gél', 'Limpiador en gel']],
            ['name' => 'Cepillo para limpieza', 'errors' => ['Cepillo para limplieza', 'Cepillo para limpeiza', 'Cepillo para limpieza']],
            ['name' => 'Limpiador en espuma', 'errors' => ['Limpiador en espúma', 'Limpiador en espuma', 'Limpiador en espuma']],
            ['name' => 'Limpiador para electrodomésticos', 'errors' => ['Limpiador para electrodomesticos', 'Limpiador para electrodomésticos', 'Limpiador para electrodomesticos']],
            ['name' => 'Limpiador para cristales', 'errors' => ['Limpiador para cristáles', 'Limpiador para cristalless', 'Limpiador para cristales']],
            ['name' => 'Esponja para baño', 'errors' => ['Esponja para baňo', 'Esponja para bano', 'Esponja para baño']],
            ['name' => 'Limpiador para telas', 'errors' => ['Limpiador para tlas', 'Limpiador para telas', 'Limpiador para telas']],
            ['name' => 'Limpiador de hornos', 'errors' => ['Limpiador de hornoss', 'Limpiador de hornos', 'Limpiador de hornoss']],
            ['name' => 'Limpiador para alfombras', 'errors' => ['Limpiador para alfombas', 'Limpiador para alfombraas', 'Limpiador para alfombras']],
            ['name' => 'Limpiador para paredes', 'errors' => ['Limpiador para paredess', 'Limpiador para parades', 'Limpiador para paredes']],
            ['name' => 'Limpiador de manchas', 'errors' => ['Limpiador de manhcas', 'Limpiador de manchass', 'Limpiador de manchas']],
            ['name' => 'Limpiador para suelos', 'errors' => ['Limpiador para suelos', 'Limpiador para suelos', 'Limpiador para suelos']],
            ['name' => 'Paño de microfibra', 'errors' => ['Paño de microfibras', 'Paño de microfibrá', 'Paño de microfibra']],
            ['name' => 'Limpiador para acero', 'errors' => ['Limpiador para acerro', 'Limpiador para aceero', 'Limpiador para acero']],
            ['name' => 'Limpiador desinfectante', 'errors' => ['Limpiador desinfectante', 'Limpiador desinfetante', 'Limpiador desinfectante']],
            ['name' => 'Limpiador para vidrios', 'errors' => ['Limpiador para vidrios', 'Limpiador para vidrios', 'Limpiador para vidrios']],
            ['name' => 'Limpiador para muebles', 'errors' => ['Limpiador para muebles', 'Limpiador para muebels', 'Limpiador para muebles']],
            ['name' => 'Limpiador para hornos', 'errors' => ['Limpiador para hornoss', 'Limpiador para hornos', 'Limpiador para hornos']],
            ['name' => 'Limpiador en spray', 'errors' => ['Limpiador en spry', 'Limpiador en spray', 'Limpiador en spray']],
            ['name' => 'Limpiador para acero inoxidable', 'errors' => ['Limpiador para acero inoxidadble', 'Limpiador para acero inoxidable', 'Limpiador para acero inoxidable']],
            ['name' => 'Limpiador para cocina', 'errors' => ['Limpiador para cochina', 'Limpiador para cocina', 'Limpiador para cocina']],
            ['name' => 'Desengrasante para cocina', 'errors' => ['Desengrasante para cochina', 'Desengrasante para cocina', 'Desengrasante para cocina']],
            ['name' => 'Limpiador para baños', 'errors' => ['Limpiador para banos', 'Limpiador para baños', 'Limpiador para baños']],
            ['name' => 'Limpiador para pisos', 'errors' => ['Limpiador para pìsos', 'Limpiador para pisos', 'Limpiador para pisos']],
            ['name' => 'Limpiador para ventanas', 'errors' => ['Limpiador para ventanas', 'Limpiador para ventnas', 'Limpiador para ventanas']],
            ['name' => 'Limpiador para lavavajillas', 'errors' => ['Limpiador para lavavajillas', 'Limpiador para lavavajias', 'Limpiador para lavavajillas']],
            ['name' => 'Limpiador para inodoros', 'errors' => ['Limpiador para inodoros', 'Limpiador para indoros', 'Limpiador para inodoros']],
            ['name' => 'Limpiador para espejos', 'errors' => ['Limpiador para espejos', 'Limpiador para espejos', 'Limpiador para espejos']],
            ['name' => 'Limpiador para lavadoras', 'errors' => ['Limpiador para lavadoras', 'Limpiador para lavdoras', 'Limpiador para lavadoras']],
            ['name' => 'Limpiador para secadoras', 'errors' => ['Limpiador para secadoras', 'Limpiador para secadoras', 'Limpiador para secadoras']],
            ['name' => 'Limpiador para azulejos', 'errors' => ['Limpiador para azulejos', 'Limpiador para azulejos', 'Limpiador para azulejos']],
            ['name' => 'Limpiador desengrasante', 'errors' => ['Limpiador desengrassante', 'Limpiador desengrasante', 'Limpiador desengrasante']],
            ['name' => 'Limpiador para muebles de madera', 'errors' => ['Limpiador para muebles de maderaa', 'Limpiador para muebles de madera', 'Limpiador para muebles de madera']],
            ['name' => 'Limpiador para acero inoxidable', 'errors' => ['Limpiador para acero inoxidable', 'Limpiador para acero inoxidable', 'Limpiador para acero inoxidable']],
            ['name' => 'Limpiador para suelos de madera', 'errors' => ['Limpiador para suelos de maderaa', 'Limpiador para suelos de madera', 'Limpiador para suelos de madera']],
            ['name' => 'Limpiador para alfombras', 'errors' => ['Limpiador para alfombas', 'Limpiador para alfombras', 'Limpiador para alfombras']],
            ['name' => 'Limpiador para tapicería', 'errors' => ['Limpiador para tapisería', 'Limpiador para tapiceria', 'Limpiador para tapicería']],
            ['name' => 'Limpiador para coches', 'errors' => ['Limpiador para coches', 'Limpiador para coches', 'Limpiador para coches']],
            ['name' => 'Limpiador para cristales', 'errors' => ['Limpiador para cristáles', 'Limpiador para cristales', 'Limpiador para cristales']],
            ['name' => 'Limpiador para ventanas', 'errors' => ['Limpiador para ventnas', 'Limpiador para ventanas', 'Limpiador para ventanas']],
            ['name' => 'Limpiador para pantallas', 'errors' => ['Limpiador para pantallas', 'Limpiador para pantallas', 'Limpiador para pantallas']],
            ['name' => 'Limpiador para ordenadores', 'errors' => ['Limpiador para ordenadores', 'Limpiador para ordanadores', 'Limpiador para ordenadores']],
            ['name' => 'Limpiador para teclados', 'errors' => ['Limpiador para teclados', 'Limpiador para teclados', 'Limpiador para teclados']],
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