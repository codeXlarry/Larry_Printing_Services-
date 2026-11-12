<?php
declare(strict_types=1);

require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/db_config.php';


/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
*/

$search = trim(
    (string) ($_GET['search'] ?? '')
);

$categoryId = filter_input(
    INPUT_GET,
    'category',
    FILTER_VALIDATE_INT
);

if ($categoryId === false) {
    $categoryId = null;
}


/*
|--------------------------------------------------------------------------
| Load Categories
|--------------------------------------------------------------------------
*/

try {

    $categoryStatement = $pdo->prepare(
        "
        SELECT
            category_id,
            category_name
        FROM categories
        ORDER BY category_name ASC
        "
    );

    $categoryStatement->execute();

    $categories =
        $categoryStatement->fetchAll();

} catch (PDOException $e) {

    error_log(
        'Category loading error: '
        . $e->getMessage()
    );

    $categories = [];

}


/*
|--------------------------------------------------------------------------
| Build Products Query
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        p.product_id,
        p.product_code,
        p.product_name,
        p.description,
        p.size,
        p.color,
        p.unit,
        p.selling_price,
        p.stock_quantity,
        p.reorder_level,
        p.status,
        c.category_name
    FROM products p

    LEFT JOIN categories c
        ON p.category_id = c.category_id

    WHERE p.status = 'Active'
";


$params = [];


/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

if ($search !== '') {

    $sql .= "
        AND (
            p.product_name LIKE :search
            OR p.product_code LIKE :search
            OR p.description LIKE :search
            OR p.size LIKE :search
            OR p.color LIKE :search
            OR c.category_name LIKE :search
        )
    ";

    $params[':search'] =
        '%' . $search . '%';

}


/*
|--------------------------------------------------------------------------
| Category Filter
|--------------------------------------------------------------------------
*/

if (!empty($categoryId)) {

    $sql .= "
        AND p.category_id = :category_id
    ";

    $params[':category_id'] =
        $categoryId;

}


$sql .= "
    ORDER BY
        p.product_name ASC,
        p.product_id DESC
";


/*
|--------------------------------------------------------------------------
| Load Products
|--------------------------------------------------------------------------
*/

try {

    $productStatement =
        $pdo->prepare($sql);

    $productStatement->execute(
        $params
    );

    $products =
        $productStatement->fetchAll();

} catch (PDOException $e) {

    error_log(
        'Product loading error: '
        . $e->getMessage()
    );

    $products = [];

}


$productCount =
    count($products);


/*
|--------------------------------------------------------------------------
| Helper
|--------------------------------------------------------------------------
*/

function e(?string $value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Products | Larry Clothing & Printing
    </title>


    <!-- Main Website CSS -->

    <link
        rel="stylesheet"
        href="assets/css/style.css?v=4.0"
    >


    <!-- Products CSS -->

    <link
        rel="stylesheet"
        href="assets/css/products.css?v=1.0"
    >

</head>


<body class="products-page">


<!-- =========================================================
     GLOBAL HEADER
========================================================= -->

<?php
require_once __DIR__ . '/header.php';
?>


<!-- =========================================================
     PRODUCTS PAGE
========================================================= -->

<main class="products-container">


    <!-- =====================================================
         HERO
    ====================================================== -->

    <section class="products-hero">

        <div class="products-hero-content">

            <span class="products-label">
                Larry Clothing & Printing
            </span>

            <h1>
                Our Products
            </h1>

            <p>
                Browse clothing and merchandise available
                for purchase and custom printing.
            </p>

        </div>

    </section>


    <!-- =====================================================
         FILTER SECTION
    ====================================================== -->

    <section class="products-toolbar">

        <form
            action="products.php"
            method="GET"
            class="products-filter-form"
        >


            <!-- SEARCH -->

            <div class="product-search-box">

                <svg
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <circle
                        cx="11"
                        cy="11"
                        r="7"
                    ></circle>

                    <line
                        x1="16.65"
                        y1="16.65"
                        x2="21"
                        y2="21"
                    ></line>

                </svg>


                <input
                    type="search"
                    name="search"
                    placeholder="Search products..."
                    value="<?= e($search); ?>"
                >

            </div>


            <!-- CATEGORY -->

            <select
                name="category"
                class="category-select"
            >

                <option value="">
                    All Categories
                </option>


                <?php foreach ($categories as $category): ?>

                    <option
                        value="<?= (int) $category['category_id']; ?>"
                        <?= (
                            (int) $categoryId
                            ===
                            (int) $category['category_id']
                        ) ? 'selected' : ''; ?>
                    >

                        <?= e(
                            $category['category_name']
                        ); ?>

                    </option>

                <?php endforeach; ?>

            </select>


            <!-- FILTER BUTTON -->

            <button
                type="submit"
                class="filter-button"
            >
                Search
            </button>


            <!-- CLEAR -->

            <?php if (
                $search !== ''
                ||
                !empty($categoryId)
            ): ?>

                <a
                    href="products.php"
                    class="clear-filter-button"
                >
                    Clear
                </a>

            <?php endif; ?>


        </form>

    </section>


    <!-- =====================================================
         RESULTS HEADER
    ====================================================== -->

    <section class="products-results-header">

        <div>

            <h2>
                Products
            </h2>

            <p>

                <?= $productCount; ?>

                <?= $productCount === 1
                    ? 'product'
                    : 'products'; ?>

                found

            </p>

        </div>

    </section>


    <!-- =====================================================
         PRODUCT GRID
    ====================================================== -->

    <?php if ($productCount > 0): ?>


        <section class="products-grid">


            <?php foreach ($products as $product): ?>


                <?php

                $stock =
                    (int) $product['stock_quantity'];

                $reorderLevel =
                    (int) $product['reorder_level'];


                if ($stock <= 0) {

                    $stockClass =
                        'out-of-stock';

                    $stockText =
                        'Out of Stock';

                } elseif (
                    $stock <= $reorderLevel
                ) {

                    $stockClass =
                        'low-stock';

                    $stockText =
                        'Low Stock';

                } else {

                    $stockClass =
                        'in-stock';

                    $stockText =
                        'In Stock';

                }


                /*
                |--------------------------------------------------------------------------
                | Product Initials
                |--------------------------------------------------------------------------
                */

                $productName =
                    trim(
                        (string)
                        $product['product_name']
                    );


                $initial =
                    strtoupper(
                        mb_substr(
                            $productName,
                            0,
                            1
                        )
                    );

                ?>


                <article class="product-card">


                    <!-- PRODUCT VISUAL -->

                    <div class="product-image-placeholder">

                        <span>
                            <?= e($initial); ?>
                        </span>


                        <?php if (
                            !empty(
                                $product['category_name']
                            )
                        ): ?>

                            <div class="product-category-badge">

                                <?= e(
                                    $product[
                                        'category_name'
                                    ]
                                ); ?>

                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- INFORMATION -->

                    <div class="product-card-body">


                        <div class="product-code">

                            <?= e(
                                $product[
                                    'product_code'
                                ]
                            ); ?>

                        </div>


                        <h3>

                            <?= e(
                                $product[
                                    'product_name'
                                ]
                            ); ?>

                        </h3>


                        <?php if (
                            !empty(
                                $product[
                                    'description'
                                ]
                            )
                        ): ?>

                            <p class="product-description">

                                <?= e(
                                    $product[
                                        'description'
                                    ]
                                ); ?>

                            </p>

                        <?php else: ?>

                            <p class="product-description">

                                Quality product available
                                from Larry Clothing & Printing.

                            </p>

                        <?php endif; ?>


                        <!-- ATTRIBUTES -->

                        <div class="product-details">


                            <?php if (
                                !empty(
                                    $product['size']
                                )
                            ): ?>

                                <span>

                                    <strong>
                                        Size
                                    </strong>

                                    <?= e(
                                        $product[
                                            'size'
                                        ]
                                    ); ?>

                                </span>

                            <?php endif; ?>


                            <?php if (
                                !empty(
                                    $product['color']
                                )
                            ): ?>

                                <span>

                                    <strong>
                                        Color
                                    </strong>

                                    <?= e(
                                        $product[
                                            'color'
                                        ]
                                    ); ?>

                                </span>

                            <?php endif; ?>


                        </div>


                        <!-- PRICE / STOCK -->

                        <div class="product-card-footer">


                            <div class="product-price">

                                <small>
                                    Price
                                </small>

                                <strong>

                                    ₱<?= number_format(
                                        (float)
                                        $product[
                                            'selling_price'
                                        ],
                                        2
                                    ); ?>

                                </strong>

                            </div>


                            <div
                                class="stock-badge
                                <?= $stockClass; ?>"
                            >

                                <span
                                    class="stock-dot"
                                ></span>

                                <?= $stockText; ?>

                            </div>


                        </div>


                        <!-- STOCK NUMBER -->

                        <div class="stock-count">

                            <?= $stock; ?>

                            <?= e(
                                $product['unit']
                                ?? 'Piece'
                            ); ?>

                            <?= $stock === 1
                                ? 'available'
                                : 'available'; ?>

                        </div>


                        <!-- BUTTON -->

                        <?php if ($stock > 0): ?>

                            <a
                                href="#"
                                class="product-action"
                            >
                                View Product
                            </a>

                        <?php else: ?>

                            <button
                                type="button"
                                class="product-action disabled"
                                disabled
                            >
                                Currently Unavailable
                            </button>

                        <?php endif; ?>


                    </div>

                </article>


            <?php endforeach; ?>


        </section>


    <?php else: ?>


        <!-- =================================================
             EMPTY RESULT
        ================================================== -->

        <section class="products-empty">

            <div class="empty-icon">

                <svg
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >

                    <circle
                        cx="11"
                        cy="11"
                        r="7"
                    ></circle>

                    <line
                        x1="16.65"
                        y1="16.65"
                        x2="21"
                        y2="21"
                    ></line>

                </svg>

            </div>


            <h2>
                No products found
            </h2>


            <p>
                We couldn't find products matching
                your current search or category.
            </p>


            <a
                href="products.php"
                class="empty-reset-button"
            >
                View All Products
            </a>

        </section>


    <?php endif; ?>


</main>


<!-- =========================================================
     GLOBAL FOOTER
========================================================= -->

<?php
require_once __DIR__ . '/footer.php';
?>


</body>

</html>