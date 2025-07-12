<?php
namespace App\DataFixtures;

use App\Entity\Item;
use App\ValueObject\Slug;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ItemVerdurasFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $items = [
            // === Verduras ===
            [
                'name' => 'Lechuga iceberg',
                'errors' => ['Lechuga iceber', 'Lechuga iseberg', 'Lechug iceberg'],
            ],
            [
                'name' => 'Lechuga romana',
                'errors' => ['Lechuga romna', 'Lechuga romanna', 'Lechuga romnaa'],
            ],
            [
                'name' => 'Lechuga hoja de roble',
                'errors' => ['Lechuga hoja roble', 'Lechuga hohja de roble', 'Lechuga hoja robl'],
            ],
            [
                'name' => 'Espinaca',
                'errors' => ['Espinasa', 'Espinnaca', 'Espiaca'],
            ],
            [
                'name' => 'Acelga',
                'errors' => ['Acselga', 'Acelgga', 'Acelg'],
            ],
            [
                'name' => 'Canónigos',
                'errors' => ['Canonigos', 'Canóñigos', 'Canónigoss'],
            ],
            [
                'name' => 'Rúcula',
                'errors' => ['Rucula', 'Rúculaa', 'Rúculla'],
            ],
            [
                'name' => 'Col lombarda',
                'errors' => ['Col lmbarda', 'Col lombarda', 'Col lombara'],
            ],
            [
                'name' => 'Col rizada',
                'errors' => ['Col rizda', 'Col rrizada', 'Col risada'],
            ],
            [
                'name' => 'Coliflor',
                'errors' => ['Colifor', 'Coliflorr', 'Colifllor'],
            ],
            [
                'name' => 'Brócoli',
                'errors' => ['Brocoli', 'Brrocoli', 'Brocooli'],
            ],
            [
                'name' => 'Repollo',
                'errors' => ['Repoyo', 'Repoll', 'Repolloo'],
            ],
            [
                'name' => 'Puerro',
                'errors' => ['Puerro', 'Puerrro', 'Puerroo'],
            ],
            [
                'name' => 'Calabaza',
                'errors' => ['Calavaza', 'Calabasa', 'Calabazza'],
            ],
            [
                'name' => 'Calabacín',
                'errors' => ['Calabacin', 'Calabazin', 'Calabcín'],
            ],
            [
                'name' => 'Pepino',
                'errors' => ['Pepinio', 'Pepiono', 'Pepinno'],
            ],
            [
                'name' => 'Judía verde',
                'errors' => ['Judia verde', 'Judía verdd', 'Judía vverde'],
            ],
            [
                'name' => 'Judión',
                'errors' => ['Judion', 'Judionn', 'Judíón'],
            ],
            [
                'name' => 'Haba',
                'errors' => ['Haba', 'Haba', 'Hba'],
            ],
            [
                'name' => 'Berenjena',
                'errors' => ['Berenjenaa', 'Berenjna', 'Berenjéna'],
            ],
            [
                'name' => 'Pimiento verde',
                'errors' => ['Pimiento verede', 'Pimiento verd', 'Pimiento vverde'],
            ],
            [
                'name' => 'Pimiento rojo',
                'errors' => ['Pimiento roio', 'Pimiento rojp', 'Pimiento rojp'],
            ],
            [
                'name' => 'Pimiento amarillo',
                'errors' => ['Pimiento amarilo', 'Pimiento amariilo', 'Pimiento amarilllo'],
            ],
            [
                'name' => 'Tomate pera',
                'errors' => ['Tomate perra', 'Tomatte pera', 'Tomate ppera'],
            ],
            [
                'name' => 'Tomate rama',
                'errors' => ['Tomate rama', 'Tomate rama', 'Tomate rma'],
            ],
            [
                'name' => 'Tomate cherry',
                'errors' => ['Tomate chery', 'Tomate cherri', 'Tomate cheryy'],
            ],
            [
                'name' => 'Zanahoria',
                'errors' => ['Zanajoria', 'Zanahoriaa', 'Zanahoría'],
            ],
            [
                'name' => 'Apio',
                'errors' => ['Apío', 'Appio', 'Apo'],
            ],
            [
                'name' => 'Nabo',
                'errors' => ['Nabbo', 'Naboo', 'Nabbo'],
            ],
            [
                'name' => 'Remolacha',
                'errors' => ['Remolcha', 'Remolaccha', 'Remollacha'],
            ],
            [
                'name' => 'Rábano',
                'errors' => ['Ravano', 'Rábanno', 'Rábanoo'],
            ],
            [
                'name' => 'Cebolla',
                'errors' => ['Ceboya', 'Cebollaa', 'Cebola'],
            ],
            [
                'name' => 'Cebolleta',
                'errors' => ['Cebolletta', 'Ceboletta', 'Cebollta'],
            ],
            [
                'name' => 'Ajo',
                'errors' => ['Ajo', 'Ajo', 'Ajjo'],
            ],
            [
                'name' => 'Guisantes',
                'errors' => ['Guisante', 'Gisantes', 'Guisanttes'],
            ],
            [
                'name' => 'Alcachofa',
                'errors' => ['Alcachoofa', 'Alcachoja', 'Alcacofa'],
            ],
            [
                'name' => 'Espárrago verde',
                'errors' => ['Esparrago verde', 'Espárrago verede', 'Espárrago vverde'],
            ],
            [
                'name' => 'Espárrago blanco',
                'errors' => ['Esparrago blanco', 'Espárrago blnco', 'Espárrago blnco'],
            ],
            [
                'name' => 'Endibia',
                'errors' => ['Endibía', 'Endibiaa', 'Endibvia'],
            ],
            [
                'name' => 'Seta',
                'errors' => ['Sseta', 'Setaa', 'Setta'],
            ],
            [
                'name' => 'Champiñón',
                'errors' => ['Champiñon', 'Champinon', 'Champiñoón'],
            ],
            [
                'name' => 'Trufa',
                'errors' => ['Truffa', 'Trufa', 'Trufa'],
            ],
            [
                'name' => 'Brotes de soja',
                'errors' => ['Brotes soja', 'Brotes desoja', 'Brotes de soya'],
            ],
            [
                'name' => 'Rabanito',
                'errors' => ['Ravanito', 'Rabanitto', 'Rabanitto'],
            ],
            [
                'name' => 'Hinojo',
                'errors' => ['Hinojjo', 'Hinoj', 'Hinojo'],
            ],
            [
                'name' => 'Okra',
                'errors' => ['Ocra', 'Okkra', 'Okraa'],
            ],
            [
                'name' => 'Batata',
                'errors' => ['Batatta', 'Batataa', 'Bataa'],
            ],
            [
                'name' => 'Patata nueva',
                'errors' => ['Patata nuea', 'Patata neva', 'Patta nueva'],
            ],
            [
                'name' => 'Patata vieja',
                'errors' => ['Patata vieia', 'Patata viejaa', 'Patataa vieja'],
            ],
            [
                'name' => 'Chirivía',
                'errors' => ['Chirivia', 'Chirivíaa', 'Chirrivía'],
            ],
            [
                'name' => 'Cardo',
                'errors' => ['Cardoo', 'Cardó', 'Ccardo'],
            ],
            [
                'name' => 'Col de Bruselas',
                'errors' => ['Col Bruselas', 'Col de Bruselas', 'Col d Bruselas'],
            ],
            [
                'name' => 'Raíz de apio',
                'errors' => ['Raiz de apio', 'Raíz apio', 'Raiz apio'],
            ],
            [
                'name' => 'Escarola',
                'errors' => ['Escarrla', 'Escarrola', 'Escarola'],
            ],
            [
                'name' => 'Mostaza',
                'errors' => ['Mostasaa', 'Mostza', 'Mostaa'],
            ],
            [
                'name' => 'Berza',
                'errors' => ['Bersa', 'Berzza', 'Berzá'],
            ],
            [
                'name' => 'Col china',
                'errors' => ['Col chna', 'Col cina', 'Col chinna'],
            ],
            [
                'name' => 'Pak choi',
                'errors' => ['Pak choy', 'Pakchoi', 'Pakc hoi'],
            ],
            [
                'name' => 'Verdolaga',
                'errors' => ['Verdologa', 'Verdollaga', 'Verdologa'],
            ],
            [
                'name' => 'Ortiga',
                'errors' => ['Ortiga', 'Orttiga', 'Ortiga'],
            ],
            [
                'name' => 'Diente de león',
                'errors' => ['Diente leon', 'Dientede león', 'Diente dle león'],
            ],
            [
                'name' => 'Col kale',
                'errors' => ['Col kal', 'Colkale', 'Col kalee'],
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