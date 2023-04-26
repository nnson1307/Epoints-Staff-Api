<?php

namespace App\Http\Middleware;

use App\Models\PiospaBrandTable;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\User\Models\ConfigTable;
use MyCore\Helper\OpensslCrypt;
use Illuminate\Support\Facades\Cache;

class S3UploadsRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->setDiskTenantConfig($request);
        $redirectUrl = $this->modifyRedirect($request);

        if (is_null($redirectUrl)) {
            return $next($request);
        } else {
            return $next($request);
//            return redirect($redirectUrl)
        }
    }

    private function modifyRedirect($request)
    {

        $urlExp = explode("/", $request->getUri());

        // redirect here
        if (config("filesystems.disks.public.driver") == "s3") {
            if (count($urlExp) > 3) {
                if (in_array($urlExp[3], explode("|", env('STORAGE_UPLOAD_PREFIX')))) {
                    return $this->getRealPath($request->path());
                }
            }
        }
        return null;
    }

    private function setDiskTenantConfig($request)
    {
        if (isset($request->brand_code)) {
            // $config = new ConfigTable();
            // $AWS_ACCESS_KEY_ID = $config->getInfoByKey("aws_access_key_id")["value"];
            // $AWS_SECRET_ACCESS_KEY = $config->getInfoByKey("aws_secret_access_key")["value"];
            // $AWS_DEFAULT_REGION = $config->getInfoByKey("aws_default_region")["value"];
            // $AWS_BUCKET = $config->getInfoByKey("aws_bucket")["value"];
            $value = Cache::remember('config', 360, function () {
                return ConfigTable::all();
            });
            $collectionDetail = collect($value->toArray());

            $AWS_ACCESS_KEY_ID = $collectionDetail->where('key', 'aws_access_key_id')->first()['value'];
            $AWS_SECRET_ACCESS_KEY = $collectionDetail->where('key', 'aws_secret_access_key')->first()['value'];
            $AWS_DEFAULT_REGION = $collectionDetail->where('key', 'aws_default_region')->first()['value'];
            $AWS_BUCKET = $collectionDetail->where('key', 'aws_bucket')->first()['value'];

            try {
                $awsS3 = [
                    'driver' => 's3',
                    'key' => $AWS_ACCESS_KEY_ID,
                    'secret' => $AWS_SECRET_ACCESS_KEY,
                    'region' => $AWS_DEFAULT_REGION,
                    'bucket' => $AWS_BUCKET
                ];
            } catch (\Exception $e) {
                Log::info($e);
            }
        }

        config([
            'filesystems.disks.public' => env("STORAGE_TYPE", "") == "s3" ? ($awsS3 ?? [
                    'driver' => 's3',
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                    'region' => env('AWS_DEFAULT_REGION'),
                    'bucket' => env('AWS_BUCKET')
                ]) : [
                'driver' => 'local',
                'root' => public_path(),
                'url' => env('APP_URL') . '/storage',
                'visibility' => 'public',
            ]
        ]);
    }

    public function getRealPath($value)
    {
        $disk = Storage::disk('public');

        if ($disk->exists($value)) {
            $url = $disk->getDriver()->getAdapter()->getClient()->getObjectUrl(config('filesystems.disks.public.bucket'), $value);
            return $url;
        }
        return $value;
    }
}