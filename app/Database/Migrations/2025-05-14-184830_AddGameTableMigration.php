<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGameTableMigration extends Migration
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
            'data' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'guanyat' => [
                'type' => 'TINYINT',
            ],
            'punts' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'durada' => [
                'type' => 'INT',
                'constraint' => '11',
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => '11',
                'unsigned'       => true,
            ],
            'created_at datetime default current_timestamp',
            'deleted_at datetime default null',
            'updated_at datetime default current_timestamp on update current_timestamp',
        ]);
        $this->forge->addKey('id', true);
        // $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE', 'userFK');
        $this->forge->createTable('game');
    }

    public function down()
    {
        $this->forge->dropTable('game');
    }
}
