<?php

namespace App\Http;

/**
 * @OA\Info(
 * version="1.0.0",
 * title="Ciph3r Product API",
 * description="API RESTful para la gestión de productos, divisas y precios de productos.",
 * @OA\Contact(
 * email="tu_email@example.com"
 * ),
 * @OA\License(
 * name="Apache 2.0",
 * url="http://www.apache.org/licenses/LICENSE-2.0.html"
 * )
 * )
 *
 * @OA\Server(
 * url=L5_SWAGGER_CONST_HOST,
 * description="Ciph3r API Server"
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
 * @OA\Tag(
 * name="Authentication", 
 * description="API Endpoints para autenticación de usuarios sanctum."
 * )
 *
 * @OA\SecurityScheme(
 * type="http",
 * description="Autenticación con Laravel Sanctum. Genera un token usando /api/auth/login y envíalo en el encabezado 'Authorization: Bearer {token}'.",
 * name="Sanctum Token based Auth",
 * in="header",
 * scheme="bearer",
 * bearerFormat="Sanctum", 
 * securityScheme="sanctum", 
 * )
 */
class OpenApi {}