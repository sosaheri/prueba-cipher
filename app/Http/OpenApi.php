<?php

namespace App\Http;

/**
 * @OA\Info(
 * version="1.0.0",
 * title="API para ciph3r - Heriberto Sosa",
 * description="API RESTful para la gestión de productos, divisas y precios de productos.",
 * @OA\Contact(
 * email="sosaheriberto2021@gmail.com"
 * ),
 * @OA\License(
 * name="Apache 2.0",
 * url="http://www.apache.org/licenses/LICENSE-2.0.html"
 * )
 * )
 *
 * @OA\Server(
 * url=L5_SWAGGER_CONST_HOST,
 * description="Ciph3r API Test"
 * )
 *
 * @OA\Tag(
 * name="Currencies",
 * description="Operaciones relacionadas con la gestión de divisas."
 * )
 * @OA\Tag(
 * name="Products",
 * description="Operaciones relacionadas con la gestión de productos."
 * )
 * @OA\Tag(
 * name="Product Prices",
 * description="Operaciones relacionadas con los precios de productos en diferentes divisas."
 * )
 *
 * @OA\SecurityScheme(
 * type="http",
 * description="Login with email and password to get the authentication token",
 * name="Token based Auth",
 * in="header",
 * scheme="bearer",
 * bearerFormat="JWT",
 * securityScheme="apiAuth",
 * )
 */
class OpenApi {}