<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUsersTableMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
                'unsigned'       => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'edat' => [
                'type' => 'INT',
                'constraint' => '3',
            ],
            'pais' => [
                'type' => 'VARCHAR',
                'constraint' => '11',
            ],
            'created_at datetime default current_timestamp',
            'deleted_at datetime default null',
            'updated_at datetime default current_timestamp on update current_timestamp',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }

}
