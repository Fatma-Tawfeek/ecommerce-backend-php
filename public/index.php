<?php

use App\Models\Product;

require_once __DIR__ . '/../vendor/autoload.php';

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->post('/graphql', [App\Controller\GraphQLController::class, 'handle']);
});

$routeInfo = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $_SERVER['REQUEST_URI']
);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        echo $handler($vars);
        break;
}

// use App\Models\Product;

$allProducts = Product::all(); // كل المنتجات
// $product = Product::find(1);   // منتج معين
// $byCat = Product::getByCategory(2); // منتجات كاتيجوري معين

// $categories = App\Models\Category::all();


echo "<pre>";
print_r($allProducts);
echo "</pre>";
// print_r($product);
// print_r($byCat);
