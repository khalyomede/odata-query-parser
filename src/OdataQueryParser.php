<?php

declare(strict_types=1);

namespace Khalyomede;

use RuntimeException;
use InvalidArgumentException;

class OdataQueryParser {
	private const COUNT_KEY = "count";
	private const FILTER_KEY = "filter";
	private const FORMAT_KEY = "format";
	private const ORDER_BY_KEY = "orderby";
	private const SELECT_KEY = "select";
	private const SKIP_KEY = "skip";
	private const TOP_KEY = "top";

	private static string $url = "";
	private static string $queryString = "";
	private static array $queryStrings = [];
	private static bool $withDollar = false;
	private static string $selectKey = "";
	private static string $countKey = "";
	private static string $filterKey = "";
	private static string $formatKey = "";
	private static string $orderByKey = "";
	private static string $skipKey = "";
	private static string $topKey = "";

    /**
     * Parses a given URL, returns an associative array with the odata parts of the URL.
     *
     * @param string $url The URL to parse the query strings from. It should be a "complete" or "full" URL, which means that http://example.com will pass while example.com will not pass.
     * @param bool $withDollar When set to false, parses the odata keys without requiring the $ in front of odata keys.
     *
     * @return array The associative array containing the different odata keys.
     */
	public static function parse(string $url, bool $withDollar = true): array {
		$output = [];

        // Set the options and url
		static::$url = $url;
		static::$withDollar = $withDollar;

        // Verify the URL is valid
		if (\filter_var(static::$url, FILTER_VALIDATE_URL) === false) {
			throw new InvalidArgumentException('url should be a valid url');
		}

		static::setQueryStrings();

		static::setQueryParameterKeys();

        // Extract the different odata keys and store them in the output array
		if (static::selectQueryParameterIsValid()) {
			$output["select"] = static::getSelectColumns();
		}

		if (static::countQueryParameterIsValid()) {
			$output["count"] = true;
		}

		if (static::topQueryParameterIsValid()) {
			$top = static::getTopValue();

			if (!\is_numeric($top)) {
				throw new InvalidArgumentException('top should be an integer');
			}

			$top = $top;

			if ($top < 0) {
				throw new InvalidArgumentException('top should be greater or equal to zero');
			}

			$output["top"] = (int) $top;
		}

		if (static::skipQueryParameterIsValid()) {
			$skip = static::getSkipValue();

			if (!\is_numeric($skip)) {
				throw new InvalidArgumentException('skip should be an integer');
			}

			$skip = $skip;

			if ($skip < 0) {
				throw new InvalidArgumentException('skip should be greater or equal to zero');
			}

			$output["skip"] = (int) $skip;
		}

		if (static::orderByQueryParameterIsValid()) {
			$items = static::getOrderByColumnsAndDirections();

			$orderBy = \array_map(function($item) {
				$explodedItem = \explode(" ", $item);

				$explodedItem = array_values(array_filter($explodedItem, function($item) {
					return $item !== "";
				}));
				
				$property = $explodedItem[0];
				$direction = isset($explodedItem[1]) ? $explodedItem[1] : "asc";

				if ($direction !== "asc" && $direction !== "desc") {
					throw new InvalidArgumentException('direction should be either asc or desc');
				}

				return [
					"property" => $property,
					"direction" => $direction
				];
			}, $items);

			$output["orderBy"] = $orderBy;
		}

		if (static::filterQueryParameterIsValid()) {
			$ands = static::getFilterValue();

			$output["filter"] = $ands;
		}
		

		return $output;
	}

	private static function urlInvalid(): bool {
		return \filter_var(static::$url, FILTER_VALIDATE_URL) === false;
	}

	private static function setQueryStrings(): void {
		static::$queryString = static::getQueryString();
		static::$queryStrings = static::getQueryStrings();
	}

	private static function getQueryString(): string {
		$queryString = \parse_url(static::$url, PHP_URL_QUERY);

		return $queryString === null ? "" : $queryString;
	}

	private static function getQueryStrings(): array {
		$result = [];

		if (!empty(static::$queryString)) {
			\parse_str(static::$queryString, $result);
		}

		return $result;
	}

	private static function hasKey(string $key): bool {
		return isset(static::$queryStrings[$key]);
	}

	private static function selectQueryParameterIsValid(): bool {
		return static::hasKey(static::$selectKey) && !empty(static::$queryStrings[static::$selectKey]);
	}

	private static function countQueryParameterIsValid(): bool {
		return static::hasKey(static::$countKey) && (bool) trim(static::$queryStrings[static::$countKey]) === true;
	}

	private static function topQueryParameterIsValid(): bool {
		return static::hasKey(static::$topKey);
	}

	private static function skipQueryParameterIsValid(): bool {
		return static::hasKey(static::$skipKey);
	}

	private static function orderByQueryParameterIsValid(): bool {
		return static::hasKey(static::$orderByKey) && !empty(static::$queryStrings[static::$orderByKey]);
	}

	private static function filterQueryParameterIsValid(): bool {
		return static::hasKey(static::$filterKey) && !empty(static::$queryStrings[static::$filterKey]);
	}

	private static function getSelectColumns(): array {
		return array_map(function($column) {
			return trim($column);
		}, explode(",", static::$queryStrings[static::$selectKey]));
	}

	private static function getTopValue(): string {
		return trim(static::$queryStrings[static::$topKey]);
	}

	private static function getSkipValue(): string {
		return trim(static::$queryStrings[static::$skipKey]);
	}

	private static function getOrderByColumnsAndDirections(): array {
		return explode(",", static::$queryStrings[static::$orderByKey]);
	}

	private static function getFilterValue(): array {
		return array_map(function($and) {
			$items = [];

			preg_match("/(\w+)\s*(eq|ne|gt|ge|lt|le|in)\s*([\w',()\s.]+)/", $and, $items);

			$left = $items[1];
			$operator = static::getFilterOperatorName($items[2]);
			$right = static::getFilterRightValue($operator, $items[3]);

			/**
			 * @todo check whether [1], [2] and [3] are set -> will fix in a different PR
			 */

			return [
				"left" => $left,
				"operator" => $operator,
				"right" => $right
			];
		}, explode("and", static::$queryStrings[static::$filterKey]));
	}

	private static function setQueryParameterKeys(): void {
		static::$selectKey = static::getSelectKey();
		static::$countKey = static::getCountKey();
		static::$filterKey = static::getFilterKey();
		static::$formatKey = static::getFormatKey();
		static::$orderByKey = static::getOrderByKey();
		static::$skipKey = static::getSkipKey();
		static::$topKey = static::getTopKey();
	}

	private static function getSelectKey(): string {
		return static::$withDollar ? '$' . static::SELECT_KEY : static::SELECT_KEY;
	}
	
	private static function getCountKey(): string {
		return static::$withDollar ? '$' . static::COUNT_KEY : static::COUNT_KEY;
	}

	private static function getFilterKey(): string {
		return static::$withDollar ? '$' . static::FILTER_KEY : static::FILTER_KEY;
	}

	private static function getFormatKey(): string {
		return static::$withDollar ? '$' . static::FORMAT_KEY : static::FORMAT_KEY;
	}

	private static function getOrderByKey(): string {
		return static::$withDollar ? '$' . static::ORDER_BY_KEY : static::ORDER_BY_KEY;
	}

	private static function getSkipKey(): string {
		return static::$withDollar ? '$' . static::SKIP_KEY : static::SKIP_KEY;
	}

	private static function getTopKey(): string {
		return static::$withDollar ? '$' . static::TOP_KEY : static::TOP_KEY;
	}

	private static function getFilterOperatorName(string $operator): string {
        return match ($operator) {
            "eq" => "equal",
            "ne" => "notEqual",
            "gt" => "greaterThan",
            "ge" => "greaterOrEqual",
            "lt" => "lowerThan",
            "le" => "lowerOrEqual",
            "in" => "in",
            default => "unknown",
        };
	}

	private static function getFilterRightValue(string $operator, string $value): int|float|string|array {
		if ($operator !== "in") {
			if (is_numeric($value)) {
				if ((int) $value != $value) {
					return (float) $value;
				} else {
					return (int) $value;
				}
			} else {
				return str_replace("'", "", trim($value));
			}
		} else {
			$value = preg_replace("/^\s*\(|\)\s*$/", "", $value);
			$values = explode(",", $value);
			
			return array_map(function($value) {
				return static::getFilterRightValue("equal", $value);
			}, $values);
		}
	}
}
