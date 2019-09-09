<?php

use yii\db\Migration;

/**
 * Class m190909_064324_add_request_log_table
 */
class m190909_064324_add_request_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->db->createCommand('CREATE SEQUENCE request_log_id_seq;')->execute();

        $this->createTable('request_log', [
            'id' => $this->integer()->comment('Идентификатор')->defaultExpression("nextval('request_log_id_seq')"),
            'method' => $this->string()->comment('Тип запроса'),
            'uri' => $this->string()->comment('URL'),
            'params' => $this->json()->comment('Параметры запроса'),
            'client_agent' => $this->string()->comment('Информация о клиенте'),
            'client_ip' => $this->string()->comment('IP адресс'),
            'duration' => $this->float()->comment('Длительность запроса'),
            'created_date' => $this->timestamp()->comment('Дата создания'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('request_log');
        $this->db->createCommand('DROP SEQUENCE request_log_id_seq;')->execute();
    }
}
