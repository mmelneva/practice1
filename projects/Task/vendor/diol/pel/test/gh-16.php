<?php
/**
 * PEL: PHP Exif Library.
 * A library with support for reading and
 * writing all Exif headers in JPEG and TIFF images using PHP.
 *
 * Copyright (C) 2004, 2006, 2007 Martin Geisler.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program in the file COPYING; if not, write to the
 * Free Software Foundation, Inc., 51 Franklin St, Fifth Floor,
 * Boston, MA 02110-1301 USA
 */
if (realpath($_SERVER['PHP_SELF']) == __FILE__) {
    require_once '../autoload.php';
    require_once '../vendor/simpletest/simpletest/autorun.php';
}
use lsolesen\pel\PelDataWindow;
use lsolesen\pel\PelJpeg;
use lsolesen\pel\PelEntryWindowsString;
use lsolesen\pel\PelTag;

class Gh16TestCase extends UnitTestCase
{

    protected $file;

    function __construct()
    {
        parent::__construct('Gh-16 Test');
    }

    function setUp()
    {
        $this->file = dirname(__FILE__) . '/images/gh-16-tmp.jpg';
        $file = dirname(__FILE__) . '/images/gh-16.jpg';
        copy($file, $this->file);
    }

    function tearDown()
    {
        unlink($this->file);
    }

    function testThisDoesNotWorkAsExpected()
    {
        $subject = "Превед, медвед!";

        $data = new PelDataWindow(file_get_contents($this->file));

        if (PelJpeg::isValid($data)) {

            $jpeg = new PelJpeg();
            $jpeg->load($data);
            $exif = $jpeg->getExif();

            if (null == $exif) {
                $exif = new PelExif();
                $jpeg->setExif($exif);
                $tiff = new PelTiff();
                $exif->setTiff($tiff);
            }

            $tiff = $exif->getTiff();

            $ifd0 = $tiff->getIfd();
            if (null == $ifd0) {
                $ifd0 = new PelIfd(PelIfd::IFD0);
                $tiff->setIfd($ifd0);
            }
        }
        $ifd0->addEntry(new PelEntryWindowsString(PelTag::XP_SUBJECT, $subject));

        file_put_contents($this->file, $jpeg->getBytes());

        $jpeg = new PelJpeg($this->file);
        $exif = $jpeg->getExif();
        $tiff = $exif->getTiff();
        $ifd0 = $tiff->getIfd();
        $written_subject = $ifd0->getEntry(PelTag::XP_SUBJECT);
        $this->assertEqual($subject, $written_subject->getValue());
    }
}
