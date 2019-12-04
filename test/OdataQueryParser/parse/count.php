<?php

use PHPUnit\Framework\TestCase;
use Khalyomede\OdataQueryParser;

final class Count extends TestCase {
	public function testShouldReturnCountTrueIfKeyFilledWithTrue(): void {
		$expected = ["count" => true];
		$actual = OdataQueryParser::parse('http://example.com/api/user?$count=1');
	
		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnCountTrueIfKeyFilledWithTrueAndSpaces(): void {
		$expected = ["count" => true];
		$actual = OdataQueryParser::parse('http://example.om/api/user?$count=%201%20');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldNotReturnCountIfKeyFilledWithFalse(): void {
		$expected = [];
		$actual = OdataQueryParser::parse('http://example.com/api/user?$count=0');
	
		$this->assertEquals($expected, $actual);
	}

	public function testShouldNotReturnCountIfKeyFilledWithFalseAndSpaces(): void {
		$expected = [];
		$actual = OdataQueryParser::parse('http://example.com/api/user?$count=%200%20');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnCountTrueIfKeyFillWithTrueInNonDollarMode(): void {
		$expected = ["count" => true];
		$actual = OdataQueryParser::parse("http://example.com/api/user?count=1", $includeDollar = false);
	
		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnCountTrueIfKeyFilledWithTrueAndSpacesInNonDollarMode(): void {
		$expected = ["count" => true];
		$actual = OdataQueryParser::parse('http://example.com/api/user?count=%201%20', $withDollar = false);

		$this->assertEquals($expected, $actual);
	}

	public function testShouldNotReturnCountIfKeyFilledWithFalseInNonDollarMode(): void {
		$expected = [];
		$actual = OdataQueryParser::parse("http://example.com/api/user?count=0", $includeDollar = false);
	
		$this->assertEquals($expected, $actual);
	}

	public function testShouldNotReturnCountIfKeyFilledWithFalseAndSpacesInNonDollarMode(): void {
		$expected = [];
		$actual = OdataQueryParser::parse('http://example.com/api/user?count=%200%20', $withDollar = false);

		$this->assertEquals($expected, $actual);
	}
}