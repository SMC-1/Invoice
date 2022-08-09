<?php
/*
Lab6
Santiago Mesa
Sec 304
*/
    session_start();
    include("ACMEarray.php");
    //set gobla variable to store information
    $_SESSION['array'] = $inventory;
    //set global variables if not set
    if(!isset($_SESSION['cart'])){
		$_SESSION["cart"] = array(); 
	}
    if(!isset($_SESSION['discount'])){
		$_SESSION['discount'] = array(); 
	}
    if(!isset($_SESSION['retail'])){
		$_SESSION['retail'] = array(); 
	}
    if(!isset($_SESSION['total'])){
		$_SESSION['total'] = array(); 
	}
    //when post is requested, add the information from the form to the according session array
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $discount = $_POST["discount"];
        $item = $_POST["item"];
        $retail = $_POST["retail"];
        //if discount is too big display error
        if($discount>35){
            echo "<script> alert('Error: $discount% is too much') </script>";
        }
        //include information to the array when discount is ok
        else{
            array_push($_SESSION["cart"], $item);
            array_push($_SESSION["discount"], $discount);
            array_push($_SESSION["retail"], $retail);
            array_push($_SESSION["total"], $total);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Santiago Mesa">
    <link rel="stylesheet" href="ACMEpurchase.css">
    <title>ACME Customer Care Portal</title>    
</head>
<body>
    <header>
        <h1>ACME Corporation</h1>
        <div class="tagline">yes we deliver!</div>
    </header>

    <main>
        <h2>Customer Invoice</h2>

        <table class="invoice">
            <tr>
                <th class="invoiceheader">Item</th>
                <th class="invoiceheader">Retail Cost</th>
                <th class="invoiceheader">Discount</th>
                <th class="invoiceheader">Total</th>
            </tr>

            <!-- HERE IS WHERE INVOICE ITEMS ARE LISTED -->          
                
            <?php
            //control variables
            $i =0; 
            $total_final = 0;
            //loop each element of the cart session array after is added
            foreach ($_SESSION["cart"] as $element){
                //fomrula to calculate the final price when a discount is entered
                $final_price = $_SESSION['retail'][$i] - ($_SESSION['retail'][$i] * ($_SESSION['discount'][$i] / 100));
                    ?>
                     <!-- Listt items according to their position in the array and control variables -->
                    </tr>
                        <td class='centered'> <?php echo $element; ?> </td>
                        <td class='centered'> $<?php echo ($_SESSION['retail'][$i]); ?> </td>
                        <td class='centered'> <?php echo ($_SESSION['discount'][$i]); ?>% </td>
                        <td class='centered'> $<?php echo $final_price; ?> </td>
                    </tr>
                    <?php
                    $i++;
                    //update final price to the invoixe
                    $total_final = $total_final + $final_price;
            }
            ?>
            
            <!-- THE FOLLOWING LINE IS ALWAYS PRESENT -->

            <tr class="totalline">
                <td colspan="3">Invoice total</td>
                 <!-- display total invoice after each element is added-->
                <td class="centered"> $<?php echo $total_final; ?> </td>
            </tr>
        </table>

        <script>
        //function to alert, redirect and refresh web after purchase is made
        function purchase(){
            alert('Thanks for your purchase. Total is $<?php echo $total_final; ?> ');
            window.location.href = 'confirm.php';
        }
        </script>
         <!-- call function when a purchase is done and only shows button when there are active products -->
        <div id="purchase">
            <button onclick="purchase()" id="submitorder" <?php if($_SESSION['cart'] == null) {?> disabled <?php } ?> >Purchase</button>
        </div>
        

        <hr>
        <script>
            //function to populate the list dynamically according to the given array
            function populate(){
                //decode the array
                let array = <?php echo json_encode($inventory); ?>;
                let newselection = document.getElementById('newitem');
                //get index of the item according to the array position
                let option = newselection.options[newselection.selectedIndex].text;
                document.getElementById('item').setAttribute('value', option);
                //set values to the list items according to corresponding values
                for (let i = 0; i < array.length; i++){
                    if (array[i][0] === option){
                        let retail = document.getElementById('retail').value = array[i][2];
                    }
                }
            }
        </script>  
        <form action="ACMEpurchases.php" method="post" name="additem">
            <fieldset class="additem">
          
                <legend>Add Item to Order</legend>
                <select id="newitem" onchange="populate()" >
                 <!-- make a list of options using PHP and the 
                    provided array of musical instruments. -->
                    <option> </option>
                    <?php 
                        //print the list and set values
                        foreach($inventory as $value){
                            echo "<option class='selection' value=$value[0]> $value[0] </option>"; 
                        }
                    ?>    
                </select>

                <div class="itemdetails">
                    <label for="item">Item:</label>
                    <input type="text" name="item" id="item" disabled>
                </div>
                
                <div class="itemdetails">    
                    <label for="retail">Price: $</label>
                    <input type="text" name="retail" id="retail" disabled>
                    <label for="discount">Discount:</label>
                     <!-- display total after discount dinamycally -->
                    <input type="text" name="discount" id="discount" value=0 onchange="calculate('retail', 'discount', 'total')">%</input>
                    <label for="total">Total: $</label>
                    <input type="text" name="total" id="total" disabled>
                </div>

                <script>
                //function to enable values and be able to be visible to user after adding
                function enable(item, retail, total){
                    document.getElementById(item).disabled = false;
                    document.getElementById(retail).disabled = false;
                    document.getElementById(total).disabled = false;
                }
                //function to calculate total price after discooutn and displlay it dinamycally
                function calculate(retail, discount, total){
                    //calculate automatically discount and display it
                    let price = document.getElementById(retail).value;
                    let percent = document.getElementById(discount).value;
                    let final = price - (price * (percent/100));
                    document.getElementById(total).value = final;
                }
                </script>
                 <!-- call fucntion enable after item is added -->
                <div class="purchase">
                    <button type="submit" onclick="enable('item', 'retail', 'total')">Add to Invoice</button>
                </div>

                <p class="centered">
                    Attention, all discounts will be verified by our software.
                </p>

            </fieldset>
        </form>
        <footer>ACME Coporation for all that you can scheme up!</footer>

    </main>
</body>
</html>