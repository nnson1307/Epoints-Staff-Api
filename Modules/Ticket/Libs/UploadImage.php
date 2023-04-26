<?php
namespace Modules\Ticket\Libs;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Http\Middleware\S3UploadsRedirect;


/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 08/05/2021
 * Time: 12:02
 */

class UploadImage
{
    /**
     * Upload ảnh/file lên s3
     *
     * @param $file
     * @param $link
     * @return string
     */
    public static function uploadImageS3($file, $link)
    {
        $time = Carbon::now();

        $idTenant = session()->get('idTenant');

        $to = $idTenant . '/' . date_format($time, 'Y') . '/' . date_format($time, 'm') . '/' . date_format($time, 'd') . '/';

        $file_name =
            str_random(5) .
            rand(0, 9) .
            time() .
            date_format($time, 'd') .
            date_format($time, 'm') .
            date_format($time, 'Y') .
            $link .
            $file->getClientOriginalExtension();

        Storage::disk('public')->put( $to.$file_name, file_get_contents($file), 'public');

        $mS3 = new S3UploadsRedirect();
        //Lấy real path trên s3
        return $mS3->getRealPath($to. $file_name);
    }

}