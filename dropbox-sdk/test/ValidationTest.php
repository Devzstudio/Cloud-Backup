<?php

require_once __DIR__.'/../lib/Dropbox/strict.php';

use \Dropbox as dbx;

class ValidationTest extends PHPUnit_Framework_TestCase
{
    function testAccessToken()
    {
        $bad = array(
            null,
            "",
            "!AZaz09-_./~+",
            "abcdefg\n",
            "abcdefg\t",
            "abcdefg ",
            "abc\ndefg",
            "abc\tdefg",
            "abc defg",
            "\nabcdefg",
            "\tabcdefg",
            " abcdefg",
        );
        $good = array(
            "1=",
            "1",
            "abcdefg",
            "AZaz09-_./~+",
            "AZaz09-_./~+=",
            "AZaz09-_./~+==============",
            ".000000000000000000000000.",
        );

        foreach ($bad as $t) {
            try {
                new dbx\Client($t, "MyApp/1.0");
                assert(false);
            }
            catch (\InvalidArgumentException $ex) {
                // This is what we expect.
            }
        }

        foreach ($good as $t) {
            new dbx\Client($t, "MyApp/1.0");
        }
    }

    function testClientIdentifier()
    {
        $bad = array(
            null,
            "",
            "abcd\nefg",
            "abcd\x00efg",
            "abcd\x1fefg",
            "abcd\x7fefg",
            "abcd\n",
            "abcd\x00",
            "abcd\x1f",
            "abcd\x7f",
            "\nefg",
            "\x00efg",
            "\x1fefg",
            "\x7fefg",
        );
        $e_accent = "\xc3\xa9";  # UTF-8 sequence for "e with accute accent"
        $good = array(
            "MyApp/1.0 (Mosaic 1.0 compatible)",
            " MyApp/1.0 (Mosaic 1.0 compatible) ",
            "MyApp/1.0 (Mosaic 1.0 compatibl${e_accent}) ",
        );

        $appInfo = new dbx\AppInfo("abcd", "efgh");

        foreach ($bad as $clientIdentifier) {
            try {
                new dbx\Client("abcd", $clientIdentifier);
                assert(false);
            }
            catch (\InvalidArgumentException $ex) {
                // This is what we expect.
            }
            try {
                new dbx\WebAuthBase($appInfo, $clientIdentifier);
                assert(false);
            }
            catch (\InvalidArgumentException $ex) {
                // This is what we expect.
            }
        }

        foreach ($good as $clientIdentifier) {
            new dbx\Client("abcd", $clientIdentifier);
            new dbx\WebAuthBase($appInfo, $clientIdentifier);
        }
    }
}
