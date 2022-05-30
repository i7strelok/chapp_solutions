<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220530184408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cliente (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nombre VARCHAR(60) NOT NULL, email VARCHAR(60) DEFAULT NULL, telefono SMALLINT NOT NULL)');
        $this->addSql('CREATE TABLE etiqueta (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nombre VARCHAR(60) NOT NULL, descripcion VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE habitacion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, numero SMALLINT NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, capacidad SMALLINT NOT NULL, precio_diario NUMERIC(5, 2) NOT NULL)');
        $this->addSql('CREATE TABLE habitacion_etiqueta (habitacion_id INTEGER NOT NULL, etiqueta_id INTEGER NOT NULL, PRIMARY KEY(habitacion_id, etiqueta_id))');
        $this->addSql('CREATE INDEX IDX_C1F8E4A7B009290D ON habitacion_etiqueta (habitacion_id)');
        $this->addSql('CREATE INDEX IDX_C1F8E4A7D53DA3AB ON habitacion_etiqueta (etiqueta_id)');
        $this->addSql('CREATE TABLE reserva (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cliente_id INTEGER NOT NULL, fecha_inicio DATE NOT NULL, fecha_fin DATE NOT NULL, numero_huespedes SMALLINT NOT NULL)');
        $this->addSql('CREATE INDEX IDX_188D2E3BDE734E51 ON reserva (cliente_id)');
        $this->addSql('CREATE TABLE reserva_habitacion (reserva_id INTEGER NOT NULL, habitacion_id INTEGER NOT NULL, PRIMARY KEY(reserva_id, habitacion_id))');
        $this->addSql('CREATE INDEX IDX_F589577FD67139E8 ON reserva_habitacion (reserva_id)');
        $this->addSql('CREATE INDEX IDX_F589577FB009290D ON reserva_habitacion (habitacion_id)');
        $this->addSql('CREATE TABLE usuario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05DE7927C74 ON usuario (email)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cliente');
        $this->addSql('DROP TABLE etiqueta');
        $this->addSql('DROP TABLE habitacion');
        $this->addSql('DROP TABLE habitacion_etiqueta');
        $this->addSql('DROP TABLE reserva');
        $this->addSql('DROP TABLE reserva_habitacion');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
