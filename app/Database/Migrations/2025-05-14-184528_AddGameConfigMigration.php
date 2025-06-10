<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGameConfigMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
                'unsigned'       => true,
            ],
            'tema' => [
                'type' => 'VARCHAR',
                'constraint' => '5',
            ],
            'musica' => [
                'type' => 'VARCHAR',
                'constraint' => '11',
            ],
            'dificultat' => [
                'type' => 'VARCHAR',
                'constraint' => '11',
            ],
            'created_at datetime default current_timestamp',
            'deleted_at datetime default null',
            'updated_at datetime default current_timestamp on update current_timestamp',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('gameconfig');
    }

    public function down()
    {
        $this->forge->dropTable('gameconfig');
    }
}
