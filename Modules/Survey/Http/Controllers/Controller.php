<?php
namespace Modules\Survey\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as LaravelController;
use MyCore\Http\Response\ResponseFormatTrait;

/**
 * Class Controller
 * @package Modules\Survey\Http\Controllers
 * @author DaiDP
 * @since Feb, 2022
 */
abstract class Controller extends LaravelController
{
    use ResponseFormatTrait, ValidatesRequests;
}