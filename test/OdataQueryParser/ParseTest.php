<?php

use PHPUnit\Framework\TestCase;
use Khalyomede\OdataQueryParser;

final class ParseTest extends TestCase {
	public function testShouldReturnExceptionIfUrlIsEmpty(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("url should be a valid url");

		OdataQueryParser::parse('');
	}

	public function testShouldReturnExceptionIfUrlIsNotValid(): void {
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("url should be a valid url");

		OdataQueryParser::parse('example.com');
	}

	public function testShouldReturnAnEmptyArrayIfNoQueryParameters(): void {
		$expected = [];
		$actual = OdataQueryParser::parse("https://example.com");

		$this->assertEquals($expected, $actual);
	}
}
