<?php
namespace Modules\TimeOffDays\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as LaravelController;
use MyCore\Http\Response\ResponseFormatTrait;

/**
 * Class Controller
 * @package Modules\TimeOffDays\Http\Controllers
 * @author PhongDT
 */
abstract class Controller extends LaravelController
{
    use ResponseFormatTrait, ValidatesRequests;
}