<?php
ob_start();
?>
<!DOCTYPE html>
<html>

<head>
    <link href="g.css" rel="stylesheet">
</head>

<body>
    <h1>TESAE SA's challenge - Sidiropoulos Savvas - 6/12/2024</h1>
    <h2>List of products</h2>

    <?php
    require_once("./lib.php");
    $productsList = new Products("./products.xml");
    $productsList->print_html_table_with_all_products();
    $productsList->add_product_from_form();
    ?>

    <button type="button" class="collapsible">Add new product</button>
    <div class="content">
        <div class="container">
            <form class="form" action="products.php" method="POST">
                <h1 id="page_header">Add new product:</h1>
                <label for="label_name">NAME:</label>
                <input type="text" id="name" name="label_name" placeholder="Enter product name (required)">
                <label for="label_price">PRICE:</label>
                <input type="number" step="0.1" id="price" name="label_price" placeholder="Enter price">
                <label for="label_quantity">QUANTITY:</label>
                <input type="number" min="0" id="quantity" name="label_quantity" placeholder="Enter quantity">
                <label for="label_category">CATEGORY:</label>
                <input type="text" id="category" name="label_category"
                    placeholder="Enter category followed by subcategory in this format: CATEGORY-&gt;Sub category">
                <label for="label_manufacturer">MANUFACTURER:</label>
                <input type="text" id="manufacturer" name="label_manufacturer" placeholder="Enter manufacturer">
                <label for="label_barcode">BARCODE:</label>
                <input type="text" id="barcode" name="label_barcode" placeholder="Enter barcode">
                <label for="label_weight">WEIGHT:</label>
                <input type="number" step="0.1" id="weight" name="label_weight" placeholder="Enter weight (in kg)">
                <label for="label_instock">IN STOCK:</label>
                <select name="label_instock" id="instock" value="Y">
                    <option value="Y">Ναι</option>
                    <option value="N">Όχι</option>
                </select>
                <label for="label_availability">AVAILABILITY:</label>
                <select name="label_availability" id="availability" value="1">
                    <option value="1">Άμεσα Διαθέσιμο</option>
                    <option value="2">Προσωρινά μη Διαθέσιμο</option>
                </select>
                <input type="submit" name="submit" value="Submit">
            </form>
        </div>
    </div>

    <script>
        var coll = document.getElementsByClassName("collapsible");
        var i;

        for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function () {
                this.classList.toggle("active");
                var content = this.nextElementSibling;
                if (content.style.display === "block") {
                    content.style.display = "none";
                } else {
                    content.style.display = "block";
                }
            });
        }
    </script>

</body>

</html>

<?php
ob_end_flush();
?>