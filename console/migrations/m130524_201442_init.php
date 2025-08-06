<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%auth_rule}}', [
            'name' => $this->string(64)->notNull(),
            'data' => $this->binary(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'PRIMARY KEY ([[name]])',
        ]);

        $this->createTable('{{%auth_item}}', [
            'name' => $this->string(64)->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY ([[name]])',
        ]);

        // creates index for column `rule_name`
        $this->createIndex(
            'idx-auth_item-rule_name',
            'auth_item',
            'rule_name'
        );

        // add foreign key for table `auth_name`
        $this->addForeignKey(
            'fk-auth_item-rule_name',
            'auth_item',
            'rule_name',
            'auth_rule',
            'name',
            'CASCADE'
        );

        $this->createTable('{{%auth_item_child}}', [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
            'PRIMARY KEY ([[parent]], [[child]])',
        ]);

        // creates index for column `parent`
        $this->createIndex(
            'idx-auth_item_child-parent',
            'auth_item_child',
            'parent'
        );

        // add foreign key for table `auth_item`
        $this->addForeignKey(
            'fk-auth_item_child-parent',
            'auth_item_child',
            'parent',
            'auth_item',
            'name',
            'CASCADE'
        );

        // creates index for column `child`
        $this->createIndex(
            'idx-auth_item_child-child',
            'auth_item_child',
            'child'
        );

        // add foreign key for table `auth_item`
        $this->addForeignKey(
            'fk-auth_item_child-child',
            'auth_item_child',
            'child',
            'auth_item',
            'name',
            'CASCADE'
        );

        $this->createTable('{{%auth_assignment}}', [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->string(64)->notNull(),
            'created_at' => $this->integer(),
            'PRIMARY KEY ([[item_name]], [[user_id]])',
        ]);

        // creates index for column `item_name`
        $this->createIndex(
            'idx-auth_assignment-item_name',
            'auth_assignment',
            'item_name'
        );

        // add foreign key for table `auth_item`
        $this->addForeignKey(
            'fk-auth_assignment-item_name',
            'auth_assignment',
            'item_name',
            'auth_item',
            'name',
            'CASCADE'
        );

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        // drops foreign key for table `auth_item`
        $this->dropForeignKey(
            'fk-auth_assignment-item_name',
            'auth_assignment'
        );

        // drops index for column `item_name`
        $this->dropIndex(
            'idx-auth_assignment-item_name',
            'auth_assignment'
        );

        $this->dropTable('{{%auth_assignment}}');
        // drops foreign key for table `auth_item`
        $this->dropForeignKey(
            'fk-auth_item_child-child',
            'auth_item_child'
        );

        // drops index for column `child`
        $this->dropIndex(
            'idx-auth_item_child-child',
            'auth_item_child'
        );

        // drops foreign key for table `auth_item`
        $this->dropForeignKey(
            'fk-auth_item_child-parent',
            'auth_item_child'
        );

        // drops index for column `parent`
        $this->dropIndex(
            'idx-auth_item_child-parent',
            'auth_item_child'
        );

        $this->dropTable('{{%auth_item_child}}');
        // drops foreign key for table `auth_item`
        $this->dropForeignKey(
            'fk-auth_item-rule_name',
            'auth_item'
        );

        // drops index for column `rule_name`
        $this->dropIndex(
            'idx-auth_item-rule_name',
            'auth_item'
        );
        $this->dropTable('{{%auth_item}}');

        $this->dropTable('{{%auth_rule}}');

        $this->dropTable('{{%user}}');
    }
}
