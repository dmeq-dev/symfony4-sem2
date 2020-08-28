<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200826144229 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD is_confirmed TINYINT(1) DEFAULT NULL, ADD token VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE user SET is_confirmed = TRUE, token =SUBSTR(HEX(SHA2(CONCAT(NOW(), RAND(), UUID()), 256)), 1, 50)');
        $this->addSql("ALTER TABLE user MODIFY is_confirmed TINYINT(1) NOT NULL, MODIFY token VARCHAR(255) NOT NULL");
        
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP is_confirmed, DROP token');
    }
}
