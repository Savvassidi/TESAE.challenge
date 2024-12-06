<?php
class Products
{

    private $xml_file_path = '';

    public function __construct($xml_file_path = '')
    {
        $this->xml_file_path = $xml_file_path;
    }

    /**
     * This function prints an HTML table with all the products as read from the xml file
     * @return void 
     */
    public function print_html_table_with_all_products()
    {
        try {
            $xmldata = simplexml_load_file($this->xml_file_path) or die("Failed to load");
            $xml_data = $xmldata->children();

            echo "<table class='products_table'>";
            echo "<thead>";
            echo "<tr>";

            foreach ($xml_data[1] as $title) {
                foreach ($title as $key => $value) {
                    echo ("<th>" . $key . "</th>");
                }
                break;
            }

            echo "</tr>";
            echo "</thead>";

            foreach ($xml_data->PRODUCTS->PRODUCT as $key => $prod) {
                $this->print_html_of_one_product_line($prod);
            }

            echo "</table>";
        } catch (Exception $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }

    /**
     * This function prints an HTML tr for a given product
     * @param mixed $prod It is the product object as retrieved from the xml file
     * @return void 
     */
    private function print_html_of_one_product_line($prod)
    {
        try {
            echo "<tr>";

            foreach ($prod as $key => $value) {
                if ($key == "BARCODE") {
                    echo "<td class='barcode'>" . htmlspecialchars($value) . "</td>";
                } else {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
            }
            echo "</tr>";
        } catch (Exception $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }

    public function add_product_from_form()
    {
        try {
            if (isset($_POST['submit'])) {
                $xml_file = new DOMDocument("1.0", "UTF-8");

                $xml_file->preserveWhiteSpace = false; // Κρατάει συγκεκριμένο format
                $xml_file->formatOutput = true; // στο xml αρχείο για λόγους ευαναγνωσιμότητας

                $xml_file->load($this->xml_file_path);

                $products_list = $xml_file->getElementsByTagName("PRODUCTS")->item(0);

                $label_name = $_POST["label_name"];

                if (!isset($label_name) || $label_name === "") {
                    echo "<script>alert('Error, missing field: Name');</script>";
                } else {
                    $label_price = $_POST["label_price"];
                    $label_quantity = $_POST["label_quantity"];
                    $label_category = $_POST["label_category"];
                    $label_manufacturer = $_POST["label_manufacturer"];
                    $label_barcode = $_POST["label_barcode"];
                    $label_weight = $_POST["label_weight"];
                    $label_instock = $_POST["label_instock"];
                    $label_availability = $_POST["label_availability"];
                    if ($label_instock === "Y" && ($label_quantity == 0) || $label_quantity === "") {
                        echo "<script>alert('Error, quantity cannot be 0 or empty if the product is in stock');</script>";
                        return;
                    } else {
                        $new_product = $xml_file->createElement("PRODUCT");
                        $name = $xml_file->createElement("NAME");
                        $name->appendChild($xml_file->createCDATASection($label_name));
                        $price = $xml_file->createElement("PRICE", $label_price);
                        $quantity = $xml_file->createElement("QUANTITY", $label_quantity);
                        $category = $xml_file->createElement("CATEGORY", $label_category);
                        $manufacturer = $xml_file->createElement("MANUFACTURER", $label_manufacturer);
                        $barcode = $xml_file->createElement("BARCODE");
                        $barcode->appendChild($xml_file->createCDATASection($label_barcode));
                        $weight = $xml_file->createElement("WEIGHT");
                        $weight->appendChild($xml_file->createCDATASection($label_weight . "kg"));

                        $instock_val = "";
                        switch ($label_instock) {
                            case 'Y':
                                $instock_val = "Ναι";
                                break;
                            case 'N':
                                $instock_val = "Όχι";
                                break;
                        }
                        $instock = $xml_file->createElement("INSTOCK", $label_instock);

                        $avail_val = "";
                        switch ($label_availability) {
                            case '1':
                                $avail_val = "Άμεσα Διαθέσιμο";
                                break;
                            case '2':
                                $avail_val = "Κατόπιν Παραγγελίας";
                                break;
                        }
                        $availability = $xml_file->createElement("AVAILABILITY", $avail_val);

                        $new_product->appendChild($name);
                        $new_product->appendChild($price);
                        $new_product->appendChild($quantity);
                        $new_product->appendChild($category);
                        $new_product->appendChild($manufacturer);
                        $new_product->appendChild($barcode);
                        $new_product->appendChild($weight);
                        $new_product->appendChild($instock);
                        $new_product->appendChild($availability);

                        $products_list->appendChild($new_product);

                        $xml_file->save($this->xml_file_path);

                        header("Refresh:0");
                        exit();
                    }
                }
            }
        } catch (Exception $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
}
