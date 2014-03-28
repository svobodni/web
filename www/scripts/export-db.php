<?php
namespace V6ak\DB\Export {
	class Exporter
	{

		/**
		 * @var \Doctrine\DBAL\Connection $db ;
		 */
		private $db;


		/**
		 * @param \Doctrine\ORM\EntityManager $em
		 */
		function __construct($em)
		{
			$this->db = $em->getConnection();
		}


		function exportCreateTable($name)
		{
			$results = $this->db->executeQuery('SHOW CREATE TABLE ' . $name)->fetchAll();
			return $results[0]['Create Table'];
		}


		function exportTableData($name)
		{
			$db = $this->db;
			$data = $this->db->executeQuery('SELECT * FROM ' . $name)->fetchAll();
			return implode(";\n",
				array_map(function ($row) use ($name, $db) {
					return "INSERT INTO $name SET " . implode(", ", array_map(function ($col, $value) use ($db) {
						return "$col = " . $db->quote($value);
					}, array_keys($row), array_values($row)));
				}, $data)
			);
		}


		function listColumns($name)
		{
			return $this->db->executeQuery("SHOW COLUMNS FROM $name")->fetchAll();
		}


		function formatInsertInto($tableName, $columns, $data)
		{
			$db = $this->db;
			$exportValues = function ($values) use ($columns, $db) {
				$quotedDataForColumn = function ($colName) use ($db, $values) {
					$value = $values[$colName];
					if ($value === null) {
						return 'NULL';
					} else {
						return $db->quote($value);
					}
				};
				return "(" . implode(", ", array_map($quotedDataForColumn, $columns)) . ")";
			};
			return !$data
				? "-- (no data)\n"
				: "INSERT INTO $tableName (" . implode(", ", $columns) . ") VALUES\n" . implode(",\n", array_map($exportValues, $data));
		}


		function exportTableList()
		{
			return array_map(function ($row) {
				$values = array_values($row);
				return $values[0];
			}, $this->db->executeQuery('SHOW TABLES')->fetchAll());
		}


		public function exportByRules($rules, $createTable)
		{
			$errors = array();
			$collectError = function ($msg) use (&$errors) {
				$errors[] = $msg;
			};
			$data = $this->internalExportByRules($rules, $createTable, $collectError);
			return (object)array(
				'errors' => $errors,
				'data' => $data
			);
		}


		private function internalExportByRules($rules, $createTable, $collectError)
		{
			$dbTables = $this->exportTableList();
			$definedTables = array_keys($rules);
			$this->testMissingAndExtraItems($definedTables, $dbTables, "[global]", 'table', $collectError);
			$commands = array();
			$commands[] = "SET foreign_key_checks = 0";
			foreach ($rules as $table => $strategy) {
				if ($createTable) {
					$commands[] = $this->exportCreateTable($table);
				}
				$cols = array_map(function ($x) {
					return $x['Field'];
				}, $this->listColumns($table));
				$tableDataWithStructure = $strategy($this->db, $table, $collectError);
				$disableColumnCheck = isset($tableDataWithStructure->disableColumnCheck) && $tableDataWithStructure->disableColumnCheck;
				if (!$disableColumnCheck) {
					$this->testMissingAndExtraItems($tableDataWithStructure->columns, $cols, "table $table", 'column', $collectError);
				}
				$commands[] = $this->formatInsertInto($table, $tableDataWithStructure->columns, $tableDataWithStructure->data);
			}
			$commands[] = "SET foreign_key_checks = 1";
			return $commands;
		}


		private function testMissingAndExtraItems($definedItems, $existingItems, $location, $name, $collectError)
		{
			foreach (array_diff($definedItems, $existingItems) as $tableName) {
				$collectError("[$location] Extra $name '$tableName'");
			}
			foreach (array_diff($existingItems, $definedItems) as $tableName) {
				$collectError("[$location] Missing $name '$tableName'");
			}
		}

	}
}
namespace V6ak\DB\Export\DSL {
	use Doctrine\DBAL\Connection;

	function parseCols($columnNames)
	{
		return is_string($columnNames)
			? array_map('trim', explode(",", str_replace("|", ",", $columnNames)))
			: $columnNames;
	}

	function filtered($condition, $columnNames)
	{
		$cols = parseCols($columnNames);
		return function ($db, $table, $collectError) use ($cols, $condition) {
			/** @var Connection $db */
			$data = $db->executeQuery("SELECT " . implode(", ", $cols) . " FROM $table WHERE $condition")->fetchAll();
			return (object)array(
				'columns' => $cols,
				'data' => $data
			);
		};
	}

	function ok($columnNames)
	{
		return filtered("true", $columnNames);
	}

	function customData($data)
	{
		if (!$data) {
			// I don't just `return skipData();`, because there is a slight semantic difference; skipData does not check column names.
			throw new \RuntimeException("When using customData, you have to specify at least one row. If you want to skip data, use skipData() instead.");
		};
		$cols = array_keys(call_user_func_array('array_merge', $data));
		return function () use ($data, $cols) {
			return (object)array(
				'columns' => $cols,
				'data' => $data
			);
		};
	}

	function skipData()
	{
		return function () {
			return (object)array(
				'disableColumnCheck' => true,
				'columns' => array(),
				'data' => array()
			);
		};
	}

	function csv($fileName, $colNames)
	{
		$dataIndexed = array_map('str_getcsv', array_map('trim', explode("\n", file_get_contents($fileName))));
		$cols = parseCols($colNames);
		$data = array_map(function ($indexedRow) use ($cols) {
			return array_combine($cols, $indexedRow);
		}, $dataIndexed);
		return function () use ($cols, $data) {
			return (object)array(
				'data' => $data,
				'columns' => $cols
			);
		};
	}
}

namespace {

	$loader = require_once __DIR__ . '/../../vendor/autoload.php';

	header('Content-Type: text/plain; charset=utf-8');

	// create and run application
	$configurator = new \Venne\Config\Configurator(__DIR__ . '/../../app', $loader);
	$configurator->enableDebugger();
	$configurator->enableLoader();
	//\Nette\Diagnostics\Debugger::enable('development', __DIR__ . '/../../app/log');
	/** @var \Doctrine\ORM\EntityManager $db */
	$db = $configurator->createContainer()->entityManager;


	use V6ak\DB\Export\Exporter;

	$rules = require __DIR__ . '/../../export/export-db.cfg.php';

	$e = new Exporter($db);
	$exportResults = $e->exportByRules($rules, false);
	foreach ($exportResults->errors as $error) {
		echo "WARNING: $error\n";
	}
	echo implode(";\n\n", $exportResults->data);

}