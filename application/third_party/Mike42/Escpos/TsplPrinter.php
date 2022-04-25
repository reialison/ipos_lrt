<?php
/**
 * This file is part of escpos-php: PHP receipt printer library for use with
 * ESC/POS-compatible thermal and impact printers.
 *
 * Copyright (c) 2014-16 Michael Billington < michael.billington@gmail.com >,
 * incorporating modifications by others. See CONTRIBUTORS.md for a full list.
 *
 * This software is distributed under the terms of the MIT license. See LICENSE.md
 * for details.
 */

namespace Mike42\Escpos;

use Exception;
use LoKingWei\Tspl\PrintImages\TsplImage;
use Mike42\Escpos\PrintConnectors\PrintConnector;

class TsplPrinter
{
    const DPI200 = 8;
    const DPI300 = 12;

    const MILIMETER = "mm";
    const DOT = "dot";
    const INCH = "";

    const LINE_BREAK = "\r\n";
    const SEPARATOR = ",";
    const SPACE = " ";

    //Configuration related
    const SIZE = "SIZE";
    const GAP = "GAP";
    const REFERENCE = "REFERENCE";
    const DIRECTION = "DIRECTION";
    const OFFSET = "OFFSET";
    const SHIFT = "SHIFT";

    //Action related command
    const TEXT = "TEXT";
    const BEEP = "BEEP";
    const BITMAP = "BITMAP";
    const PRINT_ = "PRINT";

    //Single word command
    const CLS = "CLS";
    const EOP = "EOP";
    const HOME = "HOME";
    const DEFAULT_UNIT = "";

    protected $connector;

    private $defaultUnit;

    private $sizeWidth = "50 mm";
    private $sizeHeight = "40 mm";
    private $sizeUnit;

    private $gapDistance = "3 mm,0 mm";
    private $gapOffset;
    private $gapUnit;

    private $referenceX = 0;
    private $referenceY = 0;

    private $offset = 0;
    private $offsetUnit;

    private $shiftX;
    private $shiftY = 0;

    private $direction = 1;

    public function __construct(PrintConnector $connector)
    {
        $this->connector = $connector;
    }

    public function setDefaultUnit($defaultUnit) {
        $this->defaultUnit = $defaultUnit;
        return $this;
    }

    public function setSize($width, $height = null, $unit = null) {
        $this->sizeWidth = $width;
        $this->sizeHeight = $height;
        $this->sizeUnit = $unit;
        return $this;
    }

    public function setGap($distance, $offset, $unit = null) {
        $this->gapDistance = $distance;
        $this->gapOffset = $offset;
        $this->gapUnit = $unit;
        return $this;
    }

    public function setReference($x, $y) {
        $this->referenceX = $x;
        $this->referenceY = $y;
        return $this;
    }

    public function setDirection($direction) {
        $this->direction = $direction;
        return $this;
    }

    public function setOffset($offset, $unit = null){
        $this->offset = $offset;
        $this->offsetUnit = $unit;
        return $this;
    }

    public function setShift($y, $x = null){
        $this->shiftX = $x;
        $this->shiftY = $y;
        return $this;
    }
    
    public function getSizeCommand()
    {
        $str = self::SIZE;
        $str .= self::SPACE;
        $str .= $this->sizeWidth;
        $str .= self::SPACE;
        $str .= $this->getUnit($this->sizeUnit);
        if(isset($this->sizeHeight)) {
            $str .= self::SEPARATOR;
            $str .= $this->sizeHeight;
            $str .= self::SPACE;
            $str .= $this->getUnit($this->sizeUnit);
        }
        return $str;
    }

    public function getGapCommad()
    {
        $str = self::GAP;
        $str .= self::SPACE;
        $str .= $this->gapDistance;
        $str .= self::SPACE;
        $str .= $this->getUnit($this->gapUnit);
        // $str .= self::SEPARATOR;
        // $str .= $this->gapOffset;
        // $str .= self::SPACE;
        $str .= $this->getUnit($this->gapUnit);
        return $str;
    }

    public function getReferenceCommand()
    {
        $str = self::REFERENCE;
        $str .= self::SPACE;
        $str .= $this->referenceX;
        $str .= self::SEPARATOR;
        $str .= $this->referenceY;
        return $str;
    }

    public function getDirectionCommand()
    {
        $str = self::DIRECTION;
        $str .= self::SPACE;
        $str .= $this->direction;
        return $str;
    }

    public function getOffsetCommand()
    {
        $str = self::OFFSET;
        $str .= self::SPACE;
        $str .= $this->offset;
        $str .= self::SPACE;
        $str .= $this->getUnit($this->offsetUnit);
        return $str;
    }

    public function getShiftCommand()
    {
        $str = self::SHIFT;
        $str .= self::SPACE;
        if($this->shiftX) {
            $str .= $this->shiftX;
            $str .= self::SEPARATOR;
        }
        $str .= $this->shiftY;
        return $str;
    }

    public function getBitmapCommand($x, $y, $withdBytes, $heightDots, $mode, $data)
    {
        $str = self::BITMAP;
        $str .= self::SPACE;
        $str .= $x;
        $str .= self::SEPARATOR;
        $str .= $y;
        $str .= self::SEPARATOR;
        $str .= $withdBytes;
        $str .= self::SEPARATOR;
        $str .= $heightDots;
        $str .= self::SEPARATOR;
        $str .= $mode;
        $str .= self::SEPARATOR;
        $str .= $data;
        return $str;
    }

    public function getTextCommand($x, $y, $font, $rotation, $xMultiplication, $yMultiplication, $alignment, $text)
    {
        $str = self::TEXT;
        $str .= self::SPACE;
        $str .= $x;
        $str .= self::SEPARATOR;
        $str .= $y;
        $str .= self::SEPARATOR;
        $str .= $font;
        $str .= self::SEPARATOR;
        $str .= $rotation;
        $str .= self::SEPARATOR;
        $str .= $xMultiplication;
        $str .= self::SEPARATOR;
        $str .= $yMultiplication;
        if(isset($alignment)) {
            $str .= self::SEPARATOR;
            $str .= $alignment;
        }
        $str .= self::SEPARATOR;
        $str .= $text;
        return $str;
    }

    public function getPrintCommand($set, $copy = 1)
    {
        $str = self::PRINT_;
        $str .= self::SPACE;
        $str .= $set;
        if(isset($copy)) {
            $str .= self::SEPARATOR;
            $str .= $copy;
        }
        return $str;
    }

    public function getPrintBitImageRasterFormatCommands(TsplImage $image, $x, $y, $mode)
    {
        $commands = [];
        $image->toRasterFormat();
        array_push($commands, $this->getSizeCommand());
        array_push($commands, $this->getGapCommad());
        array_push($commands, $this->getReferenceCommand());
        array_push($commands, $this->getDirectionCommand());
        array_push($commands, $this->getShiftCommand());
        array_push($commands, self::CLS);
        array_push($commands, $this->getBitmapCommand($x, $y, $image->getWidthBytes(), $image->getHeight(), $mode, $image->toRasterFormat()));
        array_push($commands, $this->getPrintCommand(1));
        array_push($commands, self::EOP);

        return $commands;
    }

    public function getPrintTextCommands($x, $y, $font, $rotation, $xMultiplication=1, $yMultiplication=1, $text, $alignment = null)
    {
        $commands = [];
        // $this->sendCommands(["SIZE 50 mm,40 mm"]);
        // $this->sendCommands(["GAP 3 mm,0 mm"]);
        // $this->sendCommands(["DIRECTION 1"]);
        // $this->sendCommands(["BEEP"]);
        // $this->sendCommands(["CLS"]);
        // $this->sendCommands(['TEXT 20,20,"3",0,1,1,"Aloha!"']);
        // $this->sendCommands(['TEXT 20,60,"3",0,1,1,"Alohax!"']);
        // $this->sendCommands(['TEXT 20,100,"2",0,1,1,"Alohax1!"']);
        // $this->sendCommands(['TEXT 20,120,"2",0,1,1,"Alohax2!"']);
        // $this->sendCommands(['TEXT 20,140,"2",0,1,1,"Alohax3!"']);

        // $this->sendCommands(['PRINT 1,1']);


        // array_push($commands, $this->getSizeCommand());
        // array_push($commands, $this->getGapCommad());
        // // array_push($commands, $this->getReferenceCommand());
        // array_push($commands, $this->getDirectionCommand());
        // array_push($commands, $this->getShiftCommand());
                // array_push($commands,"SET CUTTER 1 \r\n");
        // array_push($commands, self::CLS);
        array_push($commands, $this->getTextCommand($x, $y, $font, $rotation, $xMultiplication, $yMultiplication, $alignment, $text));
        // array_push($commands, $this->getPrintCommand(1));

        // array_push($commands, self::EOP);

        return $commands;
    }

    public function beep()
    {
        $this->sendCommands([self::BEEP]);
    }

    public function bitImageRasterFormat(TsplImage $image, $x = 0, $y = 0, $mode = 0)
    {
        $commands = $this->getPrintBitImageRasterFormatCommands($image, $x, $y, $mode);
        $this->sendCommands($commands);
    }

    public function text($text, $x = 0, $y = 0, $font = 0, $rotation = 0, $xMultiplication = 1, $yMultiplication = 1, $alignment = null)
    {
        $commands = $this->getPrintTextCommands($x, $y, '"'.$font.'"', $rotation, $xMultiplication, $yMultiplication, $alignment, '"'.$text.'"');
        // var_dump($commands);
        
        $this->sendCommands($commands);
    }

    public function close()
    {
        $this->connector->finalize();
    }

    protected function sendCommands($commands)
    {   
        // echo "<pre>",print_r($commands),"</pre>";//die();
        $commandString = implode(self::LINE_BREAK, $commands).self::LINE_BREAK;
        // var_dump($commandString);die();
        if(is_array($commands)){
            // $this->connector->write("BEEP\r\n");
            foreach($commands as $command){
                $com = $command."\r\n";
                // var_dump($com) ."<br>";//die();
               $this->connector->write($command."\r\n");
            }
        }else{
            $this->connector->write($commandString);
            
        }


    }


    protected function getUnit($unit)
    {
        return isset($unit) ? $unit : $this->defaultUnit ? $this->defaultUnit : self::DEFAULT_UNIT;
    }

     public function go_print()
    {
        $this->sendCommands(["PRINT 1,1\r\n"]);
    }

    public function cut()
    {
        $this->sendCommands(["CUT\r\n"]);
    }

     public function CLS()
    {
        $this->sendCommands([self::CLS]);
    }

}
