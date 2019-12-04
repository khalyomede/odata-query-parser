<?php

use PHPUnit\Framework\TestCase;
use Khalyomede\OdataQueryParser;

final class Filter extends TestCase {
	public function testShouldReturnEmptyArrayIfEmptyFilter(): void {
		$expected = [];
		$actual = OdataQueryParser::parse("http://example.com/api/user?\$filter=");

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnEqualClause(): void {
		$expected = [
			"filter" => [
				["left" => "name", "operator" => "equal", "right" => "foo"]
			]
		];
		$actual = OdataQueryParser::parse("http://example.com/api/user?\$filter=name%20eq%20%27foo%27");

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnEqualClauseWithFloat(): void {
		$this->assertIsFloat(OdataQueryParser::parse("http://example.com/api/user?\$filter=age%20eq%2042.42")["filter"][0]["right"]);
	}

	public function testShouldReturnEqualClauseWithInteger(): void {
		$this->assertIsInt(OdataQueryParser::parse("http://example.com/api/user?\$filter=age%20eq%2042")["filter"][0]["right"]);
	}

	public function testShouldReturnEqualClauseWithSpacedStrings(): void {
		$expected = [
			"filter" => [
				["left" => "name", "operator" => "equal", "right" => " foo "]
			]
		];
		$actual = OdataQueryParser::parse("http://example.com/api/user?\$filter=name%20eq%20%27%20foo%20%27");

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnNotEqualClause(): void {
		$expected = [
			"filter" => [
				["left" => "name", "operator" => "notEqual", "right" => "foo"]
			]
		];
		$actual = OdataQueryParser::parse("http://example.com/api/user?\$filter=name%20ne%20%27foo%27");

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnGreaterThanClause(): void {
		$expected = [
			"filter" => [
				["left" => "age", "operator" => "greaterThan", "right" => 20]
			]
		];
		$actual = OdataQueryParser::parse("http://example.com/api/user?\$filter=age%20gt%2020");

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnGreaterOrEqualToClause(): void {
		$expected = [
			"filter" => [
				["left" => "age", "operator" => "greaterOrEqual", "right" => 21]
			]
		];
		$actual = OdataQueryParser::parse("http://example.com/api/user?\$filter=age%20ge%2021");

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnLowerThanClause(): void {
		$expected = [
			"filter" => [
				["left" => "age", "operator" => "lowerThan", "right" => 42]
			]
		];
		$actual = OdataQueryParser::parse("http://example.com/api/user?\$filter=age%20lt%2042");

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnLowerOrEqualToClause(): void {
		$expected = [
			"filter" => [
				["left" => "age", "operator" => "lowerOrEqual", "right" => 42]
			]
		];
		$actual = OdataQueryParser::parse("http://example.com/api/user?\$filter=age%20le%2042");

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnInClause(): void {
		$expected = [
			"filter" => [
				["left" => "city", "operator" => "in", "right" => ["Paris", "Malaga", "London"]]
			]
		];
		$actual = OdataQueryParser::parse("http://example.com/api/user?\$filter=city%20in%20(%27Paris%27,%20%27Malaga%27,%20%27London%27)");

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnMultipleClauseSeparatedByTheAndOperator(): void {
		$expected = [
			"filter" => [
				["left" => "city", "operator" => "in", "right" => [" Paris", " Malaga ", "London "]],
				["left" => "name", "operator" => "equal", "right" => "foo"],
				["left" => "age", "operator" => "greaterThan", "right" => 20]
			]
		];
		$actual = OdataQueryParser::parse("http://example.com/api/user?\$filter=city%20in%20(%27%20Paris%27,%20%27%20Malaga%20%27,%20%27London%20%27)%20and%20name%20eq%20%27foo%27%20and%20age%20gt%2020");

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnIntegersIfInIntegers(): void {
		$expected = [
			"filter" => [
				["left" => "age", "operator" => "in", "right" => [21, 31, 41]]
			]
		];
		$actual = OdataQueryParser::parse("http://example.com/api/user?\$filter=age%20in%20(21,%2031,%2041)");

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnIntegersIfInFloats(): void {
		$expected = [
			"filter" => [
				["left" => "age", "operator" => "in", "right" => [21.42, 31.42, 41.42]]
			]
		];
		$actual = OdataQueryParser::parse("http://example.com/api/user?\$filter=age%20in%20(21.42,%2031.42,%2041.42)");

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnFloatIfCheckingInFloat(): void {
		$this->assertIsFloat(OdataQueryParser::parse("http://example.com/api/user?\$filter=taxRate%20in%20(19.5,%2020)")["filter"][0]["right"][0]);
	}
}
