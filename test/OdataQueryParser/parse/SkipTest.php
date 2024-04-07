<?php

use PHPUnit\Framework\TestCase;
use Khalyomede\OdataQueryParser;

final class SkipTest extends TestCase {
	public function testShouldThrowAnInvalidArgumentExceptionIfSkipParameterIsLowerThanZero(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("skip should be greater or equal to zero");

		OdataQueryParser::parse('http://example.com/?$skip=-1');
	}

	public function testShouldThrowAnInvalidArgumentExceptionIfSkipIsNotAnInteger(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("skip should be an integer");

		OdataQueryParser::parse('http://example.com/?$skip=test');
	}

	public function testShouldContainTheSkipValueIfProvidedInQueryParameters(): void {
		$expected = ["skip" => 42];
		$actual = OdataQueryParser::parse('http://example.com/api/user?$skip=42');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldContainTheSkipValueIfProvidedInTheQueryParameterAndFilledWithSpaces(): void {
		$expected = ["skip" => 42];
		$actual = OdataQueryParser::parse('http://example.com/api/user?$skip=%2042%20');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldContainAnEmptyArrayIfSkipParameterIsEmpty(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("skip should be an integer");
		
		OdataQueryParser::parse('http://example.com/api/user?$skip=');
	}

	public function testShouldNotThrowExceptionIfSkipIsEqualToZero(): void {
		$expected = ["skip" => 0];
		$actual = OdataQueryParser::parse('http://example.com/api/user?$skip=0');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnAnIntegerForTheSkipValue(): void {
		$this->assertIsInt(OdataQueryParser::parse('http://example.com/api/user?$skip=42')["skip"]);
	}

	// skip (non dollar mode)
	public function testShouldThrowAnInvalidArgumentExceptionIfSkipParameterIsLowerThanZeroInNonDolalrMode(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("skip should be greater or equal to zero");

		OdataQueryParser::parse('http://example.com/?skip=-1', $includeDollar = false);
	}

	public function testShouldThrowAnInvalidArgumentExceptionIfSkipIsNotAnIntegerInNonDollarMode(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("skip should be an integer");

		OdataQueryParser::parse('http://example.com/?skip=test', $includeDollar = false);
	}

	public function testShouldContainTheSkipValueIfProvidedInQueryParametersInNonDollarMode(): void {
		$expected = ["skip" => 42];
		$actual = OdataQueryParser::parse('http://example.com/api/user?skip=42', $includeDollar = false);

		$this->assertEquals($expected, $actual);
	}

	public function testShouldContainTheSkipValueIfProvidedInTheQueryParameterAndFilledWithSpacesInNonDollarMode(): void {
		$expected = ["skip" => 42];
		$actual = OdataQueryParser::parse('http://example.com/api/user?skip=%2042%20', $withDollar = false);

		$this->assertEquals($expected, $actual);
	}

	public function testShouldContainAnEmptyArrayIfSkipParameterIsEmptyInNonDollarMode(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("skip should be an integer");
		
		OdataQueryParser::parse('http://example.com/api/user?skip=', $includeDollar = false);
	}
}