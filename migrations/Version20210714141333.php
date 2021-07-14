<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\Uuid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210714141333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `sessions` (
                                `sess_id` VARBINARY(128) NOT NULL PRIMARY KEY,
                                `sess_data` BLOB NOT NULL,
                                `sess_lifetime` INTEGER UNSIGNED NOT NULL,
                                `sess_time` INTEGER UNSIGNED NOT NULL,
                                INDEX `sessions_sess_lifetime_idx` (`sess_lifetime`)
                            ) COLLATE utf8mb4_bin, ENGINE = InnoDB;'
        );
        /*$this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Untamed Elephants\', \'\', \'Untamed Elephants\', \'UE\', \'0\', \'0x613e5136a22206837d12ef7a85f7de2825de1334\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Bored Aped Punk\', \'\', \'Bored Aped Punk\', \'BAP\', \'0\', \'0x9e2953857df990c009d878c41ab5afa44ead2384\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'TopDogBeachClub\', \'\', \'TopDogBeachClub\', \'TDBC\', \'0\', \'0x6f0365ca2c1dd63473f898a60f878a07e0f68a26\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'VoxoDeus\', \'\', \'VoxoDeus\', \'VXO\', \'0\', \'0xafba8c6b3875868a90e5055e791213258a9fe7a7\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Misfit University Official\', \'\', \'Misfit University Official\', \'MU\', \'0\', \'0x74a69df3adc7235392374f728601e49807de4b30\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Arabian Camels\', \'\', \'Arabian Camels\', \'CAMELS\', \'0\', \'0x3b3bc9b1dd9f3c8716fff083947b8769e2ff9781\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'GOATz\', \'\', \'GOATz\', \'GOATZ\', \'0\', \'0x3eacf2d8ce91b35c048c6ac6ec36341aae002fb9\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'OpenSea\', \'\', \'OpenSea\', \'OS\', \'0\', \'0x7be8076f4ea4a4ad08075c2508e481d6c946d12b\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'GorillaNemesis\', \'\', \'GorillaNemesis\', \'GONS\', \'0\', \'0x984eea281bf65638ac6ed30c4ff7977ea7fe0433\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Visitors of Imma Degen\', \'\', \'Visitors of Imma Degen\', \'VOID\', \'0\', \'0xdb55584e5104505a6b38776ee4dcba7dd6bb25fe\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Cool Cats\', \'\', \'Cool Cats\', \'COOL\', \'0\', \'0x1a92f7381b9f03921564a437210bb9396471050c\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'The Wicked Craniums\', \'\', \'TheWickedCraniums\', \'TWC\', \'0\', \'0x85f740958906b317de6ed79663012859067e745b\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Monster Rehab\', \'\', \'MonsterRehab\', \'MONSTER\', \'0\', \'0x3e2895d0e6e303ac115dd424fcad52187907871a\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'The Alien Boy\', \'\', \'TheAlienBoy\', \'TABOY\', \'0\', \'0x4581649af66bccaee81eebae3ddc0511fe4c5312\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Panda Golf Squad\', \'\', \'PandaGolfSquadd\', \'PGSS\', \'0\', \'0x646c83dd4dad66e2fedc87ea4fc9b03a3df49ebe\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Epic Eggplants\', \'\', \'EpicEggplants\', \'EPICEGGPLANTS\', \'0\', \'0x23b16a0aa07ccc48fb0b17ff77534666e293fb1e\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Boring Bananas Co.\', \'\', \'Boring Bananas Co.\', \'BBC\', \'0\', \'0xb9ab19454ccb145f9643214616c5571b8a4ef4f2\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'ZED Run\', \'\', \'ZED Run\', \'ZED\', \'0\', \'0xa5f1ea7df861952863df2e8d1312f7305dabf215\', \'https://api.polygonscan.com/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Bored Ape Yacht Club\', \'\', \'BoredApeYachtClub\', \'BAYC\', \'0\', \'0xbc4ca0eda7647a8ab7c2061c2e118a18a936f13d\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Slacker Duck Pond\', \'\', \'Slacker Duck Pond\', \'SDP\', \'0\', \'0xec516efecd8276efc608ecd958a4eab8618c61e8\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Dreamloops V1\', \'\', \'Dreamloops V1\', \'DRMLOOPS\', \'0\', \'0xf1b33ac32dbc6617f7267a349be6ebb004feccff\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Bored Ape Kennel Club\', \'\', \'BoredApeKennelClub\', \'BAKC\', \'0\', \'0xba30e5f9bb24caa003e9f2f0497ad287fdf95623\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Rarible\', \'\', \'Rarible\', \'RARI\', \'0\', \'0x60f80121c31a0d46b5279700f9df786054aa5ee5\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Crypto Phunks V2\', \'\', \'CryptoPhunksV2\', \'PHUNK\', \'0\', \'0xf07468ead8cf26c752c676e43c814fee9c8cf402\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Slumdoge Billionaires\', \'\', \'Slumdoge Billionaires\', \'SDB\', \'0\', \'0x312d09d1160316a0946503391b3d1bcbc583181b\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'RUG.WTF\', \'\', \'RUG.WTF\', \'RUG\', \'0\', \'0x6c94954d0b265f657a4a1b35dfaa8b73d1a3f199\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Ethersparks\', \'\', \'Ethersparks\', \'ETHERSPARKS\', \'0\', \'0xd9a3fca910f76c82be37c0e78dd2fc435ff2a77a\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'NFT Fuck Bubbles\', \'\', \'NFT Fuck Bubbles\', \'NFTFB\', \'0\', \'0xc67b4203b42fa1bec5a80680ff86f8c23e2ee812\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Sewer Rat Social Club\', \'\', \'Sewer Rat Social Club\', \'SRSC\', \'0\', \'0xd21a23606d2746f086f6528cd6873bad3307b903\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Bastard Gan Punks V2\', \'\', \'BASTARD GAN PUNKS V2\', \'BGANPUNKV2\', \'0\', \'0x31385d3520bced94f77aae104b406994d8f2168c\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'FuckingPickles\', \'\', \'FuckingPickles\', \'FUCKINGPICKLES\', \'0\', \'0xf78296dfcf01a2612c2c847f68ad925801eeed80\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Degenz\', \'\', \'Degenz\', \'DEGENZ\', \'0\', \'0xfb9e9e7150ccebfe42d58de1989c5283d0eaab2e\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Forgotten Runes Wizards Cult\', \'\', \'ForgottenRunesWizardsCult\', \'WIZARDS\', \'0\', \'0x521f9c7505005cfa19a8e5786a9c3c9c9f5e6f42\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Polymorphs\', \'\', \'Polymorphs\', \'MORPH\', \'0\', \'0x1cbb182322aee8ce9f4f1f98d7460173ee30af1f\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'ANON\', \'\', \'ANON\', \'ANON\', \'0\', \'0x77a679db25d504d83d59b32467545c5a3783c88b\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'DystoPunks V2\', \'\', \'DystoPunks V2\', \'DYSTO\', \'0\', \'0xbea8123277142de42571f1fac045225a1d347977\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Bulls On The Block\', \'\', \'BullsOnTheBlock\', \'BOTB\', \'0\', \'0x3a8778a58993ba4b941f85684d74750043a4bb5f\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Hash Demons\', \'\', \'HashDemons\', \'HASHDEMONS\', \'0\', \'0x032dd2a3c6d234aa9620d65eb618fcdc72be3dbb\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Super Yeti\', \'\', \'Super Yeti\', \'defra\', \'0\', \'0x3f0785095a660fee131eebcd5aa243e529c21786\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'PUNKS Comic\', \'\', \'PUNKS Comic\', \'COMIC\', \'0\', \'0x5ab21ec0bfa0b29545230395e3adaca7d552c948\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Bonsai\', \'\', \'Bonsai\', \'BNSI\', \'0\', \'0xec9c519d49856fd2f8133a0741b4dbe002ce211b\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Pix Zoo\', \'\', \'Pix Zoo\', \'PixZoo\', \'0\', \'0x5719be18d99d9c3bf967044932551c6788033ede\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Pix Puppies\', \'\', \'Pix Puppies\', \'PixPuppies\', \'0\', \'0xdf58c36af16bc9cbc9c15b435abbbd4ea3931815\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'LaMelo Ball Collectibles\', \'\', \'LaMelo Ball Collectibles\', \'LBC\', \'0\', \'0x139b522955d54482e7662927653abb0bfb6f19ba\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Faticorns\', \'\', \'Faticorns\', \'FATI\', \'0\', \'0x2dbe8b9b0d31aeadf1aafb823d859623a237faba\', \'https://api.etherscan.io/api\');');
        $this->addSql('INSERT INTO project (id, name, description, token_name, token_symbol, token_decimal, contract, etherscan_url) VALUES ('.Uuid::v4()->toBinary().', \'Fame Lady Squad\', \'\', \'FameLadySquad\', \'FLS\', \'0\', \'0xf3e6dbbe461c6fa492cea7cb1f5c5ea660eb1b47\', \'https://api.etherscan.io/api\');');*/
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        //$this->addSql('DELETE FROM project;');
    }
}
