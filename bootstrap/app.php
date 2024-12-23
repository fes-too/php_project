<?php

use App\Mail\PostCountMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Application;

use App\Http\Middleware\IsAdminMiddleware;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Middleware\CanViewPostMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['can-view-post' => CanViewPostMiddleware::class]);
        $middleware->alias(['is_admin' => IsAdminMiddleware::class]);
    })
    ->withSchedule(function(Schedule $schedule){
        $schedule->call(function(){
            Mail::to('admin@example.com')->send(new PostCountMail());
        })->everyMinute();
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Record not found.'
                ], 404);
            }
        });

        $exceptions->render(function(ModelNotFoundException $e, Request $request){
        return response()->view('errors.404');
        });
    })->create();
