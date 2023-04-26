<?php
namespace Modules\TimeKeeping\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\TimeKeeping\Http\Requests\CheckOutRequest;

class TimeKeepingConfigTable extends Model
{
    protected $table = 'sf_timekeeping_config';
    protected $primaryKey = 'timekeeping_config_id';

}