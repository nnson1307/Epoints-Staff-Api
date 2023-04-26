<?php
namespace Modules\User\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as LaravelController;
use MyCore\Http\Response\ResponseFormatTrait;

/**
 * Class Controller
 * @package Modules\User\Http\Controllers
 * @author DaiDP
 * @since Aug, 2019
 */
abstract class Controller extends LaravelController
{
    use ResponseFormatTrait, ValidatesRequests;
}