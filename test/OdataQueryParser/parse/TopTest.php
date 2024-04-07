<?php

use PHPUnit\Framework\TestCase;
use Khalyomede\OdataQueryParser;

final class TopTest extends TestCase {
	public function testShouldThrowAnInvalidArgumentExceptionIfTopQueryParameterIsLowerThanZero(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("top should be greater or equal to zero");

		OdataQueryParser::parse('https://example.com/users?$top=-1');
	}

	public function testShouldNotThrowExceptionIfTopQueryParameterIsEqualToZero(): void {
		$expected = ["top" => 0];
		$actual = OdataQueryParser::parse('https://example.com/api/user/?$top=0');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldThrowAnExceptionIfTopQueryParameterIsLowerThanZeroAndFilledWithSpaces(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("top should be greater or equal to zero");

		OdataQueryParser::parse('https://example.com/users?$top=%20-1%20');
	}

	public function testShouldThrowAnInvalidArgumentExceptionIfTopIsNotAnInteger(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("top should be an integer");

		OdataQueryParser::parse('https://example.com/?$top=foo');
	}

	public function testShouldReturnTheTopValueIfProvidedInTheQueryParameters(): void {
		$expected = ["top" => 42];
		$actual = OdataQueryParser::parse('https://example.com/?$top=42');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnAnIntegerTopValue(): void {
		$this->assertIsInt(OdataQueryParser::parse('https://example.com/api/user?$top=42')["top"]);
	}

	public function testShouldReturnTheTopValueIfProvidedInTheQueryParametersAndFilledWithSpaces(): void {
		$expected = ["top" => 42];
		$actual = OdataQueryParser::parse('https://example.com/api/user?$top=%2042%20');

		$this->assertEquals($expected, $actual);
	}
}