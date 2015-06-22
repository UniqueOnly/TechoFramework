<?php
namespace Techo\File;

abstract class Document extends \Techo\File
{
    abstract public function output($filename);
}