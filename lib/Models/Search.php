<?php
namespace Lib\Models;

abstract class Search extends Model implements ISearchable {
	/**
	 * search criteria validation rules. for now simple type check, can be and will be extended
	 * @var        array
	 */
	protected $criteriaForValidation = [];

	/**
	 * search criteria themselves: associative array of search_type and search_value
	 * @var        array
	 */
	protected $criteria = [];

	/**
	 * default limit and offset for pagination. Offset = (limit-1) * #page
	 * @var        integer
	 */
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

	/**
	 * Sets one search criterion for future search
	 *
	 * @param      string            $criterion  search_type
	 * @param      string            $value      search_value
	 *
	 * @throws     \Exception        invalid criterion cannot be set
	 *
	 * @return     ISearchable|self  enables method chaining
	 */
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
	/**
	 * Determines if search_value is valid.
	 *
	 * @param      string        $key    search_type
	 * @param      string        $value  search_value
	 *
	 * @throws     Exception     Unexpected validation type, non existing in $this->criteriaForValidation []
	 *
	 * @return     bool|boolean
	 */
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