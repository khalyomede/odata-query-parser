<?php

use PHPUnit\Framework\TestCase;
use Khalyomede\OdataQueryParser;

final class SkipTest extends TestCase {
	public function testShouldThrowAnInvalidArgumentExceptionIfSkipParameterIsLowerThanZero(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("skip should be greater or equal to zero");

		OdataQueryParser::parse('https://example.com/?$skip=-1');
	}

	public function testShouldThrowAnInvalidArgumentExceptionIfSkipIsNotAnInteger(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("skip should be an integer");

		OdataQueryParser::parse('https://example.com/?$skip=test');
	}

	public function testShouldContainTheSkipValueIfProvidedInQueryParameters(): void {
		$expected = ["skip" => 42];
		$actual = OdataQueryParser::parse('https://example.com/api/user?$skip=42');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldContainTheSkipValueIfProvidedInTheQueryParameterAndFilledWithSpaces(): void {
		$expected = ["skip" => 42];
		$actual = OdataQueryParser::parse('https://example.com/api/user?$skip=%2042%20');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldContainAnEmptyArrayIfSkipParameterIsEmpty(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("skip should be an integer");
		
		OdataQueryParser::parse('https://example.com/api/user?$skip=');
	}

	public function testShouldNotThrowExceptionIfSkipIsEqualToZero(): void {
		$expected = ["skip" => 0];
		$actual = OdataQueryParser::parse('https://example.com/api/user?$skip=0');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnAnIntegerForTheSkipValue(): void {
		$this->assertIsInt(OdataQueryParser::parse('https://example.com/api/user?$skip=42')["skip"]);
	}

	// skip (non dollar mode)
	public function testShouldThrowAnInvalidArgumentExceptionIfSkipParameterIsLowerThanZeroInNonDolalrMode(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("skip should be greater or equal to zero");

		OdataQueryParser::parse('https://example.com/?skip=-1', false);
	}

	public function testShouldThrowAnInvalidArgumentExceptionIfSkipIsNotAnIntegerInNonDollarMode(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("skip should be an integer");

		OdataQueryParser::parse('https://example.com/?skip=test', false);
	}

	public function testShouldContainTheSkipValueIfProvidedInQueryParametersInNonDollarMode(): void {
		$expected = ["skip" => 42];
		$actual = OdataQueryParser::parse('https://example.com/api/user?skip=42', false);

		$this->assertEquals($expected, $actual);
	}

	public function testShouldContainTheSkipValueIfProvidedInTheQueryParameterAndFilledWithSpacesInNonDollarMode(): void {
		$expected = ["skip" => 42];
		$actual = OdataQueryParser::parse('https://example.com/api/user?skip=%2042%20', false);

		$this->assertEquals($expected, $actual);
	}

	public function testShouldContainAnEmptyArrayIfSkipParameterIsEmptyInNonDollarMode(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("skip should be an integer");
		
		OdataQueryParser::parse('https://example.com/api/user?skip=', false);
	}
}