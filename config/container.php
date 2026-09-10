<?php

declare(strict_types=1);

use App\Application;
use App\Controller\ReservationController;
use App\Controller\SalleController;
use App\Repository\EloquentReservationRepository;
use App\Repository\EloquentSalleRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;
use App\Validation\SalleValidator;
use DI\autowire;
use DI\factory;
use FastRoute\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;

return [

    

    SalleRepositoryInterface::class =>
        autowire(EloquentSalleRepository::class),

    ReservationRepositoryInterface::class =>
        autowire(EloquentReservationRepository::class),


    

    SalleValidator::class =>
        autowire(),

    ReservationValidator::class =>
        autowire(),


    

    CreerReservationService::class =>
        autowire(),

    AnnulerReservationService::class =>
        autowire(),


   

    SalleController::class =>
        autowire(),

    ReservationController::class =>
        autowire(),


    

    Capsule::class =>
        factory(function (): Capsule {

            $capsule = new Capsule();

            $capsule->addConnection([
                'driver' => getenv('DB_DRIVER') ?: 'mysql',
                'host' => getenv('DB_HOST') ?: 'mysql',
                'port' => getenv('DB_PORT') ?: '3306',
                'database' => getenv('DB_DATABASE') ?: 'reservation_salles',
                'username' => getenv('DB_USERNAME') ?: 'root',
                'password' => getenv('DB_PASSWORD') ?: 'root',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
            ]);

            $capsule->setAsGlobal();

            $capsule->bootEloquent();

            return $capsule;
        }),



    Dispatcher::class =>
        factory(function (): Dispatcher {

            $dispatcher = FastRoute\simpleDispatcher(
                require dirname(__DIR__) . '/routes/web.php'
            );

            return $dispatcher;
        }),


    

    Application::class =>
        autowire(),
];

