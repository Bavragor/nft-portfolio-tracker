<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use NftPortfolioTracker\Entity\Project;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210718210206 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // do nothing
    }

    public function postUp(Schema $schema): void
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $project = new Project();
        $project->setName('TopDogBeachClub');
        $project->setTokenSymbol('TDBC');
        $project->setTokenName('TopDogBeachClub');
        $project->setContract('0x6f0365ca2c1dd63473f898a60f878a07e0f68a26');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Faticorns');
        $project->setTokenSymbol('FATI');
        $project->setTokenName('Faticorns');
        $project->setContract('0x2dbe8b9b0d31aeadf1aafb823d859623a237faba');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('GOATz');
        $project->setTokenSymbol('GOATZ');
        $project->setTokenName('GOATz');
        $project->setContract('0x3eacf2d8ce91b35c048c6ac6ec36341aae002fb9');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Evaverse');
        $project->setTokenSymbol('EVA');
        $project->setTokenName('Evaverse');
        $project->setContract('0x837704ec8dfec198789baf061d6e93b0e1555da6');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Bonsai');
        $project->setTokenSymbol('BNSI');
        $project->setTokenName('Bonsai');
        $project->setContract('0xec9c519d49856fd2f8133a0741b4dbe002ce211b');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('The Pirate Panda Bay');
        $project->setTokenSymbol('TPPB');
        $project->setTokenName('The Pirate Panda Bay');
        $project->setContract('0xe95980371ce4b6edf54f5569f7dad1088df1a493');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Epic Eggplants');
        $project->setTokenSymbol('EPICEGGPLANTS');
        $project->setTokenName('EpicEggplants');
        $project->setContract('0x23b16a0aa07ccc48fb0b17ff77534666e293fb1e');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Bored Ape Yacht Club');
        $project->setTokenSymbol('BAYC');
        $project->setTokenName('BoredApeYachtClub');
        $project->setContract('0xbc4ca0eda7647a8ab7c2061c2e118a18a936f13d');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Polymorphs');
        $project->setTokenSymbol('MORPH');
        $project->setTokenName('Polymorphs');
        $project->setContract('0x1cbb182322aee8ce9f4f1f98d7460173ee30af1f');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Fame Lady Squad');
        $project->setTokenSymbol('FLS');
        $project->setTokenName('FameLadySquad');
        $project->setContract('0xf3e6dbbe461c6fa492cea7cb1f5c5ea660eb1b47');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Panda Golf Squad');
        $project->setTokenSymbol('PGSS');
        $project->setTokenName('PandaGolfSquadd');
        $project->setContract('0x646c83dd4dad66e2fedc87ea4fc9b03a3df49ebe');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Bulls On The Block');
        $project->setTokenSymbol('BOTB');
        $project->setTokenName('BullsOnTheBlock');
        $project->setContract('0x3a8778a58993ba4b941f85684d74750043a4bb5f');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('The Divine Order Of the Zodiac');
        $project->setTokenSymbol('THEDIVINEZODIAC');
        $project->setTokenName('The Divine Order Of the Zodiac');
        $project->setContract('0x75bd294f5adae8428ddcd1431ae2e0d4f5a05707');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Bored Aped Punk');
        $project->setTokenSymbol('BAP');
        $project->setTokenName('Bored Aped Punk');
        $project->setContract('0x9e2953857df990c009d878c41ab5afa44ead2384');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Crypto Hodlers');
        $project->setTokenSymbol('CRHDL');
        $project->setTokenName('CryptoHodlers');
        $project->setContract('0xe12a2a0fb3fb5089a498386a734df7060c1693b8');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Degenz');
        $project->setTokenSymbol('DEGENZ');
        $project->setTokenName('Degenz');
        $project->setContract('0xfb9e9e7150ccebfe42d58de1989c5283d0eaab2e');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('RUG.WTF');
        $project->setTokenSymbol('RUG');
        $project->setTokenName('RUG.WTF');
        $project->setContract('0x6c94954d0b265f657a4a1b35dfaa8b73d1a3f199');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Bored Ape Kennel Club');
        $project->setTokenSymbol('BAKC');
        $project->setTokenName('BoredApeKennelClub');
        $project->setContract('0xba30e5f9bb24caa003e9f2f0497ad287fdf95623');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Hash Demons');
        $project->setTokenSymbol('HASHDEMONS');
        $project->setTokenName('HashDemons');
        $project->setContract('0x032dd2a3c6d234aa9620d65eb618fcdc72be3dbb');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Misfit University Official');
        $project->setTokenSymbol('MU');
        $project->setTokenName('Misfit University Official');
        $project->setContract('0x74a69df3adc7235392374f728601e49807de4b30');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Sup Ducks');
        $project->setTokenSymbol('SD');
        $project->setTokenName('SupDucks');
        $project->setContract('0x3fe1a4c1481c8351e91b64d5c398b159de07cbc5');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('DystoPunks V2');
        $project->setTokenSymbol('DYSTO');
        $project->setTokenName('DystoPunks V2');
        $project->setContract('0xbea8123277142de42571f1fac045225a1d347977');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('FuckingPickles');
        $project->setTokenSymbol('FUCKINGPICKLES');
        $project->setTokenName('FuckingPickles');
        $project->setContract('0xf78296dfcf01a2612c2c847f68ad925801eeed80');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('The Rug Shop');
        $project->setTokenSymbol('TRUG');
        $project->setTokenName('TheRugShop');
        $project->setContract('0xdae5bded4d7980599817cb532aea53b2c0f1517e');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Monster Rehab');
        $project->setTokenSymbol('MONSTER');
        $project->setTokenName('MonsterRehab');
        $project->setContract('0x3e2895d0e6e303ac115dd424fcad52187907871a');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Galactic Secret Agency');
        $project->setTokenSymbol('GSA');
        $project->setTokenName('Galactic Secret Agency');
        $project->setContract('0x6184f10302cebeea0211f9310225f051cc549626');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('ANON');
        $project->setTokenSymbol('ANON');
        $project->setTokenName('ANON');
        $project->setContract('0x77a679db25d504d83d59b32467545c5a3783c88b');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Crypto Phunks V2');
        $project->setTokenSymbol('PHUNK');
        $project->setTokenName('CryptoPhunksV2');
        $project->setContract('0xf07468ead8cf26c752c676e43c814fee9c8cf402');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Slacker Duck Pond');
        $project->setTokenSymbol('SDP');
        $project->setTokenName('Slacker Duck Pond');
        $project->setContract('0xec516efecd8276efc608ecd958a4eab8618c61e8');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Royal Society');
        $project->setTokenSymbol('ROYALSOCIETY');
        $project->setTokenName('Royal Society');
        $project->setContract('0xb159f1a0920a7f1d336397a52d92da94b1279838');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Dreamloops V1');
        $project->setTokenSymbol('DRMLOOPS');
        $project->setTokenName('Dreamloops V1');
        $project->setContract('0xf1b33ac32dbc6617f7267a349be6ebb004feccff');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('BYOPills');
        $project->setTokenSymbol('BYOPILL');
        $project->setTokenName('BYOPills');
        $project->setContract('0xbd275ce24f32d6ce4e9d9519c55abe9bc0ed7fcf');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Slumdoge Billionaires');
        $project->setTokenSymbol('SDB');
        $project->setTokenName('Slumdoge Billionaires');
        $project->setContract('0x312d09d1160316a0946503391b3d1bcbc583181b');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Pix Puppies');
        $project->setTokenSymbol('PixPuppies');
        $project->setTokenName('Pix Puppies');
        $project->setContract('0xdf58c36af16bc9cbc9c15b435abbbd4ea3931815');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Super Yeti');
        $project->setTokenSymbol('defra');
        $project->setTokenName('Super Yeti');
        $project->setContract('0x3f0785095a660fee131eebcd5aa243e529c21786');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Rarible');
        $project->setTokenSymbol('RARI');
        $project->setTokenName('Rarible');
        $project->setContract('0x60f80121c31a0d46b5279700f9df786054aa5ee5');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Arabian Camels');
        $project->setTokenSymbol('CAMELS');
        $project->setTokenName('Arabian Camels');
        $project->setContract('0x3b3bc9b1dd9f3c8716fff083947b8769e2ff9781');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Maestro Pups');
        $project->setTokenSymbol('PUPS');
        $project->setTokenName('MaestroPups');
        $project->setContract('0x874dd3f2317beabb4f069b42b539010c54b195ec');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Sewer Rat Social Club');
        $project->setTokenSymbol('SRSC');
        $project->setTokenName('Sewer Rat Social Club');
        $project->setContract('0xd21a23606d2746f086f6528cd6873bad3307b903');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('PUNKS Comic');
        $project->setTokenSymbol('COMIC');
        $project->setTokenName('PUNKS Comic');
        $project->setContract('0x5ab21ec0bfa0b29545230395e3adaca7d552c948');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Cool Cats');
        $project->setTokenSymbol('COOL');
        $project->setTokenName('Cool Cats');
        $project->setContract('0x1a92f7381b9f03921564a437210bb9396471050c');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('VoxoDeus');
        $project->setTokenSymbol('VXO');
        $project->setTokenName('VoxoDeus');
        $project->setContract('0xafba8c6b3875868a90e5055e791213258a9fe7a7');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Untamed Elephants');
        $project->setTokenSymbol('UE');
        $project->setTokenName('Untamed Elephants');
        $project->setContract('0x613e5136a22206837d12ef7a85f7de2825de1334');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Forgotten Runes Wizards Cult');
        $project->setTokenSymbol('WIZARDS');
        $project->setTokenName('ForgottenRunesWizardsCult');
        $project->setContract('0x521f9c7505005cfa19a8e5786a9c3c9c9f5e6f42');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Bastard Gan Punks V2');
        $project->setTokenSymbol('BGANPUNKV2');
        $project->setTokenName('BASTARD GAN PUNKS V2');
        $project->setContract('0x31385d3520bced94f77aae104b406994d8f2168c');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Ethersparks');
        $project->setTokenSymbol('ETHERSPARKS');
        $project->setTokenName('Ethersparks');
        $project->setContract('0xd9a3fca910f76c82be37c0e78dd2fc435ff2a77a');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('LaMelo Ball Collectibles');
        $project->setTokenSymbol('LBC');
        $project->setTokenName('LaMelo Ball Collectibles');
        $project->setContract('0x139b522955d54482e7662927653abb0bfb6f19ba');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('TEST FLIGHT CREW');
        $project->setTokenSymbol('TFC');
        $project->setTokenName('TEST FLIGHT CREW');
        $project->setContract('0xfdb760b4de27fd1a3377840bf502ebc0732a5d9d');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('GorillaNemesis');
        $project->setTokenSymbol('GONS');
        $project->setTokenName('GorillaNemesis');
        $project->setContract('0x984eea281bf65638ac6ed30c4ff7977ea7fe0433');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('OpenSea');
        $project->setTokenSymbol('OS');
        $project->setTokenName('OpenSea');
        $project->setContract('0x7be8076f4ea4a4ad08075c2508e481d6c946d12b');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Bones and Bananas');
        $project->setTokenSymbol('BNB');
        $project->setTokenName('Bones and Bananas');
        $project->setContract('0xfbb6684ebd6093989740e8ef3e7d57cf3813e5a4');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Pix Zoo');
        $project->setTokenSymbol('PixZoo');
        $project->setTokenName('Pix Zoo');
        $project->setContract('0x5719be18d99d9c3bf967044932551c6788033ede');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Visitors of Imma Degen');
        $project->setTokenSymbol('VOID');
        $project->setTokenName('Visitors of Imma Degen');
        $project->setContract('0xdb55584e5104505a6b38776ee4dcba7dd6bb25fe');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Reckless Whales');
        $project->setTokenSymbol('REWS');
        $project->setTokenName('Reckless Whales');
        $project->setContract('0x4a453df93535f6baa8dc3cb1b0c032289da3bd16');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('The Alien Boy');
        $project->setTokenSymbol('TABOY');
        $project->setTokenName('TheAlienBoy');
        $project->setContract('0x4581649af66bccaee81eebae3ddc0511fe4c5312');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('The Wicked Craniums');
        $project->setTokenSymbol('TWC');
        $project->setTokenName('TheWickedCraniums');
        $project->setContract('0x85f740958906b317de6ed79663012859067e745b');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Crypto Beasts');
        $project->setTokenSymbol('rare-eggs');
        $project->setTokenName('CryptoBeasts');
        $project->setContract('0xa74e199990ff572a320508547ab7f44ea51e6f28');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Tiles');
        $project->setTokenSymbol('TILES');
        $project->setTokenName('Tiles');
        $project->setContract('0x64931f06d3266049bf0195346973762e6996d764');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Cryptinies');
        $project->setTokenSymbol('CRYPTINIES');
        $project->setTokenName('CRYPTINIES');
        $project->setContract('0xcd223812722faf45848a431a6e0387de7ffbc2b2');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Animonkeys');
        $project->setTokenSymbol('ANMK');
        $project->setTokenName('Animonkeys');
        $project->setContract('0xa32422dfb5bf85b2084ef299992903eb93ff52b0');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('ZED Run');
        $project->setTokenSymbol('ZED');
        $project->setTokenName('ZED Run');
        $project->setContract('0xa5f1ea7df861952863df2e8d1312f7305dabf215');
        $project->setEtherscanUrl('https://api.polygonscan.com/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Pudgy Penguins');
        $project->setTokenSymbol('PPG');
        $project->setTokenName('PudgyPenguins');
        $project->setContract('0xbd3531da5cf5857e7cfaa92426877b022e612cf8');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('NFT Fuck Bubbles');
        $project->setTokenSymbol('NFTFB');
        $project->setTokenName('NFT Fuck Bubbles');
        $project->setContract('0xc67b4203b42fa1bec5a80680ff86f8c23e2ee812');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Boring Bananas Co.');
        $project->setTokenSymbol('BBC');
        $project->setTokenName('Boring Bananas Co.');
        $project->setContract('0xb9ab19454ccb145f9643214616c5571b8a4ef4f2');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Pixel Glyphs');
        $project->setTokenSymbol('PxG');
        $project->setTokenName('PixelGlyphs');
        $project->setContract('0xf38d6bf300d52ba7880b43cddb3f94ee3c6c4ea6');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $project = new Project();
        $project->setName('Pixel Glyphs');
        $project->setTokenSymbol('PxG');
        $project->setTokenName('PixelGlyphs');
        $project->setContract('0xf38d6bf300d52ba7880b43cddb3f94ee3c6c4ea6');
        $project->setEtherscanUrl('https://api.etherscan.io/api');
        $project->setDescription(null);
        $em->persist($project);
        $em->flush();
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
