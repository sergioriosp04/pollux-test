<?php

namespace App\Enums;

enum ExportFormat: string
{
    case CSV = 'csv';
    case JSON = 'json';
    case XML = 'xml';
    case PDF = 'pdf';
    case EXCEL = 'xlsx';
    
    public function getLabel(): string
    {
        return match($this) {
            self::CSV => 'CSV',
            self::JSON => 'JSON',
            self::XML => 'XML',
            self::PDF => 'PDF',
            self::EXCEL => 'Excel',
        };
    }

    static function getArrayOptions(): array
    {
        return [
            self::CSV->value => self::CSV->getLabel(),
            self::JSON->value => self::JSON->getLabel(),
            self::XML->value => self::XML->getLabel(),
            self::EXCEL->value => self::EXCEL->getLabel(),
        ];
    }
}