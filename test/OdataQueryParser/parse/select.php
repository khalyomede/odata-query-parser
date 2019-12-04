<?php

use PHPUnit\Framework\TestCase;
use Khalyomede\OdataQueryParser;

final class Select extends TestCase {
	public function testShouldReturnSelectColumns(): void {
		$expected = ["select" => ["name", "type", "userId"]];
		$actual = OdataQueryParser::parse('http://example.com/users?$select=name,type,userId');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnSelectColumnsIfFilledWithSpaces(): void {
		$expected = ["select" => ["name", "type", "userId"]];
		$actual = OdataQueryParser::parse('http://example.com/api/user?$select=%20name,%20type%20,userId%20');

		$this->assertEquals($expected, $actual);
	}
	
	public function testShouldReturnTheColumnsInNonDollarMode(): void {
		$expected = ["select" => ["name", "type", "userId"]];
		$actual = OdataQueryParser::parse('http://example.com/?select=name,type,userId', $includeDollar = false);

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnTheColumnsIfFilledWithSpacesInNonDollarMode(): void {
		$expected = ["select" => ["name", "type", "userId"]];
		$actual = OdataQueryParser::parse('http://example.com/api/user?select=%20name,%20type%20,userId%20', $withDollar = false);

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnAnEmptyArrayIfNoColumnFound(): void {
		$expected = [];
		$actual = OdataQueryParser::parse('http://example.com/?$select=');

		$this->assertEquals($expected, $actual);
	}

	public function testShouldReturnAnEmptyArrayIfNoColumnFoundInNonDollarMode(): void {
		$expected = [];
		$actual = OdataQueryParser::parse('http://example.com/?select=');

		$this->assertEquals($expected, $actual);
	}
}