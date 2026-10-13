<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UcExtStudentsprotocol extends Model
{
    protected $table = 'uc_ext_studentsprotocol';
    public $timestamps = false;
    
    // Указываем параметры кастомного текстового UUID ключа
    protected $primaryKey = 'id_studentprotocol';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_studentprotocol', 'id_students', 'id_protocol', 'marktheory', 'markpract',
        'markprodpract', 'markitog', 'resulttest', 'assignedgrade', 'commissiondecision',
        'intervalmark', 'lastdocumenteduc', 'regnumber', 'fullregnumber', 'seriaregnumber',
        'codemidtrud', 'counthourfact', 'begindatefact', 'enddatefact', 'fiofact', 'stafffact',
        'gradefact', 'departfact', 'managementfact', 'status', 'flagpassed', 'flagprint',
        'barcode', 'documentnumber', 'linktodocument', 'namecompanyfact', 'codemvzfact', 'datedocumentsend',
        'notefield', 'notefieldlpp'
    ];
}
