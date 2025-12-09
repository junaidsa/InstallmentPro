<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class GroupSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'group_id',
        'name',
        'value',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    public const DATE_FORMATS = [
        'd/m/Y'   => 'Day/Month/Year',
        'd-m-Y'   => 'Day-Month-Year',
        'm/d/Y'   => 'Month/Day/Year',
        'm-d-Y'   => 'Month-Day-Year',
        'Y/m/d'   => 'Year/Month/Day',
        'Y-m-d'   => 'Year-Month-Day',
        'd M Y'   => 'Day ShortMonth Year',
        'M d, Y'  => 'ShortMonth Day, Year',
        'l, d M Y' => 'DayOfWeek, Day ShortMonth Year',
    ];

    public const DATE_FORMAT_JS = [
        'bootstrap' => [
            'd' => 'dd',
            'j' => 'd',
            'D' => 'D',
            'l' => 'DD',
            'm' => 'mm',
            'n' => 'm',
            'M' => 'M',
            'F' => 'MM',
            'Y' => 'yyyy',
            'y' => 'yy',
        ],
        'flatpickr' => [
            'd' => 'd',
            'j' => 'j',
            'D' => 'D',
            'l' => 'l',
            'm' => 'm',
            'n' => 'n',
            'M' => 'M',
            'F' => 'F',
            'Y' => 'Y',
            'y' => 'y',
        ],
        'moment' => [
            'd' => 'DD',
            'j' => 'D',
            'D' => 'ddd',
            'l' => 'dddd',
            'm' => 'MM',
            'n' => 'M',
            'M' => 'MMM',
            'F' => 'MMMM',
            'Y' => 'YYYY',
            'y' => 'YY',
        ],
    ];
    public const TIME_FORMATS = [
        '12' => '12-hour',
        '24' => '24-hour',
    ];
    const HOUR_12 = '12';
    const HOUR_24 = '24';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = Session::get('user')->id;
        });
        static::updating(function ($model) {
            $model->updated_by = Session::get('user')->id;
        });
        static::deleting(function ($model) {
            $model->deleted_by = Session::get('user')->id;
            $model->save();
        });
    }
}
