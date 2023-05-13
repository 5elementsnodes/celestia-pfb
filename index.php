<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Celestia - Blockspace Race testnet</title>
    <meta name="description" content="PayForBlob transactions tool for Blockspace Race - Celestia."/>
    <meta name="robots" content="noindex,nofollow">


    <!--Google font-->
    <link href="https://fonts.googleapis.com/css?family=K2D:300,400,500,700,800" rel="stylesheet">

    <!-- Bootstrap CSS / Color Scheme -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/form.css">

    <!--Loader Script-->
    <script>
	function showLoading() {
	  document.getElementById("submitting").style.display = "block";
	}
	</script>

</head>
<body>

<!--Header Section-->
<section class="bg-gradient pt-5 pb-6">
    <div class="container">
        <div class="row">
            <div class="col-12 d-flex flex-row align-items-center justify-content-between">
                
                <div class="heading-brand"></div>
                
            </div>
        </div>
        <div class="row mt-6">
            
            <div class="col-md-8 mx-auto text-center">
                <h1><img src="img/celestia-logo.png" height="90" width="90"/><br/><a href="#">PayForBlob Tx Tool</a></h1>
                <p class="lead mb-5">Creation of UI for allowing users to submit PayForBlob Transactions. It shows you how you can call the API in order to submit a PFB transaction, and how to retrieve the data by block height and namespace.</p>
                
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-10 mx-auto">
                <div class="PayForBlob">
                
                <?php
                switch($_GET['action']){
                    default:
                    echo '<form id="pfb" action="index.php?action=submit" method="post" onsubmit="showLoading()">
                            <input type="text" id="namespace_id" name="namespace_id" placeholder="Namespace ID" minlength="16" required>
                            <input type="text" id="data" name="data" placeholder="Hex-encoded DATA" required>
                            <button type="submit" name="submit" value="Submit Transaction">Submit</button>
                        </form>
                        <br/>
                        <img id="submitting" src="https://www.abyssindetanger.com/wp-content/themes/spontaneous/img/global/loading-gallery.gif" alt="submitting" width="80" height="80" style="display:none; margin: 0 auto;">
                      </div>';
                    break;
                    case "submit":
                        $url = 'https://celestia.5elementsnodes.com/';
                        $fields = array(
                            'namespace_id' => $_POST['namespace_id'],
                            'data' => $_POST['data'],
                            'gas_limit' => 80000,
                            'fee' => 2000
                        );
                        $fields_string = json_encode($fields, JSON_PRETTY_PRINT);
                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, $url."submit_pfb");
                        curl_setopt($curl, CURLOPT_POST, TRUE);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($fields_string)
                        ));
                        $data = curl_exec($curl);
                        $json = json_decode($data);
                        if ($json) {
                          if (isset($json->code)) {
                            echo "Something went wrong!<br />";
                            echo 'The transaction cannot be processed, <a href="/index.php">retry.</a><br /><br />';
                          } else {
                            $height = $json->height;
                            $txhash = $json->txhash;
                            echo "<b>PayForBlob Submitted!</b><br /><br/>";
                            echo 'height: <a href="https://testnet.mintscan.io/celestia-incentivized-testnet/blocks/' . $height . '" target="_blank">' . $height . '</a><br />';
                            echo 'txhash: <a href="https://testnet.mintscan.io/celestia-incentivized-testnet/txs/' . $txhash . '" target="_blank">' . $txhash . '</a><br /><br /><br/>';
                            $file_name = $txhash . '_5ElementsNodes.json';
                            echo '<button onclick="downloadJSON()">Download JSON</button>';
							echo '<script>
							        function downloadJSON() {
							          var data = JSON.stringify(' . json_encode($json) . ');
							          var blob = new Blob([data], {type: "application/json"});
							          var url = URL.createObjectURL(blob);
							          var a = document.createElement("a");
							          a.href = url;
							          a.download = "' . $file_name . '";
							          document.body.appendChild(a);
							          a.click();
							          document.body.removeChild(a);
							          URL.revokeObjectURL(url);
							        }
							      </script>';
                          }
                        }
                        curl_close($curl);
                    break;
                }
                ?>
                </div>
            </div>
        </div>
    </div>
</section>


<!--footer-->
<footer class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <img src="img/home-hero.svg" height="90" width="90"/>
            </div>
        </div>
        <div class="row my-2">
            <div class="col-md-4 mx-auto text-muted text-center small-xl">
                &copy; 2023 - 5 Elements Nodes x Celestia<br/> BlockSpace Race - All Rights Reserved
            </div>
        </div>
    </div>
</footer>


</body>
</html>
