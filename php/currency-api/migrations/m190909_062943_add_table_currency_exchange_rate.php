<?php

use yii\db\Migration;

/**
 * Class m190909_062943_add_rable_current_exchange_rate
 */
class m190909_062943_add_table_currency_exchange_rate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->db->createCommand('CREATE SEQUENCE currency_exchange_rate_id_seq;')->execute();
        $this->createTable('currency_exchange_rate', [
            'id' => $this->integer()->comment('Идентификатор')->defaultExpression("nextval('currency_exchange_rate_id_seq')"),
            'source' => $this->string()->comment('Исходная валюта'),
            'target' => $this->string()->comment('Конечная валюта'),
            'coefficient' => $this->string()->comment('Курс'),
            'created_date' => $this->timestamp()->comment('Дата создания'),
            'updated_date' => $this->timestamp()->comment('Дата обновления'),
        ]);

        $this->addPrimaryKey('pk_currency_exchange_rate', 'currency_exchange_rate', ['id']);

        $this->createIndex(
            'uq_currency_exchange_rate_source_target',
            'currency_exchange_rate',
            ['source', 'target']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('currency_exchange_rate');
        $this->db->createCommand('DROP SEQUENCE currency_exchange_rate_id_seq;')->execute();
    }
}
