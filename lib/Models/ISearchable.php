<?php
namespace Lib\Models;

interface ISearchable {
	public function addSearchCriterion(string $criterion, string $value);
	public function setLimit(int $limit);
	public function setOffset(int $offset);

	public function search();
}