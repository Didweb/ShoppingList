<?php
namespace App\DataFixtures;

use App\Entity\Item;
use App\ValueObject\Slug;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ItemFrutasFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $items = [
            // === Frutas ===
            [
                'name' => 'Manzana roja',
                'errors' => ['Manzana rojaa', 'Manzana roia', 'Manzana rojaa'],
            ],
            [
                'name' => 'Manzana verde',
                'errors' => ['Manzana verede', 'Manzana verfe', 'Manzana vverde'],
            ],
            [
                'name' => 'Manzana Fuji',
                'errors' => ['Manzana Fji', 'Manzana Fuiji', 'Manzana Fuyi'],
            ],
            [
                'name' => 'Pera conferencia',
                'errors' => ['Pera conferncia', 'Pera confernecia', 'Pera conferecia'],
            ],
            [
                'name' => 'Pera blanquilla',
                'errors' => ['Pera blanquiilla', 'Pera blanqulla', 'Pera blanquillaa'],
            ],
            [
                'name' => 'Plátano',
                'errors' => ['Platano', 'Pláttano', 'Plátaano'],
            ],
            [
                'name' => 'Banana',
                'errors' => ['Bananna', 'Bannana', 'Bananaa'],
            ],
            [
                'name' => 'Fresa',
                'errors' => ['Frezza', 'Fressa', 'Freza'],
            ],
            [
                'name' => 'Frutilla',
                'errors' => ['Frutillla', 'Frutila', 'Frutlla'],
            ],
            [
                'name' => 'Cereza',
                'errors' => ['Cerezaa', 'Cereza', 'Cereja'],
            ],
            [
                'name' => 'Arándano',
                'errors' => ['Arandano', 'Arándanno', 'Arándnao'],
            ],
            [
                'name' => 'Frambuesa',
                'errors' => ['Frambueza', 'Frambuessa', 'Frambuuesa'],
            ],
            [
                'name' => 'Mora',
                'errors' => ['Morra', 'Moraa', 'Mora'],
            ],
            [
                'name' => 'Melocotón',
                'errors' => ['Melocoton', 'Meloccotón', 'Melocotonn'],
            ],
            [
                'name' => 'Nectarina',
                'errors' => ['Nectrina', 'Nectaarina', 'Nectarína'],
            ],
            [
                'name' => 'Albaricoque',
                'errors' => ['Albaricoqe', 'Albaricoqque', 'Albaricoqúe'],
            ],
            [
                'name' => 'Ciruela',
                'errors' => ['Cirruela', 'Cirueela', 'Cirula'],
            ],
            [
                'name' => 'Higo',
                'errors' => ['Higgo', 'Hígo', 'Hgo'],
            ],
            [
                'name' => 'Uva blanca',
                'errors' => ['Uva blnca', 'Uva blanaca', 'Uva blaca'],
            ],
            [
                'name' => 'Uva roja',
                'errors' => ['Uva rojaa', 'Uva roia', 'Uva roia'],
            ],
            [
                'name' => 'Sandía',
                'errors' => ['Sandia', 'Sandíaa', 'Sandiía'],
            ],
            [
                'name' => 'Melón',
                'errors' => ['Melon', 'Melonn', 'Melónn'],
            ],
            [
                'name' => 'Kiwi',
                'errors' => ['Kiwwi', 'Kiiwi', 'Kiwi'],
            ],
            [
                'name' => 'Papaya',
                'errors' => ['Papayaa', 'Papayaa', 'Ppayaa'],
            ],
            [
                'name' => 'Mango',
                'errors' => ['Mngoo', 'Mangoo', 'Mangó'],
            ],
            [
                'name' => 'Piña',
                'errors' => ['Pina', 'Píña', 'Pinná'],
            ],
            [
                'name' => 'Coco',
                'errors' => ['Cocoo', 'Cocco', 'Coco'],
            ],
            [
                'name' => 'Granada',
                'errors' => ['Granda', 'Granadda', 'Granadá'],
            ],
            [
                'name' => 'Lima',
                'errors' => ['Limaa', 'Lma', 'Líma'],
            ],
            [
                'name' => 'Limón',
                'errors' => ['Limon', 'Limonn', 'Limóón'],
            ],
            [
                'name' => 'Pomelo',
                'errors' => ['Pomello', 'Pomélo', 'Pomelo'],
            ],
            [
                'name' => 'Mandarina',
                'errors' => ['Mandaarina', 'Mandarinaa', 'Mandarina'],
            ],
            [
                'name' => 'Naranja',
                'errors' => ['Nranja', 'Naranjaa', 'Naranjja'],
            ],
            [
                'name' => 'Chirimoya',
                'errors' => ['Chirimoyaa', 'Chrimoya', 'Chirimoyya'],
            ],
            [
                'name' => 'Guayaba',
                'errors' => ['Guayabaa', 'Guayyaba', 'Guayba'],
            ],
            [
                'name' => 'Maracuyá',
                'errors' => ['Maracuya', 'Maracuyáa', 'Maracuýa'],
            ],
            [
                'name' => 'Carambola',
                'errors' => ['Caramboola', 'Carrambola', 'Carambolaa'],
            ],
            [
                'name' => 'Pitahaya',
                'errors' => ['Pitahyaa', 'Pitahhaya', 'Pittahaya'],
            ],
            [
                'name' => 'Litchi',
                'errors' => ['Lichi', 'Litchii', 'Lichi'],
            ],
            [
                'name' => 'Tamarindo',
                'errors' => ['Tamarino', 'Tamrindo', 'Tamarindo'],
            ],
            [
                'name' => 'Mamey',
                'errors' => ['Mameyy', 'Mamay', 'Mamei'],
            ],
            [
                'name' => 'Durian',
                'errors' => ['Durián', 'Durrían', 'Durrian'],
            ],
            [
                'name' => 'Rambután',
                'errors' => ['Rambutan', 'Rambuttán', 'Rambutann'],
            ],
            [
                'name' => 'Camu camu',
                'errors' => ['Camucamu', 'Camu camuu', 'Camu camú'],
            ],
            [
                'name' => 'Açaí',
                'errors' => ['Acai', 'Açai', 'Açaíí'],
            ],
            [
                'name' => 'Mangostán',
                'errors' => ['Mangostan', 'Mangostánn', 'Mangostaan'],
            ],
            [
                'name' => 'Grosella',
                'errors' => ['Grosela', 'Groseella', 'Grosellaa'],
            ],
            [
                'name' => 'Grosella negra',
                'errors' => ['Grosella negrra', 'Grosella nega', 'Grosella ngera'],
            ],
            [
                'name' => 'Grosella espinosa',
                'errors' => ['Grosella espinoda', 'Grosella espinnosa', 'Grosella espinossa'],
            ],
            [
                'name' => 'Physalis',
                'errors' => ['Fisalis', 'Physaliss', 'Physalis'],
            ],
            [
                'name' => 'Membrillo',
                'errors' => ['Membrilo', 'Membrilllo', 'Mmembrillo'],
            ],
            [
                'name' => 'Castaña',
                'errors' => ['Castaña', 'Castañaa', 'Castana'],
            ],
            [
                'name' => 'Avellana',
                'errors' => ['Avellnaa', 'Avellana', 'Avellanaa'],
            ],
            [
                'name' => 'Nuez',
                'errors' => ['Nues', 'Nuéz', 'Nuez'],
            ],
            [
                'name' => 'Pistacho',
                'errors' => ['Pistaco', 'Pistachoo', 'Pistacho'],
            ],
            [
                'name' => 'Almendra',
                'errors' => ['Almendraa', 'Almmendra', 'Almndra'],
            ],
            [
                'name' => 'Dátil',
                'errors' => ['Datil', 'Dáttil', 'Dattíl'],
            ],
            [
                'name' => 'Higo chumbo',
                'errors' => ['Higo chmbo', 'Higo chumbbo', 'Higo chumo'],
            ],
            [
                'name' => 'Aceituna',
                'errors' => ['Aceitunaa', 'Acetuna', 'Aceitunna'],
            ],
            [
                'name' => 'Oliva',
                'errors' => ['Olivaa', 'Olliva', 'Olíva'],
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