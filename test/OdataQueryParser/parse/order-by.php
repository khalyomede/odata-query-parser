<?php

use PHPUnit\Framework\TestCase;
use Khalyomede\OdataQueryParser;

final class OrderBy extends TestCase {
	//orderBy
	public function testShouldReturnThePropertyInTheOrderBy(): void {
		$expected = ["orderBy" => [["property" => "foo", "direction" => "asc"]]];
		$actual = OdataQueryParser::parse('http://example.com/api/user?$orderby=foo');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnAllThePropertiesInTheOrderBy(): void {
		$expected = ["orderBy" => [
			["property" => "foo", "direction" => "asc"],
			["property" => "bar", "direction" => "asc"]
		]];
		$actual = OdataQueryParser::parse('http://example.com/api/user?$orderby=foo,bar');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnThePropertyInTheOrderByEvenIfFilledWithSpaces(): void {
		$expected = ["orderBy" => [["property" => "foo", "direction" => "asc"]]];
		$actual = OdataQueryParser::parse('http://example.com/api/user?$orderby=%20foo%20');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnAnEmptyArrayIfOrderByIsEmpty(): void {
		$expected = [];
		$actual = OdataQueryParser::parse('http://example.com/api/user?$orderby=');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnOrderByPropertyInAscDirectionIfSpecified(): void {
		$expected = ["orderBy" => [["property" => "foo", "direction" => "asc"]]];
		$actual = OdataQueryParser::parse('http://example.com/api/user?$orderby=foo%20asc');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnOrderByPropertyInAscDirectionIfSpecifiedEvenIfFilledWithSpaces(): void {
		$expected = ["orderBy" => [["property" => "foo", "direction" => "asc"]]];
		$actual = OdataQueryParser::parse('http://example.com/api/user?$orderby=%20foo%20%20%20asc%20');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnThePropertyInDescDirectionIfSpecified(): void {
		$expected = ["orderBy" => [["property" => "foo", "direction" => "desc"]]];
		$actual = OdataQueryParser::parse('http://example.com/api/user?$orderby=foo%20desc');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnThePropertyInDescDirectionIfSpecifiedEvenIfFilledWithSpaces(): void {
		$expected = ["orderBy" => [["property" => "foo", "direction" => "desc"]]];
		$actual = OdataQueryParser::parse('http://example.com/api/user?$orderby=%20foo%20%20%20desc%20');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldThrowExceptionIfDirectionInvalid(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("direction should be either asc or desc");

		OdataQueryParser::parse('http://example.com/api/user?$orderby=foo%20ascendant');
	}

	// orderBy (no dollar mode)
	public function testShouldReturnThePropertyInTheOrderByInNonDollarMode(): void {
		$expected = ["orderBy" => [["property" => "foo", "direction" => "asc"]]];
		$actual = OdataQueryParser::parse('http://example.com/api/user?orderby=foo', $includeDollar = false);

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnThePropertyInTheOrderByInNonDollarModeEvenIfFilledWithSpaces(): void {
		$expected = ["orderBy" => [["property" => "foo", "direction" => "asc"]]];
		$actual = OdataQueryParser::parse('http://example.com/api/user?orderby=%20foo%20', $includeDollar = false);

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnAnEmptyArrayIfOrderByIsEmptyInNonDollarMode(): void {
		$expected = [];
		$actual = OdataQueryParser::parse('http://example.com/api/user?orderby=', $includeDollar = false);

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnOrderByPropertyInAscDirectionIfSpecifiedInNonDollarMode(): void {
		$expected = ["orderBy" => [["property" => "foo", "direction" => "asc"]]];
		$actual = OdataQueryParser::parse('http://example.com/api/user?orderby=foo%20asc', $includeDollar = false);

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnOrderByPropertyInAscDirectionIfSpecifiedInNonDollarModeEvenIfFilledWithSpaces(): void {
		$expected = ["orderBy" => [["property" => "foo", "direction" => "asc"]]];
		$actual = OdataQueryParser::parse('http://example.com/api/user?orderby=%20foo%20%20%20asc%20', $includeDollar = false);

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnThePropertyInDescDirectionIfSpecifiedInNonDollarMode(): void {
		$expected = ["orderBy" => [["property" => "foo", "direction" => "desc"]]];
		$actual = OdataQueryParser::parse('http://example.com/api/user?orderby=foo%20desc', $includeDollar = false);

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnThePropertyInDescDirectionIfSpecifiedInNonDollarModeEvenIfFilledWithSpaces(): void {
		$expected = ["orderBy" => [["property" => "foo", "direction" => "desc"]]];
		$actual = OdataQueryParser::parse('http://example.com/api/user?orderby=%20foo%20%20%20desc%20', $includeDollar = false);

		$this->assertEquals($expected, $actual);
	}

	public function testShouldThrowExceptionIfDirectionInvalidInNonDollarMode(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("direction should be either asc or desc");

		OdataQueryParser::parse('http://example.com/api/user?orderby=foo%20ascendant', $includeDollar = false);
	}

	public function testShouldReturnMultipleValues(): void {
		$expected = ["select" => ["firstName", "lastName"], "orderBy" => [["property" => "id", "direction" => "asc"]], "top" => 10, "skip" => 10];
		$actual = OdataQueryParser::parse('http://example.com/api/user?$select=firstName,lastName&$orderby=id&$top=10&$skip=10');

		$this->assertEquals($expected, $actual);
	}
}