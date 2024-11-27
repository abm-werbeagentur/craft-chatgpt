<?php

namespace abmat\chatgpt\migrations;

use Craft;
use craft\db\Migration;

use abmat\chatgpt\records\PromptRecord;

/**
 * m241127_060255_increase_template_field_length migration.
 */
class m241127_060255_increase_template_field_length extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->alterColumn(PromptRecord::tableName(), 'template', $this->string(1024)->notNull());
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m241127_060255_increase_template_field_length cannot be reverted.\n";
        return false;
    }
}
