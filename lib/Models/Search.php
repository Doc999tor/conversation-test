<?php
namespace Lib\Models;

abstract class Search extends Model implements ISearchable {
	protected $criteriaForValidation = [];
	protected $criteria = [];

	protected $limit = 1000;
	protected $offset = 0;

	function __construct() {}
	public function search() {/* domain specific sql query */}

	public function setLimit(int $limit) {
		$this->limit = $limit;
	}
	public function setOffset(int $offset) {
		$this->offset = $offset;
	}

	public function addSearchCriterion(string $criterion, string $value):ISearchable {
		$value = trim($value);

		if ($this->isCriterionValid($criterion, $value)) {
			$this->criteria[$criterion] = $value;
		} else {
			throw new \Exception("Criterion {$criterion} is invalid", 1);
		}
		return $this;
	}

	protected function isCriterionValid(string $criterion, string $value):bool {
		return $this->isKeyValid($criterion) && $this->isValueValid($criterion, $value);
	}

	protected function isKeyValid(string $key):bool {
		return in_array($key, array_keys($this->criteriaForValidation));
	}
	protected function isValueValid(string $key, string $value):bool {
		switch ($this->criteriaForValidation[$key]) {
			case 'string':
				return (bool)mb_strlen($value);
				break;

			case 'number':
				return is_numeric($value);
				break;

			case 'bool':
				return $value === 'true' || $value === 'false';
				break;

			default:
				throw new Exception("Unexpected validation type", 1);
				break;
		}
	}
}