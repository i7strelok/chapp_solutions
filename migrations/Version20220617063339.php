<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220617063339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reserva_habitacion');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cliente AS SELECT id, nombre, email, telefono FROM cliente');
        $this->addSql('DROP TABLE cliente');
        $this->addSql('CREATE TABLE cliente (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nombre VARCHAR(60) NOT NULL, email VARCHAR(60) DEFAULT NULL, telefono VARCHAR(20) NOT NULL)');
        $this->addSql('INSERT INTO cliente (id, nombre, email, telefono) SELECT id, nombre, email, telefono FROM __temp__cliente');
        $this->addSql('DROP TABLE __temp__cliente');
        $this->addSql('CREATE TEMPORARY TABLE __temp__habitacion AS SELECT id, numero, descripcion, capacidad, precio_diario FROM habitacion');
        $this->addSql('DROP TABLE habitacion');
        $this->addSql('CREATE TABLE habitacion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, numero SMALLINT NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, capacidad SMALLINT NOT NULL, precio_diario DOUBLE PRECISION NOT NULL)');
        $this->addSql('INSERT INTO habitacion (id, numero, descripcion, capacidad, precio_diario) SELECT id, numero, descripcion, capacidad, precio_diario FROM __temp__habitacion');
        $this->addSql('DROP TABLE __temp__habitacion');
        $this->addSql('DROP INDEX IDX_C1F8E4A7B009290D');
        $this->addSql('DROP INDEX IDX_C1F8E4A7D53DA3AB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__habitacion_etiqueta AS SELECT habitacion_id, etiqueta_id FROM habitacion_etiqueta');
        $this->addSql('DROP TABLE habitacion_etiqueta');
        $this->addSql('CREATE TABLE habitacion_etiqueta (habitacion_id INTEGER NOT NULL, etiqueta_id INTEGER NOT NULL, PRIMARY KEY(habitacion_id, etiqueta_id), CONSTRAINT FK_C1F8E4A7B009290D FOREIGN KEY (habitacion_id) REFERENCES habitacion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C1F8E4A7D53DA3AB FOREIGN KEY (etiqueta_id) REFERENCES etiqueta (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO habitacion_etiqueta (habitacion_id, etiqueta_id) SELECT habitacion_id, etiqueta_id FROM __temp__habitacion_etiqueta');
        $this->addSql('DROP TABLE __temp__habitacion_etiqueta');
        $this->addSql('CREATE INDEX IDX_C1F8E4A7B009290D ON habitacion_etiqueta (habitacion_id)');
        $this->addSql('CREATE INDEX IDX_C1F8E4A7D53DA3AB ON habitacion_etiqueta (etiqueta_id)');
        $this->addSql('DROP INDEX IDX_188D2E3BB009290D');
        $this->addSql('DROP INDEX IDX_188D2E3BDE734E51');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reserva AS SELECT id, cliente_id, habitacion_id, fecha_inicio, fecha_fin, numero_huespedes, localizador FROM reserva');
        $this->addSql('DROP TABLE reserva');
        $this->addSql('CREATE TABLE reserva (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cliente_id INTEGER NOT NULL, habitacion_id INTEGER NOT NULL, fecha_inicio DATE NOT NULL, fecha_fin DATE NOT NULL, numero_huespedes SMALLINT NOT NULL, localizador VARCHAR(12) NOT NULL, CONSTRAINT FK_188D2E3BDE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_188D2E3BB009290D FOREIGN KEY (habitacion_id) REFERENCES habitacion (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO reserva (id, cliente_id, habitacion_id, fecha_inicio, fecha_fin, numero_huespedes, localizador) SELECT id, cliente_id, habitacion_id, fecha_inicio, fecha_fin, numero_huespedes, localizador FROM __temp__reserva');
        $this->addSql('DROP TABLE __temp__reserva');
        $this->addSql('CREATE INDEX IDX_188D2E3BB009290D ON reserva (habitacion_id)');
        $this->addSql('CREATE INDEX IDX_188D2E3BDE734E51 ON reserva (cliente_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reserva_habitacion (reserva_id INTEGER NOT NULL, habitacion_id INTEGER NOT NULL, PRIMARY KEY(reserva_id, habitacion_id))');
        $this->addSql('CREATE INDEX IDX_F589577FD67139E8 ON reserva_habitacion (reserva_id)');
        $this->addSql('CREATE INDEX IDX_F589577FB009290D ON reserva_habitacion (habitacion_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cliente AS SELECT id, nombre, email, telefono FROM cliente');
        $this->addSql('DROP TABLE cliente');
        $this->addSql('CREATE TABLE cliente (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nombre VARCHAR(60) NOT NULL, email VARCHAR(60) DEFAULT NULL, telefono SMALLINT NOT NULL)');
        $this->addSql('INSERT INTO cliente (id, nombre, email, telefono) SELECT id, nombre, email, telefono FROM __temp__cliente');
        $this->addSql('DROP TABLE __temp__cliente');
        $this->addSql('CREATE TEMPORARY TABLE __temp__habitacion AS SELECT id, numero, descripcion, capacidad, precio_diario FROM habitacion');
        $this->addSql('DROP TABLE habitacion');
        $this->addSql('CREATE TABLE habitacion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, numero SMALLINT NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, capacidad SMALLINT NOT NULL, precio_diario NUMERIC(5, 2) NOT NULL)');
        $this->addSql('INSERT INTO habitacion (id, numero, descripcion, capacidad, precio_diario) SELECT id, numero, descripcion, capacidad, precio_diario FROM __temp__habitacion');
        $this->addSql('DROP TABLE __temp__habitacion');
        $this->addSql('DROP INDEX IDX_C1F8E4A7B009290D');
        $this->addSql('DROP INDEX IDX_C1F8E4A7D53DA3AB');
        $this->addSql('CREATE TEMPORARY TABLE __temp__habitacion_etiqueta AS SELECT habitacion_id, etiqueta_id FROM habitacion_etiqueta');
        $this->addSql('DROP TABLE habitacion_etiqueta');
        $this->addSql('CREATE TABLE habitacion_etiqueta (habitacion_id INTEGER NOT NULL, etiqueta_id INTEGER NOT NULL, PRIMARY KEY(habitacion_id, etiqueta_id))');
        $this->addSql('INSERT INTO habitacion_etiqueta (habitacion_id, etiqueta_id) SELECT habitacion_id, etiqueta_id FROM __temp__habitacion_etiqueta');
        $this->addSql('DROP TABLE __temp__habitacion_etiqueta');
        $this->addSql('CREATE INDEX IDX_C1F8E4A7B009290D ON habitacion_etiqueta (habitacion_id)');
        $this->addSql('CREATE INDEX IDX_C1F8E4A7D53DA3AB ON habitacion_etiqueta (etiqueta_id)');
        $this->addSql('DROP INDEX IDX_188D2E3BDE734E51');
        $this->addSql('DROP INDEX IDX_188D2E3BB009290D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reserva AS SELECT id, cliente_id, habitacion_id, fecha_inicio, fecha_fin, numero_huespedes, localizador FROM reserva');
        $this->addSql('DROP TABLE reserva');
        $this->addSql('CREATE TABLE reserva (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cliente_id INTEGER NOT NULL, habitacion_id INTEGER NOT NULL, fecha_inicio DATE NOT NULL, fecha_fin DATE NOT NULL, numero_huespedes SMALLINT NOT NULL, localizador VARCHAR(12) NOT NULL)');
        $this->addSql('INSERT INTO reserva (id, cliente_id, habitacion_id, fecha_inicio, fecha_fin, numero_huespedes, localizador) SELECT id, cliente_id, habitacion_id, fecha_inicio, fecha_fin, numero_huespedes, localizador FROM __temp__reserva');
        $this->addSql('DROP TABLE __temp__reserva');
        $this->addSql('CREATE INDEX IDX_188D2E3BDE734E51 ON reserva (cliente_id)');
        $this->addSql('CREATE INDEX IDX_188D2E3BB009290D ON reserva (habitacion_id)');
    }
}
