<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20140209210020 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs

         $this->addSql("ALTER TABLE  `sale_commission` ADD  `buyerIP`   VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '' COMMENT '购买人IP'  AFTER  `id`");

         $this->addSql("ALTER TABLE  `sale_linksale` ADD  `parnterIP`   VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '' COMMENT '推广人IP'  AFTER  `id`");

         $this->addSql("ALTER TABLE  `sale_offsale` ADD  `parnterIP`   VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '' COMMENT '推广人IP'  AFTER  `id`");

    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs

    }
}
