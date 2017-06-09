<?php

namespace Graze\BufferedConsole\Test\Unit\Wrap;

use Graze\BufferedConsole\Test\TestCase;
use Graze\BufferedConsole\Wrap\Wrapper;

class WrapperTest extends TestCase
{
    public function testWrapper()
    {
        $wrapper = new Wrapper(10);

        $this->assertEquals(10, $wrapper->getWidth());

        $this->assertSame($wrapper, $wrapper->setWidth(20));

        $this->assertEquals(20, $wrapper->getWidth());
    }

    /**
     * @dataProvider wrapData
     *
     * @param string|string[] $input
     * @param int             $width
     * @param string[]        $expected
     */
    public function testWrap($input, $width, array $expected)
    {
        $wrapper = new Wrapper($width);

        $this->assertEquals($expected, $wrapper->wrap($input));
    }

    /**
     * @return array
     */
    public function wrapData()
    {
        return [
            [ // simple string
                '1234567890',
                5,
                [
                    '12345',
                    '67890',
                ],
            ],
            [ // array of strings
                [
                    '1234567890123456789012345678901234567890',
                    '123456789012345678901234567890',
                ],
                20,
                [
                    '12345678901234567890',
                    '12345678901234567890',
                    '12345678901234567890',
                    '1234567890',
                ],
            ],
            [ // support multi-byte characters
                '😀😃😄😁😆😅😂🤣☺😊😇🙂🙃😉😌😍😘😗😙😚😋😜😝😛🤑🤗🤓😎🤡🤠😏😒😞😔😟😕🙁😣😖😫😩😤😠😡😶😐😑😯😦😧😮😲😵😳😱😨😰😢😥',
                20,
                [
                    '😀😃😄😁😆😅😂🤣☺😊😇🙂🙃😉😌😍😘😗😙😚',
                    '😋😜😝😛🤑🤗🤓😎🤡🤠😏😒😞😔😟😕🙁😣😖😫',
                    '😩😤😠😡😶😐😑😯😦😧😮😲😵😳😱😨😰😢😥',
                ],
            ],
            [ // strip tags and wrap on non stripped version
                [
                    '<info>1234567890</info>1234567890<warning>12345678901234567890</warning>',
                    '<error>12345678901234567890</error>1234567890',
                ],
                20,
                [
                    '<info>1234567890</info>1234567890<warning>',
                    '12345678901234567890</warning>',
                    '<error>12345678901234567890</error>',
                    '1234567890',
                ],
            ],
        ];
    }

    /**
     * @dataProvider trimData
     *
     * @param string|string[] $input
     * @param int             $width
     * @param array           $expected
     */
    public function testTrim($input, $width, array $expected)
    {
        $wrapper = new Wrapper($width);

        $this->assertEquals($expected, $wrapper->trim($input));
    }

    /**
     * @return array
     */
    public function trimData()
    {
        return [
            [ // simple string
                '1234567890',
                5,
                ['12345'],
            ],
            [ // array of strings
                [
                    '1234567890123456789012345678901234567890',
                    '123456789012345678901234567890',
                ],
                20,
                [
                    '12345678901234567890',
                    '12345678901234567890',
                ],
            ],
            [ // support multi-byte characters
                '😀😃😄😁😆😅😂🤣☺😊😇🙂🙃😉😌😍😘😗😙😚😋😜😝😛🤑🤗🤓😎🤡🤠😏😒😞😔😟😕🙁😣😖😫😩😤😠😡😶😐😑😯😦😧😮😲😵😳😱😨😰😢😥',
                20,
                ['😀😃😄😁😆😅😂🤣☺😊😇🙂🙃😉😌😍😘😗😙😚'],
            ],
            [ // strip tags and wrap on non stripped version
                [
                    '<info>1234567890</info>1234567890<warning>12345678901234567890</warning>',
                    '<error>12345678901234567890</error>1234567890',
                ],
                20,
                [
                    '<info>1234567890</info>1234567890<warning>',
                    '<error>12345678901234567890</error>',
                ],
            ],
        ];
    }
}
