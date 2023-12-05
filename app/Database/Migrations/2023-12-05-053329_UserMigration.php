<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" => [
                "type" => "INT",
                "constraint" => 5,
                "unsigned" => true,
                "auto_increment" => true,
            ],
            "name" => [
                "type" => "varchar",
                "constraint" => 40,
                "null" => false

            ],
            "email" => [
                "type" => "varchar",
                "constraint" => 40,
                "null" => false,
                "unique" => true,

            ],
            "password" => [
                "type" => "varchar",
                "constraint" => 220,

            ]
        ]);
        $this->forge->addPrimaryKey("id");
        $this->forge->createTable("users");
        
        //
    }

    public function down()
    {
        $this->forge->dropTable("users");
        //
    }
}
