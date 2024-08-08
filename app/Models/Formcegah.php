<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Alfa6661\AutoNumber\AutoNumberTrait;

class Formcegah extends Model
{
    use HasFactory;
    use AutoNumberTrait;
    protected $guarded=[];

    public function getAutoNumberOptions()
    {

        return [
            'no_form' => [
                'format' => function () {
                    $exp=explode(",",$this->no_form);
                    if($exp[1]==''){
                        return '?/F.CEGAH/'.$exp[0].'/'.'/'.$exp[2];
                        
                    }else{
                        return '?/F.CEGAH/'.$exp[0].'.'.$exp[1].'/'.'/'.$exp[2];

                    }
                },
                'length' => 3,
            ]
        ];
    }

    public function wp()
    {
        return $this->belongsTo(Twp::class, 'wp_id', 'id');
    }

}
