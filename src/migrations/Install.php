<?php
namespace abmat\chatgpt\migrations;

use craft\db\Migration;

use abmat\chatgpt\records\PromptRecord;

/**
 * Install migration.
 */
class Install extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp(): bool
	{
		$this->createTables();
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function safeDown(): bool
	{
		$this->removeTables();

		return true;
	}

	/**
	 * @return void
	 */
	protected function createTables(): void
	{

		$this->archiveTableIfExists(PromptRecord::tableName());
		$this->createTable(PromptRecord::tableName(), [
			'id' => $this->primaryKey(),
			'label' => $this->string()->notNull(),
			'template' => $this->string()->notNull(),
			'active' => $this->boolean()->notNull()->defaultValue(true),
			'temperature' => $this->float()->notNull(),
			'replaceText' => $this->integer()->notNull()->defaultValue(1),

			'dateCreated' => $this->dateTime()->notNull(),
			'dateUpdated' => $this->dateTime()->notNull(),
			'uid' => $this->uid(),
		]);
	}

	/**
	 * @return void
	 */
	protected function removeTables()
	{
		$tables = [
			PromptRecord::tableName()
		];
		foreach ($tables as $table) {
			$this->dropTableIfExists($table);
		}
	}
}
