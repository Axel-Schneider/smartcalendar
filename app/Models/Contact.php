<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'userRequest_id',
        'user_id',
        'status',
        'blocker_id',
        'requesterColor',
        'receiverColor',
    ];

    public function color() {
        return ($this->user_id == auth()->user()->id) ? $this->receiverColor : $this->requesterColor;
    }

    public function colorOpposite(){
        $color = $this->color();
        $hex = substr($color, 1, null);
        $arr = str_split($hex, 2);
        foreach ($arr as &$value) {
            $c = base_convert($value, 16, 10);
            $value = str_pad(base_convert(255 - $c, 10, 16), 2, '0', STR_PAD_LEFT);
        }
        return '#' . implode('', $arr);
    }

    public function colorBlackWhite() {
        $color = $this->color();
        $hexColor = substr($color, 1, null);
        // hexColor RGB
        $R1 = hexdec(substr($hexColor, 1, 2));
        $G1 = hexdec(substr($hexColor, 3, 2));
        $B1 = hexdec(substr($hexColor, 5, 2));

        // Black RGB
        $blackColor = "#000000";
        $R2BlackColor = hexdec(substr($blackColor, 1, 2));
        $G2BlackColor = hexdec(substr($blackColor, 3, 2));
        $B2BlackColor = hexdec(substr($blackColor, 5, 2));

         // Calc contrast ratio
         $L1 = 0.2126 * pow($R1 / 255, 2.2) +
               0.7152 * pow($G1 / 255, 2.2) +
               0.0722 * pow($B1 / 255, 2.2);

        $L2 = 0.2126 * pow($R2BlackColor / 255, 2.2) +
              0.7152 * pow($G2BlackColor / 255, 2.2) +
              0.0722 * pow($B2BlackColor / 255, 2.2);

        $contrastRatio = 0;
        if ($L1 > $L2) {
            $contrastRatio = (int)(($L1 + 0.05) / ($L2 + 0.05));
        } else {
            $contrastRatio = (int)(($L2 + 0.05) / ($L1 + 0.05));
        }

        // If contrast is more than 5, return black color
        if ($contrastRatio > 5) {
            return '#000';
        } else { 
            // if not, return white color.
            return '#fff';
        }
    }

    public function requestor() {
        return $this->belongsTo(User::class, "userRequest_id");
    }

    public function receiver() {
        return $this->belongsTo(User::class, "user_id");
    }

    public function blocker() {
        return $this->belongsTo(User::class, "blocker_id");
    }
}
