<?php
namespace Lib\Models;

interface ISearchable {

	/**
	 * Sets one search criterion for future search
	 *
	 * @param      string  $criterion
	 * @param      string  $value
	 */
	public function addSearchCriterion(string $criterion, string $value);

	public function setLimit(int $limit);
	public function setOffset(int $offset);

	/**
	 * Template for virtual search method.
	 */
	public function search();
}